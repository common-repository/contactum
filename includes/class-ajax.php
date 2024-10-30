<?php
namespace Contactum;

use Contactum\Tools;
use Contactum\Recaptcha;
use Contactum\EntryManager;
use Contactum\Notification as Contactum_Notification;

class Ajax {

    public function __construct() {
        add_action( 'wp_ajax_save_contactum_form', [ $this, 'save_contactum_form' ] );
        // import
        add_action( 'wp_ajax_contactum_import_form', [ $this, 'import_form' ] );
        //upload file
        add_action( 'wp_ajax_upload_file', [ $this, 'upload_file' ] );
        add_action( 'wp_ajax_nopriv_upload_file', [ $this, 'upload_file' ] );

        //delete file
        add_action( 'wp_ajax_file_del', [ $this, 'delete_file' ] );
        add_action( 'wp_ajax_nopriv_file_del', [ $this, 'delete_file' ] );

        // frontend submit
        add_action( 'wp_ajax_contactum_frontend_submit', [ $this, 'contactum_frontend_submit' ] );
        add_action( 'wp_ajax_nopriv_contactum_frontend_submit', [ $this, 'contactum_frontend_submit' ] );
    }

    public function save_contactum_form() {
        $post_data = wp_unslash( $_POST );

        if ( !wp_verify_nonce( $post_data['contactum_form_builder_nonce'], 'contactum-form-builder-nonce' ) ) {
            wp_send_json_error( __( 'Unauthorized operation', 'contactum' ) );
        }


        if ( isset( $post_data['form_data'] ) ) {
            parse_str( $post_data['form_data'] ,  $form_data );
        }

        if ( empty( $form_data['contactum_form_id'] ) ) {
            wp_send_json_error( __( 'Invalid form id', 'contactum' ) );
        }

        $form_fields   = isset( $post_data['form_fields'] ) ? $post_data['form_fields'] : '';
        $form_fields   = json_decode( $form_fields, true );

        $notifications   = isset( $post_data['notifications'] ) ? $post_data['notifications'] : '';
        $notifications   = json_decode( $notifications, true );


        if ( isset( $post_data['settings'] ) ) {
            $form_settings = (array) json_decode( $post_data['settings'] );
        }

        if ( isset( $post_data['integrations'] ) ) {
            $integrations = (array) json_decode( $post_data['integrations'] );
        }

        $data = [
            'form_id'       => absint( $form_data['contactum_form_id'] ),
            'post_title'    => $form_data['post_title'],
            'form_fields'   => $form_fields,
            'notifications' => $notifications,
            'form_settings' => $form_settings,
            'integrations'  => $integrations,
        ];

        $form_fields = contactum()->forms->save( $data );

        wp_send_json_success( [
            'form_fields'   => $form_fields,
            'notifications' => $notifications,
            'form_settings' => $form_settings,
            'integrations'  => $integrations,
        ] );
    }

    public function upload_file() {
        $nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

        if ( !wp_verify_nonce( $nonce, 'contactum-upload-nonce' ) ) {
            die( 'error' );
        }

        $post_data = wp_unslash( $_POST );
        $form_id = isset( $post_data['form_id'] ) ? intval( sanitize_text_field( wp_unslash( $post_data['form_id'] ) ) ) : false;

        if ( !$form_id ) {
            die( 'error' );
        }

        $file = isset( $_FILES['file'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_FILES['file'] ) ) : [];

        $upload = array(
            'name'     => isset( $file['name'] ) ? $file['name'] : '',
            'type'     => isset( $file['type'] ) ? $file['type'] : '',
            'tmp_name' => isset( $file['tmp_name'] ) ? $file['tmp_name'] : '',
            'error'    => isset( $file['error'] ) ? $file['error'] : '',
            'size'     => isset( $file['size'] ) ? $file['size'] : '',
        );

        header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );

        $attach = $this->handle_upload( $upload );

        if ( $attach['success'] ) {
            $response         = [ 'success' => true ];
            $response['html'] = $this->attach_html( $attach['attach_id'] );
            echo $response['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            echo 'error'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        exit;
    }

    public function handle_upload( $upload_data ) {
        $uploaded_file = wp_handle_upload( $upload_data, ['test_form' => false] );

        // If the wp_handle_upload call returned a local path for the image
        if ( isset( $uploaded_file['file'] ) ) {
            $file_loc  = $uploaded_file['file'];
            $file_name = basename( $upload_data['name'] );
            $file_type = wp_check_filetype( $file_name );

            $attachment = [
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ];

            $attach_id   = wp_insert_attachment( $attachment, $file_loc );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );

            wp_update_attachment_metadata( $attach_id, $attach_data );
            return ['success' => true, 'attach_id' => $attach_id];
        }

        return ['success' => false, 'error' => $uploaded_file['error']];
    }

    public static function attach_html( $attach_id, $type = NULL ) {
        if ( ! $type ) {
            $type = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'image';
        }

        $attachment = get_post( $attach_id );

        if ( !$attachment ) {
            return;
        }

        if ( wp_attachment_is_image( $attach_id ) ) {
            $image = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
            $image = $image[0];
        } else {
            $image = wp_mime_type_icon( $attach_id );
        }

        // $html = '<li class="ui-state-default image-wrap thumbnail">';
        $html = '<li class="image-wrap thumbnail">';
        $html .= sprintf( '<div class="attachment-name"><img src="%s" alt="%s" /></div>', $image, esc_attr( $attachment->post_title ) );
        $html .= sprintf( '<input type="hidden" name="contactum_files[%s][]" value="%d">', $type, $attach_id );
        $html .= '<div class="caption">';
        $html .= sprintf( '<a href="#" class="attachment-delete" data-attach_id="%d"> <img src="%s" /> </a>', $attach_id,
            CONTACTUM_ASSETS . '/images/del-img.png');
        // $html .= sprintf( '<span class="drag-file"> <img src="%s" /> </span>', CONTACTUM_ASSETS . '/images/move-img.png');
        $html .= '</div>';
        $html .= '</li>';

        return $html;
    }

    public function delete_file() {
        check_ajax_referer( 'contactum_nonce', 'nonce' );

        $post_data = wp_unslash( $_POST );
        $attach_id  = isset( $post_data['attach_id'] ) ? intval( $post_data['attach_id'] ) : 0;
        $attachment = get_post( $attach_id );

        //post author or editor role
        if ( get_current_user_id() == $attachment->post_author || current_user_can( 'delete_private_pages' ) ) {
            wp_delete_attachment( $attach_id, true );
        }

        echo 'success';
        exit;
    }


    public function import_form() {
        // check_ajax_referer( 'contactum' );
        $the_file = isset( $_FILES['importFile'] ) ? $_FILES['importFile'] : false; // phpcs:ignore

        if ( !$the_file ) {
            wp_send_json_error( __( 'No file found to import.', 'contactum' ) );
        }

        $file_ext  = pathinfo( $the_file['name'], PATHINFO_EXTENSION );

        if ( ( $file_ext == 'json' ) && ( $the_file['size'] < 500000 ) ) {
            $status = Tools::import_json_file( $the_file['tmp_name'] );

            if ( $status ) {
                wp_send_json_success( __( 'The forms have been imported successfully!', 'contactum' ) );
            } else {
                wp_send_json_error( __( 'Something went wrong importing the file.', 'contactum' ) );
            }
        } else {
            wp_send_json_error( __( 'Invalid file or file size too big.', 'contactum' ) );
        }
    }

    public function contactum_frontend_submit() {
        check_ajax_referer('contactum_form_frontend');

        $post_data     = wp_unslash( $_POST );
        $form_id       = isset( $post_data['form_id'] ) ? intval( $post_data['form_id'] ) : 0;
        $page_id       = isset( $post_data['page_id'] ) ? intval( $post_data['page_id'] ) : 0;
        $form          = contactum()->forms->get( $form_id );
        $form_settings = $form->getSettings();
        $form_fields   = $form->getFields();
        $entry_fields  = $form->prepare_entries( $post_data );

        if ( !$form_fields ) {
            wp_send_json( [
                'success'     => false,
                'error'       => __( 'No form field was found.', 'contactum' ),
            ] );
        }

        $entry_id = EntryManager::create( [ 'form_id' => $form_id ], $entry_fields );

        if ( is_wp_error( $entry_id ) ) {
            wp_send_json( [
                'success' => false,
                'error'   => $entry_id->get_error_message(),
            ] );
        }

        // redirect URL
        $show_message = false;
        $redirect_to  = false;

        if ( $form_settings['redirect_to'] == 'page' ) {
            $redirect_to = get_permalink( $form_settings['page_id'] );
        } elseif ( $form_settings['redirect_to'] == 'url' ) {
            $redirect_to = $form_settings['url'];
        } elseif ( $form_settings['redirect_to'] == 'same' ) {
            $show_message = true;
        } else {
            $show_message = true;
        }

        // Fire a hook for integration
        do_action( 'contactum_entry_submission', $entry_id, $form_id, $page_id, $form_settings );

        $field_search = $field_replace = [];

        foreach ( $form_fields as $r_field ) {
            $field_search[] = '{' . $r_field['name'] . '}';

            if ( $r_field['template'] == 'name_field' ) {
                $field_replace[] = implode( ' ', explode(CONTACTUM_SEPARATOR, $entry_fields[ $r_field['name'] ] ) );
            } else if ( $r_field['template'] == 'address_field' ) {
                $field_replace[] = implode( ',', $entry_fields[ $r_field['name'] ] );
            } else {
                $field_replace[] = isset( $entry_fields[ $r_field['name'] ] ) ? $entry_fields[ $r_field['name'] ] : '';
            }
        }

        $message = str_replace( $field_search, $field_replace, $form_settings['message'] );

        // send the response
        $response = apply_filters( 'contactum_entry_submission_response', [
            'success'      => true,
            'redirect_to'  => $redirect_to,
            'show_message' => $show_message,
            'message'      => $message,
            'data'         => $post_data,
            'form_id'      => $form_id,
            'entry_id'     => $entry_id,
        ] );

        $notification = new Contactum_Notification([
            'form_id'  => $form_id,
            'page_id'  => $page_id,
            'entry_id' => $entry_id,
        ]);

        $notification->sendNotifications();
        
        // $notification->sendNotification();

        wp_send_json( $response );
    }
}

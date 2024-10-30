<?php
namespace Contactum;
use WP_Query;
class Tools {

    public static function import_json_file( $file ) {
        $encode_data = file_get_contents( $file );
        $options     = json_decode( $encode_data, true );

        foreach ( $options as $key => $value ) {
            $generate_post = [
                'post_title'     => $value['post_data']['post_title'],
                'post_status'    => $value['post_data']['post_status'],
                'post_type'      => $value['post_data']['post_type'],
                'ping_status'    => $value['post_data']['ping_status'],
                'comment_status' => $value['post_data']['comment_status'],
            ];

            $post_id = wp_insert_post( $generate_post, true );

            if ( $post_id && !is_wp_error( $post_id ) ) {
                foreach ( $value['meta_data']['fields'] as $order => $field ) {
                    contactum_insert_form_field( $post_id, $field, false, $order );
                }

                update_post_meta( $post_id, 'form_settings', $value['meta_data']['settings'] );
                update_post_meta( $post_id, 'notifications', $value['meta_data']['notifications'] );
                
            }
        }

        return true;
    }

    public static function export_to_json( $post_ids = [] ) {
        $formatted_data = [];
        $ids            = [];
        $blogname       = strtolower( str_replace( ' ', '-', get_option( 'blogname' ) ) );
        $json_name      = $blogname . '-contactum-' . time(); // Namming the filename will be generated.

        if ( !empty( $post_ids ) ) {
            foreach ( $post_ids as $key => $value ) {
                array_push( $ids, $value );
            }
        }

        $args = [
            'post_status' => 'publish',
            'post_type'   => 'contactum_forms',
            'post__in'    => ( !empty( $ids ) ) ? $ids : '',
        ];

        $query = new WP_Query( $args );

        foreach ( $query->posts as $post ) {
            $postdata = get_object_vars( $post );
            unset( $postdata['ID'] );

            $form = contactum()->forms->get( $post );

            $data = [
                'post_data' => $postdata,
                'meta_data' => [
                    'fields'        => $form->getFields(),
                    'settings'      => $form->getSettings(),
                    'notifications' => $form->getNotifications(),
                ],
            ];

            array_push( $formatted_data, $data );
        }


        $json_file = json_encode( $formatted_data ); // Encode data into json data
        error_reporting( 0 );

        if ( ob_get_contents() ) {
            ob_clean();
        }

        header( 'Content-Type: text/json; charset=' . get_option( 'blog_charset' ) );
        header( "Content-Disposition: attachment; filename=$json_name.json" );

        echo $json_file; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        exit();
    }
}

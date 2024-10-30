<?php
namespace Contactum;

class Frontend {

    public function __construct() {
        add_shortcode( 'contactum', [ $this, 'render' ] );
    }

    public function show_error( $message, $type = 'info' ) {
        return sprintf( '<div class="contactum-%s">%s</div>', $type, $message );
    }

    public function render( $atts ) {
        extract( shortcode_atts( [ 'id' => 0 ], $atts ) );

        contactum()->assets->register_frontend();
        contactum()->assets->enqueue_frontend();

        ob_start();
        $form = contactum()->forms->get( $id );

        if ( !$form->id ) {
            return $this->show_error( __( 'The form couldn\'t be found.', 'contactum' ) );
        }

        $isOpen = $form->isSubmissionOpen();

        if ( is_wp_error( $isOpen ) ) {
            return $this->show_error( $isOpen->get_error_message() );
        }

        $this->render_form( $form, $atts );

        return ob_get_clean();
    }

    private function render_form( $form, $atts ) {
        $form_fields = $form->getFields();
        $form_settings = $form->getSettings();

        if( isset( $atts['modal'] ) && 'true' == $atts['modal'] ) {
            wp_enqueue_script( 'contactum-modal' );
            wp_enqueue_style( 'contactum-modal' );

            $modal_class = 'modal';
            $modal_id    = 'modal-form';
            $modal_style = 'style="display:none"';
        } else {
            $modal_class = $modal_id = $modal_style = '';
        }

        $script = "if ( typeof contactum_conditional_items === 'undefined' ) {
                window.contactum_conditional_items = [];
            }
            if ( typeof contactum_plupload_items === 'undefined' ) {
                window.contactum_plupload_items = [];
            }
            if ( typeof contactum_map_items === 'undefined' ) {
                window.contactum_map_items = [];
            }
        ";

        wp_add_inline_script( 'contactum-frontend', $script );

        ?>
        <form class="contactum-form-add contactum-style <?php echo esc_attr( $modal_class ); ?> <?php echo esc_attr( $form->id ); ?>"
         action="" method="post" <?php echo esc_attr( $modal_style ); ?> id="<?php echo
        esc_attr($modal_id); ?>">
            <ul class="contactum-form form-label-<?php echo esc_attr( $form_settings['label_position'] ); ?>">
               <?php
                    contactum()->fields->render_fields( $form_fields, $form->id, $atts );
                    if( contactum()->fields->hassubmit_fields( $form_fields, $form->id, $atts ) ) {

                    } else {
                        $this->submit_button( $form->id );
                    }
               ?>
            </ul>
        </form>

        <?php
            if( isset( $atts['modal'] ) && 'true' == $atts['modal'] ) {
                printf( '<p> <a class="modal-btn" href="#modal-form" rel="modal:open"> Open Modal </a> </p>');
            }
    }

    private function submit_button( $form_id ) { ?>
        <li class="submit submit_wrapper contactum-submit">
            <!-- <div class="contactum-label"> &nbsp; </div> -->
            <?php esc_attr( wp_nonce_field( 'contactum_form_frontend' ) ); ?>
            <input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
            <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="action" value="contactum_frontend_submit">
            <input type="submit" class="btn btn-submit contactum_submit_btn  contactum_submit_<?php echo esc_attr( $form_id ); ?>"
            name="submit" value="Submit" />
        </li>
        <?php
    }
}

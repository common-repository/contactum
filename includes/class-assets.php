<?php
namespace Contactum;

class Assets {

    private $settings = [];
    private $postdata;

    function __construct() {
        $id   = isset( $_GET['id'] ) ? intval( wp_unslash( $_GET['id'] ) ) : '';
        $this->postdata = get_post( $id );

        add_action( 'admin_enqueue_scripts', array( $this, 'register_builder_backend' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 10 );

        if ( !empty( $this->postdata->ID ) ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_builder_scripts' ] );
        }

        add_action( 'in_admin_header', [ $this, 'remove_admin_notices' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'register_frontend' ] );
    }

    public function enqueue_admin_scripts() {
        wp_register_script( 'contactum-tools', CONTACTUM_ASSETS . '/js/tools.js', [ 'jquery'], CONTACTUM_VERSION, true );
        wp_register_script( 'contactum-chart', CONTACTUM_ASSETS . '/js/chart.js', [ 'jquery'], CONTACTUM_VERSION, true );
        wp_enqueue_script( 'contactum-tools' );
        wp_enqueue_script( 'contactum-chart' );
        wp_localize_script( 'contactum-tools', 'contactum_tools', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'contactum-nonce' ),
        ]);
    }

    public function remove_admin_notices() {
        remove_all_actions( 'network_admin_notices' );
        remove_all_actions( 'user_admin_notices' );
        remove_all_actions( 'admin_notices' );
        remove_all_actions( 'all_admin_notices' );
    }

    public function get_frontend_localized_scripts() {
        return apply_filters( 'contactum_frontend_localize_script', [
            'confirmMsg' => __( 'Are you sure?', 'contactum' ),
            'delete_it'  => __( 'Yes, delete it', 'contactum' ),
            'cancel_it'  => __( 'No, cancel it', 'contactum' ),
            'nonce'      => wp_create_nonce( 'contactum_nonce' ),
            'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'plupload'   => [
                'url'              => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'contactum-upload-nonce' ),
                'flash_swf_url'    => includes_url( 'js/plupload/plupload.flash.swf' ),
                'filters'          => [
                    [
                        'title'      => __( 'Allowed Files', 'contactum' ),
                        'extensions' => '*',
                    ],
                ],
                'multipart'        => true,
                'urlstream_upload' => true,
                'warning'          => __( 'Maximum number of files reached!', 'contactum' ),
                'size_error'       => __( 'The file you have uploaded exceeds the file size limit. Please try again.', 'contactum' ),
                'type_error'       => __( 'You have uploaded an incorrect file type. Please try again.', 'contactum' ),
            ],
            'error_message' => __( 'Please fix the errors to proceed', 'contactum' ),
        ] );
    }


    public function get_admin_localized_scripts() {
        $form               = contactum()->forms->get( $this->postdata->ID );
        $contactum_settings = contactum_get_settings();

        return apply_filters( 'contactum_admin_localize_script', [
            'i18n'    => $this->i18n(),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'contactum-form-builder-nonce' ),
            'rest'    => array(
                'root'    => esc_url_raw( get_rest_url() ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
                'version' => 'contactum/v1',
            ),
            'field_settings' => contactum()->fields->get_js_settings(),
            'panel_sections' => contactum()->fields->get_field_groups(),
            'form_fields'    => $form->getFields(),
            // 'integration'    => [],
            'settings'       => $form->getSettings(),
            'notifications'  => $form->getNotifications(),
            // 'integrations'   => contactum()->integrations->get_integration_js_settings(),
            'integrations'   => $form->get_integrations(),
            'post'                            => $this->postdata,
            'defaultNotification'             => contactum_get_default_form_notification(),
            'preview_url'                     => esc_url( add_query_arg('new_window', 1, contactum_get_form_preview_url( $form->id  ) ) ),
            'contactum_cond_supported_fields' => array( 'radio_field', 'checkbox_field', 'dropdown_field' ),
            'smart_tags'                      => contactum()->smarttags->getMergeTags(),
            'contactum_settings'              => $contactum_settings,
            'countries' => contactum_get_countries(),
        ]);
    }

        /**
     * i18n translatable strings
     *
     * @since 2.5
     *
     * @return array
     */
    private function i18n() {
        return apply_filters( 'contactum_form_builder_i18n', [
            'advanced_options'      => __( 'Advanced Options', 'contactum' ),
            'quiz_options'          => __( 'Quiz Options', 'contactum' ),
            'delete_field_warn_msg' => __( 'Are you sure you want to delete this field?', 'contactum' ),
            'yes_delete_it'         => __( 'Yes, delete it', 'contactum' ),
            'no_cancel_it'          => __( 'No, cancel it', 'contactum' ),
            'ok'                    => __( 'OK', 'contactum' ),
            'cancel'                => __( 'Cancel', 'contactum' ),
            'close'                 => __( 'Close', 'contactum' ),
            'disable'               => __( 'Disable', 'contactum' ),
            'last_choice_warn_msg'  => __( 'This field must contain at least one choice', 'contactum' ),
            'option'                => __( 'Option', 'contactum' ),
            'row'                   => __( 'Row', 'contactum' ),
            'column'                => __( 'Column', 'contactum' ),
            'last_column_warn_msg'  => __( 'This field must contain at least one column', 'contactum' ),
            'is_a_pro_feature'      => __( 'is available in Pro version', 'contactum' ),
            'pro_feature_msg'       => __( 'Please upgrade to the Pro version to unlock all these awesome features', 'contactum' ),
            'upgrade_to_pro'        => __( 'Get the Pro version', 'contactum' ),
            'select'                => __( 'Select', 'contactum' ),
            'saved_form_data'       => __( 'Saved form data', 'contactum' ),
            'unsaved_changes'       => __( 'You have unsaved changes.', 'contactum' ),
            'areYouSureToLeave'     => __( 'Are you sure to leave this page?', 'contactum' ),
            'copy_shortcode'        => __( 'Click to copy shortcode', 'contactum' ),

            'selectAnImage'         => __( 'Select an image', 'contactum' ),
            'pleaseSelectAnImage'   => __( 'Please select an image', 'contactum' ),
            'uploadAnImage'         => __( 'Upload an image', 'contactum' ),

            'shareYourForm'         => __( 'Share Your Form', 'contactum' ),
            'shareYourFormDesc'     => __( 'Sharing your form enables <strong>anyone</strong> to view and submit the form without inserting the shortcode to a page.', 'contactum' ),
            'shareYourFormText'     => __( 'Anyone with this URL will be able to view and submit this form.', 'contactum' ),
            'areYouSure'            => __( 'Are you sure?', 'contactum' ),
            'selectNotification'    => __( 'You must select a notification', 'contactum' ),
            'areYouSureDesc'        => __( 'Anyone with existing URL won\'t be able to view and submit the form anymore.', 'contactum' ),
            'disableSharing'        => __( 'Disable Sharing', 'contactum' ),
        ] );
    }

    public function register_builder_backend() {
        $screen = get_current_screen();

        // if ( $screen->base != 'toplevel_page_contactum' ) {
        //     return ;
        // }

        $this->register_styles( $this->get_admin_styles() );
        $this->register_scripts( $this->get_admin_scripts() );

        wp_localize_script( 'contactum-addon', 'contactumAddon', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'contactum-form-builder-nonce' ),
        ]);
    }

    public function enqueue_builder_scripts() {
        $screen = get_current_screen();
        if ( $screen->base != 'toplevel_page_contactum' ) {
            return ;
        }

        $this->enqueue_styles( $this->get_admin_styles() );
        $this->enqueue_scripts( $this->get_admin_scripts() );

        $localize_script = $this->get_admin_localized_scripts();
        wp_localize_script( 'contactum-admin', 'contactum', $localize_script );
    }

    public function register_frontend() {
        $this->register_styles( $this->get_frontend_styles() );
        $this->register_scripts( $this->get_frontend_scripts() );
    }

    public function enqueue_frontend() {
        $this->enqueue_styles( $this->get_frontend_styles() );
        $this->enqueue_scripts( $this->get_frontend_scripts() );

        $localize_script = $this->get_frontend_localized_scripts();

        wp_localize_script( 'contactum-frontend', 'frontend', $localize_script );

        wp_localize_script( 'contactum-frontend', 'error_str_obj', [
            'required'   => __( 'is required', 'contactum' ),
            'mismatch'   => __( 'does not match', 'contactum' ),
            'validation' => __( 'is not valid', 'contactum' ),
            'duplicate'  => __( 'requires a unique entry and this value has already been used', 'contactum' ),
        ] );
    }

    public function get_admin_scripts() {

        $form_builder_js_deps = apply_filters( 'contactum_builder_js_deps', [
            'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable',
            'jquery-ui-resizable', 'underscore', 'contactum-clipboard', 'contactum-sweetalert',
            'contactum-jquery-tooltip', 'contactum-selectize'
        ]);

        $scripts = [
            'contactum-admin' => [
                'src'       => CONTACTUM_ASSETS . '/js/admin.js',
                'deps'      => $form_builder_js_deps,
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/admin.js' ),
                'in_footer' => true
            ],
            // 'contactum-addon' => [
            //     'src'       => CONTACTUM_ASSETS . '/js/addon.js',
            //     'deps'      => [ 'jquery', 'underscore', ],
            //     'version'   => filemtime( CONTACTUM_PATH . '/assets/js/addon.js' ),
            //     'in_footer' => true
            // ],
            'contactum-jquery-scrollto' => [
                'src'       => CONTACTUM_ASSETS . '/js/jquery.scrollTo.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/jquery.scrollTo.js' ),
                'in_footer' => true
            ],
            'contactum-selectize' => [
                'src' =>  CONTACTUM_ASSETS . '/js/selectize.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/selectize.min.js' ),
                'in_footer' => true
            ],
            'contactum-jquery-tooltip' => [
                'src'       => CONTACTUM_ASSETS . '/js/tooltip.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/tooltip.js' ),
                'in_footer' => true
            ],
            'contactum-clipboard' => [
                'src'       => CONTACTUM_ASSETS . '/js/clipboard.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/clipboard.min.js' ),
                'in_footer' => true
            ],
            'contactum-sweetalert' => [
                'src'       => CONTACTUM_ASSETS . '/js/sweetalert2.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/sweetalert2.min.js' ),
                'in_footer' => true
            ],
            'contactum-chart' => [
                'src'       => CONTACTUM_ASSETS . '/js/chart.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/chart.js' ),
                'in_footer' => true
            ],
        ];

        return apply_filters( 'contactum_admin_scripts', $scripts );
    }

    public function get_admin_styles() {
        $styles = [
            'contactum-font-awesome' => [
                'src'  => CONTACTUM_ASSETS . '/css/font-awesome/css/font-awesome.min.css',
            ],
            'contactum-sweetalert2' => [
                'src' =>  CONTACTUM_ASSETS . '/css/sweetalert2.min.css'
            ],
            'contactum-selectize' => [
                'src' =>  CONTACTUM_ASSETS . '/css/selectize.css'
            ],
            'contactum-jquery-tooltip' => [
                'src' =>  CONTACTUM_ASSETS . '/css/tooltip.css'
            ],
            'contactum-admin' => [
                'src' =>  CONTACTUM_ASSETS . '/css/admin.css'
            ],
            'contactum-star' => [
                'src' =>  CONTACTUM_ASSETS . '/css/star.css'
            ]
        ];

        return apply_filters( 'contactum_admin_styles', $styles );
    }

    public function get_frontend_styles() {

        $styles = [
            'contactum-frontend' => [
                'src' =>  CONTACTUM_ASSETS . '/css/frontend.css'
            ],
            'jquery-ui' => [
                'src'  => CONTACTUM_ASSETS . '/css/jquery-ui-1.9.1.custom.css',
            ],
            'contactum-sweetalert2' => [
                'src' =>  CONTACTUM_ASSETS . '/css/sweetalert2.min.css'
            ],
            'contactum-choices' => [
                'src' =>  CONTACTUM_ASSETS . '/css/choices.min.css'
            ],
            'contactum-modal' => [
                'src' =>  CONTACTUM_ASSETS . '/css/jquery.modal.min.css'
            ],
            'contactum-flatpickr' => [
                'src' =>  CONTACTUM_ASSETS . '/css/flatpickr.css'
            ],
            'contactum-star' => [
                'src' =>  CONTACTUM_ASSETS . '/css/star.css'
            ]
        ];

        return apply_filters( 'contactum_frontend_styles', $styles );
    }

    public function get_frontend_scripts() {

        $scripts = [
            'contactum-frontend' => [
                'src'       => CONTACTUM_ASSETS . '/js/frontend.js',
                'deps'      => [ 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider', 'contactum-choices' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/frontend.js' ),
                'in_footer' => true
            ],
            'contactum-jquery-ui-timepicker' => [
                'src'       => CONTACTUM_ASSETS . '/js/jquery-ui-timepicker-addon.js',
                'deps'      => [ 'jquery-ui-datepicker' ],
                'in_footer' => true,
            ],
            'contactum-sweetalert' => [
                'src'       => CONTACTUM_ASSETS . '/js/sweetalert2.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/sweetalert2.min.js' ),
                'in_footer' => true
            ],
            'contactum-choices' => [
                'src'       => CONTACTUM_ASSETS . '/js/choices.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/choices.min.js' ),
                'in_footer' => true
            ],
            'contactum-upload' => [
                'src'       => CONTACTUM_ASSETS . '/js/upload.js',
                'deps'      => [ 'jquery', 'plupload-handlers', 'jquery-ui-sortable' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/upload.js' ),
                'in_footer' => true
            ],
            'contactum-conditional' => [
                'src'       => CONTACTUM_ASSETS . '/js/conditional.js',
                'deps'      => [ 'jquery' ],
                'in_footer' => true
            ],
            'contactum-modal' => [
                'src'       => CONTACTUM_ASSETS . '/js/jquery.modal.min.js',
                'deps'      => [ 'jquery' ],
                'in_footer' => true
            ],
            'contactum-flatpickr' => [
                'src'       => CONTACTUM_ASSETS . '/js/flatpickr.js',
                'deps'      => [ 'jquery', 'contactum-frontend' ],
                'in_footer' => true
            ],
            'contactum-mask' => [
                'src'       => CONTACTUM_ASSETS . '/js/jquery.mask.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => filemtime( CONTACTUM_PATH . '/assets/js/jquery.mask.min.js' ),
                'in_footer' => true
            ],
        ];

        return apply_filters( 'contactum_frontend_scripts', $scripts );
    }


    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : CONTACTUM_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, CONTACTUM_VERSION );
        }
    }

    public function enqueue_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            wp_enqueue_script( $handle );
        }
    }

    public function enqueue_styles( $styles ) {
        foreach ( $styles as $handle => $script ) {
            wp_enqueue_style( $handle );
        }
    }
}
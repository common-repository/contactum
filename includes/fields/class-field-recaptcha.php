<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Recaptcha extends Contactum_Field {

    public function __construct() {
        $this->name       = __( 'Recaptcha', '' );
        $this->input_type = 'recaptcha';
        $this->icon       = 'qrcode';
    }

    public function render( $field_settings, $form_id ) {
        $settings   = contactum_get_settings( 'recaptcha' );
        $public_key = $settings['key'];
        $secret_key = $settings['secret'];
        $type       = $settings['type'];
        $theme      = isset( $field_settings['recaptcha_theme'] ) ? $field_settings['recaptcha_theme'] : 'light';
        /** Recaptcha V3 start */
        if( 'v3' == $type ) { ?>
            <li <?php $this->print_list_attributes( $field_settings ); ?>>
                <?php if ( ! $public_key  ) {
                    esc_html_e( 'reCaptcha API key is missing.', 'contactum');
                } else { ?>
                    <div class="contactum-fields <?php echo esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ); ?>">
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                        <input type="hidden" name="g-action" id="g-action">
                        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr( $public_key );?>"> </script>
                        <script>
                            grecaptcha.ready(function() {
                                  grecaptcha.execute('<?php echo esc_attr( $public_key );?>', {action: 'captcha_validation'}).then( function( token ) {
                                    document.getElementById('g-recaptcha-response').value = token;
                                    document.getElementById('g-action').value = 'captcha_validation';
                                  });
                                setInterval(function () {
                                    grecaptcha.execute('<?php echo esc_attr( $public_key );?>', {action: 'captcha_validation'}).then( function( token ) {
                                        document.getElementById('g-recaptcha-response').value = token;
                                        document.getElementById('g-action').value = 'captcha_validation';
                                    });
                                }, 60000);
                            });
                        </script>
                    </div>
                <?php }  return ; ?>
            </li>
        <?php }
        /** Recaptcha V3 end */

        if ( isset( $field_settings['recaptcha_type'] ) ) {
            $is_invisible = $field_settings['recaptcha_type'] == 'invisible_recaptcha' ? true : false;
        }

        $invisible_css   = $is_invisible ? ' style="margin: 0; padding: 0" ' : '';

        ?> <li <?php $this->print_list_attributes( $field_settings ); echo esc_attr( $invisible_css ); ?>>
            <?php
            if ( !$is_invisible ) {
                $this->print_label( $field_settings );
            }

            if ( ! $public_key ) {
                esc_html_e( 'reCaptcha API key is missing.', 'contactum');
            } else {
                ?>
                <div class="contactum-fields <?php echo esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ); ?>">
                    <script>
                        function contactumRecaptchaCallback(token) {
                            jQuery('[name="g-recaptcha-response"]').val(token);
                            jQuery('.contactum_submit_btn').attr('disabled',false).show();
                            jQuery('.contactum_submit_btn_recaptcha').hide();
                        }

                        jQuery(document).ready( function($) {
                            $('.contactum_submit_btn').attr('disabled',true);
                        });
                    </script>
                    <input type="hidden" name="g-recaptcha-response">
                                    <?php
                if ( $is_invisible ) { ?>
                    <script src="https://www.google.com/recaptcha/api.js?onload=contactumreCaptchaLoaded&render=explicit&hl=en" async defer></script>
                    <script>
                        jQuery(document).ready(function($) {
                            let btn = $('.contactum_submit_btn');
                            let gc_btn = btn.clone().removeClass().addClass('contactum_submit_btn_recaptcha').attr('disabled',false);
                            btn.after(gc_btn);
                            btn.hide();

                            $(document).on('click','.contactum_submit_btn_recaptcha',function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                grecaptcha.execute();
                            })
                        });

                        let contactumreCaptchaLoaded = function() {
                            grecaptcha.render('recaptcha', {
                                'size' : 'invisible',
                                'sitekey' : '<?php echo  esc_attr( $public_key ); ?>',
                                'callback' : contactumRecaptchaCallback
                            });
                            grecaptcha.execute();
                        };
                    </script>

                    <div id='recaptcha' class="g-recaptcha" data-theme="<?php echo esc_attr( $theme ); ?>" data-sitekey="<?php echo esc_attr( $public_key ); ?>" data-callback="contactumRecaptchaCallback" data-size="invisible"></div>

                <?php } else { ?>

                    <script src="https://www.google.com/recaptcha/api.js"></script>
                    <div id='recaptcha' data-theme="<?php echo esc_attr( $theme ); ?>" class="g-recaptcha" data-sitekey="<?php echo esc_attr ( $public_key ); ?>" data-callback="contactumRecaptchaCallback"></div>
                <?php } ?>
                </div>

            <?php } ?>
        </li>
        <?php
    }

    /**
     * Custom validator
     *
     * @return array
     */
    public function get_validator() {
        return [
            'callback'      => 'has_recaptcha_api_keys',
            'button_class'  => 'button-faded',
            'msg_title'     => __( 'Site key and Secret key', 'contactum' ),
            'msg'           => sprintf(
                __( 'You need to set Site key and Secret key in <a href="%s" target="_blank">Settings</a> in order to use "Recaptcha" field. <a href="%s" target="_blank">Click here to get the these key</a>.', 'contactum' ),
                admin_url( 'admin.php?page=contactum-settings' ),
                'https://www.google.com/recaptcha/'
              ),
        ];
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $settings = [
            [
                'name'          => 'label',
                'title'         => __( 'Title', 'contactum' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Title of the section', 'contactum' ),
            ],
            [
                'name'          => 'recaptcha_type',
                'title'         => 'reCaptcha type',
                'type'          => 'radio',
                'options'       => [
                    'enable_no_captcha'    => __( 'Enable noCaptcha', 'contactum' ),
                    'invisible_recaptcha'  => __( 'Enable Invisible reCaptcha', 'contactum' ),
                ],
                'default'       => 'enable_no_captcha',
                'section'       => 'basic',
                'priority'      => 11,
                'help_text'     => __( 'Select reCaptcha type', 'contactum' ),
                'show_if'       => 'is_recaptcha_v2'
            ],
            [
                'name'          => 'recaptcha_theme',
                'title'         => 'reCaptcha Theme',
                'type'          => 'radio',
                'options'       => [
                    'light' => __( 'Light', 'contactum' ),
                    'dark'  => __( 'Dark', 'contactum' ),
                ],
                'default'       => 'light',
                'section'       => 'advanced',
                'priority'      => 12,
                'help_text'     => __( 'Select reCaptcha Theme', 'contactum' ),
                'show_if'       => 'is_recaptcha_v2'
            ]
        ];

        return $settings;
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $props = [
            'template'        => $this->get_type(),
            'label'           => '',
            'recaptcha_type'  => 'enable_no_captcha',
            'is_meta'         => 'yes',
            'id'              => 0,
            'is_new'          => true,
            'contactum_cond'       => $this->default_conditional_prop(),
            'recaptcha_theme' => 'light',
        ];

        return $props;
    }
}

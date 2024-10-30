<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Date extends Contactum_Field {

    public function __construct() {
        $this->name       = __( 'Date', 'contactum' );
        $this->input_type = 'date_field';
        $this->icon       = 'calendar';
    }

    public function render( $field_settings, $form_id ) {
        $value = '';
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php
                $this->print_label( $field_settings );
            printf('<div class="contactum-fields"> <input
                    id="contactum-date-%s"
                    type="text"
                    class="datepicker contactum-el-form-control %s"
                    data-required="%s"
                    data-type="text"
                    name="%s"
                    placeholder="%s"
                    value="%s"
                    size="30"
                /> </div>',
                esc_attr( $field_settings['name'] ),
                esc_attr( $field_settings['name'] ).'_'. esc_attr($form_id),
                esc_attr($field_settings['required']),
                esc_attr( $field_settings['name'] ),
                esc_attr( $field_settings['name'].'_'.$form_id ),
                esc_attr( $value )
            );
                $this->help_text( $field_settings );
            ?>
        </li>
        <?php
            $name   = $field_settings['name'];
            $format = $field_settings["format"];
            
            $script = "jQuery('#contactum-date-{$name}').flatpickr({
                dateFormat: '{$format}'
            });";

            wp_add_inline_script( 'contactum-flatpickr', $script );
        }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();

        $settings = [

            [
                'name'          => 'format',
                'title'     => __( 'Date Format', 'contactum' ),
                'type'          => 'select',
                'is_single_opt' => true,
                'options'       => [
                    'm/d/Y'   => __( 'm/d/Y - (Ex: 04/28/2018)', 'contactum' ),
                    'm/d/y'   => __( 'm/d/Y - (Ex: 04/28/18)', 'contactum' ),
                    'd/m/Y'   => __( 'm/d/Y - (Ex: 28/04/2018)', 'contactum' ),
                    'd.m.Y'   => __( 'd.m.Y - (Ex: 28.04.2018)', 'contactum' ),
                    'm/d/Y'   => __( 'm/d/Y - (Ex: 04/28/2018)', 'contactum' ),
                    'y/m/d'   => __( 'y/m/d - (Ex: 28/04/18)', 'contactum' ),
                    'd-m-y'   => __( 'd-m-y - (Ex: 28-04-18)', 'contactum' ),
                    'h:i K'   => __( 'h:i K - (Ex: 08:55 PM)', 'contactum' ),
                    'H:i'   => __( 'H:i - (Ex: 20:55 )', 'contactum' ),
                    'd.m.Y H:i K' => __( 'd.m.Y H:i K- (Ex: 28.04.2018 20:55 PM)', 'contactum' ),
                    'd/m/Y H:i K' => __( 'd/m/Y H:i K- (Ex: 28/04/2018 20:55 PM)', 'contactum' ),
                    'd.m.Y H:i' => __( 'd.m.Y H:i - (Ex: 28.04.2018 20:55)', 'contactum' ),
                    'd/m/Y H:i' => __( 'd/m/Y H:i - (Ex: 28/04/2018 20:55)', 'contactum' ),
                    'H:i'   => __( 'H:i - (Ex: 28-04-18 )', 'contactum' ),
                ],
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => __('The date format', 'contactum')
            ],
        ];

        return array_merge( $default_options, $settings );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = [
            'format'          => 'd/m/y',
        ];

        return array_merge( $defaults, $props );
    }
}

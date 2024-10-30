<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Number extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Numeric', '' );
        $this->input_type = 'number_field';
        $this->icon       = 'hashtag';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['default'];
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>

        <?php
                $this->print_label( $field_settings );
                printf('<div class="contactum-fields"> <input
                        id="%s"
                        type="number"
                        class="contactum-el-form-control %s"
                        min="%s"
                        max="%s"
                        step="%s"
                        name="%s"
                        placeholder="%s"
                        value="%s"
                        size="%s"
                        data-required="%s"
                        data-type="text"
                    /> </div>',
                    esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                    esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ),
                    esc_attr($field_settings['min_value_field']),
                    $field_settings['max_value_field'] == 0 ? '' : esc_attr($field_settings['max_value_field']),
                    esc_attr( $field_settings['step_text_field'] ),
                    esc_attr( $field_settings['name'] ),
                    esc_attr( $field_settings['placeholder'] ),
                    esc_attr( $value ),
                    esc_attr( $field_settings['size'] ),
                    esc_attr( $field_settings['required'] ),
                );

                $this->help_text( $field_settings );
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options = $this->get_default_option_settings();

        $settings = array(
            array(
                'name'          => 'step_text_field',
                'title'         => __( 'Step', '' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 9,
                'help_text'     => '',
            ),

            array(
                'name'          => 'min_value_field',
                'title'         => __( 'Min Value', '' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 11,
                'help_text'     => '',
            ),

            array(
                'name'          => 'max_value_field',
                'title'         => __( 'Max Value', '' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 13,
                'help_text'     => '',
            ),

            // array(
            //     'name'          => 'duplicate',
            //     'title'         => 'No Duplicates',
            //     'type'          => 'checkbox',
            //     'is_single_opt' => true,
            //     'options'       => array(
            //         'no'   => __( 'Unique Values Only', '' )
            //     ),
            //     'default'       => '',
            //     'section'       => 'advanced',
            //     'priority'      => 23,
            //     'help_text'     => __( 'Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the entry database for that field.', '' ),
            // ),
        );

        return array_merge( $default_options, $settings );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'step_text_field'   => '0',
            'min_value_field'   => '0',
            'max_value_field'   => '0',
            // 'duplicate'         => '',
        );

        return array_merge( $defaults, $props );
    }
}

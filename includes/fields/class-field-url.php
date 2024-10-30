<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Field_Text;

class Field_Url extends Field_Text {

	public function __construct() {
        $this->name       = __( 'Url', 'contactum' );
        $this->input_type = 'url_field';
        $this->icon       = 'link';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['default'];
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php
                $this->print_label( $field_settings, $form_id );
                printf('<div class="contactum-fields"> <input
                    id="%s"
                    type="url"
                    class="contactum-el-form-control %s"
                    name="%s"
                    placeholder="%s"
                    value="%s"
                    size="%s"
                    autocomplete="url"
                    data-required="%s"
                    data-type="text"
                /> </div>',
                esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ),
                esc_attr( $field_settings['name'] ),
                esc_attr( $field_settings['placeholder'] ),
                esc_attr( $value ),
                esc_attr( $field_settings['size'] ),
                esc_attr( $field_settings['required'] )
            );
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();
        $default_text_options = $this->get_default_text_option_settings( false ); // word_restriction = false
        $check_duplicate      = [
            [
                'name'          => 'duplicate',
                'title'         => 'No Duplicates',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => [
                    'no'   => __( 'Unique Values Only', '' ),
                ],
                'default'       => '',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the entry database for that field.', '' ),
            ],
        ];

        return array_merge( $default_options, $default_text_options, $check_duplicate );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = [ 'duplicate' => '' ];

    	return array_merge( $defaults, $props );
    }
}

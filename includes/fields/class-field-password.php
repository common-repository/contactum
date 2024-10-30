<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Traits\Textoption;

class Field_Passsword extends Contactum_Field {
    use Textoption;

	public function __construct() {
        $this->name       = __( 'Password', 'contactum' );
        $this->input_type = 'password_field';
        $this->icon       = 'lock';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['default']; ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php
                $this->print_label( $field_settings, $form_id );
                
                printf('<div class="contactum-fields"> <input type="password" name="%s" class="contactum-el-form-control %s" id="%s" placeholder="%s" data-required="%s" data-type="text" value="%s" size="%s" data-errormessage="%s" /> </div>',
                    esc_attr( $field_settings['name'] ),
                    esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                    esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                    esc_attr( $field_settings['placeholder'] ),
                    esc_attr( $field_settings['required'] ),
                    esc_attr( $value ),
                    esc_attr( $field_settings['size'] ),
                    esc_attr( $field_settings['message']) 
                );

                $this->help_text( $field_settings );
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();
        $default_text_options = $this->get_default_text_option_settings( false );

        return array_merge( $default_options, $default_text_options );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props = [
            // 'word_restriction' => '',
            'duplicate'        => '',
        ];

    	return array_merge( $defaults, $props );
    }
}
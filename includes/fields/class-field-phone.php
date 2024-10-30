<?php
namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Phone extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Phone', 'contactum' );
        $this->input_type = 'phone_field';
        $this->icon       = 'phone';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['default'];
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php
                $this->print_label( $field_settings, $form_id );
                printf('<div class="contact-fields"><input
                    id="%s"
                    type="text"
                    class="contactum-el-form-control %s"
                    data-required="%s"
                    data-type="text"
                    name="%s"
                    placeholder="%s"
                    value="%s"
                    size="%s"
                    autocomplete="url"
                    class="contact-el-form-control"
                /> </div>',
                esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ),
                esc_attr( $field_settings['required'] ),
                esc_attr( $field_settings['name'] ),
                esc_attr( $field_settings['placeholder'] ),
                esc_attr( $value ),
                esc_attr( $field_settings['size'] )
            );

            $this->help_text( $field_settings );

            $name        = esc_attr( $field_settings['name'] );
            $mask_option = !empty( $field_settings['mask_options'] ) ? $field_settings['mask_options'] : '';

            $script = "jQuery(document).ready(function($){
                var phone_field = $(`input[name*={$name}]`);

                if ( 'standard' == `{$mask_option}` ) {
                    phone_field.mask('(+999)-999-999999999');
                } else if( 'standard_2' == `{$mask_option}` ) {
                    phone_field.mask('(+99)-999-999-99999999');
                } else {
                    phone_field.mask('(+9)-999-999-99999999');
                }
            });";

            wp_add_inline_script( 'contactum-frontend', $script );
        ?>
            </li>
    <?php }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();
        $check_duplicate      = array(
            array(
                'name'          => 'duplicate',
                'title'         => 'No Duplicates',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => array(
                    'no'   => __( 'Unique Values Only', 'contactum' )
                ),
                'default'       => '',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the entry database for that field.', '' ),
            )
        );
        $mask_options  = array(
            array(
                'name'      => 'mask_options',
                'title'     => 'Mask Options',
                'type'      => 'select',
                'options'   => array(
                    'standard'      => __( '(+###) ###-####', 'contactum' ),
                    'standard_2'    => __( '(+##) ###-####', 'contactum' ),
                    'international' => __( 'International', 'contactum' ),
                ),
                'default'   => '',
                'section'   => 'advanced',
                'priority'  => 23,
                'help_text' => __( 'Select this option to add masking to phone field.', 'contactum' ),
            )
        );
        return array_merge( $default_options, $check_duplicate, $mask_options );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();

        return $defaults;
    }
}
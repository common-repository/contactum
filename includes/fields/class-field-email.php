<?php
namespace Contactum\Fields;

use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Traits\Textoption;

class Field_Email extends Contactum_Field {
    use Textoption;

	public function __construct() {
        $this->name       = __( 'Email', 'contactum' );
        $this->input_type = 'email_field';
        $this->icon       = 'envelope-o';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['default'];
        if ( isset( $field_settings['auto_populate'] ) && $field_settings['auto_populate'] == 'yes' && is_user_logged_in() ) {
            return;
        }
    ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id );
                printf('<div class="contactum-fields"> <input
                        id="%s"
                        type="email"
                        class="contactum-el-form-control %s"
                        data-duplicate="%s"
                        data-required="%s"
                        data-type="email"
                        name="%s"
                        placeholder="%s"
                        value="%s"
                        size="%s"
                        autocomplete="url"
                    /></div>',
                    esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
                    esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ),
                    isset( $field_settings['duplicate'] ) ? esc_attr( $field_settings['duplicate'] ) : 'no',
                    esc_attr( $field_settings['required'] ),
                    esc_attr( $field_settings['name'] ),
                    esc_attr( $field_settings['placeholder'] ),
                    esc_attr( $value ),
                    esc_attr( $field_settings['size'] )
                );

                $this->help_text( $field_settings );
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();
        $default_text_options = $this->get_default_text_option_settings();

        $check_duplicate      = [
            [
                'name'          => 'duplicate',
                'title'         => 'No Duplicates',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => [
                    'no'   => __( 'Unique Values Only', 'contactum' ),
                ],
                'default'       => '',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the entry database for that field.', 'contactum' ),
            ],
            [
                'name'          => 'auto_populate',
                'title'         => 'Auto-populate email for logged users',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => [
                    'yes'   => __( 'Auto-populate Email', 'contactum' ),
                ],
                'default'       => '',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'If a user is logged into the site, this email field will be auto-populated with his email. And form\'s email field will be hidden.', 'contactum' ),
            ],
        ];

        return array_merge( $default_options, $default_text_options, $check_duplicate );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props = [
            'duplicate' => '',
        ];

    	return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        if ( isset( $field['auto_populate'] ) && $field['auto_populate'] == 'yes' && is_user_logged_in() ) {
            $user = wp_get_current_user();

            if ( !empty( $user->user_email ) ) {
                return $user->user_email;
            }
        }

        $value = !empty( $post_data[ $field[ 'name' ] ] ) ? $post_data[ $field[ 'name' ] ] : '';

        return sanitize_text_field( trim( $value ) );
    }
}
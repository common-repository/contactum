<?php
namespace Contactum\Fields;

use Contactum\Fields\Contactum_Field;

class Field_Name extends Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Name', 'contactum' );
        $this->input_type = 'name_field';
        $this->icon       = 'user-o';
    }

    public function render( $field_settings, $form_id ) {
        if ( isset( $field_settings['auto_populate'] ) && $field_settings['auto_populate'] == 'yes' && is_user_logged_in() ) {
            return;
        }
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
             <?php // $this->print_label( $field_settings, $form_id ); ?>

        <div class="contactum-fields">
            <div class="name-container">
                <div class="first-name">
                        <?php
                        if( $field_settings['first_name']['show_field'] == 1) {
                            printf('<label class="contactum-form-sub-label"> %s %s</label>', $field_settings['first_name']['label'], wp_kses_post( $this->required( $field_settings['first_name'] ) ) );
                            printf('<input
                                name="%s[first]"
                                type="text"
                                placeholder="%s"
                                value="%s"
                                size="40"
                                autocomplete="family-name",
                                data-required="%s"
                                data-type="text"
                                class="contactum-el-form-control"
                                data-errormessage="%s"
                            >',
                                esc_attr( $field_settings['name'] ),
                                esc_attr( $field_settings['first_name']['placeholder'] ),
                                esc_attr( $field_settings['first_name']['default'] ),
                                esc_attr( $field_settings['first_name']['required'] == 1 ? 'yes' : 'no' ),
                                esc_attr( $field_settings['first_name']['message'] )
                            );
                        }
                        ?>
                </div>
            
            <?php 
            if( $field_settings['middle_name']['show_field'] == 1) {
                ?>
            
                <div class="middle-name">
                        <?php
                            printf('<label class="contactum-form-sub-label"> %s %s</label>', $field_settings['middle_name']['label'], wp_kses_post( $this->required( $field_settings['last_name'] ) ) );
                            printf('<input
                                    name="%s[middle]"
                                    type="text"
                                    placeholder="%s"
                                    value="%s"
                                    size="40"
                                    autocomplete="additional-name"
                                    class="contactum-el-form-control"
                                    data-type="text"
                                    data-required="%s"
                                    data-errormessage="%s"
                                />',
                                esc_attr( $field_settings['name'] ),
                                esc_attr( $field_settings['middle_name']['placeholder'] ),
                                esc_attr( $field_settings['middle_name']['default'] ),
                                esc_attr( $field_settings['middle_name']['required'] == 1 ? 'yes' : 'no' ),
                                esc_attr( $field_settings['middle_name']['message'] )
                            );
                        ?>
                </div>
            <?php 
            }
            if( $field_settings['last_name']['show_field'] == 1) {
            ?>

                <div class="last-name">
                    
                    <?php
                        printf('<label class="contactum-form-sub-label"> %s %s </label>', $field_settings['last_name']['label'], wp_kses_post( $this->required( $field_settings['last_name'] ) ) );
                        printf('<input
                            name="%s[last]"
                            type="text"
                            placeholder="%s"
                            value="%s"
                            size="40"
                            autocomplete="family-name"
                            class="contactum-el-form-control"
                            data-type="text"
                            data-required="%s"
                            data-errormessage="%s"
                        >',
                            esc_attr( $field_settings['name'] ),
                            esc_attr( $field_settings['last_name']['placeholder'] ),
                            esc_attr( $field_settings['last_name']['default'] ),
                            esc_attr( $field_settings['last_name']['required'] == 1 ? 'yes' : 'no' ),
                            esc_attr( $field_settings['last_name']['message'] )
                        );
                    ?>
                </div>
            <?php 
            }
            ?>
            </div>
            <?php
                $this->help_text( $field_settings );
            ?>
        </li>
    <?php
    }

    public function get_options_settings() {
        $default_options = $this->get_default_option_settings( true, ['required']);
        $name_settings = [
            // [
            //     'name'      => 'format',
            //     'title'     => __( 'Format', 'contactum' ),
            //     'type'      => 'radio',
            //     'options'   => [
            //         'first-last'        => __( 'First and Last name', 'contactum' ),
            //         'first-middle-last' => __( 'First, Middle and Last name', 'contactum' ),
            //     ],
            //     'selected'  => 'first-last',
            //     'section'   => 'advanced',
            //     'priority'  => 20,
            //     'help_text' => __( 'Select format to use for the name field', 'contactum' ),
            // ],
            [
                'name'          => 'auto_populate',
                'title'         => 'Auto-populate name for logged users',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => [
                    'yes'   => __( 'Auto-populate Name', 'contactum' ),
                ],
                'default'       => '',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'If a user is logged into the site, this name field will be auto-populated with his first-last/display name. And form\'s name field will be hidden.', 'contactum' ),
            ],
            [
                'name'      => 'sub-labels',
                'title'     => __( 'Label', 'contactum' ),
                'type'      => 'name',
                'section'   => 'advanced',
                'priority'  => 21,
                'help_text' => __( 'Select format to use for the name field', 'contactum' ),
            ],
            // [
            //     'name'          => 'hide_subs',
            //     'title'         => '',
            //     'type'          => 'checkbox',
            //     'is_single_opt' => true,
            //     'options'       => [
            //         'true'   => __( 'Hide Sub Labels', 'contactum' ),
            //     ],
            //     'section'       => 'advanced',
            //     'priority'      => 23,
            //     'help_text'     => '',
            // ],
            // [
            //     'name'          => 'inline',
            //     'title'         => __( 'Show in inline list', 'contactum' ),
            //     'type'          => 'radio',
            //     'options'       => [
            //         'yes'   => __( 'Yes', 'contactum' ),
            //         'no'    => __( 'No', 'contactum' ),
            //     ],
            //     'default'       => 'no',
            //     'inline'        => true,
            //     'section'       => 'advanced',
            //     'priority'      => 23,
            //     'help_text'     => __( 'Show this option in an inline list', 'contactum' ),
            // ],
        ];

        return array_merge( $default_options, $name_settings );
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = [
            // 'format'     => 'first-last',
            'first_name' => [
                'message'     => 'This field is required',
                'show_field'  => true,
                'required'    => false,
                'label'       => 'Firstname',
                'placeholder' => '',
                'default'     => '',
                'sub'         => __( 'First', 'contactum' ),
            ],
            'middle_name' => [
                'message'     => 'This field is required',
                'show_field'  => true,
                'required'    => false,
                'label'       => 'Middlename',
                'placeholder' => '',
                'default'     => '',
                'sub'         => __( 'Middle', 'contactum' ),
            ],
            'last_name' => [
                'message'     => 'This field is required',
                'show_field'  => true,
                'required'    => false,
                'label'       => 'Lastname',
                'placeholder' => '',
                'default'     => '',
                'sub'         => __( 'Last', 'contactum' ),
            ],
            // 'inline'           => 'yes',
            // 'hide_subs'        => false,
        ];

        return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        if ( isset( $field['auto_populate'] ) && $field['auto_populate'] == 'yes' && is_user_logged_in() ) {
            $user = wp_get_current_user();

            if ( !empty( $user->ID ) ) {
                if ( $user->first_name || $user->last_name ) {
                    $name   = [];
                    $name[] = $user->first_name;
                    $name[] = $user->last_name;

                    return implode( ' | ', $name );
                } else {
                    return $user->display_name;
                }
            }
        }

        $value = !empty( $post_data[$field['name']] ) ? $post_data[$field['name']] : '';

        if ( is_array( $value ) ) {
            $entry_value = implode(CONTACTUM_SEPARATOR, $post_data[$field['name']] );
        } else {
            $entry_value =  $value;
        }

        return $entry_value;
    }

    public function is_full_width() {
        return false;
    }
}
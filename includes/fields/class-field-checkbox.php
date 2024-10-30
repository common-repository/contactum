<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Traits\DropDownOption;

class Field_Checkbox extends Contactum_Field {
    use DropDownOption;

	public function __construct() {
        $this->name       = __( 'Checkbox', 'contactum' );
        $this->input_type = 'checkbox_field';
        $this->icon       = 'check-square-o';
        $this->multiple   = true;
    }

    public function render( $field_settings, $form_id ) {
        $selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?> >
            <?php $this->print_label( $field_settings, $form_id ); ?>
            <?php
                
                $class = $field_settings['layout'] == 'inline' ? 'show-inline ' : "show ";

                if( $field_settings['photo_value'] ) {
                    $class .= 'list_type_image';  
                }
            ?>
            <div class="contactum-fields">
                <div class="<?php echo esc_attr($class); ?>"
                data-required="<?php echo esc_attr( $field_settings['required'] ) ?>" data-type="radio">
                <?php
                    if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
                        foreach ( $field_settings['options'] as $value => $option ) { ?>
                            <label>
                        <?php    
                            printf( '<input name="%s[]" class="%s" type="checkbox" value="%s" %s/>',
                                esc_attr( $field_settings['name'] ),
                                esc_attr( $field_settings['name'] ). '_'. esc_attr( $form_id ),
                                esc_attr( $option['value'] ),
                                in_array( $option['value'], $selected ) ? ' checked="checked"' : '',
                            );

                                if( $option['photo'] != "" ) {
                                  printf( '<img src="%s"  width="50px"/>',
                                    esc_attr( $option['photo'] )
                                  );  
                                } else {
                                    echo $option['label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                } ?>
                        </label>
                       <?php } ?>
                <?php
                }
                $this->help_text( $field_settings );
            ?>
            </div>
        </li>
    <?php
    }

    public function get_options_settings() {
        $default_options  = $this->get_default_option_settings( true, [ 'width' ] );
        $dropdown_options = [
            $this->get_default_option_dropdown_settings( $this->multiple ),
            [
                'name'          => 'layout',
                'title'         => __( 'Layout', 'contactum' ),
                'type'          => 'select',
                'options'       => [
                    'default'   => __( 'Default', 'contactum' ),
                    'inline'    => __( 'Inline', 'contactum' ),
                ],
                'default'       => 'default',
                'inline'        => true,
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'Show this option in an inline list', 'contactum' ),
            ],
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

        return  array_merge( $default_options, $dropdown_options);
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = [
            'selected' => [],
            // 'inline'   => 'no',
            'layout'   => 'default',
            'image' => true,
            'options'  => [
                [
                    'label' => 'option',
                    'value' => 'option',
                    'photo' => '',
                ],
                [
                    'label' => 'option-2',
                    'value' => 'option-2',
                    'photo' => '',
                ],
                [
                    'label' => 'option-3',
                    'value' => 'option-3',
                    'photo' => '',
                ]
                // 'Option' => __( 'Option', 'contactum' ),
                // 'Option-2' => __( 'Option-2', 'contactum' ),
                // 'Option-3' => __( 'Option-3', 'contactum' )
            ],
        ];

    	return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        $entry_value = ( is_array( $post_data[ $field[ 'name' ] ] ) && $post_data[ $field[ 'name' ] ] ) ? $post_data[ $field[ 'name' ] ] : array();

        if ( $entry_value && $this->multiple ) {
            $new_val = [];

            foreach ( $entry_value as $option_key ) {
                $new_val[] = isset( $field['options'][$option_key] ) ? $field['options'][$option_key] : $option_key;
            }

            $entry_value = implode(CONTACTUM_SEPARATOR, $new_val );
        } else {
            $entry_value = '';
        }

        return $entry_value;
    }
}

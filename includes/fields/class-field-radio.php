<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Field_Checkbox;
use Contactum\Fields\Traits\DropDownOption;

class Field_Radio extends Field_Checkbox {

	public function __construct() {
        $this->name       = __( 'Radio', '' );
        $this->input_type = 'radio_field';
        $this->icon       = 'dot-circle-o';
        $this->multiple   = false;
    }

    public function render( $field_settings, $form_id ) {
        $selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
        // $field_settings['photo_value'] == 1
        // print_r($field_settings);
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>
            <?php 
                
                $class   =  $field_settings['layout'] == 'inline' ? 'show-inline ' : "show ";
                
                if( $field_settings['photo_value'] ) {
                    $class .= 'list_type_image';  
                }
                // echo $field_settings['layout'] == 'inline' ? 'class="show-inline"' : 'class="show"'; 
            ?>

            <div class="<?php echo esc_attr($class); ?>" data-required="<?php echo esc_attr( $field_settings['required'] ) ?>" data-type="radio">
                <?php
                    if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
                        foreach ( $field_settings['options'] as $value => $option ) { ?>
                            <label>
                            <?php
                                printf( '<input name="%s" class="%s" type="radio" value="%s" %s/>',
                                    esc_attr( $field_settings['name'] ),
                                    esc_attr( $field_settings['name'] ). '_'. esc_attr( $form_id ),
                                    esc_attr( $option['value'] ),
                                    ( $option['value'] == $selected ) ? ' checked="checked"' : '',
                                    // checked( $selected, $option['value'] )
                                );
                                
                                if( $option['photo'] != "" && $field_settings['photo_value'] == 1 ) {
                                  printf( '<img src="%s"/>',
                                    esc_attr( $option['photo'] )
                                  );  
                                } else {
                                    echo '<span>'. $option['label'] . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                                // echo '<span>'. $option['label'] . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                            </label>
                        <?php }
                    }
                    $this->help_text( $field_settings );
                ?>
            </div>
        </li>
    <?php }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = [
            'selected' => '',
            'inline'   => 'no',
            'layout'   => 'default',
            'image' => true,
            'sync_value' =>  true,
            'photo_value' =>  false,
            'show_value' => false,
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
                ],
                // 'Option' => __( 'option', 'contactum' ),
                // 'Option-2' => __( 'option-2', 'contactum' ),
                // 'Option-3' => __( 'option-3', 'contactum' ),
            ],
        ];

        return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        $val  = $post_data[$field['name']];
        return isset( $field['options'][$val] ) ? $field['options'][$val] : $val;
    }
}

<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Traits\DropDownOption;

class Field_Dropdown extends Contactum_Field {
    use DropDownOption;

	public function __construct() {
        $this->name       = __( 'DropDown', 'contactum' );
        $this->input_type = 'dropdown_field';
        $this->icon       = 'caret-square-o-down';
        $this->multiple   = false;
    }

    public function render( $field_settings, $form_id ) {
        $selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
        $name     = $field_settings['name'];
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>
            <div class="contactum-fields">
                <select
                    class="select contactum-el-form-control <?php echo esc_attr( $field_settings['name'] ) .'_'. esc_attr( $form_id ); ?>"
                    id="<?php echo esc_attr($field_settings['name']) . '_' . esc_attr($form_id); ?>"
                    name="<?php echo esc_attr($name); ?>"
                    data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
                    data-type="select"
                >
                    <?php
                        if ( !empty( $field_settings['first'] ) ) { ?>
                        <option value=""> <?php echo esc_attr( $field_settings['first'] ); ?> </option>
                    <?php }
                        if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
                            foreach ( $field_settings['options'] as $value => $option ) {
                               $current_select = selected( $selected, $option['value'], false );
                               printf('<option value="%s" %s> %s </option>', esc_attr( $option['value'] ), esc_attr( $current_select ), esc_attr( $option['value'] ) );
                            }
                        }
                    ?>
                </select>
            </div>
            
            <?php $this->help_text( $field_settings ); ?>
        </li>
    <?php }

    public function get_options_settings() {
        $default_options  = $this->get_default_option_settings();
        $dropdown_options = [
            $this->get_default_option_dropdown_settings( $this->multiple ),
            [
                'name'          => 'first',
                'title'         => __( 'Select Text', 'contactum' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 13,
                'help_text'     => __( "", 'contactum' ),
            ],
        ];

        return  array_merge( $default_options, $dropdown_options);
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = [
            'selected' => '',
            'image' => false,
            'options'  => [
                [
                    'label' => 'option',
                    'value' => 'option',
                ],
                [
                    'label' => 'option-2',
                    'value' => 'option-2'
                ],
                [
                    'label' => 'option-3',
                    'value' => 'option-3'
                ]
                // 'Option' => __( 'Option', 'contactum' ),
                // 'Option-2' => __( 'Option-2', 'contactum' ),
                // 'Option-3' => __( 'Option-3', 'contactum' )
            ],
            'first'    => __( '— Select —', 'contactum' ),
        ];

    	return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        $val  = $post_data[$field['name']];

        return isset( $field['options'][$val] ) ? $field['options'][$val] : $val;
    }
}

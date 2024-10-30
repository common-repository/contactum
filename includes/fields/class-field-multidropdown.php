<?php
namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_MultiDropdown extends Field_Dropdown {

	public function __construct() {
        $this->name       = __( 'Multi Select', 'contactum' );
        $this->input_type = 'multiple_select';
        $this->icon       = 'list-ul';
        $this->multiple   = false;
    }

    public function render( $field_settings, $form_id ) {
        $selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
        $selected = is_array( $selected ) ? $selected : [ $selected ];
        $name     = $field_settings['name'] . '[]';
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>
                <div class="contactum-fields">

                    <select
                        class="multiselect contactum-el-form-control <?php echo esc_attr( $field_settings['name'] ) .'_'. esc_attr( $form_id ); ?>"
                        id="<?php echo esc_attr($field_settings['name']) . '_' . esc_attr($form_id); ?>"
                        name="<?php echo esc_attr($name); ?>"
                        multiple
                        data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
                        data-type="multiselect"
                    >
                        <?php
                            if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
                                foreach ( $field_settings['options'] as  $option ) {
                                    $current_select = selected( in_array( $option['value'], $selected ), true, false );
                                    printf('<option value="%s" %s> %s </option>', esc_attr( $option['value'] ), esc_attr( $current_select ), esc_attr( $option['value'] ) );
                                }
                            }
                        ?>
                    </select>
                </div>
            <?php $this->help_text( $field_settings ); ?>
        </li>
        <?php
            $id = esc_attr($field_settings['name']) . '_' . esc_attr($form_id);

            $script = "jQuery(function($) {
                new Choices('#{$id}', {
                    removeItemButton: true
                });
            });";

            wp_add_inline_script( 'contactum-frontend', $script );

        ?>
    <?php }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = [
            'selected' => [],
            'image' => false,
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
            'first'    => __( '— Select —', 'contactum' ),
        ];

    	return array_merge( $defaults, $props );
    }

    public function prepare_entry( $field, $post_data = [] ) {
        $entry_value = ( is_array( $post_data[$field['name']] ) && $post_data[$field['name']] ) ? $post_data[$field['name']] : array();

        if ( $entry_value ) {
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

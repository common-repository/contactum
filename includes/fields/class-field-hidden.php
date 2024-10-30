<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Hidden extends Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Hidden', 'contactum' );
        $this->input_type = 'hidden_field';
        $this->icon       = 'eye-slash';
    }

    public function render( $field_settings, $form_id ) {
        $value = $field_settings['meta_value'];
    ?>
    <li <?php $this->print_list_attributes( $field_settings ); ?> >
        <div class="contactum-fields">
            <input type="hidden" name="<?php echo esc_attr( $field_settings['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        </div>
    </li>
   <?php }

    public function get_options_settings() {
        $settings = [
            [
                'name'      => 'name',
                'title'     => __( 'Meta Key', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 10,
                'help_text' => __( 'Name of the meta key this field will save to', 'contactum' ),
            ],
            [
                'name'      => 'meta_value',
                'title'     => __( 'Meta Value', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 11,
                'help_text' => __( 'Enter the meta value', 'contactum' ),
            ],
            [
                'name'          => 'dynamic',
                'title'         => '',
                'type'          => 'dynamic',
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => __( 'Check this option to allow field to be populated dynamically using hooks/query string/shortcode', 'contactum' ),
            ],
        ];

        return $settings;
    }

    public function get_field_props() {
        $props = [
            'template'      => $this->get_type(),
            'name'          => '',
            'meta_value'    => '',
            'is_meta'       => 'yes',
            'id'            => 0,
            'is_new'        => true,
            'contactum_cond'     => null,
        ];

    	return $props;
    }
}

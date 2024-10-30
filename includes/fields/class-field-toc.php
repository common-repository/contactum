<?php
namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Toc extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Toc', 'contactum' );
        $this->input_type = 'toc';
        $this->icon       = 'file-text';
    }

    public function render( $field_settings, $form_id ) { ?>
        <li>
            <label>
                <?php if ( isset( $field_settings['show_checkbox'] ) && $field_settings['show_checkbox'] ) : ?>
                    <input type="checkbox" name="toc" required="required" />
                <?php endif; ?>
                <?php echo $field_settings['description']; ?>
            </label>
        </li>
    <?php }

    public function get_options_settings() {
        $settings = [
            [
                'name'          => 'name',
                'title'         => __( 'Meta Key', 'contactum' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Name of the meta key this field will save to', 'contactum' ),
            ],

            [
                'name'          => 'description',
                'title'         => __( 'Terms & Conditions', 'contactum' ),
                'type'          => 'textarea',
                'section'       => 'basic',
                'priority'      => 11,
            ],

            [
                'name'          => 'show_checkbox',
                'type'          => 'checkbox',
                'options'       => array(
                    true        => __( 'Show checkbox', 'contactum' )
                ),
                'section'       => 'basic',
                'priority'      => 11,
            ]
        ];

        return $settings;
    }

    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = array(
            'label'         => '',
            'description'   => __( 'I have read and agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>', 'contactum' ),
            'show_checkbox' => true,
        );

        return array_merge( $defaults, $props );
    }
}
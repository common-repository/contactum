<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_SectionBreak extends Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Section Break', '' );
        $this->input_type = 'section_break';
        $this->icon       = 'text-width';
    }

    public function render( $field_settings, $form_id ) {
        $description = isset( $field_settings['description'] ) ? $field_settings['description'] : '';
        $name        = isset( $field_settings['name'] ) ? $field_settings['name'] : '';
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <div class="<?php echo 'section_' . esc_attr( $form_id ); ?> <?php echo esc_html( $name ) . '_' . esc_attr( $form_id ); ?>">
                <h2 class="section-title"><?php echo esc_attr( $field_settings['label'] ); ?></h2>
                <div class="section-details"><?php echo esc_attr( $description ); ?></div>
            </div>

        </li>
        <?php
    }

    public function get_options_settings() {
        $settings = [
            [
                'name'      => 'label',
                'title'     => __( 'Title', '' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 10,
                'help_text' => __( 'Title of the section', '' ),
            ],
            [
                'name'          => 'name',
                'title'         => __( 'Meta Key', '' ),
                'type'          => 'text_meta',
                'section'       => 'basic',
                'priority'      => 11,
                'help_text'     => __( 'Name of the meta key this field will save to', '' ),
            ],
            [
                'name'      => 'description',
                'title'     => __( 'Description', '' ),
                'type'      => 'textarea',
                'section'   => 'basic',
                'priority'  => 12,
                'help_text' => __( 'Some details text about the section', '' ),
            ],
        ];

        return $settings;
    }

    public function get_field_props() {
        $props = [
            'template'     => $this->get_type(),
            'label'        => $this->get_name(),
            'description'  => __( 'Some description about this section', '' ),
            'id'           => 0,
            'is_new'       => true,
            'contactum_cond' => $this->default_conditional_prop()
        ];

    	return $props;
    }

    public function is_full_width() {
        return false;
    }

}

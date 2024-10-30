<?php
namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Hook extends  Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Action Hook', 'contactum' );
        $this->input_type = 'hook_field';
        $this->icon       = 'anchor';
    }

    public function render( $field_settings, $form_id ) { ?>
        <li>
            <?php
                if ( !empty( $field_settings['label'] ) ) {
                    do_action( $field_settings['label'], $form_id );
                }
            ?>
        </li>
    <?php }

    public function get_options_settings() {
        $settings = [
            [
                'name'          => 'label',
                'title'         => __( 'Hook Name', 'contactum' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Name of the hook', 'contactum' ),
            ],

            [
                'name'          => 'help_text',
                'title'         => '',
                'type'          => 'html_help_text',
                'section'       => 'basic',
                'priority'      => 11,
                'text'          => sprintf( __( 'An option for developers to add dynamic elements they want. It provides the chance to add whatever input type you want to add in this form. This way, you can bind your own functions to render the form to this action hook. You\'ll be given 3 parameters to play with: $form_id, $post_id, $form_settings.', 'contactum' ) )
                                   . '<pre>add_action(\'HOOK_NAME\', \'your_function_name\', 10, 3 );<br>'
                                   . 'function your_function_name( $form_id, $post_id, $form_settings ) {<br>'
                                   . '    // do what ever you want<br>'
                                   . '}</pre>'
            ]
        ];

        return $settings;
    }

    public function get_field_props() {
        $props = array(
            'template'          => $this->get_type(),
            'label'             => 'CUSTOM_HOOK_NAME',
            'id'                => 0,
            'is_new'            => true,
            'wpuf_cond'         => null
        );

        return $props;
    }
}
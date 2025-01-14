<?php

namespace Contactum\Fields;

/**
 * Pro fields wrapper class
 */
class Contactum_Form_Field_Pro extends Contactum_Field {

    /**
     * Render the text field
     *
     * @param array $field_settings
     * @param int   $form_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id ) {
        echo esc_html_e( 'This is a premium field. You need to upgrade.', 'contactum' );
    }

    /**
     * Check if it's a pro feature
     *
     * @return bool
     */
    public function is_pro() {
        return true;
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        return __return_empty_array();
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        return __return_empty_array();
    }
}

<?php

namespace Contactum;

use Contactum\Contactum_Form_Field_Repeat;
use Contactum\Contactum_Form_Field_GMap;
/**
 * The Pro Integrations
 */
class Contactum_Pro_Upgrades {

    /**
     * Initialize
     */
    public function __construct() {
        if ( class_exists( 'Contactum_Pro' ) ) {
            return;
        }

        // form fields
        add_filter( 'contactum_form_fields', [ $this, 'register_pro_fields' ] );
        add_filter( 'contactum_form_fields_custom_fields', [ $this, 'add_to_custom_fields' ] );
        add_filter( 'contactum_form_fields_others_fields', [ $this, 'add_to_others_fields' ] );
    }

    /**
     * Register pro fields
     *
     * @param array $fields
     *
     * @return array
     */
    public function register_pro_fields( $fields ) {
        if ( !class_exists( 'Contactum_Form_Field_Pro' ) ) {
            require_once CONTACTUM_INCLUDES . '/fields/class-fields-pro.php';
        }

        require_once CONTACTUM_INCLUDES . '/admin/class-pro-upgrade-fields.php';

        $fields['repeat_field']         = new \Contactum\Contactum_Form_Field_Repeat();
        $fields['google_map']           = new \Contactum\Contactum_Form_Field_GMap();
        $fields['shortcode']            = new \Contactum\Contactum_Form_Field_Shortcode();
        $fields['action_hook']          = new \Contactum\Contactum_Form_Field_Hook();
        $fields['linear_scale']         = new \Contactum\Contactum_Form_Field_Linear_Scale();
        $fields['checkbox_grid']        = new \Contactum\Contactum_Form_Field_Checkbox_Grid();
        $fields['multiple_choice_grid'] = new \Contactum\Contactum_Form_Field_Multiple_Choice_Grid();
        $fields['step_start']           = new \Contactum\Contactum_Form_Field_Step();

        return $fields;
    }

    /**
     * Register fields to custom field section
     *
     * @param array $fields
     */
    public function add_to_custom_fields( $fields ) {
        $pro_fields = [
            'repeat_field', 'google_map', 'step_start',
        ];

        return array_merge( $fields, $pro_fields );
    }

    /**
     * Register fields to others field section
     *
     * @param array $fields
     */
    public function add_to_others_fields( $fields ) {
        $pro_fields = [
            'shortcode', 'action_hook', 'linear_scale', 'checkbox_grid', 'multiple_choice_grid',
        ];

        return array_merge( $fields, $pro_fields );
    }
}

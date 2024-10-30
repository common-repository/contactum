<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;
/**
 * Contactum form template
 */
class Template_Contactum extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Contact Form', '' );
        $this->description = __( 'Create a simple Contact form', '' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/contact.png';
        $this->category    = 'default';
    }

    /**
     * Get the form fields
     *
     * @return array
     */
    public function get_form_fields() {
        $all_fields = $this->get_register_fields();

        $form_fields = [
            array_merge( $all_fields['name_field']->get_field_props(), [
                'required' => 'yes',
                'name'     => 'name',
            ] ),
            array_merge( $all_fields['email_field']->get_field_props(), [
                'required' => 'yes',
                'name'     => 'email',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Message', 'contactum' ),
                'name'     => 'message',
            ] ),
        ];

        return $form_fields;
    }
}

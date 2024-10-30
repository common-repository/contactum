<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;
/**
 * Contactum form template
 */
class Template_Support extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Support Form', '' );
        $this->description = __( 'Create a simple Contact form', '' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/support.png';
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
                "first_name" => [ 
                    "message" => "This field is required", 
                    "show_field" => true, 
                    "required" => true, 
                    "label" => "Firstname", 
                    "placeholder" => "", 
                    "default" => "", 
                    "sub" => "First" 
                ], 
                "middle_name" => [
                    "message" => "This field is required", 
                    "show_field" => false, 
                    "required" => false, 
                    "label" => "Middlename", 
                    "placeholder" => "", 
                    "default" => "", 
                    "sub" => "Middle" 
                ], 
                "last_name" => [
                    "message" => "This field is required",
                    "show_field" => true, 
                    "required" => true, 
                    "label" => "Lastname",
                    "placeholder" => "", 
                    "default" => "", 
                    "sub" => "Last" 
                ]
            ] ),
            array_merge( $all_fields['email_field']->get_field_props(), [
                'required' => 'yes',
                'name'     => 'email',
            ] ),
            array_merge( $all_fields['dropdown_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Department', 'contactum' ),
                'name'     => 'department',
                'options'  => [
                    [
                        'value' => 'Sales',
                        'label' => 'sales'
                    ],
                    [
                        'value' => 'Customer Support',
                        'label' => 'support'
                    ],
                    [
                        'value' => 'Product Development',
                        'label' => 'product_development'
                    ],
                    [
                        'value' => 'Other',
                        'label' => 'other'
                    ],
                ],
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Subject', 'contactum' ),
                'name'     => 'subject',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Description', 'contactum' ),
                'name'     => 'description',
            ] ),
        ];

        return $form_fields;
    }
}

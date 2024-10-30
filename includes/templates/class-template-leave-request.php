<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;

/**
 * Blank form template
 */
class Template_Leave_Request extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Request for Leave', 'contactum' );
        $this->description = __( 'Get an instant leave request from your employees with this easy to fill-out a request form and get details without any conflicts.', 'contactum' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/leave_request.png';
        $this->category    = 'employment';
    }

    /**
     * Get the form fields
     *
     * @return array
     */
    public function get_form_fields() {
        $all_fields    = $this->get_register_fields();

        $form_fields   = [
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

            array_merge( $all_fields['number_field']->get_field_props(), [
                'requied'   => 'yes',
                'label'     => __( 'Employee ID', 'contactum' ),
                'name'      => 'employee_id',
            ] ),

            array_merge( $all_fields['phone_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Phone Number', 'contactum' ),
                'name'      => 'phone_number',
            ] ),

            array_merge( $all_fields['text_field']->get_field_props(), [
                'label'     => __( 'Position', 'contactum' ),
                'name'      => 'position',
            ] ),

            array_merge( $all_fields['text_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Manager', 'contactum' ),
                'name'      => 'manager',
            ] ),

            array_merge( $all_fields['date_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Leave Start', 'contactum' ),
                'name'      => 'leave_start',
            ] ),

            array_merge( $all_fields['date_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Leave End', 'contactum' ),
                'name'      => 'leave_end',
            ] ),

            array_merge( $all_fields['radio_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Leave Type', 'contactum' ),
                'name'      => 'leave_type',
                'options'   => [
                     [
                        'label' => 'Vacation',
                        'value' => 'vacation',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Sick',
                        'value' => 'sick',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Quitting',
                        'value' => 'quitting',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Other',
                        'value' => 'other',
                        'photo' => '',
                    ],
                ],
            ] ),

            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'     => __( 'Comments', 'contactum' ),
                'name'      => 'comment',
            ] ),
        ];

        return $form_fields;
    }
}
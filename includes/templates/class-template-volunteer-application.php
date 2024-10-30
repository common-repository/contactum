<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;
/**
 * Blank form template
 */
class Template_Volunteer_Application extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Volunteer Application Form', 'contactum' );
        $this->description = __( 'Get volunteer applications easily and find out which days your volunteers want to work according to their particular interests.', 'contactum' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/volunteer-application.png';
        $this->category    = 'application';
    }

    /**
     * Get the form fields
     *
     * @return array
     */
    public function get_form_fields() {
        $all_fields = $this->get_register_fields();
        $form_fields    = [
            array_merge( $all_fields['section_break']->get_field_props(), [
              'label'       => __( 'Volunteer Application', 'contactum' ),
              'description' => ' ',
            ] ),

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
                'required'  => 'yes',
                'label'     => __( 'Email Address', 'contactum' ),
                'name'      => 'email',
            ] ),

            array_merge( $all_fields['phone_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Phone Number', 'contactum' ),
                'name'      => 'phone_number',
            ] ),

            array_merge( $all_fields['address_field']->get_field_props(), [
                'required'  => 'yes',
                'label'     => __( 'Address', 'contactum' ),
                'name'      => 'address',
            ] ),

            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'     => __( 'Skillsets or Area of Interests', 'contactum' ),
                'name'      => 'area_of_interests',
            ] ),

            array_merge( $all_fields['checkbox_field']->get_field_props(), [
                'label'     => __( 'Days of Work', 'contactum' ),
                'name'      => 'days_of_work',
                'options'   => [
                    [
                        'value' => 'Monday',
                        'label' => 'monday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Tuesday',
                        'label' => 'tuesday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Wednesday',
                        'label' => 'wednesday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Thursday',
                        'label' => 'thursday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Friday',
                        'label' => 'friday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Sunday',
                        'label' => 'sunday',
                        'photo' => '',
                    ],
                    [
                        'value' => 'Satarday',
                        'label' => 'satarday',
                        'photo' => '',
                    ]
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

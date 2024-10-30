<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;


/**
 * Event registration form template
 */
class Template_Event_Registration extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Event Registration', 'contactum' );
        $this->description = __( 'Get your visitors to register for an upcoming event quickly with this registration form template.', 'contactum' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/event.png';
        $this->category    = 'event';
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
            array_merge( $all_fields['phone_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Phone', 'contactum' ),
                'name'     => 'phone',
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'label' => __( 'Company', 'contactum' ),
                'name'  => 'compnay',
            ] ),
            array_merge( $all_fields['url_field']->get_field_props(), [
                'required'    => 'no',
                'label'       => __( 'Website', 'contactum' ),
                'name'        => 'website',
                'placeholder' => 'https://',
            ] ),
            array_merge( $all_fields['radio_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Have you attended before?', 'contactum' ),
                'name'     => 'attended_before',
                'inline'   => 'no',
                'options'  => [
                    [
                        'label' => 'Yes',
                        'value' => 'yes',
                        'photo' => '',
                    ],
                    [
                        'label' => 'No',
                        'value' => 'no',
                        'photo' => '',
                    ],
                ],
            ] ),
            array_merge( $all_fields['checkbox_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Dietary Requirements', 'contactum' ),
                'name'     => 'dietary_requirements',
                'inline'   => 'no',
                'options'  => [
                    [
                        'label' => 'None',
                        'value' => 'none',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Vegeterian',
                        'value' => 'vegeterian',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Vegan',
                        'value' => 'vegan',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Lactose-free',
                        'value' => 'lactose-free',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Gluten-free',
                        'value' => 'gluten-free',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Kosher',
                        'value' => 'kosher',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Halal',
                        'value' => 'halal',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Allergies-other',
                        'value' => 'allergies-Other',
                        'photo' => '',
                    ]
                ],
            ] ),
            array_merge( $all_fields['radio_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => 'Do you require any special assistance?',
                'name'     => 'special_assistance',
                'inline'   => 'no',
                'options'  => [
                    [
                        'label' => 'Yes',
                        'value' => 'yes',
                        'photo' => '',
                    ],
                    [
                        'label' => 'No',
                        'value' => 'no',
                        'photo' => '',
                    ]
                ],
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'required' => 'no',
                'label'    => __( 'Comments or Questions', 'contactum' ),
                'name'     => 'comments',
            ] ),
        ];

        return $form_fields;
    }

    /**
     * Get the form settings
     *
     * @return array
     */
    public function get_form_settings() {
        $defaults = parent::get_form_settings();

        return array_merge( $defaults, [
            'message'     => __( 'Thanks for signing up! We will get in touch with you shortly.', 'contactum' ),
            'submit_text' => __( 'Register', 'contactum' ),
        ] );
    }
}

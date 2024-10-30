<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;

/**
 * Contactum form template
 */
class Template_Conference_Proposal extends Contactum_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = true;
        $this->title       = __( 'Conference Proposal', 'contactum' );
        $this->description = __( 'A winning conference in any industry demands the highest quality candidates and presentations.', 'contactum' );
        $this->image       = CONTACTUM_ASSETS . '/images/form-template/conference-proposal.png';
        $this->category    = 'request';
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
                    "sub" => "Firstname" 
                ], 
                "middle_name" => [
                    "message" => "This field is required", 
                    "show_field" => false, 
                    "required" => false, 
                    "label" => "Middlename", 
                    "placeholder" => "", 
                    "default" => "", 
                    "sub" => "Middlename" 
                ], 
                "last_name" => [
                    "message" => "This field is required",
                    "show_field" => true, 
                    "required" => true, 
                    "label" => "Lastname",
                    "placeholder" => "", 
                    "default" => "", 
                    "sub" => "Lastname" 
                ]
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'label'          => __( 'Job Title', 'contactum' ),
                'name'           => 'job_title',
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'label'          => __( 'Company  Name', 'contactum' ),
                'name'           => 'company_name',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'         => __( 'Biography', 'contactum' ),
                'name'          => 'biography',
            ] ),
            array_merge( $all_fields['email_field']->get_field_props(), [
                'required' => 'yes',
                'label'    => __( 'Email Address', 'contactum' ),
                'name'     => 'email_address',
            ] ),
            array_merge( $all_fields['phone_field']->get_field_props(), [
                'label'    => __( 'Phone Number', 'contactum' ),
                'name'     => 'phone_number',
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'label'    => __( 'Proposal Title', 'contactum' ),
                'name'     => 'proposals_title',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'         => __( 'Short Description', 'contactum' ),
                'name'          => 'short_description',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'         => __( 'Abstract', 'contactum' ),
                'name'          => 'abstract',
            ] ),
            array_merge( $all_fields['checkbox_field']->get_field_props(), [
                'label'    => 'Topics',
                'name'     => 'topics',
                'options'  => [
                    [
                        'label' => 'Topic First',
                        'value' => 'first_topic',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Topic Second',
                        'value' => 'second_topic',
                        'photo' => '',
                    ],
                    [
                        'label' => 'Topic Third',
                        'value' => 'third_topic',
                        'photo' => '',
                    ]
                ],
            ] ),
            array_merge( $all_fields['dropdown_field']->get_field_props(), [
                'label'    => 'Session Type',
                'name'     => 'session_type',
                'options'  => [
                    [
                        'label' => 'presentation',
                        'value' => 'Presentation',
                    ],
                    [
                        'label' => 'panel',
                        'value' => 'Panel',
                    ],
                    [
                        'label' => 'workshop',
                        'value' => 'WorkShop',
                    ],
                                        [
                        'label' => 'other',
                        'value' => 'other',
                    ]
                ],
            ] ),
            array_merge( $all_fields['dropdown_field']->get_field_props(), [
                'label'    => 'Audience Level',
                'name'     => 'audience_level',
                'options'  => [
                    [
                        'label' => 'novice',
                        'value' => 'Novice',
                    ],
                    [
                        'label' => 'intermediate',
                        'value' => 'Intermediate',
                    ],
                    [
                        'label' => 'expert',
                        'value' => 'Expert',
                    ]
                ],
            ] ),
            array_merge( $all_fields['text_field']->get_field_props(), [
                'label'    => 'Video URL',
                'name'     => 'video_url',
            ] ),
            array_merge( $all_fields['textarea_field']->get_field_props(), [
                'label'    => __( 'Additional Information', 'contactum' ),
                'name'     => 'additional_information',
            ] ),
        ];

        return $form_fields;
    }
}

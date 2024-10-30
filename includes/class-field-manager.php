<?php
namespace Contactum;

use Contactum\Fields\Field_Email;
use Contactum\Fields\Field_Name;
use Contactum\Fields\Field_Text;
use Contactum\Fields\Field_Textarea;
use Contactum\Fields\Field_Radio;
use Contactum\Fields\Field_Checkbox;
use Contactum\Fields\Field_Date;
use Contactum\Fields\Field_Dropdown;
use Contactum\Fields\Field_MultiDropdown;
use Contactum\Fields\Field_Url;
use Contactum\Fields\Field_Number;
use Contactum\Fields\Field_Image;
use Contactum\Fields\Field_Html;
use Contactum\Fields\Field_SectionBreak;
use Contactum\Fields\Field_Hidden;
use Contactum\Fields\Field_Recaptcha;
use Contactum\Fields\Field_Country;
use Contactum\Fields\Field_Address;
use Contactum\Fields\Field_Math_Captcha;
use Contactum\Fields\Field_Phone;
use Contactum\Fields\Field_Toc;
use Contactum\Fields\Field_Hook;
use Contactum\Fields\Field_File;
use Contactum\Fields\Field_Submit;
use Contactum\Fields\Field_Passsword;

class FieldManager {

	private $fields = [];

	public function getFields() {
        if ( !empty( $this->fields ) ) {
            return $this->fields;
        }

        $this->register_field_types();

        return $this->fields;
    }

	public function getField( $field_type ) {
		$fields = $this->getFields();

		if ( array_key_exists( $field_type, $fields ) ) {
            return $fields[ $field_type ];
        }

        return false;
	}

	private function register_field_types() {
        $fields = [
            'email_field'    => new Field_Email(),
            'name_field'     => new Field_Name(),
            'text_field'     => new Field_Text(),
            'textarea_field' => new Field_Textarea(),
            'radio_field'    => new Field_Radio(),
            'checkbox_field' => new Field_Checkbox(),
            'date_field'     => new Field_Date(),
            'dropdown_field' => new Field_Dropdown(),
            'url_field'      => new Field_Url(),
            'number_field'   => new Field_Number(),
            'image_field'    => new Field_Image(),
            'multiple_select'=> new Field_MultiDropdown(),
            'html_field'     => new Field_Html(),
            'section_break'  => new Field_SectionBreak(),
            'hidden_field'   => new Field_Hidden(),
            'recaptcha'      => new Field_Recaptcha(),
            'country_field'  => new Field_Country(),
            'address_field'  => new Field_Address(),
            'math_captcha'   => new Field_Math_Captcha(),
            'phone_field'    => new Field_Phone(),
            'toc'            => new Field_Toc(),
            'hook_field'     => new Field_Hook(),
            'file_field'     => new Field_File(),
            'submit_field'   => new Field_Submit(),
            'password_field' => new Field_Passsword()

        ];

        $this->fields = apply_filters( 'contactum_form_fields', $fields );
	}

	public function get_field_groups() {
        $before_custom_fields = apply_filters( 'contactum_form_fields_section_before', [] );
        $groups               = array_merge( $before_custom_fields, $this->get_custom_fields() );
        $groups               = array_merge( $groups, $this->get_others_fields() );
        $after_custom_fields  = apply_filters( 'contactum_form_fields_section_after', [] );
        $groups               = array_merge( $groups, $after_custom_fields );

        return $groups;
    }

    private function get_custom_fields() {
        $fields = apply_filters( 'contactum_form_fields_custom_fields', [
                'text_field',
                'textarea_field',
                'name_field',
                'email_field',
                'checkbox_field',
                'radio_field',
                'date_field',
                'dropdown_field',
                'url_field',
                'number_field',
                'image_field',
                'multiple_select',
                'hidden_field',
                'country_field',
                'address_field',
                'math_captcha',
                'phone_field',
                'file_field',
                'submit_field',
                'password_field'
            ]
        );

        return [
            [
                'title'     => __( 'Custom Fields', 'contactum' ),
                'id'        => 'custom-fields',
                'fields'    => $fields,
                'show'      => true
            ],
        ];
    }

    private function get_others_fields() {
        $fields = apply_filters( 'contactum_form_fields_others_fields', [
            'section_break',
            'html_field',
            'recaptcha',
            'toc',
            // 'hook_field'
        ] );

        return [
            [
                'title'     => __( 'Others', 'contactum' ),
                'id'        => 'others',
                'fields'    => $fields,
                'show'      => true
            ],
        ];
    }

    public function get_js_settings() {
        $fields   = $this->getFields();

        $js_array = [];

        if ( $fields ) {
            foreach ( $fields as $type => $object ) {
                if ( is_object( $object ) ) {
                    $js_array[ $type ] = $object->get_js_settings();
                }
            }
        }

        return $js_array;
    }

    public function render_fields( $fields, $form_id, $atts = [] ) {
        if ( empty( $fields ) ) {
            return;
        }

        foreach ($fields as $field ) {
            if ( !$field_object = $this->getField( $field['template'] ) ) {
                continue;
            }

            $field_object->render( $field, $form_id );
        }
    }

    public function hassubmit_fields( $fields, $form_id, $atts = [] ) {
        if ( empty( $fields ) ) {
            return false;
        }

        foreach ($fields as $field ) {
            if( $field['template'] == 'submit_field') {
                return true;
            }
        }

        return false;
    }

}
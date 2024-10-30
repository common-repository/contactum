<?php
namespace Contactum\Fields\Traits;

trait Textoption {

    public static function get_default_text_option_settings( $word_restriction = false ) {
        $properties = [
            [
                'name'      => 'placeholder',
                'title'     => __( 'Placeholder text', 'contactum' ),
                'type'       => 'text',
                'tag_filter' => 'no_fields',
                'section'    => 'advanced',
                'priority'   => 10,
                'help_text'  => __( 'Text for HTML5 placeholder attribute', 'contactum' ),
            ],

            [
                'name'       => 'default',
                'title'      => __( 'Default value', 'contactum' ),
                'type'       => 'text',
                'tag_filter' => 'no_fields',
                'section'    => 'advanced',
                'priority'   => 11,
                'help_text'  => __( 'The default value this field will have', 'contactum' ),
            ],
            [
                'name'      => 'size',
                'title'     => __( 'Size', 'contactum' ),
                'type'      => 'text',
                'variation' => 'number',
                'section'   => 'advanced',
                'priority'  => 20,
                'help_text' => __( 'Size of this input field', 'contactum' ),
            ],
        ];

        if ( $word_restriction ) {
            $properties[] = [
                'name'      => 'word_restriction',
                'title'     => __( 'Word Restriction', 'contactum' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 15,
                'help_text' => __( 'Numebr of words the author to be restricted in', 'contactum' ),
            ];
        }

        return apply_filters( 'contactum-form-builder-common-text-fields-properties', $properties );
    }
}

trait DropDownOption {

    public function get_default_option_dropdown_settings( $is_multiple = false ) {
        return [
            'name'          => 'options',
            'title'         => __( 'Options', '' ),
            'type'          => 'option_data',
            'is_multiple'   => $is_multiple,
            'section'       => 'basic',
            'priority'      => 12,
            'help_text'     => __( 'Add options for the form field', '' ),
        ];
    }
}

trait TextareaOption {
    public function get_default_textarea_option_settings() {
        return [
            [
                'name'      => 'rows',
                'title'     => __( 'Rows', 'contactum' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 10,
                'help_text' => __( 'Number of rows in textarea', 'contactum' ),
            ],

            [
                'name'      => 'cols',
                'title'     => __( 'Columns', 'contactum' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 11,
                'help_text' => __( 'Number of columns in textarea', 'contactum' ),
            ],

            [
                'name'         => 'placeholder',
                'title'        => __( 'Placeholder text', 'contactum' ),
                'type'         => 'text',
                'section'      => 'advanced',
                'priority'     => 12,
                'help_text'    => __( 'Text for HTML5 placeholder attribute', 'contactum' ),
                'dependencies' => [
                    'rich' => 'no',
                ],
            ],

            [
                'name'      => 'default',
                'title'     => __( 'Default value', 'contactum' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 13,
                'help_text' => __( 'The default value this field will have', 'contactum' ),
            ]
        ];
    }
}
<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

class Field_Submit extends Contactum_Field {

	public function __construct() {
        $this->name       = __( 'Submit', 'contactum' );
        $this->input_type = 'submit_field';
        $this->icon       = 'address-card-o';
    }

    public function render( $field_settings, $form_id ) {
            // print_r($field_settings);
           
            $backgrounColor   = $field_settings['settings']['normal_border_color'];
            $borderColor   = $field_settings['settings']['normal_border_color'];
            $borderRadius  = $field_settings['settings']['normal_border_radius'];
            $fontColor     = $field_settings['settings']['normal_font_color'];
            $borderRadius  = $field_settings['settings']['normal_border_radius'];
            $minwidth      = $field_settings['settings']['normal_minwidth'];

            $hoverbackgrounColor = $field_settings['settings']['hover_border_color'];
            $hoverborderColor    = $field_settings['settings']['hover_border_color'];
            $hoverborderRadius   = $field_settings['settings']['hover_border_radius'];
            $hoverfontColor      = $field_settings['settings']['hover_font_color'];
            $hoverminwidth       = $field_settings['settings']['hover_minwidth'];


            $style = 'background-color:'. $backgrounColor . '; border: 1px solid '. $borderColor. '; border-radius:'. $borderRadius . 'px; color:'. $fontColor .'; min-width:'. $minwidth;
            // echo $style;
?>

        <div class="submit_wrapper">
            <?php esc_attr( wp_nonce_field( 'contactum_form_frontend' ) ); ?>
            <input type="hidden" name="action" value="contactum_frontend_submit">
            <input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
            <input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
            <input 
                type="submit" 
                style="<?php echo $style;?>"
                class="btn-submit contactum_submit_btn  contactum_submit_<?php echo esc_attr( $form_id ); ?>"
                value="<?php echo $field_settings['button_text']; ?>"
            />
        </div>

        <?php     
            //    onmouseover="changeColor(this)"     
            $script = "
                function changeColor(element) {
                    element.style.color = '${hoverfontColor}';
                    element.style.backgroundColor = '${hoverbackgrounColor}';
                    element.style.borderColor = '${hoverborderColor}';
                    element.style.borderRadius = '${hoverborderRadius}px';
                    element.style.minWidth = '${hoverminwidth}';
                }";

            // wp_add_inline_script( 'contactum-frontend', $script );
    }

    public function get_options_settings() {
    	$options = [
            
            [
                'name'      => 'button_text',
                'title'     => __( 'Submit Button Text', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 10,
                'help_text' => __( 'Enter a title of this field', 'contactum' ),
            ],

            [
                'name'      => 'button_size',
                'title'     => __( 'Button Size', 'contactum' ),
                'type'      => 'radio',
                'options'   => [
                    'small'     => __( 'Small', 'contactum' ),
                    'medium'    => __( 'Medium', 'contactum' ),
                    'large'     => __( 'Large', 'contactum' ),
                ],
                'section'   => 'basic',
                'priority'  => 21,
                'default'   => 'default',
                'inline'    => true,
            ],

            [
                'name'      => 'button_style',
                'title'     => __( 'Button Style', 'contactum' ),
                'type'      => 'selectbtnstyle',
                'options'   => [
                    'default'   => __( 'Default', 'contactum' ),
                    'nostyle'   => __( 'No Style', 'contactum' ),
                    'red'       => __( 'Red', 'contactum' ),
                    'green'     => __( 'Green', 'contactum' ),
                    'orange'    => __( 'Orange', 'contactum' ),
                    'gray'      => __( 'Gray', 'contactum' ),
                    'custom'    => __( 'Custom', 'contactum' ),
                ],
                'current_state' => "normal",
                'settings' => [
                    [
                        'name'      => 'normal_background_color',
                        'title'     => __( 'Normal Background Color', 'contactum' ),
                        'type'      => 'color',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
                    [
                        'name'      => 'normal_border_color',
                        'title'     => __( 'Normal Border Color', 'contactum' ),
                        'type'      => 'color',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
        
                    [
                        'name'      => 'normal_border_radius',
                        'title'     => __( 'Normal Border Radius', 'contactum' ),
                        'type'      => 'text',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
                    [
                        'name'      => 'normal_font_color',
                        'title'     => __( 'Normal Font Color', 'contactum' ),
                        'type'      => 'color',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
                    [
                        'name'      => 'normal_minwidth',
                        'title'     => __( 'Normal Minimum Width', 'contactum' ),
                        'type'      => 'text',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
        
                    [
                        'name'      => 'hover_background_color',
                        'title'     => __( 'Hover Background Color', 'contactum' ),
                        'type'      => 'color',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
                    [
                        'name'      => 'hover_border_color',
                        'title'     => __( 'Hover Border Color', 'contactum' ),
                        'type'      => 'color',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
        
                    [
                        'name'      => 'hover_border_radius',
                        'title'     => __( 'Hover Border Radius', 'contactum' ),
                        'type'      => 'text',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
        
                    [
                        'name'      => 'hover_font_color',
                        'title'     => __( 'Hover Font Color', 'contactum' ),
                        'type'      => 'text',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ],
        
                    [
                        'name'      => 'hover_minwidth',
                        'title'     => __( 'Hover Minimum Width', 'contactum' ),
                        'type'      => 'text',
                        'section'   => 'basic',
                        'priority'  => 22,
                        'help_text' => __( '' ),
                    ]
                ],
                'section'   => 'basic',
                'priority'  => 21,
                'default'   => 'large',
                'inline'    => true,
            ],

            [
                'name'      => 'alignment',
                'title'     => __( 'Alignment', 'contactum' ),
                'type'      => 'radio',
                'options'   => [
                    'left'     => __( 'Left', 'contactum' ),
                    'center'   => __( 'Center', 'contactum' ),
                    'right'    => __( 'Right', 'contactum' ),
                ],
                'section'   => 'basic',
                'priority'  => 21,
                'default'   => 'default',
                'inline'    => true,
            ],

            [
                'name'      => 'css',
                'title'     => __( 'CSS Class Name', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 22,
                'help_text' => __( '' ),
            ],
        ];

    	return $options;
    }

    public function get_field_props() {
        
    	$props = [
            'template'     => $this->get_type(),
            'name'         => '',
            'label'        => $this->get_name(),
            'button_text'  => 'Submit Form',
            'button_size'  => 'medium',
            'button_style' => 'default',
            'alignment'    => 'left',
            'css'          => '',
            'currentState' => 'normal',
            'settings'     => [
                'normal_background_color' => "#1a7efb",
                'normal_border_color'     => "#1a7efb",
                'normal_border_radius' => 5,
                'normal_font_color' => '#ffffff',
                'normal_minwidth' => "20%",

                'hover_background_color' => "#ffffff",
                'hover_border_color' => "#1a7efb",
                'hover_border_radius' => 5,
                'hover_font_color' => "#1a7efb",
                'hover_minwidth' => "20%",
            ],
            'is_new' => true,
        ];
    	
    	return  $props;
    }
}
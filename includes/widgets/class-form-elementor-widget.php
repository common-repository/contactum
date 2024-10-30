<?php
namespace Contactum\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use Elementor\Group_Control_Background;
use \Elementor\Scheme_Color;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ContactumElementorFormWidget extends Widget_Base {

    public function get_name() {
        return 'contactum-form-widget';
    }

    public function get_title() {
        return __( 'Contactum Form', 'contactum' );
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_keywords() {
        return [
            'contactumform',
            'contactumforms',
            'contactum form',
            'contactum forms',
            'contactum form',
            'form',
            'elementor form',
        ];
    }

    public function get_categories() {
        return array('general');
    }

    public function get_style_depends() {
        return [];
    }

    public function get_script_depends() {
        return [ 'contactumform-elementor'];
    }

    protected function _register_controls()
    {
        $all_forms = contactum()->forms->all();
        $forms = wp_list_pluck( $all_forms['forms'], 'name', 'id' );

        $this->start_controls_section(
            'section_contactum_form',
            [
                'label' => __('Contactum Form', 'contactum'),
            ]
        );


        $this->add_control(
            'form_list',
            [
                'label' => esc_html__('Contactum Form', 'contactum'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => $forms,
                'default' => '0',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fields_style',
            [
                'label' => __( 'Form Fields', 'contactum' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'large_field_width',
                [
                    'label' => __( 'Large Field Width', '' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'default' => [
                        'unit' => '%',
                        'size' => 99
                    ],
                    'range' => [
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                        'px' => [
                            'min' => 1,
                            'max' => 800,
                        ],
                    ],
                    'selectors' => [
                    '{{WRAPPER}} .contactum-form > li.contactum-el.field-size-large > .contactum-fields input:not([type=radio]):not([type=checkbox])' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .contactum-form > li.contactum-el.field-size-large > .contactum-fields textarea' => 'width: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'field_margin',
                [
                    'label' => __( 'Field Spacing', '' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .contactum-el:not(.contactum-submit)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'field_padding',
                [
                    'label' => __( 'Padding', '' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                    '{{WRAPPER}} .contactum-fields input:not(.contactum_submit_btn)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'field_border_radius',
                [
                    'label' => __( 'Border Radius', '' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                    '{{WRAPPER}} .contactum-fields input:not(.contactum_submit_btn), {{WRAPPER}} .contactum-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography',
                    'label' => __( 'Typography', '' ),
                    'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:not(.contactum_submit_btn), .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3
                ]
            );

        $this->add_control(
            'field_textcolor',
            [
                'label' => __( 'Field Text Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_placeholder_color',
            [
                'label' => __( 'Field Placeholder Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} ::-moz-placeholder'            => 'color: {{VALUE}};',
                    '{{WRAPPER}} ::-ms-input-placeholder'       => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_field_state' );

        $this->start_controls_tab(
            'tab_field_normal',
            [
                'label' => __( 'Normal', '' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_box_shadow',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea',
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => __( 'Background Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_field_focus',
            [
                'label' => __( 'Focus', '' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_focus_border',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:focus:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_focus_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:focus:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea:focus',
            ]
        );

        $this->add_control(
            'field_focus_bg_color',
            [
                'label' => __( 'Background Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields input:focus:not(.contactum_submit_btn), {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-fields textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'contactum-form-label',
            [
                'label' => __( 'Form Fields Label', '' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __( 'Margin', '' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .contactum-label label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', '' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                     '{{WRAPPER}} .contactum-label label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr3',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Label Typography', '' ),
                'selector' => '{{WRAPPER}} .contactum-label label, {{WRAPPER}} .contactum-form-sub-label',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => __( 'Help Text Typography', '' ),
                'selector' => '{{WRAPPER}} .contactum-fields .contactum-help',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Text Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                     '{{WRAPPER}} .contactum-label label, {{WRAPPER}} .contactum-form-sub-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'requered_label',
            [
                'label' => __( 'Required Label Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                     '{{WRAPPER}} .contactum-label .required' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Help Text Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-fields .contactum-help' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'submit',
            [
                'label' => __( 'Submit Button', '' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'submit_btn_width',
            [
                'label' => __( 'Button Full Width?', '' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', '' ),
                'label_off' => __( 'No', '' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __( 'Button Width', '' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'submit_btn_width' => 'yes'
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit .contactum_submit_btn' => 'display: block; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_btn_position',
            [
                'label' => __( 'Button Position', '' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', '' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', '' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', '' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'submit_btn_width' => ''
                ],
                'desktop_default' => 'left',
                'toggle' => false,
                'prefix_class' => 'ha-form-btn--%s',
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit' => 'text-align: {{Value}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_margin',
            [
                'label' => __( 'Margin', '' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_padding',
            [
                'label' => __( 'Padding', '' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                 '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typography',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_border',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]',

            ]
        );

        $this->add_control(
            'submit_border_radius',
            [
                'label' => __( 'Border Radius', '' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_box_shadow',
                'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]',

            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'submit_text_shadow',
                 'selector' => '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]',
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', '' ),
            ]
        );

        $this->add_control(
            'submit_color',
            [
                'label' => __( 'Text Color', '' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                     '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_control(
            'submit_bg_color',
            [
                'label' => __( 'Background Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', '' ),
            ]
        );

        $this->add_control(
            'submit_hover_color',
            [
                'label' => __( 'Text Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:hover, {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_bg_color',
            [
                'label' => __( 'Background Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:hover, {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_hover_border_color',
            [
                'label' => __( 'Border Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:hover, {{WRAPPER}} .contactum-form-add.contactum-style ul.contactum-form .contactum-submit input[type=submit]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_break',
            [
                'label' => __( 'Section Break', '' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'break_title_typography',
                'label' => __( 'Title Typography', '' ),
                'selector' => '{{WRAPPER}} .section_break .contactum-section-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'break_description_typography',
                'label' => __( 'Description Typography', '' ),
                'selector' => '{{WRAPPER}} .section_break .contactum-section-details',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4
            ]
        );

        $this->start_controls_tabs( 'tabs_section_break_style' );

        $this->start_controls_tab(
            'tab_break_title',
            [
                'label' => __( 'Title', '' ),
            ]
        );

        $this->add_control(
            'break_title_color',
            [
                'label' => __( 'Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section_break .contactum-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_break_description',
            [
                'label' => __( 'Description', '' ),
            ]
        );

        $this->add_control(
            'break_description_color',
            [
                'label' => __( 'Color', '' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section_break .contactum-section-details' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        extract($settings);

        if ( ! empty( $form_list ) ) {
            echo do_shortcode('[contactum id="' . $form_list . '"]');
        }
    }
    protected function _content_template() {}
}

<?php
/**
 * This class is a wrapper for all Elementor custom Widgets defined in this module
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

/**
 * Class Elementor_Base
 */
abstract class Elementor_Widget_Base extends Widget_Base {

	/**
	 * Form strings.
	 *
	 * @var array
	 */
	public $strings = array();

	/**
	 * All the widgets that extends this class have the same category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return ['obfx-elementor-widgets'];
	}

	/**
	 * All the widgets that extends this class have the same Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-text-align-left';
	}

	/**
	 * Register the controls for each Elementor Widget.
	 */
	protected function _register_controls() {
		$this->register_form_fields();
		$this->register_settings_controls();
		$this->register_style_controls();
		$this->add_widget_specific_settings();
	}

	/**
	 * This function registers the Repeater Control that adds fields in the form.
	 */
	private function register_form_fields() {

		$this->start_controls_section(
			$this->form_type . '_form_fields',
			array(
				'label' => __( 'Fields', 'textdomain' )
			)
		);
//
//		$repeater = new Repeater();
//
//		$repeater->add_control(
//			'requirement',
//			array(
//				'label'        => __( 'Required', 'textdomain' ),
//				'type'         => Controls_Manager::SWITCHER,
//				'return_value' => 'required',
//				'default'      => '',
//			)
//		);
//
//		$field_types = array(
//			'text'     => __( 'Text', 'textdomain' ),
//			'password' => __( 'Password', 'textdomain' ),
//			'email'    => __( 'Email', 'textdomain' ),
//			'textarea' => __( 'Textarea', 'textdomain' ),
//		);
//
//		$repeater->add_control(
//			'type',
//			array(
//				'label'   => __( 'Type', 'textdomain' ),
//				'type'    => Controls_Manager::SELECT,
//				'options' => $field_types,
//				'default' => 'text'
//			)
//		);
//
//		$repeater->add_control(
//			'key',
//			array(
//				'label' => __( 'Key', 'textdomain' ),
//				'type'  => Controls_Manager::HIDDEN
//			)
//		);
//
//		$repeater->add_responsive_control(
//			'field_width',
//			[
//				'label'   => __( 'Field Width', 'textdomain' ),
//				'type'    => Controls_Manager::SELECT,
//				'options' => [
//					'100' => '100%',
//					'75'  => '75%',
//					'66'  => '66%',
//					'50'  => '50%',
//					'33'  => '33%',
//					'25'  => '25%',
//				],
//				'default' => '100',
//			]
//		);
//
//		$repeater->add_control(
//			'label',
//			array(
//				'label'   => __( 'Label', 'textdomain' ),
//				'type'    => Controls_Manager::TEXT,
//				'default' => '',
//			)
//		);
//
//		$repeater->add_control(
//			'placeholder',
//			array(
//				'label'   => __( 'Placeholder', 'textdomain' ),
//				'type'    => Controls_Manager::TEXT,
//				'default' => '',
//			)
//		);
//
//		$this->add_specific_fields_for_repeater( $repeater );
//
//		$default_fields = $this->get_default_config();
//		$this->add_control(
//			'form_fields',
//			array(
//				'label'       => __( 'Form Fields', 'textdomain' ),
//				'type'        => Controls_Manager::REPEATER,
//				'show_label'  => false,
//				'separator'   => 'before',
//				'fields'      => array_values( $repeater->get_controls() ),
//				'default'     => $default_fields,
//				'title_field' => '{{{ label }}}',
//			)
//		);



		$this->add_specific_form_fields();
		$this->end_controls_section();
	}

	/**
	 * Add widget specific settings.
	 *
	 * @return mixed
	 */
	abstract function add_widget_specific_settings();

	/**
	 * Get form fields default values.
	 *
	 * @return array
	 */
	abstract function get_default_config();

	/**
	 * Add more fields in the repeater configuration.
	 *
	 * @var Object $repeater Repeater instance.
	 */
	abstract function add_specific_fields_for_repeater( $repeater );


	/**
	 * Add Widget specific form fields.
	 */
	abstract function add_specific_form_fields();

	/**
	 * Register form setting controls.
	 */
	private function register_settings_controls(){

		$this->start_controls_section(
			'contact_form_settings',
			array(
				'label' => __( 'Form Settings', 'textdomain' ),
			)
		);

		$this->add_specific_settings_controls();

		$this->add_control(
			'hide_label',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Hide Label', 'textdomain' ),
				'return_value' => 'hide',
				'default'      => '',
				'separator'   => 'before'
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add widget specific settings controls.
	 */
	abstract function add_specific_settings_controls();

	/**
	 * Add style controls.
	 *
	 * @access protected
	 * @return void
	 * @since 1.0,0
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Form', 'textdomain' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'textdomain' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-column'          => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .content-form .submit-form' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'textdomain' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-column'          => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .content-form .submit-form' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label'     => __( 'Label', 'textdomain' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label'     => __( 'Spacing', 'textdomain' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} fieldset > label'       => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} fieldset > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} fieldset > label'           => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __( 'Text Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > label, {{WRAPPER}} .elementor-field-subgroup label' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label'     => __( 'Mark Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .required-mark' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} fieldset > label',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Field', 'textdomain' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} fieldset > input, {{WRAPPER}} fieldset select, {{WRAPPER}} fieldset > textarea, {{WRAPPER}} fieldset > button',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_responsive_control(
			'align_field_text',
			[
				'label'     => __( 'Text alignment', 'textdomain' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'left',
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'textdomain' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'textdomain' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'textdomain' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} fieldset > input'    => 'text-align: {{VALUE}}',
					'{{WRAPPER}} fieldset select'    => 'text-align: {{VALUE}}',
					'{{WRAPPER}} fieldset > textarea' => 'text-align: {{VALUE}}'
				],
			]
		);

		$this->add_responsive_control(
			'field-text-padding', [
				'label'      => __( 'Text Padding', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} fieldset > input'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset select'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset > textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_field_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			[
				'label' => __( 'Normal', 'textdomain' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => __( 'Text Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > input'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > input::placeholder'    => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset select'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset select::placeholder'    => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea'              => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea::placeholder' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);


		$this->add_control(
			'field_background_color',
			[
				'label'     => __( 'Background Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} fieldset > input'    => 'background-color: {{VALUE}};',
					'{{WRAPPER}} fieldset select'    => 'background-color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label'     => __( 'Border Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > input'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} fieldset select'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_style',
			[
				'label'     => _x( 'Border Type', 'Border Control', 'textdomain' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => __( 'None', 'textdomain' ),
					'solid'  => _x( 'Solid', 'Border Control', 'textdomain' ),
					'double' => _x( 'Double', 'Border Control', 'textdomain' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'textdomain' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'textdomain' ),
					'groove' => _x( 'Groove', 'Border Control', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} fieldset > input'    => 'border-style: {{VALUE}};',
					'{{WRAPPER}} fieldset select'    => 'border-style: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea' => 'border-style: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label'       => __( 'Border Width', 'textdomain' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'placeholder' => '',
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}} fieldset > input'    => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset select'    => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset > textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label'      => __( 'Border Radius', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} fieldset > input'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset select'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset > textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
			[
				'label' => __( 'Focus', 'textdomain' ),
			]
		);

		$this->add_control(
			'field_focus_text_color',
			[
				'label'     => __( 'Text Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > input:focus'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > input::placeholder:focus'    => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset select:focus'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset select::placeholder:focus'    => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea:focus'              => 'color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea::placeholder:focus' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'field_focus_background_color',
			[
				'label'     => __( 'Background Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} fieldset > input:focus'    => 'background-color: {{VALUE}};',
					'{{WRAPPER}} fieldset select:focus'    => 'background-color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea:focus' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_focus_border_color',
			[
				'label'     => __( 'Border Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > input:focus'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} fieldset select:focus'    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea:focus' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_focus_border_style',
			[
				'label'     => _x( 'Border Type', 'Border Control', 'textdomain' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => __( 'None', 'textdomain' ),
					'solid'  => _x( 'Solid', 'Border Control', 'textdomain' ),
					'double' => _x( 'Double', 'Border Control', 'textdomain' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'textdomain' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'textdomain' ),
					'groove' => _x( 'Groove', 'Border Control', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} fieldset > input:focus'    => 'border-style: {{VALUE}};',
					'{{WRAPPER}} fieldset select:focus'    => 'border-style: {{VALUE}};',
					'{{WRAPPER}} fieldset > textarea:focus' => 'border-style: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'field_focus_border_width',
			[
				'label'       => __( 'Border Width', 'textdomain' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'placeholder' => '',
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}} fieldset > input:focus'    => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset select:focus'    => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset > textarea:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_focus_border_radius',
			[
				'label'      => __( 'Border Radius', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} fieldset > input:focus'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset select:focus'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} fieldset > textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'textdomain' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'textdomain' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} fieldset > button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} fieldset > button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} fieldset > button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} fieldset > button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} fieldset > button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label'      => __( 'Text Padding', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} fieldset > button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'textdomain' ),
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __( 'Text Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'textdomain' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} fieldset > button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {
		$form_id  = $this->get_data( 'id' );
		$settings = $this->get_settings();
		$fields   = array_key_exists( 'provider', $settings ) ? $settings[ $settings['provider'] . '_form_fields' ]: $settings['form_fields'];

		$this->maybe_load_widget_style();

		$this->render_form_header( $form_id );
		foreach ( $fields as $index => $field ) {
			$this->render_form_field( $field );
		}

		$btn_label = !empty( $settings['submit_label'] ) ? $settings['submit_label'] : esc_html__( 'Submit', 'textdomain' );
		echo '<fieldset class="submit-form ' . esc_attr( $this->form_type ) . '">';
		echo '<button type="submit" name="submit" value="submit-' . esc_attr( $this->form_type ) . '-' . esc_attr( $form_id ) . '" class="' . $this->get_render_attribute_string( 'button' ) . '">';
		echo esc_html( $btn_label );
		if ( ! empty( $settings['button_icon'] ) ) {
			echo '<span ' . $this->get_render_attribute_string( 'content-wrapper' ) . '>';
			echo '<span ' . $this->get_render_attribute_string( 'icon-align' ) . '>';
			echo '<i class="' . esc_attr( $settings['button_icon'] ) . '"></i>';
			echo '</span>';
			echo '</span>';
		}
		echo '</button>';
		echo '</fieldset>';
		$this->render_form_footer();
	}

	/**
	 * Either enqueue the widget style registered by the library
	 * or load an inline version for the preview only
	 *
	 * @return void
	 * @since 1.0.0
	 * @access protected
	 */
	protected function maybe_load_widget_style() {
		if ( Plugin::$instance->editor->is_edit_mode() === true && apply_filters( 'themeisle_content_forms_register_default_style', true ) ) {
			echo '<style>';
			echo file_get_contents( plugin_dir_path( TI_CONTENT_FORMS_FILE ) . '/assets/content-forms.css' );
			echo '</style>';
		} else {
			// if `themeisle_content_forms_register_default_style` is false, the style won't be registered anyway
			wp_enqueue_script( 'content-forms' );
			wp_enqueue_style( 'content-forms' );
		}
	}

	/**
	 * Display method for the form's header
	 * It is also takes care about the form attributes and the regular hidden fields
	 *
	 * @param string $id Form id.
	 */
	private function render_form_header( $id ) {
		// create an url for the form's action
		$url = admin_url( 'admin-post.php' );

		echo '<form action="' . esc_url( $url ) . '" method="post" name="content-form-' . esc_attr( $id ) . '" id="content-form-' . esc_attr( $id ) . '" class="content-form content-form-' . esc_attr( $this->form_type ) . ' ' . esc_attr( $this->get_name() ) . '">';

		wp_nonce_field( 'content-form-' . esc_attr( $id ), '_wpnonce_' . esc_attr( $this->form_type ) );

		echo '<input type="hidden" name="action" value="content_form_submit" />';
		echo '<input type="hidden" name="form-type" value="' . esc_attr( $this->form_type ) . '" />';
		echo '<input type="hidden" name="form-builder" value="elementor" />';
		echo '<input type="hidden" name="post-id" value="' . get_the_ID() . '" />';
		echo '<input type="hidden" name="form-id" value="' . esc_attr( $id ) . '" />';
	}

	/**
	 * Print the output of an individual field
	 *
	 * @param array $field Field settings.
	 * @param bool $is_preview Is preview flag.
	 */
	private function render_form_field( $field, $is_preview = false ) {

		$settings = $this->get_settings();
		$provider   = array_key_exists( 'provider', $settings ) ? $settings['provider']: '';
		$field_id      = $field['_id'];
		$key           = Elementor_Widget_Manager::get_field_key_name( $field, $provider );
		$key           = $key === 'ADDRESS' ? $key = 'ADDRESS[addr1]' : $key;
		$form_id       = $this->get_data( 'id' );
		$field_name    = 'data[' . $form_id . '][' . $key . ']';
		$disabled      = $is_preview ? 'disabled="disabled"' : '';
		$required      = $field['requirement'] === 'required' ? 'required="required"' : '';
		$placeholder   = ! empty( $field['placeholder'] ) ? $field['placeholder'] : '';


		$this->add_render_attribute( 'fieldset' . $field['_id'], 'class', 'content-form-field-' . $field['type'] );
		$this->add_render_attribute( 'fieldset' . $field['_id'], 'class', 'elementor-column elementor-col-' . $field['field_width'] );
		$this->add_render_attribute( [
			'icon-align' => [
				'class' => [
					empty( $instance['button_icon_align'] ) ? '' :
						'elementor-align-icon-' . $instance['button_icon_align'],
					'elementor-button-icon',
				],
		 	]
		] );

		echo '<fieldset ' . $this->get_render_attribute_string( 'fieldset' . $field_id ) . '>';
		$this->maybe_render_field_label( $field );

		switch ( $field['type'] ) {
			case 'textarea':
				echo '<textarea name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $disabled . ' ' . $required . ' placeholder="' . esc_attr( $placeholder ) . '" cols="30" rows="5"></textarea>';
				break;
			case 'password':
				echo '<input type="password" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $disabled . '>';
				break;
			default:
				echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $disabled . ' placeholder="' . esc_attr( $placeholder ) . '">';
				break;
		}
		echo '</fieldset>';

		$this->maybe_render_newsletter_address( $field, $is_preview );
	}

	/**
	 * When using MailChimp, additional fields are required for the address field/
	 *
	 * @param array $field Field data.
	 * @return bool
	 */
	private function maybe_render_newsletter_address( $field, $is_preview ){
		$settings      = $this->get_settings();
		if( ! array_key_exists('provider', $settings ) || $settings['provider'] !== 'mailchimp'  ){
			return false;
		}

		if( ! array_key_exists($settings['provider'] . '_field_map', $field ) || $field[$settings['provider'].'_field_map'] !== 'address'  ){
			return false;
		}

		$form_id    = $this->get_data( 'id' );
		$settings      = $this->get_settings();
		$display_label = $settings['hide_label'];
		$disabled      = $is_preview ? 'disabled="disabled"' : '';
		$required      = $field['requirement'] === 'required' ? 'required="required"' : '';


		$address_fields = array( 'addr2', 'city', 'state', 'zip', 'country');
		foreach ( $address_fields as $address_item ) {
			$field_name = 'data[' . $form_id . '][ADDRESS[' . $address_item . ']]';
			$this->add_render_attribute( 'fieldset' . $field['_id'] . $address_item, 'class', 'elementor-column elementor-col-' . $field[ $address_item . '_width' ] );


			echo '<fieldset class="elementor-field-group elementor-column elementor-col-' . $field[ $address_item . '_width' ] . '">';

			if ( $display_label !== 'hide' ) {
				echo '<label for="' . esc_attr( $field_name ) . '" >';
				echo wp_kses_post( $field[ $address_item . '_label' ] );
				if ( $field['requirement'] === 'required' ) {
					echo '<span class="required-mark"> *</span>';
				}
				echo '</label>';
			}

			if( $address_item === 'country' ){
				echo '<div class="elementor-select-wrapper">';
				echo '<select class="country" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $disabled . '><option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia, Plurinational State of</option><option value="BQ">Bonaire, Sint Eustatius and Saba</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, the Democratic Republic of the</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Côte d\'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curaçao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, Democratic People\'s Republic of</option><option value="KR">Korea, Republic of</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People\'s Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia, the former Yugoslav Republic of</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States of</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territory, Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Réunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena, Ascension and Tristan da Cunha</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin (French part)</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome and Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SX">Sint Maarten (Dutch part)</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="SS">South Sudan</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela, Bolivarian Republic of</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.S.</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>';
				echo '</div>';
			} else {
				echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $disabled . ' placeholder="' . esc_attr( $field[ $address_item . '_placeholder'] ) . '">';
			}

			echo '</fieldset>';
		}
		return true;
	}

	/**
	 * Maybe render field label
	 *
	 * @var array $field Field data.
	 *
	 * @return true
	 */
	private function maybe_render_field_label( $field ){

		if ( empty( $field['label'] ) ){
			return false;
		}

		$settings      = $this->get_settings();
		$display_label = $settings['hide_label'];
		if( $display_label === 'hide' ) {
			return false;
		}

		$settings = $this->get_settings();
		$provider   = array_key_exists( 'provider', $settings ) ? $settings['provider']: '';
		$field_id   = $field['_id'];
		$key        = Elementor_Widget_Manager::get_field_key_name( $field, $provider );
		$form_id    = $this->get_data( 'id' );
		$field_name = 'data[' . $form_id . '][' . $key . ']';


		echo '<label for="' . esc_attr( $field_name ) . '" ' . $this->get_render_attribute_string( 'label' . $field_id ) . '>';
			echo wp_kses_post( $field['label'] );
			if ( $field['requirement'] === 'required' ) {
				echo '<span class="required-mark"> *</span>';
			}
		echo '</label>';

		return true;
	}


	/**
	 * Display method for the form's footer
	 */
	private function render_form_footer() {
		echo '</form>';
	}
}

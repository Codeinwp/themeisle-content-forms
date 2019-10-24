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
	 * @var string
	 */
	public $submit_button_label = '';

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

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'textdomain' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'textdomain' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'requirement',
			array(
				'label'        => __( 'Required', 'textdomain' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'required',
				'default'      => '',
			)
		);

		$field_types = array(
			'text'     => __( 'Text', 'textdomain' ),
			'password' => __( 'Password', 'textdomain' ),
			'email'    => __( 'Email', 'textdomain' ),
			'textarea' => __( 'Textarea', 'textdomain' ),
		);

		$repeater->add_control(
			'type',
			array(
				'label'   => __( 'Type', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text'
			)
		);

		$repeater->add_control(
			'key',
			array(
				'label' => __( 'Key', 'textdomain' ),
				'type'  => Controls_Manager::HIDDEN
			)
		);

		$repeater->add_responsive_control(
			'field_width',
			[
				'label'   => __( 'Field Width', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'75'  => '75%',
					'66'  => '66%',
					'50'  => '50%',
					'33'  => '33%',
					'25'  => '25%',
				],
				'default' => '100',
			]
		);

		$this->add_specific_fields_for_repeater( $repeater );

		$default_fields = $this->get_default_config();
		$this->add_control(
			'form_fields',
			array(
				'label'       => __( 'Form Fields', 'textdomain' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => false,
				'separator'   => 'before',
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => $default_fields,
				'title_field' => '{{{ label }}}',
			)
		);

		$this->add_specific_form_fields();
		$this->end_controls_section();
	}

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

		$submit_default = ! empty( $this->submit_button_label ) ? $this->submit_button_label : esc_html__( 'Submit', 'textdomain' );
		$this->add_control(
			'submit_label',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Submit', 'textdomain' ),
				'default'     => $submit_default,
				'description' => esc_html__( 'The Call To Action label', 'textdomain' )
			)
		);

		$this->add_responsive_control(
			'align_submit',
			[
				'label'     => __( 'Alignment', 'textdomain' ),
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
					'{{WRAPPER}} .content-form .submit-form' => 'text-align: {{VALUE}};',
				],
			]
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
				'selector' => '{{WRAPPER}} fieldset > input, {{WRAPPER}} fieldset > textarea, {{WRAPPER}} fieldset > button',
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
		$fields   = $settings['form_fields'];

		$this->maybe_load_widget_style();

		$this->render_form_header( $form_id );
		foreach ( $fields as $index => $field ) {
			$this->render_form_field( $field );
		}

		$btn_label = !empty( $settings['submit_label'] ) ? $settings['submit_label'] : esc_html__( 'Submit', 'textdomain' );
		echo '<fieldset class="submit-form ' . esc_attr( $this->form_type ) . '">';
		echo '<button type="submit" name="submit" value="submit-' . esc_attr( $this->form_type ) . '-' . esc_attr( $form_id ) . '" class="' . $this->get_render_attribute_string( 'button' ) . '">';
		echo esc_html( $btn_label );
		if ( ! empty( $instance['button_icon'] ) ) {
			echo '<span ' . $this->get_render_attribute_string( 'content-wrapper' ) . '>';
			echo '<span ' . $this->get_render_attribute_string( 'icon-align' ) . '>';
			echo '<i class="' . esc_attr( $instance['button_icon'] ) . '"></i>';
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
			echo file_get_contents( plugin_dir_path( __FILE__ ) . '/assets/content-forms.css' );
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
		$item_index = $field['_id'];
		$key        = ! empty( $field['label'] ) ? sanitize_title( $field['label'] ) : ( ! empty( $field['placeholder'] ) ? sanitize_title( $field['placeholder'] ) : 'field_' . $item_index );
		if ( ! empty( $field['key'] ) ){
			$key = $field['key'];
		}
		$placeholder = ! empty( $field['placeholder'] ) ? $field['placeholder'] : '';

		$required = '';
		$form_id  = $this->get_data( 'id' );

		if ( $field['requirement'] === 'required' ) {
			$required = 'required="required"';
		}

		//in case this is a preview, we need to disable the actual inputs and transform the labels in inputs
		$disabled = '';
		if ( $is_preview ) {
			$disabled = 'disabled="disabled"';
		}

		$field_name = 'data[' . $form_id . '][' . $key . ']';

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

		$this->add_inline_editing_attributes( $item_index . '_label', 'none' );


		echo '<fieldset ' . $this->get_render_attribute_string( 'fieldset' . $field['_id'] ) . '>';

		echo '<label for="' . esc_attr( $field_name ) . '" ' . $this->get_render_attribute_string( 'label' . $item_index ) . '>';
		echo wp_kses_post( $field['label'] );
		if ( ! empty( $field['label'] ) && $field['requirement'] === 'required' ) {
			echo '<span class="required-mark"> *</span>';
		}
		echo '</label>';

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
	}

	/**
	 * Display method for the form's footer
	 */
	private function render_form_footer() {
		echo '</form>';
	}
}

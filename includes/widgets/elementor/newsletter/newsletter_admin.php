<?php
/**
 * Main class for Elementor Newsletter Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

/**
 * Class Newsletter_Admin
 */
class Newsletter_Admin extends Elementor_Widget_Base{

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'newsletter';

	/**
	 * Elementor Widget Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'content_form_newsletter';
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	function get_default_config() {
		return array();
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Newsletter Form', 'textdomain' );
	}

	/**
	 * Register Registration Form Widget Fields
	 */
	function _register_fields_controls() {
		$this->start_controls_section(
			'newsletter_form_fields',
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

		$this->add_control(
			'button_icon',
			[
				'label' => __( 'Submit Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'condition' => [
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button-icon' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Registration Form Widget Settings
	 */
	function _register_settings_controls() {
		$this->start_controls_section(
			'registration_form_settings',
			array(
				'label' => __( 'Form Settings', 'textdomain' ),
			)
		);

		$this->add_control(
			'provider',
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Subscribe to', 'textdomain' ),
				'description' => esc_html__( 'Where to send the email?', 'textdomain' ),
				'options'     => array(
					'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
					'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' )
				)
			)
		);

		$this->add_control(
			'access_key',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Access Key', 'textdomain' ),
				'description' => esc_html__( 'Provide an access key for the selected service', 'textdomain' ),
				'required' => true
			)
		);

		$this->add_control(
			'list_id',
			array(
				'type'  => 'text',
				'label' => esc_html__( 'List ID', 'textdomain' ),
				'description' => esc_html__( 'The List ID (based on the seleced service) where we should subscribe the user', 'textdomain' ),
				'required' => true
			)
		);

		$this->add_control(
			'submit_label',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Submit Label', 'textdomain' ),
				'default' => esc_html__( 'Join Newsletter', 'textdomain' ),
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




}

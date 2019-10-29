<?php
/**
 * Main class for Elementor Contact Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

/**
 * Class Contact_Admin
 */
class Contact_Admin extends Elementor_Widget_Base {

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'contact';

	/**
	 * Elementor Widget Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'content_form_contact';
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Contact Form', 'textdomain' );
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	public function get_default_config() {
		return array(
			array(
				'key'         => 'name',
				'type'        => 'text',
				'label'       => esc_html__( 'Name', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Name', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'phone',
				'type'        => 'number',
				'label'       => esc_html__( 'Phone', 'textdomain' ),
				'requirement' => 'optional',
				'placeholder' => esc_html__( 'Phone', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'message',
				'type'        => 'textarea',
				'label'       => esc_html__( 'Message', 'textdomain' ),
				'requirement' => 'required',
				'placeholder' => esc_html__( 'Message', 'textdomain' ),
				'field_width' => '100',
			),

		);
	}

	/**
	 * No other required fields for this widget.
	 */
	function add_specific_form_fields() {

		$repeater = new Repeater();

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
	}

	/**
	 * Add specific settings for Contact Widget.
	 */
	function add_specific_settings_controls() {

		$this->add_control(
			'success_message',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Success message', 'textdomain' ),
				'default'     => esc_html__( 'Your message has been sent!', 'textdomain' ),
			)
		);

		$this->add_control(
			'error_message',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Error message', 'textdomain' ),
				'default'     => esc_html__( 'Oops! I cannot send this email!', 'textdomain' ),
			)
		);

		$this->add_control(
			'to_send_email',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
			)
		);

		$this->add_control(
			'submit_label',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Submit', 'textdomain' ),
				'default'     => esc_html__( 'Submit', 'textdomain' ),
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
	}

	/**
	 * Add specific widget settings.
	 */
	function add_widget_specific_settings() {
		return false;
	}
}

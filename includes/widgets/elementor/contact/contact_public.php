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
 * Class Contact_Public
 */
class Contact_Public extends Elementor_Widget_Base {

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
	 * Get Widget Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-text-align-left';
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
	 * Specific fields for this type of form.
	 *
	 * @return bool
	 */
	public function add_widget_specific_fields() {
		return false;
	}

	/**
	 * Register Contact Form Widget Fields
	 */
	public function _register_fields_controls() {
		$this->start_controls_section(
			'contact_form_fields',
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

		$this->add_widget_specific_fields();

		$this->end_controls_section();
	}

	/**
	 * Register Contact Form Widget Controls
	 */
	public function _register_settings_controls(){
		$this->start_controls_section(
			'contact_form_settings',
			array(
				'label' => __( 'Form Settings', 'textdomain' ),
			)
		);

		$this->add_control(
			'to_send_email',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' )
			)
		);

		$this->add_control(
			'submit_label',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Submit', 'textdomain' ),
				'default'     => esc_html__( 'Submit', 'textdomain' ),
				'description' => esc_html__( 'The Call To Action label', 'textdomain' )
			)
		);

		$this->add_responsive_control(
			'align_submit',
			[
				'label'     => __( 'Alignment', 'elementor-addon-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'left',
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-addon-widgets' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'elementor-addon-widgets' ),
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

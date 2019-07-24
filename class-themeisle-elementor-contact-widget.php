<?php

namespace ThemeIsle\ContentForms;

class Eelementor_Contact_Widget extends ElementorWidget {

	public function __construct( $data = [], $args = null ) {
		parent::setup_attributes();
		parent::__construct( $data, $args );
	}

	public function set_form_type() {
		$this->form_type = 'contact';
	}

	public function set_form_configuration(){
		$this->forms_config = array(
			'fields' /* or form_fields? */ => array(
				'name'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Name' ),
					'default'     => esc_html__( 'Name' ),
					'placeholder' => esc_html__( 'Your Name' ),
					'require'     => 'required'
				),
				'email'   => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email' ),
					'default'     => esc_html__( 'Email' ),
					'placeholder' => esc_html__( 'Email address' ),
					'require'     => 'required'
				),
				'phone'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Phone' ),
					'default'     => esc_html__( 'Phone' ),
					'placeholder' => esc_html__( 'Phone Nr' ),
					'require'     => 'optional'
				),
				'message' => array(
					'type'        => 'textarea',
					'label'       => esc_html__( 'Message' ),
					'default'     => esc_html__( 'Message' ),
					'placeholder' => esc_html__( 'Your message' ),
					'require'     => 'required'
				)
			),

			'controls' /* or settings? */ => array(
				'to_send_email' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Send to', 'textdomain' ),
					'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
					'default'     => get_bloginfo( 'admin_email' )
				),
				'submit_label'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'default'     => esc_html__( 'Submit', 'textdomain' ),
					'description' => esc_html__( 'The Call To Action label', 'textdomain' )
				)
			)
		);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'content_form_contact';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Contact Form', 'textdomain' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-text-align-left';
	}

	public function add_additional_controls(){
		$this->add_submit_button_align();
	}

	/**
	 * Add alignment control for button
	 */
	public function add_submit_button_align() {
		$this->add_responsive_control(
			'align_submit',
			[
				'label' => __( 'Alignment', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .content-form .submit-form' => 'text-align: {{VALUE}};',
				],
			]
		);
	}
}

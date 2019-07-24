<?php
namespace ThemeIsle\ContentForms;

class Eelementor_Newsletter_Widget extends ElementorWidget {

	public function __construct( $data = [], $args = null ) {
		parent::setup_attributes();
		parent::__construct( $data, $args );
	}

	function set_form_type() {
		$this->form_type = 'newsletter';
	}

	function set_form_configuration() {
		$this->forms_config = array(
			'controls' => array(
				'provider'     => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Subscribe to', 'textdomain' ),
					'description' => esc_html__( 'Where to send the email?', 'textdomain' ),
					'options'     => array(
						'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
						'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' )
					)
				),
				'access_key'   => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Access Key', 'textdomain' ),
					'description' => esc_html__( 'Provide an access key for the selected service', 'textdomain' ),
					'required' => true
				),
				'list_id'      => array(
					'type'  => 'text',
					'label' => esc_html__( 'List ID', 'textdomain' ),
					'description' => esc_html__( 'The List ID (based on the seleced service) where we should subscribe the user', 'textdomain' ),
					'required' => true
				),
				'submit_label' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Submit Label', 'textdomain' ),
					'default' => esc_html__( 'Join Newsletter', 'textdomain' ),
				)
			),

			'fields' => array(
				'email' => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'default'     => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'require'     => 'required'
				)
			),

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
		return 'content_form_newsletter';
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
		return esc_html__( 'Newsletter Form', 'textdomain' );
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
		$this->add_form_alignment();
	}

	public function add_form_alignment() {
		$this->add_responsive_control(
			'align_submit',
			[
				'label' => __( 'Alignment', 'elementor-addon-widgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'elementor-addon-widgets' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .content-form.content-form-newsletter' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

}

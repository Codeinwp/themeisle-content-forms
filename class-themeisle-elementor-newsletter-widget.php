<?php
/**
 * Newsletter Form Elementor custom widget.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    ThemeIsle\ContentForms
 */
namespace ThemeIsle\ContentForms;

use Elementor\Controls_Manager;
use Exception;

/**
 * Class Elementor_Newsletter_Widget
 * @package ThemeIsle\ContentForms
 */
class Elementor_Newsletter_Widget extends ElementorWidget {

	/**
	 * Elementor_Newsletter_Widget constructor.
	 *
	 * @param array $data Widget data.
	 * @param array|null $args Widget arguments.
	 *
	 * @throws Exception
	 * @since 1.0.1
	 *
	 */
	public function __construct( $data = [], $args = null ) {
		parent::setup_attributes();
		try{
			parent::__construct( $data, $args );
		} catch ( Exception $exception ){
			error_log( $exception->getMessage() );
		}
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.1
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
	 * @since 1.0.1
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

	/**
	 * Set form type.
	 *
	 * @return void
	 * @since 1.0.1
	 * @access protected
	 */
	function set_form_type() {
		$this->form_type = 'newsletter';
	}

	/**
	 * Set form configuration.
	 *
	 * @return void
	 * @since 1.0.1
	 * @access protected
	 */
	protected function set_form_configuration() {
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
	 * Add widget specific controls.
	 *
	 * @return bool|void
	 * @since 1.0.1
	 * @access protected
	 */
	protected function add_widget_specific_controls(){
		$this->add_form_alignment();
	}

	/**
	 * Add form alignment.
	 *
	 * @access private
	 * @since 1.0.1
	 * @return void
	 */
	private function add_form_alignment() {
		$this->add_responsive_control(
			'align_submit',
			[
				'label' => __( 'Alignment', 'elementor-addon-widgets' ),
				'type' => Controls_Manager::CHOOSE,
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

	/**
	 * Add specific widget fields
	 *
	 * @since 1.0.1
	 * @access protected
	 * @return bool|void
	 */
	protected function add_widget_specific_fields(){
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
	}

}

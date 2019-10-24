<?php
/**
 * Main class for Elementor Newsletter Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use Elementor\Controls_Manager;
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
	 * Change the value for submit button label.
	 *
	 * @var string
	 */
	public $submit_button_label;

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
		return array(
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
			)
		);
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
	 * Additional fields for repeater.
	 * // TODO: Add a dorpdown to map the exact fields in mailchimp and send in blue
	 *
	 * @param Object $repeater Repeater instance.
	 *
	 * @return bool
	 */
	function add_specific_fields_for_repeater( $repeater ) {
		return false;
	}

	/**
	 * Add specific form fields for Newsletter widget.
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {
		$this->submit_button_label = esc_html__( 'Join Newsletter', 'textdomain' );

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

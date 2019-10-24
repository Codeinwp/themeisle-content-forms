<?php
/**
 * Main class for Elementor Registration Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

/**
 * Class Registration_Admin
 *
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration
 */
class Registration_Admin extends Elementor_Widget_Base{

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'registration';

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
		return 'content_form_registration';
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Registration Form', 'textdomain' );
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	function get_default_config() {
		return array(
			array(
				'key'         => 'username',
				'type'        => 'text',
				'label'       => esc_html__( 'User Name', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'User Name', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'password',
				'type'        => 'password',
				'label'       => esc_html__( 'Password', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Password', 'textdomain' ),
				'field_width' => '100',
			)
		);
	}

	/**
	 * Add repeater fields.
	 *
	 * @param Object $repeater Repeater instance.
	 * @return bool
	 */
	function add_specific_fields_for_repeater( $repeater ) {
		return false;
	}

	/**
	 * Add specific form fields for Registration Widget.
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {
		$this->submit_button_label = esc_html__( 'Register', 'textdomain' );
	}
}

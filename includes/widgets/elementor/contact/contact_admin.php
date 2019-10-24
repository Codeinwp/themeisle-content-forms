<?php
/**
 * Main class for Elementor Contact Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact;

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
	 * No need to add repeater fields.
	 *
	 * @param Object $repeater Repeater instance.
	 *
	 * @return bool
	 */
	function add_specific_fields_for_repeater( $repeater ) {
		return false;
	}

	/**
	 * No other required fields for this widget.
	 *
	 * @return bool
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Contact Widget.
	 */
	function add_specific_settings_controls() {
		$this->add_control(
			'to_send_email',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
			)
		);
	}
}

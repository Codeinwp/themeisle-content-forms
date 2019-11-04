<?php
/**
 * Beaver Contact Widget main class.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

require_once 'beaver_widget_base.php';

/**
 * Class Contact_Admin
 * @package ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver
 */
class Contact_Admin extends Beaver_Widget_Base {


	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_widget_name() {
		return esc_html__( 'Contact Form', 'textdomain' );
	}

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'contact';
	}

	/**
	 * Set default values for registration widget.
	 *
	 * @return array
	 */
	public function widget_default_values() {
		return array(
			'fields'          => array(
				array(
					'key'         => 'name',
					'label'       => esc_html__( 'Name', 'textdomain' ),
					'placeholder' => esc_html__( 'Name', 'textdomain' ),
					'type'        => 'text',
					'field_width' => '100',
					'required'    => 'required',
				),
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'field_width' => '100',
					'required'    => 'required',
				),
				array(
					'key'         => 'phone',
					'label'       => esc_html__( 'Phone', 'textdomain' ),
					'placeholder' => esc_html__( 'Phone', 'textdomain' ),
					'type'        => 'number',
					'field_width' => '100',
					'required'    => 'optional',
				),
				array(
					'key'         => 'message',
					'label'       => esc_html__( 'Message', 'textdomain' ),
					'placeholder' => esc_html__( 'Message', 'textdomain' ),
					'type'        => 'textarea',
					'field_width' => '100',
					'required'    => 'required',
				),
			),
			'submit_label'    => esc_html__( 'Submit', 'textdomain' ),
			'success_message' => esc_html__( 'Your message has been sent!', 'textdomain' ),
			'error_message'   => esc_html__( 'Oops! I cannot send this email!', 'textdomain' ),
		);
	}

	/**
	 * Contact_Admin constructor.
	 */
	public function __construct() {
		$this->run_hooks();
		parent::__construct(
			array(
				'name'        => esc_html__( 'Contact', 'textdomain' ),
				'description' => esc_html__( 'A contact form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
	}

	/**
	 * Add specific controls for this type of widget.
	 *
	 * @param array $fields Fields config.
	 *
	 * @return array
	 */
	public function add_widget_specific_controls( $fields ) {
		$fields = array(
			'success_message' => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Success message', 'textdomain' ),
				'default' => $this->get_default( 'success_message' ),
			),
			'error_message'   => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Error message', 'textdomain' ),
				'default' => $this->get_default( 'error_message' ),
			),
			'to_send_email' => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' ),
			),
		) + $fields;
		return $fields;
	}

	/**
	 * Add widget repeater fields specific for contact widget.
	 *
	 * @param array $fields Widget fields.
	 *
	 * @return array
	 */
	function add_widget_repeater_fields( $fields ) {
		return $fields;
	}
}

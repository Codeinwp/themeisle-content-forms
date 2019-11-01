<?php

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

require_once 'beaver_widget_base.php';

class Contact_Admin extends Beaver_Widget_Base {


	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_widget_name(){
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
	 * Get default form data.
	 *
	 * @param string $field Field name.
	 * @return array | string | bool
	 */
	public function get_default( $field ){
		$default = array(
			'fields' => array(
				array(
					'key'         => 'name',
					'label'       => esc_html__( 'Name', 'textdomain' ),
					'placeholder' => esc_html__( 'Name', 'textdomain' ),
					'type'        => 'text',
					'field_width' => '100',
					'required' => 'required',
				),
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'field_width' => '100',
					'required' => 'required',
				),
				array(
					'key'         => 'phone',
					'label'       => esc_html__( 'Phone', 'textdomain' ),
					'placeholder' => esc_html__( 'Phone', 'textdomain' ),
					'type'        => 'number',
					'field_width' => '100',
					'required' => 'optional',
				),
				array(
					'key'         => 'message',
					'label'       => esc_html__( 'Message', 'textdomain' ),
					'placeholder' => esc_html__( 'Message', 'textdomain' ),
					'type'        => 'textarea',
					'field_width' => '100',
					'required' => 'required',
				)
			),
			'submit_label' => esc_html__( 'Submit', 'textdomain' ),
			'success_message' => esc_html__( 'Your message has been sent!', 'textdomain' ),
			'error_message' => esc_html__( 'Oops! I cannot send this email!', 'textdomain' ),
		);

		if( array_key_exists( $field, $default ) ){
			return $default[$field];
		}
		return false;
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
	 * Run hooks and filters.
	 */
	private function run_hooks(){
		add_filter( $this->get_type() . '_controls_fields', array( $this, 'add_widget_specific_controls'));
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
			'to_send_email' =>  array(
				'type'        => 'text',
				'label'       => esc_html__( 'Send to', 'textdomain' ),
				'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
				'default'     => get_bloginfo( 'admin_email' )
			)
		) + $fields;
		return $fields;
	}
}

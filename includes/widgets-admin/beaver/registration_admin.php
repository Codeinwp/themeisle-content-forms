<?php

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

use ThemeIsle\ContentForms\Form_Manager;

require_once 'beaver_widget_base.php';


class Registration_Admin extends Beaver_Widget_Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	function get_widget_name() {
		return esc_html__( 'Registration Form', 'textdomain' );
	}

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'registration';
	}

	/**
	 * Set default values for registration widget.
	 *
	 * @return array
	 */
	public function widget_default_values() {
		return array(
			'fields'       => array(
				array(
					'key'         => 'username',
					'label'       => esc_html__( 'User Name', 'textdomain' ),
					'placeholder' => esc_html__( 'User Name', 'textdomain' ),
					'type'        => 'text',
					'field_width' => '100',
					'required'    => 'required',
					'field_map'   => 'user_login',
				),
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'field_width' => '100',
					'required'    => 'required',
					'field_map'   => 'user_email',
				),
				array(
					'key'         => 'password',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'password',
					'field_width' => '100',
					'required'    => 'required',
					'field_map'   => 'user_pass',
				),
			),
			'submit_label' => esc_html__( 'Register', 'textdomain' ),
		);
	}

	/**
	 * Registration_Admin constructor.
	 */
	public function __construct() {
		$this->run_hooks();
		parent::__construct(
			array(
				'name'        => esc_html__( 'Registration', 'textdomain' ),
				'description' => esc_html__( 'A sign up form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
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

	/**
	 * Add specific controls for this type of widget.
	 *
	 * @param array $fields Fields config.
	 *
	 * @return array
	 */
	function add_widget_specific_controls( $fields ) {
		$roles = Form_Manager::get_user_roles();
		if ( ! current_user_can( 'manage_options' ) ) {
			return $fields;
		}
		$fields = array(
			'user_role' => array(
				'type'    => 'select',
				'label'   => __( 'Register user as:', 'textdomain' ),
				'default' => 'subscriber',
				'options' => $roles,
			),
		) + $fields;

		return $fields;
	}
}

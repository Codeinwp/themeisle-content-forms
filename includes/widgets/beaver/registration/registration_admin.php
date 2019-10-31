<?php

namespace ThemeIsle\ContentForms\Includes\Widgets\Beaver\Registration;

use ThemeIsle\ContentForms\Includes\Widgets\Beaver\Beaver_Widget_Base;

class Registration_Admin extends Beaver_Widget_Base {

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'registration';
	}

	public function __construct( $data = array(), $args = null ) {

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
}

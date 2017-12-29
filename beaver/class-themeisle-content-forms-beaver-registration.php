<?php

namespace ThemeIsle\ContentForms;

class BeaverModuleRegistration extends BeaverModule {

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
				'description'   => __( 'A sign up form.', 'textdomain' ),
				'category'      => __( 'OrbitFox Modules', 'textdomain' ),
				'dir'           => dirname( __FILE__ ),
				'url'           => plugin_dir_url( __FILE__ )
			)
		);
	}
}
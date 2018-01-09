<?php

namespace ThemeIsle\ContentForms;

class BeaverModuleContact extends BeaverModule {

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'contact';
	}

	public function __construct() {

		parent::__construct(
			array(
				'name'        => esc_html__( 'Contact', 'textdomain' ),
				'description' => esc_html__( 'A contact form.', 'textdomain' ),
				'category'    => esc_html__( 'OrbitFox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ )
			)
		);
	}

}
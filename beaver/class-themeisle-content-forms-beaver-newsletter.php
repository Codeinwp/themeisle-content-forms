<?php

namespace ThemeIsle\ContentForms;

class BeaverModuleNewsletter extends BeaverModule {

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'newsletter';
	}

	public function __construct( $data = array(), $args = null ) {

		parent::__construct(
			array(
				'name'        => esc_html__( 'Newsletter', 'textdomain' ),
				'description' => esc_html__( 'A simple newsletter form.', 'textdomain' ),
				'category'    => esc_html__( 'OrbitFox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ )
			)
		);
	}
}
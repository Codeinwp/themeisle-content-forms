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
				'description'   => __( 'A simple newsletter form.', 'textdomain' ),
				'category'      => __( 'OrbitFox Modules', 'textdomain' ),
				'dir'           => dirname( __FILE__ ),
				'url'           => plugin_dir_url( __FILE__ )
			)
		);
	}
}
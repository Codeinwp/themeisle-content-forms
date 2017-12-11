<?php

namespace ThemeIsle\ContentForms;

/**
 *
 * Class ContentForms_Base
 * @package ThemeIsle
 */
class ContentForm {
	private $config;

	private $name;

	public function __construct( $name, $config ) {
		/**
		 * @TODO These setups will be in separate method. They are called here only for dev purposes
		 */
		$this->set_config( $config );
		$this->hooks();
	}

	function hooks() {
		// Register the Elementor Widget
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widget' ) );
		// Register the Beaver Module
		// Register the Gutenberg Block
	}

	private function set_config( $config ) {
		$this->config = $config;
	}

	public function register_elementor_widget() {
		// @TODO https://docs.elementor.com/article/92-forms

		// We check if the Elementor plugin has been installed / activated.
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			require_once( __DIR__ . '/class-themeisle-content-forms-elementor.php' );

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type(
				new \ThemeIsle\ContentForms\ElementorWidget(
					array(
						'id' => 'content_form_contact',
						'content_forms_config',
						$this->config
					),
					array()
				)
			);
		}

	}

	private function add_elementor_field( $args ) {

	}

	private function register_beaver_module() {
		// TODO https://www.wpbeaverbuilder.com/custom-module-documentation/
	}

	private function add_beaver_field( $args ) {

	}

	private function register_gutenberg_block() {
		//@TODO https://github.com/WordPress/gutenberg-examples/tree/master/04-controls
	}

	// prolly this will be in js
	private function add_gutenberg_field( $args ) {

	}
}
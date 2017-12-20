<?php

namespace ThemeIsle\ContentForms;

/**
 * Class ContentForm
 * @package ThemeIsle\ContentForms
 */
class ContentForm {

	/**
	 * Holds the shape of the content form, names, details and fields structure.
	 * @var array $config
	 */
	private $config;

	/**
	 * The content form type.
	 * Currently the possible values are: `contact`,`newsletter` and `registration`
	 * @var string $type
	 */
	private $type;

	public function __construct( $type, $config ) {
		/**
		 * @TODO These setups will be in separate method. They are called here only for dev purposes
		 */
		$this->set_config( $config );
		$this->set_type( $type );
		$this->hooks();
	}

	/**
	 * Map the registration actions
	 */
	function hooks() {
		// Register the Elementor Widget
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widget' ) );
		// Register the Beaver Module
		// @TODO
		// Register the Gutenberg Block
		$this->register_gutenberg_block();

	}

	/**
	 * Elementor widget registration
	 */
	public function register_elementor_widget() {
		// @TODO https://docs.elementor.com/article/92-forms

		// We check if the Elementor plugin has been installed / activated.
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			require_once( __DIR__ . '/class-themeisle-content-forms-elementor.php' );

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type(
				new \ThemeIsle\ContentForms\ElementorWidget(
					array(
						'id'                   => 'content_form_' . $this->get_type(),
						'content_forms_config' => $this->get_config()
					),
					array(
						'content_forms_config' => $this->get_config()
					)
				)
			);
		}
	}

	private function register_beaver_module() {
		// TODO https://www.wpbeaverbuilder.com/custom-module-documentation/
	}

	/**
	 * Gutenberg block registration
	 */
	private function register_gutenberg_block() {

		if ( in_array( 'gutenberg/gutenberg.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			require_once( __DIR__ . '/class-themeisle-content-forms-gutenberg.php' );

			$module = new \ThemeIsle\ContentForms\GutenbergModule(
				array(
					'id'                   => 'content_form_' . $this->get_type(),
					'type'                 => $this->get_type(),
					'content_forms_config' => $this->get_config()
				)
			);
		}
	}

	/**
	 * Setter method for the form type
	 *
	 * @param $type
	 */
	private function set_type( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter method for the form type
	 * @return string
	 */
	private function get_type() {
		return $this->type;
	}

	/**
	 * Setter method for the config property
	 *
	 * @param $config
	 */
	private function set_config( $config ) {
		$this->config = $config;
	}

	/**
	 * Getter method for the config property
	 * @return mixed
	 */
	private function get_config() {
		return $this->config;
	}

}
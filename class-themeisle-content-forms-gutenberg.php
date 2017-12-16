<?php

namespace ThemeIsle\ContentForms;

/**
 * This class is used to create a Gutenberg block based on a ContentForms config
 * Class ContentFormsGutenbergModule
 * @TODO This is a work in progress and for now we will start from the basisc example of a Gutenberg block.
 */
class GutenbergModule {

	private $name;

	private $form_type;

	private $title;

	private $icon;

	private $forms_config = array();

	public function __construct( $data ) {

		$this->setup_attributes( $data );
		add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_enqueue_block_editor_assets' ) );
//		add_action( 'enqueue_block_assets', array( $this, 'gutenberg_enqueue_block_assets' ) );
	}

	/**
	 * This method takes the given attributes and sets them as properties
	 *
	 * @param $data array
	 */
	private function setup_attributes( $data ) {

		$this->form_type = $data['type'];

		if ( ! empty( $data['content_forms_config'] ) ) {
			$this->forms_config = $data['content_forms_config'];
		} else {
			$this->forms_config = apply_filters( 'content_forms_config_for_' . $this->form_type, $this->forms_config );
		}

		if ( ! empty( $data['id'] ) ) {
			$this->set_name( $data['id'] );
		}

		if ( ! empty( $this->forms_config['title'] ) ) {
			$this->set_title( $this->forms_config['title'] );
		}

		if ( ! empty( $this->forms_config['icon'] ) ) {
			$this->set_icon( $this->forms_config['icon'] );
		}
	}

	/**
	 * Load our block generator once but for each type of form we need to localize the config
	 */
	function gutenberg_enqueue_block_editor_assets() {

		if ( ! wp_script_is( 'gutenberg-content-forms' ) ) {

			wp_enqueue_script(
				'gutenberg-content-forms',
				plugins_url( './assets/gutenberg-esnext/block.build.js', __FILE__ ),
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'underscore' ),
				filemtime( plugin_dir_path( __FILE__ ) . './assets/gutenberg-esnext/block.build.js' )
			);
		}

		wp_localize_script(
			'gutenberg-content-forms',
			'content_forms_config_for_' . $this->form_type,
			$this->forms_config
		);
	}

	function gutenberg_enqueue_block_assets() {
		wp_enqueue_style(
			'gutenberg-examples-05',
			plugins_url( 'style.css', __FILE__ ),
			array( 'wp-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
		);
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the widget name property
	 */
	private function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Set the widget title property
	 */
	private function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * Retrieve content form widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Set the widget title property
	 */
	private function set_icon( $icon ) {
		$this->icon = $icon;
	}
}
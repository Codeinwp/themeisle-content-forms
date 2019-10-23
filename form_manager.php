<?php
/**
 * The main class for Content Forms Module
 */

namespace ThemeIsle\ContentForms;

use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Manager;
use ThemeIsle\ContentForms\Includes\Admin\Server;

/**
 * Class Form_Manager
 *
 * @package ThemeIsle\ContentForms
 */
class Form_Manager{

	/**
	 * Initialization function.
	 */
	public function init(){
		$this->load_hooks();
		$this->make();
	}

	/**
	 * Load hooks and filters.
	 */
	private function load_hooks(){
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$rest_server = new Server();
		$rest_server->register_hooks();
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts(){

		wp_register_script( 'content-forms', plugins_url( '/assets/content-forms.js', TI_CONTENT_FORMS_FILE ), array( 'jquery' ), TI_CONTENT_FORMS_VERSION, true );

		wp_localize_script( 'content-forms', 'contentFormsSettings', array(
			'restUrl' => esc_url_raw( rest_url() . 'content-forms/v1/' ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
		) );

		/**
		 * Use this filter to force the js loading on all pages.
		 * Otherwise, it will be loaded only if a content form is present
		 */
		if ( apply_filters( 'themeisle_content_forms_force_js_enqueue', false ) ) {
			wp_enqueue_script( 'content-forms' );
		}

		/**
		 * Every theme with a better form style can disable the default content forms styles by returning a false
		 * to this filter `themeisle_content_forms_register_default_style`.
		 */
		if ( true === apply_filters( 'themeisle_content_forms_register_default_style', true ) ) {
			wp_register_style( 'content-forms', plugins_url( '/assets/content-forms.css', TI_CONTENT_FORMS_FILE ), array(), TI_CONTENT_FORMS_VERSION );
		}
	}

	/**
	 * Load Elementor, Beaver or other widgets manager class.
	 */
	private function make() {
		$elementor_manager = new Elementor_Widget_Manager();
		$elementor_manager->init();
	}

}

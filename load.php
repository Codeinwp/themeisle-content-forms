<?php
/**
 * Loader for the ThemeIsle\ContentForms feature
 *
 * @package     ThemeIsle\ContentForms
 * @copyright   Copyright (c) 2017, Andrei Lupu
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.1.0
 */

if ( ! function_exists( 'themeisle_content_forms_load' ) ) :

	function themeisle_content_forms_load() {
		$path = dirname( __FILE__ );

		// @TODO we should autoload these
		// get each form's class
		 require_once $path . '/class-themeisle-content-forms-server.php';

		$server = new \Themeisle\ContentForms\RestServer();
		$server->init();

		 require_once $path . '/class-themeisle-content-form.php';
		 require_once $path . '/class-themeisle-content-forms-contact.php';
		 require_once $path . '/class-themeisle-content-forms-newsletter.php';
		 require_once $path . '/class-themeisle-content-forms-registration.php';

		 do_action('init_themeisle_content_forms');
	}
endif;
add_action( 'init', 'themeisle_content_forms_load' );

function themeisle_content_forms_register_public_assets() {
	wp_register_script( 'content-forms', plugins_url( '/assets/content-forms.js', __FILE__ ), array( 'jquery' ) );

	wp_localize_script( 'content-forms', 'contentFormsSettings', array(
		'restUrl' => esc_url_raw( rest_url() . 'content-forms/v1/' ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	) );

	/**
	 * Use this filter to force the js loading on all pages.
	 * Otherwise, it will be loaded only if a content form is present
	 */
	if ( apply_filters( 'themeisle_content_forms_force_js_enqueue', false ) ) {
		wp_enqueue_script( 'content-forms' );
	}
}
add_action( 'wp_enqueue_scripts', 'themeisle_content_forms_register_public_assets' );
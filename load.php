<?php
/**
 * Loader for the ThemeIsle\ContentForms feature
 *
 * @package     ThemeIsle\ContentForms
 */



//
define( 'TI_CONTENT_FORMS_VERSION', '1.0.0' );
define( 'TI_CONTENT_FORMS_NAMESPACE', 'content-forms/v1');
define( 'TI_CONTENT_FORMS_FILE', __FILE__ );
define( 'TI_CONTENT_FORMS_PATH', dirname(__FILE__) );
define( 'TI_CONTENT_FORMS_DIR_PATH', dirname(__DIR__) );
//
/*
 * Load the necessary resource for this library
 */
function themeisle_content_forms_load() {

	require_once 'form_manager.php';
	require_once 'includes/admin/server.php';

	if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
		require_once 'includes/widgets/elementor/elementor_widget_manager.php';
		require_once 'includes/widgets/elementor/elementor_widget_base.php';
		require_once 'includes/widgets/elementor/elementor_widget_actions_base.php';
		require_once 'includes/widgets/elementor/contact/contact_public.php';
		require_once 'includes/widgets/elementor/contact/contact_admin.php';
		require_once 'includes/widgets/elementor/newsletter/newsletter_public.php';
		require_once 'includes/widgets/elementor/newsletter/newsletter_admin.php';
		require_once 'includes/widgets/elementor/registration/registration_public.php';
		require_once 'includes/widgets/elementor/registration/registration_admin.php';
	}
//
//
//
//
//	if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
//		// get builders generators
//		require_once TI_CONTENT_FOTMS_PATH . '/class-themeisle-content-forms-elementor.php';
//	}
//
//	if ( class_exists( '\FLBuilderModel' ) ) {
//		require_once TI_CONTENT_FOTMS_PATH . '/beaver/class-themeisle-content-forms-beaver-base.php';
//		require_once TI_CONTENT_FOTMS_PATH . '/beaver/class-themeisle-content-forms-beaver-contact.php';
//		require_once TI_CONTENT_FOTMS_PATH . '/beaver/class-themeisle-content-forms-beaver-newsletter.php';
//		require_once TI_CONTENT_FOTMS_PATH . '/beaver/class-themeisle-content-forms-beaver-registration.php';
//	}
//
//	// @TODO Gutenberg is not working yet
//	//require_once $path . '/class-themeisle-content-forms-gutenberg.php';
//
//	// get forms
//	require_once TI_CONTENT_FOTMS_PATH . '/class-themeisle-content-forms-contact.php';
//	require_once TI_CONTENT_FOTMS_PATH . '/class-themeisle-content-forms-newsletter.php';
//	require_once TI_CONTENT_FOTMS_PATH . '/class-themeisle-content-forms-registration.php';
//
//	/**
//	 * At this point all the PHP classes are available and the forms can be loaded
//	 */
//	do_action( 'init_themeisle_content_forms' );
//
//	// Register CSS & JS assets + localizations
//	add_action( 'wp_enqueue_scripts', 'themeisle_content_forms_register_public_assets' );
}
add_action( 'init', 'themeisle_content_forms_load', 9 );

//
//if ( ! function_exists( 'themeisle_content_forms_register_public_assets' ) ) :
//	/**
//	 * Register the library assets, they will be enqueue later by builders.
//	 * Also, localize REST params
//	 */
//	function themeisle_content_forms_register_public_assets() {
//		$version = '1.2.1'; // the current plugin version
//
//		wp_register_script( 'content-forms', plugins_url( '/assets/content-forms.js', __FILE__ ), array( 'jquery' ), $version, true );
//
//		wp_localize_script( 'content-forms', 'contentFormsSettings', array(
//			'restUrl' => esc_url_raw( rest_url() . 'content-forms/v1/' ),
//			'nonce'   => wp_create_nonce( 'wp_rest' ),
//		) );
//
//		/**
//		 * Use this filter to force the js loading on all pages.
//		 * Otherwise, it will be loaded only if a content form is present
//		 */
//		if ( apply_filters( 'themeisle_content_forms_force_js_enqueue', false ) ) {
//			wp_enqueue_script( 'content-forms' );
//		}
//
//		/**
//		 * Every theme with a better form style can disable the default content forms styles by returning a false
//		 * to this filter `themeisle_content_forms_register_default_style`.
//		 */
//		if ( true === apply_filters( 'themeisle_content_forms_register_default_style', true ) ) {
//			wp_register_style( 'content-forms', plugins_url( '/assets/content-forms.css', __FILE__ ), array(), $version );
//		}
//	}
//endif;
//
//// Run the show only for PHP 5.3 or highier
//if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {

//}

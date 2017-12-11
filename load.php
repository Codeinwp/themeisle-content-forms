<?php
/**
 * Loader for the ThemeIsleContentForms feature
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
		 require_once $path . '/class-themeisle-content-form.php';
		 require_once $path . '/class-themeisle-content-forms-contact.php';
		 require_once $path . '/class-themeisle-content-forms-newsletter.php';
		 require_once $path . '/class-themeisle-content-forms-registration.php';

		 do_action('init_themeisle_content_forms');
	}
endif;
add_action( 'init', 'themeisle_content_forms_load' );

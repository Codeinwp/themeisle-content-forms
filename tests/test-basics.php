<?php
/**
 * Basic Tests
 *
 * @package ThemeIsle\ContentForms
 */

/**
 * Test functions in register.php
 */
class Plugin_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		wp_set_current_user( $this->factory->user->create( [ 'role' => 'administrator' ] ) );

		do_action( 'init' );
		do_action( 'plugins_loaded' );
	}

	/**
	 * Tests test_library_availability().
	 *
	 * @covers test_library_availability
	 */
	function test_library_availability() {
		$this->assertTrue( class_exists( '\ThemeIsle\ContentForms\ContactForm') );
		$this->assertTrue( class_exists( '\ThemeIsle\ContentForms\NewsletterForm') );
		$this->assertTrue( class_exists( '\ThemeIsle\ContentForms\RegistrationForm') );
	}

	/**
	 * Tests scripts availability.
	 *
	 * @covers test_script_availability
	 */
	function test_script_availability() {

		// check if the function which should load assets exists
		$this->assertTrue( function_exists( 'themeisle_content_forms_register_public_assets' ) );

		// the content-forms scripts should not be enqueued before the aboce function is called
		$this->assertFalse( wp_script_is( 'content-forms' ) );

		// by default, front-end scripts are not enqueued globally. but we can do it for the sake of a test
		add_filter( 'themeisle_content_forms_force_js_enqueue', '__return_true' );

		themeisle_content_forms_register_public_assets();

		$this->assertTrue( wp_script_is( 'content-forms' ) );
	}
}
<?php
/**
 * Contact Form Tests
 *
 * @package ThemeIsle\ContentForms
 */

/**
 * Test functions in register.php
 */
class ContactFormTest extends WP_UnitTestCase {

	public $form = null;

	public function setUp() {
		parent::setUp();
		wp_set_current_user( $this->factory->user->create( [ 'role' => 'administrator' ] ) );

		$this->form = new \ThemeIsle\ContentForms\ContactForm();
	}

	/**
	 * Each form should have defined a type.
	 */
	function test_form_type() {
		$this->assertEquals( 'contact', $this->form->get_type() );
	}

	/**
	 * Make sure that the form has a config defined.
	 */
	function test_if_config_exists() {
		$this->assertTrue( method_exists( $this->form, 'make_form_config' ) );
	}
}
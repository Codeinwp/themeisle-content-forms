<?php
/**
 * Contact Form Tests
 *
 * @package ThemeIsle\ContentForms
 */

/**
 * Test functions in register.php
 */
class NewsletterFormTest extends WP_UnitTestCase {

	public $form = null;

	public function setUp() {
		parent::setUp();
		wp_set_current_user( $this->factory->user->create( [ 'role' => 'administrator' ] ) );

		$this->form = \ThemeIsle\ContentForms\NewsletterForm::instance();
	}

	/**
	 * Each form should have defined a type.
	 */
	function test_form_type() {
		$this->assertEquals( 'newsletter', $this->form->get_type() );
	}

	/**
	 * Make sure that the form has a config defined.
	 */
	function test_if_config_exists() {
		$this->assertTrue( method_exists( $this->form, 'make_form_config' ) );
	}

	/**
	 * The `rest_submit_form` form is required.
	 */
	function test_if_rest_callback_exists() {
		$this->assertTrue( method_exists( $this->form, 'rest_submit_form' ) );
	}

	/**
	 * Every config must have a these keys
	 */
	function test_if_config_is_valid() {
		$config = $this->form->get_config();

		$this->assertArrayHasKey( 'id', $config );
		$this->assertArrayHasKey( 'title', $config );
		$this->assertArrayHasKey( 'icon', $config );
		$this->assertArrayHasKey( 'fields', $config );
		$this->assertArrayHasKey( 'controls', $config );
	}

	/**
	 * If a request to `rest_submit_form` lacks data the method should warn about email.
	 */
	function test_an_empty_submit() {

		$return = $this->form->rest_submit_form(
			array(),
			array(),
			1,
			1,
			'builder'
		);

		$this->assertEquals( $return, array(
			'msg' => 'Invalid email.'
		) );
	}
}
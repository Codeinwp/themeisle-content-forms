<?php
/**
 * Basic Tests
 *
 * @package ThemeIsle\ContentForms
 */

/**
 * Test functions in register.php
 */
class RestServerTest extends WP_UnitTestCase {

	public $server = null;
	private $nonce = null;

	public function setUp() {
		parent::setUp();
		wp_set_current_user( $this->factory->user->create( [ 'role' => 'administrator' ] ) );

		$this->server = \Themeisle\ContentForms\RestServer::instance();

		$this->nonce = wp_create_nonce( 'content-form-1' );

		do_action( 'rest_api_init' );
		do_action( 'init' );
		do_action( 'plugins_loaded' );
	}

	public function test_form_submission() {

		// @TODO try to mock a request
		$request = new WP_REST_Request( 'POST', '/content-forms/v1/check', array(
			'args' => array( 'nonce' => $this->nonce ),
			'form_id' => '1',
			'post_id' => '1'
		) );

		$this->assertTrue( method_exists( $this->server, 'submit_form' ) );
		// @TODO try to actually test the method call
	}

	/**
	 * Test the right type of class instance
	 */
	public function test_getInstance() {
		$this->assertInstanceOf( '\Themeisle\ContentForms\RestServer', \Themeisle\ContentForms\RestServer::$instance );
	}

	/**
	 * @expectedIncorrectUsage __clone
	 */
	public function test_Clone() {
		$obj_cloned = clone \Themeisle\ContentForms\RestServer::$instance;
	}

	/**
	 * @expectedIncorrectUsage __wakeup
	 */
	public function test_Wakeup() {
		unserialize( serialize( \Themeisle\ContentForms\RestServer::$instance ) );
	}
}
<?php

namespace ThemeIsle\ContentForms;

/**
 * Class RestServer
 *
 */
class RestServer extends \WP_Rest_Controller {

	public $namespace = 'content-forms/';
	public $version = 'v1';

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$namespace = $this->namespace . $this->version;

		register_rest_route( $namespace, '/check', array(
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'rest_check' )
			),
		) );

		register_rest_route( $namespace, '/submit', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'submit_form' ),
				'permission_callback' => array( $this, 'submit_forms_permissions_check' ),
				'args'                => array(
					'form_type' => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'What type of form is submitted.', 'textdomain' ),
					),
					'nonce'     => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The security key', 'textdomain' ),
					),
					'data'      => array(
						'type'        => 'json',
						'required'    => true,
						'description' => __( 'The form must have data', 'textdomain' ),
					),
					'form_id'   => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The form identifier.', 'textdomain' ),
					),
					'post_id'   => array(
						'type'        => 'string',
						'required'    => true,
						'description' => __( 'The form identifier.', 'textdomain' ),
					)
				),
			),
		) );
	}

	public function rest_check( \WP_REST_Request $request ) {
		return rest_ensure_response( 'success' );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function submit_form( \WP_REST_Request $request ) {
		$return = array(
			'success' => false,
			'msg'     => 'Something went wrong'
		);

		$nonce   = $request->get_param( 'nonce' );
		$form_id = $request->get_param( 'form_id' );
		$post_id = $request->get_param( 'post_id' );

		if ( ! wp_verify_nonce( $nonce, 'content-form-' . $form_id ) ) {
			$return['msg'] = 'Invalid nonce';
			rest_ensure_response( $return );
			exit;
		}

		$form_type    = $request->get_param( 'form_type' );
		$form_builder = $request->get_param( 'form_builder' );
		$data         = $request->get_param( 'data' );

		/**
		 * Each form type should be able to provide its own process of submitting data.
		 * Must return the success status and a message.
		 */
		$return = apply_filters( 'content_forms_submit_' . $form_type, $return, $data, $form_id, $post_id, $form_builder );

		return rest_ensure_response( $return );
	}

	public function submit_forms_permissions_check() {
		return 1;
	}
}
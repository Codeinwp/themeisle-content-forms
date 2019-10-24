<?php
/**
 *
 */

namespace ThemeIsle\ContentForms\Includes\Admin;

/**
 * Class Server
 * @package ThemeIsle\ContentForms\Rest
 */
class Server extends \WP_Rest_Controller{

	/**
	 * Initialize the rest functionality.
	 */
	public function register_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {

		register_rest_route(
			TI_CONTENT_FORMS_NAMESPACE,
			'/check',
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'rest_check' )
			)
		);

		register_rest_route(
			TI_CONTENT_FORMS_NAMESPACE,
			'/submit',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'submit_form' ),
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
			)
		);
	}

	/**
	 * Validate response status.
	 *
	 * @param \WP_REST_Request $request Rest Request.
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function rest_check( \WP_REST_Request $request ) {
		return rest_ensure_response( 'success' );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function submit_form( $request ) {
		$nonce   = $request->get_param( 'nonce' );
		$form_id = $request->get_param( 'form_id' );

		if ( ! wp_verify_nonce( $nonce, 'content-form-' . $form_id ) ) {
			return new \WP_REST_Response(
				array(
					'success'    => false,
					'message' => esc_html__( 'Invalid nonce', 'textdomain' ),
				),
				400
			);

		}

		$data = $request->get_param( 'data' );
		if ( empty( $data[ $form_id ] ) ) {
			return new \WP_REST_Response(
				array(
					'success'    => false,
					'message' => esc_html__( 'Invalid Data ', 'textdomain' ) . $form_id,
				),
				400
			);
		}

		$data         = $data[ $form_id ];
		$post_id      = $request->get_param( 'post_id' );
		$form_type    = $request->get_param( 'form_type' );
		$return       = array(
			'success' => false,
			'message' => esc_html__( 'Something went wrong', 'textdomain' )
		);

		/**
		 * Each form type should be able to provide its own process of submitting data.
		 * Must return the success status and a message.
		 */
		$return = apply_filters( 'content_forms_submit_' . $form_type, $return, $data, $form_id, $post_id );
		$status = 200;
		if ( $return['success'] === false ){
			$status = 400;
		}
		return new \WP_REST_Response(
			$return,
			$status
		);
	}

}

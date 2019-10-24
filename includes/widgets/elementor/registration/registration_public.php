<?php
/**
 *
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration;

class Registration_Public {

	/**
	 * Initialization function.
	 */
	public function init() {
		add_filter( 'content_forms_submit_registration', array( $this, 'rest_submit_form' ), 10, 5 );
	}

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array Must contain the following keys: `email`, `name` but it can also have extra keys
	 * @param $widget_id string
	 * @param $post_id string
	 * @param $builder string
	 *
	 * @return mixed
	 */
	public function rest_submit_form( $return, $data, $widget_id, $post_id, $builder ) {

		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		$email = sanitize_email( $data['email'] );

		unset( $data['email'] );

		if ( empty( $data['username'] ) ) {
			$username = $email;
		} else {
			$username = sanitize_user( $data['username'] );
		}

		unset( $data['username'] );

		// if there is no password we will auto-generate one
		$password = null;

		if ( ! empty( $data['password'] ) ) {
			$password = $data['password'];
			unset( $data['password'] );
		}

		$return = $this->_register_user( $return, $email, $username, $password, $data );

		return $return;
	}

	/**
	 * Add a new user for the given details
	 *
	 * @param array $return
	 * @param string $user_email
	 * @param string $user_name
	 * @param null $password
	 * @param array $extra_data
	 *
	 * @return array mixed
	 */
	private function _register_user( $return, $user_email, $user_name, $password = null, $extra_data = array() ) {

		if ( ! get_option( 'users_can_register' ) ) {
			$return['message'] = esc_html__( 'This website does not allow registrations at this moment!' );

			return $return;
		}

		if ( ! validate_username( $user_name ) ) {
			$return['message'] = esc_html__( 'Invalid user name' );

			return $return;
		}

		if ( username_exists( $user_name ) ) {
			$return['message'] = esc_html__( 'Username already exists' );

			return $return;
		}

		if ( email_exists( $user_email ) ) {
			$return['message'] = esc_html__( 'This email is already registered' );
			return $return;
		}

		// no pass? ok
		if ( empty( $password ) ) {
			$password = wp_generate_password(
				$length = 12,
				$include_standard_special_chars = false
			);
		}

		$userdata = array(
			'user_login' => $user_name,
			'user_email' => $user_email,
			'user_pass'  => $password
		);

		$user_id = wp_insert_user( $userdata );

		if ( ! is_wp_error( $user_id ) ) {

			if ( ! empty( $extra_data ) ) {
				foreach ( $extra_data as $key => $value ) {
					update_user_meta( $user_id, sanitize_title( $key ), sanitize_text_field( $value ) );
				}
			}

			$return['success'] = true;
			$return['message']     = esc_html__( 'Welcome, ', 'textdomain' ) . $user_name;
		}

		return $return;
	}
}

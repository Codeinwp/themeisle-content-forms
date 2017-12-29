<?php

namespace ThemeIsle\ContentForms;

use ThemeIsle\ContentForms\ContentFormBase as Base;

/**
 * Class RegistrationForm
 * @package ThemeIsle\ContentForms
 */
class RegistrationForm extends Base {

	/**
	 * The Call To Action
	 */
	public function init() {
		$this->set_type( 'registration' );

		$this->notices = array(
			'success' => esc_html__( 'Your message has been sent!', 'textdomain' ),
			'error'   => esc_html__( 'We failed to send your message!', 'textdomain' ),
		);
	}

	/**
	 * Create an abstract array config which should define the form.
	 *
	 * @param $config
	 *
	 * @return array
	 */
	public function make_form_config( $config ) {

		return array(
			'id'    => $this->get_type(),
			'icon'  => 'eicon-align-left',
			'title' => esc_html__( 'User Registration Form' ),

			'fields' /* or form_fields? */ => array(
				'username' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'User Name', 'textdomain' ),
					'default'     => esc_html__( 'User Name', 'textdomain' ),
					'placeholder' => esc_html__( 'User Name', 'textdomain' ),
					'require'     => 'required',
					'validation'  => ''// name a function which should allow only letters and numbers
				),
				'email'    => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'default'     => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'require'     => 'required'
				),
				'password' => array(
					'type'        => 'password',
					'label'       => esc_html__( 'Password', 'textdomain' ),
					'default'     => esc_html__( 'Password', 'textdomain' ),
					'placeholder' => esc_html__( 'Password', 'textdomain' ),
					'require'     => 'required'
				)
			),

			'controls' /* or settings? */ => array(
//				'option_newsletter' => array(
//					'type'        => 'checkbox',
//					'label'       => esc_html__( 'Newsletter OptIn', 'textdomain' ),
//					'description' => esc_html__( 'Display a checkbox which allows the user to join a newsletter', 'textdomain' )
//				),
				'submit_label' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'description' => esc_html__( 'The Call To Action label', 'textdomain' )
				)
			)
		);
	}

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 * // @TODO we still have to check for the requirement with the field settings
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
			$return['msg'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		$email = sanitize_email( $data['email'] );
		unset( $data['email'] );

		if ( empty( $data['username'] ) ) {
			$return['msg'] = esc_html__( 'Missing username.', 'textdomain' );

			return $return;
		}

		$username = sanitize_user( $data['username'] );
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
			$return['msg'] = esc_html__( 'This website does not allow registrations at this moment!' );

			return $return;
		}

		if ( ! validate_username( $user_name ) ) {
			$return['msg'] = esc_html__( 'Invalid user name' );

			return $return;
		}

		if ( username_exists( $user_name ) ) {
			$return['msg'] = esc_html__( 'Username already exists' );

			return $return;
		}

		if ( email_exists( $user_email ) ) {
			$return['msg'] = esc_html__( 'This email is already registered' );

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
			$return['msg']     = esc_html__( 'Welcome, ', 'textdomain' ) . $user_name;
		}

		return $return;
	}

}
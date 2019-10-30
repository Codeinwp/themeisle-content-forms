<?php
/**
 *
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration;

use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Actions_Base;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets/elementor/elementor_widget_actions_base.php';

class Registration_Public extends Elementor_Widget_Actions_Base {

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'registration';

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array Must contain the following keys: `email`, `name` but it can also have extra keys
	 * @param $widget_id string
	 * @param $post_id string
	 *
	 * @return mixed
	 */
	public function rest_submit_form( $return, $data, $widget_id, $post_id ) {

		if ( empty( $data['USER_EMAIL'] ) || ! is_email( $data['USER_EMAIL'] ) ) {
			$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		$settings['user_email']             = sanitize_email( $data['USER_EMAIL'] );
		$settings['user_login']             = ! empty( $data['USER_LOGIN'] ) ? $data['USER_LOGIN'] : $data['email'];
		$settings['user_pass']              = ! empty( $data['USER_PASS'] ) ? $data['USER_PASS'] : wp_generate_password(
			$length                         = 12,
			$include_standard_special_chars = false
		);
		$settings['display_name'] = ! empty( $data['DISPLAY_NAME'] ) ? $data['DISPLAY_NAME'] : '';
		$settings['first_name']   = ! empty( $data['FIRST_NAME'] ) ? $data['FIRST_NAME'] : '';
		$settings['last_name']    = ! empty( $data['LAST_NAME'] ) ? $data['LAST_NAME'] : '';

		$return = $this->_register_user( $return, $settings );

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
	private function _register_user( $return, $settings ) {

		if ( ! get_option( 'users_can_register' ) ) {
			$return['message'] = esc_html__( 'This website does not allow registrations at this moment!' );

			return $return;
		}

		if ( ! validate_username( $settings['user_login'] ) ) {
			$return['message'] = esc_html__( 'Invalid user name' );

			return $return;
		}

		if ( username_exists( $settings['user_login'] ) ) {
			$return['message'] = esc_html__( 'Username already exists' );

			return $return;
		}

		if ( email_exists( $settings['user_email'] ) ) {
			$return['message'] = esc_html__( 'This email is already registered' );
			return $return;
		}

		$user_id = wp_insert_user( $settings );

		if ( ! is_wp_error( $user_id ) ) {

			if ( ! empty( $extra_data ) ) {
				foreach ( $extra_data as $key => $value ) {
					update_user_meta( $user_id, sanitize_title( $key ), sanitize_text_field( $value ) );
				}
			}

			$return['success'] = true;
			$return['message'] = esc_html__( 'Welcome, ', 'textdomain' ) . $settings['user_login'] . '!';
		}

		return $return;
	}
}

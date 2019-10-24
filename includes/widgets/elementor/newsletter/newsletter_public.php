<?php
/**
 * This class handles the action part of the Newsletter Widget.
 *
 * @package ContentForms
 */


namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Actions_Base;

/**
 * Class Newsletter_Public
 */
class Newsletter_Public extends Elementor_Widget_Actions_Base {

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'newsletter';

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array Must contain the following keys: `email` but it can also have extra keys
	 * @param $widget_id string
	 * @param $post_id string
	 *
	 * @return mixed
	 */
	public function rest_submit_form( $return, $data, $widget_id, $post_id ) {

		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		$email = $data['email'];

		// prepare settings for submit
		$settings = $this->get_widget_settings( $widget_id, $post_id );

		$provider = 'mailchimp';
		if ( ! empty( $settings['provider'] ) ) {
			$provider = $settings['provider'];
		}

		$providerArgs = array();

		if ( empty( $settings['access_key'] ) || empty( $settings['list_id'] ) ) {
			$return['message'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );

			return $return;
		}

		$providerArgs['access_key'] = $settings['access_key'];
		$providerArgs['list_id']    = $settings['list_id'];

		$return = $this->_subscribe_mail( $return, $email, $provider, $providerArgs, $settings );

		return $return;
	}

	/**
	 * Subscribe the given email to the given provider; either mailchimp or sendinblue.
	 *
	 * @param $result
	 * @param $email
	 * @param string $provider
	 * @param array $provider_args
	 *
	 * @return bool|array
	 */
	private function _subscribe_mail( $result, $email, $provider = 'mailchimp', $provider_args = array(), $settings ) {

		$api_key = $provider_args['access_key'];
		$list_id = $provider_args['list_id'];

		switch ( $provider ) {

			case 'mailchimp':
				// add a pending subscription for the user to confirm
				$status = 'pending';

				$args = array(
					'method'  => 'PUT',
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key )
					),
					'body'    => json_encode( array(
						'email_address' => $email,
						'status'        => $status
					) )
				);

				$url = 'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) );

				$response = wp_remote_post( $url, $args );
				$body     = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
					$result['success'] = false;
					$result['message'] = $body['detail'];

					return $result;
				}

				$result['success'] = false;
				$result['message'] = $settings['error_message'];
				if ( $body['status'] == $status ) {
					$result['success'] = true;
					$result['message'] = $settings['success_message'];
				}

				return $result;
				break;
			case 'sendinblue':

				$url = 'https://api.sendinblue.com/v3/contacts';

				// https://developers.sendinblue.com/reference#createcontact
				$args = array(
					'method'  => 'POST',
					'headers' => array(
						'content-type' => 'application/json',
						'api-key'      => $api_key
					),
					'body'    => json_encode( array(
						'email'            => $email,
						'listIds'          => array( (int) $list_id ),
						'emailBlacklisted' => false,
						'smsBlacklisted'   => false,
					) )
				);

				$response = wp_remote_post( $url, $args );

				if ( is_wp_error( $response ) ) {
					$result['message'] = $settings['error_message'];

					return $result;
				}

				if ( 400 != wp_remote_retrieve_response_code( $response ) ) {
					$result['success'] = true;
					$result['message'] = $settings['success_message'];

					return $result;
				}

				$body              = json_decode( wp_remote_retrieve_body( $response ), true );
				$result['message'] = $body['message'];

				return $result;
				break;

			default;
				break;
		}

		return false;
	}
}

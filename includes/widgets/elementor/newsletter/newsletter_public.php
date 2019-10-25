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

		/**
		 * Email address is required for this type of form
		 */
		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );

			return $return;
		}

		/**
		 * Get form settings and bail if there is no access key or list id.
		 */
		$settings = $this->get_widget_settings( $widget_id, $post_id );
		if ( empty( $settings['access_key'] ) || empty( $settings['list_id'] ) ) {
			$return['message'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );

			return $return;
		}

		$form_fields = array();
		foreach ( $data as $field_name => $field_value ) {
			if( is_array( $data[ $field_name ] ) ){
				foreach ( $data[ $field_name ] as $filed => $value ){
					$form_fields[ $field_name.'['.$filed.']' ] = $value;
				}
			} else {
				$form_fields[ $field_name ] = $field_value;
			}
		}

		$form_settings = array(
			'provider_settings' => array(
				'provider'   => ! empty( $settings['provider'] ) ? $settings['provider'] : 'mailchimp',
				'access_key' => $settings['access_key'],
				'list_id'    => $settings['list_id'],
			),
			'data'              => $form_fields,
			'strings'           => array(
				'error_message'   => $settings['error_message'],
				'success_message' => $settings['success_message'],
			)
		);

		$return = $this->subscribe_mail( $form_settings, $return );

		return $return;
	}

	/**
	 * Subscribe the given email to the given provider, either mailchimp or sendinblue.
	 *
	 * @param array $form_settings Form Settings.
	 * @param array $result Return result
	 *
	 * @return array
	 */
	private function subscribe_mail( $form_settings, $result ) {

		$provider_name = $form_settings['provider_settings']['provider'];

		$submit = false;
		if ( $provider_name === 'mailchimp' ) {
			$submit = $this->mailchimp_subscribe( $form_settings );
		}

		if ( $provider_name === 'sendinblue' ) {
			$submit = $this->sib_subscribe( $form_settings );
		}

		$result['success'] = false;
		$result['message'] = $form_settings['strings']['error_message'];
		if ( $submit === true ) {
			$result['success'] = true;
			$result['message'] = $form_settings['strings']['success_message'];
		}

		return $result;
	}

	/**
	 * Handle the request for mailchimp.
	 * https://mailchimp.com/developer/reference/lists/list-members/
	 *
	 * @param array $form_settings Form settings.
	 *
	 * @return bool
	 */
	private function mailchimp_subscribe( $form_settings ) {

		$api_key   = $form_settings['provider_settings']['access_key'];
		$list_id   = $form_settings['provider_settings']['list_id'];
		$form_data = $form_settings['data'];

		$email     = $form_data['email'];
		unset( $form_data['email'] );

		$url = 'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) );

		$args = array(
			'method'  => 'PUT',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key )
			),
			'body'    => json_encode( array(
				'email_address' => $email,
				'status'        => 'pending',
				'merge_fields'  => $form_data,
			) )
		);

		$response = wp_remote_post( $url, $args );
		$body     = json_decode( wp_remote_retrieve_body( $response ), true );

		print_r($body);
		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		if ( $body['status'] === 'pending' ) {
			return true;
		}

		return false;
	}

	/**
	 * Handle the request for sendinblue.
	 * https://developers.sendinblue.com/reference#createcontact
	 *
	 * @param array $form_settings Form settings.
	 *
	 * @return bool
	 */
	private function sib_subscribe( $form_settings ) {

		$api_key   = $form_settings['provider_settings']['access_key'];
		$list_id   = $form_settings['provider_settings']['list_id'];
		$form_data = $form_settings['data'];
		$email     = $form_data['email'];
		unset( $form_data['email'] );

		$url = 'https://api.sendinblue.com/v3/contacts';

		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'content-type' => 'application/json',
				'api-key'      => $api_key
			),
			'body'    => json_encode( array(
				'email'            => $email,
				'listIds'          => array( (int) $list_id ),
				'attributes'       => $form_data,
				'emailBlacklisted' => false,
				'smsBlacklisted'   => false,
			) )
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( 400 != wp_remote_retrieve_response_code( $response ) ) {
			return true;
		}

		return false;
	}

}

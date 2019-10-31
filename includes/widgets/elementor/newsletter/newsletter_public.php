<?php
/**
 * This class handles the action part of the Newsletter Widget.
 *
 * @package ContentForms
 */


namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Actions_Base;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets/elementor/elementor_widget_actions_base.php';

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
		if ( empty( $data['EMAIL'] ) ) {
			$return['message'] = esc_html__( 'The email field cannot be empty.', 'textdomain' );
			return $return;
		}

		if ( ! is_email( $data['EMAIL'] ) ) {
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
			$form_fields[ $field_name ] = $field_value;
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
			),
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

		$provider_name     = $form_settings['provider_settings']['provider'];
		$result['success'] = false;
		$result['message'] = $form_settings['strings']['error_message'];

		if ( $provider_name === 'mailchimp' ) {
			$result = $this->mailchimp_subscribe( $form_settings, $result );
		}

		if ( $provider_name === 'sendinblue' ) {
			$result = $this->sib_subscribe( $form_settings, $result );
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
	private function mailchimp_subscribe( $form_settings, $result ) {

		$api_key   = $form_settings['provider_settings']['access_key'];
		$list_id   = $form_settings['provider_settings']['list_id'];
		$form_data = $form_settings['data'];
		$email     = $form_data['EMAIL'];
		unset( $form_data['EMAIL'] );

		$url = 'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) );

		$args = array(
			'method'  => 'PUT',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key ),
			),
			'body'    => json_encode(
				array(
					'email_address' => $email,
					'status'        => 'pending',
					'merge_fields'  => $form_data,
				)
			),
		);

		$response = wp_remote_post( $url, $args );
		$body     = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			$message = ! empty($body['detail']) && $body['detail'] !== 'null' ? $body['detail'] : $form_settings['strings']['error_message'];
			$result['success'] = false;
			$result['message'] = $message;
			return $result;
		}

		if ( $body['status'] === 'pending' ) {
			$result['message'] = $form_settings['strings']['success_message'];
			$result['success'] = true;
			return $result;
		}

		return $result;
	}

	/**
	 * Handle the request for sendinblue.
	 * https://developers.sendinblue.com/reference#createcontact
	 *
	 * @param array $form_settings Form settings.
	 *
	 * @return bool
	 */
	private function sib_subscribe( $form_settings, $result ) {

		$api_key   = $form_settings['provider_settings']['access_key'];
		$list_id   = $form_settings['provider_settings']['list_id'];
		$form_data = $form_settings['data'];
		$email     = $form_data['EMAIL'];
		unset( $form_data['EMAIL'] );

		$url = 'https://api.sendinblue.com/v3/contacts';

		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'content-type' => 'application/json',
				'api-key'      => $api_key,
			),
			'body'    => json_encode(
				array(
					'email'            => $email,
					'listIds'          => array( (int) $list_id ),
					'attributes'       => $form_data,
					'emailBlacklisted' => false,
					'smsBlacklisted'   => false,
				)
			),
		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			return $result;
		}

		if ( 400 != wp_remote_retrieve_response_code( $response ) ) {
			$result['message'] = $form_settings['strings']['success_message'];
			$result['success'] = true;
			return $result;
		}

		$body              = json_decode( wp_remote_retrieve_body( $response ), true );
		$result['message'] = $body['message'];
		return $result;
	}

}

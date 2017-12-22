<?php

namespace ThemeIsle\ContentForms;

use ThemeIsle\ContentForms\ContentFormBase as Base;

/**
 * Class NewsletterForm
 * @package ThemeIsle\ContentForms
 */
class NewsletterForm extends Base {

	/**
	 * The Call To Action
	 */
	public function init() {
		$this->set_type( 'newsletter' );

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
			'id'                           => 'newsletter',
			'icon'                         => 'eicon-align-left',
			'title'                        => esc_html__( 'Newsletter Form' ),
			'fields' /* or form_fields? */ => array(
				'email' => array(
					'type'    => 'email',
					'label'   => esc_html__( 'Email' ),
					'require' => 'required'
				)
			),

			'controls' /* or settings? */ => array(
				'provider'     => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Subscribe to', 'textdomain' ),
					'description' => esc_html__( 'Where to send the email?', 'textdomain' ),
					'options'     => array(
						'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
						'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' )
					)
				),
				'access_key'   => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Access Key', 'textdomain' ),
					'description' => esc_html__( 'If a subscription service is selected, please provide an access key', 'textdomain' )
				),
				'list_id'      => array(
					'type'  => 'text',
					'label' => esc_html__( 'List ID', 'textdomain' ),
				),
				'submit_label' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Submit Label', 'textdomain' ),
					'default' => esc_html__( 'Join our Newsletter', 'textdomain' ),
				),

			)
		);
	}

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array Must contain the following keys: `email` but it can also have extra keys
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

		$email = $data['email'];

		// prepare settings for submit
		$settings = $this->get_widget_settings( $widget_id, $post_id, $builder );

		$provider = $settings['provider'];

		$providerArgs = array();

		$providerArgs['access_key'] = $settings['access_key'];
		$providerArgs['list_id'] = $settings['list_id'];

		$return = $this->_subscribe_mail( $return, $email, $provider, $providerArgs );

		return $return;
	}

	/**
	 * Subscribe the given email to the given provider; either mailchimp or sendinblue.
	 * @param $result
	 * @param $email
	 * @param string $provider
	 * @param array $provider_args
	 *
	 * @return bool|array
	 */
	private function _subscribe_mail( $result, $email, $provider = 'mailchimp', $provider_args = array() ) {

		$api_key = $provider_args['access_key'];
		$list_id = $provider_args['list_id'];

		switch ( $provider ) {

			case 'mailchimp':
				// add a pending subscription for the user to confirm
				$status = 'pending';

				$args     = array(
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

				if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
					return $response;
				}

				$body = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( $body->status == $status ) {
					$result['success'] = true;
					$result['msg'] = 'Welcome to our newsletter';
				} else {
					$result['success'] = false;
					$result['msgz'] = 'Something went wrong';
				}

				return $result;
				break;
			case 'sendinblue':

				$url = 'https://api.sendinblue.com/v3/contacts';

				$args     = array(
					'method'  => 'POST',
					'headers' => array(
						'content-type' => 'application/json',
						'api-key' => $api_key
					),
					'body'    => json_encode( array(
						'email'            => $email,
						'listIds'          => array( (int)$list_id ),
						'emailBlacklisted' => false,
						'smsBlacklisted'   => false,
					) )
				);

				$response = wp_remote_post( $url, $args );

				if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {

					$body = json_decode( wp_remote_retrieve_body( $response ), true );

					if ( ! empty( $body['message'] ) ) {
						$result['msg'] = $body['message'];
					} else {
						$result['msg'] = $response;
					}

					return $result;
				}

				$result['success'] = true;
				$result['msg'] = 'Welcome to our newsletter';

				return $result;
				break;

			default;
				break;
		}

		return false;
	}
}
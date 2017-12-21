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

		$result = $this->_subscribe_mail( $email, $provider, $providerArgs );

		if ( $result ) {
			$return['success'] = true;

			$return['msg'] = 'Awesome! Now you just need to check your mail and confirm your subscription!';
		} else {
			$return['msg'] = 'Something went wrong!';
		}

		return $return;
	}

	/**
	 * @param $email
	 * @param string $provider
	 * @param array $provider_args
	 *
	 * @return bool
	 */
	private function _subscribe_mail( $email, $provider = 'mailchimp', $provider_args = array() ) {

		switch ( $provider ) {

			case 'mailchimp':

				$api_key = $provider_args['access_key'];
				$list_id = $provider_args['list_id'];

				// shoot this off to mailchimp via api
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

				$body = json_decode( $response['body'] );

				if ( $response['response']['code'] == 200 && $body->status == $status ) {
					return true;
				} else {
					return false;
				}

				break;
			case 'sendinblue':

				$url = 'https://api.sendinblue.com/v3/';
				// @TODO

				break;

			default;
				break;
		}

		return false;
	}
}
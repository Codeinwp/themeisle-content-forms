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
			'error' => esc_html__( 'We failed to send your message!', 'textdomain' ),
		);

	}

	/**
	 * Create an abstract array config which should define the form.
	 *
	 * @param $config
	 *
	 * @return array
	 */
	function make_form_config( $config ) {

		return array(
			'id'    => $this->get_type(),
			'icon'  => 'eicon-align-left',
			'title' => esc_html__( 'User Registration Form' ),

			'fields' /* or form_fields? */ => array(
				'username' => array(
					'type'       => 'text',
					'label'      => esc_html__( 'User Name' ),
					'require'    => 'required',
					'validation' => ''// name a function which should allow only letters and numbers
				),
				'email'    => array(
					'type'    => 'email',
					'label'   => esc_html__( 'Email' ),
					'require' => 'required'
				),
				'password' => array(
					'type'    => 'password',
					'label'   => esc_html__( 'Password' ),
					'require' => 'required'
				)
			),

			'controls' /* or settings? */ => array(
//				'option_newsletter' => array(
//					'type'        => 'checkbox',
//					'label'       => esc_html__( 'Newsletter OptIn', 'textdomain' ),
//					'description' => esc_html__( 'Display a checkbox which allows the user to join a newsletter', 'textdomain' )
//				),
				'submit_label'      => array(
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
	 * @param $data array Must contain the following keys: `email`, `name`, `message` but it can also have extra keys
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

		$from = $data['email'];

		if ( empty( $data['name'] ) ) {
			$return['msg'] = esc_html__( 'Missing name.', 'textdomain' );

			return $return;
		}

		$name = $data['name'];

		if ( empty( $data['message'] ) ) {
			$return['msg'] = esc_html__( 'Missing message.', 'textdomain' );

			return $return;
		}

		$msg = $data['message'];

		// prepare settings for submit
		$settings = $this->get_widget_settings( $widget_id, $post_id, $builder );

		// @TODO

		if ( $result ) {
			$return['success'] = true;
			$return['msg']     = $this->notices['success'];
		} else {
			$return['msg'] = $result;
		}

		return $return;
	}

}
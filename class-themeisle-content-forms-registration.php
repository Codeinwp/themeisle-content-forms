<?php

namespace ThemeIsle\ContentForms;

/**
 * Class RegistrationForm
 * @package ThemeIsle\ContentForms
 */
class RegistrationForm {

	public function __construct() {

		// add the initial config for the Contact Content Form
		add_filter( 'content_forms_config_for_registration', array( $this, 'make_form_config' ) );

		$config = apply_filters( 'content_forms_config_for_registration', array() );

		$this->build_form( $config );

	}

	function make_form_config() {

		return array(
			'id'    => 'registration',
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
				'option_newsletter' => array(
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Newsletter OptIn', 'textdomain' ),
					'description' => esc_html__( 'Display a checkbox which allows the user to join a newsletter', 'textdomain' )
				),
				'submit_label'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'description' => esc_html__( 'The Call To Action label', 'textdomain' )
				)
			)
		);
	}

	function build_form( $config ) {
		$form = new ContentForm( 'registration', $config );
	}
}
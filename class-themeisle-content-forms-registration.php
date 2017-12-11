<?php

namespace ThemeIsle\ContentForms;

/**
 *
 * Class ContentForms_Registration
 *
 */
class Registration {

	public function __construct() {

	}

	function make_form() {
		$config = array(
			// for sure,
			'fields' /* or form_fields? */  => array(
				'username'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'User Name' ),
					'require' => 'required',
					'validation' => ''// name a function which should allow only letters and numbers
				),
				'email'   => array(
					'type'    => 'email',
					'label'   => esc_html__( 'Email' ),
					'require' => 'required'
				),
				'password'   => array(
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
				'submit_label'  => array(
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
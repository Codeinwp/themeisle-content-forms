<?php

namespace ThemeIsle\ContentForms;

class Newsletter {

	public function __construct() {

	}

	function make_form() {

		$config = array(
			// for sure,
			'fields' /* or form_fields? */ => array(
				'email' => array(
					'type'    => 'email',
					'label'   => esc_html__( 'Email' ),
					'require' => 'required'
				)
			),

			'controls' /* or settings? */ => array(
				'subscribe_to' => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Subscribe to', 'textdomain' ),
					'description' => esc_html__( 'Where to send the email?', 'textdomain' ),
					'options'     => array(
						'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
						'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' ),
						'other'      => esc_html__( 'Other', 'textdomain' )
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
				)
			)
		);
	}

	// @TODO extendable on submit method?
	function on_submit(){

	}

	function build_form( $config ) {
		$form = new ContentForm( 'newsletter', $config );
	}
}
<?php

namespace ThemeIsle\ContentForms;

/**
 * Class NewsletterForm
 * @package ThemeIsle\ContentForms
 */
class NewsletterForm {

	public function __construct() {
		// add the initial config for the Contact Content Form
		add_filter( 'content_forms_config_for_newsletter', array( $this, 'make_form_config' ) );

		$config = apply_filters( 'content_forms_config_for_newsletter', array() );

		$this->build_form( $config );
	}

	function make_form_config() {

		return array(
			'id'    => 'newsletter',
			'icon'  => 'eicon-align-left',
			'title' => esc_html__( 'Newsletter Form' ),
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
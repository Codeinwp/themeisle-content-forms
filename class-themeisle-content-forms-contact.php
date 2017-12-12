<?php

namespace ThemeIsle\ContentForms;

/**
 * This class is responsible for creating a Contact "Content" Form
 * Class ContentForms_Contact
 * @package ThemeIsle
 */
class ContactForm {

	public function __construct() {

		// add the initial config for the Contact Content Form
		add_filter( 'content_forms_config_for_contact', array( $this, 'make_form_config' ) );

		$config = apply_filters( 'content_forms_config_for_contact', array() );

		$this->build_form( $config );
	}

	function make_form_config( $config ) {
		return array(
				'id'                           => 'contact',
				'icon'                         => 'eicon-align-left',
				'title'                        => esc_html__( 'Contact Form' ),
				'fields' /* or form_fields? */ => array(
					'contact_name'    => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Name' ),
						'placeholder' => esc_html__( 'Your Name' ),
						'require'     => 'required'
					),
					'contact_email'   => array(
						'type'        => 'email',
						'label'       => esc_html__( 'Email' ),
						'placeholder' => esc_html__( '@who.com' ),
						'require'     => 'required'
					),
					'contact_phone'   => array(
						'type'        => 'number',
						'label'       => esc_html__( 'Phone' ),
						'placeholder' => esc_html__( '+555 555 555' ),
						'require'     => 'optional'
					),
					'contact_message' => array(
						'type'        => 'textarea',
						'label'       => esc_html__( 'Message' ),
						'placeholder' => esc_html__( 'Add your thoughts here' ),
						'require'     => 'required'
					)
				),

				'controls' /* or settings? */ => array(
					'to_send_email'    => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Email', 'textdomain' ),
						'description' => esc_html__( 'Where to send the email?', 'textdomain' )
					),
					'submit_label'     => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Submit', 'textdomain' ),
						'description' => esc_html__( 'The Call To Action label', 'textdomain' )
					),
					'disable_honeypot' => array(
						'type'        => 'checkbox',
						'label'       => esc_html__( 'Disable Spam Filter (Honeypot)', 'textdomain' ),
						'description' => esc_html__( 'By default, the robots spam filter is enabled for every form, if for some reason you want to disable it.', 'textdomain' )
					)
				)
		);
	}

	function build_form( $config ) {
		$form = new ContentForm( 'contact', $config );
	}
}
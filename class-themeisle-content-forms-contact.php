<?php

namespace ThemeIsle\ContentForms;

/**
 * This class is responsable for creating a Contact "Content" Form
 * Class ContentForms_Contact
 * @package ThemeIsle
 */
class ContactForm {

	public function __construct() {

		$config = $this->get_config();
		$this->build_form( $config );
	}

	function get_config() {
		return apply_filters( '', array(
				'id' => 'contact',
				'label' => 'contact',
				'fields' /* or form_fields? */ => array(
					'contact_name'    => array(
						'type'    => 'text',
						'label'   => esc_html__( 'Name' ),
						'require' => 'required'
					),
					'contact_email'   => array(
						'type'    => 'email',
						'label'   => esc_html__( 'Email' ),
						'require' => 'required'
					),
					'contact_phone'   => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Phone' ),
						'require' => 'optional'
					),
					'contact_message' => array(
						'type'    => 'textarea',
						'label'   => esc_html__( 'Message' ),
						'require' => 'required'
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
			)
		);
	}

	function build_form( $config ) {
		$form = new ContentForm( 'contact', $config );
	}
}
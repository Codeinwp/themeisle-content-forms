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

		// register the classic submission action
		add_action( 'admin_post_nopriv_content_form_contact_submit', array( $this, 'submit_form' ) );
		add_action( 'admin_post_content_form_contact_submit', array( $this, 'submit_form' ) );

		// @TODO maybe add an ajax way?
	}

	/**
	 * Create an abstract array config which should define the form.
	 * @param $config
	 *
	 * @return array
	 */
	function make_form_config( $config ) {
		return array(
			'id'                           => 'contact',
			'icon'                         => 'eicon-align-left',
			'title'                        => esc_html__( 'Contact Form' ),
			'fields' /* or form_fields? */ => array(
				'name'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Name' ),
					'placeholder' => esc_html__( 'Your Name' ),
					'require'     => 'required'
				),
				'email'   => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email' ),
					'placeholder' => esc_html__( 'Email address' ),
					'require'     => 'required'
				),
				'phone'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Phone' ),
					'placeholder' => esc_html__( 'Phone Nr' ),
					'require'     => 'optional'
				),
				'message' => array(
					'type'        => 'textarea',
					'label'       => esc_html__( 'Message' ),
					'placeholder' => esc_html__( 'Your message' ),
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
					'default'     => 'false',
					'label'       => esc_html__( 'Disable Spam Filter (Honeypot)', 'textdomain' ),
					'description' => esc_html__( 'By default, the robots spam filter is enabled for every form, if for some reason you want to disable it.', 'textdomain' )
				)
			)
		);
	}

	/**
	 * Initialize the contact form from the base class
	 * @param $config
	 */
	function build_form( $config ) {
		$form = new ContentForm( 'contact', $config );
	}

	/**
	 * This method is binded to the
	 */
	function submit_form() {
		// @TODO first we need to collect and validate our parameters

		$to   = '';
		$subj = '';

		$ok = $this->_send_mail( $to, $subj );

		// @TODO we need to inform the user if the mail was sent or not;

		if ( ! wp_get_referer() ) {
			return;
		}

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * An email sender primitive.
	 *
	 * @param $to
	 * @param $subj
	 *
	 * @return bool
	 */
	private function _send_mail( $to, $subj ) {
		$success = false;

		// @TODO best way to send a mail?

		return $success;
	}
}
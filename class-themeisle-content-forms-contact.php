<?php

namespace ThemeIsle\ContentForms;

/**
 * This class is responsible for creating a Contact "Content" Form
 * Class ContactForm
 * @package ThemeIsle\ContentForms
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
		add_filter( 'content_forms_submit_contact', array( $this, 'rest_submit_form' ), 10, 5 );
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
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array
	 * @param $id string
	 * @param $builder string
	 *
	 * @return mixed
	 */
	public function rest_submit_form( $return, $data, $widget_id, $post_id, $builder ) {

		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			$return['msg'] = 'invalid email';
			return $return;
		}

		$from = $data['email'];

		if ( empty( $data['name'] ) ) {
			$return['msg'] = 'Missing name';
			return $return;
		}

		$name = $data['name'];

		if ( empty( $data['message'] ) ) {
			$return['msg'] = 'Missing message';
			return $return;
		}

		$msg = $data['message'];

		// prepare settings for submit
		$settings = $this->get_widget_settings( $widget_id, $post_id );

		// @TODO handle extra fields
		$result = $this->_send_mail( $settings['to_send_email'], $from, $name, $msg );

		if ( $result ) {
			$return['success'] = true;
			$return['msg'] = 'Great Success';
		}

		return $return;
	}

	/**
	 * The classic submission method via the `admin_post_` hook
	 */
	function submit_form(  ) {
		// @TODO first we need to collect data from $_POST and validate our parameters

//		$ok = $this->_send_mail( $to, $subj );

		// @TODO we need to inform the user if the mail was sent or not;

		if ( ! wp_get_referer() ) {
			return;
		}

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Mail sender method
	 * @param $mailto
	 * @param $mailfrom
	 * @param $subject
	 * @param $body
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	private function _send_mail( $mailto, $mailfrom, $subject, $body, $extra_data = array() ) {
		$success = false;

		$subject = sanitize_text_field( $subject );
		$mailto = sanitize_email( $mailto );
		$mailfrom = sanitize_email( $mailfrom );

		$headers = array();
		$headers[] = 'From: ' . $subject . ' <' . $mailfrom . '>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$body = $this->prepare_body( $body, $extra_data );

		$success = wp_mail( $mailto, $subject, $body, $headers );

		return $success;
	}

	/**
	 * Body template preparation
	 * @param string $body
	 * @param array $data
	 *
	 * @return string
	 */
	private function prepare_body( $body, $data ) {

		// @TODO process the email body tempalte here

		return $body;
	}

	/**
	 * Get block settings depending on what builder is in use.
	 *
	 * @param $widget_id
	 * @param $page_id
	 *
	 * @return bool
	 */
	private function get_widget_settings($widget_id, $page_id) {
		$path = dirname( __FILE__ );
		require_once $path . '/class-themeisle-content-forms-elementor.php';
		// if elementor
		$settings = ElementorWidget::get_widget_settings( $widget_id, $page_id );
		return $settings['settings'];
		// if gutenberg

		// if beaver
	}
}
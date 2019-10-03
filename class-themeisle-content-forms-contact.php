<?php

namespace ThemeIsle\ContentForms;

use ThemeIsle\ContentForms\ContentFormBase as Base;

/**
 * This class creates a Contact Form
 * Class ContactForm
 * @package ThemeIsle\ContentForms
 */
class ContactForm extends Base {

	/**
	 * @var ContactForm
	 */
	public static $instance = null;

	/**
	 * The Call To Action
	 */
	public function init() {
		$this->set_type( 'contact' );

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
	function make_form_config( $config ) {
		return array(
			'id'                           => 'contact',
			'icon'                         => 'eicon-align-left',
			'title'                        => esc_html__( 'Contact Form' ),
			'fields' /* or form_fields? */ => array(
				'name'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Name' ),
					'default'     => esc_html__( 'Name' ),
					'placeholder' => esc_html__( 'Your Name' ),
					'require'     => 'required'
				),
				'email'   => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email' ),
					'default'     => esc_html__( 'Email' ),
					'placeholder' => esc_html__( 'Email address' ),
					'require'     => 'required'
				),
				'phone'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Phone' ),
					'default'     => esc_html__( 'Phone' ),
					'placeholder' => esc_html__( 'Phone Nr' ),
					'require'     => 'optional'
				),
				'message' => array(
					'type'        => 'textarea',
					'label'       => esc_html__( 'Message' ),
					'default'     => esc_html__( 'Message' ),
					'placeholder' => esc_html__( 'Your message' ),
					'require'     => 'required'
				)
			),

			'controls' /* or settings? */ => array(
				'to_send_email' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Send to', 'textdomain' ),
					'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
					'default'     => get_bloginfo( 'admin_email' )
				),
				'submit_label'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'default'     => esc_html__( 'Submit', 'textdomain' ),
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
		$settings = $this->get_widget_settings( $widget_id, $post_id, $builder );
		foreach( $settings['form_fields'] as $field ) {
			$key = ! empty( $field['label'] ) ? sanitize_title( $field['label'] ) : ( ! empty( $field['placeholder'] ) ? sanitize_title( $field['placeholder'] ) : 'field_' . $item_index );
			if ( ! empty( $field['key'] ) ){
				$key = $field['key'];
			}

			if ( 'required' === $field['requirement'] ){
				if ( empty( $data[ $key ] ) ){
					$return['message'] = sprintf( esc_html__( 'Missing %s', 'textdomain'), $key );
					return $return;
                }
            }
			if( 'email' === $field['type']  && ! is_email( $data[$key] ) ){
				$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );
				return $return;
            }
		}

		if ( ! isset( $settings['to_send_email'] ) || ! is_email( $settings['to_send_email'] ) ) {
			$return['message'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );
			return $return;
		}

		// Empty email does not make much sense!
		$from = isset( $data['email'] ) ? $data['email'] : null;
		$name = isset( $data['name'] ) ? $data['name'] : null;


		// Empty message does not make much sense!
		$message = isset( $data['message'] ) ? $data['message'] : null;

		// prepare settings for submit
		$settings = $this->get_widget_settings( $widget_id, $post_id, $builder );

		$result = $this->_send_mail( $settings['to_send_email'], $from, $name, $message, $data );

		$return['message'] = esc_html__( 'Oops! I cannot send this email!', 'textdomain' );
		if ( $result ) {
			$return['success'] = true;
			$return['message']     = $this->notices['success'];
		}

		return $return;
	}

	/**
	 * Mail sender method
	 *
	 * @param $mailto
	 * @param $mailfrom
	 * @param $subject
	 * @param $body
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	private function _send_mail( $mailto, $mailfrom, $name, $body, $extra_data = array() ) {

		$name = sanitize_text_field( $name );
		$subject  = 'Website inquiry from ' . ( ! empty( $name ) ? $name : 'N/A' );
		$mailto   = sanitize_email( $mailto );
		$mailfrom = sanitize_email( $mailfrom );

		$headers   = array();
		// use admin email assuming the Server is allowed to send as admin email
		$headers[] = 'From: Admin <' . get_option( 'admin_email' ) . '>';
		if ( ! empty( $mailfrom ) ) {
			$headers[] = 'Reply-To: ' . $name . ' <' . $mailfrom . '>';
		}
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$body = $this->prepare_body( $body, $extra_data );

		ob_start();

		$success = wp_mail( $mailto, $subject, $body, $headers );

		if ( ! $success ) {
			return ob_get_clean();
		}

		return $success;
	}

	/**
	 * Body template preparation
	 *
	 * @param string $body
	 * @param array $data
	 *
	 * @return string
	 */
	private function prepare_body( $body, $data ) {
		$tmpl = "";

		ob_start(); ?>
		<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
			<!-- view port meta tag -->
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<title><?php echo esc_html__( 'Mail From: ', 'textdomain' ) . isset( $data['name'] ) ? esc_html( $data['name'] ) : 'N/A'; ?></title>
		</head>
		<body>
		<table>
			<thead>
			<tr>
				<th>
					<h3>
						<?php esc_html_e( 'Content Form submission from ', 'textdomain' ); ?>
						<a href="<?php echo esc_url( get_site_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
					</h3>
					<hr/>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $data as $key => $value ) { ?>
				<tr>
					<td>
						<strong><?php echo esc_html( $key ) ?> : </strong>
						<p><?php echo esc_html( $value ); ?></p>
					</td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr>
				<td>
					<hr/>
					<?php esc_html_e( 'You received this email because your email address is set in the content form settings on ' ) ?>
					<a href="<?php echo esc_url( get_site_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
				</td>
			</tr>
			</tfoot>
		</table>
		</body>
		</html>
		<?php
		return ob_get_clean();
	}

	/**
	 * The classic submission method via the `admin_post_` hook
	 * @TODO not used at this moment.
	 */
	function submit_form() {
		// @TODO first we need to collect data from $_POST and validate our parameters
		//$ok = $this->_send_mail( $to, $subj );
		// @TODO we need to inform the user if the mail was sent or not;

		if ( ! wp_get_referer() ) {
			return;
		}

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return ContactForm
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0.0' );
	}
}

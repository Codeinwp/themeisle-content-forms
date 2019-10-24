<?php
/**
 * This class handles the action part of the Contact Widget, build and sent the email.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact;

use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Actions_Base;


/**
 * Class Contact_Public
 *
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact
 */
class Contact_Public extends Elementor_Widget_Actions_Base {

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'contact';

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array Must contain the following keys: `email`, `name`, `message` but it can also have extra keys
	 * @param $widget_id string
	 * @param $post_id string
	 *
	 * @return mixed
	 */
	public function rest_submit_form( $return, $data, $widget_id, $post_id ) {

	    $settings = $this->get_widget_settings( $widget_id, $post_id );

	    if( empty( $settings ) ){
	        return $return;
        }

		/**
		 * Bail if there is nowhere to send the email.
		 */
		if ( ! isset( $settings['to_send_email'] ) || ! is_email( $settings['to_send_email'] ) ) {
			$return['message'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );
			return $return;
		}

		foreach( $settings['form_fields'] as $field ) {
			$key = ! empty( $field['label'] ) ? sanitize_title( $field['label'] ) : ( ! empty( $field['placeholder'] ) ? sanitize_title( $field['placeholder'] ) : 'field_' . $field['_id'] );
			if ( ! empty( $field['key'] ) ){
				$key = $field['key'];
			}

			if ( 'required' === $field['requirement'] && empty( $data[ $key ] ) ) {
                $return['message'] = sprintf( esc_html__( 'Missing %s', 'textdomain'), $key );
                return $return;
			}
			if( 'email' === $field['type']  && ! is_email( $data[$key] ) ){
				$return['message'] = esc_html__( 'Invalid email.', 'textdomain' );
				return $return;
			}
		}

		$from    = isset( $data['email'] ) ? $data['email'] : null;
		$name    = isset( $data['name'] ) ? $data['name'] : null;

		// prepare settings for submit
		$result = $this->_send_mail( $settings['to_send_email'], $from, $name, $data );

		$return['message'] = esc_html__( 'Oops! I cannot send this email!', 'textdomain' );
		if ( $result ) {
			$return['success'] = true;
			$return['message'] = esc_html__( 'Your message has been sent!', 'textdomain' );
		}

		return $return;
	}

	/**
	 * Mail sender method
	 *
	 * @param $mailto
	 * @param $mailfrom
	 * @param $name
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	private function _send_mail( $mailto, $mailfrom, $name, $extra_data = array() ) {

		$name     = sanitize_text_field( $name );
		$subject  = 'Website inquiry from ' . ( ! empty( $name ) ? $name : 'N/A' );
		$mailto   = sanitize_email( $mailto );
		$mailfrom = sanitize_email( $mailfrom );
		$headers  = array();

		$headers[] = 'From: Admin <' . $mailto . '>';
		if ( ! empty( $mailfrom ) ) {
			$headers[] = 'Reply-To: ' . $name . ' <' . $mailfrom . '>';
		}
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$body = $this->prepare_body( $extra_data );

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
	 * @param array $data
	 *
	 * @return string
	 */
	private function prepare_body( $data ) {
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
					<?php esc_html_e( 'You received this email because your email address is set in the content form settings on ', 'textdomain' ) ?>
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
}

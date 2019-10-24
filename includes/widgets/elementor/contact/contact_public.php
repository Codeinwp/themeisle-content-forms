<?php
/**
 * This class handles the action part of the Contact Widget, build and sent the email.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact;

use Elementor\Plugin;


/**
 * Class Contact_Public
 *
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor\Contact
 */
class Contact_Public {

	/**
	 * Initialization function.
	 */
	public function init() {
		add_filter( 'content_forms_submit_contact', array( $this, 'rest_submit_form' ), 10, 5 );
	}

	/**
	 * Extract widget settings based on a widget id and a page id
	 *
	 * @param int $post_id The id of the post.
	 * @param string $widget_id The widget id.
	 *
	 * @return array|bool
	 */
	static function get_widget_settings( $widget_id, $post_id ) {

		$document = Plugin::$instance->documents->get( $post_id );
		$el_data = $document->get_elements_data();
		$el_data = apply_filters( 'elementor/frontend/builder_content_data', $el_data, $post_id );

		if ( ! empty( $el_data ) ) {
			return self::get_widget_data_by_id( $widget_id, $el_data );
		}

		return $el_data;
	}

	/**
	 * Recursively look through Elementor data and extract the settings for a specific widget.
	 *
	 * @param string $widget_id Widget id.
	 * @param $el_data
	 *
	 * @return array|bool
	 */
	static function get_widget_data_by_id( $widget_id, $el_data ) {

		if ( ! empty( $el_data ) ) {
			foreach ( $el_data as $el ) {

				if ( $el['elType'] === 'widget' && $el['id'] === $widget_id ) {
					return $el;
				} elseif ( ! empty( $el['elements'] ) ) {
					$el = self::get_widget_data_by_id( $widget_id, $el['elements'] );

					if ( $el ) {
						return $el;
					}
				}
			}
		}

		return false;
	}

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
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
		$settings = $this->get_widget_settings( $widget_id, $post_id )['settings'];

		/**
		 * Bail if there is nowhere to send the email.
		 */
		if ( ! isset( $settings['to_send_email'] ) || ! is_email( $settings['to_send_email'] ) ) {
			$return['message'] = esc_html__( 'Wrong email configuration! Please contact administration!', 'textdomain' );
			return $return;
		}

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


		// Empty email does not make much sense!
		$from = isset( $data['email'] ) ? $data['email'] : null;
		$name = isset( $data['name'] ) ? $data['name'] : null;


		// Empty message does not make much sense!
		$message = isset( $data['message'] ) ? $data['message'] : null;

		// prepare settings for submit
		$result = $this->_send_mail( $settings['to_send_email'], $from, $name, $message, $data );

		$return['message'] = esc_html__( 'Oops! I cannot send this email!', 'textdomain' );
		if ( $result ) {
			$return['success'] = true;
			$return['message'] = esc_html__( 'Your message has been sent!', 'textdomain' );
		} else {
			$return['message'] = esc_html__( 'We failed to send your message!', 'textdomain' );
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
}

<?php
/**
 *
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor;

use Elementor\Plugin;

/**
 * Class Elementor_Widget_Actions_Base
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor
 */
abstract class Elementor_Widget_Actions_Base {

	/**
	 * Extract widget settings based on a widget id and a page id
	 *
	 * @param int $post_id The id of the post.
	 * @param string $widget_id The widget id.
	 *
	 * @return array|bool
	 */
	static function get_widget_settings( $widget_id, $post_id ) {

		$document      = Plugin::$instance->documents->get( $post_id );
		$elements_data = $document->get_elements_data();

		//Filters the builder content in the frontend.
		$elements_data = apply_filters( 'elementor/frontend/builder_content_data', $elements_data, $post_id );
		if ( ! empty( $elements_data ) ) {
			$data = self::get_widget_data_by_id( $widget_id, $elements_data );
			if( array_key_exists( 'settings', $data ) ){
				return $data['settings'];
			}
		}

		return $elements_data;
	}

	/**
	 * Recursively look through Elementor data and extract the settings for a specific widget.
	 *
	 * @param string $widget_id Widget id.
	 * @param array $elements_data Elements data.
	 *
	 * @return array|bool
	 */
	static function get_widget_data_by_id( $widget_id, $elements_data ) {
		if ( empty( $elements_data ) ) {
			return false;
		}

		foreach ( $elements_data as $el ) {

			if ( $el['elType'] === 'widget' && $el['id'] === $widget_id ) {
				return $el;
			}

			if ( ! empty( $el['elements'] ) ) {
				$el = self::get_widget_data_by_id( $widget_id, $el['elements'] );

				if ( $el ) {
					return $el;
				}
			}
		}

		return false;
	}

	/**
	 * Initialization function.
	 */
	public function init() {
		add_filter( 'content_forms_submit_contact', array( $this, 'rest_submit_form' ), 10, 4 );
	}

	/**
	 * Rest submit form for each Elementor Widget.
	 *
	 * @param $return
	 * @param $data
	 * @param $widget_id
	 * @param $post_id
	 *
	 * @return mixed
	 */
	abstract function rest_submit_form( $return, $data, $widget_id, $post_id );

}

<?php
/**
 * Beaver Newsletter Widget main class.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

require_once 'beaver_widget_base.php';

/**
 * Class Newsletter_Admin
 * @package ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver
 */
class Newsletter_Admin extends Beaver_Widget_Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	function get_widget_name() {
		return esc_html__( 'Newsletter Form', 'textdomain' );
	}

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'newsletter';
	}

	/**
	 * Get default form data.
	 *
	 * @param string $field Field name.
	 * @return array | string | bool
	 */
	public function get_default( $field ){
		$default = array(
			'fields' => array(
				array(
					'key'         => 'email',
					'label'       => esc_html__( 'Email', 'textdomain' ),
					'placeholder' => esc_html__( 'Email', 'textdomain' ),
					'type'        => 'email',
					'field_width' => '100',
					'required' => 'required',
					'field_map' => 'email',
				),
			),
			'submit_label' => esc_html__( 'Join Newsletter', 'textdomain' ),
			'success_message' => esc_html__( 'Welcome to our newsletter!', 'textdomain' ),
			'error_message' => esc_html__( 'Action failed!', 'textdomain' ),
		);

		if( array_key_exists( $field, $default ) ){
			return $default[$field];
		}
		return false;
	}

	/**
	 * Newsletter_Admin constructor.
	 */
	public function __construct() {
		$this->run_hooks();
		parent::__construct(
			array(
				'name'        => esc_html__( 'Newsletter', 'textdomain' ),
				'description' => esc_html__( 'A simple newsletter form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
	}

	/**
	 * Run hooks and filters.
	 */
	private function run_hooks(){
		add_filter( $this->get_type() . '_repeater_fields', array( $this, 'add_widget_repeater_fields'));
		add_filter( $this->get_type() . '_controls_fields', array( $this, 'add_widget_specific_controls'));
	}

	/**
	 * Add map field for Newsletter field
	 * @param array $fields Repeater fields.
	 * @return array
	 */
	public function add_widget_repeater_fields( $fields ){

		$fields['field_map'] = array(
			'label'       => __( 'Map field to', 'textdomain' ),
			'type'        => 'text',
			'description' => esc_html__( 'If you\'re using SendInBlue and you map the field to address, please ignore the additional settings.', 'textdomain' ),
		);
		return $fields;
	}

	/**
	 * Add specific controls for this type of widget.
	 *
	 * @param array $fields Fields config.
	 * TODO
	 * @return array
	 */
	public function add_widget_specific_controls( $fields ){
		return $fields;
	}
}

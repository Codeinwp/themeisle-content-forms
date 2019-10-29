<?php
/**
 * This class handel the Elementor Widgets registration, category registration and all Elementor related actions.
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor;

use Elementor\Plugin;

/**
 * Class Elementor_Widget_Manager
 */
class Elementor_Widget_Manager {

	/**
	 * Type of Widget Forms.
	 *
	 * @var $forms
	 */
	public static $forms = [ 'contact', 'newsletter', 'registration' ];

	/**
	 * Initialization Function
	 */
	public function init(){
		// Register Orbit Fox Category in Elementor Dashboard
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );

		// Register Orbit Fox Elementor Widgets
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widget' ) );

		// Register the actions that forms do
		add_action( 'rest_api_init', array( $this, 'register_widgets_actions' ) );
	}

	/**
	 * Register the category for widgets.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'obfx-elementor-widgets',
			[
				'title' => __( 'Orbit Fox Addons', 'textdomain' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * Register Elementor Widgets that are added in Orbit Fox.
	 */
	public function register_elementor_widget(){
		foreach ( self::$forms as $form ){
			$widget = '\ThemeIsle\ContentForms\Includes\Widgets\Elementor\\' . ucwords( $form ) . '\\' . ucwords( $form ) . '_Admin';
			Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
		}
	}

	/**
	 * Register Elementor Widgets actions.
	 */
	public function register_widgets_actions(){
		foreach ( self::$forms as $form ){
			$admin_class  = '\ThemeIsle\ContentForms\Includes\Widgets\Elementor\\' . ucwords( $form ) . '\\' . ucwords( $form ) . '_Public';
			if( class_exists( $admin_class ) ){
				$admin = new $admin_class();
				$admin->init();
			}
		}
	}

	/**
	 * Get the field key based on form attributes.
	 *
	 * @param array $field Field data.
	 * @param string $provider Provider name.
	 *
	 * @return string
	 */
	public static function get_field_key_name( $field, $provider ){
		$field_map_name = !empty($provider) ? $provider . '_field_map' : 'field_map';
		if( array_key_exists( $field_map_name, $field ) && ! empty( $field[$field_map_name] ) ){
			return strtoupper( $field[$field_map_name] );
		}

		if( ! empty( $field['label'] ) ){
			return sanitize_title( $field['label'] );
		}

		if( ! empty( $field['placeholder'] ) ){
			return sanitize_title( $field['placeholder'] );
		}

		return 'field_'.$field['_id'];
	}
}

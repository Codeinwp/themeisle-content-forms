<?php
/**
 *
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Beaver;

/**
 * Class Beaver_Widget_Manager\
 */
class Beaver_Widget_Manager{

	/**
	 * Type of Widget Forms.
	 *
	 * @var $forms
	 */
	public static $forms = [ 'contact' ];

	/*
	 * Register beaver modules
	 */
	public function register_beaver_module(){
		if ( ! class_exists( '\FLBuilderModel' ) ) {
			return false;
		}

		foreach ( self::$forms as $form ) {
			require_once $form . '/' . $form . '_admin.php';
			$classname = '\ThemeIsle\ContentForms\Includes\Widgets\Beaver\\' . ucwords( $form ) . '\\' . ucwords( $form ) . '_Admin';
			$module = new $classname(
				array(
					'id'                   => 'content_form_' . $form,
					'type'                 => $form,
				)
			);
			$module->register_widget();
		}
	}

}

<?php
/**
 * Main class for Elementor Registration Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets/elementor/elementor_widget_base.php';

/**
 * Class Registration_Admin
 *
 * @package ThemeIsle\ContentForms\Includes\Widgets\Elementor\Registration
 */
class Registration_Admin extends Elementor_Widget_Base {

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'registration';

	/**
	 * @var array
	 */
	public $strings = array();

	/**
	 * Elementor Widget Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'content_form_registration';
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Registration Form', 'textdomain' );
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	function get_default_config() {
		return array(
			array(
				'key'         => 'username',
				'type'        => 'text',
				'label'       => esc_html__( 'User Name', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'User Name', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
			),
			array(
				'key'         => 'password',
				'type'        => 'password',
				'label'       => esc_html__( 'Password', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Password', 'textdomain' ),
				'field_width' => '100',
			),
		);
	}

	/**
	 * Add specific form fields for Registration Widget.
	 */
	function add_specific_form_fields() {
		//      $repeater = new Repeater();
		//
		//      $repeater->add_control(
		//          'requirement',
		//          array(
		//              'label'        => __( 'Required', 'textdomain' ),
		//              'type'         => Controls_Manager::SWITCHER,
		//              'return_value' => 'required',
		//              'default'      => '',
		//          )
		//      );
		//
		//      $field_types = array(
		//          'text'     => __( 'Text', 'textdomain' ),
		//          'password' => __( 'Password', 'textdomain' ),
		//          'email'    => __( 'Email', 'textdomain' ),
		//          'textarea' => __( 'Textarea', 'textdomain' ),
		//      );
		//
		//      $repeater->add_control(
		//          'type',
		//          array(
		//              'label'   => __( 'Type', 'textdomain' ),
		//              'type'    => Controls_Manager::SELECT,
		//              'options' => $field_types,
		//              'default' => 'text',
		//          )
		//      );
		//
		//      $repeater->add_control(
		//          'key',
		//          array(
		//              'label' => __( 'Key', 'textdomain' ),
		//              'type'  => Controls_Manager::HIDDEN,
		//          )
		//      );
		//
		//      $repeater->add_responsive_control(
		//          'field_width',
		//          [
		//              'label'   => __( 'Field Width', 'textdomain' ),
		//              'type'    => Controls_Manager::SELECT,
		//              'options' => [
		//                  '100' => '100%',
		//                  '75'  => '75%',
		//                  '66'  => '66%',
		//                  '50'  => '50%',
		//                  '33'  => '33%',
		//                  '25'  => '25%',
		//              ],
		//              'default' => '100',
		//          ]
		//      );
		//
		//      $repeater->add_control(
		//          'label',
		//          array(
		//              'label'   => __( 'Label', 'textdomain' ),
		//              'type'    => Controls_Manager::TEXT,
		//              'default' => '',
		//          )
		//      );
		//
		//      $repeater->add_control(
		//          'placeholder',
		//          array(
		//              'label'   => __( 'Placeholder', 'textdomain' ),
		//              'type'    => Controls_Manager::TEXT,
		//              'default' => '',
		//          )
		//      );
		//
		//      $field_types = array(
		//          'first_name'   => __( 'First Name', 'textdomain' ),
		//          'last_name'    => __( 'Last Name', 'textdomain' ),
		//          'user_pass'    => __( 'Password', 'textdomain' ),
		//          'user_login'   => __( 'Username', 'textdomain' ),
		//          'user_email'   => __( 'Email', 'textdomain' ),
		//          'display_name' => __( 'Display Name', 'textdomain' ),
		//      );
		//      $repeater->add_control(
		//          'field_map',
		//          array(
		//              'label'   => __( 'Map field to', 'textdomain' ),
		//              'type'    => Controls_Manager::SELECT,
		//              'options' => $field_types,
		//              'default' => 'text',
		//          )
		//      );
		//
		//      $default_fields = $this->get_default_config();
		//      $this->add_control(
		//          'form_fields',
		//          array(
		//              'label'       => __( 'Form Fields', 'textdomain' ),
		//              'type'        => Controls_Manager::REPEATER,
		//              'show_label'  => false,
		//              'separator'   => 'before',
		//              'fields'      => array_values( $repeater->get_controls() ),
		//              'default'     => $default_fields,
		//              'title_field' => '{{{ label }}}',
		//          )
		//      );
	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {
		$this->add_control(
			'submit_label',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Submit', 'textdomain' ),
				'default' => esc_html__( 'Register', 'textdomain' ),
			)
		);

		$this->add_responsive_control(
			'align_submit',
			[
				'label'     => __( 'Alignment', 'textdomain' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'left',
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'textdomain' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'textdomain' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'textdomain' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .content-form .submit-form' => 'text-align: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Add specific widget settings.
	 */
	function add_widget_specific_settings() {
		return false;
	}
}

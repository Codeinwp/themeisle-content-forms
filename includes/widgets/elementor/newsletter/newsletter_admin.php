<?php
/**
 * Main class for Elementor Newsletter Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use Elementor\Controls_Manager;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

/**
 * Class Newsletter_Admin
 */
class Newsletter_Admin extends Elementor_Widget_Base{

	/**
	 * The type of current widget form.
	 *
	 * @var string
	 */
	public $form_type = 'newsletter';

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
		return 'content_form_newsletter';
	}

	/**
	 * The default values for current widget.
	 *
	 * @return array
	 */
	function get_default_config() {
		return array(
			array(
				'key'         => 'email',
				'type'        => 'email',
				'label'       => esc_html__( 'Email', 'textdomain' ),
				'require'     => 'required',
				'placeholder' => esc_html__( 'Email', 'textdomain' ),
				'field_width' => '100',
				'field_map'   => 'email',
			)
		);
	}

	/**
	 * Get Widget Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Newsletter Form', 'textdomain' );
	}

	/**
	 * Additional fields for repeater.
	 *
	 * @param Object $repeater Repeater instance.
	 */
	function add_specific_fields_for_repeater( $repeater ) {
		$field_types = array(
			'email'     => __( 'Email', 'textdomain' ),
			'fname'    => __( 'First Name', 'textdomain' ),
			'lname'    => __( 'Last Name', 'textdomain' ),
			'address' => __( 'Address', 'textdomain' ),
			'phone' => __( 'Phone', 'textdomain' ),
			'birthday' => __( 'Birth Day', 'textdomain' ),
		);


		$repeater->add_control(
			'field_map',
			array(
				'label'   => __( 'Map field to', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text'
			)
		);

		$config = array(
			'addr2' => array(
				'label' => array( 'label' => __( 'Line 2 Label', 'textdomain' ) , 'default' => __('Address Line 2', 'textdomain' ) ),
				'placeholder' => array( 'label' => __( 'Line 2 Placeholder', 'textdomain' ) , 'default' => __('Address Line 2', 'textdomain' ) ),
				'width' => array( 'label' => __( 'Line 2 Width', 'textdomain' ) , 'default' => '100'),
			),
			'city' => array(
				'label' => array( 'label' => __( 'City Label', 'textdomain' ) , 'default' => __('City', 'textdomain' ) ),
				'placeholder' => array( 'label' => __( 'City Placeholder', 'textdomain' ) , 'default' => __('City', 'textdomain' ) ),
				'width' => array( 'label' => __( 'City Width', 'textdomain' ) , 'default' => '100'),
			),
			'state' => array(
				'label' => array( 'label' => __( 'State Label', 'textdomain' ) , 'default' => __('State/Province/Region', 'textdomain' ) ),
				'placeholder' => array( 'label' => __( 'State Placeholder', 'textdomain' ) , 'default' => __('State/Province/Region', 'textdomain' ) ),
				'width' => array( 'label' => __( 'State Width', 'textdomain' ) , 'default' => '100'),
			),
			'zip' => array(
				'label' => array( 'label' => __( 'Zip Code Label', 'textdomain' ) , 'default' => __('Postal / Zip Code', 'textdomain' ) ),
				'placeholder' => array( 'label' => __( 'Zip Code Placeholder', 'textdomain' ) , 'default' => __('Postal / Zip Code', 'textdomain' ) ),
				'width' => array( 'label' => __( 'Zip Code Width', 'textdomain' ) , 'default' => '100'),
			),
			'country' => array(
				'label' => array( 'label' => __( 'Country Label', 'textdomain' ) , 'default' => __('Country', 'textdomain' ) ),
				'placeholder' => array( 'label' => __( ' Country Placeholder', 'textdomain' ) , 'default' => __('Country', 'textdomain' ) ),
				'width' => array( 'label' => __( 'Country Width', 'textdomain' ) , 'default' => '100'),
			),
		);

		foreach ( $config as $main_field => $field_value ){
			foreach ( $field_value as $specific_field => $field_data ){
				$key = $main_field . '_' . $specific_field;
				if( $specific_field !== 'width' ){
					$repeater->add_control(
						$key,
						array(
							'label'   => $field_data['label'],
							'type'    => Controls_Manager::TEXT,
							'default' => $field_data['default'],
							'condition' => [
								'field_map' => 'address',
							],
						)
					);
				} else {
					$repeater->add_responsive_control(
						$key,
						array(
							'label'   => $field_data['label'],
							'type'    => Controls_Manager::SELECT,
							'options' => [
								'100' => '100%',
								'75'  => '75%',
								'66'  => '66%',
								'50'  => '50%',
								'33'  => '33%',
								'25'  => '25%',
							],
							'default' =>  $field_data['default'],
							'condition' => [
								'field_map' => 'address',
							],
						)
					);
				}
			}
		}
	}

	/**
	 * Add specific form fields for Newsletter widget.
	 */
	function add_specific_form_fields() {
		return false;
	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {
		$this->strings['submit_button_label'] = esc_html__( 'Join Newsletter', 'textdomain' );

		$this->add_control(
			'success_message',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Success message', 'textdomain' ),
				'default'     => esc_html__( 'Welcome to our newsletter!', 'textdomain' ),
			)
		);

		$this->add_control(
			'error_message',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Error message', 'textdomain' ),
				'default'     => esc_html__( 'Action failed!', 'textdomain' ),
			)
		);

		$this->add_control(
			'provider',
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Subscribe to', 'textdomain' ),
				'description' => esc_html__( 'Where to send the email?', 'textdomain' ),
				'options'     => array(
					'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
					'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' )
				)
			)
		);

		$this->add_control(
			'access_key',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Access Key', 'textdomain' ),
				'description' => esc_html__( 'Provide an access key for the selected service', 'textdomain' ),
				'required' => true
			)
		);

		$this->add_control(
			'list_id',
			array(
				'type'  => 'text',
				'label' => esc_html__( 'List ID', 'textdomain' ),
				'description' => esc_html__( 'The List ID (based on the seleced service) where we should subscribe the user', 'textdomain' ),
				'required' => true
			)
		);

		$this->add_control(
			'button_icon',
			[
				'label' => __( 'Submit Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'condition' => [
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button-icon' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}
}

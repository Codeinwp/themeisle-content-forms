<?php
/**
 * Main class for Elementor Newsletter Form Custom Widget
 *
 * @package ContentForms
 */

namespace ThemeIsle\ContentForms\Includes\Widgets\Elementor\Newsletter;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ThemeIsle\ContentForms\Includes\Widgets\Elementor\Elementor_Widget_Base;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets/elementor/elementor_widget_base.php';

/**
 * Class Newsletter_Admin
 */
class Newsletter_Admin extends Elementor_Widget_Base {

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
		return [
			[
				'key'                  => 'email',
				'type'                 => 'email',
				'label'                => esc_html__( 'Email', 'textdomain' ),
				'requirement'          => 'required',
				'placeholder'          => esc_html__( 'Email', 'textdomain' ),
				'field_width'          => '100',
				'mailchimp_field_map'  => 'email',
				'sendinblue_field_map' => 'email',
			],
		];
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
	 * Add necessary fields for sendinblue subscribe form.
	 *
	 * @param object $repeater Repeater object.
	 */
	private function add_sendinblue_fields( $repeater ) {
		$field_types = array(
			'email'   => __( 'Email', 'textdomain' ),
			'name'    => __( 'Name', 'textdomain' ),
			'surname' => __( 'Surname', 'textdomain' ),
		);
		$repeater->add_control(
			'sendinblue_field_map',
			array(
				'label'   => __( 'Map field to', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			)
		);

		$repeater->remove_control( 'mailchimp_field_map' );

		$default_fields = $this->get_default_config();
		$this->add_control(
			'sendinblue_form_fields',
			array(
				'label'       => __( 'Form Fields', 'textdomain' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => false,
				'separator'   => 'before',
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => $default_fields,
				'title_field' => '{{{ label }}}',
				'condition'   => [
					'provider' => 'sendinblue',
				],
			)
		);
	}

	/**
	 * Add necessary fields for mailchimp subscribe form.
	 *
	 * @param object $repeater Repeater object.
	 */
	private function add_mailchimp_fields( $repeater ) {
		$field_types = array(
			'email'    => __( 'Email', 'textdomain' ),
			'fname'    => __( 'First Name', 'textdomain' ),
			'lname'    => __( 'Last Name', 'textdomain' ),
			'address'  => __( 'Address', 'textdomain' ),
			'phone'    => __( 'Phone', 'textdomain' ),
			'birthday' => __( 'Birth Day', 'textdomain' ),
		);
		$repeater->add_control(
			'mailchimp_field_map',
			array(
				'label'   => __( 'Map field to', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			)
		);
		$config = array(
			'addr2'   => array(
				'label'       => array(
					'label'   => __( 'Line 2 Label', 'textdomain' ),
					'default' => __( 'Address Line 2', 'textdomain' ),
				),
				'placeholder' => array(
					'label'   => __( 'Line 2 Placeholder', 'textdomain' ),
					'default' => __( 'Address Line 2', 'textdomain' ),
				),
				'width'       => array(
					'label'   => __( 'Line 2 Width', 'textdomain' ),
					'default' => '100',
				),
			),
			'city'    => array(
				'label'       => array(
					'label'   => __( 'City Label', 'textdomain' ),
					'default' => __( 'City', 'textdomain' ),
				),
				'placeholder' => array(
					'label'   => __( 'City Placeholder', 'textdomain' ),
					'default' => __( 'City', 'textdomain' ),
				),
				'width'       => array(
					'label'   => __( 'City Width', 'textdomain' ),
					'default' => '100',
				),
			),
			'state'   => array(
				'label'       => array(
					'label'   => __( 'State Label', 'textdomain' ),
					'default' => __( 'State/Province/Region', 'textdomain' ),
				),
				'placeholder' => array(
					'label'   => __( 'State Placeholder', 'textdomain' ),
					'default' => __( 'State/Province/Region', 'textdomain' ),
				),
				'width'       => array(
					'label'   => __( 'State Width', 'textdomain' ),
					'default' => '100',
				),
			),
			'zip'     => array(
				'label'       => array(
					'label'   => __( 'Zip Code Label', 'textdomain' ),
					'default' => __( 'Postal / Zip Code', 'textdomain' ),
				),
				'placeholder' => array(
					'label'   => __( 'Zip Code Placeholder', 'textdomain' ),
					'default' => __( 'Postal / Zip Code', 'textdomain' ),
				),
				'width'       => array(
					'label'   => __( 'Zip Code Width', 'textdomain' ),
					'default' => '100',
				),
			),
			'country' => array(
				'label'       => array(
					'label'   => __( 'Country Label', 'textdomain' ),
					'default' => __( 'Country', 'textdomain' ),
				),
				'placeholder' => array(
					'label'   => __( ' Country Placeholder', 'textdomain' ),
					'default' => __( 'Country', 'textdomain' ),
				),
				'width'       => array(
					'label'   => __( 'Country Width', 'textdomain' ),
					'default' => '100',
				),
			),
		);
		foreach ( $config as $main_field => $field_value ) {
			foreach ( $field_value as $specific_field => $field_data ) {
				$key = $main_field . '_' . $specific_field;
				if ( $specific_field !== 'width' ) {
					$repeater->add_control(
						$key,
						array(
							'label'     => $field_data['label'],
							'type'      => Controls_Manager::TEXT,
							'default'   => $field_data['default'],
							'condition' => [
								'mailchimp_field_map' => 'address',
							],
						)
					);
				} else {
					$repeater->add_responsive_control(
						$key,
						array(
							'label'     => $field_data['label'],
							'type'      => Controls_Manager::SELECT,
							'options'   => [
								'100' => '100%',
								'75'  => '75%',
								'66'  => '66%',
								'50'  => '50%',
								'33'  => '33%',
								'25'  => '25%',
							],
							'default'   => $field_data['default'],
							'condition' => [
								'mailchimp_field_map' => 'address',
							],
						)
					);
				}
			}
		}

		$default_fields = $this->get_default_config();
		$this->add_control(
			'mailchimp_form_fields',
			array(
				'label'       => __( 'Form Fields', 'textdomain' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => false,
				'separator'   => 'before',
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => $default_fields,
				'title_field' => '{{{ label }}}',
				'condition'   => [
					'provider' => 'mailchimp',
				],
			)
		);
	}

	/**
	 * Add specific form fields for Newsletter widget.
	 */
	function add_specific_form_fields() {

		$repeater = new Repeater();

		$repeater->add_control(
			'requirement',
			array(
				'label'        => __( 'Required', 'textdomain' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'required',
			)
		);

		$field_types = array(
			'text'     => __( 'Text', 'textdomain' ),
			'password' => __( 'Password', 'textdomain' ),
			'email'    => __( 'Email', 'textdomain' ),
			'textarea' => __( 'Textarea', 'textdomain' ),
		);
		$repeater->add_control(
			'type',
			array(
				'label'   => __( 'Type', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			)
		);

		$repeater->add_control(
			'key',
			array(
				'label' => __( 'Key', 'textdomain' ),
				'type'  => Controls_Manager::HIDDEN,
			)
		);

		$repeater->add_responsive_control(
			'field_width',
			[
				'label'   => __( 'Field Width', 'textdomain' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'75'  => '75%',
					'66'  => '66%',
					'50'  => '50%',
					'33'  => '33%',
					'25'  => '25%',
				],
				'default' => '100',
			]
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'textdomain' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label'   => __( 'Placeholder', 'textdomain' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_mailchimp_fields( $repeater );
		$this->add_sendinblue_fields( $repeater );

	}

	/**
	 * Add specific settings for Newsletter widget.
	 */
	function add_specific_settings_controls() {

		$this->add_control(
			'provider',
			[
				'type'      => 'select',
				'label'     => esc_html__( 'Subscribe to', 'textdomain' ),
				'options'   => [
					'mailchimp'  => esc_html__( 'MailChimp', 'textdomain' ),
					'sendinblue' => esc_html__( 'Sendinblue ', 'textdomain' ),
				],
				'default'   => 'mailchimp',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'success_message',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Success message', 'textdomain' ),
				'default' => esc_html__( 'Welcome to our newsletter!', 'textdomain' ),
			)
		);

		$this->add_control(
			'error_message',
			array(
				'type'      => 'text',
				'label'     => esc_html__( 'Error message', 'textdomain' ),
				'default'   => esc_html__( 'Action failed!', 'textdomain' ),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'submit_label',
			array(
				'type'    => 'text',
				'label'   => esc_html__( 'Submit', 'textdomain' ),
				'default' => esc_html__( 'Join Newsletter', 'textdomain' ),
			)
		);

		$this->add_control(
			'button_icon',
			[
				'label'       => __( 'Icon', 'elementor-pro' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label'     => __( 'Icon Spacing', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
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
	 * Add widget specific settings.
	 * //TODO
	 * @return mixed|void
	 */
	function add_widget_specific_settings() {

		$this->start_controls_section(
			'provider_settings',
			array(
				'label' => __( 'Provider Settings', 'textdomain' ),
			)
		);

		$this->add_control(
			'access_key',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'Access Key', 'textdomain' ),
				'required' => true,
			)
		);

		$this->add_control(
			'list_id',
			array(
				'type'     => 'text',
				'label'    => esc_html__( 'List ID', 'textdomain' ),
				'required' => true,
			)
		);

		$this->end_controls_section();
	}
}

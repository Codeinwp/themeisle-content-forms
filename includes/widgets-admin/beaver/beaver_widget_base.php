<?php

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

use ThemeIsle\ContentForms\Form_Manager;
use ThemeIsle\ContentForms\Includes\Admin\Widget_Actions_Base;

/**
 * This class is used to create an Beaver module based on a ContentForms config
 * Class BeaverModule
 * @package ThemeIsle\ContentForms
 */
abstract class Beaver_Widget_Base extends \FLBuilderModule {

	/**
	 * Form type
	 *
	 * @var array
	 */
	public $form_type;

	/**
	 * Current module settings.
	 *
	 * @var array
	 */
	public $module_settings;

	/**
	 * Widget default data.
	 *
	 * @var array
	 */
	public $default_data = array();

	/**
	 * Beaver_Widget_Base constructor.
	 *
	 * @param $data
	 */
	public function __construct( $data ) {

		$this->run_hooks();

		$this->default_data    = $this->widget_default_values();
		$this->module_settings = $this->get_module_settings();

		parent::__construct( $data );
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();
		$this->add_js( 'content-forms' );
		$this->add_css( 'content-forms' );
	}

	/**
	 * Run hooks and filters.
	 */
	public function run_hooks() {
		add_filter( 'fl_builder_register_settings_form', array( $this, 'filter_widget_settings' ), 10, 2 );
		add_filter( $this->get_type() . '_repeater_fields', array( $this, 'add_widget_repeater_fields' ) );
		add_filter( $this->get_type() . '_controls_fields', array( $this, 'add_widget_specific_controls' ) );
	}


	/**
	 * Filter the form settings.
	 *
	 * @param array $form Form settings
	 * @param static $slug Form slug
	 *
	 * @return array
	 */
	public function filter_widget_settings( $form, $slug ) {
		$form_widgets = array( 'newsletter_admin', 'contact_admin', 'registration_admin' );
		if ( in_array( $slug, $form_widgets, true ) ) {
			return $this->module_settings;
		}

		return $form;
	}

	/**
	 * Beaver Widget style settings.
	 *
	 * @param array $args Settings array.
	 *
	 * @return array
	 */
	private function get_style_settings( $args ) {
		$args['style']['sections'] = array(
			'spacing'      => array(
				'title'  => esc_html__( 'Spacing', 'textdomain' ),
				'fields' => array(
					'column_gap' => array(
						'type'    => 'unit',
						'units'   => array( 'px' ),
						'label'   => __( 'Columns Gap', 'textdomain' ),
						'default' => 0,
						'slider'  => array(
							'min'  => 0,
							'max'  => 60,
							'step' => 1,
						),
						'preview' => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.content-form-' . $this->get_type() . ' fieldset',
									'property' => 'padding-right',
								),
								array(
									'selector' => '.content-form-' . $this->get_type() . ' fieldset',
									'property' => 'padding-left',
								),
							),
						),
					),
					'row_gap'    => array(
						'type'    => 'unit',
						'units'   => array( 'px' ),
						'label'   => __( 'Rows Gap', 'textdomain' ),
						'default' => 0,
						'slider'  => array(
							'min'  => 0,
							'max'  => 60,
							'step' => 1,
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset',
							'property' => 'margin-bottom',
						),
					),
				),
			),
			'label'        => array(
				'title'  => esc_html__( 'Label', 'textdomain' ),
				'fields' => array(
					'label_spacing'       => array(
						'type'    => 'unit',
						'units'   => array( 'px' ),
						'label'   => __( 'Label Spacing', 'textdomain' ),
						'default' => 0,
						'slider'  => array(
							'min'  => 0,
							'max'  => 60,
							'step' => 1,
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset label',
							'property' => 'padding-bottom',
						),
					),
					'label_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset label',
							'property' => 'color',
						),

					),
					'mark_required_color' => array(
						'type'       => 'color',
						'label'      => __( 'Mark Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset label .required-mark',
							'property' => 'color',
						),
					),
					'label_typography'    => array(
						'type'       => 'typography',
						'label'      => __( 'Label Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset label',
						),
					),
				),
			),
			'field'        => array(
				'title'  => esc_html__( 'Field', 'textdomain' ),
				'fields' => array(
					'field_typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Field Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset input, .content-form-' . $this->get_type() . ' fieldset select, .content-form-' . $this->get_type() . ' fieldset textarea, .content-form-' . $this->get_type() . ' fieldset button',
						),
					),
					'field_text_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset input, .content-form-' . $this->get_type() . ' fieldset textarea, .content-form-' . $this->get_type() . ' fieldset select, .content-form-' . $this->get_type() . ' fieldset input::placeholder, .content-form-' . $this->get_type() . ' fieldset textarea::placeholder, .content-form-' . $this->get_type() . ' fieldset select::placeholder',
							'property' => 'color',
						),
					),
					'field_background_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset input, .content-form-' . $this->get_type() . ' fieldset textarea, .content-form-' . $this->get_type() . ' fieldset select',
							'property' => 'background-color',
						),
					),
					'field_border_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset input, .content-form-' . $this->get_type() . ' fieldset textarea, .content-form-' . $this->get_type() . ' fieldset select',
							'property' => 'border-color',
						),
					),
					'field_border'           => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset input, .content-form-' . $this->get_type() . ' fieldset textarea, .content-form-' . $this->get_type() . ' fieldset select',
						),
					),
				),
			),
			'button'       => array(
				'title'  => esc_html__( 'Submit Button', 'textdomain' ),
				'fields' => array(
					'button_background_color' => array(
						'type'       => 'color',
						'label'      => __( 'Button Background Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button',
							'property' => 'background-color',
						),
					),
					'button_text_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Button Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button',
							'property' => 'color',
						),
					),
					'button_typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button',
						),
					),
					'button_border'           => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button',
						),
					),
				),
			),
			'button_hover' => array(
				'title'  => esc_html__( 'Submit Button Hover', 'textdomain' ),
				'fields' => array(
					'button_background_color_hover' => array(
						'type'       => 'color',
						'label'      => __( 'Button Background Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button:hover',
							'property' => 'background-color',
						),
					),
					'button_text_color_hover'       => array(
						'type'       => 'color',
						'label'      => __( 'Button Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button:hover',
							'property' => 'color',
						),
					),
					'button_typography_hover'       => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button:hover',
						),
					),
					'button_border_hover'           => array(
						'type'       => 'border',
						'label'      => __( 'Border Hover', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->get_type() . ' fieldset.submit-form button:hover',
						),
					),
				),
			),
		);
		return $args;
	}

	/**
	 * Beaver Widget form settings.
	 *
	 * @param array $args Settings array.
	 *
	 * @return array
	 */
	private function get_form_settings( $args ) {
		$args['general']['sections']['settings'] = array(
			'title'  => esc_html__( 'Fields', 'textdomain' ),
			'fields' => array(
				'fields' => array(
					'multiple'     => true,
					'type'         => 'form',
					'label'        => esc_html__( 'Field', 'textdomain' ),
					'form'         => $this->get_type() . '_field',
					'preview_text' => 'label',
					'default'      => $this->get_default( 'fields' ),
				),
			),
		);

		$repeater_fields = array(
			'label'       => array(
				'type'  => 'text',
				'label' => esc_html__( 'Label', 'textdomain' ),
			),
			'placeholder' => array(
				'type'  => 'text',
				'label' => esc_html__( 'Placeholder', 'textdomain' ),
			),
			'type'        => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Type', 'textdomain' ),
				'options' => array(
					'text'     => esc_html__( 'Text' ),
					'email'    => esc_html__( 'Email' ),
					'textarea' => esc_html__( 'Textarea', 'textdomain' ),
					'password' => esc_html__( 'Password', 'textdomain' ),
				),
			),
			'field_width' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Field Width', 'textdomain' ),
				'options'    => array(
					'100' => '100%',
					'75'  => '75%',
					'66'  => '66%',
					'50'  => '50%',
					'33'  => '33%',
					'25'  => '25%',
				),
				'responsive' => true,
				'default'    => '100',
			),
			'required'    => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Is required?', 'textdomain' ),
				'options' => array(
					'required' => esc_html__( 'Required', 'textdomain' ),
					'optional' => esc_html__( 'Optional', 'textdomain' ),
				),
			),
		);

		\FLBuilder::register_settings_form(
			$this->get_type() . '_field',
			array(
				'title' => esc_html__( 'Field', 'textdomain' ),
				'tabs'  => array(
					'general' => array(
						'title'    => esc_html__( 'Field', 'textdomain' ),
						'sections' => array(
							'fields' => array(
								'title'  => esc_html__( 'Field', 'textdomain' ),
								'fields' => apply_filters( $this->get_type() . '_repeater_fields', $repeater_fields ),
							),
						),
					),
				),
			)
		);

		return $args;
	}

	/**
	 * Beaver Widget controls settings.
	 *
	 * @param array $args Settings array.
	 *
	 * @return array
	 */
	private function get_control_settings( $args ) {
		$args['general']['sections']['controls'] = apply_filters(
			$this->get_type() . '_controls_fields',
			array(
				'title'  => esc_html__( 'Form Settings', 'textdomain' ),
				'fields' => array(
					'hide_label'      => array(
						'type'    => 'select',
						'label'   => __( 'Hide Label', 'textdomain' ),
						'default' => 'show',
						'options' => array(
							'hide' => esc_html__( 'Hide', 'textarea' ),
							'show' => esc_html__( 'Show', 'textarea' ),
						),
					),
					'submit_label'    => array(
						'type'        => 'text',
						'label'       => esc_html__( 'Submit', 'textdomain' ),
						'default'     => $this->get_default( 'submit_label' ),
						'description' => esc_html__( 'The Call To Action label', 'textdomain' ),
					),
					'submit_position' => array(
						'type'    => 'align',
						'label'   => esc_html__( 'Alignment', 'textdomain' ),
						'default' => 'left',
						'preview' => array( //TODO
							'type'     => 'css',
							'selector' => '.submit-form.' . $this->get_type(),
							'property' => 'text-align',
						),

					),
				),
			)
		);
		return $args;
	}

	/**
	 * Set module settings.
	 */
	private function get_module_settings() {

		$args = array(
			'general' => array(
				'title'    => $this->get_widget_name(),
				'sections' => array(),
			),
			'style'   => array(
				'title'    => esc_html__( 'Style', 'textdomain' ),
				'sections' => array(),
			),
		);

		$args = $this->get_style_settings( $args );
		$args = $this->get_form_settings( $args );
		$args = $this->get_control_settings( $args );

		return $args;
	}

	/**
	 * Get default config data.
	 *
	 * @param string $field Field to retrieve.
	 * @return array | string | bool
	 */
	public function get_default( $field ) {
		if ( ! array_key_exists( $field, $this->default_data ) ) {
			return false;
		}
		return $this->default_data[ $field ];
	}

	/**
	 * Render the header of the form based on the block id(for JS identification)
	 *
	 * @param $id
	 */
	public function render_form_header( $id ) {
		$url = admin_url( 'admin-post.php' );
		echo '<form action="' . esc_url( $url ) . '" method="post" name="content-form-' . $id . '" id="content-form-' . $id . '" class="content-form content-form-' . $this->get_type() . '">';
		wp_nonce_field( 'content-form-' . $id, '_wpnonce_' . $this->get_type() );
		echo '<input type="hidden" name="action" value="content_form_submit" />';
		echo '<input type="hidden" name="form-type" value="' . $this->get_type() . '" />';
		echo '<input type="hidden" name="form-builder" value="beaver" />';
		echo '<input type="hidden" name="post-id" value="' . get_the_ID() . '" />';
		echo '<input type="hidden" name="form-id" value="' . $id . '" />';
	}

	/**
	 * Render form errors.
	 *
	 * @return bool
	 */
	public function maybe_render_form_errors( $widget_id ){
		$has_error = false;
		if ( ! current_user_can( 'manage_options' ) ) {
			return $has_error;
		}
		$widget = $this->get_type();

		require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-public/widget_actions_base.php';
		$widget_settings = Widget_Actions_Base::get_beaver_module_settings_by_id( $widget_id, get_the_ID() );

		if( $widget === 'newsletter' ){

			echo '<div class="content-forms-required">';

			if ( array_key_exists( 'access_key', $widget_settings ) && empty( $widget_settings['access_key'] ) ) {
				echo '<p>';
				printf(
					esc_html__( 'The %s setting is required!', 'textdomain' ),
					'<strong>'. esc_html__('Access Key', 'textdomain' ) . '</strong>'
				);
				echo '</p>';
				$has_error = true;
			}

			if ( array_key_exists( 'list_id', $widget_settings ) && empty( $widget_settings['list_id'] ) ) {
				echo '<p>';
				printf(
					esc_html__( 'The %s setting is required!', 'textdomain' ),
					'<strong>'. esc_html__('List id', 'textdomain' ) . '</strong>'
				);
				echo '</p>';
				$has_error = true;
			}

			$form_fields = $widget_settings['fields'];
			$mapping     = array();
			foreach ( $form_fields as $field ) {
				$field_map = $field['field_map'];
				if ( in_array( $field_map, $mapping, true ) ) {
					echo '<p>';
					printf(
						esc_html__( 'The %s field is mapped to multiple form fields. Please check your field settings.', 'textdomain' ),
						'<strong>' . $field_map . '</strong>'
					);
					echo '</p>';
					$has_error = true;
				}
				array_push( $mapping, $field_map );
			}

			echo '</div>';

			return $has_error;
		}

		if ( $widget === 'contact' ) {
			if ( array_key_exists( 'to_send_email', $widget_settings ) && empty( $widget_settings['to_send_email'] ) ) {
				echo '<p>';
				printf(
					esc_html__( 'The %s setting is required!', 'textdomain' ),
					'<strong>' . esc_html__( 'Send to Email Address', 'textdomain' ) . '</strong>'
				);
				echo '</p>';
				$has_error = true;
			}
		}

		return $has_error;
	}

	/**
	 * Render form fields
	 */
	public function render_form_field( $field, $label_visibility ) {
		$key         = Form_Manager::get_field_key_name( $field );
		$key         = $key === 'ADDRESS' ? $key = 'ADDRESS[addr1]' : $key;
		$form_id     = $this->node;
		$field_name  = 'data[' . $form_id . '][' . $key . ']';
		$required    = $field['required'] === 'required' ? 'required="required"' : '';
		$placeholder = array_key_exists( 'placeholder', $field ) ? 'placeholder="' . esc_attr( $field['placeholder'] ) . '"' : '';
		$width       = array_key_exists( 'field_width', $field ) ? 'style="width:' . $field['field_width'] . '%"' : '';

		echo '<fieldset class="content-form-field-' . esc_attr( $field['type'] ) . '" ' . $width . '>';
		if ( $label_visibility === 'show' ) {
			$this->maybe_render_field_label( $field_name, $field );
		}

		switch ( $field['type'] ) {
			case 'textarea':
				echo '<textarea name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $placeholder . ' cols="30" rows="5"></textarea>';
				break;
			case 'password':
				echo '<input type="password" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . '>';
				break;
			default:
				echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $placeholder . '">';
				break;
		}

		echo '</fieldset>';

	}

	/**
	 * @param $field
	 * @param $label_visibility
	 * @param $widget_id
	 *
	 * @return bool
	 */
	public function maybe_render_newsletter_address( $field, $widget_id ) {
		require_once TI_CONTENT_FORMS_PATH . '/includes/widgets-public/widget_actions_base.php';
		$widget_settings = Widget_Actions_Base::get_beaver_module_settings_by_id( $widget_id, get_the_ID() );
		if ( ! is_array( $widget_settings ) ) {
			return false;
		}
		if ( ! array_key_exists( 'provider', $widget_settings ) || $widget_settings['provider'] !== 'mailchimp' ) {
			return false;
		}

		if ( ! array_key_exists( 'field_map', $field ) || strtolower( $field['field_map'] ) !== 'address' ) {
			return false;
		}

		$display_label  = $widget_settings['hide_label'];
		$required       = $field['required'] === 'required' ? 'required="required"' : '';
		$width          = array_key_exists( 'field_width', $field ) ? 'style="width:' . $field['field_width'] . '%"' : '';
		$address_fields = array(
			'addr2'   => __( 'Address Line 2', 'textdomain' ),
			'city'    => __( 'City', 'textdomain' ),
			'state'   => __( 'State/Province/Region', 'textdomain' ),
			'zip'     => __( 'Postal / Zip Code', 'textdomain' ),
			'country' => __( 'Country', 'textdomain' ),
		);
		foreach ( $address_fields as $address_item => $item_label ) {
			$placeholder = array_key_exists( 'placeholder', $field ) && ! empty( $field['placeholder'] ) ? 'placeholder="' . esc_attr( $item_label ) . '"' : '';
			$field_name  = 'data[' . $widget_id . '][ADDRESS[' . $address_item . ']]';
			echo '<fieldset class="content-form-field-' . esc_attr( $field['type'] ) . '" ' . $width . '>';

			if ( $display_label !== 'hide' ) {
				echo '<label for="' . esc_attr( $field_name ) . '" >';
				echo $item_label;
				if ( $field['required'] === 'required' ) {
					echo '<span class="required-mark"> *</span>';
				}
				echo '</label>';
			}

			if ( $address_item === 'country' ) {
				echo '<div class="elementor-select-wrapper">';
				echo '<select class="country" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . '><option value="">-</option><option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia, Plurinational State of</option><option value="BQ">Bonaire, Sint Eustatius and Saba</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, the Democratic Republic of the</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Côte d\'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curaçao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, Democratic People\'s Republic of</option><option value="KR">Korea, Republic of</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People\'s Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia, the former Yugoslav Republic of</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States of</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territory, Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Réunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena, Ascension and Tristan da Cunha</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin (French part)</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome and Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SX">Sint Maarten (Dutch part)</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="SS">South Sudan</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela, Bolivarian Republic of</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.S.</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>';
				echo '</div>';
			} else {
				echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' ' . $placeholder . '>';
			}

			echo '</fieldset>';
		}
		return true;
	}

	/**
	 * @param $field_name
	 * @param $field
	 * @return bool
	 */
	private function maybe_render_field_label( $field_name, $field ) {
		$label = $field['label'];
		if ( empty( $label ) ) {
			return false;
		}

		echo '<label for="' . esc_attr( $field_name ) . ' ">';
		echo $field['label'];
		if ( $field['required'] === 'required' ) {
			echo '<span class="required-mark"> *</span>';
		}
		echo '</label>';

		return true;
	}

	/**
	 * Render form footer.
	 */
	public function render_form_footer() {
		echo '</form>';
	}

	/**
	 * Get form type.
	 * @return string
	 */
	abstract function get_type();

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	abstract function get_widget_name();

	/**
	 * Add widget specific repeater fields.
	 *
	 * @param array $fields Repeater fields.
	 * @return mixed
	 */
	abstract function add_widget_repeater_fields( $fields );

	/**
	 * Add widget specific controls.
	 *
	 * @param array $fields Widget fields.
	 * @return mixed
	 */
	abstract function add_widget_specific_controls( $fields );

	/**
	 * Set default values for registration widget.
	 *
	 * @return array
	 */
	abstract function widget_default_values();
}

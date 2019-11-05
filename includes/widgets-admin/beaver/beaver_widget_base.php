<?php

namespace ThemeIsle\ContentForms\Includes\Widgets_Admin\Beaver;

use ThemeIsle\ContentForms\Form_Manager;

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

		$this->form_type       = $this->get_type();
		$this->default_data    = $this->widget_default_values();
		$this->module_settings = $this->get_module_settings();

		parent::__construct( $data );

		wp_enqueue_script( 'content-forms' );
		wp_enqueue_style( 'content-forms' );
	}

	/**
	 * Run hooks and filters.
	 */
	public function run_hooks() {
		add_filter( 'fl_builder_register_settings_form', array( $this, 'filter_widget_settings' ) , 10, 2);
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
	public function filter_widget_settings( $form, $slug ){
		if( $slug === 'newsletter_admin' ){
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
	private function get_style_settings( $args ){
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
									'selector' => '.content-form-' . $this->form_type . ' fieldset',
									'property' => 'padding-right',
								),
								array(
									'selector' => '.content-form-' . $this->form_type . ' fieldset',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset label',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset label',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset label .required-mark',
							'property' => 'color',
						),
					),
					'label_typography'    => array(
						'type'       => 'typography',
						'label'      => __( 'Label Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset label',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset input, .content-form-' . $this->form_type . ' fieldset select, .content-form-' . $this->form_type . ' fieldset textarea, .content-form-' . $this->form_type . ' fieldset button',
						),
					),
					'field_text_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset input, .content-form-' . $this->form_type . ' fieldset textarea, .content-form-' . $this->form_type . ' fieldset select, .content-form-' . $this->form_type . ' fieldset input::placeholder, .content-form-' . $this->form_type . ' fieldset textarea::placeholder, .content-form-' . $this->form_type . ' fieldset select::placeholder',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset input, .content-form-' . $this->form_type . ' fieldset textarea, .content-form-' . $this->form_type . ' fieldset select',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset input, .content-form-' . $this->form_type . ' fieldset textarea, .content-form-' . $this->form_type . ' fieldset select',
							'property' => 'border-color',
						),
					),
					'field_border'           => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset input, .content-form-' . $this->form_type . ' fieldset textarea, .content-form-' . $this->form_type . ' fieldset select',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button',
							'property' => 'color',
						),
					),
					'button_typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button',
						),
					),
					'button_border'           => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button',
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
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button:hover',
							'property' => 'background-color',
						),
					),
					'button_text_color_hover' => array(
						'type'       => 'color',
						'label'      => __( 'Button Text Color', 'textdomain' ),
						'show_reset' => true,
						'show_alpha' => false,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button:hover',
							'property' => 'color',
						),
					),
					'button_typography_hover' => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button:hover',
						),
					),
					'button_border_hover'     => array(
						'type'       => 'border',
						'label'      => __( 'Border Hover', 'textdomain' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.content-form-' . $this->form_type . ' fieldset.submit-form button:hover',
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
	private function get_form_settings( $args ){
		$args['general']['sections']['settings'] = array(
			'title'  => esc_html__( 'Fields', 'textdomain' ),
			'fields' => array(
				'fields' => array(
					'multiple'     => true,
					'type'         => 'form',
					'label'        => esc_html__( 'Field', 'textdomain' ),
					'form'         => 'field',
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
			'field',
			array(
				'title' => esc_html__( 'Field', 'textdomain' ),
				'tabs'  => array(
					'general' => array(
						'title'    => esc_html__( 'Field', 'textdomain' ),
						'sections' => array(
							'fields' => array(
								'title'  => esc_html__( 'Field', 'textdomain' ),
								'fields' => apply_filters( $this->form_type . '_repeater_fields', $repeater_fields ),
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
	private function get_control_settings( $args ){
		$args['general']['sections']['controls'] = apply_filters(
			$this->form_type . '_controls_fields', array(
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
							'selector' => '.submit-form.' . $this->form_type,
							'property' => 'text-align',
						),

					),
				)
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
			'style' => array(
				'title'    => esc_html__( 'Style', 'textdomain' ),
				'sections' => array(),
			)
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
	 * Render form fields
	 */
	public function render_form_field( $field, $label_visibility ) {
		$key         = Form_Manager::get_field_key_name( $field );
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

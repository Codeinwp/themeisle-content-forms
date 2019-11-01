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
	 * Get form type.
	 * @return string
	 */
	abstract function get_type();

	/**
	 * Get default config data.
	 *
	 * @param $field
	 * @return mixed
	 */
	abstract function get_default( $field );

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	abstract function get_widget_name();

	/**
	 * Beaver_Widget_Base constructor.
	 *
	 * @param $data
	 */
	public function __construct( $data ) {

		$this->form_type = $this->get_type();
		$this->module_settings = $this->set_module_settings();

		parent::__construct( $data );

		wp_enqueue_script( 'content-forms' );
		wp_enqueue_style( 'content-forms' );
	}

	/**
	 * Set module settings.
	 */
	private function set_module_settings(){

		$args = array(
			'general' => array(
				'title'       => $this->get_widget_name(),
				'sections'    => array(),
			),
		);

		$args['general']['sections']['settings'] = array(
			'title'  => esc_html__( 'Fields', 'textdomain' ),
			'fields' => array(
				'fields' => array(
					'multiple'     => true,
					'type'         => 'form',
					'label'        => esc_html__( 'Field', 'textdomain' ),
					'form'         => 'field',
					'preview_text' => 'label',
					'default'      => $this->get_default('fields'),
				),
			),
		);

		$repeater_fields = apply_filters(
			$this->form_type . '_repeater_fields',
			array(
				'label'    => array(
					'type'  => 'text',
					'label' => esc_html__( 'Label', 'textdomain' ),
				),
				'placeholder'    => array(
					'type'  => 'text',
					'label' => esc_html__( 'Placeholder', 'textdomain' ),
				),
				'type'     => array(
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
					'type'    => 'select',
					'label'   => esc_html__( 'Field Width', 'textdomain' ),
					'options' => array(
						'100' => '100%',
						'75'  => '75%',
						'66'  => '66%',
						'50'  => '50%',
						'33'  => '33%',
						'25'  => '25%',
					),
					'responsive' => true,
				),
				'required' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Is required?', 'textdomain' ),
					'options' => array(
						'required' => esc_html__( 'Required', 'textdomain' ),
						'optional' => esc_html__( 'Optional', 'textdomain' ),
					),
				),
			)
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
								'title' => esc_html__( 'Field', 'textdomain' ),
								'fields' => $repeater_fields
							)
						),
					),
				),
			)
		);

		$controls = apply_filters(
			$this->form_type . '_controls_fields',
			array(
				'success_message' => array(
					'type' => 'text',
					'label'   => esc_html__( 'Success message', 'textdomain' ),
					'default' => $this->get_default('success_message'),
				),
				'error_message' => array(
					'type' => 'text',
					'label'   => esc_html__( 'Error message', 'textdomain' ),
					'default' => $this->get_default('error_message'),
				),
				'hide_label'      => array(
					'type'        =>  'select',
					'label'       => __( 'Hide Label', 'textdomain' ),
					'default'     => 'show',
					'options'     => array(
						'hide'    => esc_html__( 'Hide', 'textarea' ),
						'show'    => esc_html__( 'Show', 'textarea' ),
					)
				),
				'submit_label'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'default'     => $this->get_default( 'submit_label' ),
					'description' => esc_html__( 'The Call To Action label', 'textdomain' )
				),
				'submit_position' => array(
					'type'    => 'align',
					'label'   => esc_html__( 'Alignment', 'textdomain' ),
					'default' => 'left',
					'preview' => array( //TODO
						'type'       => 'css',
						'selector'   => '.submit-form.'.$this->form_type,
						'property'   => 'text-align',
					),

				)
			)
		);

		$args['general']['sections']['controls'] = array(
			'title'  => esc_html__( 'Form Settings', 'textdomain' ),
			'fields' => $controls,
		);

		return $args;
	}

	/**
	 * Register Beaver widgets.
	 */
	public function register_widget() {
		\FLBuilder::register_module( get_called_class(), $this->module_settings );
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
		// there could be also the possibility to submit by type
		// echo '<input type="hidden" name="action" value="content_form_{type}_submit" />';
		echo '<input type="hidden" name="form-type" value="' . $this->get_type() . '" />';
		echo '<input type="hidden" name="form-builder" value="beaver" />';
		echo '<input type="hidden" name="post-id" value="' . get_the_ID() . '" />';
		echo '<input type="hidden" name="form-id" value="' . $id . '" />';
	}

	/**
	 * Render form fields
	 */
	public function render_form_field( $field, $label_visibility ) {
		$key = Form_Manager::get_field_key_name( $field );
		$form_id  = $this->node;
		$field_name = 'data[' . $form_id . '][' . $key . ']';
		$required = $field['required'] === 'required' ? 'required="required"' : '';
		$placeholder = array_key_exists( 'placeholder', $field ) ? 'placeholder="'. esc_attr( $field['placeholder'] ) .'"' : '';
		$width = array_key_exists('field_width', $field ) ? 'style="width:'.$field['field_width'].'%"' : '';

		echo '<fieldset class="content-form-field-'. esc_attr( $field['type'] ) .'" '. $width .'>';
		if( $label_visibility === 'show' ){
			$this->maybe_render_field_label( $field_name, $field );
		}

		switch ( $field['type'] ) {
			case 'textarea':
				echo '<textarea name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" ' . $required . ' '. $placeholder. ' cols="30" rows="5"></textarea>';
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
	private function maybe_render_field_label($field_name, $field){
		$label = $field['label'];
		if ( empty( $label ) ){
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
}

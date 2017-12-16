<?php

namespace ThemeIsle\ContentForms;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This class is used to create an Elementor widget based on a ContentForms config.
 * @TODO this is a work in progress and it's the basic example of and Elementor widget
 * @TODO handle in a better way the preview state
 * @TODO handle submit action
 */
class ElementorWidget extends \Elementor\Widget_Base {

	private $name;

	private $form_type;

	private $title;

	private $icon;

	private $forms_config = array();

	/**
	 * Widget base constructor.
	 *
	 * Initializing the widget base class.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data Widget data. Default is an empty array.
	 * @param array|null $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->setup_attributes( $data );
	}

	/**
	 * This method takes the given attributes and sets them as properties
	 *
	 * @param $data array
	 */
	private function setup_attributes( $data ) {
		if ( ! empty( $data['content_forms_config'] ) ) {
			$this->forms_config = $data['content_forms_config'];
		} else {
			$this->form_type = $this->get_data( 'widgetType' );
			$this->form_type = str_replace( 'content_form_', '', $this->form_type );

			$this->forms_config = apply_filters( 'content_forms_config_for_' . $this->form_type, $this->forms_config );
		}

		if ( ! empty( $data['id'] ) ) {
			$this->set_name( $data['id'] );
		}

		if ( ! empty( $this->forms_config['title'] ) ) {
			$this->set_title( $this->forms_config['title'] );
		}

		if ( ! empty( $this->forms_config['icon'] ) ) {
			$this->set_icon( $this->forms_config['icon'] );
		}
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// first we need to make sure that we have some fields to build on
		if ( empty( $this->forms_config['fields'] ) ) {
			return;
		}

		// get the fields from config
		$fields = $this->forms_config['fields'];

		foreach ( $fields as $field_name => $field ) {

			$this->start_controls_section(
				$field_name,
				[
					'label' => $field['label'],
				]
			);

			$this->add_control(
				$field_name . '_label',
				[
					'label'   => $field['label'] . ' label',
					'type'    => 'text',
					'default' => isset( $field['placeholder'] ) ? $field['placeholder'] : ''
				]
			);

			$this->add_control(
				$field_name . '_requirement',
				[
					'label'   => esc_html__( 'Requirement' ),
					'type'    => 'select',
					'options' => array(
						'required' => esc_html__( 'Required' ),
						'optional' => esc_html__( 'Optional' ),
						'hidden'   => esc_html__( 'Hidden' )
					),
					'default' => isset( $field['require'] ) ? $field['require'] : 'optional'
				]
			);

			$this->end_controls_section();
		}

		if ( empty( $this->forms_config['controls'] ) ) {
			return;
		}

		$controls = $this->forms_config['controls'];

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'textdoamin' ),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		foreach ( $controls as $control_name => $control ) {

			switch ( $control['type'] ) {

				case 'select':

					if ( ! empty( $control['options'] ) ) {
						$this->add_control(
							$control_name,
							[
								'label'       => $control['label'],
								'type'        => 'select',
								'description' => isset( $control['description'] ) ? $control['description'] : '',
								'options'     => $control['options']
							]
						);
					}
					break;

				case 'checkbox':
					$this->add_control(
						$control_name,
						[
							'label'       => $control['label'],
							'type'        => 'checkbox',
							'description' => isset( $control['description'] ) ? $control['description'] : '',
						]
					);
					break;

				case 'text':
				case 'email':
				default:

					$this->add_control(
						$control_name,
						[
							'label'       => $control['label'],
							'type'        => 'text',
							'description' => isset( $control['description'] ) ? $control['description'] : '',
						]
					);

					break;
			}
		}

		$this->end_controls_section();
	}

	/**
	 * Render content form widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render( $instance = [] ) {

		$form_id  = $this->get_data( 'id' );

		if ( empty( $this->forms_config['fields'] ) ) {
			return;
		}

		$fields   = $this->forms_config['fields'];
		$controls = $this->forms_config['controls'];

		$this->render_form_header( $form_id );

		foreach ( $fields as $field_name => $field ) {
			$field_id = $this->form_type . '_' . $field_name;
			$this->render_form_field( $field_name, $field );
		}

		$btn_label = esc_html__( 'Submit', 'textdomain' );
		if ( ! empty( $controls['submit_label'] ) ) {
			$btn_label = $this->get_settings( 'submit_label' );
		} ?>
		<fieldset>
			<button type="submit" name="submit" value="submit-<?php echo $this->form_type; ?>-<?php echo $form_id; ?>">
				<?php echo $btn_label; ?>
			</button>
		</fieldset>
		<?php

		$this->render_form_footer();
	}

	/**
	 * Display method for the form's header
	 * It is also takes care about the form attributes and the regular hidden fields
	 *
	 * @param $type
	 * @param $id
	 */
	public function render_form_header( $id ) {
		// create an url for the form's action
		$url = admin_url( 'admin-post.php' );

		echo '<form action="' . esc_url( $url ) . '" method="post" class="content-form content-form-' . $this->form_type . '">';

		wp_nonce_field( 'content-form-' . $id, '_wpnonce' );

		echo '<input type="hidden" name="action" value="content_form_submit" />';
		// there could be also the possibility to submit by type
		// echo '<input type="hidden" name="action" value="content_form_{type}_submit" />';
		echo '<input type="hidden" name="form-type" value="' . $this->form_type . '" />';
		echo '<input type="hidden" name="form-id" value="' . $id . '" />';
	}

	/**
	 * Display method for the form's footer
	 */
	public function render_form_footer() {
		echo '</form>';
	}

	public function render_form_field( $field_id, $field, $is_preview = false ) {

		$required = 'false';

		if ( $field['require'] === 'required' ) {
			$required = $field['require'];
		}

		// in case this is a preview, we need to disable the actual inputs and transform the labels in inputs
		$disabled = '';
		if ( $is_preview ) {
			$disabled = 'disabled="disabled"';
		}

		$this->add_inline_editing_attributes( $field_id . '_label', 'none' );

		$saved_label = $this->get_settings( $field_id . '_label' ); ?>
		<fieldset>
			<label for="<?php echo $field_id ?>" <?php echo $this->get_render_attribute_string( 'title' ); ?>>
				<?php
				if ( $is_preview ) { ?>
					<p class="elementor-inline-editing" data-elementor-setting-key="<?php echo $field_id . '_label'; ?>">
						{{{settings.<?php echo $field_id . '_label'; ?>}}}
					</p>
				<?php } else {
					echo $saved_label;
				} ?>
			</label>

			<?php
			switch ( $field['type'] ) {
				case 'textarea': ?>
					<textarea name="<?php echo $field_id ?>" id="<?php echo $field_id ?>"
					          required="<?php echo $required; ?>" cols="30"
					          rows="10" <?php echo $disabled; ?>></textarea>
					<?php break;
				default: ?>
					<input type="text" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>"
					       required="<?php echo $required; ?>" <?php echo $disabled; ?>>
					<?php
					break;
			}
			?>
		</fieldset>
		<?php
	}

	/**
	 * Render content form widget as plain content.
	 *
	 * Override the default behavior by printing the content without rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
//	public function render_plain_content() {
//		// In plain mode, render without shortcode
//		echo $this->get_settings( 'editor' );
//	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

	/**
	 * Render content form widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected

	protected function _content_template() {

		$fields = $this->forms_config['fields'];
		?>
		<div class="elementor-content-form elementor-clearfix elementor-inline-editing"
		     data-elementor-setting-key="editor" data-elementor-inline-editing-toolbar="advanced">
			<?php
			foreach ( $fields as $field_name => $field ) {
				$field_id = $this->form_type . '_' . $field_name;
				$this->render_form_field( $field_id, $field, true );
			} ?>
		</div>
		</div>
		<?php
	}	 */



	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the widget name property
	 */
	private function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Set the widget title property
	 */
	private function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * Retrieve content form widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Set the widget title property
	 */
	private function set_icon( $icon ) {
		$this->icon = $icon;
	}

	/**
	 * Widget Category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'obfx-elementor-widgets' ];
	}

}
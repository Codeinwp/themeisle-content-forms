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

		$this->start_controls_section(
			'section_form_settings',
			[
				'label' => __( 'Form Settings', 'textdomain' ),
			]
		);

		$controls = $this->forms_config['controls'];

		foreach( $controls as $control_name => $control ) {

			$control_args = [
				'label'       => $control['label'],
				'type'        => $control['type'],
				'default'     => isset( $control['default'] ) ? $control['default'] : '',
			];

			if ( isset( $control['options'] ) ) {
				$control_args['options'] = $control['options'];
			}

			$this->add_control(
				$control_name,
				$control_args
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			$this->form_type . '_form_fields',
			[
				'label' => __( 'Fields', 'textdomain' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$field_types = [
			'text'     => __( 'Text', 'textdomain' ),
			'tel'      => __( 'Tel', 'textdomain' ),
			'email'    => __( 'Email', 'textdomain' ),
			'textarea' => __( 'Textarea', 'textdomain' ),
			'number'   => __( 'Number', 'textdomain' ),
			'select'   => __( 'Select', 'textdomain' ),
			'url'      => __( 'URL', 'textdomain' ),
		];

		$repeater->add_control(
			'type',
			[
				'label'   => __( 'Type', 'textdomain' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label'   => __( 'Label', 'textdomain' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label'   => __( 'Placeholder', 'textdomain' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$repeater->add_control(
			'requirement',
			[
				'label'   => __( 'Requirement', 'textdomain' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'required' => esc_html__( 'Required' ),
					'optional' => esc_html__( 'Optional' ),
					'hidden'   => esc_html__( 'Hidden' )
				),
				'default' => 'required',
			]
		);

		$fields = $this->forms_config['fields'];

		$default_fields = [];

		foreach ( $fields as $field_name => $field ) {
			$default_fields[] = [
				'type'        => $field['type'],
				'label'       => $field['label'],
				'requirement' => $field['require'],
				'placeholder' => isset( $field['placeholder'] ) ? $field['placeholder'] : $field['label'],
				'width'             => '100',
			];
		}

		$this->add_control(
			'form_fields',
			[
				'label'       => __( 'Form Fields', 'textdomain' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'show_label'  => false,
				'separator'   => 'before',
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => $default_fields,
				'title_field' => '{{{ label }}}',
			]
		);

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
		$settings = $this->get_settings();

		if ( empty( $this->forms_config['fields'] ) ) {
			return;
		}

		$fields = $settings['form_fields'];

		$controls = $this->forms_config['controls'];

		$this->render_form_header( $form_id );

		foreach ( $fields as $index => $field ) {
			$this->render_form_field( $field );
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

		echo '<form action="' . esc_url( $url ) . '" method="post" class="content-form content-form-' . $this->form_type . ' ' . $this->get_name() . '">';

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

	public function render_form_field($field, $is_preview = false ) {
		$item_index = $field['_id'];
		$required = '';

		if ( $field['requirement'] === 'required' ) {
			$required = 'required="required"';
		}

		// in case this is a preview, we need to disable the actual inputs and transform the labels in inputs
		$disabled = '';
		if ( $is_preview ) {
			$disabled = 'disabled="disabled"';
		}

		$this->add_inline_editing_attributes( $item_index . '_label', 'none' ); ?>
		<fieldset  <?php echo $this->get_render_attribute_string( 'fieldset' . $item_index ); ?> >

			<label <?php echo $this->get_render_attribute_string( 'label' . $item_index ); ?>>
				<?php echo $field['label']; ?>
			</label>

			<?php
			switch ( $field['type'] ) {
				case 'textarea': ?>
					<textarea name="<?php echo $item_index ?>" id="<?php echo $item_index ?>"
						<?php echo $required; ?> cols="30" rows="10" <?php echo $disabled; ?>></textarea>
					<?php break;
				default:
					$this->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' ); ?>
					<input type="text"
						<?php echo $this->get_render_attribute_string( 'input' . $item_index ); ?>
						<?php echo $required; ?> <?php echo $disabled; ?>>
					<?php
					break;
			} ?>
		</fieldset>
		<?php
	}

	public function old_render_form_field( $field_id, $field, $is_preview = false ) {

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
					<p class="elementor-inline-editing"
					   data-elementor-setting-key="<?php echo $field_id . '_label'; ?>">
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

	protected function content_template() {
	}

	public function render_plain_content( $instance = [] ) {
	}

	/**
	 * Render content form widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * protected function _content_template() {
	 *
	 * $fields = $this->forms_config['fields'];
	 * ?>
	 * <div class="elementor-content-form elementor-clearfix elementor-inline-editing"
	 * data-elementor-setting-key="editor" data-elementor-inline-editing-toolbar="advanced">
	 * <?php
	 * foreach ( $fields as $field_name => $field ) {
	 * $field_id = $this->form_type . '_' . $field_name;
	 * $this->render_form_field( $field_id, $field, true );
	 * } ?>
	 * </div>
	 * </div>
	 * <?php
	 * }     */

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
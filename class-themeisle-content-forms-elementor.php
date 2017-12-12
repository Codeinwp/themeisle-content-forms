<?php

namespace ThemeIsle\ContentForms;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This class is used to create an Elementor widget based on a ContentForms config.
 * @TODO this is a work in progress and it's the basic example of and Elementor widget
 * @TODO Make the the Widget Section details and fields dynamic and inherited from the ContentForms config
 */
class ElementorWidget extends \Elementor\Widget_Base {

	private $name;

	private $title;

	private $icon;

	private $forms_config;

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
		$form_type = $this->get_data( 'widgetType' );

		if ( false === strpos( $form_type, 'content_form_' ) ) {
			return;
		}

		$form_type = str_replace( 'content_form_', '', $form_type );

		$config = array();

		$config = apply_filters( 'content_forms_config_for_' . $form_type, $config );

		if ( empty( $config['fields'] ) ) {
			return;
		}

		// @TOOD This is just a dummy output... work in progress
		?>
		<h3> Form: <?php echo $form_type ?></h3>
		<?php

		foreach ( $config['fields'] as $field_name => $field ) {
			$field_id = $form_type . '_' . $field_name;
			// @TOOD This is just a dummy output... work in progress
			?>
			<fieldset>
				<label for="<?php echo $field_id ?>"><?php echo $field['label'] ?></label>
				<input type="text" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
			</fieldset>

			<?php
		}

		/**
		 * $editor_content = $this->get_settings( 'editor' );
		 *
		 * $editor_content = $this->parse_text_editor( $editor_content );
		 *
		 * $this->add_render_attribute( 'editor', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ] );
		 *
		 * $this->add_inline_editing_attributes( 'editor', 'advanced' );
		 * ?>
		 * <div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
		 * <?php
		 *
		 *
		 * */
	}

	/**
	 * Render content form widget as plain content.
	 *
	 * Override the default behavior by printing the content without rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode
		echo $this->get_settings( 'editor' );
	}

	/**
	 * Render content form widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="elementor-text-editor elementor-clearfix elementor-inline-editing"
		     data-elementor-setting-key="editor" data-elementor-inline-editing-toolbar="advanced">{{{ settings.editor
			}}}
		</div>
		<?php
	}
}
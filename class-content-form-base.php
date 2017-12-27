<?php

namespace ThemeIsle\ContentForms;

/**
 * An abstract class which should reflect how a Content Form should look like
 * Class ContentFormBase
 * @package ThemeIsle\ContentForms
 */
abstract class ContentFormBase {

	/**
	 * The content form type.
	 * Currently the possible values are: `contact`,`newsletter` and `registration`
	 * @var string $type
	 */
	private $type;

	/**
	 * Holds the shape of the content form, names, details and fields structure.
	 * @var array $config
	 */
	private $config;

	protected $notices = array();

	/**
	 * Create the Content Form Object and add initial hooks
	 * ContentFormBase constructor.
	 */
	function __construct() {
		$this->init();

		$this->add_base_hooks();
	}

	/**
	 * This method is passed to the rest controller and it is responsible for submitting the data.
	 *
	 * @param $return array
	 * @param $data array
	 * @param $widget_id string
	 * @param $post_id string
	 * @param $builder string
	 *
	 * @return mixed
	 */
	abstract public function rest_submit_form( $return, $data, $widget_id, $post_id, $builder );

	/**
	 * Create an abstract array config which should define the form.
	 * This method's body will be passed to a filter
	 * @param $config
	 *
	 * @return mixed
	 */
	abstract public function make_form_config( $config );

	/**
	 * Map the registration actions
	 */
	public function add_base_hooks() {

		// add the initial config for the Contact Content Form
		add_filter( 'content_forms_config_for_' . $this->get_type() , array( $this, 'make_form_config' ) );

		$config = apply_filters( 'content_forms_config_for_' . $this->get_type(), array() );
		$this->set_config( $config );

		// @TODO if we will ever think about letting users submit without AJAX
		// register the classic submission action
		//add_action( 'admin_post_nopriv_content_form_contact_submit', array( $this, 'submit_form' ) );
		//add_action( 'admin_post_content_form_contact_submit', array( $this, 'submit_form' ) );

		// add a rest api callback for the `submit` route
		add_filter( 'content_forms_submit_' . $this->get_type(), array( $this, 'rest_submit_form' ), 10, 5 );

		// Register the Elementor Widget
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widget' ) );

		// Register the Beaver Module
		// @TODO

		// Register the Gutenberg Block
		$this->register_gutenberg_block();
	}

	/**
	 * Elementor widget registration
	 */
	public function register_elementor_widget() {

		// We check if the Elementor plugin has been installed / activated.
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type(
				new \ThemeIsle\ContentForms\ElementorWidget(
					array(
						'id'                   => 'content_form_' . $this->get_type(),
						'content_forms_config' => $this->get_config()
					),
					array(
						'content_forms_config' => $this->get_config()
					)
				)
			);
		}
	}

	public function register_beaver_module() {
		// TODO https://www.wpbeaverbuilder.com/custom-module-documentation/
	}

	/**
	 * Gutenberg block registration
	 */
	public function register_gutenberg_block() {

		if ( in_array( 'gutenberg/gutenberg.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			require_once( __DIR__ . '/class-themeisle-content-forms-gutenberg.php' );

			$module = new \ThemeIsle\ContentForms\GutenbergModule(
				array(
					'id'                   => 'content_form_' . $this->get_type(),
					'type'                 => $this->get_type(),
					'content_forms_config' => $this->get_config()
				)
			);
		}
	}

	/**
	 * Get block settings depending on what builder is in use.
	 *
	 * @param $widget_id
	 * @param $page_id
	 *
	 * @return bool
	 */
	protected function get_widget_settings( $widget_id, $page_id, $builder ) {

		if ( 'elementor' === $builder ) {

			// if elementor
			$settings = ElementorWidget::get_widget_settings( $widget_id, $page_id );

			return $settings['settings'];
		}

		// if gutenberg

		// if beaver

		return false;
	}

	/**
	 * Setter method for the form type
	 * @param $type
	 */
	protected function set_type( $type ) {
		$this->type = $type;
	}

	/**
	 * Getter method for the form type
	 *
	 * @return string
	 */
	final public function get_type() {
		return $this->type;
	}

	/**
	 * Setter method for the config property
	 * @param $config
	 */
	protected function set_config( $config ) {
		$this->config = $config;
	}

	/**
	 * Getter method for the config property
	 * @return mixed
	 */
	final public function get_config() {
		return $this->config;
	}

}
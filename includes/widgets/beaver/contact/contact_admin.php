<?php

namespace ThemeIsle\ContentForms\Includes\Widgets\Beaver\Contact;

use ThemeIsle\ContentForms\Includes\Widgets\Beaver\Beaver_Widget_Base;

require_once TI_CONTENT_FORMS_PATH . '/includes/widgets/beaver/beaver_widget_base.php';

class Contact_Admin extends Beaver_Widget_Base {

	/**
	 * Define the form type
	 * @return string
	 */
	public function get_type() {
		return 'contact';
	}

	public function __construct() {

		$this->form_config = array(
			'id'                           => 'contact',
			'icon'                         => 'eicon-align-left',
			'title'                        => esc_html__( 'Contact Form' ),
			'fields' /* or form_fields? */ => array(
				'name'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Name' ),
					'default'     => esc_html__( 'Name' ),
					'placeholder' => esc_html__( 'Your Name' ),
					'require'     => 'required'
				),
				'email'   => array(
					'type'        => 'email',
					'label'       => esc_html__( 'Email' ),
					'default'     => esc_html__( 'Email' ),
					'placeholder' => esc_html__( 'Email address' ),
					'require'     => 'required'
				),
				'phone'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Phone' ),
					'default'     => esc_html__( 'Phone' ),
					'placeholder' => esc_html__( 'Phone Nr' ),
					'require'     => 'optional'
				),
				'message' => array(
					'type'        => 'textarea',
					'label'       => esc_html__( 'Message' ),
					'default'     => esc_html__( 'Message' ),
					'placeholder' => esc_html__( 'Your message' ),
					'require'     => 'required'
				)
			),
			'controls' /* or settings? */ => array(
				'to_send_email' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Send to', 'textdomain' ),
					'description' => esc_html__( 'Where should we send the email?', 'textdomain' ),
					'default'     => get_bloginfo( 'admin_email' )
				),
				'submit_label'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Submit', 'textdomain' ),
					'default'     => esc_html__( 'Submit', 'textdomain' ),
					'description' => esc_html__( 'The Call To Action label', 'textdomain' )
				)
			)
		);

		parent::__construct(
			array(
				'name'        => esc_html__( 'Contact', 'textdomain' ),
				'description' => esc_html__( 'A contact form.', 'textdomain' ),
				'category'    => esc_html__( 'Orbit Fox Modules', 'textdomain' ),
				'dir'         => dirname( __FILE__ ),
				'url'         => plugin_dir_url( __FILE__ ),
			)
		);
	}

}

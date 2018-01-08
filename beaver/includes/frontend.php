<?php
/**
 * The module rendering file
 *
 * @$module object
 * @$settings object
 */
$form_settings = apply_filters( 'content_forms_config_for_' . $module->get_type(), array() );

/** == Fields Validation == */
$controls = $form_settings['controls'];

foreach ( $controls as $control_name => $control ) {
	$control_value = $module->get_setting( $control_name );
	if ( isset( $control['required'] ) && $control['required'] && empty( $control_value ) ) { ?>
		<div class="content-forms-required">
			<?php
			printf(
				esc_html__( 'The %s setting is required!', 'textdomain' ),
				'<strong>' . $control['label'] . '</strong>'
			); ?>
		</div>
		<?php return;
	}
}

/** == FORM HEADER == */
$module->render_form_header( $module->node );

/** == FORM FIELDS == */
$fields = $form_settings['fields'];

foreach ( $fields as $field_name => $field ) {
	$module->render_form_field( $field_name, $field );
}

$controls = $form_settings['controls'];

/** == FORM SUBMIT BUTTON == */
$btn_label = esc_html__( 'Submit', 'textdomain' );

if ( ! empty( $settings->submit_label ) ) {
	$btn_label = $settings->submit_label;
} ?>
<fieldset>
	<button type="submit" name="submit" value="submit-<?php echo $module->get_type(); ?>-<?php echo $module->node; ?>">
		<?php echo $btn_label; ?>
	</button>
</fieldset>
<?php

/** == FORM FOOTER == */
$module->render_form_footer();
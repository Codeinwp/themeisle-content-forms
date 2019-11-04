<?php
$module->render_form_header( $module->node );

$fields           = $settings->fields;
$label_visibility = property_exists( $settings, 'hide_label' ) ? $settings->hide_label : 'show';
foreach ( $fields as $key => $field ) {
	$field        = (array) $field;
	$field['_id'] = $key;
	$module->render_form_field( $field, $label_visibility );
}

$btn_label = ! empty( $settings->submit_label ) ? $settings->submit_label : esc_html__( 'Submit', 'textdomain' );
echo '<fieldset class="submit-form ' . esc_attr( $module->get_type() ) . '" >';
echo '<button type="submit" name="submit" value="submit-' . esc_attr( $module->get_type() ) . '-' . esc_attr( $module->node ) . '">';
echo $btn_label;
echo '</button>';
echo  '</fieldset>';

$module->render_form_footer();

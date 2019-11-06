<?php
$module_type = $module->get_type();

/**
 * Field spacing.
 */
$fieldset_selector = '.fl-node-'. $id .' .content-form-'. $module_type .' fieldset';
echo $fieldset_selector .'{';
    echo property_exists( $settings, 'column_gap' ) ? 'padding: 0 '. $settings->column_gap . 'px 0 ' . $settings->column_gap .'px;':'';
    echo property_exists( $settings, 'row_gap' ) ? 'margin-bottom:' . $settings->row_gap .'px;':'';
echo '}';

/**
 * Field label style.
 */
$fieldset_label_selector = '.fl-node-' . $id .' .content-form-' . $module_type .' fieldset label';
echo $fieldset_label_selector.'{';
    echo property_exists( $settings, 'label_color' ) ? 'color: #' . $settings->label_color  : '';
echo '}';

$required_mark_selector = '.fl-node-'. $id .' .content-form-' . $module_type . ' fieldset label .required-mark';
echo $required_mark_selector . '{';
    echo property_exists( $settings, 'mark_required_color' ) ? 'color: #'. $settings->mark_required_color .';' : '';
echo '}';

if ( property_exists( $settings, 'label_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'label_typography',
			'selector'     => $fieldset_label_selector,
		)
	);
}

/**
 * Field style
 */
$input_field_selector = '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset input';
$textarea_field_selector = '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset textarea';
$select_field_selector = '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset select';

if ( property_exists( $settings, 'field_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'field_typography',
			'selector'     => $input_field_selector .',' . $textarea_field_selector .','. $select_field_selector,
		)
	);
}

echo $input_field_selector . ',' . $input_field_selector . '::placeholder,' . $textarea_field_selector . ',' . $textarea_field_selector .'::placeholder,' . $select_field_selector . ',' . $select_field_selector .'::placeholder{';
    echo property_exists( $settings, 'field_text_color' ) ? 'color: #'.  $settings->field_text_color.';' : '';
echo '}';

echo $input_field_selector .',' . $textarea_field_selector .','. $select_field_selector . '{';
    echo property_exists( $settings, 'field_background_color' ) ? 'background-color: #'.  $settings->field_background_color .';' : '';
echo '}';

if ( property_exists( $settings, 'field_border' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'field_border',
			'selector'     => $input_field_selector .',' . $textarea_field_selector .','. $select_field_selector,
		)
	);
}

/**
 * Submit Button style.
 */
$fieldset_button_selector = '.fl-node-'. $id . ' .content-form-'. $module_type .' fieldset.submit-field';
$button_selector       = '.fl-node-'. $id . ' .content-form-'. $module_type .' fieldset button[name="submit"]';
$button_hover_selector = '.fl-node-'. $id . ' .content-form-'. $module_type .' fieldset button[name="submit"]:hover';

echo $fieldset_button_selector . '{';
    echo property_exists( $settings, 'submit_position' ) ? 'text-align: '. $settings->submit_position .';' : '';
echo '}';

echo $button_selector.'{';
    echo property_exists( $settings, 'button_background_color' ) ? 'background-color: #'. $settings->button_background_color .';' : '';
    echo property_exists( $settings, 'button_text_color' ) ? 'color: #'. $settings->button_text_color .';' : '';
echo '}';

echo $button_hover_selector.'{';
    echo property_exists( $settings, 'button_background_color_hover' ) ? 'background-color: #'. $settings->button_background_color_hover .';' : '';
    echo property_exists( $settings, 'button_text_color_hover' ) ? 'color: #'. $settings->button_text_color_hover .';' : '';
echo '}';

if ( property_exists( $settings, 'button_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_typography',
			'selector'     => $button_selector,
		)
	);
}

if ( property_exists( $settings, 'button_typography_hover' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_typography_hover',
			'selector'     => $button_hover_selector,
		)
	);
}

if ( property_exists( $settings, 'button_border' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_border',
			'selector'     => $button_selector,
		)
	);
}

if ( property_exists( $settings, 'button_border_hover' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_border_hover',
			'selector'     => $button_hover_selector,
		)
	);
}

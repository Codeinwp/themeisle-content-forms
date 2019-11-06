<?php
$module_type = $module->get_type();

if ( property_exists( $settings, 'column_gap' ) ) { ?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset {
	padding: 0 <?php echo  $settings->column_gap; ?>px 0 <?php echo  $settings->column_gap; ?>px;
}

	<?php
}

if ( property_exists( $settings, 'row_gap' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset {
	margin-bottom: <?php echo  $settings->row_gap; ?>px;
}

	<?php
}


if ( property_exists( $settings, 'label_spacing' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset label {
	padding-bottom: <?php echo  $settings->label_spacing; ?>px;
}

	<?php
}

if ( property_exists( $settings, 'label_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset label {
	color: #<?php echo  $settings->label_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'mark_required_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset label .required-mark {
	color: #<?php echo  $settings->mark_required_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'label_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'label_typography',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset label',
		)
	);
}


if ( property_exists( $settings, 'field_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'field_typography',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset input, .fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset textarea, .fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset select, .fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset button',
		)
	);
}


if ( property_exists( $settings, 'field_text_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset input,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset input::placeholder,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset select,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset select::placeholder,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset textarea,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset textarea::placeholder {
	color: #<?php echo  $settings->field_text_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'field_background_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset input,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset select,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset textarea {
	background-color: #<?php echo  $settings->field_background_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'field_border_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset input,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset select,
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset textarea {
	border-color: #<?php echo  $settings->field_border_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'field_border' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'field_border',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset input, .content-form-' . $module_type . ' fieldset textarea, .content-form-' . $module_type . ' fieldset select',
		)
	);
}

if ( property_exists( $settings, 'button_background_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset.submit-form button {
	background-color: #<?php echo  $settings->button_background_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'button_background_color_hover' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset.submit-form button:hover {
	background-color: #<?php echo  $settings->button_background_color_hover; ?>;
}

	<?php
}

if ( property_exists( $settings, 'button_text_color' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset.submit-form button {
	color: #<?php echo  $settings->button_text_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'button_text_color_hover' ) ) {
	?>
.fl-node-<?php echo $id; ?> .content-form-<?php echo $module_type; ?> fieldset.submit-form button:hover {
	color: #<?php echo  $settings->button_text_color; ?>;
}

	<?php
}

if ( property_exists( $settings, 'button_typography' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_typography',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset.submit-form button',
		)
	);
}

if ( property_exists( $settings, 'button_typography_hover' ) ) {
	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_typography_hover',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset.submit-form button:hover',
		)
	);
}

if ( property_exists( $settings, 'button_border' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_border',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset.submit-form button',
		)
	);
}

if ( property_exists( $settings, 'button_border_hover' ) ) {
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'button_border_hover',
			'selector'     => '.fl-node-' . $id . ' .content-form-' . $module_type . ' fieldset.submit-form button:hover',
		)
	);
}

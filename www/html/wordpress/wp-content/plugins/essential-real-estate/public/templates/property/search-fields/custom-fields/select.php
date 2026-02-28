<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $css_class_field
 * @var $field
 */
$name = isset($field['id']) ? $field['id'] : '';
if (empty($name)) {
	return;
}
$value = isset($_REQUEST[$name]) ? wp_filter_kses(wp_unslash($_REQUEST[$name] )) : '';
$label = isset($field['label']) ? $field['label'] : '';

$options_arr = isset($field['select_choices']) ? $field['select_choices'] : '';
$options_arr = str_replace("\r\n", "\n", $options_arr);
$options_arr = str_replace("\r", "\n", $options_arr);
$options_arr = explode("\n", $options_arr);
if (!is_array($options_arr) || empty($options_arr)) {
	return;
}

$wrapper_classes = array(
	'form-group',
);
if (isset($css_class_field)) {
	$wrapper_classes[] = $css_class_field;
}
$wrapper_class = implode(' ', $wrapper_classes);

$input_classes = array(
	'form-control',
	'search-field',
	'ere-custom-search-field',
	'ere-custom-search-field-select',
	"ere-{$name}"
);
$input_class = implode(' ', $input_classes);

?>
<div class="<?php echo esc_attr($wrapper_class); ?>">
	<select name="<?php echo esc_attr($name)?>" class="<?php echo esc_attr($input_class)?>" title="<?php echo esc_attr($label)?>" data-selected="<?php echo esc_attr($value); ?>" data-default-value="">
		<option <?php selected($value,'') ?> value=""><?php echo esc_attr($label)?></option>
		<?php foreach ($options_arr as $k => $v): ?>
			<option <?php selected($value,$v) ?> value="<?php echo esc_attr($v)?>"><?php echo esc_attr($v)?></option>
		<?php endforeach; ?>
	</select>
</div>

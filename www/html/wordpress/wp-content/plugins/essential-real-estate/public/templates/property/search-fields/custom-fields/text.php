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
	"ere-{$name}"
);
$input_class = implode(' ', $input_classes);

?>
<div class="<?php echo esc_attr($wrapper_class); ?>">
	<input type="text" class="<?php echo esc_attr($input_class)?>" data-default-value="" value="<?php echo esc_attr($value); ?>" name="<?php echo esc_attr($name)?>" placeholder="<?php echo esc_attr($label)?>">
</div>

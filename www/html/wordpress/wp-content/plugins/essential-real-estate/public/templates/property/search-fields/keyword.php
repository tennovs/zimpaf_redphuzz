<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * @var $css_class_field
 * @var $request_keyword
 */
$keyword_field = ere_get_option('keyword_field','prop_address');

if( $keyword_field == 'prop_title' ) {
	$keyword_placeholder = esc_html__('Enter Keyword...','essential-real-estate');

} else if( $keyword_field == 'prop_city_state_county' ) {
	$keyword_placeholder = esc_html__('Search City, State or Area','essential-real-estate');

} else if( $keyword_field == 'prop_address' ) {
	$keyword_placeholder = esc_html__('Enter an address, zip or property ID','essential-real-estate');

} else {
	$keyword_placeholder = esc_html__('Enter Keyword...','essential-real-estate');
}

?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
	<input type="text" class="form-control search-field" data-default-value="" value="<?php echo esc_attr($request_keyword); ?>" name="keyword" placeholder="<?php echo esc_attr($keyword_placeholder)?>">
</div>

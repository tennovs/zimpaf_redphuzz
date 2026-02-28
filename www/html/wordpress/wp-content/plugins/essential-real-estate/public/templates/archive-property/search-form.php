<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$hide_archive_search_fields = ere_get_option('hide_archive_search_fields', array('property_country', 'property_state', 'property_neighborhood', 'property_label','property_rooms'));
if (!is_array($hide_archive_search_fields)) {
$hide_archive_search_fields = array();
}
$status_enable = !in_array("property_status", $hide_archive_search_fields);
$type_enable = !in_array("property_type", $hide_archive_search_fields);
$keyword_enable = !in_array("keyword", $hide_archive_search_fields);
$title_enable = !in_array("property_title", $hide_archive_search_fields);
$address_enable = !in_array("property_address", $hide_archive_search_fields);
$country_enable = !in_array("property_country", $hide_archive_search_fields);
$state_enable = !in_array("property_state", $hide_archive_search_fields);
$city_enable = !in_array("property_city", $hide_archive_search_fields);
$neighborhood_enable = !in_array("property_neighborhood", $hide_archive_search_fields);
$rooms_enable = !in_array("property_rooms", $hide_archive_search_fields);
$bedrooms_enable = !in_array("property_bedrooms", $hide_archive_search_fields);
$bathrooms_enable = !in_array("property_bathrooms", $hide_archive_search_fields);
$price_enable = !in_array("property_price", $hide_archive_search_fields);
$area_enable = !in_array("property_size", $hide_archive_search_fields);
$land_area_enable = !in_array("property_land", $hide_archive_search_fields);
$label_enable = !in_array("property_label", $hide_archive_search_fields);
$garage_enable = !in_array("property_garage", $hide_archive_search_fields);
$property_identity_enable = !in_array("property_identity", $hide_archive_search_fields);
$other_features_enable = !in_array("property_feature", $hide_archive_search_fields);
?>
    <div class="ere-heading-style2">
        <h2><?php esc_html_e('Search Property', 'essential-real-estate') ?></h2>
    </div>
<?php
$property_price_field_layout = ere_get_option('archive_search_price_field_layout', '0');
$property_size_field_layout = ere_get_option('archive_search_size_field_layout', '0');
$property_land_field_layout = ere_get_option('archive_search_land_field_layout', '0');

$additional_fields = ere_get_search_additional_fields();
$additional_fields_params = '';
foreach ($additional_fields as $k => $v) {
	$additional_fields_params .= sprintf(' %s_enable="%s"', $k, !in_array($k, $hide_archive_search_fields) ? "true" : "false");
}
echo do_shortcode('[ere_property_advanced_search layout="tab" column="3" color_scheme="color-dark" status_enable="' . ($status_enable ? 'true' : 'false') . '" type_enable="' . ($type_enable ? 'true' : 'false') . '" title_enable="' . ($title_enable ? 'true' : 'false') . '" keyword_enable="'. ($keyword_enable ? 'true' : 'false') .'" address_enable="' . ($address_enable ? 'true' : 'false') . '" country_enable="' . ($country_enable ? 'true' : 'false') . '" state_enable="' . ($state_enable ? 'true' : 'false') . '"  city_enable="' . ($city_enable ? 'true' : 'false') . '"  neighborhood_enable="' . ($neighborhood_enable ? 'true' : 'false') . '" rooms_enable="' . ($rooms_enable ? 'true' : 'false') . '" bedrooms_enable="' . ($bedrooms_enable ? 'true' : 'false') . '" bathrooms_enable="' . ($bathrooms_enable ? 'true' : 'false') . '" price_enable="' . ($price_enable ? 'true' : 'false') . '" price_is_slider="' . (($property_price_field_layout == '1') ? 'true' : 'false') . '" area_enable="' . ($area_enable ? 'true' : 'false') . '" area_is_slider="' . (($property_size_field_layout == '1') ? 'true' : 'false') . '" land_area_enable="' . ($land_area_enable ? 'true' : 'false') . '" land_area_is_slider="' . (($property_land_field_layout == '1') ? 'true' : 'false') . '" label_enable="' . ($label_enable ? 'true' : 'false') . '" garage_enable="' . ($garage_enable ? 'true' : 'false') . '" property_identity_enable="' . ($property_identity_enable ? 'true' : 'false') . '" other_features_enable="' . ($other_features_enable ? 'true' : 'false') . '" '. $additional_fields_params .']');
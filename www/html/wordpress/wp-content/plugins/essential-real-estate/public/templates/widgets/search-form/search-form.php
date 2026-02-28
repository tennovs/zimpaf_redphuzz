<?php
/**
 * Created by G5Theme.
 * User: Kaga
 * Date: 21/12/2016
 * Time: 9:35 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$layout = (!empty($instance['layout'])) ? ($instance['layout']) : 'tab';
$status_enable= (!empty($instance['status_enable'])) ? ($instance['status_enable']) : '0';
$type_enable= (!empty($instance['type_enable'])) ? ($instance['type_enable']) : '0';
$title_enable= (!empty($instance['title_enable'])) ? ($instance['title_enable']) : '0';
$address_enable= (!empty($instance['address_enable'])) ? ($instance['address_enable']) : '0';
$country_enable= (!empty($instance['country_enable'])) ? ($instance['country_enable']) : '0';
$state_enable= (!empty($instance['state_enable'])) ? ($instance['state_enable']) : '0';
$city_enable= (!empty($instance['city_enable'])) ? ($instance['city_enable']) : '0';
$neighborhood_enable= (!empty($instance['neighborhood_enable'])) ? ($instance['neighborhood_enable']) : '0';
$rooms_enable= (!empty($instance['rooms_enable'])) ? ($instance['rooms_enable']) : '0';
$bedrooms_enable= (!empty($instance['bedrooms_enable'])) ? ($instance['bedrooms_enable']) : '0';
$bathrooms_enable= (!empty($instance['bathrooms_enable'])) ? ($instance['bathrooms_enable']) : '0';
$price_enable= (!empty($instance['price_enable'])) ? ($instance['price_enable']) : 'false';
$price_is_slider= (!empty($instance['price_is_slider'])) ? ($instance['price_is_slider']) : '0';
$area_enable= (!empty($instance['area_enable'])) ? ($instance['area_enable']) : '0';
$area_is_slider= (!empty($instance['area_is_slider'])) ? ($instance['area_is_slider']) : '0';
$land_area_enable= (!empty($instance['land_area_enable'])) ? ($instance['land_area_enable']) : '0';
$land_area_is_slider= (!empty($instance['land_area_is_slider'])) ? ($instance['land_area_is_slider']) : '0';
$label_enable= (!empty($instance['label_enable'])) ? ($instance['label_enable']) : '0';
$garage_enable= (!empty($instance['garage_enable'])) ? ($instance['garage_enable']) : '0';
$property_identity_enable= (!empty($instance['property_identity_enable'])) ? ($instance['property_identity_enable']) : '0';
echo do_shortcode('[ere_property_advanced_search layout="'.$layout.'" column="1" color_scheme="color-dark"  status_enable="'. ($status_enable==1 ? 'true' : 'false').'" type_enable="'.($type_enable==1 ? 'true' : 'false').'" title_enable="'.($title_enable==1? 'true' : 'false').'" address_enable="'.($address_enable==1? 'true' : 'false').'" country_enable="'.($country_enable==1? 'true' : 'false').'" state_enable="'.($state_enable==1? 'true' : 'false').'"  city_enable="'.($city_enable==1? 'true' : 'false').'" neighborhood_enable="'.($neighborhood_enable==1? 'true' : 'false').'" rooms_enable="'.($rooms_enable==1? 'true' : 'false').'" bedrooms_enable="'.($bedrooms_enable==1? 'true' : 'false').'" bathrooms_enable="'.($bathrooms_enable==1? 'true' : 'false').'" price_enable="'.($price_enable==1? 'true' : 'false').'" price_is_slider="'.($price_is_slider==1? 'true' : 'false').'" area_enable="'.($area_enable==1? 'true' : 'false').'" area_is_slider="'.($area_is_slider==1? 'true' : 'false').'" land_area_enable="'.($land_area_enable==1? 'true' : 'false').'" land_area_is_slider="'.($land_area_is_slider==1? 'true' : 'false').'" label_enable="'.($label_enable==1? 'true' : 'false').'" garage_enable="'.($garage_enable==1? 'true' : 'false').'" property_identity_enable="'.($property_identity_enable==1? 'true' : 'false').'"]');
<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 30/12/2016
 * Time: 8:20 SA
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$key = false;
$user_id = $current_user->ID;
$my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);
$property_id= get_the_ID();
if (!empty($my_favorites)) {
	$key = array_search($property_id, $my_favorites);
}
$title_not_favorite = $title_favorited = '';
$icon_favorite = apply_filters('ere_icon_favorite','fa fa-star') ;
$icon_not_favorite = apply_filters('ere_icon_not_favorite','fa fa-star-o');

if ($key !== false) {
	$css_class = $icon_favorite;
	$title = esc_attr__('It is my favorite', 'essential-real-estate');
} else {
	$css_class = $icon_not_favorite;
	$title =esc_attr__('Add to Favorite', 'essential-real-estate');
}
?>
<a href="javascript:void(0)" class="property-favorite" data-property-id="<?php echo intval($property_id) ?>"
   data-toggle="tooltip"
   title="<?php echo esc_attr($title) ?>" data-title-not-favorite="<?php esc_attr_e('Add to Favorite', 'essential-real-estate') ?>" data-title-favorited="<?php esc_attr_e('It is my favorite', 'essential-real-estate'); ?>" data-icon-not-favorite="<?php echo esc_attr($icon_not_favorite)?>" data-icon-favorited="<?php echo esc_attr($icon_favorite)?>"><i
			class="<?php echo esc_attr($css_class); ?>"></i></a>
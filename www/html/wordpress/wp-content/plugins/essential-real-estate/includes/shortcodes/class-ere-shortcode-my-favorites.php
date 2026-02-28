<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_My_Favorites')) {
	/**
	 * Class ERE_Shortcode_My_Favorites
	 */
	class ERE_Shortcode_My_Favorites
	{
		public static function output($atts)
		{
			if (!is_user_logged_in()) {
				echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
				return null;
			}
			$posts_per_page = 8;
			extract(shortcode_atts(array(
				'posts_per_page' => '9',
			), $atts));
			ob_start();
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			$my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);
			if(empty($my_favorites))
			{
				$my_favorites=array(0);
			}
			$args = apply_filters('ere_my_properties_query_args', array(
				'post_type' => 'property',
				'post__in' => $my_favorites,
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $posts_per_page,
				'offset' => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
			));

			$favorites = new WP_Query($args);
			ere_get_template('property/my-favorites.php', array('favorites' => $favorites, 'max_num_pages' => $favorites->max_num_pages));
			return ob_get_clean();
		}
	}
}
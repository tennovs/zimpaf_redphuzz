<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Search')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Search
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			$search_styles = isset($atts['search_styles']) ? $atts['search_styles'] : 'style-default';
			$map_search_enable = isset($atts['map_search_enable']) ? $atts['map_search_enable'] : '';

			if ($search_styles === 'style-vertical' || $search_styles === 'style-absolute') {
				$map_search_enable='true';
			}

			if ($map_search_enable == 'true') {
				wp_enqueue_script('google-map');
				wp_enqueue_script('markerclusterer');
				wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search_js_map');
			} else {
				wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search_js');
			}


			wp_enqueue_style( ERE_PLUGIN_PREFIX . 'property-search');
			wp_enqueue_style( ERE_PLUGIN_PREFIX . 'property');

			$enable_filter_location = ere_get_option('enable_filter_location', 0);
			if($enable_filter_location==1)
			{
				wp_enqueue_style( 'select2_css');
				wp_enqueue_script('select2_js');
			}

			return ere_get_template_html('shortcodes/property-search/property-search.php', array('atts' => $atts));
		}
	}
}
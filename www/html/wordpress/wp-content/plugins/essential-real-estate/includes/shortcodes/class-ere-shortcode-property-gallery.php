<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Gallery')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Gallery
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			$filter_style = isset($atts['filter_style']) ? $atts['filter_style'] : 'filter-isotope';

			if ($filter_style == 'filter-isotope') {
				wp_enqueue_script('isotope');
			}

			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-gallery');
			wp_enqueue_script('imageLoaded');
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_gallery');

			return ere_get_template_html('shortcodes/property-gallery/property-gallery.php', array('atts' => $atts));
		}
	}
}
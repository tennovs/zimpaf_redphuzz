<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 08/11/2016
 * Time: 10:30 SA
 */
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('ERE_Shortcode')) {
	/**
	 * ERE_Shortcode_Agent class.
	 */
	class ERE_Shortcode
	{

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->include_system_shortcode();
			$this->register_public_shortcode();
		}

		/**
		 * Include system shortcode
		 */
		public function include_system_shortcode()
		{
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-account.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-property.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-package.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-payment.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-invoice.php';
		}

		/**
		 * Register shortcode
		 */
		public function register_public_shortcode()
		{
			add_shortcode('ere_property', array($this, 'property_shortcode'));
			add_shortcode('ere_property_carousel', array($this, 'property_carousel_shortcode'));
			add_shortcode('ere_property_slider', array($this, 'property_slider_shortcode'));
			add_shortcode('ere_property_gallery', array($this, 'property_gallery_shortcode'));
			add_shortcode('ere_property_featured', array($this, 'property_featured_shortcode'));
			add_shortcode('ere_property_type', array($this, 'property_type_shortcode'));
			add_shortcode('ere_property_search', array($this, 'property_search_shortcode'));
			add_shortcode('ere_property_search_map', array($this, 'property_search_map_shortcode'));
			add_shortcode('ere_property_advanced_search', array($this, 'property_advanced_search_shortcode'));
			add_shortcode('ere_property_mini_search', array($this, 'property_mini_search_shortcode'));
			add_shortcode('ere_property_map', array($this, 'property_map_shortcode'));
			add_shortcode('ere_agent', array($this, 'agent_shortcode'));
			add_shortcode('ere_agency', array($this, 'agency_shortcode'));
		}

		/**
		 * Property Gallery
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_gallery_shortcode($atts)
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

		/**
		 * Property Carousel with Left Navigation
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_carousel_shortcode($atts)
		{
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-carousel');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
			return ere_get_template_html('shortcodes/property-carousel/property-carousel.php', array('atts' => $atts));
		}

		/**
		 * Property Slider
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_slider_shortcode($atts) {
			wp_enqueue_style( ERE_PLUGIN_PREFIX . 'property-slider');
			return ere_get_template_html('shortcodes/property-slider/property-slider.php', array('atts' => $atts));
		}
		/**
		 * Property Search
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_search_shortcode($atts) {
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

		/**
		 * Property Search Map
		 * @param $atts
		 * @return string
		 */
		public function property_search_map_shortcode($atts) {
			wp_enqueue_script('google-map');
			wp_enqueue_script('markerclusterer');
			wp_enqueue_style( 'select2_css');
			wp_enqueue_script('select2_js');


			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search_map');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-search-map');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
			return ere_get_template_html('shortcodes/property-search-map/property-search-map.php', array('atts' => $atts));
		}
		/**
		 * Property Full Search
		 * @param $atts
		 * @return string
		 */
		public function property_advanced_search_shortcode($atts) {
			$enable_filter_location = ere_get_option('enable_filter_location', 0);
			if($enable_filter_location==1)
			{
				wp_enqueue_style( 'select2_css');
				wp_enqueue_script('select2_js');
			}

			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'advanced_search_js');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-advanced-search');
			return ere_get_template_html('shortcodes/property-advanced-search/property-advanced-search.php', array('atts' => $atts));
		}
		/**
		 * Mini Search
		 * @param $atts
		 * @return string
		 */
		public function property_mini_search_shortcode($atts) {
			return ere_get_template_html('shortcodes/property-mini-search/property-mini-search.php', array('atts' => $atts));
		}

		/**
		 * Property Featured
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_featured_shortcode($atts)
		{
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-featured');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_featured');
			return ere_get_template_html('shortcodes/property-featured/property-featured.php', array('atts' => $atts));
		}

		/**
		 * Property type
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_type_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-type/property-type.php', array('atts' => $atts));
		}

		/**
		 * Property shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property/property.php', array('atts' => $atts));
		}

		/**
		 * Agent shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function agent_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/agent/agent.php', array('atts' => $atts));
		}

		/**
		 * Agency shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function agency_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/agency/agency.php', array('atts' => $atts));
		}

		/**
		 * googlemap property
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_map_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-map/property-map.php', array('atts' => $atts));
		}

		/**
		 * Filter Ajax callback
		 */
		public function property_gallery_fillter_ajax()
		{
			$property_type = isset($_REQUEST['property_type']) ?  str_replace('.', '',ere_clean(wp_unslash($_REQUEST['property_type']))) : '';
			$is_carousel = isset($_REQUEST['is_carousel']) ? ere_clean(wp_unslash($_REQUEST['is_carousel'])) : '';
			$columns_gap = isset($_REQUEST['columns_gap']) ? wp_unslash($_REQUEST['columns_gap']) : 'col-gap-30';
			$columns = isset($_REQUEST['columns']) ? absint(wp_unslash($_REQUEST['columns'])) : 4;
			$item_amount = isset($_REQUEST['item_amount']) ? absint(wp_unslash($_REQUEST['item_amount'])) : 10;
			$image_size = isset($_REQUEST['image_size']) ? ere_clean(wp_unslash($_REQUEST['image_size'])) : '';
			$color_scheme = isset($_REQUEST['color_scheme']) ? ere_clean(wp_unslash($_REQUEST['color_scheme'])) : '';

			$short_code = '[ere_property_gallery is_carousel="' . $is_carousel . '" color_scheme="' . $color_scheme . '"
		columns="' . $columns . '" item_amount="' . $item_amount . '" image_size="' . $image_size . '" columns_gap="' . $columns_gap . '"
		category_filter="true" property_type="' . $property_type . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Filter City Ajax callback
		 */
		public function property_featured_fillter_city_ajax()
		{
			$property_city = isset($_REQUEST['property_city']) ? str_replace('.', '', ere_clean(wp_unslash($_REQUEST['property_city']))) : '';
			$layout_style= isset($_REQUEST['layout_style']) ? ere_clean(wp_unslash($_REQUEST['layout_style'])) : '';
			$property_type= isset($_REQUEST['property_type']) ? ere_clean(wp_unslash($_REQUEST['property_type'])) : '';
			$property_status= isset($_REQUEST['property_status']) ? ere_clean(wp_unslash($_REQUEST['property_status'])) : '';
			$property_feature= isset($_REQUEST['property_feature']) ? ere_clean(wp_unslash($_REQUEST['property_feature'])) : '';
			$property_cities= isset($_REQUEST['property_cities']) ? ere_clean(wp_unslash($_REQUEST['property_cities'])) : '';
			$property_state= isset($_REQUEST['property_state']) ? ere_clean(wp_unslash($_REQUEST['property_state'])) : '';
			$property_neighborhood= isset($_REQUEST['property_neighborhood']) ? ere_clean(wp_unslash($_REQUEST['property_neighborhood'])) : '';
			$property_label= isset($_REQUEST['property_label']) ? ere_clean(wp_unslash($_REQUEST['property_label'])) : '';
			$color_scheme= isset($_REQUEST['color_scheme']) ? ere_clean(wp_unslash($_REQUEST['color_scheme'])) : '';
			$item_amount= isset($_REQUEST['item_amount']) ? absint(wp_unslash($_REQUEST['item_amount'])) : 10;
			$image_size= isset($_REQUEST['image_size']) ? ere_clean(wp_unslash($_REQUEST['image_size'])) : '';
			$include_heading= isset($_REQUEST['include_heading']) ? ere_clean(wp_unslash($_REQUEST['include_heading'])) : '';
			$heading_sub_title= isset($_REQUEST['heading_sub_title']) ? ere_clean(wp_unslash($_REQUEST['heading_sub_title'])) : '';
			$heading_title= isset($_REQUEST['heading_title']) ? ere_clean(wp_unslash($_REQUEST['heading_title'])) : '';
			$heading_text_align= isset($_REQUEST['heading_text_align']) ? ere_clean(wp_slash($_REQUEST['heading_text_align'])) : '';
			$short_code = '[ere_property_featured layout_style="' . $layout_style . '" property_type="' . $property_type . '" property_status="' . $property_status . '" property_feature="' . $property_feature . '" property_cities="' . $property_cities . '" property_state="' . $property_state . '" property_neighborhood="' . $property_neighborhood . '" property_label="' . $property_label . '" color_scheme="' . $color_scheme . '" color_scheme="' . $color_scheme . '" item_amount="' . $item_amount . '" image_size2="' . $image_size . '" include_heading="' . $include_heading . '" heading_sub_title="' . $heading_sub_title . '" heading_title="' . $heading_title . '" heading_text_align="' . $heading_text_align . '" property_city="' . $property_city . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Property paging
		 */
		public function property_paging_ajax()
		{
			$paged = isset($_REQUEST['paged']) ? absint(wp_unslash($_REQUEST['paged'])) : 1;
			$layout = isset($_REQUEST['layout']) ? ere_clean(wp_unslash($_REQUEST['layout'])) : '';
			$items_amount = isset($_REQUEST['items_amount']) ? absint(wp_unslash($_REQUEST['items_amount'])) : 10;
			$columns = isset($_REQUEST['columns']) ? absint(wp_unslash($_REQUEST['columns'])) : 4;
			$image_size = isset($_REQUEST['image_size']) ? ere_clean(wp_unslash($_REQUEST['image_size']))  : '';
			$columns_gap = isset($_REQUEST['columns_gap']) ? wp_unslash($_REQUEST['columns_gap']) : 'col-gap-30';
			$view_all_link = isset($_REQUEST['view_all_link']) ? ere_clean(wp_unslash($_REQUEST['view_all_link'])) : '';

			$property_type= isset($_REQUEST['property_type']) ? ere_clean(wp_unslash($_REQUEST['property_type'])) : '';
			$property_status= isset($_REQUEST['property_status']) ? ere_clean(wp_unslash($_REQUEST['property_status'])) : '';
			$property_feature= isset($_REQUEST['property_feature']) ? ere_clean(wp_unslash($_REQUEST['property_feature'])) : '';
			$property_city= isset($_REQUEST['property_city']) ? ere_clean(wp_unslash($_REQUEST['property_city'])) : '';
			$property_state= isset($_REQUEST['property_state']) ? ere_clean(wp_unslash($_REQUEST['property_state'])) : '';
			$property_neighborhood= isset($_REQUEST['property_neighborhood']) ? ere_clean(wp_unslash($_REQUEST['property_neighborhood'])) : '';
			$property_label= isset($_REQUEST['property_label']) ? ere_clean(wp_unslash($_REQUEST['property_label'])) : '';
			$property_featured= isset($_REQUEST['property_featured']) ? ere_clean(wp_unslash($_REQUEST['property_featured'])) : '';

			$author_id = isset($_REQUEST['author_id']) ? ere_clean(wp_unslash($_REQUEST['author_id'])) : '';
			$agent_id = isset($_REQUEST['agent_id']) ? ere_clean(wp_unslash($_REQUEST['agent_id'])) : '';
			$short_code = '[ere_property item_amount="' . $items_amount . '" layout_style="' . $layout . '"
					view_all_link="' . $view_all_link . '" show_paging="true" columns="' . $columns . '"
					image_size="' . $image_size . '" columns_gap="' . $columns_gap . '" paged="' . $paged . '"
					property_type="' . $property_type . '" property_status="' . $property_status . '"
					property_feature="' . $property_feature . '" property_city="' . $property_city . '"
					property_state="' . $property_state . '" property_neighborhood="' . $property_neighborhood . '"
					property_label="' . $property_label . '" property_featured="' . $property_featured . '"
				    author_id="' . $author_id . '" agent_id="' . $agent_id . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Agent paging
		 */
		public function agent_paging_ajax()
		{
			$paged = isset($_REQUEST['paged']) ? absint(wp_unslash($_REQUEST['paged'])) : 1;
			$layout = isset($_REQUEST['layout']) ? ere_clean(wp_unslash($_REQUEST['layout'])) : '';
			$item_amount = isset($_REQUEST['item_amount']) ? absint(wp_unslash($_REQUEST['item_amount'])) : 10;
			$items = isset($_REQUEST['items']) ? ere_clean(wp_unslash($_REQUEST['items'])) : '';
			$image_size = isset($_REQUEST['image_size']) ? ere_clean(wp_unslash($_REQUEST['image_size'])) : '';
			$show_paging = isset($_REQUEST['show_paging']) ? ere_clean(wp_unslash($_REQUEST['show_paging'])) : '';
			$post_not_in = isset($_REQUEST['post_not_in']) ? ere_clean(wp_unslash($_REQUEST['post_not_in'])) : '';

			$short_code = '[ere_agent layout_style="' . $layout . '" item_amount="' . $item_amount . '" items ="' . $items . '" image_size="' . $image_size . '" paged="' . $paged . '" show_paging="' . $show_paging . '" post_not_in="' . $post_not_in . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		public function property_set_session_view_as_ajax() {
			ERE_Compare::open_session();
			$view_as = isset($_REQUEST['view_as']) ? ere_clean(wp_unslash($_REQUEST['view_as'])) : '';
			if (!empty( $view_as ) && in_array($view_as, array('property-list', 'property-grid'))) {
				$_SESSION['property_view_as'] = $view_as;
			}
		}

		public function agent_set_session_view_as_ajax() {
            $view_as = isset($_REQUEST['view_as']) ? ere_clean(wp_unslash($_REQUEST['view_as'])) : '';
			if (!empty( $view_as ) && in_array($view_as, array('agent-list', 'agent-grid'))) {
				$_SESSION['agent_view_as'] = $view_as;
			}
		}
	}
}
new ERE_Shortcode();


<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://themeforest.net/user/G5Themes
 * @since      1.0.0
 *
 * @package    Essential_Real_Estate
 * @subpackage Essential_Real_Estate/includes
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Template_Hooks')) {
    /**
     * Class ERE_Template_Hooks
     */
    require_once ERE_PLUGIN_DIR . 'includes/class-ere-loader.php';

    class ERE_Template_Hooks
    {
        protected $loader;
        /**
         * Instance variable for singleton pattern
         */
        private static $instance = null;

        /**
         * Return class instance
         * @return ERE_Template_Hooks|null
         */
        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {
            $this->loader = new ERE_Loader();
            $this->loader->add_action('ere_before_main_content', $this, 'output_content_wrapper_start', 10);
            $this->loader->add_action('ere_after_main_content', $this, 'output_content_wrapper_end', 10);
            //property_sidebar
            $this->loader->add_action('ere_sidebar_property', $this, 'sidebar_property', 10);
            $this->loader->add_action('ere_sidebar_agent', $this, 'sidebar_agent', 10);
            $this->loader->add_action('ere_sidebar_invoice', $this, 'sidebar_invoice', 10);

            //Archive Property
            $this->loader->add_action('ere_archive_property_before_main_content', $this, 'archive_property_search', 10);
            $this->loader->add_action('ere_archive_property_heading', $this, 'archive_property_heading', 10, 4);
            $this->loader->add_action('ere_archive_property_action', $this, 'archive_property_action', 10, 1);
            $this->loader->add_action('ere_loop_property', $this, 'loop_property', 10, 4);
            //Advanced Search
            $this->loader->add_action('ere_advanced_search_before_main_content', $this, 'advanced_property_search', 10);
            //Archive Agent
            $this->loader->add_action('ere_archive_agent_heading', $this, 'archive_agent_heading', 10, 1);
            $this->loader->add_action('ere_archive_agent_action', $this, 'archive_agent_action', 10, 1);
            $this->loader->add_action('ere_loop_agent', $this, 'loop_agent', 10, 3);

            //Single Property
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_header', 5);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_gallery', 10);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_description', 15);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_location', 20);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_features', 25);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_floors', 30);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_attachments', 35);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_map_directions', 40);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_nearby_places', 45);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_walk_score', 50);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_contact_agent', 55);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_footer', 90);

            $enable_comments_reviews_property = ere_get_option('enable_comments_reviews_property', 1);
            if ($enable_comments_reviews_property == 1) {
                $this->loader->add_action('ere_single_property_after_summary', $this, 'comments_template', 95);
            }
            if ($enable_comments_reviews_property == 2) {
                $this->loader->add_action('ere_single_property_summary', $this, 'single_property_reviews', 95);
            }

            //Single Agent
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_info', 5);

            $enable_comments_reviews_agent = ere_get_option('enable_comments_reviews_agent', 0);
            if ($enable_comments_reviews_agent == 1) {
                $this->loader->add_action('ere_single_agent_summary', $this, 'comments_template', 15);
            }
            if ($enable_comments_reviews_agent == 2) {
                $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_reviews', 15);
            }
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_property', 20);
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_other', 30);

            //Author
            $this->loader->add_action('ere_author_summary', $this, 'author_info', 5);
            $this->loader->add_action('ere_author_summary', $this, 'author_property', 10);

            //Single Invoice
            $this->loader->add_action('ere_single_invoice_summary', $this, 'single_invoice', 10);
            //Taxonomy
            $this->loader->add_action('ere_taxonomy_agency_summary', $this, 'taxonomy_agency_detail', 10);
            $this->loader->add_action('ere_taxonomy_agency_agents', $this, 'taxonomy_agency_agents', 10, 1);
            //Property Action
            $this->loader->add_action('ere_property_action', $this, 'property_view_gallery', 5);
            $this->loader->add_action('ere_property_action', $this, 'property_favorite', 10);
            $this->loader->add_action('ere_property_action', $this, 'property_compare', 15);

	        add_action( 'pre_get_posts', array( $this, 'order_by_featured_pre_get_posts' ),99 );
	        add_action( 'pre_get_posts', array( $this, 'order_by_viewed_pre_get_posts' ),99 );
            $this->loader->run();
        }

        /**
         * output_content_wrapper
         */
        public function output_content_wrapper_start()
        {
            ere_get_template('global/wrapper-start.php');
        }

        /**
         * output_content_wrapper
         */
        public function output_content_wrapper_end()
        {
            ere_get_template('global/wrapper-end.php');
        }

        /**
         * archive_property_search
         */
        public function archive_property_search()
        {
            $enable_archive_search_form = ere_get_option('enable_archive_search_form', '0');
            if ($enable_archive_search_form == '1'){
                ere_get_template('archive-property/search-form.php');
            }
        }

        /**
         * advanced_property_search
         */
        public function advanced_property_search()
        {
            $enable_advanced_search_form = ere_get_option('enable_advanced_search_form', '1');
            if ($enable_advanced_search_form == '1') {
	            $additional_fields = ere_get_search_additional_fields();
	            $additional_fields_attr = array();
	            foreach ($additional_fields as $k => $v) {
		            $additional_fields_attr[] = sprintf('%s_enable="true"',$k);
	            }
	            $additional_fields_attr = implode(' ', $additional_fields_attr);

                $property_price_field_layout = ere_get_option('advanced_search_price_field_layout', '0');
                $property_size_field_layout = ere_get_option('advanced_search_size_field_layout', '0');
                $property_land_field_layout = ere_get_option('advanced_search_land_field_layout', '0');
                echo do_shortcode('[ere_property_advanced_search layout="tab" column="3" color_scheme="color-dark" status_enable="true" type_enable="true" keyword_enable="true" title_enable="true" address_enable="true" country_enable="true" state_enable="true"  city_enable="true"  neighborhood_enable="true" rooms_enable="true" bedrooms_enable="true" bathrooms_enable="true" price_enable="true" price_is_slider="' . (($property_price_field_layout == '1') ? 'true' : 'false') . '" area_enable="true" area_is_slider="' . (($property_size_field_layout == '1') ? 'true' : 'false') . '" land_area_enable="true" land_area_is_slider="' . (($property_land_field_layout == '1') ? 'true' : 'false') . '" label_enable="true" garage_enable="true" property_identity_enable="true" other_features_enable="true" '. $additional_fields_attr .']');
            }
        }

        /**
         * property_sidebar
         */
        public function  sidebar_property()
        {
            ere_get_template('global/sidebar-property.php');
        }

        /**
         *agent_sidebar
         */
        public function sidebar_agent()
        {
            ere_get_template('global/sidebar-agent.php');
        }

        /**
         * invoice_sidebar
         */
        public function sidebar_invoice()
        {
            ere_get_template('global/sidebar-invoice.php');
        }

        /**
         * archive_property_heading
         * @param $total_post
         * @param $taxonomy_title
         * @param $agent_id
         * @param $author_id
         */
        public function archive_property_heading($total_post, $taxonomy_title, $agent_id, $author_id)
        {
            ere_get_template('archive-property/heading.php', array('total_post' => $total_post, 'taxonomy_title' => $taxonomy_title, 'agent_id' => $agent_id, 'author_id' => $author_id));
        }

        /**
         * archive_property_action
         * @param $taxonomy_name
         */
        public function archive_property_action($taxonomy_name)
        {
            ere_get_template('archive-property/action.php', array('taxonomy_name' => $taxonomy_name));
        }

        /**
         * archive_agent_heading
         * @param $total_post
         */
        public function archive_agent_heading($total_post)
        {
            ere_get_template('archive-agent/heading.php', array('total_post' => $total_post));
        }

        /**
         * archive_agent_action
         * @param $keyword
         */
        public function archive_agent_action($keyword)
        {
            ere_get_template('archive-agent/action.php', array('keyword' => $keyword));
        }

        /**
         * loop_property
         * @param $property_item_class
         * @param $custom_property_image_size
         * @param $property_image_class
         * @param $property_item_content_class
         */
        public function loop_property($property_item_class, $custom_property_image_size, $property_image_class, $property_item_content_class)
        {
            ere_get_template('loop/property.php', array(
            	'property_item_class' => $property_item_class,
	            'custom_property_image_size' => $custom_property_image_size,
	            'property_image_class' => $property_image_class,
	            'property_item_content_class' => $property_item_content_class
            ));
        }

        /**
         * loop_agent
         * @param $gf_item_wrap
         * @param $agent_layout_style
         */
        public function loop_agent($gf_item_wrap, $agent_layout_style, $custom_agent_image_size)
        {
            ere_get_template('loop/agent.php', array('gf_item_wrap' => $gf_item_wrap, 'agent_layout_style' => $agent_layout_style, 'custom_agent_image_size' => $custom_agent_image_size));
        }

        /**
         * single_property_header
         */
        public function single_property_header()
        {
            ere_get_template('single-property/header.php');
        }

        /**
         * single_property_footer
         */
        public function single_property_footer()
        {
            ere_get_template('single-property/footer.php');
        }

        /**
         * single_property_reviews
         */
        public function single_property_reviews()
        {
            ere_get_template('single-property/review.php');
        }

        /**
         * single_property_gallery
         */
        public function single_property_gallery()
        {
            ere_get_template('single-property/gallery.php');
        }

        /**
         * single_property_description
         */
        public function single_property_description()
        {
            ere_get_template('single-property/description.php');
        }

        /**
         * single_property_attachments
         */
        public function single_property_attachments()
        {
            ere_get_template('single-property/attachments.php');
        }

        /**
         * single_property_location
         */
        public function single_property_location()
        {
            ere_get_template('single-property/location.php');
        }

        /**
         * single_property_features
         */
        public function single_property_features()
        {
            ere_get_template('single-property/features.php');
        }

        /**
         * single_property_floors
         */
        public function single_property_floors()
        {
            global $post;
            $property_meta_data = get_post_custom($post->ID);
            $property_floors = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'floors', true);
            $property_floor_enable = isset($property_meta_data[ERE_METABOX_PREFIX . 'floors_enable']) ? $property_meta_data[ERE_METABOX_PREFIX . 'floors_enable'][0] : '';
            if ($property_floor_enable && $property_floors) {
                ere_get_template('single-property/floors.php', array('property_floors' => $property_floors));
            }
        }

        /**
         * single_property_map_directions
         */
        public function single_property_map_directions()
        {
            global $post;
            $enable_map_directions = ere_get_option('enable_map_directions', 1);
            if ($enable_map_directions == 1){
                ere_get_template('single-property/google-map-directions.php', array('property_id' => $post->ID));
            }
        }

        /**
         * single_property_nearby_places
         */
        public function single_property_nearby_places()
        {
            global $post;
            $enable_nearby_places = ere_get_option('enable_nearby_places', 1);
            if ($enable_nearby_places == 1){
                ere_get_template('single-property/nearby-places.php', array('property_id' => $post->ID));
            }
        }

        /**
         * single_property_walk_score
         */
        public function single_property_walk_score()
        {
            global $post;
            $enable_walk_score = ere_get_option('enable_walk_score', 0);
            if ($enable_walk_score == 1)
            {
                ere_get_template('single-property/walk-score.php', array('property_id' => $post->ID));
            }
        }

        /**
         * single_property_contact_agent
         */
        public function single_property_contact_agent()
        {
            $property_form_sections = ere_get_option('property_form_sections', array('title_des', 'location', 'type', 'price', 'features', 'details', 'media', 'floors', 'agent'));
            if (in_array('contact', $property_form_sections)) {
                $hide_contact_information_if_not_login = ere_get_option('hide_contact_information_if_not_login', 0);
                if ($hide_contact_information_if_not_login == 0) {
                    ere_get_template('single-property/contact-agent.php');
                } else {
                    if (is_user_logged_in()) {
                        ere_get_template('single-property/contact-agent.php');
                    } else {
                        ere_get_template('single-property/contact-agent-not-login.php');
                    }
                }
            }
        }

        /**
         * single_agent_info
         */
        public function single_agent_info()
        {
            ere_get_template('single-agent/agent-info.php');
        }

        /**
         * single_agent_reviews
         */
        public function single_agent_reviews()
        {
            ere_get_template('single-agent/review.php');
        }

        /**
         * single_agent_property
         */
        public function single_agent_property()
        {
            $enable_property_of_agent = ere_get_option('enable_property_of_agent');
            if ($enable_property_of_agent == 1) {
                ere_get_template('single-agent/agent-property.php');
            }
        }

        /**
         * author_info
         */
        public function author_info()
        {
            ere_get_template('author/author-info.php');
        }

        /**
         * author_property
         */
        public function author_property()
        {
            ere_get_template('author/author-property.php');
        }

        /**
         * single_agent_other
         */
        public function single_agent_other()
        {
            $enable_other_agent = ere_get_option('enable_other_agent');
            if ($enable_other_agent == 1) {
                ere_get_template('single-agent/other-agent.php');
            }
        }

        /**
         * single_invoice
         */
        public function single_invoice()
        {
            ere_get_template('single-invoice/invoice.php');
        }

        /**
         * taxonomy_agency_detail
         */
        public function taxonomy_agency_detail()
        {
            ere_get_template('taxonomy/agency-detail.php');
        }

        /**
         * taxonomy_agency_agents
         * @param $agency_term_slug
         */
        public function taxonomy_agency_agents($agency_term_slug)
        {
            ere_get_template('taxonomy/agency-agents.php', array('agency_term_slug' => $agency_term_slug));
        }

        /**
         * Social Share
         */
        public function property_view_gallery()
        {
            ere_get_template('property/view-galley.php');
        }

        /**
         * Favorite
         */
        public function property_favorite()
        {
            if (ere_get_option('enable_favorite_property', '1') == '1') {
                ere_get_template('property/favorite.php');
            }
        }

        /**
         * Compare
         */
        public function property_compare()
        {
            if (ere_get_option('enable_compare_properties', '1') == '1'){
                ere_get_template('property/compare-button.php');
            }
        }

        /**
         * comments_template
         */
        public function comments_template()
        {
            // If comments are open or we have at least one comment, load up the comment template
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
        }


        public function order_by_featured_pre_get_posts($q) {
	        $ere_orderby_featured = $q->get('ere_orderby_featured',false);
	        if ($ere_orderby_featured == true) {
		        add_filter('posts_clauses', array($this , 'order_by_featured_post_clauses') , 10,2);
		        add_filter('the_posts', array($this , 'remove_property_query_featured_filters') );
	        }
        }

	    public function order_by_featured_post_clauses($args, $wp_query) {
		    global $wpdb;
		    $ere_prefix = ERE_METABOX_PREFIX;
		    $args['join'] .= " LEFT JOIN {$wpdb->prefix}postmeta as ere_mt1 ON ( {$wpdb->prefix}posts.ID = ere_mt1.post_id AND ere_mt1.meta_key = '{$ere_prefix}property_featured')";
		    $args['join'] .= " LEFT JOIN {$wpdb->prefix}postmeta as ere_mt2 ON ( {$wpdb->prefix}posts.ID = ere_mt2.post_id AND ere_mt2.meta_key = '{$ere_prefix}property_featured_date')";
		    $args['orderby'] = " CAST(ere_mt1.meta_value AS CHAR) DESC, CAST(ere_mt2.meta_value AS CHAR) DESC, {$wpdb->prefix}posts.menu_order DESC, {$wpdb->prefix}posts.post_date DESC ";
		    return $args;
	    }

	    public function remove_property_query_featured_filters($posts) {
		    remove_filter( 'posts_clauses', array( $this, 'order_by_featured_post_clauses' ) );
        	return $posts;
	    }

	    public function order_by_viewed_pre_get_posts($q) {
		    $ere_orderby_viewed = $q->get('ere_orderby_viewed',false);
		    if ($ere_orderby_viewed == true) {
			    add_filter('posts_clauses', array($this , 'order_by_viewed_post_clauses') , 10,2);
			    add_filter('the_posts', array($this , 'remove_property_query_viewed_filters') );
		    }
	    }

	    public function order_by_viewed_post_clauses($args, $wp_query) {
		    global $wpdb;
		    $ere_prefix = ERE_METABOX_PREFIX;
		    $args['join'] .= " LEFT JOIN {$wpdb->prefix}postmeta as ere_mt3 ON ( {$wpdb->prefix}posts.ID = ere_mt3.post_id AND ere_mt3.meta_key = '{$ere_prefix}property_views_count')";
		    $args['orderby'] = " (ere_mt3.meta_value+0) DESC, {$wpdb->prefix}posts.menu_order DESC, {$wpdb->prefix}posts.post_date DESC ";
		    return $args;
	    }

	    public function remove_property_query_viewed_filters($posts) {
		    remove_filter( 'posts_clauses', array( $this, 'order_by_viewed_post_clauses' ) );
		    return $posts;
	    }

    }
}
if (!function_exists('ere_template_hooks')) {
    function ere_template_hooks()
    {
        return ERE_Template_Hooks::get_instance();
    }
}
// Global for backwards compatibility.
$GLOBALS['ere_template_hooks'] = ere_template_hooks();
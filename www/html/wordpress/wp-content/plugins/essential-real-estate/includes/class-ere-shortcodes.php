<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * ERE_Shortcodes class.
 */
class ERE_Shortcodes {

	/*
		 * loader instances
		 */
	private static $_instance;

	public static function getInstance()
	{
		if (self::$_instance == null) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	private static $ere_message;
	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'ere_login'                    => __CLASS__ . '::login',
			'ere_register'                    => __CLASS__ . '::register',
			'ere_profile'                    => __CLASS__ . '::profile',
			'ere_reset_password'                    => __CLASS__ . '::reset_password',
			'ere_package'                    => __CLASS__ . '::package',
			'ere_my_invoices'                    => __CLASS__ . '::my_invoices',
			'ere_payment'                    => __CLASS__ . '::payment',
			'ere_payment_completed'                    => __CLASS__ . '::payment_completed',
			'ere_my_properties'                    => __CLASS__ . '::my_properties',
			'ere_submit_property'                    => __CLASS__ . '::submit_property',
			'ere_my_favorites'                    => __CLASS__ . '::my_favorites',
			'ere_advanced_search'                    => __CLASS__ . '::advanced_search',
			'ere_my_save_search'                    => __CLASS__ . '::my_save_search',
			'ere_compare'                    => __CLASS__ . '::compare',
			///
			'ere_property'                    => __CLASS__ . '::property',
			'ere_property_carousel'                    => __CLASS__ . '::property_carousel',
			'ere_property_slider'                    => __CLASS__ . '::property_slider',
			'ere_property_gallery'                    => __CLASS__ . '::property_gallery',
			'ere_property_featured'                    => __CLASS__ . '::property_featured',
			'ere_property_type'                    => __CLASS__ . '::property_type',
			'ere_property_search'                    => __CLASS__ . '::property_search',
			'ere_property_search_map'                    => __CLASS__ . '::property_search_map',
			'ere_property_advanced_search'                    => __CLASS__ . '::property_advanced_search',
			'ere_property_mini_search'                    => __CLASS__ . '::property_mini_search',
			'ere_property_map'                    => __CLASS__ . '::property_map',
			'ere_agent'                    => __CLASS__ . '::agent',
			'ere_agency'                    => __CLASS__ . '::agency',
		);
		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();
		echo empty( $wrapper['before'] ) ? '' : $wrapper['before'];
		echo call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '' : $wrapper['after'];
		return ob_get_clean();
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function login( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Login', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function register( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Register', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function profile( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Profile', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function reset_password( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Reset_Password', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function package( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Package', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function my_invoices( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_My_Invoice', 'output' ), $atts );
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function payment( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Payment', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function payment_completed( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Payment_Completed', 'output' ), $atts );
	}

	/**
	 * Action handler for properties
	 */
	public function shortcode_property_action_handler()
	{
		global $post;
		if (is_page() && strstr($post->post_content, '[ere_my_properties')) {
			$this->my_properties_handler();
		}
		if (is_page() && strstr($post->post_content, '[ere_my_save_search')) {
			$this->my_save_search_handler();
		}
	}
	/**
	 * My properties
	 * @param $atts
	 * @return null|string
	 */
	public static function my_properties($atts)
	{
		if (!is_user_logged_in()) {
			echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
			return null;
		}
		$posts_per_page = '25';
		$post_status = $title = $property_status=$property_identity='';
		$tax_query=$meta_query=array();
		extract(shortcode_atts(array(
			'posts_per_page' => '25',
			'post_status' => ''
		), $atts));
		global $current_user;
		wp_get_current_user();
		$user_id = $current_user->ID;
		ob_start();

		// If doing an action, show conditional content if needed....
		if (!empty($_REQUEST['action'])) {
			$action = sanitize_title(wp_slash($_REQUEST['action']));
			if (has_action('ere_my_properties_content_' . $action)) {
				do_action('ere_my_properties_content_' . $action, $atts);
				return ob_get_clean();
			}
		}
		if (empty($post_status)) {
			$post_status = array('publish', 'expired', 'pending', 'hidden');
		}
		if (!empty($_REQUEST['post_status'])) {
			$post_status = sanitize_title(wp_unslash($_REQUEST['post_status']));
		}
		if (!empty($_REQUEST['property_status'])) {
			$property_status = sanitize_title(wp_slash($_REQUEST['property_status']));
			$tax_query[] = array(
				'taxonomy' => 'property-status',
				'field' => 'slug',
				'terms' => $property_status
			);
		}
		if (!empty($_REQUEST['property_identity'])) {
			$property_identity = sanitize_text_field(wp_unslash($_REQUEST['property_identity']));
			$meta_query[] = array(
				'key' => ERE_METABOX_PREFIX. 'property_identity',
				'value' => $property_identity,
				'type' => 'CHAR',
				'compare' => '=',
			);
		}

		if (!empty($_REQUEST['title'])) {
			$title = sanitize_text_field(wp_unslash($_REQUEST['title']));
		}
		$query_args=array(
			'post_type' => 'property',
			'post_status' => $post_status,
			'ignore_sticky_posts' => 1,
			'posts_per_page' => $posts_per_page,
			'offset' => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
			'orderby' => 'date',
			'order' => 'desc',
			'author' => $user_id,
			's' => $title
		);
		$meta_count = count($meta_query);
		if ($meta_count > 0) {
			$query_args['meta_query'] = array(
				'relation' => 'AND',
				$meta_query
			);
		}
		$tax_count = count($tax_query);
		if ($tax_count > 0) {
			$query_args['tax_query'] = array(
				'relation' => 'AND',
				$tax_query
			);
		}
		$args = apply_filters('ere_my_properties_query_args', $query_args);

		$properties = new WP_Query;
		echo self::$ere_message;
		ere_get_template('property/my-properties.php', array('properties' => $properties->query($args), 'max_num_pages' => $properties->max_num_pages, 'post_status' => $post_status, 'title' => $title, 'property_identity' => $property_identity,'property_status'=>$property_status,'the_query' => $properties));
		wp_reset_postdata();
		return ob_get_clean();
	}
	/**
	 * Property Handler
	 */
	public function my_properties_handler()
	{
		if (!empty($_REQUEST['action']) && !empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'ere_my_properties_actions')) {
			$ere_profile=new ERE_Profile();
			$action = isset($_REQUEST['action']) ?  sanitize_title(wp_unslash($_REQUEST['action'])) : '';
			$property_id = isset($_REQUEST['property_id']) ?  absint(wp_unslash($_REQUEST['property_id'])) : '';
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			try {
				$property = get_post($property_id);
				$ere_property = new ERE_Property();
				if (!$ere_property->user_can_edit_property($property_id)) {
					throw new Exception(esc_html__('Invalid ID', 'essential-real-estate'));
				}
				switch ($action) {
					case 'delete' :
						// Trash it
						wp_trash_post($property_id);
						// Message
						self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been deleted', 'essential-real-estate'), $property->post_title))  . '</div>';

						break;
					case 'mark_featured' :
						$prop_featured = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', true);

						if ($prop_featured == 1)
							throw new Exception(__('This position has already been filled', 'essential-real-estate'));
						$paid_submission_type = ere_get_option('paid_submission_type', 'no');
						if ($paid_submission_type == 'per_package') {
							$package_num_featured_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_featured', $user_id);

							$check_package=$ere_profile->user_package_available($user_id);

							if ($package_num_featured_listings > 0 && ($check_package!=-1)  && ($check_package!= 0)) {
								if ($package_num_featured_listings - 1 >= 0) {
									update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_featured', $package_num_featured_listings - 1);
								}
								update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
								update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured_date',current_time('mysql'));
								self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been featured', 'essential-real-estate'), $property->post_title))  . '</div>';
							} else {
								self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . wp_kses_post(sprintf(__('<strong>Warning!</strong> %s Cannot be marked as featured. Either your package does not support featured listings, or you have use all featured listing available under your plan.', 'essential-real-estate'), $property->post_title))  . '</div>';
							}
						} elseif ($paid_submission_type == 'per_listing') {
							$price_featured_listing = apply_filters('ere_price_featured_listing_for_check_mark_featured',ere_get_option('price_featured_listing',0)) ;
							if($price_featured_listing>0)
							{
								$payment_page_link = ere_get_permalink('payment');
								$return_link = add_query_arg(array('property_id' => $property_id, 'is_upgrade' => 1), $payment_page_link);
								wp_redirect($return_link);
							}
							else{
								update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
								update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured_date',current_time('mysql'));
							}
						}
						break;
					case 'allow_edit' :
						$listing_avl = get_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
						$check_package=$ere_profile->user_package_available($user_id);
						if (($listing_avl > 0 || $listing_avl == -1)&&($check_package==1)) {
							if ($listing_avl != -1) {
								update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', $listing_avl - 1);
							}
							$package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'package_key', $package_key );
							self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been allow edit', 'essential-real-estate'), $property->post_title))  . '</div>';
						} else {
							self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . wp_kses_post(__('<strong>Warning!</strong> Can not make "Allow Edit" this property', 'essential-real-estate'))  . '</div>';
						}
						break;
					case 'relist_per_package' :
						$listing_avl = get_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
						$check_package=$ere_profile->user_package_available($user_id);
						if (($listing_avl > 0 || $listing_avl == -1)&&($check_package==1)) {
							$auto_approve_request_publish = ere_get_option('auto_approve_request_publish', 0);
							if ($auto_approve_request_publish==1)
							{
								$data = array(
									'ID' => $property_id,
									'post_type' => 'property',
									'post_status' => 'publish'
								);
							}
							else{
								$data = array(
									'ID' => $property_id,
									'post_type' => 'property',
									'post_status' => 'pending'
								);
							}

							wp_update_post($data);
							update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 0);
							$package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'package_key', $package_key );
							if ($listing_avl != -1) {
								update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', $listing_avl - 1);
							}
							self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been reactivate', 'essential-real-estate'), $property->post_title))  . '</div>';
						} else {
							self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . wp_kses_post(__('<strong>Warning!</strong> Can not relist this property', 'essential-real-estate'))  . '</div>';
						}
						break;
					case 'relist_per_listing' :
						$auto_approve_request_publish = ere_get_option('auto_approve_request_publish', 0);
						if ($auto_approve_request_publish==1)
						{
							$data = array(
								'ID' => $property_id,
								'post_type' => 'property',
								'post_status' => 'publish'
							);
						}
						else{
							$data = array(
								'ID' => $property_id,
								'post_type' => 'property',
								'post_status' => 'pending'
							);
						}
						wp_update_post($data);
						$submit_title = get_the_title($property_id);
						$args = array(
							'submission_title' => $submit_title,
							'submission_url' => get_permalink($property_id)
						);
						ere_send_email(get_option('admin_email'), 'admin_mail_relist_listing', $args);
						self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been resend for approval', 'essential-real-estate'), $property->post_title) . '</div>';
						break;
					case 'payment_listing' :
						$payment_page_link = ere_get_permalink('payment');
						$return_link = add_query_arg(array('property_id' => $property_id), $payment_page_link);
						wp_redirect($return_link);
						break;
					case 'hidden' :
						$data = array(
							'ID' => $property_id,
							'post_type' => 'property',
							'post_status' => 'hidden'
						);
						wp_update_post($data);
						self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been hidden', 'essential-real-estate'), $property->post_title))  . '</div>';
						break;
					case 'show' :
						if($property->post_status=='hidden')
						{
							$data = array(
								'ID' => $property_id,
								'post_type' => 'property',
								'post_status' => 'publish'
							);
							wp_update_post($data);
							self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . wp_kses_post(sprintf(__('<strong>Success!</strong> %s has been publish', 'essential-real-estate'), $property->post_title))  . '</div>';
						}
						else{
							self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . wp_kses_post(__('<strong>Warning!</strong> Can not publish this property', 'essential-real-estate'))  . '</div>';
						}
						break;
					default :
						do_action('ere_my_properties_do_action_' . $action);
						break;
				}

				do_action('ere_my_properties_do_action', $action, $property_id);

			} catch (Exception $e) {
				self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
			}
		}
	}

	/**
	 * @param $atts
	 * @return null|string
	 */
	public static function my_save_search($atts)
	{
		if (!is_user_logged_in()) {
			echo ere_get_template_html('global/access-denied.php',array('type'=>'not_login'));
			return null;
		}
		extract(shortcode_atts(array(), $atts));
		ob_start();
		global $current_user;
		wp_get_current_user();
		$user_id = $current_user->ID;
		global $wpdb;
		$table_name         = $wpdb->prefix . 'ere_save_search';
		$results        = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE user_id = ' . $user_id, OBJECT );
		echo self::$ere_message;
		ere_get_template('property/my-save-search.php', array('save_seach' => $results));
		return ob_get_clean();
	}
	/**
	 * Saved Search Handler
	 */
	public function my_save_search_handler()
	{
		if (!empty($_REQUEST['action']) && !empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'ere_my_save_search_actions')) {
			$action = isset($_REQUEST['action']) ? sanitize_title(wp_unslash($_REQUEST['action'])) : '';
			$save_id = isset($_REQUEST['save_id']) ?  absint(wp_unslash($_REQUEST['save_id'])) : '';
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			try {
				switch ($action) {
					case 'delete' :
						global $wpdb;
						$table_name         = $wpdb->prefix . 'ere_save_search';
						$results        = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE id = ' . $save_id );
						if ( $user_id == $results->user_id ){
							$wpdb->delete( $table_name, array( 'id' => $save_id ), array( '%d' ) );
							self::$ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been deleted', 'essential-real-estate'), $results->title) . '</div>';
						}
						break;
					default :
						do_action('ere_my_save_search_do_action_' . $action);
						break;
				}

				do_action('ere_my_save_search_do_action', $action, $save_id);

			} catch (Exception $e) {
				self::$ere_message = '<div class="ere-message alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
			}
		}
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function submit_property($atts = array())
	{
		return ERE()->get_forms()->get_form('submit-property', $atts);
	}

	/**
	 * Edit property
	 * @return mixed
	 */
	public function edit_property()
	{
		return ERE()->get_forms()->get_form('edit-property');
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function my_favorites( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_My_Favorites', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function advanced_search( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Advanced_Search', 'output' ), $atts );
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function compare( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Compare', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_carousel( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Carousel', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_slider( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Slider', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_gallery( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Gallery', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_featured( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Featured', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_type( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Type', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_search( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_search_map( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Search_Map', 'output' ), $atts );
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_advanced_search( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Advanced_Search', 'output' ), $atts );
	}
	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_mini_search( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Mini_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function property_map( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Property_Map', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function agent( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Agent', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 * @return string
	 */
	public static function agency( $atts ) {
		return self::shortcode_wrapper( array( 'ERE_Shortcode_Agency', 'output' ), $atts );
	}

	/**
	 * Filter Ajax callback
	 */
	public function property_gallery_fillter_ajax()
	{
		$property_type = isset($_REQUEST['property_type']) ? str_replace('.', '', ere_clean(wp_unslash($_REQUEST['property_type']))) : '';
		$is_carousel = isset($_REQUEST['is_carousel']) ? ere_clean(wp_unslash($_REQUEST['is_carousel']))  : '';
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
		$include_heading= isset($_REQUEST['include_heading']) ?  ere_clean(wp_unslash($_REQUEST['include_heading'])) : '';
		$heading_sub_title= isset($_REQUEST['heading_sub_title']) ? ere_clean(wp_unslash($_REQUEST['heading_sub_title'])) : '';
		$heading_title= isset($_REQUEST['heading_title']) ? ere_clean(wp_unslash($_REQUEST['heading_title'])) : '';
		$heading_text_align= isset($_REQUEST['heading_text_align']) ? ere_clean(wp_unslash($_REQUEST['heading_text_align'])) : '';
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
		$columns = isset($_REQUEST['columns']) ? absint(wp_unslash($_REQUEST['columns'])) :  4;
		$image_size = isset($_REQUEST['image_size']) ? ere_clean(wp_unslash($_REQUEST['image_size'])) : '';
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
		$agent_id = isset($_REQUEST['agent_id'])? ere_clean(wp_unslash($_REQUEST['agent_id'])) : '';
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
		ERE_Compare::open_session();
        $view_as = isset($_REQUEST['view_as']) ? ere_clean(wp_unslash($_REQUEST['view_as'])) : '';
		if (!empty( $view_as ) && in_array($view_as, array('agent-list', 'agent-grid'))) {
			$_SESSION['agent_view_as'] = $view_as;
		}
	}
}
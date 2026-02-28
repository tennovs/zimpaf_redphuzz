<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property')) {
	/**
	 * Class ERE_Shortcode_Property
	 */
	class ERE_Shortcode_Property
	{
		private $ere_message = '';

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			add_shortcode('ere_my_properties', array($this, 'my_properties'));
			add_shortcode('ere_submit_property', array($this, 'submit_property'));
			add_shortcode('ere_my_favorites', array($this, 'my_favorites'));
			add_shortcode('ere_advanced_search', array($this, 'advanced_search_shortcode'));
			add_shortcode('ere_compare', array($this, 'compare_shortcode'));
			add_shortcode('ere_my_save_search', array($this, 'my_save_search'));
		}

		/**
		 * Handle actions which need to be run before the shortcode
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
		 * New property
		 * @param array $atts
		 * @return mixed
		 */
		public function submit_property($atts = array())
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
		 * Property Handler
		 */
		public function my_properties_handler()
		{
			if (!empty($_REQUEST['action']) && !empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'ere_my_properties_actions')) {
				$ere_profile=new ERE_Profile();
				$action = sanitize_title(wp_unslash($_REQUEST['action']));
				$property_id = absint(wp_unslash($_REQUEST['property_id']));
				global $current_user;
				wp_get_current_user();
				$user_id = $current_user->ID;
				try {
					$property = get_post($property_id);
					$ere_property = new ERE_Property();
					if (!$ere_property->user_can_edit_property($property_id)) {
						throw new Exception(__('Invalid ID', 'essential-real-estate'));
					}
					switch ($action) {
						case 'delete' :
							// Trash it
							wp_trash_post($property_id);
							// Message
							$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been deleted', 'essential-real-estate'), $property->post_title) . '</div>';

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
									update_post_meta($property_id, ERE_METABOX_PREFIX .'property_featured_date',current_time('mysql'));
									$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been featured', 'essential-real-estate'), $property->post_title) . '</div>';
								} else {
									$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . sprintf(__('<strong>Warning!</strong> %s Cannot be marked as featured. Either your package does not support featured listings, or you have use all featured listing available under your plan.', 'essential-real-estate'), $property->post_title) . '</div>';
								}
							} elseif ($paid_submission_type == 'per_listing') {
								$price_featured_listing = ere_get_option('price_featured_listing',0);
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
								$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been allow edit', 'essential-real-estate'), $property->post_title) . '</div>';
							} else {
								$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . __('<strong>Warning!</strong> Can not make "Allow Edit" this property', 'essential-real-estate') . '</div>';
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
								$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been reactivate', 'essential-real-estate'), $property->post_title) . '</div>';
							} else {
								$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . __('<strong>Warning!</strong> Can not relist this property', 'essential-real-estate') . '</div>';
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
							$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been resend for approval', 'essential-real-estate'), $property->post_title) . '</div>';
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
							$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been hidden', 'essential-real-estate'), $property->post_title) . '</div>';
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
								$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been publish', 'essential-real-estate'), $property->post_title) . '</div>';
							}
							else{
								$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . __('<strong>Warning!</strong> Can not publish this property', 'essential-real-estate') . '</div>';
							}
							break;
						default :
							do_action('ere_my_properties_do_action_' . $action);
							break;
					}

					do_action('ere_my_properties_do_action', $action, $property_id);

				} catch (Exception $e) {
					$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
				}
			}
		}

		/**
		 * My properties
		 * @param $atts
		 * @return null|string
		 */
		public function my_properties($atts)
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
				$action = sanitize_title(wp_unslash($_REQUEST['action']));
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
				$property_status = sanitize_title(wp_unslash($_REQUEST['property_status']));
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
			echo wp_kses_post($this->ere_message);
			ere_get_template('property/my-properties.php', array('properties' => $properties->query($args), 'max_num_pages' => $properties->max_num_pages, 'post_status' => $post_status, 'title' => $title, 'property_identity' => $property_identity,'property_status'=>$property_status,'the_query' => $properties));
			wp_reset_postdata();
			return ob_get_clean();
		}

		/**
		 * My favorites
		 * @param $atts
		 * @return null|string
		 */
		public function my_favorites($atts)
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
		/**
		 * Advanced Search shortcode
		 */
		/**
		 * My Saved Searches
		 * @param $atts
		 * @return null|string
		 */
		public function my_save_search($atts)
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
			echo wp_kses_post($this->ere_message);
			ere_get_template('property/my-save-search.php', array('save_seach' => $results));
			return ob_get_clean();
		}
		/**
		 * Saved Search Handler
		 */
		public function my_save_search_handler()
		{
			if (!empty($_REQUEST['action']) && !empty($_REQUEST['_wpnonce']) && wp_verify_nonce(ere_clean(wp_unslash($_REQUEST['_wpnonce'])), 'ere_my_save_search_actions')) {
				$action = sanitize_title(wp_unslash($_REQUEST['action']));
				$save_id = absint(wp_unslash($_REQUEST['save_id']));
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
								$this->ere_message = '<div class="ere-message alert alert-success" role="alert">' . sprintf(__('<strong>Success!</strong> %s has been deleted', 'essential-real-estate'), $results->title) . '</div>';
							}
							break;
						default :
							do_action('ere_my_save_search_do_action_' . $action);
							break;
					}

					do_action('ere_my_save_search_do_action', $action, $save_id);

				} catch (Exception $e) {
					$this->ere_message = '<div class="ere-message alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
				}
			}
		}

		public function advanced_search_shortcode()
		{
			return ere_get_template_html('property/advanced-search.php');
		}
		/**
		 * Compare shortcode
		 */
		public function compare_shortcode()
		{
			return ere_get_template_html('property/compare.php');
		}
	}
}
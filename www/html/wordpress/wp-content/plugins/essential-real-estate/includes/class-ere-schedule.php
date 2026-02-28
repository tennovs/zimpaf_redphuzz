<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Schedule')) {
	/**
	 * Class ERE_Schedule
	 */
	class ERE_Schedule
	{

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

		/**
		 * Check expire listing
		 */
		public function per_listing_check_expire()
		{

			$paid_submission_type = ere_get_option('paid_submission_type', 'no');
			if ($paid_submission_type !== 'per_listing') return;
			$per_listing_expire_days = ere_get_option('per_listing_expire_days',0);
			$number_expire_days = intval(ere_get_option('number_expire_days',30));
			if ($number_expire_days > 0 && $per_listing_expire_days == 1) {
				$args = array(
					'post_type' => 'property',
					'post_status' => 'publish'
				);
				$data = new WP_Query($args);
				while ($data->have_posts()): $data->the_post();
					$post_id = get_the_ID();
					$property_date = strtotime(get_the_date("Y-m-d H:i:s", $post_id));
					$expired_date = $property_date + $number_expire_days * 24 * 60 * 60;
					$today = time();
					$user_id = get_post_field('post_author', $post_id);
					$user = new WP_User($user_id);
					$user_role = $user->roles[0];
					if ($user_role != 'administrator') {
						if ($expired_date < $today) {
							$args = array(
								'ID' => $post_id,
								'post_type' => 'property',
								'post_status' => 'expired'
							);
							wp_update_post($args);
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_featured', 0);
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'payment_status', 'not_paid');
							$user_email = $user->user_email;
							$args = array(
								'listing_title' => get_the_title($post_id),
								'listing_url' => get_permalink($post_id)
							);
							ere_send_email($user_email, 'mail_expired_listing', $args);
						}
					}
				endwhile;
				wp_reset_postdata();
			}
		}

		public function search_query_results($search_query){
			$links   = '';
			$search_query['posts_per_page'] = -1;
			$data = new WP_Query($search_query);
			if($data->have_posts()){
				while ($data->have_posts()): $data->the_post();
					$property_id = get_the_ID();
					$links .= get_the_permalink($property_id)."\r\n";
				endwhile;
				wp_reset_postdata();
			}
			return $links;
		}

		public function saved_search_check_result() {
            $today = getdate(strtotime("-1 days"));
			$date_query = array(
				array(
					'year' => $today['year'],
					'month' => $today['mon'],
					'day' => $today['mday'],
				)
			);
			$args = array(
				'post_type' => 'property',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'date_query' =>  $date_query
			);
			$new_properties = new WP_Query($args);

			if ($new_properties->have_posts()) {
				global $wpdb;
				$table_name         = $wpdb->prefix . 'ere_save_search';
				$results        = $wpdb->get_results( 'SELECT * FROM ' . $table_name, OBJECT );
				if ( sizeof ( $results ) !== 0 ){
					foreach ( $results as $result ){
						$search_query = unserialize( base64_decode( $result->query ) );
						$search_query['date_query'] =$date_query;

						$user_email = $result->email;
						$links =$this->search_query_results($search_query);
						if ($links != '') :
							$args = array(
								'listings' => $links
							);
							ere_send_email($user_email, 'mail_matching_saved_search', $args);
						endif;
					}
				}
				wp_reset_postdata();
			}
		}
		/**
		 * Scheduled hook
		 */
		public function scheduled_hook()
		{
			$paid_submission_type = ere_get_option('paid_submission_type', 'no');
			if ($paid_submission_type == 'per_listing') {
				$per_listing_expire_days = ere_get_option('per_listing_expire_days',0);
				if ($per_listing_expire_days == 1) {
					if (!wp_next_scheduled('ere_per_listing_check_expire')) {
						//twicedaily
						wp_schedule_event(time(), 'twicedaily', 'ere_per_listing_check_expire');
					}
				}
			}
			$enable_saved_search = ere_get_option('enable_saved_search', 1);
			if($enable_saved_search==1)
			{
				if (!wp_next_scheduled('ere_saved_search_check_result')) {
					wp_schedule_event(time(), 'daily', 'ere_saved_search_check_result');
				}
			}
		}

		public static function clear_scheduled_hook()
		{
			wp_clear_scheduled_hook('ere_per_listing_check_expire');
			wp_clear_scheduled_hook('ere_saved_search_check_result');
			do_action('ere_clear_scheduled_hook');
		}
	}
}
<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 14/11/2016
 * Time: 2:54 CH
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Package')) {
    /**
     * Class ERE_Package
     */
    class ERE_Package
    {
        /**
         * get_time_unit
         * @param $time_unit
         * @return null|string
         */
        public static function get_time_unit($time_unit)
        {
            if ($time_unit == 'Day') {
                return esc_html__('day', 'essential-real-estate');
            } else if ($time_unit == 'Days') {
                return esc_html__('days', 'essential-real-estate');
            } else if ($time_unit == 'Week') {
                return esc_html__('week', 'essential-real-estate');
            } else if ($time_unit == 'Weeks') {
                return esc_html__('weeks', 'essential-real-estate');
            } else if ($time_unit == 'Month') {
                return esc_html__('month', 'essential-real-estate');
            } else if ($time_unit == 'Months') {
                return esc_html__('months', 'essential-real-estate');
            } else if ($time_unit == 'Year') {
                return esc_html__('year', 'essential-real-estate');
            } else if ($time_unit == 'Years') {
                return esc_html__('years', 'essential-real-estate');
            }
            return null;
        }

        /**
         * Insert agent package
         * @param $user_id
         * @param $package_id
         */
        public function insert_user_package($user_id,$package_id)
        {
            $args = array(
                'post_type' => 'user_package',
                'posts_per_page' => -1,
                'meta_query'=>array(
                    array(
                        'key' => ERE_METABOX_PREFIX . 'package_user_id',
                        'value' => $user_id,
                        'compare' => '='
                    )
                ),
            );
            $user_package = new WP_Query( $args );
            wp_reset_postdata();
            $existed_post=$user_package->found_posts;
            if ($existed_post<1) {
                $args = array(
                    'post_title' => 'UserID ' . $user_id,
                    'post_type' => 'user_package',
                    'post_status' => 'publish'
                );
                $post_id = wp_insert_post($args);
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'package_user_id', $user_id);
            }
            $package_number_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
            $package_number_featured = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
            $package_unlimited_listing = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_listing', true);

            if ($package_unlimited_listing == 1) {
                $package_number_listings = -1;
            }
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', $package_number_listings);
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_featured', $package_number_featured);
            $time = time();
            $date = date('Y-m-d H:i:s', $time);
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_activate_date', $date);
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_id', $package_id);
            $package_key=uniqid();
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_key', $package_key);
            $headers = apply_filters( "ere_contact_mail_header", array('Content-Type: text/html; charset=UTF-8'));
            $message = esc_html__('Hi there,', 'essential-real-estate') . "\r\n\r\n";
            $message .= sprintf(esc_html__("Your new package on  %s is activated! You should go check it out.", 'essential-real-estate'), get_option('blogname')) . "\r\n\r\n";

            $user = get_user_by('id', $user_id);
            $user_email = $user->user_email;
            wp_mail($user_email,
                sprintf(esc_html__('[%s] Package Activated', 'essential-real-estate'), get_option('blogname')),
                $message,
                $headers);
        }

        public function get_expired_date($package_id, $package_user_id)
        {
            $package_unlimited_time = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_time', true);
            if($package_unlimited_time==1)
            {
                $expired_date=esc_html__('Never Expires','essential-real-estate');
            }
            else
            {
                $expired_date =$this->get_expired_time($package_id,$package_user_id);
                $expired_date = date_i18n(get_option('date_format'), $expired_date);
            }
            return $expired_date;
        }

        public function get_expired_time($package_id, $package_user_id)
        {
            $package_time_unit = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_time_unit', true);
            $package_period = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_period', true);
            $package_activate_date = strtotime(get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_activate_date', true));
            $seconds = 0;
            switch ($package_time_unit) {
                case 'Day':
                    $seconds = 60 * 60 * 24;
                    break;
                case 'Week':
                    $seconds = 60 * 60 * 24 * 7;
                    break;
                case 'Month':
                    $seconds = 60 * 60 * 24 * 30;
                    break;
                case 'Year':
                    $seconds = 60 * 60 * 24 * 365;
                    break;
            }
            $expired_time = $package_activate_date + ($seconds * $package_period);
            return $expired_time;
        }
    }
}
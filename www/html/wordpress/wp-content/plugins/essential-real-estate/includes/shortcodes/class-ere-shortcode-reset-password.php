<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('ERE_Shortcode_Reset_Password')) {
    class ERE_Shortcode_Reset_Password
    {
        /**
         * Output the cart shortcode.
         *
         * @param array $atts
         */
        public static function output($atts)
        {
            return ere_get_template_html('account/reset-password.php', array('atts' => $atts));
        }
    }
}
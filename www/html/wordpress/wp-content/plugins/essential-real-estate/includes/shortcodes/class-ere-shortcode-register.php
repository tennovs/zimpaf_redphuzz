<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('ERE_Shortcode_Register')) {
    class ERE_Shortcode_Register
    {
        /**
         * Output the cart shortcode.
         *
         * @param array $atts
         */
        public static function output($atts)
        {
            return ere_get_template_html('account/register.php', array('atts' => $atts));
        }
    }
}
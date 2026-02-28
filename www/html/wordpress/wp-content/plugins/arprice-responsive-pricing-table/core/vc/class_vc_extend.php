<?php

if (!defined('WPINC')) {
    die;
}

class ARPrice_Lite_VCExtendArp {

    protected static $instance = null;

    public function __construct() {
        add_action('init', array($this, 'ARPLiteintegrateWithVC'));
        add_action('init', array($this, 'callmyfunction'));
    }


    public static function arp_get_instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

   
    public function ARPLiteintegrateWithVC() {
        if (function_exists('vc_map')) {
            vc_map(array(
                'name' => esc_html__('ARPrice Lite', 'arprice-responsive-pricing-table'),
                'description' => esc_html__('Responsive WordPress Pricing Table / Team Showcase Plugin', 'arprice-responsive-pricing-table'),
                'base' => 'ARPLite',
                'category' => esc_html__('Content', 'arprice-responsive-pricing-table'),
                'class' => '',
                'controls' => 'full',
                'admin_enqueue_css' => array(ARPLITE_PRICINGTABLE_URL . '/core/vc/arpricelite_vc.css'),
                'front_enqueue_css' => ARPLITE_PRICINGTABLE_URL . '/core/vc/arpricelite_vc.css',
                'icon' => 'arpricelite_vc_icon',
                'params' => array(
                    array(
                        "type" => "ARPrice_lite_Shortode",
                        'heading' => false,
                        'param_name' => 'id',
                        'value' => false,
                        'description' => '&nbsp;',
                        'admin_label' => true
                    )
                )
            ));
        }
    }

    public function callmyfunction() {
        if (function_exists('vc_add_shortcode_param')) {
            vc_add_shortcode_param('ARPrice_lite_Shortode', array($this, 'arpricelite_param_html'), ARPLITE_PRICINGTABLE_URL . '/core/vc/arpricelite_vc.js');
        }
    }

    public function arpricelite_param_html($settings, $value) {
		$html = '';
        if ($settings) {
            
            $html .= '<input type="hidden" name="' . $settings['param_name'] . '" value="' . esc_html( $value ) . '" class="wpb_vc_param_value" />';


            global $wpdb;
            $arp_short_code_data = array();
            $templates = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE status = %s and is_template != %d  ORDER BY ID ASC", 'published', 1));

            if (!empty($templates)) {
                foreach ($templates as $key => $template) {
                    $active_class = "";
                    $active_class = ($template->ID == $value) ? ' arp_active ' : '';
                    $html .= '<div class="arp_vd_img_list" title="' . esc_attr($template->table_name) . ' ">';
                    if ($template->is_template == '1') {
                        $html .= '<img width="200" height="90"  alt="' . esc_attr($template->table_name) . '" id="' . $template->ID . '"  class="' . $active_class . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field"   src="' . ARPLITE_PRICINGTABLE_IMAGES_URL . '/arplitetemplate_' . $template->ID . '.png">';
                    } else {
                        $html .= '<img width="200" height="90" alt="' . esc_attr($template->table_name) . '" id="' . $template->ID . '"  class="' . $active_class . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field"   src="' . ARPLITE_PRICINGTABLE_UPLOAD_URL . '/template_images/arplitetemplate_' . $template->ID . '.png">';
                    }
                    $html .= '</div>';
                }
            }
        }
        if (!empty($templates)) {
            return '<div class="arp_param_block"><div class="arp_vc_title">' . esc_html__('Please Select Your Pricing Table', 'arprice-responsive-pricing-table') . '</div>' . $html . '</div>';
        } else{
            return '<div class="arp_param_block"><div class="arp_vc_title">' . esc_html__('Pricing Table Not Found', 'arprice-responsive-pricing-table') . '</div></div>';
        }           
    }

}

?>
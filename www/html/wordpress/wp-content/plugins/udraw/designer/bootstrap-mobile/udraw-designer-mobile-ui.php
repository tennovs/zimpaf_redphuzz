<?php

if (!defined('UDRAW_MOBILE_UI_URL')) {
    define('UDRAW_MOBILE_UI_URL', plugins_url('/', __FILE__));
}

if (!defined('UDRAW_MOBILE_UI_DIR')) {
    define('UDRAW_MOBILE_UI_DIR', dirname(__FILE__));
}

if (!defined('UDRAW_MOBILE_UI_IMG_URL')) {
    define('UDRAW_MOBILE_UI_IMG_URL', UDRAW_MOBILE_UI_URL . 'skin/img/');
}

if (!defined('UDRAW_MOBILE_UI_IMG_DIR')) {
    define('UDRAW_MOBILE_UI_IMG_DIR', UDRAW_MOBILE_UI_DIR . 'skin/img/');
}

if (!class_exists('uDrawMobileUI')) {
    class uDrawMobileUI {
        function __contsruct() { }
        
        // ------------------------------------------------------------- //
        // -------------------------- Init ----------------------------- //
        // ------------------------------------------------------------- //        
        public function init() {
            add_filter('udraw_designer_register_skin', array(&$this, 'udraw_designer_register_skin'), 10, 1);
            add_filter('udraw_designer_ui_override', array(&$this,'udraw_designer_ui_override'), 10, 9);
        }
        
        public function udraw_designer_register_skin($skins) {
            $skins['mobile'] = "Mobile";
                        
            return $skins;
        }
        
        public function udraw_designer_ui_override($override, $template_id, $current_skin, $displayOptionsFirst,$allowCustomerDownloadDesign,$isPriceMatrix,$templateCount,$isTemplatelessProduct,$isuDrawApparelProduct) {
            if (strtolower($current_skin) === 'mobile') {
                $this->register_designer();
                require_once("skin/designer.php");
                return true; // We will override the default UI
            }
            return false; // We wont override the default UI
        }
        
        public function register_designer(){
            wp_enqueue_style('udraw_mobile_designer_css' , plugins_url('skin/css/designer.css', __FILE__));
            wp_enqueue_script('udraw_mobile_designer_js', plugins_url('skin/js/designer.js', __FILE__));
        }
        
    }
}


// Init the plugin.
$uDrawMobileUI = new uDrawMobileUI();
$uDrawMobileUI->init();

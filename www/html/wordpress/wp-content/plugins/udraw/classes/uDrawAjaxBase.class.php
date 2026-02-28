<?php

if (!class_exists('uDrawAjaxBase')) {
    
    abstract class uDrawAjaxBase {  

        public $uDraw;
        public $uDrawTemplatesTable;
        public $uDrawDesignHandler;
        public $uDrawSettings;
        
        public $udraw_templates_table;
        public $udraw_templates_category_table;
        public $udraw_customer_designs_table;
        
        public $udraw_clipart_table;
        public $udraw_clipart_category_table;
        
        function __construct() {
            global $wpdb;
            
            $this->uDraw = new uDraw();
            $this->uDrawDesignHandler = new uDrawDesignHandler();
            $this->uDrawSettings = new uDrawSettings();
            $udrawSettings = $this->uDrawSettings->get_settings();
            $this->udraw_templates_table = $udrawSettings['udraw_db_udraw_templates'];
            $this->udraw_templates_category_table = $udrawSettings['udraw_db_udraw_templates_category'];
            $this->udraw_customer_designs_table = $udrawSettings['udraw_db_udraw_customer_designs'];
            
            $this->udraw_clipart_table = $udrawSettings['udraw_db_udraw_clipart'];
            $this->udraw_clipart_category_table = $udrawSettings['udraw_db_udraw_clipart_category'];
        }
        
        public abstract function init_actions();
        
        /**
         * Based on the request coming in, sends a response back.  
         * If it's an AJAX request, we will JSON encode the response and call wp_die() to close the session.
         * Otherwise if it's a direct function call, it will return the response passed to it.
         * 
         * @param mixed $response Response to send back.
         * @param mixed $isAJAXRequest (true|false) If it's an AJAX Request.
         * @return mixed
         */        
        function sendResponse($response) {
            if ($this->isAJAXRequest()) {
                echo json_encode($response);
                wp_die();
            } else {
                return $response;
            }
        }
        
        function getBaseURL() {
            // Determine Protocol
            if (isset($_SERVER['HTTPS']) &&  ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                $protocol = 'https://';
            } else {
                $protocol = 'http://';
            }
            
            return $protocol . $_SERVER['HTTP_HOST'];            
        }
        
        function downloadFile($url, $path) {
            //Remove any spaces that may be present
            $url = preg_replace('/\s/', '%20', $url);
            
            $newfname = $path;
            $file = fopen ($url, "rb");
            if ($file) {
                $newf = fopen ($newfname, "wb");

                if ($newf)
                    while(!feof($file)) {
                        fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
                    }
            }

            if ($file) {
                fclose($file);
            }

            if ($newf) {
                fclose($newf);
            }
        }
        
        function startsWith($haystack, $needle) {
            // search backwards starting from haystack length characters from the end
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
        }
        
        function endsWith($haystack, $needle) {
            // search forward starting from end minus needle length characters
            return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
        }
        
        /**
         * Returns true if request is an AJAX call, otherwise returns false.
         * 
         * @return bool Returns true if request is an AJAX Request.
         */        
        public function isAJAXRequest() {
            if (isset($_REQUEST['action'])) {
                return true;
            } else {
                return false;
            }
        }
        
        
    }
}

?>
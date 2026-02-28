<?php
if (!class_exists('uDrawTextTemplatesHandler')) {
    class uDrawTextTemplatesHandler extends uDrawAjaxBase {
        
        function __construct() { }
        
        function init_actions() {
            add_action( 'wp_ajax_udraw_text_templates_save',            array(&$this,'save') );
            add_action( 'wp_ajax_udraw_text_templates_save_db',         array(&$this,'save_db') );
            add_action( 'wp_ajax_udraw_text_templates_load',            array(&$this,'load') );
            add_action( 'wp_ajax_udraw_text_templates_load_fonts',      array(&$this,'load_fonts') );
            add_action( 'wp_ajax_udraw_text_templates_load_fonts_css',  array(&$this,'load_fonts_css') );
            add_action( 'wp_ajax_udraw_text_templates_delete',          array(&$this,'delete') );
            add_action( 'wp_ajax_udraw_text_templates_update_tags',     array(&$this,'update_tags') );
            add_action( 'wp_ajax_udraw_assign_text_templates',          array(&$this,'assign_text_templates') );
            add_action( 'wp_ajax_udraw_add_text_templates_category',    array(&$this,'add_text_templates_category') );
            add_action( 'wp_ajax_udraw_remove_text_templates_category', array(&$this,'remove_text_templates_category') );
            add_action( 'wp_ajax_udraw_update_text_templates_category', array(&$this,'update_text_templates_category') );
        }
        
        public function save () {
            if (!isset($_REQUEST['output_path'])) {
                throw new Exception('No output path.');
            }
            $output_path = $_REQUEST['output_path'];
            $output_dir = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $output_path);
            if (!isset($_REQUEST['data'])) {
                throw new Exception('No data was sent.');
            }
            $data = json_encode($_REQUEST['data']);
            if (strlen($_REQUEST['unique_id'])) {
                $name = $_REQUEST['unique_id'];
            } else {
                $name = $this->make_uniqid_folder_id($output_dir);
            }
            file_put_contents("$output_dir/$name/$name.json", $data);
            $preview_path = UDRAW_PLUGIN_URL . '/assets/includes/no_image.jpg';
            if (isset($_REQUEST['preview'])) {
                $image_contents = $this->convert_base64_image($_REQUEST['preview']);
                file_put_contents("$output_dir/$name/$name.png", $image_contents);
                $preview_path = "$output_path/$name/$name.png";
            }
            $return_object  = array(
                'preview_path'  => $preview_path,
                'data_path'     => "$output_path/$name/$name.json",
                'unique_id'     => $name
            );
            $this->sendResponse($return_object);
        }
        
        public function save_db () {
            global $wpdb;
            if (!isset($_REQUEST['preview_path'])) {
                throw new Exception('No preview was sent.');
            }
            $preview_path = $_REQUEST['preview_path'];
            if (!isset($_REQUEST['data_path'])) {
                throw new Exception('No data was sent.');
            }
            if (!isset($_REQUEST['unique_id'])) {
                throw new Exception('No unique ID was sent.');
            }
            $uniqid = $_REQUEST['unique_id'];
            $data_path = $_REQUEST['data_path'];
            $template_name = 'Text template ' . $uniqid;
            if (isset($_REQUEST['template_name'])) {
                $template_name = htmlentities($_REQUEST['template_name']);
            }
            $table = $wpdb->prefix . 'udraw_text_templates';
            $dt = new DateTime();
            $date = $dt->format('Y-m-d H:i:s');
            if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0 && isset($uniqid)) {
                $id = $_REQUEST['id'];
                $result = $wpdb->update($table, array(
                    'name'          => $template_name,
                    'json'          => $data_path,
                    'preview'       => $preview_path,
                    'modify_date'   => $date
                ), array(
                    'ID'            => $id,
                    'public_key'    => $uniqid
                ));
            } else {
                $result = $wpdb->insert($table, array(
                    'name'          => $template_name,
                    'json'          => $data_path,
                    'preview'       => $preview_path,
                    'create_date'   => $date,
                    'create_user'   => wp_get_current_user()->user_login,
                    'public_key'    => $uniqid
                ));
            }
            return $this->sendResponse($result);
        }
        
        public function load () {
            global $wpdb;
            if (!isset($_REQUEST['template_id'])) {
                throw new Exception('No ID passed');
            }
            if (!isset($_REQUEST['unique_id'])) {
                throw new Exception('No public key passed');
            }
            $template_id = $_REQUEST['template_id'];
            $unique_id = $_REQUEST['unique_id'];
            $table = $wpdb->prefix . 'udraw_text_templates';
            $json_path = $wpdb->get_var("SELECT json FROM $table WHERE ID=$template_id AND public_key='$unique_id'");
            $json_dir = $output_dir = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $json_path);
            $json = file_get_contents($json_dir);
            $this->sendResponse(json_decode($json));
        }
        
        function load_fonts() {
            $this->sendResponse($this->__process_fonts());
        }
        
        function load_fonts_css() {
            $localFonts = $this->__process_fonts();
            if (gettype($localFonts) == 'string') { return $this->sendResponse(""); }
            
            $css = "";
            foreach ($localFonts as $fonts) {
                $css .= "@font-face { " . PHP_EOL;
                $css .= "font-family: '". $fonts->name ."';". PHP_EOL;
                $css .= "font-style: normal;". PHP_EOL;
                $css .= "font-weight: 400;". PHP_EOL;
                $css .= "src: url('". $fonts->path ."') format('". $fonts->fontType ."');". PHP_EOL;
                $css .= "}". PHP_EOL;
            }
            
            header('Content-Type: text/css');
            echo $css;
            wp_die();
        }
        
        function delete () {
            if (!isset($_REQUEST['template_id']) || !isset($_REQUEST['key'])) {
                throw new Exception("No template ID or key was sent.");
            }
            
            $template_id    = $_REQUEST['template_id'];
            $key            = $_REQUEST['key'];
            
            global $wpdb;
            $table = $wpdb->prefix . 'udraw_text_templates';
            $result = $wpdb->delete(
                        $table, 
                        array(
                            'ID'        => $template_id,
                            'public_key'=> $key
                        )
                    );
            
            if (!$result) {
                throw new Exception("Template not found");
            }
                
            $this->sendResponse($result);
        }
        
        function update_tags () {
            if (!isset($_REQUEST['template_id'])) {
                throw new Exception("No template ID was supplied.");
            }
            $template_id    = $_REQUEST['template_id'];
            $tags           = $_REQUEST['tags'];
            
            global $wpdb;
            $table = $wpdb->prefix . 'udraw_text_templates';
            $wpdb->update($table, array(
                'tags' => $tags
            ), array(
                'ID' => $template_id
            ));     
            $response = $wpdb->get_var("SELECT tags FROM $table WHERE ID=$template_id");
            
            if ($response) {
                return $this->sendResponse($response);
            } else {
                throw new Exception("No result for template ID $template_id");
            }
        }

        function add_text_templates_category($category_name = '', $parent_id = 0) {
            global $wpdb;
            $response = false;
            $text_templates_category_table = $wpdb->prefix . 'udraw_text_templates_category';

            if (isset($_REQUEST['category_name']) && isset($_REQUEST['parent_id'])) {
                $category_name = $_REQUEST['category_name'];
                $parent_id = $_REQUEST['parent_id'];
            }
            if (strlen($category_name) > 0) {
                $response = $wpdb->insert($text_templates_category_table, array('category_name'=>$category_name, 'parent_id'=>$parent_id));
                return $this->sendResponse($response);
            }
        }

        function remove_text_templates_category($category = 0) {
            global $wpdb;
            $text_templates_table = $wpdb->prefix . 'udraw_text_templates';
            $text_templates_category_table = $wpdb->prefix . 'udraw_text_templates_category';
            if (isset($_REQUEST['category'])) {
                $category = $_REQUEST['category'];
            }
            $wpdb->update($text_templates_table, array('category' => ''), array('category' => $category));
            $response = $wpdb->delete($text_templates_category_table, array('ID'=>$category));
            return $this->sendResponse($response);
        }

        function update_text_templates_category ($category_id = 0, $category_name = '', $parent_id = 0) {
            global $wpdb;
            $text_templates_category_table = $wpdb->prefix . 'udraw_text_templates_category';
            if (isset($_REQUEST['category_id']) && isset($_REQUEST['category_name']) && isset($_REQUEST['parent_id'])) {
                $category_id = $_REQUEST['category_id'];
                $category_name = $_REQUEST['category_name'];
                $parent_id = $_REQUEST['parent_id'];
            }
            if (strlen($category_name) > 0) {
                $response = $wpdb->update($text_templates_category_table, array('category_name'=>$category_name, 'parent_id'=>$parent_id), array('ID'=>$category_id));
                return $this->sendResponse($response);
            }
        }

        function assign_text_templates ($category = 0, $publicKey = '') {
            global $wpdb;
            $text_templates_table = $wpdb->prefix . 'udraw_text_templates';
            if (current_user_can('edit_udraw_templates')) {
                if (isset($_REQUEST['public_key']) && isset($_REQUEST['category'])){
                    $publicKey = $_REQUEST['public_key'];
                    $category = $_REQUEST["category"];
                }
                if (strlen($publicKey) > 0) {
                    $response = $wpdb->update($text_templates_table, array('category' => $category), array('public_key' => $publicKey));
                    return $this->sendResponse($response);
                }
            }
        }
        
        private function __process_fonts() {
            $uDraw = new uDraw();
            $localFontPath = $_REQUEST['local_fonts_path'];
            if (strlen($localFontPath) == 0) { return ""; }
            $fontDir = $uDraw->get_physical_path($localFontPath);
            
            //$fontFiles = glob($fontDir . "/*.woff");
            $fontFiles = glob($fontDir . "/*.{woff,ttf}", GLOB_BRACE);
            $fontList = array();
            foreach($fontFiles as $fonts) {
                $fontType = '';
                if ($this->endsWith(strtolower($fonts), "woff")) { 
                    $fontType = 'woff'; 
                    $_name = basename($fonts, '.woff');
                } else if ($this->endsWith(strtolower($fonts), "ttf")) { 
                    $fontType = 'truetype'; 
                    $_name = basename($fonts, '.ttf');
                }
                $_path = $localFontPath . basename($fonts);
                
                $uDrawHandlerLocalFonts = new uDrawHandler_LocalFonts($_name, $_path, $fonts, filesize($fonts), $fontType);
                array_push($fontList, $uDrawHandlerLocalFonts);
            }
            return $fontList;
        }
        
        private function convert_base64_image ($image_string = '') {
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_string));
            return $image;
        }
        
        private function make_uniqid_folder_id ($base_dir) {
            $rand_id = uniqid('udraw_text_template_');
            if (!file_exists($base_dir . $rand_id)) {
                wp_mkdir_p($base_dir . $rand_id);
                return $rand_id;
            } else {
                return $this->make_uniqid_folder_id($base_dir);
            }
        }
    }
}
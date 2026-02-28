<?php
if (!class_exists('SVGDesigner_handler')) {
    class SVGDesigner_handler extends uDrawAjaxBase {
        public function __construct() { }
        public function init_actions(){
            add_action('wp_ajax_udraw_SVGDesigner_save_svg', array(&$this, 'save_svg'));
            add_action('wp_ajax_udraw_SVGDesigner_read_svg', array(&$this, 'read_svg'));
            add_action('wp_ajax_udraw_SVGDesigner_upload_image', array(&$this, 'upload_image'));
            add_action('wp_ajax_udraw_SVGDesigner_uploaded_images', array(&$this, 'uploaded_images'));
            add_action('wp_ajax_udraw_SVGDesigner_download_image', array(&$this, 'download_image'));
            add_action('wp_ajax_udraw_SVGDesigner_local_fonts', array(&$this, 'process_fonts'));
            add_action('wp_ajax_udraw_SVGDesigner_export_image', array(&$this, 'export_image'));
            add_action('wp_ajax_udraw_SVGDesigner_authenticate_instagram', array(&$this, 'authenticate_instagram'));
            add_action('wp_ajax_udraw_SVGDesigner_retrieve_instagram', array(&$this, 'retrieve_instagram'));
            add_action('wp_ajax_udraw_SVGDesigner_check_license_key', array(&$this, 'check_license_key'));
            add_action('wp_ajax_udraw_SVGDesigner_get_templates_count', array(&$this, 'get_templates_count'));
            add_action('wp_ajax_udraw_SVGDesigner_save_page', array(&$this, 'save_page'));
            add_action('wp_ajax_udraw_SVGDesigner_create_page', array(&$this, 'create_page'));
            add_action('wp_ajax_udraw_convert_url_to_base64', array(&$this, 'convert_url_to_base64'));
            
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_save_svg', array(&$this, 'save_svg'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_read_svg', array(&$this, 'read_svg'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_upload_image', array(&$this, 'upload_image'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_uploaded_images', array(&$this, 'uploaded_images'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_download_image', array(&$this, 'download_image'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_local_fonts', array(&$this, 'process_fonts'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_export_image', array(&$this, 'export_image'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_authenticate_instagram', array(&$this, 'authenticate_instagram'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_retrieve_instagram', array(&$this, 'retrieve_instagram'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_check_license_key', array(&$this, 'check_license_key'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_get_templates_count', array(&$this, 'get_templates_count'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_save_page', array(&$this, 'save_page'));
            add_action('wp_ajax_nopriv_udraw_SVGDesigner_create_page', array(&$this, 'create_page'));
            add_action('wp_ajax_nopriv_udraw_convert_url_to_base64', array(&$this, 'convert_url_to_base64'));
        }
        public function save_svg ($output_path = '', $svg_string = '') {
            $uDraw_SVG = new uDraw_SVG();
            $dt = new DateTime();
            $time_stamp = $dt->getTimestamp();
            if (isset($_REQUEST['output_path'])) {
                $output_path = $_REQUEST['output_path'];
            } else {
                $this->sendResponse(false);
            }
            if (isset($_REQUEST['data'])) {
                $svg_string = $_REQUEST['data'];
            } else {
                $this->sendResponse(false);
            }
            if (isset($_REQUEST['preview_data'])) {
                $image_data = $_REQUEST['preview_data'];
            }
            
            $document_name = $time_stamp . '.svg';
            $output_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $output_path);
            
            //Preview Image
            $image_name = $time_stamp . '.png';
            if (strlen($image_data) > 0) {
                $image_contents = $uDraw_SVG->convert_base64_image($image_data);
                file_put_contents($output_dir . '/' . $image_name, $image_contents);
            }
            
            $results = file_put_contents($output_dir . '/' . $document_name, stripslashes($svg_string));
            if (!$results) {
                $this->sendResponse(array('success' => false, 'message' => 'Save file failed.'));
            } else {
                $return_object = array(
                    'success' => true,
                    'output_path' => $output_path,
                    'document_name' => $document_name,
                    'preview_image' => $image_name,
                    'design_data' => $svg_string
                );
                $this->sendResponse($return_object);
            }
        }
        public function read_svg ($file_path = '') {
            if (isset($_REQUEST['design_file']) && strlen($_REQUEST['design_file'])) {
                $svg_file = $_REQUEST['design_file'];
            } else {
                $this->sendResponse(false);
            }
            $replace = wp_make_link_relative(UDRAW_STORAGE_URL);
            if (strpos($svg_file, UDRAW_STORAGE_URL) !== false) {
                $replace = UDRAW_STORAGE_URL;
            }
            $svg_file_dir = str_replace($replace, UDRAW_STORAGE_DIR, $svg_file);
            $svg_string = file_get_contents($svg_file_dir);
            $this->sendResponse($svg_string);
        }
        public function upload_image($upload_path = '') {
            if (isset($_REQUEST['upload_path']) && strlen($_REQUEST['upload_path']) > 0) {
                $upload_path = $_REQUEST['upload_path'];
            } else {
                $this->sendResponse(false);
            }
            $upload_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $upload_path);
            if (!file_exists($upload_dir)) {
                if (!wp_mkdir_p($upload_dir)) {
                    $this->sendResponse(false);
                }
            }
            // Check file exstension
            $file_name = pathinfo($_FILES['files']['name'][0], PATHINFO_FILENAME);
            $file_ext = strtolower(pathinfo($_FILES['files']['name'][0], PATHINFO_EXTENSION));
            // New Filename
            $new_file = rand(1, 32) .'_'. str_replace('_','', $file_name) . '.' . $file_ext;
            if ($file_ext == 'pdf' || $file_ext == 'eps') {
                if (strlen(uDraw::get_udraw_activation_key()) > 0) {
                    // Save EPS/PDF locally to the sysetm.
                    $uDrawUpload = new uDrawUpload();
                    $base_url = $this->getBaseURL();
                    $uploaded_files = $uDrawUpload->handle_upload($_FILES['files'], $upload_dir, $base_url . $upload_path, array('pdf' => 'application/pdf', 'eps' => 'application/postscript') );
                    if ( !key_exists('error', $uploaded_files[0]) ) {
                        
                        $new_file_array = array();
                        $uDrawUtil = new uDrawUtil();
                            
                        if ($file_ext == "eps") {
                            $data = array(
                                'epsDocument' => str_replace('localhost', 'udraw-wordpress.ngrok.io', $uploaded_files[0]['url']),
                                'key' => uDraw::get_udraw_activation_key()
                            );    
                        } else {
                            $data = array(
                                'pdfDocument' => str_replace('localhost', 'udraw-wordpress.ngrok.io', $uploaded_files[0]['url']),
                                'key' => uDraw::get_udraw_activation_key()
                            );
                        }

                        $endpoint_action = ($file_ext == "eps") ? "EPS2PNG" : "PDF2SVG";
                        $output_file_ext = ($file_ext == "eps") ? ".png" : ".svg";

                        $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/' . $endpoint_action, http_build_query($data)));

                        if ($udraw_convert_response->isSuccess) {
                            for ($i = 0; $i < count($udraw_convert_response->data); $i++) {
                                array_push($url_array, UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$i]);
                            }                                    
                        } else {
                            echo "false"; wp_die();
                        } 
                        
                        for ($i = 0; $i < count($url_array); $i++) {
                            $new_file_name = $new_file . '_page_' . ($i + 1) . $output_file_ext;
                            $this->downloadFile($url_array[$i], $upload_dir . '/'. $new_file_name);
                            array_push($new_file_array, (object)array(
                                'filename' => $new_file_name
                            ));
                        }

                        // Once new SVG document is saved, we can remove the uploaded PDF document.
                        unlink($uploaded_files[0]['file']);

                        // return back the new SVG file name.
                        $this->sendResponse($new_file_array);
                    }
                }
            } else {
                if ($file_ext != 'jpg' && $file_ext != 'jpeg' && $file_ext != 'png' && $file_ext != 'gif' && $file_ext != 'svg') {
                    $this->sendResponse(false);
                } else {
                    $file_dir = $upload_dir . '/'. $new_file;
                    move_uploaded_file($_FILES['files']['tmp_name'][0], $file_dir);
                    if (version_compare(phpversion(), '7.2.0', '>=') && extension_loaded('exif')) {
                        //Check for correct orientation if jpeg/jpg
                        if ($file_ext === 'jpg' || $file_ext === 'jpeg') {
                            $exif = exif_read_data($file_dir);
                            if ($exif) {
                                ob_start();
                                $image = imagecreatefromjpeg($file_dir);
                                $image_header = 'data:image/jpeg;base64,';
                                if(!empty($exif['Orientation'])) {
                                    switch($exif['Orientation']) {
                                        case 8:
                                            $rotation = imagerotate($image,90,0);
                                            break;
                                        case 3:
                                            $rotation = imagerotate($image,180,0);
                                            break;
                                        case 6:
                                            $rotation = imagerotate($image,-90,0);
                                            break;
                                    }
                                }
                                imagejpeg($rotation, $file_dir, 100);
                                ob_end_clean();
                                imagedestroy($image);
                                imagedestroy($rotation);
                            }
                        }
                    }
                    
                    // Try to look and detect malformed SVG documents that can mark the design "dirty".
                    if ($file_ext == 'svg') {
                        $handle = fopen($upload_dir . '/'. $new_file, 'r');
                        $isMalformed = false;
                        while (($buffer = fgets($handle)) !== false) {
                            if (  strpos($buffer, "<foreignObject") !== false) {
                                $isMalformed = true;
                                break; 
                            }
                        }
                        fclose($handle);

                        if ($isMalformed) {
                            $svg_file = file_get_contents($upload_dir . '/'. $new_file);

                            if ( strpos($svg_file, "<foreignObject") !== false ) {
                                // remove foreignObject from SVG
                                $start = "<foreignObject";
                                $end = "foreignObject>";
                                $start_idx = strpos($svg_file, $start);
                                $end_idx = strpos($svg_file, $end, $start_idx+strlen($start));
                                $svg_file = substr_replace($svg_file,'', $start_idx, $end_idx-$start_idx+strlen($end));
                            }

                            sleep(1);
                            unlink($upload_dir . '/'. $new_file);
                            $new_file = '/udraw_'. $new_file;
                            file_put_contents($upload_dir . $new_file, $svg_file);
                        }
                    }

                    $this->sendResponse((object)array(
                        'filename' => $new_file
                    ));
                }
            }
            $this->sendResponse((object)array(
                'filename' => ''
            ));
        }
        public function uploaded_images ($path = '') {
            if (isset($_REQUEST['path'])) {
                $path = $_REQUEST['path'];
            } else {
                $this->sendResponse(false);
            }
            $image_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $path);

            $response = array();

            // files in top level
            $files = glob($image_dir . '/*.{png,jpg,jpeg,svg}', GLOB_BRACE);
            usort($files, function($a,$b){
              return filemtime($b) - filemtime($a);
            });
            foreach($files as $file) {
                $_item = (object)array(
                    'type' => 'file',
                    'name' => basename($file),
                    'ext' =>  '.' . pathinfo($file, PATHINFO_EXTENSION)
                );
                if (version_compare(phpversion(), '7.2.0', '>=')) {
                    //Check that minimum DPI is set
                    $uDraw_SVG_settings = new uDraw_SVG_settings();
                    $settings = $uDraw_SVG_settings->get_settings();
                    if ($settings['udraw_SVGDesigner_enable_dpi'] && $settings['udraw_SVGDesigner_minimum_dpi'] > 0) {
                        if (pathinfo($file, PATHINFO_EXTENSION) === 'jpg' || pathinfo($file, PATHINFO_EXTENSION) === 'jpeg') {
                            $image = imagecreatefromjpeg($file);
                            $resolution = imageresolution($image);
                            $_item->dpi = $resolution;
                        }
                    }
                }
                array_push($response, $_item);
            }

            $this->sendResponse($response);
        }
        public function download_image ($image_source = '', $image_name = '', $image_dir = '') {
            if (isset($_REQUEST['source']) && isset($_REQUEST['image_name']) && isset($_REQUEST['upload_path'])) {
                $image_source = $_REQUEST['source'];
                $image_name = $_REQUEST['image_name'];
                $path = $_REQUEST['upload_path'];
            } else {
                $this->sendResponse(false);
            }
            
            $filename_info = pathinfo($image_name);
            $base_name = $filename_info['filename'];
            $ext = (isset($filename_info['extension'])) ? $filename_info['extension'] : 'jpg';
            
            $image_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $path);
            $file_path = $image_dir . '/' . $base_name . '.' . $ext;
            $this->downloadFile($image_source, $file_path);
            $result = file_exists($file_path);
            if ($result) {
                $this->sendResponse($base_name . '.' . $ext);
            } else {
                $this->sendResponse(false);
            }
        }
        public function export_image ($image_data = '', $export_path = '') {
            if (isset($_REQUEST['image_data']) && isset($_REQUEST['export_path'])) {
                $image_data = $_REQUEST['image_data'];
                $export_path = $_REQUEST['export_path'];
            } else {
                $this->sendResponse(false);
            }
            $image_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $export_path);
            $image_contents = explode('base64,', $image_data);
            //We'll worry about only png, jpg, and jpeg for now
            $exploded_part = strtolower($image_contents[0]);
            $file_extension = '';
            if (strpos($exploded_part, 'png') !== false) { $file_extension = 'png'; }
            if (strpos($exploded_part, 'jpg') !== false) { $file_extension = 'jpg'; }
            if (strpos($exploded_part, 'jpeg') !== false) { $file_extension = 'jpeg'; }
            
            if (strlen($file_extension) > 0) {
                $file_name = uniqid('image_') . '.' . $file_extension;
                $result = file_put_contents($image_dir . '/' . $file_name, base64_decode($image_contents[1]));
                if ($result) {
                    $result = 'success';
                }
            } else {
                $result = false;
                $file_name = '';
            }
            $return_object = (object)array(
                'status' => $result,
                'image_url' => $file_name
            );
            $this->sendResponse($return_object);
        }
        public function process_fonts () {
            $font_path = $_REQUEST['font_path'];
            if (strlen($font_path) == 0) { return ""; }
            $fontDir = str_replace(wp_make_link_relative(UDRAW_FONTS_URL), UDRAW_FONTS_DIR, $font_path);
            $fontFiles = glob($fontDir . "/*.woff");
            $fontList = array();
            foreach($fontFiles as $fonts) {
                $_name = basename($fonts, '.woff');
                $_path = $font_path . basename($fonts);
                $font = (object) array(
                    'name' => $_name,
                    'path' => $_path
                );
                array_push($fontList, $font);
            }
            $this->sendResponse($fontList);
        }
        
        public function authenticate_instagram() {
            echo "<script>var access_token = location.hash.replace('#access_token=','');</script>";
            return $this->sendResponse(true);
        }
		
        public function retrieve_instagram() {
            $path = (isset($_REQUEST['url'])) ? $_REQUEST['url'] : '';
            if (strlen($path) === 0) {
                return $this->sendResponse(false);
            }
            $data = json_decode(file_get_contents($path));
            
            return $this->sendResponse($data);
        }
        
        public function check_license_key () {
            $uDrawUtil = new uDrawUtil();
            $uDraw = new uDraw();
            $key = $uDraw->get_udraw_activation_key();
            $host = $_SERVER['HTTP_HOST'];
            if(strpos($host,':'.$_SERVER['SERVER_PORT'])!== false){
                $host=str_replace(':'.$_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
            }
            $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/access/check/'. $key . '/'. str_replace(':','-',str_replace('.', '-', $host)));
            $response = json_decode($json);
            return $this->sendResponse($response);
        }
        
        public function get_templates_count () {
            global $wpdb;
            $table_name = $wpdb->prefix . 'udraw_svg_templates';
            $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            return $this->sendResponse(count($results));
        }
        
        public function save_page () {
            $uDraw_SVG = new uDraw_SVG();
            if (isset($_REQUEST['data']) && isset($_REQUEST['current_file'])) {
                $svg_string = $_REQUEST['data'];
                $current_file = $_REQUEST['current_file'];
            } else {
                $this->sendResponse(false);
            }
            try {
                $replace = wp_make_link_relative(UDRAW_STORAGE_URL);
                if (strpos($current_file, UDRAW_STORAGE_URL) !== false) {
                    $replace = UDRAW_STORAGE_URL;
                }
                $svg_file_dir = str_replace($replace, UDRAW_STORAGE_DIR, $current_file);
                file_put_contents($svg_file_dir, stripslashes($svg_string));
                
                if (isset($_REQUEST['preview_data']) && isset($_REQUEST['preview_file'])) {
                    $preview_file = $_REQUEST['preview_file'];
                    $image_data = $_REQUEST['preview_data'];
                    if (strlen($image_data) > 0) {
                        $image_replace = wp_make_link_relative(UDRAW_STORAGE_URL);
                        if (strpos($preview_file, UDRAW_STORAGE_URL) !== false) {
                            $image_replace = UDRAW_STORAGE_URL;
                        }
                        $svg_file_dir = str_replace($replace, UDRAW_STORAGE_DIR, $preview_file);
                        $image_content = $uDraw_SVG->convert_base64_image($image_data);
                        file_put_contents($svg_file_dir, $image_content);
                    }
                }
                $this->sendResponse(true);
            } catch (Exception $e) {
                error_log(print_r($e, true));
            }
        }
        
        public function create_page () {
            if (isset($_REQUEST['page_list']) && isset($_REQUEST['session_id']) && isset($_REQUEST['output_path'])) {
                $page_list = $_REQUEST['page_list'];
                $session_id = $_REQUEST['session_id'];
                $output_path = $_REQUEST['output_path'];
            } else {
                $this->sendResponse(false);
            }
            try {
                $output_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $output_path);
                $_dir = $output_dir . $session_id . '/';
                if (!wp_mkdir_p($_dir)) {
                    $this->sendResponse(false);
                }
                $contents = array(
                    'session_id' => $session_id,
                    'pages' => $page_list
                );
                $result = file_put_contents($_dir . $session_id . '.json', stripslashes(json_encode($contents)));

                $this->sendResponse($result);
            } catch (Exception $e) {
                error_log(print_r($e, true));
            }
        }
        
        public function convert_url_to_base64 () {
            $response = false;
            if (isset($_REQUEST['url']) && strlen($_REQUEST['url']) > 0) {
                $response = file_get_contents($_REQUEST['url']);
                if ($response !== false) {
                    $type = pathinfo($_REQUEST['url'], PATHINFO_EXTENSION);
                    if ($type === 'svg') {
                        $type = 'svg+xml';
                    }
                    $response = 'data:image/' . $type . ';base64,' . base64_encode($response);
                }
            }
            $this->sendResponse($response);
        }
    }
}
?>
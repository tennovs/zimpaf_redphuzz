<?php

if (!class_exists('uDrawPDFBlocks')) {
    class uDrawPDFBlocks {
        
        function __contsruct() { }
        
        function init() {
            add_action( 'wp_ajax_udraw_pdf_block_blob_upload', array(&$this,'handle_ajax_upload_blob') );
            add_action( 'wp_ajax_udraw_pdf_block_get_templates', array(&$this, 'handle_ajax_get_templates') );
            add_action( 'wp_ajax_udraw_pdf_block_upload', array(&$this, 'upload') );
            
            add_action( 'wp_ajax_nopriv_udraw_pdf_block_blob_upload', array(&$this,'handle_ajax_upload_blob') );
            add_action( 'wp_ajax_nopriv_udraw_pdf_block_upload', array(&$this, 'upload') );
        }
        
        function get_company_products() {
            $goEpower = new GoEpower();
            return $goEpower->get_company_products_by_type("blocks");
        }
        
        function get_product($product_id) {
            $all_products = $this->get_company_products();
            for ($x = 0; $x < count($all_products); $x++) {
                if ($all_products[$x]['ProductID'] == $product_id || $all_products[$x]['UniqueID'] == $product_id) {
                    return $all_products[$x];
                }
            }
            return null;
        }
        
        function handle_ajax_get_templates() {
            if (isset($_REQUEST['block-template-id'])) {    
                $block_template = $this->get_product($_REQUEST['block-template-id']);
                
                if (!is_null($block_template)) {
                    echo json_encode($block_template);
                } else {
                    echo json_encode(false);
                }                
            }
            wp_die();
        }
        public static function is_pdf_block_product($product_id) {
            if (get_post_meta($product_id, '_udraw_product', true) == 'true') {
                if (get_post_meta($product_id, '_udraw_block_template_id', true) > 0) {
                    return true;
                }
            }
            
            return false;
        } 
        
        function handle_ajax_upload_blob() 
        {
            // Saving Blob Image.
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_REQUEST['imageData']));
            $image_name = uniqid('uatemp_') . '.png';
            file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $image_name, $data);
            echo UDRAW_TEMP_UPLOAD_URL . $image_name;
            wp_die();
        }
        
        public function upload() {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $fileObj = new stdClass();
            
            $_session_id = uniqid();
            if (isset($_REQUEST['session'])) {
                $_session_id = $_REQUEST['session'];
            }
            if (!isset($_REQUEST['asset'])) {
                wp_die();
            }

            // Set both upload folders and url location.
            $upload_dir = UDRAW_TEMP_UPLOAD_DIR . $_session_id . "/";
            $upload_url = UDRAW_TEMP_UPLOAD_URL . $_session_id . "/";

            // Create directory if doesn't exist.
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir);
            }
            
            $validExt = array (
                'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'psd' => 'application/octet-stream', 'pdf' => 'application/pdf',
                'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ai' => 'application/postscript', 'cdr' => 'application/octet-stream', 'eps' => 'application/postscript', 'ps' => 'application/postscript',
                'indd' => 'application/octet-stream', 'doc|docx' => 'application/msword', 'xls|xlsx' => 'application/excel', 'ppt|pptx' => 'application/mspowerpoint', 'obj' => 'application/octet-stream'
            );
            if (!is_null($_udraw_settings['goprint2_file_upload_types'])) {
                if (is_array($_udraw_settings['goprint2_file_upload_types'])) {
                    $validExt = $_udraw_settings['goprint2_file_upload_types'];
                }
            }
            
            $valid_ext_array = array();
            foreach ($validExt as $ext => $mime_type) {
                array_push($valid_ext_array, $mime_type);
            }
            $before_base64 = explode(';base64', $_REQUEST['asset'])[0];
            if ($before_base64 !== null) {
                $uploaded_file_type = explode('data:',$before_base64)[1];
                if ($uploaded_file_type !== null) {
                    // Check file exstension
                    if (isset($_REQUEST['name'])) {
                        $fileName = pathinfo($_REQUEST['name'], PATHINFO_FILENAME);
                        $fileExt = strtolower(pathinfo($_REQUEST['name'], PATHINFO_EXTENSION));
                    } else {
                        $fileName = uniqid('uploaded_image_');
                        $fileExt = '';
                        foreach ($validExt as $ext => $mimeType) {
                            if ($mimeType === $uploaded_file_type) {
                                $fileExt = explode('|', $ext)[0];
                            }
                        }
                    }
                    // New Filename
                    $newFile = rand(1, 32) .'_'. str_replace(' ','', $fileName) . '.' . $fileExt;
                    $fileObj->name = $newFile;
                    
                    if (in_array($uploaded_file_type, $valid_ext_array)) {
                        $output_file = $upload_dir . $newFile;
                        $img_data = $_REQUEST['asset'];

                        if ($uploaded_file_type == 'application/pdf')
                        {
                            $data = str_replace('data:application/pdf;base64,', '', $img_data);
                            $data = str_replace(' ', '+', $data);
                            $data = base64_decode($data);
                        } else {
                            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_REQUEST['asset']));
                        }

                        $_file = file_put_contents($output_file, $data);
                        if ($_file === false) {
                            $fileObj->error = "Upload Failed";
                        } else {
                            $fileObj->name = $newFile;
                            $fileObj->url = $fileObj->asset = $upload_url . $newFile;

                            if (isset($_REQUEST['greyscale'])) {
                                if (strtolower($fileExt) == 'pdf') {
                                    $uDrawUtil = new uDrawUtil();
                                    $uDrawConnect = new uDrawConnect();
                                    $data = array(
                                        'pdfDocument' => $fileObj->url,
                                        'key' => uDraw::get_udraw_activation_key()
                                    );
                                    $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2BW', http_build_query($data)));                
                                    if ($udraw_convert_response->isSuccess) {
                                        if (is_array($udraw_convert_response->data)) {
                                            $uDrawConnect->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[0], $output_file);
                                            $fileObj->size = filesize($output_file);
                                        }
                                    }                                                                        
                                }
                            }
                        }
                    } else {
                        $fileObj->error = "Invalid file type.";
                    }
                } else {
                    $fileObj->error = "Invalid file.";
                }
            } else {
                $fileObj->error = "Invalid file.";
            }
            echo json_encode($fileObj);
            wp_die();
        }

    }
}

?>
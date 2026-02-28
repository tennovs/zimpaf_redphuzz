<?php
/**
 * DesignHandler for uDraw Designer
 *
 * This will handle all server requests from uDraw Designer.
 *
 * @version 1.0
 * @author Amram Bentolila
 */

if (!class_exists('uDrawDesignHandler')) {
    class uDrawDesignHandler extends uDrawAjaxBase {
        
        function __construct() { }
        
        function init_actions() {
            add_action( 'wp_ajax_udraw_designer_local_fonts_list', array(&$this,'local_fonts_list') );
            add_action( 'wp_ajax_udraw_designer_local_fonts_css', array(&$this,'local_fonts_css') );
            add_action( 'wp_ajax_udraw_designer_local_fonts_css_base64', array(&$this,'local_fonts_css_base64') );
            add_action( 'wp_ajax_udraw_designer_remove_font', array(&$this,'remove_font') );
            add_action( 'wp_ajax_udraw_designer_upload', array(&$this,'upload') );
            add_action( 'wp_ajax_udraw_designer_upload_preview', array(&$this,'upload_preview') );
            add_action( 'wp_ajax_udraw_designer_save', array(&$this,'save') );
            add_action( 'wp_ajax_udraw_designer_save_page_data', array(&$this,'save_page_data') );
            add_action( 'wp_ajax_udraw_designer_compile_design_data', array(&$this,'compile_design_data') );
            add_action( 'wp_ajax_udraw_designer_finalize', array(&$this,'finalize') );
            add_action( 'wp_ajax_udraw_designer_export_pdf', array(&$this,'export_pdf') );
            add_action( 'wp_ajax_udraw_designer_rebuild_order_pdf', array(&$this,'rebuild_pdf') );
            add_action( 'wp_ajax_udraw_designer_asset', array(&$this,'asset') );
            add_action( 'wp_ajax_udraw_designer_download_image', array(&$this, 'download_image') );
            add_action( 'wp_ajax_udraw_designer_local_images', array(&$this,'local_images') );
            add_action( 'wp_ajax_udraw_designer_remove_image', array(&$this,'remove_image') );
            add_action( 'wp_ajax_udraw_designer_local_patterns', array(&$this,'local_patterns') );
            add_action( 'wp_ajax_udraw_designer_upload_patterns', array(&$this,'upload_patterns') );
            add_action( 'wp_ajax_udraw_designer_export_preview_image', array(&$this, 'export_preview_image') );
            add_action( 'wp_ajax_udraw_designer_export_image', array(&$this, 'export_image') );
            add_action( 'wp_ajax_udraw_designer_get_text_templates', array(&$this, 'get_text_templates') );
            //Convert PDF
            add_action( 'wp_ajax_udraw_designer_convert_pdf', array(&$this, 'convert_pdf') );
            //Translation files
            add_action( 'wp_ajax_udraw_designer_create_translation_file', array(&$this, 'create_translation_file') );
            add_action( 'wp_ajax_udraw_designer_retrieve_translation_file_contents', array(&$this, 'retrieve_translation_file_contents') );
            add_action( 'wp_ajax_udraw_designer_edit_translation_file', array(&$this, 'edit_translation_file') );
            add_action( 'wp_ajax_udraw_designer_update_translation_files', array(&$this, 'update_translation_files') );
            //Instagram
            add_action( 'wp_ajax_udraw_designer_authenticate_instagram', array(&$this, 'authenticate_instagram') );
            add_action( 'wp_ajax_udraw_designer_retrieve_instagram', array(&$this, 'retrieve_instagram') );
            //Flickr
            add_action( 'wp_ajax_udraw_designer_authenticate_flickr', array(&$this, 'authenticate_flickr') );
            add_action( 'wp_ajax_udraw_designer_flickr_access_token', array(&$this, 'flickr_access_token') );
            add_action( 'wp_ajax_udraw_designer_flickr_get', array(&$this, 'flickr_get') );
            //Creating xml
            add_action( 'wp_ajax_udraw_designer_create_product_xml_pages', array(&$this, 'create_product_xml_pages') );
            add_action( 'wp_ajax_udraw_designer_retrieve_page_xml_files', array(&$this, 'retrieve_page_xml_files') );
            add_action( 'wp_ajax_udraw_designer_create_page_xml', array(&$this, 'create_page_xml') );
            add_action( 'wp_ajax_udraw_designer_create_merged_xml', array(&$this, 'create_merged_xml') );
            add_action( 'wp_ajax_udraw_designer_remove_xml_files', array(&$this, 'remove_xml_files') );
            //Add send email ajax action
            add_action( 'wp_ajax_udraw_send_saved_design_link', array(&$this,'udraw_send_saved_design_link') );
            
            //nopriv
            add_action( 'wp_ajax_nopriv_udraw_designer_local_fonts_list', array(&$this,'local_fonts_list') );
            add_action( 'wp_ajax_nopriv_udraw_designer_local_fonts_css', array(&$this,'local_fonts_css') );
            add_action( 'wp_ajax_nopriv_udraw_designer_local_fonts_css_base64', array(&$this,'local_fonts_css_base64') );                     
            add_action( 'wp_ajax_nopriv_udraw_designer_upload', array(&$this,'upload') );
            add_action( 'wp_ajax_nopriv_udraw_designer_upload_preview', array(&$this,'upload_preview') );
            add_action( 'wp_ajax_nopriv_udraw_designer_save', array(&$this,'save') );
            add_action( 'wp_ajax_nopriv_udraw_designer_save_page_data', array(&$this,'save_page_data') );
            add_action( 'wp_ajax_nopriv_udraw_designer_compile_design_data', array(&$this,'compile_design_data') );
            add_action( 'wp_ajax_nopriv_udraw_designer_export_pdf', array(&$this,'export_pdf') );
            add_action( 'wp_ajax_nopriv_udraw_designer_asset', array(&$this,'asset') );
            add_action( 'wp_ajax_nopriv_udraw_designer_download_image', array(&$this, 'download_image') );
            add_action( 'wp_ajax_nopriv_udraw_designer_local_images', array(&$this,'local_images') );
            add_action( 'wp_ajax_nopriv_udraw_designer_remove_image', array(&$this,'remove_image') );
            add_action( 'wp_ajax_nopriv_udraw_designer_local_patterns', array(&$this,'local_patterns') );
            add_action( 'wp_ajax_nopriv_udraw_designer_upload_patterns', array(&$this,'upload_patterns') );
            add_action( 'wp_ajax_nopriv_udraw_designer_export_preview_image', array(&$this, 'export_preview_image') );
            add_action( 'wp_ajax_nopriv_udraw_designer_convert_pdf', array(&$this, 'convert_pdf') );
            add_action( 'wp_ajax_nopriv_udraw_designer_export_image', array(&$this, 'export_image') );
            add_action( 'wp_ajax_nopriv_udraw_designer_get_text_templates', array(&$this, 'get_text_templates') );
            //Instagram
            add_action( 'wp_ajax_nopriv_udraw_designer_authenticate_instagram', array(&$this, 'authenticate_instagram') );
            add_action( 'wp_ajax_nopriv_udraw_designer_retrieve_instagram', array(&$this, 'retrieve_instagram') );
            //Flickr
            add_action( 'wp_ajax_nopriv_udraw_designer_authenticate_flickr', array(&$this, 'authenticate_flickr') );
            add_action( 'wp_ajax_nopriv_udraw_designer_flickr_access_token', array(&$this, 'flickr_access_token') );
            add_action( 'wp_ajax_nopriv_udraw_designer_flickr_get', array(&$this, 'flickr_get') );
            //Creating xml
            add_action( 'wp_ajax_nopriv_udraw_designer_create_product_xml_pages', array(&$this, 'create_product_xml_pages') );
            add_action( 'wp_ajax_nopriv_udraw_designer_retrieve_page_xml_files', array(&$this, 'retrieve_page_xml_files') );
            add_action( 'wp_ajax_nopriv_udraw_designer_create_page_xml', array(&$this, 'create_page_xml') );
            add_action( 'wp_ajax_nopriv_udraw_designer_create_merged_xml', array(&$this, 'create_merged_xml') );
            add_action( 'wp_ajax_nopriv_udraw_designer_remove_xml_files', array(&$this, 'remove_xml_files') );
            //Email
            add_action( 'wp_ajax_nopriv_udraw_send_saved_design_link', array(&$this,'udraw_send_saved_design_link') );

            // Purchase a saved design            
            add_action( 'wp_ajax_udraw_purchase_saved_design',             array(&$this,'udraw_purchase_saved_design' ) );
            add_action( 'wp_ajax_nopriv_udraw_purchase_saved_design',      array(&$this,'udraw_purchase_saved_design' ) );
        }
        
        function local_fonts_list() {
            $this->sendResponse($this->__process_fonts());
        }
        
        function local_fonts_css() 
        {
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
        
        function local_fonts_css_base64()
        {
            $localFonts = $this->__process_fonts();
            if (gettype($localFonts) == 'string') { return $this->sendResponse(""); }
            
            $css = "";
            foreach ($localFonts as $fonts) {
                $base64Font = fread(fopen($fonts->sysPath, "r"), $fonts->fileSize);
                $css .= "@font-face { " . PHP_EOL;
                $css .= "font-family: '". $fonts->name ."';". PHP_EOL;

                if ($fonts->fontType === 'woff') {
                    $css .= "src: url('data:application/font-woff;charset=utf-8;base64,".  base64_encode($base64Font) ."') format('". $fonts->fontType ."');". PHP_EOL;
                } else if ($fonts->fontType === 'truetype') {
                    $css .= "src: url('data:application/octet-stream;charset=utf-8;base64,".  base64_encode($base64Font) ."') format('". $fonts->fontType ."');". PHP_EOL;
                }
                $css .= "}". PHP_EOL;
            }
            
            header('Content-Type: text/css');
            echo $css;
            wp_die();
        }
        
        function remove_font() {
            if (isset($_REQUEST['font_name'])) {    
                if (file_exists(UDRAW_FONTS_DIR . $_REQUEST['font_name'] . $_REQUEST['font_type']) || file_exists(UDRAW_FONTS_DIR . $_REQUEST['font_name'] . strtoupper($_REQUEST['font_type'])) ) {
                    if (current_user_can('delete_udraw_fonts')) {
                        if (file_exists(UDRAW_FONTS_DIR . $_REQUEST['font_name'] . $_REQUEST['font_type'])) {
                            unlink(UDRAW_FONTS_DIR . $_REQUEST['font_name'] . $_REQUEST['font_type']);
                            return $this->sendResponse(true);
                        } else {
                            unlink(UDRAW_FONTS_DIR . $_REQUEST['font_name'] . strtoupper($_REQUEST['font_type']));
                            return $this->sendResponse(true);
                        }                        
                    }
                }
            }
            
            return $this->sendResponse(false);
        }
        
        function upload() {
            $assetPath = $_REQUEST['assetPath'];
            $this->__process_upload($assetPath);
            wp_die();
        }
        
        function save() {
            $this->__process_save(false);
            wp_die();
        }
        
        function finalize() {
            $this->__process_save(true);
            wp_die();
        }
        
        function export_pdf() {
            $uDrawUtil = new uDrawUtil();

            $data = array(
                'designFile' => stripcslashes($_REQUEST['design']),
                'key' => uDraw::get_udraw_activation_key()
            );
            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/uDraw2PDF', http_build_query($data)));                
            if ($udraw_convert_response->isSuccess) {
                $pdfContent = UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data->pdf;
                $pdfName = uniqid('udraw_') . '.pdf';
                $uDrawUtil->download_file($pdfContent, UDRAW_TEMP_UPLOAD_DIR . $pdfName);

                echo json_encode(UDRAW_TEMP_UPLOAD_URL . $pdfName);
            }

            wp_die();
        }
        
        function rebuild_pdf() {
            $uDraw = new uDraw();
            if (isset($_REQUEST['order_id'])) {
                $uDraw->generate_pdf_from_order($_REQUEST['order_id'], true);
            }
            echo json_encode('okay');
            
            wp_die();
        }
        
        function asset() {
            $extension = "";
            $contentType = "";
            $asset = stripslashes($_REQUEST['asset']);
            $assetPathInfo = pathinfo($asset);
            $assetFile = basename($asset);
            $isExternalRequest = false;
            
            // Facebook Params
            $oe = ""; $__gda__ = "";
            if (isset($_REQUEST['oe'])) { $oe = $_REQUEST['oe']; }
            if (isset($_REQUEST['__gda__'])) { $__gda__ = $_REQUEST['__gda__']; }

            if ($this->endsWith(strtolower($asset), "jpg")) { $extension = "jpg"; $contentType = "image/jpeg"; }
            if ($this->endsWith(strtolower($asset), "jpeg")) { $extension = "jpg"; $contentType = "image/jpeg"; }                
            if ($this->endsWith(strtolower($asset), "png")) { $extension = "png"; $contentType = "image/png"; }
            if ($this->endsWith(strtolower($asset), "svg")) { $extension = "svg"; $contentType = "image/svg+xml"; }
            if ($this->endsWith(strtolower($asset), "gif")) { $extension = "gif"; $contentType = "image/gif"; }
            if ($this->endsWith(strtolower($asset), "xml")) { $extension = "xml"; $contentType = "application/xml"; }
            if ($this->endsWith(strtolower($asset), "eps")) { $extension = "eps"; $contentType = "application/eps"; }
            if ($this->endsWith(strtolower($asset), "pdf")) { $extension = "pdf"; $contentType = "application/pdf"; }
            if ($this->endsWith(strtolower($asset), "ps")) { $extension = "ps"; $contentType = "application/ps"; }
            if ($this->endsWith(strtolower($asset), "tiff")) { $extension = "tiff"; $contentType = "application/tiff"; }                                  
            if (strpos($asset, '?') !== false && $contentType == "") {
                $isExternalRequest = true;
                $extension = "jpg"; $contentType = "application/octet-stream";
            }            
            
            // Return nothing if content type isn't found. We only want to accept certain type of files.
            if ($contentType == "") { echo "invalid"; return; }            
            
            if (!$this->startsWith(strtolower($asset), "http")) {
                $protocol = UDRAW_SYSTEM_WEB_PROTOCOL;                
                $assetFile = rawurldecode($assetFile);
                while ( (strpos($assetFile, '%25') !== false) || (strpos($assetFile, '%2F') !== false) ) {
                    $assetFile = rawurldecode($assetFile);
                }

                $dirname = $assetPathInfo['dirname'];
                if ($dirname === '.') { $dirname = ""; }
                
                $asset = $protocol . $_SERVER['HTTP_HOST'] . $dirname . '/'. $assetFile;
            } else {
                $asset = rawurldecode($asset);
                while ( (strpos($asset, '%25') !== false) || (strpos($asset, '%2F') !== false) ) {
                    $asset = rawurldecode($asset);
                }
            }                       
            // Facebook params
            if ($oe != null)
            {
                // Adding the parameters back into the url so that the image can be downloaded
                $asset .= "&oe=" . $oe;
            }
            
            // Facebook Param
            if ($__gda__ != null)
            {
                $asset .= "&__gda__=" . $__gda__;
            }

            if (!$isExternalRequest) {
                // Stript name out of url
                $assetParts = explode('/', $asset);
                $assetFile = $assetParts[count($assetParts)-1];
                $asset = str_replace($assetFile, '',$asset);

                // URL encode name
                $is_encoded = preg_match('~%[0-9A-F]{2}~i', $assetFile);
                if (!$is_encoded) {
                    $assetFile = urlencode($assetFile);
                }

                // Inject URL encoded name back to asset.
                $asset = $asset . $assetFile;
            }
                                                
            // Create tempfile
            $tmp_fp = tmpfile();
            
            // Download asset and store to tempfile pointer
            $uDrawUtil = new uDrawUtil();
            $local_asset = $_SERVER['DOCUMENT_ROOT'] . parse_url($asset)['path'];
            if (is_file($local_asset)) { 
                // Update URL incase this asset was moved from server to server.
                $asset = UDRAW_SYSTEM_WEB_PROTOCOL . $_SERVER['HTTP_HOST'] . parse_url($asset)['path'];
            }
            
            $tmp_fp = $uDrawUtil->download_file_with_pointer($asset, $tmp_fp);
           
            // Send tempfile out to browser and close the pointer which removes the tempfile.
            if (ob_get_contents()) {
                ob_end_clean();
            }
            $tmp_fp_stat = fstat($tmp_fp);
            // put pointer back at the start
            if (ob_get_contents()) {
                ob_end_clean();
            }
            if (count(ob_get_status(true)) > 0 ) {
                ob_clean(); // Clean (erase) the output buffer
            }
            rewind($tmp_fp);
            header('Content-Type: '. $contentType);
            header("Content-Length: " . $tmp_fp_stat['size']);
            header("Access-Control-Allow-Origin: *");
            fpassthru($tmp_fp);
            fclose($tmp_fp);
            
            wp_die();
        }
        
        function download_image() {
            $uDraw = new uDraw();
            
            $asset_path = $_REQUEST['asset_path'];
            $asset_dir = $uDraw->get_physical_path($asset_path);
            
            $extension = "";
            $contentType = "";
            $asset = stripslashes($_REQUEST['image_src']);
            $source = $_REQUEST['source'];
            $assetPathInfo = pathinfo($asset);
            $assetFile = basename($asset);
            $isExternalRequest = false;
            
            // Facebook Params
            $oe = ""; $__gda__ = "";
            if (isset($_REQUEST['oe'])) { $oe = $_REQUEST['oe']; }
            if (isset($_REQUEST['__gda__'])) { $__gda__ = $_REQUEST['__gda__']; }

            if ($this->endsWith(strtolower($asset), "jpg")) { $extension = "jpg"; $contentType = "image/jpeg"; }
            if ($this->endsWith(strtolower($asset), "jpeg")) { $extension = "jpg"; $contentType = "image/jpeg"; }                
            if ($this->endsWith(strtolower($asset), "png")) { $extension = "png"; $contentType = "image/png"; }
            if ($this->endsWith(strtolower($asset), "svg")) { $extension = "svg"; $contentType = "image/svg+xml"; }
            if ($this->endsWith(strtolower($asset), "gif")) { $extension = "gif"; $contentType = "image/gif"; }
            if ($this->endsWith(strtolower($asset), "xml")) { $extension = "xml"; $contentType = "application/xml"; }
            if ($this->endsWith(strtolower($asset), "eps")) { $extension = "eps"; $contentType = "application/eps"; }
            if ($this->endsWith(strtolower($asset), "pdf")) { $extension = "pdf"; $contentType = "application/pdf"; }
            if ($this->endsWith(strtolower($asset), "ps")) { $extension = "ps"; $contentType = "application/ps"; }
            if ($this->endsWith(strtolower($asset), "tiff")) { $extension = "tiff"; $contentType = "application/tiff"; }                                  
            if ((strpos($asset, 'photo') !== false || strpos($asset, '?') !== false) && $contentType == "") {
                $isExternalRequest = true;
                $extension = "jpg"; $contentType = "application/octet-stream";
            }            
            
            // Return nothing if content type isn't found. We only want to accept certain type of files.
            if ($contentType == "") { echo "invalid"; return; }            
            
            if (!$this->startsWith(strtolower($asset), "http")) {
                $protocol = UDRAW_SYSTEM_WEB_PROTOCOL;                
                $assetFile = rawurldecode($assetFile);
                while ( (strpos($assetFile, '%25') !== false) || (strpos($assetFile, '%2F') !== false) ) {
                    $assetFile = rawurldecode($assetFile);
                }

                $dirname = $assetPathInfo['dirname'];
                if ($dirname === '.') { $dirname = ""; }
                
                $asset = $protocol . $_SERVER['HTTP_HOST'] . $dirname . '/'. $assetFile;
            } else {
                $asset = rawurldecode($asset);
                while ( (strpos($asset, '%25') !== false) || (strpos($asset, '%2F') !== false) ) {
                    $asset = rawurldecode($asset);
                }
            }                       
            // Facebook params
            if ($oe != null)
            {
                // Adding the parameters back into the url so that the image can be downloaded
                $asset .= "&oe=" . $oe;
            }
            
            // Facebook Param
            if ($__gda__ != null)
            {
                $asset .= "&__gda__=" . $__gda__;
            }

            if (!$isExternalRequest) {
                // Stript name out of url
                $assetParts = explode('/', $asset);
                $assetFile = $assetParts[count($assetParts)-1];
                $asset = str_replace($assetFile, '',$asset);

                // URL encode name
                $is_encoded = preg_match('~%[0-9A-F]{2}~i', $assetFile);
                if (!$is_encoded) {
                    $assetFile = urlencode($assetFile);
                }

                // Inject URL encoded name back to asset.
                $asset = $asset . $assetFile;
            }
            
            $uDrawUtil = new uDrawUtil();
            $filename = uniqid($source . '_') . '.' . $extension;
            $result = $uDrawUtil->download_file($asset, $asset_dir . '/' . $filename);
            
            if ($result) {
                $result = 'success';
            }
            $return_object = (object)array(
                'filename' => $filename,
                'status' => $result
            );
            $this->sendResponse($return_object);
        }
        
        function local_images() {
            $localImagePath = $_REQUEST['localImagePath'];
            $this->__process_images($localImagePath, true);
            wp_die();
        }
        
        function remove_image() {
            ob_end_clean();
            ob_end_clean();
            if (count(ob_get_status(true)) > 0 ) {
                ob_clean(); // Clean (erase) the output buffer
            }
            $file = $_REQUEST['image_file'];
            $_asset_path = "_UNSET_";
            if (is_user_logged_in()) {
                $_asset_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/assets/';
            } else {
                if (isset($_REQUEST['session_id'])) {
                    $session_id = $_REQUEST['session_id'];
                    $_asset_path = UDRAW_STORAGE_DIR .'_'. $session_id .'_/assets/';
                }
            }
            $image_path = $_asset_path . $file;
            if (is_file($image_path)) {
                $ext = strtolower( pathinfo($image_path, PATHINFO_EXTENSION) );
                if ($ext == 'svg' || $ext == 'png' || $ext == 'pdf' || $ext == 'jpeg' || $ext == 'tif' || $ext == 'tiff' || $ext == 'jpg' || $ext == 'gif') {
                    unlink($image_path);
                    $this->sendResponse(true);
                }
            }
            $this->sendResponse(false);
        }
        
        function local_patterns() {
            $localPatternsPath = $_REQUEST['localPatternsPath'];
            $this->__process_images($localPatternsPath, false);
            wp_die();
        }
        
        function upload_patterns() {
            $patternPath = $_REQUEST['patternPath'];
            $this->__process_upload($patternPath);
            wp_die();
        }
        
        function export_preview_image () {
            $uDraw = new uDraw();
            $user_session_id = uniqid();
            $page_no = 1;
            $preview_data = '';
            $outputPath = $_REQUEST['outputPath'];
            if (strlen($outputPath) == 0) { 
                $this->sendResponse(false);
            }
            $outputDir = $uDraw->get_physical_path($outputPath);
            if (!file_exists($outputDir)) {
                wp_mkdir_p($outputDir);
            }
            if (isset($_REQUEST['design_file']) && strlen($_REQUEST['design_file']) > 0) {
                if (strpos($_REQUEST['design_file'], '.xml') > 0) {
                    $user_session_id = basename($_REQUEST['design_file'], '.xml'); 
                }
            }
            if (isset($_REQUEST['user_session_id']) && strlen($_REQUEST['user_session_id']) > 0) {
                $user_session_id = $_REQUEST['user_session_id'];
            }
            if (isset($_REQUEST['page_no'])) {
                $page_no = $_REQUEST['page_no'];
            }
            if (isset($_REQUEST['data'])) {
                $preview_data = $_REQUEST['data'];
            }

            if ($this->startsWith($preview_data, "data:image")) {
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $preview_data));
                // Now that we have our image data, we'll write it to disk.
                if (!file_exists($outputDir . '/' . $user_session_id)) {
                    wp_mkdir_p($outputDir . '/' . $user_session_id);
                }
                $page_preview_handle = fopen($outputDir . '/' . $user_session_id . '/' . $page_no . '.png', "w");
                fwrite($page_preview_handle, $data);
                fclose($page_preview_handle);
                $this->sendResponse($outputPath . '/' . $user_session_id . '/' . $page_no . '.png');
            }
        }
        
        function upload_preview() {
            $unid_id = uniqid();
            if ($this->startsWith($_REQUEST['udraw_product_preview'], '<?xml')) {
                $udraw_preview_file = $unid_id . '_udp.svg';
                file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $udraw_preview_file, stripcslashes($_REQUEST['udraw_product_preview']));
                
                return $this->sendResponse(UDRAW_TEMP_UPLOAD_URL . $udraw_preview_file);
            } else if ($this->startsWith($_REQUEST['udraw_product_preview'], 'data:image')) {
                $udraw_preview_file = $unid_id . '_udp.jpg';
                $preview_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['udraw_product_preview']));
                file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $udraw_preview_file, $preview_data);                
                
                return $this->sendResponse(UDRAW_TEMP_UPLOAD_URL . $udraw_preview_file);
            }
            return $this->sendResponse("");
        }
        
        function extract_images_from_design($outputDir, $outputPath, $docname, $xmlStr) {
            // Extract Images from the design and store on file system.
            if (!file_exists($outputDir . '/' . $docname)) {
                wp_mkdir_p($outputDir . '/' . $docname);
            }
            $xmlPreviews = explode('preview="', $xmlStr);
            for ($x = 0; $x < count($xmlPreviews); $x++) {
                if ($this->startsWith($xmlPreviews[$x], "data:image")) {
                    $imgString = substr($xmlPreviews[$x], 0,strpos($xmlPreviews[$x],'"'));
                    $imgData = substr($imgString, strpos($xmlPreviews[$x], ',') + 1);
                    
                    // Now that we have our image data, we'll write it to disk.
                    $page_preview_handle = fopen($outputDir . '/' . $docname . '/' . $x . '.png', "w");
                    fwrite($page_preview_handle, base64_decode($imgData));
                    fclose($page_preview_handle);
                    $xmlStr = str_replace($imgString, $outputPath . '/' . $docname . '/' . $x . '.png', $xmlStr);
                }
            }
            
            return $xmlStr;
        }
        
        function save_page_data() {
            $uDraw = new uDraw();
            try {
                $outputPath = $_REQUEST['outputPath'];
                if (strlen($outputPath) == 0) { echo "false"; return;}
                $outputDir = $uDraw->get_physical_path($outputPath);
                
                $assetPath = $_REQUEST['assetPath'];
                if (strlen($assetPath) == 0) { echo "false"; return;}
                $assetDir = $uDraw->get_physical_path($assetPath);

                $docname = basename($_REQUEST['document'], '.xml'); 
                if (strlen($docname) == 0) { echo "false"; return; }
                
                // Make sure both folders exists.
                if (gettype($assetDir) == 'boolean' || gettype($outputDir) == 'boolean') {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "missing folders");
                    return;
                }
                
                // Check to see if page number and data was sent in request.
                if ( !strlen($_REQUEST['pageNo']) > 0 || !strlen($_REQUEST['pageData']) > 0 )  {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "No Docs Found");
                }
                
                $pageNo = $_REQUEST['pageNo'];
                $xml = $docname . '_' . $pageNo;                
                
                // Save Page Data
                $xml_handle = fopen($outputDir . '/' . $xml, "w");
                fwrite($xml_handle, base64_decode($_REQUEST['pageData']));
                fclose($xml_handle);

                // Save cropped page data if passed as well.
                if (strlen($_REQUEST['croppedPageData']) > 0)
                {
                    $xml_handle = fopen($outputDir . '/cropped_' . $xml, "w");
                    fwrite($xml_handle, base64_decode($_REQUEST['croppedPageData']));
                    fclose($xml_handle);
                }                
                
                echo "{\"response\": \"ok\"}";
            }
            catch (Exception $e) {
                echo $this->__generate_callBack("invalid", "invalid", "invalid", $e->getMessage());
            } 
            wp_die();
        }
        
        function compile_design_data() {
            $uDraw = new uDraw();
            try {
                $outputPath = $_REQUEST['outputPath'];
                if (strlen($outputPath) == 0) { echo "false"; return;}
                $outputDir = $uDraw->get_physical_path($outputPath);
                
                $assetPath = $_REQUEST['assetPath'];
                if (strlen($assetPath) == 0) { echo "false"; return;}
                $assetDir = $uDraw->get_physical_path($assetPath);
                
                $docname = basename($_REQUEST['document'], '.xml'); 
                if (strlen($docname) == 0) { echo "false"; return; }
                
                // Make sure both folders exists.
                if (gettype($assetDir) == 'boolean' || gettype($outputDir) == 'boolean') {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "missing folders");
                    return;
                }
                
                // Check to see xml and preview was sent in request.
                if ( !strlen($_REQUEST['canvasData']) > 0 || !strlen($_REQUEST['previewDoc']) > 0 || !strlen($_REQUEST['pageCount']) > 0 )  {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "No Docs Found");
                }                                
                
                $xml = $docname . '.xml';
                $preview = $docname . '/1.png';
                $pageCount = intval($_REQUEST['pageCount']);
                
                $xmlStr = base64_decode($_REQUEST['canvasData']);
                $xmlStrCropped = (!strlen($_REQUEST['canvasDataCropped']) > 0) ? "" : base64_decode($_REQUEST['canvasDataCropped']);
                // compile the design.
                $files_to_delete = [];
                for ($x = 1; $x <= $pageCount; $x++) {
                    if (is_file($outputDir . '/'. $docname .'_'. $x)) {
                        array_push($files_to_delete, $outputDir . '/'. $docname .'_'. $x);
                        $handle = fopen($outputDir . '/'. $docname .'_'. $x, "r") or die("Couldn't get handle");
                        if ($handle) {
                            while (!feof($handle)) {
                                $xmlStr .= fgets($handle, 4096);
                                // Process buffer here..
                            }
                            fclose($handle);
                        }
                    }
                    if (is_file($outputDir . '/cropped_'. $docname .'_'. $x)) {
                        array_push($files_to_delete, $outputDir . '/'. $docname .'_'. $x);
                        $handle = fopen($outputDir . '/cropped_'. $docname .'_'. $x, "r") or die("Couldn't get handle");
                        if ($handle) {
                            while (!feof($handle)) {
                                $xmlStrCropped .= fgets($handle, 4096);
                                // Process buffer here..
                            }
                            fclose($handle);
                        }
                    }                    
                }
                $xmlStr .= '</canvas>';
                $xmlStrCropped .= (!strlen($_REQUEST['canvasDataCropped']) > 0) ? "" : '</canvas>';

                // clean up, clean up, everyone do their share! :)
                for ($z = 0; $z < count($files_to_delete); $z++) {
                    if (is_file($files_to_delete[$z])) {
                        error_log('THESE FILES WERE DELETED');
                        error_log(print_r($files_to_delete[$z], true));
                        unlink($files_to_delete[$z]);
                    }
                }
                
                // Extract Images from the design and store on file system.
                if (!file_exists($outputDir . '/' . $docname)) {
                    wp_mkdir_p($outputDir . '/' . $docname);
                }
                $xmlPreviews = explode('preview="', $xmlStr);
                for ($x = 0; $x < count($xmlPreviews); $x++) {
                    if ($this->startsWith($xmlPreviews[$x], "data:image")) {
                        $imgString = substr($xmlPreviews[$x], 0,strpos($xmlPreviews[$x],'"'));
                        $imgData = substr($imgString, strpos($xmlPreviews[$x], ',') + 1);
                        
                        // Now that we have our image data, we'll write it to disk.
                        $page_preview_handle = fopen($outputDir . '/' . $docname . '/' . $x . '.png', "w");
                        fwrite($page_preview_handle, base64_decode($imgData));
                        fclose($page_preview_handle);
                        $xmlStr = str_replace($imgString, $outputPath . '/' . $docname . '/' . $x . '.png', $xmlStr);
                    }
                }
                
                // Save XML Document
                $xml_handle = fopen($outputDir . '/' . $xml, "w");
                fwrite($xml_handle, $xmlStr);
                fclose($xml_handle);
                if (strlen($xmlStrCropped) > 0) {
                    $xml_cropped_handle = fopen($outputDir . '/cropped_' . $xml, "w");
                    fwrite($xml_cropped_handle, $xmlStrCropped);
                    fclose($xml_cropped_handle);
                }
                
                // Save Preview Image
                $previewData = $_REQUEST['previewDoc'];
                if (!$this->endsWith(strtolower($previewData), "png")) {
                    $preview_handle = fopen($outputDir . '/' . $preview, "w");
                    fwrite($preview_handle, base64_decode($_REQUEST['previewDoc']));
                    fclose($preview_handle);
                } else {
                    $splitStr = explode("wp-content", $previewData);
                    $previewDir = WP_CONTENT_DIR . $splitStr[1];
                    if (file_exists($previewDir)) {
                        if( copy ($previewDir, $outputDir . '/' . $preview) ) {
                            unlink ($previewDir);
                        }
                    }
                }           

                $xmlCroppedRelativePath = (strlen($xmlStrCropped) > 0) ? 'cropped_' . $xml : "";   
                
                echo $this->__generate_callBack("-", $xml, $preview, "-", $xmlCroppedRelativePath);
            }
            catch (Exception $e) {
                echo $this->__generate_callBack("invalid", "invalid", "invalid", $e->getMessage());
            }
            
            wp_die();
        }    
        
        public function create_translation_file ($language = '', $display_name = '') {
            if (isset($_REQUEST['language'])) {
                $language = $_REQUEST['language'];
            }
            if (isset($_REQUEST['display_name'])) {
                $display_name = $_REQUEST['display_name'];
            } else {
                $display_name = $language;
            }
            $file_dir = $this->__get_translation_file_location($language)['directory'];
            $file_url = $this->__get_translation_file_location($language)['url'];
            //Using English file as base
            $base_file_dir = $this->__get_translation_file_location('en')['directory'];
            $base_file_contents = $this->__read_translation_file($base_file_dir);
            //Check that this file does not exist first
            if ((!file_exists($file_dir) || file_exists($file_dir) == '') && strlen($language) > 0) {
                $tr = new Stichoza\GoogleTranslate\TranslateClient('en', $language);
                $hasLanguageName = false;
                foreach($base_file_contents as $category=>$categoryValues) {
                    if (gettype($categoryValues) == 'object') {
                        $labelArray = array();
                        $textArray = array();
                        foreach($categoryValues as $key=>$value) {
                            array_push($labelArray, $key);
                            array_push($textArray, $value);
                            if ($key == 'languageName') {
                                $hasLanguageName = true;
                            }
                        }
                        $translatedArray = $tr->translate($textArray);
                        foreach($categoryValues as $key=>$value) {
                            for ($i = 0; $i < count($labelArray); $i++) {
                                if ($key == $labelArray[$i]) {
                                    $newValue = $translatedArray[$i];
                                    $base_file_contents->$category->$key = htmlspecialchars($newValue);
                                }
                            }
                            if ($key == 'languageName') {
                                $hasLanguageName = true;
                            }
                        }
                    } else if (gettype($categoryValues) == 'string') {
                        $newValue = $tr->translate($categoryValues);
                        $base_file_contents->$categoryValues = htmlspecialchars($newValue);
                        if ($categoryValues == 'languageName') {
                            $hasLanguageName = true;
                        }
                    }
                }
                if (!$hasLanguageName) { $base_file_contents->languageName = $display_name; }
                file_put_contents($file_dir, json_encode($base_file_contents));
                $response = $file_url;
            } else {
                $response = false;
            }
            return $this->sendResponse($response);
        }
        
        public function retrieve_translation_file_contents ($language = '') {
            if (isset($_REQUEST['language'])) {
                $language = $_REQUEST['language'];
            }
            $file_dir = $this->__get_translation_file_location($language)['directory'];
            return $this->sendResponse($this->__read_translation_file($file_dir));
        }
        
        public function edit_translation_file ($language = '', $editedContents = '') {
            if (isset($_REQUEST['language']) && isset($_REQUEST['file_contents'])) {
                $language = $_REQUEST['language'];
                $editedContents = json_decode(stripcslashes($_REQUEST['file_contents']));
            }
            $file_dir = $this->__get_translation_file_location($language)['directory'];
            
            if (!file_exists($file_dir) || file_exists($file_dir) == '') {
                return $this->sendResponse(false);
            }
            foreach($editedContents as $category=>$categoryValues) {
                if (gettype($categoryValues) == 'object') {
                    foreach($categoryValues as $key=>$value) {
                        $newValue = urldecode($value);
                        $editedContents->$category->$key = $newValue;
                    }
                } else if (gettype($categoryValues) == 'string') {
                    $newValue = urldecode($categoryValues);
                    $editedContents->$categoryValues = $newValue;
                }
            }
            $language_file = UDRAW_LANGUAGES_DIR . 'udraw-'. $language .'.txt';
            file_put_contents($language_file, json_encode($editedContents));
            return $this->sendResponse($editedContents);
        }
        
        public function update_translation_files () {
            //Get English file
            $base_file_dir = $this->__get_translation_file_location('en')['directory'];
            $base_file_contents = $this->__read_translation_file($base_file_dir);
            //Get all locale files
            $localeDirectory = dir(UDRAW_PLUGIN_DIR.'/designer/includes/locales/');
            $languageDirectory = dir(UDRAW_LANGUAGES_DIR);
            $availableLanguages = array();
            while(false !== $entry = $localeDirectory->read()) {
                if ($entry != '.' && $entry != '..') {
                    $currentLanguage = str_replace(array('udraw-', '.txt'), '', $entry);
                    array_push($availableLanguages, $currentLanguage);
                }
            }
            while(false !== $entry = $languageDirectory->read()) {
                if ($entry != '.' && $entry != '..') {
                    $currentLanguage = str_replace(array('udraw-', '.txt'), '', $entry);
                    array_push($availableLanguages, $currentLanguage);
                }
            }
            foreach ($availableLanguages as $language) {
                $tr = new Stichoza\GoogleTranslate\TranslateClient('en', $language);
                if ($language != 'en') {
                    $current_file_dir = $this->__get_translation_file_location($language)['directory'];
                    $current_file_contents = $this->__read_translation_file($current_file_dir);
                    $needsUpdate = false;
                    //Loop through the base file and check if all tags exist in the translated file, if not, create it and translate the value
                    foreach($base_file_contents as $category=>$categoryValues) {
                        if (gettype($base_file_contents->$category) == 'object') {
                            if (!property_exists($current_file_contents, $category) || property_exists($current_file_contents, $category) == '') {
                                $current_file_contents->$category = new stdClass();
                            }
                            $labelArray = array();
                            $textArray = array();
                            foreach($categoryValues as $key=>$value) {
                                if (!property_exists($current_file_contents->$category, $key) || property_exists($current_file_contents->$category, $key) == '') {
                                    $needsUpdate = true;
                                    array_push($labelArray, $key);
                                    array_push($textArray, $value);
                                }
                            }
                            $translatedArray = $tr->translate($textArray);
                            foreach($categoryValues as $key=>$value) {
                                for ($i = 0; $i < count($labelArray); $i++) {
                                    if ($key == $labelArray[$i]) {
                                        $newValue = $translatedArray[$i];
                                        $current_file_contents->$category->$key = htmlspecialchars($newValue);
                                    }
                                }
                            }
                        } else if (gettype($base_file_contents->$category) == 'string') {
                            if (!property_exists($current_file_contents, $category) || property_exists($current_file_contents, $category) == '') {
                                if ($category == 'languageName') {
                                    $current_file_contents->$category = $language;
                                } else {
                                    $current_file_contents->$category = htmlspecialchars($tr->translate($base_file_contents->$category));
                                }
                            }
                        }
                    }
                    if ($needsUpdate) {
                        file_put_contents($current_file_dir, json_encode($current_file_contents));
                    }
                }
            }
            return $this->sendResponse(true);
        }
        
        public function authenticate_instagram() {
            echo "<script>var access_token = location.hash.replace('#access_token=','');</script>";
            return $this->sendResponse(true);
        }
		
        public function retrieve_instagram() {
            $access_token = (isset($_REQUEST['access_token'])) ? $_REQUEST['access_token'] : '';
            $term = (isset($_REQUEST['term'])) ? $_REQUEST['term'] : '';
            //If there is a search term
            if (strlen($term) > 0) {
                $data = json_decode(file_get_contents('https://api.instagram.com/v1/tags/'.$term.'/media/recent?access_token='.$access_token));
            } else {
                $data = json_decode(file_get_contents('https://api.instagram.com/v1/users/self/media/recent?access_token='.$access_token));
            }
            
            return $this->sendResponse($data);
        }
        
        public function authenticate_flickr() {
            $uDrawSettings = new uDrawSettings();
            $udraw_settings = $uDrawSettings->get_settings();
            //Destroy previous sessions, and start a new one
            if (version_compare(PHP_VERSION, '5.4.0', '<')) {
                if(session_id() !== '') {
                    session_destroy();
                }
            } else {
                if (session_status() !== PHP_SESSION_NONE) {
                    session_destroy();
                }
            }
            session_start();
            $api_key = $udraw_settings['designer_flickr_client_id'];
            $secret = $udraw_settings['designer_flickr_secret_id'];
            $timestamp = time();
            $signature_method = 'HMAC-SHA1';
            $nonce = bin2hex(mt_rand());
            $version = '1.0';
            $callback_url = admin_url('admin-ajax.php') . '?action=udraw_designer_flickr_access_token';

            $secret_key = $secret . '&';

            $oAuth_params = array(
                'oauth_callback' => $callback_url,
                'oauth_consumer_key' => $api_key,
                'oauth_nonce' => $nonce,
                'oauth_signature_method' => $signature_method,
                'oauth_timestamp' => $timestamp,
                'oauth_version' => $version
            );

            $sign_string = base64_encode(hash_hmac('sha1', 'GET&http%3A%2F%2Fwww.flickr.com%2Fservices%2Foauth%2Frequest_token&' . urlencode(http_build_query($oAuth_params)), $secret_key, true));

            $oAuth_params['oauth_signature'] = $sign_string;

            $oauth_callback_string = file_get_contents('http://www.flickr.com/services/oauth/request_token?' . http_build_query($oAuth_params));
            $oauth_callback_array = explode('&', $oauth_callback_string);
            $oauth_token = '';
            $oauth_token_secret = '';
            for ($i = 0; $i < count($oauth_callback_array); $i++) {
                $oauth_callback_array[$i] = explode('=', $oauth_callback_array[$i]);
                if ($oauth_callback_array[$i][0] === "oauth_token") {
                    $oauth_token = $oauth_callback_array[$i][1];
                }
                if ($oauth_callback_array[$i][0] === "oauth_token_secret") {
                    $oauth_token_secret = $oauth_callback_array[$i][1];
                }
            }
            $_SESSION['oauth_token_secret'] = $oauth_token_secret;
            echo json_encode('https://www.flickr.com/services/oauth/authorize?oauth_token=' . $oauth_token . '&perms=read');
        }

        public function flickr_access_token($oauth_token = '', $oauth_verifier = '', $oauth_token_secret = '') {
            $uDrawSettings = new uDrawSettings();
            $udraw_settings = $uDrawSettings->get_settings();
            if (version_compare(PHP_VERSION, '5.4.0', '<')) {
                if(session_id() == '') {
                    session_start();
                }
            } else {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
            }
            if (isset($_GET['oauth_token'])) {
                $oauth_token = $_GET['oauth_token'];
            }
            if (isset($_GET['oauth_verifier'])) {
                $oauth_verifier = $_GET['oauth_verifier'];
            }
            if (isset($_SESSION['oauth_token_secret'])) {
                $oauth_token_secret = $_SESSION['oauth_token_secret'];
            }
            $api_key = $udraw_settings['designer_flickr_client_id'];
            $secret = $udraw_settings['designer_flickr_secret_id'];
            $timestamp = time();
            $signature_method = 'HMAC-SHA1';
            $nonce = bin2hex(mt_rand());
            $version = '1.0';
            $secret_key = $secret . '&' . $oauth_token_secret;
            $oAuth_params = array(
                'oauth_consumer_key' => $api_key,
                'oauth_nonce' => $nonce,
                'oauth_signature_method' => $signature_method,
                'oauth_timestamp' => $timestamp,
                'oauth_token' => $oauth_token,
                'oauth_verifier' => $oauth_verifier,
                'oauth_version' => $version
            );
            $sign_string = base64_encode(hash_hmac('sha1', 'GET&http%3A%2F%2Fwww.flickr.com%2Fservices%2Foauth%2Faccess_token&' . urlencode(http_build_query($oAuth_params)), $secret_key, true));
            $oAuth_params['oauth_signature'] = $sign_string;
            $user_string = file_get_contents('http://www.flickr.com/services/oauth/access_token?' . http_build_query($oAuth_params));
            $user_array = explode('&', $user_string);
            for ($i = 0; $i < count($user_array); $i++) {
                $user_array[$i] = explode('=', $user_array[$i]);
            }
            //echo json_encode($user_array);
            echo '<script>var auth_string = ' . json_encode($user_array) . '; </script>';
            echo '<span style="font-size: 2em; color: green;">Thank you for logging in.</span>';
        }

        public function flickr_get ($oauth_token = '', $oauth_token_secret = '', $method = '', $user_id = '', $photoset_id = '') {
            $uDrawSettings = new uDrawSettings();
            $udraw_settings = $uDrawSettings->get_settings();
            if (isset($_REQUEST['oauth_token'])) {
                $oauth_token = $_REQUEST['oauth_token'];
            }
            if (isset($_REQUEST['oauth_token_secret'])) {
                $oauth_token_secret = $_REQUEST['oauth_token_secret'];
            }
            if (isset($_REQUEST['method'])) {
                $method = $_REQUEST['method'];
            }
            if (isset($_REQUEST['user_id'])) {
                $user_id = $_REQUEST['user_id'];
            }
            if (isset($_REQUEST['photoset_id'])) {
                $photoset_id = $_REQUEST['photoset_id'];
            }
            $api_key = $udraw_settings['designer_flickr_client_id'];
            $secret = $udraw_settings['designer_flickr_secret_id'];
            $timestamp = time();
            $signature_method = 'HMAC-SHA1';
            $nonce = bin2hex(mt_rand());
            $version = '1.0';

            $secret_key = $secret . '&' . $oauth_token_secret;

            $oAuth_params = array(
                'format' => 'json',
                'method' => $method,
                'nojsoncallback' => 1,
                'oauth_consumer_key' => $api_key
            );
            if (strlen($photoset_id) > 0) { $oAuth_params['photoset_id'] = $photoset_id; }
            $oAuth_params['oauth_nonce'] = $nonce;
            $oAuth_params['oauth_signature_method'] = $signature_method;
            $oAuth_params['oauth_timestamp'] = $timestamp;
            $oAuth_params['oauth_token'] = $oauth_token;
            if (strlen($user_id) > 0) { $oAuth_params['user_id'] = $user_id; }
            //$oAuth_params['user_id'] = '66956608@N06'; //Official Flickr account's user_id
            $oAuth_params['oauth_version'] = $version;

            $sign_string = base64_encode(hash_hmac('sha1', 'GET&https%3A%2F%2Fapi.flickr.com%2Fservices%2Frest&' . urlencode(http_build_query($oAuth_params)), $secret_key, true));

            $oAuth_params['oauth_signature'] = $sign_string;

            echo json_encode(file_get_contents('https://api.flickr.com/services/rest?' . http_build_query($oAuth_params)));
        }
        
        public function create_product_xml_pages ($path_url = '', $xml_array = array()) {
            if (isset($_REQUEST['folder_path']) && isset($_REQUEST['xml_array'])) {
                $path_url = $_REQUEST['folder_path'];
                $xml_array = $_REQUEST['xml_array'];
            } else {
                return $this->sendResponse(false);
            }
            $path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $path_url);
            //Create the folder if it doesn't exist
            if (!file_exists($path)) {
                wp_mkdir_p($path);
            } else {
                //Clean out the folder before adding in new files                
                array_map('unlink', glob($path . '*'));
                error_log('Folder Deleted [ ' . $path . ' ]');
            }
            
            $file_array = array();
            for ($i = 0; $i < count($xml_array); $i++) {
                $result = file_put_contents($path . $i . '.xml', base64_decode($xml_array[$i]));
                if ($result) {
                    array_push($file_array, $path_url . $i . '.xml');
                }
            } 
            return $this->sendResponse($file_array);
        }
        
        public function retrieve_page_xml_files ($path_url = '') {
            if (isset($_REQUEST['folder_path'])) {
                $path_url = $_REQUEST['folder_path'];
            }
            $path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $path_url);
            if (!file_exists($path)) {
                wp_mkdir_p($path);
            }
            $files_found = glob($path . '*');
            $files_array = array();
            for ($i = 0; $i < count($files_found); $i++) {
                $file_parts = pathinfo($files_found[$i]);
                if ($file_parts['extension'] === 'xml') {
                    array_push($files_array, str_replace(UDRAW_STORAGE_DIR, UDRAW_STORAGE_URL, $files_found[$i]));
                }
            }
            return $this->sendResponse($files_array);
        }
        
        public function create_page_xml ($path_url = '', $xml = '', $page_no = 0) {
            if (isset($_REQUEST['folder_path']) && isset($_REQUEST['xml']) && isset($_REQUEST['page_no'])) {
                $path_url = $_REQUEST['folder_path'];
                $xml = base64_decode($_REQUEST['xml']);
                $page_no = $_REQUEST['page_no'];
            }
            $path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $path_url);
            if (!file_exists($path)) {
                wp_mkdir_p($path);
            }
            $result = file_put_contents($path . $page_no . '.xml', $xml);
            if ($result) {
                return $this->sendResponse($path_url . $page_no . '.xml');
            } else {
                return $this->sendResponse(false);
            }
        }
        
        public function create_merged_xml ($path_url = '', $canvas_header = '', $pages_length = 1, $cart_path_url = '') {
            if (isset($_REQUEST['folder_path']) && isset($_REQUEST['canvas_header'])) {
                $path_url = $_REQUEST['folder_path'];
                $canvas_header = base64_decode($_REQUEST['canvas_header']);
            } else {
                return $this->sendResponse(false);
            }
            if (isset($_REQUEST['cart_folder_path'])) {
                $cart_path_url = $_REQUEST['cart_folder_path'];
            } else {
                $cart_path_url = $path_url;
            }
            if (isset($_REQUEST['pages_length'])) {
                $pages_length = $_REQUEST['pages_length'];
            }
            $path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $path_url);
            $cart_path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $cart_path_url);
            if (!file_exists($path)) {
                wp_mkdir_p($path);
            }
            
            if (!file_exists($cart_path)) {
                wp_mkdir_p($cart_path);
            }
            $files_found = glob($path . '*');
            $files_array = array();
            if (count($files_found) > 0) {
                for ($i = 0; $i < count($files_found); $i++) {
                    $file_parts = pathinfo($files_found[$i]);
                    if ($file_parts['extension'] === 'xml') {
                        array_push($files_array, $files_found[$i]);
                    }
                }
            }
            if (count($files_array) > 0) {
                $string = $canvas_header;
                for ($j = 0; $j < $pages_length; $j++) {
                    $string .= file_get_contents($path . $j . '.xml');
                }
                $string .= '</canvas>';
                $result = file_put_contents($cart_path . 'design.xml', $string);
                if ($result) {
                    return $this->sendResponse($cart_path_url . 'design.xml');
                } else {
                    return $this->sendResponse(false);
                }
            }
            return $this->sendResponse(false);
        }
        
        public function remove_xml_files ($path_url = '', $pages_length = 1) {
            if (isset($_REQUEST['folder_path'])) {
                $path_url = $_REQUEST['folder_path'];
            } else {
                return $this->sendResponse(false);
            }
            if (isset($_REQUEST['pages_length'])) {
                $pages_length = $_REQUEST['pages_length'];
            }
            $path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $path_url);
            $files_found = glob($path . '*');
            for ($i = 0; $i < count($files_found); $i++) {
                $file_parts = pathinfo($files_found[$i]);
                if ($file_parts['extension'] === 'xml') {
                    //remove only <pageNo>.xml
                    for ($p = 0; $p < $pages_length; $p++) {
                        if ($file_parts['filename'] === $p) {
                            unlink($files_found[$i]);
                        }
                    }
                }
            }
            return $this->sendResponse(true);
        }
        
        function udraw_send_saved_design_link ($email = '', $design_url = '', $preview_url = '', $date = '', $product_id = 0) {
            if (isset($_REQUEST['email'])) {
                $email = esc_attr($_REQUEST['email']);
            }
            if (isset($_REQUEST['design_url'])) {
                $design_url = $_REQUEST['design_url'];
            }
            if (isset($_REQUEST['preview_url'])) {
                $preview_url = $_REQUEST['preview_url'];
            }
            if (isset($_REQUEST['date'])) {
                $date = $_REQUEST['date'];
            } else {
                $dt = new DateTime();
                $date = $dt->format('Y-m-d');
            }
            if (isset($_REQUEST['product_id'])) {
                $product_id = intval($_REQUEST['product_id']);
            }
            $product = wc_get_product( $product_id );
            $product_name = $product->get_title();
            
            //Some styles
            $bg_colour              = get_option( 'woocommerce_email_background_color' );
            $bg_darker_10           = wc_hex_darker( $bg_colour, 10 );
            $base_colour            = get_option( 'woocommerce_email_base_color' );
            $base_darker_10         = wc_hex_darker( $base_colour, 10 );
            $base_text_colour       = wc_light_or_dark( $base_colour, '#202020', '#ffffff' );
            $text_colour            = get_option( 'woocommerce_email_text_color' );
            function set_wp_mail_content_type(){
                return "text/html";
            }
            add_filter( 'wp_mail_content_type','set_wp_mail_content_type' );
            $header = '<div style="width: 50%; margin: auto; color: ' . $base_text_colour . '">' . 
                        '<div style="padding: 30px; background-color: ' . $base_colour . '; border: 1px solid ' . $base_darker_10 . ';"><h1>' . __('Your Saved Design', 'udraw') . '</h1></div>';
            $email_body = '<div style="padding: 30px; border: 1px solid ' . $bg_darker_10 . '; margin-top: -1px; color: ' . $text_colour . ';">'.
                            '<p>' . __('We know that this personalized gift is important to you and you want to get it just right. Take your time, show your family and friends, and then come back and pick up where you left off!', 'udraw') . '</p>' .
                            '<p>' . __('Click on the image below to continue editing your design.', 'udraw') . '</p>'.
                            '<p><a href="' . $design_url . '"><img src="' . $preview_url . '" style="max-width: 250px; max-height: 250px;"></a></p>'.
                          '</div>';
            $footer = '</div>';
            
            $content = $header . $email_body . $footer;
            
            $result = false;
            if (is_email($email)) {
                $result = wp_mail($email, 'Your Saved Design from ' . get_bloginfo('name') . ' on ' . $date, $content);
            }
            remove_filter( 'wp_mail_content_type','set_wp_mail_content_type' );
            return $this->sendResponse($content);
        }
        
        function convert_pdf($key = '', $type = '', $pdf_action = '', $file = '', $pageNo = 1) {
            if (isset($_REQUEST['key']) && isset($_REQUEST['type']) && isset($_REQUEST['action']) && isset($_REQUEST['document'])) {
                $key = $_REQUEST['key'];
                $type = $_REQUEST['type'];
                $pdf_action = $_REQUEST['pdf_action'];
                $file = $_REQUEST['document'];
            }
			
            $pageNo = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;

            $uDrawUtil = new uDrawUtil();
            
            if ( $type == 'png' && $pdf_action == 'convert') {
                $preview_file = uniqid('udraw_') . '.png';
                $uDrawUtil->download_file(UDRAW_CONVERT_URL."key=".$key."&type=".$type."&action=".$pdf_action."&document=".urlencode($file).'&page='.$pageNo,UDRAW_TEMP_UPLOAD_DIR . $preview_file);
                echo UDRAW_TEMP_UPLOAD_URL . $preview_file;
				wp_die();
            } else if ($type == 'pdfbw' && $pdf_action == 'convert') {
				$data = array(
					'pdfDocument' => $file,
					'key' => uDraw::get_udraw_activation_key(),
				);
				$headers = array(
					"Content-Type" => "application/x-www-form-urlencoded"
				);
				$output = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2BW', http_build_query($data), $headers)); 
				if ($output->isSuccess) {
					if ($output->data) {
						$temp_file = uniqid('udraw_') . '.pdf';
						$uDrawUtil->download_file(UDRAW_CONVERT_SERVER_URL . $output->data[0], UDRAW_TEMP_UPLOAD_DIR . $temp_file);
						echo UDRAW_TEMP_UPLOAD_URL . $temp_file;
					}
				}
				wp_die();				
			} else if ($type == 'pdf' && $pdf_action == 'inkcov'){
				$data = array(
					'pdfDocument' => $file,
					'key' => uDraw::get_udraw_activation_key(),
				);
				$headers = array(
					"Content-Type" => "application/x-www-form-urlencoded"
				);
				$output = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDFInfo', http_build_query($data), $headers)); 
				if ($output->isSuccess) {
					$previewImage = uniqid('preview');
					if ($output->data) {
						$uDrawUtil->download_file(UDRAW_CONVERT_SERVER_URL . $output->data->previewURL, UDRAW_TEMP_UPLOAD_DIR . $previewImage . '.png');
						$output->data->previewURL = UDRAW_TEMP_UPLOAD_URL . $previewImage . '.png';
					}
				}    
                return $this->sendResponse($output);
            } else {
                $output = $uDrawUtil->get_web_contents(UDRAW_CONVERT_URL."key=".$key."&type=".$type."&action=".$pdf_action."&document=".urlencode($file).'&page='.$pageNo);
                return $this->sendResponse($output);
            }
        }
        
        public function export_image ($dataURL = '', $assetPath = '') {
            $uDraw = new uDraw();
            $user_session_id = uniqid();
            $page_no = 1;
            $preview_data = '';
            $assetPath = $_REQUEST['assetPath'];
            if (strlen($assetPath) == 0) { 
                $this->sendResponse(false);
            }
            $assetDir = $uDraw->get_physical_path($assetPath);
            if (isset($_REQUEST['dataURL'])) {
                $dataURL = $_REQUEST['dataURL'];
            }
            if (strlen($dataURL) === 0) {
                $this->sendResponse(false);
            }
            
            $image_name = uniqid('image_');
            if ($this->startsWith($dataURL, "data:image")) {
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataURL));
                $pos  = strpos($dataURL, ';');
                $type = str_replace('image/', '', explode(':', substr($dataURL, 0, $pos))[1]);
                // Now that we have our image data, we'll write it to disk.
                if (!file_exists($assetDir)) {
                    wp_mkdir_p($assetDir);
                }
                $image_handle = fopen($assetDir . '/' . $image_name . '.' . $type, "w");
                fwrite($image_handle, $data);
                fclose($image_handle);
                $this->sendResponse($assetPath . '/' . $image_name . '.' . $type);
            }
        }
        
        public function get_text_templates () {
            global $wpdb;
            $table = $wpdb->prefix . 'udraw_text_templates';
            $results = $wpdb->get_results("SELECT ID,json,preview,tags FROM $table", ARRAY_A);
            $this->sendResponse($results);
        }
        
        //////////////////////////////////////
        // Private Methods                  //
        //////////////////////////////////////
        
        private function __process_upload($assetPath) {
            $uDraw = new uDraw();
            $uDrawUpload = new uDrawUpload();
            
            if (strlen($assetPath) == 0) { echo "false"; return;}

            $assetDir = $uDraw->get_physical_path($assetPath);
            
            $assetBaseURL = $this->getBaseURL();
            
            // Check file exstension
            $fileName = pathinfo($_FILES['files']['name'][0], PATHINFO_FILENAME);
            $fileExt = strtolower(pathinfo($_FILES['files']['name'][0], PATHINFO_EXTENSION));
            
            // New Filename
            $newFile = rand(1, 32) .'_'. str_replace(' ','', $fileName) . '.' . $fileExt;
            
            $accepted_file_list = ['pdf', 'eps', 'jpg', 'jpeg', 'png', 'svg', 'gif'];
            $accepted_file_list = apply_filters('udraw_designer_accepted_image_file_types', $accepted_file_list);
            for ($f = count($accepted_file_list) - 1; $f > 0; $f--) {
                //Check that nothing malicious was added
                if (!in_array($accepted_file_list[$f], ['pdf', 'eps', 'jpg', 'jpeg', 'png', 'svg', 'gif'])) {
                    unset($accepted_file_list[$f]);
                }
            }
            // See if we can convert the file before dismissing it.
            if (($fileExt == 'pdf' || $fileExt == 'eps') && in_array($fileExt, $accepted_file_list)) {
                if (strlen(uDraw::get_udraw_activation_key()) > 0) {
                    // Save EPS/PDF locally to the sysetm.
                    $uploaded_files = $uDrawUpload->handle_upload($_FILES['files'], $assetDir, $assetBaseURL . $assetPath, array('pdf' => 'application/pdf', 'eps' => 'application/postscript') );
                    
                    if (is_array($uploaded_files)) {
                        if ( !key_exists('error', $uploaded_files[0]) ) {
                            // Pass the PDF document to remote converting server and convert to SVG document.
                            $url_array = array();
                            $new_file_array = array();
                            $uDrawUtil = new uDrawUtil();
                                
                            if ($fileExt == "eps") {
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

                            $endpoint_action = ($fileExt == "eps") ? "EPS2PNG" : "PDF2PNG";
                            //$output_file_ext = ($fileExt == "eps") ? ".png" : ".svg";
							$output_file_ext = ".png";

                            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/' . $endpoint_action, http_build_query($data)));

                            if ($udraw_convert_response->isSuccess) {
                                for ($i = 0; $i < count($udraw_convert_response->data); $i++) {
                                    array_push($url_array, UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$i]);
                                }                                    
                            } else {
                                echo "false"; wp_die();
                            } 
                            
                            for ($i = 0; $i < count($url_array); $i++) {
                                $new_file = $newFile . '_page_' . ($i + 1) . $output_file_ext;
                                $this->downloadFile($url_array[$i], $assetDir . '/'. $new_file);
                                array_push($new_file_array, $new_file);
                            }
                            
                            // Once new SVG document is saved, we can remove the uploaded PDF document.
                            unlink($uploaded_files[0]['file']);
                            
                            // return back the new SVG file name.
                            echo json_encode($new_file_array);
                            wp_die();
                        }
                    }   
                }
            } else {
                if ($fileExt != 'jpg' && $fileExt != 'jpeg' && $fileExt != 'png' && $fileExt != 'gif' && $fileExt != 'svg') {
                    echo "false"; wp_die();
                } else if (in_array($fileExt, $accepted_file_list)) {                    
                    $uploaded_files = $uDrawUpload->handle_upload($_FILES['files'], $assetDir, $assetBaseURL . $assetPath);
                    if (is_array($uploaded_files)) {
                        if ( !key_exists('error', $uploaded_files[0]) ) {
                            // move file to a more unique name.
                            rename($uploaded_files[0]['file'], $assetDir . '/' . $newFile);
                            
                            // Try to look and detect malformed SVG documents that can mark the design "dirty".
                            if ($fileExt == 'svg') {
                                $handle = fopen($assetDir . '/'. $newFile, 'r');
                                $isMalformed = false;
                                while (($buffer = fgets($handle)) !== false) {
                                    if ( strpos($buffer, "<foreignObject") !== false ) {
                                        $isMalformed = true;
                                        break; 
                                    }
                                }
                                fclose($handle);
                                
                                if ($isMalformed) {
                                    $svg_file = file_get_contents($assetDir . '/'. $newFile);
                                    
                                    if ( strpos($svg_file, "<foreignObject") !== false ) {
                                        // remove foreignObject from SVG
                                        $start = "<foreignObject";
                                        $end = "foreignObject>";
                                        $start_idx = strpos($svg_file, $start);
                                        $end_idx = strpos($svg_file, $end, $start_idx+strlen($start));
                                        $svg_file = substr_replace($svg_file,'', $start_idx, $end_idx-$start_idx+strlen($end));
                                    }
                                    
                                    sleep(1);
                                    error_log('MALFORMED SVGS');
                                    error_log(print_r($assetDir . '/'. $newFile, true));
                                    unlink($assetDir . '/'. $newFile);
                                    $newFile = '/udraw_'. $newFile;
                                    file_put_contents($assetDir . $newFile, $svg_file);
                                }
                            }
                            
                            // return new name.
                            echo $newFile; wp_die();                          
                        }
                    }
                }
            }
            
            // default return false.
            echo "false"; wp_die();            
        }
        
        private function __process_images($localImagePath, $includeDir) {
            $uDraw = new uDraw();
            if (strlen($localImagePath) == 0) { echo "false"; return; }
            $localImageDir = $uDraw->get_physical_path($localImagePath);
            
            $response = array();
            
            if ($includeDir) {
                // Directories in top level.
                $directories = glob($localImageDir . '/*', GLOB_ONLYDIR);
                foreach($directories as $directory) {
                    $_rel_dir_path = str_replace(get_home_path(),'', preg_replace("@[/\\\]@", "/", $directory));
                    if (strlen($uDraw->get_virtual_path()) > 0) {
                        $_rel_dir_path = $uDraw->get_virtual_path() . '/' . $_rel_dir_path;
                    }
                    $_rel_dir_path = str_replace("\\","/",$_rel_dir_path);  
                    if ($_rel_dir_path[0] !== '/') {
                        $_rel_dir_path = '/' . $_rel_dir_path;
                    }
                    $_item = new uDrawHandler_LocalImages("folder", basename($_rel_dir_path), "", $_rel_dir_path);
                    array_push($response, $_item);
                }
            }
            
            // files in top level
            $files = glob($localImageDir . '/*.{png,jpg,jpeg,gif,pdf,eps,svg,tiff,tif}', GLOB_BRACE);
            usort($files, function($a,$b){
              return filemtime($b) - filemtime($a);
            });
            foreach($files as $file) {
                $_rel_file_path = str_replace(get_home_path(),'', preg_replace("@[/\\\]@", "/",$file));
                if (strlen($uDraw->get_virtual_path()) > 0) {
                    $_rel_file_path = $uDraw->get_virtual_path() . '/' . $_rel_file_path;
                }
                $_rel_file_path = str_replace("\\","/",$_rel_file_path);
                if ($_rel_file_path[0] !== '/') {
                    $_rel_file_path = '/' . $_rel_file_path; 
                }
                
                if (strpos($localImagePath, SITE_CDN_DOMAIN) !== false) {
                    $_rel_file_path = str_replace('/srv/htdocs', SITE_CDN_DOMAIN, $_rel_file_path);
                }
                $_item = new uDrawHandler_LocalImages("file", basename($_rel_file_path), ".".pathinfo($file, PATHINFO_EXTENSION), $_rel_file_path);
                
                array_push($response, $_item);
            }
            
			echo json_encode($response);
        }
        
        /**
         * Returns String if empty or array of woff fonts based on path.
         * 
         * @return array|string
         */
        private function __process_fonts() 
        {
            $uDraw = new uDraw();
            $localFontPath = $_REQUEST['localFontPath'];
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
        
        /**
         * Saves the uDraw designer files to the filesystem.
         * 
         * @param boolean $includePDF 
         * @return void
         */        
        private function __process_save($includePDF) {
            $uDraw = new uDraw();
            try {       
                $outputPath = $_REQUEST['outputPath'];
                if (strlen($outputPath) == 0) { echo "false"; return;}
                $outputDir = $uDraw->get_physical_path($outputPath);
                
                $assetPath = $_REQUEST['assetPath'];
                if (strlen($assetPath) == 0) { echo "false"; return;}
                $assetDir = $uDraw->get_physical_path($assetPath);
                
                // Make sure both folders exists.
                if (gettype($assetDir) == 'boolean' || gettype($outputDir) == 'boolean') {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "missing folders");
                    return;
                }
                // Check to see xml and preview was sent in request.
                if ( !strlen($_REQUEST['xmlDoc']) > 0 || !strlen($_REQUEST['previewDoc']) > 0 )  {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "No Docs Found");
                }
                
                if ($includePDF) {
                    if (!strlen($_REQUEST['pdfDoc']) > 0) {
                        echo $this->__generate_callBack("invalid", "invalid", "invalid", "No PDF Found");
                    }
                }
                
                $docname = uniqid();
                if (strlen($_REQUEST['document']) > 0) {
                    if (strpos($_REQUEST['document'], '.xml') > 0) {
                        $docname = basename($_REQUEST['document'], '.xml'); 
                    }
                }
                
                $xml = $docname . '.xml';
                $preview = $docname . '/1.png';
                $pdf = $docname . '.pdf';
                
                // Extract Images from the design and store on file system.
                //$xmlStr = $this->extract_images_from_design($outputDir, $outputPath, $docname, base64_decode($_REQUEST['xmlDoc']));

                $xmlStr = base64_decode($_REQUEST['xmlDoc']);
                
                // Extract Images from the design and store on file system.
                if (!file_exists($outputDir . '/' . $docname)) {
                //if (!is_file($outputDir . '/' . $docname)) {
                    mkdir($outputDir . '/' . $docname);
                }
                $xmlPreviews = explode('preview="', $xmlStr);
                for ($x = 0; $x < count($xmlPreviews); $x++) {
                    if ($this->startsWith($xmlPreviews[$x], "data:image")) {
                        $imgString = substr($xmlPreviews[$x], 0,strpos($xmlPreviews[$x],'"'));
                        $imgData = substr($imgString, strpos($xmlPreviews[$x], ',') + 1);
                        
                        // Now that we have our image data, we'll write it to disk.
                        $page_preview_handle = fopen($outputDir . '/' . $docname . '/' . $x . '.png', "w");
                        fwrite($page_preview_handle, base64_decode($imgData));
                        fclose($page_preview_handle);
                        $xmlStr = str_replace($imgString, $outputPath . '/' . $docname . '/' . $x . '.png', $xmlStr);
                    }
                }

                // Save XML Document
                $xml_handle = fopen($outputDir . '/' . $xml, "w");
                fwrite($xml_handle, base64_decode($_REQUEST['xmlDoc']));
                fclose($xml_handle);
                
                // Save Preview Image
                $previewData = $_REQUEST['previewDoc'];
                if (!$this->endsWith(strtolower($previewData), "png")) {
                    $preview_handle = fopen($outputDir . '/' . $preview, "w");
                    fwrite($preview_handle, base64_decode($_REQUEST['previewDoc']));
                    fclose($preview_handle);
                } else {
                    $splitStr = explode("wp-content", $previewData);
                    $previewDir = WP_CONTENT_DIR . $splitStr[1];
                    if (file_exists($previewDir)) {
                        if( copy ($previewDir, $outputDir . '/' . $preview) ) {
                            unlink ($previewDir);
                        }
                    }
                }
                
                if ($includePDF) {
                    // Save PDF Document
                    $pdf_handle = fopen($outputDir . '/' . $pdf, "w");
                    fwrite($pdf_handle, base64_decode($_REQUEST['pdfDoc']));
                    fclose($pdf_handle);
                }
                
                if ($includePDF) {
                    echo $this->__generate_callBack($pdf, $xml, $preview, "-");
                } else {
                    echo $this->__generate_callBack("-", $xml, $preview, "-");
                }
            }
            catch (Exception $e) {
                echo $this->__generate_callBack("invalid", "invalid", "invalid", $e->getMessage());
            }            
        }
        
        private function __generate_callBack($name, $xml, $preview, $error, $croppedXML="") {
            return "{\"PDFdocument\": \"" . $name . "\", \"XMLDocument\": \"" . $xml . "\", \"XMLDocumentCropped\": \"" . $croppedXML . "\", \"Preview\": \"" . $preview . "\", \"errorMessage\": \"" . $error . "\"}";
        }
        
        private function scan_directory($dir) { 
   
           $result = array(); 

           $cdir = scandir($dir); 
           foreach ($cdir as $key => $value) 
           { 
              if (!in_array($value,array(".",".."))) 
              { 
                 if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
                 { 
                    $result[$value] = scan_directory($dir . DIRECTORY_SEPARATOR . $value); 
                 } 
                 else 
                 { 
                    $result[] = $value; 
                 } 
              } 
           } 

           return $result; 
        }
        
        private function remove_utf8_bom($text) {
            $bom = pack('H*','EFBBBF');
            $text = preg_replace("/^$bom/", '', $text);
            return $text;
        }
        
        private function __read_translation_file($file) {
            return json_decode($this->remove_utf8_bom(file_get_contents($file)));
        }
        private function __get_translation_file_location($language) {
            //Check for custom / edited file before loading in default file
            $file_dir = (file_exists(UDRAW_LANGUAGES_DIR.'udraw-'.$language.'.txt')) ? UDRAW_LANGUAGES_DIR.'udraw-'.$language.'.txt' : UDRAW_PLUGIN_DIR.'/designer/includes/locales/udraw-'.$language.'.txt';
            $file_url = (file_exists(UDRAW_LANGUAGES_URL.'udraw-'.$language.'.txt')) ? UDRAW_LANGUAGES_URL.'udraw-'.$language.'.txt' : UDRAW_PLUGIN_URL.'/designer/includes/locales/udraw-'.$language.'.txt';
            return array('directory'=>$file_dir, 'url'=>$file_url);
        }

        public function udraw_purchase_saved_design() {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_customer_designs'];
            $current_user = wp_get_current_user();
    
            if (isset($_REQUEST['saved_access_key'])) {
                $saved_design_access_key = $_REQUEST['saved_access_key'];
            }
            $sql = "SELECT * FROM $table_name WHERE customer_id = '$current_user->ID' AND access_key = '$saved_design_access_key'";
            $results = $wpdb->get_results($sql);
            $design = $results[0];
            $design_file = $design->design_data;
            $product_id = $design->post_id;
            $design_price_options = json_decode(stripcslashes($design->price_matrix_options));
            $variation_id = '0';
            //$qty = $design_price_options->quantity;
            $qty = 1;
            $all_variations = array();
            
            $cart_item_data['udraw_data'] = array ();
            $cart_item_data['udraw_data']['udraw_product'] = "";
            $cart_item_data['udraw_data']['udraw_product_cart_item_key'] = "";
    
            $ext = pathinfo($design_file, PATHINFO_EXTENSION);
            if ($ext == 'xml') {
                $design_file = str_replace ( UDRAW_STORAGE_URL , UDRAW_STORAGE_DIR , $design_file );
                if (file_exists($design_file)) {
                    $design_data = file_get_contents($design_file);
                    $unid_id = uniqid();
                    $udraw_product_data_file = $unid_id . '_udf';
                    file_put_contents(UDRAW_STORAGE_DIR . '_designs_/' . $udraw_product_data_file, base64_encode($design_data));
                    $cart_item_data['udraw_data']['udraw_product_data'] = '_designs_/' . $udraw_product_data_file;
                }
            }
            $cart_item_data['udraw_data']['udraw_product_preview'] = $design->preview_data;
            $cart_item_data['udraw_data']['udraw_options_uploaded_files_preview'] = $design->preview_data;
            $cart_item_data['udraw_data']['udraw_price_matrix_selected_options_idx'] = join(',', $design_price_options->options);
            $cart_item_data['udraw_data']['udraw_price_matrix_selected_options'] = json_encode($design_price_options->selectedOutput);
            $cart_item_data['udraw_data']['udraw_price_matrix_selected_options_object'] = json_encode($design_price_options->selectedOptions);
            $cart_item_data['udraw_data']['udraw_price_matrix_price'] = $design_price_options->price;
            $cart_item_data['udraw_data']['udraw_price_matrix_qty'] = $design_price_options->quantity;
            $cart_item_data['udraw_data']['udraw_price_matrix_records'] = 1;
            $cart_item_data['udraw_data']['udraw_price_matrix_shipping_dimensions'] = $design_price_options->shipping_dimensions;
            $cart_item_data['udraw_data']['reorder'] = 1;
            
            $add_to_cart = WC()->cart->add_to_cart ( $product_id, $qty, $variation_id, $all_variations, $cart_item_data );
            
            if( !$add_to_cart ) {
                $error = true;
            } 
    
            if($error){
                echo json_encode(array('success'=>false));
            }
            else{
                echo json_encode(array('success'=>true));
                // Calculate totals
                WC()->cart->calculate_totals();
                // Save cart to session
                WC()->cart->set_session();
            }
            wp_die();
        }
        
    }
    
    class uDrawHandler_LocalFonts {
        public $name;
        public $path;
        public $sysPath;
        public $fileSize;
        public $fontType;
        
        public function __construct($_name, $_path, $_sysPath, $_fileSize, $_fileType) {
            $this->name = $_name;
            $this->path = $_path;
            $this->sysPath = $_sysPath;
            $this->fileSize = $_fileSize;
            $this->fontType = $_fileType;
        }
    }
    
    class uDrawHandler_LocalImages {
        public $type;
        public $name;
        public $extension;
        public $path;
        
        public function __construct($_type, $_name, $_extension, $_path) {
            $this->type = $_type;
            $this->name = $_name;
            $this->extension = $_extension;
            $this->path = $_path;
        }
    }
}
?>

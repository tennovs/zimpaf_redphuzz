<?php

if (!class_exists('uDrawConnect')) {
    class uDrawConnectOrderRequest {
        public $order_id;
        public $key;
        public $qty;
        public $data;
        public $block_id;
        public $type;
        public $excelObject;
        public $base_file;
        
        function __construct() {}
    }
    
    class uDrawConnectOrderResponse {
        public $key;
        public $type;
        public $qty;
        public $pdf;
        public $pdfPages;
        public $jpgPages;
        public $pngPages;
        public $preview;
        public $uploadedFiles;
        public $xml;
        public $block_id;
        public $isSuccess;        
        
        function __construct() {}
    }

    class uDrawEmailObject {
        public $key;
        public $orderId;
        public $itemIndex;
        public $jpgPages;
        public $pngPages;
        public $pdfPath;

        function __construct() {}
    }
    
    class uDrawConnect {
        function __contsruct() {}
        
        public function init() {
            add_action('process_udraw_order', array(&$this, 'process_udraw_order'), 10, 2);
            add_action('build_udraw_pdf', array(&$this, '__to_pdf'), 10, 2);

            //Woocommerce custom order action
            add_action( 'woocommerce_order_actions', array( $this, 'add_order_meta_box_actions' ) );
            add_action( 'woocommerce_order_action_send_to_goepower', array( $this, 'process_order_send_to_goepower' ) );            
        }

        function add_order_meta_box_actions ($actions) {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();

            if ( strlen($_udraw_settings['goepower_api_key']) > 1 && strlen($_udraw_settings['goepower_producer_id']) > 0 &&
                strlen($_udraw_settings['goepower_company_id']) > 0 && $_udraw_settings['goepower_send_file_after_order']) {  
                    $actions['send_to_goepower'] = __( 'Send to GoEpower', 'udraw' );
            }
            return $actions;
        }
        function process_order_send_to_goepower ($order) {
            $this->update_thirdparty_systems($order->get_id());
        }
        
        public function process_udraw_order($order_id, $build_files_only) {
            global $woocommerce;
            $uDrawUtil = new uDrawUtil();
            $GoEpower = new GoEpower();
			$GoSendEx = new GoSendEx();
            $uDrawPdfXMPie = new uDrawPdfXMPie();
            
            if (is_null($order_id)) { return; }
            
            // Store order data and keys.
            $uDrawOrdersRequest = array();
            
            // Get WooCommerce Order.
            $order = new WC_Order($order_id);
            $items = $order->get_items();
            $item_keys = array_keys($items);
            for ($x = 0; $x < count($item_keys); $x++) {
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $udraw_data = $items[$item_keys[$x]]['udraw_data'];                  
                } else {
                    $udraw_data = unserialize($items[$item_keys[$x]]['udraw_data']);                  
                }
                if (!$udraw_data) {
                    $fixed = preg_replace_callback('/s:([0-9]+):\"(.*?)\";/', function ($matches)
                        { return "s:".strlen($matches[2]).':"'.$matches[2].'";'; },
                        $items[$item_keys[$x]]['udraw_data']);                        
                    $udraw_data = unserialize($fixed);                   
                }
                
                if (isset($udraw_data['udraw_pdf_block_product_data']) && strlen($udraw_data['udraw_pdf_block_product_data']) > 0) { 
                    if (is_null(json_decode($udraw_data['udraw_pdf_block_product_data']))) {
                        $udraw_data['udraw_pdf_block_product_data'] = uDraw::fixBlocksJSONValues($udraw_data['udraw_pdf_block_product_data']);
                    }
                }
                
                $uDrawOrder = new uDrawConnectOrderRequest();
                $uDrawOrder->order_id = $order_id;
                $uDrawOrder->key = $item_keys[$x];
                $uDrawOrder->qty = (isset($udraw_data['udraw_price_matrix_qty'])) ? $udraw_data['udraw_price_matrix_qty'] : wc_get_order_item_meta($item_keys[$x], '_qty', true);
                
                // uDraw designer product.
                if (isset($udraw_data['udraw_product_data']) && strlen($udraw_data['udraw_product_data']) > 0) {                    
                    $uDrawOrder->data = $udraw_data['udraw_product_data'];
                    if (strlen($udraw_data['udraw_product_data']) < 100) {
                        $uDrawOrder->data = file_get_contents(UDRAW_STORAGE_DIR . $udraw_data['udraw_product_data']);
                    }
                    $uDrawOrder->preview = $udraw_data['udraw_product_preview'];                    
                    $uDrawOrder->type = 'designer';
                    
                    if (isset($udraw_data['udraw_options_uploaded_excel']) && strlen($udraw_data['udraw_options_uploaded_excel']) > 0) {
                        $excelObject = json_decode($udraw_data['udraw_options_uploaded_excel']);
                        $uDrawOrder->excelObject = $excelObject;
                        $uDrawOrder->type = 'designer_excel';
                        $uDrawOrder->base_file = $udraw_data['udraw_product_data'];
                    }
                    
                    array_push($uDrawOrdersRequest, $uDrawOrder);
                    continue;
                }
                
                // Price Matrix product and has a design attached to it.
                if (isset($udraw_data['udraw_price_matrix_design_data']) && strlen($udraw_data['udraw_price_matrix_design_data']) > 0) {
                    $uDrawOrder->data = $udraw_data['udraw_price_matrix_design_data'];                    
                    $uDrawOrder->type = 'designer';
                    array_push($uDrawOrdersRequest, $uDrawOrder);
                    continue;
                }
                
                // PDF product.
                if (isset($udraw_data['udraw_pdf_block_product_id']) && strlen($udraw_data['udraw_pdf_block_product_id']) > 0) {
                    $uDrawOrder->block_id = $udraw_data['udraw_pdf_block_product_id'];
                    $uDrawOrder->data = $udraw_data['udraw_pdf_block_product_data'];
                    $uDrawOrder->type = 'blocks';                    
                    array_push($uDrawOrdersRequest, $uDrawOrder);
                    continue;                   
                }
                
                // XMPie product.
                if (isset($udraw_data['udraw_pdf_xmpie_product_id']) && strlen($udraw_data['udraw_pdf_xmpie_product_id']) > 0) {
                    $uDrawOrder->block_id = $udraw_data['udraw_pdf_xmpie_product_id'];
                    $uDrawOrder->data = $udraw_data['udraw_pdf_xmpie_product_data'];
                    $uDrawOrder->type = 'xmpie';
                    array_push($uDrawOrdersRequest, $uDrawOrder);
                    continue;
                }

                //Upload Product.
                if (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 0) {
                    $uDrawOrder->data = $udraw_data['udraw_options_uploaded_files'];                    
                    $uDrawOrder->type = 'upload';
                    array_push($uDrawOrdersRequest, $uDrawOrder);
                    continue;
                }
            }

            // Store Response data
            $uDrawOrdersResponse = array();
            
            // Loop through uDraw Orders array to process the files accordingly.
            foreach ($uDrawOrdersRequest as $uDrawRequest) {
                $uDrawOrderResponse = new uDrawConnectOrderResponse();
                $activationKey = base64_encode(uDraw::get_udraw_activation_key() .'%%'. str_replace('.', '-', $_SERVER['HTTP_HOST']));
                
                // Process PDF Block Product
                if ($uDrawRequest->type == 'blocks') {
                    
                    if (strlen($uDrawRequest->block_id) > 10) {
                        $_auth_object = $GoEpower->get_auth_object();
                        $data = array(
                            "Token" => $_auth_object->Token,
                            "ProductUniqueID" => $uDrawRequest->block_id,
                            "CustomID" => $_auth_object->CustomID,
                            "isEpower" => "false",
                            "isProof" => "false",
                            "Entries" => json_decode(html_entity_decode($uDrawRequest->data)),
                            "Size" => "480"
                        );
                        $response = $uDrawUtil->get_web_contents('https://pdflib.w2pstore.com/api/Preview', http_build_query($data));
                    } else {
                        $data = array(
                            'ProductID' => $uDrawRequest->block_id,
                            'Proof' => 'false',
                            'Entries' => html_entity_decode($uDrawRequest->data),
                            'Size' => '480'
                        );
                        $response = $uDrawUtil->get_web_contents($GoEpower->get_api_url() . '/CS_Handlers/BlocksPreviewHandler.ashx', http_build_query($data));
                    }
                    
                    if (strlen($response) <= 200) {                        
                        // Response was good
                        $uDrawOrderResponse->key = $uDrawRequest->key;
                        $uDrawOrderResponse->isSuccess = true;                        
                        if (strlen($uDrawRequest->block_id) > 10) {
                            $uDrawOrderResponse->pdf = str_replace('"', '', $response);
                            $uDrawOrderResponse->preview = str_replace('"', '', $response) . '_1.png';
                        } else {
                            $uDrawOrderResponse->pdf = $GoEpower->get_api_url() . $response;
                            $uDrawOrderResponse->preview = $GoEpower->get_api_url() . $response . '.png';
                        }
                        $uDrawOrderResponse->block_id = $uDrawRequest->block_id;
                    } else {
                        // Failed Response
                        $uDrawOrderResponse->isSuccess = false;
                    }
                }
                
                // Process XMPie Product
                if ($uDrawRequest->type == 'xmpie') {
                    $response = null;
                    $xjobid = '';
                    $current_xmpie_attempt = 0;
                    $xmpie_max_retry = 60;
                    
                    while ($current_xmpie_attempt <= $xmpie_max_retry) {
                        $data = array(
                            'pun' => $uDrawPdfXMPie->get_product($uDrawRequest->block_id)['UniqueID'],
                            'action' => 'order',
                            'xjobid' => $xjobid,
                            'entries' => html_entity_decode($uDrawRequest->data),
                            'size' => -1
                        );
                        
                        $response = json_decode($uDrawUtil->get_web_contents($GoEpower->get_api_url() . '/CS_Handlers/Remote/XmpieRemoteHandler.ashx', http_build_query($data)));
                       
                        if (!is_null($response)) {
                            if ($response->status == "wait") {
                                $xjobid = $response->xjobid;
                                $current_xmpie_attempt++;
                                sleep(3);
                                continue;
                            } else if ($response->status == "success") {
                                // Response was good
                                $uDrawOrderResponse->key = $uDrawRequest->key;
                                $uDrawOrderResponse->isSuccess = true;
                                $uDrawOrderResponse->pdf = $response->src;
                                $uDrawOrderResponse->block_id = $uDrawRequest->block_id;
                                if (strlen($response->src) > 0) {
                                    $previewImage = uniqid('preview') . '.png';
                                    $data = array(
                                        'pdfDocument' => $response->src,
                                        'key' => uDraw::get_udraw_activation_key()
                                    );
                                    $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2PNG', http_build_query($data)));                
                                    if ($udraw_convert_response->isSuccess) {
                                        if (is_array($udraw_convert_response->data)) {
                                            $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[0], UDRAW_TEMP_UPLOAD_DIR . $previewImage);
                                            $uDrawOrderResponse->preview = UDRAW_TEMP_UPLOAD_URL . $previewImage;
                                        }
                                    }
                                }
                                break;
                            } else {
                                // Failed Response
                                $uDrawOrderResponse->isSuccess = false;
                                break;
                            }
                        } else {
                            // Failed Response
                            $uDrawOrderResponse->isSuccess = false;
                            break;
                        }
                    }
                    
                }

                if ($uDrawRequest->type == 'designer') {
                    $uDrawOrderResponse = $this->__to_pdf($uDrawRequest, array());
                }
                
                if ($uDrawRequest->type == 'designer_excel') {
                    $uDraw = new uDraw();
                    //Get all the entry xml files
                    $outputDir = $uDraw->get_physical_path($uDrawRequest->excelObject->outputPath);
                    $entries = $uDrawRequest->excelObject->entryObject;
                    $sessionID = $uDrawRequest->excelObject->sessionID;
                    
                    $order_item_dir = UDRAW_ORDERS_DIR.'uDraw-Order-'.$order_id.'-'.$uDrawRequest->key;
                    //check if the target folder exists, if not, create it, if it does, empty it
                    if (!file_exists($order_item_dir)) {
                        wp_mkdir_p($order_item_dir);
                    }
                    $uDrawUtil = new uDrawUtil();
                    $uDrawUtil->empty_target_folder($order_item_dir);
                    $xmlFiles = array();
                    
                    if (!file_exists(UDRAW_STORAGE_DIR . '_designs_/' . $sessionID . '_xml')) {
                        wp_mkdir_p(UDRAW_STORAGE_DIR . '_designs_/' . $sessionID . '_xml');
                    }
                    
                    for ($i = 1; $i <= count($entries); $i++) {
                        $file = $outputDir . $sessionID . '/entry_' . $i . '.xml';
                        $design_file = UDRAW_STORAGE_DIR . '_designs_/' . $sessionID . '_xml/' . $sessionID . '_entry_'.$i.'.xml';
                        //Create the new xml file
                        file_put_contents($design_file, file_get_contents($file));
                        array_push($xmlFiles, $design_file);
                    }
                    $uDrawOrderResponse->key = $uDrawRequest->key;
                    $uDrawOrderResponse->type = $uDrawRequest->type;
                    $uDrawOrderResponse->order_id = $order_id;
                        
                    $orderObject = array(
                        'data' => base64_encode(file_get_contents($xmlFiles[0])),
                        'xmlFile' => str_replace(UDRAW_STORAGE_DIR, UDRAW_STORAGE_URL, $xmlFiles[0]),
                        'order_id' => $order_id,
                        'key' => $uDrawRequest->key,
                        'type' => $uDrawRequest->type
                    );
                    //Insert job into excel jobs table
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'udraw_excel_jobs';
                    //Delete any previous jobs with this 
                    $wpdb->delete($table_name, array(
                            'order_id' => $order_id,
                            'item_id' => $uDrawRequest->key
                        ),
                        array(
                            '%d',
                            '%d'
                        )
                    );
                    $wpdb->insert($table_name,
                        array(
                            'order_id' => $order_id,
                            'item_id' => $uDrawRequest->key,
                            'xmlFiles' => serialize($xmlFiles),
                            'totalCount' => count($xmlFiles)
                        ),
                        array(
                            '%d',
                            '%d',
                            '%s',
                            '%d'
                        )
                    );
                    wp_schedule_single_event(time(), 'build_udraw_pdf', array( (object)$orderObject, $xmlFiles ) );
                    //Check on the jobs in 10 mins to make sure it's still going
                    wp_schedule_single_event(time() + (10 * 60), 'check_udraw_pdfs', array( $order_id, $uDrawRequest->key ) );
                }

                if ($uDrawRequest->type == 'upload') {
                    $uDrawOrderResponse->key = $uDrawRequest->key;
                    $uDrawOrderResponse->type = $uDrawRequest->type;
                    $uDrawOrderResponse->qty = $uDrawRequest->qty;
                    $uDrawOrderResponse->uploadedFiles = true;
                }
                
                // Add item to Response Array
                array_push($uDrawOrdersResponse, $uDrawOrderResponse);
            }
            error_log('[uDraw Processed Order - Preperation Completed.]');
            $this->update_order_meta($order_id, $uDrawOrdersResponse);
            error_log('[uDraw Processed Order - Updated Order Meta Information.]');

            do_action('udraw_before_update_thirdparty_systems', $order_id);
            if (!$build_files_only) {
                $this->update_thirdparty_systems($order_id);
            }
        }
        
        public function __to_pdf($uDrawRequest, $xmlFiles) {               
            $uDrawUtil = new uDrawUtil();
            $uDrawRequest = (gettype($uDrawRequest) == 'string') ? json_decode($uDrawRequest) : $uDrawRequest;
            $designXML = simplexml_load_string(base64_decode($uDrawRequest->data), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
            $activationKey = base64_encode(uDraw::get_udraw_activation_key() .'%%'. str_replace('.', '-', $_SERVER['HTTP_HOST']));
            $ratio = wc_get_order_item_meta($uDrawRequest->key, '_CanvasRatio', true);
            $woocommerce_order = new WC_Order($uDrawRequest->order_id);
            $pagesInfo = array();
            for ($x = 0; $x < count($designXML->page); $x++) {
                $pageInfo = new StdClass();
                $pageInfo->width = (float)$designXML->page[$x]->attributes()->width + ((float)$designXML->page[$x]->attributes()->bleed * 2) + ((float)$designXML->page[$x]->attributes()->border * 2);
                $pageInfo->height = (float)$designXML->page[$x]->attributes()->height + ((float)$designXML->page[$x]->attributes()->bleed * 2) + ((float)$designXML->page[$x]->attributes()->border * 2);
                $pageInfo->ratio = wc_get_order_item_meta($uDrawRequest->key, '_CanvasRatio', true);
                if (!is_null($ratio) || strlen($ratio) > 0) {
                    $ratioVal = intval($ratio);
                }
                if (!is_numeric($ratio)) { $ratio = 'none'; }

                if ($ratio == 'none') {
                    $designObj = json_decode(base64_decode($designXML->page->item[0]["json"]));
                    if ($designObj->racad_properties->settings->pdfRatio) {
                        if (is_numeric($designObj->racad_properties->settings->pdfRatio)) {
                            $ratioVal = floatval($designObj->racad_properties->settings->pdfRatio);
                            if ($ratioVal >= 1) {
                                $pageInfo->ratio = $ratioVal;
                            }
                         }
                    }
                }
                array_push($pagesInfo, $pageInfo);
            }
            $pdfPages = array();
            
            if (!is_null($ratio) || strlen($ratio) > 0) {
                $ratioVal = intval($ratio);
            }
            if (!is_numeric($ratio)) { $ratio = 'none'; }

            if ($ratio == 'none') {
                $designObj = json_decode(base64_decode($designXML->page->item[0]["json"]));
                if ($designObj->racad_properties->settings->pdfRatio) {
                    if (is_numeric($designObj->racad_properties->settings->pdfRatio)) {
                        $ratioVal = intval($designObj->racad_properties->settings->pdfRatio);
                        if ($ratioVal > 1) {
                            $ratio = $ratioVal;
                        }
                     }
                }
            }
            $session_udraw_id = uniqid('udraw_');
            $pdf_file = $session_udraw_id . '.pdf';
            $preview_file = $session_udraw_id . '.png';
            $xml_file = $session_udraw_id . '.xml';
            
            // Write the XML Design Data.
            file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $xml_file, base64_decode($uDrawRequest->data));

            set_time_limit(300);
            $pdfContent = false;            

            $data = array(
                'designFile' => str_replace('localhost', 'udraw-wordpress.ngrok.io', UDRAW_TEMP_UPLOAD_URL) . '/' . $xml_file,
                'key' => uDraw::get_udraw_activation_key()
            );
            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/uDraw2PDF', http_build_query($data)));              
            if ($udraw_convert_response->isSuccess) {
                $pdfContent = UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data->pdf;
                $woocommerce_order->add_order_note("Generated uDraw documents.");
            } else {
                $woocommerce_order->add_order_note("uDraw failed to generate documents. [ " . $udraw_convert_response->message . " ]");
            }            

            // If $pdfContent is a boolean, that means response data is empty and is returning false.
            // In this case, our request has failed.
            $uDrawOrderResponse = new uDrawConnectOrderResponse();            
            if ($uDrawRequest->type == 'designer_excel') {
                if (gettype($pdfContent) != "boolean" && $pdfContent != '') {
                    $order_item_dir = UDRAW_ORDERS_DIR.'uDraw-Order-'.$uDrawRequest->order_id.'-'.$uDrawRequest->key.'/';
                    $uDrawUtil->download_file($pdfContent, $order_item_dir . $pdf_file);
                    $uDrawOrderResponse->excel_pdf = $order_item_dir . $pdf_file;
                    //Remove xml file from array
                    array_shift($xmlFiles);
                    //Update table
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'udraw_excel_jobs';
                    $wpdb->update($table_name,
                        array(
                            'xmlFiles' => serialize($xmlFiles)
                        ),
                        array(
                            'order_id' => $uDrawRequest->order_id,
                            'item_id' => $uDrawRequest->key,
                        ),
                        array(
                            '%s'
                        )
                    );
                    if (count($xmlFiles) === 0) {
                        wp_mail(get_option('admin_email'), 'PDF files for order #' . $uDrawRequest->order_id, 'PDF files for order #' . $uDrawRequest->order_id . ' have finished generating. Please check for compressed file.');
                        //Run function to check number of pdf files in folder before creating zip file
                        $uDrawExcelHandler = new uDrawExcelHandler();
                        $uDrawExcelHandler->check_excel_folder_for_packaging($uDrawRequest->order_id, $uDrawRequest->key);
                        //Job is done. Delete it from DB
                        $wpdb->delete($table_name, array(
                                'order_id' => $uDrawRequest->order_id,
                                'item_id' => $uDrawRequest->key
                            ),
                            array(
                                '%d',
                                '%d'
                            )
                        );
                    } else {
                        $orderObject = array(
                            'data' => base64_encode(file_get_contents($xmlFiles[0])),
                            'xmlFile' => str_replace(UDRAW_STORAGE_DIR, UDRAW_STORAGE_URL, $xmlFiles[0]),
                            'order_id' => $uDrawRequest->order_id,
                            'key' => $uDrawRequest->key,
                            'type' => $uDrawRequest->type
                        );
                        wp_schedule_single_event(time() + 1, 'build_udraw_pdf', array( (object)$orderObject, $xmlFiles ) );
                    }
                }
            } else {
                // Download the PDF Document.
                if (gettype($pdfContent) != "boolean" && $pdfContent != '') {
                    $uDrawUtil->download_file($pdfContent, UDRAW_TEMP_UPLOAD_DIR . $pdf_file);                    
                }                    
            }
            // Write the Preview Image.
            $preview_dir = str_replace(UDRAW_TEMP_UPLOAD_URL, UDRAW_TEMP_UPLOAD_DIR, $uDrawRequest->preview);
            if (isset($uDrawRequest->preview) && !is_null($uDrawRequest->preview)) { $this->base64_to_image($preview_dir, UDRAW_TEMP_UPLOAD_DIR . $preview_file); }
            $uDrawOrderResponse->isSuccess = true;
            $uDrawOrderResponse->pdf = UDRAW_TEMP_UPLOAD_URL . $pdf_file;
            $uDrawOrderResponse->preview = UDRAW_TEMP_UPLOAD_URL . $preview_file;
            $uDrawOrderResponse->xml = UDRAW_TEMP_UPLOAD_URL . $xml_file;
            $uDrawOrderResponse->key = $uDrawRequest->key;
            $uDrawOrderResponse->type = $uDrawRequest->type;
            $uDrawOrderResponse->qty = $uDrawRequest->qty;
            $uDrawOrderResponse->pdfPages = $pdfPages;

            return $uDrawOrderResponse;
        }
        
        function png_to_jpg ($input_file, $output_file, $quality) {
            $image = imagecreatefrompng($input_file);
            imagejpeg($image, $output_file, $quality);
            imagedestroy($image);
            if (file_exists($output_file)) {
                return str_replace(UDRAW_TEMP_UPLOAD_DIR, UDRAW_TEMP_UPLOAD_URL, $output_file);
            } else {
                return false;
            }
        }
    
        //////////////////////////
        /// PRIVATE FUNCTIONS ////
        //////////////////////////
        
        private function base64_to_image($base64_string, $output_file) {
            $ifp = fopen($output_file, "wb");
            $data = explode(',', $base64_string);
            fwrite($ifp, base64_decode($data[0])); 
            fclose($ifp); 
            
            if (is_file($base64_string)) {
                $ifp = fopen($output_file, "wb");
                $data = file_get_contents($base64_string);
                fwrite($ifp, $data); 
                fclose($ifp);
            }

            return $output_file; 
        }
                
        private function update_order_meta($order_id, $uDrawOrdersResponse) {
            global $woocommerce;
			$GoSendEx = new GoSendEx();
            $udraw_settings = new uDrawSettings();
            $_settings = $udraw_settings->get_settings();
            $uDrawUtil = new uDrawUtil();
            $order = wc_get_order( $order_id );
            $pdfsGenerated = false;
            $pngsGenerated = false;
            $jpgsGenarated = false;
            $filesUploaded = false;

            $uDrawEmailObjectArray = array();

            if (!is_dir(UDRAW_ORDERS_DIR) || is_dir(UDRAW_ORDERS_DIR) === '') {
                wp_mkdir_p(UDRAW_ORDERS_DIR);
            }
            
            foreach ($uDrawOrdersResponse as $itemIndex => $uDrawResponse) { 
                if (is_null($uDrawResponse->key)) { continue; } // bad item, this really shouldn't happen.
                // Remove any existing pdf meta.
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_pdf_path");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_pdf_xref");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_pdf_pages");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_product_jpg");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_pdf_pages");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_block_id");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_jpg_pages");
                wc_delete_order_item_meta($uDrawResponse->key, "_udraw_png_pages");

                $getIndex = 0;
                $uDrawEmailItem = new uDrawEmailObject();
                foreach ( $order->get_items() as $item_id => $item ) {
                    //Get line item index
                    $getIndex = $getIndex + 1;
                    if ( $uDrawResponse->key == $item_id ) { 
                        break;
                    }
                }

                if ($uDrawResponse->type == 'designer') {
                    wc_delete_order_item_meta($uDrawResponse->key, "_udraw_xml_xref");
                }
                $pdf_exists = false;
                if (isset($uDrawResponse->pdf)) {
                    if (strlen($uDrawResponse->pdf) > 1) { $pdf_exists = true; }
                }

                if (isset($uDrawResponse->uploadedFiles)) {
                    if ($uDrawResponse->uploadedFiles) { $filesUploaded = true; }
                }

                // Attempt to normalize the file name.
                $newFilename = "uDraw-Order-" . $order_id . "-" . $uDrawResponse->key;
                $pageNameArray = array();

                if (!is_null($_settings['udraw_order_document_format'])) {
                    if (strlen($_settings['udraw_order_document_format']) > 0) {
                        $outputFilename = $_settings['udraw_order_document_format'];
                        $outputFilename = str_replace('%_ORDER_ID_%', $order_id, $outputFilename);
                        $outputFilename = str_replace('%_JOB_ID_%', $uDrawResponse->key, $outputFilename);
                        $outputFilename = str_replace('%_ITEM_INDEX_%', $getIndex, $outputFilename);
                        $outputFilename = str_replace('%_QUANTITY_%', $uDrawResponse->qty, $outputFilename);
                        if (strlen($outputFilename) > 2) {
                            $newFilename = $outputFilename;
                        }                        
                    }
                }
                if ($_settings['udraw_design_page_names']) {
                    if(isset($_settings['udraw_design_page_names']) && strlen($_settings['udraw_design_page_names']) > 0) {
                        $pageNames = $_settings['udraw_design_page_names'];
                        $pageNameArray = array_map('trim', explode(',', $pageNames));       
                    }
                }

                $newPreviewFile = $newFilename . ".png";
                $newXMLFile = $newFilename . ".xml";
                $newJPGFile = $newFilename . ".jpg";
                if (!is_dir(UDRAW_ORDERS_DIR)) { wp_mkdir_p(UDRAW_ORDERS_DIR); }
                if (is_dir(UDRAW_ORDERS_DIR)) {

                    if ($pdf_exists) {
                        // Download the processed files locally from the api servers.
                        $extension = pathinfo($uDrawResponse->pdf, PATHINFO_EXTENSION);
                        // Main Output PDF Document
                        $this->__downloadFile($uDrawResponse->pdf, UDRAW_ORDERS_DIR . $newFilename . '.' . $extension);
                        $epowerLink = strpos($uDrawResponse->pdf, 'Storage');
                        if ($epowerLink >= 20){
							$pdfPath = $uDrawResponse->pdf;
                            wc_add_order_item_meta($uDrawResponse->key, "_udraw_pdf_path", $pdfPath);
                        } else {
							$pdfPath = UDRAW_ORDERS_DIR . $newFilename . '.' . $extension;
                            wc_add_order_item_meta($uDrawResponse->key, "_udraw_pdf_path", $pdfPath);
                        }
                        wc_add_order_item_meta($uDrawResponse->key, "_udraw_pdf_xref", $pdfPath); 
                        $pdfsGenerated = true;

                        if ($_settings['udraw_generate_jpg_production']) {
                            $data = array(
                                'pdfDocument' => str_replace('localhost', 'udraw-wordpress.ngrok.io', UDRAW_ORDERS_URL) . $newFilename . '.' . $extension,
                                'key' => uDraw::get_udraw_activation_key()
                            );
    
                            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2JPG', http_build_query($data)));
                            if ($udraw_convert_response->isSuccess) {
                                if (is_array($udraw_convert_response->data)) {
                                    $jpgPages = array();
                                    for ($x = 0; $x < count($udraw_convert_response->data); $x++) {
                                        if ($pageNameArray) {
                                            if (is_array($pageNameArray) && count($pageNameArray) === count($udraw_convert_response->data)) {
                                                $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . ' - ' . ($pageNameArray[$x]) .'.jpg');
                                                array_push($jpgPages, UDRAW_ORDERS_URL . $newFilename . ' - ' . ($pageNameArray[$x]) .'.jpg');
                                            } else {
                                                $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . '-' . ($x+1) .'.jpg');
                                                array_push($jpgPages, UDRAW_ORDERS_URL . $newFilename . '-' . ($x+1) .'.jpg');
                                            }
                                        } else {
                                            $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . '-' . ($x+1) .'.jpg');
                                            array_push($jpgPages, UDRAW_ORDERS_URL . $newFilename . '-' . ($x+1) .'.jpg');
                                        }
                                    }
                                    wc_add_order_item_meta($uDrawResponse->key, "_udraw_jpg_pages", $jpgPages);
                                    $jpgsGenarated = true;
                                }
                            } else {
                                $jpgsGenarated = false;
                            }
                        }

                        if ($_settings['udraw_generate_png_production']) {
                            if(isset($_settings['udraw_production_png_color_replacement']) && strlen($_settings['udraw_production_png_color_replacement']) > 0) {
                                $png_color_color_replacement_arr = explode("\n", $_settings['udraw_production_png_color_replacement']);
                                $countArr = count($png_color_color_replacement_arr);
                                $parsed_color_replacement_arr = []; $parsed_color_replacement_val = [];
                                for($i = 0; $i < $countArr ; $i++) {
                                    $parsed_temp = explode("-", $png_color_color_replacement_arr[$i]);
                                    $parsed_color_replacement_val["target"] = array_map('intval', explode(',', trim($parsed_temp[0])));
                                    $parsed_color_replacement_val["replace"] = array_map('intval', explode(',', trim($parsed_temp[1])));
                                    array_push($parsed_color_replacement_arr , $parsed_color_replacement_val);
                                }
                                $data = array(
                                    'pdfDocument' => str_replace('localhost', 'udraw-wordpress.ngrok.io', UDRAW_ORDERS_URL) . $newFilename . '.' . $extension,
                                    'key' => uDraw::get_udraw_activation_key(),
                                    'replaceColor' => json_encode($parsed_color_replacement_arr)
                                );
                            } else {
                                $data = array(
                                    'pdfDocument' => str_replace('localhost', 'udraw-wordpress.ngrok.io', UDRAW_ORDERS_URL) . $newFilename . '.' . $extension,
                                    'key' => uDraw::get_udraw_activation_key()
                                );
                            }
    
                            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2PNG', http_build_query($data)));
                            if ($udraw_convert_response->isSuccess) {
                                if (is_array($udraw_convert_response->data)) {
                                    $pngPages = array();
                                    for ($x = 0; $x < count($udraw_convert_response->data); $x++) {
                                        if ($pageNameArray) {
                                            if (is_array($pageNameArray) && count($pageNameArray) === count($udraw_convert_response->data)) {
                                                $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . ' - ' . ($pageNameArray[$x]) .'.png');
                                                array_push($pngPages, UDRAW_ORDERS_URL . $newFilename . ' - ' . ($pageNameArray[$x]) .'.png');
                                            } else {
                                                $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . '-' . ($x+1) .'.png');
                                                array_push($pngPages, UDRAW_ORDERS_URL . $newFilename . '-' . ($x+1) .'.png');
                                            }
                                        } else {
                                            $this->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[$x], UDRAW_ORDERS_DIR . $newFilename . '-' . ($x+1) .'.png');
                                            array_push($pngPages, UDRAW_ORDERS_URL . $newFilename . '-' . ($x+1) .'.png');
                                        }
                                    }
                                    wc_add_order_item_meta($uDrawResponse->key, "_udraw_png_pages", $pngPages);
                                    $pngsGenerated = true;
									if (isset($_settings['udraw_send_order_item_info'])) {
										//Custom for Do+Dare
                                        $uDrawEmailItem->key = $uDrawResponse->key;
                                        $uDrawEmailItem->orderId = $order_id;
                                        $uDrawEmailItem->itemIndex = $getIndex;
                                        $uDrawEmailItem->pngPages = json_encode($pngPages);
                                        $uDrawEmailItem->pdfPath = $pdfPath;
                                        array_push($uDrawEmailObjectArray, $uDrawEmailItem);
									}
                                }
                            } else {
                                $pngsGenerated = false;
                            }
                        }
                    } 

					// Handle Pages ( if exists )
					if (is_array($uDrawResponse->pdfPages)) {
						if (count($uDrawResponse->pdfPages) > 0) {
							$pdfPages = array();
							for ($x = 0; $x < count($uDrawResponse->pdfPages); $x++) {
								$this->__downloadFile($uDrawResponse->pdfPages[$x], UDRAW_ORDERS_DIR . $x .'-' . $newFilename . '.' . $extension);
								array_push($pdfPages, UDRAW_ORDERS_URL . $x .'-' . $newFilename . '.' . $extension);
							}
							wc_add_order_item_meta($uDrawResponse->key, "_udraw_pdf_pages", $pdfPages);
						}
					}					
                    
                    // Preview
                    if (isset($uDrawResponse->preview) && strlen($uDrawResponse->preview) > 0) {
                        $this->__downloadFile($uDrawResponse->preview, UDRAW_ORDERS_DIR . $newPreviewFile);
                    }
                    
                    // JPG Preview
                    if (isset($uDrawResponse->jpg) && strlen($uDrawResponse->jpg) > 0) {
                        $this->__downloadFile($uDrawResponse->jpg, UDRAW_ORDERS_DIR . $newJPGFile);
                        wc_add_order_item_meta($uDrawResponse->key, "_udraw_product_jpg", UDRAW_ORDERS_URL . $newJPGFile);
                    }
                    
                    // uDraw Designer
                    if ($uDrawResponse->type == 'designer') {
                        $this->__downloadFile($uDrawResponse->xml, UDRAW_ORDERS_DIR . $newXMLFile);
                        wc_add_order_item_meta($uDrawResponse->key, "_udraw_xml_xref", UDRAW_ORDERS_URL . $newXMLFile);
                    }
                    
                    // Product/Block Id
                    if (strlen($uDrawResponse->block_id) > 0) {
                        wc_add_order_item_meta($uDrawResponse->key, "_udraw_block_id", $uDrawResponse->block_id);
                    }                    
                }
            }

            //Sending order to SendEx after production files are available.
            if (isset($_settings['gosendex_send_file_after_order'])) {
                if ($filesUploaded) {
                    $GoSendEx->order_status_processing($order_id);
                } else if ($_settings['udraw_generate_jpg_production'] || $_settings['udraw_generate_png_production']) {
                    if ($pngsGenerated || $jpgsGenarated) {
                        $GoSendEx->order_status_processing($order_id);
                    }
                } else {
                    if ($pdfsGenerated) {
                        $GoSendEx->order_status_processing($order_id);
                    }
                }
            }
            if (isset($_settings['udraw_send_order_item_info'])) {
                if (count($uDrawEmailObjectArray) > 0) {
                    $this->send_an_email_for_each_item($uDrawEmailObjectArray);
                }
            }
        }

        public function send_an_email_for_each_item($uDrawEmailObjectArray) {
            //Custom Email for Do+Dare generated for each order item.
            global $woocommerce;
            foreach ( $uDrawEmailObjectArray as $uDrawEmailObjItem ) {
                $order_id = $uDrawEmailObjItem->orderId;
                $getIndex = $uDrawEmailObjItem->itemIndex;
                $order = new WC_Order($order_id);
                $totalItemCount = count($order->get_items());
                $uDrawSettings = new uDrawSettings();
                $settings = $uDrawSettings->get_settings();
                $attachments = array();
                $previewArray = array();
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $invoice = wcpdf_get_document( 'invoice', $order, true );
                $pdf_data = $invoice->get_pdf();
                $invoice_path = WP_CONTENT_DIR . '/uploads/invoice - ' . $order_id . '.pdf';
                $invoicePath = file_put_contents($invoice_path, $pdf_data);

                if (isset($settings['udraw_send_order_item_info'])) {
                    $send_to_email = $settings['udraw_send_order_item_info'];
                    foreach ( $order->get_items() as $item_id => $item ) {
                        if ($item_id == $uDrawEmailObjItem->key) {
                            $udraw_data = $item->get_meta( 'udraw_data' , true);
                            $quantity = $item->get_quantity();
                            $formatted_meta_data = $item->get_formatted_meta_data( '', true );
                            foreach($formatted_meta_data as $key => $order_item) {
                                if ($order_item->key === 'Undie Type') {
                                    $undie_type = $order_item->value;
                                } else if ($order_item->key === 'Size') {
                                    $undie_size = 'Size ' . $order_item->value;
                                } else if ($order_item->key === 'Color') {
                                    $undie_color = $order_item->value;
                                } else if ($order_item->key === 'Undie Name') {
                                    $undie_name = $order_item->value;
                                } else if ($order_item->key === 'Prints') {
                                    $undie_prints = 'Prints ' . $order_item->value;
                                }
                            }

                            if (isset($udraw_data['udraw_options_uploaded_files_preview'])){
                                $thumbnails = json_decode(stripcslashes($udraw_data['udraw_options_uploaded_files_preview']));
                                foreach ($thumbnails as $key => $value) {
                                    if (strpos($value, '/wp-content/udraw/storage/') !== false) {
                                        $previewPageSrc = str_replace('/wp-content', WP_CONTENT_DIR, $value);
                                        $baseName = basename($previewPageSrc , '.png');
                                        if ($baseName == '1') { $sideName = 'undies-front'; }
                                        else if ($baseName == '2') { $sideName = 'undies-back'; }
                                        else if ($baseName == '3') { $sideName = 'undies-inside-front'; }
                                        else if ($baseName == '4') { $sideName = 'undies-inside-back'; }
                                        $fileName = 'proof - ' . $order_id . '-' . $getIndex . ' - ' . $sideName;
                                        $destinationSrc = WP_CONTENT_DIR . '/uploads/' . $fileName . '.png';
                                        if( copy($previewPageSrc, $destinationSrc) ) { 
                                            array_push($previewArray, $destinationSrc);
                                        } 
                                    }
                                }
                            }

                            $subject_line = $order_id . '-' . $getIndex . ' of ' . $totalItemCount . ' ~ ' . 'Qty ' . $quantity . ' ~ ' . $undie_prints . ' ~ ' . $undie_type . ' - ' . $undie_size . ' - ' . $undie_color . ' ~ ' . $undie_name;
                            $body = 'Files for ' . $order_id . ' - line item ' . $getIndex . ' of ' . $totalItemCount . '.';
                        }
                    }
                    //Attach Production PDF
                    $pdfPath = $uDrawEmailItem->pdfPath;
                    array_push($attachments, $pdfPath);
                    //Attach Production PNGs
                    $pngPages = json_decode($uDrawEmailObjItem->pngPages, true);
                    foreach ( $pngPages as $key => $pngPage ) {
                        $pngPage = str_replace(UDRAW_ORDERS_URL, UDRAW_ORDERS_DIR, $pngPage);
                        array_push($attachments, $pngPage);
                    }
                    //Attach Temp Preview Pages
                    foreach ( $previewArray as $key => $previewPage ) {
                        array_push($attachments, $previewPage);
                    }
                    //Attach Temp Invoice
                    if (file_exists($invoice_path)) {
                        array_push($attachments, $invoice_path);
                    }

                    wp_mail( $send_to_email, $subject_line, $body, $headers, $attachments );
                    
                    //Delete Previews generated on a new location after email sent.
                    if (count($previewArray) > 0) {
                        foreach ( $previewArray as $key => $previewPage ) {
                            unlink($previewPage);
                        }
                    }
                    //Delete Invoice after email sent.
                    if (file_exists($invoice_path)) { unlink($invoice_path); }
                }
            }
        }
        
        private function update_thirdparty_systems($order_id) {
            // Init all required Classes and variables.
            global $woocommerce;   
            $goepower = new GoEpower();
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();  
            
            //
            // Run the next part of code only if this is an order. If we are just re-building PDF no need to submit order info again.
            //            
            $order = new WC_Order($order_id);
            $taxes = new WC_Tax();
            $product_factory = new WC_Product_Factory();
            
            $order_taxes = $order->get_taxes();

            $order_tax_label_1 = "";
            $order_tax_label_2 = "";
            $order_tax_total_1 = 0.00; // used in case we have multiple taxes.
            $order_tax_total_2 = 0.00; // used in case we have multiple taxes.

            // Array which holds all items from order.
            $order_items_array = array();
            $order_total_price = number_format($order->get_total( ), 2);
            $order_subtotal_price = 0.00;

            if ($order->get_total_tax() > 0) {
                $order_total_taxes = number_format($order->get_total_tax( ), 2);
            } else {
                $order_total_taxes = 0.00;
            }            

            if ($order->get_shipping_total() > 0) {                
                $order_total_shipping = number_format($order->get_shipping_total(), 2);
            } else {
                $order_total_shipping = "0.00";
            }                        
            
            // Iterate through all items
            $order_items = $order->get_items();
            $order_item_keys = array_keys($order_items);    


            //for ($x = 0; $x < count($order_item_keys); $x++) {
            foreach ($order_item_keys as $x => $value) {
                $_item = $order_items[$order_item_keys[$x]];
                $_item_product = $product_factory->get_product($_item['product_id']);
                $_item_subtotal = number_format($order->get_line_subtotal($_item, false, false), 2);                
                $_item_total = number_format($order->get_line_total($_item, false, false), 2);

                $order_subtotal_price = $order_subtotal_price + $order->get_line_subtotal($_item, false, false);

                // If we have more than one type of tax for the order, we need to split up $order_total_taxes.
                // Otherwise we can just use the total taxes.
                if (count($order_taxes) > 0) {
                    $_item_taxes_keys = array_keys($order_taxes);

                    for ($y = 0; $y < count($_item_taxes_keys); $y++) {
                        $order_tax_label = $order_taxes[$_item_taxes_keys[$y]]["label"];
                        $order_tax_total = number_format($order_taxes[$_item_taxes_keys[$y]]["tax_amount"], 2);
                        if ($y == 0) {
                            $order_tax_label_1 = $order_tax_label;                          
                            $order_tax_total_1 = $order_tax_total;
                        } else if ($y == 1) {
                            $order_tax_label_2 = $order_tax_label;
                            $order_tax_total_2 = $order_tax_total;
                        } else {
                            // Too many taxes for GoEpower to Support.
                            continue;
                        }                        
                    }             
                }
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $udraw_data = $_item["udraw_data"];
                } else {
                    $udraw_data = unserialize($_item["udraw_data"]);
                }
                
                if (isset($_item["item_meta"]["_udraw_pdf_xref"])) {
                    $_pdf_path = $_item["item_meta"]["_udraw_pdf_xref"];
                    if (strpos($_pdf_path, UDRAW_ORDERS_DIR) !== false) {
                        $_pdf_path = str_replace(UDRAW_ORDERS_DIR, UDRAW_ORDERS_URL, $_pdf_path);
                    }
                } else {
                    $_pdf_path = NULL;
                }

                $_item_object = new StdClass;
                $_item_object->ProductID = (isset($_item["item_meta"]["_udraw_block_id"])) ? $_item["item_meta"]["_udraw_block_id"][0] : 0;
                $_item_object->ProductName = $_item["name"];                
                $_item_object->SKU = $_item_product->get_sku();
                $_item_object->ItemExternalID = $_item["product_id"];
                $_item_object->Quantity = $_item["qty"];
                $_item_object->Price = $_item_total;
                $_item_object->SetupPrice = 0.00;
                $_item_object->TotalPrice = $_item_total;
                $_item_object->DesignFileName = (isset($_item["item_meta"]["_udraw_xml_xref"])) ? $_item["item_meta"]["_udraw_xml_xref"] : NULL;
                $_item_object->PDFFileName = $_pdf_path;
                $_item_object->PNGFileName = (isset($_item["item_meta"]["_udraw_preview_xref"])) ? $_item["item_meta"]["_udraw_preview_xref"] : NULL;
                $_item_object->Thumbnail = ""; // This gets auto genrated by GoEpower service.
                $_item_object->CustomID = 0;
                $_item_object->GoPrint2RouteEmail = get_post_meta($_item_object->ItemExternalID, '_udraw_goprint2_order_route_email', true);
                $udraw_product_type = "Designer";
                if (isset($udraw_data['udraw_pdf_block_product_id']) && strlen($udraw_data['udraw_pdf_block_product_id']) > 0) {
                    $udraw_product_type = "Blocks";
                } else if (is_null($_item_object->DesignFileName)) {
                    $udraw_product_type = "WooCommerce";
                }
                $product_type = apply_filters('udraw_thirdparty_system_job_type', $udraw_product_type, $_item);
                $_item_object->JobType = $product_type;
                $_datas = array();

                // Inject Price Matrix Selected Options ( if product contains the data )
                if (isset($udraw_data['udraw_price_matrix_selected_options']) && isset($udraw_data['udraw_price_matrix_qty']) ) {                
                    //Price matrix selected quantity
                    $_item_object->Quantity = $udraw_data['udraw_price_matrix_qty'];
                    if (isset($udraw_data['udraw_price_matrix_selected_options_object'])) {
                        $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options_object']));
                        for ($x = 0; $x < count($selected_options); $x++) {
                            // Price matrix selected options
                            $_data = new StdClass;
                            if (isset($selected_options[$x]->name) && isset($selected_options[$x]->value)) {
                                $_data->FieldName = $selected_options[$x]->name;
                                $_data->FieldValue = (strlen($selected_options[$x]->value) > 0) ? $selected_options[$x]->value : " ";
                                array_push($_datas, $_data);
                            }
                        }                        
                    } else {
                        $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options']));                
                        foreach ($selected_options as $option => $value) {
                            // Price matrix selected options
                            $_data = new StdClass;
                            $_data->FieldName = $option;
                            $_data->FieldValue = $value[0];
                            array_push($_datas, $_data);
                        }
                    }
                } else {
                    if (isset($_item['variation_id'])) {
                        $_variation_product = $product_factory->get_product($_item['variation_id']);
                        if (isset($_variation_product) && gettype($_variation_product) == 'object') {
                            $_item_object->SKU = $_variation_product->get_sku();
                            if (is_array($_item['item_meta'])) {
                                $item_meta_keys = array_keys($_item['item_meta']);
                                for ($z = 0; $z < count($item_meta_keys); $z++) {
                                    if (substr($item_meta_keys[$z], 0, 1) != '_') {
                                        if ($item_meta_keys[$z] == 'udraw_data') { continue; }
                                        if (is_array($_item['item_meta'][$item_meta_keys[$z]])) {
                                            if (count($_item['item_meta'][$item_meta_keys[$z]]) > 0) {
                                                if (strlen($_item['item_meta'][$item_meta_keys[$z]][0]) > 0) {
                                                    $_data = new StdClass;
                                                    $_data->FieldName = wc_attribute_label( $item_meta_keys[$z] );
                                                    $_data->FieldValue = $_item['item_meta'][$item_meta_keys[$z]][0];
                                                    array_push($_datas, $_data);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }                    
                $_item_object->Datas = $_datas;
                
                array_push($order_items_array, $_item_object);
            }

            $countries = new WC_Countries();
            if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                $billing_country = $order->get_billing_country();
                $shipping_country = $order->get_shipping_country();
            } else {
                $billing_country = $order->billing_country;
                $shipping_country = $order->shipping_country;
            }
            $billing_country_name = $countries->countries[$billing_country];
            $shipping_country_name = $countries->countries[$shipping_country];
            
            if (0 === strpos($billing_country_name, 'United States (US)')) {
                $billing_country_name = 'USA';
            }
            if (0 === strpos($shipping_country_name, 'United States (US)')) {
                $shipping_country_name = 'USA';
            }
            
            do_action('udraw_send_thirdparty_order', $order, $order_items_array, $order_total_price, $order_total_shipping, $order_total_taxes);
            
            if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                $billing_first_name = $order->get_billing_first_name();
                $billing_last_name = $order->get_billing_last_name();
                $billing_company = $order->get_billing_company();
                $billing_address_1 = $order->get_billing_address_1();
                $billing_address_2 = $order->get_billing_address_2();
                $billing_city = $order->get_billing_city();
                $billing_state = $order->get_billing_state();
                $billing_country = $order->get_billing_country();
                $billing_postcode = $order->get_billing_postcode();
                $billing_phone = $order->get_billing_phone();
                $billing_email = $order->get_billing_email();
                
                $order_id = $order->get_id();
                
                $user_id = $order->get_customer_id();
            } else {
                $billing_first_name = $order->billing_first_name;
                $billing_last_name = $order->billing_last_name;
                $billing_company = $order->billing_company;
                $billing_address_1 = $order->billing_address_1;
                $billing_address_2 = $order->billing_address_2;
                $billing_city = $order->billing_city;
                $billing_state = $order->billing_state;
                $billing_country = $order->billing_country;
                $billing_postcode = $order->billing_postcode;
                $billing_phone = $order->billing_phone;
                $billing_email = $order->billing_email;
                
                $order_id = $order->id;
                
                $user_id = $order->user_id;
            }            
                
            // Submit Order to GoEpower
            if ( strlen($_udraw_settings['goepower_api_key']) > 1 && strlen($_udraw_settings['goepower_producer_id']) > 0 &&
                strlen($_udraw_settings['goepower_company_id']) > 0 && $_udraw_settings['goepower_send_file_after_order']) {  
                // Create/Update Customer in Epower.
                $goepower_customer =
                $goepower->create_user($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_company_id'] . "_". $billing_email, $user_id,
                                        $_udraw_settings['goepower_producer_id'], $_udraw_settings['goepower_company_id'],
                                        $billing_first_name, $billing_last_name, $billing_company,
                                        $billing_address_1, $billing_address_2, "", $billing_city,
                                        $billing_state, $billing_postcode, $billing_country_name, "",
                                        $billing_email, "", "", "", "", "");
                
                if (!is_null($goepower_customer)) {
                    if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                        $shipping_first_name = $order->get_shipping_first_name();
                        $shipping_last_name = $order->get_shipping_last_name();
                        $shipping_company = $order->get_shipping_company();
                        $shipping_address_1 = $order->get_shipping_address_1();
                        $shipping_address_2 = $order->get_shipping_address_2();
                        $shipping_city = $order->get_shipping_city();
                        $shipping_state = $order->get_shipping_state();
                        $shipping_postcode = $order->get_shipping_postcode();
                    } else {
                        $shipping_first_name = $order->shipping_first_name;
                        $shipping_last_name = $order->shipping_last_name;
                        $shipping_company = $order->shipping_company;
                        $shipping_address_1 = $order->shipping_address_1;
                        $shipping_address_2 = $order->shipping_address_2;
                        $shipping_city = $order->shipping_city;
                        $shipping_state = $order->shipping_state;
                        $shipping_postcode = $order->shipping_postcode;
                    }
                    // Submit order that we have created/updated user in GoEpower.
                    $goepower_order =
                    $goepower->create_order($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_producer_id'],
                                            $_udraw_settings['goepower_company_id'], $goepower_customer->Username, "",
                                            $_udraw_settings['goepower_additional_notify_email'], $order_id, $order->get_date_completed(), 'false',
                                            $shipping_first_name, $shipping_last_name, $shipping_company,
                                            $shipping_address_1, $shipping_address_2, "", $shipping_city,
                                            $shipping_state, $shipping_postcode, $shipping_country_name, "",
                                            $goepower_customer->Username, $order_items_array, number_format($order_subtotal_price, 2), 
                                            $order_total_shipping, $order_tax_label_1, $order_tax_label_2,
                                            number_format($order_tax_total_1, 2), number_format($order_tax_total_2, 2),
                                            $order_total_price);

                    if ($goepower_order == null) {
                        $order->add_order_note("Failed to send GoEpower order.");
                    } else {
                        $order->add_order_note("Sent order to GoEpower - Order Id [" . $goepower_order->OrderID . "]");
                    }                   
                } else {
                    $order->add_order_note("Failed to send GoEpower order.");
                }
            }
            
        }
        
        private function __getPostData($url, $data) {            
            $uDrawUtil = new uDrawUtil();
            return $uDrawUtil->get_web_contents($url, http_build_query($data));
        }
       
        public function __downloadFile($url, $path) {
            $uDrawUtil = new uDrawUtil();
            $uDrawUtil->download_file($url, $path);
        }
    }
}

?>
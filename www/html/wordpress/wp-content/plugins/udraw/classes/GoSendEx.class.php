<?php

if (!class_exists('GoSendEx')) {
    class GoSendEx {
        
        function __contsruct() { }

        public function init() {
            //Should trigger after the production files have been generated.
            //add_action('woocommerce_order_status_processing', array(&$this, 'order_status_processing'), 10, 1);

            add_action( 'woocommerce_order_actions', array( $this, 'add_order_meta_box_actions' ) );
            add_action( 'woocommerce_order_action_send_to_gosendex', array( $this, 'process_order_submit_to_gosendex' ) );            
        }
        
        function add_order_meta_box_actions ($actions) {
            $actions['send_to_gosendex'] = __( 'Submit to GoSendEx', 'udraw' );
            return $actions;
        }

        function process_order_submit_to_gosendex($order) {
            $order_id = $order->get_id();
            $this->order_status_processing($order_id);
        }

        public function order_status_processing($order_id) {
            error_log('GoSendEx:: Preparing to process order.');
            $order = new WC_Order($order_id);

            $uDrawSettings = new uDrawSettings();
            $settings = $uDrawSettings->get_settings();
            $is_valid = false;
            if (!is_null($settings['gosendex_api_key'])) {
                if (strlen($settings['gosendex_api_key']) > 0) {
                    if ($this->validate_key($settings['gosendex_api_key'])) {
                        $is_valid = true;
                    }
                }
            }

            if (!$is_valid) { return; }

            $_order = $order->get_items();
            $order_id = $order->get_id();
            $gse_file_set = array();            
            foreach ($_order as $order_item_id => $order_product_detail) {
                if (isset($order_product_detail['udraw_data']['udraw_options_uploaded_files'])) {
                    if (strlen($order_product_detail['udraw_data']['udraw_options_uploaded_files']) > 0) {
                        $uploaded_files = json_decode(stripcslashes($order_product_detail['udraw_data']['udraw_options_uploaded_files']));

                        for ($x = 0; $x < count($uploaded_files); $x++) {
                            $gse_file = new stdClass();
                            $gse_file->FileURL = $uploaded_files[$x]->url;
                            $gse_file->Filename = $uploaded_files[$x]->name;
                            $gse_file->BasePath = $order_item_id;
                            array_push($gse_file_set, $gse_file);
                        }
                    }
                }

                if (isset($order_product_detail['_udraw_pdf_xref'])) {
                    if (strlen($order_product_detail['_udraw_pdf_xref']) > 0) {
						if (strpos($order_product_detail['_udraw_pdf_xref'], UDRAW_ORDERS_DIR) !== false) {
							$order_product_detail['_udraw_pdf_xref'] = str_replace(UDRAW_ORDERS_DIR, UDRAW_ORDERS_URL, $order_product_detail['_udraw_pdf_xref']);
						}
                        $gse_file = new stdClass();
                        $gse_file->FileURL = $order_product_detail['_udraw_pdf_xref'];
                        $gse_file->Filename = pathinfo($order_product_detail['_udraw_pdf_xref'], PATHINFO_BASENAME);
                        $gse_file->BasePath = $order_item_id;
                        array_push($gse_file_set, $gse_file);
                    }
                }

                if (isset($order_product_detail['_udraw_jpg_pages'])) {
					if (is_array($order_product_detail['_udraw_jpg_pages']) && count($order_product_detail['_udraw_jpg_pages']) > 0) {
						$jpg_arr_length = count($order_product_detail['_udraw_png_pages']);
						for($j = 0; $j < $jpg_arr_length; $j++) {
							$gse_file = new stdClass();
							$gse_file->FileURL = $order_product_detail['_udraw_jpg_pages'][$j];
							$gse_file->Filename = pathinfo($order_product_detail['_udraw_jpg_pages'][$j], PATHINFO_BASENAME);
							$gse_file->BasePath = $order_item_id;
							array_push($gse_file_set, $gse_file);
						}
                    }
                }
                if (isset($order_product_detail['_udraw_png_pages'])) {
                    if (is_array($order_product_detail['_udraw_png_pages']) && count($order_product_detail['_udraw_png_pages']) > 0) {
						$png_arr_length = count($order_product_detail['_udraw_png_pages']);
						for($i = 0; $i < $png_arr_length; $i++) {
							$gse_file = new stdClass();
							$gse_file->FileURL = $order_product_detail['_udraw_png_pages'][$i];
							$gse_file->Filename = pathinfo($order_product_detail['_udraw_png_pages'][$i], PATHINFO_BASENAME);
							$gse_file->BasePath = $order_item_id;
							array_push($gse_file_set, $gse_file);
						}
                    }
                } 				
            }

            $gse_order_xml = new stdClass();
            $gse_order_xml->FileURL = "Base64//" . base64_encode($this->generate_order_xml($order_id));
            $gse_order_xml->Filename = "WP_" . $order_id . ".xml";
            
            array_push($gse_file_set, $gse_order_xml);
            if (!empty($gse_file_set)) {
                $gse_meta_data = array();
                array_push($gse_meta_data, $this->_generate_meta_data_object("Order Id", $order_id));
                $data = array('files' => json_encode($gse_file_set), 'fileName' => "WP_" . $order_id, 'metaData' => json_encode($gse_meta_data));
                $post_upload_response = $this->_post_uploadv2_api($data);

                if ($post_upload_response != null) {
                    $post_upload_response = json_decode($post_upload_response);
                    if (is_object($post_upload_response)) {
                        if ($post_upload_response->successful) {
                            $order->add_order_note("[GoSendEx] " . $post_upload_response->message);
                            $this->send_email_with_files($post_upload_response->data, $order_id);
                        }
                    }                    
                }
            } 
        } 

        public function send_email_with_files($upload_token, $order_id) {
            $uDrawSettings = new uDrawSettings();
            $settings = $uDrawSettings->get_settings();
            if (isset($settings['gosendex_send_email_after_order_sent']) && isset($settings['gosendex_email_to_send_notification'])) {
                if ($settings['gosendex_send_email_after_order_sent']) {
                    $send_to_email = $settings['gosendex_email_to_send_notification'];
					$gosendex_domain = $settings['gosendex_domain'];
					$getReponseToken = $upload_token->token;
					$download_link = 'https://' . $gosendex_domain . '/Files/Download/' . $getReponseToken;
					$website_name = get_bloginfo( 'name' );
					$subject = 'Files for Order Number ' . $order_id . ' received on ' . $website_name;
					$body = '<p>Hello,</p><p>Files for order# ' . $order_id . ' are ready to be downloaded.</p><p><a href="' . $download_link . '">Download Files</a></p>';

					$content_type = function() { return 'text/html'; };
					add_filter( 'wp_mail_content_type', $content_type );
					wp_mail( $send_to_email, $subject, $body );
					remove_filter( 'wp_mail_content_type', $content_type );
                }
            }
        }
              
        public function get_gosendex_api_url() {
            return "https://gosendex.w2pstore.com/Api";
        }

        public function get_api_key() {
            $uDrawSettings = new uDrawSettings();
            $settings = $uDrawSettings->get_settings();
            return (!is_null($settings['gosendex_api_key'])) ? $settings['gosendex_api_key'] : "";
        }

        public function validate_key($key) {
            $uDrawUtil = new uDrawUtil();            
            $request = $this->get_gosendex_api_url() . "?action=validate-key&key=" . $key;
            $response = json_decode($uDrawUtil->get_web_contents($request));        
            if ($response != null) {
                if (is_object($response)) {
                    return $response->successful;
                }
            }

            return false;
        }   
        
        public function generate_order_xml($order_id) {            
            $order = new WC_Order($order_id);

            $order_xml = new SimpleXMLElement("<Order></Order>");
            $order_xml->addChild("OrderId",                 $order_id);
            $order_xml->addChild("OrderCreatedDate",        $order->get_date_created());
            $order_xml->addChild("OrderPaidDate",           $order->get_date_paid());
            $order_xml->addChild("OrderStatus",             $order->get_status());
            $order_xml->addChild("OrderCurrency",           $order->get_currency());

            // Billing Information
            $order_xml->addChild("BillingFirstName",        $order->get_billing_first_name());
            $order_xml->addChild("BillingLastName",         $order->get_billing_last_name());
            $order_xml->addChild("BillingFullName",         $order->get_formatted_billing_full_name());
            $order_xml->addChild("BillingCompany",          $order->get_billing_company());
            $order_xml->addChild("BillingAddress1",         $order->get_billing_address_1());
            $order_xml->addChild("BillingAddress2",         $order->get_billing_address_2());
            $order_xml->addChild("BillingCity",             $order->get_billing_city());
            $order_xml->addChild("BillingStateProvince",    $order->get_billing_state());
            $order_xml->addChild("BillingZipPostal",        $order->get_billing_postcode());
            $order_xml->addChild("BillingCountry",          $order->get_billing_country());
            $order_xml->addChild("BillingPhone",            $order->get_billing_phone());
            $order_xml->addChild("BillingEmail",            $order->get_billing_email());

            // Shipping Information
            $order_xml->addChild("ShippingFirstName",        $order->get_shipping_first_name());
            $order_xml->addChild("ShippingLastName",         $order->get_shipping_last_name());
            $order_xml->addChild("ShippingFullName",         $order->get_formatted_shipping_full_name());
            $order_xml->addChild("ShippingCompany",          $order->get_shipping_company());
            $order_xml->addChild("ShippingAddress1",         $order->get_shipping_address_1());
            $order_xml->addChild("ShippingAddress2",         $order->get_shipping_address_2());
            $order_xml->addChild("ShippingCity",             $order->get_shipping_city());
            $order_xml->addChild("ShippingStateProvince",    $order->get_shipping_state());
            $order_xml->addChild("ShippingZipPostal",        $order->get_shipping_postcode());
            $order_xml->addChild("ShippingCountry",          $order->get_shipping_country());

            // Shipping and Payment Methods
            $order_xml->addChild("ShippingMethod",           $order->get_shipping_method());
            $order_xml->addChild("PaymentMethod",            $order->get_payment_method_title());
            
            // Order Totals and Note
            $order_xml->addChild("ShippingTotal",            $order->get_shipping_total());
            $order_xml->addChild("ShippingTaxTotal",         $order->get_shipping_tax());            
            $order_xml->addChild("DiscountTotal",            $order->get_total_discount());
            $order_xml->addChild("OrderSubTotal",            $order->get_subtotal());
            $order_xml->addChild("OrderTotal",               $order->get_total());
            $order_xml->addChild("FeeTotal",                 $order->get_total_fees());
            $order_xml->addChild("TaxTotal",                 (isset($order->get_tax_totals()['amount'])) ? $order->get_tax_totals()['amount'] : 0.00);
            $order_xml->addChild("CustomerId",               $order->get_customer_id());
            $order_xml->addChild("CustomerNote",             $order->get_customer_note());            

            $order_line_items = $order_xml->addChild("OrderLineItems");

            $order_items = $order->get_items(); //to get info about product

            foreach ($order_items as $order_item_id => $order_product_detail) {
                // Get Product Object
                $product = wc_get_product( $order_product_detail['product_id'] );

                // Determine Qty purchased while taking Price Matrix into consideration.
                $quantity = $order_product_detail['quantity'];
                if (isset($order_product_detail['udraw_data'])) {
                    if (isset($order_product_detail['udraw_data']['udraw_price_matrix_qty'])) {
                        $quantity = $order_product_detail['udraw_data']['udraw_price_matrix_qty'];
                    }
                }

                $order_line = $order_line_items->addChild("OrderLineItem");
                $order_line->addChild("Id",                 $order_item_id);
                $order_line->addChild("ProductId",          $order_product_detail['product_id']);
                $order_line->addChild("Name",               $order_product_detail['name']);
                $order_line->addChild("SKU",                $product->get_sku());
                $order_line->addChild("Quantity",           $quantity);
                $order_line->addChild("Subtotal",           $order_product_detail['subtotal']);
                $order_line->addChild("SubtotalTax",        $order_product_detail['subtotal_tax']);
                $order_line->addChild("Total",              $order_product_detail['total']);
                $order_line->addChild("TotalTax",           $order_product_detail['total_tax']);
                $order_line->addChild("ProductionPDF",      (isset($order_product_detail['_udraw_pdf_xref'])) ? $order_product_detail['_udraw_pdf_xref'] : "");
				
				//Production PNG Files
				if (isset($order_product_detail['_udraw_png_pages'])) {
                    if (is_array($order_product_detail['_udraw_png_pages']) && count($order_product_detail['_udraw_png_pages']) > 0) {
						$attached_pngs = count($order_product_detail['_udraw_png_pages']);
						$udraw_png_files = $order_line->addChild("ProductionPNGs");   
						for($y = 0; $y < $attached_pngs; $y++) {
							$udraw_png_file = $udraw_png_files->addChild("ProductionPNGFile", (isset($order_product_detail['_udraw_png_pages'][$y])) ? $order_product_detail['_udraw_png_pages'][$y] : "");
						}
					}
				}
				
				//Production JPG Files
				if (isset($order_product_detail['_udraw_jpg_pages'])) {
                    if (is_array($order_product_detail['_udraw_jpg_pages']) && count($order_product_detail['_udraw_jpg_pages']) > 0) {
						$attached_jpgs = count($order_product_detail['_udraw_jpg_pages']);
						$udraw_jpg_files = $order_line->addChild("ProductionJPGs");   
						for($z = 0; $z < $attached_jpgs; $z++) {
							$udraw_jpg_file = $udraw_png_files->addChild("ProductionJPGFile", (isset($order_product_detail['_udraw_jpg_pages'][$z])) ? $order_product_detail['_udraw_jpg_pages'][$z] : "");
						}
					}
				}

                // Price Matrix Options
                if (isset($order_product_detail['udraw_data']['udraw_price_matrix_selected_options_object'])) {
                    if (strlen($order_product_detail['udraw_data']['udraw_price_matrix_selected_options_object']) > 0) {
                        $price_matrix_options = json_decode(stripcslashes($order_product_detail['udraw_data']['udraw_price_matrix_selected_options_object']));
                        $pm_options = $order_line->addChild("Options");
						for ($y = 0; $y < count($price_matrix_options); $y++) {
							$_pm_option = $pm_options->addChild("Option");                            
                            $_pm_name = strtolower($price_matrix_options[$y]->name);
                            $_pm_value = strtolower($price_matrix_options[$y]->value);
                            $_pm_option->addAttribute("Key", $_pm_name);
                            $_pm_option->addAttribute("Value", $_pm_value);
						}
                    }
                }

                // Attached Documents
                if (isset($order_product_detail['udraw_data']['udraw_options_uploaded_files'])) {
                    if (strlen($order_product_detail['udraw_data']['udraw_options_uploaded_files']) > 0) {
                        $uploaded_files = json_decode(stripcslashes($order_product_detail['udraw_data']['udraw_options_uploaded_files']));

                        $udraw_attachments = $order_line->addChild("Attachments");                        
                        for ($x = 0; $x < count($uploaded_files); $x++) {  
                            $udraw_attachment = $udraw_attachments->addChild("Attachment");
                            $udraw_attachment->addAttribute("Key", $uploaded_files[$x]->name);
                            $udraw_attachment->addAttribute("Value", $uploaded_files[$x]->url);
                        }
                    }
                }

            }
            return $order_xml->asXML();
        }
        
        private function _generate_meta_data_object($key, $value) {
            $meta_item = new stdClass();
            $meta_item->label = $key;
            $meta_item->userData = array($value);
            return $meta_item;
        }

        private function _post_upload_api($data) {            
            $uDrawUtil = new uDrawUtil();
            $url = $this->get_gosendex_api_url() . '/Upload';
            return $uDrawUtil->get_web_contents($url, http_build_query($data), array('ApiKey' => $this->get_api_key()));

        }

        private function _post_uploadv2_api($data) {            
            $uDrawUtil = new uDrawUtil();
            $url = $this->get_gosendex_api_url() . '/UploadV2';        
            return $uDrawUtil->get_web_contents($url, http_build_query($data), array('ApiKey' => $this->get_api_key()));
        }
    }
}

?>
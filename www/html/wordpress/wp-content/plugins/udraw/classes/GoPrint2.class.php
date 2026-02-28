<?php

if (!class_exists('GoPrint2')) {
    class GoPrint2 {
        
        function __contsruct() { }
        
        public function init() {
            $uDrawSettings = new uDrawSettings();
            $settings = $uDrawSettings->get_settings();
            if (!is_null($settings['goprint2_api_key'])) {
                if (strlen($settings['goprint2_api_key']) > 0) {
                    if ($this->validate_key($settings['goprint2_api_key'])) {
                        add_action('udraw_admin_product_panel', array(&$this,'udraw_admin_product_panel'));
                        add_action('udraw_admin_save_custom_fields', array(&$this,'udraw_admin_save_custom_fields'), 10, 1);                        
                        add_action('udraw_send_thirdparty_order', array(&$this,'udraw_send_thirdparty_order'), 10, 5);
                    }
                }
            }
        }
        
        public function udraw_admin_product_panel() {
            require_once(UDRAW_PLUGIN_DIR . '/goprint2/templates/admin/admin-product-panel.php');
        }

        public function udraw_admin_save_custom_fields($post_id) {
            if (isset($_POST['_udraw_goprint2_order_route_email'])) {
                update_post_meta($post_id, '_udraw_goprint2_order_route_email', $_POST['_udraw_goprint2_order_route_email']);
            }    
        }        
        
        public function udraw_send_thirdparty_order($order, $items, $total_price, $total_shipping, $total_taxes) {
            global $woocommerce;
            $uDrawSettings = new uDrawSettings();
            $settings = $uDrawSettings->get_settings();
            $goprint2 = new GoPrint2();
            $udrawUtil = new uDrawUtil();
            
            foreach ($items as $item) {
                if (strlen($item->GoPrint2RouteEmail) > 0) {
                    $gp2_files = basename($item->PDFFileName) . '%' . $item->PDFFileName . '@';
                    // TODO: Support uploaded attached documents.
                    if (strlen($gp2_files) > 3) {
                        // We will first create/get customer
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
                        }
                        $gp2_customer_id = $goprint2->create_customer($settings['goprint2_api_key'],
                                                                        $billing_first_name, $billing_last_name,
                                                                        $billing_company, $billing_address_1, $billing_address_2,
                                                                        $billing_city, $billing_state, $billing_country,
                                                                        $billing_postcode, $billing_phone, "", $billing_email);

                        // Now wen can submit the jobs to GoPrint2
                        $gp2_download_key = $goprint2->create_new_order($settings['goprint2_api_key'], $gp2_customer_id, $gp2_files, "uDraw-Route");
                        
                        // Send Email Notification.
                        $email_body = $udrawUtil->get_web_contents("https://www.goprint2.com/common/view_ticket.aspx?DownloadId=". $gp2_download_key . "&From=store&AID=". $settings['goprint2_api_key']);
                        $result = wp_mail($item->GoPrint2RouteEmail, "[Job Submitted] - ". $order_id, $email_body);
                    }
                }
            }
        }        
                        
        public function validate_key($key) {
            $data = array ('key' => $key, 'action' => 'validate');            
            return json_decode($this->_post_Api($data));
        }
        
        public function get_store_info($key) {
            $data = array ('key' => $key, 'action' => 'store-info');
            return json_decode($this->_post_Api($data));
        }
        
        public function get_email_settings($key) {
            $data = array ('key' => $key, 'action' => 'email');
            return json_decode($this->_post_Api($data));
        }
        
        public function get_ftp_settings($key) {
            $data = array ('key' => $key, 'action' => 'ftp');
            return json_decode($this->_post_Api($data));            
        }
        
        public function create_customer($key, $firstname, $lastname, $company, $address1, $address2, $city, $province, $country, $postal, $phone, $fax, $email) {            
            $data = array ('key' => $key,
                           'mKey' => 'srfhml3XRKMJQAfavHELCv8Op29LOlzIvbS4kJTK3QKGOnyKbwWSaT9M9o1C',
                           'action' => 'create-customer',
                           'firstname' => $firstname,
                           'lastname' => $lastname,
                           'company' => $company,
                           'address1' => $address1,
                           'address2' => $address2,
                           'city' => $city,
                           'province' => $province,
                           'country' => $country,
                           'postal' => $postal,
                           'phone' => $phone,
                           'fax' => $fax,
                           'email' => $email);
            
            return json_decode($this->_post_Api($data));
        }
        
        public function create_new_order($key, $customerId, $files, $uploadMethod) {            
            $data = array('key' => $key,
                          'mKey' => 'srfhml3XRKMJQAfavHELCv8Op29LOlzIvbS4kJTK3QKGOnyKbwWSaT9M9o1C',
                          'action' => 'create-new-order',
                          'customerId' => $customerId,
                          'uploadMethod' => $uploadMethod,
                          'uploadFiles' => $files);
            
            return json_decode($this->_post_Api($data));
        }
        
        private function _post_Api($data) {
            $url = 'https://www.goprint2.com/api/V1.aspx';
            $uDrawUtil = new uDrawUtil();
            return $uDrawUtil->get_web_contents($url, http_build_query($data));
        }
    }
}

?>
<?php

if (!class_exists('uDrawCustomerDesigns')) {
    class uDrawCustomerDesigns extends uDrawAjaxBase {                
        
        function __contsruct() { }
        
        public function init_actions() {
            add_action( 'wp_ajax_udraw_update_customer_design', array(&$this,'update_customer_design') );
            add_action( 'wp_ajax_udraw_duplicate_customer_design', array(&$this,'duplicate_customer_design') );
            add_action( 'wp_ajax_udraw_remove_customer_design', array(&$this,'remove_customer_design') );
        }
        
        function update_customer_design($access_key = "", $name = "My Design") {            
            global $wpdb;
            
            if (isset($_REQUEST['access_key'])) {
                $access_key = $_REQUEST['access_key'];
            }
            
            if (isset($_REQUEST['name'])) {
                $name = $_REQUEST['name'];
            }
            
            $response = $wpdb->update($this->udraw_customer_designs_table, array(
                'name' => $name),  
            array(
                'access_key' => $access_key
            ));
            
            $this->sendResponse($response);
        }
        
        function duplicate_customer_design($access_key = "") { 
            global $wpdb;
            $uDraw = $this->uDraw;
            
            if (isset($_REQUEST['access_key'])) {
                $access_key = $_REQUEST['access_key'];
            }
            
            $customer_design = $uDraw::get_udraw_customer_design($access_key);
            $designFile = $customer_design['design_data'];
			$designFilePath = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $designFile);
            $new_access_key = uniqid('udraw_');
			$username = wp_get_current_user()->user_login;
			
            if (file_exists($designFilePath)) {
                //Save a different design file as file in references based on access key on designer.
                $get_design_data =  file_get_contents($designFilePath);
                $udraw_product_data_file = $new_access_key . '_usdf.xml';
                $new_design_file_path = UDRAW_STORAGE_DIR . $username . '/output/' . $udraw_product_data_file;
				$new_design_file = UDRAW_STORAGE_URL . $username . '/output/' . $udraw_product_data_file;
                file_put_contents($new_design_file_path, $get_design_data);
            }
            
            if (isset($customer_design['post_id'])) {
                $dt = new DateTime();
                // insert new design
                $response = $wpdb->insert($this->udraw_customer_designs_table, array(
                    'post_id' => $customer_design['post_id'],
                    'customer_id' => wp_get_current_user()->ID,
                    'preview_data' => $customer_design['preview_data'],
                    'design_data' => $new_design_file,
                    'create_date' => $dt->format('Y-m-d H:i:s'),
                    'access_key' => $new_access_key,
                    'price_matrix_options' => $customer_design['price_matrix_options'],
                    'name' => 'Copy of ' . $customer_design['name']
                ));
                $this->sendResponse($response);
            } else {
                $this->sendResponse(false);
            } 
        }
        
        function remove_customer_design($access_key = "") {
            global $wpdb;
            
            if (isset($_REQUEST['access_key'])) {
                $access_key = $_REQUEST['access_key'];
            }
            
            if (isset($_REQUEST['customer_id'])) {
                $customer_id = $_REQUEST['customer_id'];
            }
            
            $wpdb->delete( $this->udraw_customer_designs_table, 
                array(
                'access_key' => $access_key, 
                'customer_id' => $customer_id
                )
            );
        }
    }
}

?>
<?php

if (!class_exists('uDrawClipart')) {
    
    /**
     * uDraw Clipart Class will handle all clipart related requests including AJAX requests.
     */
    class uDrawClipart extends uDrawAjaxBase {                
        
        function __contsruct() { }
        
        public function init_actions() {
            add_action( 'wp_ajax_udraw_related_clipart', array(&$this,'get_related_clipart') );
            add_action( 'wp_ajax_udraw_update_clipart_tags', array(&$this,'update_clipart_tags') );
            add_action( 'wp_ajax_udraw_add_clipart_category', array(&$this,'add_clipart_category') );
            add_action( 'wp_ajax_udraw_remove_clipart_category', array(&$this,'remove_clipart_category') );
            add_action( 'wp_ajax_udraw_remove_clipart', array(&$this, 'remove_clipart') );
            add_action( 'wp_ajax_udraw_assign_clipart', array(&$this, 'assign_clipart') );
            add_action( 'wp_ajax_udraw_update_clipart_category', array(&$this, 'update_clipart_category') );
            add_action( 'wp_ajax_udraw_retrieve_clipart', array(&$this, 'retrieve_clipart') );
            
            add_action( 'wp_ajax_nopriv_udraw_retrieve_clipart', array(&$this, 'retrieve_clipart') );
        }
        
        function get_related_clipart($clipart_id = 0) {
            if (isset($_REQUEST['clipart_id'])) {
                $clipart_id = $_REQUEST['clipart_id'];
            }
            
            $clipartArray = array();
            $sourceClipart = $this->uDraw->get_udraw_clipart($clipart_id);
            $sourceClipart = $sourceClipart[0];
            
            if (!is_null($sourceClipart->tags)) {
                $sourceTags = explode(',',$sourceClipart->tags);
                $allClipart = $this->uDraw->get_udraw_clipart();                
                for ($y = 0; $y < count($allClipart); $y++) {                    
                    if (!is_null($allClipart[$y]->tags)) {
                        $_tags = explode(',', $allClipart[$y]->tags);                        
                        foreach ($_tags as $_tag) {
                            $foundMatch = false;
                            foreach ($sourceTags as $sourceTag) {
                                if ($_tag == $sourceTag) {                                   
                                    array_push($clipartArray, $allClipart[$y]);
                                    $foundMatch = true;
                                    break;
                                }
                            }
                            if ($foundMatch) { break; }
                        }
                    } else {
                        continue;
                    }                    
                }
            }
            
            return $this->sendResponse($clipartArray);
        }
        
        function update_clipart_tags($clipart_id = 0, $tags = "") {
            global $wpdb;
            
            if (isset($_REQUEST['clipart_id'])) {
                $clipart_id = $_REQUEST['clipart_id'];                
            }
            
            if (isset($_REQUEST['tags'])) {
                $tags = $_REQUEST['tags'];
            }
            
            $response;
            if ($clipart_id > 0) {
                $response = $wpdb->update($this->udraw_clipart_table, array(
                    'tags' => $tags
                ), array(
                    'id' => $clipart_id
                ));                
            }
            $response = $wpdb->get_var("SELECT tags FROM $this->udraw_clipart_table WHERE ID=$clipart_id");
            return $this->sendResponse($response);
        }
        
        function add_clipart_category($category_name = '', $parent_id = 0) {
            global $wpdb;
            $response = false;
            if (isset($_REQUEST['category_name']) && isset($_REQUEST['parent_id'])) {
                $category_name = $_REQUEST['category_name'];
                $parent_id = $_REQUEST['parent_id'];
            }
            if (strlen($category_name) > 0) {
                $response = $wpdb->insert($this->udraw_clipart_category_table, array('category_name'=>$category_name, 'parent_id'=>$parent_id));
                return $this->sendResponse($response);
            }
        }
        
        function remove_clipart_category($category = 0) {
            global $wpdb;
            if (isset($_REQUEST['category'])) {
                $category = $_REQUEST['category'];
            }
            $wpdb->update($this->udraw_clipart_table, array('category' => ''), array('category' => $category));
            $response = $wpdb->delete($this->udraw_clipart_category_table, array('ID'=>$category));
            return $this->sendResponse($response);
        }
        
        function remove_clipart ($accesskey = '') {
            global $wpdb;
            if (current_user_can('delete_udraw_clipart_upload')) {
                if (isset($_REQUEST['access_key'])) {
                    $accesskey = $_REQUEST['access_key'];
                }
                $result = $wpdb->get_row("SELECT * FROM $this->udraw_clipart_table WHERE access_key = '$accesskey'");
                if (count($result) > 0) {
                    $image_name = $result->image_name;
                    $clipart_file = UDRAW_CLIPART_DIR . $image_name;
                    if (file_exists($clipart_file)) {
                        unlink($clipart_file);
                        $response = $wpdb->delete($this->udraw_clipart_table, array('access_key' => $accesskey));
                        return $this->sendResponse($response);
                    }
                }
            }
        }
        
        function assign_clipart ($category = 0, $accessKey = '') {
            global $wpdb;
            if (current_user_can('edit_udraw_clipart_upload')) {
                if (isset($_REQUEST['access_key']) && isset($_REQUEST['category'])){
                    $accessKey = $_REQUEST['access_key'];
                    $category = $_REQUEST["category"];
                }
                if (strlen($accessKey) > 0) {
                    $response = $wpdb->update($this->udraw_clipart_table, array('category' => $category), array('access_key' => $accessKey));
                    return $this->sendResponse($response);
                }
            }
        }
        
        function update_clipart_category ($category_id = 0, $category_name = '', $parent_id = 0) {
            global $wpdb;
            if (isset($_REQUEST['category_id']) && isset($_REQUEST['category_name']) && isset($_REQUEST['parent_id'])) {
                $category_id = $_REQUEST['category_id'];
                $category_name = $_REQUEST['category_name'];
                $parent_id = $_REQUEST['parent_id'];
            }
            if (strlen($category_name) > 0) {
                $response = $wpdb->update($this->udraw_clipart_category_table, array('category_name'=>$category_name, 'parent_id'=>$parent_id), array('ID'=>$category_id));
                return $this->sendResponse($response);
            }
        }
        
        function retrieve_clipart () {
            global $wpdb;
            $clipart_category = $wpdb->get_results("SELECT * FROM $this->udraw_clipart_category_table");
            $clipart = $wpdb->get_results("SELECT * FROM $this->udraw_clipart_table");
            return $this->sendResponse(array($clipart, $clipart_category));
        }
    }
}

?>
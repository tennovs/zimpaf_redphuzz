<?php

if (!class_exists('uDrawTemplates')) {
    
    /**
     * uDraw Templates Class will handle all template related requests including AJAX requests.
     */
    class uDrawTemplates extends uDrawAjaxBase {                
        
        public $_templates_category_list = array();
        public $_templates_category_array = array();
        
        function __contsruct() {
        }
        
        function init_actions() {
            // uDraw Template AJAX Actions
            add_action( 'wp_ajax_udraw_create_template', array(&$this, 'create_template') );
            add_action( 'wp_ajax_udraw_related_templates', array(&$this,'get_related_templates') );            
            add_action( 'wp_ajax_udraw_update_template_tags', array(&$this, 'update_template_tags') );
            add_action( 'wp_ajax_udraw_update_template_name', array(&$this, 'update_template_name') );
            add_action( 'wp_ajax_udraw_get_templates', array(&$this, 'get_templates') );
            add_action( 'wp_ajax_udraw_get_templates_id', array(&$this,'get_templates_id') );
            add_action( 'wp_ajax_udraw_get_template_stats', array(&$this,'get_template_stats') );
            add_action( 'wp_ajax_udraw_get_template_data', array(&$this, 'get_template_data') );
            add_action( 'wp_ajax_udraw_add_template_category', array(&$this, 'add_template_category') );
            add_action( 'wp_ajax_udraw_remove_template_category', array(&$this, 'remove_template_category') );
            add_action( 'wp_ajax_udraw_apply_template_category', array(&$this, 'apply_template_category') );
            add_action( 'wp_ajax_udraw_detach_template_category', array(&$this, 'detach_template_category') );
            add_action( 'wp_ajax_udraw_update_template_category', array(&$this, 'update_template_category') );
            
            add_action( 'wp_ajax_nopriv_udraw_related_templates', array(&$this,'get_related_templates') );
            add_action( 'wp_ajax_nopriv_udraw_get_templates_id', array(&$this,'get_templates_id') );
            add_action( 'wp_ajax_nopriv_udraw_get_template_stats', array(&$this,'get_template_stats') );
        }
        
        function create_template($response ="", $output_path = "", $template_id = 0, $template_name = '', $doc_width = 0, $doc_height = 0, $doc_pages = 1) {
            
            if (!current_user_can('edit_udraw_templates')) {
                return $this->sendResponse("invalid permissions");
            }
            
            global $wpdb;
            
            if (isset($_REQUEST['response'])) {
                $response = $_REQUEST['response'];
            }
            
            if (isset($_REQUEST['output_path'])) {
                $output_path = $_REQUEST['output_path'];
            }
            
            if (isset($_REQUEST['template_id'])) {
                $template_id = $_REQUEST['template_id'];
            }
            
            if (isset($_REQUEST['template_name'])) {
                $template_name = $_REQUEST['template_name'];
            }
            
            if (isset($_REQUEST['doc_width'])) {
                $doc_width = $_REQUEST['doc_width'];
            }
            
            if (isset($_REQUEST['doc_height'])) {
                $doc_height = $_REQUEST['doc_height'];
            }
            
            if (isset($_REQUEST['doc_pages'])) {
                $doc_pages = $_REQUEST['doc_pages'];
            }
            
            if (strlen($response) == 0 || substr_count($response, ",") == 0) {
                return $this->sendResponse("invalid response");
            }
            
            // Designer Response
            $response = explode(",", $response);
            $outputPath = $output_path;
            
            if (count($response) < 2) { return $this->sendResponse("invalid response length"); }
            
            $pdf = $outputPath . $response[0];
            $design = $outputPath . $response[1];
            $preview = $outputPath. $response[2];		
            $dt = new DateTime();
            
            if (strlen($template_id) > 0) {
                // Update existing record in DB.
                $result = $wpdb->update($this->udraw_templates_table, array(
                    'name' => base64_decode($template_name),
                    'design' => $design,
                    'preview' => $preview,
                    'pdf' => $pdf,
                    'modify_date' => $dt->format('Y-m-d H:i:s'),
                    'design_width' => $doc_width,
                    'design_height' => $doc_height,
                    'design_pages' => $doc_pages
                ), array(
                    'id' => $template_id
                ));
                $udraw_settings = $this->uDrawSettings->get_settings();
                if ($udraw_settings['update_product_images']) {
                    $this->uDraw->replace_all_product_images($template_id, 0);
                }
                
                return $this->sendResponse($result);
            } else {
                if ($this->uDraw->_is_u_valid()) {
                    // Create new record in DB.
                    $result = $wpdb->insert($this->udraw_templates_table, array(
                            'name' => base64_decode($template_name),
                            'design' => $design,
                            'preview' => $preview,
                            'pdf' => $pdf,
                            'create_date' => $dt->format('Y-m-d H:i:s'),
                            'create_user' => wp_get_current_user()->user_login,
                            'design_width' => $doc_width,
                            'design_height' => $doc_height,
                            'design_pages' => $doc_pages					
                        ));
                    return $this->sendResponse($result);
                }
            }   
        }
        
        /**
         * This will return all related templates based on the tags assigned to it.
         * If this is an AJAX request, it will return a JSON response, otherwise an Array of templates will be returned.
         * 
         * @param mixed $template_id Template to Lookup
         * 
         * @return array
         */
        function get_related_templates($template_id = 0) {
            if (isset($_REQUEST['template_id'])) {
                $template_id = $_REQUEST['template_id'];
            }
            
            $templatesArray = array();
            $sourceTemplate = $this->uDraw->get_udraw_templates($template_id);
            $sourceTemplate = $sourceTemplate[0];
            if (!is_null($sourceTemplate->tags) && strlen($sourceTemplate->tags) > 0) {
                $sourceTemplate = $this->uDraw->get_udraw_templates($template_id);
                $sourceTemplate = $sourceTemplate[0];
                $sourceTags = explode(',',$sourceTemplate->tags);
                
                global $wpdb;
                $tags_table = $wpdb->prefix . 'udraw_templates_tags';
                $table_check = $wpdb->get_var("SHOW TABLES LIKE '$tags_table'");
                //Check if templates tags table exists
                if ($wpdb->get_var("SHOW TABLES LIKE '$tags_table'")) {
                    $template_ids = array();
                    foreach ($sourceTags as $key => $tag) {
                        $tag_rows = $wpdb->get_results("SELECT template_id FROM $tags_table WHERE name='$tag'", ARRAY_A);
                        for ($i = 0; $i < count($tag_rows); $i++) {
                            array_push($template_ids, $tag_rows[$i]['template_id']);
                        }
                    }
                    $template_ids = implode(',', array_unique($template_ids));
                    $templates_table = $wpdb->prefix . 'udraw_templates';
                    $templates = $wpdb->get_results("SELECT * FROM $templates_table WHERE id in($template_ids)", ARRAY_A);
                    
                    $offset = 0;
                    if (isset($_REQUEST['offset'])) {
                        $offset = $_REQUEST['offset'];
                    }
                    if (isset($_REQUEST['limit'])) {
                        $templates = array_slice($templates, $offset, $_REQUEST['limit']);
                    }
                    return $this->sendResponse($templates);
                } else {
                    //Use the old method
                    $templatesArray = array();
                    $allTemplates = $this->uDraw->get_udraw_templates();                
                    for ($y = 0; $y < count($allTemplates); $y++) {                    
                        if (!is_null($allTemplates[$y]->tags)) {
                            $_tags = explode(',', $allTemplates[$y]->tags); 
                            foreach ($_tags as $_tag) {
                                $foundMatch = false;
                                foreach ($sourceTags as $sourceTag) {
                                    if ($_tag == $sourceTag) {             
                                        array_push($templatesArray, $allTemplates[$y]);
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
            }
            
            return $this->sendResponse($templatesArray);
        }
        
        /**
         * Updates the Template with a set of tags. Used to link multiple templates together.
         * 
         * @param mixed $template_id Template to Update
         * @param mixed $tags Tags (Comma separated string of values)
         */
        function update_template_tags($template_id = 0, $tags = "") {
            global $wpdb;            
            if (isset($_REQUEST['template_id'])) {
                $template_id = $_REQUEST['template_id'];                
            }
            
            if (isset($_REQUEST['tags'])) {
                $tags = $_REQUEST['tags'];
            }
            
            $response;
            if ($template_id > 0) {
                $wpdb->update($this->udraw_templates_table, array(
                    'tags' => $tags
                ), array(
                    'id' => $template_id
                ));     
                $response = $wpdb->get_var("SELECT tags FROM $this->udraw_templates_table WHERE id=$template_id");
                
                //Update the templates_tags table
                $tags = explode(',', $tags);
                $tagsTable = $wpdb->prefix . "udraw_templates_tags";
                $tagsInDB = $wpdb->get_results("SELECT * FROM $tagsTable WHERE template_id=$template_id", ARRAY_A);
                $dbTags = array();
                $toRemove = array();
                for ($i = 0; $i < count($tagsInDB); $i++) {
                    array_push($dbTags, $tagsInDB[$i]['name']);
                    if (!in_array($tagsInDB[$i]['name'], $tags)) {
                        array_push($toRemove, $tagsInDB[$i]['ID']);
                    }
                }
                for ($i = 0; $i < count($tags); $i++) {
                    if (!in_array($tags[$i], $dbTags)) {
                        $wpdb->insert(
                            $tagsTable,
                            array(
                                'name' => $tags[$i],
                                'template_id' => $template_id
                            )
                        );
                    }
                }
                $removeString = implode(',', $toRemove);
                if (strlen($removeString) > 0) {
                    //Remove deleted tags
                    $wpdb->query("DELETE FROM $tagsTable WHERE ID in ($removeString)");
                }
            }
            
            return $this->sendResponse($response);
        }
        
        /**
         * Updates the Template name.
         * 
         * @param mixed $template_id Template Id to Update
         * @param mixed $template_name New Template name.
         */
        function update_template_name($template_id = 0, $template_name = "My Template") {
            global $wpdb;
            
            if (isset($_REQUEST['template_id'])) {
                $template_id = $_REQUEST['template_id'];                
            }
            if (isset($_REQUEST['template_name'])) {
                $template_name = $_REQUEST['template_name'];                
            }
            
            $response;
            if ($template_id > 0) {
                $response = $wpdb->update($this->udraw_templates_table, array(
                    'name' => base64_decode($template_name)
                ), array(
                    'id' => $template_id
                ));                
            }
            
            return $this->sendResponse($response);
        }
        
        /**
         * Returns all templates in the system.
         * If template_id is passed, it will return just the specified template. 
         * Otherwise, all templates will be returned along with category info on the templates.
         * 
         * @param mixed $template_id (optional) Template Id to look up.
         * @param mixed $include_categories (optional) Toggle on/off the category output when all templates are returned.
         */
        function get_templates($template_id = 0, $include_categories = true) {
            if (isset($_REQUEST['template_id'])) {
                $template_id = $_REQUEST['template_id'];
            }
            if (isset($_REQUEST['include_categories'])) {
                $include_categories = filter_var($_REQUEST['include_categories'], FILTER_VALIDATE_BOOLEAN);
            }
            
            if ($template_id == 0) {
                if ($include_categories) {
                    // Return all templates and Cateogry data.
                    return $this->sendResponse(array($this->uDraw->get_udraw_templates(), $this->uDraw->get_templates_categories()), $this->isAJAXRequest());
                } else {
                    // Return all templates without Category data.
                    return $this->sendResponse($this->uDraw->get_udraw_templates());
                }
            } else {
                // Return just the template passed to us.
                return $this->sendResponse($this->uDraw->get_udraw_templates($template_id));                
            }
        }
        
        /**
         * Returns all templates based on the id(s) passed.
         * 
         * @param mixed $template_id 
         * @return mixed
         */
        function get_templates_id($templates = "") {
            if (isset($_REQUEST['templates'])) {
                $templates = $_REQUEST['templates'];
            }
            
            $template_ids = explode(",", $templates);
            $templates_response = [];
            
            $all_templates = $this->uDraw->get_udraw_templates();
            for ($y = 0; $y < count($template_ids); $y++) {
                for($x = 0; $x < count($all_templates); $x++) {
                    if ($all_templates[$x]->id == intval($template_ids[$y])) {
                        array_push($templates_response, $all_templates[$x]);
                        break;
                    }
                }
            }
            
            return $this->sendResponse($templates_response);
        }
        
        function get_template_stats() {
            $all_templates = $this->uDraw->get_udraw_templates();            
            $templates_response = [];            
            for ($y = 0; $y < count($all_templates); $y++) {
                $obj = new stdClass;
                $obj->id = $all_templates[$y]->id;
                $obj->name = $all_templates[$y]->name;
                $obj->preview = $all_templates[$y]->preview;
                array_push($templates_response, $obj);
            }
            
            return $this->sendResponse($templates_response);            
        }
        
        function get_template_data($template_path = "") {
            if (isset($_REQUEST['template_path'])) {
                $template_path = $_REQUEST['template_path'];
            }
            
            if (strlen($template_path) > 0) {
                if (is_file(UDRAW_STORAGE_DIR . $template_path)) {
                    return $this->sendResponse(base64_decode(file_get_contents(UDRAW_STORAGE_DIR . $template_path)));
                }
            }
            
            return $this->sendResponse('');
        }
        
        /**
         * Creates a category or sub cateogry that templates can be assigned to.
         * 
         * @param mixed $category_name Category Name
         * @param mixed $sub_cateogry_id Sub Category Id. Only used if type is not "main".
         * @param mixed $type Category Type. Default "main". If not "main", category is considered a sub category.
         */        
        function add_template_category($category_name = "My Cateogry", $sub_category_id ="", $type = "main") {
            global $wpdb;
            
            $udraw_templates_category_table = $this->udraw_templates_category_table;
            
            if (isset($_REQUEST['category_name'])) {
                $category_name = $_REQUEST['category_name'];
            }
            if (isset($_REQUEST['type'])) {
                $type = $_REQUEST['type'];
            }
            if (isset($_REQUEST['sub_category_id'])) {
                $sub_category_id = $_REQUEST['sub_category_id'];
            }
            
            // Check if request wants us to create a main cateogry or sub category. 
            
            if ($type == "main") {
                //Search through category table to see if name exists in parent category
                $searchName = $wpdb->get_results("SELECT * FROM $udraw_templates_category_table WHERE category_name='". $category_name ."' AND parent_id = 0");
                if (count($searchName) == 0) {
                    $result = $wpdb->insert($udraw_templates_category_table, array("category_name"=>$category_name, "parent_id"=>0), array("%s", "%d", "%d"));
                    return $this->sendResponse($result);
                } else {
                    return $this->sendResponse(false);
                }
            } else {
                // Check to be sure that sub category id was passed with a value before continue.
                if (strlen($sub_category_id) > 0) {
                    $parentID = $wpdb->get_var("SELECT ID FROM $udraw_templates_category_table WHERE ID='". $sub_category_id ."'");
                    $searchName = $wpdb->get_results("SELECT * FROM $udraw_templates_category_table WHERE category_name='".$category_name."' AND parent_id ='".$parentID."'");
                    if (count($searchName) == 0) {
                        $result = $wpdb->insert($udraw_templates_category_table, array("category_name"=>$category_name, "parent_id"=>$parentID), array("%s", "%d", "%d"));
                        return $this->sendResponse($result);
                    } else {
                        return $this->sendResponse(false);
                    }
                } else {
                    return $this->sendResponse(false);
                }
            }
        }
        
        /**
         * Removes category for templates base on Cateogry Id.
         * @param mixed $category_id Category Id to remove.
         * @return mixed
         */        
        function remove_template_category($category_id = 0) {
            global $wpdb; 
            
            $udraw_templates_table = $this->udraw_templates_table;
            $udraw_templates_category_table = $this->udraw_templates_category_table;            
            
            if (isset($_REQUEST['category_id'])) {
                $category_id = intval($_REQUEST['category_id']);
            }
            
            if ($category_id > 0) {
                $result = $wpdb->get_results("SELECT * FROM $udraw_templates_category_table WHERE ID='".$category_id."'");
                $id = $result[0]->ID;
                //Create array of categories to delete
                $toRemove = array();
                $toRemoveID = array();
                array_push($toRemove, $result[0]->category_name);
                array_push($toRemoveID, $result[0]->ID);
                $childCategory = $wpdb->get_results("SELECT * FROM $udraw_templates_category_table WHERE parent_id='".$id."'");
                for ($c = 0; $c < count($childCategory); $c++) {
                    array_push($toRemove, $childCategory[$c]->category_name);
                    array_push($toRemoveID, $childCategory[$c]->ID);
                }
                //Get entries in templats table which contains this deleted category and its subcategories, and replace with ''
                for ($r = 0; $r < count($toRemove); $r++) {
                    $resultTemplates = $wpdb->get_results("SELECT * FROM $udraw_templates_table WHERE category LIKE '%%".$toRemove[$r]."%%'");
                    for ($i = 0; $i < count($resultTemplates); $i++) {
                        $resultID = $resultTemplates[$i]->id;
                        $resultCat = $resultTemplates[$i]->category;
                        $replacingStr = preg_replace('/(?<=^| )\b'.$toRemove[$r].'\b(?= |$)/', '', $resultCat);
                        $wpdb->update($table_name, array('category'=>$replacingStr), array('id'=>$resultID));
                    }
                    $resultTemplates = $wpdb->get_results("SELECT * FROM $udraw_templates_table WHERE category LIKE '%%".$toRemoveID[$r]."%%'");
                    for ($i = 0; $i < count($resultTemplates); $i++) {
                        $resultID = $resultTemplates[$i]->id;
                        $resultCat = $resultTemplates[$i]->category;
                        $replacingStr = preg_replace('/(?<=^| )\b'.$toRemoveID[$r].'\b(?= |$)/', '', $resultCat);
                        $wpdb->update($table_name, array('category'=>$replacingStr), array('id'=>$resultID));
                    }
                }
                //Delete the categories
                $this->get_templates_category_array();
                $this->build_category_array("0");
                $childArray = $this->get_category_list_with_children($category_id, $childArray);
                if (is_array($childArray)) {
                    if (count($childArray) > 0) {
                        $ids = implode(',', $childArray);
                        $wpdb->query("DELETE FROM $udraw_templates_category_table WHERE ID IN (". $ids .")");
                    }
                }
                return $this->sendResponse(true);
            } else {
                return $this->sendResponse(false);
            }
        }
        
        function apply_template_category($template_id = 0, $category_id = 0) {
            global $wpdb;

            $udraw_templates_table = $this->udraw_templates_table;
            
            if (isset($_REQUEST['template_id'])) {
                $template_id = intval($_REQUEST['template_id']);
            }
            
            if (isset($_REQUEST['category_id'])) {
                $category_id = intval($_REQUEST['category_id']);
            }
            
            if ($template_id > 0 && $category_id > 0) {                
                $template = $wpdb->get_results("SELECT * FROM $udraw_templates_table WHERE id='".$template_id."'");
                $previousCat = $template[0]->category;
                if ($previousCat != null && $previousCat != '') {
                    if (preg_match("~\b".$category_id."\b~",$previousCat)) {} else {
                        $wpdb->update($udraw_templates_table, array('category'=>$previousCat.' '.$category_id), array('id'=>$template_id));
                    }
                } else {
                    $wpdb->update($udraw_templates_table, array('category'=>$category_id), array('id'=>$template_id));
                }
                return $this->sendResponse(true);
            } else {
                return $this->sendResponse(false);
            }
        }
        
        function detach_template_category($template_id = 0, $category_id = 0) {
            global $wpdb;
            
            $udraw_templates_table = $this->udraw_templates_table;
            
            if (isset($_REQUEST['template_id'])) {
                $template_id = intval($_REQUEST['template_id']);
            }
            
            if (isset($_REQUEST['category_id'])) {
                $category_id = intval($_REQUEST['category_id']);
            }

            if ($template_id > 0 && $category_id > 0) {                
                $previousCat = $wpdb->get_var("SELECT category FROM $udraw_templates_table WHERE id='".$template_id."'");
                $replacingStr = preg_replace('/(?<=^| )\b'.$category_id.'\b(?= |$)/', '', $previousCat);
                $replaceArray = explode(" ", $replacingStr);
                $cleanedString = "";
                for ($i = 0; $i < count($replaceArray); $i++) {
                    if ($replaceArray[$i] != '' && $replaceArray[$i] != ' ' && $replaceArray[$i] != null) {
                        if ($cleanedString === "") {
                            $cleanedString .= $replaceArray[$i];
                        } else {
                            $cleanedString .= " ".$replaceArray[$i];
                        }
                    }
                }
                $wpdb->update($udraw_templates_table, array('category'=>$cleanedString), array('id'=>$template_id));
                
                return $this->sendResponse(true);
            } else {
                return $this->sendResponse(false);
            }
        }
        
        function update_template_category ($category_id = 0, $parent_id = 0, $category_name = '') {
            global $wpdb;
            if (isset($_REQUEST['category_id']) && isset($_REQUEST['parent_id']) && isset($_REQUEST['category_name'])) {
                $category_id = $_REQUEST['category_id'];
                $category_name = $_REQUEST['category_name'];
                $parent_id = $_REQUEST['parent_id'];
            }
            $response = $wpdb->update($this->udraw_templates_category_table, array('parent_id'=>$parent_id, 'category_name'=>$category_name), array('ID'=>$category_id));
            return $this->sendResponse($response);
        }
        
        function get_category_list_with_children($ID, &$array) { 
            if (is_null($array)) { $array = array(); }
            
            for ($x = 0; $x < count($this->_templates_category_list); $x++) {
                if ($this->_templates_category_list[$x]->ID == $ID) {
                    array_push($array, $ID);
                    for ($y = 0; $y < count($this->_templates_category_list); $y++) {
                        if ($this->_templates_category_list[$y]->parent_id == $ID) {
                            $this->get_category_list_with_children($this->_templates_category_list[$y]->ID, $array);
                        }
                    }
                }
            }            
            return $array;
        }
        
        function get_templates_category_array() {
            global $wpdb;
            $category_table = $this->udraw_templates_category_table;
            $result = $wpdb->get_results("SELECT * from $category_table");
            
            $this->_templates_category_array = $result;
        }
        
        function build_category_array($parentId) {
            for ($x = 0; $x < count($this->_templates_category_array); $x++) {
                if ($this->_templates_category_array[$x]->parent_id == $parentId) {
                    array_push($this->_templates_category_list, $this->_templates_category_array[$x]);
                    
                    // Make a recursive Call
                    $this->build_category_array($this->_templates_category_array[$x]->ID);
                }
            }
        }
    }
    
}

?>

<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_Templates_Table extends WP_List_Table {

    var $_items_full;
    var $_templates_category_array;
    var $_templates_category_list;
    var $_templates_category_select_html;
    
    
    function __construct() {
        global $status, $page;
        
        // Init Category Arrays
        $this->getTemplatesCategoryArray();        
        $this->_templates_category_list = array();
        $this->buildCategoryArray("0");
        
        
        parent::__construct(array(
            'singular' => 'udraw_template',
            'plural' => 'udraw_templates',
            'ajax' => false
        ));

    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_preview($item)
    {
        $uDraw = new uDraw();
        $dt = new DateTime(); 
        return '<a href="?page=udraw_modify_template&template_id='. $item['id'] .'"><img src ="' . $item['preview'] . '?'. $dt->getTimestamp() .'" alt="Preview Template" style="max-height:50px;"></img></a>';    
    }
    
    function column_linked_products($item) 
    {
        $response = "";
        if (is_array($item['products'])) {
            if (count($item['products']) > 0) {
                foreach($item['products'] as $linkedProduct) {
                    if (strlen($response) > 1) {  $response .= " | "; }
                    if (current_user_can('edit_products')) {
                        $response .= "<a href=\"post.php?post=". $linkedProduct["post_id"] . "&action=edit\">" . $linkedProduct["product_title"] . "</a>";
                    } else {
                        $response .= $linkedProduct["product_title"];
                    }
                    
                }
            }
            if (current_user_can('edit_products')) {
                if (strlen($response) > 1) { $response .= "<br />"; }            
                $response .= "<a class=\"button button-primary button-small\" href=\"post-new.php?post_type=product&udraw_template_id=". $item['id'] . "&udraw_action=new-product\">Link To Product</a>";
            }
        }
        return $response;
    
    }
    
    function column_tags($item) {      
        $response = "";
        $response .= "<div id=\"tags-". $item['id'] ."-display\">";
        $response .= "<span id=\"tags-". $item['id'] ."-span\">";
        if (!is_null($item['tags'])) {
            $tags = explode(',',$item['tags']);
            for ($x = 0; $x < count($tags); $x++ ) {
                $response .= $tags[$x];
                if ($x != count($tags)-1) {
                    $response .= " | ";
                }
            }
        }
        $response .= "</span>";
        if (current_user_can('edit_products')) {
            if (strlen($response) > 1) { $response .= "<br />"; }            
            $response .= "<a class=\"button button-primary button-small\" onClick=\"javascript:updateTags('". $item['id'] ."'); return false;\" href=\"#\">Update Tags</a>";
        }
        $response .= "</div>";
        
        $response .= "<div id=\"tags-". $item['id'] ."-update\" style=\"display:none;\">";
        $response .= "<input id=\"tags-". $item['id'] ."-input\" type=\"text\" class=\"template-tags\" value=\"". $item['tags'] ."\" />";
        $response .= "<a class=\"button\" onClick=\"javascript:saveTags('". $item['id'] ."'); return false;\" href=\"#\" style=\"margin-top:10px; float:right;\">Save</a>";
        $response .= "</div>";
        
        return $response;
    }


    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        $uDraw = new uDraw();
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array();
        
        if (current_user_can('delete_udraw_templates')) {
            $actions = array(
                'id' => sprintf('<span>ID: %s</span>', $item['id']),
                'rename' => sprintf('<a href="#" onclick=javascript:renameTemplate(\'%s\');>%s</a>', $item['id'], __('Rename', 'udraw')),
                'duplicate' => sprintf('<a href="?page=%s&udraw=duplicate&id=%s" target="_self">%s</a>', $_REQUEST['page'], $item['id'], __('Duplicate', 'udraw')),
                'edit' => sprintf('<a href="?page=udraw_modify_template&template_id=%s">%s</a>', $item['id'], __('Edit', 'udraw')),
                'delete' => sprintf('<a href="?page=%s&udraw=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'udraw')),
            );
        } else if (current_user_can('edit_udraw_templates')) {
            $actions = array(
                'id' => sprintf('<span>ID: %s</span>', $item['id']),
                'rename' => sprintf('<a href="#" onclick=javascript:renameTemplate(\'%s\');>%s</a>', $item['id'], __('Rename', 'udraw')),
                'duplicate' => sprintf('<a href="?page=%s&udraw=duplicate&id=%s" target="_self">%s</a>', $_REQUEST['page'], $item['id'], __('Duplicate', 'udraw')),
                'edit' => sprintf('<a href="?page=udraw_modify_template&template_id=%s">%s</a>', $item['id'], __('Edit', 'udraw_table')),
            );
        }
        
        
        if (uDraw::is_udraw_okay() == false) {
            if (count($uDraw->get_udraw_templates()) >= 2) {
                unset($actions['duplicate']);
            }
        }
        
        return sprintf('<div style="display:none;" id="udraw_rename_template_%s"><input type="text" value="%s" id="udraw_rename_template_input_%s"><a class="button button-primary button-small" onclick=javascript:execRenameTemplate(\'%s\');>Update</a></div><span id="udraw_item_name_%s">%s</span> %s',
            $item['id'],
            $item['name'],
            $item['id'],
            $item['id'],
            $item['id'],
            $item['name'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
    
    function column_date($item)
    {
        $_date = $item['create_date'];
        if (strlen($item['modify_date']) > 1) {
            $_date = $item['modify_date'];
        }
        $dateObj = new DateTime($_date);
        
        $m_time = $dateObj->format('Y/m/d');
        $time = $dateObj->format('U');

        $time_diff = time() - $time;
        if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
            return sprintf( __( '%s ago' ), human_time_diff( $time ) );
        } else {
            return mysql2date( __( 'Y/m/d' ), $m_time );        
        }    
    }
    
function column_category($item)
    {   
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $category_table = $_udraw_settings['udraw_db_udraw_templates_category'];
        $categories = $item['category'];
        if ($categories != null) {
            if (!strrpos($categories, ' ')) {
                $categoryList = array($categories);
            } else {
                $categoryList = explode(" ", $categories);
            }
            foreach ($categoryList as $category) {
                if ($category != "" && $category != " ") {
                    if (is_numeric($category)) {
                        $categoryResult = $wpdb->get_results("SELECT * FROM $category_table WHERE ID='".$category."'");
                    } else {
                        $categoryResult = $wpdb->get_results("SELECT * FROM $category_table WHERE category_name='".$category."'");
                    }
                    
                    if (is_array($categoryResult)) {
                        if (count($categoryResult) > 0) {
                            $categoryID = $categoryResult[0]->ID;
                            if (current_user_can('edit_udraw_templates')) {
                                echo '<a href="#" id="remove-category-from-'.$item['id'].'" style="text-decoration: none;" onclick="javascript: removeCategoryFrom('.$item['id'].','. $category.')">x</a>';
                            }
                            $this->getCategoryPath($categoryID, "");
                            echo '<br/>';
                        }
                    }
                }
            }
        }
        if (current_user_can('edit_udraw_templates')) {
            echo '<div>';
            echo '<select id="select-category-for-'.$item['id'].'" class="add-template-category" style="width: 135px; display: inline-block;">';
            echo '<option value="" selected disabled>Select one...</option>';
                $this->buildCategorySelect("0", "");
            echo '</select>';
            echo '</div>';
        }
    }
    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text            
            'name' => __('Name', 'udraw'),
			'preview' => __('Preview', 'udraw'),
			'size' => __('Size (inches)', 'udraw'),
			'tags' => __('Tags', 'udraw'),
            'linked_products' => __('Linked Products', 'udraw'),
            'category' => __('Category', 'udraw'),
            'date' => __('Date', 'udraw')
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
			'design_pages' => array('design_pages', false),
			'size' => array('size', false),
            'date' => array('create_date', false)
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $category_table_name = $_udraw_settings['udraw_db_udraw_templates_category'];
        
        $actions = array(
            'delete' => __('Delete', 'udraw'),
            'unassign_category' => __('Remove category from template', 'udraw')
        );
        if (current_user_can('edit_udraw_templates')) {
            for ($i = 0; $i < count($this->_templates_category_list); $i++) {
                $categoryID = $this->_templates_category_list[$i]->ID;
                //array_push($actions['add_category_'.$this->_templates_category_list[$i]->category_name]);
                $actions['add_category_'.$this->_templates_category_list[$i]->ID] = "Add template to: ".$this->buildCategoryPathString($categoryID, '').'';
            }
        }
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     */
    function process_bulk_action()
    {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $table_name = $_udraw_settings['udraw_db_udraw_templates'];                
        $category_table_name = $_udraw_settings['udraw_db_udraw_templates_category'];

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
        
        if ('unassign_category' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            
            if (!empty($ids)) {
                $wpdb->query("UPDATE $table_name SET category='' WHERE id IN($ids)");
            }
        }
        
        for ($i = 0; $i < count($this->_templates_category_list); $i++) {
            if ('add_category_'.$this->_templates_category_list[$i]->ID === $this->current_action()) {
                $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
                if (is_array($ids)) $ids = implode(',', $ids);
                if (!empty($ids)) {
                    //check to see that the template does not already have the category listed
                    $getResult = $wpdb->get_results("SELECT * FROM $table_name WHERE id IN($ids)");
                        foreach ($getResult as $oneResult) {
                        if (strpos($oneResult->category, $this->_templates_category_list[$i]->category_name) == '') {
                            $previousCategory = $oneResult->category;
                            $id = $oneResult->id;
                            //get list of old category and update it
                            if ($previousCategory != null && $previousCategory != '') {
                                if (preg_match("~\b".$this->_templates_category_list[$i]->ID."\b~",$previousCategory)) {} else {
                                    $wpdb->update($table_name, array('category'=>$previousCategory.' '.$this->_templates_category_list[$i]->ID), array('id'=>$id));
                                }
                            } else {
                                $wpdb->update($table_name, array('category'=>$this->_templates_category_list[$i]->ID), array('id'=>$id));
                            }
                        }
                    }
                }
            }
        }
    }
	
    function get_views()
    {        
        $views = array();
        $current = ( !empty($_REQUEST['udraw_view']) ? $_REQUEST['udraw_view'] : 'all');
        
        $count_all = count($this->_items_full);
        $count_linked = 0;
        $count_notLinked = 0;
        
        for($i = 0; $i < count($this->_items_full); ++$i) {
            if (is_array($this->_items_full[$i]['products'])) {
                if (count($this->_items_full[$i]['products']) > 0) {
                    $count_linked++;
                } else {
                    $count_notLinked++;
                }                
            } else {
                $count_notLinked++;
            }
            
        }

        //All link
        $class = ($current == 'all' ? ' class="current"' :'');
        $all_url = esc_url(remove_query_arg('udraw_view'));
        $views['All'] = "<a href='{$all_url }' {$class} >" . __('All', 'udraw') . "<span class=\"count\">&nbsp;({$count_all})</span></a>";

        //Foo link
        $foo_url = esc_url(add_query_arg('udraw_view','linked'));
        $class = ($current == 'linked' ? ' class="current"' :'');
        $views['Linked'] = "<a href='{$foo_url}' {$class} >" . __('Linked To Product', 'udraw') . "<span class=\"count\">&nbsp;({$count_linked})</span></a>";

        //Bar link
        $bar_url = esc_url(add_query_arg('udraw_view','not_linked'));
        $class = ($current == 'not_linked' ? ' class="current"' :'');
        $views['NotLinked'] = "<a href='{$bar_url}' {$class} >" . __('Not Linked To Product', 'udraw') . "<span class=\"count\">&nbsp;({$count_notLinked})</span></a>";
        
        return $views;
    }
    
    function extra_tablenav( $which ) {
        if ($which == 'top') {
            echo '<select id="filter-category-select" style="margin-top: 3px;">';
            echo '<option value="none">No filter</option>';
            echo '<option value="uncategorized">Uncategorized</option>';
        $this->buildCategorySelect("0", "");
            echo '</select>';
            echo '<a href="#" class="button button-default" id="filter-category-button" onclick="javascript: getFilter();" style="margin-top: 3px; display: inline-block;">Filter</a>';
        }
    }
    
	/**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $table_name = $_udraw_settings['udraw_db_udraw_templates'];                
        $category_table_name = $_udraw_settings['udraw_db_udraw_templates_category'];

        $per_page = 20;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // prepare query params, as usual current page, order by and order direction
        $paged = $this->get_pagenum();
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'create_date';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $search = isset($_REQUEST['s']) ? '%%'. $_REQUEST['s'] . '%%' : '%%';
        $category = isset($_GET['filter']) ? $_GET['filter'] : '%%';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        if (!isset($_GET['filter'])) {
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT id, name, design, preview, pdf, create_date, create_user, modify_date, CONCAT(design_width,'\" x ', design_height,'\"') as size, design_pages, tags, category FROM $table_name WHERE public_key IS NULL AND name LIKE %s OR tags LIKE %s OR category LIKE %s ORDER BY $orderby $order", array($search,$search,$search)), ARRAY_A);
        } else {
            if ($category != 'uncategorized') {
                //Should get category, as well as its children
                $catID = $category;
                $childArray = array();
                $childArray = $this->getCategoryListWithChildren($catID, $childArray);
                $catSQL = "";
                for ($i = 0; $i<count($childArray); $i++) {
                    $catSQL .= "category REGEXP '[[:<:]]".$childArray[$i]."[[:>:]]'";
                    if ($i !== count($childArray)-1 ) { $catSQL .= " OR "; }
                }
                $this->items = $wpdb->get_results($wpdb->prepare("SELECT id, name, design, preview, pdf, create_date, create_user, modify_date, CONCAT(design_width,'\" x ', design_height,'\"') as size, design_pages, tags, category FROM $table_name WHERE (public_key IS NULL) AND ($catSQL) AND (name LIKE %s OR tags LIKE %s) ORDER BY $orderby $order", array($search,$search)), ARRAY_A);
            } else {
                $this->items = $wpdb->get_results($wpdb->prepare("SELECT id, name, design, preview, pdf, create_date, create_user, modify_date, CONCAT(design_width,'\" x ', design_height,'\"') as size, design_pages, tags, category FROM $table_name WHERE public_key IS NULL AND (name LIKE %s OR tags LIKE %s) AND (category='' OR category IS NULL)  ORDER BY $orderby $order", array($search,$search)), ARRAY_A);
            }
        }

        // Get all uDraw products from WooCommerce, and attach that to the templates when displaying the table.
        $uDraw = new uDraw();
        $uDrawProducts = $uDraw->get_udraw_products();    

        for($i = 0; $i < count($this->items); ++$i) {
            $this->items[$i]["products"] = array();
            foreach ($uDrawProducts->posts as $post) {
                $templateId = $uDraw->get_udraw_template_ids($post->ID);
                if (count($templateId) > 0) { 
                    for ($x = 0; $x < count($templateId); $x++) {
                        if ($this->items[$i]["id"] == $templateId[$x]) {
                            $product_array = array();
                            $product_array["post_id"] = $post->ID;
                            $product_array["product_title"] = $post->post_title;
                            array_push($this->items[$i]["products"], $product_array);
                            break;
                        }
                    }
                }
            }
        }
        
        $this->_items_full = $this->items;
        
        // Update items in array if the view is filtered.
        if (isset($_GET['udraw_view'])) {
            $filteredView = $_GET['udraw_view'];
            // Linked Products View Only.
            if ($filteredView == "linked") {
                $linked = array();
                for($i = 0; $i < count($this->items); ++$i) {
                    if (is_array($this->items[$i]['products'])) {
                        if (count($this->items[$i]['products']) > 0) {
                            array_push($linked, $this->items[$i]);
                        }
                    }
                }
                $this->items = $linked;                
            }
            
            // Not Linked Products View Only.
            if ($filteredView == "not_linked") {
                $not_linked = array();
                for($i = 0; $i < count($this->items); ++$i) {
                    if (is_array($this->items[$i]['products'])) {
                        if (count($this->items[$i]['products']) == 0) {
                            array_push($not_linked, $this->items[$i]);
                        }
                    }
                }
                $this->items = $not_linked;                
            }
        }
        
        // will be used in pagination settings
        $total_items = count($this->items);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
                
        // Splice the array for pagingation.
        $_pagedArray = array_splice($this->items,($paged-1)*$per_page, $per_page);
        // prevent results from showing empty when using paging.
        if (count($_pagedArray) > 0) {
            $this->items = $_pagedArray;
        }
        
    }    
    
    function getCategoryListWithChildren ($ID, &$array)
    {
        
        for ($x = 0; $x < count($this->_templates_category_list); $x++) {
            if ($this->_templates_category_list[$x]->ID == $ID) {
                array_push($array, $ID);
                for ($y = 0; $y < count($this->_templates_category_list); $y++) {
                    if ($this->_templates_category_list[$y]->parent_id == $ID) {
                        $this->getCategoryListWithChildren($this->_templates_category_list[$y]->ID, $array);
                    }
                }
            }
        }
        return $array;
    }
    
    function getCategoryPath ($id, $arrowPath)
    {
        for ($x = 0; $x < count($this->_templates_category_list); $x++) {
            if ($this->_templates_category_list[$x]->ID == $id) {
                $category_name = stripslashes($this->_templates_category_list[$x]->category_name);
                $parentID = $this->_templates_category_list[$x]->parent_id;
                if ($parentID == 0) {
                    $newPath = $category_name.$arrowPath;
                    echo $newPath;
                } else {
                    $newPath = '<span style="padding: 0px 2px 0px 2px;"><i class="fa fa-chevron-right" style="transform: scale(0.5)"></i></span>'.$category_name.$arrowPath;
                    $this->getCategoryPath($parentID, $newPath);
                }
            }
        }
    }
    
    /**
     *
     * Sets Array Dataset of table udraw_templates_category
     */
    function getTemplatesCategoryArray() {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $table_name = $_udraw_settings['udraw_db_udraw_templates_category'];        
        $result = $wpdb->get_results("SELECT * from $table_name");
        
        $this->_templates_category_array = $result;
    }
    
    function buildCategoryArray($parentId) {
        for ($x = 0; $x < count($this->_templates_category_array); $x++) {
            if ($this->_templates_category_array[$x]->parent_id == $parentId) {
                array_push($this->_templates_category_list, $this->_templates_category_array[$x]);
                
                // Make a recursive Call
                $this->buildCategoryArray($this->_templates_category_array[$x]->ID);
            }
        }
    }
    
    function buildCategorySelect($parentId, $parentName) {
        for ($x = 0; $x < count($this->_templates_category_list); $x++) { 
            if ($this->_templates_category_list[$x]->parent_id == $parentId) {
                $category_name = stripcslashes($this->_templates_category_list[$x]->category_name);
                echo "<option value='".$this->_templates_category_list[$x]->ID."'>";
                echo $parentName;
                if (strlen($parentName) > 0) {
                    echo " >> ";
                }
                
                echo $category_name;
                echo '</option>';
                    
                if (strlen($parentName) > 0) {
                    $this->buildCategorySelect($this->_templates_category_list[$x]->ID, $parentName . " >> " . $category_name);
                } else {
                    $this->buildCategorySelect($this->_templates_category_list[$x]->ID, $parentName . $category_name);
                }
            }
            
        }
    }
    
    function buildCategoryPathString($id, $string)
    {
        for ($x = 0; $x < count($this->_templates_category_list); $x++) {
            if ($this->_templates_category_list[$x]->ID == $id) {
                $category_name = stripslashes($this->_templates_category_list[$x]->category_name);
                $parentID = $this->_templates_category_list[$x]->parent_id;
                if ($parentID == 0) {
                    $newPath = $category_name.$string;
                    return $newPath;
                } else {
                    $newPath = ' > '.$category_name.$string;
                    return $this->buildCategoryPathString($parentID, $newPath);
                }
            }
        }
    }
}

<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_Clipart_Table extends WP_List_Table {
    
    public $_clipart_category_array;
    
    function __construct() {
        global $status, $page;
        
        $this->getClipartCategory();

        parent::__construct(array(
            'singular' => 'udraw_clipart',
            'plural' => 'udraw_cliparts',
            'ajax' => false
        ));

    }
    
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    
    function column_image_name ($item) {
        if (is_user_logged_in()) {
            if (current_user_can('delete_udraw_clipart_upload')) {
                $actions = array(
                    'delete' => sprintf('<a href="?page=%s" onclick="javascript: removeClipart(\'%s\');">%s</a>', $_REQUEST['page'], $item['access_key'], __('Delete', 'udraw'))
                );
        
                return sprintf('%s %s',
                    $item['image_name'],
                    $this->row_actions($actions)
                );
            }
        }
    }
    
    function column_user_uploaded($item)
    {
        return $item['user_uploaded'];
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="ID[]" value="%s" />',
            $item['ID']
        );
    }
    
    function column_date($item)
    {
       return $item['date'];
    }
    
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'image_name' => __('Image Name', 'udraw'),
            'preview' => __('Preview', 'udraw'),
            'user_uploaded' => __('Uploader', 'udraw'),
            'date' => __('Date Uploaded', 'udraw'),
            'tags' => __('Tags', 'udraw'),
            'category' => __('Category', 'udraw')
        );
        return $columns;
    }
    
    function column_preview($item)
    {
        return '<img src="' . UDRAW_CLIPART_URL.$item["image_name"] . '"  style="max-height:80px; max-width:50px;"/>';
    }
    
    function column_tags($item) {
        $response = "";
        $response .= "<div id=\"tags-". $item['ID'] ."-display\">";
        $response .= "<span id=\"tags-". $item['ID'] ."-span\">";
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
        if (current_user_can('edit_udraw_clipart_upload')) {
            if (strlen($response) > 1) { $response .= "<br />"; }            
            $response .= "<a class=\"button button-primary button-small\" onClick=\"javascript:updateTags('". $item['ID'] ."'); return false;\" href=\"#\">Update Tags</a>";
        }
        $response .= "</div>";
        
        $response .= "<div id=\"tags-". $item['ID'] ."-update\" style=\"display:none;\">";
        $response .= "<input id=\"tags-". $item['ID'] ."-input\" type=\"text\" class=\"clipart-tags\" value=\"". $item['tags'] ."\" />";
        $response .= "<a class=\"button\" onClick=\"javascript:saveTags('". $item['ID'] ."'); return false;\" href=\"#\" style=\"margin-top:10px; float:right;\">Save</a>";
        $response .= "</div>";
        
        return $response;
    }
    
    function column_category($item) {
        echo '<select id="select-clipart-category-id-'.$item["access_key"].'" class="select-clipart-category" style="width: 150px;"';
        if (!current_user_can('edit_udraw_clipart_upload')) {
            echo ' disabled';
        }
        echo '>';
        ?>
        <option value="">Select one</option>
        <option value="none">::None::</option>
        <?php
        $this->buildCategorySelectOptions('0');
        ?>
        </select>
        <script>
            var categoryID = '<?php echo $item["category"] ?>';
            if (categoryID !== '' && categoryID != null) {
                jQuery('#select-clipart-category-id-<?php echo $item["access_key"]?> option[value="'+categoryID+'"]').prop('selected', true);
            }
        </script>
        <?php
    }
    
    function extra_tablenav( $which ) {
        global $wpdb;
        
        if ($which == 'top') {
            ?>
            <select id="filter-category-select" name="filter-category-select" style="margin-top: 3px;">
            <option value=" ">No filter</option>
            <option value="uncategorized">Uncategorized</option>
            <?php 
                $this->buildCategorySelectOptions('0');
            ?>
            </select>
            <a href="#" class="button button-default" id="filter-category-button" style="margin-top: 3px;">Filter</a>
            <?php
        }
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_uploaded' => array('user_uploaded', true),
            'date' => array('date', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        
        $actions = array();
        if (is_user_logged_in()) {
            if (current_user_can('delete_udraw_clipart_upload')) {
                $actions['delete'] = 'Delete';
            }
            if (current_user_can('edit_udraw_clipart_upload')) {
                $actions['unlink_category'] = 'Unassign category';
                foreach($this->_clipart_category_array as $category) {
                    $actions['assign_to_'.$category->ID] = 'Assign to category: "'.$this->buildClipartCategoryPath($category->ID, "").'"';
                }
            }
        }
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $table_name = $_udraw_settings['udraw_db_udraw_clipart'];
        
        if (is_user_logged_in()) {
            if (current_user_can('delete_udraw_clipart_upload')) {
                if ('delete' === $this->current_action()) {
                    $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : array();
                    if (is_array($ids)) $ids = implode(',', $ids);
                    if (!empty($ids)) {
                        $return_row = $wpdb->get_results("SELECT * FROM $table_name WHERE ID IN ($ids)");
                        for ($i = 0; $i < count($return_row); $i++) {
                            $image_name = $return_row[$i]->image_name;
                            $accesskey = $return_row[$i]->access_key;
                            $clipart_file = UDRAW_CLIPART_DIR . $image_name;
                            if (file_exists($clipart_file)) {
                                unlink($clipart_file);
                                $wpdb->delete($table_name, array('access_key' => $accesskey));
                            }
                        }
                    }
                }
            }
            if (current_user_can('edit_udraw_clipart_upload')) {
                if ('unlink_category' === $this->current_action()) {
                    $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : array();
                    if (is_array($ids)) $ids = implode(',', $ids);
                    if (!empty($ids)) {
                        $return_row = $wpdb->query("UPDATE $table_name SET category='' WHERE ID IN ($ids)");
                    }
                }
                //move to folder stuff
                foreach($this->_clipart_category_array as $category) {
                    if ('assign_to_'.$category->ID === $this->current_action()) {
                        $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : array();
                        if (is_array($ids)) $ids = implode(',', $ids);
                        if (!empty($ids)) {
                            $wpdb->query("UPDATE $table_name SET category=$category->ID WHERE ID IN ($ids)");
                        }
                    } 
                }
            }
        }
    }
    
    function no_items() {
        _e ('No images found.');
    }
    
    
    function prepare_items()
    {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $table_name = $_udraw_settings['udraw_db_udraw_clipart'];

        $per_page = 50;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // prepare query params, as usual current page, order by and order direction
        $paged = $this->get_pagenum();
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'date';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $search = isset($_REQUEST['s']) ? '%%'. $_REQUEST['s'] . '%%' : '%%';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '\'%%\'';
        
        if (!isset($_GET['filter'])) {
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE image_name LIKE %s OR user_uploaded LIKE %s OR tags LIKE %s OR category LIKE %s ORDER BY $orderby $order", array($search,$search,$search,$search)), ARRAY_A);
        } else {
            if ($filter === 'uncategorized') {
                $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE category = %s AND (image_name LIKE %s OR user_uploaded LIKE %s OR tags LIKE %s) ORDER BY $orderby $order", array($filter,$search,$search,$search)), ARRAY_A);
            }
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE category = '' OR category IS NULL AND (image_name LIKE %s OR user_uploaded LIKE %s OR tags LIKE %s) ORDER BY $orderby $order", array($search,$search,$search)), ARRAY_A);
        }
        
        $this->_items_full = $this->items;
        
        // will be used in pagination settings
        $total_items = count($this->items);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
                
        // Splice the array for pagingation.
        $_pagedArray = array_splice($this->items, ($paged-1)*$per_page, $per_page);
        // prevent results from showing empty when using paging.
        if (count($_pagedArray) > 0) {
            $this->items = $_pagedArray;
        }
        
    }
    
    function getClipartCategory() {
        global $wpdb;
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $clipart_category_DB = $_udraw_settings['udraw_db_udraw_clipart_category'];
        $this->_clipart_category_array = $wpdb->get_results("SELECT * FROM $clipart_category_DB");
    }
    
    function buildClipartCategoryPath($ID, $path){
        $category_name = '';
        $parent_id;
        foreach ($this->_clipart_category_array as $category) {
            if ($category->ID === $ID) {
                $category_name = $category->category_name;
                $parent_id = $category->parent_id;
            }
        }
        if ($path !== '') {
            $path = $category_name . ' > ' . $path;
        } else {
            $path = $category_name;
        }
        if ($parent_id === '0') {
            return $path;
        } else {
            return $this->buildClipartCategoryPath($parent_id, $path);
        }
    }
    
    function buildCategorySelectOptions ($parent_id) {
        foreach ($this->_clipart_category_array as $category) {
            if ($category->parent_id == $parent_id) {
                echo '<option value="'.$category->ID.'">'.stripslashes($this->buildClipartCategoryPath($category->ID, "")).'</option>';
                $this->buildCategorySelectOptions($category->ID);
            }
        }
    }
    
}

?>
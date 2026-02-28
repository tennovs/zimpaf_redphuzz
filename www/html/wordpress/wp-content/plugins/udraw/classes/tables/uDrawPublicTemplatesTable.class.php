<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_Public_Templates_Table extends WP_List_Table {

    var $_items_full;
    var $_categories;
    
    function __construct() {
        global $status, $page;

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
    function column_PreviewLocation($item)
    {
        return '<a onclick="javascript:PreviewTemplate(\''. $item['PreviewLocation'] .'\');return false;" href="#TB_inline?width=600&height=550&inlineId=template-preview-thickbox" class="thickbox"><img src ="' . $item['PreviewLocation'] . '" alt="Preview Template" style="max-height:50px;"></img></a>';
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
            } else {
                if (current_user_can('edit_products')) {
                    $createProductUrl = esc_url(add_query_arg('create_udraw_product',$item['AccessKey']));
                    $response = "<a class=\"button button-primary button-small\" href=\"{$createProductUrl}\">".__('Link To Product', 'udraw')."</a>";
                }
            }
        }
        
        return $response;
    }


    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_Name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array(
            'Preview' => sprintf('<a onclick="javascript:PreviewTemplate(\'%s\');return false;" href="#TB_inline?width=600&height=550&inlineId=template-preview-thickbox" class="thickbox">%s</a>', $item['PreviewLocation'], __('Preview', 'udraw_table'))
        );

        return sprintf('%s %s',
            $item['Name'],
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
            $item['AccessKey']
        );
    }
    
    function column_LastModified($item)
    {
        $_date = $item['LastModified'];
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
            'Name' => __('Name', 'udraw'),
			'PreviewLocation' => __('Preview', 'udraw'),
			'size' => __('Size (inches)', 'udraw'),
			'Pages' => __('Pages', 'udraw'),
            'linked_products' => __('Linked Products', 'udraw'),
            'LastModified' => __('Date', 'udraw')
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
            'Name' => array('Name', true),
			'Pages' => array('Pages', false),
			'size' => array('size', false)
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
        $actions = array( );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     */
    function process_bulk_action()
    {
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
        $views['Linked'] = "<a href='{$foo_url}' {$class} >" . __('Linked To Products', 'udraw') . "<span class=\"count\">&nbsp;({$count_linked})</span></a>";

        //Bar link
        $bar_url = esc_url(add_query_arg('udraw_view','not_linked'));
        $class = ($current == 'not_linked' ? ' class="current"' :'');
        $views['NotLinked'] = "<a href='{$bar_url}' {$class} >" . __('Not Linked To Products', 'udraw') . "<span class=\"count\">&nbsp;({$count_notLinked})</span></a>";
        
        return $views;
    }
    
    function extra_tablenav( $which ) {
        if ( 'top' == $which ) {
            $category_id = 0;
            if (isset($_GET["category_id"])) {
                $category_id = intval($_GET["category_id"]);
            }
            
            echo "<label for=\"category_id\" style=\"font-size: 1.1em;font-weight: bold;\">" . __('Category:','udraw') . " &nbsp;</label>\n";
            echo "<select name=\"category_id\">\n";
            foreach($this->_categories as $category) {
                $selected = "";
                if ($category->Id == $category_id) { $selected = "selected=\"selected\""; }
                echo "\t<option value='" . $category->Id . "' ". $selected . ">". $category->Name ."</option>\n";
                if (count($category->Children) > 0) {
                    foreach ($category->Children as $child_cateogry) {
                        $selected = "";
                        if ($child_cateogry->Id == $category_id) { $selected = "selected=\"selected\""; }
                        echo "\t<option value='" . $child_cateogry->Id . "' ". $selected . ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $child_cateogry->Name ."</option>\n";
                    }
                }
            }
            echo "</select>\n";
            
            submit_button(__('Filter', 'udraw') , 'primary', false, false);
        }
    }
    
	/**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $uDrawUtil = new uDrawUtil();
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $global_key = $_udraw_settings['udraw_designer_global_template_key'];

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

        // Get all categories
        $category_json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/category/' . $global_key);
        $this->_categories = json_decode($category_json);
        
        $category_id = $this->_categories[0]->Id;
        if (isset($_GET["category_id"])) {
            $category_id = $_GET["category_id"];
        }
        
        // [REQUIRED] define $items array           
        $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/templates/' . $category_id . '/' . $global_key);
        $this->items = json_decode($json, true);        
        for($i = 0; $i < count($this->items); ++$i) {
            $this->items[$i]["size"] = ($this->items[$i]["Width"] / 72) . '" x '. ($this->items[$i]["Height"] / 72) . '" ';
        }        

        
        // Get all uDraw products from WooCommerce, and attach that to the templates when displaying the table.
        $uDraw = new uDraw();
        $uDrawProducts = $uDraw->get_udraw_products();    

        for($i = 0; $i < count($this->items); ++$i) {
            $this->items[$i]["products"] = array();
            foreach ($uDrawProducts->posts as $post) {
                $publicKey = get_post_meta($post->ID, '_udraw_public_key', true);
                if (isset($publicKey)) { 
                    if ($this->items[$i]["AccessKey"] == $publicKey) {
                        $product_array = array();
                        $product_array["post_id"] = $post->ID;
                        $product_array["product_title"] = $post->post_title;
                        array_push($this->items[$i]["products"], $product_array);
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
        
        if (isset($_GET['orderby'])) {
            if ($_GET['order'] == "asc") {
                $sortedArray = $this->array_sort($this->items, $_GET['orderby'], SORT_ASC);
                $this->items = $sortedArray;
            } else {
                $sortedArray = $this->array_sort($this->items, $_GET['orderby'], SORT_DESC);
                $this->items = $sortedArray;
            }            
        }
        
        // Splice the array for pagingation.
        $_pagedArray = array_splice($this->items,($paged-1)*$per_page, $per_page);
        // prevent results from showing empty when using paging.
        if (count($_pagedArray) > 0) {
            $this->items = $_pagedArray;
        }
    }
    
    
    function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }    

}

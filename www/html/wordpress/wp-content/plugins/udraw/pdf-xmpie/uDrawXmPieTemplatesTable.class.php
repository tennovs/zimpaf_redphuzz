<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_XMPie_Templates_Table extends WP_List_Table {

    var $_items_full;
    
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

	function column_ProductName($item) {
		return '<strong>Name:</strong>&nbsp;' .$item['ProductName'] . '<br /><strong>ID:</strong>&nbsp;' . $item['UniqueID'];
	}

    /**
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_ThumbnailSmall($item)
    {
        return '<a onclick="javascript:PreviewTemplate(\''. $item['ThumbnailLarge'] .'\');return false;" href="#TB_inline?width=520&height=450&inlineId=template-preview-thickbox" title="'. $item['ProductName'] . '" class="thickbox"><img src ="' . $item['ThumbnailSmall'] . '" alt="Preview Template" style="max-height:50px;"></img></a>';
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
        }
        if (current_user_can('edit_products')) {
                if (strlen($response) > 1) { $response .= "<br />"; }            
                $response .= "<a class=\"button button-primary button-small\" href=\"post-new.php?post_type=product&udraw_template_id=". $item['ProductID'] . "&udraw_action=new-xmpie-product\">Link To Product</a>";
            }
        return $response;
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
            //$item['AccessKey'] 'AccessKey' does not exist; use ProductID instead
            $item['ProductID']
        );
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
            'ProductName' => __('Name', 'udraw_table'),
            'ThumbnailSmall' => __('Preview', 'udraw_table'),
            'linked_products' => __('Linked Products', 'udraw_table'),
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
        $views['All'] = "<a href='{$all_url }' {$class} >All<span class=\"count\">&nbsp;({$count_all})</span></a>";

        //Foo link
        $foo_url = esc_url(add_query_arg('udraw_view','linked'));
        $class = ($current == 'linked' ? ' class="current"' :'');
        $views['Linked'] = "<a href='{$foo_url}' {$class} >Linked To Product<span class=\"count\">&nbsp;({$count_linked})</span></a>";

        //Bar link
        $bar_url = esc_url(add_query_arg('udraw_view','not_linked'));
        $class = ($current == 'not_linked' ? ' class="current"' :'');
        $views['NotLinked'] = "<a href='{$bar_url}' {$class} >Not Linked To Product<span class=\"count\">&nbsp;({$count_notLinked})</span></a>";
        
        return $views;
    }
    
	/**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
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
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'create_date';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
		$search = isset($_REQUEST['s']) ? '\'%%'. $_REQUEST['s'] . '%%\'' : '\'%%\'';    
        
        // [REQUIRED] define $items array
        $uDraw = new uDraw();
        $uDrawProducts = $uDraw->get_udraw_products();
        
        $uDrawPdfXMPie = new uDrawPdfXMPie();
        $this->items = $uDrawPdfXMPie->get_company_products();                        
        
        
        for($i = 0; $i < count($this->items); ++$i) {
            $this->items[$i]["products"] = array();
            foreach ($uDrawProducts->posts as $post) {     
                $xmpie_product_id = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);                
                
                // Convert String ( old type ) to Array ( new type )
                if (gettype($xmpie_product_id) == 'string') {
                    $xmpie_product_id = explode("HuhWhatOkay", get_post_meta($post->ID, '_udraw_xmpie_template_id', true));
                }                
                if (count($xmpie_product_id) > 0) {
                    for ($j = 0; $j < count($xmpie_product_id); $j++) {                        
                        if ($this->items[$i]["ProductID"] == intval($xmpie_product_id[$j])) {
                            $product_array = array();
                            $product_array["post_id"] = $post->ID;
                            $product_array["product_title"] = $post->post_title;
                            array_push($this->items[$i]["products"], $product_array);
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

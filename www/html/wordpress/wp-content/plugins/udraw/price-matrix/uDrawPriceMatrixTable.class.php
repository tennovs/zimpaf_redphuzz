<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_Price_Matrix_Table extends WP_List_Table {

    var $_items_full;
    
    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'udraw_price_matrix',
            'plural' => 'udraw_price_matrix',
            'ajax' => false
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
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
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array();
        
        if (current_user_can('delete_udraw_price_matrix')) {
            $actions = array(
                'edit' => sprintf('<a href="?page=udraw_manage_price_matrix&access_key=%s">%s</a>', $item['access_key'], __('Edit', 'udraw')),
                'delete' => sprintf('<a href="?page=%s&udraw=delete&access_key=%s">%s</a>', $_REQUEST['page'], $item['access_key'], __('Delete', 'udraw')),
            );
        } else if (current_user_can('edit_udraw_price_matrix')) {
            $actions = array(
                'edit' => sprintf('<a href="?page=udraw_manage_price_matrix&access_key=%s">%s</a>', $item['access_key'], __('Edit', 'udraw'))
            );
        }
        
        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }
    
    function column_create_date($item)
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
    
    function column_actions($item)
    {
        $buttons = "";        
        $buttons .= sprintf('<a class="button button-primary thickbox" target="_new" href="?page=udraw_preview_price_matrix&access_key=%s">%s</a>', $item['access_key'], __('Preview', 'udraw'));
        
        return $buttons;
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
            '<input type="checkbox" name="access_key[]" value="%s" />',
            $item['access_key']
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
            'name' => __('Name', 'udraw'),
            'create_date' => __('Date', 'udraw')
            //'actions' => __('', 'udraw_price_matrix_table')
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
            'name' => array('name', false),
            'create_date' => array('create_date', true)
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
        $actions = array(
            'delete' => __('Delete', 'udraw')
        );
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
        $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];
        $_reqest = '';
        if (isset($_REQUEST['udraw'])) { $_reqest = $_REQUEST['udraw']; }                  

        if ('delete' === $this->current_action() || 'delete' === $_reqest) {
            $ids = isset($_REQUEST['access_key']) ? $_REQUEST['access_key'] : array();                        
            if (is_array($ids)) {
                $ids = implode(',', array_map(array(&$this,'quote'), $ids));
            } else {
                $ids = "'" . $ids . "'";
            }            
            
            if (!empty($ids)) {
                $sql = "DELETE FROM $table_name WHERE access_key IN($ids)";
                $wpdb->query($sql);
            }
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
        $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];

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
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        $search = isset($_REQUEST['s']) ? '%%'. $_REQUEST['s'] . '%%' : '%%';

        // [REQUIRED] define $items array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT id, name, create_date, modify_date, access_key FROM $table_name WHERE name LIKE %s ORDER BY $orderby $order", array($search)), ARRAY_A);             
        
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
    
    function quote($str) {
        return sprintf("'%s'", $str);
    }
    

}

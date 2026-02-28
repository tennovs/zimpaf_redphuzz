<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class uDraw_SVG_Table extends WP_List_Table {
    var $all_items;
    function __construct() {
        parent::__construct(array(
            'singular' => 'svg_template',
            'plural' => 'svg_templates',
            'ajax' => false
        ));
    }
    function column_default($item, $column_name) {
        return $item[$column_name];
    }
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="ID[]" value="%s" />',
            $item['ID']
        );
    }
    function column_name ($item) {
        global $wpdb;
        $order_table = $wpdb->prefix . 'woocommerce_order_items';
        $actions = array();
        $actions['ID'] = sprintf('<span class="identifier">%s %s</span>',  __('ID: ', 'udraw_svg'), $item['ID']);
        if (is_user_logged_in()) {
            if (current_user_can('edit_udraw_templates')) {
                $actions['edit'] = sprintf('<a href="#" data-template_id="%s" data-action="edit">%s</a>', $item['ID'], __('Edit', 'udraw_svg'));
            }
            if (current_user_can('delete_udraw_templates')) {
                $actions['delete'] = sprintf('<a href="#" data-action="delete" data-access_key="%s" data-template_id="%s">%s</a>', $item['access_key'], $item['ID'], __('Delete', 'udraw_svg'));
            }
        }
        return sprintf('%s%s',
            html_entity_decode(stripslashes($item['name']), ENT_QUOTES),
            $this->row_actions($actions)
        );
    }
    function column_template_preview ($item) {
        $preview = $item['preview'];
        return sprintf('<img src="%s?v=%s" class="preview_thumbnail"/>',
            $preview,
            uniqid()
        );
    }
    function column_size ($item) {
        $design_summary = unserialize($item['design_summary']);
        $_width = (isset($design_summary['width'])) ? $design_summary['width'] : 'N/A';
        $_height = (isset($design_summary['height'])) ? $design_summary['height'] : 'N/A';
        return sprintf(
            '%s x %s',
            $_width,
            $_height
        );
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
                $response .= "<a class=\"button button-primary button-small\" href=\"post-new.php?post_type=product&udraw_svg_template_id=". $item['ID'] . "&udraw_svg_action=new-product\">Link To Product</a>";
            }
        }
        return $response;
    
    }
    function column_date ($item) {
        $_date = $item['date'];
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
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text     
            'name' => __('Name', 'udraw_svg'),
            'template_preview' => __('Preview', 'udraw_svg'),
            'size' => __('Size (px)', 'udraw_svg'),
            'linked_products' => __('Linked Products', 'udraw_svg'),
            'date' => __('Date', 'udraw_svg')
        );
        return $columns;
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'cb' => array('ID', true),
            'date' => array('date', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array();
        if (is_user_logged_in()) {
            if (current_user_can('delete_udraw_templates')) {
                $actions['delete'] = 'Delete';
            }
        }
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $templates_table = $wpdb->prefix . 'udraw_svg_templates';
        
        if (is_user_logged_in()) {
            if (current_user_can('delete_udraw_templates')) {
                if ('delete' === $this->current_action()) {
                    $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : array();
                    if (is_array($ids)) $ids = implode(',', $ids);

                    if (!empty($ids)) {
                        $wpdb->query("DELETE FROM $templates_table WHERE ID IN($ids)");
                    }
                }
            }
        }
    }
    
    function prepare_items() {
        global $wpdb;
        $templates_table_name = $wpdb->prefix . 'udraw_svg_templates';

        $per_page = 5;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // prepare query params, as usual current page, order by and order direction
        $paged = $this->get_pagenum();
        $orderby = (isset($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'date';
        $order = (isset($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
        $search = isset($_REQUEST['s']) ? '%%'. htmlentities($_REQUEST['s'], ENT_QUOTES) . '%%' : '%';
        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $templates_table_name WHERE name LIKE %s ORDER BY $orderby $order", $search), ARRAY_A);
        
        // Get all uDraw SVG products from WooCommerce, and attach that to the templates when displaying the table.
        $uDraw_SVG = new uDraw_SVG();
        $SVG_products = $uDraw_SVG->get_SVG_products();    

        for($i = 0; $i < count($this->items); ++$i) {
            $this->items[$i]["products"] = array();
            foreach ($SVG_products->posts as $post) {
                if (metadata_exists('post', $post->ID, '_udraw_SVG_template_id')) {
                    $templateId = get_post_meta($post->ID, '_udraw_SVG_template_id', true);
                    if (strlen($templateId) > 0) {
                        if ($this->items[$i]["ID"] === $templateId) {
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
        
        $this->all_items = $this->items;
        
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
}
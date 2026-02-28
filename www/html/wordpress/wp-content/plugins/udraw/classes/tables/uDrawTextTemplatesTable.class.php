<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class uDraw_Text_Templates extends WP_List_Table {    

    public $_text_templates_category_array;
    
    function __construct() {
        global $status, $page;  
        
        $this->getTextTemplatesCategory();
        
        parent::__construct(array(
            'singular' => 'udraw_text_template',
            'plural' => 'udraw_text_templates',
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
        return '<img src ="' . $item['preview'] . '" alt="Preview Template" style="max-height:50px;"></img>';
    }
    
    function column_tags($item) {
        $tags_string = '';
        if (!is_null($item['tags'])) {
            $tags = explode(',', $item['tags']);
            $tags_string = join(' | ', $tags);
        }
        
        
        $tags_display = sprintf('<div class="display_tags active" data-template_id="%s"><span class="tags_span" data-template_id="%s">%s</span></div>',
            $item['ID'],
            $item['ID'],
            $tags_string
        );
            
        $update_tags_btn = '';
        $tags_div = '';
        
        if (current_user_can('edit_udraw_templates')) {
            $br = strlen($tags_string) > 0 ? '<br />' : '';
            $update_tags_btn = sprintf('%s <a href="#" class="button button-primary button-small update_tags active" data-template_id="%s">%s</a>',
                $br,
                $item['ID'],
                __('Update Tags', 'udraw')
            );
            $tags_input = sprintf('<input type="text" class="template-tags tag_input" data-template_id="%s" value="%s" />',
                $item['ID'],
                $item['tags']
            );
            $save_tags_btn = sprintf('<a href="#" class="button save_tags" data-template_id="%s">Save</a>',
                $item['ID']
            );
            $tags_div = sprintf('<div class="update_tags" data-template_id="%s">%s%s</div>',
                $item['ID'],
                $tags_input,
                $save_tags_btn
            );
        }
        
        return sprintf('%s %s %s',
                $tags_display,
                $update_tags_btn,
                $tags_div
            );
    }

    function column_category($item) {
        echo '<select id="select-text-template-category-id-'.$item["public_key"].'" class="select-text-template-category" style="width: 150px;"';
        if (!current_user_can('edit_udraw_templates')) {
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
                jQuery('#select-text-template-category-id-<?php echo $item["public_key"]?> option[value="'+categoryID+'"]').prop('selected', true);
            }
        </script>
        <?php
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
        
        if (current_user_can('delete_udraw_templates')) {
            $actions = array(
                'id'        => sprintf('<span>ID: %s</span>', $item['ID']),
                'edit'      => sprintf('<a href="?page=udraw_edit_text_template&template_id=%s&key=%s">%s</a>', $item['ID'], $item['public_key'], __('Edit', 'udraw')),
                'delete'    => sprintf('<a href="#" data-action="delete" data-template_id="%s" data-key="%s">%s</a>', $item['ID'], $item['public_key'], __('Delete', 'udraw'))
            );
        } else if (current_user_can('edit_udraw_templates')) {
            $actions = array(
                'id'    => sprintf('<span>ID: %s</span>', $item['ID']),
                'edit'  => sprintf('<a href="?page=udraw_edit_text_template&template_id=%s&key=%s">%s</a>', $item['ID'], $item['public_key'], __('Edit', 'udraw_table')),
            );
        }
        
        return sprintf('%s %s',
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
            '<input type="checkbox" name="ID[]" value="%s" />',
            $item['ID']
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
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text            
            'name'      => __('Name', 'udraw'),
            'preview'   => __('Preview', 'udraw'),
            'tags'      => __('Tags', 'udraw'),
            'date'      => __('Date', 'udraw'),
            'category'  => __('Category', 'udraw')
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
        $table_name = $wpdb->prefix . 'udraw_text_templates';

        if ($this->current_action() === 'delete') {
            $ids = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : array();
            if (is_array($ids)) { $ids = implode(',', $ids); }
            
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");
            }
        }
    }    
    /**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'udraw_text_templates';

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

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results("SELECT * FROM $table_name ORDER BY $orderby $order", ARRAY_A);
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
        $_pagedArray = array_splice($this->items,($paged-1)*$per_page, $per_page);
        // prevent results from showing empty when using paging.
        if (count($_pagedArray) > 0) {
            $this->items = $_pagedArray;
        }
    }

    function getTextTemplatesCategory() {
        global $wpdb;
        $text_templates_category_DB = $wpdb->prefix . 'udraw_text_templates_category';
        $this->_text_templates_category_array = $wpdb->get_results("SELECT * FROM $text_templates_category_DB");
    }
    
    function buildTextTemplatesCategoryPath($ID, $path){
        $category_name = '';
        $parent_id;
        foreach ($this->_text_templates_category_array as $category) {
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
            return $this->buildTextTemplatesCategoryPath($parent_id, $path);
        }
    }
    
    function buildCategorySelectOptions ($parent_id) {
        foreach ($this->_text_templates_category_array as $category) {
            if ($category->parent_id == $parent_id) {
                echo '<option value="'.$category->ID.'">'.stripslashes($this->buildTextTemplatesCategoryPath($category->ID, "")).'</option>';
                $this->buildCategorySelectOptions($category->ID);
            }
        }
    }
}

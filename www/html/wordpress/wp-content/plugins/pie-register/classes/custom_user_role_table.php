<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists( 'WP_List_Table' ) )
    require_once( $this->admin_path  . 'includes/class-wp-list-table.php' );

class Pie_Custom_Role_Table extends WP_List_Table
{
    private $order;
    private $orderby;
    private $non_pro;
    
    public function __construct()
    {
        parent :: __construct( array(
            'singular' => 'Pie Register Custom Roles',
            'plural'   => 'Pie Register Custom Roles',
            'ajax'     => true
        ) );
    }
    
    private function get_sql_results()
    {
        global $wpdb;
        $args = array( "`id`", "`role_key`", "`role_name`","`wp_role_name`" ); 
        
        $sql_select = implode( ', ', $args );
		$prefix=$wpdb->prefix."pieregister_";
        $roletable=$prefix."custom_user_roles";
        $order_by = "`id` DESC";

        $query = $wpdb->prepare("SELECT $sql_select,%s as `action` FROM `$roletable` ORDER BY $order_by",  "action");
        
        $sql_results = $wpdb->get_results( $query );
		
        return $sql_results;
    }
    public function search_box($text, $input_id)
    {
        return false;
    }
    public function ajax_user_can() 
    {
        return current_user_can( 'edit_posts' );
    }
    public function no_items() 
    {
         _e( 'No User Roles Created ', "pie-register" );
    }
    public function get_views()
    {
        return array();
    }
    function get_table_classes() {
        return array( 'widefat', 'fixed', 'pie-list-table', 'striped', $this->_args['plural'] );
    }
    public function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'role_name'     => __( 'Role Name', "pie-register" ),
            'role_key'      => __( 'Role Key', "pie-register" ),
            'wp_role_name'  => __( 'Inherited Permissions From', "pie-register"),
            'action'        => __( 'Action',"pie-register" )
        );
        
        return $columns;        
    }
    public function get_sortable_columns()
    {
        $sortable = array();
        
        return $sortable;
    }
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" value="%s" class="role_fields_class"  />', intval($item->id)
        ); 
    }
    public function prepare_items( )
    {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( 
            $columns,
            $hidden,
            $sortable 
        );

        // SQL results
        $posts = $this->get_sql_results();
		$id = 1;
		
        foreach ( $posts as $key => $post )
        {
            $role_name       = ucwords($post->wp_role_name);
            $custom_role     = $post->role_name;
            $custom_role_key = $post->role_key;
            
            $posts[ $key ]->cb = '<input type="checkbox" value="'.$posts[ $key ]->id.'" class="role_fields_class" id="role_fields[id_'.$id.']" />';

			/*Role name*/ 
            $posts[ $key ]->role_name = '<span id="field_id_1_'.$id.'">'.$custom_role.'</span>';
            
			/*Role key*/ 
			$posts[ $key ]->role_key = '<span id="field_id_1_'.$id.'">'.$custom_role_key.'</span>';
			
			/*Inherited Role*/ 
            $posts[ $key ]->wp_role_name = '<span>'.$role_name.'</span>';

			$posts[ $key ]->action = '<a class="delete" href="javascript:;" onclick="confirmDelUserRole(\''.$post->id.'\',\''.$custom_role.'\',\''.$custom_role_key.'\');" title="'.__("Delete","pie-register").'"></a>';
			$id++;
        }
        
        $this->items = $posts;	
    }
    public function column_default( $item, $column_name )
    {
        return $item->$column_name;
    }
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php //Bulk option here ?>
            </div>
             
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="piereg_clear" />
        </div>
        <?php
    }
    public function extra_tablenav( $which )
    {
        global $wp_meta_boxes;
        $views = $this->get_views();
        if ( empty( $views ) )
            return;
        $this->views();
    }
}
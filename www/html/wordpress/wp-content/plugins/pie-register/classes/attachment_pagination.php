<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists( 'WP_List_Table' ) )
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	
class Pie_Attachment_Table extends WP_List_Table
{
    
    public function __construct()
    {
        parent :: __construct( array(
            'singular' => 'Pie Register Attachment',
            'plural'   => 'Pie Register Attachments',
            'ajax'     => true
        ) );
    }
    
    private function get_sql_results()
    {

        return array();
        
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
         _e( 'No attachments were found', "pie-register" );
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
            'user_id'           => __( 'ID' ),
            'username'          => __( 'Username', "pie-register" ),
            'email'             => __( 'Email Address', "pie-register"),
            'attachments'       => __( 'Attachments', "pie-register")
        );
        
        return $columns;        
    }
    public function prepare_items( )
    {
        $columns  = $this->get_columns();
        $hidden   = array();
        $this->_column_headers = array( 
            $columns,
            $hidden
        );

        // SQL results
        $posts = $this->get_sql_results();
                
        empty( $posts ) AND $posts = array();

        $posts_array = $posts;

        // Prepare the data
        
        $this->items = $posts_array;	
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
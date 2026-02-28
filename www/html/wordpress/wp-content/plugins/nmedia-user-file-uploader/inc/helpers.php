<?php 

 if( ! defined('ABSPATH' ) ){
	exit;
}


function wpfm_pa($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

// loading template files
function wpfm_load_templates( $template_name, $vars = null) {
    if( $vars != null && is_array($vars) ){
    extract( $vars );
    }

    $template_path =  WPFM_PATH . "/templates/{$template_name}";
    if( file_exists( $template_path ) ){
    	include_once( $template_path );
    } else {
     die( "Error while loading file {$template_path}" );
    }
}

// util templates load function
function ffmwp_load_template($template, $array=null){
  
    if(is_array($array)){
        extract($array);
    }
    include(WPFM_PATH.'/v20/templates/'.$template);
}

function wpfm_render_settings_input($data) {


    $wpfm_settings = get_option(WPFM_SHORT_NAME . '_settings');
    $field_id   = isset($data['id']) ? $data['id'] : '';
    $type       = isset($data['type']) ? $data['type'] : '';
    $value      = (isset($wpfm_settings[ $data['id']]) ? $wpfm_settings[ $data['id']] : $data['default'] );
    $value      = stripslashes( $value );
    $options    = (isset($data['options']) ? $data['options'] : '');

    // wpfm_pa($wpfm_settings);
    switch($type) {

        case 'text' :
            echo '<input type="text" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ). '" value="' . esc_attr( $value ). '" class="regular-text">';
            break;
            
        case 'url' :
            echo '<input type="url" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ). '" value="' . esc_url( $value ). '" class="regular-url">';
            break;

        case 'textarea':
            echo '<textarea cols="45" rows="6" name="' . esc_attr( $field_id ). '" id="' . esc_attr( $field_id ). '" >'.esc_textarea( $value ).'</textarea>';
            break;

        case 'checkbox':

            foreach($options as $k => $label){
                $label_id = $field_id.'-'.$k;
                echo '<label for="'. esc_attr( $label_id ).'">';
                echo '<input type="checkbox" name="' .esc_attr( $field_id ). '" id="'.esc_attr( $label_id ).'" value="' . esc_attr( $k ). '" '.checked( $value, $k, false).'>';
                printf(__(" %s", 'wpfm'), $label);
                echo '</label> ';
            }

            break;

        case 'radio':
                
            foreach($options as $k => $label){
                $label_id = $field_id.'-'.$k;
                echo '<label for="'.esc_attr( $label_id ).'">';
                echo '<input type="radio" name="' .esc_attr(  $field_id ). '" id="'.esc_attr( $label_id).'" value="' . esc_attr( $k ). '" '.checked( $value, $k, false).'>';
                printf(__(" %s", 'wpfm'), $label);
                echo '</label> ';
            }
                
            break;

        case 'select':

            $default = (isset($data['default']) ? $data['default'] : 'Select option');

            echo '<select name="' . esc_attr( $field_id ). '" id="' . esc_attr( $field_id ). '" class="the_chosen">';
            echo '<option value="">'.esc_html($default).'</option>';

            foreach($options as $k => $label){

                echo '<option value="'.esc_attr($k).'" '.selected( $value, $k, false).'>'.esc_attr($label).'</option>';
            }

            echo '</select>';
            break;

        case 'color' :
            echo '<input type="text" name="' . esc_attr( $field_id ). '" id="' . esc_attr( $field_id ). '" value="' . esc_attr( $value ). '" class="wp-color-field">';
            break;
        case 'btn':
            echo '<a href="#" id="' . esc_attr( $field_id ). '" class="button button-primary">Post FTP files</a>';
        break;

    }
}
/*
* this function is extrating single
* key must be prefixed e.g: _key
*/
function wpfm_get_option($key, $default=null){

   //HINT: $key should be under schore (_) prefix

    $full_key =  WPFM_SHORT_NAME. $key;
    
    $plugin_settings = get_option ( WPFM_SHORT_NAME . '_settings' );
    
    $the_option = (isset($plugin_settings[$full_key]) ? $plugin_settings[$full_key]: '');

    if (is_array($the_option))
      return $the_option != null ? $the_option : $default;
    else
      return $the_option != null ? stripcslashes( trim($the_option) ) : $default;
        
}

/**
* file pre download functions
*/
function wpfm_get_attachment_file_name( $file_post_id ){

    $filename = null;
    
    // New version: first check in meta
    if( $filename = get_post_meta($file_post_id, 'wpfm_file_name', true) ) 
        return $filename;
        
    $args = array(
    'post_type' => 'attachment',
    'numberposts' => null,
    'post_status' => null,
    'post_parent' => $file_post_id,
    );
    
    $attachments = get_posts($args);
    
    if ($attachments) {
        foreach($attachments as $attachment){
            $file_path = get_post_meta($attachment->ID, '_wp_attached_file');
            $file_type = wp_check_filetype(basename( $file_path[0] ), null );
            $filename = basename ( get_attached_file( $attachment->ID ) );
        
        }
    }
    
    return $filename;
}

// Set/create directory and return path
function wpfm_files_setup_get_directory($user_id=null, $dir='root', $file_id=null) {
    
    $upload_dir = wp_upload_dir();
    $users_directories = wpfm_get_users_directories( $user_id, $file_id );
    

    $wpfm_root_dir = null;
    foreach( $users_directories as $key => $path ) {
    	
    	$full_path = $upload_dir ['basedir'].'/'.WPFM_USER_UPLOADS.'/'.$path.'/';
    	if( $key == $dir ) {
    	    $wpfm_root_dir = $full_path;
    	}
    	
    	wp_mkdir_p( $full_path );
    }
    
    return apply_filters('wpfm_user_dir_path', $wpfm_root_dir);

    
}

function wpfm_get_all_dir_name(){
    
    $args = array(
			'orderby'        => 'date',
			'post_type'      => 'wpfm-files',
			
			 
	);
	
	$all_posts = get_posts($args);

	$all_dir = array();
	foreach($all_posts as $post){
	    $file = new WPFM_File($post->ID);
	    $file_type = wp_check_filetype($file->name);
	    
		if ( !$file_type['ext'] ) {
		    $all_dir[$file->id] =  $file->title;
		}
			
	}
	
	var_dump($all_dir);
	
	return $all_dir;
   
}


/*
 * geting file path of author
 */
function wpfm_get_author_file_dir_path( $authorid ) {
    
    $current_user = get_userdata( $authorid );
    // sometimes user deleted but file remains
    if( !$current_user ) return '';
    
    $upload_dir = wp_upload_dir ();
    
    $path = $upload_dir ['basedir'] . '/' . WPFM_USER_UPLOADS . '/' . $current_user -> user_login . '/';
    return apply_filters('wpfm_file_author_dir_path', $path, $authorid);
}

// Get file dir path
function wpfm_get_file_path_by_id($file_id) {
    
    $file_dir_path = null;
    //First check in meta (new vesion)
    if( ! $file_dir_path = get_post_meta($file_id, 'wpfm_dir_path', true) ) {
       
        $file_owner = get_post_field('post_author', $file_id);
        $file_name = wpfm_get_attachment_file_name($file_id);
        
        $file_dir_path = wpfm_get_author_file_dir_path($file_owner) . $file_name;
    }
    
    if( file_exists($file_dir_path) ) {
        return $file_dir_path;
    }
    
    return null;
}
// Get filesize by id
function wpfm_get_file_size_by_id($file_id) {
    
    $file_size = '';
    
    if( ! $file_size = get_post_meta($file_id, 'wpfm_file_size', true) ) {
        
        $file_dir_path = wpfm_get_file_path_by_id($file_id);
        
        if( file_exists($file_dir_path) ) {
            $file_size = size_format( filesize( $file_path_dir ));
        }
    }
    
    return $file_size;
}
/*
 * getting file URL
 */
function wpfm_get_file_dir_url($owner_id = null, $thumbs = false, $file_id=null) {
    
    if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) )
        return;
    
    if( $owner_id ) {
        $current_user = get_userdata( $owner_id );
    } else {
        $current_user = wpfm_get_current_user();    
    }

    
    $content_url = wp_upload_dir();
    $content_url = $content_url['baseurl'];
    
    // sometimes user deleted but file remains
    if( !$current_user ) return $content_url . '/' . WPFM_USER_UPLOADS . '/';
    
    $author_name = $current_user -> user_login;
    
    
    if($file_id !=null){
        $author_name  = get_post_meta($file_id, 'author_name', true);
    }
    
    $author_name = apply_filters('wpfm_file_url_author_dir', $author_name, $current_user, $file_id);

    if ($thumbs)
        return $content_url . '/' . WPFM_USER_UPLOADS . '/' . $author_name . '/thumbs/';
    else
        return $content_url . '/' . WPFM_USER_UPLOADS . '/' . $author_name . '/';
}

function wpfm_get_file_meta($post_id){
        
    $existing_meta = get_option('filemanager_meta');
    
    $fileMeta = array();
    if($existing_meta){
        foreach($existing_meta as $key => $meta)
        {
            $fileMeta[$meta['title']] = get_post_meta($post_id, $meta['data_name'], true);  
        }
    }    
    return $fileMeta;
}


// This function will return a current user object
// if logged in then current otherwise will see public user settings
function wpfm_get_current_user() {

    $current_user_id = null;
    if( is_user_logged_in() ) {
        
        $current_user_id = get_current_user_id();
    } elseif ( wpfm_is_guest_upload_allow() ) {

        $current_user_id = get_option('wpfm_guest_user_id');
    }
    
    // if( isset($_GET['file_owner']) && $_GET['file_owner'] != '' ) {
    //     $current_user_id = $_GET['file_owner'];
    // }
    
    $current_user   = get_userdata( $current_user_id );
    
    return apply_filters('wpfm_get_current_user', $current_user);
}

// Check if current user is public
function wpfm_is_current_user_is_public() {
    
    $current_user = wpfm_get_current_user();
    
    $is_public = false;
    if( get_option('wpfm_guest_user_id') == $current_user->ID ) {
        
        $is_public = true;
    }
    
    return $is_public;
}

// Get logged in user role
function wpfm_get_current_user_role() {

    $current_user = wpfm_get_current_user();
    
    if( $current_user ) {
        
        $role = ( array ) $current_user->roles;
        return $role[0];
    } else {
        return false;
    }
}


// Return message after file saved
function wpfm_get_message_file_saved() {
    
    $saved_message = wpfm_get_option('_file_saved');
    $saved_message = $saved_message == '' ? __("File saved successfully", "wpfm") : $saved_message;
    return apply_filters('wpfm_file_saved_message', $saved_message);
}

function wpfm_get_post_file_url( $file_id, $is_thumb=false ) {
    
    $file_name  = wpfm_get_attachment_file_name($file_id);
    $file_owner = get_post_field('post_author', $file_id);
    $file_url   = wpfm_get_file_dir_url($file_owner, $is_thumb, $file_id) . $file_name;
    
    return apply_filters( 'wpfm_file_url', $file_url, $file_id);
}

// Loading required scripts
// Context contains: 1- upload (default) 2- download
function wpfm_load_scripts( $context = 'upload' ) {
    
    // AnimateModal
    wp_enqueue_style( 'wpfm-normalize', WPFM_URL .'/css/normalize.min.css');
    wp_enqueue_style( 'wpfm-animate-modal', WPFM_URL .'/css/animate.min.css');
    wp_enqueue_script( 'wpfm-modal-js', WPFM_URL .'/js/animatedModal.min.js', array('jquery'));
    
    
    // Dashicons frontend
    wp_enqueue_style( 'dashicons' );
    
    $wpfm_lib = wpfm_get_view_type() == 'grid' ? 'wpfm-lib.js' : 'wpfm-table.js';

    wp_enqueue_script( 'wpfm-lib', WPFM_URL.'/js/'.$wpfm_lib, array('jquery','wpfm-modal-js'));
    
    switch ($context) {
        case 'upload':
                $wpfm_js_vars = wpfm_array_fileapi_vars();
            break;
        case 'download':
                $wpfm_js_vars = wpfm_array_download_vars();
            break;
        case 'easy_digital_download':
                $wpfm_js_vars = wpfm_array_digital_downloads_vars();
            break;
    }
 
    wp_localize_script('wpfm-lib', 'wpfm_vars', $wpfm_js_vars);
    
    if( wpfm_is_upload_form_visible( $context ) ) {
        wp_enqueue_script( 'wpfm-fileapi', WPFM_URL.'/js/fileapi/dist/FileAPI.min.js');
        wp_enqueue_script( 'wpfm-file', WPFM_URL.'/js/wpfm-file.js', array('wpfm-fileapi'));
        wp_localize_script('wpfm-file', 'wpfm_file_vars', $wpfm_js_vars);
    }
          
    wp_enqueue_style( 'wpfm-font-awesome', WPFM_URL .'/css/font-awesome.min.css');
    wp_enqueue_style( 'wpfm-jquery-ui', WPFM_URL .'/css/jquery-ui.min.css');
    wp_enqueue_style( 'wpfm-select', WPFM_URL .'/css/select2.css');
    wp_enqueue_style( 'wpfm-styles', WPFM_URL .'/css/styles.css');
    wp_enqueue_script( 'wpfm-blcok-ui-js', WPFM_URL .'/js/block-ui.js', array('jquery','jquery-ui-core'));
    
    wp_enqueue_script( 'wpfm-mixitup-js', WPFM_URL .'/js/jquery.mixitup.min.js', array('jquery'));
    wp_enqueue_script( 'wpfm-main-js', WPFM_URL .'/js/wpfm-main.js', array('jquery','wpfm-modal-js', 'jquery-ui-draggable', 'jquery-ui-droppable'));
    
    wp_localize_script('wpfm-main-js', 'wpfm_main', wpfm_array_main_vars());
    
    wp_enqueue_script( 'wpfm-select-js', WPFM_URL .'/js/select2.js', array('jquery'));
    wp_enqueue_script( 'wpfm-block-ui-js', WPFM_URL .'/js/block-ui.js', array('jquery'));
    
    if ( wpfm_get_option("_disable_bootstarp") != "yes" ) {

        wp_dequeue_script('bootstrap');
        wp_dequeue_script('bootstrap-js');
        
        wp_enqueue_script( 'wpfm-bootstrap4-min-js', WPFM_URL .'/css/bootstrap-4/bootstrap.min.js');
    }

    wp_enqueue_style( 'wpfm-modal-css', WPFM_URL .'/css/wpfm-modal.css');

    // SweetAlert
    wp_enqueue_style( 'wpfm-swal', WPFM_URL .'/js/swal/sweetalert.css');
    wp_enqueue_script( 'wpfm-swal-js', WPFM_URL .'/js/swal/sweetalert.js', array('jquery'));
    
    /**
     * New frontend WP Utile based scripts and styles added
     * Date: July 25, 2021
     * By: Najeeb, Faheem
     **/
    wp_enqueue_style( 'ffmwp-css', WPFM_URL.'/v20/css/ffmwp.css' );
    wp_enqueue_style( 'ppom-grid-css', WPFM_URL.'/v20/css/ppom-grid.css' );
	do_action( 'wpfm_after_scripts_loaded' );
	
}

function nm_plugin_time_difference($date){
	if(empty($date)) {
		return "No date provided";
	}

	$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths         = array("60","60","24","7","4.35","12","10");

	$now             = time();
	$unix_date         = strtotime($date);

	// check validity of date
	if(empty($unix_date)) {
		return "Bad date";
	}

	// is it future date or past date
	if($now > $unix_date) {
		$difference     = $now - $unix_date;
		$tense         = "ago";

	} else {
		$difference     = $unix_date - $now;
		$tense         = "from now";
	}

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		$periods[$j].= "s";
	}

	return "$difference $periods[$j] {$tense}";
}

function wpfm_get_all_filetypes() {

    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'wpfm-files',
    );
    $wpfm_posts = get_posts( $args );
    $wpfm_type = array();
    foreach ($wpfm_posts as $wpfm_post) {

        $file = new WPFM_File($wpfm_post->ID);
        if ($file->node_type != 'dir') {
            $file_type = wp_check_filetype($file->name);
                
                $wpfm_type[$file_type["ext"]] = isset( $wpfm_type[$file_type["ext"]] ) ? $wpfm_type[$file_type["ext"]] + 1 : 1;
        }
    }

    return $wpfm_type;
}

// get the monthly uploads array for barchart in dashboard
function wpfm_get_previous_month_uploades() {

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'wpfm-files',
        'date_query' => array(
            array(
              'after'   => '-1 month',
            ),
        ),
    );
    $the_query = new WP_Query( $args );

    $monthly_uploads = array();
    if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
        
        $post_date = get_the_date( 'm/d', get_the_ID() );
    
        $monthly_uploads[$post_date] = isset( $monthly_uploads[$post_date] ) ? $monthly_uploads[$post_date] + 1 : 1;
    
    endwhile;
    wp_reset_postdata();

    else : ?>
    <p><?php echo  ''; ?></p>
<?php endif;
    
    // return the monthly uploads array [date => no of posts] 
    return $monthly_uploads;
}

function wpfm_get_top_menu() {
    
    $wpfm_menu[] = array('icon' => 'dashicons-portfolio',
                        'label' => sprintf(__(" My Files (%d Items)", 'wpfm'), wpfm_get_wp_files_count(get_current_user_id())),
                        'link'  => add_query_arg('file-src','my')
                        );
                        
    return apply_filters( 'wpfm_top_menu', $wpfm_menu);
}

function wpfm_get_download_top_menu() {
    
    $wpfm_menu[] = array('icon' => 'dashicons-portfolio',
                        'label' => __(" Files", 'wpfm'),
                        'link'  => '#'
                        );
                        
    return apply_filters( 'wpfm_download_top_menu', $wpfm_menu);
}

function get_paichart_data() {

    $wpfm_filetypes = wpfm_get_all_filetypes();
    $google_filetype_label = array(
        'Task',
        'Filetypes in WPFM'
    );
    $google_chart_array = array();
    array_push($google_chart_array, $google_filetype_label);

    foreach ($wpfm_filetypes as $filetype => $filetype_value) {

        $filetype = array(
            $filetype,
            $filetype_value,
        );
        array_push($google_chart_array, $filetype);
    }

    return json_encode($google_chart_array);
}

function get_barchart_data() {

    $monthly_uploads = wpfm_get_previous_month_uploades();
    $google_uploads_label = array(
        'Day',
        'Uploads'
    );
    $google_monthly_uploads = array();
    array_push($google_monthly_uploads, $google_uploads_label);

    foreach ($monthly_uploads as $fileupload_date => $file_uploaded) {

     $file_uploads = array(
         $fileupload_date,
         $file_uploaded,
     );
     array_push($google_monthly_uploads, $file_uploads);
    }
    
    return json_encode($google_monthly_uploads);
}
// check if addon is installed
function wpfm_is_addon_installed( $addon ) {
    
    $addon_active = false;
    
    switch( $addon ) {
        
        case 'user-specific':
            if( class_exists('WPFM_UserSpecific') ) {
                $addon_active = true;
            }
        break;
        
        case 'amazon-upload':
            if( class_exists('WPFM_AmazonS3') ) {
                $addon_active = true;
            }
        break;
        
        case 'table-view':
            if( class_exists('WPFM_TableView') ) {
                $addon_active = true;
            }
        break;
    }
    
    return $addon_active;
}
// check if upload is allow
function wpfm_is_upload_form_visible( $context='upload' ) {
    
    $visible = true;
    
    if( $context == 'download' ) return apply_filters( 'wpfm_upload_form_visible', false );
    if( $context == 'easy_digital_download' ) return apply_filters( 'wpfm_upload_form_visible', false );
    
    if( ! wpfm_can_user_upload_file() || wpfm_get_option('_hide_uploader')  == 'yes'   ) {
        
        $visible = false;
    }
    
    return apply_filters( 'wpfm_upload_form_visible', $visible );
}

// check if menu area visible is allow
function wpfm_is_frontend_menu_visible() {
    
    $visible = true;
    if( wpfm_get_file_request_type() == 'wpfm_bp') {
        $visible = false;
    }
    return apply_filters( 'wpfm_frontend_menu_visible', $visible );
}

// check if upload is allow
function wpfm_is_files_area_visible( $context='upload' ) {
    
    $visible = true;
    
    if( wpfm_get_option('_hide_files')  == 'yes' && $context !== 'download') {
        $visible = false;
    }
    
    // If revision addon enabled then files will be hidden
    if( isset($_GET['file_id']) ){
        $visible = false;
    }
    
    
    return apply_filters( 'wpfm_files_visible', $visible );
}


// check if upload is allow
function wpfm_is_keep_log_file_name() {
    
    $keep_log = true;
    
    if( wpfm_get_option('_keep_old_log')  == 'yes') {
        
        $keep_log = false;
    }
    
    
    return $keep_log ;
}

function wpfm_files_allower_per_user() {
    
    $max_files  = wpfm_get_option('_max_files_user');
    
    if( $max_files == ''){
        $max_files = 10000;
    }
    
    $current_user       = wpfm_get_current_user();
    $current_user_role  = key($current_user->caps);
    $found_role_quota   = '';
    $arr_role_quota     = wpfm_get_option ( '_on_of_file_role' );
    $allow_upload       = true;
    
    
        if( $arr_role_quota ) {
        
            $arr_role_quota = explode("\n", $arr_role_quota);
            foreach($arr_role_quota as $role_quota){
                $role_quota = explode('|', $role_quota);
                if (strtolower($role_quota[0]) == strtolower($current_user_role)){
                    
                        $max_files = $role_quota[1];
                }
            }
        }
   
    
    return intval($max_files);
}

function user_upload_file_in_given_dir(){
    
    $current_user       = wpfm_get_current_user();
    $current_user_role  = key($current_user->caps);
    $dir_name = '';
    $dir_id = 0;
    
    $arr_default_dir     = wpfm_get_option ( '_default_dir' );
    if( $arr_default_dir ) {
        
        $arr_default_dir = explode("\n", $arr_default_dir);
        foreach($arr_default_dir as $role_quota){
            $role_quota = explode('|', $role_quota);
            
            if (strtolower($role_quota[0]) == strtolower($current_user_role)){
                    $dir_name = $role_quota[1];
                    $dir_id = check_dir_name_exist($dir_name);
            }
        }
    }
    
       
    return $dir_id;
    
}

function check_dir_name_exist($dir_name){
    
    $dir_id     = 0;
    $cpt_dir    = array();
    $args       = array("post_type" => "wpfm-files", "s" => $dir_name);
    $posts_form = get_posts($args);
    
    if (!empty($posts_form)) {
        foreach($posts_form as $form){
            $cpt_dir[$form->ID] = strtolower($form->post_title); 
            
        }
    }
    
    if(array_search(trim(strtolower($dir_name)), $cpt_dir))
        $dir_id = array_search(trim(strtolower($dir_name)), $cpt_dir);
          
    
    return $dir_id;
}
// this return user quota by role in mb if defined.
function wpfm_get_user_quota_by_role(){
    
        $current_user = wpfm_get_current_user();

        $found_role_quota = '';
        $current_user_role = key($current_user->caps);
        $arr_role_quota = wpfm_get_option ( '_default_quota' );
        
        if( $arr_role_quota ) {
            
            $arr_role_quota = explode("\n", $arr_role_quota);
            foreach($arr_role_quota as $role_quota){
                $role_quota = explode('|', $role_quota);
                if (strtolower($role_quota[0]) == strtolower($current_user_role)){
                    $found_role_quota = str_replace('mb', '', $role_quota[1]);
                    break;  
                }
            }
        }

        return $found_role_quota;
    }

// user role base max filesize limit.
function wpfm_max_filesize_limit_by_role(){
    
        $current_user      = wpfm_get_current_user();
        $found_role_quota  = '';
        $current_user_role = key($current_user->caps);
        $arr_role_quota    = wpfm_get_option ( '_filesize_role' );
        $max_filesize      = wpfm_get_option('_max_file_size') != '' ? str_replace('mb','', wpfm_get_option('_max_file_size')) : '2';

        if( $arr_role_quota ) {
        
            $arr_role_quota = explode("\n", $arr_role_quota);
            foreach($arr_role_quota as $role_quota){
                $role_quota = explode('|', $role_quota);
                if (strtolower($role_quota[0]) == strtolower($current_user_role)){
                    
                    $max_filesize   = str_replace('mb', '', $role_quota[1]);
                    
                }
            }
        }
            
           
        return $max_filesize;
}

function wpfm_set_limit_upload_file_onetime(){
    
        $found_role_quota  = '';
        $current_user      = wpfm_get_current_user();
        $current_user_role = key($current_user->caps);
        $arr_role_quota    = wpfm_get_option ( '_number_server_file_role' );
        $max_file_limit    = 10000;
 
        if( $arr_role_quota ) {
        
            $arr_role_quota = explode("\n", $arr_role_quota);
            foreach($arr_role_quota as $role_quota){
                $role_quota = explode('|', $role_quota);
                if (strtolower($role_quota[0]) == strtolower($current_user_role)){
                    
                    $max_file_limit  = $role_quota[1];
                    
                }
            }
        }
            
        return intval($max_file_limit);
}

function user_can_upload_file_one_atemp(){
    
    $file_limit    = wpfm_get_option ( '_max_files' );
    
    if($file_limit == '')
        $file_limit = 100;
        
    return apply_filters('wpfm_can_user_upload_file_oneatemp', $file_limit);
        
        
}
      
function wpfm_can_user_upload_file() {

    $allow_upload   = true;
    $current_user   = wpfm_get_current_user();
    $user_id        = $current_user->ID;

    if( wpfm_files_allower_per_user() <= wpfm_get_wp_files_count($user_id) ) {

        $allow_upload = false;
    }
    
    
    if(wpfm_set_limit_upload_file_onetime() <= get_user_meta($user_id, 'wpfm_file_upload_limit',true) ) {
        $allow_upload = false;
    }

    // Now checking file size restriction
    $allow_file_size = wpfm_get_user_quota_by_role();
    if( $allow_file_size != '' ) {

        //convert mb to bits
        $allow_file_size = $allow_file_size * 1024 *1024;

        if( $allow_file_size <= wpfm_get_user_files_size($user_id) ) {
            $allow_upload = false;
        }
    }
    return $allow_upload;
}

function wpfm_can_user_create_directory() {
    
    $allow_directory = wpfm_get_option('_create_dir');
    
    $allow_directory = $allow_directory == 'yes' ? true : false;
    
    // If revision addon enabled then files will be hidden
    if( isset($_GET['file_id']) ){
        $allow_directory = false;
    }
    
    return apply_filters('wpfm_can_user_create_directory', $allow_directory);
}
// Check if user allow to send file via email
function wpfm_is_user_allow_to_send_file() {
    
    $is_email_share_allow = wpfm_get_option('_send_file');
    
    $is_email_share_allow = $is_email_share_allow == 'yes' ? true : false;
    
    // if view from download then disable sharing
    $download_id = get_query_var('download_id');
    if( $download_id ) {
        $is_email_share_allow = false;
    }
    
    // if view is shared
    if(defined('WPFM_REQUEST_TYPE') && WPFM_REQUEST_TYPE == 'wpfm_shared' ) {
        
        $is_email_share_allow = false;
    }
    
    return apply_filters('wpfm_allow_user_to_send_file', $is_email_share_allow);
}
// Check if user allow to send file via email
function wpfm_is_user_to_edit_file() {
    
    $is_allow_to_edit = true;
    
    // if view from download then disable sharing
    $download_id = get_query_var('download_id');
    if( $download_id ) {
        $is_allow_to_edit = true;
    }
    
    // if view is shared
    if( defined('WPFM_REQUEST_TYPE') && WPFM_REQUEST_TYPE == 'wpfm_shared' ) {
        
        $is_allow_to_edit = true;
    }
    
    return apply_filters('wpfm_allow_user_to_edit_file', $is_allow_to_edit);
}
/*
    these all are used in download manager post meta boxs
*/
function wpfm_access_roles( $dafault_roles ) {
    global $wp_roles;
    $all_roles = $wp_roles->roles;
    $html = '';
    $html .= '<select style="width:100%;"  class="multiple-select" name="access_roles[]" multiple="multiple">';
        foreach ($all_roles as $role_name => $role) { 
            
            $selected = '';
            if ( in_array($role_name , $dafault_roles) ) {
                $selected = 'selected';
            }
            
            $html .= '<option value="'.$role_name.'" '.$selected.'> '.$role["name"].' </option>';
        }
    $html .= '</select>';
    return $html;
}

function wpfm_access_users( $dafault_users ) {

    $all_users = get_users();
    $html = '';
    $html .= '<select style="width:100%;"  class="multiple-select" name="access_users[]" multiple="multiple">';
        foreach ($all_users as $index => $user) { 
                $selected = '';
            if ( in_array($user->ID , $dafault_users) ) {
                $selected = 'selected';
            }
            $html .= '<option value="'.$user->ID.'" '.$selected.'> '.$user->data->user_nicename.' </option>';
        }
    $html .= '</select>';
    return $html;
}

function wpfm_source_roles( $dafault_roles ) {
    global $wp_roles;
    $all_roles = $wp_roles->roles;
    $html = '';
    $html .= '<select style="width:100%;"  class="multiple-select" name="source_roles[]" multiple="multiple">';
        foreach ($all_roles as $role_name => $role) { 
            
            $selected = '';
            if ( in_array($role_name , $dafault_roles) ) {
                $selected = 'selected';
            }
            
            $html .= '<option value="'.$role_name.'" '.$selected.'> '.$role["name"].' </option>';
        }
    $html .= '</select>';
    return $html;
}

function wpfm_source_users( $dafault_users ) {

    $all_users = get_users();
    $html = '';
    $html .= '<select style="width:100%;" class="multiple-select" name="source_users[]" multiple="multiple">';
        foreach ($all_users as $index => $user) { 
            
            $selected = '';
            if ( in_array($user->ID , $dafault_users) ) {
                $selected = 'selected';
            }
            $html .= '<option value="'.$user->ID.'" '.$selected.'> '.$user->data->user_nicename.' </option>';
        }
    $html .= '</select>';
    return $html;
}

function wpfm_source_groups( $dafault_groups ){
    
    $all_groups = get_terms( array('taxonomy' => 'file_groups', 'hide_empty' => false,) );
    
    if( is_wp_error($all_groups) ) {
        return __("Pro version should be installed to see file groups", "wpfm");
    }
    
    $html = '';
    $html .= '<select style="width:100%;" class="multiple-select" name="source_group[]" multiple="multiple">';
        foreach ($all_groups as $group) {
            
            $selected = '';
            if ( in_array($group->term_id , $dafault_groups) ) {
                $selected = 'selected';
            }
            $html .= '<option value="'.$group->term_id.'" '.$selected.'> '.$group->name.' </option>';
        }
    $html .= '</select>';
    return $html;
}

// Checking if PRO version is installed
function wpfm_is_pro_installed() {
    
    $return = false;
    
    if( class_exists('WPFM_PRO') ) 
        $return = true;
   
   return $return;
}

// Checking if PRO version is installed
function wpfm_digital_download_addon_installed() {
    
    $return = false;
    
    if( class_exists('NM_EDDW') ) 
        $return = true;
   
   return $return;
}

// files pagination
function pagination_apply_on_files($files_limits){
    
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$data= new WP_Query(array(
	    'post_type'=>'wpfm-files',
	    'posts_per_page' => $files_limits,
	    'paged' => $paged,
	));

	if($data->have_posts()) {
	
	    $total_pages = $data->max_num_pages;
	
	    if ($total_pages > 1){
	
	        $current_page = max(1, get_query_var('paged'));
			echo '<div class="pagination">';
	        echo paginate_links(array(
	            'base' => get_pagenum_link(1) . '%_%',
	            'format' => '/page/%#%',
	            'current' => $current_page,
	            'total' => $total_pages,
	            'prev_text'    => __('« prev'),
	            'next_text'    => __('next »'),
	        ));
	        echo '</div>';
	    }
	}	
    
}

// get view type (grid or table)
function wpfm_get_view_type(){
    
    $view = wpfm_get_option('_enable_table');
    return $view == 'yes' && wpfm_is_addon_installed('table-view') ? 'table' : 'grid';
}

function wpfm_get_fields_meta(){
    $serialized_data = get_option( 'wpfm_file_meta');
    return is_array($serialized_data) ? $serialized_data : json_decode($serialized_data, true);
}

// removing the key
function wpfm_get_fields_meta_array(){
    $serialized_data = get_option( 'wpfm_file_meta');
    $serialized_data = is_array($serialized_data) ? $serialized_data : json_decode($serialized_data, true);
    // $unkey = array_map(function($meta){
    //     return array_pop($meta);
    // }, $serialized_data);
    
    $new_array = [];
    
    if( !$serialized_data ) return $new_array;
    
    foreach($serialized_data as $index => $meta){
        foreach($meta as $type => $meta2){
            $meta2['type'] = $type;
            $new_array[] = $meta2;
        }
    }
    
    return $new_array;
}

// BuddyPress helper
function wpfm_is_bp_group_public($bp_group_id){
    $is_public = '';
    if( function_exists('groups_get_groupmeta') ){
        $is_public = groups_get_groupmeta( $bp_group_id, 'nm_bp_file_sharing');
    }
    return $is_public == 'group' ? true : false;
}

// allowed html tags for esc html in wp
function wpfm_get_allowed_html(){
    $allowed_html = 'post';
    return apply_filters('wpfm_allowed_html_tags', $allowed_html);
}

// getting file manager version
function wpfm_get_current_version() {
    return defined('WPFM_VERSION') ? intval(WPFM_VERSION) : 15;
}
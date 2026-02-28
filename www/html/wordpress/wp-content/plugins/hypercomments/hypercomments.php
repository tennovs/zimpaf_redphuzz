<?php
/*
Plugin Name: HyperComments
Plugin URI: http://hypercomments.com/
Description: HyperComments - New dimension of comments. Hypercomments technology allows commenting a specific word or a piece of text. 
Version: 1.2.1
Author:  Alexandr Bazyk
Author URI: http://www.hypercomments.com/
*/
define('HC_DEV', false);
require_once(dirname(__FILE__) . '/export.php');
require_once(dirname(__FILE__) . '/widgets.php');
define('HC_CONTENT_URL', get_option('siteurl') . '/wp-content');
define('HC_PLUGIN_URL', HC_CONTENT_URL . '/plugins/hypercomments');
define('HC_XML_PATH',$_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads');
define('HC_VERSION', '1.2.0');


if(HC_DEV == true){
    define('HC_URL', 'https://w3dev.hypercomments.com');
}else{
    define('HC_URL', 'https://www.hypercomments.com');
}
$is_append = false;

register_deactivation_hook(__FILE__,'hc_delete');
register_activation_hook(__FILE__,'hc_active');          
add_action('init', 'hc_request_handler');
add_action('admin_head', 'hc_admin_head');
add_filter('the_content', 'hc_the_content_filter', 50);
add_action('upgrader_process_complete', array('hypercomments', 'hc_upgrade'), 10, 2);
add_action('wp_footer', 'hc_count_widget',20);
add_filter('comments_template', 'hc_comments_template');
add_filter('comments_number', 'hc_comments_text');
add_filter('get_comments_number', 'hc_comments_number');

add_action('admin_menu', 'hc_add_pages', 10);
add_action('admin_notices', 'hc_messages');

/**
 * The event handler
 * @global type $post
 * @global type $wpdb
 * @return type 
 */
function hc_request_handler()
{
    global $post;
    global $wpdb; 

  
    if(empty($_GET['hc_action'])) return;

    switch ($_GET['hc_action']) {      
        case 'export_comments':               
            if (current_user_can('manage_options')) {  
                try{   
                    $response_array = array();
                    $id_post = $_GET['post'];
                    require_once(dirname(__FILE__) . '/export.php');
                    $posts = $wpdb->get_results($wpdb->prepare("
                        SELECT * FROM $wpdb->posts WHERE ID=%d",$id_post)); 
                    foreach ($posts as $p) {
                        $wxr = hc_export_wp($p);
                        if($wxr){
                            $dir_root  = dirname(dirname(dirname(__FILE__))).'/uploads';
                            if(is_dir($dir_root)){
                                $file_name = time().'_'.rand(0,100).'.xml';                    
                                $file_root = $dir_root.'/'.$file_name;                      
                                $file_path = HC_CONTENT_URL.'/uploads/'.$file_name;
                                $write_file = file_put_contents($file_root, $wxr);
                                if($write_file){
                                    $json_arr = array(
                                        'service'     => 'wordpress',
                                        'pageID'      => $p->ID,
                                        'widget_id'   => get_option('hc_wid'),
                                        'request_url' => $file_path,                      
                                        'result_url'  => admin_url('index.php').'?hc_action=delete_xml&xml='.$file_name,
                                        'result'      => 'success'
                                    );                                             
                                                          
                                }else{
                                    $json_arr = array('result'=>'error','description'=>'Error writing XML');
                                }
                            }else{
                                $json_arr = array('result'=>'error','description'=>'Error writing XML');
                            }
                        }else{
                             $json_arr = array('result'=>'error','description'=>'Failed to generate XML');
                        }
                        $response_array[] = $json_arr;
                    }
                    echo json_encode($response_array);                       
                    die();
                }catch(Exception $e){
                     $json_arr = array('result'=>'error','description'=>'Error');
                }
            }                     
        break;   
        case 'save_wid':
            update_option('hc_wid', $_GET['wid']);
            update_option('hc_access', $_GET['access']);
            update_option('hc_secret_key', $_GET['secret_key']);
            update_option('hc_synch', 'on');
            update_option('hc_plan', $_GET['plan']);
            echo $_GET['access'];
            die();
        break;
        case 'delete_xml':
            if(isset($_GET['result']) && $_GET['result'] == 'success'){
                $filename = dirname(dirname(dirname(__FILE__))).'/uploads/'.$_GET['xml'];
                unlink($filename);
                return json_encode(array('result'=>'success'));
            }else{
                return json_encode(array('result'=>'error'));
            }
            exit();
        break;
        case 'notify':
            $data = stripslashes($_POST['data']);
            $time = $_POST['time'];
            $signature = $_POST['signature'];
                                         
            if(get_option('hc_secret_key')){                                                                 
                     if($signature == md5((string)get_option('hc_secret_key').(string)$data.(string)$time)){
                         $data_decode = json_decode($data);                                                               
                         foreach($data_decode as $cmd){                                 
                             switch($cmd->cmd){
                                 case 'streamMessage':                                       
                                    $post_id_mas = explode('?', $cmd->xid);
                                    $pos = strpos($cmd->xid,'=');                                         
                                    $post_id = substr($cmd->xid, $pos+1);
                                    
                                    $parent_id =$wpdb->get_var($wpdb->prepare( "SELECT comment_id FROM $wpdb->commentmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1", 'hc_comment_id', $cmd->parent_id));

                                    $data = array(
                                        'comment_post_ID'  => $post_id,
                                        'comment_author'   => $cmd->nick,                                          
                                        'comment_content'  => $cmd->text,                                         
                                        'comment_parent'   => $parent_id,                                                                                   
                                        'comment_date'     => date('Y-m-d H:i:s', time() + (get_option('gmt_offset') * 3600)),
                                        'comment_date_gmt' => date('Y-m-d H:i:s'),
                                        'comment_approved' => 1,
                                    );    
                                    if(isset($cmd->user_id)){
                                        $data['user_id'] = $cmd->user_id;
                                    }else{
                                        $data['user_id'] = 0;
                                    }
                                    if(isset($cmd->ip)){
                                        $data['comment_author_IP'] = $cmd->ip;
                                    }else{
                                        $data['comment_author_IP'] = '';
                                    }
                                    $comments_id = wp_insert_comment($data);
                                    update_comment_meta($comments_id,'hc_comment_id',$cmd->id);                                           
                                    exit();                                      
                                 break;
                                 case 'streamEditMessage':                                      
                                     $comments_id = $wpdb->get_var($wpdb->prepare( "SELECT comment_id FROM $wpdb->commentmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1", 'hc_comment_id', $cmd->id));
                                     $commentarr = array();
                                     $commentarr['comment_ID'] = $comments_id;  
                                     $commentarr['comment_content'] = $cmd->text;                                       
                                     wp_update_comment( $commentarr );                                        
                                     exit();                                           
                                 break;
                                 case 'streamRemoveMessage':
                                     $comments_id = $wpdb->get_var($wpdb->prepare( "SELECT comment_id FROM $wpdb->commentmeta WHERE meta_key = %s AND meta_value = %s LIMIT 1", 'hc_comment_id', $cmd->id));
                                     wp_delete_comment ($comments_id);                                           
                                     exit();                                   
                                 break;
                             }                                
                         }
                     }
            }                  
            
        break;
        case 'update_options':
            $options = (array)json_decode(stripslashes($_POST['data']));
            foreach ($options as $key => $value) {
                update_option($key, $value);
            }
            echo json_encode(array('result'=>'success'));
            exit();
        break;
        case 'get_css':
            $css_url  = $_POST['css'];
            $css_file = end(explode('/', $css_url));
            $css_path = HC_CONTENT_URL.'/uploads/hc_css/'.$css_file;
            $str = file_get_contents($css_path);
            if($str)
                echo json_encode(array('result'=>'success','css'=>$str));
            else
                echo json_encode(array('result'=>'error','code'=>101));
            exit();
        break;
        case 'save_css':
            $css = $_POST['css'];
            $dir_root  = dirname(dirname(dirname(__FILE__))).'/uploads';
            if(is_dir($dir_root)){
                $css_dir = $dir_root.'/hc_css';
                if(!is_dir( $css_dir )){
                    if(!mkdir($css_dir, 0777)){
                        echo json_encode(array(
                            'result' => 'error',
                            'code'    => 103
                        ));
                        die();
                    }
                }
                // del old css
                if($handle = opendir( $css_dir )){
                    while (false !== ($file = readdir($handle))) { 
                        if(end(explode(".", $file)) == 'css'){               
                            unlink($css_dir.'/'.$file);
                        }             
                    }
                    closedir($handle);
                }

                $file_name  = 'hc_css_'.time().'.css';                    
                $file_root  = $css_dir.'/'.$file_name;                      
                $write_file = file_put_contents($file_root, stripslashes($css));
                $file_path  = HC_CONTENT_URL.'/uploads/hc_css/'.$file_name;
                if($write_file){
                    echo json_encode(array('result'=>'success','css'=>$file_path));
                }else{
                    echo json_encode(array('result'=>'error','code'=>102));
                }
            }else{
                echo json_encode(array('result'=>'error','code'=>101));
            }
            exit();
        break;
    }

}

/**
 * Include styles and files in the admin
 */
function hc_admin_head()
{
    $page = (isset($_GET['page']) ? $_GET['page'] : null);
    if ( $page == 'hypercomments') {
?>
    <link rel='stylesheet' href='<?php echo plugins_url( '/css/hypercomments.css', __FILE__ );?>'  type='text/css' />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>jQueryHC = jQuery.noConflict(true);</script>
    <script src="<?php echo plugins_url( '/js/hypercomments.js', __FILE__ );?>"></script>
<?php
    }
}

/**
 * Action by activating the plugin
 */
function hc_active()
{
      update_option('hc_selector', '.hc_counter_comments');
      update_option('hc_append', '#hypercomments_widget');
      update_option('hc_word_limit', 10);
      update_option('hc_realtime', true);
      update_option('hc_comments_level', 4);
      update_option('hc_locale', hc_get_language());
}

/**
 * Action by upgrade the plugin
 */
function hc_upgrade()
{
    update_option('hc_locale', hc_get_language());
}

/**
 * Action when uninstall plugin
 */
function hc_delete()
{  
    delete_option('hc_wid');
    delete_option('hc_access');
    delete_option('hc_selector');
    delete_option('hc_title_widget');
    delete_option('hc_secret_key');
    delete_option('hc_synch');
    delete_option('hc_root');
    delete_option('hc_label_counter');
    delete_option('hc_append');
    delete_option('hc_word_limit');
    delete_option('hc_realtime');
    delete_option('hc_comments_level');
    delete_option('hc_locale');
}

/**
 * Changing the template comments
 * @param type $value
 * @return type 
 */
function hc_comments_template($value)
{     
   return dirname(__FILE__) . '/comments.php';
}

function hc_comments_number($count)
{
    global $post;
    return $count;
}

/**
 * Replacement of counters
 * @global type $post
 * @param type $comment_text
 * @return type 
 */
function hc_comments_text($comment_text)
{
    global $post;  
    $parse = parse_url($post->guid);
    $url   =  str_replace('https://','',str_replace('http://','',str_replace('www.','',str_replace($parse['host'], get_option('home'), $post->guid))));
    return '<span class="hc_counter_comments" data-xid="'.$url.'">'.$comment_text.'</span>'; 
}

/**
 * Insert widget on the site of the old comments
 * @global type $post 
 */
function hc_show_script()
{      
    global $post;      
    global $is_append;
    $parse = parse_url($post->guid);
    if(get_option('hc_root')){
        $url = str_replace('https://','',str_replace('http://','',str_replace('www.','',$post->guid)));  
    }else{
        $url = str_replace('https://','',str_replace('http://','',str_replace('www.','',str_replace($parse['host'], get_option('home'), $post->guid))));  
    }  
    if($is_append === false && $post->comment_status == 'open'){
        echo hc_get_comment_widget(
                get_option('hc_wid'),
                get_option('hc_locale'),
                $url, 
                hc_get_auth(),
                get_option('hc_append'),
                get_option('hc_word_limit'),
                get_option('hc_realtime'),
                get_option('hc_comments_level'),
                get_option('hc_css')
            );
    }else{
        echo hc_new_append_widget(get_option('hc_append'));
    }
}

/**
 * Insert widget counters
 */
function hc_count_widget()
{       
  if(!is_singular() && !(is_page() && is_single())) {     
    $counter_label = (get_option('hc_label_counter') && strlen(get_option('hc_label_counter'))>0) ?
                        ' label : "'.get_option('hc_label_counter').'"' : '';
    echo hc_get_counter_widget(get_option('hc_wid'), get_option('hc_selector'), get_option('hc_locale'), $counter_label);
  }
}

/**
 * Include manage file
 */
function hc_options_page()
{
    include_once(dirname(__FILE__) . '/hc_manage.php');
}

/**
 * Insert menu in the Comments section
 */
function hc_add_pages()
{
    add_submenu_page(
        'edit-comments.php',
        'HyperComments',
        'HyperComments',
        'moderate_comments',
        'hypercomments',
        'hc_options_page'
    );
}

/**
 * Notice of setting the widget
 */
function hc_messages()
{
    $page = (isset($_GET['page']) ? $_GET['page'] : null);
    if ( !get_option('hc_wid') && $page != 'hypercomments') {
       echo '<div class="updated"><p><b>'.__('You must <a href="edit-comments.php?page=hypercomments">configure the plugin</a> to enable HyperComments.', 'hypercomments').'</b></p></div>';
    }
}

/**
 * Returns the locale
 * @return type 
 */
function hc_get_language($type='comment')
{
    if($type = 'adm')
        $support_lang = array('en','ru');
    else
        $support_lang = hc_get_locales();

    $local = get_locale();
    $local_lang = explode('_',$local);  
    if(in_array($local_lang[0], $support_lang))
    {
        return $local_lang[0];
    }else{
        return'en';
    } 
}
/**
 * Return all hc widget comments locales
 * @global type $wpdb
 * @global type $post
 * @returnarray
 */
function hc_get_locales()
{
    return array('ar','cs','de','dk','en','es','es_ar','fr','ja','kk','ko','lv','pl','pt','ru','tr','uk','zh','tt','be');
}
/**
 * Filter content
 * @global type $post
 * @param type $content
 * @return string 
 */
function hc_the_content_filter( $content )
{ 
    global $post;     
    global $is_append;    
    global $user;

    $parse = parse_url($post->guid);
    if(get_option('hc_root')){
        $url = str_replace('https://','',str_replace('http://','',str_replace('www.','',$post->guid)));  
    }else{
        $url = str_replace('https://','',str_replace('http://','',str_replace('www.','',str_replace($parse['host'], get_option('home'), $post->guid))));  
    }       
    if(get_option('hc_title_widget')){
        if($post->comment_status == 'open'){  
            if ( !is_singular()){          
                $content = sprintf(
                    '%s<div class="hc_content_comments" data-xid="'.$url.'"></div>',
                  $content          
                );
            }
        }
    }    
    
    if(is_singular()){   
        if($post->comment_status == 'open'){   
            $is_append = true;          
            $widget = hc_get_comment_widget(
                get_option('hc_wid'),
                get_option('hc_locale'),
                $url, 
                hc_get_auth(),
                get_option('hc_append'),
                get_option('hc_word_limit'),
                get_option('hc_realtime'),
                get_option('hc_comments_level'),
                get_option('hc_css')
            );
            $content = $content.$widget;         
        }
    }
    return $content;
}
/**
 * Get auth token
 * @global type $user
 * @return string 
 */
function hc_get_auth()
{
    global $current_user;
    get_currentuserinfo();
    if(is_user_logged_in() && get_option('hc_synch')){
        $user = array(
            'nick'   => $current_user->display_name,
            'avatar' => hc_parse_avatar($current_user->ID, 36),
            'id'     => $current_user->ID,
            'email'  => $current_user->user_email
        );
        $secret = get_option('hc_secret_key');
        $time   = time();    
        $base64 = base64_encode(json_encode($user));    
        $sign   = md5($secret . $base64 . $time);
        $auth = $base64 . "_" . $time . "_" . $sign;
        $hcp = ', auth : "'.$auth.'"';              
    }else{
        $hcp = '';
    }
    return $hcp;
}
/**
 * Get avatar path
 * @param type $email
 * @return string 
 */
function hc_parse_avatar($email)
{
    $html_avatar = get_avatar($email);
    preg_match("/src=(\'|\")(.*)(\'|\")/Uis", $html_avatar, $matches);
    $avatar_src = substr(trim($matches[0]), 5, strlen($matches[0]) - 6);
    if(strpos($avatar_src, 'http') === false)
    {
        $avatar_src = get_option('siteurl').$avatar_src;
    }
    return $avatar_src;
}
/**
 * Return all post comments
 * @global type $wpdb
 * @global type $post
 * @return boolean array
 */
function hc_get_comments_post()
{
    global $wpdb;
    global $post;
    $comments = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID=%d", $post->ID));
    return $comments;
}
/**
 * Return all posts with comments
 * @global type $wpdb
 * @global type $post
 * @returnarray
 */
function hc_get_post_export()
{
    global $wpdb;
    global $post;
    $posts = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $wpdb->posts WHERE post_type != 'revision' AND post_status = %s AND comment_count > %d", 'publish', 0)); 
    return $posts;
}

?>

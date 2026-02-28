<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vx_form_admin_pages' ) ) {

class cfx_form_admin_pages{
  
     
public function __construct(){ 
    // Adding Menu and Submenu items
add_action('admin_menu', array( $this, 'add_menu'),26);  //menu
add_action('admin_init', array( $this, 'init')); 
if (isset($_GET['page']) && (($_GET['page'] == cfx_form::$page))) {
add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ),99 );
}
add_action( 'wp_ajax_vx_form_save_api_settings',  array($this,'save_api_settings'));
global $pagenow;  
  if(in_array($pagenow, array("admin-ajax.php"))){
add_action( 'wp_ajax_vx_form_save_main_form',  array($this,'save_form')); 

add_action( 'wp_ajax_vx_other_forms_export_html', array($this,'forms_export_html_ajax'));
 
add_action( 'wp_ajax_vx_form_import_forms', array($this,'import_forms_ajax')); 
add_action( 'wp_ajax_vx_form_edit_entry_note', array($this,'edit_entry_note_ajax')); 
add_action( 'wp_ajax_vx_form_edit_entry_toggle', array($this,'edit_entry_toggle_ajax'));
add_action( 'wp_ajax_'.cfx_form::$id.'_form_status_toggle', array($this,'form_status_toggle_ajax'));
} 
add_filter( 'set-screen-option', array($this,'set_per_page'), 10, 3 );
add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);
  
}

public function init(){ 
if(isset($_GET['page']) && $_GET['page'] == cfx_form::$page && !empty($_REQUEST['cfx_form_tab_action'])){
$this->handle_form();            
}
}    
    /**
     * Adding Menu and Submenu Items to backend
     * 
     */
public function add_menu(){

        $page_title = 'CRM Perks Forms';
        $menu_title = 'CRM Forms';
        $capability = cfx_form::$id.'_read_settings';
        $menu_slug  = cfx_form::$page;
        $function   = array( $this, 'settings_pages');
        $icon='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNy4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjJweCIgaGVpZ2h0PSIyNC43MjdweCIgdmlld0JveD0iMCAwIDIyIDI0LjcyNyIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjIgMjQuNzI3IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0yMSwxOC4wMjlsLTkuOTAyLDUuODM1bC0xMC01LjY2NUwxLDYuNjk5bDkuOTAyLTUuODM1bDEwLDUuNjY1TDIxLDE4LjAyOXogTTYuMDExLDcuOTQzDQoJYy0wLjgyOCwwLTEuNSwwLjY3Mi0xLjUsMS41YzAsMC44MjgsMC42NzIsMS41LDEuNSwxLjVzMS41LTAuNjcyLDEuNS0xLjVDNy41MTEsOC42MTQsNi44MzksNy45NDMsNi4wMTEsNy45NDN6IE0xOC4zODYsOC45NDNoLTl2Mg0KCWg5VjguOTQzeiBNMTguMzg2LDEzLjk0M2gtOXYyaDlWMTMuOTQzeiBNNi4wMTEsMTMuOTQzYy0wLjgyOCwwLTEuNSwwLjY3Mi0xLjUsMS41czAuNjcyLDEuNSwxLjUsMS41czEuNS0wLjY3MiwxLjUtMS41DQoJUzYuODM5LDEzLjk0Myw2LjAxMSwxMy45NDN6Ii8+DQo8L3N2Zz4NCg==';
add_menu_page($page_title,$menu_title,$capability,$menu_slug,$function,$icon); //'dashicons-screenoptions'

 if(isset($_GET['tab']) && in_array($_GET['tab'],array('entries','entries_abo') )){
add_filter( 'manage_toplevel_page_'.cfx_form::$page.'_columns', array($this,'screen_cols') );
  //add form fields , if form options do not exist
add_filter( 'get_user_option_managetoplevel_page_'.cfx_form::$page.'columnshidden', array($this,'hide_cols') );
add_action("load-toplevel_page_".cfx_form::$page, array($this,'screen_options'));
} 

}
public function register_admin_scripts() {    
wp_enqueue_style( 'fontawsome', cfx_form_plugin_url.'css/font-awesome.min.css' ); 
wp_enqueue_style('cfx-jquery-ui', cfx_form_plugin_url. 'css/jquery-ui.css');

    
wp_enqueue_script( cfx_form::$id.'_colorbox', cfx_form_plugin_url. 'js/jquery.colorbox-min.js');
wp_enqueue_style( cfx_form::$id.'_colorbox', cfx_form_plugin_url. 'css/box-5/colorbox.css' );
    

wp_enqueue_style( cfx_form::$id.'_admin', cfx_form_plugin_url. 'css/admin.css' );
wp_register_script( cfx_form::$id.'_admin', cfx_form_plugin_url. 'js/admin.js?t='.time() );
   
//form designer
   wp_register_script( cfx_form::$id.'_chroma', cfx_form_plugin_url. 'js/chroma.min.js' ); 
   wp_register_script( cfx_form::$id.'_less', cfx_form_plugin_url. 'js/less.min.js' ); 
   wp_register_script( cfx_form::$id.'_google', cfx_form_plugin_url. 'js/jquery.fontselect.js' );
   wp_register_style(cfx_form::$id.'_google', cfx_form_plugin_url. 'css/fontselect.css');
   
wp_register_script( cfx_form::$id.'_sel2',cfx_form_plugin_url. 'js/select2.min.js' );
wp_register_style(cfx_form::$id.'_sel2', cfx_form_plugin_url. 'css/select2.min.css');
   
        //opacity color picker 
   wp_register_script( cfx_form::$id.'_color', cfx_form_plugin_url. 'js/jquery.minicolors.min.js'); 
   wp_register_style(cfx_form::$id.'_color', cfx_form_plugin_url. 'css/jquery.minicolors.css');
   
}
    /**
  * Add settings and support link
  * 
  * @param mixed $links
  * @param mixed $file
  */
  public function plugin_action_links( $links, $file ) {
   $slug=cfx_form::get_slug();
      if ( $file == $slug ) {
          $settings_link=cfx_form::link_to_settings();
            array_unshift( $links, '<a href="' .$settings_link.'">Settings</a>' );
        }
        return $links;
   }

    /**
     * Display Admin Screen.
     */
public function settings_pages(){
    $title='CRM Perks Forms';
    
    $form_id=cfx_form::post('form_id');
    $form=array();
    if( !empty($form_id)){
        $form = cfx_form::get_form($form_id); 
      $title='# '.$form_id.' '.$form['name'];  
    } 
?>
<div id="crm-panel" class="wrap">
  <div id="crm-panel-body">
   <div class="logo_bg">
    <div id="crm-panel-top" class="wp-ui-primary">
      <div id="menu_button" class="fa fa-bars"></div>
      <div class="logo"> <span class="fa fa-dashboard"></span> <span id="cfx_title"><?php echo $title; ?></span> </div>
          <div style="clear: both"></div>
          </div>
</div>
<?php 
//wp_deregister_script('ninja_forms_admin_submissions');
wp_dequeue_script('ninja_forms_admin_submissions');
$page_url =admin_url('admin.php?page='.cfx_form::$page);
$tab=cfx_form::post('tab');
 
$tabs_form=array(''=>array('label'=>'Form Fields','icon'=>'laptop'),'step2'=>array('label'=>'Form Design','icon'=>'eyedropper'),'step3'=>array('label'=>'Form Settings','icon'=>'wrench'),'notify'=>array('label'=>'Notifications','icon'=>'bell')); 

$tabs_form=apply_filters('side_menu_form_'.cfx_form::$id,$tabs_form);
 
$tabs=array(''=>array('label'=>'Forms','icon'=>'th-list'));
$tabs=apply_filters('side_menu_'.cfx_form::$id,$tabs);
$tabs['settings']=array('label'=>'Settings','icon'=>'plug');
  
$border_styles=array("solid"=>"Solid","dotted"=>"Dotted","dashed"=>"Dashed");
$border_types=array("top"=>"Top","bottom"=>"Bottom","right"=>"Right",'left'=>'Left','all'=>'All Sides');
$bg_position_x=array('center'=>'Center',"right"=>"Right",'left'=>'Left','initial'=>'initial');
$bg_position_y=array('center'=>'Center',"top"=>"Top","bottom"=>"Bottom",'initial'=>'initial');
$bg_repeate=array("no-repeat"=>"No Repeate","repeat"=>"Repeate");

//$new_form=$this->get_new_form();   
include_once(cfx_form_plugin_dir.'templates/sidebar.php');
?>
<div id="crm-panel-content">
<div class="crm_content_panel">
<?php
//if(isset($_GET['msg'])){ 
    $this->show_msgs(); 
//}
$is_section=apply_filters('add_page_html_'.cfx_form::$id,false,$form);
if($is_section === false){
if(!empty($form_id)){ 
    
$font_styles=array("normal"=>"Normal","italic_n"=>"Italic Normal","italic_b"=>"Italic Bold","bold"=>"Bold");
$font_align=array("left"=>"Left","center"=>"Center","right"=>"Right","justify"=>"Justify");

$lightbox_positions_temp=array("center"=>"Center","center_top"=>"Center Top","top"=>"Top","bottom"=>"Bottom","top_left"=>"Top Left","top_right"=>"Top Right","bottom_left"=>"Bottom Left","bottom_right"=>"Bottom Right");
 $lightbox_positions=array();
 foreach($lightbox_positions_temp as $k=>$v){
  $v=array('label'=>$v);
   //  if(!in_array($k,array('center','center_top','top'))){ $v['disable']='true'; }
     $lightbox_positions[$k]=$v;
 }
 $lightbox_positions=apply_filters(cfx_form::$id.'_popup_positions',$lightbox_positions);

 $scrol_pos_temp=array('5'=>'5% to bottom','10'=>'10% to bottom','1'=>'1% to bottom','70'=>'70% to bottom','98'=>'98% to bottom','50'=>'50% t bottom','25'=>'25% to bottom','15'=>'15% to bottom');
 $scrol_pos=array();
 foreach($scrol_pos_temp as $k=>$v){
  $v=array('label'=>$v);
     if(!in_array($k,array('5'))){ $v['disable']='true'; }
     $scrol_pos[$k]=$v;
 }
$scrol_pos=apply_filters(cfx_form::$id.'_popup_scroll_pos',$scrol_pos);
 
 $auto_open_temp=array(''=>'Select Any','page'=>'Auto Open on page load','leave'=>'When mouse leaves active browser window','scroll'=>'Scrolls to bottom','time'=>'After few Seconds');
 $auto_open=array();
 foreach($auto_open_temp as $k=>$v){
$v=array('label'=>$v);
     if(in_array($k,array('time','scroll','leave'))){ $v['disable']='true'; }
     $auto_open[$k]=$v; 
 }              
$auto_open=apply_filters(cfx_form::$id.'_popup_auto_open',$auto_open);
$btn_types_temp=array('right'=>'Fixed Button (Bottom Right)','left'=>'Fixed Button (Bottom Left)'
,'rightm'=>'Fixed Button (Middle Right)','leftm'=>'Fixed Button (Middle Left)','html'=>'Custom HTML',''=>'No Button');
 $btn_types=array();
 foreach($btn_types_temp as $k=>$v){
     $v=array('label'=>$v);
     if(!in_array($k,array('right',''))){ $v['disable']='true'; }
     $btn_types[$k]=$v;
 } 
$btn_types=apply_filters(cfx_form::$id.'_popup_btn_types',$btn_types);

$options= !empty($form['settings']) ? $form['settings'] : array();

if(empty($form['fields'])){
$fields_json='{"1":{"label":"Name","type":"text","cookie_name":"","par_name":"","field_val":" ","hint":"","desc":"","err_msg":"","field_class":"","con_class":"","default":"","max":"","mask":"","custom_mask":"","valid_err_msg":"","text_height":"","input_align":"1"}}';                
$form['fields']=json_decode($fields_json,true);        
}        
if(empty($options['head_img'])){
$options['head_img']=cfx_form_plugin_url."images/logo.png"; 
}
if(empty($options['button_img'])){
$options['button_img']=cfx_form_plugin_url."images/button.png"; 
}
if(empty($options['outer_img'])){
$options['outer_img']=cfx_form_plugin_url."images/bg.png"; 
}
if( !empty($options['outer_img']) && filter_var($options['outer_img'],FILTER_VALIDATE_URL) === false){
$options['outer_img']=cfx_form_plugin_url."images/".$options['outer_img'];   
}

//$options['head_img']=cfx_form_plugin_url."images/logo.png"; 
//$options['button_img']=cfx_form_plugin_url."images/button.png"; 
//$options['outer_img']=cfx_form_plugin_url."images/multi-color-bg6.png"; 
//
//$options['outer_img']=cfx_form_plugin_url."images/bg-email.png"; 
//$options['outer_img']=cfx_form_plugin_url."images/colored-border.png"; 
//$options['outer_img']=cfx_form_plugin_url."images/bg.png";  
//$options['outer_img']=cfx_form_plugin_url."images/large-tech4.png"; 
//$options['outer_img']=cfx_form_plugin_url."images/small-coffee.png"; 
if(empty($form['config'])){
$options['screen_text']='Contact Us';  
$options['screen_color']='#dd6a6a';  
$options['screen_border']='0';  
$options['screen_border_color']='#ffffff';  
$options['screen_icon']='21';  
$options['lightbox_opacity']=".8";  
$options['lightbox_pos']='center';  
$options['warning_msg']="Warning Message";
$options['thanks_msg']="Thank You Message";
$options['ip_msg']="Sorry, Your IP Adress is blocked";
$options['limit_msg']="Sorry, Form Submission limit is Over";
$options['start_msg']="Sorry, Form not started yet";
$options['box_button']='<button>Contact Us</button>';
} 
?>
 <div id="crm_load_theme" style="<?php if($tab == "step2"){echo "display:block";} ?>" class="crm_load_page"><i class="fa fa-spinner fa-spin"></i></div>
 <div class="crm_overlay"></div>  
 <?php
 wp_enqueue_script('jquery-ui-slider');
 wp_enqueue_script(cfx_form::$id.'_color');
 wp_enqueue_style(cfx_form::$id.'_color');
if($tab == ""){ 
$field_types=cfx_form::get_field_types(); 
$types_js=array();
$types_group=array('basic'=>array(),'advanced'=>array());
foreach($field_types as $k=>$v){
 if(empty($v['group'])){ $v['group']='basic'; }
 $v['type']=$k;
 $types_group[$v['group']][]=$v; 
 if(isset( $v['show_js_classes'])){
 $types_js[$k]=$v['show_js_classes']; 
 }
}

if(function_exists('wp_enqueue_editor')){
wp_enqueue_editor();
wp_enqueue_media();
}

wp_enqueue_script('jquery-ui-sortable'); 
include_once(cfx_form_plugin_dir.'templates/step1.php');

}else if($tab == "step2"){
 wp_enqueue_media();
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script(cfx_form::$id.'_chroma');
 wp_enqueue_script(cfx_form::$id.'_less');
 wp_enqueue_script(cfx_form::$id.'_google');
 wp_enqueue_style(cfx_form::$id.'_google');
include_once(cfx_form_plugin_dir.'templates/step2.php');
 
}else if($tab == "step3"){
      wp_enqueue_script(cfx_form::$id.'_chroma');
wp_enqueue_style(cfx_form::$id.'_sel2');
   wp_enqueue_script(cfx_form::$id.'_sel2');
include_once(cfx_form_plugin_dir.'templates/step3.php');
}else if($tab == "notify"){
include_once(cfx_form_plugin_dir.'templates/notify.php');
}else if($tab == "change_template"){
include_once(cfx_form_plugin_dir.'templates/new_form.php');
} 

}

else if($tab == "export"){
$forms=cfx_form::get_forms(); 
include_once(cfx_form_plugin_dir.'templates/export.php');

}
else if($tab == 'settings'){
$api=cfx_form::get_meta(); 
include_once(cfx_form_plugin_dir.'templates/settings.php');

}else if($tab == 'new_form'){
include_once(cfx_form_plugin_dir.'templates/new_form.php');
}else if(!empty($tab)){     
do_action('tab_'.cfx_form::$id,$tab);             

}else{
$forms=cfx_form::get_forms(true); 
include_once(cfx_form_plugin_dir.'templates/forms.php');  
}                   
}         
wp_enqueue_script('jquery-ui-datepicker');     
wp_enqueue_script(cfx_form::$id.'_admin');   
 ?>

</div>
</div>
    <!-- /crm-panel-content --> 
    
  </div>
  <!-- /wps_panel --> 
</div>
<!-- /wps_panel -->
<?php
} 
public function add_msg($msg,$level='updated'){
   $option=get_option(cfx_form::$id.'_msgs',array());
   if(!is_array($option)){
   $option=array();    
   }
   $option[]=array('msg'=>$msg,'class'=>$level);
 update_option(cfx_form::$id.'_msgs',$option);  
}

public function show_msgs($msgs=""){ 
 if(empty($msgs)){
   $option=get_option(cfx_form::$id.'_msgs',array());
 }else{
     $option=$msgs;
 }
   if(is_array($option) && count($option)>0){
       $option=array_slice($option,0,1);
   foreach($option as $msg){
     cfx_form::screen_msg($msg['msg'],$msg['class']);  
   }
  if(empty($msgs)){ 
  update_option(cfx_form::$id.'_msgs',array());
  }  
   }  
}
public function op_val($name,$options){
   return !isset($options[$name]) ? "1" : $options[$name];
}    
    /**
     * Get Form Settings
     * @param  string $form_id Form Id
     * @return array Form Settings
     */
public function get_new_form() {
global $wpdb;
$table= cfx_form::table_name('forms');
$row = $wpdb->get_row( 'SELECT * FROM '.$table.' where status=3 limit 1',ARRAY_A );
if(empty($row)){
$wpdb->insert($table,array('name'=>'New Form','status'=>'3'));
$id=$wpdb->insert_id;
$form="New Form $id";
if($id){
 $wpdb->update($table,array('name'=>$form ),array('id'=>$id));   
}
$row=array("id"=>$id,"name"=>$form);
}
return $row;        
}

public function hide_cols($hidden){
//if new form then hide default fields
if($hidden === false){
 $hidden=array(); 
if( is_array(cfx_form::$form_fields) && count(cfx_form::$form_fields)>7){
 $fields_arr=array_slice(cfx_form::$form_fields,5,count(cfx_form::$form_fields)-7); 

 $fields=array(); foreach($fields_arr as $v){ $fields[]=$v['id'];   } 
 $user_id = get_current_user_id();
 $hidden=array_merge($hidden,$fields);   
 update_user_option( $user_id, 'managetoplevel_page_{'.cfx_form::$page.'}columnshidden', $hidden , true ); 
} }
return $hidden;
}
public function screen_cols($cols){
$forms=cfx_form::get_forms();
cfx_form::$form_id=esc_sql(cfx_form::post('f_id')); 


if(empty(cfx_form::$form_id) && !empty($forms)){
cfx_form::$form_id=$forms[0]['id'];   
}   
  
if(!empty(cfx_form::$form_id)){
$form = cfx_form::get_form(cfx_form::$form_id);
$fields_arr=array();
if(!empty($form['fields']) ){
  foreach($form['fields']  as $k=>$v){
    if(!in_array($v['type'],array('html','hr'))){
        if(empty($v['label'])){ $v['label']='# '.$k; }
     $fields_arr[$k]=$v;   
    }  
  }  
}
$fields_arr['browser']=array('id'=>'browser','label'=>'System'); 
$fields_arr['time']=array('id'=>'time','label'=>'Time'); 
cfx_form::$form_fields=$fields_arr;     
 if(!empty($fields_arr) ){ 
 $i=0;
        foreach($fields_arr as $k=>$v){
      if($i > 0){
       $cols[$k]=$v['label'];
      } $i++;     
        }
    }
}
   
    return $cols;
}
public function set_per_page( $save, $option, $value ){
      if ( $option == cfx_form::$id.'_per_page' ) {
            $save = (int) $value; 
        }
   return $save;    
 }
public function screen_options(){
     add_screen_option( 'per_page', array( 'label' =>'Entries', 'default' => 20, 'option' => cfx_form::$id.'_per_page' ) );
       
 }
    /**
     * Common Date and Time search parameters to UNIX timestamp
     * @return array timestamps
     */
public static function common_dates(){
$start_date=0; $end_date=0; 
$time_key=cfx_form::post('time');
  switch($time_key){
  case"today": $start_date=strtotime('today',$time);  break;
  case"this_week": $start_date=strtotime('last sunday',$time);  break;
  case"last_7": $start_date=strtotime('-7 days',$time);  break;
  case"last_30": $start_date=strtotime('-30 days',$time); break;
  case"this_month": $start_date=strtotime('first day of 0 month',$time);  break;
  case"yesterday": 
  $start_date=strtotime('yesterday',$time);
  $end_date=strtotime('today',$time);  

  break;
  case"last_month": 
  $start_date=strtotime('first day of -1 month',$time); 
  $end_date=strtotime('last day of -1 month',$time); 

  break;
  case"custom":
  if(!empty($req['start_date'])){
  $start_date=strtotime($req['start_date'].' 00:00:00');
  }
   if(!empty($req['end_date'])){
  $end_date=strtotime($req['end_date'].' 23:59:59');
   } 
  break;
  }
  return array('start_date'=>$start_date,'end_date'=>$end_date);
}
      /**
     * Get Graphical Stats
     * @return null
     */
private function get_graphical_stats(){
       global $wpdb;
       $arr=array("data"=>'',"browser"=>'',"os"=>'',"country"=>'');
 $pro_table_prefix = $wpdb->prefix;
 $table= cfx_form::table_name('entries');
 $where_f="";
 $where_e=""; $from_r=(int)cfx_form::post('form');
 if(!empty($from_r)){
 $where_f=" and id=$from_r";    
 $where_e=" and form_id=$from_r";    
 }
   $start_date=$end_date='';
   $dates=self::common_dates();
   $start=$dates['start_date'];
   $end=$dates['end_date'];
 ///  echo  "Start=".date('d/m/y H:i:s',$dates['start_date'])." =--- End=".date('d/m/y H:i:s',$dates['end_date']);
  if(!empty($start)){
  $start_date=" and `time` >= '$start'";
  }
  if(!empty($end)){
  $end_date=" and `time` <= '$end'";
  }
 $entries=array(); 
 $sql='SELECT count(id) as entries, count(distinct(vis_id)) as visitors, browser,os ,`time` as `date` FROM '.$table.' where type=0 '.$where_e.$start_date.$end_date.' group by day(time) order by id desc limit 30';
 $results = $wpdb->get_results($sql ,ARRAY_A );
 $max=0; $max_time=0; $entries_t=0; $ab_entries_t=0; $browsers=array(); $os=array(); $countries=array();
 foreach($results as $row){
 $date=date('Y-m-d', strtotime($row['date']) );  
 $entries[$date]=array("entries"=>(int)$row['entries']);
 $entries_t+=(int)$row['entries'];    
 $max=max($max,(int)$row['entries']);
 $max_time=max($max_time,(int)$row['date']);
 /*$row['browser'] = $row['browser'] == "" ? "Unknown" : $row['browser'];
 $row['os'] = $row['os'] == "" ? "Unknown" : $row['os'];
 $row['country'] = trim($row['country']) == "" ? "Unknown" : $row['country'];
 (int)$browsers[$row['browser']]++;
 (int)$os[$row['os']]++;
 (int)$countries[$row['country']]++;*/
 }
// $end_date=" and `time` <= '$max_time'";
 $abo_results = $wpdb->get_results( 'SELECT count(id) as entries , count(distinct(vis_id)) as visitors , browser,os ,`time` as `date` FROM '.$table.' where type=1 '.$where_e.$start_date.$end_date.' group by day(time) order by id desc limit 30',ARRAY_A );
 
 foreach($abo_results as $row){
$date=date('Y-m-d',strtotime($row['date'])); 
  $entries[$date]["ab_entries"]=(int)$row['entries'];   
  $ab_entries_t+=(int)$row['entries'];  
 $max=max($max,(int)$row['entries']);
 }
 $visitors_t=0;
 $sql='SELECT  count(distinct(vis_id)) as visitors , `time` as `date` FROM '.$table.' where id!=""'.$where_e.$start_date.$end_date.' group by day(time) order by id desc limit 30';
  $vis_results = $wpdb->get_results($sql ,ARRAY_A ); 
 foreach($vis_results as $row){
$date=date('Y-m-d',strtotime($row['date'])); 
  $entries[$date]["visitors"]=(int)$row['visitors'];   
  $visitors_t+=(int)$row['visitors'];  
 $max=max($max,(int)$row['visitors']);
 }  
 if($max%4!=0){
 for($i=0;$i<10;$i++){
 $max++;
 if($max%4 == 0)
 break;    
 }    
 }
 $arr['max']=$max;
 $entries_f=array(); $min_time=0; 
 foreach($entries as $date=>$entry){
     $t=strtotime($date);
     if($min_time==0){
         $min_time=$t;
     }
   if($t<$min_time){
    $min_time=$t;   
   } 
$entries_f[$t]=array("date"=>$date,"entries"=>(int)cfx_form::post('entries',$entry),"ab_entries"=>(int)cfx_form::post('ab_entries',$entry),"visitors"=>(int)cfx_form::post('visitors',$entry));    
}


 if(count($entries) == 0){
     $arr['chart']='';
 }else{
      if(count($entries_f)<5){
    //append  more date
    $e=12-count($entries_f);
for($i=$e;$i>0;$i--){
    $date=date('Y-m-d',strtotime("-$i day",$min_time));
$entries_f[]=array("date"=>$date,"entries"=>0,"ab_entries"=>0,"visitors"=>0);  
}    
 }
asort($entries_f);
  $arr['chart']='Line';   
 } 
 $arr['entries']=array_values($entries_f); 
 //count browsers
 $browsers = $wpdb->get_results( 'SELECT count(*) as entries, browser FROM '.$table.' where id!="" '.$where_e.$start_date.$end_date.' group by browser order by id desc limit 30',ARRAY_A );
if(count($browsers)>0){
$arr['browser']=array();
foreach($browsers as $v){
    $v['browser'] = $v['browser'] == "" ? "Unknown" : $v['browser'];
$arr['browser'][]=array("label"=>$v['browser'],"value"=>$v['entries']);    
}
}
 //count OS
$os= $wpdb->get_results( 'SELECT count(*) as entries,os FROM '.$table.' where id!="" '.$where_e.$start_date.$end_date.' group by os order by id desc limit 30',ARRAY_A );
 if(count($os)>0){ $arr['os']=array();
foreach($os as $v){
     $v['os'] = $v['os'] == "" ? "Unknown" : $v['os'];
$arr['os'][]=array("label"=>$v['os'],"value"=>$v['entries']);    
}
}
 //count Country
/*$countries= $wpdb->get_results( 'SELECT count(distinct(vis_id)) as entries,country FROM '.$table.' where id!="" '.$where_e.$start_date.$end_date.' group by country order by id desc limit 30',ARRAY_A );
if(count($countries)>0){ $arr['country']=array();
foreach($countries as $v){
     $v['country'] = trim($v['country']) == "" ? "Unknown" : $v['country'];
$arr['country'][]=array("label"=>$v['country'],"value"=>$v['entries']);    
}
}*/
if($entries_t>0 || $ab_entries_t>0 || $visitors_t>0)  
$arr['data']=array(array("label"=>"Entries","value"=>$entries_t),array("label"=>"Abandoned Entries","value"=>$ab_entries_t),array("label"=>"Visitors","value"=>$visitors_t));

return $arr;
}   
         
    /**
     * Save Form in database
     * @return null
     */
public function save_form() { 

 check_ajax_referer('vx_nonce','vx_nonce');
 if(!current_user_can(cfx_form::$id."_edit_settings")){ 
 echo 'You do not have permission to Save Changes'; 
 die();
 }
$form_id=cfx_form::post('form_id');
global $wpdb; 
 $table= cfx_form::table_name('forms');
 $arr=array();
 if(!empty($_POST['settings'])){ 
 $settings=array();
     foreach($_POST['settings'] as $k=>$v){
     if(in_array($k,array('css'))){
         $settings[$k]=cfx_form::clean_text($v); 
     }else{
         $settings[$k]=cfx_form::clean($v); 
     }
} 
 if(!empty($_POST['vx_html'])){
     foreach($_POST['vx_html'] as $k=>$v){
      $settings[$k]=cfx_form::clean_html($v);   
     }
 }
 $arr['settings']=json_encode($settings);
}
if(!empty($_POST['vx_config'])){
      $config=array();

foreach($_POST['vx_config'] as $k=>$v){
      if(in_array($k,array('code','block','alert_emails'))){
     $config[$k]=cfx_form::clean_text($v);     
      }else{
       $config[$k]=cfx_form::clean($v);    
      }  
}
 if(!empty($_POST['vx_html'])){
     foreach($_POST['vx_html'] as $k=>$v){
      $config[$k]=cfx_form::clean_html($v);   
     }
 }

      if(!empty($config['btn_type']) && !empty($config['screen_icon'])){
  $icon='1';
if(!empty($config['screen_icon'])){
    $icon=$config['screen_icon'];
}
$img_file=cfx_form_plugin_dir.'images/icons/chat'.$icon.'.png'; 
if(file_exists($img_file)){
  $img_data = base64_encode(file_get_contents($img_file));  
//mime_content_type($img_file)
$img= 'data:image/png;base64,'.$img_data; 
$config['btn_src']=$img;
}
}

$arr['form_location']=$config['form_location'];   
$arr['config']=json_encode($config);
}


 if(!empty($_POST['fields'])){
 $arr['name']=cfx_form::post('form_name');
$fields=array();
 foreach($_POST['fields'] as $n=>$field){
  foreach($field as $k=>$v){
      if(in_array($k,array('field_val'))){
     $field[$k]=cfx_form::clean_text($v);     
      }else if(in_array($k,array('html','desc','label'))){
     $field[$k]=cfx_form::clean_html($v);     
      }else{
       $field[$k]=cfx_form::clean($v);    
      }
  }
$fields[$n]=$field;     
 }

 $arr['fields']=json_encode($fields) ;
 }
 
  if(!empty($_POST['vx_notify'])){ 
 $notify=array();
     foreach($_POST['vx_notify'] as $k=>$v){
     if(in_array($k,array('alert_emails'))){
         $notify[$k]=cfx_form::clean_text($v); 
     }else{
         $notify[$k]=cfx_form::clean($v); 
     }
} 
 if(!empty($_POST['vx_html'])){
     foreach($_POST['vx_html'] as $k=>$v){
      $notify[$k]=cfx_form::clean_html($v);   
     }
 }
 $arr['notify']=json_encode($notify);
}
  if(!empty($_POST['vx_extra']) && !empty($_POST['vx_key'])){
      $form=cfx_form::get_form($form_id);
  $extra=!empty($form['extra']) ? $form['extra'] : array(); 
 $extra_e=array();
     foreach($_POST['vx_extra'] as $k=>$v){
     if(strpos($k,'_text') !== false ){
         $extra_e[$k]=cfx_form::clean_text($v); 
     }if(strpos($k,'_html') !== false ){
        $extra_e[$k]=cfx_form::clean_html($v);  
     }else{
         $extra_e[$k]=cfx_form::clean($v); 
     }
}
$ex_key=cfx_form::post('vx_key');
$extra[$ex_key]=$extra_e;
$arr['extra']=json_encode($extra);
} 

if(!empty($arr)){
$results = $wpdb->update( $table,$arr,array("id"=>cfx_form::post('form_id')) );
 echo json_encode(array("status"=>"ok"));
 die();
 }
 }

     /**
     * Save API Settings AJAX method
     * @return null
     */
public function save_api_settings() {
    check_ajax_referer('vx_nonce','vx_nonce');
if(!current_user_can(cfx_form::$id."_edit_settings")){  
   echo 'You do not have permission to Save Changes'; 
 die();  
}
   
if(isset($_POST['cfx_settings'])){
$info_form=cfx_form::post('cfx_settings');
if(!empty($_POST['cfx_settings']['alert_emails'])){
$info_form['alert_emails']=cfx_form::clean_text($_POST['cfx_settings']['alert_emails']);    
}

update_option(cfx_form::$id.'_meta',$info_form);
 }

 echo json_encode(array("status"=>"ok"));
  die();
 
 }
/**
  * Get fields of a form
  * @return null
  */
public function form_fields_export_html_ajax(){
     check_ajax_referer('vx_nonce','vx_nonce');
      if(!current_user_can(cfx_form::$id."_edit_settings")){  
echo cfx_form::screen_msg('You do not have permission to perform this action','error'); 
 die(); 
      }
 $form_id=cfx_form::post('form_id');
 $form=cfx_form::get_form($form_id);
 $fields=!empty($form['fields']) ? $form['fields'] : array();
 $s_fields=array('browser'=>'Browser','os'=>'OS','vis_id'=>'visitor ID','url'=>'Landing Page','time'=>'Time');
 $fields=array_merge($fields,$s_fields);
?>
<div class="crm_checks_w">    
 <div><label><input type="checkbox" name="" class="sel_all_checks">Select All Fields</label></div>   
    <?php 
 foreach($fields as $id=>$field){
     if(is_array($field)){ $id.='_field'; $field=$field['label']; }
?><div class="crm_checkbox_div"><label class="label_normal"><input type="checkbox" class="input_checks" value="<?php echo $id?>" name="fixed[<?php echo $id ?>]"><?php echo $field?></label></div><?php    
 }
 ?></div>
 <?php   
die();   
}
/**
  * Get fields of a form
  * @return null
  */
public function forms_export_html_ajax(){
check_ajax_referer('vx_nonce','vx_nonce');
if(!current_user_can(cfx_form::$id."_edit_settings")){  
    echo cfx_form::screen_msg('You do not have permission to perform this action','error'); 
 die(); 
}
$form=cfx_form::post('form_id');
$forms=array();
if( in_array($form,array('gf_forms','gf_entries'))){
global $wpdb;
$table=$wpdb->prefix.'rg_form';
$sql='select * from '.$table.' where is_trash=0';
$res=$wpdb->get_results($sql,ARRAY_A);
foreach($res as $v){
    $forms[$v['id']]=$v['title'];
}
}else if($form == 'cf7'){
      if( !function_exists('wpcf7_contact_forms') ) {
        $cf_forms = get_posts( array(
            'numberposts' => -1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_type' => 'wpcf7_contact_form' ) );
   foreach($cf_forms as $form){
     if(!empty($form->post_title)){
  $forms[$form->ID]=$form->post_title;       
     }
 }
    }
}
if(!empty($forms)){  
?>
<div class="crm_checks_w">    
 <div><label><input type="checkbox" name="" class="sel_all_checks">Select All Forms</label></div>   
    <?php 
 foreach($forms as $k=>$v){
?><div class="crm_checkbox_div"><label class="label_normal"><input type="checkbox" class="input_checks" value="<?php echo $k?>" name="fixed[<?php echo $k ?>]"><?php echo $v?></label></div><?php    
 }
 ?></div>
 <?php 
}  
die();   
}

public function import_forms_ajax(){
check_ajax_referer('vx_nonce','vx_nonce');
 if(!current_user_can(cfx_form::$id."_edit_settings")){  
     echo cfx_form::screen_msg('You do not have permission','error'); 
 die(); 
 }
include_once(cfx_form_plugin_dir.'includes/import.php');
$import=new cfx_form_import();
$res=$import->gf_import();     
echo json_encode($res);
die();
}

public function form_status_toggle_ajax(){
 check_ajax_referer('vx_nonce','vx_nonce');
 if(!current_user_can(cfx_form::$id."_edit_entries")){  return; }
    $form_id=cfx_form::post('form_id');  
    $status=cfx_form::post('status');
    global $wpdb;
    $wpdb->update(cfx_form::table_name('forms'),array('status'=>$status),array('id'=>$form_id));
    die('done');  
}
   /**
     * Edit entry notes, add or delete
     * @return null
     */
public function edit_entry_note_ajax(){
    check_ajax_referer('vx_nonce','vx_nonce');
     if(!current_user_can(cfx_form::$id."_edit_entries")){  return; }
  $entry_id=cfx_form::post('entry_id');
  $action=cfx_form::post('action2');
  $entry=cfx_form::get_entry($entry_id);
  if(!empty($entry)){
     global $wpdb;
 $table= cfx_form::table_name('notes');
  if($action == "add_note"){
$msg=cfx_form::clean_text($_POST['note']);
//get wp user
$current_user = wp_get_current_user();
$note=array("note"=>$msg,"user_id"=>$current_user->ID,"entry_id"=>$entry_id,'time'=>current_time( 'mysql' ) );
$r=$wpdb->insert($table,$note);
$note['display_name']=$current_user->display_name;
 $note['id'] = $wpdb->insert_id;   
 //$wpdb->show_errors();
//$wpdb->print_error();   
//var_dump($r,$id); die();
vx_form_plugin()->entries->note_temp($note);

do_action('cfx_form_post_note_added',$note['id'],$entry,$msg);
      die();
  }else{
 $id=cfx_form::post('id');
 if($id !=""){
do_action('cfx_form_pre_note_deleted',$id,$entry);      
  $wpdb->query( 'delete FROM '.$table.' where id='.$id.' limit 1');      
}
 } }  
    
}
    /**
     * Toggle star and mark entry read/unread
     * @return null
     */
public function edit_entry_toggle_ajax(){
    check_ajax_referer('vx_nonce','vx_nonce');
     if(!current_user_can(cfx_form::$id."_edit_entries")){  return; }
  $id=cfx_form::post('id');
  $action=cfx_form::post('action2');
  $status=isset($_REQUEST['status']) && $_REQUEST['status'] == 1 ? 1 :0;
  $ids=cfx_form::post('ids');
     global $wpdb;
 $table= cfx_form::table_name('entries');
 $field="is_read";
  if($action == "toggle_star"){
      $field="is_star";
  }
if(is_array($ids) && count($ids)>0){
   $temp=array();
     foreach($ids as $id){
   $temp[]=(int)$id;    
   } 
$ids_str=implode(",",$temp);
 $sql="update $table set $field='$status' where id in($ids_str) limit 50";  
$wpdb->query($sql);       }
}

    /**
     * Get All Forms from database
     * @return array All Forms
     */
public static function get_wp_pages($type) {
    $args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'authors' => '',
    'child_of' => 0,
    'parent' => -1,
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page',
    'post_status' => 'publish'
); 
$pages = get_pages($args); 
return $pages;
/*
global $wpdb;
 $table= $wpdb->posts;
$results = $wpdb->get_results( 'SELECT ID,post_title,guid FROM '.$table.' where post_status="publish" and post_type="'.$type.'" order by ID desc limit 400',ARRAY_A);
return $results;
*/
} 


    /**
     * Handles URL based functions (import, export,get token)
     * @return null
     */
private function handle_form(){ 
$action=cfx_form::post('cfx_form_tab_action'); 
check_admin_referer('vx_nonce','vx_nonce');
$per_msg='You do not have permission to perform this action'; 
 
$msg=''; $msg_type='updated';
     global $wpdb;
     $table= cfx_form::table_name('forms');
  if($action =="export_forms"){
  if(current_user_can(cfx_form::$id."_edit_settings")){    
header('Content-disposition: attachment; filename=forms_export.txt');
header('Content-type: text/plain');
$forms=array();
if(!empty($_REQUEST['forms_exp'])){
$form_ids=implode(',',array_map('intval',cfx_form::post('forms_exp') ));
$sql='SELECT * FROM '.$table.' where id in('.$form_ids.') limit 200';
$results = $wpdb->get_results($sql , ARRAY_A );
foreach($results as $form){
    $arr=array('name'=>$form['name']);
 $arr['settings']=json_decode($form['settings'],true);   
 $arr['config']=json_decode($form['config'],true);   
 $arr['fields']=json_decode($form['fields'],true);   
 $arr['notify']=json_decode($form['notify'],true);   
 $arr['extra']=json_decode($form['extra'],true);   
$forms[]=$arr;
}
if(count($forms)>0){
echo json_encode($forms);  
 die();  
}
} }else{
  $this->add_msg($per_msg,'error');      
    }
}
if($action=="import_forms"){
    if(current_user_can(cfx_form::$id."_edit_settings")){
if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
$uploadedfile = $_FILES['forms_file'];
$upload_overrides = array( 'test_form' => false );
$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
//var_dump($movefile); die();
$done=false;
if ( $movefile['file'] ) {
   $json=file_get_contents($movefile['file']);
  //var_dump($json); die();
    if($json!=""){
        $forms=json_decode($json,true);
        if(is_array($forms) &&count($forms)>0){
            foreach($forms as $form)
            { 
                
                if(isset($form['name'])){
                $arr=array("name"=>$form['name'],"settings"=>json_encode($form['settings']),'config'=>json_encode($form['config']),'fields'=>json_encode($form['fields']),'status'=>'1',"time"=>current_time('timestamp'));
                if(!empty($form['notify'])){
               $arr['notify']=json_encode($form['notify']);     
                }
                if(!empty($form['extra'])){
               $arr['extra']=json_encode($form['extra']);     
                }
            $sql=$wpdb->insert($table,$arr);
     
                }
            } 
            $done=true;      
        }
    }
} 

if($done){
       $msg= 'File Imported Successfully';
}else{  
   $msg = !empty($movefile['error']) ? $movefile['error'] : 'Unable to read file';
   $msg_type='error';    
} 
$this->add_msg($msg,$msg_type); 
    }else{
  $this->add_msg($per_msg,'error');      
    }
$redir=cfx_form::link_to_settings(true,true).'&msg=1';
wp_redirect($redir);
die();       
}
if($action=="copy_form"){
    $redir=cfx_form::link_to_settings().'&msg=1';
    if(current_user_can(cfx_form::$id."_edit_settings")){
       global $wpdb;  
 $table= cfx_form::table_name('forms');
 $id=cfx_form::post('form_id');
 $form=cfx_form::get_form($id);
 $form_arr=array("settings"=>$form['settings_json'],"config"=>$form['config'],"fields"=>json_encode($form['fields']),"time"=>current_time('mysql'),"name"=>$form['name']."(copy)",'status'=>'1'); 
 if(!empty($form['notify'])){
   $form_arr['notify']=json_encode($form['notify']);  
 }
  if(!empty($form['extra'])){
   $form_arr['extra']=json_encode($form['extra']);  
 }

 $wpdb->insert($table,$form_arr); 
$msg="Form Copied Successfully";
$this->add_msg($msg);
}else{
  $this->add_msg($per_msg,'error');      
    }
wp_redirect($redir);
die();    
}
if($action=="new_form"){
    $redir=cfx_form::link_to_settings();  
    if(current_user_can(cfx_form::$id."_edit_settings")){
       global $wpdb;  
 $table= cfx_form::table_name('forms');
 $id=cfx_form::post('id');
 include_once(cfx_form_plugin_dir.'includes/form-templates.php');
 $form_arr=array('name'=>$temps[$id]['label'],'status'=>'1');
 $field_name=isset($temps[$id]['fields']) ? $temps[$id]['fields'] : '';
 
 $form_arr['config']= isset($config[$id]) ? $config[$id] : $config['0'];
 $form_arr['fields']= isset($fields[$field_name]) ? $fields[$field_name] : $fields['all'];
 $form_arr['settings']= isset($settings[$id]) ? $settings[$id] : $settings['0'];
 $form_arr['notify']= isset($notify[$id]) ? $notify[$id] : $notify['0'];
  $form_arr['settings']=str_replace(array('cfx_form_plugin_url'),array(cfx_form_plugin_url), $form_arr['settings']);
 $form_arr['fields']=str_replace(array('cfx_form_plugin_url'),array(cfx_form_plugin_url), $form_arr['fields']);
 $form_arr['time']= current_time('mysql');
 
 $wpdb->insert($table,$form_arr);
 $form_id=$wpdb->insert_id; 
 if(!empty($form_id)){
  $wpdb->update($table,array('name'=>$temps[$id]['label'].' (ID #'.$form_id.')'),array('id'=>$form_id));   
$redir=cfx_form::link_to_settings().'&form_id='.$form_id;
$msg="New Form Created Successfully";
 }else{  
 $msg="Error while Creating Form";    
 }
$this->add_msg($msg);
    }else{
  $this->add_msg($per_msg,'error');      
    }
wp_redirect($redir);
die();
}
if($action== 'change_template'){
    $redir=cfx_form::link_to_settings();  
    if(current_user_can(cfx_form::$id."_edit_settings")){
       global $wpdb;  
 $table= cfx_form::table_name('forms');
 $id=(int)cfx_form::post('id');
 $form_id=(int)cfx_form::post('form_id');
 include_once(cfx_form_plugin_dir.'includes/form-templates.php');
$form_arr=array();
$form_arr['settings']= isset($settings[$id]) ? $settings[$id] : $settings['0'];

 $u=$wpdb->update($table,$form_arr,array('id'=>$form_id));
 if(!empty($form_id)){  
$redir=cfx_form::link_to_settings().'&tab=step2&form_id='.$form_id;
$msg='Form Design Template Changed';
 }else{  
 $msg='Error while updating changing form design template';    
 }
$this->add_msg($msg);
    }else{
  $this->add_msg($per_msg,'error');      
    }
wp_redirect($redir);
die();
}
if($action=="del_form"){
    if(current_user_can(cfx_form::$id."_edit_settings")){
global $wpdb;
 $table= cfx_form::table_name('forms');
 $id=(int)cfx_form::post('id');
 $wpdb->query("delete from $table where id='$id'");  
$msg="Form Deleted Successfully";
$this->add_msg($msg);
    }else{
  $this->add_msg($per_msg,'error');      
    }
$redir=cfx_form::link_to_settings().'&msg=1';
wp_redirect($redir); 
die();
}


 }
 
}
new cfx_form_admin_pages();
}

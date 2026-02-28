<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'vx_crmperks_notice_cfx' )):
class vx_crmperks_notice_cfx{
public $plugin_url="https://www.crmperks.com";
public $review_link='https://wordpress.org/support/plugin/crm-perks-forms/reviews/?filter=5#new-post';
public $option='cfx_form';

public function __construct(){
add_action( 'side_menu_form_'.cfx_form::$id, array($this,'plugin_pro_link'),40);
add_action( 'side_menu_'.cfx_form::$id, array($this,'plugin_pro_link'),10);
add_action( 'add_page_html_'.cfx_form::$id, array($this,'tab'),10);
add_filter( 'plugin_row_meta', array( $this , 'pro_link' ), 30, 2 );
//install entries
add_action( 'admin_notices', array( $this , 'install_entries_notice' ) );
add_filter( 'plugins_api', array( $this, 'entries_info' ), 11, 3 );

add_action( 'after_plugin_row_'.cfx_form::get_slug(), array( $this, 'plugin_msgs' ),99 );
add_action( 'wp_ajax_cfx_form_review_dismiss', array( $this, 'review_dismiss' ) );

add_action('add_section_cfx_form', array($this, 'free_plugins_notice'),99);

if(isset($_GET['page']) && $_GET['page'] == cfx_form::$page ){
add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
add_action( 'admin_notices', array( $this , 'review_notice' ) );
}
}
public function add_section_cf($tabs){
$tabs["vxc_notice"]=array('label'=>__('Go Premium','contact-form-entries'),'function'=>array($this, 'notice'));
return $tabs;
}
public function plugin_pro_link($tabs){
$tabs['go_pro']=array('label'=>__('Go Premium','contact-form-entries'),'icon'=>'check-square-o');
return $tabs;
}
public function tab($added){
    $tab=cfx_form::post('tab');
if(in_array($tab,array("go_pro") )){
$this->notice();  
$added=true;  
}
return $added;
}
public function notice(){
 //      vx_addons::premium_page();   
$plugin_url=$this->plugin_url();
?>
<div style="border: 2px solid  #1192C1; border-left-width: 6px; padding: 2px 12px; margin: 30px 20px 30px 0px">
<h3>Premium Features</h3>
<p><i class="fa fa-check cfx_feature"></i> All features of floating buttons</p>
<p><i class="fa fa-check cfx_feature"></i> All features of popups</p>
<p><i class="fa fa-check cfx_feature"></i> Push form events to Google Analytics</p>
<p><i class="fa fa-check cfx_feature"></i> Multi-Page forms</p>
<p><i class="fa fa-check cfx_feature"></i> Conditional Logic</p>
<p><i class="fa fa-check cfx_feature"></i> Payment fields</p>
<p><i class="fa fa-check cfx_feature"></i> Stripe Add-on</p>
<p><i class="fa fa-check cfx_feature"></i> Paypal Add-on</p>
<p><i class="fa fa-check cfx_feature"></i> 20+ Pro Add-on</p>
<p><i class="fa fa-check cfx_feature"></i> Premium Version of <a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">Contact Form Entries</a> Plugin</p>
<p>By purchasing the premium version of the plugin you will get access to advanced marketing features and you will get one year of free updates & support</p>
<p>
<a href="<?php echo $plugin_url ?>" target="_blank" class="button-primary button">Go Premium</a>
</p>
</div>
<style type="text/css">
.cfx_feature{
  color: #727f30; font-size: 20px;  
}
</style>
<?php
}

public function install_entries_notice(){

if(!empty($_GET['page']) && $_GET['page'] == cfx_form::$page){
 if(!empty($_GET['cfx_form_dissmiss_notice'])){
        check_admin_referer('vx_nonce');
   update_option('cfx_form_install_entries_notice','true',false);
    }
    
$show=get_option('cfx_form_install_entries_notice');
if(empty($show)){        
//var_dump($link);
if(!class_exists('vxcf_form')) {
    $plugin_file='contact-form-entries/contact-form-entries.php';
$plugin_msg='';
$link=wp_nonce_url(cfx_form::link_to_settings().'&cfx_form_dissmiss_notice=true','vx_nonce');

  if(file_exists(WP_PLUGIN_DIR.'/'.$plugin_file)) {
$url=admin_url("plugins.php?action=activate&plugin=$plugin_file");      
$url=wp_nonce_url( $url , "activate-plugin_{$plugin_file}"); 
$plugin_msg=__('Activate Plugin','crm-perks-forms');
}else{
$url=admin_url("update.php?action=install-plugin&plugin=$plugin_file");
$url=wp_nonce_url( $url, "install-plugin_$plugin_file");  
$plugin_msg=__('Install Plugin','crm-perks-forms');  
} 
$msg =sprintf(__('Want to save conatct form submissions? Manage contact form entries with free %sConatct Form Entries Plugin%s','crm-perks-forms'),'<a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">','</a>');
?>
<div class="notice-warning settings-error notice is-dismissible below-h2" style="font-weight: bold">
<p><?php echo $msg; ?></p>
<p><a href="<?php echo $url ?>"><?php echo $plugin_msg; ?></a> | <a href="<?php echo $link; ?>"><?php _e('Dismiss this notice','crm-perks-forms'); ?></a></p>
</div>
<?php
}
  } }
}

public function entries_info( $data, $action = '', $args = null ) {

$slug = isset( $args->slug ) ? $args->slug : cfx_form::post( 'plugin' );   
if($slug == 'contact-form-entries/contact-form-entries.php'){
   $arr=new stdClass();
   $arr->download_link='https://downloads.wordpress.org/plugin/contact-form-entries.zip';  
return $arr;
} 
return $data;
}
  /**
  * display plgin messages
  * 
  * @param mixed $type
  */
public function plugin_msgs($type=""){
    
    
    $plugin_url=$this->plugin_url();
    $message=__('This plugin has Premium add-ons and many powerful features.','crm-perks-forms');
    $message.=' <a href="'.$plugin_url.'" target="_blank" style="font-color: #fff; font-weight: bold;">'.__('Go Premium','crm-perks-forms').'</a>';
?>
  <tr class="plugin-update-tr"><td colspan="5" class="plugin-update">
  <style type="text/css"> .vx_msg a{color: #fff; text-decoration: underline;} .vx_msg a:hover{color: #eee} </style>
  <div style="background-color: rgba(224, 224, 224, 0.5);  padding: 5px; margin: 0px 10px 10px 28px "><div style="background-color: #d54d21; padding: 5px 10px; color: #fff" class="vx_msg"> <span class="dashicons dashicons-info"></span> <?php echo wp_kses_post($message) ?>
</div></div></td></tr>
<?php 
  }
public function pro_link($links,$file){
    $slug=cfx_form::get_slug();
    if($file == $slug){
    $url=$this->plugin_url();
        $links[]='<a href="'.$url.'"><b>Go Premium</b></a>';
    }
   return $links; 
}
public function plugin_url() {
  return  $this->plugin_url.='?vx_product=cfx-form&wpth='.$this->wp_id();
} 
public function wp_id() { 
$id='';
if(function_exists('wp_get_theme')){  
$theme=wp_get_theme(); 
if(property_exists($theme,'stylesheet')){
$id=md5($theme->stylesheet);}
}
return $id;
}
public function review_dismiss() {
    $install_time=get_option($this->option."_install_data");
    if(!is_array($install_time)){ $install_time =array(); }
$install_time['review_closed']='true';
update_option($this->option."_install_data",$install_time,false);
die();
}
public function admin_footer($text) {
    if(isset($_GET['page']) && $_GET['page'] == cfx_form::$page ){
$text=sprintf(__( 'if you enjoy using %sCRM Perks Forms%s, please %s leave us a %s rating%s. A %shuge%s thank you in advance.','crm-perks-forms'),'<b>','</b>','<a href="'.$this->review_link.'" target="_blank" rel="noopener noreferrer">','&#9733;&#9733;&#9733;&#9733;&#9733;','</a>','<b>','</b>');
} return $text;
}
public function review_notice() { 
 $install_time=get_option($this->option."_install_data");
   if(!is_array($install_time)){ $install_time =array(); }
   if(empty($install_time['time'])){
       $install_time['time']=current_time( 'timestamp' , 1 );
      update_option($this->option."_install_data",$install_time,false); 
   }
    $time=current_time( 'timestamp' , 1 )-(DAY_IN_SECONDS*3);
//$install_time['review_closed']='';
 if(!empty($install_time) && is_array($install_time) && !empty($install_time['time']) && empty($install_time['review_closed'])){
   $time_i=(int)$install_time['time'];
    if($time > $time_i){ 
        ?>
        <div class="notice notice-info is-dismissible vxcf-review-notice" style="margin: 14px 0 -4px 0">
  <p><?php echo sprintf(__( 'You\'ve been using CRM Perks Forms for some time now; we hope you love it!.%s If you do, please %s leave us a %s rating on WordPress.org%s to help us spread the word and boost our motivation.','contact-form-entries'),'<br/>','<a href="'.$this->review_link.'" target="_blank" rel="noopener noreferrer">','&#9733;&#9733;&#9733;&#9733;&#9733;','</a>'); ?></p>
    <p><a href="<?php echo $this->review_link ?>"  target="_blank" rel="noopener noreferrer" class="vxcf_close_notice_a"><?php _e('Yes, you deserve it','crm-perks-forms') ?></a> | <a href="#" class="vxcf_close_notice_a"><?php _e('Dismiss this notice','crm-perks-forms'); ?></a></p>
        </div>
        <script type="text/javascript">
            jQuery( document ).ready( function ( $ ) {
           $( document ).on( 'click', '.vxcf-review-notice .vxcf_close_notice_a', function ( e ) {
                      // e.preventDefault(); 
                       $('.vxcf-review-notice .notice-dismiss').click();
 //$.ajax({ type: "POST", url: ajaxurl, async : false, data: {action:"vxcf_form_review_dismiss"} });          
        $.post( ajaxurl, { action: 'cfx_form_review_dismiss' } );
                });
            });
        </script>
        <?php
    } }
}
public function free_plugins_notice(){
?>
<div class="updated below-h2" style="border: 1px solid  #1192C1; border-left-width: 6px; padding: 5px 12px;">
<h3>Our Other Free Plugins</h3>
<p><b><a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">Contact Form Entries</a></b> saves contact form submissions from all popular contact forms(contact form 7 , crmperks forms, ninja forms, Gravity forms etc) into database.</p>
<p><b><a href="https://wordpress.org/plugins/support-x/" target="_blank">Support X - Wordpress Helpdesk</a></b> Shows user tickets from HelpScout, ZenDesk, FreshDesk, Desk.com and Teamwork in wordpress. Users can create new tickets and reply to old tickets from wordpress.</p>
<p><b><a href="https://wordpress.org/plugins/woo-salesforce-plugin-crm-perks/" target="_blank">WooCommerce Salesforce Plugin</a></b> allows you to quickly integrate WooCommerce Orders with Salesforce CRM.</p>
<p><b><a href="https://wordpress.org/plugins/gf-freshdesk/" target="_blank">Gravity Forms FreshDesk Plugin</a></b> allows you to quickly integrate Gravity Forms with FreshDesk CRM.</p>
</div>
<?php    
} 

}
new vx_crmperks_notice_cfx();
endif;

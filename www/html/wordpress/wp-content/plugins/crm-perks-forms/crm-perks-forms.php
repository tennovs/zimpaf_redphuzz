<?php
/**
* Plugin Name: CRM Perks Forms
* Description: Create beautiful contact forms , popups with floating buttons.
* Version: 1.0.7
* Requires at least: 3.8
* Tested up to: 5.4
* Author URI: https://www.crmperks.com
* Plugin URI: https://www.crmperks.com/plugins/contact-form-plugins/crm-perks-forms/
* Author: CRM Perks
*/

class cfx_form {
    public static $version='1.0.7';
    public static $page='cfx-form';
    public static $id='cfx_form';
    public static $upload_folder = 'crm_perks_uploads';
    public $form;
    public $entries;
    public static $instance;
  public static $plugin;  
    public static $forms;
    public static $settings;
    public static $form_fields=array();
    public static $form_id=0;
    public static $is_pr=false;
	 
public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
return self::$instance;
}
 
public function __construct(){ 
$this->define_constants();    
register_activation_hook( __FILE__, array( $this, 'activate' ) );
register_deactivation_hook(__FILE__,array($this,'deactivate'));
 
add_action( 'plugins_loaded', array( $this, 'start_plugin'));  
   
}
   
public function start_plugin() {
if( is_admin() ){

add_action( 'init', array( $this, 'setup_plugin'));    

include_once( cfx_form_plugin_dir. 'includes/admin-pages.php' );
include_once( cfx_form_plugin_dir. 'includes/editor-btn.php' );

}

$file=cfx_form_plugin_dir. 'pro/common.php';
if(file_exists($file)){ include_once( $file ); }
 $file=cfx_form_plugin_dir.'pro/pro.php';
 if(file_exists($file)){ self::$is_pr=true; include_once($file);  }
    
include_once( cfx_form_plugin_dir. 'includes/front-form.php' ); 
$this->form=new cfx_form_front(); 
}  

private function define_constants() {
        $this->define( 'cfx_form_plugin_dir', plugin_dir_path(__FILE__) );
        $this->define( 'cfx_form_plugin_url', plugin_dir_url(__FILE__) );
}
private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
}

/**
* Fired when the plugin is activated or version changes.
*/ 
public function setup_plugin(){
$file=cfx_form_plugin_dir. 'wp/crmperks-notices.php';
if(file_exists($file)){ include_once( $file ); }

$file=cfx_form_plugin_dir. 'pro/add-ons.php';
if(file_exists($file)){ include_once( $file ); }
$this->plugin_api(true);

if(current_user_can( 'manage_options' ) && get_option(self::$id.'_plugin_version','') != self::$version){
include_once(cfx_form_plugin_dir . "includes/install.php"); 
$install=new cfx_form_install();
$install->create_roles();   
$install->create_tables();   
update_option(self::$id.'_plugin_version', self::$version);
$install->create_upload_dir(); 
}
}

  /**
  * activate plugin
  * 
  */
public function activate(){
 $this->setup_plugin();  
$this->plugin_api(true);

do_action('plugin_status_'.cfx_form::$id,'activate');  
}

public  function plugin_api($start_instance=false){
$file=cfx_form_plugin_dir . "pro/plugin-api.php";
if(file_exists($file)){   
if(!class_exists('vxcf_plugin_api')){    include_once($file); }

if(class_exists('vxcf_plugin_api')){
 $update_id = "400020";
 $title='CRM Perks Forms';
 $slug=self::get_slug();
 $settings_link=self::link_to_settings();
 $is_plugin_page=self::is_crm_page(); 
self::$plugin=new vxcf_plugin_api(self::$id,self::$version,self::$id,self::$page,$update_id,$title,$slug,cfx_form_plugin_dir,$settings_link,$is_plugin_page);
if($start_instance){
self::$plugin->instance();
} }

}


} 

public static function get_field_types(){
    $types=array("text"=>"Single Line Text","textarea"=>"Textarea",
'email'=>'Email','tel'=>'Phone Number',
'url'=>'URL','number'=>'Number',
"select"=>"Selectbox","checkbox"=>"Checkbox","radio"=>"Radio Buttons","state"=>"Select State","country"=>"Select Country","date"=>"Select Date",'file'=>'File Upload','password'=>'Password','html'=>'HTML Block','hr'=>'Line Break','hidden'=>'Hidden Field','star'=>'Star Rating','range'=>'HTML5 Range Slider','search'=>'HTML5 Search','submit'=>'Submit Button','captcha'=>'Captcha');

$types_classes=array(
'input'=>array('text','email','textarea','tel','url','number','date','search','password'),
'html'=>array('html'),
'select'=>array("select","radio","checkbox"),
'desc'=>array("select","state","country","radio","checkbox",'file','range'), //+input
'input_align'=>array("radio","checkbox"),
'star'=>array('star'),
'row_fields'=>array('submit','html',"select","state","country","radio","checkbox",'file','range','star','hr','captcha'), //+input
'file_exts'=>array('file'),
'text_height'=>array('textarea'),
'min_max'=>array('number','range'),
'default_value'=>array("select","state","country","radio","checkbox",'star','hidden'), //+input
'read_request'=>array("select","state","country","radio","checkbox","hidden"), //+input
'valid_msg'=>array("select","state","country","radio","checkbox",'star','file'), //+input
'input_class'=>array("select","state","country","radio","checkbox",'range','file','submit','hidden'), //+input
'con_class'=>array("select","state","country","radio","checkbox",'range','star','file','submit','hr','html','captcha'), //+input
'hide_label'=>array("select","state","country","radio","checkbox",'range','file','hr'), //+input
'req'=>array("select","state","country","radio","checkbox",'file'), //+input
'max_length'=>array('text','email','textarea','tel','url','date','search','password'),
'hint'=>array('state','country','select'),
'captcha_type'=>array('captcha'),
'field_id'=>array("select","state","country","radio","checkbox",'range','star','file','submit','hr','html','captcha','hidden'), //+input
'date_format'=>array("date")
);
$advance=array('html','hr','hidden','star','range','search','submit','captcha');
$layout_fields=array('hr','html','submit');

$field_types=array();
foreach($types as $k=>$v){
   $v=array('label'=>$v,'field_type'=>$k);
   if(in_array($k,$layout_fields)){
       $v['is_layout']='true';
   }
   $group_name='basic';
     if(in_array($k,$advance)){
 $group_name='advanced';
 }  
 $v['group']=$group_name;    
    $fields_temp=array();
    foreach($types_classes as $kk=>$vv){
       if(in_array($k,$vv)){
        $fields_temp[]=$kk;
       }
    }
 $v['show_js_classes']=$fields_temp;   
 $field_types[$k]=$v;
}
$field_types=apply_filters('crmperks_forms_field_types',$field_types);

return $field_types;
}
    /**
     * Get Form settings
     * dcoded JSON , Decrypted Values
     * @param  string $form_id  Form Id
     * @return array Form Settings
     */
public static function get_form($form_id,$valid_fields=false){
   global $wpdb;
 $table= cfx_form::table_name("forms");
 $sql=$wpdb->prepare('SELECT * FROM '.$table.' where id=%d limit 1',array($form_id));
 $arr = $wpdb->get_row($sql ,ARRAY_A );
 
 if(empty($arr)){
 return array();
 }


 $temp=json_decode($arr['settings'],true);
 $config=json_decode($arr['config'],true);
 $settings= is_array($temp) ? $temp: array();
 $config= is_array($config) ? $config: array();
 $arr['settings_json']=$arr['settings'];
 $arr['settings']=array_merge($settings,$config);
 $arr['notify']=json_decode($arr['notify'],true);
 if(!empty($arr['extra'])){
 $arr['extra']=json_decode($arr['extra'],true);    
 }
 $temp=json_decode($arr['fields'],true);
 $fields=array();
 $types=self::get_field_types(); 
if(!empty($temp)){
    $n=0;
    foreach($temp as $k=>$v){ 
if(isset($types[$v['type']])){ 
    $type=$types[$v['type']];
if($valid_fields && isset($type['is_layout']) ){ continue; }
$v['id']=$k;
$v['field_type']=$type['field_type'];
$v['order']=$n++;
$fields[$k]=$v; 

} }
$fields=cfx_form::update_field_options($fields);
} 
if(empty($arr['settings']['process_text'])){
    $arr['settings']['process_text']='sending ...';
}
 $arr['fields']=$fields;
 return $arr;
}
    /**
     * Get Form entry
     * @param  string $entry_id Entry Id
     * @return array Entry
     */
public static function get_entry($entry_id) {
global $wpdb;
 $table= cfx_form::table_name('entries');
 $sql='SELECT * FROM '.$table.' where id='.(int)$entry_id.' limit 1';
$entry= $wpdb->get_row( 'SELECT * FROM '.$table.' where id='.(int)$entry_id.' limit 1',ARRAY_A );
return $entry;
} 

public static function update_field_options($fields_arr){
  $fields=array();
   if(!empty($fields_arr)){
 foreach($fields_arr as $k=>$v){
  if(!empty($v['field_val']) && in_array($v['field_type'],array('radio','checkbox','select'))){
      
  $field_val=array_filter(array_map('trim',explode("\n",$v['field_val'])));
 $options=array();
  foreach($field_val as $option_string){
   $op=array_map('trim',explode("=",$option_string) ) ; 
  if(!empty($op)){ 

   $label=isset($op[1]) ? $op[1] : $op[0];
  $options[$op[0]]=array('label'=>$label,'value'=>$op[0]);
  }
  }
$v['options']=$options;  
  }
$fields[$k]=$v;     
 }     
   } 
return $fields;
}
/**
  * admin_screen_message function.
  * 
  * @param mixed $message
  * @param mixed $level
  */
public static function screen_msg( $message, $level = 'updated') {
  echo '<div class="'. esc_attr( $level ) .' fade below-h2 notice is-dismissible vx_alert"><p>';
  echo $message ;
  echo '</p></div>';
  } 
  /**
  * settings link
  * 
  * @param mixed $escaped
  */
public static function link_to_settings( $tab=false , $form=false ) {
  $q=array('page'=>cfx_form::$page);
  if($tab){
   $q['tab']=cfx_form::post('tab');   
  }  if($form){
   $q['f_id']=cfx_form::post('f_id');   
  }
  $url = admin_url('admin.php?'.http_build_query($q));
  return  $url;
  }
    /**
  * Returns true if the current page is an Feed pages. Returns false if not
  * 
  * @param mixed $page
  */
public static function is_crm_page($page=""){
  if(empty($page)) {
  $page = cfx_form::post("page");
  }
  return $page == self::$page;
}

    /**
     * Get All Forms from database
     * @return array All Forms
     */
public static function get_forms($refresh=false) {    
global $wpdb;
if($refresh || empty(cfx_form::$forms)){
 $table=cfx_form::table_name('forms');
 $sql='SELECT * FROM '.$table.' where status!=3 limit 200';
cfx_form::$forms = $wpdb->get_results( $sql , ARRAY_A );
}
return cfx_form::$forms;
}
public static function field_str($v,$form){
    $form_id=$form['id']; 
    $str=''; $name='true'; 
$auto_complete= !empty($v['mask']) ? ' autocomplete="off"' : '';

     if(in_array($v['type'],array('html','hr'))){ 
        $v['label']='';
    }
    if(!isset($v['req'])){ $v['req']=''; }
    if(!isset($v['input_align'])){ $v['input_align']='1'; }
    if(!isset($v['field_name'])){ $v['field_name']='fixed['.$v['id'].']'; }
    $field_id='crm_field_'.$form_id.'_'.$v['id'];
    if(!empty($v['field_id'])){
     $field_id='crm_field_'.preg_replace("/[^a-zA-Z0-9_]+/", "", $v['field_id']);   
    }
    //disable html5 validation if mask present
$err_msg="";
if(!empty($v['mask'])){
    $v['mask']=$v['mask'] == "custom" ? $v['custom_mask'] : $v['mask'];
$err_msg.=' data-mask="'.$v['mask'].'"';
$v['req']="";
}
if( !empty($v['required']) && !empty($v['err_msg'])){
$err_msg.='data-msg="'.esc_attr($v['err_msg']).'"';
}
$data_class="";
if($err_msg!=""){ //if custom validation msg 
$data_class=" cfx_valid_msg";
}
if( !in_array($v['type'],array('hidden','star') ) && !empty($v['required'])){
$data_class.=" crm_required";
}
if(!empty($v['field_class'] ) && !in_array($v['field_class'],array("radio","checkbox"))){
$data_class.=" ".$v['field_class'];    
}
 if(empty($values) && !empty($v['param_name']) && isset($_REQUEST[$v['param_name']])){
     $v['default']=cfx_form::post($v['param_name']);
 }  
 switch($v['type']){
     case"textarea": 
     if(empty($v['text_height']) ){
      $v['text_height']="60px";   
     }else{
     $v['text_height']=trim($v['text_height']);
     if(strpos($v['text_height'],"px") ===false && strpos($v['text_height'],"%") ===false)
     $v['text_height'].="px";
     }
     if(!isset($v['hint'])){ 
          $v['hint']='';
     }
 $str.='<textarea  id="'.$field_id.'" '.$err_msg.' data-name="'.$v['id'].'" placeholder="'.$v['hint'].'" class="cfx_input '.$data_class.'"';
 if(!empty($v['max'])){
 $str.=' maxlength="'.$v['max'].'"';
 }
 if($name != ""){
 $str.=' name="'.$v['field_name'].'"';
 }

 $str.=$auto_complete;   

 $str.='  style="width: 100%; height:'.$v['text_height'].';"  '.$v['req'].'>'.$v['default'].'</textarea>';
 
 break;
 case"date": 
 $str.='<input type="text" '.$err_msg.'  id="'.$field_id.'" autocomplete="off"  data-name="'.$v['id'].'" placeholder="'.$v['hint'].'" class="cfx_input cfx_date_picker  '.$data_class.'" value="'.$v['default'].'"';
if(isset($v['max'])){
 $str.=' maxlength="'.$v['max'].'"';
}
if(isset($v['date_format'])){
 $str.=' date-format="'.$v['date_format'].'"';
}
 if($name != "")
 $str.=' name="'.$v['field_name'].'"';
 $str.=' '.$v['req'].'>';
 break;
 
 case"select": 
  $str.='<select id="'.$field_id.'" '.$err_msg.' data-name="'.$v['id'].'"  class="cfx_input '.$data_class.'" ';
if($name != "")
  $str.=' name="'.$v['field_name'].'"';
  $str.=$auto_complete.' '.$v['req'].'>';
  if(!empty($v['hint'])){
      $str.="<option disabled selected value=''>".$v['hint']."</option>"; 
  }
if(!empty($v['options'])){
  foreach($v['options'] as $option){
   $sel="";
   if($v['default'] == $option['value'])
   $sel='selected="selected"';
   $str.="<option value='".$option['value']."' $sel>".$option['label']."</option>";   
  }
}
  $str.='</select>';
 break;
  
  case"radio": 

if(!empty($v['options'])){
$ii=0; 
  foreach($v['options'] as $k=>$option){
      $ii++;
      $option_id=$field_id.'_'.$ii;
 $str.="<div class='crm_radio_div crm_radio_label_".$v['input_align']."'>";

   $sel="";
   if($v['default'] == $option['value'])
   $sel='checked="checked"';
   
 $str.='<input type="radio" value="'.$option['value'].'" id="'.$option_id.'" ';
   if($name != ""){
  $str.=' name="'.$v['field_name'].'"'; }
    $str.=$sel.'  class="cfx_radio '.$v['field_class'].'"  data-name="'.$v['id'].'"> <label class="crm_radio_label" for="'.$option_id.'" >'.$option['label']."</label>";   
  
  $str.="</div>";
  } }
 break; 
 
  case"checkbox": 
  
  if(!empty($v['options'])){
$ii=0; 
  foreach($v['options'] as $k=>$option){
         $ii++;
      $option_id=$field_id.'_'.$ii;
      $str.="<div class='crm_radio_div crm_radio_label_".$v['input_align']."'>"; 
   $sel="";
   if(!empty($v['default'])){
     $d=$v['default'];
     if(!is_array($d)){
     $d=array($d);    
     }
  if(in_array($option['value'],$d)){
  $sel='checked="checked"';     
  }     
   }

   $str.='<input type="checkbox" value="'.$option['value'].'"  id="'.$option_id.'" ';
   if($name != "")
  $str.=' name="'.$v['field_name'].'[]"';
    $str.=$sel.' class="cfx_radio '.$v['field_class'].'"  data-name="'.$v['id'].'"> <label class="crm_radio_label" for="'.$option_id.'">'.$option['label']."</label>";  
    $str.="</div>";
  }
   
    }
 break; 
  
 case"file":
 
   $str.="<div><input  id='".$field_id."' data-name='".$v['id']."' type='file' ".$v['req'];
   if($name != "")
  $str.=' name="'.$v['field_name'].'"';
$str.=$auto_complete; 
    $str.=" ></div>";   

 break;
 
  case"filea":

   $str.="<div><label class='crm_file_label' for='".$v['id']."' data-name='".$v['id']."'><input class='crm_file_field' id='".$v['field']."' type='file' ".$v['req'];
   if($name != "")
  $str.=' name="'.$v['field_name'].'"';
    $str.="  onchange=\"document.getElementById('text_".$v['field']."').value = this.value;\"> <input id='text_".$v['field']."' type='text' readonly='readonly' class='cfx_input'><span class='crm_btn crm_file_btn'>Choose File</span></label></div>";   
 break;
 
  case"state": 
 $json=self::get_country_states();
$states_json=$json['state'];
  $str.='<select class="cfx_input '.$data_class.'" '.$err_msg.'  data-name="'.$v['id'].'"  id="'.$field_id.'"';
 if($name != "")
  $str.=' name="'.$v['field_name'].'"';
  $str.=$auto_complete; 
  $str.='  '.$v['req'].'>';
    $str.="<option disabled selected value=''>".$v['hint']."</option>";  
  $states=json_decode($states_json,true);
  foreach($states as $state_k=>$state){
   $sel="";
   if(strtolower($v['default']) == strtolower($state_k))
   $sel='selected="selected"';
   $str.="<option value='".$state_k."' $sel>".$state."</option>";   
  }
  $str.='</select>';
 break;
 
   case"country": 
 $json=self::get_country_states();
$countries_json=$json['country'];
  $str.='<select class="cfx_input '.$data_class.'" '.$err_msg.' data-name="'.$v['id'].'" id="'.$field_id.'"';
  if($name != "")
  $str.=' name="'.$v['field_name'].'"';
  $str.=$auto_complete; 
  $str.='  '.$v['req'].'>';
     $str.="<option disabled selected value=''>".$v['hint']."</option>"; 
  $countries=json_decode($countries_json,true);
  foreach($countries as $country_k=>$country){
   $sel="";
   if(strtolower($v['default']) == strtolower($country))
   $sel='selected="selected"';
   $str.="<option value='".$country."' $sel>".$country."</option>";   
  }
  $str.='</select>';
  
 break;
 
 case'hidden1':
 $hidden_type= $no_hidden_fields === true ? 'text' : 'hidden';
 $str.='<input type="'.$hidden_type.'" id="crm_field_'.$form_id.'_'.$v['id'].'"  value="'.$v['default'].'"';
 if($name != "")
 $str.=' name="'.$v['field_name'].'"';
 $str.='>';
 break;
  case'submit':
 $str.=' <button class="crm_btn cfx_submit '.$data_class.'" autocomplete="off" type="submit" id="'.$field_id.'" data-alt="'.$form['settings']['process_text'].'">'.$v['label'].'</button>'; 
 break;

 case 'html':
 $str.=$v['html'];
 break;
  case 'hr':
 $str.='<hr class="cfx_hr">';
 break;
default:     
      $data_type=$v['type'];
if(in_array($data_type,array('text','email','tel','url','date','password','hidden','star','range','search','number'))){
   if($v['type'] == 'star'){
 $str.='<div class="cfx_rating_div"><div class="cfx_rating">';
 if(empty($v['stars'])){ $v['stars']=5; }
$n=$stars=intval($v['stars']);
 for($i=0; $i<$stars; $i++){
     $star_class= $n == $v['default'] ? 'class="cfx_sel_star"' : '';
$str.='<span data-val="'.$n.'" '.$star_class.'>&#9734;</span>'; $n-=1;
 }
 $data_type='hidden';
$str.='</div>';
   }
   $input_class='cfx_input';
    if($v['type'] == 'range'){ 
       $input_class='cfx_range_input'; 
       if(empty($v['default'])){ $v['default']='0';  } 
   }
   
   if($data_type != 'hidden'){
       $data_class=$input_class.$data_class;
   }
  
 $str.='<input type="'.$data_type.'" id="'.$field_id.'" '.$err_msg.' data-name="'.$v['id'].'"';
 $str.=' class="'.$data_class.'" value="'.$v['default'].'" ';
if($name != ""){
 $str.=' name="'.$v['field_name'].'"';
}

if($v['type'] == 'range'){ 
$str.=' style="width: 100%;"';
}
 if($data_type != 'hidden'){
   if(!empty($v['max'])){
 $str.=' maxlength="'.$v['max'].'"';
  }
    if(!empty($v['max_value'])){
 $str.=' max="'.$v['max_value'].'"';
  }
    if(!empty($v['min_value'])){
 $str.=' min="'.$v['min_value'].'"';
  }
   if(!empty($v['hint'])){
 $str.=' placeholder="'.$v['hint'].'"';
 }

 $str.=' '.$v['req'];
 }
if(in_array($v['type'],array('hidden','range'))){ //disable auto cmplete fopr firefox for hidden fields only
$str.=' autocomplete="off"';     
 }else{
 $str.=$auto_complete;     
 }
$str.='>';
if($v['type'] == 'star'){ $str.='</div>';   }
}
break;
}
 return $str;
}


    /**
     * Get Countries and States JSON
     * @return array countries and states
     */
public static function get_country_states(){
           $states_json='{ "AL": "Alabama", "AK": "Alaska", "AS": "American Samoa", "AZ": "Arizona", "AR": "Arkansas", "CA": "California", "CO": "Colorado", "CT": "Connecticut", "DE": "Delaware", "DC": "District Of Columbia", "FM": "Federated States Of Micronesia", "FL": "Florida", "GA": "Georgia", "GU": "Guam", "HI": "Hawaii", "ID": "Idaho", "IL": "Illinois", "IN": "Indiana", "IA": "Iowa", "KS": "Kansas", "KY": "Kentucky", "LA": "Louisiana", "ME": "Maine", "MH": "Marshall Islands", "MD": "Maryland", "MA": "Massachusetts", "MI": "Michigan", "MN": "Minnesota", "MS": "Mississippi", "MO": "Missouri", "MT": "Montana", "NE": "Nebraska", "NV": "Nevada", "NH": "New Hampshire", "NJ": "New Jersey", "NM": "New Mexico", "NY": "New York", "NC": "North Carolina", "ND": "North Dakota", "MP": "Northern Mariana Islands", "OH": "Ohio", "OK": "Oklahoma", "OR": "Oregon", "PW": "Palau", "PA": "Pennsylvania", "PR": "Puerto Rico", "RI": "Rhode Island", "SC": "South Carolina", "SD": "South Dakota", "TN": "Tennessee", "TX": "Texas", "UT": "Utah", "VT": "Vermont", "VI": "Virgin Islands", "VA": "Virginia", "WA": "Washington", "WV": "West Virginia", "WI": "Wisconsin", "WY": "Wyoming" }';
         $countries_json='{"AF":"Afghanistan","AX":"Aland Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"AndorrA","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic static","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, The Democratic Republic static of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D\"Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic static","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic static","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic static Of","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People\"S Republic static of","KR":"Korea, Republic static of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People\"S Democratic Republic static","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, The Former Yugoslav Republic static of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic static of","MC":"Monaco","MN":"Mongolia","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory, Occupied","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"RWANDA","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","ME":"Montenegro","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic static","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic static of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}';
         return array('country'=>$countries_json,"state"=>$states_json);
}
public static function get_currency_list(){
        $currencies = array(
        'USD' => array(
            'name'                => __( 'U.S. Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'GBP' => array(
            'name'                => __( 'Pound Sterling', 'crmperks-forms' ),
            'symbol'              => '&pound;'
        ),
        'EUR' => array(
            'name'                => __( 'Euro', 'crmperks-forms' ),
            'symbol'              => '&euro;',
            'symbol_pos'          => 'right',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'AUD' => array(
            'name'                => __( 'Australian Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'BRL' => array(
            'name'                => __( 'Brazilian Real', 'crmperks-forms' ),
            'symbol'              => 'R$',
            'symbol_pos'          => 'left',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'CAD' => array(
            'name'                => __( 'Canadian Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'CZK' => array(
            'name'                => __( 'Czech Koruna', 'crmperks-forms' ),
            'symbol'              => '&#75;&#269;',
            'symbol_pos'          => 'right',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'DKK' => array(
            'name'                => __( 'Danish Krone', 'crmperks-forms' ),
            'symbol'              => 'kr.',
            'symbol_pos'          => 'right',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'HKD' => array(
            'name'                => __( 'Hong Kong Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
            'symbol_pos'          => 'right',
            'thousands_separator' => ',',
            'decimal_separator'   => '.',
        ),
        'HUF' => array(
            'name'                => __( 'Hungarian Forint', 'crmperks-forms' ),
            'symbol'              => 'Ft',
            'symbol_pos'          => 'right',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'ILS' => array(
            'name'                => __( 'Israeli New Sheqel', 'crmperks-forms' ),
            'symbol'              => '&#8362;',
            'symbol_pos'          => 'left',
        ),
        'MYR' => array(
            'name' => __( 'Malaysian Ringgit', 'crmperks-forms' ),
            'symbol'              => '&#82;&#77;',
        ),
        'MXN' => array(
            'name'                => __( 'Mexican Peso', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'NOK' => array(
            'name'                => __( 'Norwegian Krone', 'crmperks-forms' ),
            'symbol'              => 'Kr',
            'symbol_pos'          => 'left',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'NZD' => array(
            'name'                => __( 'New Zealand Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'PHP' => array(
            'name'                => __( 'Philippine Peso', 'crmperks-forms' ),
            'symbol'              => 'Php',
        ),
        'PLN' => array(
            'name'                => __( 'Polish Zloty', 'crmperks-forms' ),
            'symbol'              => '&#122;&#322;',
            'symbol_pos'          => 'left',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'RUB' => array(
            'name'                => __( 'Russian Ruble', 'crmperks-forms' ),
            'symbol'              => 'pyб',
            'symbol_pos'          => 'right',
            'thousands_separator' => ' ',
            'decimal_separator'   => '.',
        ),
        'SGD' => array(
            'name'                => __( 'Singapore Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'ZAR' => array(
            'name'                => __( 'South African Rand', 'crmperks-forms' ),
            'symbol'              => 'R',
        ),
        'SEK' => array(
            'name'                => __( 'Swedish Krona', 'crmperks-forms' ),
            'symbol'              => 'Kr',
            'symbol_pos'          => 'right',
            'thousands_separator' => '.',
            'decimal_separator'   => ',',
        ),
        'CHF' => array(
            'name'                => __( 'Swiss Franc', 'crmperks-forms' ),
            'symbol'              => 'CHF',
        ),
        'TWD' => array(
            'name'                => __( 'Taiwan New Dollar', 'crmperks-forms' ),
            'symbol'              => '&#36;',
        ),
        'THB' => array(
            'name'                => __( 'Thai Baht', 'crmperks-forms' ),
            'symbol'              => '&#3647;',
        ),
    );
  $keys=array('symbol_pos'=> 'left','thousands_separator' => ',','decimal_separator' => '.','decimals' => 2);  
    $arr=array();
    foreach($currencies as $k=>$v){
       foreach($keys as $kk=>$vv){
           if(!isset($v[$kk])){
               $v[$kk]=$vv;
           }
       } 
    $arr[$k]=$v;
    }
return $arr;
}

  public static function check_filter($filters,$entry){
  $final=false;
   foreach($filters as $filter_s){
  if(is_array($filter_s)){ $and=true; 
  foreach($filter_s as $filter){
  $field=$filter['field'];
  $fval=$filter['value']; 
 $val=isset($entry[$field]) ? $entry[$field] : '';
   $matched=false; 
 if(in_array($filter['op'],array('','not_is') ) ){
      $matched=$fval == $val;  
    }
 if(in_array($filter['op'],array('less','not_less') ) ){
     $val=(int)$val; $fval=(int)$fval; 
      $matched= $val < $fval;  
    }
 if(in_array($filter['op'],array('empty','not_empty') ) ){
      $matched= $val == '';  
    }
if(in_array($filter['op'],array('contains','not_contains')) ){
   $matched=strpos($val,$fval) !== false;
}
if(in_array($filter['op'],array('starts','not_starts'))){
   $matched= strpos($val,$fval) === 0;
  
}
if(in_array($filter['op'],array('ends','not_ends') ) ){
   $matched=strpos($val,$fval) == ( strlen($val)-strlen($fval) ); 
}
  if( strpos($filter['op'],'not_' ) !== false ){
    $matched=!$matched;
  }

$and=$and && $matched;      
} //end and loop filter
$final=$final || $and;
  } } // end or loop
  
  return $final;
}
  /**
     * Get API Settings from database
     * @param  string $id Settings id
     * @return array API settings
     */
public static function get_meta($refresh=true){
    if(empty(self::$settings) || $refresh){
self::$settings=get_option(cfx_form::$id.'_meta',array());
if(!is_array(self::$settings)){ self::$settings=array(); }
if(empty(self::$settings['currency'])){
self::$settings['currency']='USD';    
}
    }
 return self::$settings;
} 
 
     /**
     * Get Contents using CURL
     * @param  string $path Request URL
     * @param  string $method Request Method
     * @param  string $body(optional) Request Body
     * @param  string $head(optional) Request Header
     * @return string Response string
     */
public static function get_contents_curl($path,$method,$body="",$head=""){
if($head == ""){$head=array(); }
$args=array('body' => $body,'headers'=> $head,
            'method' => strtoupper($method),'sslverify' => false,'timeout' => 40);
$response = wp_remote_request($path, $args);
$json=wp_remote_retrieve_body($response);
return $json;

}     
/**
  * Get time Offset 
  * 
  */
  public static function time_offset(){
 $offset = (int) get_option('gmt_offset');
  return $offset*3600;
 } 

public static function get_upload_folder(){
//   if(method_exists('vxcf_form','get_upload_folder')){
 //      $folder=vxcf_form::get_upload_folder();
 //  }else{
    $folder=get_option('crm_perks_upload_folder','');  
      if(empty($folder)){
     $folder=uniqid().rand(9999999,999999999).rand(99999,999999999);
     update_option('crm_perks_upload_folder', $folder);     
      }
      $folder='crm_perks_uploads/'.$folder;
  // }
   return $folder;   
}
public static function get_form_post($fields){
    $fixed=array();
    if(!empty($fields) && !empty($_POST['fixed'])){
        $post=$_POST['fixed'];
        foreach($fields as $k=>$v){
     if(isset($post[$k])){
   if(in_array($v['type'],array('textarea'))){
   $fixed[$k]=cfx_form::clean_text($post[$k]);    
   }else{
        $fixed[$k]=cfx_form::clean($post[$k]);    
   }      
     }   
    } } 
return $fixed;    
}
  /**
  * deactivate
  * 
  * @param mixed $action
  */
  public function deactivate($action="deactivate"){ 
  do_action('plugin_status_'.cfx_form::$id,$action);
  }
public static function select_options($options,$option_db){ 
    $res='';
foreach($options as $k=>$v){ 
    if(!is_array($v)){ $v=array('label'=>$v); }
    $sel=''; if($k == $option_db){$sel='selected="selected"';}
     if( !empty($v['disable'])){$sel.=' disabled="disabled"'; $v['label'].='  - Premium feature '; }
  $res.='<option value="'.$k.'" '.$sel.'>'.$v['label'].'</option>';  
}
return $res;
}  
public static function table_name($name){
    global $wpdb;
    return $wpdb->prefix.cfx_form::$id.'_'.$name;
}
public static function post($key, $arr=''){
     if(is_array($arr)){
  return isset($arr[$key])  ? $arr[$key]: "";
  }
  return isset($_REQUEST[$key]) ? self::clean($_REQUEST[$key]) : ""; 
}
  /**
  * get plugin slug
  *  
  */
  public static function  get_slug(){
  return plugin_basename(__FILE__);
  }
public static function clean($var){
    if ( is_array( $var ) ) {
        return array_map( 'cfx_form::clean', $var );
    } else {
        return  sanitize_text_field(wp_unslash($var));
    }
}
public static function clean_html($var){
    return wp_unslash($var);
}
public static function clean_text($var){
    if ( is_array( $var ) ) {
        return array_map( 'cfx_form::clean_text', $var );
    }else{
   $var=wp_unslash($var);
   if(function_exists('sanitize_textarea_field')){
   $var=sanitize_textarea_field($var);
   }
return $var;
    }
}
} // end class
function vx_form_plugin() {
    return cfx_form::instance();
}
vx_form_plugin();
if(!isset($vx_cf)){ $vx_cf=array(); } 
$vx_cf['cfx_form']='cfx_form';



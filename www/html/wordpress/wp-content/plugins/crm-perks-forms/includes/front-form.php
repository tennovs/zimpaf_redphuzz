<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'cfx_form_front' ) ) {

class cfx_form_front{
  private $f_name;
  private $l_name;
  private $email;
  private $phone;
  private $vis_id;
  private $ip_loc;
  private $bro_info;
  private $global_api;
  public static $err_msgs;
  public static $entry_id='';
  public static $pops='';
  public static $upload_dir;
  public static $left_widget=false;
  public static $star_css=false;
  public static $range_js=false;
  public static $ga_js=false;
  public static $captcha_js='';
  public static $popup_js=false;
  public static $cookies_js=false;
  public static $pr_js=false;
  public static $date_js=false;
  public static $right_widget=false;
  public static $forms_added=array();
  public static $response=array();
  public static $cookies=array();
  public static $triggers=array();
  private $lightbox_script_enq=false;

     
public function __construct(){

    //register short code
add_shortcode('crmperks-forms', array( $this, 'add_form_shortcode'));
add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) ); 
add_action( 'init', array( $this, 'verify_header' ) ); 
add_action( 'wp_footer', array($this,'maybe_add_form'),10); 
add_action( 'wp_footer', array($this,'add_form_footer_js'),30); 

global $pagenow;
if($pagenow == 'admin-ajax.php'){ 
add_action( 'wp_ajax_nopriv_post_cfx_form', array($this,'post_form')); 
add_action( 'wp_ajax_post_cfx_form', array($this,'post_form'));
}
}

    /**
     * Registers and enqueues plugin-specific scripts and styles.
     */
public function register_plugin_scripts() { 
    wp_register_style('cfx-jquery-ui', cfx_form_plugin_url. 'css/jquery-ui.min.css');
        // Plugin scripts    
    //wp_register_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js' );
    wp_register_script( 'cfx-front-mask', cfx_form_plugin_url. 'js/mask.js',array('jquery') );
}
    

    /**
     * Handle WP Short Code
     * @param  array $atts Short code attributes 
     * @return string Form HTML string
     */
public function add_form_shortcode($atts){
return  $this->show_form($atts['id'],"");
}
public static function animate_css($name){
 switch($name){
case'cfx_bounce': ?>
@-webkit-keyframes cfx_bounce {
  from,
  20%,
  53%,
  80%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  40%,
  43% {
    -webkit-animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    -webkit-transform: translate3d(0, -30px, 0);
    transform: translate3d(0, -30px, 0);
  }

  70% {
    -webkit-animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    -webkit-transform: translate3d(0, -15px, 0);
    transform: translate3d(0, -15px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, -4px, 0);
    transform: translate3d(0, -4px, 0);
  }
}

@keyframes cfx_bounce {
  from,
  20%,
  53%,
  80%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  40%,
  43% {
    -webkit-animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    -webkit-transform: translate3d(0, -30px, 0);
    transform: translate3d(0, -30px, 0);
  }

  70% {
    -webkit-animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
    -webkit-transform: translate3d(0, -15px, 0);
    transform: translate3d(0, -15px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, -4px, 0);
    transform: translate3d(0, -4px, 0);
  }
}

.cfx_bounce {
  -webkit-animation-name: cfx_bounce;
  animation-name: cfx_bounce;
  -webkit-transform-origin: center bottom;
  transform-origin: center bottom;
}
<?php break; case'cfx_shake': ?>
@-webkit-keyframes cfx_shake {
  from,
  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  10%,
  30%,
  50%,
  70%,
  90% {
    -webkit-transform: translate3d(-10px, 0, 0);
    transform: translate3d(-10px, 0, 0);
  }

  20%,
  40%,
  60%,
  80% {
    -webkit-transform: translate3d(10px, 0, 0);
    transform: translate3d(10px, 0, 0);
  }
}

@keyframes cfx_shake {
  from,
  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }

  10%,
  30%,
  50%,
  70%,
  90% {
    -webkit-transform: translate3d(-10px, 0, 0);
    transform: translate3d(-10px, 0, 0);
  }

  20%,
  40%,
  60%,
  80% {
    -webkit-transform: translate3d(10px, 0, 0);
    transform: translate3d(10px, 0, 0);
  }
}

.cfx_shake {
  -webkit-animation-name: cfx_shake;
  animation-name: cfx_shake;
}
<?php break; case'cfx_bounceIn': ?>
@-webkit-keyframes cfx_bounceIn {
  from,
  20%,
  40%,
  60%,
  80%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  20% {
    -webkit-transform: scale3d(1.1, 1.1, 1.1);
    transform: scale3d(1.1, 1.1, 1.1);
  }

  40% {
    -webkit-transform: scale3d(0.9, 0.9, 0.9);
    transform: scale3d(0.9, 0.9, 0.9);
  }

  60% {
    opacity: 1;
    -webkit-transform: scale3d(1.03, 1.03, 1.03);
    transform: scale3d(1.03, 1.03, 1.03);
  }

  80% {
    -webkit-transform: scale3d(0.97, 0.97, 0.97);
    transform: scale3d(0.97, 0.97, 0.97);
  }

  to {
    opacity: 1;
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}

@keyframes cfx_bounceIn {
  from,
  20%,
  40%,
  60%,
  80%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  20% {
    -webkit-transform: scale3d(1.1, 1.1, 1.1);
    transform: scale3d(1.1, 1.1, 1.1);
  }

  40% {
    -webkit-transform: scale3d(0.9, 0.9, 0.9);
    transform: scale3d(0.9, 0.9, 0.9);
  }

  60% {
    opacity: 1;
    -webkit-transform: scale3d(1.03, 1.03, 1.03);
    transform: scale3d(1.03, 1.03, 1.03);
  }

  80% {
    -webkit-transform: scale3d(0.97, 0.97, 0.97);
    transform: scale3d(0.97, 0.97, 0.97);
  }

  to {
    opacity: 1;
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}

.cfx_bounceIn {
  -webkit-animation-duration: 0.75s;
  animation-duration: 0.75s;
  -webkit-animation-name: cfx_bounceIn;
  animation-name: cfx_bounceIn;
}

<?php break; case'cfx_bounceInLeft': ?>
@-webkit-keyframes cfx_bounceInLeft {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: translate3d(-3000px, 0, 0);
    transform: translate3d(-3000px, 0, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(25px, 0, 0);
    transform: translate3d(25px, 0, 0);
  }

  75% {
    -webkit-transform: translate3d(-10px, 0, 0);
    transform: translate3d(-10px, 0, 0);
  }

  90% {
    -webkit-transform: translate3d(5px, 0, 0);
    transform: translate3d(5px, 0, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

@keyframes cfx_bounceInLeft {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: translate3d(-3000px, 0, 0);
    transform: translate3d(-3000px, 0, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(25px, 0, 0);
    transform: translate3d(25px, 0, 0);
  }

  75% {
    -webkit-transform: translate3d(-10px, 0, 0);
    transform: translate3d(-10px, 0, 0);
  }

  90% {
    -webkit-transform: translate3d(5px, 0, 0);
    transform: translate3d(5px, 0, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

.cfx_bounceInLeft {
  -webkit-animation-name: cfx_bounceInLeft;
  animation-name: cfx_bounceInLeft;
}
<?php break; case'cfx_bounceInRight': ?>
@-webkit-keyframes cfx_bounceInRight {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  from {
    opacity: 0;
    -webkit-transform: translate3d(3000px, 0, 0);
    transform: translate3d(3000px, 0, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(-25px, 0, 0);
    transform: translate3d(-25px, 0, 0);
  }

  75% {
    -webkit-transform: translate3d(10px, 0, 0);
    transform: translate3d(10px, 0, 0);
  }

  90% {
    -webkit-transform: translate3d(-5px, 0, 0);
    transform: translate3d(-5px, 0, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

@keyframes cfx_bounceInRight {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  from {
    opacity: 0;
    -webkit-transform: translate3d(3000px, 0, 0);
    transform: translate3d(3000px, 0, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(-25px, 0, 0);
    transform: translate3d(-25px, 0, 0);
  }

  75% {
    -webkit-transform: translate3d(10px, 0, 0);
    transform: translate3d(10px, 0, 0);
  }

  90% {
    -webkit-transform: translate3d(-5px, 0, 0);
    transform: translate3d(-5px, 0, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

.cfx_bounceInRight {
  -webkit-animation-name: cfx_bounceInRight;
  animation-name: cfx_bounceInRight;
}
<?php break; case'cfx_bounceInUp': ?>
@-webkit-keyframes cfx_bounceInUp {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  from {
    opacity: 0;
    -webkit-transform: translate3d(0, 3000px, 0);
    transform: translate3d(0, 3000px, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(0, -20px, 0);
    transform: translate3d(0, -20px, 0);
  }

  75% {
    -webkit-transform: translate3d(0, 10px, 0);
    transform: translate3d(0, 10px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, -5px, 0);
    transform: translate3d(0, -5px, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

@keyframes cfx_bounceInUp {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  from {
    opacity: 0;
    -webkit-transform: translate3d(0, 3000px, 0);
    transform: translate3d(0, 3000px, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(0, -20px, 0);
    transform: translate3d(0, -20px, 0);
  }

  75% {
    -webkit-transform: translate3d(0, 10px, 0);
    transform: translate3d(0, 10px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, -5px, 0);
    transform: translate3d(0, -5px, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

.cfx_bounceInUp {
  -webkit-animation-name: cfx_bounceInUp;
  animation-name: cfx_bounceInUp;
}
<?php break; case'cfx_bounceInDown': ?>
@-webkit-keyframes cfx_bounceInDown {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -3000px, 0);
    transform: translate3d(0, -3000px, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(0, 25px, 0);
    transform: translate3d(0, 25px, 0);
  }

  75% {
    -webkit-transform: translate3d(0, -10px, 0);
    transform: translate3d(0, -10px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

@keyframes cfx_bounceInDown {
  from,
  60%,
  75%,
  90%,
  to {
    -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }

  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -3000px, 0);
    transform: translate3d(0, -3000px, 0);
  }

  60% {
    opacity: 1;
    -webkit-transform: translate3d(0, 25px, 0);
    transform: translate3d(0, 25px, 0);
  }

  75% {
    -webkit-transform: translate3d(0, -10px, 0);
    transform: translate3d(0, -10px, 0);
  }

  90% {
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }

  to {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}

.cfx_bounceInDown {
  -webkit-animation-name: cfx_bounceInDown;
  animation-name: cfx_bounceInDown;
}
<?php break; case'cfx_fadeIn': ?>
@-webkit-keyframes cfx_fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes cfx_fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

.cfx_fadeIn {
  -webkit-animation-name: cfx_fadeIn;
  animation-name: cfx_fadeIn;
}
<?php break; case'cfx_zoomIn': ?>
@-webkit-keyframes cfx_zoomIn {
  from {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  50% {
    opacity: 1;
  }
}

@keyframes cfx_zoomIn {
  from {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  50% {
    opacity: 1;
  }
}

.cfx_zoomIn {
  -webkit-animation-name: cfx_zoomIn;
  animation-name: cfx_zoomIn;
}
<?php break; 
 }   
 if(!empty($name)){
     ?>
     .cfx_animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
    -webkit-animation-duration: 400ms;
  animation-duration: 400ms;
}
@media (prefers-reduced-motion) {
  .cfx_animated {
    -webkit-animation: unset !important;
    animation: unset !important;
    -webkit-transition: none !important;
    transition: none !important;
  }
}
     <?php
 }
}
public static function footer_css(){
?>
<style type="text/css">   
.cfx_form_div .col12{
      padding-bottom: 4px;  
    }
  .cfx_form_div .crm_file_label{
 position:relative;
 }
 .crm_input_field .crm_error{
    display: inline;
}
 .cfx_form_div label.crm_error{
     border: 1px solid #f2070b;
     color: #f2070b;
     padding: 5px 10px;
     font-size: 14px;
     display: block;
 }

 .crm_radio_div label{
    font-size: 14px;
} 
   .cfx_form_div .crm_file_btn{
      float: none;
    height: 28px;
    line-height: 28px;
    padding: 0 16px !important;
    position: absolute;
    right: 4px;
    top: 4px;
    z-index: 1;
  }
  .cfx_form_div .crm_radio_div input{
   width: auto; outline: 0; margin: 0 2px 0 0;
  }
  .cfx_form_div .crm_radio_div{
   padding-left: 2px;   
  }
 .cfx_form_div input[type=radio]:checked+.crm_radio_label , .cfx_form_div input[type=checkbox]:checked+.crm_radio_label{
    font-weight: bold;  
  }
  .cfx_form_div .crm_radio_label{
      cursor: pointer;
      display: inline;
  }
  .cfx_form_div .cfx_radio{
      vertical-align: middle; 
  }
  .cfx_form_div .crm_file_field{
 bottom: 0;
    cursor: pointer;
    height: 100%;
    opacity: 0;
    padding: 8px 10px;
    position: absolute;
    right: 0;
    width: 100%;
 }
   .cfx_form_div .crm_form_row_wrap , .crm_form_footer{
       padding-right: 16px;
   }
   .cfx_form_div .crm_form_row_1{
      display: block; clear: both;
  }
@media screen and (min-width: 850px) {  
  .cfx_form_div .crm_form_row_2{
       float: left;
      width: 50%;
  }
.cfx_form_div .crm_form_row_3{
      float: left;
      width: 33%;
  }
.cfx_form_div .crm_form_row_4{
      float: left;
      width: 25%; 
  }
.cfx_form_div .crm_form_row_5{ float: left; width: 20%; }
  .cfx_form_div .crm_form_row_90{ float: left; width: 90%; }
  .cfx_form_div .crm_form_row_80{ float: left; width: 80%; }
  .cfx_form_div .crm_form_row_70{ float: left; width: 70%; }
  .cfx_form_div .crm_form_row_60{ float: left; width: 60%; }
  .cfx_form_div .crm_form_row_50{ float: left; width: 50%; }
  .cfx_form_div .crm_form_row_40{ float: left; width: 40%; }
  .cfx_form_div .crm_form_row_30{ float: left; width: 30%; }
  .cfx_form_div .crm_form_row_20{ float: left; width: 20%; }
  .cfx_form_div .crm_form_row_10{ float: left; width: 10%; }
  .cfx_form_div .crm_form_row_6{ float: left; width: 5%; }
  
  .cfx_form_div .crm_radio_label_1{
      display: block;
  }
  .cfx_form_div .crm_radio_label_2{
      display: inline-block;
      width: 50%; padding-right: 10px;
  }
.cfx_form_div .crm_radio_label_3{
      display: inline-block;
      width: 33%; padding-right: 10px;
  }
  .cfx_form_div .crm_radio_label_4{
      display: inline-block;
      width: 25%; padding-right: 10px;
  }
    .cfx_form_div .crm_radio_label_5{
      display: inline-block;
      width: 20%; padding-right: 10px;
  }
}
@media screen and ( max-width: 520px ) {
.cfx_form_div .cfx_form_contents .cfx_submit{
   width: 100%; 
}
}
 .cfx_form_label .cfx_star{
      color: #dc3232;
  }
    .cfx_form_div .crm_input_field textarea{
         padding: 5px 10px;
     }
  .crm_radio_div{
margin: 5px 0px;
} 
  .cfx_form_div .cfx_form_contents{
      clear: both;
  }
  .cfx_form_div .crm_sf_ajax{
      display: none;
      margin-left: 2px;
      vertical-align: middle;
      border: 0px; outline:0px; 
  }
  .cfx_form_div .crm_img_btn{
      border: 0px; outline:0px; display: inline-block;
  }
.cfx_msgs_div{
    padding: 0px 0;
}

  .cfx_alert_msg{
     border-width: 3px;
     padding: 10px;
     margin: 10px 0px;
 }

  .cfx_alert_msg,.cfx_form_div  .cfx_alert_block{
     border-style:solid; border-color:#dd3333;  border-width: 2px;
    color:#dd3333;      padding: 6px 10px;
    font-size: 14px; margin: 4px 0px;
}
  .cfx_alert_msg{
     display: none;   
}

.cfx_hr{
    margin-top: 10px;
}
 .cfx_form_div img ,  .cfx_form_div input[type="image"]{
         max-width: 100%;
         height: auto;
         border: 0px;
         vertical-align: middle;
         background: transparent;
     }
.cfx_form_div input[type="image"]{
         cursor: pointer;
}
.cfx_form_div .cfx_form_contents .cfx_submit:disabled{
   opacity: 0.6; 
}
.cfx_prog_percent{
width: 10%; background-color: #71b029; height: 100%; 
 background-image: linear-gradient(135deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);
     background-size: 1rem 1rem;
}
.cfx_prog{
    background-color: #ddd; height: 22px; width: 100%; position: relative;
}
.cfx_form_div iframe{
    vertical-align: bottom;
}

</style>
    <?php
}    
public function maybe_add_form(){
    global $wpdb;
 global $post;
 $table= cfx_form::table_name('forms');
$results = $wpdb->get_results( 'SELECT * FROM '.$table.' where form_location!="" limit 10', ARRAY_A ); 
//---------
foreach($results as $form){
    $show_widget=false;
  $config=json_decode($form['config'],true);  
  $loc=$form['form_location'];
    if($loc=='all'){
    $show_widget=true;
}else{
    $page_match=false; 
       if(!empty($config['pages']) && in_array($post->ID,$config['pages'])){
     $page_match=true;  
   }
  if($loc == 'selected' && $page_match === true){ //show on selected pages
  $show_widget=true;
  } 
   if( $loc == 'except' && $page_match === false){ //show except these
$show_widget=true;   
   }
}

if($show_widget){ 
echo $this->show_form($form['id']);   
}
}
//-------
if(!empty(self::$captcha_js)){
  $meta=cfx_form::get_meta();
  wp_enqueue_script( 'recaptcha','https://www.google.com/recaptcha/api.js?onload=cfx_cap_loaded&render='.self::$captcha_js, array(), false );  
} 
}
public function add_form_footer_js(){

if(!empty(self::$forms_added)){
 self::footer_css(); 
 $meta=cfx_form::get_meta(); 
?>
<style type="text/css">
<?php if( self::$star_css){  ?>
.cfx_rating {
  unicode-bidi: bidi-override;
  direction: rtl;
}
.cfx_rating span:hover:before,
.cfx_rating > .cfx_sel_star:before,
.cfx_rating > span:hover ~ span:before,
.cfx_rating > .cfx_sel_star ~ span:before
 {
   content: "\2605";
   position: absolute;
 color: #ffbf00;
}
.cfx_rating > span {
  display: inline-block;
  position: relative;
  width: 1em;
  font-size: 36px;
  line-height: 42px;
  cursor: pointer;
  color: #777;
}
.cfx_rating_div{
    text-align: left;
}
.cfx_range_val{
line-height: 48px;
padding-left: 12px;
}
<?php } ?>
.vx_form_btn_wrapper{
font-family: "Segoe UI Symbol","Arial Unicode MS","Lucida Sans Unicode",sans-serif;
z-index:999999;
}
.cfx_inline_content{
    z-index: 10000000; position: absolute; margin: 30px auto;
}
.cfx_hide_html_scrol{
    overflow: hidden !important;
}
.cfx_form_overlay{
 position: fixed; width: 100%; height: 100%; background-color: rgba(255,255,255,0.7);  top:0; left:0; bottom:0;
 overflow-y: auto;
  z-index: 9999999; display: none;  
}
.cfx_close_popup_btn_inner{
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
    opacity: 1;
}
.cfx_close_popup_btn_inner:hover{
   opacity: .8;  
}
.cfx_close_popup_btn{
    position: absolute;
    right: -16px;
    top: -10px;
    cursor: pointer;
    opacity: 1;
}
.cfx_close_popup_btn:hover{
   opacity: .8;  
}
.cfx_form_div form{
    margin: 0; padding: 0;
}
</style>
<script type="text/javascript">
var cfx_ajax_url='<?php echo admin_url('admin-ajax.php'); ?>';
 var vx_params={};
 var cfx_captcha_stamp=0;
window.addEventListener("load", function(event) {
    
if(typeof JSON == "undefined"){ return true; }
  var  is_pr=jQuery('.vx_form_pr').length ? true : false ;
var is_abo=jQuery('.cfx_disable_abo').length ? false : true ;
  var note_form=false; var cfx_submitting_form=false;
    var vis_id=cfx_get_cookie('vx_user');
    var form_id="";
var screen_width=""; var screen_height="";
 if(screen_width == ""){
 if(screen){
   screen_width=screen.width;  
 }else{
     screen_width=jQuery(window).width();
 }    }  
  if(screen_height == ""){
 if(screen){
   screen_height=screen.height;  
 }else{
     screen_height=jQuery(window).height();
 }    }

    
<?php if(self::$star_css){ ?>    
jQuery(document).on('click','.cfx_rating span',function(){ 
 var div=jQuery(this).parents('.cfx_rating_div');
 div.find('.cfx_sel_star').removeClass('cfx_sel_star');   
 div.find('input').val(jQuery(this).attr('data-val'));  
 
  jQuery(this).addClass('cfx_sel_star');   
 }); 
<?php } if(self::$captcha_js){ ?>

if(typeof grecaptcha !="undefined"){
// cfx_cap_loaded(); //if captacha loaded by other plugins 
}else{
//jQuery("head").append('<script src="https://www.google.com/recaptcha/api.js?onload=cfx_cap_loaded&render=explicit" async defer><\/script>');
}   

<?php } if(self::$popup_js){ ?>

jQuery(document).on('click','.cfx_form_overlay',function(e){
    if(e.target != this) return;
maybe_close_popup();
});
jQuery(document).keyup(function(e) {
if (e.keyCode == 27) { 
maybe_close_popup();
}
});
    
jQuery(document).on('click','.cfx_close_popup_btn_js',function(){
var div=jQuery(this).parents('.cfx_inline_content');
cfx_close_popup(div);
});

jQuery(document).on('click','.vx_form_btn_wrapper',function(){
var btn=jQuery(this);
cfx_open_box_btn(btn);
});
jQuery( window ).resize(function() {
var open_boxes=jQuery('.cfx_popup_open');
if(open_boxes.length){
open_boxes.each(function(){  
var btn=jQuery(this);      
 cfx_set_box_pos(btn);   
}); }
});

function maybe_close_popup(){
    var btn=jQuery('.cfx_popup_open');
    if(btn.length && btn.attr('data-close') == 'yes'){
  var form_id=btn.attr('data-id');
  var div=jQuery('#crm_box_'+form_id);
    cfx_close_popup(div);  
 }
}
  
var auto_btn=jQuery(".crm_auto_open_form_page");
if(auto_btn.length){ //only open first lightbox 
auto_btn.each(function(){
 cfx_open_box_btn(jQuery(this));   
})
}
<?php } ?>
cfx_load_js();
    
<?php  if(self::$cookies_js){ ?>
if(jQuery(".cfx_form_div").find(".use_cookies").length){
jQuery(".cfx_form_div").find(".use_cookies").each(function(){
  var form=jQuery(this).parents('.cfx_form_div');   
    var form_id=jQuery(this).val();
    var cookie_name='cfx_form_data_'+form_id;  
try{
var crm_form=jQuery.parseJSON(cfx_get_cookie(cookie_name)); 
if(typeof crm_form == "object"){
jQuery.each(crm_form,function(k,v){ 
if(k !=""){
 form.find("#"+k).val(v);
} 
})
} }catch(e){}
});

jQuery(document).on('blur','.cfx_input,.cfx_radio',function(){
var form=jQuery(this).parents('.cfx_form_div'); 
if(form.find(".use_cookies").length){
var form_id=form.find('.form_id').val();  
var cookie_name='cfx_form_data_'+form_id;    
try{ var crm_form=jQuery.parseJSON(cfx_get_cookie(cookie_name)); }catch(e){ }
if(!crm_form){ crm_form={}; }
crm_form[this.id]=this.value; 
cfx_set_cookie(cookie_name,JSON.stringify(crm_form),720000);  
}  
});
}
<?php } if(self::$ga_js){ ?>
     var no_cat="no category"; 
    jQuery(document).on('blur','.cfx_form_main :input',function() {
         no_cat=jQuery(this.form).find(".crm_ga").val(); 
        
         var inputAction = 'skipped';
      if(jQuery.inArray(this.type,["checkbox","radio"])!==-1){
    var elem=document.getElementsByName(this.name);
    if(jQuery(elem).filter(":checked").length)
    inputAction='completed';
     }else{  
      if (this.value && this.value !== this.defaultValue) {
        var inputAction = 'completed';
      }
      }
      var label=jQuery(this).attr('data-name') || jQuery(this).attr('id')|| jQuery(this).attr('name');
      if(!label || !jQuery(this.form).find(".crm_ga").length)
      return;
    /// console.log((form_id|| no_cat)+"---blur-----"+inputAction+"============"+label);
        if (typeof gtag !== 'undefined') {
        gtag('event', no_cat, {  'event_category': inputAction, 'event_label': label });
                 }else if (typeof ga !== 'undefined') {
                    ga('send', 'event', no_cat, inputAction, label,1);
                 }
                //check if _gaq is set too
                if (typeof _gaq !== 'undefined') {
                    _gaq.push(['_trackEvent', no_cat, inputAction, label]);
                }
  });
  jQuery(document).on('click','.cfx_form_main :submit',function() {
         no_cat=jQuery(this.form).find(".crm_ga").val();   
      inputAction = 'submitted';
        if(!jQuery(this.form).find(".crm_ga").length){ return; }
      if (typeof dataLayer !== 'undefined') {
        dataLayer.push({'event' : 'vx.event','vx.category': no_cat,'vx.action' :inputAction ,'vx.label' : 'submit','vx.value':1})
                 }else if (typeof ga !== 'undefined') {
                    ga('send', 'event', no_cat, inputAction, 'submit',1);
                 }
                //check if _gaq is set too
                if (typeof _gaq !== 'undefined') {
                    _gaq.push(['_trackEvent', no_cat, inputAction, 'submit']);
                }
 });
<?php } ?>
jQuery(".cfx_submit").removeAttr('disabled');

jQuery(document).on('submit','.cfx_form_ajax',function(e){
if(typeof FormData !='function' ){ return; }
e.preventDefault(); 
var form=jQuery(this);
var submit_stop=form.triggerHandler('cfx_form_before_submit');
if( form.hasClass('cfx_process') || submit_stop){ return; } 

var is_steps=false; var step;
  var data=new FormData(this);
  total_parts=form.find('.cfx_steps').length;
if(total_parts){
is_steps=true;
step=form.find('.cfx_steps:visible'); 
}
form.addClass('cfx_process');
var form_id=form.find(".form_id").val();

    var form_div=jQuery(this).parents(".cfx_form_div");
    data.append('vx_is_ajax','true');
    data.append('action','post_cfx_form');
    if(!form.find('input[name="vx_width"]').length){
    data.append('width',screen_width);
    data.append('height',screen_height);
    }
    data.append('submit',jQuery('.cfx_submit_clicked:visible').val());
    data.append('url',window.location.href);
    var btn_type="submit";    
var button=form.find('.cfx_submit_clicked');
if(!button.length){ 
    btn_type="image";
    button=form.find(".crm_img_btn");
} 
 if(btn_type=="submit"){
    var text=button.text();
    var btn_text=button.attr('data-alt') || 'Sending ...';
    button.text(btn_text); 
    }else{
    form.find(".crm_sf_ajax").show();    
    } 
  var btn_all=form.find(".cfx_submit");  
   // btn_all.css({opacity:'.6'});
    btn_all.attr('disabled','disabled');
  /*  for(var pair of data.entries()) {
   console.log(pair); 
}*/

var vx_post = function () {
  jQuery.ajax({
    url: cfx_ajax_url,
    type: "POST",
    data:  data,
    processData: false,
    contentType: false,
    success: function(res){
form.removeClass('cfx_process');
    form.find('.cfx_alert_block').remove();
    form.find('.cfx_msg_div').hide();
var reset_btn=true;
cfx_cap_loaded(); 
   /// jQuery(".crm_alert").hide();
    var re={};
    try{re=jQuery.parseJSON(res);}catch(e){};
    
   var re_new=form.triggerHandler('cfx_form_after_submit',re);
   if(re_new){ re=re_new; }
if(!re || typeof re != 'object' ){ re={'status':'error','msg':'Unknow Error'}; }

form.trigger('cfx_form_after_submit_trigger',[re]);
    if(re.status == "ok"){ 
    note_form=false;
    if(re.code){ form.append(re.code); }
        if(re.thanks_page){
            window.location=re.thanks_page;
            reset_btn=false;
        }else if(re.close_popup){
        cfx_close_popup(form.parents('.cfx_inline_content'));    
        }else if(re.msg !=""){
    if(!form_div.find(".cfx_thanks_msg").length){
  form.after('<div class="cfx_thanks_msg cfx_msg_div">'+re.msg+'</div>');
 }else{ form_div.find(".cfx_thanks_msg").html(re.msg);}
///  console.log( form.find(".crm_alert"));
  form_div.find(".cfx_thanks_msg").show(); 
  form_div.find(".cfx_alert_msg").hide(); 
  //form.find(".cfx_msg_div").hide(); 
  if(re.hide_form){
if(re.hide_form == 'yes'){
      form.find('.cfx_form_fields').hide();
      form.find('.cfx_form_head').hide();
      form.find('.crm_form_footer').hide();
}
  }else{
       form[0].reset(); 
  }

      if(is_steps){
step=form.find('.cfx_steps:visible');          
var next=form.find('.cfx_steps').eq(0);
sel_step(step,next,form);
    }
        }
  //reset html5 validation
     if(!form.find(".crm_no_validate").length){
      form.removeAttr('novalidate');   
     } 
    if(form.find(".vx_custom_captcha").length){
           var cap=form.find(".vx_custom_captcha");
           var cap_img=cap.find("img").attr('src');
           jQuery(".vx_custom_captcha").find("img").attr({src:cap_img+"?img="+Math.round(Math.random()*100000)});
            jQuery(".captcha_input").val("");   
          }    
    }
    else if(re.status == 'next_step'){
var next=step.next();
sel_step(step,next,form); 
    }
    else{
          if(form.find(".vx_custom_captcha").length){
           var cap=form.find(".vx_custom_captcha");
           var cap_img=cap.find("img").attr('src');
           jQuery(".vx_custom_captcha").find("img").attr({src:cap_img+"?img="+Math.round(Math.random()*100000)});
            jQuery(".captcha_input").val("");   
          }
     if(re.msgs){
         jQuery.each(re.msgs,function(k,v){
             if(!form.find('#crm_alert_'+form_id+'_'+k).length ){
           var row=form.find('#cfx_row_'+form_id+'_'+k);
           row.find('.crm_input_field').append('<div class="cfx_alert_block" id="crm_alert_'+form_id+'_'+k+'">'+v+'</div>');        
             }else{
          form.find('#crm_alert_'+form_id+'_'+k).html(v);
          form.find('#crm_alert_'+form_id+'_'+k).show();
             } 
         })
     }     
     if(re.msg){
                if(!form_div.find(".cfx_alert_msg").length){
  form.after('<div class="cfx_alert_msg cfx_msg_div">'+re.msg+'</div>');
                }else{ 
  form_div.find(".cfx_alert_msg").html(re.msg); 
                }
     }        
    if(is_steps){
step=form.find('.cfx_steps:visible');          
var error=form.find('.cfx_alert_block').eq(0);
if(error.length){
var next=error.parents('.cfx_steps');
//console.info(step,next);
sel_step(step,next,form);
    } }
                    
  form_div.find(".cfx_alert_msg").show(); form_div.find(".cfx_thanks_msg").hide();  
    }
    
   if(reset_btn){
    if(btn_type=="submit"){
    button.text(text);
    }else{
    form.find(".crm_sf_ajax").hide();    
    }
  btn_all.removeAttr('disabled');
   // btn_all.css({opacity:'1'});
   }
  
  form.triggerHandler('cfx_form_end_submit',re); 
    
  }
  });
}

var cap_time= new Date().getTime() - cfx_captcha_stamp;
if( cfx_captcha_stamp && cap_time > 110000 ){ //google cap token expiry is 120 sec , we use 110
    cfx_cap_loaded(function(token){
        data.append('g-recaptcha-response',token);
        vx_post();
    });
}else{
vx_post();
}
 });  
 
jQuery(document).on('click',".cfx_submit",function(e){
  jQuery('.cfx_submit_clicked').removeClass('cfx_submit_clicked');  
  jQuery(this).addClass('cfx_submit_clicked');  
});
   
jQuery(".cfx_steps:hidden").find('.crm_required').removeAttr('required');

jQuery(".cfx_form_ajax").each(function(){
if(jQuery(this).find('.cfx_steps').length){  jQuery(this).addClass('has_error');     }
});

jQuery(document).on('click','.cfx_prev_btn',function(e){
  
var step=jQuery(this).parents('.cfx_steps');
var form=step.parents('form');
if(form.hasClass('cfx_form_ajax')){
  e.preventDefault();
var val=jQuery(this).val();
var form_id=form.find('.form_id').val();
var next=jQuery('#cfx_step_'+form_id+'_'+val);      
sel_step(step,next,form);
}
});

if(typeof cfx_filters == 'object' ){
  var body;
    jQuery.each(cfx_filters,function(form_id,v){
  body=jQuery('.cfx_form_div_'+form_id);
  do_filters(body);    
  })  
}
jQuery(document).on('input','.crm_form_body :input',function(){ 
var body=jQuery(this).parents('.crm_form_body');
do_filters(body);
});
function do_filters(body){
         var form_id=body.find('.form_id').val();
     var field_row,matched;
if(typeof cfx_filters != 'undefined' && cfx_filters[form_id]){ 
  jQuery.each(cfx_filters[form_id],function(k,filter){
      if(typeof filter == 'object' && filter['when'] && filter['do']){ 
      matched=cfx_check_filter(filter['when'],form_id,body); 
         jQuery.each(filter['do'],function(k,v){
             if(v['do_field']){
           field_row=jQuery('#cfx_row_'+form_id+'_'+v['do_field'],body); 
           switch(v['do_action']){
               case'hide':
               if(matched){ field_row.hide(); }else{ field_row.show(); }
               break;
               default: 
           if(matched){ field_row.show(); }else{ field_row.hide(); }
               break;
           }
             }
         })  
      }
  })  }
}


function sel_step(step,next,form){
    step.hide();  
    next.show();
     step.find('.crm_required').removeAttr('required');
     next.find('.crm_required').attr('required','required');
    var part=parseInt(next.attr('data-id'));
    var total_parts=form.find('.cfx_steps').length;
    var percent=Math.ceil((part/total_parts)*100);
    form.find('.cfx_prog_percent').css({width: percent+'%'});
   // console.info(form,percent,part,total_parts);
    form.find('.cfx_prog_title').text(next.attr('data-label'));
    form.find('.cfx_prog_step').text(next.attr('data-id'));
}

jQuery(document).on('click',".cfx_refresh_cap",function(e){
e.preventDefault();
var captcha=jQuery(this).parents(".vx_custom_captcha").find("img");
var url=captcha.attr('src').split("?")[0];
captcha.attr({src:url+"?time="+Math.random()});
});

<?php    if(self::$range_js){ ?>
 jQuery(document).on('input','.cfx_range_input',function(){ 
 jQuery(this).parents('.crm_form_row').find('.cfx_range_val').text('('+this.value+')');    
 })   
<?php } ?>
});
<?php if(self::$popup_js){ ?>
function cfx_open_box_btn(btn){
//var bg=jQuery('#cfx_form_overlay');
var form_id=btn.attr('data-id');
var bg=jQuery('#cfx_popup_'+form_id);
if(!btn.attr('data-bg')){
 bg.show(); 
}
 btn.addClass('cfx_popup_open');
 btn.attr('data-opened','true');
// var op=btn.attr('data-opacity'); 
// var bg_color=btn.attr('data-overlay'); 
 //if(op){ bg.css({'opacity': op}); } 
// if(bg_color){ bg.css({'background-color': bg_color}); } 
 cfx_set_box_pos(btn);
 
}
function cfx_close_popup(div){
    jQuery('.cfx_popup_open').removeClass('cfx_popup_open');
    div.parents('.cfx_form_overlay').hide();
    div.hide();
    if(jQuery('.cfx_hide_html_scrol').length){
jQuery('.cfx_hide_html_scrol').removeClass('cfx_hide_html_scrol');    
} }

function cfx_set_box_pos(btn){
var form_id=btn.attr('data-id');
var pos=btn.attr('data-pos');
 var elem=jQuery('#crm_box_'+form_id);
 var overlay=jQuery('#cfx_popup_'+form_id);
  var css={display:'block'};
  if(overlay.length){ css['position']='absolute'; pos='center'; }else{ css['position']='fixed';   }
 elem.css(css); 
 var width=elem.width();   
 var height=elem.height();
 var win_width=window.innerWidth;
 var win_height=window.innerHeight;

  
  if(jQuery.inArray(pos,['center','center_top']) != -1){
    if(width < win_width){
  css['left']=Math.floor((win_width-width)/2)+'px'  

 }else{ css['left']='12px'; css['width']='96%';  }
  if(height < win_height){
 var top_margin=Math.floor((win_height-height)/2);
if(top_margin > 30 ){ top_margin-=30; }else{ top_margin=0; }
  css['top']=top_margin+'px'   
  if(pos == 'center_top' && top_margin > 100 ){
  css['top']='100px';   
 } 
  }else{ css['top']='10px';  } 
  }

switch(pos){
    case'top':
css['top']='0px'; css['left']='0px';
    break;
    case'bottom_left':
css['bottom']='20px'; css['left']='20px'; css['margin-right']='14px'; css['margin-top']='14px';
    break;
        case'bottom_right':
css['bottom']='20px'; css['right']='20px'; css['margin-left']='10px'; css['margin-top']='14px';
    break;
        case'bottom':
css['bottom']='0px'; css['left']='0px';
    break;
    case'top_left':
css['top']='20px'; css['left']='20px'; css['margin-right']='14px';
    break;
        case'top_right':
css['top']='20px'; css['right']='20px'; css['margin-left']='10px';
    break;
}   
 elem.css(css); 
  if(height+60 > win_height){ //height+top and bottom margins
  var div=jQuery('html').eq(0);
  if(div.length && overlay.length ){
      div.addClass('cfx_hide_html_scrol');
     // div.css('overflow-y','auto');
  }    
 //var head_height=elem.find('.cfx_form_head').outerHeight();
 //console.info(win_height,head_height);
 //elem.find('.cfx_form_contents').css({'max-height': win_height-head_height-20 ,'overflow-y':'auto'});    
 }
   
}
function cfx_show_form(form_id){
    if(form_id){
        var elem=jQuery("#cfx_round_btn_"+form_id);
        cfx_open_box_btn(elem);
    }
}
<?php } ?>
function cfx_check_filter(filter,form_id,body){
    var val,or,and,matched; 
   if(typeof filter == 'object'){
       jQuery.each(filter,function(k,box){
           or=false;
         if(typeof box == 'object'){
           and=true;  
         jQuery.each(box,function(k,v){
          if(v['field'] != ''){
              var elem=jQuery('#crm_field_'+form_id+'_'+v['field'],body);
              if(!elem.length){
               elem=jQuery('#cfx_row_'+form_id+'_'+v['field']+' input',body);
               if(elem.is(':checkbox')){
                elem=jQuery('#cfx_row_'+form_id+'_'+v['field']+' input[value="'+v['value']+'"]',body);  
                if(elem.is(':checked')){
                val=v['value'];   
                }  
               }else{ 
               elem=jQuery('#cfx_row_'+form_id+'_'+v['field']+' input:checked',body); 
               val=elem.val(); 
               }
              }else{
              val=elem.val(); 
              }
          
          var str=v['value']; var op=v['op'];
          matched=false;
 if(jQuery.inArray(op,['','not_is']) != -1){
      matched=str == val;  
    }
 if(jQuery.inArray(op,['empty','not_empty']) != -1){
      matched=str == '';  
    }
 if(jQuery.inArray(op,['less','not_less']) != -1){
     val=isNaN(parseFloat(val)) || 0; str=isNaN(parseFloat(str)) || 0;
      matched=str < val;  
    }
if(jQuery.inArray(op,['contains','not_contains']) != -1){
    var pos=val.indexOf(str);
   matched=pos>-1;
}
if(jQuery.inArray(op,['starts','not_starts']) != -1){
    var pos=val.indexOf(str);
   matched=pos == 0; 
}
if(jQuery.inArray(op,['ends','not_ends']) != -1){
    var pos=val.indexOf(str); 
   matched=pos == (str.length-val.length); 
}
  if(op.indexOf('not_') == 0){
    matched=!matched;
  }
         and=and&&matched;    
          }   
         }); 
      or=and||or;      
         }  
       })
   } 
return or;   
}
function cfx_get_cookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i< ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}
function cfx_set_cookie(cname,cvalue,exmin)
{
var d = new Date();
d.setTime(d.getTime()+(exmin*60*1000));
var expires = "expires="+d.toGMTString();
document.cookie = cname + "=" + cvalue + "; " + expires+ "; path=/";
} 

function cfx_cap_loaded(cap_loaded){ 
    if(typeof grecaptcha !="undefined" && jQuery(".vx_google_cap").length){
   //     grecaptcha.ready(function() {
      grecaptcha.execute('<?php echo self::$captcha_js ?>', {action: 'homepage'}).then(function(token) {
          jQuery(".vx_google_cap").val(token); 
          if(typeof cap_loaded == 'function'){
              cap_loaded(token);
          }
      });
      cfx_captcha_stamp= new Date().getTime();
 // });
  /*  
    jQuery(".vx_google_cap").each(function(){ 
    grecaptcha.render(this, {'sitekey' : jQuery(this).attr('data-sitekey'),'theme' : 'light'});    
    });*/ 
    }
}
function cfx_load_js(){
<?php  if(self::$date_js){ ?>
if(jQuery(".cfx_date_picker").length){ 
     jQuery(".cfx_date_picker").each(function(){
         var format=jQuery(this).attr('date-format') || 'mm/dd/yy';
     jQuery(this).datepicker({
         changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "-100:+10",
        dateFormat: format });     
     })
        jQuery("#ui-datepicker-div").hide();  
}
<?php } //if(self::$cookies_js){ ?>
if(jQuery(".cfx_valid_msg").length){
jQuery(".cfx_form_div").each(function(){ 
     var form_id_t=jQuery(this).find(".form_id").val();
 
    jQuery(this).find(".cfx_valid_msg").each(function(){
        
   if(jQuery(this).attr('data-mask')){
    jQuery(this).mask(jQuery(this).attr('data-mask'));   
   } 
       if(jQuery(this).hasClass('crm_required') ){ //do not handle radio and checkboxes
     if(!jQuery(this).hasClass('crm_radio_checks') ){     
           try{
       var name=jQuery(this).attr('name');
   var element=jQuery(this); 
      element.on('invalid', function(e) {  
      if (!e.target.validity.valid) {   
   element[0].setCustomValidity(jQuery(this).attr('data-msg'));
      }   
     }); 
     element.on('input', function(e) { 
   element[0].setCustomValidity(""); 
   /*     if (!e.target.validity.valid) {   
   element.setCustomValidity(jQuery(this).attr('data-msg'));
      } */
     });}catch(e){ 
     //    console.warn(e); 
     } }
     }  
    });    
    }); //console.log(err_rules);
}    
}
</script>
 <?php 
}
if(!empty(self::$triggers)){
?>
<script type="text/javascript">
window.addEventListener("load", function(event) {
 <?php foreach(self::$triggers as $form_id=>$trigger){ ?>   
jQuery('<?php echo esc_attr($trigger); ?>').click(function(e){
  e.preventDefault();
 cfx_open_box_btn(jQuery('#cfx_round_btn_<?php echo $form_id; ?>'));   
});
<?php } ?>   
})
</script>
<?php    
}
if(!empty(self::$cookies)){
    $global_api=cfx_form::get_meta(false);
    if(empty($global_api['cookies'])){
?>
<script type="text/javascript">
 <?php foreach(self::$cookies as $cookie_name=>$submit){ ?>   
cfx_set_cookie('<?php echo $cookie_name ?>','<?php echo $submit?>',72000000); 
<?php } ?>   
</script>
<?php    
} }
if(!empty(self::$pops)){ echo self::$pops; }
   
} 
private function font_style($style){
    $str=''; 
    switch($style){
     case"italic_n": $str='font-style: italic; font-weight: normal;';    break;
     case"italic_b": $str='font-style: italic; font-weight: bold;';    break;
     case"bold": $str='font-style:normal; font-weight: bold;';    break;
     default:$str='font-style: normal; font-weight: normal;';    break;
    }
    return $str;
}   
     /**
     * Save Abandoned form data (Rejected fields)
     * @param  string $form_id Form Id
     * @return string Form HTML string
     */
public function show_form($form_id){ 
    
if(isset(self::$forms_added[$form_id])){
    return;
}
$form = cfx_form::get_form($form_id);
   
$form=apply_filters('crmperks_forms_front_end_form',$form);
if(empty($form['status']) && $form['status'] != '1'){
    return;
}
if(empty($form['settings']['process_text'])){
 $form['settings']['process_text']='Sending ...';   
}
$options=$form['settings'];  
if(!isset($options['head_bg']) || !isset($options['msg_type'])){
return "<strong>".$form['name'].': </strong><i>Please complete the form setup</i>';
}
if(!empty($options['logged_in'])){
    $user_id=get_current_user_id();
    if(empty($user_id)){
   return !empty($options['login_msg']) ? $options['login_msg'] : '';     
    }
}

wp_enqueue_script('jquery');  
   self::$forms_added[$form_id]='true';     
    $response=self::$response;
        if( !empty($options['block'])){
             if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
       $ips_list=explode("\n",$options['block']);
       foreach($ips_list as $ip_range)
   if($this->check_ip_range($ip,trim($ip_range))){
    return $options['ip_msg'];   
   }
        }
        $time_offset=get_option('gmt_offset'); 
     $start_date= strtotime($options['start_date']) + $time_offset;  
     $expiry_date= strtotime($options['expiry_date']) + $time_offset;  
       if($options['start_date'] !="" && $start_date > current_time( 'timestamp' )){
       return $options['start_msg'];        
   }
      if($options['expiry_date'] !="" && $expiry_date <= current_time( 'timestamp' )){
       return $options['warning_msg'];        
   }
   $cookie_name='cfx_form_'.$form_id;
   $submit= isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] :0;
   if((int)$options['limit']>0){   
     if($options['limit']<=$submit){
       return $options['limit_msg'];        
     }
   }
$class_pr=''; 
if(file_exists(cfx_form_plugin_dir . "includes/plugin-pr.php")){
$class_pr='vx_form_pr'; self::$pr_js=true; $global_api=cfx_form::get_meta(false);
if(!empty($global_api['disable_abo'])){ $class_pr.=' cfx_disable_abo'; }    
}
$class_pr=apply_filters('cfx_form_classes',$class_pr,$form);
$form_fields_html=$this->form_fields_html($form);
$head_bg=$options['head_bg'];
$head_border=$options['head_border_color'];
$head_border_top= !empty($options['border_top']) ? $options['border_top'] : 0;
$input_focus=$options['input_focus'];
$options['input_border_radius']=(int) $options['input_border_radius'];

$steps_form=apply_filters('crmperks_forms_ajax_option',false, $form);            
$form_class_main='cfx_form_normal';
if(empty($options['disable_ajax']) || $steps_form){ $form_class_main='cfx_form_ajax'; }
$form_class=".cfx_form_div_".$form_id;     
ob_start();
if($options['fonts_type'] == "google" && $options['google_family'] !=""){
////   echo '@import url("//fonts.googleapis.com/css?family='.$options['google_family'].'");';
wp_enqueue_style('cfx-google','https://fonts.googleapis.com/css?family='.$options['google_family']);
   }
if($options['fonts_type'] == "url" && $options['url_fonts'] !=""){
          wp_enqueue_style('cfx-form',$options['url_fonts']);
}
$use_popup=cfx_form::post('use_box',$options);
$form_atts='';
  if(isset($options['browser_validation']) && $options['browser_validation'] == "yes" && !$steps_form){
 $form_atts.='novalidate'; 
  }
if(!empty($_GET['cfx_form_return'])){
  parse_str(base64_decode($_GET['cfx_form_return']),$query); 
  if(!empty($query['form_id']) && $query['form_id'] == $form_id){
    $response['status'] = 'ok'; $response['msg']=$options['thanks_msg'];  
  }
}  
$hide_form='';
if(!empty($response['msg']) && !empty($response['status']) && $response['status'] == 'ok' && !empty($options['hide_form']) ){ $hide_form='display:none;';  }   
?>
<style type='text/css'>
<?php

   $fonts="";
   if($options['fonts_type'] == "" && $options['custom_family'] !=""){
   $fonts="font-family: ".$options['custom_family'].';';   
   }
   if($options['fonts_type'] == "google" && $options['google_family'] !=""){
   ////   echo '@import url("//fonts.googleapis.com/css?family='.$options['google_family'].'");';
   $fonts="font-family: ".$options['google_family'].';';   
   }
   if($options['fonts_type'] == "url" && $options['url_fonts_name'] !=""){
   $fonts="font-family: ".$options['url_fonts_name'].';';   
   }
   
 if(!empty($options['width'])){
     if(strpos($options['width'],"px") === false && strpos($options['width'],"%") === false){
  $options['width'].="px";
}
 echo '#crm_box_'.$form_id; ?>{
 width: <?php echo $options['width'] ?>;
 <?php if(!empty($options['max_width'])){?>
 max-width: <?php echo $options['max_width'] ?>;
 <?php } ?>
 }
<?php } 
echo $form_class;?> .cfx_form_inner{
 <?php if(!empty($options['form_bg'])){ ?>
    background-color: <?php echo $options['form_bg']?>;<?php
    }  ?>   
}
<?php echo $form_class;?>.cfx_form_div{
 <?php 
 if(empty($use_popup)){
 if(!empty($options['width'])){
 ?>  width: <?php echo $options['width'] ?>; <?php     
 }
 if(!empty($options['max_width'])){?>
 max-width: <?php echo $options['max_width'] ?>;
 <?php } 
 
 }
 if(!empty($fonts)){ echo  $fonts; }
 ?>    
     -ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;
         <?php 
         if(!empty($options['outer_bg'])){ ?>
    background-color: <?php echo $options['outer_bg'] ?>;
<?php }
if( !empty($options['outer_bg_type']) && !empty($options['outer_img']) ){
if(strpos($options['outer_img'],'//') === false){ $options['outer_img']=cfx_form_plugin_url.'images/'.$options['outer_img']; }         
          ?>
 background-image: url(<?php echo $options['outer_img'] ?>);
<?php      
if(!empty($options['outer_bg_pos_x'])){ ?>
    background-position-x: <?php echo $options['outer_bg_pos_x'] ?>;
<?php }
if(!empty($options['outer_bg_pos_y'])){ ?>
    background-position-y: <?php echo $options['outer_bg_pos_y'] ?>;
<?php }
if(!empty($options['outer_bg_rep_x'])){ ?>
    background-repeat-x: <?php echo $options['outer_bg_rep_x'] ?>;
<?php }
if(!empty($options['outer_bg_rep_y'])){ ?>
    background-repeat-y: <?php echo $options['outer_bg_rep_y'] ?>;
<?php }
if(!empty($options['outer_bg_size'])){ ?>
    background-size: <?php echo $options['outer_bg_size'] ?>;
<?php } }
if(!empty($options['form_padding_left'])){
    ?> padding-left:  <?php echo $options['form_padding_left'].'px'?>; 
<?php }
if(!empty($options['form_padding_right'])){
    ?>
 padding-right:  <?php echo $options['form_padding_right'].'px;'?>; 
<?php }
if(!empty($options['form_padding_top'])){
    ?> padding-top:  <?php echo $options['form_padding_top'].'px'?>; 
<?php }
if(!empty($options['form_padding_bottom'])){
    ?> padding-bottom:  <?php echo $options['form_padding_bottom'].'px'?>; 
<?php }
        if($options['form_border'] == "shadow"){
        ?>
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65);
        <?php
        }
        ?>
border-radius: <?php echo (int)$options['body_radius']?>px;
      overflow: hidden;
    }
 .cfx_form_div *{
            -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    <?php echo $fonts; ?>
    }
     <?php echo $form_class;?> .cfx_prog_percent{
      background-color: <?php echo cfx_form::post('prog_color',$options); ?>;   
     }
     <?php echo $form_class;?> .cfx_form_head{
        font-size: <?php echo $options['head_font_size'];?>px;
        line-height: normal;
        background-color: <?php echo $options['head_bg'] ?>;
        padding: <?php echo (int)$options['head_padding_v']."px ".(int)$options['head_padding_h']."px";  ?>;
        color:<?php echo $options['head_text']?>;
       <?php
       $border_type='border'; 
       if($options['head_border_type'] != 'all' ){
             ?> border-width: 0; <?php
       $border_type.='-'.$options['head_border_type'];     
       } ?>
       <?php echo $border_type; ?>-width: <?php echo $options['head_border'];?>px;
        <?php echo $border_type; ?>-style: <?php echo $options['head_border_style']?>;
        <?php echo $border_type; ?>-color: <?php echo $head_border; ?>;
        
       <?php echo $this->font_style($options['head_font_style']);  ?> 
       
    text-align: <?php echo $options['head_font_align']?>;

    }
   
  <?php echo $form_class;?>  .cfx_form_contents{
        border-width:<?php echo $options['border_width']?>px;
        border-color:<?php echo $options['border_color']?>;
        margin-top: <?php echo (int)cfx_form::post('body_top_margin',$options).'px '; ?>;
        border-style:solid;
        border-top-width: <?php echo (int)cfx_form::post('border_width_top',$options)?>px;
   
    }
<?php echo $form_class;?> .crm_form_body{
padding-top: <?php echo (int)$options['padding_top']; ?>px;
padding-left: <?php echo (int)$options['padding_left']; ?>px;
padding-right: <?php echo (int)$options['padding_right']; ?>px;
padding-bottom: <?php echo (int)$options['padding_bottom']; ?>px;
border-top: 0px;
border-bottom: 0px;
         <?php 
    if($options['use_footer_border'] == "custom" && $options['form_body_bg']!=''){
    ?>
background-color: <?php echo $options['form_body_bg']; ?>;    
    <?php
    }
    ?>
 }
 <?php echo $form_class;?>  .cfx_desc{
 <?php echo $this->font_style($options['desc_font_style']);  ?>
 font-size: <?php echo $options['desc_font_size'];?>px;
text-align: <?php echo $options['desc_font_align']?>;
color: <?php echo $options['desc_text'];?>;   
}
   
 <?php echo $form_class;?> .cfx_refresh_cap{
  color: <?php echo $head_border?>;
  text-decoration: underline; 
  cursor: pointer;  
  margin-left: 20px;
 }
  <?php echo $form_class;?> .cfx_refresh_cap:hover{
  color:<?php echo $head_bg ?>;
  }
<?php echo $form_class;?> .cfx_input_row{
      margin-left:auto;
     margin-right:auto;
     padding-top:<?php echo $options['input_padding_top'] ?>px;
     padding-bottom:<?php echo $options['input_padding_bottom'] ?>px;   
}
<?php echo $form_class;?> .crm_form_row_wrap{

 } 
  <?php echo $form_class;?> .cfx_submit_wrap {
        text-align: <?php echo $options['button_align'] ?>;  
  }
  <?php echo $form_class;?> .crm_form_footer {
    overflow: hidden; clear: both;
    border-top:0px;
    padding-top: <?php echo $options['footer_top_padding'] ?>px;
    padding-bottom: <?php echo $options['footer_bottom_padding'] ?>px;
    text-align: <?php echo $options['button_align'] ?>;
    background-color:<?php echo $options['footer_bg']?>;
    <?php 
    if($options['use_footer_border'] == "custom"){
    ?>
    border-top-width:<?php echo $options['footer_border_width']?>px;
    border-top-color:<?php echo $options['footer_border']?>;
    border-top-style:solid;
     <?php
    }else{
     ?>
    background-image:url(<?php echo cfx_form_plugin_url?>css/images/foobg.png);
       background-repeat: repeat-x;
    background-position: top left; 
<?php } if(!empty($options['footer_top'])){ ?>
margin-top: <?php echo (int)$options['footer_top'] ?>px;
<?php } if(!empty($options['footer_bottom'])){ ?>
margin-bottom: <?php echo (int)$options['footer_bottom'] ?>px;
<?php } ?>
 }

   <?php echo $form_class;?>  .cfx_prog_wrap{
           margin: <?php echo $options['prog_padding_y'].'px '.$options['prog_padding_x'].'px' ?>;
   }
   <?php echo $form_class;?>  .cfx_prog_head{
       font-size: <?php echo $options['prog_font_size'] ?>px;
       color: <?php echo $options['prog_text'] ?>;
       font-style: <?php echo $this->font_style($options['prog_font_style']); ?>;
   }
   <?php echo $form_class;?>  .cfx_prog{
    margin-top: <?php echo $options['prog_top'] ?>px;
    height: <?php echo $options['prog_height'] ?>px;   
   }
   <?php echo $form_class;?>  .cfx_prog_percent{
    background-color: <?php echo $options['prog_color'] ?>;      
   }
   <?php echo $form_class;?>  .cfx_thanks_msg{
    border-width: <?php echo (int)$options['msg_border_width']; ?>px; 
    border-color:<?php echo $options['msg_border']; ?>; 
    background-color:<?php echo $options['msg_bg']; ?>; 
    color: <?php echo $options['msg_text'];?>;
    padding: <?php echo $options['msg_padding_y'];?>px <?php echo $options['msg_padding_x'];?>px;  
    font-size: <?php echo $options['msg_font_size'];?>px;  
     <?php echo $this->font_style($options['msg_font_style']); ?>
    border-style: solid;
    display: none;
}

 <?php echo $form_class;?> .crm_form_body .cfx_alert_block{
    border-width: <?php echo (int)$options['msg_border_width']; ?>px; 
    border-color:<?php echo $options['error_border']; ?>; 
    background-color:<?php echo $options['error_bg']; ?>; 
    color: <?php echo $options['error_text'];?>;
 }   
 <?php echo $form_class;?> .cfx_msgs_div{
 margin-top: <?php echo (int)cfx_form::post('msg_top_margin',$options) ?>px;
 }
 <?php echo $form_class;?> .cfx_alert_msg{
    border-width: <?php echo (int)$options['msg_border_width']; ?>px; 
    border-color:<?php echo $options['error_border']; ?>; 
    background-color:<?php echo $options['error_bg']; ?>; 
    color: <?php echo $options['error_text'];?>;
    
    padding: <?php echo $options['msg_padding_y'];?>px <?php echo $options['msg_padding_x'];?>px;  
    font-size: <?php echo $options['msg_font_size'];?>px;  
     <?php echo $this->font_style($options['msg_font_style']); ?>
    border-style: solid;
 }
  <?php echo $form_class;?>   .cfx_submit{
vertical-align: top;
     border-radius:<?php echo (int)cfx_form::post('submit_radius',$options); ?>px; 
     margin-top:<?php echo (int)cfx_form::post('submit_top_margin',$options); ?>px; 
     margin-bottom:<?php echo (int)cfx_form::post('submit_bottom_margin',$options); ?>px; 
     <?php  if($options['adjust_submit'] == "custom"){ ?>
     width:<?php echo $options['submit_width'].'%'?>;
        <?php } ?> 
    }
  <?php echo $form_class;?> .cfx_input{
     height: <?php echo (int)$options['input_height']?>px;  
 }   
<?php if(!empty($options['star_hex'])){ ?>
<?php echo $form_class;?> .cfx_form_label .cfx_star {
    color: <?php echo $options['star_hex']; ?>;
}

<?php } if(empty($options['disable_input_css'])){ ?>
  <?php echo $form_class;?> .cfx_input{
            -webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
    -webkit-border-radius: <?php echo $options['input_border_radius']?>px;
    -moz-border-radius: <?php echo $options['input_border_radius']?>px;
    -ms-border-radius: <?php echo $options['input_border_radius']?>px;
    -o-border-radius: <?php echo $options['input_border_radius']?>px;
    border-radius: <?php echo $options['input_border_radius']?>px;
    /*     -webkit-tap-highlight-color: transparent;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
   -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;*/
      <?php
       $border_type='border'; 
       if($options['input_border_type'] != 'all' ){
           ?> border-width: 0; <?php
       $border_type.='-'.$options['input_border_type'];     
       } ?>
       <?php echo $border_type; ?>-width: <?php echo $options['input_border_width'];?>px;
        <?php echo $border_type; ?>-style: solid;
        <?php echo $border_type; ?>-color: <?php echo $options['input_color']; ?>;

    position: relative;
    vertical-align: top;
    display: block;
    float: none;
    outline: 0;
  
 <?php
 if(!empty($options['remove_input_shadow'])){
           ?> box-shadow: none; <?php
       }
  if( !empty($options['use_input_bg']) ){?>
  background: <?php echo $options['input_bg'] ?>;
    <?php
    }
    ?>
  padding-left: <?php echo (int)cfx_form::post('input_padding_h',$options) ?>px; 
  padding-right: <?php echo (int)cfx_form::post('input_padding_h',$options) ?>px;
   <?php if(empty($options['input_padding_y'])){ $options['input_padding_y']='0'; } ?>
padding-top:<?php echo (int)$options['input_padding_y']?>px;
padding-bottom:<?php echo (int)$options['input_padding_y']?>px;

  font-size: <?php echo (int)cfx_form::post('input_font_size',$options) ?>px;
  <?php if(!empty($options['input_font_color'])){ ?> 
  color: <?php echo cfx_form::post('input_font_color',$options) ?>;
<?php } ?>
    width: 100%;
*zoom:1
    }
 <?php if(!empty($options['place_color'])){ ?>  
 <?php echo $form_class;?> .cfx_input::placeholder{
    color: <?php echo cfx_form::post('place_color',$options) ?>;  
 }
 <?php } ?>
 <?php echo $form_class;?> .cfx_input:hover{
      border-color: <?php echo $input_focus; ?>;    
      }
   <?php echo $form_class;?> .cfx_input:focus{
             border-style: <?php echo $options['input_border_style_focus'] ?>;
<?php   if( !empty($options['use_input_bg_focus']) ){?>
  background: <?php echo $options['input_bg_focus'] ?>;
    <?php
    }
    ?>
    border-color:<?php echo $options['input_focus']; ?>;
      color: <?php echo cfx_form::post('input_font_color',$options) ?>;
 /*  -webkit-box-shadow: 0 0 3px <?php echo $head_bg;?> inset;
    -moz-box-shadow: 0 0 3px <?php echo $head_bg;?> inset;
    -o-box-shadow: 0 0 3px <?php echo $head_bg; ?> inset;
    box-shadow: 0 0 3px <?php echo $head_bg; ?> inset;*/
    outline: 0;   
        }
<?php } 
$label_pos=cfx_form::post('label_pos',$options);
if(empty($options['disable_label_css'])){ ?>
<?php echo $form_class;?>  .cfx_form_label{
     <?php echo $fonts; ?>  
     display: <?php echo $label_pos == 'hidden' ? 'none' : 'block'; ?>;  
<?php echo $this->font_style($options['label_font_style']);  ?>
      font-size: <?php echo $options['label_font_size'];?>px;
      color: <?php echo $options['font_color'];?>;
    
        margin-bottom: <?php echo (int)$options['label_bottom_margin']; ?>px;
        
    }
    <?php echo $form_class;?>  .cfx_form_label a{
    color: <?php echo $options['font_color'];?>;    
    }
<?php } if(empty($options['disable_btn_css'])){ ?>
 <?php echo $form_class;?>   .cfx_submit{
         background-color: <?php echo $options['btn_bg'] ?>;
  <?php echo $fonts; ?>  
     font-size:<?php echo $options['submit_font_size']?>px;
     color:<?php echo $options['btn_text']?>;
     cursor:pointer;  
     <?php
        if($options['adjust_submit'] == "custom"){
        ?>
width:<?php echo $options['submit_width'].'%'?>;
line-height:<?php echo $options['submit_height']?>px;
padding-top:0; padding-bottom:0;
padding-left:<?php echo (int)$options['submit_pad_h']?>px;
padding-right:<?php echo (int)$options['submit_pad_h']?>px;
 

        <?php
        }
        echo $this->font_style($options['submit_font_style']);
    ?> 
  border-width:<?php echo  $options['btn_border_width']?>px;  
  border-color:<?php echo  $options['btn_border_color']?>;  
  border-style:solid;  text-align:center;
    }
     <?php echo $form_class;?> .cfx_submit:hover,<?php echo $form_class;?> .cfx_submit:focus {
    background-color:<?php echo $options['btn_hover'] ?>;
    border-color:<?php echo $options['btn_border_hover'] ?>;
}
 <?php echo $form_class;?> .cfx_submit:disabled {
    cursor: wait;
    opacity:.7;
}

<?php echo $form_class;?> .cfx_submit:active {
    background-color: <?php echo $options['btn_focus']?>;
        border-color:<?php echo $options['btn_border_focus'] ?>;
    }   
<?php }
    if(trim($options['css']) !=""){
       echo trim($options['css']);    
       }
?>
 </style>
<div class="cfx_form_div cfx_form_div_<?php echo $form_id.' '.$class_pr?>">
<div class="cfx_form_inner">
<form method="post" action="" <?php echo $form_atts; ?> class="cfx_form_main <?php echo $form_class_main ?>" enctype="multipart/form-data">
  <div class="cfx_form_head" style="<?php echo $hide_form ?>">
      <?php
        if($options['head_type'] =="image"){
            ?><img src="<?php echo $options['head_img']?>" style="<?php if(!empty($options['head_img_width'])){ echo 'max-width:'.$options['head_img_width']; } ?>">
            <?php }else if($options['head_type'] == 'html'){
               echo $options['head_html']; 
            }else{ 
            echo $options['heading'];
            }
      ?></div>
  <div class="cfx_form_contents">
    <div class="crm_form_body"> 
    <div class="cfx_form_fields" style="<?php echo $hide_form; ?>"> 
    <?php
        echo $form_fields_html;
        do_action('cfx_form_fields_end',$form);
    $msg=$msg_display=$msg_err=''; $msg_display_err='style="display:none"';
    if(!empty($response['msg'])){
     if(!empty($response['status']) && $response['status'] == 'error'){
         $msg_err=$response['msg'];
     $msg_display_err='style="display:block"';
     }else{
        $msg=$response['msg'];
     $msg_display='style="display:block"';
     }   
    }
    do_action('crmperks_forms_form_fields_end',$form);
    ?>
    <div style="clear: both"></div>
    </div>
    <div class="crm_form_row_wrap cfx_msgs_div">
      <div class="cfx_thanks_msg cfx_msg_div" <?php echo $msg_display ?>><?php echo $msg; ?></div>
      <div class="cfx_alert_msg cfx_msg_div" <?php echo $msg_display_err ?>><?php echo $msg_err; ?></div>
      </div>
    </div>
    <?php if(!empty($options['show_footer'])){ ?>
    <div class="crm_form_footer cfx_submit_wrap" style="<?php echo $hide_form ?>">
   <?php if($options['button_type'] =="image"){ ?>
      <input type="image" class="crm_img_btn" src="<?php echo $options['button_img']?>">    
      <img src="<?php echo cfx_form_plugin_url ?>images/ajx.gif" class="crm_sf_ajax">  
        <?php     }else{ ?>
<button class="crm_btn cfx_submit" type="submit" data-alt="<?php echo $options['process_text']; ?>"><?php echo $options['submit_text']; ?></button>
      <?php }  ?>
    </div>  <?php } ?>
  </div>
  <?php     if(!empty($options['box_trigger'])){
    $jq_trigger=str_replace(' ','',$options['box_trigger']);
if(!empty($jq_trigger)){
      ?>
     <input type="hidden" value="<?php echo esc_attr($jq_trigger); ?>" value="vx_form_triggers"> 
     <?php
}}
     ?>
     <input type="hidden" name="cfx_form_action" value="post_cfx_form"> 
</form>

</div>
</div>
<?php
if(!isset($_COOKIE[$cookie_name])){
   global $wpdb;
    $table_name = cfx_form::table_name('forms');
    $sql="update $table_name set views=views+1 where id='$form_id' limit 1";
    $wpdb->query($sql);
 self::$cookies[$cookie_name]=$submit;   

}
if(!empty($options['conditions']) && !empty($options['filters'])){
  ?>
<script type="text/javascript">
if (typeof cfx_filters === "undefined") {var cfx_filters={}; }
cfx_filters[<?php echo $form_id ?>]=<?php echo json_encode($options['filters']) ?>;   
</script>
 <?php
}
$html=ob_get_clean(); 
if(!empty($use_popup)){
self::$popup_js=true;
$options['lightbox_style']=empty($options['lightbox_style']) ? "1" : $options['lightbox_style'];
if($this->lightbox_script_enq === false){
$this->lightbox_script_enq=true;
//wp_enqueue_script( 'cfx-front-colorbox');
//wp_enqueue_style( 'cfx-front-box-'.$options['lightbox_style']);
}
    $auto_open="";
    if( empty($submit) && !empty($options['auto_open']) ){
      $auto_open="crm_auto_open_form_".$options['auto_open'];   
    }
$meta_par=' data-id="'.$form_id.'"';
if(empty($options['lightbox_pos'])){ $options['lightbox_pos']='center'; }
$meta_par.=' data-pos="'.$options['lightbox_pos'].'" ';   
    
if( !empty( $options['close_box'] ) ){
   $meta_par.=' data-close="yes" ';  
}    
if( !empty( $options['hide_bg'] ) ){
   $meta_par.=' data-bg="yes" ';  
}
if(!empty($options['lightbox_opacity'])){
  // $meta_par.=' data-opacity="'.$options['lightbox_opacity'].'" ';  
}
if(!empty($options['overlay_color'])){
 //  $meta_par.=' data-overlay="'.$options['overlay_color'].'" ';  
}
 $bg_color='#999';
 if(!empty($options['screen_color'])){
 $bg_color=$options['screen_color'];    
 }
 $btn_pos= !empty($options['btn_type']) ? $options['btn_type'] : '';
 $btn_pos_e=$btn_pos == 'left' ? 'left' : 'right';
 $bottom=20;
 $widgets=0; $hide_mscreen=false;
if(class_exists('vx_chat')){
  if(vx_chat::$chat_added == $btn_pos_e){ $widgets+=1; }else{ $hide_mscreen=true; }  
}
if(class_exists('vx_dialpad') ){
 if(vx_dialpad::$button_added == $btn_pos_e){ $widgets+=1; }else{ $hide_mscreen=true; }     
}
if($widgets == 1){
 $bottom=94;   
}else if($widgets == 2){
 $bottom=170;   
}
$btn_size='60px'; $btn_radius='50%'; $bg_size='34px'; $btn_margin='72px';
if(in_array($btn_pos,array('leftm','rightm'))){
$btn_size='46px';     $btn_radius='0'; $bg_size='30px'; $btn_margin='57px';
}
$close_btn='cfx_close_popup_btn';
if(!empty($options['lightbox_pos']) && $options['lightbox_pos'] == 'top'){
$close_btn='cfx_close_popup_btn_inner';   
}
$css= empty($options['btn_type']) ? 'display:none;' : ''; 
 ob_start();
 if(!empty($options['btn_type']) && !in_array($options['btn_type'],array('html'))){
?>
<style type="text/css">
#cfx_round_btn_<?php echo $form_id; ?>{
    position: fixed;
   <?php
  if($btn_pos == 'left'){
   ?> bottom: <?php echo (int)$bottom ?>px; left: 20px; 
   <?php
  }else if($btn_pos == 'right'){
   ?>  bottom: <?php echo (int)$bottom ?>px; right: 20px; 
   <?php
  }else if($btn_pos == 'leftm'){
   ?>  bottom: 50%; left: 0px; 
   <?php
  }else if($btn_pos == 'rightm'){
   ?>  bottom: 50%; right: 0px; 
   <?php
  }
   ?>
}

#cfx_round_btn_<?php echo $form_id; ?> .vx_form_btn{
 
 background-color: <?php echo $bg_color;  ?>;
    text-align: center;
position: absolute;
width: <?php echo $btn_size ?>;
height: <?php echo $btn_size ?>;
border-radius: <?php echo $btn_radius ?>;
background-size: <?php echo $bg_size ?>;
     top: 0;
     <?php
     if(in_array($btn_pos,array('left','leftm'))){
         ?>
     left: 0;   
         <?php
     }else{
         ?> right:0;
         <?php
     } ?>
    z-index: 1050;
    cursor: pointer;
    -webkit-box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    white-space: normal;
    background-image: url(<?php echo $options['btn_src'];  ?>);
    background-repeat: no-repeat;
    background-position: center center;
    border-style: solid;
    border-width: <?php echo $options['screen_border'];  ?>px;
    border-color: <?php echo $options['screen_border_color'];  ?>;
}
#cfx_round_btn_<?php echo $form_id; ?> .vx_form_btn:hover{
    opacity: .7;
}
#cfx_round_btn_<?php echo $form_id; ?> .vx_form_arrow_box {
    position: relative;
    background: <?php echo $bg_color;  ?>;
    padding: 7px 8px;
    color: #fff;
  <?php if( in_array($btn_pos,array('left','leftm')) ){ ?> margin-left: <?php echo $btn_margin; ?>; <?php }
  else{ ?> margin-right: <?php echo $btn_margin; ?>; <?php } ?>

    max-width: 250px;
    line-height: normal;
    font-size: 14px;
}
#cfx_round_btn_<?php echo $form_id; ?> .vx_form_arrow_box:after {
    top: 50%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: rgba(136, 183, 213, 0);
          <?php if(in_array($btn_pos,array('left','leftm'))){ ?>border-right-color: <?php echo $bg_color;  ?>;  right: 100%; <?php }else{ ?> border-left-color: <?php echo $bg_color;  ?>; left: 100%; <?php } ?>
    border-width: 7px;
    margin-top: -7px;
}
.vx_btn_pos_m .vx_form_arrow_box{
    display: none;
}
.vx_btn_pos_m:hover .vx_form_arrow_box{
    display: block;
}
<?php if(!empty($options['overlay_color'])){  ?>
#cfx_popup_<?php echo $form_id; ?>{
  background-color: <?php echo $options['overlay_color'] ?>;  
}
<?php 
} 
if(!empty($options['animation'])){ 
self::animate_css('cfx_'.$options['animation']);
}
if($hide_mscreen){
    ?>
 @media (max-width : 400px) {
 .vx_form_arrow_box{
display: none;
}   
}   
<?php
} ?>
</style>
<?php
}
$btn_w_class='';
if( in_array($btn_pos,array('rightm','leftm')) ){ $btn_w_class='vx_btn_pos_m'; }
if(!empty($options['auto_open'])){
if($options['auto_open'] == 'scroll'){  
$auto_open.=' cfx_auto_open_scroll';     
$meta_par.=' data-scroll="'.$options['scroll_pos'].'"';
}  
if($options['auto_open'] == 'time'){
$delay=!empty($options['scroll_sec']) ? (int)$options['scroll_sec'] : 5;
$auto_open.=' cfx_auto_open_time';     
$meta_par.=' data-time="'.$delay.'"';   
}   }
$animation_class='';
if(!empty($options['animation'])){ $animation_class=' cfx_animated cfx_'.$options['animation']; }
if(!empty($options['box_trigger'])){
    self::$triggers[$form_id]=$options['box_trigger'];
}
$is_html=false;
?>
<div class="vx_form_btn_wrapper <?php echo $btn_w_class.' '.$auto_open ?>" <?php echo $meta_par; ?> id="cfx_round_btn_<?php echo $form_id; ?>" style="<?php echo $css ?>">
<?php
 if(!empty($options['btn_type'])){
 if($options['btn_type'] == 'html'){
     echo $options['box_button'];
     $is_html=true;
 }else{   
?>
<div style="position: relative; display: table-cell; height: <?php echo $btn_size; ?>; vertical-align: middle;">
<?php
if(!empty($options['screen_text'])){    
?>
<div class="vx_form_arrow_box"><?php echo $options['screen_text'];  ?></div>
<?php } ?>
<div class="vx_form_btn vx_form_start_btn" title="Cick to open form"></div>    
</div>   
<?php
 } }
?>
</div>
<?php
$btn_html=ob_get_clean();
  
ob_start();
 if(empty($options['hide_bg'])){ ?>
<div class="cfx_form_overlay" id="cfx_popup_<?php echo $form_id ?>" style="display: none;">
<?php } ?>
<div class="cfx_inline_content<?php echo $animation_class; ?>" id="crm_box_<?php echo $form_id ?>" style="display: none;">
<?php
$close_btn_class='';
if(!empty($options['close_btn'])){ $close_btn_class='_'.$options['close_btn']; 
$close_btn='cfx_close_popup_btn_inner';   }
if($close_btn_class != '_no'){
?>
<img src="<?php echo cfx_form_plugin_url ?>images/close<?php echo $close_btn_class; ?>.png" class="cfx_close_popup_btn_js <?php echo $close_btn ?>" />
<?php } echo $html; ?>
</div>
<?php if(empty($options['hide_bg'])){ ?>
</div>
<?php }
self::$pops.=ob_get_clean(); 
if($is_html){ 
$html=$btn_html;
}else{
    self::$pops.=$btn_html;
$html='';    
}
}

return $html;
}   

    /**
     * Check if IP address is in range
     * @param String $ip , IP Address
     * @param Array $range , IP Addresses Range
     * @return bool ip match result
     */
private function check_ip_range($ip, $range) {
  if (strpos($range, '/') !== false) {
    // $range is in IP/NETMASK format
    list($range, $netmask) = explode('/', $range, 2);
    if (strpos($netmask, '.') !== false) {
      // $netmask is a 255.255.0.0 format
      $netmask = str_replace('*', '0', $netmask);
      $netmask_dec = ip2long($netmask);
      return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
    } else {
      // $netmask is a CIDR size block
      // fix the range argument
      $x = explode('.', $range);
      while(count($x)<4) $x[] = '0';
      list($a,$b,$c,$d) = $x;
      $range = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
      $range_dec = ip2long($range);
      $ip_dec = ip2long($ip);

      # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
      #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

      # Strategy 2 - Use math to create it
      $wildcard_dec = pow(2, (32-$netmask)) - 1;
      $netmask_dec = ~ $wildcard_dec;

      return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
    }
  } else {
    // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
    if (strpos($range, '*') !==false) { // a.b.*.* format
      // Just convert to A-B format by setting * to 0 for A and 255 for B
      $lower = str_replace('*', '0', $range);
      $upper = str_replace('*', '255', $range);
      $range = "$lower-$upper";
    }

    if (strpos($range, '-')!==false) { // A-B format
      list($lower, $upper) = explode('-', $range, 2);
      $lower_dec = (float)sprintf("%u",ip2long($lower));
      $upper_dec = (float)sprintf("%u",ip2long($upper));
      $ip_dec = (float)sprintf("%u",ip2long($ip));
      return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
    }
    return false;
  }

} 

    /**
     * Get Front end form's HTML
     * @param  array $form Form setings
     * @param  array $form_id Form Id
     * @param  string $name Makes Input field names optional
     * @return string Front end form HTML String
     */
public function form_fields_html($form,$values=array(),$no_hidden_fields=false){
$form_id=$form['id'];
$response=self::$response;
    $fields_arr=$form['fields'];  
 
    $options=$form['settings']; 
$fields=array(); $total_fields=0;   $mask_script=false;
  $upload_dir=''; 
if( is_array($fields_arr) && count($fields_arr)>0){
foreach($fields_arr as $k=>$v){ 
      //required
      $v['req']="";
      if(isset($v['required']) && $v['required']=="yes"){
      $v['req']='required="required"';
      }
      //field name
      $v['field_name']='fixed['.$k.']';  
      $v['name']=$k;
      //set default
      if(!isset($v['default'])){ $v['default']=''; }
      if(isset($values[$k])){
      $v['default']=$values[$k];    
      } 
      
      $v['type']=$v['field_type'];
           if(!empty($v['par_name'])){ 
        if(!empty($_REQUEST[$v['par_name']])){
      $v['default']=cfx_form::post($v['par_name']);      
        }else if(!empty($_COOKIE[$v['par_name']])){
     $v['default']=cfx_form::clean_text($_COOKIE[$v['par_name']]);       
        }  
      }
      // if(!isset($v['input_align'])){ $v['input_align']='1'; }
      if(!empty($response['status']) && $response['status'] != 'ok' && isset($_REQUEST['fixed'][$k])){
       $v['default']=cfx_form::clean_text($_REQUEST['fixed'][$k]);
      }
 if(!in_array($v['type'],array('html','hr','hidden','file'))){     $total_fields++; }
  if($v['type'] == 'date'){ self::$date_js=true; } 
  if($v['type'] == 'star'){ self::$star_css=true; } 
  if($v['type'] == 'range'){ self::$range_js=true; } 
  if(!empty($v['mask'])){ $mask_script=true; }   
   if(!isset($v['required'])){
    $v['required']='';
}
$str=cfx_form::field_str($v,$form);

  if($v['type'] == 'captcha'){
        //addng captcha
 //wp_enqueue_script( 'cfx-front-captcha', 'https://www.google.com/recaptcha/api/js/recaptcha_ajax.js' );
      if($v['captcha_type'] == "google"){
        $global_api=cfx_form::get_meta(false);
        self::$captcha_js=!empty($global_api['google_public']) ? $global_api['google_public'] : ''; 
        $str='<input type="hidden" name="g-recaptcha-response" class="vx_google_cap">';
//   wp_enqueue_script( 'cfx-front-captcha' );
      }else{
$str='<div class="vx_custom_captcha"><img src="'.cfx_form_plugin_url.'images/captcha.php'.'" style="border: 0px solid #ccc; display:inline-block; vertical-align: middle;"><span href="#" class="cfx_refresh_cap">Refresh</span></div><input style="width: 100%;" placeholder="Enter Above Text" type="text" class="text captcha_input cfx_input" id="captcha_input" name="captcha" required="required">';
      } 
  }
  
if(empty($str)){ 
$str=apply_filters('crmperks_forms_front_end_field_html',$str,$v,$form); 
}

if(!empty($str)){
 // $v['field_val']=trim($v['field_val']);
  $v['desc']=isset($v['desc']) ?  trim($v['desc']) : '';
  $r_req=$v['required'] == "yes" ? " cfx_valid_msg crm_required":"";
  if( $v['type'] == "radio"){
  $str.='<input type="hidden" data-id="crm_field_'.$v['name'].'" class="crm_radio_checks '.$r_req.'" data-msg="'.esc_attr($v['err_msg']).'"  data-name="'.$v['name'].'">';    
   }
   if( $v['type'] =="checkbox"){
  $str.='<input type="hidden" data-id="crm_field_'.$v['name'].'" class="crm_radio_checks '.$r_req.'" data-msg="'.esc_attr($v['err_msg']).'"  data-name="'.$v['name'].'">';    
   }
   $pos_div='';
   $label_pos=cfx_form::post('label_pos',$v);
   if( in_array($label_pos,array('left','right'))){
     $pos_div=' crm_form_row_70';  
   }
   $str='<div class="crm_input_field'.$pos_div.'">'.$str.'</div>';
  if($v['desc']!=""){
  $str.="<div class='cfx_desc'>".$v['desc']."</div>";
  }  

$star= !empty($v['required']) ? " <span class='cfx_star'>*</span>": "";
$val_div='';
if($v['type'] == 'range'){
$val_div='<span class="cfx_range_val">';
if(!empty($v['default'])){ $val_div.='('.$v['default'].')'; }
$val_div.='</span>';
}

if(!empty($response['msgs'][$k])){ 
$str.='<div class="cfx_alert_block" id="crm_alert_'.$k.'">'.$response['msgs'][$k].'</div>';   
}
    //append label to start of input
if(!in_array($v['type'],array('html','hidden','captcha'))){
 if($label_pos !='hidden' && !in_array($v['type'],array('submit'))){
      if($label_pos == 'left'){
      $str='<label class="cfx_form_label crm_form_row_30">'.$v['label'].$star.$val_div.'</label>'.$str;
   }else if($label_pos == 'right'){
$str.='<label class="cfx_form_label crm_form_row_30">'.$v['label'].$star.$val_div.'</label>';
   }else{
  $str='<label class="cfx_form_label">'.$v['label'].$star.$val_div.'</label>'.$str;     
   }

}
//$str='<div class="crm_form_row">'.$str.'</div>';
}
//if(!in_array($v['type'],array('hidden'))){
$container_class='cfx_input_row '; 
if(!empty($v['con_class'])){
$container_class.=trim($v['con_class']).' ';
}
if(empty($v['row_fields'])){ $v['row_fields']='1'; }

if($v['row_fields'] != 'append_top'){
 $container_class.='crm_form_row_wrap crm_form_row_'.$v['row_fields'].' ';   
}
if($v['type'] == 'submit'){
    $container_class.='cfx_submit_wrap ';
}
$str='<div id="cfx_row_'.$form_id.'_'.$k.'" class="'.trim($container_class).'">'.$str;
$added=false;
if($v['row_fields'] == 'append_top'){
 if(count($fields)>0){
     $added=true;
      $str.="</div>\n";
     $keys=array_keys($fields);
    $arr_key=end($keys); 
    $fields[$arr_key].=$str; 
 } }
//} 

if(!$added){ $fields[$v['order']]=$str; }
} 
}
//close after appending lower field   
foreach($fields as $k=>$v){
    $fields[$k]=$v."</div>\n";
}
$form_html=apply_filters('crmperks_forms_front_end_fields_html',$fields,$form);
if(is_array($form_html)){ $form_html=implode("\n",$form_html); }
}
  $use_cookies='';
  if(!empty($options['use_cookies'])){ $use_cookies='use_cookies'; self::$cookies_js=true; }
  $fun_class=""; 

  if(isset($options['google_events']) && $options['google_events'] == "yes"){
 $fun_class.=" crm_ga"; self::$ga_js=true;
 //wp_enqueue_script( 'cfx-google-tracking');
}
  if($fun_class !=""){
  $form_html.='<input type="hidden" value="'.esc_attr($options['ga_category']).'" class="'.$fun_class.'">';    
  }
  if(trim($form_html) !=""){
  $form_html.='<input type="hidden" name="form_id" class="form_id '.$use_cookies.'" value="'.$form_id.'">
  <input type="hidden"  class="crm_fields" value="'.$total_fields.'">';
  }


  if(self::$date_js){
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('cfx-jquery-ui');
} 
if($mask_script){
wp_enqueue_script('cfx-front-mask');    
} 

return $form_html;
}


    /**
     * Handles the Front end form Submission
     * @param $return string (optional) echo or return response
     * @return null
     */
public function post_form(){  
$form_id=cfx_form::post('form_id');
$return='yes';
if(!empty($_REQUEST['vx_is_ajax'])){
$return='';    
}
//get form setting
$form=cfx_form::get_form($form_id);
$options=$form['settings']; 
$form_parts=array(); $n=1; $form_part=array();
$fields=array(); $options['use_captcha']='';
$entry_id='';

foreach($form['fields'] as $k=>$v){ 
$field_type=$v['field_type']; 
if($v['type'] == 'captcha'){
 $options['use_captcha']='yes';   
 $options['captcha']=$v['captcha_type'];   
}
if($field_type == 'page'){
  $form_parts[$n]=$form_part;  
  $n++;
   $form_part=array();   
}else if(!isset($v['is_layout'])){
$form_part[$k]=$v;
$fields[$k]=$v;
} }
if(!empty($form_part)){ $form_parts['last']=$form_part; }
$global_api= cfx_form::get_meta();

//fixed fields 
$fixed=cfx_form::get_form_post($form['fields']);
//var_dump($fixed,$fields); die();
//if no fields to post then show error message
if($options == null || !isset($_REQUEST['fixed']) || !is_array($_REQUEST['fixed'])){
   $response=array("status"=>"error");
    if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die('');
    }   
}
     //verify captcha
if( !empty($options['use_captcha'] ) ){ 
     if($options['captcha'] == "google"){    
$google=array("response"=>cfx_form::post('g-recaptcha-response'),"remoteip"=>$_SERVER['REMOTE_ADDR'],"secret"=>$global_api['google_private']);
    $path="https://www.google.com/recaptcha/api/siteverify";
    $google_response = "true success";
   $google_response=cfx_form::get_contents_curl($path,"post",$google);
  // var_dump($google_response); 
     if(!preg_match("/true/",$google_response)){
  $response=array("status"=>"error","msg"=>"There was an error trying to send your message. Please try again later.","response"=>$google_response); 
  $response=$this->res_out($response,$fixed,$entry_id,$form);
       if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die();
    } 
} }else{ 
        if(!session_id()){ @session_start(); }
     if($_REQUEST['captcha'] !=$_SESSION['captcha']){
         unset($_SESSION['captcha']);
       $response=array("status"=>"error","msg"=>"Invalid Captcha");  
         $response=$this->res_out($response,$fixed,$entry_id,$form);
            if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die();
    }   
     }
       unset($_SESSION['captcha']);    
} }

  //check form limit, show warning if limit exceeded    
       $cookie_name='cfx_form_'.$form_id;
        if( isset($options['limit']) && $options['limit'] != ''){
     $submit= isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] :0;
     if($options['limit']<=$submit){
    $response=array("status"=>"error","msg"=>"Already Submitted");  
    $response=$this->res_out($response,$fixed,$entry_id,$form);
        if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die();
    }    
     }
   }
   
   if(!empty($_COOKIE['vx_user'])){
 $this->vis_id=cfx_form::clean_text($_COOKIE['vx_user']);   
}

   
$submit=cfx_form::post('submit');
$previous=cfx_form::post('previous');

if(!empty($previous)){ $submit=$previous; }

$valid_fields=$fields; $current_step='';
if(!empty($submit) && is_numeric($submit)){
$valid_fields=$form_parts[$submit];  
self::$response['step']=$current_step=$submit;
} 
//var_dump($valid_fields); die();
//$valid_fields=apply_filters('crmperks_forms_validation_fields',$fields,$form);
   /////php form validation
  $err_msgs=self::validate_form($fixed,$valid_fields,$form);  
  $err_msgs=apply_filters('crmperks_forms_error_msgs',$err_msgs,$valid_fields,$fixed,$form); 
  $err_msg='Please fix above errors first';
  self::$err_msgs=$err_msgs; 
    if(!empty($err_msgs)){
        if(!empty($err_msgs['error']) ){ $err_msg=$err_msgs['error']; unset($err_msgs['error']); }
        $response=array("status"=>"error","msg"=>$err_msg,'msgs'=>$err_msgs);
     $response=$this->res_out($response,$fixed,$entry_id,$form);
            if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die();
    }
       
}
        
$fixed=self::handle_uploaded_files($fixed,$fields); 
 

  if(is_array($fixed)){
   //   $fixed=array_filter($fixed);
  }
  
  if(empty($fixed)){
    $err_msg='Empty Form';  
  }
   if(!empty(self::$err_msgs) ){
   $response=array("status"=>"error","msg"=>$err_msg,'msgs'=>self::$err_msgs); 
$response=$this->res_out($response,$fixed,$entry_id,$form);
            if($return == "yes"){
   return $response;     
    }else{ 
    echo json_encode($response);
      die();
    }
}
//do not process , if previous or next step
if(!empty(self::$response['step']) && is_numeric(self::$response['step'])){
  if(empty($previous)){  self::$response['step']+=1; }
    $response=array('next_step'=>'true','status'=>'next_step');
 $response=$this->res_out($response,$fixed,$entry_id,$form);
if($return == "yes"){ return $response;     
}else{ 
    echo json_encode($response); die(); }   
}


if(is_array($fixed) && count($fixed)>0){
     foreach($fixed as $k=>$v){
       if(!empty($v) && isset( $fields[$k]['type']) ){ ////get last email from form
if($fields[$k]['type'] == "email"){
       $this->email=$v;
}elseif($fields[$k]['type'] == "tel"){
       $this->phone=$v;
}else if($fields[$k]['type'] == 'f_name'){
  $this->f_name=$v;  
}else if($fields[$k]['type'] == 'l_name'){
  $this->l_name=$v;  
}
if($fields[$k]['type'] == 'l_name' && empty($this->l_name)){
  $name_arr=array_map('trim',explode(' ',$this->l_name));
 if(!empty($name_arr[0])){
 $this->f_name=$name_arr[0];    
 }
  if(!empty($name_arr[1])){
 $this->l_name=$name_arr[1];    
 }   
}
  }    
     }
}
$cookies=$this->get_form_meta($form);

//check , if abadoned entry of this form is already in db
//$entry_id=$this->get_vis_info_of_day($this->vis_id,$form_id,'1'); 
//save entry in database
//$entry_id=$this->save_entry($fixed,$entry_id,$form_id,$cookies,'0');
//if(empty($options['disable_db'])){
$entry_id=apply_filters('crmperks_forms_new_submission',self::$entry_id,$fixed,$form); 
//}   
$submit= isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] :0;
    if(empty($global_api['cookies'])){
setcookie($cookie_name,$submit+=1,time()+(3600*24),"/");
    }
//fix file path
$fixed['form_id']=$form['id'];
$fixed['form_name']=$form['name'];

$fixed_labels=array();
foreach($fixed as $field=>$field_val){
if(isset($fields[$field]['type']) ){
$field_type=$fields[$field]['type'];

if($field_type == 'file' ){
          if(filter_var($field_val,FILTER_VALIDATE_URL) === false){
  $field_val=self::$upload_dir.$field_val;     
    } 
     if(filter_var($field_val,FILTER_VALIDATE_URL)){
          $file_arr=explode('/',$field_val);
    $file_name=$file_arr[count($file_arr)-1];
$fixed[$field]=$field_val="<div><a href='$field_val' target='_blank'>".$file_name."</a></div>";
     }
}else if(!empty($fields[$field]['options'])){
    $temp_arr=$field_val;
   if(!is_array($field_val)){ $temp_arr=array($field_val); }
   $field_val=array();
    foreach($temp_arr as $option_val){
     if(!empty($fields[$field]['options'][$option_val]['label'])){
  $field_val[]=$fields[$field]['options'][$option_val]['label'];       
     }else{
  $field_val[]=$option_val;       
     } }  
} 

if(isset($fields[$field]['label'])){
if(is_array($field_val)){ $field_val=implode(',',$field_val); }
$fixed_labels[$fields[$field]['label']]=$field_val;   
}
if(is_string($field_val)){
$options['thanks_msg']=str_replace("%".$field."%",$field_val,$options['thanks_msg']);
} }

}

//posting form to webhook
   if(!empty($options['hook']) ){
//  cfx_form::get_contents_curl($options['hook'],"post",$fixed);     
   }  
 //update form entries counter  
 global $wpdb; 
    $table_name = cfx_form::table_name('forms');
    $sql="update $table_name set entries=entries+1 where id='$form_id' limit 1";
  $wpdb->query($sql);
$notify=$form['notify'];
$wp_email=get_bloginfo('admin_email');
  //email alerts
  $email_headers = array('Content-type'=>'Content-Type: text/html; charset=UTF-8'); //get_option('blog_charset')

   

      if(!empty($notify['use_alert'] )){
          $emails=array();
  if(!empty($notify['alert_emails'])){  $emails=explode("\n",$notify['alert_emails']); }
if(!empty($global_api['alert_emails'])){ $emails=array_merge($emails,explode("\n",$global_api['alert_emails'])); }
    if(!empty($emails)){
   $emails=array_unique($emails);      
      $user_info=array("info"=>$cookies,"info_title"=>"Visitor Information","fields"=>$fixed_labels,"fields_title"=>"Lead Information","form_name"=>$form['name']." (Id=".$form['id'].")");
if(class_exists('vxcf_form') && !empty($entry_id) && is_numeric($entry_id)){
   $user_info["wp_link"]=admin_url('admin.php?page=vxcf_leads&tab=entries&id='.$entry_id);
}
    $user_info['url']=$cookies['url'];

    if(empty($notify['admin_email_type'])){
$email_body=self::admin_email_body($user_info,true);
    }else{
        $email_body=self::process_tags($notify['alerta_body'],$fixed,$form,$wp_email);
    }
  $email_body=apply_filters(cfx_form::$id.'_admin_email_notification',$email_body,$fixed,$form);
$email_body=nl2br($email_body);
  $admin_headers=$email_headers;
  if(!empty($notify['alerta_from'])){
  $from_name='Wordpress'; if(!empty($notify['alerta_name'])){ $from_name=self::process_tags($notify['alerta_name'],$fixed,$form,$wp_email); }
 $from_email=self::process_tags($notify['alerta_from'],$fixed,$form,$wp_email);
  $admin_headers['From'] = 'From: "' . wp_strip_all_tags($from_name) . '" <' . $from_email . '>';
  }
   if(!empty($notify['alerta_reply'])){
 $admin_headers['Reply-To'] = 'Reply-To: '.self::process_tags($notify['alerta_reply'],$fixed,$form,$wp_email);  
 }
 if(empty($notify['alerta_subject'])){ $notify['alerta_subject']=$form['name'].' submitted'; }
 
 $admin_email_subject=self::process_tags($notify['alerta_subject'],$fixed,$form,$wp_email);
//$headers = "MIME-Version: 1.0" . "\r\n";
      foreach($emails as $email){ 
      $email=str_replace('{admin_email}',$wp_email,$email);        
    wp_mail(trim($email), $admin_email_subject,$email_body,$admin_headers);
      }
    } }
    
 if(!empty($notify['use_alertc']) && !empty($notify['alertc_email']) && !empty($fixed[$notify['alertc_email']])){
     $user_email=$fixed[$notify['alertc_email']];
 if(!empty($notify['alertc_reply'])){
 $email_headers['Reply-To'] = 'Reply-To: '.self::process_tags($notify['alertc_reply'],$fixed,$form,$wp_email);  
 } 

 if(!empty($notify['alertc_bcc'])){
  $email_headers['Bcc']='Bcc: '.self::process_tags($notify['alertc_bcc'],$fixed,$form,$wp_email);  
 }
   if(!empty($notify['alertc_from'])){ 
  $from_name='Wordpress'; if(!empty($notify['alertc_name'])){ $from_name=self::process_tags($notify['alertc_name'],$fixed,$form,$wp_email); }
 $from_email=self::process_tags($notify['alertc_from'],$fixed,$form,$wp_email);
  $email_headers['From'] = 'From: "' . wp_strip_all_tags($from_name) . '" <' . $from_email . '>';
 // $email_headers['From'] = 'From: ' . $from_email;
  }

 //$email_headers=array_values($email_headers);
 // var_dump($email_headers);
 $user_email=str_replace('{admin_email}',$wp_email,$user_email);
$user_body=self::process_tags($notify['alertc_body'],$fixed,$form);
$user_body=nl2br($user_body);
 $user_email_msg=apply_filters(cfx_form::$id.'_user_email_notification',$user_body,$fixed,$form);
 $user_email_msg=self::user_email_body($user_email_msg);
 $user_email_subject=self::process_tags($notify['alertc_subject'],$fixed,$form,$wp_email);
 wp_mail(trim($user_email),$user_email_subject,$user_email_msg,$email_headers);      
 } 
// var_dump($user_email,$user_email_msg); die();
$url='';
$response=array("status"=>"ok");
if(empty($options['msg_type'])){
 $thanks_msg=!empty($options['thanks_msg']) ? self::process_tags($options['thanks_msg'],$fixed,$form) : 'Submitted Successully';  
 
 if(!empty($options['hide_form'])){   
$response['hide_form']='yes';
if($options['hide_form'] == 'keep'){
  $response['hide_form']='keep_form';  
}
 }
$response['msg']=$thanks_msg;

}else if( $options['msg_type'] == 'close_popup' ){
  $response['close_popup']='true';   
 }else{
       if($options['msg_type'] =='url'){
     $url=trim($options['thanks_page']);   
    }else if($options['msg_type'] == 'page'){
     $url=get_permalink($options['thanks_page_wp']);   
    }
  if(!empty($url)){
$response['thanks_page']=$url;      
  }   
} 
 if(!empty($options['code'])){
 if(strpos($options['code'],'</script>')=== false){
     $response['code']='<script type="text/javascript">'.$options['code'].'</script>';
 }else{
     $response['code']=$options['code'];     
 }
}
$response=$this->res_out($response,$fixed,$entry_id,$form);

if($return == "yes"){

 if(!empty($response['thanks_page'])){
  wp_redirect($response['thanks_page']);
  exit;   
 }   
return $response;    
}else{
    
    echo json_encode($response);
die();
} 
}
public function res_out($response,$fixed,$entry_id,$form){
  $response=apply_filters('crmperks_forms_form_submission_response',$response,$fixed,$entry_id,$form);
$response=array_merge($response,self::$response); 
return $response;  
}
    /**
     * Formates User Informations and submitted form to string
     * This string is sent to email 
     * @param  array $info User informations 
     * @param  bool $is_html If HTML needed or not 
     * @return string formated string
     */
public static function admin_email_body($info,$is_html=false){
$str="";
    if(isset($info['fields']) && is_array($info['fields'])){
      if($is_html){
            if(isset($info['fields_title'])){
                  $str.='<tr><td style="font-family: Helvetica, Arial, sans-serif;background-color: #3A9FD1; height: 36px; color: #fff; font-size: 24px; padding: 0px 10px;">'.$info['fields_title'].'</td></tr>'."\n";
              }
        if(is_array($info['fields']) && count($info['fields'])>0){
        $str.='<tr><td style="padding: 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%;" ><tbody>';      
      foreach($info['fields'] as $f_k=>$f_val){
          $f_val=is_array($f_val) ? implode(", ",$f_val) : $f_val;
  $str.='<tr><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;">'.$f_k.'</td><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;">'.$f_val.'</td></tr>'."\n";      
    }
  $str.="</table></td></tr>";             
        }            
      }else{
          if(isset($info['fields_title']))
    $str.=$info['fields_title']."\n";    
    foreach($info['fields'] as $f_k=>$f_val){
  $str.=$f_k." : ".$f_val."\n";      
    }
      }
} 
    if(isset($info['info']) && is_array($info['info'])){
           if($is_html){
              if(isset($info['info_title'])){
                  $str.='<tr><td style="font-family: Helvetica, Arial, sans-serif;background-color: #3A9FD1; height: 36px; color: #fff; font-size: 24px; padding: 0px 10px">'.$info['info_title'].'</td></tr>'."\n";
              }
        if(is_array($info['info']) && count($info['info'])>0){
        $str.='<tr><td style="padding: 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%;"><tbody>';      
      foreach($info['info'] as $f_k=>$f_val){
  $str.='<tr><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;">'.ucfirst($f_k).'</td><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;">'.$f_val.'</td></tr>'."\n";      
    }
  $str.="</table></td></tr>";             
        }
      }else{
          if(isset($info['info_title']))
    $str.="\n".$info['info_title']."\n";    
    foreach($info['info'] as $f_k=>$f_val){
  $str.=$f_k." : ".$f_val."\n";      
    }
      }
}
if($is_html){
ob_start();
include_once(cfx_form_plugin_dir.'templates/admin.phtml');
$file= ob_get_contents(); // data is now in here
ob_end_clean();
$wp_link="";
if(isset($info['wp_link']) && $info['wp_link']!="")
{
$wp_link='<td align="left" valign="top" style="padding-bottom:20px; width: 50%"><table border="0" cellpadding="10" cellspacing="0" class="rssButton" style="-moz-border-radius: 5px;-webkitborder-radius: 5px;background-color: #59b329;border-radius: 5px;color: #FFFFFF;font-family: Helvetica;font-size: 18px;font-weight: bold;line-height: 40px;text-decoration: none; width: 90%; margin-left: 30px; "> <tbody><tr> <td align="center" valign="middle"> <a href="'.$info['wp_link'].'" target="_blank" style="color: #FFFFFF;font-family: Helvetica;font-size: 18px;font-weight: bold;line-height: 40px;text-decoration: none;">View in Wordpress</a> </td> </tr> </tbody></table></td>';    
}
$str=str_replace(array("{sf_form_name}","{sf_time_now}","{sf_page_url}","{sf_contents}","{sf_link}","{sf_wp_link}"),array($info['form_name'],date('d/M/y H:i:s',current_time('timestamp')),$info['url'],$str,'',$wp_link),$file);
}
return $str;   
}
public static function process_tags($body,$fields,$form='',$admin_email=''){
    if(!empty($form)){
        $fields['form_id']=$form['id'];
        $fields['form_name']=$form['name'];
    }
    if( !empty($admin_email) ){
        $fields['admin_email']=$admin_email;
    }
foreach($fields as $field=>$field_val){
$field_val=is_array($field_val) ? implode(", ",$field_val) : $field_val;
$body=str_replace('{'.$field.'}',$field_val,$body);
}
return $body;    
}
public static function user_email_body($body){
ob_start();
include_once(cfx_form_plugin_dir.'templates/user.phtml');
$file= ob_get_contents(); // data is now in here
ob_end_clean();
$str=str_replace(array("{{body}}"),array($body),$file);
return $str;     
}
  
public static function handle_uploaded_files($fixed,$fields){
$allowed_files=array('png','jpg','jpeg','gif','txt','pdf','csv','docx','xlsx','pptx');
if(!empty($_FILES['fixed']['tmp_name'])){
$err_msgs=array();
$base_folder=cfx_form::get_upload_folder();
$upload_dir=wp_upload_dir();
self::$upload_dir=$upload_dir['baseurl'].'/'.$base_folder.'/';
$time = current_time( 'mysql' );
$y= substr( $time, 0, 4 );
$m= substr( $time, 5, 2 );
           
$folder=$y.'/'.$m;
$upload_path=$upload_dir['basedir'].'/'.$base_folder.'/'.$folder;
$dir=wp_mkdir_p($upload_path);        

  foreach($_FILES['fixed']['tmp_name'] as $k=>$file){
      if(empty($file)){continue;}
 //
 $name=!empty($_FILES['fixed']['name'][$k]) ? $_FILES['fixed']['name'][$k] : '';
 if(!empty($fields[$k]['exts'])){
   $allowed_files=array_filter(array_map('trim',explode(',',$fields[$k]['exts'])) );  
 }
 $ext=substr($name,strrpos($name,'.')+1);
if(!in_array($ext,$allowed_files)){
 $err_msgs[$k]=!empty($fields[$k]['valid_err_msg']) ? $fields[$k]['valid_err_msg'] : 'File Type Not Allowed';   
continue;
}
//valid file
 $file_name=sanitize_file_name($name);
 $file_name = wp_unique_filename( $upload_path, $file_name );
 $dest=$upload_path.'/'.$file_name;
 if(@move_uploaded_file($file,$dest) === false){
$err_msgs[$k]=!empty($fields[$k]['valid_err_msg']) ? $fields[$k]['valid_err_msg'] : 'File Upload Failed';      
 }else{
 chmod($dest, 0644);
 $fixed[$k]=$folder.'/'.$file_name;    
 }
//                
  }
self::$err_msgs=$err_msgs;  
}
return $fixed;
}

    /**
     * PHP form validation
     */
public static function validate_form($fixed,$fields_arr,$form_id='',$entry_id=''){ 
       /////php form validation
    $msgs=array();

  if(is_array($fields_arr) && count($fields_arr)>0){
      foreach($fields_arr as $f_key=>$field){
          $err_msg=$field_type="";
$f_val=isset($fixed[$f_key]) ? $fixed[$f_key] : '';
if(isset($field['type'])){ 
$field_type=$field['type'];   
   switch($field['type']){
       case"email": if(!empty($f_val)){
       if(function_exists('filter_var')){
      if(!filter_var($f_val, FILTER_VALIDATE_EMAIL)){
      $err_msg="Email Address is not Valid";  
  
      }
       }else{
       if(strpos($f_val,"@")<0){
      $err_msg="Email Address is not Valid";       
       }    
       } }
       break;
       case"number":
      if($f_val !== '' && !is_numeric($f_val)){
      $err_msg=$field['label']." is not Valid";    
      }
       break;
      case"url": if(!empty($f_val)){
               if(function_exists('filter_var')){
       if(!filter_var($f_val, FILTER_VALIDATE_URL)){
      $err_msg=$field['label']." is not Valid";    
      }
               }else{
                 if(strpos($f_val,"://")<0){
          $err_msg=$field['id']." is not Valid";     
       }        } }
       break;
   } 
         }
         
if(!empty($field['required'])){ 
    if(in_array($field_type,array('file'))){
        if(empty($_FILES['fixed']['tmp_name'][$f_key])){
      $err_msg='Please Select File';  
        }
    }else if($f_val == ''){
    $err_msg="This field is required"; 
    }
}           
if(!empty($err_msg) && !empty($field['err_msg'])){
        $err_msg=$field['err_msg']; 
}  

$err_msg=apply_filters('crmperks_forms_field_validation_message',$err_msg,$f_val,$field,$form_id,$fixed);
if(!empty($err_msg)){
     $msgs[$f_key]=$err_msg;
} 
      
  }}
  return $msgs; 
}
    /**
     * verify all fields submitted by user
     * Adds meta fields
     * Adds empty fields
     * @param  array $fields object fields array
     * @param  array $bro_info browser info array
     * @param  array $ip_loc IP to Location info array
     * @return array meta fields
     */
public function get_form_meta($form){

$cookies=array();
/*    
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$this->ip=$ip;
$this->bro_info=$bro_info=$this->browser_info();
 $resolution="";
if(isset($_POST['vx_width'])){
$width=cfx_form::post('vx_width');
$height=cfx_form::post('vx_height');
 $resolution=$width." x ".$height;
$cookies['screen']=$resolution;
}

$cookies['browser']=$bro_info['name'];
$cookies['os']=$bro_info['platform'];
$cookies['ip']=$ip;
//var_dump($cookies); die();
 
*/
//get page url
$page_url="//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_REQUEST['vx_url'])){
$page_url=cfx_form::post('vx_url');
}
$page_url=substr($page_url,0,250);
$cookies['url']=$page_url; 


return $cookies;   
}

    /**
     * Parse User Agent to get Browser and OS
     * @param  string $u_agent (optional) User Agent
     * @return array Browser Informations
     */
public static function browser_info($u_agent=""){ 
    $bname = '0';
    $platform = '0';
    $version= "";
if($u_agent == "")
$u_agent=$_SERVER['HTTP_USER_AGENT'];
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
    ////further refine platform
     if (preg_match('/iphone/i', $u_agent)) {
                $platform    =   "iPhone";
            } else if (preg_match('/android/i', $u_agent)) {
                $platform    =   "Android";
            } else if (preg_match('/blackberry/i', $u_agent)) {
                $platform    =   "BlackBerry";
            } else if (preg_match('/webos/i', $u_agent)) {
                $platform    =   "Mobile";
            } else if (preg_match('/ipod/i', $u_agent)) {
                $platform    =   "iPod";
            } else if (preg_match('/ipad/i', $u_agent)) {
                $platform    =   "iPad";
            }
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
      elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    }
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    }  
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }  
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }  
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}  
    return array(
        'userAgent' => $u_agent,
        'full_name'      => $bname,
        'name'      => $ub,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}  
 
public function verify_header(){
if(isset($_POST['cfx_form_action']) && $_POST['cfx_form_action'] =='post_cfx_form' && !is_admin()){
$this->post_form();    
}
}
public function vx_id(){
      $vx_id='';
 if(!empty($_COOKIE['vx_user'])){
     $vx_id=$_COOKIE['vx_user'];
 }else{
     $vx_id=uniqid().time().rand(9,99999999);
   $_COOKIE['vx_user']=$vx_id;  
 setcookie('vx_user', $vx_id, time()+25920000, '/');   
 }

 return $vx_id;
}

 


 
}

}

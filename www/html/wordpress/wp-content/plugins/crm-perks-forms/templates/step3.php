<?php 
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }   ?>
    <form method="post"  class="crm_form" novalidate>
    <div class="steps step3">
            <div class="crm-panel-field">
              <label >
                <input type="checkbox"  autocomplete="off" class="switches" data-rel="light_div" name="vx_config[use_box]" value="yes" <?php if( !empty($options['use_box']) ){echo "checked='checked'";}?>>
                Open form in Popup</label>
            </div>
            <div id="light_div" style="<?php if( empty($options['use_box']) ){echo "display:none";} ?>">
                  <div class="crm-panel-field">
                <label class="crm_text_label">Button Type</label>
<select name="vx_config[btn_type]" id="vx_btn_type"><?php 
$btn_type=cfx_form::post('btn_type',$options);
echo cfx_form::select_options($btn_types,$btn_type);
?>
</select>
              </div>
          <div id="vx_btn_div_html" style="<?php if( $btn_type!='html' ){echo "display:none";} ?>">    
              <div class="crm-panel-field">
                <label class="crm_text_label">Popup Button HTML</label>
                <div class="crm-panel-description">You can override HTML here.</div>
                
                <textarea name="vx_html[box_button]" placeholder="Lightbox Button HTML eg.<button>Contact Us</button>" style="width: 100%; height: 60px;"><?php  echo !empty($options['box_button']) ? htmlentities($options['box_button']) : '';?>
</textarea>
              </div>
              </div>
              <div id="vx_btn_div_fixed" style="<?php if( in_array($btn_type, array('html','')) ){echo "display:none";} ?>">
                   <div class="crm-panel-field">
                <label class="crm_text_label">Button Text</label>
<input name="vx_config[screen_text]" type="text" id="vx_title-on"  class="vx_input" value="<?php echo cfx_form::post('screen_text',$options); ?>">
              </div>
              <?php
                  if(!isset($options['screen_color_hex']) && isset($options['screen_color'])){
                      $options['screen_color_hex']=$options['screen_color'];
                  }
              ?>
                  <div class="crm-panel-field">
                <label class="crm_text_label">Button Background Color</label>
<input type="text" name="vx_config[screen_color_hex]" id="vx_color-on"  autocomplete="off" class="crm_color_picker vx_color" value="<?php echo cfx_form::post('screen_color_hex',$options) ?>" data-rel="cfx_float_btn_bg" data-opacity="<?php echo !isset($options['screen_color_op']) ? "1" : $options['screen_color_op']; ?>" style="width: 100%;">

    <input type="hidden" name="vx_config[screen_color_op]" class="cfx_float_btn_bg_op" value="<?php echo cfx_form::post('screen_color_op',$options); ?>">
       <input type="hidden" name="vx_config[screen_color]" class="cfx_float_btn_bg_rgba" value="<?php echo cfx_form::post('screen_color',$options); ?>">
       
              </div>
                  <div class="crm-panel-field">
                <label class="crm_text_label">Border Width</label>
<input type="number" name="vx_config[screen_border]" id="vx_border-on" class="vx_input" min="0" max="5" value="<?php echo cfx_form::post('screen_border',$options) ?>" style="width: 100%;">
              </div>
                  <div class="crm-panel-field">
                <label class="crm_text_label">Button Border Color</label>
<input type="text" name="vx_config[screen_border_color]" id="vx_border_color-on"   autocomplete="off" class="crm_color_picker_n vx_color" value="<?php echo cfx_form::post('screen_border_color',$options) ?>" style="width: 100%;">
              </div>
                      <div class="crm-panel-field">
                <label class="crm_text_label">Button Icon</label>
                <div>
<ul class="vx_images_ul" data-id="on">
  <?php
  $icon=1;
  if(!empty($options['screen_icon'])){
  $icon=$options['screen_icon'];    
  }
    for($i=1; $i<31 ; $i++){
?><li <?php if($icon == $i){echo 'class="vx_images_li_sel"';} ?> data-id="<?php echo $i; ?>"><img id="vx_icon_on_<?php echo $i ?>" src="<?php echo cfx_form_plugin_url.'images/icons/chat'.$i.'.png' ?>"></li>
<?php        
    }
?>
</ul>
<input type="hidden" name="vx_config[screen_icon]" id="loc_btn_icon" value="<?php echo $icon; ?>"><div style="clear: both;"></div> </div>
              </div>
           
               </div>  
                 <div class="crm-panel-field">
                <label class="crm_text_label">Popup Trigger</label>
                <div class="crm-panel-description">Open Popup on clicking an element.Specify comma separated jQuery Selectors for elements. </div>
                
                <input type="text" name="vx_config[box_trigger]" placeholder="jQuery selectors e.g #btn" value="<?php  echo !empty($options['box_trigger']) ? htmlentities($options['box_trigger']) : '';?>" >
              </div>

              <div class="crm-panel-field">
                <label>Close Popup Button</label>
                   <select name="vx_config[close_btn]"  class="text"  autocomplete="off">
              <?php
$btns=array(''=>__('Black Round Button','crmperks-forms'),'white'=>__('White Button','crmperks-forms'),'black'=>__('Black Button','crmperks-forms'),'no'=>__('No Button','crmperks-forms'));
               $box_pos_s=cfx_form::post('close_btn',$options);
               echo cfx_form::select_options($btns,$box_pos_s);   
              ?>
              </select>
              </div>
              
              <div class="crm-panel-field">
                <label >
                  <input type="checkbox" class="switches"  autocomplete="off"  name="vx_config[hide_bg]" data-rel="pop_positions" value="yes" <?php if(!empty($options['hide_bg']) ){echo "checked='checked'";}?>>Hide Background overlay</label>
              </div>
              <div id="pop_positions" style="<?php if( empty($options['hide_bg']) ){echo "display:none";} ?>">
                   <div class="crm-panel-field">
              <label class="crm_text_label">Popup Position</label>
              <div class="crm-panel-description">Choose Popup Position.</div>
              <select name="vx_config[lightbox_pos]"  class="text lightbox_pos"  autocomplete="off">
              <?php
               $box_pos_s=cfx_form::post('lightbox_pos',$options);
               echo cfx_form::select_options($lightbox_positions,$box_pos_s);   
              ?>
              </select>
            </div>
              </div>
         
         <div class="crm-panel-field">
              <label class="crm_text_label">Popup Animation</label>
              <select name="vx_config[animation]"  class="text"  autocomplete="off">
              <?php
                 $animations=array(''=>'No effect','bounce'=>'Bounce','shake'=>'Shake','bounceIn'=>'Bounce In','bounceInLeft'=>'Bounce In Left','bounceInRight'=>'Bounce In Right','bounceInUp'=>'Bounce In Up','bounceInDown'=>'Bounce In Down','fadeIn'=>'Fade In','zoomIn'=>'Zoom In');
                 $animation=cfx_form::post('animation',$options);
                  echo cfx_form::select_options($animations,$animation);
              ?>
              </select>
            </div>
                 
                  <div class="crm-panel-field">
                <label >
                  <input type="checkbox"  autocomplete="off"  name="vx_config[close_box]" value="yes" <?php if( !empty($options['close_box']) ){echo "checked='checked'";}?>>
                  Close Popup on clicking Background Overlay or pressing ESC button</label>
              </div>
              
            
           <div class="crm-panel-field">
              <label class="crm_text_label">Auto Open Popup</label>
              <div class="crm-panel-description">You can auto open Popup on page load or when visitor's mouse leaves active browser window.</div>
              <select name="vx_config[auto_open]" id="vx_auto_open_sel"  class="text"  autocomplete="off">
              <?php
                 $auto_open_s=cfx_form::post('auto_open',$options);
                  echo cfx_form::select_options($auto_open,$auto_open_s);
              ?>
              </select>
            </div>
          
              <div class="crm-panel-field vx_auto_open" id="vx_auto_open_scroll" style="<?php if($auto_open_s != 'scroll'){ echo 'display:none;'; }   ?>">
              <label class="crm_text_label">Scroll Position</label>
              <div class="crm-panel-description">% scroll to bottom.</div>
              <select name="vx_config[scroll_pos]"  class="text">
               <?php
$scrol_pos_db=cfx_form::post('scroll_pos',$options);
echo cfx_form::select_options($scrol_pos,$scrol_pos_db);
              ?>
              </select> 
            </div>  
            
            <div class="crm-panel-field vx_auto_open" id="vx_auto_open_time" style="<?php if($auto_open_s != 'time'){ echo 'display:none;'; }   ?>">
              <label class="crm_text_label">Seconds</label>
              <div class="crm-panel-description">show after 5 seconds.</div>
            <input type="text" name="vx_config[scroll_sec]" placeholder="5"  class="text" value="<?php echo cfx_form::post('scroll_sec',$options); ?>"  autocomplete="off">
            </div>
            
            
             
         
           <div class="crm-panel-field">
                <label class="crm_text_label">Color of Popup Overlay</label>
<input type="text" name="vx_config[overlay_color_hex]"  autocomplete="off" class="crm_color_picker" value="<?php echo cfx_form::post('overlay_color_hex',$options) ?>" data-rel="cfx_float_overlay" data-opacity="<?php echo !isset($options['overlay_color_op']) ? "1" : $options['overlay_color_op']; ?>" style="width: 100%;">

    <input type="hidden" name="vx_config[overlay_color_op]" class="cfx_float_overlay_op" value="<?php echo cfx_form::post('overlay_color_op',$options); ?>">
       <input type="hidden" name="vx_config[overlay_color]" class="cfx_float_overlay_rgba" value="<?php echo cfx_form::post('overlay_color',$options); ?>">
       
              </div>
           
            </div>
            <div class="crm-panel-field">
              <label >
                <input type="checkbox"   name="vx_config[use_cookies]" value="yes" <?php if( !empty($options['use_cookies']) ){echo "checked='checked'";}?> autocomplete="off">
                Store form data in cookies if form not submitted</label>
            </div>
           
              
<?php $steps_form=apply_filters('crmperks_forms_ajax_option',false, $form);
if(!$steps_form){ ?>            
            <div class="crm-panel-field">
                <label >
                  <input type="checkbox"  autocomplete="off"  name="vx_config[browser_validation]" value="yes" <?php if( !empty($options['browser_validation']) ){echo "checked='checked'";}?>>
                  Do Not Use Browser's own Form Validation feature</label>
              </div>
              <div class="crm-panel-field">
                <label >
                  <input type="checkbox"  autocomplete="off"  name="vx_config[disable_ajax]" value="yes" <?php if(  !empty($options['disable_ajax'] )){echo "checked='checked'";}?>>
                  Disable Ajax Form Submission</label>
              </div>
<?php } ?>          
 
            <div class="crm-panel-field">
              <label >
                <input type="checkbox" class="switches" data-rel="events_div"   name="vx_config[google_events]" value="yes" <?php if( !empty( $options['google_events'] ) ){echo 'checked="checked"';} if(!cfx_form::$is_pr){ echo 'disabled="disabled"';} ?> autocomplete="off">
                Enable Google Analytics Events <?php if(!cfx_form::$is_pr){ echo '(Premium)';} ?> 
                </label>
            </div>
            <div id="events_div" style="<?php if( empty($options['google_events'] )){echo "display:none";} ?>">
                    <div class="crm-panel-field">
              <label class="crm_text_label">Events Category Name</label>
              <div class="crm-panel-description">Events category name form GA. We support all Classic (ga.js), Universal (analytics.js) and tag manger (gtag/js).How to configure tags in GTM.</div>
              
              <input type="text" name="vx_config[ga_category]" placeholder="example-category" class="text" value="<?php echo cfx_form::post('ga_category',$options)?>" />
            </div>
</div>
   
   <div class="crm-panel-field">
              <label >
                <input type="checkbox" class="switches" data-rel="logged_in_msg"  name="vx_config[logged_in]" value="yes" <?php if( !empty($options['logged_in'] )){echo "checked='checked'";}?> autocomplete="off">
                Display Form to Logged in Users Only</label>
            </div>
      <div id="logged_in_msg" style="<?php if(empty($options['logged_in']) ){echo "display:none";} ?>">
     
      <div class="crm-panel-field">
              <label class="crm_text_label">Require Login Message</label>
     <div class="crm-panel-description">Enter a message to be displayed to users who are not logged in</div>
              
   <textarea name="vx_config[login_msg]" class="text"><?php echo cfx_form::post('login_msg',$options)?></textarea>
            </div>
            
      </div>
            
  
<?php  
do_action('crmperks_forms_step3_html',$options,$form);
 ?>
            <div class="crm-panel-field">
              <label class="crm_text_label">After Form Submission</label>
<div class="crm-panel-description">Do this after successfully submitting a form</div>
           <select name="vx_config[hide_form]"  class="text">
               <?php
          $hide_ops=array(''=>'Reset Form','keep'=>'Do nothing','yes'=>'Hide Form');     
$scrol_pos_db=cfx_form::post('hide_form',$options);
echo cfx_form::select_options($hide_ops,$scrol_pos_db);
              ?>
              </select> 
            </div> 
            <div class="crm-panel-field">
              <label class="crm_text_label">Submit Button Processing Text</label>
<div class="crm-panel-description">On clicking submit button , this text will be displayed in button</div>
              <input type="text" name="vx_config[process_text]" placeholder="" class="text" value="<?php echo cfx_form::post('process_text',$options)?>" />
            </div> 
            <div class="crm-panel-field">
              <label class="crm_text_label">Block from IPs</label>
              <div class="crm-panel-description">Enter new line separated IP addresses or IP ranges which you want to block. <a href="javascript:void(0);" onclick="sf_colorbox('IP Ranges Instructions','#sf_ip_block_help');">Help</a></div>
              
              <textarea name="vx_config[block]" placeholder="" style="width: 100%; height: 90px;"><?php echo cfx_form::post('block',$options)?></textarea>
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Blocked IP Message</label>
              <div class="crm-panel-description">Message to show if IP address is blocked.</div>
              <?php
        $content = cfx_form::post('ip_msg',$options);
  $editor_id = 'vx_config_ip_msg';
  $settings = array("textarea_name"=>"vx_html[ip_msg]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20);
  wp_editor($content,$editor_id,$settings);
      ?>
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Limit Number of Submission</label>
              <div class="crm-panel-description">How many times a user can submit form. To enable unique submission enter 1</div>
              
              <input type="text" name="vx_config[limit]" placeholder="" class="text" value="<?php echo cfx_form::post('limit',$options)?>" />
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Submission Limit Over Message</label>
              <div class="crm-panel-description">Message to show when submission limit is over.</div>
              <?php
        $content = cfx_form::post('limit_msg',$options);
  $editor_id = 'vx_config_limit_msg';
  $settings = array("textarea_name"=>"vx_html[limit_msg]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20);
  wp_editor($content,$editor_id,$settings);
      ?>
            </div>
           <div class="crm-panel-field">
              <label class="crm_text_label">Form Start Date</label>
              <div class="crm-panel-description">Start date of form (for example if start date is 03-02-2015 then Form will be displayed from this date and onward)</div>              
<input type="text" name="vx_config[start_date]" placeholder="Start Date" class="text sales_date" value="<?php echo cfx_form::post('start_date',$options)?>" />
            </div>
                <div class="crm-panel-field">
              <label class="crm_text_label">Message Before starting form</label>
              <div class="crm-panel-description">Message to show before form's start date.</div>
              <?php
        $content = cfx_form::post('start_msg',$options);
  $editor_id = 'vx_config_start_msg';
  $settings = array("textarea_name"=>"vx_html[start_msg]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20);
  wp_editor($content,$editor_id,$settings);
      ?>
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Form Expiry Date</label>
              <div class="crm-panel-description">Expiry date of form (for example if expiry date is 23-02-2015 then Form will not be displayed on this date and onward)</div>
              
              <input type="text" name="vx_config[expiry_date]" placeholder="Expiry Date" class="text sales_date" value="<?php echo cfx_form::post('expiry_date',$options)?>" />
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Form Expiry Message</label>
              <div class="crm-panel-description">Message to show when form will expire.</div>
              <?php
        $content = cfx_form::post('warning_msg',$options);
  $editor_id = 'vx_config_expire_msg';
  $settings = array("textarea_name"=>"vx_html[warning_msg]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20);
  wp_editor($content,$editor_id,$settings);
      ?>
            </div>
            <div class="crm-panel-field">
              <label class="crm_text_label">Thank You Message Type</label> 
              <div class="crm-panel-description">Choose thank you message type.</div>
              <select name="vx_config[msg_type]"  class="text choose_thanks"  autocomplete="off">
                 <option value="" <?php if(cfx_form::post('msg_type',$options) == ""){echo "selected='selected'"; }?>>Thank You Message</option>
                <option value="url" <?php if(cfx_form::post('msg_type',$options) == "url"){echo "selected='selected'"; }?>>Thank You Page URL</option>
                <option value="page" <?php if(cfx_form::post('msg_type',$options) == "page"){echo "selected='selected'"; }?>>Wordpress Page</option>
 
 <option value="close_popup" <?php if(cfx_form::post('msg_type',$options) == 'close_popup'){echo "selected='selected'"; }?>>Close Popup (for popup forms only)</option>
              </select>
            </div>
            <div class="thanks_msg crm_url" style="<?php if( cfx_form::post('msg_type',$options) !="url"){ echo "display:none"; }?>">
              <div class="crm-panel-field">
                <label class="crm_text_label">Thak You Page URL</label>
                <div class="crm-panel-description">After Form Submission Go to this page.</div>
                
                <input type="text" name="vx_config[thanks_page]"  class="text" value="<?php echo cfx_form::post( 'thanks_page',$options); ?>">
              </div>
            </div>
            
 <div class="thanks_msg crm_page" style="<?php if(cfx_form::post( 'msg_type',$options) !="page"){ echo "display:none"; }?>">
              <div class="crm-panel-field">
                <label class="crm_text_label">Choose Wordpress Page</label>
                <div class="crm-panel-description">After Form Submission Go to this page.</div>
                 <select name="vx_config[thanks_page_wp]"  class="text vx_select2" style="width: 100%;"  autocomplete="off">
<?php
    $wp_pages=cfx_form_admin_pages::get_wp_pages("page");
    if(is_array($wp_pages) && count($wp_pages)>0){
       foreach($wp_pages as $p){
           $sel="";
           if($p->ID == cfx_form::post( 'thanks_page_wp',$options) )
           $sel="selected='selected'";
        echo '<option value="'.$p->ID.'" '.$sel.'>'.$p->post_title.'</option>';
       }
    }
?>
              </select>
              </div>
            </div>
            
            <div class="thanks_msg crm_msg"  style="<?php if( !empty( $options['msg_type'] )){ echo "display:none"; }?>">
              <div class="crm-panel-field">
                <label class="crm_text_label">Thank You Message</label>   <a href="#" class="crm_merge_tags">Add Merge Tags</a>
                <div class="crm-panel-description">You can use %field_name% in message like use %FirstName% to display first name.</div>
                <?php
        $content = cfx_form::post('thanks_msg',$options);
  $editor_id = 'vx_config_thanks_msg';
  $settings = array("textarea_name"=>"vx_html[thanks_msg]","tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20,);
  wp_editor($content,$editor_id,$settings);
      ?>
              </div>
          
            </div>
       <div class="crm-panel-field">
                <label class="crm_text_label">JS Code</label>
                <div class="crm-panel-description">After Form Submission, this JS code will be executed.</div>
                
                <textarea name="vx_config[code]"  class="text"  style="width: 100%"><?php echo cfx_form::post('code',$options); ?></textarea>
              </div>         
      <div class="crm-panel-field">
                <label class="crm_text_label">Automatically add the form</label>
<div class="crm-panel-description">Form will be added to the bottom of the page, or you can  use [form-x id=<?php echo $form_id; ?>] as a shortcode to place it in a post or a page. </div>
<select name="vx_config[form_location]"  class="text"  autocomplete="off">
<?php
$button_options=array(''=>'Select Any','all'=>'All Pages','selected'=>'Only Selected Pages','except'=>'All Pages Except selected pages');
    foreach($button_options as $k=>$v){
    $sel="";
  if( cfx_form::post('form_location',$options) == $k)
  $sel="selected='selected'";
  echo "<option value='".$k."' $sel>".$v."</option>";       
    }
?>
</select>
</div>
<div class="crm-panel-field">
<label class="crm_text_label">Select Pages</label>
<select name="vx_config[pages][]" class="vx_select2" multiple="multiple" autocomplete="off">
<?php
$pages=!empty($options['pages']) && is_array($options['pages']) ? $options['pages'] : array(); 
    if(is_array($wp_pages) && count($wp_pages)>0){
       foreach($wp_pages as $p){
           $sel=""; if( in_array( $p->ID ,$pages ) ){ $sel="selected='selected'"; }
        echo '<option value="'.$p->ID.'" '.$sel.'>'.$p->post_title.'</option>';
       }
    }
?>
</select>
</div>
                 
            <div class="crm-panel-field">
              <h3 class="crm-panel-description">use <code> [crmperks-forms id=<?php echo $form_id; ?>] </code> as a shortcode to place it in a post or a page.</h3>
            </div>
<div style="display: none;">            
<div id="sf_gtm_help">
<h3>Create following Macros:</h3>
<table class="sf_help_table">
<tr><th>Macro Name</th><th>Macro Type</th><th>Variable Name</th></tr>
<tr><td>Event Category</td><td>Data Layer Variable</td><td>vx.cateogry</td></tr>
<tr><td>Event Action</td><td>Data Layer Variable</td><td>vx.action</td></tr>
<tr><td>Event Label</td><td>Data Layer Variable</td><td>vx.label</td></tr>
<tr><td>Event Value<td>Data Layer Variable</td><td>vx.value</tr>
</table>
<p>Note: You can set macro name and default value according to your own choice too</p>
<h3>Create New Rule(vx rule):</h3>
<div><code>{{event}}</code> equals <code>vx.event</code></div>
<h3>Now finally create a Tag</h3>
<ol>
<li><strong>Step 1:</strong> Create New tag in GTM container , enter any name and select tag type <code>Universal Analytics</code></li>
<li><strong>Step 2:</strong> Enter your tracking ID and select track type <code>Event</code></li>
<li><strong>Step 3:</strong> Now Add newly created rule<code>vx rule</code> as Firing rule of the tag</li>
<li><strong>Step 4:</strong> Next assign newly created macros in respective fields(i.e: in <code>Category</code> field assign <code>{{Event Category}}</code>)</li>
<li><strong>Step 5:</strong> Save Tag and publish new changes to GTM container</li>
</ol>
</div>

<div id="sf_ip_block_help">
<h3>Network ranges can be specified as:</h3>
<ol>
<li>Plain IP:     1.2.3.4</li>
<li>Wildcard format:     1.2.3.*</li>
<li>CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0</li>
<li>Start-End IP format: 1.2.3.0-1.2.3.255</li>
</ol>
</div>

 </div>
            <!--SAVE-->
             <!-- #crm-panel-actions --> 
            <div id="crm-panel-actions">
              <div class="clear-block" style="text-align: right; padding: 10px;">
              <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
                <input type="hidden" name="form_id" class="form_id" value="<?php echo $form_id; ?>">
                <button type="submit" class="button button-hero button-primary main_submit"> 
                <span class="reg_ok"><i class="fa fa-download"></i> Save</span> 
                <span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Saving ...</span> 
                </button>
              </div>
            </div>
            <!-- /crm-panel-actions --> 
          </div> <!-- /STEP 4-->

</form>       
<?php include_once(cfx_form_plugin_dir.'templates/merge_tags.php');
//var_dump($options['filters']);
 ?>
 <script type="text/javascript">
 jQuery(document).ready(function($){
    $('.vx_select2').select2({});
 
 })
 </script>       

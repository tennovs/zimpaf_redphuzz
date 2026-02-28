<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }   ?>
                    
<form method="post" id="crm_theme"  class="crm_form" novalidate style="display:none">
        <!-- #STEP 3-->
          <div class="steps step2">
          
            <div class="use_theme theme_type" style="<?php if(!empty($options['theme_type'] )){echo 'display:none';}?>">
           
            
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Header and Form Design</span></div>
            <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-minus crm_toggle_btn"></i></div><div class="crm_clear"></div> </div>
            <div class="crm_panel_content" style="display: block;">
   
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Head Background</label>
                  <div class="crm-panel-description">Choose theme color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[head_bg_hex]"  autocomplete="off" class="crm_color_picker crm_color" data-color="crm_head_bg" data-rel="crm_head" value="<?php echo $options['head_bg_hex'];?>" data-opacity="<?php echo $this->op_val('head_bg_op',$options);?>">
  <input type="hidden" name="settings[head_bg_op]" class="crm_head_op" value="<?php echo  $this->op_val('head_bg_op',$options);  ?>">
 
  <input type="hidden" name="settings[head_bg]" class="crm_head_bg" value="<?php echo $options['head_bg']; ?>">
                   
<label><input type="checkbox" id="reset_crm_theme" name="settings[modify_theme]" value="yes" <?php if(!empty($options['modify_theme'])){echo "checked='checked'";}?>> Modify other theme Colors according to this color</label>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Design Template</label>
                  <div class="crm-panel-description">Change Form Design Template</div>
                </div>
                <div class="col_val">
             <a href="<?php echo $page_url ?>&tab=change_template&form_id=<?php echo $form_id; ?>"><?php _e('Change Template (Design Only)','crm-perks-forms'); ?></a>
             <div class="howto"><?php _e('Design of selected template will be applied to current form','crm-perks-forms'); ?></div>
                </div>
                <div style="clear: both;"></div>
              </div>
         
<div class="crm-panel-field">
                <label>
                  <input type="radio"  class="toggle" data-rel="head_img"  data-hide="crm_head_type_div" data-show="crm_text_head_div" name="settings[head_type]" value="" <?php if($options['head_type'] == ""){echo "checked='checked'";}?> autocomplete="off">
                  Text Heading</label>
                <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle" data-rel="head_img"  name="settings[head_type]" value="image" <?php if($options['head_type'] == "image"){echo "checked='checked'";}?> data-hide="crm_head_type_div" data-show="crm_img_head_div" autocomplete="off">
                  Image Heading</label>
                  
                  <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle" data-rel="head_img"  name="settings[head_type]" value="html" <?php if($options['head_type'] == 'html'){echo "checked='checked'";}?> data-hide="crm_head_type_div" data-show="crm_html_head_div" autocomplete="off">
                HTML Heading</label>
                  
</div>

<div class="crm_head_type_div crm_text_head_div"   style="<?php if($options['head_type'] != ""){echo "display:none";}?>">
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Heading</label>
                  <div class="crm-panel-description">Enter Form heading.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[heading]" class="input crm_heading_text" value="<?php echo $options['heading']; ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
          
</div>
              
<div class="crm_head_type_div crm_img_head_div"   style="<?php if($options['head_type'] != "image"){echo "display:none";}?>"> 
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Image</label>
                  <div class="crm-panel-description">Choose button image.</div>
                </div>
                <div class="col_val">
                  <div class="crm_file_area">
             <div class="crm_img_div"><img src="<?php echo $options['head_img'] ?>"></div>
             <button type="button" data-rel="cfx_head_img" class="button crm_select_img" style="vertical-align: bottom;"><i class="fa fa-refresh"></i> Change Image</button>     
              <input type="hidden" name="settings[head_img]" class="crm_img_name" value="<?php echo $options['head_img']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Image Max-Width</label>
                  <div class="crm-panel-description">60% , 250px etc.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[head_img_width]" class="cfx_head_img_width" placeholder="250px"  autocomplete="off" value="<?php echo cfx_form::post('head_img_width',$options) ?>">

                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
               </div>
<div class="crm_head_type_div crm_html_head_div" style="<?php if($options['head_type'] != 'html'){echo "display:none";}?>">
<div class="crm-panel-field">
        <?php //NodeChange
        $head_html = cfx_form::post('head_html',$options);
  $editor_id = 'vx_head_html';
  $settings = array("textarea_name"=>"vx_html[head_html]","tinymce"=>array('forced_root_block'=>"div",'setup'=>"function (editor) {
    editor.on('Change', function (e) {
    jQuery('.crm_head_span_html').html(e.target.getContent());
      //console.log('Editor was initialized.',e.target.getContent());
    }); }",'height'=>'240'),"textarea_rows"=>50);
  wp_editor($head_html,$editor_id,$settings);
      ?>
</div>
             </div>
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Head Text Color</label>
                  <div class="crm-panel-description">Choose head text color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[head_text_hex]"  autocomplete="off" class="crm_color_picker"  data-rel="head_text" data-color="crm_head_text_color" value="<?php echo $options['head_text_hex']; ?>" data-opacity="<?php
echo $this->op_val('head_text_op',$options); ?>">
       <input type="hidden" name="settings[head_text_op]" class="head_text_op" value="<?php echo $this->op_val('head_text_op',$options); ?>">
       <input type="hidden" name="settings[head_text]" class="crm_head_text_color" value="<?php echo $options['head_text']; ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                  <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Head Font Size</label>
                  <div class="crm-panel-description">Font size of Head text.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[head_font_size]" data-rel="cfx_form_head" data-action="head_font_size"  data-slider-range="8,60"  class="vis_slider head_font_size"  value="<?php echo (int)$options['head_font_size']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['head_font_size']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Head Font Style</label>
                  <div class="crm-panel-description">Choose font style of heading.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[head_font_style]"  data-rel="crm_head_span_" class="select_action head_font_style" >
                      <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['head_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
             <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Vertical Padding</label>
                  <div class="crm-panel-description">vertical padding of head.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[head_padding_v]" data-rel="cfx_form_head" data-action="head_padding_v"  data-slider-range="0,50"  class="vis_slider slider"  value="<?php echo (int)$options['head_padding_v']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['head_padding_v']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div> 
                      <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Horizontal Padding</label>
                  <div class="crm-panel-description">horizontal padding of head.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[head_padding_h]" data-rel="cfx_form_head" data-action="head_padding_h"  data-slider-range="0,50"  class="vis_slider slider"  value="<?php echo (int)$options['head_padding_h']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['head_padding_v']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>  
<div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Head Font Alignment</label>
                  <div class="crm-panel-description">Choose font alignment of heading.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[head_font_align]"  data-rel="cfx_form_head" data-action="text-align" class="select_action head_font_align" >
                      <?php
 foreach($font_align as $style_key=>$style){
     $sel="";
     if($style_key == $options['head_font_align'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Width</label>
                  <div class="crm-panel-description">Width of head bottom border.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" id="head_border_width" name="settings[head_border]" data-rel="cfx_form_head" data-action="head_bottom_border"  data-slider-range="0,40"  class="vis_slider head_border slider"  value="<?php echo (int)$options['head_border']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['head_border']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Style</label>
                  <div class="crm-panel-description">Choose border style.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[head_border_style]"  data-rel="cfx_form_head" data-action="crm_border_style" class="select_action" >
                      <?php
 foreach($border_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['head_border_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
               <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Type</label>
                  <div class="crm-panel-description">Choose border type.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[head_border_type]" data-width="head_border_width"  data-rel="cfx_form_head" data-action="crm_border_type" class="select_action head_border_type">
                      <?php
 foreach($border_types as $style_key=>$style){
     $sel="";
     if(isset($options['head_border_type']) && $style_key == $options['head_border_type'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
</select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Color</label>
                  <div class="crm-panel-description">Color of border.</div>
                </div>       
              <div class="col_val">
                  <div>
<input type="text" name="settings[head_border_color]"  autocomplete="off" class="crm_color_picker_n crm_head_border_color"  data-color="self"  data-rel="head_border" value="<?php echo $options['head_border_color'];?>">
                  </div>
                </div>
                   <div style="clear: both;"></div>
              </div>  
            
            </div>
            </div>
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Field Labels</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i></div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Font color</label>
                  <div class="crm-panel-description">Choose font color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[font_color_hex]"  autocomplete="off" class="crm_color_picker" data-rel="font_color" data-color="crm_font_color"  value="<?php echo $options['font_color_hex']; ?>" data-opacity="<?php echo $this->op_val('font_color_op',$options); ?>">
<input type="hidden" name="settings[font_color_op]" class="font_color_op"  value="<?php echo $this->op_val('font_color_op',$options); ?>">
<input type="hidden" name="settings[font_color]" class="crm_font_color"  value="<?php echo $options['font_color']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Font size</label>
                  <div class="crm-panel-description">Font size of form labels.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[label_font_size]" data-rel="cfx_form_label" data-action="body_font_size"  data-slider-range="8,60"  class="vis_slider label_font_size"  value="<?php echo (int)$options['label_font_size']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['label_font_size']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Font Style</label>
                    <div class="crm-panel-description">Choose font style.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <select name="settings[label_font_style]"  data-rel="cfx_form_label"  class="select_action" >
                        <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['label_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel>$style</option>";
 }   
?>
                      </select>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Margin Bottom</label>
                  <div class="crm-panel-description">Space below labels.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[label_bottom_margin]" data-rel="cfx_form_label" data-action="label_bottom_margin"  data-slider-range="0,60"  class="vis_slider"  value="<?php echo (int)cfx_form::post('label_bottom_margin',$options)?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('label_bottom_margin',$options); ?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
             
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Field Labels Design</label>
                  <div class="crm-panel-description">want to use theme's default design ?</div>
                </div>
                <div class="col_val">
              <label><input type="checkbox" name="settings[disable_label_css]" value="yes" <?php if(!empty($options['disable_label_css'])){echo "checked='checked'";}?>> Use theme's default design for field labels</label>
                
                </div>
                <div style="clear: both;"></div>
              </div>
              
               <div class="crm-panel-field">
                <label >
                  <input type="checkbox" autocomplete="off"  class="switches check_action_alt" data-rel="cfx_form_label" name="settings[hide_label]" id="hide_input_label" value="yes" <?php if(!empty($options['hide_label'] ) ){echo "checked='checked'";}?> >
                  Hide Field Labels</label>
              </div>
              
              </div> </div>
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Field Description</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i></div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Text Color</label>
                  <div class="crm-panel-description">Choose text color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[desc_text_hex]"  autocomplete="off" class="crm_color_picker" id="crm_desc_color" data-color="crm_desc_text_color" data-rel="desc_text" value="<?php echo $options['desc_text_hex']; ?>" data-opacity="<?php echo $this->op_val('desc_text_op',$options); ?>">
       <input type="hidden" name="settings[desc_text_op]" class="desc_text_op" value="<?php echo $this->op_val('input_bg_op',$options); ?>">
       <input type="hidden" name="settings[desc_text]" class="crm_desc_text_color" value="<?php echo $options['desc_text']; ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
     
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Font Size</label>
                  <div class="crm-panel-description">Font size of Description text.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[desc_font_size]" id="crm_desc_size" data-rel="cfx_desc" data-action="font-size"  data-slider-range="8,60" class="vis_slider desc_font_size"  value="<?php echo (int)$options['desc_font_size']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['desc_font_size']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
        
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Font Style</label>
                  <div class="crm-panel-description">Choose font style of description.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[desc_font_style]" id="crm_desc_style"  data-rel="cfx_desc" class="select_action" >
                      <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['head_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Font Alignment</label>
                  <div class="crm-panel-description">Choose font alignment of description.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[desc_font_align]"  data-rel="cfx_desc" data-action="text-align" class="select_action" >
                      <?php
 foreach($font_align as $style_key=>$style){
     $sel="";
     if($style_key == $options['head_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
     
     
               
            </div></div>
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Input Fields</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i></div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Elements Height</label>
                  <div class="crm-panel-description">Choose Height of input Elements.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_height]" id="crm_input_height"  data-rel="cfx_input" data-action="input_height"  data-slider-range="0,100"  class="vis_slider "  value="<?php echo (int)$options['input_height']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['input_height']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                  <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Horizontal Padding</label>
                  <div class="crm-panel-description">Choose Horizontal Padding of input Elements.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_padding_h]"  data-rel="cfx_input" data-action="input_pad"  data-slider-range="0,100"  class="vis_slider "  value="<?php echo (int)cfx_form::post('input_padding_h',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('input_padding_h',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Vertical Padding</label>
                  <div class="crm-panel-description">Choose Vertical Padding of input Elements.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_padding_y]" id="crm_input_height"  data-rel="cfx_input" data-action="input_pad_y"  data-slider-range="0,100"  class="vis_slider "  value="<?php echo (int)cfx_form::post('input_padding_y',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('input_padding_y',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                    <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Type</label>
                  <div class="crm-panel-description">Choose border type.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[input_border_type]" data-width="crm_input_border_width"  data-rel="cfx_input" data-action="crm_border_type" class="select_action input_border_type">
                      <?php
          if(empty($options['input_border_type'])) { $options['input_border_type']='all'; }            
 foreach($border_types as $style_key=>$style){
     $sel="";
     if( $style_key == $options['input_border_type'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
</select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Width</label>
                  <div class="crm-panel-description">Choose border width.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_border_width]" id="crm_input_border_width"  data-rel="cfx_input" data-action="input_border_width"  data-slider-range="0,40"   class="vis_slider"  value="<?php echo (int)$options['input_border_width']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['input_border_width']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Radius</label>
                  <div class="crm-panel-description">Choose border radius.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_border_radius]" id="crm_input_border_radius"  data-rel="cfx_input" data-action="input_border_radius"  data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)$options['input_border_radius']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['input_border_radius']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
            <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Style</label>
                  <div class="crm-panel-description">Choose border style.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[input_border_style]"  data-rel="cfx_input" data-action="crm_border_style_less" class="select_action crm_input_border_style" >
                      <?php
 foreach($border_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['input_border_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Font size</label>
                  <div class="crm-panel-description">Font size of input fields.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_font_size]" data-rel="cfx_input" data-action="font-size"  data-slider-range="8,60"  class="vis_slider label_font_size"  value="<?php echo (int)cfx_form::post('input_font_size',$options)?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('input_font_size',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Font Color</label>
                  <div class="crm-panel-description">Choose color of input text.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[input_font_color_hex]"  autocomplete="off" class="crm_color_picker"  data-rel="input_font_color" value="<?php echo cfx_form::post('input_font_color_hex',$options); ?>" data-opacity="<?php echo  $this->op_val('input_font_color_op',$options); ?>">
       <input type="hidden" name="settings[input_font_color_op]" class="input_font_color_op" value="<?php echo $this->op_val('input_font_color_op',$options); ?>">
       <input type="hidden" name="settings[input_font_color]" class="input_font_color_rgba" value="<?php echo cfx_form::post('input_font_color',$options); ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Placeholder Color</label>
                  <div class="crm-panel-description">Choose color of input Placeholder.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[place_color_hex]"  autocomplete="off" class="crm_color_picker"  data-rel="place_color" value="<?php echo $options['place_color_hex']; ?>" data-opacity="<?php echo $this->op_val('place_color_op',$options); ?>">
       <input type="hidden" name="settings[place_color_op]" class="place_color_op" value="<?php echo $this->op_val('place_color_op',$options); ?>">
       <input type="hidden" name="settings[place_color]" class="place_color_rgba" value="<?php echo $options['place_color']; ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Row Padding Bottom</label>
                  <div class="crm-panel-description">Bottom padding of field row.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_padding_bottom]" id="crm_input_padding_bottom" data-rel="crm_form_row_wrap" data-action="padding-bottom"  data-slider-range="0,60" class="vis_slider"  value="<?php echo (int)$options['input_padding_bottom']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['input_padding_bottom']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
    
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Row Padding Top</label>
                  <div class="crm-panel-description">Top padding of field row.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[input_padding_top]" id="crm_input_padding_top" data-rel="crm_form_row_wrap" data-action="padding-top"  data-slider-range="0,60" class="vis_slider"  value="<?php echo (int)$options['input_padding_top']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['input_padding_top']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Color</label>
                  <div class="crm-panel-description">Choose border color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[input_color]"  autocomplete="off" class="crm_color_picker_n crm_input_color"  data-color="self" data-rel="input_color"  value="<?php echo $options['input_color']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Color on Focus</label>
                  <div class="crm-panel-description">Choose border color on focus.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[input_focus]"  autocomplete="off" class="crm_color_picker_n crm_input_focus" data-color="self" data-rel="input_focus"  value="<?php echo $options['input_focus']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                    <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Style on focus</label>
                  <div class="crm-panel-description">Choose border style on focus.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[input_border_style_focus]"  data-rel="cfx_input" data-action="crm_border_style_less" class="select_action crm_input_border_style_focus" >
                      <?php
 foreach($border_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['input_border_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <label >
                  <input type="checkbox"  class="switches check_action" data-rel="input_bg" id="use_input_bg" name="settings[use_input_bg]" value="yes" <?php if(!empty($options['use_input_bg'] )){echo "checked='checked'";}?> autocomplete="off">
                  Add Light Background to Input Elements</label>
              </div>
<div id="input_bg" class="crm-panel-field" style="<?php if(empty($options['use_input_bg'])){echo "display:none";}?> ">
                <div class="col_label">
                  <label class="crm_text_label">BG Color</label>
                  <div class="crm-panel-description">Choose background color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[input_bg_hex]"  autocomplete="off" class="crm_color_picker" data-rel="input_bg" data-color="crm_input_bg"  value="<?php echo $options['input_bg_hex']; ?>" data-opacity="<?php echo $this->op_val('input_bg_op',$options); ?>">
<input type="hidden" name="settings[input_bg_op]" class="input_bg_op"   value="<?php echo $this->op_val('input_bg_op',$options); ?>">
<input type="hidden" name="settings[input_bg]" class="crm_input_bg"   value="<?php echo $options['input_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
            <div class="crm-panel-field">
                <label >
                  <input type="checkbox" autocomplete="off"  class="switches check_action" data-rel="input_bg_focus" name="settings[use_input_bg_focus]" id="use_input_bg_focus" value="yes" <?php if(!empty($options['use_input_bg_focus'] ) ){echo "checked='checked'";}?> >
                  Add theme Background to Input Elements on Focus</label>
              </div>
                     <div   id="input_bg_focus" class="crm-panel-field" style="<?php if( empty($options['use_input_bg_focus'] ) ){echo "display:none";}?> ">
                <div class="col_label">
                  <label class="crm_text_label">BG Color on Focus</label>
                  <div class="crm-panel-description">Choose BG color on focus.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[input_bg_focus_hex]"  autocomplete="off" class="crm_color_picker input_bg_focus_hex" data-color="self" data-rel="input_bg_focus"  value="<?php echo $options['input_bg_focus_hex']; ?>" data-opacity="<?php echo $this->op_val('input_bg_focus_op',$options); ?>">
<input type="hidden" name="settings[input_bg_focus_op]" class="input_bg_focus_op"  value="<?php echo $this->op_val('input_bg_focus_op',$options); ?>">
<input type="hidden" name="settings[input_bg_focus]" class="crm_input_bg_focus" data-rel="input_bg_focus"  value="<?php echo $options['input_bg_focus']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                  <div class="crm-panel-field">
                <label >
                  <input type="checkbox"  class="check_action" data-rel="input_shadow" name="settings[remove_input_shadow]" value="yes" <?php if(!empty($options['remove_input_shadow'] )){echo "checked='checked'";}?> autocomplete="off">
                  Remove Shadow from Input Elements</label>
              </div>
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Input Fields Design</label>
                  <div class="crm-panel-description">want to use theme's default design ?</div>
                </div>
                <div class="col_val">
              <label><input type="checkbox" name="settings[disable_input_css]" value="yes" <?php if(!empty($options['disable_input_css'])){echo "checked='checked'";}?>> Use theme's default design for Input fields except input height</label>
                
                </div>
                <div style="clear: both;"></div>
              </div>
              </div> </div>                        
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Button</span> </div><div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i></div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
   
                 <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Button Alignment</label>
                  <div class="crm-panel-description">Choose alignment of button.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[button_align]"  data-rel="cfx_submit_wrap" data-action="text-align" class="select_action button_align" >
                      <?php
 foreach($font_align as $style_key=>$style){
     $sel="";
     if($style_key == $options['button_align'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>

            <div class="crm-panel-field">
                <label >
                  <input type="radio"  class="toggle submit_button_type" data-rel="btn_img"  data-hide="crm_button_type" data-show="crm_text_button" name="settings[button_type]" value="" <?php if($options['button_type'] == ""){echo "checked='checked'";}?> autocomplete="off">
                  Use Text Button</label>
                <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle submit_button_type" data-rel="btn_img"  name="settings[button_type]" value="image" <?php if($options['button_type'] == "image"){echo "checked='checked'";}?> data-hide="crm_button_type" data-show="crm_img_button" autocomplete="off">
                  Use Image Button (for footer button)</label>
              </div>
                 <div class="crm_button_type crm_text_button"   style="<?php if($options['button_type'] != ""){echo "display:none";}?>">
                            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Submit Button Text</label>
                  <div class="crm-panel-description">Enter submit button text.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[submit_text]" class="input crm_submit_text"  value="<?php echo $options['submit_text'];?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Text Color</label>
                  <div class="crm-panel-description">Choose button text color.</div>
                </div>
                <div class="col_val">
                  <div>
              <input type="text" name="settings[btn_text_hex]"  autocomplete="off" class="crm_color_picker" data-color="crm_btn_text" data-rel="btn_text" value="<?php echo $options['btn_text_hex']; ?>" data-opacity="<?php echo $this->op_val('btn_text_op',$options); ?>">
              
                <input type="hidden" name="settings[btn_text_op]" class="btn_text_op"  value="<?php echo $this->op_val('btn_text_op',$options); ?>">
                <input type="hidden" name="settings[btn_text]" class="crm_btn_text"  value="<?php echo $options['btn_text']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
               <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Font Size</label>
                    <div class="crm-panel-description">Choose font size of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <input type="text" name="settings[submit_font_size]"  data-rel="cfx_submit" data-action="submit_font_size"  data-slider-range="8,100"  class="vis_slider"  value="<?php echo (int)$options['submit_font_size']?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)$options['submit_font_size']?></span></div>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
                <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Font Style</label>
                    <div class="crm-panel-description">Choose font style of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <select name="settings[submit_font_style]"  data-rel="cfx_submit" class="select_action submit_font_style" >
                        <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['submit_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel>$style</option>";
 }   
?>
                      </select>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                     <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Background</label>
                  <div class="crm-panel-description">Choose button BG color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[btn_bg_hex]"  autocomplete="off" class="crm_color_picker" data-rel="btn_bg" data-color="crm_btn_bg"  value="<?php echo $options['btn_bg_hex']; ?>" data-opacity="<?php echo $this->op_val('btn_bg_op',$options); ?>">
<input type="hidden" name="settings[btn_bg_op]" class="btn_bg_op"  value="<?php echo $this->op_val('btn_bg_op',$options) ; ?>">
<input type="hidden" name="settings[btn_bg]" class="crm_btn_bg"  value="<?php echo $options['btn_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Hover</label>
                  <div class="crm-panel-description">Choose button hover color.</div>
                </div>
                <div class="col_val">
<input type="text" name="settings[btn_hover_hex]"  autocomplete="off" class="crm_color_picker" data-rel="btn_hover" data-color="crm_btn_hover"  value="<?php echo $options['btn_hover_hex']; ?>" data-opacity="<?php echo $this->op_val('btn_hover_op',$options);  ?>">
<input type="hidden" name="settings[btn_hover_op]" class="btn_hover_op"  value="<?php echo $this->op_val('btn_hover_op',$options); ?>">
<input type="hidden" name="settings[btn_hover]" class="crm_btn_hover"  value="<?php echo $options['btn_hover']; ?>">
<input type="hidden" name="settings[btn_focus]" class="crm_btn_focus"  value="<?php echo $options['btn_focus']; ?>">
          
                </div>
                <div style="clear: both;"></div>
              </div>
              
        
            <div class="crm-panel-field">
                <label >
                  <input type="radio"  class="toggle adjust_submit_button"   data-hide="adjust_submit" name="settings[adjust_submit]" value="" <?php if($options['adjust_submit'] == ""){echo "checked='checked'";}?> autocomplete="off">
                  Use Auto Width/Height</label>
                <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle adjust_submit_button"  name="settings[adjust_submit]" value="custom" <?php if($options['adjust_submit'] == "custom"){echo "checked='checked'";}?> data-hide="adjust_submit" data-show="custom_submit" autocomplete="off">
                  Adjust Width/Height</label>
              </div>
              <div class="adjust_submit custom_submit"   style="<?php if($options['adjust_submit'] != "custom"){echo "display:none";}?>">
                <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Button Width</label>
                    <div class="crm-panel-description">Choose form width of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <input type="text" name="settings[submit_width]"  data-rel="cfx_submit" data-action="submit_width"  data-slider-range="0,100"  class="vis_slider submit_width"  value="<?php echo (int)$options['submit_width']?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)$options['submit_width']?></span></div>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
              
                    
                <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Button Height</label>
                    <div class="crm-panel-description">Choose height of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <input type="text" name="settings[submit_height]"  data-rel="cfx_submit" data-action="height"  data-slider-range="8,100" data-slider-highlight="true" data-slider="true"  class="vis_slider submit_height"  value="<?php echo $options['submit_height']?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo $options['submit_height']?></span></div>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                </div>
           
            <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Button Radius</label>
                    <div class="crm-panel-description">Choose corner radius of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <input type="text" name="settings[submit_radius]"  data-rel="cfx_submit" data-action="border-radius"  data-slider-range="0,60" data-slider-highlight="true" data-slider="true"  class="vis_slider"  value="<?php echo $options['submit_radius']?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo $options['submit_radius']?></span></div>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
            <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Top Margin</label>
                    <div class="crm-panel-description">Choose top margin of submit button.</div>
                  </div>
                  <div class="col_val">
                      <input type="text" name="settings[submit_top_margin]"  data-rel="cfx_submit" data-action="margin-top"  data-slider-range="0,100"  class="vis_slider"  value="<?php echo (int)cfx_form::post('submit_top_margin', $options); ?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)cfx_form::post('submit_top_margin', $options);?></span></div>
                 
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
                <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Bottom Margin</label>
                    <div class="crm-panel-description">Choose bottom margin of submit button.</div>
                  </div>
                  <div class="col_val">
                      <input type="text" name="settings[submit_bottom_margin]"  data-rel="cfx_submit" data-action="margin-bottom"  data-slider-range="0,100"  class="vis_slider"  value="<?php echo (int)cfx_form::post('submit_bottom_margin', $options); ?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)cfx_form::post('submit_bottom_margin', $options); ?></span></div>
                 
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
                    <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Horizontal Padding</label>
                    <div class="crm-panel-description">Left and Right padding of submit button.</div>
                  </div>
                  <div class="col_val">
                      <input type="text" name="settings[submit_pad_h]"  data-rel="cfx_submit" data-action="submit_pad_h"  data-slider-range="0,100"  class="vis_slider"  value="<?php echo (int)cfx_form::post('submit_pad_h', $options); ?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
           <span class="vis_output"><?php echo (int)cfx_form::post('submit_pad_h', $options);?></span></div>
                 
                  </div>
                  <div style="clear: both;"></div>
                </div>
                
                
                         <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Submit Border Width</label>
                    <div class="crm-panel-description">Choose border width of submit button.</div>
                  </div>
                  <div class="col_val">
                    <div>
                      <input type="text" name="settings[btn_border_width]"  data-rel="cfx_submit" data-action="border-width"  data-slider-range="0,10"   class="vis_slider"  value="<?php echo (int)$options['btn_border_width']?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)$options['btn_border_width']?></span></div>
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Border Color</label>
                  <div class="crm-panel-description">Choose button border color.</div>
                </div>
                <div class="col_val">
                  <div>
              <input type="text" name="settings[btn_border_color]" data-color="self" data-rel="btn_border_color"  autocomplete="off" class="crm_color_picker_n crm_btn_border_color" value="<?php echo $options['btn_border_color']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Border Hover Color</label>
                  <div class="crm-panel-description">Choose button border hover color.</div>
                </div>
                <div class="col_val">
                  <div>
              <input type="text" name="settings[btn_border_hover]" data-color="self" data-rel="btn_border_hover"  autocomplete="off" class="crm_color_picker_n crm_btn_border_hover" value="<?php echo $options['btn_border_hover']; ?>">
<input type="hidden" name="settings[btn_border_focus]" class="crm_btn_border_focus" value="<?php echo $options['btn_border_focus'];?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
           <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Submit Button Design</label>
                  <div class="crm-panel-description">want to use theme's default design ?</div>
                </div>
                <div class="col_val">
              <label><input type="checkbox" name="settings[disable_btn_css]" value="yes" <?php if(!empty($options['disable_btn_css'])){echo "checked='checked'";}?>> Use theme's default design for submit buttons</label>
                
                </div>
                <div style="clear: both;"></div>
              </div> 
                  
                   </div>
                  <div class="crm_button_type crm_img_button"   style="<?php if($options['button_type'] != "image"){echo "display:none";}?>"> 
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Button Image</label>
                  <div class="crm-panel-description">Choose button image.</div>
                </div>
                <div class="col_val">
                  <div class="crm_file_area">
             <div class="crm_img_div"><img src="<?php echo $options['button_img'] ?>"></div>
             <button type="button" data-rel="crm_img_btn" class="button crm_select_img" style="vertical-align: bottom;"><i class="fa fa-refresh"></i> Change Image</button>     
              <input type="hidden" name="settings[button_img]" class="crm_img_name" value="<?php echo $options['button_img']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div> 
              
               </div>
              </div> </div>
              
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Footer </span> </div><div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
             
            <div class="crm_panel_content">
            
             <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Show Footer</label>
                </div>
                <div class="col_val">
             <label><input type="checkbox" name="settings[show_footer]" class="check_action_alt" id="cfx_toggle_footer_check" data-rel="hide_footer" value="yes" <?php if(!empty($options['show_footer'])){echo "checked='checked'";}?>> Yes, show Footer with default Submit Button</label>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <label >
                  <input type="radio"  class="toggle footer_border_check"  data-hide="choose_border" name="settings[use_footer_border]" value="" <?php if($options['use_footer_border'] == ""){echo "checked='checked'";}?> autocomplete="off">
                  Use Default Border</label>
                <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle footer_border_check"  name="settings[use_footer_border]" value="custom" <?php if($options['use_footer_border'] == "custom"){echo "checked='checked'";}?> data-hide="choose_border" data-show="custom_border" autocomplete="off">
                  Use Custom Border</label>
              </div>
              <div class="choose_border custom_border"   style="<?php if($options['use_footer_border'] != "custom"){echo "display:none";}?>">
                  <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Top Border</label>
                  <div class="crm-panel-description">Width of footer top border.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" id="footer_border_width" name="settings[footer_border_width]" data-rel="crm_form_footer" data-action="border-top-width"  data-slider-range="0,10"  class="vis_slider"  value="<?php echo  (int)$options['footer_border_width']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['footer_border_width']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Footer Border Style</label>
                  <div class="crm-panel-description">Top border style of footer.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[footer_border_style]"  data-rel="crm_form_footer" data-action="crm_border_style" class="select_action" >
                      <?php
 foreach($border_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['footer_border_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Top Border Color</label>
                  <div class="crm-panel-description">Choose footer top border color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[footer_border]"  autocomplete="off" class="crm_color_picker_n crm_footer_border_color"  data-color="self"  value="<?php echo $options['footer_border']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Bottom Radius</label>
                  <div class="crm-panel-description">Radius of footer bottom.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[footer_radius]" data-rel="cfx_form_div" data-action="footer_radius"  data-slider-range="0,20"  class="vis_slider slider"  value="<?php echo (int)$options['footer_radius']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['footer_radius']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
<?php
$footer_top=(int)cfx_form::post('footer_top',$options);
$footer_bottom=(int)cfx_form::post('footer_bottom',$options);
?>
                  <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Top Margin</label>
                  <div class="crm-panel-description">Top margin of footer.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[footer_top]" data-rel="crm_form_footer" data-action="margin-top"  data-slider-range="0,100"  class="vis_slider slider"  value="<?php echo $footer_top ?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo $footer_top; ?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Bottom Margin</label>
                  <div class="crm-panel-description">Bottom margin of footer.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[footer_bottom]" data-rel="crm_form_footer" data-action="margin-bottom"  data-slider-range="0,100"  class="vis_slider slider"  value="<?php echo $footer_bottom; ?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo $footer_bottom; ?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                        <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Top Padding</label>
                  <div class="crm-panel-description">Top padding of footer.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[footer_top_padding]" data-rel="crm_form_footer" data-action="padding-top"  data-slider-range="0,100"  class="vis_slider slider"  value="<?php echo (int)$options['footer_top_padding']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['footer_top_padding']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                      <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Bottom Padding</label>
                  <div class="crm-panel-description">Bottom padding of footer.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[footer_bottom_padding]" data-rel="crm_form_footer" data-action="padding-bottom"  data-slider-range="0,100"  class="vis_slider slider"  value="<?php echo (int)$options['footer_bottom_padding']?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['footer_bottom_padding']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Footer Background</label>
                  <div class="crm-panel-description">Choose footer background.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[footer_bg_hex]"  autocomplete="off" class="crm_color_picker crm_footer_bg_hex" data-rel="footer_bg" data-color="self"  value="<?php echo $options['footer_bg_hex']; ?>" data-opacity="<?php echo $this->op_val('footer_bg_op',$options);  ?>">
<input type="hidden" name="settings[footer_bg_op]" class="footer_bg_op"  value="<?php echo $this->op_val('footer_bg_op',$options); ?>">
<input type="hidden" name="settings[footer_bg]" class="crm_footer_bg"  value="<?php echo $options['footer_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>   
              
              
              </div> </div>
              
              
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Fonts</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Font family</label>
                  <div class="crm-panel-description">Font family of form.</div>
                </div>
                <div class="col_val">
                  <label  >
                    <input type="radio"  class="toggle"  name="settings[fonts_type]" value="" <?php if($options['fonts_type'] == ""){echo "checked='checked'";}?> data-hide="choose_fonts" data-show="custom_font" autocomplete="off">
                    Common Fonts</label>
                  <label style="margin-left: 20px">
                    <input type="radio"  class="toggle" data-show="google_font" data-hide="choose_fonts" name="settings[fonts_type]" value="google" <?php if($options['fonts_type'] == "google"){echo "checked='checked'";}?> autocomplete="off">
                    Google Fonts</label>
                       <label style="margin-left: 20px">
                    <input type="radio"  class="toggle" data-show="url_font" data-hide="choose_fonts" name="settings[fonts_type]" value="url" <?php if($options['fonts_type'] == "url"){echo "checked='checked'";}?> autocomplete="off">
                    Fonts URL</label>
                  <div style="margin-top: 20px;">
                    <div class="choose_fonts google_font"  style="<?php if($options['fonts_type'] !="google"){echo "display:none";}?>">
                      <input type="text" name="settings[google_family]" value="<?php echo $options['google_family']?>" id="crm_font_family" style="width:100%">
                    </div>
          
                    <div class="choose_fonts custom_font"  style="<?php if($options['fonts_type'] !=""){echo "display:none";}?>">
                      <select name="settings[custom_family]" class="text fonts_family_select">
                        <option value="" style="font-size: 16px;">Default Fonts</option>
                        <?php
$fonts_json='{"Arial,\"Helvetica Neue\",Helvetica,sans-serif":"Arial","\"Arial Black\",\"Arial Bold\",Gadget,sans-serif":"Arial-Black","\"Arial Narrow\",Arial,sans-serif":"Arial-Narrow","\"Arial Rounded MT Bold\",\"Helvetica Rounded\",Arial,sans-serif":"Arial-Rounded-MT-Bold","\"Avant Garde\",Avantgarde,\"Century Gothic\",CenturyGothic,AppleGothic,sans-serif":"Avant-Garde","Calibri,Candara,Segoe,\"Segoe UI\",Optima,Arial,sans-serif":"Calibri","Candara,Calibri,Segoe,\"Segoe UI\",Optima,Arial,sans-serif":"Candara","\"Century Gothic\",CenturyGothic,AppleGothic,sans-serif":"Century-Gothic","\"Franklin Gothic Medium\",\"Franklin Gothic\",\"ITC Franklin Gothic\",Arial,sans-serif":"Franklin-Gothic-Medium","Futura,\"Trebuchet MS\",Arial,sans-serif":"Futura","Geneva,Tahoma,Verdana,sans-serif":"Geneva","\"Gill Sans\",\"Gill Sans MT\",Calibri,sans-serif":"Gill-Sans","\"Helvetica Neue\",Helvetica,Arial,sans-serif":"Helvetica","Impact,Haettenschweiler,\"Franklin Gothic Bold\",Charcoal,\"Helvetica Inserat\",\"Bitstream Vera Sans Bold\",\"Arial Black\",\"sans serif\"":"Impact","\"Lucida Grande\",\"Lucida Sans Unicode\",\"Lucida Sans\",Geneva,Verdana,sans-serif":"Lucida-Grande","Optima,Segoe,\"Segoe UI\",Candara,Calibri,Arial,sans-serif":"Optima","\"Segoe UI\",Frutiger,\"Frutiger Linotype\",\"Dejavu Sans\",\"Helvetica Neue\",Arial,sans-serif":"Segoe-UI","Tahoma,Verdana,Segoe,sans-serif":"Tahoma","\"Trebuchet MS\",\"Lucida Grande\",\"Lucida Sans Unicode\",\"Lucida Sans\",Tahoma,sans-serif":"Trebuchet-MS","Verdana,Geneva,sans-serif":"Verdana","\"Big Caslon\",\"Book Antiqua\",\"Palatino Linotype\",Georgia,serif":"Big-Caslon","\"Bodoni MT\",Didot,\"Didot LT STD\",\"Hoefler Text\",Garamond,\"Times New Roman\",serif":"Bodoni-MT","\"Book Antiqua\",Palatino,\"Palatino Linotype\",\"Palatino LT STD\",Georgia,serif":"Book-Antiqua","\"Calisto MT\",\"Bookman Old Style\",Bookman,\"Goudy Old Style\",Garamond,\"Hoefler Text\",\"Bitstream Charter\",Georgia,serif":"Calisto-MT","Cambria,Georgia,serif":"Cambria","Didot,\"Didot LT STD\",\"Hoefler Text\",Garamond,\"Times New Roman\",serif":"Didot","Garamond,Baskerville,\"Baskerville Old Face\",\"Hoefler Text\",\"Times New Roman\",serif":"Garamond","Georgia,Times,\"Times New Roman\",serif":"Georgia","\"Goudy Old Style\",Garamond,\"Big Caslon\",\"Times New Roman\",serif":"Goudy-Old-Style","\"Hoefler Text\",\"Baskerville Old Face\",Garamond,\"Times New Roman\",serif":"Hoefler-Text","\"Lucida Bright\",Georgia,serif":"Lucida-Bright","Palatino,\"Palatino Linotype\",\"Palatino LT STD\",\"Book Antiqua\",Georgia,serif":"Palatino","Perpetua,Baskerville,\"Big Caslon\",\"Palatino Linotype\",Palatino,\"URW Palladio L\",\"Nimbus Roman No9 L\",serif":"Perpetua","Rockwell,\"Courier Bold\",Courier,Georgia,Times,\"Times New Roman\",serif":"Rockwell","\"Rockwell Extra Bold\",\"Rockwell Bold\",monospace":"Rockwell-Extra-Bold","Baskerville,\"Baskerville Old Face\",\"Hoefler Text\",Garamond,\"Times New Roman\",serif":"Baskerville","TimesNewRoman,\"Times New Roman\",Times,Baskerville,Georgia,serif":"Times-New-Roman","Consolas,monaco,monospace":"Consolas","\"Courier New\",Courier,\"Lucida Sans Typewriter\",\"Lucida Typewriter\",monospace":"Courier-New","\"Lucida Console\",\"Lucida Sans Typewriter\",monaco,\"Bitstream Vera Sans Mono\",monospace":"Lucida-Console","\"Lucida Sans Typewriter\",\"Lucida Console\",monaco,\"Bitstream Vera Sans Mono\",monospace":"Lucida-Sans-Typewriter","monaco,Consolas,\"Lucida Console\",monospace":"Monaco","\"Andale Mono\",AndaleMono,monospace":"Andale-Mono","Copperplate,\"Copperplate Gothic Light\",fantasy":"Copperplate","Papyrus,fantasy":"Papyrus","\"Brush Script MT\",cursive":"Brush-Script-MT"}';
    $fonts=json_decode($fonts_json,true);
    foreach($fonts as $font=>$family){
        $sel='';
        if($font == $options['custom_family'])
        $sel='selected="selected"';
        echo "<option value='$font' $sel style='font-family: $font; font-size: 16px'>$family</option>";
    }
?>
                      </select>
                    </div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                      <div class="choose_fonts url_font"  style="<?php if($options['fonts_type'] !="url"){echo "display:none";}?>">
                              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Font URL</label>
                  <div class="crm-panel-description">Enter font url.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[url_fonts]" value="<?php echo $options['url_fonts']?>" placeholder="https://fonts.googleapis.com/css?family=Open+Sans">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
<div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Font Name</label>
                  <div class="crm-panel-description">Enter font name.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[url_fonts_name]" value="<?php echo $options['url_fonts_name']?>" placeholder="'Open Sans', sans-serif;">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                  
                    </div>
              </div> </div>
              

            
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Form Body</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Border</label>
                  <div class="crm-panel-description">Choose form border.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[form_border]" class="input form_border">
                      <option value="" <?php if($options['form_border'] == ""){echo "selected='selected'";}?>>Simple Border</option>
                      <option value="shadow" <?php if($options['form_border'] == "shadow"){echo "selected='selected'";}?>>Border + Shadow</option>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              
              <div class="form_border_div">
              
            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Width</label>
                  <div class="crm-panel-description">Choose border width.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[border_width]" id="crm_border_width"  data-rel="crm_border" data-action="border_width"  data-slider-range="0,30"   class="vis_slider"  value="<?php echo (int)$options['border_width']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['border_width']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Top Border Width</label>
                  <div class="crm-panel-description">Choose top border width.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[border_width_top]"  data-rel="crm_form_con" data-action="border-top-width"  data-slider-range="0,30"   class="vis_slider crm_border_top_width"  value="<?php echo (int)cfx_form::post('border_width_top',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('border_width_top',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                  
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Border Style</label>
                  <div class="crm-panel-description">Choose border style.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[border_style]" id="crm_border_style" data-rel="crm_form_con" data-action="crm_border_style" class="select_action" >
                      <?php
 foreach($border_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['border_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Border Color</label>
                  <div class="crm-panel-description">Choose border color.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[border_color]"  autocomplete="off" class="crm_color_picker_n crm_border_color" data-color="self" data-rel="border_color"  value="<?php echo $options['border_color']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>

                   
              
              </div>
          <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Body Top Padding</label>
                  <div class="crm-panel-description">Choose Top Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[padding_top]"  data-rel="crm_form_body" data-action="padding-top"  data-slider-range="0,100"   class="vis_slider"  value="<?php echo (int)$options['padding_top']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['padding_top']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Body Bottom Padding</label>
                  <div class="crm-panel-description">Choose Bottom Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[padding_bottom]"  data-rel="crm_form_body" data-action="padding-bottom"  data-slider-range="0,100"   class="vis_slider"  value="<?php echo (int)$options['padding_bottom']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['padding_bottom']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Body Left Padding</label>
                  <div class="crm-panel-description">Choose Left Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[padding_left]"  data-rel="crm_form_body" data-action="padding-left"  data-slider-range="0,100"   class="vis_slider"  value="<?php echo (int)$options['padding_left']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['padding_left']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Body Right Padding</label>
                  <div class="crm-panel-description">Choose Right Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[padding_right]"  data-rel="crm_form_body" data-action="padding-right"  data-slider-range="0,100"   class="vis_slider"  value="<?php echo (int)$options['padding_right']?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$options['padding_right']?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
               <div  class="crm-panel-field" >
                  <div class="col_label">
                    <label class="crm_text_label">Form Body Top Margin</label>
                    <div class="crm-panel-description">Choose top margin of Form Body.</div>
                  </div>
                  <div class="col_val">
                      <input type="text" name="settings[body_top_margin]"  data-rel="crm_form_con" data-action="margin-top"  data-slider-range="-50,100"  class="vis_slider"  value="<?php echo (int)cfx_form::post('body_top_margin', $options); ?>" autocomplete="off">
                      <div class="vis_slide_div">
                        <div class="vis_slide"></div>
                        <span class="vis_output"><?php echo (int)$options['body_top_margin']?></span></div>
                 
                  </div>
                  <div style="clear: both;"></div>
                </div>
           <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Corner Radius</label>
                  <div class="crm-panel-description">Radius of corner.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[body_radius]" data-rel="cfx_form_div" data-action="border-radius"  data-slider-range="0,100"  class="vis_slider"  value="<?php echo (int)cfx_form::post('body_radius',$options)?>" autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
           <span class="vis_output"><?php echo (int)cfx_form::post('body_radius',$options); ?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   
<div class="crm-panel-field">
                <label >
                  <input type="radio"  class="toggle" data-rel="crm_bg_img" data-hide="use_bg_img_div"  name="settings[outer_bg_type]" value="" <?php if( empty($options['outer_bg_type']) ){echo "checked='checked'";}?> autocomplete="off"> 
                  No Background Image</label>
                <label  style="margin-left: 20px">
                  <input type="radio"  class="toggle"  name="settings[outer_bg_type]" value="img" <?php if( !empty($options['outer_bg_type']) ){echo "checked='checked'";}?>  data-rel="crm_bg_img" data-show="bg_img_div" autocomplete="off">
                  Form Background Image</label>
              </div>
<?php //data-hide="choose_bg_color_outer" //data-show="choose_bg_color_outer" style="if( !empty($options['outer_bg_type']) ){echo "display:none";} 
$outer_img=cfx_form::post('outer_img',$options);                  
$outer_bg=cfx_form::post('outer_bg',$options);                  
$outer_op=cfx_form::post('outer_bg_op',$options);                  
$outer_hex=cfx_form::post('outer_bg_hex',$options);                  
$form_padding_top=cfx_form::post('form_padding_top',$options);                  
$form_padding_bottom=cfx_form::post('form_padding_bottom',$options);                  
$form_padding_left=cfx_form::post('form_padding_left',$options);                  
$form_padding_right=cfx_form::post('form_padding_right',$options);                  
?>            
<div class="use_bg_img_div bg_img_div" style="<?php if( empty($options['outer_bg_type']) ){echo "display:none";} ?>">      
       <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form BG Image</label>
                  <div class="crm-panel-description">Choose form background image of form.</div>
                </div>
                <div class="col_val">
                  <div class="crm_file_area">
             <div class="crm_img_div"><img src="<?php echo $outer_img ?>"></div>
             <button type="button" data-rel="crm_bg_img" class="button crm_select_img" style="vertical-align: bottom;"><i class="fa fa-refresh"></i> Change Image</button>     
              <input type="hidden" name="settings[outer_img]" class="crm_img_name crm_bg_img_name" value="<?php echo $outer_img; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
             <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">BG Position X</label>
                  <div class="crm-panel-description">Background Position X.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[outer_bg_pos_x]" data-rel="cfx_form_div" data-action="background-position-x" class="select_action" >
                      <?php
 foreach($bg_position_x as $k=>$v){
     $sel="";
     if(!empty($options['outer_bg_pos_x']) && $options['outer_bg_pos_x'] == $k){
     $sel='selected="selected"'; }
     echo "<option value='$k' $sel >$v</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">BG Position Y</label>
                  <div class="crm-panel-description">Background Position Y.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[outer_bg_pos_y]" data-rel="cfx_form_div" data-action="background-position-y" class="select_action" >
                      <?php
 foreach($bg_position_y as $k=>$v){
     $sel="";
     if(!empty($options['outer_bg_pos_y']) && $options['outer_bg_pos_y'] == $k){
     $sel='selected="selected"'; }
     echo "<option value='$k' $sel >$v</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>     
              
            <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">BG Repeate X</label>
                  <div class="crm-panel-description">Background Repeate X.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[outer_bg_rep_x]"  data-rel="cfx_form_div" data-action="background-repeat-x" class="select_action" >
                      <?php
 foreach($bg_repeate as $k=>$v){
     $sel="";
     if(!empty($options['outer_bg_rep_x']) && $options['outer_bg_rep_x'] == $k){
     $sel='selected="selected"'; }
     echo "<option value='$k' $sel >$v</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">BG Repeate Y</label>
                  <div class="crm-panel-description">Background Repeate Y.</div>
                </div>
                <div class="col_val">
                  <div>
                    <select name="settings[outer_bg_rep_y]"  data-rel="cfx_form_div" data-action="background-repeat-y" class="select_action" >
                      <?php
 foreach($bg_repeate as $k=>$v){
     $sel="";
     if(!empty($options['outer_bg_rep_y']) && $options['outer_bg_rep_y'] == $k){
     $sel='selected="selected"'; }
     echo "<option value='$k' $sel >$v</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
             
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">BG Size</label>
                  <div class="crm-panel-description">100% 100%.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[outer_bg_size]" placeholder="100% 100%" class="input outer_bg_size"  value="<?php echo cfx_form::post('outer_bg_size',$options); ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                    
              </div>

<div class="choose_bg_color_outer">  
        <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Background</label>
                  <div class="crm-panel-description">body and header background.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[outer_bg_hex]"  autocomplete="off" class="crm_color_picker outer_bg_hex" data-color="self" data-rel="form_bg_outer"  value="<?php echo $outer_hex; ?>" data-opacity="<?php echo $outer_op =='' ? "1" : $outer_op; ?>">
<input type="hidden" name="settings[outer_bg_op]" class="form_bg_outer_op"  value="<?php echo $outer_op; ?>">
<input type="hidden" name="settings[outer_bg]" class="form_bg_outer_bg"  value="<?php echo $outer_bg; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
</div>
              
  <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Padding Top</label>
                  <div class="crm-panel-description">Outer Top Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[form_padding_top]"  data-rel="cfx_form_div" data-action="padding-top"  data-slider-range="0,400"   class="vis_slider"  value="<?php echo (int)$form_padding_top; ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$form_padding_top; ?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
           
           <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Padding Bottom</label>
                  <div class="crm-panel-description">Outer Padding Bottom</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[form_padding_bottom]"  data-rel="cfx_form_div" data-action="padding-bottom"  data-slider-range="0,400"   class="vis_slider"  value="<?php echo (int)$form_padding_bottom; ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$form_padding_bottom?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
           <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Padding Left</label>
                  <div class="crm-panel-description">Outer margin left.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[form_padding_left]"  data-rel="cfx_form_div" data-action="padding-left"  data-slider-range="0,400"   class="vis_slider"  value="<?php echo (int)$form_padding_left; ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$form_padding_left?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>  
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Padding Right</label>
                  <div class="crm-panel-description">Outer margin right.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[form_padding_right]"  data-rel="cfx_form_div" data-action="padding-right"  data-slider-range="0,400"   class="vis_slider"  value="<?php echo (int)$form_padding_right; ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)$form_padding_right?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                                     
<div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Body Background</label>
                  <div class="crm-panel-description">Choose body background.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[form_bg_hex]"  autocomplete="off" class="crm_color_picker crm_form_bg_hex" data-color="self" data-rel="form_bg"  value="<?php echo $options['form_bg_hex']; ?>" data-opacity="<?php echo $this->op_val('form_bg_op',$options); ?>">
<input type="hidden" name="settings[form_bg_op]" class="form_bg_op"  value="<?php echo $this->op_val('form_bg_op',$options); ?>">
<input type="hidden" name="settings[form_bg]" class="crm_form_bg"  value="<?php echo $options['form_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                            <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Fields Background</label>
                  <div class="crm-panel-description">Choose background color of form body. </div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[form_body_bg_hex]"  autocomplete="off" class="crm_color_picker crm_form_body_hex"  data-rel="form_body_bg"  value="<?php echo $options['form_body_bg_hex']; ?>" data-opacity="<?php echo $this->op_val('form_body_bg_op',$options); ?>">
<input type="hidden" name="settings[form_body_bg_op]" class="form_body_bg_op"  value="<?php echo $this->op_val('form_body_bg_op',$options); ?>">
<input type="hidden" name="settings[form_body_bg]" class="crm_form_body_bg"  value="<?php echo $options['form_body_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>              
          
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Form Width</label>
                  <div class="crm-panel-description">Choose form width.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[width]" class="input crm_width"  value="<?php echo $options['width']; ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
          
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Max Width</label>
                  <div class="crm-panel-description">maximum form width.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[max_width]" class="input"  value="<?php echo cfx_form::post('max_width',$options); ?>">
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              </div> </div>
<div class="crm_panel crm_sort">
    <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Success and Error Message</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
                    
                    <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Success Border Color</label>
                  <div class="crm-panel-description">Border Color of Success Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[msg_border_hex]"  autocomplete="off" class="crm_color_picker" data-rel="msg_border_color" value="<?php echo $options['msg_border_hex']; ?>" data-opacity="<?php echo !isset($options['msg_border_op']) ? "1" : $options['msg_border_op']; ?>">
<input type="hidden" name="settings[msg_border_op]" class="msg_border_color_op"  value="<?php echo $options['msg_border_op']; ?>">
<input type="hidden" name="settings[msg_border]" class="msg_border_color_rgba"  value="<?php echo $options['msg_border']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Success BG Color</label>
                  <div class="crm-panel-description">Background Color of Success Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[msg_bg_hex]"  autocomplete="off" class="crm_color_picker" data-rel="msg_bg_color" value="<?php echo $options['msg_bg_hex']; ?>" data-opacity="<?php echo !isset($options['msg_bg_op']) ? "1" : $options['msg_bg_op']; ?>">
<input type="hidden" name="settings[msg_bg_op]" class="msg_bg_color_op"  value="<?php echo $options['msg_bg_op']; ?>">
<input type="hidden" name="settings[msg_bg]" class="msg_bg_color_rgba"  value="<?php echo $options['msg_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Success Text Color</label>
                  <div class="crm-panel-description">Text Color of Success Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[msg_text_hex]"  autocomplete="off" class="crm_color_picker" data-rel="msg_text_color" value="<?php echo $options['msg_text_hex']; ?>" data-opacity="<?php echo !isset($options['msg_text_op']) ? "1" : $options['msg_text_op']; ?>">
<input type="hidden" name="settings[msg_text_op]" class="msg_text_color_op"  value="<?php echo $options['msg_text_op']; ?>">
<input type="hidden" name="settings[msg_text]" class="msg_text_color_rgba"  value="<?php echo $options['msg_text']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Error Border Color</label>
                  <div class="crm-panel-description">Border Color of Error Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[error_border_hex]"  autocomplete="off" class="crm_color_picker" data-rel="error_border_color" value="<?php echo $options['error_border_hex']; ?>" data-opacity="<?php echo !isset($options['error_border_op']) ? "1" : $options['error_border_op']; ?>">
<input type="hidden" name="settings[error_border_op]" class="error_border_color_op"  value="<?php echo $options['error_border_op']; ?>">
<input type="hidden" name="settings[error_border]" class="error_border_color_rgba"  value="<?php echo $options['error_border']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Error BG Color</label>
                  <div class="crm-panel-description">Background Color of Error Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[error_bg_hex]"  autocomplete="off" class="crm_color_picker" data-rel="error_bg_color" value="<?php echo $options['error_bg_hex']; ?>" data-opacity="<?php echo !isset($options['error_bg_op']) ? "1" : $options['error_bg_op']; ?>">
<input type="hidden" name="settings[error_bg_op]" class="error_bg_color_op"  value="<?php echo $options['error_bg_op']; ?>">
<input type="hidden" name="settings[error_bg]" class="error_bg_color_rgba"  value="<?php echo $options['error_bg']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Error Text Color</label>
                  <div class="crm-panel-description">Text Color of Error Message.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[error_text_hex]"  autocomplete="off" class="crm_color_picker" data-rel="error_text_color" value="<?php echo $options['error_text_hex']; ?>" data-opacity="<?php echo !isset($options['error_text_op']) ? "1" : $options['error_text_op']; ?>">
<input type="hidden" name="settings[error_text_op]" class="error_text_color_op"  value="<?php echo $options['error_text_op']; ?>">
<input type="hidden" name="settings[error_text]" class="error_text_color_rgba"  value="<?php echo $options['error_text']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Message Border Width</label>
                  <div class="crm-panel-description">Border Width of both success and error message.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[msg_border_width]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('msg_border_width',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('msg_border_width',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
             <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Message Font Size</label>
                  <div class="crm-panel-description">Font size of both success and error message.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[msg_font_size]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('msg_font_size',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('msg_font_size',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Message Font Style</label>
                  <div class="crm-panel-description">Font Style of both messages.</div>
                </div>
                <div class="col_val">
                  <div>
             <select name="settings[msg_font_style]">
                      <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['msg_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Vertical Padding</label>
                  <div class="crm-panel-description">Top and Bottom Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[msg_padding_y]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('msg_padding_y',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('msg_padding_y',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Horizontal Padding</label>
                  <div class="crm-panel-description">Left and Right Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[msg_padding_x]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('msg_padding_x',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('msg_padding_x',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>   
              
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Top Margin</label>
                  <div class="crm-panel-description">Space above Message.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[msg_top_margin]"   data-slider-range="0,100"   class="vis_slider"  value="<?php echo (int)cfx_form::post('msg_top_margin',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('msg_top_margin',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Required field's Star Color</label>
                  <div class="crm-panel-description">Color of star in required field labels.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[star_hex]"  autocomplete="off" class="crm_color_picker" data-rel="star_color" value="<?php echo cfx_form::post('star_hex',$options); ?>" data-opacity="<?php echo !isset($options['star_op']) ? "1" : $options['star_op']; ?>">
<input type="hidden" name="settings[star_op]" class="star_color_op"  value="<?php echo cfx_form::post('star_op',$options); ?>">
<input type="hidden" name="settings[star]" class="star_color_rgba"  value="<?php echo cfx_form::post('star',$options); ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              
            </div></div>  

<div class="crm_panel crm_sort">
    <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Steps for Multi-page forms</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
             
                  
                   <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Progress Text Color</label>
                  <div class="crm-panel-description">Text Color of Progress Bar.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[prog_text_hex]"  autocomplete="off" class="crm_color_picker" data-rel="prog_text" value="<?php echo $options['prog_text_hex']; ?>" data-opacity="<?php echo !isset($options['prog_text_op']) ? "1" : $options['prog_text_op']; ?>">
<input type="hidden" name="settings[prog_text_op]" class="prog_text_op"  value="<?php echo $options['prog_text_op']; ?>">
<input type="hidden" name="settings[prog_text]" class="prog_text_rgba"  value="<?php echo $options['prog_text']; ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              
             <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Progress Font Size</label>
                  <div class="crm-panel-description">Font size of Progress bar.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[prog_font_size]"   data-slider-range="5,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('prog_font_size',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
<span class="vis_output"><?php echo (int)cfx_form::post('prog_font_size',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label">Progress Font Style</label>
                  <div class="crm-panel-description">Font Style of Progress bar.</div>
                </div>
                <div class="col_val">
                  <div>
             <select name="settings[prog_font_style]">
                      <?php
 foreach($font_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['prog_font_style'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Vertical Padding</label>
                  <div class="crm-panel-description">Top and Bottom Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[prog_padding_y]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('prog_padding_y',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('prog_padding_y',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Horizontal Padding</label>
                  <div class="crm-panel-description">Left and Right Padding.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[prog_padding_x]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('prog_padding_x',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('prog_padding_x',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>   
              
            
             <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Progress bar Top Margin</label>
                  <div class="crm-panel-description">Progress bar Top Margin.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[prog_top]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('prog_top',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('prog_top',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
              
              <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Progress bar Height</label>
                  <div class="crm-panel-description">Height of Progress bar.</div>
                </div>
                <div class="col_val">
                  <div>
                    <input type="text" name="settings[prog_height]"   data-slider-range="0,60"   class="vis_slider"  value="<?php echo (int)cfx_form::post('prog_height',$options); ?>"  autocomplete="off">
                    <div class="vis_slide_div">
                      <div class="vis_slide"></div>
                      <span class="vis_output"><?php echo (int)cfx_form::post('prog_height',$options);?></span></div>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>        
              
               <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Progress bar Color</label>
                  <div class="crm-panel-description">Color of progress bar for multi-page form.</div>
                </div>
                <div class="col_val">
                  <div>
<input type="text" name="settings[prog_hex]"  autocomplete="off" class="crm_color_picker" data-rel="prog_color" value="<?php echo cfx_form::post('prog_hex',$options); ?>" data-opacity="<?php echo !isset($options['prog_op']) ? "1" : $options['prog_op']; ?>">
<input type="hidden" name="settings[prog_op]" class="prog_color_op"  value="<?php echo cfx_form::post('prog_op',$options); ?>">
<input type="hidden" name="settings[prog_color]" class="prog_color_rgba"  value="<?php echo cfx_form::post('prog_color',$options); ?>">
                    </div>
                </div>
                <div style="clear: both;"></div>
              </div>
           
                <div  class="crm-panel-field" >
                <div class="col_label">
                  <label class="crm_text_label"><?php _e('Progress Indicator','crmperks-forms'); ?></label>
                  <div class="crm-panel-description"><?php _e('Choose Progress Indicator for form','crmperks-forms'); ?></div>
                </div>
                <div class="col_val">
                  <div>
             <select name="settings[prog_type]">
                      <?php
      $prog_styles=array(''=>__('Progress bar','crmperks-forms'),'no'=>__('Do not display any indicator','crmperks-forms'));             
 foreach($prog_styles as $style_key=>$style){
     $sel="";
     if($style_key == $options['prog_type'])
     $sel='selected="selected"';
     echo "<option value='$style_key' $sel >$style</option>";
 }   
?>
                    </select>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
                 
              
            </div></div>  
                                
<div class="crm_panel crm_sort">
            <div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text">Custom CSS</span></div> <div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-plus crm_toggle_btn"></i>
            </div><div class="crm_clear"></div></div>
            <div class="crm_panel_content">
                <div class="crm-panel-field">
                <div class="col_label">
                  <label class="crm_text_label">Custom CSS</label>
                  <div class="crm-panel-description">Custom CSS - No Styling Tags</div>
                </div>
                <div class="col_val">
                  <div>
                    <textarea name="settings[css]" class="input" placeholder="Custom CSS - No Styling Tags"><?php echo $options['css'];?></textarea>
                  </div>
                </div>
                <div style="clear: both;"></div>
              </div>
       
                 
             
              
            </div></div>
            
              <div class="crm-panel-field">
              <h3 class="crm-panel-description">use <code> [crmperks-forms id=<?php echo $form_id; ?>] </code> as a shortcode to place it in a post or a page.</h3>
            </div>
            </div>
            <div style=" padding: 10px">
            <?php wp_nonce_field('vx_nonce','vx_nonce'); ?>
            <input type="hidden" name="form_id" value="<?php echo $form_id?>">
              <button type="submit" class="button-primary button button-hero main_submit"  style="float: left;"> <span class="reg_ok"><i class="fa fa-download"></i> Save</span> <span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Saving ...</span> </button>
   
            </div>
          </div> <!-- /STEP 3-->
    
        </form>
  <?php
                
                            $form_css="";
                            if($options['fonts_type'] == "" && $options['custom_family'] !=""){
                             $form_css="font-family: ".$options['custom_family'].';';   
                            }
                            if($options['fonts_type'] == "google" && $options['google_family'] !=""){
                             $form_css="font-family: ".$options['google_family'].';';   
                            }
                              if( !empty($options['outer_bg_type'])){        
                             $form_css.=' background-image: url('.$options['outer_img'].');';
                            } if(!empty($options['outer_bg'])){
                            $form_css.=' background-color: '.$options['outer_bg'].';';    
                            }if(!empty($options['outer_bg_size'])){
                            $form_css.=' background-size: '.$options['outer_bg_size'].';';    
                            }
                        ?>              
 
<div class="vx_preview_div crm_drag" id="cfx_form_preview">
<div id="crm_toggle_preview" class="crm_panel_head" style="background-color: #3670aa; border-color: #ddd; color: #fff;"><div class="crm_head_div">
<span class="crm_head_text" style="color: #fff;">Design Preview</span>
</div>
            
<div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-minus crm_toggle_preview"></i></div>
<div class="crm_clear"></div>
</div>

<div class="crm_panel_content crm_theme_bg" style="position: relative; display: block; height: calc(100% - 36px);">
<div class="crm_theme" style="overflow:auto; padding: 12px; height: 100%;">              
<div class="cfx_form_div">
<div class="cfx_form_inner">
                  <div class="cfx_form_head">
                  <span class="crm_head_spans crm_head_span_" style="<?php if($options['head_type'] != ""){echo "display:none";} ?>"><?php echo $options['heading']; ?></span>
                  <span class="crm_head_spans crm_head_span_image" style="<?php if($options['head_type'] != "image"){echo "display:none";} ?>"><img src="<?php echo $options['head_img']?>" style="<?php if(!empty($options['head_img_width'])){ echo 'max-width:'.$options['head_img_width'].';'; } ?>" class="cfx_head_img"></span>
                
                      <span class="crm_head_spans crm_head_span_html" style="<?php if($options['head_type'] != "html"){echo "display:none";} ?>"><?php echo $head_html; ?></span>
                        
                  </div>
                  <div class="crm_form_con">
                    <div class="crm_form_body">
                      <?php
                      
    $fields=vx_form_plugin()->form->form_fields_html($form); 
  /// $fields="";
    if($fields == ""){
?>
                      <div class="crm_form_row_wrap">
                        <div class="col12">
                          <label class="cfx_form_label">First Name</label>
                        </div>
                        <div class="col12">
                          <input type="text" class="cfx_input">
                        </div>
                        <div class="cfx_desc">Field Description here</div>
                      </div>
                      <div class="crm_form_row_wrap">
                        <div class="col12">
                          <label  class="cfx_form_label">Last Name</label>
                        </div>
                        <div class="col12">
                          <input type="text"  class="cfx_input">
                        </div>
                          <div class="cfx_desc">Field Description here</div>
                      </div>
                      <?php
    }else{
        echo $fields;
    }
?>
 <div style="clear: both"></div>
                    </div>
                  <div class="crm_form_footer cfx_submit_wrap">
                      <button id="cfx_footer_submit" class="crm_btn cfx_submit" style="<?php if($options['button_type'] !=""){echo "display:none";} ?>" type="button"><?php echo $options['submit_text']; ?></button>
                  <img class="crm_img_btn" style="<?php if($options['button_type'] !="image"){echo "display:none";} ?>" src="<?php echo $options['button_img']; ?>">    
                    </div>
                  </div>
                  </div>
</div>
<div style="clear: both; height: 12px;"></div>
</div>             
 
 <div id="cfx_toggle_fields_btn">
<a href="#" id="crm_show_fields" class="more_fields_toggle">Show All Fields</a>
<a href="#" id="crm_hide_fields" class="more_fields_toggle">Show Two Fields Only</a>
</div> 
</div>


  
         
</div> 
<script type="text/javascript">
var cfx_base_url='<?php echo cfx_form_plugin_url ?>';
var cfx_btn_url='<?php echo $options['button_img'] ?>';
jQuery(document).ready(function($){

$("#cfx_form_preview").draggable({stop: function(e,ui) {
  
    return $(this).css({
      height: 'auto'
    });
  }});
$('#cfx_form_preview').css({'height':'auto'});
$(document).on('click','.crm_toggle_preview,#crm_toggle_preview',function(e){
    e.stopPropagation();
  //  if($(this).attr('id') == 'crm_toggle_preview'){
$('#cfx_form_preview').css({'height':'auto'});
  //  }
var panel=jQuery(".vx_preview_div");
var div=panel.find(".crm_panel_content");
var btn=panel.find('.crm_toggle_preview');

   if(!div.is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus'); 
 div.slideDown('fast');  
  }else{
      div.slideUp('fast');       
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }
  
});

 
         
     })
</script> 
    
<style type="text/css">
.vx_preview_div *{
    webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
#cfx_toggle_fields_btn{
    position:absolute; bottom: 20px; right: 26px; background-color: #ddd; padding: 3px 6px;
}
.vx_preview_div .cfx_prog_wrap , .vx_preview_div .vx_google_cap , .vx_preview_div .vx_custom_captcha {
    display: none;
}
.vx_preview_div .crm_panel_content {
    position: relative;
}
#crm-panel .vx_preview_div .crm_panel_content{
    padding: 0;
}
.vx_preview_div .cfx_form_div{
    /*max-width: 100%;*/
}
.vx_preview_div{
    width: 400px;
    max-height: 85vh;
    position: fixed;
    bottom: 0;
    right: 6px;
}
.vx_preview_div .crm_head_div{
    cursor: move;
}
.crm_toggle_preview{
    cursor: pointer;
}
#cfx_form_preview{
    height: auto !important;
}
#crm-panel-content .crm_theme .cfx_form_div{
 <?php echo $form_css; ?>   
}
</style>
<?php cfx_form_front::footer_css(); ?>     
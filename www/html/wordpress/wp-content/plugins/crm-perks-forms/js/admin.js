/**
 * Form Plugin functions
 * @since        Version 1.0
 */
// less={globalVars:{'@head_bg':'#000'}};
jQuery(document).ready(function($){
apply_date_picker($( ".sales_date" ));

if(typeof less != 'undefined' ){
var link  = document.createElement('link');
link.rel  = "stylesheet";
link.type = "text/less";
link.href = cfx_base_url+'css/theme.less?t='+Math.random(); //Math.random()
less.sheets.push(link); ///alert("loadded ...");
update_theme({update:true});   
hide_fields("");
update_border();    
update_footer();

$(".select_action").each(function(){
   update_font_style(this);   
});
$(".check_action_alt").each(function(){
var id=$(this).attr('id');
update_check_alt(id);    
});
jQuery(".toggle:checked").each(function(){
    handle_toggle(this);  
});
$("#crm_load_theme").hide();     
$("#crm_theme").fadeIn('slow');   
}
         
var interval="";
if($('#crm_font_family').length){
       $('#crm_font_family').fontselect().change(function(){
          // replace + signs with spaces for css
          var font = $(this).val().replace(/\+/g, ' ');
          // split font into family and weight
          font = font.split(':');
          // set family on paragraphs 
          $('.cfx_form_div').css('font-family', font[0]);
        });
}
     
if($(".crm_color_picker").length){
$(".crm_color_picker").minicolors({ opacity:true, change: function(hex,op){ apply_color(this,hex,op) }  });
}
if($(".crm_color_picker_n").length){
$(".crm_color_picker_n").minicolors({ change: function(hex,op){ apply_color(this,hex,op) } });
}
function apply_color(elem,hex,op){
      //  console.log($(this).minicolors('rgbaString'));
        // event = standard jQuery event, produced by whichever control was changed.
        // ui = standard jQuery UI object, with a color member containing a Color.js object
        // change the headline color
        var less=true;
      //  if(hex){
         var c_color=chroma(hex);
       // }
        var rel=$(elem).attr('data-rel');

        var rgba=$(elem).minicolors('rgbaString');
       if($("."+rel+"_op").length){
        $("."+rel+"_op").val(op);
       }
switch(rel){
       case"crm_head":
//$(".crm_head").css({'background-color':rgba});       
//$(".crm_head").css({'border-color':c_color.darker(10).hex()});       
        $(".crm_head_bg").val(rgba);
        $(".crm_head_border").val(c_color.darker(10).hex());
       break; 
       case"crm_head_hover":     
        $(".crm_head_hover").val(rgba);
       break;  
       case"head_text":
$(".cfx_form_head").css({'color':rgba});            
  $(".crm_head_text_color").val(rgba); 
  less=false;     
       break;  
  case"desc_text":
$(".cfx_desc").css({'color':rgba});            
  $(".crm_desc_text_color").val(rgba); 
  less=false;     
       break;    
       case"btn_bg":
///$(".crm_btn").css({'background-color':rgba});            
$(".crm_btn_bg").val(rgba);      
       break; 
case"btn_hover":                 
$(".crm_btn_hover").val(rgba);      
$(".crm_btn_focus").val(c_color.darker(4).alpha(op).css());      
       break;  
       case"btn_border_hover":                 
$(".crm_btn_border_hover").val(hex);      
$(".crm_btn_border_focus").val(c_color.darker(4).hex());      
       break;    
     case"btn_text":
$(".crm_btn").css({'color':rgba});            
$(".crm_btn_text").val(rgba);      
less=false;
       break;   
     case"input_focus":          
$(".crm_input_hover").val(c_color.darker(-12).hex());      
       break;   
case"border_color":
$(".crm_form_con").css({'border-color':hex});            
less=false;      
       break;  
case"border_color":
$(".crm_form_con").css({'border-color':hex});
less=false;                  
       break;
        
case"form_bg_outer":
$(".cfx_form_div").css({'background-color':rgba});
$(".form_bg_outer_bg").val(rgba);
less=false;                  
break;    
case"form_bg":
$(".cfx_form_inner").css({'background-color':rgba});
$(".crm_form_bg").val(rgba);
less=false;                  
break; 
       
       case"form_body_bg":
$(".crm_form_body").css({'background-color':rgba});
$(".crm_form_body_bg").val(rgba);
less=false;                  
       break; 
       case"font_color":
$(".cfx_form_label").css({'color':rgba});
$(".crm_font_color").val(rgba);
less=false;                  
       break;
       case"input_bg":
$(".crm_input_bg").val(rgba);  
       break;
       case"input_font_color":
$(".cfx_input").css('color',rgba);  
       break;
       
       case"input_bg_focus":
$(".crm_input_bg_focus").val(rgba);  
       break;
       case"footer_bg":
$(".crm_form_footer").css({'background-color':rgba});                  
$(".crm_footer_bg").val(rgba);
less=false;                  
       break; 
       case"footer_border":
$(".crm_form_footer").css({'border-top-color':hex}); 
       break;
case"body_bg":
$(".cfx_form_div").css({'background-color':rgba});
break;
case'btn_border_color': less=true;    break;
default:

if($.inArray(rel,['btn_border_color','input_color','place_color'])==-1){
// less=false;  
}
  break; 
}

   if($("."+rel+"_rgba").length){
        $("."+rel+"_rgba").val(rgba);
   } 
        if(less){
clearTimeout(interval);
        interval=setTimeout(function(){
          ///  var c=ui.color.toString();
      var re={update:true};
      if(rel == "crm_head" && $("#reset_crm_theme").is(":checked"))
      re={color: rgba}    
   update_theme(re);   
},300);
}
}

         
$("#vx_btn_type").change(function(e){
  var val=$(this).val();
  if(val == 'html'){
  $('#vx_btn_div_html').show();     
  $('#vx_btn_div_fixed').hide();     
  }else if(val != ''){
  $('#vx_btn_div_html').hide();     
  $('#vx_btn_div_fixed').show();        
  }else{
  $('#vx_btn_div_html').hide(); 
   $('#vx_btn_div_fixed').hide();    
  }
   
});         
$("#vx_auto_open_sel").change(function(e){
  var val=$(this).val();
  $('.vx_auto_open').hide();
  $('#vx_auto_open_'+val).show();
   
});
$("#vx_sel_admin_email").change(function(e){
  var val=$(this).val();
  if(val == 'custom'){
	$('#vx_sel_admin_email_div').show();  
  }else{
	  $('#vx_sel_admin_email_div').hide(); 
  }
   
});

$(document).on('click','.vx_images_ul li',function(e){
        $('.vx_images_li_sel').removeClass('vx_images_li_sel');
        $(this).addClass('vx_images_li_sel');
       $('#loc_btn_icon').val($(this).attr('data-id')); 
    //   var src=$(this).find('img').attr('src');
//      $('#vx_main_chat_btn').css('background-image', 'url('+src+')'); 
    //vx_images_ul li.vx_images_li_sel
    });

$(document).on("click",".more_fields_toggle",function(e){
     e.preventDefault(); 
     hide_fields($(this));    
});

function hide_fields(elem){
 if($(".crm_form_row_wrap").length<3){
 $("#crm_hide_fields").hide();      
 $("#crm_show_fields").hide();
 return;     
 }
   if(elem== "" || elem.attr('id') == "crm_hide_fields"){
    ///hide more fields here
 $("#crm_hide_fields").hide();      
 $("#crm_show_fields").show();      
 $(".crm_form_row_wrap:gt(1)").hide();      
   }else{
  $("#crm_hide_fields").show();      
 $("#crm_show_fields").hide();      
 $(".crm_form_row_wrap").show();       
   } 
   $('#cfx_form_preview .crm_theme').css({'max-height':$(window).height()-100});
}

$(".fonts_family_select").change(function(){
$('.cfx_form_div').css('font-family', $(this).val());   
});
$(document).on("click",'.show_custom_fields',function(e) {
     e.preventDefault();
 $("#custom_fields_div").slideDown();
 $(this).hide();   
});
$(document).on("click",'.crm_merge_tags',function(e) {
     e.preventDefault();
     $('.active_merge_field').removeClass('active_merge_field');
     $(this).parents(".crm-panel-field").addClass('active_merge_field');
$("#merge_tags_list").css({top:$(this).position().top+26,left:$(this).position().left-100});
 $("#merge_tags_list").slideDown('fast');
  $(".crm_overlay").show();
});

$(document).on("click",'#merge_tags_list span',function(e) {
     e.preventDefault();
     var title=$(this).attr('title');
     var text=$('.active_merge_field').find('.text');
     if(!text.length){
     text=$('.active_merge_field').find("textarea");    
     }
     var val=text.val();
     text.val(val+" {"+title+"}");
     var editor = tinymce.editors[text.attr('id')];
if (editor) {
  var text=editor.getContent();
  editor.setContent(text+" {"+title+"}");
}
      $(".crm_overlay_divs").hide();   
 $(".crm_overlay").hide();
});

$(".crm_overlay").click(function(){
 $(".crm_overlay_divs").hide();   
 $(".crm_overlay").hide(); 
 if($(".fs-drop").is(":visible")){
 $(".font-select-active b").click();    
 }  
});
$(document).keydown(function(e) {
  if (e.keyCode == 27) { 
 $(".crm_overlay_divs").hide();   
 $(".crm_overlay").hide(); 
 if($(".fs-drop").is(":visible")){
 $(".font-select-active b").click();    
 } 
  }   // escape key maps to keycode `27`
});

$(document).on("click",'.close_custom_fields',function(e) {
     e.preventDefault();
 $("#custom_fields_div").slideUp();
 $('.show_custom_fields').show();   
});


$(document).on("click",'.sel_all_fields',function(e) {
    if($(this).is(":checked")){
 $("#custom_fields_div .fields_check").prop('checked',true);
 //$(".fields_check_span").text('Unselect All Fields');
    }
 else{
 $("#custom_fields_div .fields_check").prop('checked',false);
 // $(".fields_check_span").text('Select All Fields');
 }  
});


    
 function update_fonts_color(){
 if(!$("#remove_fonts_color").is(":checked"))
$(".crm_form_body").find("label").addClass("cfx_form_label");
else     
$(".crm_form_body").find("label").removeClass("cfx_form_label");     
 }  
 function update_check_alt(id){
var checked=false;
 if($("#"+id).is(":checked")){ checked=true}
var rel=$("#"+id).attr('data-rel');
 switch(rel){
     case'cfx_form_label':
  var elem=$('.crm_form_body .cfx_form_label');   
if(!checked){ elem.show(); }else{elem.hide();}    
     break;
     case'hide_footer':
  var elem=$('.crm_form_footer');   
if(checked){ elem.show(); }else{elem.hide();}    
     break;
 }   
 }  

///////////////////

  $(".check_action").click(function(){
   var action=$(this).attr('data-rel');
update_theme({update:true});   
  });
     $(".check_action_alt").click(function(){
   var id=$(this).attr('id');
update_check_alt(id);   
  })  
  $("#remove_fonts_color").click(function(){
update_fonts_color(); 
  })
  $(".select_action").change(function(){
      update_font_style(this);
  })

  $(".form_border").change(function(){
update_border();  
  });  
 $("#remove_footer_border").click(function(){
update_footer();  
  });  

$(".vis_slider").each(function(){
      var input=$(this);
      var val=input.val();
    ////  
      var ran=input.attr('data-slider-range').split(",");
      var min=parseInt(ran[0]);
      var max=parseInt(ran[1]);
      var action=input.attr('data-action');
      var rel=input.attr('data-rel');
      update_slider_data(action,rel,val)
///
   var col_val=$(this).parents(".col_val");
   var slide=col_val.find(".vis_slide");
   var output=col_val.find(".vis_output");
      var slider =slide.slider({
            min: min,
            max: max,
         range: "min",
            value: val,
            slide: function( event, ui ) { 
input.val(ui.value); output.text(ui.value);
   update_slider_data(action,rel,ui.value);          
            }
        });    
  });
$(".point_slider").each(function(){
  var col_val=$(this).parents(".col_val");
   var slide=col_val.find(".vis_slide");
   var output=col_val.find(".vis_output");
   var input=$(this);
   var val=$(this).val();
slide.slider({
            min:0,
            max:1,
         range: "min",
         step:0.1,
            value:val,
            slide: function( event, ui ) { 
input.val(ui.value); output.text(ui.value);         
            }
        });
    }); 
$(".lightbox_pos").change(function(){
var val=$(this).val();
if($.inArray(val,["top_left","bottom_left"])!=-1){
  $(".left_pos").show();      
    }else{
    $(".left_pos").hide();  
    }
if($.inArray(val,["bottom_right","top_right"])!=-1){
  $(".right_pos").show();      
    }else{
    $(".right_pos").hide();  
    }
if($.inArray(val,["top_left","top_right","top"])!=-1){
  $(".top_pos").show();      
    }else{
    $(".top_pos").hide();  
    }
    if($.inArray(val,["bottom_left","bottom_right","bottom"])!=-1){
  $(".bottom_pos").show();      
    }else{
    $(".bottom_pos").hide();  
    }          
});    
function update_slider_data(action,rel,val){
if(!rel){ return; }
      switch(action){
          case"head_font_size":
      $("."+rel).css({'font-size':val+"px"}); 
          break;   
           case"head_height":
      $("."+rel).css({'line-height':val+"px"}); 
          break; 
      case"head_padding_v":
      $("."+rel).css({'padding-top':val+"px",'padding-bottom':val+"px"}); 
          break; 
      case"head_padding_h":
      $("."+rel).css({'padding-left':val+"px",'padding-right':val+"px"}); 
          break; 
      case"submit_pad_h":
      $("."+rel).css({'padding-left':val+"px",'padding-right':val+"px"}); 
          break; 
           case"head_bottom_border":
           update_head_border($("."+rel),$('.head_border_type').val(),val);
          break; 
           case"submit_width":
            if(jQuery('.adjust_submit_button:checked').val() == "custom"){ 
      $("."+rel).css({'width':val+"%"});
            }
          break; 
             case"submit_height": 
              if(jQuery('.adjust_submit_button:checked').val() == "custom"){       
      $("."+rel).css({'line-height':val+"px"}); 
              }
          break;
             case"submit_font_size":
      $("."+rel).css({'font-size':val+"px"}); 
          break;

             case"outer_padding_y": 
      $("."+rel).css({'padding-top':val+'px','padding-bottom':val+'px'}); 
          break;
            case"outer_padding_x": 
      $("."+rel).css({'padding-left':val+'px','padding-right':val+'px'}); 
          break;
             case"desc_font_size": 
      $("."+rel).css({'font-size':val}); 
          break;
             case"input_height":
             $("."+rel).css({'height':val+'px'});
          break;
          case"input_pad":
             $("."+rel).css({'padding-left':val+'px','padding-right':val+'px'});
          break;
          case"input_pad_y":
             $("."+rel).css({'padding-top':val+'px','padding-bottom':val+'px'});
          break;
              case"input_border_width":
              update_head_border($("."+rel),$('.input_border_type').val(),val);
          break;
               case"input_border_radius":
             $(".cfx_input").css({'-moz-border-radius':val+'px','-webkit-border-radius':val+'px','border-radius':val+'px'});
          break;
      case"border_width":
      var top_width=$('.crm_border_top_width').val();
$(".crm_form_con").css({'border-left-width':val+'px','border-right-width':val+'px','border-bottom-width':val+'px','border-top-width':top_width+'px'});
      break;
          case"body_padding_top":
             $("."+rel).css({'padding-top':val+'px','padding-bottom':val+'px'});
          break;
          case"body_padding_left":
             $("."+rel).css({'padding-left':val+'px','padding-right':val+'px'});
          break;
          case"body_font_size":
      $(".crm_form_body .cfx_form_label").css({'font-size':val+"px",'line-height':val+"px"}); 
          break;
          case"label_bottom_margin":
      $(".crm_form_body .cfx_form_label").css({'margin-bottom':val+"px"}); 
          break;
          case"head_radius":
      $(".cfx_form_div").css({'border-top-left-radius':val+"px",'border-top-right-radius':val+"px"});    
          break;   
      case'footer_padding':
      $("."+rel).css({'padding-top':val+"px",'padding-bottom':val+"px"});    
          break;
          case"footer_radius":
      $(".crm_form_con").css({'border-bottom-left-radius':val+"px",'border-bottom-right-radius':val+"px"});    
      $(".cfx_form_div").css({'border-bottom-left-radius':val+"px",'border-bottom-right-radius':val+"px"});    
          break;
          default:
        $("."+rel).css(action,parseInt(val));      
          break;
      }
}
 


var global_function="";
$(".crm_form").submit(function(e){
    e.preventDefault();
    if(typeof tinymce != 'undefined'){
    jQuery.each(tinymce.editors,function(){ 
        $('#'+this.id).val(this.getContent());
    });
    }
    var form=$(this);
    var button=form.find(".main_submit");
    button_state("ajax",button);
    var form_arr=form.serializeArray();
    form_arr.push({name:'action',value:'vx_form_save_main_form'})
    $.post(ajaxurl,form_arr
    ,function(res){
     button_state("ok",button);   
       var re={}; try{re=$.parseJSON(res);}catch(e){}
       if(!re || !re.status || re.status!='ok' ){
           alert(res || 'Error While Saving Data');
       }

    })   
});
$(document).on('keyup','.field_label_value',function(e){
   var panel=$(this).parents('.crm_panel');
var val=$(this).val();
var head=panel.find('.crm_head_text');
head.find('.crm_text_label').html(val);
}); 
  

$(document).on("click",".footer_border_check",function(){
update_footer();
});
$(".crm_select_img").click(function(e){
     e.preventDefault();
     var button=$(this);
         // Media Library params
        var frame = wp.media({
            title : 'Select Image',
            multiple : false,
            library : { type : 'image'},
            button : { text : 'Insert' }
        });
        // Runs on select
        frame.on('select',function(){
            var wp_img= frame.state().get('selection').first().toJSON();
          //  var arrImages = [];
         //   onInsert(objSettings.url,objSettings.id);
         var file_area=button.parents(".crm_file_area");
         file_area.find("img").attr("src",wp_img.url);
         file_area.find(".crm_img_name").val(wp_img.url);
         var rel=button.attr('data-rel'); 
         if(rel){
             switch(rel){
              case"crm_bg_img":
 jQuery(".cfx_form_div").css("background-image",'url('+wp_img.url+')');  
              break;
              default:
              jQuery("."+rel).attr("src",wp_img.url);   
              break;   
             } 
         }           
        });
        // Open ML
        frame.open();   
})





$("#menu_button").click(function(){
var bar=$("#crm-panel-sidebar");
if(bar.is(":visible")){
    bar.hide();
}else{
    bar.show();
}    
});

$(document).on("click",".adjust_submit_button",function(){
 if($(this).val() == ""){
 $(".crm_btn").css({width:'auto',height:'auto'});    
 }else{
  $(".crm_btn").css({width:$(".submit_width").val()+"%",height:$(".submit_height").val()+"px"});    
 } 
});
$(document).on("click",".crm_toggle_btn",function(e){
     e.stopPropagation();
toggle_panel(this);
});
$(document).on("click",".crm_panel_head",function(){ //dblclick
if(!$(this).find('.crm_move').length){
toggle_panel(this);
}
});
$(document).on("change",".crm_time_select",function(){
    var input=$(this).parents("form").find(".crm_custom_range");
 if($(this).val() == "custom"){
     input.show();
 }else{
        input.hide();
 }   
});
$(".view_report").click(function(e){
    e.preventDefault();
    var tr=$(this).parents("tr");
    var next_tr=tr.next(tr);
    var td=next_tr.find("td");
    td.html("<div style='text-align:center'><i class='fa fa-circle-o-notch fa-spin' style='margin: 20px auto'></i></div>");
    next_tr.show();
    var form_id=$(this).attr('form-id');
    $.post(ajaxurl,{action:'vx_form_get_form_stats',form_id:form_id,vx_nonce:$("#vx_nonce").val()},function(res){
     td.html(res);   
    })
});




$(document).on("click",".crm_close_history",function(){
 $(this).parents(".crm_info_div").hide();   
 $(this).parents(".entry_td").find(".view_sf_history").show();   
})



$(document).on("click",".toggle_mark",function(e){
    e.preventDefault();
toggle_read($(this).parents("tr"),"");
});

$(".outer_bg_size").blur(function(){
var width=$(this).val();
$(".cfx_form_div").css({'background-size':width});    
});

$(".cfx_head_img_width").blur(function(){
var width=$(this).val();
$(".cfx_head_img").css({'max-width':width});    
});

$(".crm_heading_text").blur(function(){
var text=$(this).val();
$(".crm_head_span_").html(text);    
});
$(".crm_submit_text").blur(function(){
var text=$(this).val();
$('#cfx_footer_submit').html(text);    
})


jQuery(document).on("click",".show_more",function(e){
              e.preventDefault();
              var button=$(this);
              var options=$(this).parents('.crm_field').find(".more_options");
              if(options.is(":visible")){
                  options.hide();
                       button.text('Show Field Options');
                
              }else{
                  button.text('Hide Field Options'); 
                  options.show();
              }
})
 
jQuery(".switches").click(function(){
          var rel=jQuery(this).attr('data-rel');
              if(jQuery(this).is(":checked")){
          jQuery("#"+rel).show();    
         
              }else{
               jQuery("#"+rel).hide();
         /////////    
          }  
          });
       
jQuery(".toggle").click(function(){
    handle_toggle(this);  
});
function handle_toggle(elem){
            var hide=jQuery(elem).attr('data-hide');
          var show=jQuery(elem).attr('data-show');
          
          if(hide !=""){ jQuery("."+hide).hide();  }   
          if(show !=""){ jQuery("."+show).show(); }
          
          var rel=jQuery(elem).attr('data-rel');
          if(rel){
              var val=$(elem).val();
              switch(rel){
              case"btn_img":
              var img=jQuery(".crm_img_btn");
              var btn=jQuery(".crm_form_footer  .cfx_submit");
              if(val == ""){
             btn.show(); img.hide();     
              }else{
             if(!img.length){ btn.after('<img class="crm_img_btn" src="'+cfx_btn_url+'">'); }     
              btn.hide(); img.show();   
              }
              break;  
             case"crm_bg_img":
             if(val == ""){
           var img="none"; // 
            //  jQuery(".cfx_form_div").css("background-color",$('.form_bg_outer_bg').val());    
             }else{
           var img='url('+$(".crm_bg_img_name").val()+')';      
             }   
              jQuery(".cfx_form_div").css("background-image",img);
              break;  
              case"head_img": 
             var divs=jQuery(".crm_head_spans");
              var div=jQuery(".crm_head_span_"+val);
              divs.hide();
              div.show(); 
              break;
              }
          }
}
              var theme_added=false;
jQuery(".steps_button").click(function(e){
              if($(this).hasClass("wp-ui-active")){
                  return;
              }
          var id=$(this).attr('id');
          jQuery(".crm_content_panel").find(".steps").hide();    
          jQuery("#crm_load_theme").show();    
          jQuery(".crm_content_panel").find("."+id).show();     
          jQuery(".menu_panel").find(".wp-ui-highlight").removeClass("wp-ui-highlight");
          jQuery(".menu_panel").find("."+id).addClass("wp-ui-highlight"); 
          // window.location.hash=id+"_";      
          });

jQuery(".choose_thanks").change(function(e){
              e.preventDefault();
          var val=$(this).val();
          if(val == ""){val="msg";}
          jQuery(".thanks_msg").hide();
           jQuery(".crm_"+val).show();           
          });

///////////////////////////////////  
  $(".crm-sales-settings").submit(function(e){
    e.preventDefault();
    var form=$(this);
    var button=form.find(".main_submit");
    button_state("ajax",button);
    var form_arr=form.serializeArray();
    $.post(ajaxurl,form_arr
    ,function(res){
     button_state("ok",button);   
     var re={}; try{re=$.parseJSON(res);}catch(e){}
       if(!re || !re.status || re.status!='ok' ){
           alert(res || 'Error While Saving Data');
       }
   
    })   
}); 
                                                  
});
function toggle_panel(elem){
     var panel=jQuery(elem).parents(".crm_panel");
  /*   if(panel.hasClass("crm_drag")){
         panel.find("#crm_popup").click();
     }*/
 var div=panel.find(".crm_panel_content");
 var btn=panel.find(".crm_toggle_btn");
 div.slideToggle('fast',function(){
  if(jQuery(this).is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');     
  }else{
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }   
 });
}
function apply_date_picker(elem){
elem.datepicker({ changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "-100:+10",
        dateFormat: 'dd-M-yy'  });
}

function button_state(state,button){
var ok=button.find('.reg_ok');
var proc=button.find('.reg_proc');
     if(state == "ajax"){
          button.attr({'disabled':'disabled'});
ok.hide();
proc.show();
     }else{
         button.removeAttr('disabled');
   ok.show();
proc.hide();      
     }
}
function update_footer(){
      var value=jQuery(".form_footer").val();
    var custom_border=jQuery(".footer_border_check:checked").val();
    if(custom_border == ""){
     jQuery(".crm_form_footer").addClass('crm_bg_footer');
     jQuery(".crm_form_footer").css('border-top-width','0px');   
     jQuery(".cfx_form_inner").css('background-color','#fff');   
    }else{
   jQuery(".crm_form_footer").removeClass('crm_bg_footer'); 
    jQuery(".crm_form_footer").css('border-top-width',parseInt(jQuery("#footer_border_width").val())); 
    jQuery(".crm_form_footer").css('border-top-color',jQuery(".crm_footer_border").val()); 
      jQuery(".cfx_form_inner").css('background-color',jQuery(".crm_form_bg").val());        
    }   
}
function update_font_style(select){
   /// console.info("tt"+jQuery(select).attr('data-rel'));
        if(!jQuery(select).attr('data-rel'))
        return;
        var rel=jQuery(select).attr('data-rel'); //console.log(rel);
        var style=jQuery(select).val();
        var elem=jQuery("."+rel); 
  var data_action=jQuery(select).attr('data-action') || '';
       switch(data_action){
      case"crm_border_style":
    elem.css({'border-style':style}); 
      break;  
      case"crm_border_type":
var width=jQuery('#'+jQuery(select).attr('data-width')).val();
update_head_border(elem,style,width);
      break; 
    case"crm_border_style_less":
    update_theme({update:true});
      break;
      
      default:
            if(data_action){
            var action=jQuery(select).attr('data-action'); 
        elem.css(action,style);    
        }else{
          if(style == "bold")
     elem.css({'font-style':'normal','font-weight':'bold'});
    else if(style == "italic_n")
  elem.css({'font-style':'italic','font-weight':'normal'});
    else if(style == "italic_b")
  elem.css({'font-style':'italic','font-weight':'bold'});
         else 
elem.css({'font-style':'normal','font-weight':'normal'});
        }
      break;     
       }         
}

function update_head_border(elem,side_sel,width){
var sides=['top','left','bottom','right']; 
jQuery.each(sides,function(k,side){
 if(side_sel != 'all' && side != side_sel ){
 elem.css('border-'+side+'-width','0px');  
 }else{
 elem.css('border-'+side+'-width',width+'px');     
}
});
}
function update_border(){
         var val=jQuery(".form_border").val();
   if(val == "shadow"){
jQuery(".cfx_form_div").addClass('crm_form_shadow');       
jQuery(".cfx_form_div").removeClass('crm_form_border');       
   }else if(val == "no"){
jQuery(".cfx_form_div").removeClass('crm_form_shadow');       
jQuery(".cfx_form_div").removeClass('crm_form_border');       
   }else{
       jQuery(".cfx_form_div").removeClass('crm_form_shadow');       
jQuery(".cfx_form_div").addClass('crm_form_border'); 
   } 
  }
function update_theme(arr){
    var input_height= jQuery("#crm_input_height").val()  !="" ? jQuery("#crm_input_height").val()  : '38px';  
    var border_width= jQuery("#crm_border_width").val()  !="" ? jQuery("#crm_border_width").val()  : '1';  
    var input_border_width= jQuery("#crm_input_border_width").val()  !="" ? jQuery("#crm_input_border_width").val()  : '1';  
    var input_color=jQuery(".crm_input_color").val();
    var focus_border_style=jQuery(".crm_input_border_style_focus").val();
    var input_border_style=jQuery(".crm_input_border_style").val();
    var crm_form_body_bg=jQuery(".crm_form_body_bg").val();
    var head_bg=jQuery(".crm_color").val(); 
      if(arr.update && head_bg !=""){
     var head_bg=jQuery(".crm_head_bg").val();        
     var head_border_hover=jQuery(".crm_head_border_hover").val();     
     var head_hover=jQuery(".crm_head_hover").val();     
     var head_border=jQuery(".crm_head_border_color").val();     
     var border_color=jQuery(".crm_border_color").val();     
     var input_bg=jQuery(".crm_input_bg").val();     
     var input_bg_focus=jQuery(".crm_input_bg_focus").val();     
     var input_hover=jQuery(".crm_input_hover").val();     
     var input_focus=jQuery(".crm_input_focus").val();     
     var btn_bg=jQuery(".crm_btn_bg").val();     
     var btn_hover=jQuery(".crm_btn_hover").val();     
     var btn_focus=jQuery(".crm_btn_focus").val();     
     var btn_text=jQuery(".crm_btn_text").val();     
     var head_text=jQuery(".crm_head_text_color").val();     
   ////  var body_bg=jQuery(".crm_form_bg").val();          
     var body_text=jQuery(".crm_font_color").val();          
     var footer_bg=jQuery(".crm_footer_bg").val();          
     var footer_border_color=jQuery(".crm_footer_border_color").val();          
     var desc_color=jQuery(".crm_desc_text_color").val(); 
     var btn_border_color=jQuery(".crm_btn_border_color").val();
     var btn_border_hover=jQuery(".crm_btn_border_hover").val();         
     var btn_border_focus=jQuery(".crm_btn_border_focus").val();         
     var input_text_color=jQuery(".input_color_rgba").val();         
     var input_place_color=jQuery(".place_color_rgba").val();         
    // var input_margin=jQuery("#crm_input_margin").val();          
      }else{
        if(!arr.color){
        head_bg= head_bg == "" ? "#7abf04" : head_bg;    
        }else{
        var head_bg=arr.color;
        } 
 
        var dd=chroma(head_bg);
        var btn_bg=head_bg;
        var body_text=btn_border_hover=head_border=head_border_hover=footer_border_color=btn_hover=input_focus=dd.darker(10).hex();
        
        var input_hover=head_hover=dd.darker(-16).hex();
        var btn_focus=btn_border_focus=dd.darker(12).hex();
        var btn_border_color=dd.hex();
        var crm_head_rgba=dd.css('rgba');
        var head_hover_rgba=dd.darker(10).css('rgba');
        var input_bg_focus=dd.alpha(.3).css(); 
       /// var hover=dd.darker(-3).hex();
              var footer_bg="#f1f1f1";
 
    body_bg="#fff"; 
    border_color="#ccc"; input_bg="#f6f6f6"; btn_text=head_text="#fff";
       desc_color="#999"; 
        jQuery(".crm_head_bg").val(crm_head_rgba);
        jQuery(".crm_head_border_hover").val(head_border_hover);
        jQuery(".crm_head_hover").val(head_hover_rgba);
        jQuery(".crm_head_border_color").val(head_border);
      ////  jQuery(".crm_input_color").val(input_color);
    ////    jQuery(".crm_input_bg").val(input_bg);
        jQuery(".crm_input_bg_focus").val(input_bg_focus);
        jQuery(".input_bg_focus_hex").val(input_hover);
        jQuery(".input_bg_focus_hex").attr('data-opacity','.3');
       jQuery(".input_bg_focus_op").val('.3');
        jQuery(".crm_input_color").val(dd.hex());
        jQuery(".crm_input_focus").val(dd.darker(10).hex());
        jQuery(".crm_btn_bg").val(btn_bg);
        jQuery(".crm_btn_hover").val(btn_hover);
        jQuery(".crm_btn_border_color").val(btn_border_color);
        jQuery(".crm_btn_border_hover").val(btn_border_hover);
        jQuery(".crm_btn_border_focus").val(btn_border_focus);
     ////   jQuery(".crm_btn_text").val(btn_text);
    ////    jQuery(".crm_head_text_color").val(head_text);
        jQuery(".crm_font_color").val(body_text); 
     ////   jQuery(".crm_footer_bg_hex").val(footer_bg);
    ////    jQuery(".crm_footer_bg_hex").attr('data-opacity','0.8');
     //   jQuery(".crm_form_body_op").val('.3');
    //    jQuery(".crm_form_body").val(chroma(footer_bg).alpha(.3).css());
      ///  jQuery(".crm_form_bg").val(body_bg);
   ////     jQuery(".crm_border_color").val(border_color);   
     ////   jQuery(".crm_input_color").val(input_color);
        jQuery(".crm_footer_border_color").val(footer_border_color);
    ////    jQuery(".crm_form_bg_hex").val(body_bg);
    ////    jQuery(".crm_form_bg_hex").attr('data-opacity',1);
    ////   jQuery(".crm_form_body_hex").val('#fff');
     ////   jQuery(".crm_form_body_hex").attr('data-opacity','0');
       /*  jQuery(".crm_form_body_op").val('0');
        jQuery(".crm_form_body").val('rgba(255,255,255,0)');*/
        //update color picker
 
      } 

    if(!jQuery("#use_input_bg").is(":checked"))
    input_bg='';
    if(!jQuery("#use_input_bg_focus").is(":checked"))
    input_bg_focus='';
    if(typeof less != 'undefined'){
    less.modifyVars({            
  '@head_bg': head_bg,
  '@head_hover': head_hover,
  '@head_border':head_border, 
  '@head_border_hover':head_border_hover, 
  '@input_color':input_color, 
 // '@input_fonts_color':input_fonts_color, 
  '@input_place':input_place_color, 
  '@input_text':input_text_color, 
  '@input_hover':input_hover, 
  '@input_bg':input_bg, 
  '@input_bg_focus':input_bg_focus, 
  '@input_focus':input_focus, 
  '@input_border':input_border_width, 
  '@btn_bg':btn_bg, 
  '@btn_hover':btn_hover, 
  '@btn_focus':btn_focus, 
  '@btn_border_color':btn_border_color, 
  '@btn_border_hover':btn_border_hover, 
  '@btn_border_focus':btn_border_focus, 
  '@btn_text':btn_text, 
  '@head_text':head_text, 
  '@label_color':body_text, 
  '@footer_border_color':footer_border_color, 
  '@footer_bg':footer_bg, 
 '@desc_color':desc_color, 
  '@border_color':border_color, 
   '@input_border_style_focus':focus_border_style, 
   '@input_border_style':input_border_style, 
     '@form_body_bg':crm_form_body_bg
}); }
      jQuery(".crm_color_picker,.crm_color_picker_n").each(function(){
      if(jQuery(this).attr('data-color')){
          var color_class=jQuery(this).attr('data-color');
         var color="#fff";
          if(color_class !="self"){
          if(jQuery("."+color_class).val()!="")
          color=jQuery("."+color_class).val();
          }else{
          color=jQuery(this).val(); 
          } 
          try{
           c_color=chroma(color).hex();
         // console.info(color_class+"-----------"+color);
     jQuery(this).minicolors('value',c_color);  
          }catch(e){
        //      console.warn(color_class+"-----------"+color+"==============="+e+"+++++++"+c_color);
          }
      }       
      }) 
 }
  
function sf_colorbox(title,div,width,height){
    if(!width)
    width=400;
    if(!height)
    height=400;
    jQuery.colorbox({inline:true,escKey:true,overlayClose:true,href:div,maxWidth:'98%',width:width,height:height,title:title}); 
} 

/*{theme:"modern",skin:"lightgray",language:"en",formats:{alignleft: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"left"}},{selector: "img,table,dl.wp-caption", classes: "alignleft"}],aligncenter: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"center"}},{selector: "img,table,dl.wp-caption", classes: "aligncenter"}],alignright: [{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"right"}},{selector: "img,table,dl.wp-caption", classes: "alignright"}],strikethrough: {inline: "del"}},relative_urls:false,remove_script_host:false,convert_urls:false,browser_spellcheck:true,fix_list_elements:true,entities:"38,amp,60,lt,62,gt",entity_encoding:"raw",keep_styles:false,cache_suffix:"wp-mce-4506-20170408",preview_styles:"font-family font-size font-weight font-style text-decoration text-transform",end_container_on_empty_block:true,wpeditimage_disable_captions:false,wpeditimage_html5_captions:true,plugins:"charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,wpembed",wp_lang_attr:"en-US",wp_shortcut_labels:{"Heading 1":"access1","Heading 2":"access2","Heading 3":"access3","Heading 4":"access4","Heading 5":"access5","Heading 6":"access6","Paragraph":"access7","Blockquote":"accessQ","Underline":"metaU","Strikethrough":"accessD","Bold":"metaB","Italic":"metaI","Code":"accessX","Align center":"accessC","Align right":"accessR","Align left":"accessL","Justify":"accessJ","Cut":"metaX","Copy":"metaC","Paste":"metaV","Select all":"metaA","Undo":"metaZ","Redo":"metaY","Bullet list":"accessU","Numbered list":"accessO","Insert\/edit image":"accessM","Insert\/edit link":"metaK","Remove link":"accessS","Toolbar Toggle":"accessZ","Insert Read More tag":"accessT","Insert Page Break tag":"accessP","Distraction-free writing mode":"accessW","Keyboard Shortcuts":"accessH"},content_css:"https://local.virtualbrix.net/wp471/wp-includes/css/dashicons.min.css?ver=4.7.10,https://local.virtualbrix.net/wp471/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.7.10",selector:"#vx_config_alert_body",resize:"vertical",menubar:false,wpautop:true,indent:false,toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",toolbar3:"",toolbar4:"",tabfocus_elements:":prev,:next",body_class:"vx_config_alert_body locale-en-us",forced_root_block:"div"},'vx_config_ip_msg':{ */
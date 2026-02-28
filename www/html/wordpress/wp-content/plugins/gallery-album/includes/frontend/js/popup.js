/**
  * wpda_gall0:java obect, 
  * wpda_gallc:json obect  
 **/

////////////////////////////////////////////   support variables    /////////////////////////////////////////////////////////////////
//"use strict";


var
    wpda_gall_grandfather,		                        				                                                                      
    wpda_gall_popup_parent,      
    wpda_gall_popup,
    wpda_gall_loading_img,
	wpda_gall_popup_img,
	wpda_gall_popup_iframe,
	wpda_gall_popup_img_copi,
    wpda_gall_canvas,
	wpda_gall_ctx,              
	wpda_gall_overlay,
	wpda_gall_p_close_icon,
	wpda_gall_p_close_icon_,
    wpda_gall_p_setting_icon,
	wpda_gall_p_setting_icon_,
	wpda_gall_p_load_icon,
	wpda_gall_p_load_icon_,
	wpda_gall_p_play_icon,
	wpda_gall_p_play_icon_,
	wpda_gall_p_full_icon,
	wpda_gall_p_full_icon_,	
	wpda_gall_p_imgs_count,
	wpda_gall_p_imgs_count_,
	wpda_gall_popup_icon_left_bar,
    wpda_gall_popup_icon_right_bar,
	wpda_gall_p_popup_icon_left_,
	wpda_gall_p_popup_icon_right_,
	wpda_gall_p_right_icon,
	wpda_gall_p_right_icon_,	
	wpda_gall_p_left_icon,
	wpda_gall_p_left_icon_,
	wpda_gall_progress_bar_base,
	wpda_gall_left_bar_icons,
	wpda_gall_right_bar_icons,
	wpda_gall_progress_bar_screen,
	wpda_gall_progress_bar_count_screen,
    wpda_gall_icons_outBar,															  
    wpda_gall_icons_outBar_cln,
	wpda_gall_popup_cln,
	wpda_gall_close_description_index = 0;

	
var wpda_gall0 = {};
( function() {
	var result;
	jQuery.each(wpda_gallc,function(index, value) {	
		if(typeof(wpda_gallc[index])!='object'){
			result = wpda_gallc[index].match( /[^0-9]/i );
			if(!result && wpda_gallc[index]!=""){
				wpda_gallc[index]=parseInt(wpda_gallc[index]);
			}
		}else{		
			var loc_array = jQuery.map(wpda_gallc[index], function(value, index) {
				return [value];
			});
			wpda_gallc[index]=loc_array;
		}
	});
})();
 
wpda_gallc.start_popup_location=0;
wpda_gallc.close_location=0;
wpda_gallc.popup_location=5;
wpda_gallc.icons_inBar_yes_no=1;
wpda_gallc.outBar_icons_center=1;
wpda_gallc.icons_in_out="in";
wpda_gallc.icons_top_bottom=0;
wpda_gallc.right_icon="fa fa-chevron-circle-";
wpda_gallc.play_icon="fa fa-play";
wpda_gallc.stop_icon="fa fa-pause";
wpda_gallc.full_icon="fa fa-compress";
wpda_gallc.setting_icon="fa fa-file-text";
wpda_gallc.close_icon="fa fa-close";
wpda_gallc.transition_duration=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gallc.start_popup_location=0;
wpda_gall0 = {	
	slayd_duration                    : wpda_gallc.transition_duration,
	popup_start_width_                : wpda_gallc.popup_start_width,
	progress_bar_s_w_                 : wpda_gallc.progress_bar_screen_width + 2 * wpda_gallc.progress_bar_screen_brd_width,	                     
	distance_popup_from_outBar_       : wpda_gallc.popup_base_cornice - wpda_gallc.icons_outBar_distance_from_html,
	distance_popup_from_outBar        : wpda_gallc.popup_base_cornice - wpda_gallc.icons_outBar_distance_from_html,	  
	full_yes_no_value                 : 1,
	full_yes_no                       : 1,                                                           
	bar_icons_height                  : wpda_gallc.icons_inBar_height - wpda_gallc.progress_bar_top,                                 
	icons_inBar_height_zero           : 0,            				  
	resize_yes_no                     : 0,
	popup_min_w                       : wpda_gallc.popup_min_width, 	  				 
	local_slide_value                 : 0,    
	imgs_class_index                  : 0,
	load_counter                      : 0,
	full_check                        : 1,
	start_time                        : 0,                                                                                  		  
	frequency                         : 1000 / wpda_gallc.transition_duration,
	distance_popupContent_from_html_  : wpda_gallc.popup_base_cornice + wpda_gallc.popup_brd_width,                                  
	distance_popupContent_from_html   : wpda_gallc.popup_base_cornice + wpda_gallc.popup_brd_width,	                                                 
	t                                 : wpda_gallc.popup_start_width + wpda_gallc.icons_inBar_yes_no * wpda_gallc.icons_inBar_height,                           
	z                                 : wpda_gallc.popup_start_width,           
	play_stop                         : "PLAY",                                  				                    
	scrubber                          : "",
	progress_bar                      : "",				  				  
	window_w                          : 0,
	window_h                          : 0,
	imgs_clases                       : [], 	  
	imgs_little                       : [],	  
	number_imgs                       : [],
	Big_imgs                          : [],
	progress_bar_buttons              : [],
	progress_bar_buttons_             : [],
	p                                 : 0, 
	q                                 : 0, 
	change_value                      : 0,
	slide_left_right_all              : 0,
	close_open                        : 1,
	count_images_prev_per_page        : []
}
	 
if ( wpda_gallc.icons_in_out == "out" ) {wpda_gallc.icons_inBar_yes_no = 0;}
wpda_gall0.icons_inBar_height_zero = wpda_gallc.icons_inBar_yes_no * wpda_gallc.icons_inBar_height;

   //////////////////////////////////////////////////////////// //end
if ( !window.requestAnimationFrame ) {
    window.requestAnimationFrame = ( function() {
        return window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function( /* function FrameRequestCallback */ callback, /* DOMElement Element */ element ) {
            window.setTimeout( callback, 1000 / 60 );
		};	
    })();                             
}			
window.addEventListener("resize", wpda_gall_restart_resize);
///////////////	

	
///////////////*************************************************************** popup functions  ****************************************************//////////////////
///////////////*************************************************************** popup functions  ****************************************************//////////////////
///////////////*************************************************************** popup functions  ****************************************************//////////////////

//00. wpda_gall_generete_css_java(j)
//01. wpda_gall_create_popup()
//02. wpda_gall_create_popup_()
//03. wpda_gall_load_image()
//04. wpda_gall_load(download_link)
//05. wpda_gall_open_description()
//06. wpda_gall_close_description()
//07. wpda_gall_create_progress_bar_buttons(i)
//08. wpda_gall_restart_resize()
//09.wpda_gall_restart()
//10. wpda_gall_start_popup()
//11. wpda_gall_open_popup(img1)
//12. wpda_gall_Slideshow()
//13. wpda_gall_Slideshow_video(img,vvv)
//14. wpda_gall_Slideshow_img(img)
//15. wpda_gall_Slideshow_() 
//16. wpda_gall_Slideshow_video_(img,vvv) 
//17. wpda_gall_Slideshow_img_(img) 
//18. wpda_gall_close_popup() 
//19. wpda_gall_close_popup_overlay() 
//20. wpda_gall_main_function(t_, z_, e) 
//21. wpda_gall_onmousemove_progress_bar_buttons(i, j) 
//22. wpda_gall_click_progress_bar_buttons(i, j) 
//23. wpda_gall_show_coords_progress_bar_buttons(event) 
//24. wpda_gall_show_coords_little_imgs(event) 
//25. wpda_gall_popup_locations( e, w, h ) 
//26. wpda_gall_click_img_little(i, j) 
//27. get_youtube_url(youtube_image_url) 
//28. wpda_gall_show_count_and_scrubber_color(i, j) 
//29. wpda_gall_check_div_or_canvas_slide_effects() 
//30. wpda_gall_full_screen() 
//31. 
//32. wpda_gall_play_right()
//33. wpda_gall_play_left() 
//34. wpda_gall_slide_right() 
//35. wpda_gall_slide_left() 
//36. wpda_gall_aftomat() 
//37. wpda_gall_aftomat1() 
//38. wpda_gall_div_slide_effects(e) 
//39. wpda_gall_canvas_slide_effects(lab)
//40.

/*01*/
function wpda_gall_create_popup() {
    var test = 
    '<div id="wpda_gall_overlay" onclick="wpda_gall_close_popup_overlay();"></div>' +                  
    '<div id="wpda_gall_icons_outBar" class="wpda_gall_icons_outBar"></div>' +
    '<div id="wpda_gall_popup_parent" class="wpda_gall_popup_parent">' +
        '<div id="wpda_gall_popup" class="wpda_gall_popup">' +
	        '<img id="wpda_gall_loading_img" style="position:absolute;max-width:none;width:128px;height:128px;z-index:100;" />' +                                              	       
	        '<img id="wpda_gall_popup_img" class="wpda_gall_popup_img0" />' + 
	        '<img id="wpda_gall_popup_img_copi" class="wpda_gall_popup_img0" />' + 
	        '<canvas id="wpda_gall_popup_canvas_copi" class="wpda_gall_popup_img0"></canvas>' +	       
		    '<div id="wpda_gall_popup_icon_left_bar" class="wpda_gall_popup_icon_left_bar" onclick="wpda_gall_play_left(); wpda_gall0.slide_left_right_all = 1;">' +
				'<i id="wpda_gall_p_popup_icon_left_"></i>' +
			'</div>' +
            '<div id="wpda_gall_popup_icon_right_bar" class="wpda_gall_popup_icon_right_bar" onclick="wpda_gall_play_right(); wpda_gall0.slide_left_right_all = 2;" >' +
				'<i id="wpda_gall_p_popup_icon_right_"></i>' +
			'</div>' +	       		   	   		      
		    '<div dir="ltl" id="wpda_gall_left_bar_icons" class="wpda_gall_left_bar_icons">' +
			    '<div id="wpda_gall_p_left_icon_" class="wpda_gall_parent_icon wpda_gall_right_icon_font_size"><i id="wpda_gall_p_left_icon" onclick="wpda_gall_play_left(); wpda_gall0.slide_left_right_all = 1;"></i></div>' +			      
				'<div id="wpda_gall_p_play_icon_" class="wpda_gall_parent_icon wpda_gall_play_icon_font_size"><i id="wpda_gall_p_play_icon" onclick="wpda_gall_aftomat(); wpda_gall0.slide_left_right_all = 0;"></i></div>' +
			    '<div id="wpda_gall_p_right_icon_" class="wpda_gall_parent_icon wpda_gall_right_icon_font_size"><i id="wpda_gall_p_right_icon" onclick="wpda_gall_play_right(); wpda_gall0.slide_left_right_all = 2;"></i></div>' +
		        '<div id="wpda_gall_p_imgs_count_" class="wpda_gall_parent_icon wpda_gall_count_icon_font_size"><span id="wpda_gall_p_imgs_count" class="wpda_gall_number_image ">number nkar</span></div>' +
			    '<div id="wpda_gall_right_bar_icons" class="wpda_gall_right_bar_icons">' +
		            '<div id="wpda_gall_p_full_icon_" class="wpda_gall_parent_icon wpda_gall_full_icon_font_size"><i id="wpda_gall_p_full_icon" onclick="wpda_gall_full_screen();"></i></div>' +
					'<div id="wpda_gall_p_load_icon_" class="wpda_gall_parent_icon wpda_gall_load_icon_font_size"><i id="wpda_gall_p_load_icon"></i></div>' +
				    '<div id="wpda_gall_p_setting_icon_" class="wpda_gall_parent_icon wpda_gall_setting_icon_font_size"><i id="wpda_gall_p_setting_icon"></i></div>' +
		            '<div id="wpda_gall_p_close_icon_" class="wpda_gall_parent_icon wpda_gall_close_icon_font_size"><i id="wpda_gall_p_close_icon" onclick="wpda_gall_close_popup();"></i></div>' +	             			
		        '</div>' +
		    '</div>' +           		   	   	   
	    '</div>' +      
    '</div>';	 
    wpda_gall_grandfather = document.createElement("div");               
    document.body.appendChild(wpda_gall_grandfather);
	wpda_gall_grandfather.style.all = "initial";
    wpda_gall_grandfather.innerHTML = test;		                        				                                                                      
    wpda_gall_popup_parent                   = document.getElementById("wpda_gall_popup_parent");       
    wpda_gall_popup                          = document.getElementById("wpda_gall_popup");
    wpda_gall_loading_img                    = document.getElementById("wpda_gall_loading_img");
	wpda_gall_popup_img                      = document.getElementById("wpda_gall_popup_img");
	wpda_gall_popup_img_copi                 = document.getElementById("wpda_gall_popup_img_copi");
    wpda_gall_canvas                         = document.getElementById("wpda_gall_popup_canvas_copi");
	wpda_gall_ctx                            = wpda_gall_canvas.getContext("2d");               
	wpda_gall_overlay                        = document.getElementById("wpda_gall_overlay");
	wpda_gall_p_close_icon                   = document.getElementById("wpda_gall_p_close_icon");
	wpda_gall_p_load_icon                    = document.getElementById("wpda_gall_p_load_icon");
    wpda_gall_p_setting_icon                 = document.getElementById("wpda_gall_p_setting_icon");
	wpda_gall_p_play_icon                    = document.getElementById("wpda_gall_p_play_icon");
	wpda_gall_p_full_icon                    = document.getElementById("wpda_gall_p_full_icon");	                
	wpda_gall_p_imgs_count                   = document.getElementById("wpda_gall_p_imgs_count");
	wpda_gall_p_close_icon_                  = document.getElementById("wpda_gall_p_close_icon_");
	wpda_gall_p_load_icon_                   = document.getElementById("wpda_gall_p_load_icon_");
    wpda_gall_p_setting_icon_                = document.getElementById("wpda_gall_p_setting_icon_");
	wpda_gall_p_play_icon_                   = document.getElementById("wpda_gall_p_play_icon_");
	wpda_gall_p_full_icon_                   = document.getElementById("wpda_gall_p_full_icon_");	                
	wpda_gall_p_imgs_count_                  = document.getElementById("wpda_gall_p_imgs_count_");	
	wpda_gall_popup_icon_left_bar            = document.getElementById("wpda_gall_popup_icon_left_bar");
    wpda_gall_popup_icon_right_bar           = document.getElementById("wpda_gall_popup_icon_right_bar");
	wpda_gall_p_popup_icon_left_             = document.getElementById("wpda_gall_p_popup_icon_left_");
	wpda_gall_p_popup_icon_right_            = document.getElementById("wpda_gall_p_popup_icon_right_");
	wpda_gall_p_right_icon                   = document.getElementById("wpda_gall_p_right_icon");   
	wpda_gall_p_left_icon                    = document.getElementById("wpda_gall_p_left_icon");
	wpda_gall_p_right_icon_                  = document.getElementById("wpda_gall_p_right_icon_");   
	wpda_gall_p_left_icon_                   = document.getElementById("wpda_gall_p_left_icon_");	/////////////////////////  07-03-2017
	wpda_gall_left_bar_icons                 = document.getElementById("wpda_gall_left_bar_icons");	
	wpda_gall_right_bar_icons                = document.getElementById("wpda_gall_right_bar_icons");
    wpda_gall_icons_outBar                   = document.getElementById("wpda_gall_icons_outBar");																  
    wpda_gall_icons_outBar_cln               = wpda_gall_icons_outBar.cloneNode(true);			                                  
	wpda_gall_icons_outBar_cln.style.zIndex  = "100000";								
	wpda_gall_grandfather.appendChild(wpda_gall_icons_outBar_cln);
    document.getElementById("wpda_gall_loading_img").src = wpda_gallc.rgn_popup_url + "assets/img/" + wpda_gallc.loading_img;
} 
/*02*/					  							  
function wpda_gall_create_popup_() {	      
    if(wpda_gallc.icons_inBar_yes_no == 0) {
	    wpda_gall_left_bar_icons.className += " wpda_gall_left_bar_icons_grad" + wpda_gallc.icons_top_bottom;
	}	
    wpda_gall_p_play_icon.className             = wpda_gallc.play_icon + " wpda_gall_class_icon";
	wpda_gall_p_load_icon.className             = wpda_gallc.load_icon + " wpda_gall_class_icon";
	wpda_gall_p_setting_icon.className          = wpda_gallc.setting_icon + " wpda_gall_class_icon";	 
    wpda_gall_p_right_icon.className            = wpda_gallc.right_icon + "right wpda_gall_class_icon";
    wpda_gall_p_left_icon.className             = wpda_gallc.right_icon + "left wpda_gall_class_icon";
	wpda_gall_p_close_icon.className            = wpda_gallc.close_icon + " wpda_gall_class_icon";
	
	
	if(wpda_gallc.full_icon == "material-icons" || wpda_gallc.full_icon == "wpda_gall_display_none"){
		wpda_gall_p_full_icon.className         = wpda_gallc.full_icon + " wpda_gall_class_icon";
		wpda_gall_p_full_icon.innerHTML         = "fullscreen_exit";
    } else {
		wpda_gall_p_full_icon.className         = wpda_gallc.small_icon + " wpda_gall_class_icon";	
	}
	wpda_gall_p_popup_icon_right_.className     = wpda_gallc.popup_right_icon + "right wpda_gall_popup_icon_right";
	wpda_gall_p_popup_icon_left_.className      = wpda_gallc.popup_right_icon + "left wpda_gall_popup_icon_left";	  		 
			         
    if(wpda_gallc.icons_top_bottom == 1) {
		wpda_gall_left_bar_icons.style.top = "0px";
	}
	if(wpda_gall0.icons_inBar_height_zero == 0 && wpda_gallc.icons_in_out != "out" && wpda_gallc.icons_inBar_yes_no == 0) { 
		wpda_gall_left_bar_icons.className += " wpda_gall_opasity_0";
	} else {
		//wpda_gall_right_bar_icons.style.backgroundColor = rkrgn_popup_bg_color_;
	}		    			 

	if(wpda_gallc.outBar_icons_center == 0) {
		wpda_gall_left_bar_icons.appendChild(wpda_gall_p_full_icon_);
		wpda_gall_left_bar_icons.appendChild(wpda_gall_p_load_icon_);
		wpda_gall_left_bar_icons.appendChild(wpda_gall_p_setting_icon_);
		wpda_gall_left_bar_icons.appendChild(wpda_gall_p_close_icon_);
		wpda_gall_left_bar_icons.removeChild(wpda_gall_right_bar_icons);
		wpda_gall_left_bar_icons.style.textAlign = "center";
	}
	
    if(wpda_gallc.icons_in_out == "out") {		
	    wpda_gall_icons_outBar.appendChild(wpda_gall_popup_icon_left_bar); 
	    wpda_gall_icons_outBar.appendChild(wpda_gall_popup_icon_right_bar);
	    wpda_gall_icons_outBar.appendChild(wpda_gall_left_bar_icons);
        wpda_gall_icons_outBar_cln.className += " wpda_gall_icons_outBar_box_shadow";		
    } else {
		wpda_gall_grandfather.removeChild(wpda_gall_icons_outBar);
		wpda_gall_grandfather.removeChild(wpda_gall_icons_outBar_cln);
	}	 
	wpda_gall_restart();				                                      							                                                 
    if(wpda_gallc.slide_show_effect == 1) {wpda_gall_popup.removeChild(wpda_gall_canvas);} 
	if(wpda_gallc.slide_show_effect == 0) {wpda_gall_popup.removeChild(wpda_gall_popup_img_copi);}		
	wpda_gall_open_description();
	wpda_gall_load_image();   	
}
/*03*/
function wpda_gall_load_image() {		
    jQuery(wpda_gall_p_load_icon).click(function(){
		if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "PLAY"){			
			wpda_gall_load(wpda_gall0.Big_imgs[wpda_gall0.imgs_class_index][wpda_gall0.q]);
		}
	})	
}
/*04*/
function wpda_gall_load(download_link){
	var donwloaded_hiperlink = {}; 
	var filename = download_link.substring(download_link.lastIndexOf('/')+1); 
	donwloaded_hiperlink = jQuery("<a>").attr("href",download_link ).attr("download", filename).appendTo("body");
	donwloaded_hiperlink[0].click();
	donwloaded_hiperlink.remove();
}
/*05*/
function wpda_gall_open_description() {		
    jQuery(wpda_gall_p_setting_icon).click(function(){
		if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "PLAY" && wpda_gall_close_description_index == 0){
			
			wpda_gall_popup_parent.style.width = wpda_gall_popup.clientWidth + "px";
			wpda_gall_popup_parent.style.height = wpda_gall_popup.clientHeight + "px";
			
			wpda_gall_close_description_index = 1;
			wpda_gall0.close_open = 0;
			wpda_gall_icons_outBar.style.zIndex = "99998";
			wpda_gall_icons_outBar_cln.style.zIndex = "99998";
			wpda_gall_popup_cln = wpda_gall_popup.cloneNode(true);
			wpda_gall_popup_cln.innerHTML = "...";
			wpda_gall_popup_parent.appendChild(wpda_gall_popup_cln);
			wpda_gall_popup_cln.className += " wpda_gall_popup_description";		
			var urll = wpda_gall0.Big_imgs[wpda_gall0.imgs_class_index][wpda_gall0.q];		
			jQuery.ajax({
				url: wpda_gallc.admin_ajax_url + "?action=wpda_gall_get_description&gallery_current_index="+wpda_gall0.gallery_current_index+"&album_current_index="+wpda_gall0.album_current_index+"&q="+(wpda_gall0.count_images_prev_per_page[wpda_gall0.imgs_class_index] + wpda_gall0.q),
				
			}).done(function(date) {															
				wpda_gall_popup_cln.innerHTML = '<div class="wpda_gall_description_margin">' + date + '</div>';
			});				
		
			jQuery(wpda_gall_popup_cln).css({
												'-webkit-transform': 'rotateY(-180deg)',
												'-moz-transform': 'rotateY(-180deg)',
												'-ms-transform': 'rotateY(-180deg)',
												'transform': 'rotateY(-180deg)'
											});
											 
			wpda_gall_popup_cln.style.WebkitTransition = "all "+ wpda_gallc.transition_duration / 1000 + "s";
			wpda_gall_popup_cln.style.transition = "all "+ wpda_gallc.transition_duration / 1000 + "s";
			setTimeout(function() {	
				jQuery(wpda_gall_popup).css({
												'-webkit-transform' : 'rotateY(180deg)',
												'-moz-transform' : 'rotateY(180deg)',
												'-ms-transform' : 'rotateY(180deg)',
												'transform' : 'rotateY(180deg)'
											});
										 
				jQuery(wpda_gall_popup_cln).css({
													'-webkit-transform' : 'rotateY(0deg)',
													'-moz-transform' : 'rotateY(0deg)',
													'-ms-transform' : 'rotateY(0deg)',
													'transform' : 'rotateY(0deg)'
												 });
				//17-09-2017
				jQuery(wpda_gall_popup_cln).css({
													'z-index' : '1'
												});												 
			},50);
			wpda_gall_popup_cln.onclick = function() {				
				jQuery(wpda_gall_popup).css({
												'-webkit-transform': 'rotateY(0deg)',
												'-moz-transform': 'rotateY(0deg)',
												'-ms-transform': 'rotateY(0deg)',
												'transform': 'rotateY(0deg)'
											});									 
				jQuery(wpda_gall_popup_cln).css({
													'-webkit-transform': 'rotateY(-180deg)',
													'-moz-transform': 'rotateY(-180deg)',
													'-ms-transform': 'rotateY(-180deg)',
													'transform': 'rotateY(-180deg)'
												});
				setTimeout(function() {
					jQuery(wpda_gall_popup_cln).remove();
					if(wpda_gallc.icons_in_out == "out") {	
						wpda_gall_icons_outBar.style.zIndex = "100003";
						wpda_gall_icons_outBar_cln.style.zIndex = "100000";
					}
				}, 0.6 * wpda_gallc.transition_duration);
				setTimeout(function() {
					wpda_gall_popup_parent.style.width = "0px";
			        wpda_gall_popup_parent.style.height = "0px";
					wpda_gall_close_description_index = 0;
					wpda_gall0.close_open = 1;
				}, wpda_gallc.transition_duration);				
			};
		}
    });		
}
/*06*/
function wpda_gall_close_description() {	
	jQuery(wpda_gall_popup).css({
									'-webkit-transform': 'rotateY(0deg)',
									'-moz-transform': 'rotateY(0deg)',
									'-ms-transform': 'rotateY(0deg)',
									'transform': 'rotateY(0deg)'
								});									 
	jQuery(wpda_gall_popup_cln).css({
										'-webkit-transform': 'rotateY(-180deg)',
										'-moz-transform': 'rotateY(-180deg)',
										'-ms-transform': 'rotateY(-180deg)',
										'transform': 'rotateY(-180deg)'
									});
	setTimeout(function() {
		jQuery(wpda_gall_popup_cln).remove();
		if(wpda_gallc.icons_in_out == "out") {	
			wpda_gall_icons_outBar.style.zIndex = "100003";
			wpda_gall_icons_outBar_cln.style.zIndex = "100000";
		}
	}, 0.6 * wpda_gallc.transition_duration);
	setTimeout(function() {
		wpda_gall_popup_parent.style.width = "0px";
		wpda_gall_popup_parent.style.height = "0px";
		wpda_gall_close_description_index = 0;
		wpda_gall0.close_open = 1;
	}, wpda_gallc.transition_duration);		
}
/*08*/ 	 	 
function wpda_gall_restart_resize() {
    if(wpda_gall0.resize_yes_no == 1) {wpda_gall_restart()}
}
/*09*/ 	 	 			
function wpda_gall_restart() {
    wpda_gall0.distance_popup_from_outBar = wpda_gall0.distance_popup_from_outBar_ = wpda_gall0.full_yes_no * wpda_gallc.icons_outBar_distance_from_html;
    wpda_gall0.distance_popupContent_from_html = wpda_gall0.distance_popupContent_from_html_ = wpda_gall0.full_yes_no * wpda_gallc.popup_base_cornice + wpda_gallc.popup_brd_width;
    wpda_gall0.popup_min_w = wpda_gall0.full_yes_no_value * wpda_gallc.popup_start_width;
    var img_w = wpda_gall0.t;
    var img_h = wpda_gall0.z; 
    wpda_gall0.window_w = document.body.clientWidth;                          ////wpda_gall0.window_w=document.body.clientWidth;wpda_gall0.window_w=window.innerWidth;
    wpda_gall0.window_h = window.innerHeight;
    if(wpda_gall0.window_w - 2 * wpda_gall0.distance_popupContent_from_html_ < 300 || wpda_gall0.window_h - 2 * wpda_gall0.distance_popupContent_from_html_ < 300) {wpda_gall0.distance_popupContent_from_html = wpda_gallc.popup_brd_width; wpda_gall0.distance_popup_from_outBar = 0;} else {wpda_gall0.distance_popupContent_from_html = wpda_gall0.distance_popupContent_from_html_; wpda_gall0.distance_popup_from_outBar = wpda_gall0.distance_popup_from_outBar_}			  
	wpda_gall_icons_outBar.style.left = wpda_gall0.distance_popup_from_outBar + "px";
    wpda_gall_icons_outBar.style.top = wpda_gall0.distance_popup_from_outBar + "px";
	wpda_gall_icons_outBar.style.width = (wpda_gall0.window_w - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
	wpda_gall_icons_outBar.style.height = (wpda_gall0.window_h - 2 * wpda_gall0.distance_popup_from_outBar) + "px";			  			  			  
	wpda_gall_icons_outBar_cln.style.left = wpda_gall0.distance_popup_from_outBar + "px";
    wpda_gall_icons_outBar_cln.style.top = wpda_gall0.distance_popup_from_outBar + "px";
	wpda_gall_icons_outBar_cln.style.width = (wpda_gall0.window_w - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
	wpda_gall_icons_outBar_cln.style.height = (wpda_gall0.window_h - 2 * wpda_gall0.distance_popup_from_outBar) + "px";       
    var outBar_w = wpda_gall0.window_w - 2 * wpda_gall0.distance_popupContent_from_html;
    var outBar_h = wpda_gall0.window_h - 2 * wpda_gall0.distance_popupContent_from_html;
    var x_ = Math.min(outBar_w, Math.max(wpda_gall0.popup_min_w, img_w));
    var y_ = wpda_gall0.icons_inBar_height_zero + x_ * img_h / img_w;
    y_ = Math.min(outBar_h, y_);
    x_ = (y_ - wpda_gall0.icons_inBar_height_zero) * img_w / img_h;
    img_h = Math.floor(y_ - wpda_gall0.icons_inBar_height_zero);
    img_w = Math.floor(x_); 			  
    wpda_gall_popup_locations(wpda_gallc.popup_location, img_w, img_h);	 
    wpda_gall_popup.style.left = - wpda_gallc.popup_brd_width + "px";
    wpda_gall_popup.style.top = - wpda_gallc.popup_brd_width + "px";
    wpda_gall_popup.style.width = img_w + "px";
    wpda_gall_popup.style.height = (img_h + wpda_gall0.icons_inBar_height_zero) + "px";		
	wpda_gall_popup_img.style.width = img_w + "px";
    wpda_gall_popup_img.style.height = img_h + "px"; 
    wpda_gall_canvas.style.width = img_w + "px";
    wpda_gall_canvas.style.height = img_h + "px";
	wpda_gall_canvas.width = img_w + "px";
    wpda_gall_canvas.height = img_h + "px";
    wpda_gall_popup_img_copi.style.width = img_w + "px";
    wpda_gall_popup_img_copi.style.height = img_h + "px";
	wpda_gall_loading_img.style.left = (img_w / 2 - 64) + "px";
	wpda_gall_loading_img.style.top = (img_h / 2 - 64) + "px";
	if(wpda_gall_close_description_index == 1){wpda_gall_close_description()};
}
/*10*/
function wpda_gall_start_popup() {
	 wpda_gall0.close_open = 0;
    wpda_gall_left_bar_icons.style.display = "none";                     
	wpda_gall_popup_img.src = wpda_gall0.imgs_little[wpda_gall0.imgs_class_index][wpda_gall0.q].src;
	if(wpda_gall0.imgs_class_index > 0) {
        wpda_gall_popup_img.src = wpda_gall0.imgs_little[wpda_gall0.imgs_class_index][wpda_gall0.q].style.backgroundImage.replace(/url\((['"])?(.*?)\1\)/gi, '$2').split(',')[0]; 
    }	

	wpda_gall_popup.style.WebkitTransitionProperty = "none";
	wpda_gall_popup.style.transitionProperty = "none";
	wpda_gall_popup_img.style.WebkitTransitionProperty = "none";
	wpda_gall_popup_img.style.transitionProperty = "none";
	wpda_gall_canvas.style.WebkitTransitionProperty = "none";
	wpda_gall_canvas.style.transitionProperty = "none";             
	wpda_gall_popup_img_copi.style.WebkitTransitionProperty = "none";
	wpda_gall_popup_img_copi.transitionProperty = "none";
	wpda_gall_loading_img.style.WebkitTransitionProperty = "none";
	wpda_gall_loading_img.style.transitionProperty = "none";
 		

	wpda_gall_popup.style.border = "solid transparent";
	wpda_gall_popup.style.backgroundColor = "transparent";
	wpda_gall_popup.style.boxShadow = "0 0 0 transparent";
	wpda_gall_popup.style.borderRadius = "0px";

									
    wpda_gall_main_function(wpda_gall0.t, wpda_gall0.z, wpda_gallc.popup_start_width);
	wpda_gall_popup.style.visibility = "visible";
}
/*11*/			  
function wpda_gall_open_popup() {
    var img1 = new Image();
    img1.src = wpda_gall0.Big_imgs[wpda_gall0.imgs_class_index][wpda_gall0.q];     
	wpda_gall_loading_img.style.display = "inline";
    var kq = wpda_gall0.load_counter;
    img1.onload = function() {
	    if (kq == wpda_gall0.load_counter) {		
		    wpda_gall_loading_img.style.display = "none"; 		
            wpda_gall0.t = img1.width;
            wpda_gall0.z = img1.height;	

			//if popup_cover_box fixed
			if(wpda_gallc.popup_cover_box == 1){
				if(wpda_gallc.popup_cover_box_height / wpda_gallc.popup_cover_box_width >= wpda_gall0.z / wpda_gall0.t){				                                                                                  
					wpda_gall0.z = Math.floor(wpda_gallc.popup_cover_box_width * wpda_gall0.z / wpda_gall0.t);
					wpda_gall0.t = wpda_gallc.popup_cover_box_width;				
				} else {
					wpda_gall0.t = Math.floor(wpda_gallc.popup_cover_box_height * wpda_gall0.t / wpda_gall0.z);
					wpda_gall0.z = wpda_gallc.popup_cover_box_height;
				}
			}
			
            wpda_gall_main_function(wpda_gall0.t, wpda_gall0.z, wpda_gallc.popup_min_width);
			wpda_gallc.popup_start_width = wpda_gallc.popup_min_width;
	        img1.style.display = "none";
			wpda_gall_popup_img.src = img1.src;
            //wpda_gall_popup_img_copi.style.opacity = "0";    	                 
            setTimeout(function() {
				wpda_gall_popup_icon_left_bar.style.display = "inline";
		        wpda_gall_popup_icon_right_bar.style.display = "inline";				                        
				wpda_gall_left_bar_icons.style.display = "inline";
				wpda_gall_icons_outBar.style.display = "inline";
				wpda_gall_icons_outBar_cln.style.display = "inline";
				wpda_gall_popup_img_copi.src = img1.src;
					wpda_gall0.close_open = 1;
				//wpda_gall_popup_img_copi.style.opacity = "1";
            }, wpda_gallc.transition_duration);
	    }
	}		 		 
}		  			  	 	
/*15*/										
function wpda_gall_Slideshow_() {
    wpda_gall_popup_img_copi.style.display = "inline";	       
    var img = new Image();
    img.src = wpda_gall0.Big_imgs[wpda_gall0.imgs_class_index][wpda_gall0.q];                
	wpda_gall_loading_img.style.display = "inline";
	var kq = wpda_gall0.load_counter;
    img.onload = function() {
		if(kq == wpda_gall0.load_counter) {
			wpda_gall_loading_img.style.display = "none";
	        if(wpda_gallc.icons_in_out == "in") {		
	            wpda_gall_popup_icon_left_bar.style.display = "none";
	            wpda_gall_popup_icon_right_bar.style.display = "none";				                        
	            wpda_gall_left_bar_icons.style.display = "none";
	        }
		    if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "STOP") {
				setTimeout(function() {wpda_gall_aftomat1()}, wpda_gallc.transition_duration + wpda_gallc.pause_duration); 
			};
			wpda_gall0.close_open = 0;
		    wpda_gall0.t = img.width;
            wpda_gall0.z = img.height;
							
			//if popup_cover_box fixed
			if(wpda_gallc.popup_cover_box == 1){
				if(wpda_gallc.popup_cover_box_height / wpda_gallc.popup_cover_box_width >= wpda_gall0.z / wpda_gall0.t){				                                                                                  
					wpda_gall0.z = Math.floor(wpda_gallc.popup_cover_box_width * wpda_gall0.z / wpda_gall0.t);
					wpda_gall0.t = wpda_gallc.popup_cover_box_width;				
				} else {
					wpda_gall0.t = Math.floor(wpda_gallc.popup_cover_box_height * wpda_gall0.t / wpda_gall0.z);
					wpda_gall0.z = wpda_gallc.popup_cover_box_height;
				}
			}			
			
            wpda_gall_main_function(wpda_gall0.t, wpda_gall0.z, wpda_gallc.popup_min_width);	  	             
		    wpda_gall_popup_img.src = img.src;
				//wpda_gall_popup_img_copi.className += " wpda_gall_bg_color";			
			    wpda_gall_popup_img_copi.style.WebkitTransitionProperty = "left, top, width, height, opacity"; 
                wpda_gall_popup_img_copi.style.transitionProperty = "left, top, width, height, opacity";			
            wpda_gall_popup_img_copi.style.zIndex = (100 - 98) + "";				   
	        if(wpda_gall0.slide_left_right_all == 0) {wpda_gall_div_slide_effects(parseInt(wpda_gallc.slide_show_effect_standart[Math.floor(Math.random() * wpda_gallc.slide_show_effect_standart.length)])); }			  
	        if(wpda_gall0.slide_left_right_all == 1) {wpda_gall_div_slide_effects(parseInt(wpda_gallc.slide_show_effect_standart[Math.floor(Math.random() * wpda_gallc.slide_show_effect_standart.length)])); }
	        if(wpda_gall0.slide_left_right_all == 2) {wpda_gall_div_slide_effects(parseInt(wpda_gallc.slide_show_effect_standart[Math.floor(Math.random() * wpda_gallc.slide_show_effect_standart.length)])); }
            setTimeout(function() {
				wpda_gall_popup_img_copi.src = img.src;
				img.style.display = "none";
			    wpda_gall_popup_img_copi.style.WebkitTransitionProperty = "none"; 
                wpda_gall_popup_img_copi.style.transitionProperty = "none";
				wpda_gall_popup_img_copi.style.opacity = "1";
	            wpda_gall_popup_img_copi.style.zIndex = (100 - 100) + "";
	            wpda_gall_popup_img_copi.style.top = wpda_gallc.icons_top_bottom * wpda_gall0.icons_inBar_height_zero + "px";
				wpda_gall_popup_img_copi.style.left = "0px";
				wpda_gall0.close_open = 1;
				wpda_gall_popup_icon_left_bar.style.display = "inline";
				wpda_gall_popup_icon_right_bar.style.display = "inline";				                        
				wpda_gall_left_bar_icons.style.display = "inline"; 
			}, wpda_gallc.transition_duration);
		}
	}	 		 
}
/*18*/		                                               												                         
function wpda_gall_close_popup() {
	if(wpda_gall0.resize_yes_no == 1){	
		var st, x, y, x2, y2;
		
			wpda_gall_popup.style.border = "solid transparent";
			wpda_gall_popup.style.backgroundColor = "transparent";
			wpda_gall_popup.style.boxShadow = "0 0 0 transparent";
			wpda_gall_popup.style.borderRadius = "0px"; 

		wpda_gall_loading_img.style.display = "none";
		wpda_gallc.popup_start_width = wpda_gall0.popup_start_width_;
		wpda_gall0.resize_yes_no = 0;
		wpda_gall_left_bar_icons.style.display = "none";
		wpda_gall_popup_icon_left_bar.style.display = "none";
		wpda_gall_popup_icon_right_bar.style.display = "none";
		wpda_gall_icons_outBar.style.display = "none";
		wpda_gall0.load_counter++;
		wpda_gall0.close_open = 1;
		wpda_gall_p_play_icon.className = wpda_gallc.play_icon + " wpda_gall_class_icon";
		wpda_gall0.play_stop = "PLAY";
		if(wpda_gall0.full_check == 0) {wpda_gall_full_screen();}
		
		var object = wpda_gall0.imgs_little[wpda_gall0.imgs_class_index][wpda_gall0.q].getBoundingClientRect();	
		if(wpda_gallc.close_location == 1){
			wpda_gall0.t = wpda_gallc.popup_start_width;	
			wpda_gall0.z = wpda_gallc.popup_start_width * object.height / object.width;
			x2 = wpda_gall0.t;
			y2 = wpda_gall0.z;	
		}else{
			wpda_gall0.t = object.width;
			wpda_gall0.z = object.height;
			x2 = wpda_gall0.t;
			y2 = wpda_gall0.z;		
			wpda_gallc.popup_start_width = 20; 
		}
		wpda_gall_restart();
		setTimeout(function() {
			st = document.body.scrollTop || document.documentElement.scrollTop;
				if(wpda_gallc.popup_position == "absolute") {
					x = object.left + window.pageXOffset + wpda_gallc.popup_brd_width -3;
					y = object.top + window.pageYOffset + wpda_gallc.popup_brd_width - 3;				
				} else {
					st = 0;
					x = object.left + wpda_gallc.popup_brd_width - 3; 
					y = object.top + wpda_gallc.popup_brd_width - 3;
				};	    
			switch(wpda_gallc.start_popup_location) {
				case 0: 
					wpda_gall_popup_parent.style.left = x + "px";           
					wpda_gall_popup_parent.style.top = y + "px"; 
					break;       
				case 1: 
					wpda_gall_popup_parent.style.left = (0 - x2 - wpda_gallc.popup_brd_width) + "px";           
					wpda_gall_popup_parent.style.top = (st - y2 - wpda_gallc.popup_brd_width) + "px"; 
					break;
				case 2: 
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w - x2) / 2 + "px";  
					wpda_gall_popup_parent.style.top = (st - y2 - wpda_gallc.popup_brd_width) + "px"; 
					break;
				case 3: 
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w + wpda_gallc.popup_brd_width) + "px";      
					wpda_gall_popup_parent.style.top = (st - y2 - wpda_gallc.popup_brd_width) + "px"; 
					break;	
				case 4: 
					wpda_gall_popup_parent.style.left = (0 - x2 - wpda_gallc.popup_brd_width) + "px";           
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st + "px"; 
					break;
				case 5: 
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w - x2) / 2 + "px";  
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st + "px"; 
					break;         
				case 6: 
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w + wpda_gallc.popup_brd_width) + "px";      
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st + "px"; 
					break;
				case 7: 
					wpda_gall_popup_parent.style.left = (0 - x2 - wpda_gallc.popup_brd_width) + "px";           
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h + wpda_gallc.popup_brd_width + st) + "px"; 
					break;
				case 8: 
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w - x2) / 2 + "px";  
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h + wpda_gallc.popup_brd_width + st) + "px"; 
					break;
				case 9:
					wpda_gall_popup_parent.style.left = (wpda_gall0.window_w + wpda_gallc.popup_brd_width) + "px";      
					wpda_gall_popup_parent.style.top = (wpda_gall0.window_h + wpda_gallc.popup_brd_width + st) + "px";
					break;
			}		 
		}, wpda_gallc.transition_duration); 	 
		setTimeout(function() {
			wpda_gall_overlay.style.display = "none";
			wpda_gall_popup_img_copi.style.display = "none";
			wpda_gall_canvas.style.display = "none";
			wpda_gall_popup_parent.style.WebkitTransitionProperty = "none";
			wpda_gall_popup_parent.style.transitionProperty = "none";
			wpda_gall_popup_parent.style.display = "none";
			wpda_gall_grandfather.innerHTML = ""; 
			document.body.removeChild(wpda_gall_grandfather);
		}, 2 * wpda_gallc.transition_duration);
	}	
}
/*19*/
function wpda_gall_close_popup_overlay() {
	if(wpda_gall0.resize_yes_no == 1){	
		if(wpda_gall_close_description_index == 0){
			wpda_gallc.popup_start_width = wpda_gall0.popup_start_width_;
			wpda_gall0.resize_yes_no = 0;
			wpda_gall_left_bar_icons.style.display = "none";
			wpda_gall_popup_icon_left_bar.style.display = "none";
			wpda_gall_popup_icon_right_bar.style.display = "none";
			wpda_gall_icons_outBar.style.display = "none";            
			wpda_gall0.load_counter++;
			wpda_gall0.close_open = 1;
			wpda_gall_p_play_icon.className = wpda_gallc.play_icon + " wpda_gall_class_icon";
			wpda_gall0.play_stop = "PLAY";				
			wpda_gall_popup_parent.style.WebkitTransform = "rotateY(90deg)";
			wpda_gall_popup_parent.style.msTransform = "rotateY(90deg)";
			wpda_gall_popup_parent.style.transform = "rotateY(90deg)";
			setTimeout(function() {
				wpda_gall_overlay.style.display = "none"; 
				wpda_gall_popup_img_copi.style.display = "none"; 
				wpda_gall_canvas.style.display = "none";
				if(wpda_gall0.full_check == 0) {wpda_gall_full_screen();}
				wpda_gall0.t = wpda_gallc.popup_start_width + wpda_gall0.icons_inBar_height_zero;
				wpda_gall0.z = wpda_gallc.popup_start_width;
				wpda_gall_restart(); 
				wpda_gall_grandfather.innerHTML = "";
				document.body.removeChild(wpda_gall_grandfather);
			}, wpda_gallc.transition_duration);
		}else{
			wpda_gall_close_description();
		}
	}
}
/*20*/
function wpda_gall_main_function(t_, z_, e) {
    wpda_gall0.distance_popup_from_outBar = wpda_gall0.full_yes_no * wpda_gallc.icons_outBar_distance_from_html;
    wpda_gall0.distance_popupContent_from_html = wpda_gall0.full_yes_no * wpda_gallc.popup_base_cornice + wpda_gallc.popup_brd_width;
    wpda_gall0.popup_min_w = wpda_gall0.full_yes_no_value * e;
    if(wpda_gall0.window_w - 2 * wpda_gall0.distance_popupContent_from_html_ < 300 || wpda_gall0.window_h - 2 * wpda_gall0.distance_popupContent_from_html_ < 300) {wpda_gall0.distance_popupContent_from_html = wpda_gallc.popup_brd_width; wpda_gall0.distance_popup_from_outBar = 0;} else {wpda_gall0.distance_popupContent_from_html = wpda_gall0.distance_popupContent_from_html_; wpda_gall0.distance_popup_from_outBar = wpda_gall0.distance_popup_from_outBar_}
    wpda_gall_icons_outBar.style.left = wpda_gall0.distance_popup_from_outBar + "px";
    wpda_gall_icons_outBar.style.top = wpda_gall0.distance_popup_from_outBar + "px";
	wpda_gall_icons_outBar.style.width = (wpda_gall0.window_w - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
	wpda_gall_icons_outBar.style.height = (wpda_gall0.window_h - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
	wpda_gall_icons_outBar_cln.style.left = wpda_gall0.distance_popup_from_outBar + "px";
    wpda_gall_icons_outBar_cln.style.top = wpda_gall0.distance_popup_from_outBar + "px";
	wpda_gall_icons_outBar_cln.style.width = (wpda_gall0.window_w - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
	wpda_gall_icons_outBar_cln.style.height = (wpda_gall0.window_h - 2 * wpda_gall0.distance_popup_from_outBar) + "px";
    var outBar_w = wpda_gall0.window_w - 2 * wpda_gall0.distance_popupContent_from_html;
    var outBar_h = wpda_gall0.window_h - 2 * wpda_gall0.distance_popupContent_from_html;
    var x_ = Math.min(outBar_w, Math.max(wpda_gall0.popup_min_w, t_));
    var y_ = wpda_gall0.icons_inBar_height_zero + x_ * z_ / t_;
    y_ = Math.min(outBar_h, y_);
    x_ = (y_ - wpda_gall0.icons_inBar_height_zero) * t_ / z_;
    z_ = Math.floor(y_- wpda_gall0.icons_inBar_height_zero);
    t_ = Math.floor(x_); 			  
    wpda_gall_popup_locations(wpda_gallc.popup_location, t_, z_);

    wpda_gall_popup.style.left = - wpda_gallc.popup_brd_width + "px";
    wpda_gall_popup.style.top = - wpda_gallc.popup_brd_width + "px";
    wpda_gall_popup.style.width = t_ + "px";
    wpda_gall_popup.style.height = (z_ + wpda_gall0.icons_inBar_height_zero) + "px";		 		 
	wpda_gall_popup_img.style.width = t_ + "px";
    wpda_gall_popup_img.style.height = z_ + "px"; 
	wpda_gall_canvas.style.width = t_ + "px";
    wpda_gall_canvas.style.height = z_ + "px";
	wpda_gall_canvas.width = t_;
    wpda_gall_canvas.height = z_;
	wpda_gall_canvas.style.zIndex = (100 - 100) + "";
    wpda_gall_popup_img_copi.style.width = t_ + "px";
    wpda_gall_popup_img_copi.style.height = z_ + "px";
	wpda_gall_loading_img.style.left = (t_ / 2 - 64) + "px";
	wpda_gall_loading_img.style.top = (z_ / 2 - 64) + "px";
	
}
/*24*/   
function wpda_gall_show_coords_little_imgs(event) {
	wpda_gall_popup_parent.style.WebkitTransitionProperty = "none";
	wpda_gall_popup_parent.style.transitionProperty = "none";
	
    var st, x, y, x1, y1, x2, y2;		
	var object = wpda_gall0.imgs_little[wpda_gall0.imgs_class_index][wpda_gall0.q].getBoundingClientRect();	
	if(wpda_gallc.close_location == 1){
		wpda_gall0.t = wpda_gallc.popup_start_width;	
		wpda_gall0.z = wpda_gallc.popup_start_width * object.height / object.width;
		x2 = wpda_gall0.t;
		y2 = wpda_gall0.z;
	}else{
		wpda_gall0.t = object.width;
		wpda_gall0.z = object.height;
		x2 = wpda_gall0.t;
		y2 = wpda_gall0.z;		
		wpda_gallc.popup_start_width = 20;// 
	}	
	x1 = object.left + window.pageXOffset;
	y1 = object.top + window.pageYOffset;			
	st = document.body.scrollTop || document.documentElement.scrollTop;

	if(wpda_gallc.start_popup_location == 0) {
		if(wpda_gallc.popup_position == "absolute") {
			st = 0;
			x1 = object.left + window.pageXOffset - 3;
			y1 = object.top + window.pageYOffset - 3;				
		} else { 
			x1 = object.left - 3; 
			y1 = object.top - 3;
		};
	} else {
		if(wpda_gallc.popup_position == "fixed") {st = 0; };
	}

	switch(wpda_gallc.start_popup_location) {
 		case 0:
			if(wpda_gallc.close_location == 1){		
				x = event.pageX; y = event.pageY - st;
			}else{
				x = x1 + wpda_gallc.popup_brd_width; 
				y = y1 + wpda_gallc.popup_brd_width; 
				
			}
			break;       
        case 1: 
			x = 0 - x2 - wpda_gallc.popup_brd_width; 
			y = st - y2 - wpda_gallc.popup_brd_width; 
			break;
        case 2:
			x = (wpda_gall0.window_w - x2) / 2; 
			y = st - y2 - wpda_gallc.popup_brd_width;
			break;
        case 3:
			x = wpda_gall0.window_w + wpda_gallc.popup_brd_width; 
			y = st - y2 - wpda_gallc.popup_brd_width;
			break;	
	    case 4:
			x = 0 - x2 - wpda_gallc.popup_brd_width; 
			y = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st;
			break;
	    case 5:
			x = (wpda_gall0.window_w - x2) / 2; 
			y = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st;
			break;         
        case 6:
			x = wpda_gall0.window_w + wpda_gallc.popup_brd_width; 
			y = (wpda_gall0.window_h - y2 - wpda_gall0.icons_inBar_height_zero) / 2 + st;
			break;
		case 7:
			x = 0 - x2 - wpda_gallc.popup_brd_width; 
			y = wpda_gall0.window_h + st + wpda_gallc.popup_brd_width;
			break;
		case 8:
			x = (wpda_gall0.window_w - x2) / 2; 
			y = wpda_gall0.window_h + st + wpda_gallc.popup_brd_width;
			break;
        case 9:
			x = wpda_gall0.window_w + wpda_gallc.popup_brd_width; 
			y = wpda_gall0.window_h + st + wpda_gallc.popup_brd_width;
			break;
	}	 	 


    wpda_gall_popup_parent.style.top = (y - 0) + "px";
	wpda_gall_popup_parent.style.left = (x - 0) + "px";	
}
/*25*/ 
function wpda_gall_popup_locations( e, w, h ) {
    var st = 0;	
    if ( wpda_gallc.popup_position == "absolute" ) {
       st = document.body.scrollTop || document.documentElement.scrollTop;
    }  
	st = Math.floor(st);	 
	wpda_gall_icons_outBar.style.top = ( st + wpda_gall0.distance_popup_from_outBar ) + "px";
	wpda_gall_icons_outBar_cln.style.top = ( st + wpda_gall0.distance_popup_from_outBar ) + "px";
	
    switch ( wpda_gallc.popup_location ) {			
        case 1: 
            wpda_gall_popup_parent.style.left = wpda_gall0.distance_popupContent_from_html + "px"; 
            wpda_gall_popup_parent.style.top = ( st + wpda_gall0.distance_popupContent_from_html ) + "px";
            break;
        case 2: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - w ) / 2 + "px"; 
            wpda_gall_popup_parent.style.top = ( st + wpda_gall0.distance_popupContent_from_html ) + "px"; 
            break;
        case 3: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - wpda_gall0.distance_popupContent_from_html - w ) + "px"; 
            wpda_gall_popup_parent.style.top = ( st + wpda_gall0.distance_popupContent_from_html ) + "px"; 
            break;
        case 4: 
            wpda_gall_popup_parent.style.left = wpda_gall0.distance_popupContent_from_html + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - h - wpda_gall0.icons_inBar_height_zero ) / 2 + "px"; 
            break;
        case 5: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - w ) / 2 + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - h - wpda_gall0.icons_inBar_height_zero ) / 2 + "px"; 
            break;
        case 6: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - wpda_gall0.distance_popupContent_from_html - w ) + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - h - wpda_gall0.icons_inBar_height_zero ) / 2 + "px";
            break;
        case 7: 
            wpda_gall_popup_parent.style.left = wpda_gall0.distance_popupContent_from_html + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - wpda_gall0.distance_popupContent_from_html - h - wpda_gall0.icons_inBar_height_zero ) + "px";
            break;
        case 8: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - w ) / 2 + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - wpda_gall0.distance_popupContent_from_html - h - wpda_gall0.icons_inBar_height_zero ) + "px"; 
            break;
        case 9: 
            wpda_gall_popup_parent.style.left = ( wpda_gall0.window_w - wpda_gall0.distance_popupContent_from_html - w ) + "px"; 
            wpda_gall_popup_parent.style.top = st + ( wpda_gall0.window_h - wpda_gall0.distance_popupContent_from_html - h - wpda_gall0.icons_inBar_height_zero ) + "px"; 
            break;
    }
}
/*26*/						 
function wpda_gall_click_img_little(i, j) {	
    wpda_gall0.imgs_little[i][j].onclick = function(event) {
			wpda_gall0.q = j; 
			wpda_gall0.imgs_class_index = i;		

		wpda_gall0.resize_yes_no = 1; 
		wpda_gall_create_popup(); 
		wpda_gall_create_popup_();
		wpda_gall_loading_img.style.display = "none";
		wpda_gall0.load_counter++;
	    wpda_gall_show_coords_little_imgs(event);
		setTimeout(function() {			 
			wpda_gall_popup_parent.style.WebkitTransition = "all "+ wpda_gallc.transition_duration / 1000 + "s";
			wpda_gall_popup_parent.style.transition = "all " + wpda_gallc.transition_duration / 1000 + "s";
			wpda_gall_popup_parent.style.WebkitTransitionProperty = "left, top, transform";
			wpda_gall_popup_parent.style.transitionProperty = "left, top, transform";			
			wpda_gall_popup_parent.style.WebkitTransform = "rotateY(0deg)";
			wpda_gall_popup_parent.style.msTransform = "rotateY(0deg)";
			wpda_gall_popup_parent.style.transform = "rotateY(0deg)";
            wpda_gall_p_play_icon.className = wpda_gallc.play_icon + " wpda_gall_class_icon";
		    wpda_gall0.play_stop = "PLAY";
			wpda_gall0.close_open = 1; 
			   
            wpda_gall0.local_slide_value = 0; 
			wpda_gall0.q = j; 
			wpda_gall0.imgs_class_index = i;  
			wpda_gall_show_count_and_scrubber_color(i, j);
		    wpda_gall_popup_parent.style.display = "inline";		                   
		    wpda_gall_start_popup();
			setTimeout(function() {
				wpda_gall_popup.style.WebkitTransition = "all "+ wpda_gallc.transition_duration / 1000 + "s";
				wpda_gall_popup.style.transition = "all " + wpda_gallc.transition_duration / 1000 + "s";
				wpda_gall_popup_img.style.WebkitTransitionProperty = "left, top, width, height, opacity";
				wpda_gall_popup_img.style.transitionProperty = "left, top, width, height, opacity";
				wpda_gall_canvas.style.WebkitTransitionProperty = "left, top, width, height, opacity";
				wpda_gall_canvas.style.transitionProperty = "left, top, width, height, opacity";
				wpda_gall_popup_img_copi.style.WebkitTransitionProperty = "left, top, width, height, opacity";
				wpda_gall_popup_img_copi.style.transitionProperty = "left, top, width, height, opacity";
				wpda_gall_loading_img.style.WebkitTransitionProperty = "left, top";
				wpda_gall_loading_img.style.transitionProperty = "left, top";
	
				wpda_gall_popup.style.border = wpda_gallc.popup_border_rgba;
				wpda_gall_popup.style.backgroundColor = wpda_gallc.popup_bg_color;
				wpda_gall_popup.style.boxShadow = wpda_gallc.popup_box_shadow_rgba;
				wpda_gall_popup.style.borderRadius = wpda_gallc.popup_brd_radius + "" + wpda_gallc.pixel;

				wpda_gall_open_popup();}, wpda_gallc.transition_duration);
		}, 20)
	}           
}
/*28*/	 
function wpda_gall_show_count_and_scrubber_color(i, j) {
	wpda_gall_p_imgs_count.innerHTML = (j + 1) + " " + wpda_gallc.count_icon + " " + wpda_gall0.number_imgs[i];
}
/*29*/
function wpda_gall_check_div_or_canvas_slide_effects() {
	wpda_gall_Slideshow_();
}
/*30*/		  
function wpda_gall_full_screen() {
	wpda_gall_close_description_index = 2;	
    if(wpda_gall0.full_check == 1) {
		wpda_gall0.full_yes_no_value = 10000;
		wpda_gall0.full_yes_no = 0; 		
		if(wpda_gallc.full_icon == "material-icons"){
			wpda_gall_p_full_icon.className = wpda_gallc.full_icon + " wpda_gall_class_icon";
			wpda_gall_p_full_icon.innerHTML = "fullscreen";
		} else {
			wpda_gall_p_full_icon.className = wpda_gallc.full_icon + " wpda_gall_class_icon";	
		}		
	} else {
		wpda_gall0.full_yes_no_value = 1;
		wpda_gall0.full_yes_no = 1;		
		if(wpda_gallc.full_icon == "material-icons"){
			wpda_gall_p_full_icon.className = wpda_gallc.full_icon + " wpda_gall_class_icon";
			wpda_gall_p_full_icon.innerHTML = "fullscreen_exit";
		} else {
			wpda_gall_p_full_icon.className = wpda_gallc.small_icon + " wpda_gall_class_icon";	
		}		
	}
	wpda_gall0.full_check = (wpda_gall0.full_check + 1) % 2;
	wpda_gall_restart();
	setTimeout(function() {wpda_gall_close_description_index = 0;}, wpda_gallc.transition_duration);		
} 
/*31*/
jQuery(document).ready(function(){

    jQuery("body").keydown(function(event){
        if(event.which == "39"){
            wpda_gall_play_right(); 
			wpda_gall0.slide_left_right_all = 2;
        }
    });
    jQuery("body").keydown(function(event){
        if(event.which == "37"){
            wpda_gall_play_left(); 
			wpda_gall0.slide_left_right_all = 1;
        }
    });  
});
                   /////////////control functions	
/*32*/
function wpda_gall_play_right() {
	if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "PLAY") {
		wpda_gall0.load_counter++; 
		wpda_gall0.local_slide_value = 0; 
		wpda_gall0.change_value = 0; 
		wpda_gall0.p = wpda_gall0.q; 
		wpda_gall0.q = (wpda_gall0.q + 1) % wpda_gall0.number_imgs[wpda_gall0.imgs_class_index]; 
		wpda_gall_show_count_and_scrubber_color(wpda_gall0.imgs_class_index, wpda_gall0.q); 
		wpda_gall_check_div_or_canvas_slide_effects();
	};
}
/*33*/
function wpda_gall_play_left() {
	if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "PLAY") {
		wpda_gall0.load_counter++; 
		wpda_gall0.local_slide_value = 0; 
		wpda_gall0.change_value = 0; 
		wpda_gall0.p = wpda_gall0.q; 
		wpda_gall0.q = (wpda_gall0.q + wpda_gall0.number_imgs[wpda_gall0.imgs_class_index] - 1) % wpda_gall0.number_imgs[wpda_gall0.imgs_class_index]; 
		wpda_gall_show_count_and_scrubber_color(wpda_gall0.imgs_class_index, wpda_gall0.q); 
		wpda_gall_check_div_or_canvas_slide_effects();
	};
}
/*34*/
function wpda_gall_slide_right() {
	if(wpda_gall0.close_open == 1) {
		wpda_gall0.load_counter++; 
		wpda_gall0.local_slide_value = 0; 
		wpda_gall0.change_value = 0; 
		wpda_gall0.p = wpda_gall0.q; 
		wpda_gall0.q = (wpda_gall0.q + 1) % wpda_gall0.number_imgs[wpda_gall0.imgs_class_index]; 
		wpda_gall_show_count_and_scrubber_color(wpda_gall0.imgs_class_index, wpda_gall0.q); 
		wpda_gall_check_div_or_canvas_slide_effects();
	};
}
/*35*/
function wpda_gall_slide_left() {
	if(wpda_gall0.close_open == 1) {
		wpda_gall0.close_open = 0; 
		wpda_gall0.load_counter++; 
		wpda_gall0.local_slide_value = 0; 
		wpda_gall0.change_value = 0; 
		wpda_gall0.p = wpda_gall0.q; 
		wpda_gall0.q = (wpda_gall0.q + wpda_gall0.number_imgs[wpda_gall0.imgs_class_index] - 1) % wpda_gall0.number_imgs[wpda_gall0.imgs_class_index]; 
		wpda_gall_show_count_and_scrubber_color(wpda_gall0.imgs_class_index, wpda_gall0.q); 
		wpda_gall_check_div_or_canvas_slide_effects();
	};
}
/*36*/
function wpda_gall_aftomat() {
	if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "PLAY") {
		wpda_gall0.play_stop = "STOP"; 
		wpda_gall_p_play_icon.className = wpda_gallc.stop_icon + " wpda_gall_class_icon"; 
		wpda_gall_slide_right();
	} else {
	    if(wpda_gall0.play_stop == "STOP") {
			wpda_gall0.play_stop = "PLAY"; 
			wpda_gall_p_play_icon.className = wpda_gallc.play_icon + " wpda_gall_class_icon"; 
			wpda_gall0.close_open = 1; 
			wpda_gall_loading_img.style.display = "none";
		};
	}
}
/*37*/
function wpda_gall_aftomat1() {
	if(wpda_gall0.close_open == 1 && wpda_gall0.play_stop == "STOP") {
		wpda_gall_slide_right();
	};
} 

////////////////**********   slide functions    ***** ///////////////
/*38*/
function wpda_gall_div_slide_effects(e) {
    if(e == 0) {wpda_gall_popup_img_copi.style.top = "-100%";}
    if(e == 1) {wpda_gall_popup_img_copi.style.left = "-100%";}
    if(e == 2) {wpda_gall_popup_img_copi.style.left = "100%";}         
    if(e == 3) {wpda_gall_popup_img_copi.style.opacity = "0";}
    if(e == 4) {wpda_gall_popup_img_copi.style.top = "100%";}
	if(e == 5) {wpda_gall_popup_img_copi.style.top = "100%";}
}
/*39*/
function wpda_gall_canvas_slide_effects(lab) {
	switch(lab) {
        case 0: {return wpda_gall_t50(), wpda_gall_d00();}; break;        
        case 1: {return wpda_gall_t50(), wpda_gall_d01();}; break;
        case 2: {return wpda_gall_t50(), wpda_gall_d02();}; break;
        case 3: {return wpda_gall_t50(), wpda_gall_d03();}; break;	
	    case 4: {return wpda_gall_t50(), wpda_gall_d04();}; break;
	    case 5: {return wpda_gall_t50(), wpda_gall_d05();}; break;         
        case 6: {return wpda_gall_t50(), wpda_gall_d06();}; break;
		case 7: {return wpda_gall_t50(), wpda_gall_d07();}; break;
		case 8: {return wpda_gall_t50(), wpda_gall_d08();}; break;
        case 9: {return wpda_gall_t50(), wpda_gall_d09();}; break;
		case 10: {return wpda_gall_t50(), wpda_gall_d10();}; break;				 				
		case 11: {return wpda_gall_t50(), wpda_gall_d11();}; break;
		case 12: {return wpda_gall_t50(), wpda_gall_d12();}; break;
		case 13: {return wpda_gall_t50(), wpda_gall_d13();}; break; 
		case 14: {return wpda_gall_t50(), wpda_gall_d14();}; break;
		case 15: {return wpda_gall_t50(), wpda_gall_d15();}; break;
		case 16: {return wpda_gall_t50(), wpda_gall_d16();}; break;
		case 17: {return wpda_gall_t50(), wpda_gall_d17();}; break;
		case 18: {return wpda_gall_t50(), wpda_gall_d18();}; break;
		case 19: {return wpda_gall_t50(), wpda_gall_d19();}; break;
		case 20: {return wpda_gall_t50(), wpda_gall_d20();}; break;
		case 21: {return wpda_gall_t50(), wpda_gall_d21();}; break;
		case 22: {return wpda_gall_t50(), wpda_gall_d22();}; break;
		case 23: {return wpda_gall_t50(), wpda_gall_d23();}; break;
		case 24: {return wpda_gall_t50(), wpda_gall_d24();}; break;
        case 25: {return wpda_gall_t50(), wpda_gall_d25();}; break;
		case 26: {return wpda_gall_t50(), wpda_gall_d26();}; break;
		case 27: {return wpda_gall_t50(), wpda_gall_d27();}; break;
		case 28: {return wpda_gall_t50(), wpda_gall_d28();}; break;								  							  
 	 	case 29: {return wpda_gall_t50(), wpda_gall_d28();}; break;
	}
}  
function wpda_gall_t50() {wpda_gall0.start_time = new Date();} 

 
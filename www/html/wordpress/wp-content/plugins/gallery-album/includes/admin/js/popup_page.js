/*activ tab*/
function submitbutton(value){
	jQuery("#adminForm").attr("action",jQuery("#adminForm").attr("action")+"&task="+value);
	jQuery("#adminForm").submit();
}
jQuery(document).ready(function(){
	jQuery('.wpda_gall_tabs_content span').click(function(){
		jQuery('.wpda_gall_tabs_content span').removeClass('active');
		jQuery(this).addClass('active');
	})
})
wpda_gall_popup_class={
	tabs_content_class:"wpda_gall_tabs_content",
	params_content_class:"wpda_gall_main_settings_div",
	start_tab_id:'popup_general_settings_tab',
	local_save_item:function(tab_id){
		localStorage.setItem('wpda_gall_popup_activ_tab', tab_id);
	},
	local_get_item:function(){
		if(localStorage.getItem('wpda_gall_popup_activ_tab'))
			return localStorage.getItem('wpda_gall_popup_activ_tab');
		else
			return this.start_tab_id;
	},
	conected_array:{
		"popup_overlay_settings_tab":"wpda_gall_overlay",
		"popup_window_navigation_settings_tab":"wpda_gall_bar_icons_out",
		"popup_animation_settings_tab":"wpda_gall_map_popup_img",
		"popup_select_outside_icons_settings_tab":Array("wpda_gall_map_popup_icon_left_bar_","wpda_gall_map_popup_icon_right_bar_"),
		"popup_select_icons_settings_tab":Array("wpda_gall_left_icon","wpda_gall_play_icon","wpda_gall_right_icon","wpda_gall_imgs_count","wpda_gall_close_icon","wpda_gall_setting_icon","wpda_gall_full_icon"),
		"popup_icons_settings_tab":"wpda_gall_left_bar_icons",
		"popup_thumbanils_line_settings_tab":Array("wpda_gall_bar_thumbnail_imgs","wpda_gall_thumbnail_imgs_1","wpda_gall_thumbnail_imgs_2","wpda_gall_thumbnail_imgs_3","wpda_gall_thumbnail_imgs_4"),
		"popup_thumbanils_image_settings_tab":"wpda_gall_map_thumbnail",
		"popup_thumbnail_description_settings_tab":"wpda_gall_map_thumbnail_count",
	},
	activate_tab:function(tab_id){
		var self=this;
		var content_id=tab_id.replace("_tab","");
		jQuery('.'+self.params_content_class+' tr').removeClass('active');
		jQuery('.'+self.params_content_class+' tr.'+content_id).addClass('active');
		jQuery('.'+this.tabs_content_class+' span').removeClass('active');
		jQuery('#'+tab_id).addClass('active');
		jQuery('.wpda_gall_map_settings_map .active').removeClass('active');
		if(Object.prototype.toString.call( self.conected_array[tab_id] ) === '[object Array]'){			
			var i=0;
			for(i=0;i<self.conected_array[tab_id].length;i++){
				jQuery('#'+self.conected_array[tab_id][i]).addClass('active');
			}
		}else{			
			jQuery('#'+self.conected_array[tab_id]).addClass('active');
		}
		self.local_save_item(tab_id);
	},	
	conect_map_settings_to_tab:function(){
		var self=this;
		jQuery.each(self.conected_array,function( index, element ) {
			loc_index=index;
			if(Object.prototype.toString.call( element ) === '[object Array]'){
				i=0;
				for(i=0;i<element.length;i++){
					self.click_on_map(element[i],loc_index);	
				}
			}else{
				self.click_on_map(element,loc_index);
			}
		});	
	},
	click_on_map:function(id,tab_id){
		jQuery("#"+id).click(function(event){
			jQuery("#"+tab_id).trigger('click');
			event.stopPropagation();
		})
	},
	conect_activate_tabs:function(){
		var self=this;
		jQuery('.'+this.tabs_content_class+' span').click(function(){
			var current_tab=jQuery(this).attr("id");
			self.activate_tab(current_tab);
		})
	},
	submit_form:function(){
		jQuery('.wpda_gall_head button.button').click(function(){
			jQuery('#wpda_gall_pupup_form').submit();
		})
	},
	construct_popup:function(){
		var self=this;
		jQuery(document).ready(function(){
			// activate tabs
			self.submit_form();
			self.activate_tab(self.local_get_item());
			self.conect_activate_tabs();
			self.conect_map_settings_to_tab();
		})
	}
}

wpda_gall_popup_class.construct_popup();
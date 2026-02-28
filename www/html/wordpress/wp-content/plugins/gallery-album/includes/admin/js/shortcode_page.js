wpda_gall_post_page_popup={
	tree_info:{},
	lost_elements:[],
	popup_albums_themes:'',
	default_gallery_id:'all',
	default_album_id:'all',
	gallery_slect_id:'select_gallery',
	album_select_id:'select_album',
	start:function(){
		this.set_checkbox_checked();
		this.set_selects_checked();
		this.open_close_sections();		
		this.construct_galleryies();
		insert_gallery();
	},
	construct_galleryies:function(){
		var self=this;
		jQuery('#'+self.gallery_slect_id+' option').remove()
		if(self.default_gallery_id=='all'){
			jQuery('#'+self.gallery_slect_id).append('<option selected value="all">All</option>')
		}else{
			jQuery('#'+self.gallery_slect_id).append('<option  value="all">All</option>')
		}
		if(self.default_gallery_id=='all_'){
			jQuery('#'+self.gallery_slect_id).append('<option selected value="all_">All start with button</option>')
		}else{
			jQuery('#'+self.gallery_slect_id).append('<option  value="all_">All start with button</option>')
		}
		jQuery.each(self.tree_info,function(index,value){
			if(self.default_gallery_id==index){
				jQuery('#'+self.gallery_slect_id).append('<option selected value="'+index+'">'+value['name']+'</option>')
			}else{
				jQuery('#'+self.gallery_slect_id).append('<option value="'+index+'">'+value['name']+'</option>')
			}
		})
		self.construct_albums(self.default_gallery_id);
		jQuery('#'+self.gallery_slect_id).change(function(){
			self.construct_albums(jQuery(this).val());
		})
	},
	construct_albums:function(gallery_id){
		var self=this;
		jQuery('#'+self.album_select_id+' option').remove()
		if(self.album_select_id=='all'){
			jQuery('#'+self.album_select_id).append('<option selected value="all">All</option>')
		}else{
			jQuery('#'+self.album_select_id).append('<option  value="all">All</option>')
		}
		if(typeof(self.tree_info[gallery_id])!='undefined')
		jQuery.each(self.tree_info[gallery_id]['albums'],function(index,value){
			if(index!='name'){
				if(self.default_album_id==index){
					jQuery('#'+self.album_select_id).append('<option selected value="'+index+'">'+value+'</option>')
				}else{
					jQuery('#'+self.album_select_id).append('<option value="'+index+'">'+value+'</option>')
				}
			}
		})
	},
	open_close_sections:function(){
		jQuery('.main_parametrs_group_div > .head_panel_div').click(function(){	
			if(jQuery(this).parent().hasClass('closed_params')){
				jQuery(this).siblings().slideDown( "normal" )
				jQuery(this).parent().removeClass('closed_params');
			}
			else{
				jQuery(this).siblings().slideUp( "normal",function(){jQuery(this).parent().addClass('closed_params');} )
			}		
		})
		this.click_checkbox_inside_header();
	},
	click_checkbox_inside_header:function(){
		var self=this;
		jQuery('.gallery_checkbox,.alboms_checkbox').click(function(event){	
			if(jQuery(this).hasClass("gallery_checkbox")){
				if(jQuery(this).prop("checked")){
					jQuery(this).closest('.main_parametrs_group_div').find(".alboms_checkbox").prop("checked",true);
				}else{
					jQuery(this).closest('.main_parametrs_group_div').find(".alboms_checkbox").prop("checked",false);
				}
			}
			if(jQuery(this).hasClass("alboms_checkbox")){
				if(jQuery(this).prop("checked")){					
					jQuery(this).closest('.main_parametrs_group_div').find(".gallery_checkbox").prop("checked",true);
				}else{	
					/*if(!jQuery(this).closest('.main_parametrs_group_div').find(".alboms_checkbox:checked").length>0){
						jQuery(this).closest('.main_parametrs_group_div').find(".gallery_checkbox").prop("checked",false);
					}*/
				}
			}
			self.regenerete_tree();
			event.stopPropagation();
		});
		
		jQuery('.ch_button').click(function(event){	
			event.stopPropagation();
		});
		self.regenerete_tree();
		jQuery('.ch_button').change(function(event){	
			self.regenerete_tree();
		})
	},
	
	regenerete_tree:function(){
		var self=this;
		var all_checked_gallerys=jQuery(".gallery_checkbox:checked");
		var new_tree={};
		var lost_elements=[];
		var popup_album_themes='';
		jQuery(".gallery_checkbox:checked").each(function(){
			index_of_gallery=jQuery(this).val();
			new_tree[index_of_gallery]={};
			new_tree[index_of_gallery]["name"]=jQuery(this).attr("data-name");
			new_tree[index_of_gallery]["albums"]={}			
			jQuery(this).closest('.main_parametrs_group_div').find(".alboms_checkbox:checked").each(function(){
				var index_of_albom=jQuery(this).val();
				new_tree[index_of_gallery]["albums"][index_of_albom]=jQuery(this).attr("data-name");
			})
		})
		jQuery(".ch_button input:not(:checked)").each(function(){
			index_of_gallery=jQuery(this).val();
			lost_elements.push(index_of_gallery);			
		})
		jQuery(".ch_button.album_selecte_button input").each(function(){
			var album_id= jQuery(this).val();
			var popup_theme_val=jQuery(this).parent().parent().find('select.album_popup_select').val();

			if(popup_theme_val!='0'){
				popup_album_themes+=album_id+':'+popup_theme_val+',';
			}						
		})
		if(popup_album_themes[popup_album_themes.length-1]==','){
			popup_album_themes = popup_album_themes.slice(0, -1);
		}
		this.tree_info=new_tree;
		this.lost_elements=lost_elements;
		this.popup_albums_themes=popup_album_themes;
		this.construct_galleryies();
	},
	
	set_checkbox_checked:function(){
		for(i=0;i<this.lost_elements.length;i++){
			jQuery(".ch_button input[value="+this.lost_elements[i]+"]").prop("checked",false);
		}
	},
	set_selects_checked:function(){
		
		var selected_array=this.popup_albums_themes.split(',')
		if(this.popup_albums_themes!=''){
			for(i=0;i<selected_array.length;i++){
				albums_and_slectval=selected_array[i].split(':')
				checkbox_id=albums_and_slectval[0];
				select_value=albums_and_slectval[1];
				jQuery(".ch_button input[value="+checkbox_id+"]").parent().parent().find('select.album_popup_select').val(select_value);;
			}
		}
	}

}
jQuery(document).ready(function(){
	wpda_gall_post_page_popup.start();
})
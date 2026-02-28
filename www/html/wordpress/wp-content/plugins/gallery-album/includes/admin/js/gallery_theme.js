function submitbutton(value){
	jQuery("#adminForm").attr("action",jQuery("#adminForm").attr("action")+"&task="+value);
	jQuery("#adminForm").submit();
	
}
wpda_gall_theme_class={
	start_tab_id:"theme_general_settings",
	start:function(){
		var self=this;
		jQuery(document).ready(function(){
			self.conect_tab_activate_functionality();
			self.activete_tab(self.start_tab_id);
			jQuery('.wpda_gall_pro_feature').mousedown(function(){
				alert('If you want to use this feature upgrade to Pro Version');
				return false;
			})
		})
	},
	conect_tab_activate_functionality:function(){
		var self=this;
		jQuery(".wpda_gall_theme_link_tabs li").click(function(){
			self.activete_tab(jQuery(this).attr('id').replace("_tab",""));
		});
	},
	activete_tab:function(tab_id){
		console.log(".all_options_panel table tr" + "."+tab_id)
		jQuery(".wpda_gall_theme_link_tabs li,.all_options_panel table tr").removeClass('active');	
		jQuery("#"+tab_id+"_tab").addClass('active');
		jQuery((".all_options_panel table tr" + "."+tab_id)).addClass('active');
	}

}
wpda_gall_theme_class.start();
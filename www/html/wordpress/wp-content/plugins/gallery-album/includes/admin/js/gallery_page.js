wpda_gallery_tree={
	drag_gallery_index:1,
	drag_album_index:1,
	drag_img_index:1,
	drop_gallery_index:1,
	drop_album_index:1,
	drop_img_index:1,	
	drag_img_index_id:1,
	drag_img_index_ord:1,
	drag_gallery_index_name:1,
	drag_album_index_name:1,
	index_dr:1,
	drag_target:1,
	drop_target:1,
	drag_drop:1,	
	drag_obect:{},
	drop_obect:{"0":"0"},
	tree_info:{},
	stored_gallery:[],
	stored_album:[],	
	create_tree:function(){
		var self=this;
		// create tree content 
		self.create_content();
		// create new gallery
		self.create_add_gallery();
		// create gallery existing items
		for (i = 0; i < self.get_gallerys_count(); i++) {
			self.create_gallery_into_tree(i);
		}
		/*#######add functionality############*/		
		this.functionality_tree();
	},	
	// create main content
	create_content:function(){	
		wpda_gall_tree_content_parent = document.createElement('div');
		jQuery('#wpdevart_gallery_page_content .wpdevart_page_head').after(wpda_gall_tree_content_parent);
		wpda_gall_tree_content_parent.className = 'wpda_gall_tree_content_parent';
		
		jQuery('.wpda_gall_tree_content_parent').append('<div class="wpda_gall_load_trash1" title="Recycle Bin"></div><div id="wpda_gall_tree_content" class="wpda_gall_tree_content" title="Navigation panel"></div>');
		wpda_gall_tree_content = document.getElementById("wpda_gall_tree_content");					
	},
	//create add gallery item
	create_add_gallery:function(){		
		jQuery( ".wpda_gall_tree_content" ).eq(0).append( '<div class="wpda_gall_tree_gallery_" title="Instruction"><span class="wpda_gall_tree_galery" title="Gallery Draggable Navigation Tree">Navigation Tree</span></div><div class="wpda_gall_tree_vertical_line"></div><div class="wpda_gall_tree_vertical_line"><div title="add new gallery" class="wpda_gall_create_gallery"><div class="wpda_gall_tree_horizone_line"></div><span title="Create Galley(group of Albums)">Create Gallery</span></div></div>' );
	},
	// insert gallery
	create_gallery_into_tree:function(index){
		var self=this;		
		jQuery( ".wpda_gall_tree_content" ).eq(0).append('<div class="wpda_gall_tree_vertical_line"> </div>');
		jQuery( ".wpda_gall_tree_content" ).eq(0).append('<div class="wpda_gall_tree_open_close_gallery"> <span class="wpda_gall_tree_open_close_gallery_button" title="Expand/Collapse">+</span> <div class="wpda_gall_tree_open_close_gallery_right_span" data-gallery_index="'+index+'" title="Moving the Gallery"></div><div class="wpda_gall_tree_open_close_gallery_horizon_line"></div><span class="wpda_gall_tree_galery_name" style="white-space:nowrap" title="Edit The Gallery Name">' + self.tree_info[index]['name'] + '</span></div>');
		jQuery( ".wpda_gall_tree_content" ).eq(0).append('<div class="wpda_gall_tree_gallery_content"></div>');
		self.create_add_album(index);
		self.create_existing_album(index);
	},
	//create add album item
	create_add_album:function(index){		
		jQuery( ".wpda_gall_tree_gallery_content" ).eq(index).append('<div class="wpda_gall_tree_album_vertical_line"></div>');
		jQuery( ".wpda_gall_tree_gallery_content" ).eq(index).append('<div class="wpda_gall_tree_album_vertical_line"><div class="wpda_gall_tree_create_album" title="add new album"><div class="wpda_gall_tree_album_horizon_line"></div><span title="Create Album(group of Images/Videos)"> Create Album </span></div><span class="wpda_gall_tree_album_last"></span></div>');
	},	
	//insert albums
	create_existing_album:function(index){	
		var self = this;
		var i=0;
		for(i=0;i<self.get_album_count(index);i++){
			jQuery( ".wpda_gall_tree_gallery_content" ).eq(index).append('<div class="wpda_gall_tree_album_vertical_line"></div>');
			jQuery( ".wpda_gall_tree_gallery_content" ).eq(index).append('<div class="wpda_gall_tree_open_close_album"><span class="wpda_gall_tree_open_close_album_button" title="Expand/Collapse">+</span>  <div class="wpda_gall_tree_open_close_album_right_span" data-album_index="'+i+'" data-gallery_index="'+index+'" title="Moving the Album"></div><div class="wpda_gall_tree_open_close_album_horizon_line"></div><span class="wpda_gall_tree_album_name" title="Edit the Album">' + self.tree_info[index][i]['name'] + '</span></div>');
			jQuery( ".wpda_gall_tree_gallery_content" ).eq(index).append('<div class="wpda_gall_tree_album_content"></div>');	
			self.create_add_img(index,i);
			self.create_existing_img(index,i);
		}
	},
	//create add image
	create_add_img:function(index_gallery,index_album){
		jQuery( ".wpda_gall_tree_gallery_content" ).eq(index_gallery).find(".wpda_gall_tree_album_content").eq(index_album).append('<div class="wpda_gall_tree_img_vertical_line"></div>');
		jQuery( ".wpda_gall_tree_gallery_content" ).eq(index_gallery).find(".wpda_gall_tree_album_content").eq(index_album).append('<div class="wpda_gall_tree_img_vertical_line" title="add New image"><div class="wpda_gall_tree_img_horizon_line"></div><span>New</span><div class="wpda_gall_tree_create_img"></div></div>');
	},
	//create exsisting image
	create_existing_img:function(index_gallery,index_album){
		var self = this;
		var i=0;
		for(i=0;i<self.get_img_count(index_gallery,index_album);i++){
			jQuery( ".wpda_gall_tree_gallery_content" ).eq(index_gallery).find(".wpda_gall_tree_album_content").eq(index_album).append('<div class="wpda_gall_tree_img_vertical_line"></div>');
			jQuery( ".wpda_gall_tree_gallery_content" ).eq(index_gallery).find(".wpda_gall_tree_album_content").eq(index_album).append('<div class="wpda_gall_tree_img_content" title="image operations"><div class="wpda_gall_tree_edit_img" style="background-image :url('+wpda_gall_content_url_height + '' + wpda_gallery_tree.tree_info[index_gallery][index_album][i].url+'?'+wpda_gall_load.k+');" data-gallery_index="'+index_gallery+'" data-album_index="'+index_album+'" data-img_index="'+i+'"></div><div class="wpda_gall_tree_img_horizon_line"></div><span class="span_image" date-url="' + self.tree_info[index_gallery][index_album][i]['url'] + '" style="white-space:nowrap">' + self.tree_info[index_gallery][index_album][i]['image_name'] + '</span></div>');
		}		
	},
	/*tree functionality*/
	functionality_tree:function(){	
		var self = this;	
		this.open_close_gallery();
		this.open_close_album();
		jQuery( window ).unload(function() {  return self.storage_items();});		
		this.conect_add_galery_to_form();
		this.conect_add_album_to_form();
		this.conect_edit_gallery_to_tree();	
		this.conect_edit_album_to_tree();
		this.conect_add_new_image();
		this.conect_edit_image();
		this.conect_edit_album();
		this.restore_iems();
		
		//return gallery instruction		
		jQuery('.wpda_gall_tree_gallery_, .wpda_gall_tree_galery').click(function(){              
			localStorage.setItem('wpda_gall_gallery_active_tab_index','0');														
			wpda_gallery_edit_upload.restart_tree();
		});

		//keep tree scroll
		if(!localStorage.getItem('wpda_gall_treeScroll')){localStorage.setItem('wpda_gall_treeScroll',0);}
		setTimeout(function () { jQuery(".wpda_gall_tree_content").scrollTop(localStorage.getItem('wpda_gall_treeScroll'));}, 220);		
		jQuery('.wpda_gall_tree_content').scroll(function(){                                                
			localStorage.setItem('wpda_gall_treeScroll',jQuery(".wpda_gall_tree_content").scrollTop());														
		});
		
		//remove drop targets
		jQuery(".wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_tree_open_close_album_right_span,.wpda_gall_tree_edit_img,.wpda_gall_edited_image").on("dragend", function (event) {
			 jQuery('.wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img,#wpda_gall_edit_album_images_tab').css("box-shadow", "");
		});		
	},
	//store in ceshe on brauzer
	storage_items:function(){
		var gallery_items=[];
		var album_items=this.create_matrix(jQuery('.wpda_gall_tree_open_close_gallery_button').length,jQuery('.wpda_gall_tree_open_close_album_button').length);
		jQuery('.wpda_gall_tree_open_close_gallery_button').each(function(index, element) {			
			gallery_items[ jQuery( ".wpda_gall_tree_open_close_gallery_button" ).index( this )]=(jQuery(this).html()=='+')
        });
		localStorage.setItem('wpda_gall_gallery',JSON.stringify(gallery_items));
		jQuery('.wpda_gall_tree_gallery_content').each(function(index, element) {
            var self_gall=jQuery(this);
			jQuery(self_gall).find('.wpda_gall_tree_open_close_album_button').each(function(index, element) {			
				album_items[ jQuery( ".wpda_gall_tree_gallery_content" ).index( self_gall )][jQuery(self_gall).find( ".wpda_gall_tree_open_close_album_button" ).index( this )]=(jQuery(this).html()=='+')
       	 	});
        });
		localStorage.setItem('wpda_gall_album',JSON.stringify(album_items));
		
	},
	storage_new_image_item:function(gallery_index,album_index){
		localStorage.setItem('wpda_gall_gallery_index',gallery_index);
		localStorage.setItem('wpda_gall_album_index',album_index);
	},
	//load stored parametrs
	restore_iems:function(){
		this.stored_gallery=JSON.parse(localStorage.getItem('wpda_gall_gallery'));
		this.stored_album=JSON.parse(localStorage.getItem('wpda_gall_album'));
		
		jQuery(this.stored_gallery).each(function(index, element) {
			if(this==false)
				jQuery( ".wpda_gall_tree_open_close_gallery_button" ).eq(index).trigger('click');
        });
		var gall_indx=0;
		jQuery(this.stored_album).each(function(index, element) {
			jQuery(this).each(function(index, element) {
				if(this==false)
                	jQuery( ".wpda_gall_tree_gallery_content" ).eq(gall_indx).find(".wpda_gall_tree_open_close_album_button").eq(index).trigger('click')
            });
			gall_indx++;
        });
	},
	//open close gallery
	open_close_gallery:function(gallery_index){var self=this;
			jQuery('.wpda_gall_tree_open_close_gallery_button').click(function(){
				var self_gall=this;
				jQuery(this).parent().next().slideToggle(200,function(){
					if(jQuery(self_gall).html()==='+'){
						jQuery(self_gall).html('-')
					}else{
						jQuery(self_gall).html('+')
					}
					self.storage_items();
				});
				
			})		
	},
	//open close album
	open_close_album:function(){var self=this;
		jQuery('.wpda_gall_tree_open_close_album_button').click(function(){
			var self_album=this;
			jQuery(this).parent().next().slideToggle(200,function(){
				if(jQuery(self_album).html()==='+'){
					jQuery(self_album).html('-')
				}else{
					jQuery(self_album).html('+')
				}
				self.storage_items();
			});	
			
		})
	},
	//create javascript array 
	create_matrix:function(length){	
		var arr = new Array(length || 0),
			i = length;	
		if (arguments.length > 1) {
			var args = Array.prototype.slice.call(arguments, 1);
			while(i--) arr[length-1 - i] = this.create_matrix.apply(this, args);
		}	
		return arr;
	},
	// conect click for add galery
	conect_add_galery_to_form:function(){	
		var self=this;
		jQuery(window).click(function(){self.remove_add_gallery_form();})
		jQuery('.wpda_gall_create_gallery').click(function(event){	                                           								
			//remove other opened forms
			self.tree_remove_inline_opened_boxes();
			//create gallery form				
			self.create_gallery_name_form(this);
			// for not removing element
			event.stopPropagation();
		});	    	
	},
	// create add gallery form

	/***/
	create_gallery_name_form:function(element){
		jQuery(element).append('<div class="wpda_gall_tree_gallery_form_name_conteiner"><form action="" method="post" style="float: left;"><input type="text" name="wpda_gall_tree_gallery_form_name_input" placeholder="Type gallery name" class="regular-text" required="" style="width: 180px;"><button type="submit" class="button dashicons dashicons-welcome-add-page" name="wpda_gall_tree_gallery_form_submit_button"></button></form><button type="submit" name="wpda_gall_tree_gallery_form_cencel_button" class="button wpda_gall_tree_gallery_form_cencel_button dashicons dashicons-no"></button></div>');
		// for security
		wp_nonce_securyty();
		jQuery('.wpda_gall_tree_gallery_form_name_conteiner').click(function(event){event.stopPropagation();});                                     
		jQuery('.wpda_gall_tree_gallery_form_cencel_button').click(function(event){jQuery(this).parent().remove();event.stopPropagation();});       

		jQuery(element).find("button").eq(0).click(function(event){
			event.preventDefault();
			var form_create_gallery_name=jQuery(element).find("input").val();
			form_create_gallery_name = form_create_gallery_name.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018	
			
			var gallery_names = [];			
			for(x in wpda_gallery_tree.tree_info){
				gallery_names[x] = wpda_gallery_tree.tree_info[x].name.toLowerCase();     						
			}						
			if(form_create_gallery_name != ''&& gallery_names.indexOf(form_create_gallery_name.toLowerCase()) < 0){        
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');						
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_create_gallery&drag_g='+form_create_gallery_name,
				}).done(function(date) {

					if(Object.keys(wpda_gallery_tree.tree_info).length == 0){wpda_gallery_tree.tree_info={};}
					wpda_gallery_tree.tree_info[Object.keys(wpda_gallery_tree.tree_info).length]={"name":form_create_gallery_name};
					wpda_gallery_edit_upload.restart_tree();

					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}else{
				alert('Please change out this field');
			}
		});			
	},	

	// remove inline add gallery form
	remove_add_gallery_form:function(){
		if(jQuery('.wpda_gall_tree_gallery_form_name_conteiner').length){jQuery('.wpda_gall_tree_gallery_form_name_conteiner').remove() }
	},	
	
	conect_add_album_to_form:function(){	
		var self=this;
		jQuery(window).click(function(){self.remove_add_album_form()})
		jQuery('.wpda_gall_tree_create_album').click(function(event){                                          						
			//remove other opened forms
			self.tree_remove_inline_opened_boxes();
			// create album				
			self.create_album_add_name_form(this);
			// for not removing element
			event.stopPropagation();
		});			    	
	},
	
	/***/
	create_album_add_name_form:function(element){
		var gallery_name=jQuery(element).closest('.wpda_gall_tree_gallery_content').prev().find('.wpda_gall_tree_galery_name').html();
		jQuery(element).append('<div class="wpda_gall_tree_album_form_name_conteiner"><form action="" method="post" style="float: left;"><input type="text" name="wpda_gall_tree_album_form_name_input" placeholder="Type album name" required="" style="width: 180px;"><button type="submit" class="button dashicons dashicons-welcome-add-page" name="wpda_gall_tree_album_form_submit_button" class="button"></button></form><button type="submit" name="wpda_gall_tree_album_form_cencel_button" class="button wpda_gall_tree_album_form_cencel_button dashicons dashicons-no"></button></div>');
		// for security
		wp_nonce_securyty();
		//for detecting album gallery
		jQuery('.wpda_gall_tree_album_form_name_conteiner form').append('<input type="hidden" name="wpda_gall_tree_gallery_form_name_input">');
		jQuery('.wpda_gall_tree_album_form_name_conteiner form input[name=wpda_gall_tree_gallery_form_name_input]').val(gallery_name)		
		
		jQuery('.wpda_gall_tree_album_form_name_conteiner').click(function(event){event.stopPropagation();});                                
		jQuery('.wpda_gall_tree_album_form_cencel_button').click(function(event){jQuery(this).parent().remove();event.stopPropagation();});   
  
		var form_gallery_index = jQuery(element).closest('.wpda_gall_tree_gallery_content').prev().find('.wpda_gall_tree_open_close_gallery_right_span').attr('data-gallery_index');		   
		var form_gallery_index_name = wpda_gallery_tree.tree_info[form_gallery_index]['name'];
		
		jQuery(element).find("button").eq(0).click(function(event){
			event.preventDefault();
			var form_create_album_name=jQuery(element).find("input").val();			
			form_create_album_name = form_create_album_name.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018
				
			var albom_names = [];			
			for(x in wpda_gallery_tree.tree_info[form_gallery_index]){
				albom_names[x] = wpda_gallery_tree.tree_info[form_gallery_index][x].name
				albom_names[x] = albom_names[x]?albom_names[x].toLowerCase():albom_names[x];     
			}			
			if(form_create_album_name != ''&& albom_names.indexOf(form_create_album_name.toLowerCase()) < 0){   
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');						
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_create_album&drag_g='+form_gallery_index_name+'&drag_a='+form_create_album_name,
				}).done(function(date) {																	
					wpda_gallery_tree.tree_info[form_gallery_index][Object.keys(wpda_gallery_tree.tree_info[form_gallery_index]).length-1]={"name":form_create_album_name};  					
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}else{
				alert('Please change out this field');
			}
		});					
	},	

	remove_add_album_form:function(){
		if(jQuery('.wpda_gall_tree_album_form_name_conteiner').length){jQuery('.wpda_gall_tree_album_form_name_conteiner').remove() }
	},
	// edit gallery
	conect_edit_gallery_to_tree:function(){	
		var self=this;
		jQuery(window).click(function(){self.remove_edit_gallery_form()})
		jQuery('.wpda_gall_tree_galery_name').click(function(event){                                          								
			//remove other appened forms
			self.tree_remove_inline_opened_boxes();
			// create album				
			self.create_edit_gallery_form_to_tree(this);
			// for not removing element
			event.stopPropagation();
		});			    	
	},

	/***/
	create_edit_gallery_form_to_tree:function(element){
		var self=this;
		var name=jQuery(element).html();
		jQuery(element).after('<div class="wpda_gall_edit_gallery_name_content"><form action="" method="post" style="float: left;"><button type="submit" name="wpda_gall_update_gallery_name" class="wpda_gall_rename_gallery button dashicons dashicons-welcome-write-blog"></button><button type="submit" name="wpda_gall_delete_gallery" class="wpda_gall_tree_remove_gallery button dashicons dashicons-trash"></button><button name="wpda_gall_tree_gallery_form_cencel_button" class="button wpda_gall_tree_gallery_edit_form_cencel_button dashicons dashicons-no"></button></form></div>');
		
		//jQuery('.wpda_gall_tree_remove_gallery').click(function(){if(self.confirm_delete_gallery()) return false;})
		// for security
		wp_nonce_securyty();
		// old name gallery
		jQuery('.wpda_gall_edit_gallery_name_content form').append('<input type="hidden" class="wpda_gall_gallery_old_name" name="wpda_gall_old_name" />');
		jQuery('.wpda_gall_edit_gallery_name_content form .wpda_gall_gallery_old_name').val(name)
		
		jQuery('.wpda_gall_edit_gallery_name_content form').prepend('<input type="text" class="input wpda_gall_tree_gallery_form_name_input" name="wpda_gall_tree_gallery_form_name_input" />');
		jQuery('.wpda_gall_edit_gallery_name_content form .wpda_gall_tree_gallery_form_name_input').val(name)
		jQuery('.wpda_gall_edit_gallery_name_content').click(function(event){event.stopPropagation();});                                 	
		jQuery('.wpda_gall_tree_gallery_edit_form_cencel_button').click(function(event){self.remove_edit_gallery_form();return false;}); 	

		//03-04-2017   
		var form_gallery_index = jQuery(element).prev().prev().attr('data-gallery_index');		   
		var form_gallery_index_old_name = wpda_gallery_tree.tree_info[form_gallery_index]['name'];			

		jQuery(element).next().children().eq(0).children().eq(1).click(function(event){
			event.preventDefault();
			var form_gallery_index_name=jQuery("[name='wpda_gall_tree_gallery_form_name_input']").val();
			form_gallery_index_name = form_gallery_index_name.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018
			
			var gallery_names = [];			
			for(x in wpda_gallery_tree.tree_info){
				gallery_names[x] = wpda_gallery_tree.tree_info[x].name.toLowerCase();						
			}						
			if(form_gallery_index_name != ''&& gallery_names.indexOf(form_gallery_index_name.toLowerCase()) < 0){              	
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');					
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_gallery_rename&form_gallery_index_old_name='+form_gallery_index_old_name+'&form_gallery_index_name='+form_gallery_index_name,
				}).done(function(date) {															
					wpda_gallery_tree.tree_info[form_gallery_index]['name']=form_gallery_index_name;  					
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}else{
				alert('Please change out this field');
			}
		});
		jQuery(element).next().children().eq(0).children().eq(2).click(function(event){                              					
			event.preventDefault();
			if(self.confirm_delete_gallery()) {
				return false;
			}else{
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_gallery_delete&form_gallery_index_old_name='+form_gallery_index_old_name,
				}).done(function(date) {																	
					delete wpda_gallery_tree.tree_info[form_gallery_index];
					var drag_tree_info=wpda_gallery_tree.tree_info;
					//drag_tree_info_value=Object.values(drag_tree_info);
					drag_tree_info_value = (typeof Object.values == 'function')?Object.values(drag_tree_info):Object.keys(drag_tree_info).map(function(e) {return drag_tree_info[e]});//23-07-2018
					//var drag_tree_info1= Object.assign({}, drag_tree_info_value);
					var drag_tree_info1= self.toObject(drag_tree_info_value);//23-07-2018
					wpda_gallery_tree.tree_info=drag_tree_info1;						
					localStorage.setItem('wpda_gall_gallery_active_tab_index','0');						
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}
		});
	},		

	remove_edit_gallery_form:function(){
		if(jQuery('.wpda_gall_edit_gallery_name_content').length){jQuery('.wpda_gall_edit_gallery_name_content').remove();event.stopPropagation(); }
	},
	confirm_delete_gallery:function(){		
		confirmed=confirm('do you really want delete gallery? if you delete gallery you lost all albums and images into this gallery')	;
		if(!confirmed){	return true; }
	},
	confirm_delete_images_permanently:function(){		
		confirmed=confirm('do you really want delete images from Media Library Wpdevart Permanently? if you delete images you lost this images from all albums Permanently')	;
		if(!confirmed){	return true; }
	},		
	// edit album
	conect_edit_album_to_tree:function(){	
		var self=this;
		jQuery(window).click(function(){self.remove_edit_album_form()})
		jQuery('.wpda_gall_tree_album_name').click(function(event){                   												
			//remove other appened forms
			self.tree_remove_inline_opened_boxes();
			// create album				
			self.create_edit_album_form_to_tree(this);
			// for not removing element
			event.stopPropagation();
		});			    	
	},

	/***/
	create_edit_album_form_to_tree:function(element){
		var self=this;
		var name_ablom=jQuery(element).html();
		var name_gallery=jQuery(element).closest('.wpda_gall_tree_gallery_content').prev().find('.wpda_gall_tree_galery_name').html();
		jQuery(element).after('<div class="wpda_gall_edit_album_name_content"><form action="" method="post" style="float: left;"><button type="submit" name="wpda_gall_update_album_name" class="wpda_gall_rename_gallery button dashicons dashicons-welcome-write-blog"></button><button type="submit" name="wpda_gall_delete_album" class="wpda_gall_tree_remove_album button dashicons dashicons-trash"></button><button name="wpda_gall_tree_album_form_cencel_button" class="button wpda_gall_tree_album_edit_form_cencel_button dashicons dashicons-no"></button></form></div>');
		
		//jQuery('.wpda_gall_tree_remove_album').click(function(){if(self.confirm_delete_album()) return false;})
		// for security
		wp_nonce_securyty();
		// old name of gallery
		jQuery('.wpda_gall_edit_album_name_content form').append('<input type="hidden" class="wpda_gall_gallery_old_name" name="wpda_gall_gallery_old_name" />');
		jQuery('.wpda_gall_edit_album_name_content form .wpda_gall_gallery_old_name').val(name_gallery);
		// album old name
		jQuery('.wpda_gall_edit_album_name_content form').append('<input type="hidden" class="wpda_gall_album_old_name" name="wpda_gall_album_old_name" />');
		jQuery('.wpda_gall_edit_album_name_content form .wpda_gall_album_old_name').val(name_ablom);
		// album new name
		jQuery('.wpda_gall_edit_album_name_content form').prepend('<input type="text" class="input wpda_gall_tree_album_form_name_input" name="wpda_gall_tree_album_form_name_input" />');
		jQuery('.wpda_gall_edit_album_name_content form .wpda_gall_tree_album_form_name_input').val(name_ablom)
		jQuery('.wpda_gall_edit_album_name_content').click(function(event){event.stopPropagation();});                                              
		jQuery('.wpda_gall_tree_album_edit_form_cencel_button').click(function(event){self.remove_edit_album_form();return false;});                

		var form_album_index = jQuery(element).prev().prev().attr('data-album_index');   
		var form_gallery_index = jQuery(element).prev().prev().attr('data-gallery_index');		
		var form_album_index_old_name = wpda_gallery_tree.tree_info[form_gallery_index][form_album_index]['name'];   
		var form_gallery_index_name = wpda_gallery_tree.tree_info[form_gallery_index]['name'];			

		jQuery(element).next().children().eq(0).children().eq(1).click(function(event){
			event.preventDefault();		
			var form_album_index_name=jQuery("[name='wpda_gall_tree_album_form_name_input']").val();
			form_album_index_name = form_album_index_name.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018
			
			var albom_names = [];			
			for(x in wpda_gallery_tree.tree_info[form_gallery_index]){
				albom_names[x] = wpda_gallery_tree.tree_info[form_gallery_index][x].name;
				albom_names[x] = albom_names[x]?albom_names[x].toLowerCase():albom_names[x];
			}
			//if(form_album_index_name != ''&& !albom_names.includes(form_album_index_name.toLowerCase())){
			if(form_album_index_name != ''&& albom_names.indexOf(form_album_index_name.toLowerCase()) < 0){	                  		
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');						
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_album_rename&form_gallery_index_name='+form_gallery_index_name+'&form_album_index_old_name='+form_album_index_old_name+'&form_album_index_name='+form_album_index_name,
				}).done(function(date) {															
					wpda_gallery_tree.tree_info[form_gallery_index][form_album_index]['name']=form_album_index_name;  					
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}else{
				alert('Please change out this field');
			}
		});
		jQuery(element).next().children().eq(0).children().eq(2).click(function(event){                                                    
			event.preventDefault();	
			if(self.confirm_delete_album()) {
				return false;
			}else{
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_album_delete&form_gallery_index_name='+form_gallery_index_name+'&form_album_index_old_name='+form_album_index_old_name,
				}).done(function(date) {														
					var drag_gallery_name=wpda_gallery_tree.tree_info[form_gallery_index].name;
					delete wpda_gallery_tree.tree_info[form_gallery_index][form_album_index];
					delete wpda_gallery_tree.tree_info[form_gallery_index].name;
					var drag_gallery=wpda_gallery_tree.tree_info[form_gallery_index];
					//drag_gallery_value=Object.values(drag_gallery);
					drag_gallery_value = (typeof Object.values == 'function')?Object.values(drag_gallery):Object.keys(drag_gallery).map(function(e) {return drag_gallery[e]});//23-07-2018
					//var drag_gallery1= Object.assign({}, drag_gallery_value);
					var drag_gallery1= self.toObject(drag_gallery_value);//23-07-2018
					wpda_gallery_tree.tree_info[form_gallery_index]=drag_gallery1;
					wpda_gallery_tree.tree_info[form_gallery_index].name=drag_gallery_name;	
					
					localStorage.setItem('wpda_gall_gallery_active_tab_index','0');
								
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});
			}
		});
	},	
		
	remove_edit_album_form:function(){
		if(jQuery('.wpda_gall_edit_album_name_content').length){jQuery('.wpda_gall_edit_album_name_content').remove();event.stopPropagation(); }
	},
	confirm_delete_album:function(){		
		confirmed=confirm('do you really want delete album? if you delete album you lost all images into this album')	;
		if(!confirmed){	return true;}
	},	
	conect_add_new_image:function(){
		self=this;
		jQuery('.wpda_gall_tree_create_img').click(function(){			
			var loc_self=this;
			var album_name=jQuery(this).closest('.wpda_gall_tree_album_content').prev().find('.wpda_gall_tree_album_name').html()
			var gallery_name=jQuery(this).closest('.wpda_gall_tree_gallery_content').prev().find('.wpda_gall_tree_galery_name').html()
			var index_gallery=jQuery('.wpda_gall_tree_gallery_content').index(jQuery(loc_self).closest('.wpda_gall_tree_gallery_content'))
			var index_album=jQuery('.wpda_gall_tree_gallery_content').eq(index_gallery).find('.wpda_gall_tree_album_content').index(jQuery(loc_self).closest('.wpda_gall_tree_album_content'))

			jQuery('input[name="insert_from_upload_images"]').attr('value', 'Insert album : "'+gallery_name+'/'+album_name+'"'); 
			jQuery('input[name="insert_from_meia_library"]').attr('value', 'Insert album : "'+gallery_name+'/'+album_name+'"'); 
			jQuery('input[name="insert_from_standat_uploader"]').attr('value', 'Start uploading to an album : "'+gallery_name+'/'+album_name+'"'); 
			jQuery('input[name="insert_from_youtube_url"]').attr('value', 'Insert album : "'+gallery_name+'/'+album_name+'"'); 
			
			wpda_gallery_edit_upload.current_page_index = 1;
			jQuery('#from_media_library .thumbnails.with_checkbox').remove();	
			wpda_gallery_edit_upload.create_media_attachments();			
			jQuery('.wpda_gall_add_image_tab_links ul li').removeClass('active');

			//remove active image
			jQuery('.wpda_gall_tree_edit_img').removeClass('image_active');
			jQuery('.wpda_gall_tree_create_img').removeClass('image_active');
			jQuery('.wpda_gall_tree_open_close_album_right_span').removeClass('image_active');
			jQuery(this).addClass('image_active');		
			
			var auxiliary = localStorage.getItem('wpda_gall_index') ? localStorage.getItem('wpda_gall_index'):0;
			var auxiliary_ = localStorage.getItem('wpda_gall_attachments_or_upload') ? localStorage.getItem('wpda_gall_attachments_or_upload'):0;
			if(auxiliary == 1 && auxiliary_ == 1){
				jQuery('.wpda_gall_add_image_tab_links ul li').eq(2).addClass('active');
			}else{
				jQuery('.wpda_gall_add_image_tab_links ul li').eq(auxiliary).addClass('active');	
			}
			jQuery('.wpda_gall_add_image_tab_contents > div').removeClass('active');
			jQuery('.wpda_gall_add_image_tab_contents > div').eq(auxiliary).addClass('active');
			if(auxiliary == 0){
				jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'none');
			}else{
				jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'inline');	
			}
			localStorage.setItem('wpda_gall_gallery_index',index_gallery);
			localStorage.setItem('wpda_gall_album_index',index_album);
						

			wpda_gallery_edit_upload.open_tab('wpda_gall_add_new_image_tab');
			wpda_gallery_edit_upload.insert_gallery_album_name(album_name,gallery_name);
			self.storage_new_image_item(index_gallery,index_album);
			if(localStorage.getItem('wpda_gall_gallery_active_tab_index')){
				if(localStorage.getItem('wpda_gall_gallery_active_tab_index') != 1 && localStorage.getItem('wpda_gall_gallery_active_tab_index') != 2){				
					localStorage.setItem('wpda_gall_gallery_active_tab_index',1);
				}
			}else{
				localStorage.setItem('wpda_gall_gallery_active_tab_index',1);
			}
			//remove active image
			jQuery('.wpda_gall_tree_edit_img').removeClass('image_active');
		})
	},
	// on click edit image
	conect_edit_image:function(){
		var self=this;
		jQuery('.wpda_gall_tree_edit_img').click(function(){
			var gallery_index=jQuery(this).attr('data-gallery_index')
			var album_index=jQuery(this).attr('data-album_index')
			var img_index=jQuery(this).attr('data-img_index')
						
			localStorage.setItem('wpda_gall_gallery_index',gallery_index);
			localStorage.setItem('wpda_gall_album_index',album_index);
			localStorage.setItem('wpda_gall_img_index',img_index);
			localStorage.setItem('wpda_gall_gallery_active_tab_index',4);
						
			var album_name=jQuery(this).closest('.wpda_gall_tree_album_content').prev().find('.wpda_gall_tree_album_name').html()
			var gallery_name=jQuery(this).closest('.wpda_gall_tree_gallery_content').prev().find('.wpda_gall_tree_galery_name').html();
			
			//remove active image
			jQuery('.wpda_gall_tree_edit_img').removeClass('image_active'); 
			jQuery('.wpda_gall_tree_create_img').removeClass('image_active');
			jQuery('.wpda_gall_tree_open_close_album_right_span').removeClass('image_active');
			jQuery(this).addClass('image_active');
			
			wpda_gallery_edit_upload.update_information_for_edit_image(self.tree_info[gallery_index][album_index][img_index]);
			wpda_gallery_edit_upload.open_tab('wpda_gall_edit_image_tab');
			wpda_gallery_edit_upload.insert_gallery_album_name(album_name,gallery_name);
	
		})
		jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'none');
	},

	conect_edit_image_ayax:function(){		
		jQuery("[name='edit_image']").click(function(){
			event.preventDefault();
			jQuery('.wpda_gall_remove_image, .wpda_gall_load_trash').addClass('wpda_gall_display_inline');
			var form_img_index_id=wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')][localStorage.getItem('wpda_gall_img_index')].id;			 
			var form_img_index_description=jQuery("[name='wpda_gall_edit_image_description']").val();			
			var form_img_index_name=jQuery("[name='wpda_gall_edit_image_name']").val();
			form_img_index_description = form_img_index_description.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018			
			form_img_index_name = form_img_index_name.replace(/[&^%$#@!~+'\\]/g,"");//13-11-2018
			
			jQuery.ajax({
				url: wpda_gall_admin_ajax_url+'?action=wpda_gall_form_edit_image&form_img_index_id='+form_img_index_id+'&form_img_index_name='+form_img_index_name+'&form_img_index_description='+form_img_index_description,
			}).done(function(date) {															
				wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')][localStorage.getItem('wpda_gall_img_index')].image_name=form_img_index_name;
				wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')][localStorage.getItem('wpda_gall_img_index')].image_description=form_img_index_description;  					
				wpda_gallery_edit_upload.restart_tree();
				jQuery('.wpda_gall_remove_image, .wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
			});						
		});	
	},
	
	conect_edit_album:function(){
		var self=this;
		jQuery('.wpda_gall_tree_open_close_album_right_span').click(function(){
			var gallery_index=jQuery(this).attr('data-gallery_index');
			var album_index=jQuery(this).attr('data-album_index')

			localStorage.setItem('wpda_gall_gallery_index',gallery_index);
			localStorage.setItem('wpda_gall_album_index',album_index);
			localStorage.setItem('wpda_gall_gallery_active_tab_index',3);

			wpda_gallery_edit_upload.current_page_index = 1;
			wpda_gallery_edit_upload.current_gallery_index = gallery_index;
			wpda_gallery_edit_upload.current_album_index = album_index;			
			wpda_gallery_edit_upload.open_tab('wpda_gall_edit_album_images_tab');
			wpda_gallery_edit_upload.create_edit_album_elements(self.tree_info[gallery_index][album_index],gallery_index,album_index);
			//remove active image
			jQuery('.wpda_gall_tree_edit_img').removeClass('image_active'); 
			jQuery('.wpda_gall_tree_create_img').removeClass('image_active');
			jQuery('.wpda_gall_tree_open_close_album_right_span').removeClass('image_active');
			jQuery(this).addClass('image_active'); 

		});
	},
	/*####### remove tree inline opened boxes ######*/
	tree_remove_inline_opened_boxes:function(){
		this.remove_add_gallery_form();
		this.remove_add_album_form();
		this.remove_edit_gallery_form();
		this.remove_edit_album_form();
	},
	// get elements counts
	get_gallerys_count:function(){var self=this; return Object.keys(self.tree_info).length},
	
	get_album_count:function(gallery_index){var self=this; return (Object.keys(self.tree_info[gallery_index]).length-1)},
	
	get_img_count:function(gallery_index,album_index){var self=this; return (Object.keys(self.tree_info[gallery_index][album_index]).length-1)},
	

	//////////////////////////////////////// drag drop functions///////////////////////////////////	
	//////////////////////////////////////// drag drop functions///////////////////////////////////
	drag_img_drop_img_inversia_rows:function() {
		var tree_info_drag_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index];
		var tree_info_drop_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index];
		var tree_info_drag_row_id=tree_info_drag_row["order_id"];
		var tree_info_drop_row_id=tree_info_drop_row["order_id"];
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]=tree_info_drag_row;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]["order_id"]=tree_info_drop_row_id;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]=tree_info_drop_row;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]["order_id"]=tree_info_drag_row_id;			
	},
	dragEnter_rows:function(event) {
		if ( event.target.nodeName == "TD" && wpda_gallery_tree.drag_target == 'wpda_gall_drop_rows' ){ 
			jQuery('tr td').find("*").addClass("child-elements");
			//jQuery(event.target).closest("tr").css('background-color', '#bbbbbb');
			jQuery(event.target).closest("tr").find("td").css('padding-top', '15px');
			wpda_gallery_tree.index_dr=jQuery(event.target).index();			
		}
	},
	dragLeave_rows:function(event) {		
		if ( jQuery(event.target).index() == wpda_gallery_tree.index_dr && event.target.nodeName == "TD" && wpda_gallery_tree.drag_target == 'wpda_gall_drop_rows' ) { 
			//jQuery(event.target).closest("tr").css('background-color', '');
			jQuery(event.target).closest("tr").find("td").css('padding-top', '');
		}
	},	
	drag_rows:function(event) {                                                           
		event.dataTransfer.setData("Text", "");                                           
		var drag_obect = event.target;

		wpda_gallery_tree.drag_target = 'wpda_gall_drop_rows';
		wpda_gallery_tree.drag_gallery_index = jQuery(drag_obect).closest("tr").attr("data-gallery_index");   
		wpda_gallery_tree.drag_album_index = jQuery(drag_obect).closest("tr").attr("data-album_index");
		wpda_gallery_tree.drag_img_index = jQuery(drag_obect).closest("tr").attr("data-img_index");
		wpda_gallery_tree.drag_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index]['name'];
		wpda_gallery_tree.drag_album_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]['name'];
		wpda_gallery_tree.drag_img_index_id=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]['id'];
		wpda_gallery_tree.drag_img_index_ord=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]['order_id'];		
	},
	drop_rows:function(event) {                                                           
		
		if ( wpda_gallery_tree.drag_target == 'wpda_gall_drop_rows'){
			var drop_img_index_id, drop_img_index_ord, drop_gallery_index_name, drop_album_index_name, drop_obect;
			var self=this;
			drop_obect = event.target;
			
			wpda_gallery_tree.drop_target = 'wpda_gall_drop_rows';		
			wpda_gallery_tree.drop_gallery_index = jQuery(drop_obect).closest("tr").attr("data-gallery_index");
			wpda_gallery_tree.drop_album_index = jQuery(drop_obect).closest("tr").attr("data-album_index");
			drop_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index]['name'];
			drop_album_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]['name'];
			wpda_gallery_tree.drop_img_index = jQuery(drop_obect).closest("tr").attr("data-img_index");
			drop_img_index_id=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]['id'];
			drop_img_index_ord=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]['order_id'];
			
			jQuery('tr td').find("*").removeClass("child-elements");
			jQuery(event.target).closest("tr").find("td").css('padding-top', '');
			if (jQuery(event.target).closest("tr").attr("class") == 'wpda_gall_drop_rows' && wpda_gallery_tree.drag_img_index_id != drop_img_index_id){				
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_img_drop_img_inversia&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drag_i='+wpda_gallery_tree.drag_img_index_id+'&drag_ord='+wpda_gallery_tree.drag_img_index_ord+'&drop_g='+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name+'&drop_i='+drop_img_index_id+'&drop_ord='+drop_img_index_ord,
				}).done(function(date) {															
					self.drag_img_drop_img_inversia_rows();
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');					
				});
			}
		}
	},
	
	/////////////////////////////////////////////////   end   /////////////////////////////////////////////////////	
	/////////////////////////////////////////////////   end   /////////////////////////////////////////////////////		
	
	
	
	//////////////////////////////////////// drag drop functions///////////////////////////////////	
	//////////////////////////////////////// drag drop functions///////////////////////////////////	
	
	//23-07-2018 for E11
	toObject:function(arr) {
	  var rv = {};
	  for (var i = 0; i < arr.length; ++i)
		rv[i] = arr[i];
	  return rv;
	},	

	dragEnter:function(event) {//rechanged 09-09-2017
		if ( wpda_gallery_tree.drag_target != 'wpda_gall_drop_rows'){
			if ((wpda_gallery_tree.drag_target != 'wpda_gall_tree_open_close_album_right_span' && event.target.className.replace(' image_active','') == 'wpda_gall_tree_edit_img') || event.target.className.replace(' image_active','') == 'wpda_gall_tree_open_close_album_right_span' || event.target.className ==  'wpda_gall_tree_open_close_gallery_right_span' || event.target.className ==  'wpda_gall_load_trash1') {    
				if((wpda_gallery_tree.drag_target != 1 && event.target.className != "wpda_gall_tree_open_close_gallery_right_span") || (wpda_gallery_tree.drag_target == "wpda_gall_tree_open_close_gallery_right_span" && event.target.className == "wpda_gall_tree_open_close_gallery_right_span")){
					if(!(wpda_gallery_tree.drag_target == "wpda_gall_tree_open_close_gallery_right_span" && event.target.className.replace(' image_active','') == "wpda_gall_tree_open_close_album_right_span")){
						if(!(wpda_gallery_tree.drag_target == "wpda_gall_tree_open_close_gallery_right_span" && event.target.className.replace(' image_active','') == "wpda_gall_tree_edit_img")){							
							//event.target.style.boxShadow = "0px 0px 0px 0px red";
							event.target.style.WebkitTransform = "scale(2,2)";
							event.target.style.msTransform = "scale(2,2)";
							event.target.style.transform = "scale(2,2)";
							event.target.style.zIndex= "101";
							event.target.style.outline= "#999999 dotted 2px";
						}
					}
				}				
			}
			if (event.target.id == 'wpda_gall_edit_album_form' && wpda_gallery_tree.drag_target != 1 && wpda_gallery_tree.drag_target != "wpda_gall_tree_open_close_gallery_right_span"){
				event.target.style.backgroundColor = "#ccffff";
				jQuery('#wpda_gall_edit_album_images_tab').css("outline", "3px dotted blue");
			}
		}
	},		
	dragLeave:function(event) {
		if (event.target.className.replace(' image_active','') == 'wpda_gall_tree_edit_img' || event.target.className.replace(' image_active','') == 'wpda_gall_tree_open_close_album_right_span' || event.target.className ==  'wpda_gall_tree_open_close_gallery_right_span' || event.target.className ==  'wpda_gall_load_trash1') { 
			//event.target.style.boxShadow = "";
			event.target.style.WebkitTransform = "";
			event.target.style.msTransform = "";
			event.target.style.transform = "";
			event.target.style.outline= "";
			event.target.style.backgroundColor = "";
			event.target.style.zIndex= "100"; 
		}
		if (event.target.id == 'wpda_gall_edit_album_form'){
			event.target.style.backgroundColor = "";
			jQuery('#wpda_gall_edit_album_images_tab').css("outline", "");
		}
	},	
	allowDrop:function(event) {
		event.preventDefault();
	},
	drag:function(event) {
		event.dataTransfer.setData("Text", "");                                

		var aaa = document.getElementsByClassName('image_active')[0];
		if("wpda_gall_edited_image" == event.currentTarget.getAttribute("class")){this.drag_obect = aaa;}else{this.drag_obect = event.currentTarget;};		
		wpda_gallery_tree.drag_target = this.drag_obect.getAttribute("class");		
		wpda_gallery_tree.drag_target = wpda_gallery_tree.drag_target.replace(' image_active','');
			
						
		jQuery('.wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img,#wpda_gall_edit_album_images_tab').css("box-shadow", "");				
		switch(wpda_gallery_tree.drag_target) {
			case 'wpda_gall_tree_open_close_gallery_right_span':
				jQuery('.wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_load_trash1').css("box-shadow", "0px 0px 3px 3px #666666");	
				break;		
			case 'wpda_gall_tree_open_close_album_right_span':
				jQuery('.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1').css("box-shadow", "0px 0px 3px 3px #666666");jQuery('#wpda_gall_edit_album_images_tab').css("box-shadow", "0px 0px 5px 5px #666666");
				break;
			case 'wpda_gall_tree_edit_img':
				jQuery('.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img').css("box-shadow", "0px 0px 3px 3px #666666");jQuery('#wpda_gall_edit_album_images_tab').css("box-shadow", "0px 0px 5px 5px #666666");
				break;
			case 'wpda_gall_edited_image':
				jQuery('.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img').css("box-shadow", "0px 0px 3px 3px #666666");
				break;
		}							
		window.onmouseup = function(){
			jQuery('.wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img,#wpda_gall_edit_album_images_tab').css("box-shadow", "");
		}		
		
				
		wpda_gallery_tree.drag_gallery_index = this.drag_obect.getAttribute("data-gallery_index");
		wpda_gallery_tree.drag_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index]['name'];
		if(wpda_gallery_tree.drag_target != "wpda_gall_tree_open_close_gallery_right_span"){
			wpda_gallery_tree.drag_album_index = this.drag_obect.getAttribute("data-album_index");			
			wpda_gallery_tree.drag_album_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]['name'];
		}
		if(wpda_gallery_tree.drag_target == "wpda_gall_tree_edit_img"){
			wpda_gallery_tree.drag_img_index = this.drag_obect.getAttribute("data-img_index");
			wpda_gallery_tree.drag_img_index_id=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]['id'];
			wpda_gallery_tree.drag_img_index_ord=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]['order_id'];
		}

	},
	drop:function(event) {
		event.preventDefault();             
		var drop_img_index_id, drop_img_index_ord;		
		jQuery('.wpda_gall_tree_open_close_gallery_right_span,.wpda_gall_tree_open_close_album_right_span,.wpda_gall_load_trash1,.wpda_gall_tree_edit_img').css("box-shadow", "0px 0px blue");jQuery('#wpda_gall_edit_album_images_tab').css("box-shadow", "");		
		
		
		if(wpda_gallery_tree.drag_target == 'wpda_gall_tree_edit_img' || wpda_gallery_tree.drag_target == 'wpda_gall_tree_open_close_album_right_span' || wpda_gallery_tree.drag_target ==  'wpda_gall_tree_open_close_gallery_right_span'){		
			var self=this;
			var drop_gallery_index_name, drop_album_index_name;
			this.drop_obect = event.currentTarget;

			if(this.drop_obect.getAttribute('id') == 'wpda_gall_edit_album_form'){
				wpda_gallery_tree.drop_target = "wpda_gall_edit_album_images_tab";
				wpda_gallery_tree.drop_gallery_index = wpda_gallery_edit_upload.current_gallery_index;
				wpda_gallery_tree.drop_album_index = wpda_gallery_edit_upload.current_album_index;
				drop_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index]['name'];
				drop_album_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]['name'];				
			}else{
				wpda_gallery_tree.drop_target = this.drop_obect.getAttribute("class");
				wpda_gallery_tree.drop_target = wpda_gallery_tree.drop_target.replace(' image_active','');
				if(wpda_gallery_tree.drop_target == "wpda_gall_tree_open_close_album_right_span" || wpda_gallery_tree.drop_target == "wpda_gall_tree_edit_img"){		
					wpda_gallery_tree.drop_gallery_index = this.drop_obect.getAttribute("data-gallery_index");
					wpda_gallery_tree.drop_album_index = this.drop_obect.getAttribute("data-album_index");
					drop_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index]['name'];
					drop_album_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]['name'];				
					if(wpda_gallery_tree.drop_target == "wpda_gall_tree_edit_img"){		
						wpda_gallery_tree.drop_img_index = this.drop_obect.getAttribute("data-img_index");
						drop_img_index_id=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]['id'];
						drop_img_index_ord=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]['order_id'];
					}			
				}
				if(wpda_gallery_tree.drop_target == "wpda_gall_tree_open_close_gallery_right_span"){		
					wpda_gallery_tree.drop_gallery_index = this.drop_obect.getAttribute("data-gallery_index");
					drop_gallery_index_name = wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index]['name'];
				}								
			}		
			wpda_gallery_tree.drag_drop = wpda_gallery_tree.drag_target + ' ' + wpda_gallery_tree.drop_target;
			switch(wpda_gallery_tree.drag_drop) {
				case "wpda_gall_tree_edit_img wpda_gall_tree_edit_img":
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_img_drop_img_inversia&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drag_i='+wpda_gallery_tree.drag_img_index_id+'&drag_ord='+wpda_gallery_tree.drag_img_index_ord+'&drop_g='+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name+'&drop_i='+drop_img_index_id+'&drop_ord='+drop_img_index_ord,
					}).done(function(date) {															
					self.drag_img_drop_img_inversia();
					wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					});
					break;       
				case "wpda_gall_tree_edit_img wpda_gall_tree_open_close_album_right_span":
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_img_drop_album&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drag_i='+wpda_gallery_tree.drag_img_index_id+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name,
					}).done(function(date) {															
						self.drag_img_drop_album();
	
						if(Object.keys(wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')]).length > 1){
							localStorage.setItem('wpda_gall_img_index',Math.min(localStorage.getItem('wpda_gall_img_index'),Object.keys(wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')]).length - 2 ));				
						} else{			
							localStorage.setItem('wpda_gall_gallery_active_tab_index','3');				
						}						

						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					});
					break;
				case "wpda_gall_tree_open_close_album_right_span wpda_gall_tree_open_close_album_right_span":
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_album_drop_album_inversia&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name,
					}).done(function(date) {
						var res = JSON.parse(date);
						self.drag_album_drop_album_inversia(res[0],res[1]);

						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break;       
				case "wpda_gall_tree_edit_img wpda_gall_edit_album_images_tab": 
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_img_drop_album&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drag_i='+wpda_gallery_tree.drag_img_index_id+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name,
					}).done(function(date) {															
						self.drag_img_drop_album_tab();
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					});
					break; 
				case "wpda_gall_tree_open_close_album_right_span wpda_gall_edit_album_images_tab": 
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_album_drop_album&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name+'&drop_g='+drop_gallery_index_name+'&drop_a='+drop_album_index_name,
					}).done(function(date) {															
						self.drag_album_drop_album_tab();
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break;       
				case "wpda_gall_tree_edit_img wpda_gall_load_trash1":
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_img_drop_delet&&drag_i='+wpda_gallery_tree.drag_img_index_id,
					}).done(function(date) {															
						self.drag_img_drop_delet();
						if(Object.keys(wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')]).length > 1){
							localStorage.setItem('wpda_gall_img_index',Math.min(localStorage.getItem('wpda_gall_img_index'),Object.keys(wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')]).length - 2 ));				
						} else{			
							localStorage.setItem('wpda_gall_gallery_active_tab_index','3');				
						}					
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break;
				case "wpda_gall_tree_open_close_album_right_span wpda_gall_load_trash1":						
					if(self.confirm_delete_album()) {self.dragLeave(event); wpda_gallery_tree.drag_target = 1; return false;}
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline'); 
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_album_drop_delet&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drag_a='+wpda_gallery_tree.drag_album_index_name,
					}).done(function(date) {															
						self.drag_album_drop_delet();
						
						localStorage.setItem('wpda_gall_gallery_active_tab_index','0');	
											
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break; 
				case "wpda_gall_tree_open_close_gallery_right_span wpda_gall_load_trash1":
					if(self.confirm_delete_gallery())  {self.dragLeave(event); wpda_gallery_tree.drag_target = 1; return false;}				
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_gallery_drop_delet&drag_g='+wpda_gallery_tree.drag_gallery_index_name,
					}).done(function(date) {															
						self.drag_gallery_drop_delet();
						
						localStorage.setItem('wpda_gall_gallery_active_tab_index','0');	
						
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break;
				case "wpda_gall_tree_open_close_gallery_right_span wpda_gall_tree_open_close_gallery_right_span":
					//if(self.confirm_delete_gallery())  {self.dragLeave(event); wpda_gallery_tree.drag_target = 1; return false;}				
					jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
					jQuery.ajax({
						url: wpda_gall_admin_ajax_url+'?action=wpda_gall_drag_gallery_drop_gallery_inversia&drag_g='+wpda_gallery_tree.drag_gallery_index_name+'&drop_g='+drop_gallery_index_name,
					}).done(function(date) {															
						self.drag_gallery_drop_gallery_inversia();
						
						localStorage.setItem('wpda_gall_gallery_active_tab_index','0');	
						
						wpda_gallery_edit_upload.restart_tree();
						jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
					}); 
					break;					
			};
			self.dragLeave(event);
			wpda_gallery_tree.drag_target = 1;
		}	
	},

	drag_img_drop_img_inversia:function() {
		var tree_info_drag_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index];
		var tree_info_drop_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index];
		var tree_info_drag_row_id=tree_info_drag_row["order_id"];
		var tree_info_drop_row_id=tree_info_drop_row["order_id"];
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]=tree_info_drag_row;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index][wpda_gallery_tree.drop_img_index]["order_id"]=tree_info_drop_row_id;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]=tree_info_drop_row;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index]["order_id"]=tree_info_drag_row_id;		
	
	},

	drag_img_drop_album_tab:function() {
		this.drag_img_drop_album();
	},	
	
	drag_img_drop_album:function() {	
		var drop_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index];
		var drag_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];
		var tree_info_drag_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index];

		var drop_album_name=drop_album.name;
		var drag_album_name=drag_album.name;
		delete drop_album.name;
		delete drag_album.name;
		
		delete drag_album[wpda_gallery_tree.drag_img_index];
		drop_album["-1"]=tree_info_drag_row;
		var drop_album_value = (typeof Object.values == 'function')?Object.values(drop_album):Object.keys(drop_album).map(function(e) {return drop_album[e]});//23-07-2018
	
		drop_album_value.sort(function(a, b){return a["order_id"]-b["order_id"]});
		//var drop_album1= Object.assign({}, drop_album_value);
		var drop_album1= this.toObject(drop_album_value);
		
		drop_album1["name"]=drop_album_name;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]=drop_album1;
		var drag_album_value = (typeof Object.values == 'function')?Object.values(drag_album):Object.keys(drag_album).map(function(e) {return drag_album[e]});//23-07-2018

		drag_album_value.sort(function(a, b){return a["order_id"]-b["order_id"]});
		//var drag_album1= Object.assign({}, drag_album_value);
		var drag_album1= this.toObject(drag_album_value);
		
		
		drag_album1["name"]=drag_album_name;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]=drag_album1;					
	},
	
	drag_album_drop_album_tab:function() {
		if(wpda_gallery_tree.drag_gallery_index!=wpda_gallery_tree.drop_gallery_index||wpda_gallery_tree.drop_album_index!=wpda_gallery_tree.drag_album_index){
			var drop_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index];
			var drag_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];

			var drop_album_name=drop_album.name;
			var drag_album_name=drag_album.name;
			delete drop_album.name;
			delete drag_album.name;

			//var drop_album_value = Object.values(drop_album).concat(Object.values(drag_album));
			var drop_album_value = (typeof Object.values == 'function')?Object.values(drop_album).concat(Object.values(drag_album)):Object.keys(drop_album).map(function(e) {return drop_album[e]}).concat(Object.keys(drag_album).map(function(e) {return drag_album[e]}));//23-07-2018
			
			drop_album_value.sort(function(a, b){return a["order_id"]-b["order_id"]});
			//var drop_album1= Object.assign({}, drop_album_value);
			var drop_album1= this.toObject(drop_album_value);//23-07-2018
			drop_album1["name"]=drop_album_name;
			wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]=drop_album1;
			wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]={"name":drag_album_name};
		}
	},
	drag_album_drop_album_inversia:function(drag_name,drop_name) {
		if(wpda_gallery_tree.drag_gallery_index!=wpda_gallery_tree.drop_gallery_index||wpda_gallery_tree.drop_album_index!=wpda_gallery_tree.drag_album_index){
			var drop_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index];
			var drag_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];
			drag_album.name=drag_name;
			drop_album.name=drop_name;

			wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index][wpda_gallery_tree.drop_album_index]=drag_album;
			wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]=drop_album;
		}
	},	
	drag_img_drop_delet:function() {
		var drag_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];
		var tree_info_drag_row=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index][wpda_gallery_tree.drag_img_index];

		var drag_album_name=drag_album.name;
		delete drag_album.name;		
		delete drag_album[wpda_gallery_tree.drag_img_index];

		//drag_album_value=Object.values(drag_album);
		drag_album_value = (typeof Object.values == 'function')?Object.values(drag_album):Object.keys(drag_album).map(function(e) {return drag_album[e]});//23-07-2018
		drag_album_value.sort(function(a, b){return a["order_id"]-b["order_id"]});
		//var drag_album1= Object.assign({}, drag_album_value);
		var drag_album1= this.toObject(drag_album_value);//23-07-2018
		drag_album1["name"]=drag_album_name;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]=drag_album1;						
	},
	
	drag_album_drop_delet:function() {
		//delete only images from album
		/*
		var drag_album=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];
		var drag_album_name=drag_album.name;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index]={"name":drag_album_name};
		*/
		//global delet album
		
		var drag_gallery_name=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index].name;
		delete wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index][wpda_gallery_tree.drag_album_index];
		delete wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index].name;
		var drag_gallery=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index];
		//drag_gallery_value=Object.values(drag_gallery);
		drag_gallery_value = (typeof Object.values == 'function')?Object.values(drag_gallery):Object.keys(drag_gallery).map(function(e) {return drag_gallery[e]});//23-07-2018
		//var drag_gallery1= Object.assign({}, drag_gallery_value);
		var drag_gallery1= this.toObject(drag_gallery_value);//23-07-2018
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index]=drag_gallery1;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index].name=drag_gallery_name;
	},
	
	drag_gallery_drop_delet:function() {
		//delete only images and albums from gallery
		/*		
		var drag_gallery=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index];
		var drag_gallery_name=drag_gallery.name;
		wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index]={"name":drag_gallery_name};
		*/
		//global delet gallery
	
		delete wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index];
		var drag_tree_info=wpda_gallery_tree.tree_info;
		//drag_tree_info_value=Object.values(drag_tree_info);
		drag_tree_info_value = (typeof Object.values == 'function')?Object.values(drag_tree_info):Object.keys(drag_tree_info).map(function(e) {return drag_tree_info[e]});//23-07-2018
		//var drag_tree_info1= Object.assign({}, drag_tree_info_value);
		var drag_tree_info1= this.toObject(drag_tree_info_value);//23-07-2018
		wpda_gallery_tree.tree_info=drag_tree_info1;
			
	},
	drag_gallery_drop_gallery_inversia:function() {
		
		if(wpda_gallery_tree.drag_gallery_index!=wpda_gallery_tree.drop_gallery_index){
			var drop_gallery=wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index];
			var drag_gallery=wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index];

			wpda_gallery_tree.tree_info[wpda_gallery_tree.drop_gallery_index]=drag_gallery;
			wpda_gallery_tree.tree_info[wpda_gallery_tree.drag_gallery_index]=drop_gallery;
		}					
	}	
}

// object for  edit window
var wpda_gallery_edit_upload={
	medi_attachments:[],
	upload_images:[],	
	pagination_buttons_bar:"",
	pagination_buttons_count:1,
	count_image_per_page:30,	
	current_page_index:1,	
	current_gallery_index:0,
	current_album_index:-1,	
	album_images_count:0,
	pagination_buttons:[],
	shift_key_preset:false,
	start:function(){
		var self=this;		
		self.create_pageintion_html();		
		self.activete_image_tab();
		self.create_media_attachments();			
	},
	open_tab:function(tab_name){
		jQuery('.uploader_and_image_editor .tab').removeClass('active')
		jQuery('.uploader_and_image_editor #'+tab_name).addClass('active')
	},
	insert_gallery_album_name:function(album_name,gallery_name){
		jQuery('input[name=gallery_name]').val(gallery_name)
		jQuery('input[name=album_name]').val(album_name)
	},
	activete_image_tab:function(){
		var self=this;

		jQuery('.wpda_gall_add_image_tab_links ul li').click(function(){			
			jQuery('.wpda_gall_add_image_tab_links ul li').removeClass('active');
			jQuery(this).addClass('active');
			jQuery(this).parent().parent().parent().find('.wpda_gall_add_image_tab_contents > div').removeClass('active');
			jQuery(this).parent().parent().parent().find('.wpda_gall_add_image_tab_contents > div').eq(jQuery('.wpda_gall_add_image_tab_links ul li').index(this)).addClass('active');

			switch(jQuery('.wpda_gall_add_image_tab_links ul li').index(this)) {
				case 0:
					jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'none');
					localStorage.setItem('wpda_gall_index',0);
					localStorage.setItem('wpda_gall_gallery_active_tab_index',1);
					break;
				case 1:
					jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'inline');
					jQuery('input[name="insert_from_meia_library"]').css('display', 'inline');
					jQuery('input[name="insert_from_upload_images"]').css('display', 'none');
					jQuery('input[name="remove_from_upload_images"]').css('display', 'none');
					localStorage.setItem('wpda_gall_index',1);
					localStorage.setItem('wpda_gall_attachments_or_upload',0);
					localStorage.setItem('wpda_gall_gallery_active_tab_index',2);
					wpda_gallery_edit_upload.restart_tree();
					break;
				case 2:
					jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'inline');
					jQuery('input[name="insert_from_meia_library"]').css('display', 'none');
					jQuery('input[name="insert_from_upload_images"]').css('display', 'inline');
					jQuery('input[name="remove_from_upload_images"]').css('display', 'inline');					
					localStorage.setItem('wpda_gall_index',1);
					localStorage.setItem('wpda_gall_attachments_or_upload',1);
					localStorage.setItem('wpda_gall_gallery_active_tab_index',2);
					wpda_gallery_edit_upload.restart_tree();
					break;
			}	
		})				
	},
	create_media_attachments:function(){
		var self=this;
		jQuery('#from_media_library .thumbnails.with_checkbox').remove();		
		self.current_album_index = -1;
		if(!localStorage.getItem('wpda_gall_attachments_or_upload')) {localStorage.setItem('wpda_gall_attachments_or_upload',0);};	
		if(localStorage.getItem('wpda_gall_attachments_or_upload') == 0) {
			jQuery('input[name="insert_from_meia_library"]').css('display', 'inline');
			jQuery('input[name="insert_from_upload_images"]').css('display', 'none');
			jQuery('input[name="remove_from_upload_images"]').css('display', 'none');				
			self.pagination_buttons_count = Math.ceil(self.get_attachments_count() / self.count_image_per_page);
			self.album_images_count = Math.min(self.count_image_per_page, self.get_attachments_count() - (self.current_page_index - 1) * self.count_image_per_page);  //var		
			for (var i = 0; i < self.album_images_count; i++) {
				jQuery('#from_media_library').prepend('<div class="thumbnails with_checkbox" style="background-image:url(\''+self.medi_attachments[(self.current_page_index - 1) * self.count_image_per_page + i]['thumb_url']+'?'+wpda_gall_load.k+'\');"><input type="checkbox" class="wpda_gall_media_checkbox" name="medi_library_attachment[]" value="'+self.medi_attachments[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"></div>');
			}
		}else{
			jQuery('input[name="insert_from_meia_library"]').css('display', 'none');
			jQuery('input[name="insert_from_upload_images"]').css('display', 'inline');
			jQuery('input[name="remove_from_upload_images"]').css('display', 'inline');				
			self.pagination_buttons_count = Math.ceil(self.get_upload_images_count() / self.count_image_per_page);
			self.album_images_count = Math.min(self.count_image_per_page, self.get_upload_images_count() - (self.current_page_index - 1) * self.count_image_per_page);  //var
			//12-09-2017		
			for (var i = 0; i < self.album_images_count; i++) {
				var url = self.upload_images[(self.current_page_index - 1) * self.count_image_per_page + i]['thumb_url'];				
				if(url.substr(0,8) == "vimeo___" || url.substr(0,8) == "youtube_"){
					jQuery('#from_media_library').prepend('<div class="thumbnails with_checkbox" style="background-image:url(\''+wpda_gall_content_url_width+self.upload_images[(self.current_page_index - 1) * self.count_image_per_page + i]['thumb_url']+'?'+wpda_gall_load.k+'\');"><input type="checkbox" class="wpda_gall_media_checkbox" name="medi_library_attachment[]" value="'+self.upload_images[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"><div id = "youtube_icon">&#9654;</div></div>');
				}else{
					jQuery('#from_media_library').prepend('<div class="thumbnails with_checkbox" style="background-image:url(\''+wpda_gall_content_url_width+self.upload_images[(self.current_page_index - 1) * self.count_image_per_page + i]['thumb_url']+'?'+wpda_gall_load.k+'\');"><input type="checkbox" class="wpda_gall_media_checkbox" name="medi_library_attachment[]" value="'+self.upload_images[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"></div>');
				}
			}			
		}
		self.set_view_pagination_buttons();
		self.media_attachemnt_thumbnail_click();
		self.select_with_shift_media();	
	},
	select_with_shift_media:function(){	
		var self=this;
		var chkboxes = jQuery('.wpda_gall_media_checkbox');
		chkboxes.click(function(event) {                                                    
			event.stopPropagation();
			if(typeof(lastChecked)==='undefined') {
				lastChecked = this;
				return;
			}
			if(self.shift_key_preset || event.shiftKey) {                                   
				var start = chkboxes.index(this);
				var end = chkboxes.index(lastChecked);
				chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
				if(lastChecked.checked){
					chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).parent().addClass('active');
				}else{
					chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).parent().removeClass('active');
				}
			}
			lastChecked = this;
		});
	},
	media_attachemnt_thumbnail_click:function(){
		var self=this;
		jQuery('#from_media_library .thumbnails.with_checkbox').click(function(event){	       
			if(event.shiftKey){                                                                  
				self.shift_key_preset=true;
			}else{
				self.shift_key_preset=false;
			}
			jQuery(this).find('.wpda_gall_media_checkbox').trigger({type:'click',shiftKey:true});			
		})
		jQuery('#from_media_library .thumbnails.with_checkbox input').click(function(event){      
			if(jQuery(this).is( ":checked" )){
				jQuery(this).parent().addClass('active')				
			}else{
				jQuery(this).parent().removeClass('active')
			}	
		})		
	},
	create_edit_album_elements:function(elements,gallery_index,album_index){ 
		var self=this;		
		jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit').remove();
		
	
		jQuery('#wpda_gall_edit_album_form table').remove();
				
		self.pagination_buttons_count = Math.ceil((Object.keys(elements).length-1) / self.count_image_per_page);
		self.album_images_count = Math.min(self.count_image_per_page, Object.keys(elements).length-1 - (self.current_page_index - 1) * self.count_image_per_page);  //var
		jQuery('#wpda_gall_album_caunt').text(((wpda_gallery_edit_upload.current_page_index - 1) * wpda_gallery_edit_upload.count_image_per_page + 1 ) + ' - ' + ((wpda_gallery_edit_upload.current_page_index - 1) * wpda_gallery_edit_upload.count_image_per_page + self.album_images_count) + ' of ' + (Object.keys(elements).length - 1) + ' items');  //08-08-2017
		
		if(!localStorage.getItem('wpda_gall_redact_album_column')){localStorage.setItem('wpda_gall_redact_album_column','0');};
		if(localStorage.getItem('wpda_gall_redact_album_column') == 0){
			jQuery('input[name="save_album_images_name_description"]').hide();
			jQuery('#wpda_gall_edit_album_form i').eq(0).css('color', 'black');
			jQuery('#wpda_gall_edit_album_form i').eq(1).css('color', '#d57fab');										
			for (var i = 0; i < self.album_images_count; i++) {
				var url = elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url'].split('/').pop();				
				if(url.substr(0,8) == "vimeo___" || url.substr(0,8) == "youtube_"){
					jQuery('#wpda_gall_edit_album_form').append('<div class="thumbnails with_checkbox with_edit" style="background-image:url(\''+wpda_gall_content_url_width+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'?'+wpda_gall_load.k+'\');"><input type="checkbox" class="wpda_gall_album_thumb_checkbox" name="album_thumbnail[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"><span data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'"><span>✎</span></span><div id = "youtube_icon">&#9654;</div></div>');
				}else{
					jQuery('#wpda_gall_edit_album_form').append('<div class="thumbnails with_checkbox with_edit" style="background-image:url(\''+wpda_gall_content_url_width+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'?'+wpda_gall_load.k+'\');"><input type="checkbox" class="wpda_gall_album_thumb_checkbox" name="album_thumbnail[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"><span data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'"><span>✎</span></span></div>');
				}
			}
		}else{			
			jQuery('#wpda_gall_edit_album_form i').eq(0).css('color', '#d57fab');
			jQuery('#wpda_gall_edit_album_form i').eq(1).css('color', 'black');						
			jQuery('#wpda_gall_edit_album_form').append('<table><tr><th><i style="font-size:24px" class="fa">&#xf0ec;</i></th><th><input type="checkbox" name="wpda_gall_album_thumbnail_all"></th><th>id</th><th>image</th><th>image info</th><th>name</th><th>description</th><th>published</th><th>trash</th></tr></table>')		
			for (var i = 0; i < self.album_images_count; i++) {
				var publ = elements[(self.current_page_index - 1) * self.count_image_per_page + i]['published'];
				publ = (publ == 1)?'visibility':'visibility_off';
				
				
				var url = elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url'].split('/').pop();
				if(url.substr(0,8) == "vimeo___" || url.substr(0,8) == "youtube_"){
					jQuery('#wpda_gall_edit_album_form table').append(''+
					  '<tr class="wpda_gall_drop_rows" data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'" ondragenter="wpda_gallery_tree.dragEnter_rows(event)" ondragleave="wpda_gallery_tree.dragLeave_rows(event)" ondrop="wpda_gallery_tree.drop_rows(event)" ondragover="wpda_gallery_tree.allowDrop(event)" draggable="true" ondragstart="wpda_gallery_tree.drag_rows(event)">'+
						'<td class="wpda_gall_drag_rows" drag_td_id="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"><i style="font-size:18px;color:#1e8cbe" class="fa child-elements">&#xf047;</i></td>'+
						'<td><input type="checkbox" class="wpda_gall_album_thumb_checkbox" name="album_thumbnail[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"></td>'+
						'<td >'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'</td>'+
						'<td><div class="thumbnails with_checkbox with_edit" style="background-image:url(\''+wpda_gall_content_url_width+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'?'+wpda_gall_load.k+'\');"><span data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'"><span>✎</span></span><div id = "youtube_icon">&#9654;</div></div></td>'+
						'<td><p class="child-elements"><b>File Demisions:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_w']+'x'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_h']+'</p><p class="child-elements"><b>File size:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_size']+'</p><p class="child-elements"><b>File name:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'</p></td>'+
						'<td><input type="text" name="album_name[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_name']+'"></td>'+
						'<td><textarea rows="4" cols="20" name="album_description[]">'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_description']+'</textarea></td>'+
						'<td><i data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'" visibility_id="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'" class="material-icons" style="font-size:24px;color:#1e8cbe;cursor:pointer;">'+publ+'</i></td>'+
						'<td><i class="material-icons" style="font-size:24px;color:#7052fb;cursor:pointer;">delete</i></td>'+    
					  '</tr>');
				}else{
					jQuery('#wpda_gall_edit_album_form table').append(''+
					  '<tr class="wpda_gall_drop_rows" data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'" ondragenter="wpda_gallery_tree.dragEnter_rows(event)" ondragleave="wpda_gallery_tree.dragLeave_rows(event)" ondrop="wpda_gallery_tree.drop_rows(event)" ondragover="wpda_gallery_tree.allowDrop(event)" draggable="true" ondragstart="wpda_gallery_tree.drag_rows(event)">'+
						'<td class="wpda_gall_drag_rows" drag_td_id="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"><i style="font-size:18px;color:#1e8cbe" class="fa child-elements">&#xf047;</i></td>'+
						'<td><input type="checkbox" class="wpda_gall_album_thumb_checkbox" name="album_thumbnail[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'"></td>'+
						'<td >'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'</td>'+
						'<td><div class="thumbnails with_checkbox with_edit" style="background-image:url(\''+wpda_gall_content_url_width+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'?'+wpda_gall_load.k+'\');"><span data-gallery_index="'+gallery_index+'" data-album_index="'+album_index+'" data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'"><span>✎</span></span></div></td>'+
						'<td><p class="child-elements"><b>File Demisions:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_w']+'x'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_h']+'</p><p class="child-elements"><b>File size:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_size']+'</p><p class="child-elements"><b>File name:</b>'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['url']+'</p></td>'+
						'<td><input type="text" name="album_name[]" value="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_name']+'"></td>'+
						'<td><textarea rows="4" cols="20" name="album_description[]">'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['image_description']+'</textarea></td>'+
						'<td><i data-img_index="'+((self.current_page_index - 1) * self.count_image_per_page + i)+'" visibility_id="'+elements[(self.current_page_index - 1) * self.count_image_per_page + i]['id']+'" class="material-icons" style="font-size:24px;color:#1e8cbe;cursor:pointer;">'+publ+'</i></td>'+
						'<td><i class="material-icons" style="font-size:24px;color:#7052fb;cursor:pointer;">delete</i></td>'+    
					  '</tr>');					
				}				  			
			}
			jQuery('input[name="save_album_images_name_description"]').show();
			jQuery('input[name="save_album_images_name_description"]').mousedown(function(){
				jQuery('#wpda_gall_edit_album_form table tr td:nth-child(2)').children().attr('checked',true);				
			});
			//21-11-2018
			jQuery('#wpda_gall_edit_album_form table tr td:nth-child(1)').siblings().mousedown(function(){
				jQuery( ".wpda_gall_drop_rows" ).attr("draggable","false");
			});
			jQuery( document ).mouseup(function() {
				jQuery( ".wpda_gall_drop_rows" ).attr("draggable","true");
			});   
			
			jQuery('input[name="wpda_gall_album_thumbnail_all"]').click(function(){
				if(this.checked){
					jQuery('#wpda_gall_edit_album_form table tr td:nth-child(2)').children().attr('checked',true);
				}else{
					jQuery('#wpda_gall_edit_album_form table tr td:nth-child(2)').children().attr('checked',false);
				}
			});				
				
			jQuery('#wpda_gall_edit_album_form table tr td:nth-child(8)').children().click(function(){
				jQuery('.wpda_gall_load_trash').addClass('wpda_gall_display_inline');
				var visibility_id = jQuery(this).attr('visibility_id');
				var visibility_value = jQuery(this).text();
				var visibility_value_;
				var sss = this;
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_visibility&visibility_id='+visibility_id+'&visibility_value='+visibility_value,
				}).done(function(date) {															
					if(visibility_value == 'visibility'){
						jQuery(sss).text('visibility_off');
						visibility_value_ = 0;
					}else{
						jQuery(sss).text('visibility');
						visibility_value_ = 1;
					}
					wpda_gallery_tree.tree_info[gallery_index][album_index][jQuery(sss).attr('data-img_index')]["published"] = visibility_value_;					
					//wpda_gallery_edit_upload.restart_tree();
					jQuery('.wpda_gall_load_trash').removeClass('wpda_gall_display_inline');
				});				
			});	

			jQuery('#wpda_gall_edit_album_form table tr td:last-child i').click(function(){
				jQuery('#wpda_gall_edit_album_form table tr td:nth-child(2)').children().attr('checked',false);
				jQuery(this).parent().siblings("td").eq(-7).children().attr('checked',true);
				jQuery('input[name="remov_album_images"]').trigger('click');				
			});	
		}

		jQuery('#wpda_gall_edit_album_form>h2 span').html(wpda_gallery_tree.tree_info[gallery_index]['name']+"/"+elements['name']);
		self.album_thumbnail_click();
		self.select_with_shift_album();
		self.album_thumbnail_edit_click();		
		self.set_view_pagination_buttons();		
	},
	
	redact_album_list_view:function(){
		localStorage.setItem('wpda_gall_redact_album_column','1'); 
		wpda_gallery_edit_upload.restart_tree();
	},
	redact_album_square_view:function(){
		localStorage.setItem('wpda_gall_redact_album_column','0');
		wpda_gallery_edit_upload.restart_tree();
	},

	album_thumbnail_click:function(){
		var self=this;
		jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit').click(function(event){	             
			if(event.shiftKey){
				self.shift_key_preset=true;
			}else{
				self.shift_key_preset=false;
			}
			jQuery(this).find('.wpda_gall_album_thumb_checkbox').trigger({type:'click',shiftKey:true});			
		})
		jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit input').click(function(event){                
			if(jQuery(this).is( ":checked" )){
				jQuery(this).parent().addClass('active')				
			}else{
				jQuery(this).parent().removeClass('active')
			}	
		})		
	},
	select_with_shift_album:function(){	
		var self=this;
		var chkboxes = jQuery('.wpda_gall_album_thumb_checkbox');
		chkboxes.click(function(event) {                                
			event.stopPropagation();
			if(typeof(lastChecked)==='undefined') {
				lastChecked = this;
				return;
			}
			if(self.shift_key_preset || event.shiftKey) {                
				var start = chkboxes.index(this);
				var end = chkboxes.index(lastChecked);
				chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
				if(lastChecked.checked){
					chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).parent().addClass('active');
				}else{
					chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).parent().removeClass('active');
				}
			}
			lastChecked = this;
		});
	},
	album_thumbnail_edit_click:function(){
		var self=this;

		jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit span, #wpda_gall_edit_album_form table tr td span').click(function(event){
			var loc_self=this;
			event.stopPropagation();
			if(typeof(jQuery(loc_self).attr('data-gallery_index'))!='undefined'){
				var gallery_index=jQuery(loc_self).attr('data-gallery_index');
			}else{
				var gallery_index=jQuery(loc_self).parent().attr('data-gallery_index');
			}
			if(typeof(jQuery(loc_self).attr('data-album_index'))!='undefined'){
				var album_index=jQuery(loc_self).attr('data-album_index');
				self.current_album_index = album_index;
			}else{
				var album_index=jQuery(loc_self).parent().attr('data-album_index');
				self.current_album_index = album_index;
			}
			if(typeof(jQuery(loc_self).attr('data-img_index'))!='undefined'){
				var image_index=jQuery(loc_self).attr('data-img_index');
			}else{
				var image_index=jQuery(loc_self).parent().attr('data-img_index');
			}			
			jQuery('.wpda_gall_tree_gallery_content').eq(gallery_index).find('.wpda_gall_tree_album_content').eq(album_index).find('.wpda_gall_tree_edit_img').eq(image_index).trigger('click');
			
			
		})		
	},
	update_information_for_edit_image:function(image_info){
		// get information from image info and places in html
		jQuery('#wpda_gall_edit_image_form input[name=wpda_gall_image_id]').val(image_info['id'])
		jQuery('#edited_image').attr('src',wpda_gall_content_url_main+image_info['url']+'?'+ wpda_gall_load.k)
		jQuery('#edited_image').attr({ondragenter:"wpda_gallery_tree.dragEnter(event)", ondragleave:"wpda_gallery_tree.dragLeave(event)",ondrop:"wpda_gallery_tree.drop(event)", ondragover:"wpda_gallery_tree.allowDrop(event)", draggable:"true", ondragstart:"wpda_gallery_tree.drag(event)"})
		jQuery('.wpda_gall_image_filname span').eq(1).html(image_info['url'])
		jQuery('.wpda_gall_image_filesize span').eq(1).html(image_info['image_size'])
		jQuery('.wpda_gall_image_filedemisions span').eq(1).html(image_info['image_w']+'x'+image_info['image_h'])
		jQuery('.wpda_gall_image_filetype span').eq(1).html(image_info['image_type'])
		jQuery('.wpda_gall_image_uploadeddate span').eq(1).html(image_info['time'])
		jQuery('#wpda_gall_edit_image_form input[name=wpda_gall_edit_image_url]').val(wpda_gall_content_url+image_info['url']);
		jQuery('#wpda_gall_edit_image_form input[name=wpda_gall_edit_image_url]').attr('title',wpda_gall_content_url+image_info['url']);
		jQuery('#wpda_gall_attachment_image_page').attr('href',wpda_gall_content_url.replace("original", "attachment")+image_info['url'].substr(0,image_info['url'].lastIndexOf('.'))+'.html');//19-01-2019		
		jQuery('#wpda_gall_edit_image_form input[name=wpda_gall_edit_image_name]').val(image_info['image_name'])
		jQuery('#wpda_gall_edit_image_form textarea[name=wpda_gall_edit_image_description]').val(image_info['image_description'])		
		jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'none');		
	},

	
	//// pagination		
	create_pageintion_html:function(){
		var i;
		var self=this;		
		jQuery('#uploader_and_image_editor').append('<div id="wpda_gall_pagination_buttons_bar"></div>');
		self.pagination_buttons_bar = document.getElementById("wpda_gall_pagination_buttons_bar");
		for(i = 0; i < 15; i++) {
			this.pagination_buttons[i] = document.createElement('BUTTON');  
			this.pagination_buttons[i].innerHTML = '' + (i + 11);
			this.pagination_buttons[i].className = 'wpda_gall_pagination_buttons';
		}
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[11]);
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[0]);
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[12]); 
		for(i = 1; i < 11; i++) { 
			self.pagination_buttons_bar.appendChild(this.pagination_buttons[i]);
		}
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[13]);
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[10]);
		self.pagination_buttons_bar.appendChild(this.pagination_buttons[14]); 
		for(i = 0; i < 11; i++) {
			this.pagination_buttons[i].innerHTML = '' + (i + 1);
		}
		this.pagination_buttons[11].innerHTML = 'prev'; 
		this.pagination_buttons[12].innerHTML = '...';
		this.pagination_buttons[13].innerHTML = '...';
		this.pagination_buttons[14].innerHTML = 'next';
		
		for(i = 0; i < 11; i++) {
			this.pagination_buttons_click(i);	
		}
		self.pagination_buttons[11].onclick = function() { 
			self.current_page_index = self.current_page_index - 1;

			jQuery('#from_media_library .thumbnails.with_checkbox').remove();
			jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit').remove();
			if(self.current_album_index == -1){		
				self.create_media_attachments();
			} else {
				self.create_edit_album_elements(wpda_gallery_tree.tree_info[self.current_gallery_index][self.current_album_index],self.current_gallery_index,self.current_album_index);	
			}
			self.set_view_pagination_buttons();
		}
		self.pagination_buttons[14].onclick = function() {
			self.current_page_index = self.current_page_index + 1;

			jQuery('#from_media_library .thumbnails.with_checkbox').remove();
			jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit').remove();
			if(self.current_album_index == -1){		
				self.create_media_attachments();
			} else {
				self.create_edit_album_elements(wpda_gallery_tree.tree_info[self.current_gallery_index][self.current_album_index],self.current_gallery_index,self.current_album_index);	
			}
			self.set_view_pagination_buttons();
		}	
	},
	set_view_pagination_buttons:function () {	
		var i;
		jQuery('#wpda_gall_pagination_buttons_bar').css('display', 'inline');		 
		for(i = 0; i < 15; i++) {
			this.pagination_buttons[i].style.display = 'inline';  
			this.pagination_buttons[i].disabled = false;
		}
		if(this.pagination_buttons_count  < 11) {
			this.pagination_buttons[12].style.display = 'none';
			this.pagination_buttons[13].style.display = 'none';
		}
	 //define   pagination_button_activ
		var pagination_button_activ = 6;
		if(this.pagination_buttons_count - this.current_page_index < 6) {
			pagination_button_activ = 10 - (this.pagination_buttons_count - this.current_page_index);
			this.pagination_buttons[13].style.display = 'none';
		}
		if(this.current_page_index < 7) {
			pagination_button_activ = this.current_page_index;
			this.pagination_buttons[12].style.display = 'none';
		}
		if(pagination_button_activ == 10) {this.pagination_buttons[14].disabled = true;}
		if(this.current_page_index == this.pagination_buttons_count) {this.pagination_buttons[14].disabled = true;}
		if(pagination_button_activ == 1) {this.pagination_buttons[11].disabled = true;}
		for(i = 0; i < 11; i++) { 
			this.pagination_buttons[i].innerHTML = this.current_page_index + i - pagination_button_activ + 1;	  
		}
		this.pagination_buttons[pagination_button_activ - 1].disabled = true;
		for(i = 0; i < 11; i++) {
			if(this.pagination_buttons[i].innerHTML > this.pagination_buttons_count ) {
				this.pagination_buttons[i].style.display = 'none';
			}
			if(this.pagination_buttons[i].innerHTML < 1) {
				this.pagination_buttons[i].style.display = 'none';
			} 
		}
		this.pagination_buttons[0].innerHTML = 1;
		this.pagination_buttons[10].innerHTML = this.pagination_buttons_count;
		if(this.pagination_buttons_count == 1) {
			
			for(i = 0; i < 15; i++) {
				this.pagination_buttons[i].style.display = 'none';
			}		
		} 		
	},
	pagination_buttons_click:function(i) { 
		var self=this;
		self.pagination_buttons[i].onclick = function() {		
			self.current_page_index = self.pagination_buttons[i].innerHTML * 1;

			jQuery('#from_media_library .thumbnails.with_checkbox').remove();
			jQuery('#wpda_gall_edit_album_form .thumbnails.with_checkbox.with_edit').remove();
			if(self.current_album_index == -1){		
				self.create_media_attachments();
			} else {
				self.create_edit_album_elements(wpda_gallery_tree.tree_info[self.current_gallery_index][self.current_album_index],self.current_gallery_index,self.current_album_index);
			}
			self.set_view_pagination_buttons();
		}
	},
	restart_tree:function(){
		jQuery(document).ready(function(e) {
		
			var auxiliary = localStorage.getItem('wpda_gall_gallery_active_tab_index');
			jQuery('.wpda_gall_tree_content_parent').remove();	
			wpda_gallery_tree.create_tree();
			
			localStorage.setItem('wpda_gall_gallery_active_tab_index',auxiliary);
			jQuery('.wpda_gall_tree_edit_img, .wpda_gall_tree_open_close_album_right_span, .wpda_gall_tree_open_close_gallery_right_span').attr({ondragenter:"wpda_gallery_tree.dragEnter(event)", ondragleave:"wpda_gallery_tree.dragLeave(event)",ondrop:"wpda_gallery_tree.drop(event)", ondragover:"wpda_gallery_tree.allowDrop(event)", draggable:"true", ondragstart:"wpda_gallery_tree.drag(event)"});
			jQuery('#wpda_gall_edit_album_form, .wpda_gall_load_trash1').attr({ondragenter:"wpda_gallery_tree.dragEnter(event)", ondragleave:"wpda_gallery_tree.dragLeave(event)",ondrop:"wpda_gallery_tree.drop(event)", ondragover:"wpda_gallery_tree.allowDrop(event)"});

	
			switch(localStorage.getItem('wpda_gall_gallery_active_tab_index')) {
				case '0':
					//wpdevard uloader tab
					jQuery('#wpda_gall_add_new_image_tab,#wpda_gall_edit_album_images_tab,#wpda_gall_edit_image_tab,#wpda_gall_welcome_tab').removeClass('active');	jQuery('#wpda_gall_welcome_tab').addClass('active');
					break;				
				case '1':
					//wpdevard uloader tab
					jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_create_img').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');	
					break;		
				case '2':
					//media library uloader tab
					jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_create_img').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');
					if(localStorage.getItem('wpda_gall_attachments_or_upload') == 1) {
						jQuery('.wpda_gall_add_image_tab_links ul li').removeClass('active'); 
						jQuery('.wpda_gall_add_image_tab_links ul li').eq(2).addClass('active');
					}											
					break;
				case '3':
					//edit album images tab
					jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_open_close_album_right_span').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');
					break;
				case '4':
					//edit image tab
					
					jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_album_content').eq(localStorage.getItem('wpda_gall_album_index')).find('.wpda_gall_tree_edit_img').eq(localStorage.getItem('wpda_gall_img_index')).trigger('click');
					break;
			}		
		});		
	},	 

	get_attachments_count:function(){var self=this; if(self.medi_attachments==null){return 0;} return Object.keys(self.medi_attachments).length;},
	get_upload_images_count:function(){var self=this; if(self.upload_images==null){return 0;} return Object.keys(self.upload_images).length;},
}
// setup securyty function
function wp_nonce_securyty(){
	jQuery('form').submit(function(){
		var nonce_main=jQuery('#wpda_gall_gallery_page_nonce_name');
		var nonce_referer=jQuery('#wpda_gall_gallery_page_nonce_name').next();
		jQuery(this).append(nonce_main);
		jQuery(this).append(nonce_referer);		
	})
}

jQuery(document).ready(function(e) {
	wpda_gallery_edit_upload.start();
    wpda_gallery_tree.create_tree();
	wp_nonce_securyty();
	jQuery('input[name="remove_from_upload_images"]').click(function(){if(wpda_gallery_tree.confirm_delete_images_permanently()) return false;});
	wpda_gallery_tree.conect_edit_image_ayax(); 	
		
    jQuery('.wpda_gall_tree_edit_img, .wpda_gall_tree_open_close_album_right_span, .wpda_gall_tree_open_close_gallery_right_span').attr({ondragenter:"wpda_gallery_tree.dragEnter(event)", ondragleave:"wpda_gallery_tree.dragLeave(event)",ondrop:"wpda_gallery_tree.drop(event)", ondragover:"wpda_gallery_tree.allowDrop(event)", draggable:"true", ondragstart:"wpda_gallery_tree.drag(event)"});
    jQuery('#wpda_gall_edit_album_form, .wpda_gall_load_trash1').attr({ondragenter:"wpda_gallery_tree.dragEnter(event)", ondragleave:"wpda_gallery_tree.dragLeave(event)",ondrop:"wpda_gallery_tree.drop(event)", ondragover:"wpda_gallery_tree.allowDrop(event)"});

	switch(localStorage.getItem('wpda_gall_gallery_active_tab_index')) {
		case '1':
			//wpdevard uloader tab
			jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_create_img').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');	
			break;		
		case '2':
			//media library uloader tab
			jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_create_img').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');		
			if(localStorage.getItem('wpda_gall_attachments_or_upload') == 1) {
				jQuery('.wpda_gall_add_image_tab_links ul li').removeClass('active'); 
				jQuery('.wpda_gall_add_image_tab_links ul li').eq(2).addClass('active');
			}
			break;
		case '3':
			//edit album images tab
			jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_open_close_album_right_span').eq(localStorage.getItem('wpda_gall_album_index')).trigger('click');
			break;
		case '4':
			//edit image tab
			jQuery('.wpda_gall_tree_gallery_content').eq(localStorage.getItem('wpda_gall_gallery_index')).find('.wpda_gall_tree_album_content').eq(localStorage.getItem('wpda_gall_album_index')).find('.wpda_gall_tree_edit_img').eq(localStorage.getItem('wpda_gall_img_index')).trigger('click');
			break;
	}		
});



////############################################### ajax upload tools  ########################################
////############################################### ajax upload tools  ########################################

var wpda_gall_load = {
	l               : 0,
	k               : 0,
	open_close      : 1,
	cycle           : 1,
	key             : 0,
	cancel			: 1,
	array_images    : [],
	array_no_images : [],
	countImages     : 0,
	files           : []	
};
jQuery(document).ready(function(){
	document.getElementById('wpda_gall_stadndart_files_upload').addEventListener('change', wpda_gall_load_handle_files, false);

	jQuery( '#wpdevart_standart_uploader #insert_from_standat_uploader' ).click(function( event ) {
		event.preventDefault();
		if(wpda_gall_load.open_close == 1 && wpda_gall_load.countImages > 0){
			wpda_gall_load.open_close = 0;			
			wpda_gall_load.cancel = 1;			
			wpda_gall_load_images();
		}
	});			
});		
function wpda_gall_load_handle_files(evt) {
	jQuery('#wpda_gall_standart_uploaded_image_status1').remove();
	document.getElementById('wpda_gall_load_list_images').innerHTML = '';
	wpda_gall_load.cancel = 1;
	wpda_gall_load.cycle = 1;
	wpda_gall_load.key = 0;
	wpda_gall_load.array_images = [];
	wpda_gall_load.array_no_images = [];
	wpda_gall_load.files = evt.target.files;
	wpda_gall_load.countImages = wpda_gall_load.files.length;
	var countImages_ = wpda_gall_load.countImages;
	var output_images = [];
	var output_no_images = [];
	for (var i = 0; i < countImages_; i++) {
		f = wpda_gall_load.files[i];
		if (!f.type.match('image.*')) {
			wpda_gall_load.array_no_images.push(i);
			wpda_gall_load.countImages --;
			output_no_images.push('<li>', f.name, ' -the file is not image</li>');
			continue;
		}
		if (f.type != 'image/jpeg' && f.type != 'image/JPEG' && f.type != 'image/jpg' && f.type != 'image/JPG' && f.type != 'image/png' && f.type != 'image/PNG' && f.type != 'image/gif' && f.type != 'image/GIF') {
			wpda_gall_load.array_no_images.push(i);
			wpda_gall_load.countImages --;
			output_no_images.push('<li>', f.name, ' -the image is not type -jpeg or jpg or png or gif</li>');
			continue;
		}
		wpda_gall_load.array_images.push(i);		
		output_images.push('<tr class="wpda_gall_tr" id="li', i, '"><td><div id="im', i, '" class="tumb"><div class="load"></div></div></td><td><span><strong>', f.name, '</strong></span></td><td><span class="kbyt">',f.size, ' bytes</span></td><td><progress value="0" max="100" style="width:100px;"></progress></td><td><button class="start"><i class="fa fa-arrow-circle-o-up"></i> Start</button><button class="cancel"><i class="fa fa-ban"></i> Cancel</button><span><button class="right" title="rotate right"><i class="fa fa-rotate-right"></i></button><button class="left" title="rotate left"><i class="fa fa-rotate-left"></i></button></span></td></tr>');
		  
		var reader = new FileReader(); 
		reader.readAsDataURL(f);
		reader.onload = (function(theFile,index) {
			return function(e) {
				var vv = 'im'+index;
				document.getElementById(vv).style.backgroundImage = 'url('+e.target.result+')';
			};
		})(f,i);				  
				  
	}

	document.getElementById('wpda_gall_load_list_images').innerHTML = '<table id="wpda_gall_load_table">' + output_images.join('') + '</table>';
	document.getElementById('wpda_gall_load_list_no_images').innerHTML = '<ul>' + output_no_images.join('') + '</ul><hr>';
	
	
	
	
	
	jQuery(document).ready(function(){
		jQuery("#wpda_gall_load_table .cancel").click(function(){
			if(wpda_gall_load.open_close == 1){	
				var id = jQuery(this).parent().parent().attr('id');
				id = parseInt(id.substr(2));
				var n = wpda_gall_load.array_images.indexOf(id);				
				if (n > -1) {
					wpda_gall_load.array_images.splice(n,1);
					wpda_gall_load.countImages--;
				}					
				jQuery(this).parent().parent().remove();
			}			
		});	


		jQuery("#wpda_gall_load_table .start").click(function(){
			if(wpda_gall_load.open_close == 1){								
				var id = jQuery(this).parent().parent().attr('id');
				id = parseInt(id.substr(2));
				var n = wpda_gall_load.array_images.indexOf(id);				
				if (n > -1) {
					wpda_gall_load.open_close = 0;
					wpda_gall_load.cancel = 1;
					wpda_gall_load.cycle = 0;
					wpda_gall_load.key = n;
					wpda_gall_load_images();
				}
			}			
		});	

		jQuery("#wpdevart_standart_uploader #cancel").click(function(){									
			wpda_gall_load.cancel = 0;
			wpda_gall_load.open_close = 1;
		});

		
		
		jQuery("#wpda_gall_load_table .right").click(function(){
			if(wpda_gall_load.open_close == 1){
				wpda_gall_load.open_close = 0;
				var gallery_name = jQuery(this).parent().attr('gallery-name');		
				var album_name = jQuery(this).parent().attr('album-name');
				var image_name = jQuery(this).parent().parent().siblings().eq(1).text();				
				var id = jQuery(this).parent().parent().parent().attr('id');
				jQuery(this).parent().css("visibility","hidden");
				
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_image_rotate',
					dataType: "html",					
					type: 'POST',
					data : {
						gallery_name : gallery_name,
						album_name : album_name,
						image_name : image_name,
						degree : -90
					},
					beforeSend	: function() {

						jQuery('#wpda_gall_load_table #' + id + ' .load').addClass('wpda_gall_display_inline');

					},					
					success: function(data){//alert('right');
						jQuery('#wpda_gall_load_table #' + id + ' .load').removeClass('wpda_gall_display_inline');
						wpda_gall_load.k++;
						jQuery("#wpda_gall_load_table #" + id).children().eq(0).children().eq(0).css("background-image", "url('"+wpda_gall_content_url_width + data+"?" + wpda_gall_load.k + "')");
						jQuery("#wpda_gall_load_table #" + id).children().eq(4).children().eq(2).css("visibility","visible");
						wpda_gallery_edit_upload.restart_tree();
						wpda_gall_load.open_close = 1;
					},
					error	: function() {
						wpda_gall_load.open_close = 1;
						jQuery('#wpda_gall_load_table #' + id + ' .load').removeClass('wpda_gall_display_inline');
					}					
				});
			}			
		});
		
		
		jQuery("#wpda_gall_load_table .left").click(function(){
			if(wpda_gall_load.open_close == 1){
				wpda_gall_load.open_close = 0;				
				var gallery_name = jQuery(this).parent().attr('gallery-name');		
				var album_name = jQuery(this).parent().attr('album-name');
				var image_name = jQuery(this).parent().parent().siblings().eq(1).text();				
				var id = jQuery(this).parent().parent().parent().attr('id');
				jQuery(this).parent().css("visibility","hidden");
				
				jQuery.ajax({
					url: wpda_gall_admin_ajax_url+'?action=wpda_gall_image_rotate',
					dataType: "html",					
					type: 'POST',
					data : {
						gallery_name : gallery_name,
						album_name : album_name,
						image_name : image_name,
						degree : 90
					},
					beforeSend	: function() {
						jQuery('#wpda_gall_load_table #' + id + ' .load').addClass('wpda_gall_display_inline');

					},					
					success: function(data){
						jQuery('#wpda_gall_load_table #' + id + ' .load').removeClass('wpda_gall_display_inline');
						wpda_gall_load.k++;
						jQuery("#wpda_gall_load_table #" + id).children().eq(0).children().eq(0).css("background-image", "url('"+wpda_gall_content_url_width + data+"?" + wpda_gall_load.k + "')");
						jQuery("#wpda_gall_load_table #" + id).children().eq(4).children().eq(2).css("visibility","visible");
						wpda_gallery_edit_upload.restart_tree();
						wpda_gall_load.open_close = 1;
					},
					error	: function() {
						wpda_gall_load.open_close = 1;
						jQuery('#wpda_gall_load_table #' + id + ' .load').removeClass('wpda_gall_display_inline');
					}					
				});
			}			
		});	
	});					
}

function wpda_gall_load_images() {	
	if (wpda_gall_load.countImages > 0 && wpda_gall_load.cancel == 1) {

		//Grab just one file, since we are not allowing multiple file uploads
		var file = wpda_gall_load.files[wpda_gall_load.array_images[wpda_gall_load.key]];
		var gallery_name = document.getElementById('gallery_name').value;		
		var album_name = document.getElementById('album_name').value;
		var form = jQuery('#wpdevart_standart_uploader');
		var formdata = false;
		if (window.FormData){
			formdata = new FormData();
		}

		//var formAction = form.attr('action');
		
		formdata.append('input_files', file);
		formdata.append('gallery_name', gallery_name);
		formdata.append('album_name', album_name);
		var vv = 'li' + wpda_gall_load.array_images[wpda_gall_load.key];
		var progress = document.getElementById(vv).children[3].children[0];
		jQuery.ajax({
			xhr: function()
				{
				var xhr = new window.XMLHttpRequest();
				//Upload progress
				xhr.upload.addEventListener("progress", function(evt){
				  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					//Do something with upload progress
					jQuery(progress).attr("value", (evt.loaded / evt.total)*100);
				  }
				}, false);
				//Download progress
				xhr.addEventListener("progress", function(evt){
				  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					//Do something with download progress
					jQuery(progress).attr("value", (evt.loaded / evt.total)*100);
				  }
				}, false);
				return xhr;
			},				
			url         : wpda_gall_admin_ajax_url+'?action=wpda_gall_upload_image',
			data        : formdata ? formdata : form.serialize(),
			cache       : false,
			contentType : false,
			processData : false,
			type        : 'POST',				
			beforeSend	: function() {
				var im = 'im'+wpda_gall_load.array_images[wpda_gall_load.key];
				jQuery('#wpda_gall_load_table #' + im + ' .load').addClass('wpda_gall_display_inline');

			},		
			success     : function(data){
					var vv = 'li'+wpda_gall_load.array_images[wpda_gall_load.key];
					var im = 'im'+wpda_gall_load.array_images[wpda_gall_load.key];
					var data = JSON.parse(data);
					document.getElementById(vv).children[1].style.color= 'blue';
					if(data[0]==1){
						jQuery('#wpda_gall_load_table #' + im + ' .load').removeClass('wpda_gall_display_inline');
						jQuery('#wpda_gall_load_table #' + im).css('background-image', wpda_gall_content_url_width + data[3].thumb_url );
						document.getElementById(vv).children[1].children[0].innerHTML = data[3].thumb_url;
						document.getElementById(vv).children[4].children[0].innerHTML = '<i class="fa fa-check"></i>';
						document.getElementById(vv).children[4].children[1].style.display = "none";
						document.getElementById(vv).children[4].children[2].style.display = "inline";
						document.getElementById(vv).children[4].children[2].setAttribute("gallery-name", data[4].gallery_name);
						document.getElementById(vv).children[4].children[2].setAttribute("album-name", data[4].album_name);
						
						var legth_album = wpda_gallery_tree.get_img_count(localStorage.getItem('wpda_gall_gallery_index'),localStorage.getItem('wpda_gall_album_index'));
						wpda_gallery_tree.tree_info[localStorage.getItem('wpda_gall_gallery_index')][localStorage.getItem('wpda_gall_album_index')][legth_album] = data[2];
						var legth_upload_images = wpda_gallery_edit_upload.get_upload_images_count();						
						wpda_gallery_edit_upload.upload_images[legth_upload_images] = data[3];
						wpda_gallery_edit_upload.restart_tree();
						
					}else{
						jQuery('#wpda_gall_load_table #' + im + ' .load').removeClass('wpda_gall_display_inline');
						document.getElementById(vv).children[1].children[0].innerHTML = data[1];
						document.getElementById(im).children[0].style.backgroundImage = 'url(unload.png)';
						document.getElementById(vv).children[4].children[0].innerHTML = '<i class="fa fa-remove"></i>';
						jQuery(progress).attr("value", 0);
					}
					wpda_gall_load.array_images.splice(wpda_gall_load.key,1);
					wpda_gall_load.countImages--;
					if(wpda_gall_load.countImages == 0){wpda_gall_load.open_close = 1;}
					if (wpda_gall_load.cycle == 1) {														
						if (wpda_gall_load.cancel == 1) {
							wpda_gall_load_images();
						}
					}else{
						wpda_gall_load.cycle = 1;
						wpda_gall_load.key = 0;
						wpda_gall_load.open_close = 1;						
					}
				},
			error		: function() {
						wpda_gall_load.open_close = 1;
						jQuery('#wpda_gall_load_table #' + ' .load').removeClass('wpda_gall_display_inline');
				}
		});
	}
};

function GALLERIA_CLASS() {
	var self = this;
			
	this.elements_title_bar = [];		     
	this.elmn_object = [];
	this.object_main_conteiner;
	
	
				

	this.elmn_elements_hgt;
	this.elmn_elements_wdt;	 
	this.elmn_mg; 
	this.elmn_bd; 
	this.elmn_count;
	this.elmn_elements_count;

	this.main_conteiner;
	this.elements = []; 
	this.elements_heights = []; 
	this.elements_widths = []; 			
	this.gallery_current_index = 0; 
	this.album_current_index = 0;		
	this.gall_current_view;
	this.pagination_buttons_bar;
	this.gall_pagination_buttons =[];
	this.count_image_per_page;	
	this.y;

	this.call_load_thumb_images = call_load_thumb_images;
	this.restart_stop_duble_resize = restart_stop_duble_resize;	

	function call_load_thumb_images() {
		if (self.gall_current_view == 'image') {self.load_thumb_images();} 
	}
	function restart_stop_duble_resize() {
		if(self.gall_resizeble == 1) {self.call_duble_resize_for_scroll();}
	}			
}




// define obect:GALLERIA_CLASS_PROTOTYPE      -28 hit functions

var GALLERIA_CLASS_PROTOTYPE = {
	load_thumb_images:function () {
		for(k = 0; k < this.elmn_count; k++) { 
			this.elmn_object[k] = this.elements[k].getBoundingClientRect();
			if( this.elmn_object[k].top < wpda_gall_window_h -0 && this.elmn_object[k].bottom > 0 ){
				//
				var url = wpdevar_gall_img_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k];
				if(url.substr(0,8) == "vimeo___" || url.substr(0,8) == "youtube_"){this.elements[k].children[0].style.display = "inline";}
			
				this.elements[k].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k] + ')';
			}
		}
		this.object_main_conteiner = this.main_conteiner.getBoundingClientRect();
	}
	,
	set_initial_values_for_image:function () {                           
		var k;
		this.y = Math.ceil(wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]] / this.count_image_per_page);
		var image_count = Math.min(this.count_image_per_page, wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]] - (this.k_i - 1) * this.count_image_per_page);	
		var image_partial_count = Math.min(image_count, this.image_partial_count_0);	
				
		for(k = 0; k <= image_count; k++) {    
			this.elements_heights[k] = this.image_wdt * wpdevar_gall_img_url_h[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k] / wpdevar_gall_img_url_w[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k];       
			this.elements_widths[k] = this.image_hgt * wpdevar_gall_img_url_w[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k] / wpdevar_gall_img_url_h[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k];
		}	

		for(k = 0; k <= image_count; k++) {          
			this.elements[k] = document.createElement('div');
			this.main_conteiner.appendChild(this.elements[k]);
			this.elements[k].className = this.tmb_class_name + ' wpda_gall_css_' + this.vrl_id + '_image_kmmtc';
			
			var image = document.createElement('img');                                                 
			image.className = "youtube_icon";                                                          
			image.src = wpdevart_gallery_plugin_url + "assets/img/youtube_icon.png";                   
			this.elements[k].appendChild(image);                                                      			
			
			this.elements_title_bar[k] = document.createElement('div');
			this.elements_title_bar[k].innerHTML = wpdevar_gall_img_name_user[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + k]; 
			this.elements_title_bar[k].className = 'wpda_gall_cs_' + this.vrl_id + '_elements_title_bar';		
			this.elements[k].appendChild(this.elements_title_bar[k]);		
		}
		
		
		this.set_view_pagination_buttons();
		
		if (this.scroll_top == 0) {
			if (this.scroll0 == 1) {
				location.href = '#' + this.main_conteiner.getAttribute("id");   
			}else{
				this.scroll0 = 1;
			}
		}
		
		this.gall_current_view = 'image';
 
		this.elmn_mg = this.image_mgn; 
		this.elmn_bd = this.image_brd; 
		this.elmn_count = image_partial_count; 
		this.elmn_elements_count = image_count;
		this.elmn_elements_hgt = this.image_hgt;
		this.elmn_elements_wdt = this.image_wdt;
		//
		if(this.go_back_button_show < 1){
			this.go_back_button.style.display = 'none';
		}else{
			this.go_back_button.style.display = 'inline';	
		}
	}
	,
	set_initial_values_for_album:function () {
		var j, k;	
		this.y = Math.ceil(this.wpdevar_gall_length_gallerianer[this.gallery_current_index] / this.count_image_per_page);
		var album_count = Math.min(this.count_image_per_page, this.wpdevar_gall_length_gallerianer[this.gallery_current_index] - (this.k_a - 1) * this.count_image_per_page);	
		var album_partial_count = Math.min(album_count, this.album_partial_count_0);	
		
		for(j = 0; j <= album_count; j++) {
			this.elements_heights[j] = this.album_hgt; 
			this.elements_widths[j] = this.album_wdt; 
		}



		//albom sizes 	
		for(j = 0; j < album_count; j++) {
			if(wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]] > 0) {
				this.elements_heights[j] = this.album_wdt * wpdevar_gall_img_url_h[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]][0] / wpdevar_gall_img_url_w[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]][0];
				this.elements_widths[j] = this.album_hgt * wpdevar_gall_img_url_w[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]][0] / wpdevar_gall_img_url_h[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]][0];
			}
		}	
		//

		var inset_images = [],inset_kazm = [];
		for(j = 0; j <= album_count; j++) {
			inset_images[j] = [];
			inset_kazm[j] = [];	
			this.elements[j] = document.createElement('div');	                 
			this.main_conteiner.appendChild(this.elements[j]);	    
			this.elements[j].className = this.tmb_class_name + ' wpda_gall_css_' + this.vrl_id + '_album_kmmtc';
			this.elements_title_bar[j] = document.createElement('div');
			this.elements_title_bar[j].innerHTML = wpdevar_gall_album_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]] + ''; 
			this.elements_title_bar[j].className = 'wpda_gall_cs_' + this.vrl_id + '_elements_title_bar';		
			this.elements[j].appendChild(this.elements_title_bar[j]);
			
							
			for(k = 0; k < Math.min(4, wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]]); k++) {           
				inset_images[j][k] = document.createElement('img');
				this.elements[j].appendChild(inset_images[j][k]);
				inset_images[j][k].className = 'wpda_gall_cs_album_images wpda_gall_cs_rotate' + k;
			}
			if (j < album_partial_count) {
	
				for(k = 0; k < Math.min(4, wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]]); k++) {           
					inset_images[j][k].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]][k] + ')';
				}
				//
				if (wpdevar_gall_length_album[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][((this.k_a - 1) * this.count_image_per_page + j)]] == 0){
					this.elements[j].style.backgroundImage = 'url(' +  wpdevart_gallery_plugin_url + '/assets/img/album-empoty.jpg)';
				}				
			}						
		}
		
			
		this.set_view_pagination_buttons();
		
		if (this.scroll_top == 0) {
			if (this.scroll0 == 1) {
				location.href = '#' + this.main_conteiner.getAttribute("id");  
			}else{
				this.scroll0 = 1;
			}
		}
		
		this.gall_current_view = 'album';
 
		this.elmn_mg = this.album_mgn; 
		this.elmn_bd = this.album_brd; 
		this.elmn_count = album_partial_count; 
		this.elmn_elements_count = album_count;
		this.elmn_elements_hgt = this.album_hgt;
		this.elmn_elements_wdt = this.album_wdt;
		//
		if(this.go_back_button_show < 2){
			this.go_back_button.style.display = 'none';
		}else{
			this.go_back_button.style.display = 'inline';
		}
	}
	,
	set_initial_values_for_gallery:function () {
		var i, j;	
		this.y = Math.ceil(this.wpdevar_gall_gallery_count / this.count_image_per_page);
		var gallery_count = Math.min(this.count_image_per_page, this.wpdevar_gall_gallery_count - (this.k_g - 1) * this.count_image_per_page);				
		var gallery_partial_count = Math.min(gallery_count, this.gallery_partial_count_0);
			
		for(i = 0; i <= gallery_count; i++) {
			this.elements_heights[i] = this.gallery_hgt; 
			this.elements_widths[i] = this.gallery_wdt;  
		}

		//gallery sizes 
		for(i = 0; i < gallery_count; i++) {
			if (wpdevar_gall_length_album[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][0]] > 0) {
				this.elements_heights[i] = this.gallery_wdt * wpdevar_gall_img_url_h[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][0]][0] / wpdevar_gall_img_url_w[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][0]][0];
				this.elements_widths[i] = this.gallery_hgt * wpdevar_gall_img_url_w[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][0]][0] / wpdevar_gall_img_url_h[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][0]][0];
			}
		}	
		//
	
		var inset_images = [],inset_kazm = [];
		for(i = 0; i <= gallery_count; i++) {
			inset_images[i] = [];
			inset_kazm[i] = [];	
			this.elements[i] = document.createElement('div');	
			this.main_conteiner.appendChild(this.elements[i]);	    
			this.elements[i].className = this.tmb_class_name + ' wpda_gall_css_' + this.vrl_id + '_gallery_kmmtc';
			this.elements_title_bar[i] = document.createElement('div');
			this.elements_title_bar[i].innerHTML = wpdevar_gall_gallery_name[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]] + ''; 
			this.elements_title_bar[i].className = 'wpda_gall_cs_' + this.vrl_id + '_elements_title_bar';		
			this.elements[i].appendChild(this.elements_title_bar[i]);		
			for(j = 0; j < Math.min(4, this.wpdevar_gall_length_gallerianer[((this.k_g - 1) * this.count_image_per_page + i)]); j++) {            
				inset_images[i][j] = document.createElement('img');
				this.elements[i].appendChild(inset_images[i][j]);
				inset_images[i][j].className = 'wpda_gall_cs_gallery_images wpda_gall_cs_gal_rotate' + j;
			}
			
			for(j = 0; j < 2; j++) {            
				inset_kazm[i][j] = document.createElement('span');
				this.elements[i].appendChild(inset_kazm[i][j]);
				inset_kazm[i][j].className = 'wpda_gall_cs_gal_kazm' + j;
			}		
			//inset_kazm[i][1].innerHTML = 'GALLERY';
			if (i < gallery_partial_count) {

				for(j = 0; j < Math.min(4, this.wpdevar_gall_length_gallerianer[((this.k_g - 1) * this.count_image_per_page + i)]); j++) {
					if (wpdevar_gall_length_album[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][j]] > 0) {
						inset_images[i][j].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[this.gallery_tema[((this.k_g - 1) * this.count_image_per_page + i)]][this.album_tema[((this.k_g - 1) * this.count_image_per_page + i)][j]][0] + ')';
					} else {
						inset_images[i][j].style.backgroundImage = 'url(' +  wpdevart_gallery_plugin_url + '/assets/img/album-empoty.jpg)';
					}
				}	
			}				
		}
				
		this.set_view_pagination_buttons();
		
		if (this.scroll_top == 0) {
			if (this.scroll0 == 1) {
				location.href = '#' + this.main_conteiner.getAttribute("id"); 
			}else{
				this.scroll0 = 1;
			}
		}
			
		this.gall_current_view = 'gallery';
 
		this.elmn_mg = this.gallery_mgn; 
		this.elmn_bd = this.gallery_brd; 
		this.elmn_count = gallery_partial_count; 
		this.elmn_elements_count = gallery_count;
		this.elmn_elements_hgt = this.gallery_hgt;
		this.elmn_elements_wdt = this.gallery_wdt;

		this.sort_mode_conteiner.children[0].style.display = 'inline';
		if(this.go_back_button_show < 3){
			this.go_back_button.style.display = 'none';
		}else{
			this.go_back_button.style.display = 'inline';	
		}		
	}
	,
	call_duble_resize_for_scroll:function () {
		//this.resize();
		this.resize();
		this.set_view_pagination_buttons();		
	}	
	,
	resize:function () {
		var j;	
		wpda_gall_window_h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		
		for(j = 0; j < this.elmn_count; j++) { 
			this.elements[j].style.display = 'inline-block';
			this.elements_title_bar[j].className = 'wpda_gall_cs_' + this.vrl_id + '_elements_title_bar';
		}	
		for(j = this.elmn_count; j <= this.elmn_elements_count; j++) { 
			this.elements[j].style.display = 'none';
		}
		this.hide_more_button();		
		switch (this.tmb_class_name) {
			case 'wpda_gall_cs_kirpich':
				this.masonry_horizone_dinamic_size();	
				break;
			case 'wpda_gall_cs_masony': 
				this.masonry_dinamic_size();
				break;
			case 'wpda_gall_cs_mosaik': 
				this.mosaic_dinamic_size();
				break;
			case 'wpda_gall_cs_tumbnails': 
				this.thumbnails_dinamic_size();
				break;
			case 'wpda_gall_cs_kirpich_': 
				this.masonry_horizone_fixed_size();	
				break;		
			case 'wpda_gall_cs_masony_': 
				this.masonry_fixed_size();
				break;
			case 'wpda_gall_cs_mosaik_': 
				this.mosaic_fixed_size();
				break;
			case 'wpda_gall_cs_tumbnails_': 
				this.thumbnails_fixed_size();
				break;		
			case 'wpda_gall_cs_column': 
				this.column();
				break;		
		}		
		
		this.call_load_thumb_images();
			
	}
	,

	thumbnails_dinamic_size:function () {
		var widt = this.main_conteiner.clientWidth - 1;
		var i;
		var mb = 2 * (this.elmn_mg + this.elmn_bd);
		var m = Math.min(Math.max(1, Math.floor((widt + 1) / (this.elmn_elements_wdt + mb))), this.gall_max_columns_count);	
		var beta = (widt / m - mb) / this.elmn_elements_wdt;	
		var elements_hgt1 = this.elmn_elements_hgt;
		this.elmn_elements_hgt = beta * elements_hgt1;
		var elements_wdt1 = this.elmn_elements_wdt;
		this.elmn_elements_wdt = beta * elements_wdt1;
		for(var k = 0; k <= this.elmn_count; k++) { 
			this.elements[k].style.width = beta * elements_wdt1 + 'px';		
			this.elements[k].style.height = beta * elements_hgt1 + 'px';    
		}	
		var wmb = this.elmn_elements_wdt + mb;
		var hmb = this.elmn_elements_hgt + mb;	
		this.main_conteiner.style.height = (hmb * Math.ceil(this.elmn_count / m) + 0 + this.pagination_buttons_bar_h) + 'px';	
		this.main_conteiner.style.textAlign = 'center';
		this.elmn_elements_wdt = elements_wdt1;	
		this.elmn_elements_hgt = elements_hgt1;	
	}
	,

	add_more_elements:function () {
		var self = this;
		var elmn_count0 = self.elmn_count;
		self.elmn_count = Math.min(self.elmn_count + self.count_of_added_elements, self.elmn_elements_count); 	
		switch (self.gall_current_view) {
			case 'image':    
				var s = document.createElement('script');			
				jQuery.ajax({
					url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+elmn_count0+'&limit='+(self.elmn_count-elmn_count0)+'&id='+self.vrl_id,
				}).done(function(date) {															
					//console.log(date);
					s.innerHTML = date;
					document.body.appendChild(s);
					for(k = elmn_count0; k < self.elmn_count; k++) {
						//	
						self.elmn_object[k] = self.elements[k].getBoundingClientRect();
						if( self.elmn_object[k].top < wpda_gall_window_h -0 && self.elmn_object[k].bottom > 0 ){				
							self.elements[k].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k] + ')';
						}
						self.elements_heights[k] = self.image_wdt * wpdevar_gall_img_url_h[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k] / wpdevar_gall_img_url_w[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k];       
						self.elements_widths[k] = self.image_hgt * wpdevar_gall_img_url_w[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k] / wpdevar_gall_img_url_h[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k];			
						self.elements_title_bar[k].innerHTML = wpdevar_gall_img_name_user[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]][(self.k_i - 1) * self.count_image_per_page + k];						
					}
					wpda_gall0.number_imgs[self.vrl_id] = self.elmn_count;
					self.open_image1();			
					self.call_duble_resize_for_scroll();				
				});				
				break;
			case 'album':
				for(j = elmn_count0; j < self.elmn_count; j++) {
					for(k = 0; k < Math.min(4, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][((self.k_a - 1) * self.count_image_per_page + j)]]); k++) {           
						self.elements[j].children[1+k].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][((self.k_a - 1) * self.count_image_per_page + j)]][k] + ')';
					}	
				}
				self.call_duble_resize_for_scroll();				
				break;
			case 'gallery':
				for(i = elmn_count0; i < self.elmn_count; i++) {
					for(j = 0; j < Math.min(4, self.wpdevar_gall_length_gallerianer[((self.k_g - 1) * self.count_image_per_page + i)]); j++) {          
						self.elements[i].children[1+j].style.backgroundImage = 'url(' +  wp_content_url + '/' + 'wpdevart_gallery/width/' + wpdevar_gall_img_name[self.gallery_tema[((self.k_g - 1) * self.count_image_per_page + i)]][self.album_tema[((self.k_g - 1) * self.count_image_per_page + i)][j]][0] + ')';
					}	
				}
				self.call_duble_resize_for_scroll();
				break;
		}				
	}
	,
	remove_exsists_elements:function () {
		this.elmn_count = Math.max(this.elmn_count - this.count_of_added_elements, 1);
		wpda_gall0.number_imgs[this.vrl_id] = this.elmn_count;	 
	}
	,
	open_gallery:function () {
	   var i;

	   jQuery(this.pagination_buttons_bar).siblings().remove();
	   this.set_initial_values_for_gallery();
	   for(i = 0; i < this.elmn_elements_count; i++) {this.call_open_gallery(i);}   
	} 
	,
	open_album:function () {
	   var j;

	   jQuery(this.pagination_buttons_bar).siblings().remove();
	   this.set_initial_values_for_album();
	   for(j = 0; j < this.elmn_elements_count; j++) {this.call_open_album(j);}   
	}
	,
	open_image:function () {
		
		var j;

		jQuery(this.pagination_buttons_bar).siblings().remove();
		this.set_initial_values_for_image();

		//
		wpda_gall0.gallery_current_index = this.gallery_tema[this.gallery_current_index];
		wpda_gall0.album_current_index = this.album_tema[this.gallery_current_index][this.album_current_index];
		
		wpda_gall0.Big_imgs[this.vrl_id] = [];	  
		wpda_gall0.imgs_little[this.vrl_id] = document.getElementsByClassName(this.tmb_class_name + ' wpda_gall_css_' + this.vrl_id + '_image_kmmtc');
		wpda_gall0.number_imgs[this.vrl_id] = wpda_gall0.imgs_little[this.vrl_id].length - 1;
		wpda_gall0.count_images_prev_per_page[this.vrl_id] = (this.k_i - 1) * this.count_image_per_page;
		for( j = 0; j < wpda_gall0.number_imgs[this.vrl_id]; j++ ) {				  				  				  
			wpda_gall0.Big_imgs[this.vrl_id][j] = '' +  wp_content_url + '/' + 'wpdevart_gallery/'+this.image_quality+'/' + wpdevar_gall_img_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + j] + '';		
		}
		for ( j = 0; j < wpda_gall0.number_imgs[this.vrl_id]; j++ ) { wpda_gall_click_img_little(this.vrl_id, j); }
		wpda_gall0.number_imgs[this.vrl_id] = this.elmn_count;
	}
	,
	open_image1:function () {
		var j;

		//
		wpda_gall0.gallery_current_index = this.gallery_tema[this.gallery_current_index];
		wpda_gall0.album_current_index = this.album_tema[this.gallery_current_index][this.album_current_index];
		
		wpda_gall0.imgs_little[this.vrl_id] = document.getElementsByClassName(this.tmb_class_name + ' wpda_gall_css_' + this.vrl_id + '_image_kmmtc');
		wpda_gall0.number_imgs[this.vrl_id] = wpda_gall0.imgs_little[this.vrl_id].length - 1;
		for( j = 0; j < wpda_gall0.number_imgs[this.vrl_id]; j++ ) {				  				  				  
			wpda_gall0.Big_imgs[this.vrl_id][j] = '' +  wp_content_url + '/' + 'wpdevart_gallery/'+this.image_quality+'/' + wpdevar_gall_img_name[this.gallery_tema[this.gallery_current_index]][this.album_tema[this.gallery_current_index][this.album_current_index]][(this.k_i - 1) * this.count_image_per_page + j] + '';		
		}

		wpda_gall0.number_imgs[this.vrl_id] = this.elmn_count;
	}
	,
	call_open_gallery:function (i) {
		var self = this;	
		this.elements[i].onmouseup = function(event) {
			self.gallery_current_index = ((self.k_g - 1) * self.count_image_per_page + i);		
			self.current_page_index = self.k_a;					
			self.open_album();
			self.call_duble_resize_for_scroll();
		}
	}
	,
	call_open_gallery1:function (i) {
		var self = this;	
		self.gallery_current_index = ((self.k_g - 1) * self.count_image_per_page + i);		
		self.current_page_index = self.k_a;					
		self.open_album();
		self.call_duble_resize_for_scroll();
	}
	,	
	call_open_album:function (j) {
		var self = this;	
		this.elements[j].onmouseup = function(event) {	
			this.innerHTML='';
			this.style.backgroundImage='url(' +  wpdevart_gallery_plugin_url + '/assets/img/load7.gif)'; 
			self.album_current_index = ((self.k_a - 1) * self.count_image_per_page + j);		
			self.current_page_index = 1;
			var image_count = Math.min(self.count_image_per_page, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]] - (self.k_i - 1) * self.count_image_per_page);	
			var image_partial_count = Math.min(image_count, self.image_partial_count_0);
			var s = document.createElement('script');			
			jQuery.ajax({
				url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+((self.k_i - 1) * self.count_image_per_page)+'&limit='+image_partial_count+'&id='+self.vrl_id,
			}).done(function(date) {															
				//console.log(date);
				s.innerHTML=date;
				document.body.appendChild(s);	
				self.open_image();
				self.call_duble_resize_for_scroll();
			});
		}
	}
	,
	call_open_album1:function (j) {
		var self = this;			
		self.album_current_index = ((self.k_a - 1) * self.count_image_per_page + j);		
		self.current_page_index = 1;
		var image_count = Math.min(self.count_image_per_page, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]] - (self.k_i - 1) * self.count_image_per_page);	
		var image_partial_count = Math.min(image_count, self.image_partial_count_0);
		var s = document.createElement('script');			
		jQuery.ajax({
			url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+((self.k_i - 1) * self.count_image_per_page)+'&limit='+image_partial_count+'&id='+self.vrl_id,
		}).done(function(date) {															
			//console.log(date);
			s.innerHTML=date;
			document.body.appendChild(s);	
			self.open_image();
			self.call_duble_resize_for_scroll();
		});
	}
	,	
	open_view_mod:function () {
		switch (this.gall_current_view) {
			case 'image':
				this.current_page_index = this.k_a; this.k_i = 1;
				this.open_album();
				this.call_duble_resize_for_scroll();
				break;
			case 'album':
				this.current_page_index = this.k_g; this.k_a = 1;
				this.open_gallery();
				this.call_duble_resize_for_scroll();
				break;
			case 'gallery':
				this.current_page_index = 1;
				this.k_a = 1;
				this.k_i = 1;
				this.k_g = 1;
				//this.main_conteiner.innerHTML = '';
				jQuery(this.pagination_buttons_bar).siblings().remove();
				this.main_conteiner.style.height = '0px';
				this.gall_resizeble = 0;
				this.open_gallery_button.style.display = 'inline';
				this.go_back_button.style.display = 'none';
				this.sort_mode_conteiner.children[0].style.display = 'none';
				break;
		}
	}
	,
	open_sorted_mod:function () {
		var x = wpda_gall_sort_content_[this.sort_mode_conteiner.children[0].title];
		switch (x) {
			case 'kirpich':       
				this.tmb_class_name = 'wpda_gall_cs_kirpich';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;
			case 'masony':
				this.tmb_class_name = 'wpda_gall_cs_masony'; 
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;
			case 'mosaik':
				this.tmb_class_name = 'wpda_gall_cs_mosaik'; 
				this.tmb_class_name_(this.gall_current_view); 
				this.restart_stop_duble_resize();
				break;
			case 'tumbnails':
				this.tmb_class_name = 'wpda_gall_cs_tumbnails';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;
			case 'kirpich_':      
				this.tmb_class_name = 'wpda_gall_cs_kirpich_';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;			
			case 'masony_':
				this.tmb_class_name = 'wpda_gall_cs_masony_';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;
			case 'mosaik_':
				this.tmb_class_name = 'wpda_gall_cs_mosaik_';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;
			case 'tumbnails_':
				this.tmb_class_name = 'wpda_gall_cs_tumbnails_';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;			
			case 'column':
				this.tmb_class_name = 'wpda_gall_cs_column';
				this.tmb_class_name_(this.gall_current_view);
				this.restart_stop_duble_resize();
				break;		
		}
	}
	, 
	set_view_pagination_buttons:function () {
		var i, widt, buttons_conteiner_widt, sort_conteiner_widt;
		this.pagination_buttons_bar.style.display = 'inline';		 
		for(i = 0; i < 15; i++) {
			this.gall_pagination_buttons[i].style.display = 'inline';  
			this.gall_pagination_buttons[i].disabled = false;		
		}
		this.gall_pagination_buttons[12].disabled = true;
		this.gall_pagination_buttons[13].disabled = true;		
		if(this.y  < 11) {
			this.gall_pagination_buttons[12].style.display = 'none';
			this.gall_pagination_buttons[13].style.display = 'none';
		}
	    //define this.pagination_button_activ
		this.pagination_button_activ = 6;
		if(this.y - this.current_page_index < 6){
			this.pagination_button_activ = 10 - (this.y - this.current_page_index);
			this.gall_pagination_buttons[13].style.display = 'none';
		}
		if(this.current_page_index < 7){
			this.pagination_button_activ = this.current_page_index;
			this.gall_pagination_buttons[12].style.display = 'none';
		}

		if(this.pagination_button_activ == 10){this.gall_pagination_buttons[14].disabled = true;}
		if(this.current_page_index == this.y){this.gall_pagination_buttons[14].disabled = true;}
		if(this.pagination_button_activ == 1){this.gall_pagination_buttons[11].disabled = true;}
		for(i = 0; i < 11; i++) { 
			this.gall_pagination_buttons[i].innerHTML = this.current_page_index + i - this.pagination_button_activ + 1;	  
		}
		this.gall_pagination_buttons[this.pagination_button_activ - 1].disabled = true;
		for(i = 0; i < 11; i++) {
			if(this.gall_pagination_buttons[i].innerHTML > this.y ){
				this.gall_pagination_buttons[i].style.display = 'none';
			}
			if(this.gall_pagination_buttons[i].innerHTML < 1){
				this.gall_pagination_buttons[i].style.display = 'none';
			} 
		}
		this.gall_pagination_buttons[0].innerHTML = 1;
		this.gall_pagination_buttons[10].innerHTML = this.y;
		if(this.y <= 1 || this.wpdeva_gall_more_page == 0){
			for(i = 0; i < 15; i++) {
				this.gall_pagination_buttons[i].style.display = 'none';
			}		
		} 
        //////////////////17-04-2018
		widt = this.main_conteiner.clientWidth - 1;
		buttons_conteiner_widt = this.buttons_conteiner.clientWidth;
        sort_conteiner_widt = this.sort_mode_conteiner.clientWidth;
		if(widt < (buttons_conteiner_widt + 2 * sort_conteiner_widt + 8)){
			for(i = 1; i < 10; i++) {
				if(i != (this.pagination_button_activ - 1)){
					this.gall_pagination_buttons[i].style.display = 'none';
				}
				if(this.gall_pagination_buttons[i].innerHTML == 1){
					this.gall_pagination_buttons[0].style.display = 'inline';
				} 				
			}		
			if(this.current_page_index < this.y - 1){
				this.gall_pagination_buttons[10].style.display = 'inline';
				this.gall_pagination_buttons[13].style.display = 'inline';
			}							
			if(this.current_page_index < this.y){
				this.gall_pagination_buttons[10].style.display = 'inline';
			}		
			if(this.current_page_index > 2){
				this.gall_pagination_buttons[12].style.display = 'inline';
			}
			buttons_conteiner_widt = this.buttons_conteiner.clientWidth;
		}
		if(widt < (buttons_conteiner_widt + 2 * sort_conteiner_widt + 8)){
			for(i = 0; i < 11; i++) {
				if(i != (this.pagination_button_activ - 1)){
					this.gall_pagination_buttons[i].style.display = 'none';
				}
			}
			this.gall_pagination_buttons[13].style.display = 'none';
			this.gall_pagination_buttons[12].style.display = 'none';
		}
        ////////////////////////////////////
		
	}
	,
	set_functionality_in_pagination_buttons:function (i) { 
		var self = this, image_count, image_partial_count, s;	
		this.gall_pagination_buttons[i].onclick = function() {
			var button_self = this;	                                                        
			switch (self.gall_current_view) {
				case 'image':
					button_self.className += ' wpda_gall_anim';                          
					button_self.style.color = 'transparent';                            
					self.k_i = self.gall_pagination_buttons[i].innerHTML * 1;
					self.current_page_index = self.k_i;

					image_count = Math.min(self.count_image_per_page, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]] - (self.k_i - 1) * self.count_image_per_page);				
					image_partial_count = Math.min(image_count, self.image_partial_count_0);
					s = document.createElement('script');			
					jQuery.ajax({
						url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+((self.k_i - 1) * self.count_image_per_page)+'&limit='+image_partial_count+'&id='+self.vrl_id,
					}).done(function(date) {															
						//console.log(date);
						s.innerHTML=date;
						document.body.appendChild(s);
						jQuery(button_self).removeClass('wpda_gall_anim');                   
						button_self.style.color = '';                                       
						self.open_image();
						self.call_duble_resize_for_scroll();
					});
					break;
				case 'album':
					self.k_a = self.gall_pagination_buttons[i].innerHTML * 1;
					self.current_page_index = self.k_a;
					self.open_album();
					self.call_duble_resize_for_scroll();
					break;
				case 'gallery':
					self.k_g = self.gall_pagination_buttons[i].innerHTML * 1;
					self.current_page_index = self.k_g;
					self.open_gallery();
					self.call_duble_resize_for_scroll();			
					break;
			}		
		}
	} 
	,
	set_functionality_in_pagination_buttons11:function() {
		var self = this, image_count, image_partial_count, s;
		self.gall_pagination_buttons[11].onclick    = function() {
			var button_self = this;	                                                       
			switch (self.gall_current_view) {
				case 'image':
					button_self.className += ' wpda_gall_anim';                            
					button_self.style.color = 'transparent';                               
					self.k_i = self.k_i - 1;
					self.current_page_index = self.k_i;				
					image_count = Math.min(self.count_image_per_page, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]] - (self.k_i - 1) * self.count_image_per_page);	
					image_partial_count = Math.min(image_count, self.image_partial_count_0);
					s = document.createElement('script');			
					jQuery.ajax({
						url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+((self.k_i - 1) * self.count_image_per_page)+'&limit='+image_partial_count+'&id='+self.vrl_id,
					}).done(function(date) {															
						//console.log(date);
						s.innerHTML=date;
						document.body.appendChild(s);
						jQuery(button_self).removeClass('wpda_gall_anim');                
						button_self.style.color = '';                                    
						self.open_image();
						self.call_duble_resize_for_scroll();
					});
					break;
				case 'album':
					self.k_a = self.k_a - 1;
					self.current_page_index = self.k_a;
					self.open_album(); 
					self.call_duble_resize_for_scroll();
					break;
				case 'gallery':
					self.k_g = self.k_g - 1;
					self.current_page_index = self.k_g;
					self.open_gallery(); 
					self.call_duble_resize_for_scroll();
					break;			
			}	 
		}
	}
	,	
	set_functionality_in_pagination_buttons14:function() {
		var self = this, image_count, image_partial_count, s;
		self.gall_pagination_buttons[14].onclick = function() {
			var button_self = this;	                                                  
			switch (self.gall_current_view) {
				case 'image':
					button_self.className += ' wpda_gall_anim';                         
					button_self.style.color = 'transparent';                             
					self.k_i = self.k_i + 1;
					self.current_page_index = self.k_i;
					image_count = Math.min(self.count_image_per_page, wpdevar_gall_length_album[self.gallery_tema[self.gallery_current_index]][self.album_tema[self.gallery_current_index][self.album_current_index]] - (self.k_i - 1) * self.count_image_per_page);	
					image_partial_count = Math.min(image_count, self.image_partial_count_0);
					s = document.createElement('script');			
					jQuery.ajax({
						url: wpda_gall_admin_url_admin_ajax+'?action=wpda_gall_load_image_info&gallery_current_index='+self.gallery_tema[self.gallery_current_index]+'&album_current_index='+self.album_tema[self.gallery_current_index][self.album_current_index]+'&start='+((self.k_i - 1) * self.count_image_per_page)+'&limit='+image_partial_count+'&id='+self.vrl_id,
					}).done(function(date) {															
						//console.log(date);
						s.innerHTML=date;
						document.body.appendChild(s);
						jQuery(button_self).removeClass('wpda_gall_anim');              
						button_self.style.color = '';                                    
						self.open_image();
						self.call_duble_resize_for_scroll();
					});
					break;
				case 'album':
					self.k_a = self.k_a + 1;
					self.current_page_index = self.k_a;
					self.open_album(); 
					self.call_duble_resize_for_scroll();
					break;
				case 'gallery':
					self.k_g = self.k_g + 1;
					self.current_page_index = self.k_g;
					self.open_gallery(); 
					self.call_duble_resize_for_scroll();			
					break;
			}
		} 
	}
	,	
	hide_more_button:function () {
		if(this.wpdeva_gall_more_page == 0){	
			if(this.elmn_count >= this.elmn_elements_count) {
				this.button_for_add_images_in_main_conteiner.style.display = 'none'; 
			} else {
				this.button_for_add_images_in_main_conteiner.style.display = 'inline'; 
			}
		}
	}

}
GALLERIA_CLASS.prototype = GALLERIA_CLASS_PROTOTYPE;
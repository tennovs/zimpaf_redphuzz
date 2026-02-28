<?php
class wpdevart_gallery_theme{
	public $parametrs;
	
	public static $prefix;
	
	private $theme_requared_params;
	
	function __construct(){
		add_shortcode('wpdevart_gallery', array($this,'shortcode'));
		add_action( 'wp_ajax_wpda_gall_load_image_info', array($this,'load_image_info') ); 
		add_action( 'wp_ajax_nopriv_wpda_gall_load_image_info', array($this,'load_image_info') );		
	}
	
    /*###################### Function for loading the image info ##################*/	
	
	public function load_image_info(){
		global $wpdb;
		
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];		
		
		$obj = new stdClass();
		$obj->gallery_current_index =$_GET['gallery_current_index'];
		$obj->album_current_index =$_GET['album_current_index'];
		$obj->start =$_GET['start'];
		$obj->limit =$_GET['limit'];
		$output = '';	
		$gallery_id = $wpdb->get_var( "SELECT DISTINCT gallery FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `album` IS NULL ORDER BY order_id ASC;",0 ,$obj->gallery_current_index);	
		$album_id = $wpdb->get_var( "SELECT DISTINCT album FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallery_id' And album IS NOT NULL And image_name IS NULL ORDER BY order_id ASC;",0 ,$obj->album_current_index);	
		$nkarner = $wpdb->get_results( "SELECT ".$table_images.".image_w, ".$table_images.".image_h, ".$table_images.".url,".$table_gallery.".image_name FROM ".$table_gallery." INNER JOIN ".$table_images." ON ".$table_gallery.".img_id = ".$table_images.".id WHERE ".$table_gallery.".gallery = '$gallery_id' And ".$table_gallery.".album = '$album_id' And ".$table_gallery.".published = 1 And ".$table_gallery.".image_name IS NOT NULL ORDER BY ".$table_gallery.".order_id ASC LIMIT " . $obj->start . ", " . $obj->limit);	
		     
		for ($c = 0; $c < $obj->limit; $c++) {								
			$output .= "wpdevar_gall_img_url_h[" . $obj->gallery_current_index . "][" . $obj->album_current_index . "][" . ($obj->start + $c) . "] = " . $nkarner[$c]->image_h . ";";
			$output .= "wpdevar_gall_img_url_w[" . $obj->gallery_current_index . "][" . $obj->album_current_index . "][" . ($obj->start + $c) . "] = " . $nkarner[$c]->image_w . ";";								
			$output .= "wpdevar_gall_img_name[" . $obj->gallery_current_index . "][" . $obj->album_current_index . "][" . ($obj->start + $c) . "] = '" . $nkarner[$c]->url . "';";
			$output .= "wpdevar_gall_img_name_user[" . $obj->gallery_current_index . "][" . $obj->album_current_index . "][" . ($obj->start + $c) . "] = '" . $nkarner[$c]->image_name . "';";	
		}
		echo $output;
		exit;
	}
	
    /*###################### Function for generating the theme ##################*/		
	
	private function generete_theme_array($theme_id){
		global $wpdb;
		$theme = $wpdb->get_row( "SELECT * FROM ".wpdevart_gallery_databese::$table_names['theme']." WHERE `id` = $theme_id" );
		$this->theme_requared_params=json_decode($theme->option_value, true);
		$params_geted_admin=wpda_gall_themes::return_params_array();
		foreach($params_geted_admin as $param_heading_key=>$param_heading_value){
			foreach($param_heading_value['params'] as $key=>$value){
				if(!isset($this->theme_requared_params[$key]) || $this->theme_requared_params[$key] == ''){
					$this->theme_requared_params[$key]=$value['default_value'];
				}
			}
		}	
		//$this->theme_requared_params['count_image_per_page'] = 100000;
		
		$this->theme_requared_params['pagination_buttons_bg_color_rgba'] =  wpdevart_gallery_library::hex2rgba($this->theme_requared_params['pagination_buttons_font_bg_color'], $this->theme_requared_params['pagination_buttons_font_bg_color_opacity'] / 100);
		$this->theme_requared_params['pagination_buttons_bg_color_hover_rgba'] =  wpdevart_gallery_library::hex2rgba($this->theme_requared_params['pagination_buttons_font_bg_color_hover'], $this->theme_requared_params['pagination_buttons_font_bg_color_opacity_hover'] / 100);
		$this->theme_requared_params['pagination_buttons_font_bg_color_active_rgba'] =  wpdevart_gallery_library::hex2rgba($this->theme_requared_params['pagination_buttons_font_bg_color_active'], $this->theme_requared_params['pagination_buttons_font_bg_color_active_opacity'] / 100);
		
	}
	
	/*###################### Shortcode function ##################*/		
	
	public function shortcode($atts){

		global $wpdb;
		/*15-01-2019
		$table_images = wpdevart_gallery_databese::$table_names['images'];
		$upload_images_url = $wpdb->get_col( "SELECT url FROM ".$table_images." ;");
		$upload_images_url_length = count($upload_images_url);
		$output_urls = '';
			for ($c = 0; $c < $upload_images_url_length; $c++) {								
				$output_urls .= '<a href="' . WP_CONTENT_URL . '/wpdevart_gallery/original/' . $upload_images_url[$c] . '" target = "_blank"></a>';	
			}		
		*/		
		$gallery_id=$atts['gallery_id'];
		$album_id=$atts['album_id'];
		$theme_id=$atts['theme_id'];
		if(isset($atts['lost_ids'])){
			$lost_ids=$atts['lost_ids'];
		}else{
			$lost_ids="";
		}
		$order_type="tumbnails";
		if(isset($atts['order_type']) && ($atts['order_type']=="kirpich" || $atts['order_type']=="masony" ||$atts['order_type']=="mosaik" ||$atts['order_type']=="tumbnails" ||$atts['order_type']=="kirpich_" ||$atts['order_type']=="masony_" ||$atts['order_type']=="mosaik_" ||$atts['order_type']=="tumbnails_" || $atts['order_type']=="column")){
			$order_type=$atts['order_type'];
		}
		self::$prefix++;
		$this->generete_theme_array($theme_id);
		$wpda_gall_tema=$this->theme_requared_params;

		///////////////**************for local tema_tree_id handles************/////////////////////
		$tema_cut_tree_id= explode(",",$lost_ids);
		$full_tree_id = $wpdb->get_col( "SELECT id FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE image_name IS NULL ORDER BY id ASC ;");
		$tema_tree_id=array_values(array_diff($full_tree_id, $tema_cut_tree_id));			
		$gallerianer = $wpdb->get_col( "SELECT DISTINCT gallery FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE album IS NULL ORDER BY order_id ASC;");			      
		$gallerianer_id = $wpdb->get_col( "SELECT id FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE album IS NULL ORDER BY order_id ASC ;");
		//gallery_tema:array id for gallery from  tema_tree_id
		$gallery_tema=array_keys(array_intersect($gallerianer_id, $tema_tree_id));
		//album_tema:array id for album from  tema_tree_id 
		$album_tema=array();
		for ($x = 0; $x < count($gallery_tema); $x++) { 
			$xx=$gallery_tema[$x];
			$albumner_id = $wpdb->get_col( "SELECT id FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallerianer[$xx]' And album IS NOT NULL AND image_name IS NULL ORDER BY order_id ASC ;" );
			$album_tema[$x]=array_keys(array_intersect($albumner_id, $tema_tree_id));
		}
		
		//if(self::$prefix > 1){$output_urls = '';}//15-01-2019				
        ////////////////////////////////////////end

		return "
		<div style = 'all:initial !important;'>	
			<button type='button' id = 'wpda_gall_elm_" . self::$prefix . "_open_gallery_button' class='wpda_gall_cs_open_gallery_button'>gallery</button>
			<div id='wpda_gall_elm_" . self::$prefix . "_main_conteiner'>
				<span id='wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas' class = 'wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas'></span>
			</div>
		</div>	
		".$this->generete_css()."

		<script>
			var wp_content_url = '" . WP_CONTENT_URL . "';
			" . $this->one_time_genereted_js() . "
			var wpdevart_gallery_plugin_url = '" . wpdevart_gallery_plugin_url . "';
			document.addEventListener('DOMContentLoaded', function(){			
			var Glr_" . self::$prefix . " = new GALLERIA_CLASS();	

			// Glr_" . self::$prefix . "  object:initializacia	
			Glr_" . self::$prefix . ".vrl_id                     = " . self::$prefix . ";
			Glr_" . self::$prefix . ".scroll0 = 0;

			Glr_" . self::$prefix . ".tmb_class_name             = 'wpda_gall_cs_tumbnails';
			Glr_" . self::$prefix . ".tmb_class_name             = 'wpda_gall_cs_tumbnails';
			
			Glr_" . self::$prefix . ".image_quality                  = '" . $wpda_gall_tema['image_quality'] . "';//03-06-2017
			
			Glr_" . self::$prefix . ".scroll_top                 = " . $wpda_gall_tema['scroll_top'] . ";               //22-11-2017
			Glr_" . self::$prefix . ".image_hgt                  = " . $wpda_gall_tema['image_hgt'] . ";
			Glr_" . self::$prefix . ".image_wdt                  = " . $wpda_gall_tema['image_wdt'] . ";
			Glr_" . self::$prefix . ".album_hgt                  = " . $wpda_gall_tema['album_hgt'] . ";
			Glr_" . self::$prefix . ".album_wdt                  = " . $wpda_gall_tema['album_wdt'] . ";
			Glr_" . self::$prefix . ".gallery_hgt                = " . $wpda_gall_tema['gallery_hgt'] . ";
			Glr_" . self::$prefix . ".gallery_wdt                = " . $wpda_gall_tema['gallery_wdt'] . ";	

			Glr_" . self::$prefix . ".image_partial_count_0      = 10000;     
			Glr_" . self::$prefix . ".image_mgn                  = " . $wpda_gall_tema['image_mgn'] . ";
			Glr_" . self::$prefix . ".image_brd                  = 0;

			Glr_" . self::$prefix . ".album_partial_count_0      = 10000;     
			Glr_" . self::$prefix . ".album_mgn                  = " . $wpda_gall_tema['album_mgn'] . ";
			Glr_" . self::$prefix . ".album_brd                  = 0;

			Glr_" . self::$prefix . ".gallery_partial_count_0    = 10000;     
			Glr_" . self::$prefix . ".gallery_mgn                = " . $wpda_gall_tema['gallery_mgn'] . ";
			Glr_" . self::$prefix . ".gallery_brd                = 0;

			Glr_" . self::$prefix . ".count_of_added_elements    = 20; 
			Glr_" . self::$prefix . ".gall_max_columns_count 	 = 5;
			Glr_" . self::$prefix . ".mas_mos_tumb_order_column  = 0;
			Glr_" . self::$prefix . ".wpdeva_gall_more_page      = 1;


			Glr_" . self::$prefix . ".gall_resizeble  = 0;
			Glr_" . self::$prefix . ".count_image_per_page        = " . $wpda_gall_tema['count_image_per_page'] . "; 
			Glr_" . self::$prefix . ".current_page_index        = 1;
			Glr_" . self::$prefix . ".T_i      = 4; 
			Glr_" . self::$prefix . ".k_i      = 1;
			Glr_" . self::$prefix . ".T_a      = 3; 
			Glr_" . self::$prefix . ".k_a      = 1;
			Glr_" . self::$prefix . ".T_g      = 2; 
			Glr_" . self::$prefix . ".k_g      = 1;
			Glr_" . self::$prefix . ".pagination_buttons_bar_h     = " . $wpda_gall_tema['pagination_bar_height'] . ";

			if(Glr_" . self::$prefix . ".wpdeva_gall_more_page == 1){
				Glr_" . self::$prefix . ".image_partial_count_0 = 100000; 
				Glr_" . self::$prefix . ".album_partial_count_0 = 100000; 
				Glr_" . self::$prefix . ".gallery_partial_count_0 = 100000;
			}
			//fill  pagination_buttons_bar
		 	jQuery('#wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas').append('<div id=\'wpda_gall_elm_" . self::$prefix . "_sort_mode_conteiner\'><button type=\'button\' id = \'wpda_gall_elm_" . self::$prefix . "_go_back_button\' class=\'wpda_gall_cs_go_back_button\'><i class=\'fa\'>&#xf0e2;</i></button></div>   <button type=\'button\' id = \'wpda_gall_elm_" . self::$prefix . "_button_for_add_images_in_main_conteiner\' class =\'wpdevar_gall_buton\'></button><button type=\'button\' id = \'wpda_gall_elm_" . self::$prefix . "_button_for_lower_images_in_main_conteiner\' class =\'wpdevar_gall_buton\' style = \'display:none;\'></button> '); 	
  						

			//gallery buttons inicializacume
			Glr_" . self::$prefix . ".pagination_buttons_bar = document.getElementById('wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas');	// 17-04-2018	
			Glr_" . self::$prefix . ".sort_mode_conteiner            = document.getElementById('wpda_gall_elm_" . self::$prefix . "_sort_mode_conteiner');

			Glr_" . self::$prefix . ".main_conteiner                 = document.getElementById('wpda_gall_elm_" . self::$prefix . "_main_conteiner');						
			Glr_" . self::$prefix . ".button_for_add_images_in_main_conteiner            = document.getElementById('wpda_gall_elm_" . self::$prefix . "_button_for_add_images_in_main_conteiner');
			

			if(1 == 1){Glr_" . self::$prefix . ".button_for_add_images_in_main_conteiner.style.display = 'none';}

			Glr_" . self::$prefix . ".button_for_add_images_in_main_conteiner.onclick    = function() {
				Glr_" . self::$prefix . ".add_more_elements();
			};

			Glr_" . self::$prefix . ".button_for_lower_images_in_main_conteiner           = document.getElementById('wpda_gall_elm_" . self::$prefix . "_button_for_lower_images_in_main_conteiner');
			Glr_" . self::$prefix . ".button_for_lower_images_in_main_conteiner.innerHTML = '-' + Glr_" . self::$prefix . ".count_of_added_elements;
			Glr_" . self::$prefix . ".button_for_lower_images_in_main_conteiner.onclick   = function() {
				Glr_" . self::$prefix . ".remove_exsists_elements();
				Glr_" . self::$prefix . ".call_duble_resize_for_scroll();
			};

			Glr_" . self::$prefix . ".open_gallery_button          = document.getElementById('wpda_gall_elm_" . self::$prefix . "_open_gallery_button');
			Glr_" . self::$prefix . ".open_gallery_button.onclick  = function() { 
				Glr_" . self::$prefix . ".gall_resizeble = 1; 
				Glr_" . self::$prefix . ".open_gallery();
				Glr_" . self::$prefix . ".main_conteiner.style.height='1px';  //for IE 11
				Glr_" . self::$prefix . ".call_duble_resize_for_scroll();
				Glr_" . self::$prefix . ".open_gallery_button.style.display = 'none';	
			};

			Glr_" . self::$prefix . ".go_back_button           = document.getElementById('wpda_gall_elm_" . self::$prefix . "_go_back_button');
			Glr_" . self::$prefix . ".go_back_button.onclick   = function() {
				Glr_" . self::$prefix . ".open_view_mod();
			};




			//pagination buttons bar initials

			(function (){
				var i, buttons_conteiner;																						// 17-04-2018
				buttons_conteiner = document.createElement('div');																// 17-04-2018
				buttons_conteiner.className = 'wpda_gall_page_buttons_cont';													// 17-04-2018
				Glr_" . self::$prefix . ".pagination_buttons_bar.appendChild(buttons_conteiner);
				Glr_" . self::$prefix . ".buttons_conteiner = buttons_conteiner;	
				for(i = 0; i < 15; i++) {
					Glr_" . self::$prefix . ".gall_pagination_buttons[i] = document.createElement('BUTTON');  
					Glr_" . self::$prefix . ".gall_pagination_buttons[i].innerHTML = '' + (i + 11);
					Glr_" . self::$prefix . ".gall_pagination_buttons[i].className = 'wpdevar_gall_buton';
				}

				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[11]);
				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[0]);
				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[12]);

				for(i = 1; i < 11; i++) { 
					buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[i]);
				}
				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[13]);
				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[10]);
				buttons_conteiner.appendChild(Glr_" . self::$prefix . ".gall_pagination_buttons[14]);         					// 17-04-2018

				for(i = 0; i < 11; i++) {
					Glr_" . self::$prefix . ".gall_pagination_buttons[i].innerHTML = '' + (i + 1);
				}
				Glr_" . self::$prefix . ".gall_pagination_buttons[11].innerHTML  = '".$wpda_gall_tema['pagination_buttons_pref_view']."'; 
				Glr_" . self::$prefix . ".gall_pagination_buttons[12].innerHTML  = '...';
				Glr_" . self::$prefix . ".gall_pagination_buttons[13].innerHTML  = '...';
				Glr_" . self::$prefix . ".gall_pagination_buttons[12].disabled = true;
				Glr_" . self::$prefix . ".gall_pagination_buttons[13].disabled = true;	
				Glr_" . self::$prefix . ".gall_pagination_buttons[14].innerHTML  = '".$wpda_gall_tema['pagination_buttons_next_view']."'; 
				for(i = 0; i < 11; i++) {
					Glr_" . self::$prefix . ".set_functionality_in_pagination_buttons(i);	
				}
				Glr_" . self::$prefix . ".set_functionality_in_pagination_buttons11();
				Glr_" . self::$prefix . ".set_functionality_in_pagination_buttons14();
			})();			
			///////////////////////////   end     Glr_" . self::$prefix . "  -i inicializaciayi 
			
			
			
			//////**********************************for local tema_tree_id*****************************////////////////////////////////////
			(function (){
				var i;	
				Glr_" . self::$prefix . ".gallery_tema = ".json_encode($gallery_tema, JSON_PRETTY_PRINT).";
				Glr_" . self::$prefix . ".album_tema = ".json_encode($album_tema, JSON_PRETTY_PRINT).";
				Glr_" . self::$prefix . ".wpdevar_gall_gallery_count = Glr_" . self::$prefix . ".gallery_tema.length;
				Glr_" . self::$prefix . ".wpdevar_gall_length_gallerianer = [];
				for(var i = 0; i < Glr_" . self::$prefix . ".wpdevar_gall_gallery_count; i++ ){
					Glr_" . self::$prefix . ".wpdevar_gall_length_gallerianer[i] = Glr_" . self::$prefix . ".album_tema[i].length;
				}
			})();
            //////////////////////////////////end

			
			
			
			
			
			///////////////////////////       views gallery:ALL_, ALL, album, imaje


	                                                                                              //22-04-2017						
				if('$gallery_id' == 'all'){
					Glr_" . self::$prefix . ".open_gallery_button.onclick();
					//jQuery( Glr_" . self::$prefix . ".open_gallery_button ).trigger('click');
					jQuery(Glr_" . self::$prefix . ".go_back_button).css('display', 'none');//17-04-2017
					Glr_" . self::$prefix . ".go_back_button_show = 2;							
				}
				if('$gallery_id' == 'all_'){																		
					Glr_" . self::$prefix . ".open_gallery_button.style.display='inline';
					Glr_" . self::$prefix . ".go_back_button_show = 3;							
				}					
				if('$gallery_id' != 'all' && '$gallery_id' != 'all_' && '$album_id' == 'all'){
					Glr_" . self::$prefix . ".main_conteiner.style.height='1px';  //for IE 11
					Glr_" . self::$prefix . ".gall_resizeble = 1; 										
					Glr_" . self::$prefix . ".set_view_pagination_buttons();								
					Glr_" . self::$prefix . ".call_open_gallery1(wpda_gall_gall_id[" . $gallery_id . "]);
					jQuery(Glr_" . self::$prefix . ".go_back_button).css('display', 'none');
					Glr_" . self::$prefix . ".go_back_button_show = 1;																				
				}			
				if('$gallery_id' != 'all' && '$gallery_id' != 'all_' && '$album_id' != 'all'){
					Glr_" . self::$prefix . ".main_conteiner.style.height='1px';  //for IE 11
					Glr_" . self::$prefix . ".gall_resizeble = 1; 												
					Glr_" . self::$prefix . ".set_view_pagination_buttons();										
					Glr_" . self::$prefix . ".gallery_current_index = wpda_gall_gall_id[" . $gallery_id . "];				
					Glr_" . self::$prefix . ".call_open_album1(wpda_gall_alb_id[" . $album_id . "]);

  					
					jQuery(Glr_" . self::$prefix . ".go_back_button).css('display', 'none');
					Glr_" . self::$prefix . ".go_back_button_show = 0;
				}					

			window.addEventListener('resize', Glr_" . self::$prefix . ".restart_stop_duble_resize);
			window.addEventListener('scroll', Glr_" . self::$prefix . ".call_load_thumb_images);
			});
		</script>

		";	
		
	}
	
	/*###################### One-time generated JS function ##################*/		
	
	private function one_time_genereted_js(){
		global $wpdb;
		
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];		
		
		$output_script='';
		if(self::$prefix==1){			
			$output_script.='var wpdevar_gall_gallery_count, wpdevar_gall_length_gallerianer = [], wpdevar_gall_gallery_name = [], wpdevar_gall_length_album = [], wpdevar_gall_album_name = [], wpdevar_gall_img_name = [], wpdevar_gall_img_name_user = [],  wpdevar_gall_img_url_h = [], wpdevar_gall_img_url_w = [];';
		
			$gallerianer = $wpdb->get_col( "SELECT DISTINCT gallery FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE album IS NULL ORDER BY order_id ASC;");
			$gallery_count = count($gallerianer);
			$output_script.="(function() { \r\n";
			$output_script.="var i, j, k;\r\n";
			$output_script.="wpdevar_gall_gallery_count = " . $gallery_count . "\r\n";
			$output_script.="for(i = 0; i < wpdevar_gall_gallery_count; i++) {\r\n";
			$output_script.="wpdevar_gall_album_name[i] = [];\r\n";
			$output_script.="wpdevar_gall_length_album[i] = [];\r\n";
			$output_script.="wpdevar_gall_img_name[i] = [];\r\n";
			$output_script.="wpdevar_gall_img_name_user[i] = [];\r\n";
			$output_script.="wpdevar_gall_img_url_h[i] = [];\r\n";
			$output_script.="wpdevar_gall_img_url_w[i] = [];\r\n}\r\n";		
			
			for ($x = 0; $x < $gallery_count ; $x++) {		
				$albums = $wpdb->get_col( "SELECT DISTINCT album FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallerianer[$x]' And album IS NOT NULL And image_name IS NULL ORDER BY order_id ASC ;" );		
				$count_albums = count($albums);
				$output_script.= "wpdevar_gall_length_gallerianer[" . $x . "] = " . $count_albums . ";\r\n";
				$output_script.= "wpdevar_gall_gallery_name[" . $x . "] = '" . $gallerianer[$x] . "';\r\n";
			}	
			
			$output_script.="for(i = 0; i < wpdevar_gall_gallery_count; i++) { \r\n ";
			$output_script.="for(j = 0; j < wpdevar_gall_length_gallerianer[i]; j++) { \r\n";
			$output_script.="wpdevar_gall_img_name[i][j] = []; \r\n";
			$output_script.="wpdevar_gall_img_name_user[i][j] = []; \r\n";
			$output_script.="wpdevar_gall_img_url_h[i][j] = []; \r\n";
			$output_script.="wpdevar_gall_img_url_w[i][j] = []; \r\n"	;
			$output_script.="} \r\n }\r\n";

			$gallerianer_ = $wpdb->get_results( "SELECT DISTINCT gallery, id FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE album IS NULL ORDER BY order_id ASC ;"); 
			
			$gall_id = array(); 
			$alb_id = array(); 	
			for ($x = 0; $x < $gallery_count ; $x++) { 
				
				$gall_id[$gallerianer_[$x]->id] = $x; 			
				$albumner = $wpdb->get_col( "SELECT DISTINCT album FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallerianer[$x]' And album IS NOT NULL AND image_name IS NULL ORDER BY order_id ASC ;" );
				
				$albumner_ = $wpdb->get_results( "SELECT DISTINCT album, id FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallerianer[$x]' And album IS NOT NULL AND image_name IS NULL ORDER BY order_id ASC ;" );
	
				$y = count($albumner);
				for ($col = 0; $col < $y; $col++) {
					
						$alb_id[$albumner_[$col]->id] = $col;	
					
					$nkarner = $wpdb->get_results( "SELECT ".$table_images.".image_w, ".$table_images.".image_h, ".$table_images.".url,".$table_gallery.".image_name FROM ".$table_gallery." INNER JOIN ".$table_images." ON ".$table_gallery.".img_id = ".$table_images.".id WHERE ".$table_gallery.".gallery = '$gallerianer[$x]' And ".$table_gallery.".album = '$albumner[$col]' And ".$table_gallery.".image_name IS NOT NULL ORDER BY ".$table_gallery.".order_id ASC LIMIT 4;");
					$z = $wpdb->get_var( "SELECT COUNT(*) FROM ".$table_gallery." INNER JOIN ".$table_images." ON ".$table_gallery.".img_id = ".$table_images.".id WHERE ".$table_gallery.".gallery = '$gallerianer[$x]' And ".$table_gallery.".album = '$albumner[$col]' And ".$table_gallery.".published = 1 And ".$table_gallery.".image_name IS NOT NULL" );
					$output_script.="wpdevar_gall_length_album[" . $x . "][" . $col . "] = " . $z . ";\r\n";
					$output_script.="wpdevar_gall_album_name[" . $x . "][" . $col . "] = '" . $albumner[$col] . "';\r\n";
					for ($c = 0; $c < min($z,4); $c++) {								
						$output_script.= "wpdevar_gall_img_url_h[" . $x . "][" . $col . "][" . $c . "] = " . $nkarner[$c]->image_h . ";\r\n";
						$output_script.= "wpdevar_gall_img_url_w[" . $x . "][" . $col . "][" . $c . "] = " . $nkarner[$c]->image_w . ";\r\n";								
						$output_script.= "wpdevar_gall_img_name[" . $x . "][" . $col . "][" . $c . "] = '" . $nkarner[$c]->url . "';\r\n";
						$output_script.= "wpdevar_gall_img_name_user[" . $x . "][" . $col . "][" . $c . "] = '" . $nkarner[$c]->image_name . "';\r\n";
					}								
				}
			}	
			$output_script.= "})();";
			
			$output_script.=	"var	wpda_gall_gall_id = ".json_encode($gall_id, JSON_PRETTY_PRINT).";\r\n";
			$output_script.=	"var	wpda_gall_alb_id = ".json_encode($alb_id, JSON_PRETTY_PRINT).";\r\n";
			$output_script.=	"var	wpda_gall_admin_url_admin_ajax = '".admin_url( 'admin-ajax.php' )."';\r\n";							
		}
		return $output_script;
	}

    /*###################### Function for generating CSS ##################*/	
	
	private function generete_css(){
		$wpda_gall_tema=$this->theme_requared_params;
		$output_css="<style>
			#wpda_gall_elm_" . self::$prefix . "_main_conteiner {
				background-color: " . wpdevart_gallery_library::hex2rgba($this->theme_requared_params['pagination_bar_color'], $this->theme_requared_params['pagination_bar_color_opacity'] / 100) . ";				
				line-height: 0em !important; 
				position: relative;
				width: 100%;
				height: 0px;	
				left: 0px;
				top: 0px;
				min-width: 150px;
				box-shadow: 0px 0px " . $wpda_gall_tema['main_conteyner_box_shadow'] . "px #333333;
				overflow: hidden;	
			}


			/***********************************   title_bar    ***********************************************/
			.wpda_gall_cs_" . self::$prefix . "_elements_title_bar {			
				position: absolute;
				width: 100% !important;
				left: 0px !important;
				bottom: 0px;			
				text-align: center;
				z-index: 10;
				overflow: hidden;		
				line-height: 1em !important;
				font: 15px arial, sans-serif;
				font-size: " . $wpda_gall_tema['image_text_font_size'] . "px;	        			
			}
			.wpda_gall_css_" . self::$prefix . "_image_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar {
				font-size: 	" . $wpda_gall_tema['image_text_font_size'] . "px;
				padding: " . $wpda_gall_tema['image_text_bar_padding'] . "px 0;
				background-color: " .wpdevart_gallery_library::hex2rgba($wpda_gall_tema['image_text_bar_color'], 54 / 100). ";
			}	

			.wpda_gall_css_" . self::$prefix . "_album_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar {
				font-size: 	" . $wpda_gall_tema['album_text_font_size'] . "px;
				padding: " . $wpda_gall_tema['album_text_bar_padding'] . "px 0;
				background: rgba(255, 255, 255, 0.54);
			}

			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar {
				font-size: 	" . $wpda_gall_tema['gallery_text_font_size'] . "px;
				padding: " . $wpda_gall_tema['gallery_text_bar_padding'] . "px 0;
				color: " . $wpda_gall_tema['gallery_text_color'] . ";
				background: rgba(255, 255, 255, 0.54);
			}			
			.wpda_gall_css_" . self::$prefix . "_image_kmmtc:hover .wpda_gall_cs_" . self::$prefix . "_elements_title_bar{	
				font-size: ".$wpda_gall_tema['image_text_font_size_hover'] ."px;	
				color: " . $wpda_gall_tema['image_text_color_hover'] . ";
				
			}			
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover .wpda_gall_cs_" . self::$prefix . "_elements_title_bar {	
				font-size: ".$wpda_gall_tema['album_text_font_size_hover'] ."px;
				color: " . $wpda_gall_tema['album_text_color_hover'] . ";
			}		
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc:hover .wpda_gall_cs_" . self::$prefix . "_elements_title_bar{	
				font-size: ".$wpda_gall_tema['gallery_text_font_size_hover'] ."px;
				color: " . $wpda_gall_tema['gallery_text_color_hover'] . ";
			}
			/***********************************   title_bar1    ***********************************************/
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar1 {
				top: 0px;		
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar1 {
				top: 0px;		
			}	
			.wpda_gall_css_" . self::$prefix . "_image_kmmtc .wpda_gall_cs_" . self::$prefix . "_elements_title_bar1 {
				top: 0px;		
			}				
	
			
			/***********************************   pagination_buttons_bar_clas    ***********************************************/		
			.wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas{
				height:" . $wpda_gall_tema['pagination_bar_height'] . "px;			
				background-color:" . $wpda_gall_tema['pagination_bar_color'] . ";						
				position:absolute;
				width: 100%;
				bottom: 0px;
				left: 0px;
				z-index: 6;                                                                                      /* 09-08-2017*/				
				box-shadow: 0px 0px " . $wpda_gall_tema['main_conteyner_box_shadow'] . "px #333333;
				text-align: center;	
				
				line-height:" . $wpda_gall_tema['pagination_bar_height'] . "px;                                             
				font-size:" . 9 * $wpda_gall_tema['pagination_buttons_font_size_pracent'] * ($wpda_gall_tema['pagination_bar_height'] - 2 * $wpda_gall_tema['pagination_buttons_border_width'] - 4) / 1000 . "px !important;
				vertical-align:middle !important;				
				
				display: -webkit-flex !important;
				display: flex !important;
				-webkit-justify-content: center;
				justify-content: center;
				-webkit-align-items: center;
				align-items: center;
					
			}			
			#wpda_gall_elm_" . self::$prefix . "_sort_mode_conteiner {    /********20-03-2017******/
				position:           absolute;
				height:100%;				
				bottom:             0px;
				right:              2px;
				display:            inline-block !important;
				z-index:            1;
				font-size:inherit !important;
				
				display: -webkit-flex !important;
				display: flex !important;
				-webkit-justify-content: center;
				justify-content: center;
				-webkit-align-items: center;
				align-items: center;
			}
							
			.wpda_gall_page_buttons_cont{																												/* 17-04-2018*/
				height:" . $wpda_gall_tema['pagination_bar_height'] . "px;				
				display:            inline-block !important;
				font-size:			inherit !important;
				line-height:" . $wpda_gall_tema['pagination_bar_height'] . "px;
				
				display: -webkit-flex !important;
				display: flex !important;
				-webkit-justify-content: center;
				justify-content: center;
				-webkit-align-items: center;
				align-items: center;
			}
			/***************************************               pagination buttons              ************************************/
		
			.wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas .wpdevar_gall_buton {
				position: relative;
				padding: 2px 7px !important;
				font-size:inherit !important;
				font: 1em arial !important;
			}
			.wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas .wpdevar_gall_buton {	
				border:solid " . $wpda_gall_tema['pagination_buttons_border_color'] . " " . $wpda_gall_tema['pagination_buttons_border_width'] . "px;
				margin: 0px " . $wpda_gall_tema['pagination_buttons_distans'] . "px !important;	
				color: " . $wpda_gall_tema['pagination_buttons_font_text_color'] . ";
				background-color:" . $wpda_gall_tema['pagination_buttons_bg_color_rgba'] . ";
				border-radius: " . $wpda_gall_tema['pagination_buttons_border_radius'] . "px;
			}
			.wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas .wpdevar_gall_buton:hover {
				border:solid " . $wpda_gall_tema['pagination_buttons_border_color_hover'] . " " . $wpda_gall_tema['pagination_buttons_border_width'] . "px;
				color: " . $wpda_gall_tema['pagination_buttons_font_text_color_hover'] . ";
				background-color:" . $wpda_gall_tema['pagination_buttons_bg_color_hover_rgba'] . ";
				cursor: pointer;
				-webkit-animation: mymove 0.1s 2 alternate; 
				animation: mymove 0.1s 2 alternate;			
			}
			.wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas .wpdevar_gall_buton[disabled], .wpda_gall_css_" . self::$prefix . "_pagination_buttons_bar_clas .wpdevar_gall_buton[disabled]:hover {         
				border-color: rgba(0,0,0,0.3) !important;
				cursor: default !important;
				color:".$wpda_gall_tema['pagination_buttons_font_text_color_active']." !important;
				background-color:".$wpda_gall_tema['pagination_buttons_font_bg_color_active_rgba']." !important;
				-webkit-animation: mymove 0.0s 0 alternate; 
				animation: mymove 0.0s 0 alternate;		
			}				

			/***************************************               classes for images              ************************************/		
			.wpda_gall_css_" . self::$prefix . "_image_kmmtc {
				width: " . $wpda_gall_tema['image_wdt'] . "px;
				height: " . $wpda_gall_tema['image_hgt'] . "px;				
				margin: " . $wpda_gall_tema['image_mgn'] . "px;		
				border: none !important;	
				-moz-box-sizing: content-box !important;
				-webkit-box-sizing: content-box !important;
				box-sizing: content-box !important;	
				transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/
				-webkit-transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/
				background-clip: padding-box;		/*************************************** add 02-03-2017************************************/				
			}
			.wpda_gall_css_" . self::$prefix . "_image_kmmtc:hover{	
				transition: all 0.1s;
				-webkit-transition: all 0.1s;
				border: none !important;	
				cursor: pointer;	
				transition: all 0.1s;
				-webkit-transition: all 0.1s;
			}		


			/********************************************        classes for album             *********************************************/		
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc {
				width: " . $wpda_gall_tema['album_wdt'] . "px;
				height: " . $wpda_gall_tema['album_hgt'] . "px;
				margin: " . $wpda_gall_tema['album_mgn'] . "px !important;				
				-moz-box-sizing: content-box !important;
				-webkit-box-sizing: content-box !important;
				box-sizing: content-box !important;		
				transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/;
				-webkit-transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover {
				transition: all 0.1s;
				-webkit-transition: all 0.1s;
			}			
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:active {
				overflow:hidden;
			}		


			/*******************************       classes for gallery            **************************************/
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc {
				width: " . $wpda_gall_tema['gallery_wdt'] . "px;
				height: " . $wpda_gall_tema['gallery_hgt'] . "px;				
				margin: " . $wpda_gall_tema['gallery_mgn'] . "px !important;
				-webkit-perspective: 1000px; 
				perspective: 1000px;
				-webkit-perspective-origin: 50% -20%;
				perspective-origin: 50% -20%;    
				-moz-box-sizing: content-box !important;
				-webkit-box-sizing: content-box !important;
				box-sizing: content-box !important;
				overflow: visible !important;
				transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/
				-webkit-transition: all 0.1s, width 0s, height 0s;                                         /*28-08-2017*/
			}
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc:hover {
				-webkit-perspective: 1200px;
				perspective: 1200px;
				-webkit-perspective-origin: 140% -20%;
				perspective-origin: 140% -20%; 
			}


			/***********************************************************   for album images    *************************************************************************/
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc .wpda_gall_cs_album_images {
				position: absolute;
				width: " . $wpda_gall_tema['album_image_width'] . "%;    
				height: " . $wpda_gall_tema['album_image_width'] . "% !important;	
				left: " . ((100 - $wpda_gall_tema['album_image_width']) / 2 - 0) . "%;   
				top: " . ((100 - $wpda_gall_tema['album_image_width']) / 2 - 0) . "%;	 
				border-style: solid;
				border-color: #fefefe;
				border-width: 2px;
				border-radius: 5px;	
				background-color: rgba(220,220,220,1);	
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;
				box-shadow: 3px 0px 5px #444444;			
				/*overflow: hidden;	           06-04-2018*/	
				-ms-transform-origin: 0% 100%;    
				-webkit-transform-origin: 0% 100%;    
				transform-origin: 0% 100%;
				-moz-box-sizing: border-box !important;
				-webkit-box-sizing: border-box !important;
				box-sizing: border-box !important;	
				transition: all 0.1s; 
				-webkit-transition: all 0.1s; 
			}		

			/*     hover     */				
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover .wpda_gall_cs_rotate0 {
				-ms-transform: rotate(-10deg) scale(1.0, 1.0); 
				-webkit-transform: rotate(-10deg) scale(1.0, 1.0);
				transform: rotate(-10deg) scale(1.0, 1.0);
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover .wpda_gall_cs_rotate1 {
				-ms-transform: rotate(5deg) scale(0.8, 0.8); 
				-webkit-transform: rotate(5deg) scale(0.8, 0.8);
				transform: rotate(5deg) scale(0.8, 0.8);
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover .wpda_gall_cs_rotate2 {
				-ms-transform: rotate(20deg) scale(0.6, 0.6);
				-webkit-transform: rotate(20deg) scale(0.6, 0.6);
				transform: rotate(20deg) scale(0.6, 0.6);
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:hover .wpda_gall_cs_rotate3 {
				-ms-transform: rotate(35deg) scale(0.4, 0.4);
				-webkit-transform: rotate(35deg) scale(0.4, 0.4);
				transform: rotate(35deg) scale(0.4, 0.4);
			}

			/*      activ       */
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:active .wpda_gall_cs_" . $wpda_gall_tema['album_images_active'] . "0 {
				-ms-transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%); 
				-webkit-transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%);
				transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%);
			}					
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:active .wpda_gall_cs_" . $wpda_gall_tema['album_images_active'] . "1 {
				-ms-transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%);
				-webkit-transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%);
				transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, -" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%);
			}
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:active .wpda_gall_cs_" . $wpda_gall_tema['album_images_active'] . "2 {
				-ms-transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
				-webkit-transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
				transform: rotate(0deg) scale(0.3, 0.3) translate(" . 500 * (100 + $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
			}		
			.wpda_gall_css_" . self::$prefix . "_album_kmmtc:active .wpda_gall_cs_" . $wpda_gall_tema['album_images_active'] . "3 {

				-ms-transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
				-webkit-transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
				transform: rotate(0deg) scale(0.3, 0.3) translate(-" . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%, " . (500 * (100 - $wpda_gall_tema['album_image_width']) / (3 * $wpda_gall_tema['album_image_width']) + 100) . "%);
			}		

	

			/***************************************************        for gallery images            **********************************************************/
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc .wpda_gall_cs_gal_kazm0, .wpda_gall_css_" . self::$prefix . "_gallery_kmmtc .wpda_gall_cs_gal_kazm1 {
				position: absolute;
				width: " . $wpda_gall_tema['gallery_image_width'] . "% !important; 
				height: " . $wpda_gall_tema['gallery_image_width'] . "% !important;	
				left: " . ((100 - $wpda_gall_tema['gallery_image_width']) / 2 -  0) . "%  !important;;   
				top: " . (3 * (100 - $wpda_gall_tema['gallery_image_width']) / 8  - 0) . "% !important;
				border-radius: 0px 5px;		
				border-style: solid;
				border-color: #000000;
				border-width: 1px;
				background-color: #000000;
				box-shadow: 3px 0px 5px #444444;   
				-ms-transform-origin: 0% 0%;    
				-webkit-transform-origin: 0% 0%;    
				transform-origin: 0% 0%;
				-moz-box-sizing: border-box !important;
				-webkit-box-sizing: border-box !important;
				box-sizing: border-box !important;				       
			}
			.wpda_gall_css_" . self::$prefix . "_gallery_kmmtc .wpda_gall_cs_gallery_images {
				position: absolute;
				width: " . ($wpda_gall_tema['gallery_image_width'] + 3) . "% !important;		
				height: " . ($wpda_gall_tema['gallery_image_width'] - 2) . "% !important;			              
				left: " . ((100 - $wpda_gall_tema['gallery_image_width']) / 2 - 0) . "% !important;         
				top: " . (3 * (100 - $wpda_gall_tema['gallery_image_width']) / 8 + 1) . "% !important;	
				border-radius: 5px;   
				border-style: solid;
				border-color: #fefefe;
				border-width: 2px; 
				box-shadow: 3px 0px 5px #444444;		
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;  
				-ms-transform-origin: 0% 0%;    
				-webkit-transform-origin: 0% 0%;    
				transform-origin: 0% 0%;
				-moz-box-sizing: border-box !important;
				-webkit-box-sizing: border-box !important;
				box-sizing: border-box !important;		    		      
			}																		
		</style>";
		return $output_css;
	}
}
	?>
<?php
/* Class for popup WpDevArt*/
/* Admin popup class name ==  wpda_gall_popup_themes */
/* In wpda_gall_popup admin page class function <<return_params_array>> returned array of parameters*/

class wpdevart_gallery_popup{
	public $parametrs;
	private $prefix;
	
	function __construct(){
		$this->prefix="wpdeva_gall_";
		$this->generete_parametrs();
		add_filter('init',array($this,'localize'),999);		
		add_action('wp_head',array($this,'generete_css'));
		add_action( 'wp_ajax_wpda_gall_get_description', array($this,'get_description') ); 
		add_action( 'wp_ajax_nopriv_wpda_gall_get_description', array($this,'get_description') ); 
	}
	public function localize(){
		wp_localize_script( 'wpda_gall_popup', 'wpda_gallc', $this->parametrs );
	}
	
	/*###################### Function for getting the description ##################*/			

	public function  get_description() {
		global $wpdb;
		$table_gallery = wpdevart_gallery_databese::$table_names['gallery'];
		$table_images = wpdevart_gallery_databese::$table_names['images'];		
		
		$obj = new stdClass();
		$obj->gallery_current_index =$_GET['gallery_current_index'];
		$obj->album_current_index =$_GET['album_current_index'];
		$obj->q =$_GET['q'];
			
		$gallery_id = $wpdb->get_var( "SELECT DISTINCT gallery FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE `album` IS NULL ORDER BY order_id ASC;",0 ,$obj->gallery_current_index);	
		$album_id = $wpdb->get_var( "SELECT DISTINCT album FROM ".wpdevart_gallery_databese::$table_names['gallery']." WHERE gallery = '$gallery_id' And album IS NOT NULL And `image_name` IS NULL ORDER BY order_id ASC;",0 ,$obj->album_current_index);	
		$description = $wpdb->get_var( "SELECT ".$table_gallery.".image_description FROM ".$table_gallery." INNER JOIN ".$table_images." ON ".$table_gallery.".img_id = ".$table_images.".id WHERE ".$table_gallery.".gallery = '$gallery_id' And ".$table_gallery.".album = '$album_id' And ".$table_gallery.".published = 1 And ".$table_gallery.".image_name IS NOT NULL ORDER BY ".$table_gallery.".order_id ASC;",0 ,$obj->q);					 	
		if($description){
			echo $description;
		}else{
			echo "<h1>Description is empty</h1>";
		}
		exit;
	}
	
    /*###################### Function for generating parameters ##################*/
	
	public function generete_parametrs(){
		global $wpdb;
		$table_popup_theme = wpdevart_gallery_databese::$table_names['popup_theme'];		
		$default_theme = $wpdb->get_var( "SELECT option_value FROM ".wpdevart_gallery_databese::$table_names['popup_theme']." WHERE `default`=1 ;");
		$array_default_theme=json_decode($default_theme,true);			
		$array_of_popup_params=wpda_gall_popup_themes::return_params_array();		
		foreach($array_of_popup_params as $main_gorup){
			foreach($main_gorup['params'] as $key=>$value){				
				if(!isset($array_default_theme[$key]) || $array_default_theme[$key] == ''){
					$array_default_theme[$key]=$value['default_value'];
				}								
				$new_key=(str_replace($this->prefix,"",$key));
				$this->parametrs[$new_key]=$array_default_theme[$key];
			}
		}			
			

		$this->parametrs['icons_outBar_distance_from_html'] = min($this->parametrs['icons_outBar_distance_from_html'], $this->parametrs['popup_base_cornice']); 
		
		$this->parametrs['image_description_bg_color_rgba'] = wpdevart_gallery_library::hex2rgba($this->parametrs['image_description_bg_color'], $this->parametrs['image_description_bg_color_opacity'] / 100);
		$this->parametrs['image_description_bg_color_hover_rgba'] = wpdevart_gallery_library::hex2rgba($this->parametrs['image_description_bg_color_hover'], $this->parametrs['image_description_bg_color_opacity_hover'] / 100);
		
		$this->parametrs['overlay_bg_color_rgba'] =  wpdevart_gallery_library::hex2rgba($this->parametrs['overlay_bg_color'], $this->parametrs['overlay_opacity'] / 100);
		$this->parametrs['popup_border_rgba'] = 'solid ' . wpdevart_gallery_library::hex2rgba($this->parametrs['popup_brd_color'], $this->parametrs['popup_brd_opasity'] / 100) . ' ' . $this->parametrs['popup_brd_width'] . 'px';
		
		if($this->parametrs['thumbanils_line_visibility']=='hidden'){$this->parametrs['progress_bar_top'] = -4;};	
					
		$this->parametrs['small_icon']=($this->parametrs['full_icon']=='material-icons')?'material-icons':'fa fa-expand';
		$this->parametrs['small_icon']=($this->parametrs['full_icon']=='wpdeva_gall_display_none')?"wpdeva_gall_display_none":$this->parametrs['small_icon'];		

		$this->parametrs['icons_inBar_yes_no_'] = substr($this->parametrs['icons_inBar_yes_no'], 1, 1);
		$this->parametrs['icons_inBar_yes_no']  = substr($this->parametrs['icons_inBar_yes_no'], 0, 1);		
		$this->parametrs['transition_duration']=($this->parametrs['slide_show_effect']==2)?0:$this->parametrs['transition_duration'];
		 
		$this->parametrs['rgn_popup_url']=wpdevart_gallery_plugin_url;
		$this->parametrs['admin_ajax_url']=admin_url( 'admin-ajax.php' );		

		/*indefinable parametres*/
		$this->parametrs['icons_blur_width']='';
		$this->parametrs['icons_blur_color']='';
		$this->parametrs['icons_scale_anim']='';
	}

	/*###################### Function for generating CSS ##################*/
		
	public function generete_css(){
		?>
		<style id='wpda_gall_popup_style'>
			#wpda_gall_overlay{                
				background-color:   <?php echo $this->parametrs['overlay_bg_color_rgba']; ?>;
			}		
			.wpda_gall_popup_parent {                      
				-ms-transform:      rotateY(<?php echo $this->parametrs['popup_start_rotate']; ?>deg);
				-webkit-transform:  rotateY(<?php echo $this->parametrs['popup_start_rotate']; ?>deg);
				transform:          rotateY(<?php echo $this->parametrs['popup_start_rotate']; ?>deg);
				position:           <?php echo $this->parametrs['popup_position']; ?>;	
			}
			.wpda_gall_popup {	
				background-color:   <?php echo $this->parametrs['popup_bg_color']; ?>;
				position:           absolute; 		      
				border-radius:      <?php echo $this->parametrs['popup_brd_radius']; echo $this->parametrs['pixel']; ?>;
				border:             <?php echo $this->parametrs['popup_border_rgba']; ?>;  
			}
			.wpda_gall_transition {
			}
			.wpda_gall_popup_description {	
				background-color:   <?php echo $this->parametrs['image_description_bg_color_rgba']; ?> !important;
				color:              <?php echo $this->parametrs['image_description_text_color']; ?>;
				font-size: 			<?php echo $this->parametrs['image_description_font_size']; ?>px;
				
			}
			.wpda_gall_description_margin {
				margin-top: 		<?php echo $this->parametrs['image_description_distacne_top']; ?>px;
				margin-left: 		<?php echo $this->parametrs['image_description_distacne_left_right']; ?>px;
				margin-right: 		<?php echo $this->parametrs['image_description_distacne_left_right']; ?>px;
			}
			.wpda_gall_popup_description:hover {
				background-color:   <?php echo $this->parametrs['image_description_bg_color_hover_rgba']; ?> !important;
				color:              <?php echo $this->parametrs['image_description_text_color_hover']; ?>;		
			}
			.wpda_gall_popup_img0 {
			} 

			.wpda_gall_popup_icon_left_bar {
				color:              <?php echo "#0085ba;" ?>;
				font-size:          <?php echo $this->parametrs['popup_right_icon_font_size']; ?>px;
			}

			.wpda_gall_popup_icon_left {               
				color:              <?php echo $this->parametrs['popup_icons_color']; ?>;
				box-shadow:         0px 0px <?php echo $this->parametrs['icons_blur_width']; ?>px <?php echo $this->parametrs['icons_blur_color']; ?>;	
				left:               <?php echo $this->parametrs['popup_icon_distance']; ?>px;
				margin-top:                -<?php echo $this->parametrs['popup_right_icon_font_size'] / 2; ?>px;
			}
			.wpda_gall_popup_icon_right_bar {       
				color:              <?php echo "#0085ba;" ?>;
				font-size:          <?php echo $this->parametrs['popup_right_icon_font_size']; ?>px;
			}

  
			.wpda_gall_popup_icon_right {              
				color:              <?php echo $this->parametrs['popup_icons_color']; ?>;
				box-shadow:         0px 0px <?php echo $this->parametrs['icons_blur_width']; ?>px <?php echo $this->parametrs['icons_blur_color']; ?>;	
				right:              <?php echo $this->parametrs['popup_icon_distance']; ?>px;
				margin-top:                -<?php echo $this->parametrs['popup_right_icon_font_size'] / 2; ?>px;
			}
			.wpda_gall_popup_icon_left:active, .wpda_gall_popup_icon_right:active {
				-webkit-animation: mymove1 0.1s <?php echo $this->parametrs['icons_scale_anim']; ?> alternate; 
				animation: mymove1 0.1s <?php echo $this->parametrs['icons_scale_anim']; ?> alternate;		
			}     	   	   
			.wpda_gall_left_bar_icons { 
				<?php echo "background-color:#ffffff;" ?>     
				height:<?php echo $this->parametrs['icons_inBar_height']; ?>px;                    
				<?php echo "border-top:" ?> 
				<?php echo $this->parametrs['progress_bar_top'] + 4; ?>px solid <?php echo "#ffffff"; ?>;
				/*30-09-2017*/
				display: -webkit-flex !important;
				display: flex !important;
				<?php if($this->parametrs['outBar_icons_center'] == 0) {echo "-webkit-justify-content: center; justify-content: center;";} ?>
				-webkit-align-items: center;
				align-items: center;				
			}
			<?php 
				if($this->parametrs['popup_right_icon'] == 'wpdeva_gall_display_none') {
					echo
					".wpda_gall_popup_icon_right_bar, .wpda_gall_popup_icon_left_bar {  
						display: none !important;
					 }";				
				} 	
						
				echo
				".wpda_gall_left_bar_icons:hover { 
					opacity: 1;
				 }";					
				
			?>
			.wpda_gall_right_bar_icons {      
				<?php echo "background-color:" . $this->parametrs['popup_bg_color'] . ";" ?>    						
				padding-right:      <?php echo $this->parametrs['icons_distance']; ?>px;
				/*30-09-2017*/
				display: -webkit-flex !important;
				display: flex !important;
				-webkit-justify-content: center;
				justify-content: center;
				-webkit-align-items: center;
				align-items: center;				
			}
			.wpda_gall_number_image { 
				color:              <?php echo "#0085ba;" ?>;	                            
			}
			.wpda_gall_class_icon {
				color:              <?php echo "#0085ba;" ?>;  				
			}
			.wpda_gall_class_icon:hover {          
				color:              <?php echo "#006799;" ?>;
			}
			.wpda_gall_class_icon:active {          
				-webkit-animation: mymove1 0.1s <?php echo $this->parametrs['icons_scale_anim']; ?> alternate; 
				animation: mymove1 0.1s <?php echo $this->parametrs['icons_scale_anim']; ?> alternate;	
			}
			.wpda_gall_progress_bar_base {   
				left:               <?php echo (100 - $this->parametrs['progress_bar_width']) / 2; ?>%;	
				width:              <?php echo $this->parametrs['progress_bar_width']; ?>%;				
				display:        none; 
			}
			.wpda_gall_progress_bar_buttons_ {             
				background-color:   <?php echo $this->parametrs['progress_bar_bg_color']; ?>; 								
			}			  
			.wpda_gall_scrubber {                      
				background-color:   <?php echo $this->parametrs['scrubber_bg_color']; ?>;
			}	   
			.wpda_gall_progress_bar_screen {      
				width:              <?php echo $this->parametrs['progress_bar_screen_width']; ?>px; 
				top:                <?php echo ( -$this->parametrs['progress_bar_screen_height'] - 2 * $this->parametrs['progress_bar_screen_brd_width'] - 5 - $this->parametrs['progress_bar_screen_bottom'] ); ?>px;
				height:             <?php echo $this->parametrs['progress_bar_screen_height']; ?>px;
				border:             <?php echo "solid " . $this->parametrs['progress_bar_screen_brd_color'] . " " . $this->parametrs['progress_bar_screen_brd_width']; ?>px;
				background-color:   <?php echo $this->parametrs['progress_bar_screen_bg_color']; ?>;
				opacity:            <?php echo $this->parametrs['progress_bar_screen_opacity'] / 100; ?>;
			}
			.wpda_gall_progress_bar_count_screen  {
				left:               <?php echo ( $this->parametrs['progress_bar_screen_width'] - $this->parametrs['progress_bar_count_screen_width'] ) / 2; ?>px;
				width:              <?php echo $this->parametrs['progress_bar_count_screen_width']; ?>px;			  
				height:             <?php echo $this->parametrs['progress_bar_count_screen_height']; ?>px;
				background-color:   <?php echo $this->parametrs['progress_bar_count_screen_bg_color']; ?>;
				opacity:            <?php echo $this->parametrs['progress_bar_count_screen_opacity'] / 100; ?>;
				color:              <?php echo $this->parametrs['progress_bar_count_screen_color']; ?>;
			}
			.wpda_gall_icons_outBar {               
				position:           <?php echo $this->parametrs['popup_position']; ?>;
			} 
			.wpda_gall_icons_outBar_box_shadow {
				border-radius:      <?php echo $this->parametrs['icons_outBar_brd_radius']; ?>px;
				background-color:   <?php echo $this->parametrs['icons_outBar_bg_color']; ?>;
				opacity:            <?php echo $this->parametrs['icons_outBar_bg_opasaty'] / 100; ?>;				
			}
			@-webkit-keyframes mymove2 {    
				to {background: <?php echo $this->parametrs['progress_bar_button_bg_color_hover']; ?>;}
			}
			@keyframes mymove2 {    
				to {background: <?php echo $this->parametrs['progress_bar_button_bg_color_hover']; ?>;}
			}
						
			.wpda_gall_parent_icon {
				<?php if($this->parametrs['outBar_icons_center'] != 0) {echo "float:left;";} ?>
				line-height: <?php echo $this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4; ?>px;
				<?php echo "margin-left:" . $this->parametrs['icons_distance'] . "px;";?>	
			}
			.wpda_gall_right_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['right_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;	
			}
			.wpda_gall_play_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['play_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
			}
			.wpda_gall_count_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['count_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
			}
			.wpda_gall_load_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['load_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
				<?php if($this->parametrs['load_icon'] == "wpdeva_gall_display_none") {echo "display:none !important;";} ?>	
			}			
			.wpda_gall_full_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['full_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
			}
			.wpda_gall_setting_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['setting_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
			}
			.wpda_gall_close_icon_font_size {
				font-size:<?php echo 0.9 * $this->parametrs['close_icon_relative_font_size'] * ($this->parametrs['icons_inBar_height'] - $this->parametrs['progress_bar_top'] - 4) / 100; ?>px;
			}					  			                                                                                                        
		</style> 
		<?php
	}
}
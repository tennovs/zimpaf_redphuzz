<?php
//installing the database
class wpdevart_gallery_databese{
	public static $table_names;
	public static $popup_settings;

//16-05-2017

	public function install_gallery_table(){
		global $wpdb,$wpdevart_gallery;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		//install gallery image database
		$table_name =  self::$table_names['gallery'];
		$table_images =  self::$table_names['images'];
		$table_img_tags =  self::$table_names['img_tags'];
		$table_img_comment =  self::$table_names['img_comment'];	
		$charset_collate = $wpdb->get_charset_collate();
		
		$row_table = $wpdb->get_results(  "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".$wpdb->dbname."' AND TABLE_NAME = '".$table_name."';"  );		
		
		//img
		if(empty($row_table)){
			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				`id` bigint(20)	 NOT NULL AUTO_INCREMENT,		
				`time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				`gallery` text,
				`album` text DEFAULT '',
				`image_description` text DEFAULT '',
				`image_name` text DEFAULT '',
				`order_id` bigint(20)  NOT NULL DEFAULT 1,
				`img_id` bigint(20)	 NOT NULL DEFAULT 0,
				`published` tinyint(1) DEFAULT 1,				
				UNIQUE KEY id (id)		
			) $charset_collate;";		
			dbDelta( $sql );
			
			$sql1 = "CREATE TABLE IF NOT EXISTS $table_images (
				`id` bigint(20)	 NOT NULL AUTO_INCREMENT,		
				`image_h` text DEFAULT '',
				`image_w` text DEFAULT '',
				`image_size` text DEFAULT '',
				`image_type` text DEFAULT '',				
				`url` varchar(4048) DEFAULT '',				
				UNIQUE KEY id (id)		
			) $charset_collate;";		
			dbDelta( $sql1 );
					
		}else{
			$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".$wpdb->dbname."' AND TABLE_NAME = '".$table_name."' AND COLUMN_NAME = 'order_id'"  );			
			if(empty($row)){
			   $wpdb->query("ALTER TABLE $table_name ADD order_id bigint(20) NOT NULL DEFAULT 1");
			   $wpdb->query("UPDATE $table_name SET order_id = id;");
			}
			$row_img = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".$wpdb->dbname."' AND TABLE_NAME = '".$table_name."' AND COLUMN_NAME = 'img_id'"  );			
			if(empty($row_img)){
			   $wpdb->query("ALTER TABLE $table_name ADD img_id bigint(20) NOT NULL DEFAULT 0");
			   $wpdb->query("UPDATE $table_name SET img_id = id;");
			   $wpdb->query("ALTER TABLE $table_name ADD published tinyint(1) NOT NULL DEFAULT 1");			   		   
			}			
						

			$row_table_images = $wpdb->get_results(  "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".$wpdb->dbname."' AND TABLE_NAME = '".$table_images."'"  );
			if(empty($row_table_images)){
				$sql1 = "CREATE TABLE IF NOT EXISTS $table_images (
					`id` bigint(20)	 NOT NULL AUTO_INCREMENT,		
					`image_h` text DEFAULT '',
					`image_w` text DEFAULT '',
					`image_size` text DEFAULT '',
					`image_type` text DEFAULT '',				
					`url` varchar(4048) DEFAULT '',				
					UNIQUE KEY id (id)		
				) $charset_collate;";		
				dbDelta( $sql1 );				
				$sql_ = "INSERT INTO $table_images SELECT id, image_h,image_w,image_size,image_type,url FROM $table_name WHERE image_type IS NOT NULL ORDER BY id ASC;";	
				dbDelta( $sql_ );	
				$wpdb->query("ALTER TABLE $table_name DROP image_h, DROP image_w, DROP image_size, DROP image_type, DROP url");										
			}	
		}	
	}

	/*###################### Install the theme table function ##################*/		
	
	public function install_theme_tabel(){
		global $wpdb;
		//install gallery databese
		$table_name =  self::$table_names['theme'];	
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		  `name` varchar(512) NOT NULL,
		  `option_value` longtext NOT NULL,
		  `default` tinyint(4) NOT NULL,
			UNIQUE KEY id (id)		
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );	
		$this->insert_to_theme_default_values();
	}
	
	/*###################### Install the Popup Theme Table - function ##################*/	
			
	
	public static function install_popup_theme_tabel(){
		global $wpdb;
		//install gallery database
		$table_name =  self::$table_names['popup_theme'];
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			  `name` varchar(512) NOT NULL,
			  `option_value` longtext NOT NULL,
			  `default` tinyint(4) NOT NULL,
				UNIQUE KEY id (id)		
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );	
			self::insert_to_popup_theme_default_values();
		}
	}
	
	/*###################### Popup Theme function ##################*/		
	
	public static function insert_to_popup_theme_default_values(){
		global $wpdb;
		$array_of_popup_params=wpda_gall_popup_themes::return_params_array();		
		foreach($array_of_popup_params as $main_gorup){
			foreach($main_gorup['params'] as $key=>$value){			
				$parametrs[$key]=get_option($key,$value['default_value']);
			}
		}
		$parametrs['name']="Default theme";
		$isset_theme=$wpdb->get_var("SELECT `id` FROM ".self::$table_names['popup_theme'] ." WHERE id=1");
		if(!$isset_theme){
			$wpdb->insert( self::$table_names['popup_theme'], 
				array( 
					'id' => 1,
					'name' => "Default Theme",
					'option_value' =>json_encode($parametrs),
					'default' => 1,

				), 
				array( 
					'%d',
					'%s', 
					'%s',
					'%d',
				) 
			);
		}
	}
	
	/*###################### Theme default values function ##################*/	
	
	private function insert_to_theme_default_values(){
		global $wpdb;
		$install_themes='{"name":"Theme 1","count_image_per_page":"20","tmb_class_name":"kirpich","mas_mos_tumb_order_column":"1","mas_mos_tumb_number_column":"4","count_of_added_elements":"20","pagination_bar_height":"20","pagination_bar_color":"#ffffff","pagination_bar_color_opacity":"36","more_page":"1","pagination_buttons_font_size":"14","pagination_buttons_font_bg_color":"#0a0a0a","pagination_buttons_font_bg_color_active":"#757575","pagination_buttons_font_bg_color_hover":"#4f4f4f","pagination_buttons_font_text_color":"#ffffff","pagination_buttons_font_text_color_active":"#ffffff","pagination_buttons_font_text_color_hover":"#ffffff","select_hide":"0","hover_image_or_text_bar":":hover","speed_hover":"500","image_wdt":"200","image_hgt":"180","image_mgn":"5","image_scale":"100","image_rotate":"0","image_opacity":"80","image_opacity_hover":"100","image_brd":"1","image_brd_color":"#1e73be","image_brd_opacity":"20","image_brd_color_hover":"#1e73be","image_brd_opacity_hover":"100","image_brd_rad":"3","image_brd_rad_hover":"3","image_shadov_color":"#32c1ff","image_shadov_color_hover":"#337ebf","image_shadov_h":"2","image_shadov_h_hover":"1","image_shadov_v":"2","image_shadov_v_hover":"1","image_shadov_blur":"2","image_shadov_blur_hover":"1","image_shadov_spread":"2","image_shadov_spread_hover":"1","image_shadov_opacity":"70","image_shadov_opacity_hover":"100","image_shadov_out_inset":" ","image_shadov_out_inset_hover":" ","image_text_bar_padding":"2","image_text_font_size":"12","image_text_font_size_hover":"12","image_text_color":"#000000","image_text_color_hover":"#000000","image_text_opacity":"20","image_text_opacity_hover":"100","image_text_shadov_color":"#ffffff","image_text_shadov_color_hover":"#ffffff","image_text_shadov_blur":"0","image_text_shadov_blur_hover":"0","image_text_shadov_opacity":"100","image_text_shadov_opacity_hover":"100","image_text_bar_color":"#ffffff","image_text_bar_color_hover":"#ffffff","image_text_bar_color_opacity":"100","image_text_bar_color_opacity_hover":"100","image_partial_count0":"2","album_wdt":"200","album_hgt":"180","album_mgn":"25","album_image_width":"71","album_opacity":"80","album_opacity_hover":"100","album_bg_color":"#ffffff","album_bg_color_hover":"#f7f7f7","album_bg_color_opacity":"80","album_bg_color_opacity_hover":"100","album_brd":"1","album_brd_color":"#1e73be","album_brd_color_hover":"#1e73be","album_brd_opacity":"22","album_brd_opacity_hover":"100","album_brd_rad":"3","album_brd_rad_hover":"3","album_shadov_color":"#1e73be","album_shadov_color_hover":"#1e73be","album_shadov_h":"3","album_shadov_h_hover":"2","album_shadov_v":"3","album_shadov_v_hover":"2","album_shadov_blur":"3","album_shadov_blur_hover":"2","album_shadov_spread":"3","album_shadov_spread_hover":"2","album_shadov_opacity":"47","album_shadov_opacity_hover":"78","album_shadov_out_inset":" ","album_shadov_out_inset_hover":" ","album_text_bar_padding":"5","album_text_font_size":"12","album_text_font_size_hover":"14","album_text_color":"#0a0a0a","album_text_color_hover":"#0a0a0a","album_text_opacity":"100","album_text_opacity_hover":"100","album_text_shadov_color":"#1e73be","album_text_shadov_color_hover":"#1e73be","album_text_shadov_blur":"3","album_text_shadov_blur_hover":"3","album_text_shadov_opacity":"49","album_text_shadov_opacity_hover":"52","album_partial_count0":"3","gallery_wdt":"200","gallery_hgt":"180","gallery_mgn":"25","gallery_image_width":"71","gallery_opacity":"80","gallery_opacity_hover":"100","gallery_bg_color":"#ffffff","gallery_folder_bg_color":"#2133f2","gallery_bg_color_hover":"#fcfcfc","gallery_bg_color_opacity":"100","gallery_bg_color_opacity_hover":"100","gallery_brd":"2","gallery_brd_color":"#1e73be","gallery_brd_color_hover":"#1e73be","gallery_brd_opacity":"21","gallery_brd_opacity_hover":"100","gallery_brd_rad":"3","gallery_brd_rad_hover":"3","gallery_shadov_color":"#1e73be","gallery_shadov_color_hover":"#1e73be","gallery_shadov_h":"3","gallery_shadov_h_hover":"2","gallery_shadov_v":"3","gallery_shadov_v_hover":"2","gallery_shadov_blur":"3","gallery_shadov_blur_hover":"2","gallery_shadov_spread":"3","gallery_shadov_spread_hover":"2","gallery_shadov_opacity":"48","gallery_shadov_opacity_hover":"84","gallery_shadov_out_inset":" ","gallery_shadov_out_inset_hover":" ","gallery_text_bar_padding":"5","gallery_text_font_size":"12","gallery_text_font_size_hover":"14","gallery_text_color":"#0a0a0a","gallery_text_color_hover":"#0a0a0a","gallery_text_opacity":"68","gallery_text_opacity_hover":"100","gallery_text_shadov_color":"#1e73be","gallery_text_shadov_color_hover":"#1e73be","gallery_text_shadov_blur":"3","gallery_text_shadov_blur_hover":"3","gallery_text_shadov_opacity":"50","gallery_text_shadov_opacity_hover":"50","gallery_partial_count0":"3"}';
		$isset_theme=$wpdb->get_var("SELECT `id` FROM ".self::$table_names['theme'] ." WHERE id=1");
		if(!$isset_theme){
			$wpdb->insert( self::$table_names['theme'], 
				array( 
					'id' => 1,
					'name' => "Blue Theme",
					'option_value' => $install_themes,
					'default' => 1,

				), 
				array( 
					'%d',
					'%s', 
					'%s',
					'%d',
				) 
			);
		}
	}	
} 
global $wpdb;
wpdevart_gallery_databese::$table_names=array(
	'img_tags'=>$wpdb->prefix.'wpdevart_img_tags',
	'gallery'=>$wpdb->prefix.'wpdevart_gallery',
	'images'=>$wpdb->prefix.'wpdevart_images',
	'img_comment'=>$wpdb->prefix.'wpdevart_img_comment',
	'theme'=>$wpdb->prefix.'wpdevart_gallery_theme',
	'popup_theme'=>$wpdb->prefix.'wpdevart_gallery_popup_theme'
);
?>
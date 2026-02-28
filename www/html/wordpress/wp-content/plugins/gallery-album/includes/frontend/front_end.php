<?php	 
class wpdevar_gallery_frontend{
// previous defined admin constants
// wpdevart_gallery_plugin_url
// wpdevart_gallery_plugin_path
	private static $unique_prefix=0;
	function __construct(){
		$this->admin_filters();
		$this->include_files();
	}
	
	/*###################### Function for including the files ##################*/			
	
	private function include_files(){
		// include wpdevart_gallery_popup_class
		require_once(wpdevart_gallery_plugin_path.'includes/frontend/popup_class.php');
		$wpda_gall_popup=new wpdevart_gallery_popup();
		require_once(wpdevart_gallery_plugin_path.'includes/frontend/gallery_class.php');
		$wpda_gall_popup=new wpdevart_gallery_theme();
		
	}
	
	/*###################### Function for the admin filters ##################*/	
	
	private function admin_filters(){
		add_filter('wp_head',array($this,'include_scripts'),0);		
	}
	
	/*###################### Function for including the scripts ##################*/	
	
	public function include_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpda_gall_gallery_class_prototype');
		wp_enqueue_script('wpda_gall_popup');
		wp_enqueue_style('wpda_gallery_style');
		wp_enqueue_style('FontAwesome');
		wp_enqueue_style('metrical_icons','https://fonts.googleapis.com/icon?family=Material+Icons');
	}
}
?>
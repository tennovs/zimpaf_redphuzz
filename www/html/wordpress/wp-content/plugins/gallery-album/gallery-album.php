<?php
/*
* Plugin Name: Gallery - Wpdevart Gallery
* Plugin URI: https://wpdevart.com/wordpress-gallery-plugin/
* Author URI: https://wpdevart.com
* Description: Gallery - WpDevArt Gallery plugin is an useful tool that will help you to create Galleries and Albums. There are a lot of nice Gallery views and useful options that you can use.
* Version: 1.9.9
* Author: wpdevart
* License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

class wpdevart_gallery{
	
	private $version;
	
	private $databese;	
	
	public $options;
	
	public $admin_menu;	
	
    /*###################### Construct function ##################*/	
		
	function __construct(){		
		$this->version     = 1.0;
		// define constatnt. all files in wpdevart use this constants
		$this->define_constants();
		// include files 
		$this->include_files();
		// call filters for plugin
		$this->call_base_filters();
		// crate admin panel	
		$this->databese = new wpdevart_gallery_databese();
		$this->update_tables_databese();
		$this->create_admin();
		$this->front_end();
		
		
	}	
	
	/*###################### Create admin function ##################*/	
		
	private function create_admin(){
		// create admin menu		
		$this->admin_menu = new wpdevar_gallery_admin_panel();		
	}
	
	/*###################### Front end function ##################*/		
	
	public function front_end(){
		// create front end	
		$wpda_gall_fornt_end = new wpdevar_gallery_frontend();	
	}
	
	public function registr_requeried_scripts(){	
		/*registr scripts*/	
		wp_register_style('FontAwesome',wpdevart_gallery_plugin_url.'includes/admin/css/font-awesome.min.css');
		/*front end scripts styles*/
		wp_register_style('wpda_gallery_style',wpdevart_gallery_plugin_url.'includes/frontend/css/front_end.css');
		wp_register_script('wpda_gall_gallery_class_prototype',wpdevart_gallery_plugin_url.'includes/frontend/js/GALLERIA_CLASS_PROTOTYPE.js');
		wp_register_script('wpda_gall_popup',wpdevart_gallery_plugin_url.'includes/frontend/js/popup.js',array(),'1.0',true);
		
	}

	/*###################### Call filters function ##################*/	
		
	private function call_base_filters(){
		/* some admin hooks*/
		
		register_activation_hook( __FILE__, array($this,'install_databese') );
		add_action('init',  array($this,'registr_requeried_scripts') );
		//for_upgrade
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'plugin_activate_sublink') );
	}
	public function plugin_activate_sublink($links){
		$plugin_submenu_added_link=array();		
		 $added_link = array(
		 '<a target="_blank" style="color: #7052fb; font-weight: bold; font-size: 13px;" href="https://wpdevart.com/wordpress-gallery-plugin/">Upgrade to Pro</a>',
		 );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $added_link );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $links );
		return $plugin_submenu_added_link;
	}
  	private function define_constants(){
		 define('wpdevart_gallery_plugin_url',trailingslashit( plugins_url('', __FILE__ ) ));
		 define('wpdevart_gallery_plugin_path',trailingslashit( plugin_dir_path( __FILE__ ) ));
		 define('wpdevart_gallery_support_url',"https://wordpress.org/support/plugin/gallery-album");
		 define('wpdevart_gallery_start_mem',ini_get('memory_limit'));
	}

	/*###################### Include files function ##################*/		
	
	private function include_files(){
		require_once(wpdevart_gallery_plugin_path.'includes/install_databese.php');
		require_once(wpdevart_gallery_plugin_path.'includes/admin/popup_settings.php'); // for geting popup parametrs		
		require_once(wpdevart_gallery_plugin_path.'includes/admin/gallery_theme.php'); // for geting popup parametrs		
		require_once(wpdevart_gallery_plugin_path.'includes/wpdevart_library.php'); 
		require_once(wpdevart_gallery_plugin_path.'includes/admin/admin.php'); 	
		require_once(wpdevart_gallery_plugin_path.'includes/frontend/front_end.php');
	}	
	
	/*###################### Database function ##################*/		
	
	public function install_databese(){
		// new class for installing databese
		$this->databese->install_gallery_table();
		$this->databese->install_theme_tabel();
		$this->databese->install_popup_theme_tabel();
	}
	
	/*###################### Database tables function ##################*/	
	
	private function update_tables_databese(){
		wpdevart_gallery_databese::install_popup_theme_tabel();
	}
}
$wpdevart_gallery = new wpdevart_gallery();
?>
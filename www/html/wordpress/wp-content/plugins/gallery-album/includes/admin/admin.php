<?php

class wpdevar_gallery_admin_panel{
// previus defined admin constants
// wpdevart_gallery_plugin_url
// wpdevart_gallery_plugin_path
	private $text_fileds;
	function __construct(){
		$this->include_requared_files();
		$this->admin_filters();
	}
	
	/*###################### Admin filters function ##################*/			

	private function admin_filters(){
		//hook for admin menu
		add_action( 'admin_menu', array($this,'create_admin_menu') );
		/* for post page button*/
		add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
		add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
		add_action('wp_ajax_wpdevart_gallery_post_page_content', array($this,"post_page_popup_content"));
	}
	//Connect admin menu
	public function create_admin_menu(){
		global $submenu;
		/* conect admin pages to wordpress core*/
		$main_page=add_menu_page( "Wpdevart Gallery", "Wpdevart Gallery", 'manage_options', "Wpdevart_gallery_menu", array($this, 'create_gallery_page'),'dashicons-camera');
		add_submenu_page( "Wpdevart_gallery_menu", "Gallery", "Gallery", 'manage_options',"Wpdevart_gallery_menu",array($this, 'create_gallery_page'));
		$popup_page=$theme_subpage_popup=add_submenu_page( "Wpdevart_gallery_menu", "Popup", "Popup", 'manage_options',"wpdevart_gallery_popup",array($this, 'popup_settings_page'));
		$gallery_theme=add_submenu_page( "Wpdevart_gallery_menu", "Themes", "Themes", 'manage_options',"wpdevart_gallery_themes",array($this, 'gallery_themes_page'));
		$gallery_image_crop=add_submenu_page( "Wpdevart_gallery_menu", "Crope", "Crope", 'manage_options',"Wpdevart_gallery_crop",array($this, 'croping_page'));
		$shortcode_gen_page=add_submenu_page( "Wpdevart_gallery_menu", "Shortcode Generator", "Shortcode Generator", 'manage_options',"Wpdevart_gallery_shordcode_generator",array($this, 'shordcode_generator'));
		$featured_plugin=add_submenu_page( "Wpdevart_gallery_menu", "Featured Plugins", "Featured Plugins", 'manage_options',"Wpdevart_gallery_featured_plugins",array($this, 'featured_plugins'));
		$hire_an_expert=add_submenu_page( "Wpdevart_gallery_menu", "Hire an Expert", "<span style=\"color:#00ff66\" >Hire an Expert</span>", 'manage_options',"Wpdevart_gallery_hire_expert",array($this, 'hire_expert'));
		/*for including page styles and scripts*/
		add_action('admin_print_styles-' .$main_page, array($this,'create_gallery_page_style_js'));
		add_action('admin_print_styles-' .$popup_page, array($this,'create_popup_page_style_js'));
		add_action('admin_print_styles-' .$gallery_theme, array($this,'create_theme_page_style_js'));
		add_action('admin_print_styles-' .$gallery_image_crop, array($this,'create_crop_page_style_js'));
		add_action('admin_print_styles-' .$shortcode_gen_page, array($this,'shordcode_generator_page_style_js'));
		add_action('admin_print_styles-' .$hire_an_expert, array($this,'hire_expert_page_style_js'));
		
		if(isset($submenu['Wpdevart_gallery_menu']))
			add_submenu_page( 'Wpdevart_gallery_menu', "Support or Any Ideas?", "<span style='color:#00ff66' >Support or Any Ideas?</span>", 'manage_options',"wpdevart_gallery_any_ideas",array($this, 'any_ideas'),155);
		if(isset($submenu['Wpdevart_gallery_menu']))
			$submenu['Wpdevart_gallery_menu'][7][2]=wpdevart_gallery_support_url;
	}

    /*###################### Any ideas function ##################*/	
	
	public function any_ideas(){
		
	}

	/* Gallery page style and js*/	
	public function create_gallery_page_style_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/gallery_page.css');
		wp_enqueue_script('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/js/gallery_page.js');
	}
	/* Shortcode page style and js*/	
	public function shordcode_generator_page_style_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp-tinymce');
		wp_enqueue_script('shortcode_page_js',wpdevart_gallery_plugin_url.'includes/admin/js/shortcode_page.js');
		wp_enqueue_style('shortcode_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/shortcode_page.css');		
	}
	
	/* Popup page style and js*/	
	public function create_popup_page_style_js(){
		wp_enqueue_style('FontAwesome');
		wp_enqueue_style('metrical_icons','https://fonts.googleapis.com/icon?family=Material+Icons');
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script('angularejs',wpdevart_gallery_plugin_url.'includes/admin/js/angular.min.js');
		wp_enqueue_style('wpdevart_gallery_admin_theme_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/theme_page.css');
		wp_enqueue_style('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/popup_page.css');
		wp_enqueue_script('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/js/popup_page.js');
		wp_enqueue_script("admin_gallery_theme",wpdevart_gallery_plugin_url.'includes/admin/js/gallery_theme.js');                     //05-11-2017 added
	}
	
	/* Themes page style and js*/	
	public function create_theme_page_style_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('angularejs',wpdevart_gallery_plugin_url.'includes/admin/js/angular.min.js');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style('wpdevart_gallery_admin_theme_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/theme_page.css');
		wp_enqueue_script("admin_gallery_theme",wpdevart_gallery_plugin_url.'includes/admin/js/gallery_theme.js');
	}
	
	/* cropping page style and js*/	
	public function create_crop_page_style_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/croping_page.css');
		wp_enqueue_script('wpdevart_gallery_admin_gallery_page_css',wpdevart_gallery_plugin_url.'includes/admin/js/croping_page.js');
	}
	/* hire an expert page css js */
	public function hire_expert_page_style_js(){
		wp_enqueue_style('wpdevart_gallery_admin_hire_page_css',wpdevart_gallery_plugin_url.'includes/admin/css/hire_expert.css');
	}
	/* Gallery page main*/	
	public function create_gallery_page(){				
		$galler_page_objet=new wpda_gall_gallery_page();
		$galler_page_objet->controller();	
	}	
	
	/* Popup page function */
	public function popup_settings_page(){
		$popup_page_objet=new wpda_gall_popup_themes();
	}	
	/* Themes page function */		
	public function gallery_themes_page(){
		$popup_page_objet=new wpda_gall_themes();		
	}
	/* Cropping page function */ 
	public function croping_page(){
		$croping_object=new wpda_gall_crop_page();	
		$croping_object->controller();		
	}
	
	/*post page button*/
	public function mce_external_plugins( $plugin_array ) {
		$plugin_array["wpdevart_gallery"] = wpdevart_gallery_plugin_url.'includes/admin/js/post_page_insert_button.js';
		return $plugin_array;
	}
	/* shortcode page function */ 
	public function shordcode_generator(){
		$shortcode_object=new wpda_gall_shortcode_generator();	
		$shortcode_object->controller();		
	}
	/**/
	public function mce_buttons( $buttons ) {
		array_push( $buttons, "wpdevart_gallery" );
		return $buttons;
	}
	public function post_page_popup_content(){
		$popup_page_objet=new wpda_gall_post_page_popup();
	}
	private function include_requared_files(){
		require_once(wpdevart_gallery_plugin_path.'includes/admin/gallery_page_class.php');	
		require_once(wpdevart_gallery_plugin_path.'includes/admin/popup_settings.php');	
		require_once(wpdevart_gallery_plugin_path.'includes/admin/gallery_theme.php');
		require_once(wpdevart_gallery_plugin_path.'includes/admin/croping_page.php');	
		require_once(wpdevart_gallery_plugin_path.'includes/admin/post_page_popup.php');
		require_once(wpdevart_gallery_plugin_path.'includes/admin/shortcode_generator_page.php');			
	}
	
	/*###################### Featured plugins function ##################*/		
	
	public function featured_plugins(){
		$plugins_array=array(
			'Pricing Table'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/Pricing-table.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-pricing-table-plugin/',
						'title'			=>	'WordPress Pricing Table',
						'description'	=>	'WordPress Pricing Table plugin is a nice tool for creating beautiful pricing tables. Use WpDevArt pricing table themes and create tables just in a few minutes.'
						),		
			'countdown-extended'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/icon-128x128.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-countdown-extended-version/',
						'title'			=>	'WordPress Countdown Extended',
						'description'	=>	'Countdown extended is a fresh and extended version of the countdown timer. You can easily create and add countdown timers to your website.'
						),							
			'coming_soon'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/coming_soon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'Contact forms'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/contact_forms.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-contact-form-plugin/',
						'title'			=>	'Contact Form Builder',
						'description'	=>	'Contact Form Builder plugin is a handy tool for creating different types of contact forms on your WordPress websites.'
						),	
			'Booking Calendar'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/Booking_calendar_featured.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
						'title'			=>	'WordPress Booking Calendar',
						'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
						),	
			'chart'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/chart-featured.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-organization-chart-plugin/',
						'title'			=>	'WordPress Organization Chart',
						'description'	=>	'WordPress organization chart plugin is a great tool for adding organizational charts to your WordPress websites.'
						),						
			'youtube'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is a convenient tool for adding videos to your website. Use YouTube Embed plugin for adding YouTube videos in posts/pages, widgets.'
						),
            'facebook-comments'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/facebook-comments-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
						'title'			=>	'Wpdevart Social comments',
						'description'	=>	'WordPress Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
						),						
			'countdown'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is a nice tool for creating countdown timers for your website posts/pages and widgets.'
						),
			'lightbox'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/lightbox.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-lightbox-plugin',
						'title'			=>	'WordPress Lightbox plugin',
						'description'	=>	'WordPress Lightbox Popup is a highly customizable and responsive plugin for displaying images and videos in a popup.'
						),
			'facebook'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/facebook.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Social Like Box',
						'description'	=>	'Facebook like box plugin will help you to display Facebook like box on your website, just add Facebook Like box widget to the sidebar or insert it into posts/pages and use it.'
						),
			'vertical_menu'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/vertical-menu.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-vertical-menu-plugin/',
						'title'			=>	'WordPress Vertical Menu',
						'description'	=>	'WordPress Vertical Menu is a handy tool for adding nice vertical menus. You can add icons for your website vertical menus using our plugin.'
						),						
			'poll'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/poll.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-polls-plugin',
						'title'			=>	'WordPress Polls system',
						'description'	=>	'WordPress Polls system is a handy tool for creating polls and survey forms for your visitors. You can use our polls on widgets, posts, and pages.'
						),
			'duplicate_page'=>array(
						'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/featured_plugins/featured-duplicate.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-duplicate-page-plugin-easily-clone-posts-and-pages/',
						'title'			=>	'WordPress Duplicate page',
						'description'	=>	'Duplicate Page or Post is a great tool that allows duplicating pages and posts. Now you can do it with one click.'
						),						
						
			
		);
		?>
        <style>
         .featured_plugin_main{
			background-color: #ffffff;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			float: left;
			margin-right: 30px;
			margin-bottom: 30px;
			width: calc((100% - 90px)/3);
			border-radius: 15px;
			box-shadow: 1px 1px 7px rgba(0,0,0,0.04);
			padding: 20px 25px;
			text-align: center;
			-webkit-transition:-webkit-transform 0.3s;
			-moz-transition:-moz-transform 0.3s;
			transition:transform 0.3s;   
			-webkit-transform: translateY(0);
			-moz-transform: translateY0);
			transform: translateY(0);
			min-height: 344px;
		 }
		.featured_plugin_main:hover{
			-webkit-transform: translateY(-2px);
			-moz-transform: translateY(-2px);
			transform: translateY(-2px);
		 }
		.featured_plugin_image{
			max-width: 128px;
			margin: 0 auto;
		}
		.blue_button{
    display: inline-block;
    font-size: 15px;
    text-decoration: none;
    border-radius: 5px;
    color: #ffffff;
    font-weight: 400;
    opacity: 1;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    transition: opacity 0.3s;
    background-color: #7052fb;
    padding: 10px 22px;
    text-transform: uppercase;
		}
		.blue_button:hover,
		.blue_button:focus {
			color:#ffffff;
			box-shadow: none;
			outline: none;
		}
		.featured_plugin_image img{
			max-width: 100%;
		}
		.featured_plugin_image a{
		  display: inline-block;
		}
		.featured_plugin_information{	

		}
		.featured_plugin_title{
	color: #7052fb;
	font-size: 18px;
	display: inline-block;
		}
		.featured_plugin_title a{
	text-decoration:none;
	font-size: 19px;
    line-height: 22px;
	color: #7052fb;
					
		}
		.featured_plugin_title h4{
			margin: 0px;
			margin-top: 20px;		
			min-height: 44px;	
		}
		.featured_plugin_description{
			font-size: 14px;
				min-height: 63px;
		}
		@media screen and (max-width: 1460px){
			.featured_plugin_main {
				margin-right: 20px;
				margin-bottom: 20px;
				width: calc((100% - 60px)/3);
				padding: 20px 10px;
			}
			.featured_plugin_description {
				font-size: 13px;
				min-height: 63px;
			}
		}
		@media screen and (max-width: 1279px){
			.featured_plugin_main {
				width: calc((100% - 60px)/2);
				padding: 20px 20px;
				min-height: 363px;
			}	
		}
		@media screen and (max-width: 768px){
			.featured_plugin_main {
				width: calc(100% - 30px);
				padding: 20px 20px;
				min-height: auto;
				margin: 0 auto 20px;
				float: none;
			}	
			.featured_plugin_title h4{
				min-height: auto;
			}	
			.featured_plugin_description{
				min-height: auto;
					font-size: 14px;
			}	
		}

        </style>
      
		<h1 style="text-align: center;font-size: 50px;font-weight: 700;color: #2b2350;margin: 20px auto 25px;line-height: 1.2;">Featured Plugins</h1>
		<?php foreach($plugins_array as $key=>$plugin) { ?>
		<div class="featured_plugin_main">
			<div class="featured_plugin_image"><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><img src="<?php echo $plugin['image_url'] ?>"></a></div>
			<div class="featured_plugin_information">
				<div class="featured_plugin_title"><h4><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><?php echo $plugin['title'] ?></a></h4></div>
				<p class="featured_plugin_description"><?php echo $plugin['description'] ?></p>
				<a target="_blank" href="<?php echo $plugin['site_url'] ?>" class="blue_button">Check The Plugin</a>
			</div>
			<div style="clear:both"></div>                
		</div>
		<?php } 
	
	}

	public function hire_expert(){
		$plugins_array=array(
			'custom_site_dev'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/1.png',
				'title'			=>	'Custom WordPress Development',
				'description'	=>	'Hire a WordPress developer and he will do any custom development you need for you WordPress website.'
			),
			'custom_plug_dev'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/2.png',
				'title'			=>	'WordPress Plugin Development',
				'description'	=>	'Our developers can create any WordPress plugin. They can also customize any plugin and add any functionality you need.'
			),
			'custom_theme_dev'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/3.png',
				'title'			=>	'WordPress Theme Development',
				'description'	=>	'If you need a unique theme or any customization for a ready-made theme, our developers are ready to do it.'
			),
			'custom_theme_inst'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/4.png',
				'title'			=>	'WordPress Theme Installation and Customization',
				'description'	=>	'If you need to install and customize a theme, just let us know, our specialists will customize it.'
			),
			'gen_wp_speed'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/5.png',
				'title'			=>	'General WordPress Support',
				'description'	=>	'Our developers can provide general support. If you have any problems with your site, then our experts are ready to help.'
			),
			'speed_op'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/6.png',
				'title'			=>	'WordPress Speed Optimization',
				'description'	=>	'Hire an expert from WpDevArt and let him take care of your website speed optimization.'
			),
			'mig_serv'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/7.png',
				'title'			=>	'WordPress Migration Services',
				'description'	=>	'Our specialists can migrate websites from any platform to WordPress.'
			),
			'page_seo'=>array(
				'image_url'		=>	wpdevart_gallery_plugin_url.'includes/admin/images/hire_expert/8.png',
				'title'			=>	'WordPress SEO',
				'description'	=>	'Hire SEO specialists and they will take care of the search engine optimization of your site.'
			)
		);
		$content='';
		
		$content.='<h1 class="wpdev_hire_exp_h1"> Hire an Expert from WpDevArt </h1>';
		$content.='<div class="hire_expert_main">';		
		foreach($plugins_array as $key=>$plugin) {
			$content.='<div class="wpdevart_hire_main"><a target="_blank" class="wpdev_hire_buklet" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">';
			$content.='<div class="wpdevart_hire_image"><img src="'.$plugin["image_url"].'"></div>';
			$content.='<div class="wpdevart_hire_information">';
			$content.='<div class="wpdevart_hire_title">'.$plugin["title"].'</div>';			
			$content.='<p class="wpdevart_hire_description">'.$plugin["description"].'</p>';
			$content.='</div></a></div>';		
		} 
		$content.='<div><a target="_blank" class="wpdev_hire_button" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">Hire an Expert</a></div>';
		$content.='</div>';
		
		echo $content;
	
	}
	
}
?>

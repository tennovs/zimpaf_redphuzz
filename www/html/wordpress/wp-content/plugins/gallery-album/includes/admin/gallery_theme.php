<?php
class wpda_gall_themes{
	private $options;
	
	private $gag;
	function __construct(){
		
		$this->options=self::return_params_array();	
		$this->controller_page();				
	}
	
	/*###################### Function for returning parameters array ##################*/	
	
	public static function return_params_array(){
		return array(
			"theme_general_settings"=>array(
				"heading_name"=>"General",
				"params"=>array(
					"count_image_per_page"=>array(
						"title"=>"Count of elements in page",
						"description"=>"Type the count of elements in a page",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"default_value"=>"20",
					),			
					"mas_mos_tumb_order_column"=>array(
						"title"=>"Image distance type",
						"description"=>"Select the distance type of the image(work only for Masonry alternative, Mosaic alternative and Thumbnail alternative views)",
						"values"=>array("0"=>"Space from sides","1"=>"Space between images","2"=>"Space between images and sides"),
						"function_name"=>"simple_select",
						"default_value"=>"1",
						"pro"=>true,
					),
					"mas_mos_tumb_number_column"=>array(
						"title"=>"Type the number of max columns in the Gallery, Album and Images",
						"description"=>"mos mas thumb Type max columns in Gallery, Album and Images",			
						"function_name"=>"simple_input",
						"type"=>"number",	
						"default_value"=>"3",
						"pro"=>true,
					),
					"count_of_added_elements"=>array(
						"title"=>"Count of added elements(more button)",
						"description"=>"Set the count of the added elements, this option works only when the pagination type is set 'more' or 'scroll'",				
						"function_name"=>"simple_input",
						"type"=>"number",	
						"default_value"=>"20",
						"pro"=>true,
					),
					"select_hide"=>array(
						"title"=>"Show ordering type on front end",
						"description"=>"Choose to show or hide the ordering type on the front end",
						"values"=>array("0"=>"Hide","1"=>"Show"),
						"function_name"=>"simple_select",
						"default_value"=>"0",
						"pro"=>true,
					),
					"hover_image_or_text_bar"=>array(
						"title"=>"When text hover should work",
						"description"=>"Choose gallery shadow type hover",
						"values"=>array(":hover"=>"When whole element is on hover","empoty"=>"When only text element is on hover"),
						"function_name"=>"simple_select",
						"default_value"=>":hover",
						"pro"=>true,
					),
					"speed_hover"=>array(
						"title"=>"Elements animation speed <span style='color:blue'>on hover</span>",
						"description"=>"Select elements animation speed on hover",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(ms)",
						"default_value"=>"70",
						"pro"=>true,
					),
					"main_conteyner_box_shadow"=>array(
						"title"=>"Main container box shadow size",
						"description"=>"Type main container box shadow size",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"1",
					),
					"pagination_bar_color"=>array(
						"title"=>"Main and pagination background color",
						"description"=>"Select main and pagination background color",
						"default_value"=>"#FFFFFF",
						"function_name"=>"color_input",
					),
					"pagination_bar_color_opacity"=>array(
						"title"=>"Main background opacity",
						"description"=>"Select main background opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
					),
					"go_back_button_show"=>array(
						"title"=>"Show go back button",
						"description"=>"Choose to always show or show only click open, go back button on front end",
						"values"=>array("0"=>"Always show","1"=>"Show only go"),
						"function_name"=>"simple_select",
						"default_value"=>"1",
						"pro"=>true,											
					),
					"open_effects"=>array(
						"title"=>"Open effects",
						"description"=>"Choose open effects gallery album image",
						"values"=>array("none"=>"None","scale(0.1)"=>"Scale","rotateX(90deg)"=>"RotateX","rotateY(90deg)"=>"RotateY"),
						"function_name"=>"simple_select",
						"default_value"=>"none",
						"pro"=>true,
					),
					"scroll_top"=>array(
						"title"=>"Scroll top",
						"description"=>"Select the top scroll gallery, album, image when changing one to another",
						"values"=>array("0"=>"Yes","1"=>"No"),
						"function_name"=>"simple_select",
						"default_value"=>"1",
					),																														 										
				)
			),
			"theme_pagination_settings"=>array(
				"heading_name"=>"Pagination Settings",
				"params"=>array(					
					"pagination_bar_height"=>array(
						"title"=>"Pagination panel height",
						"description"=>"Type here pagination panel height",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"30",
					),
					"more_page"=>array(
						"title"=>"Pagination type",
						"description"=>"Choose the pagination type",
						"values"=>array("0"=>"More button","1"=>"Pagination bar","2"=>"Scroll"),   //09-08-2017
						"function_name"=>"simple_select",
						"default_value"=>"1",
						"pro"=>true,
					),

					"pagination_buttons_font_bg_color"=>array(
						"title"=>"Pagination buttons background color",
						"description"=>"Select pagination buttons background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",	
					),
					"pagination_buttons_font_bg_color_opacity"=>array(
						"title"=>"Pagination buttons background opacity",
						"description"=>"Select pagination buttons background opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
					),
					"pagination_buttons_font_bg_color_hover"=>array(
						"title"=>"Pagination buttons background color <span style='color:blue'>on hover</span>",
						"description"=>"Choose pagination buttons on hover background color",
						"default_value"=>"#4f4f4f",
						"function_name"=>"color_input",				
					),
					"pagination_buttons_font_bg_color_opacity_hover"=>array(
						"title"=>"Pagination buttons background opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select pagination buttons background on hover opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
					),					 					
					"pagination_buttons_font_bg_color_active"=>array(
						"title"=>"Active pagination button background color",
						"description"=>"Choose active pagination button background color",
						"default_value"=>"#757575",
						"function_name"=>"color_input",				
					),
					"pagination_buttons_font_bg_color_active_opacity"=>array(
						"title"=>"Active pagination button background opacity",
						"description"=>"Select active pagination button background opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
					),									
					"pagination_buttons_font_text_color"=>array(
						"title"=>"Pagination buttons text color",
						"description"=>"Select pagination buttons text color",
						"default_value"=>"#ffffff",
						"function_name"=>"color_input",
					),
					"pagination_buttons_font_text_color_hover"=>array(
						"title"=>"Pagination buttons text color <span style='color:blue'>on hover</span>",
						"description"=>"Select pagination buttons on hover text color",
						"default_value"=>"#ffffff",
						"function_name"=>"color_input",	
					),					
					"pagination_buttons_font_text_color_active"=>array(
						"title"=>"Active pagination button text color",
						"description"=>"Select active pagination button text color",
						"default_value"=>"#ffffff",
						"function_name"=>"color_input",	
					),
 					"pagination_buttons_border_color"=>array(
						"title"=>"Pagination buttons border color",
						"description"=>"Select pagination buttons border color",
						"default_value"=>"#999999",
						"function_name"=>"color_input",	
					),
 					"pagination_buttons_border_color_hover"=>array(
						"title"=>"Pagination buttons border color <span style='color:blue'>on hover</span>",
						"description"=>"Select pagination buttons border color on hover",
						"default_value"=>"#dddddd",
						"function_name"=>"color_input",	
					),					    
					"pagination_buttons_border_width"=>array(
						"title"=>"Pagination buttons border width",                    
						"description"=>"Type pagination buttons border width",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"0",
					),    
					"pagination_buttons_border_radius"=>array(
						"title"=>"Pagination buttons border radius",                    
						"description"=>"Type pagination buttons border radius",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"2",
					),        
					"pagination_buttons_distans"=>array(
						"title"=>"Pagination buttons distance",                    
						"description"=>"Type pagination buttons distance",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"2",
					),  
					"pagination_buttons_pref_view"=>array(
						"title"=>"Pagination previous button type",
						"description"=>"Select pagination previous button type",
						"values"=>array("Prev"=>"Prev","<<"=>"<<"),
						"function_name"=>"simple_select",
						"default_value"=>"Prev",
					),  
					"pagination_buttons_next_view"=>array(
						"title"=>"Pagination next button type",
						"description"=>"Select pagination next button type",
						"values"=>array("Next"=>"Next",">>"=>">>"),
						"function_name"=>"simple_select",
						"default_value"=>"Next",
					),         
					"pagination_buttons_font_size_pracent"=>array(
						"title"=>"Pagination buttons font size",
						"description"=>"Select pagination buttons font size",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"90",
					),
					"sort_goback_buttons_bg_color"=>array(
						"title"=>"Sort and go_back buttons background color",
						"description"=>"Select sort and go_back buttons background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"sort_goback_buttons_bg_color_hover"=>array(
						"title"=>"Sort and go_back buttons background color <span style='color:blue'>on hover</span>",
						"description"=>"Select on hover sort and go_back buttons background color",
						"default_value"=>"#4f4f4f",
						"function_name"=>"color_input",
						"pro"=>true,				
					),					
				),
			),			
			"theme_gallery_settings"=>array(
				"heading_name"=>"Gallery settings",
				"params"=>array(
						"gallery_wdt"=>array(
						"title"=>"Gallery width",
						"description"=>"Type gallery width",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"200",
					),
					"gallery_hgt"=>array(
						"title"=>"Gallery height",
						"description"=>"Type gallery height",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"180",
					),
					"gallery_mgn"=>array(
						"title"=>"Galleries distance",
						"description"=>"Type distance between galleries",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"25",
					),
					"gallery_image_width"=>array(
						"title"=>"Gallery folder size inside gallery window",
						"description"=>"Select gallery folder size inside gallery window",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"71",
					),
					"gallery_opacity"=>array(
						"title"=>"Gallery opacity",
						"description"=>"Select gallery opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_opacity_hover"=>array(
						"title"=>"Gallery opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_bg_color"=>array(
						"title"=>"Gallery background color",
						"description"=>"Select gallery background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",	
						"pro"=>true,			
					),
					"gallery_bg_color_opacity"=>array(
						"title"=>"Gallery background color opacity",
						"description"=>"Select gallery background color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_bg_color_hover"=>array(
						"title"=>"Gallery background color <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery background color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"gallery_bg_color_opacity_hover"=>array(
						"title"=>"Gallery background color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery background color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					//10-09-2017
					"gallery_bg_image"=>array(
						"title"=>"Gallery background image show or hide",
						"description"=>"Show or hide the first image of first album of gallery as gallery background image",
						"values"=>array("show"=>"show","hide"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>"hide",
						"pro"=>true,
					),					
					"gallery_folder"=>array(
						"title"=>"Gallery folder of images show or hide",
						"description"=>"Show or hide gallery folder of images",
						"values"=>array(" "=>"show","display:none;"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>" ",
						"pro"=>true,
					),						
					"gallery_folder_active"=>array(
						"title"=>"Gallery folder of images show or hide <span style='color:red'>on active</span>",
						"description"=>"Show or hide gallery folder of images on active ",
						"values"=>array(" "=>"show","opacity:0;"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>" ",
						"pro"=>true,
					),						
										
										
					"gallery_brd"=>array(
						"title"=>"Gallery border size",
						"description"=>"Type gallery border size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_brd_color"=>array(
						"title"=>"Gallery border color",
						"description"=>"Select gallery border color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"gallery_brd_opacity"=>array(
						"title"=>"Gallery border color opacity",
						"description"=>"Select gallery border color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),					
					"gallery_brd_color_hover"=>array(
						"title"=>"Gallery border color <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery border color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),			
					"gallery_brd_opacity_hover"=>array(
						"title"=>"Gallery border color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery border color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_brd_rad"=>array(
						"title"=>"Gallery border radius",
						"description"=>"Type gallery border radius",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_brd_rad_hover"=>array(
						"title"=>"Gallery border radius <span style='color:blue'>on hover</span>",
						"description"=>"Type gallery border radius on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_color"=>array(
						"title"=>"Gallery shadow color",
						"description"=>"Select color of the shadow",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"gallery_shadov_opacity"=>array(
						"title"=>"Gallery shadow color opacity",
						"description"=>"Select color opacity of the shadow",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_shadov_h"=>array(
						"title"=>"Gallery horizontal shadow",
						"description"=>"Select position of the horizontal shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_v"=>array(
						"title"=>"Gallery vertical shadow",
						"description"=>"Select position of the vertical shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_blur"=>array(
						"title"=>"Gallery shadow blur",
						"description"=>"Select blur distance",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_spread"=>array(
						"title"=>"Gallery shadow spread",
						"description"=>"Select size of shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_out_inset"=>array(
						"title"=>"Gallery shadow type",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),																														
					"gallery_shadov_color_hover"=>array(
						"title"=>"Gallery shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select color of the shadow on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",	
						"pro"=>true,	
					),
					"gallery_shadov_opacity_hover"=>array(
						"title"=>"Gallery shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select shadow color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_shadov_h_hover"=>array(
						"title"=>"Gallery horizontal shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the horizontal shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_v_hover"=>array(
						"title"=>"Gallery vertical shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the vertical shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_blur_hover"=>array(
						"title"=>"Gallery shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_spread_hover"=>array(
						"title"=>"Gallery spread <span style='color:blue'>on hover</span>",
						"description"=>"Select size of shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"gallery_shadov_out_inset_hover"=>array(
						"title"=>"Gallery shadow type <span style='color:blue'>on hover</span>",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow on hover",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),
					"gallery_text_bar_padding"=>array(
						"title"=>"Gallery text padding",
						"description"=>"Type gallery text padding",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
					),
					"gallery_text_font_size"=>array(
						"title"=>"Gallery text font size",
						"description"=>"Type gallery text font size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"12",
					),
					"gallery_text_font_size_hover"=>array(
						"title"=>"Gallery text font size <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery text font size on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"12",
					),					
					"gallery_text_color"=>array(
						"title"=>"Gallery text color",
						"description"=>"Select gallery text color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"gallery_text_opacity"=>array(
						"title"=>"Gallery text color opacity",
						"description"=>"Select gallery text color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),										
					"gallery_text_color_hover"=>array(
						"title"=>"Gallery text color <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery text color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"gallery_text_opacity_hover"=>array(
						"title"=>"Gallery text color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select gallery text color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),			
					"gallery_text_shadov_color"=>array(
						"title"=>"Gallery text shadow color",
						"description"=>"Select gallery text shadow color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"gallery_text_shadov_opacity"=>array(
						"title"=>"Gallery text shadow color opacity",
						"description"=>"Select gallery text shadow color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_text_shadov_blur"=>array(
						"title"=>"Gallery text shadow blur",
						"description"=>"Select blur distance of the gallery text shadow",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),										
					"gallery_text_shadov_color_hover"=>array(
						"title"=>"Gallery text shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select shadow color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),		
					"gallery_text_shadov_opacity_hover"=>array(
						"title"=>"Gallery text shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select shadow color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"gallery_text_shadov_blur_hover"=>array(
						"title"=>"Gallery text shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance of the gallery text shadow on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),			
					"gallery_folder_bg_color"=>array(
						"title"=>"Gallery folder background color",
						"description"=>"Select gallery folder background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,
					),
					"gallery_order_type"=>array(
						"title"=>"Gallery sort view",
						"description"=>"Choose gallery sort view",
						"values"=>array( "kirpich"=>"Square view","masony"=>"Masonry view","mosaik"=>"Mosaic view","tumbnails"=>"Thumbnail view","kirpich_"=>"Square alternative view","masony_"=>"Masonry alternative view","mosaik_"=>"Mosaic alternative view","tumbnails_"=>"Thumbnail alternative view","column"=>"Column view"),
						"function_name"=>"simple_select",
                        "default_value"=>"tumbnails",
						"pro"=>true,
					),													
					"gallery_partial_count0"=>array(
						"title"=>"Number of galleries to show before clicking more button",
						"description"=>"Type how many galleries to show before clicking more button(work only when selected more button option)",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"20",
						"pro"=>true,
					),
					"gallery_border_style"=>array(
						"title"=>"Gallery border style",
						"description"=>"Type gallery border style)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),
					"gallery_border_style_hover"=>array(
						"title"=>"Gallery border style <span style='color:blue'>on hover</span>",
						"description"=>"Type gallery border style on hover)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),										
				),
			),			
			"theme_album_settings"=>array(
				"heading_name"=>"Album settings",
				"params"=>array(
						"album_wdt"=>array(
						"title"=>"Album width",
						"description"=>"Type album width",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"200",
					),
					"album_hgt"=>array(
						"title"=>"Album height",
						"description"=>"Type album height",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"180",
					),
					"album_mgn"=>array(
						"title"=>"Albums distance",
						"description"=>"Type distance between Albums",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"25",
					),
					"album_image_width"=>array(
						"title"=>"Album folder size inside album window",
						"description"=>"Select album folder size inside album window",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"71",
					),
					"album_opacity"=>array(
						"title"=>"Album opacity",
						"description"=>"Select album opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_opacity_hover"=>array(
						"title"=>"Album opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select album opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_bg_color"=>array(
						"title"=>"Album background color",
						"description"=>"Select album background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"album_bg_color_opacity"=>array(
						"title"=>"Album background color opacity",
						"description"=>"Select album background color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"0",
						"pro"=>true,
					),										
					"album_bg_color_hover"=>array(
						"title"=>"Album background color <span style='color:blue'>on hover</span>",
						"description"=>"Select album background color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"album_bg_color_opacity_hover"=>array(
						"title"=>"Album background color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select album background color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					//10-09-2017
					"album_bg_image"=>array(
						"title"=>"Album background image show or hide",
						"description"=>"Show or hide the first image of album as album background image",
						"values"=>array("show"=>"show","hide"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>"hide",
						"pro"=>true,
					),					
					"album_images"=>array(
						"title"=>"Album images show or hide",
						"description"=>"Show or hide album images",
						"values"=>array(" "=>"show","display:none;"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>" ",
						"pro"=>true,
					),					
					"album_images_active"=>array(
						"title"=>"Album images translate or hide <span style='color:red'>on active</span>",
						"description"=>"Translate or hide album images on active ",
						"values"=>array("rotate"=>"translate","r"=>"hide"),
						"function_name"=>"simple_select",
						"default_value"=>"rotate",
						"pro"=>true,
					),					
					
								
					"album_brd"=>array(
						"title"=>"Album border size",
						"description"=>"Type album border size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_brd_color"=>array(
						"title"=>"Album border color",
						"description"=>"Select album border color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"album_brd_opacity"=>array(
						"title"=>"Album border color opacity",
						"description"=>"Select album border color opacity ",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),					
					"album_brd_color_hover"=>array(
						"title"=>"Album border color <span style='color:blue'>on hover</span>",
						"description"=>"Select album border color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),			
					"album_brd_opacity_hover"=>array(
						"title"=>"Album border color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select album border color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_brd_rad"=>array(
						"title"=>"Album border radius",
						"description"=>"Type album border radius",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_brd_rad_hover"=>array(
						"title"=>"Album border radius <span style='color:blue'>on hover</span>",
						"description"=>"Type album border radius on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_color"=>array(
						"title"=>"Album shadow color",
						"description"=>"Select color of the shadow",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"album_shadov_opacity"=>array(
						"title"=>"Album shadow color opacity",
						"description"=>"Select color opacity of the shadow",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_shadov_h"=>array(
						"title"=>"Album horizontal shadow",
						"description"=>"Select position of the horizontal shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_v"=>array(
						"title"=>"Album vertical shadow",
						"description"=>"Select position of the vertical shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_blur"=>array(
						"title"=>"Album shadow blur",
						"description"=>"Select blur distance",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_spread"=>array(
						"title"=>"Album shadow spread",
						"description"=>"Select size of shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_out_inset"=>array(
						"title"=>"Album shadow type",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),																														
					"album_shadov_color_hover"=>array(
						"title"=>"Album shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select color of the shadow on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"album_shadov_opacity_hover"=>array(
						"title"=>"Album shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select color opacity of the shadow on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_shadov_h_hover"=>array(
						"title"=>"Album horizontal shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the horizontal shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_v_hover"=>array(
						"title"=>"Album vertical shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the vertical shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_blur_hover"=>array(
						"title"=>"Album shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_spread_hover"=>array(
						"title"=>"Album separate shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select size of shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_shadov_out_inset_hover"=>array(
						"title"=>"Album shadow type <span style='color:blue'>on hover</span>",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow on hover",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),
					"album_text_bar_padding"=>array(
						"title"=>"Album text padding",
						"description"=>"Type album text padding",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
					),
					"album_text_font_size"=>array(
						"title"=>"Album text font size",
						"description"=>"Type album text font size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"12",
					),
					"album_text_font_size_hover"=>array(
						"title"=>"Album text font size <span style='color:blue'>on hover</span>",
						"description"=>"Type album text font size on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"12",
					),
					"album_text_color"=>array(
						"title"=>"Album text color",
						"description"=>"Select album text color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"album_text_opacity"=>array(
						"title"=>"Album text color opacity",
						"description"=>"Select album text color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),					
					"album_text_color_hover"=>array(
						"title"=>"Album text color <span style='color:blue'>on hover</span>",
						"description"=>"Select album text color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"album_text_opacity_hover"=>array(
						"title"=>"Album text color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select album text color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),			
					"album_text_shadov_color"=>array(
						"title"=>"Album text shadow color",
						"description"=>"Select album text shadow color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"album_text_shadov_opacity"=>array(
						"title"=>"Album text shadow color opacity",
						"description"=>"Select album text shadow color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_text_shadov_blur"=>array(
						"title"=>"Album text shadow blur",
						"description"=>"Select blur distance of the album text shadow",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),										
					"album_text_shadov_color_hover"=>array(
						"title"=>"Album text shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select album text shadow color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),		
					"album_text_shadov_opacity_hover"=>array(
						"title"=>"Album text shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select album text shadow color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"album_text_shadov_blur_hover"=>array(
						"title"=>"Album text shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance of the album text shadow on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"album_order_type"=>array(
						"title"=>"Album sort view",
						"description"=>"Choose the album sort view",
						"values"=>array( "kirpich"=>"Square view","masony"=>"Masonry view","mosaik"=>"Mosaic view","tumbnails"=>"Thumbnail view","kirpich_"=>"Square alternative view","masony_"=>"Masonry alternative view","mosaik_"=>"Mosaic alternative view","tumbnails_"=>"Thumbnail alternative view","column"=>"Column view"),
						"function_name"=>"simple_select",
                        "default_value"=>"tumbnails",
						"pro"=>true,
					),											
					"album_partial_count0"=>array(
						"title"=>"Number of albums to show before clicking on the more button",
						"description"=>"Type how many albums to show before clicking on the more button(works only if selected the more button option)",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"20",
						"pro"=>true,
					),
					"album_border_style"=>array(
						"title"=>"Album border style",
						"description"=>"Select the album border style)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),
					"album_border_style_hover"=>array(
						"title"=>"Album border style <span style='color:blue'>on hover</span>",
						"description"=>"Select the album border style on hover)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),					
				),
			),
			"theme_image_settings"=>array(
				"heading_name"=>"Image Settings",
				"params"=>array(					
					"image_wdt"=>array(
						"title"=>"Image width",
						"description"=>"Select image width",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"200",
					),
					"image_hgt"=>array(
						"title"=>"Image height",
						"description"=>"Select image height",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"180",
					),
					"image_mgn"=>array(
						"title"=>"Images distance",
						"description"=>"Select the distance between images",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"default_value"=>"5",
					),
					"image_scale"=>array(
						"title"=>"Image zoom <span style='color:blue'>on hover</span>",
						"description"=>"Select image zoom on hover",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(100/100)",
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_rotate"=>array(
						"title"=>"Image rotation <span style='color:blue'>on hover</span>",
						"description"=>"Select image rotation on hover",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(deg)",
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_opacity"=>array(
						"title"=>"Image opacity",
						"description"=>"Select image opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_opacity_hover"=>array(
						"title"=>"Image opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_brd"=>array(
						"title"=>"Image border size",
						"description"=>"Select image border size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"1",
						"pro"=>true,
					),
					"image_brd_color"=>array(
						"title"=>"Image border color",
						"description"=>"Select image border color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"image_brd_opacity"=>array(
						"title"=>"Image border color opacity",
						"description"=>"Select image border color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_brd_color_hover"=>array(
						"title"=>"Image border color <span style='color:blue'>on hover</span>",
						"description"=>"Select image border color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,	
					),
					"image_brd_opacity_hover"=>array(
						"title"=>"Image border color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image border color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_brd_rad"=>array(
						"title"=>"Image border radius",
						"description"=>"Select image border radius",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_brd_type_rad"=>array(
						"title"=>"Image border radius metric type",
						"description"=>"Select image border radius metric type",
						"values"=>array("px"=>"Pixels","%"=>"Pracents","em"=>"Em","cm"=>"Cantimetrs"),
						"function_name"=>"simple_select",	
						"default_value"=>"px",
						"pro"=>true,
					),
					"image_brd_rad_hover"=>array(
						"title"=>"Image border radius <span style='color:blue'>on hover</span>",
						"description"=>"Select image border radius on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_brd_rad_type_hover"=>array(
						"title"=>"Image border radius metric type <span style='color:blue'>on hover</span>",
						"description"=>"Type image border radius metric type on hover",
						"values"=>array("px"=>"Pixels","%"=>"Pracents","em"=>"Em","cm"=>"Cantimetrs"),
						"function_name"=>"simple_select",
						"default_value"=>"px",
						"pro"=>true,
					),
					"image_shadov_color"=>array(
						"title"=>"Image shadow color",
						"description"=>"Select image shadow color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"image_shadov_opacity"=>array(
						"title"=>"Image shadow color opacity",
						"description"=>"Select image shadow color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_shadov_h"=>array(
						"title"=>"Image horizontal shadow",
						"description"=>"Select position of the horizontal shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_v"=>array(
						"title"=>"Image vertical shadow",
						"description"=>"Select position of the vertical shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_blur"=>array(
						"title"=>"Image shadow blur",
						"description"=>"Select blur distance",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_spread"=>array(
						"title"=>"Image shadow spread",
						"description"=>"Select size of shadow. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_out_inset"=>array(
						"title"=>"Image shadow type",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),																													
					"image_shadov_color_hover"=>array(
						"title"=>" Image shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select image shadow color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"image_shadov_opacity_hover"=>array(
						"title"=>"Image shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image shadow color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_shadov_h_hover"=>array(
						"title"=>"Image horizontal shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the horizontal shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_v_hover"=>array(
						"title"=>"Image vertical shadow <span style='color:blue'>on hover</span>",
						"description"=>"Select position of the vertical shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_blur_hover"=>array(
						"title"=>"Image shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_spread_hover"=>array(
						"title"=>"Image shadow spread <span style='color:blue'>on hover</span>",
						"description"=>"Select size of shadow on hover. Negative values are allowed",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),
					"image_shadov_out_inset_hover"=>array(
						"title"=>"Image shadow type <span shadow type style='color:blue'>on hover</span>",
						"description"=>"Changes the shadow from an outer shadow (outset) to an inner shadow on hover",
						"values"=>array("inset"=>"inset"," "=>"out"),
						"function_name"=>"simple_select",
						"default_value"=>"inset",
						"pro"=>true,
					),
					"image_text_bar_padding"=>array(
						"title"=>"Image text padding",
						"description"=>"Select image text padding",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"2",
					),
					"image_text_font_size"=>array(
						"title"=>"Image text font size",
						"description"=>"Select image text font size",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"12",
					),
					"image_text_font_size_hover"=>array(
						"title"=>"Image text font size <span style='color:blue'>on hover</span>",
						"description"=>"Select image text font size on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"14",
					),
					"image_text_color"=>array(
						"title"=>"Image text color",
						"description"=>"Select image text color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"image_text_opacity"=>array(
						"title"=>"Image text color opacity",
						"description"=>"Select image text color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"80",
						"pro"=>true,
					),										
					"image_text_color_hover"=>array(
						"title"=>"Image text color <span style='color:blue'>on hover</span>",
						"description"=>"Select image text color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"image_text_opacity_hover"=>array(
						"title"=>"Image text color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image text color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),			
					"image_text_shadov_color"=>array(
						"title"=>"Image text shadow color",
						"description"=>"Select image text shadow color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"image_text_shadov_opacity"=>array(
						"title"=>"Image text shadow color opacity",
						"description"=>"Select image text shadow color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_text_shadov_blur"=>array(
						"title"=>"Image text shadow blur",
						"description"=>"Select blur distance of the image text shadow",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),										
					"image_text_shadov_color_hover"=>array(
						"title"=>"Image text shadow color <span style='color:blue'>on hover</span>",
						"description"=>"Select imager text shadow color on hove",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),		
					"image_text_shadov_opacity_hover"=>array(
						"title"=>"Image text shadow color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image text shadow color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"100",
						"pro"=>true,
					),
					"image_text_shadov_blur_hover"=>array(
						"title"=>"Image text shadow blur <span style='color:blue'>on hover</span>",
						"description"=>"Select blur distance of the image text shadow on hover",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"default_value"=>"0",
						"pro"=>true,
					),						
					"image_text_bar_color"=>array(
						"title"=>"Image text panel background color",
						"description"=>"Select image text panel background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",				
					),
					"image_text_bar_color_opacity"=>array(
						"title"=>"Image text panel background color opacity",
						"description"=>"Select image text panel background color opacity",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"30",
						"pro"=>true,
					),					
					"image_text_bar_color_hover"=>array(
						"title"=>"Image text panel background color <span style='color:blue'>on hover</span>",
						"description"=>"Select image text panel background color on hover",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,				
					),
					"image_text_bar_color_opacity_hover"=>array(
						"title"=>"Image text panel background color opacity <span style='color:blue'>on hover</span>",
						"description"=>"Select image text panel background color opacity on hover",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"default_value"=>"40",
						"pro"=>true,
					),
					"image_order_type"=>array(
						"title"=>"Image sort view",
						"description"=>"Choose image sort view",
						"values"=>array( "kirpich"=>"Square view","masony"=>"Masonry view","mosaik"=>"Mosaic view","tumbnails"=>"Thumbnail view","kirpich_"=>"Square alternative view","masony_"=>"Masonry alternative view","mosaik_"=>"Mosaic alternative view","tumbnails_"=>"Thumbnail alternative view","column"=>"Column view"),
						"function_name"=>"simple_select",
                        "default_value"=>"tumbnails",
						"pro"=>true,											
					),					
					"image_partial_count0"=>array(
						"title"=>"Number of images to show before clicking more button",
						"description"=>"Type how many images to show before clicking more button(work only when selected more button option)",				
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"default_value"=>"20",
						"pro"=>true,
					),
					"image_quality"=>array(
						"title"=>"Quality of the popup image",
						"description"=>"Type original or cropped image in popup)",	
						"values"=>array("main"=>"cropped","original"=>"original"),
						"function_name"=>"simple_select",
						"default_value"=>"original",
						"pro"=>true,
					),
					"image_border_style"=>array(
						"title"=>"Image border style",
						"description"=>"Type image border style)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),
					"image_border_style_hover"=>array(
						"title"=>"Image border style <span style='color:blue'>on hover</span>",
						"description"=>"Type image border style on hover)",	
						"values"=>array("solid"=>"solid","double"=>"double","groove"=>"groove","ridge"=>"ridge","inset"=>"inset","outset"=>"outset","dotted"=>"dotted","dashed"=>"dashed"),
						"function_name"=>"simple_select",
						"default_value"=>"solid",
						"pro"=>true,
					),											
				),
			),			
		);
	}
	
	/*###################### Controller page function ##################*/	
	
	public function controller_page(){
		global $wpdb;
		$task="default";
		$id=0;
		if(isset($_GET["task"])){
			$task=$_GET["task"];
		}
		if(isset($_GET["id"])){
			$id=$_GET["id"];
		}
		
		switch($task){
		case 'add_wpda_galltheme':	
			$this->add_edit_theme($id);
			break;
			
		case 'add_edit_theme':	
			$this->add_edit_theme($id);
			break;
		
		case 'save_gallery':		
			if($id)	
				$this->update_gallery($id);
			else
				$this->save_gallery();
				
			$this->display_table_list_theme();	
			break;
			
			
		case 'update_gallery':		
			if($id){
				$this->update_gallery($id);
			}else{
				$this->save_gallery();
				$_GET['id']=$wpdb->get_var("SELECT MAX(id) FROM ".wpdevart_gallery_databese::$table_names['theme']);
				$id=$_GET['id'];
			}
			$this->add_edit_theme($id);
			break;
		case 'set_default_theme':
			$this->set_default_theme($id);
			$this->display_table_list_theme();	
		break;
		case 'remove_theme':	
			$this->remove_theme($id);
			$this->display_table_list_theme();
			break;
				
		default:
			$this->display_table_list_theme();
		}
	}
	
/*############  Save function  ################*/
	
	private function save_gallery(){
		global $wpdb;
		if(count($_POST)==0)
			return;		
		$params_array=array();
		if(isset($_POST['wpda_gall_theme_name'])){
			$name=sanitize_text_field($_POST['wpda_gall_theme_name']);
		}else{
			$name="Gallery theme";
		}
		$params_array=array('name'=>sanitize_text_field($_POST['wpda_gall_theme_name']));
		foreach($this->options as $param_heading_key=>$param_heading_value){
			foreach($param_heading_value['params'] as $key=>$value){
				if(isset($_POST[$key])){
					$params_array[$key]=$_POST[$key];
				}else{
					$params_array[$key]=$value['default_value'];
				}
			}
		}	
		$save_or_no=$wpdb->insert( wpdevart_gallery_databese::$table_names['theme'], 
			array( 
				'name' => $name,
				'option_value' => json_encode($params_array),
				'default' => 0,
				
			), 
			array( 
				'%s', 
				'%s',
				'%d',
			) 
		);
		if($save_or_no){
			?><div class="updated"><p><strong>Item Saved</strong></p></div><?php
		}
		else{
			?><div id="message" class="error"><p>Error please reinstall plugin</p></div> <?php
		}
	}
	
/*############  Update gallery ID function  ################*/
	
	private function update_gallery($id){
		global $wpdb;
		if(count($_POST)==0)
			return;
		$params_array=array();
		if(isset($_POST['wpda_gall_theme_name'])){
			$name=sanitize_text_field($_POST['wpda_gall_theme_name']);
		}else{
			$name="Gallery theme";
		}
		$params_array=array('name'=>sanitize_text_field($_POST['wpda_gall_theme_name']));
		foreach($this->options as $param_heading_key=>$param_heading_value){
			foreach($param_heading_value['params'] as $key=>$value){
				if(isset($_POST[$key])){
					$params_array[$key]=$_POST[$key];
				}else{
					$params_array[$key]=$value['default_value'];
				}
			}
		}		
		$wpdb->update( wpdevart_gallery_databese::$table_names['theme'], 
			array( 
				'name' => $name,
				'option_value' => json_encode($params_array),
			), 
			array( 
				'id'=>$id 
			),
			array( 
				'%s', 
				'%s'
			),
			array( 
				'%d'
			)  
		);
		
		?><div class="updated"><p><strong>Item Saved</strong></p></div><?php
		
	}
	
	/*###################### Remove theme function ##################*/
	
	private function remove_theme($id){
		global $wpdb;
		$default_theme = $wpdb->get_var($wpdb->prepare('SELECT `default` FROM ' . wpdevart_gallery_databese::$table_names['theme'].' WHERE id="%d"', $id));
		if (!$default_theme) {
			$wpdb->query($wpdb->prepare('DELETE FROM ' . wpdevart_gallery_databese::$table_names['theme'].' WHERE id="%d"', $id));
		}
		else{
			?><div id="message" class="error"><p>You cannot remove default theme</p></div> <?php
		}
	}

	/*###################### Set default Theme function ##################*/	
				
	private function set_default_theme($id){
		global $wpdb;
		$wpdb->update(wpdevart_gallery_databese::$table_names['theme'], array('default' => 0), array('default' => 1));
		$save = $wpdb->update(wpdevart_gallery_databese::$table_names['theme'], array('default' => 1), array('id' => $id));		
	}
	
    /*###################### Function for displaying the table list of the theme page ##################*/	
	
	private function display_table_list_theme(){
		
		
		?>
        <style>
        .description_row:nth-child(odd){
			background-color: #f9f9f9;
		}
		
        </style>
        <script> var my_table_list=<?php echo $this->generete_jsone_list(); ?></script>
        <div class="wrap">
			<div class="wpdevart_plugins_header div-for-clear">
				<div class="wpdevart_plugins_get_pro div-for-clear">
					<div class="wpdevart_plugins_get_pro_info">
						<h3>WpDevArt Gallery Premium</h3>
						<p>Powerful and Customizable Gallery</p>
					</div>
						<a target="blank" href="https://wpdevart.com/wordpress-gallery-plugin/" class="wpdevart_upgrade">Upgrade</a>
				</div>
				<a target="blank" href="<?php echo wpdevart_gallery_support_url; ?>" class="wpdevart_support">Have any Questions? Get a quick support!</a>
			</div>
            <form method="post"  action="" id="admin_form" name="admin_form" ng-app="" ng-controller="customersController">
				<h2> <span>Gallery Themes</span> <a href="admin.php?page=wpdevart_gallery_themes&task=add_wpda_galltheme" class="add-new-h2">Add New</a></h2>            
   
            <div class="tablenav top" style="width:95%">  
                <input type="text" placeholder="Search" ng-change="filtering_table();" ng-model="searchText">            
                <div class="tablenav-pages"><span class="displaying-num">{{filtering_table().length}} items</span>
                <span ng-show="(numberOfPages()-1)>=1">
                    <span class="pagination-links"><a class="first-page" ng-class="{disabled:(curPage < 1 )}" title="Go to the first page" ng-click="curPage=0">«</a>
                    <a class="prev-page" title="Go to the previous page" ng-class="{disabled:(curPage < 1 )}" ng-click="curPage=curPage-1; curect()">‹</a>
                    <span class="paging-input"><span class="total-pages">{{curPage + 1}}</span> of <span class="total-pages">{{ numberOfPages() }}</span></span>
                    <a class="next-page" title="Go to the next page" ng-class="{disabled:(curPage >= (numberOfPages() - 1))}" ng-click=" curPage=curPage+1; curect()">›</a>
                    <a class="last-page" title="Go to the last page" ng-class="{disabled:(curPage >= (numberOfPages() - 1))}" ng-click="curPage=numberOfPages()-1">»</a></span></div>
                </span>
            </div>
            <table class="wp-list-table widefat fixed pages" style="width:95%">
                <thead>
                    <tr>
                        <th style="width: 100px;" id='oreder_by_id' data-ng-click="order_by='id'; reverse=!reverse; ordering($event,order_by,reverse);" class="manage-column sortable desc" scope="col"><a><span>ID</span><span class="sorting-indicator"></span></a></th>
                        <th data-ng-click="order_by='name'; reverse=!reverse; ordering($event,order_by,reverse)" class="manage-column sortable desc"><a><span>Name</span><span class="sorting-indicator"></span></a></th>
                        <th style="width:100px"><a>Default</span></a></th>
                        <th style="width:80px">Edit</th>
                        <th  style="width:80px">Delete</th>
                    </tr>
                </thead>
                <tbody>
                 <tr ng-repeat="rows in names | filter:filtering_table" class="description_row">
                         <td>{{rows.id}}</td>
                         <td><a href="admin.php?page=wpdevart_gallery_themes&task=add_edit_theme&id={{rows.id}}">{{rows.name}}</a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_themes&task=set_default_theme&id={{rows.id}}"><img src="<?php echo wpdevart_gallery_plugin_url.'includes/admin/images/default' ?>{{rows.default}}.png"></a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_themes&task=add_edit_theme&id={{rows.id}}">Edit</a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_themes&task=remove_theme&id={{rows.id}}">Delete</a></td>
                               
                  </tr> 
                </tbody>
            </table>
        </form>
        </div>
    <script>

jQuery(document).ready(function(e) {
    jQuery('a.disabled').click(function(){return false});
	jQuery('form').on("keyup keypress", function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
			return false;
		}
	});
});
    function customersController($scope,$filter) {
		var orderBy = $filter('orderBy');
		$scope.previsu_search_result='';
		$scope.oredering=new Array();
		$scope.baza = my_table_list;
		$scope.curPage = 0;
		$scope.pageSize = 20;
		$scope.names=$scope.baza.slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
		$scope.numberOfPages = function(){
		   return Math.ceil($scope.filtering_table().length / $scope.pageSize);
	   };
	   $scope.filtering_table=function(){
		   var new_searched_date_array=new Array;
		   new_searched_date_array=[];
		   angular.forEach($scope.baza,function(value,key){
			   var catched=0;
			   angular.forEach(value,function(value_loc,key_loc){
				   if((''+value_loc).indexOf($scope.searchText)!=-1 || $scope.searchText=='' || typeof($scope.searchText) == 'undefined')
					  catched=1;
			   })
			  if(catched)
				  new_searched_date_array.push(value);
		   })
		   if($scope.previsu_search_result != $scope.searchText){
			  
			  $scope.previsu_search_result=$scope.searchText;
			   $scope.ordering($scope.oredering[0],$scope.oredering[1], $scope.oredering[2]);
			   
		   }
		   if(new_searched_date_array.length<=$scope.pageSize)
		   		$scope.curPage = 0;
		   return new_searched_date_array;
	   }
	   $scope.curect=function(){
		   if( $scope.curPage<0){
				$scope.curPage=0;
		   }
		   if( $scope.curPage> $scope.numberOfPages()-1)
			   $scope.curPage=$scope.numberOfPages()-1;
		  $scope.names=$scope.filtering_table().slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
	   }
		
		$scope.ordering=function($event,order_by,revers){
		   if( typeof($event) != 'undefined' && typeof($event.currentTarget) != 'undefined')
		   		element=$event.currentTarget;
			else
				element=jQuery();
		   
			if(revers)
			  indicator='asc'
			else
			  indicator='desc'
			 $scope.oredering[0]=$event;
			 $scope.oredering[1]=order_by;
			 $scope.oredering[2]=revers;
			jQuery(element).parent().find('.manage-column').removeClass('sortable desc asc sorted');
			jQuery(element).parent().find('.manage-column').not(element).addClass('sortable desc');
			jQuery(element).addClass('sorted '+indicator);		  
			$scope.names=orderBy($scope.filtering_table(),order_by,revers).slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
		}
	}
    </script>
		<?php
		$this->generete_jsone_list();
	}
	
    /*###################### Json list function ##################*/	
	
	private function generete_jsone_list(){
		global $wpdb;
		$query = "SELECT `id`,`name`,`default` FROM ".wpdevart_gallery_databese::$table_names['theme'];
		$rows=$wpdb->get_results($query);
		$json="[";
		$no_frst_storaket=1;
		foreach($rows as $row){
			$json.=(($no_frst_storaket) ? '' : ',' )."{";
			$no_frst_storaket=1;
			foreach($row as $key=>$value){
				if($key!='id'){
					$json.= "".(($no_frst_storaket) ? '' : ',' )."'".$key."':"."'".(($value)?preg_replace('/^\s+|\n|\r|\s+$/m', '',htmlspecialchars_decode(addslashes(strip_tags($value)))):'0')."'";				
				}
				else{					
					$json.= "".(($no_frst_storaket) ? '' : ',' )."'".$key."':".(($value)?htmlspecialchars_decode(addslashes($value)):'0'); 
				}
				
				$no_frst_storaket=0;
			 }			 
			 $json.="}";
		}
		$json.="]";
		return $json;
	}	

    /*###################### Generating the theme parameters ##################*/	
	
	private function generete_theme_parametrs($id=0){
		global $wpdb;		
		if($id){
			$theme_params = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.wpdevart_gallery_databese::$table_names['theme'].' WHERE id=%d',$id));	
		}else{
			$theme_params = $wpdb->get_row('SELECT * FROM '.wpdevart_gallery_databese::$table_names['theme'].' WHERE `default`=1');	
		}
		if($theme_params==NULL){
			foreach($this->options as $param_heading_key=>$param_heading_value){
				foreach($this->options as $key=>$value){
					$this->options[$param_heading_key]['params'][$key]["value"]=$this->options[$param_heading_key]['params'][$key]["default_value"];
				}
			}
		}else{
			$databases_parametrs=json_decode($theme_params->option_value, true);
			foreach($this->options as $param_heading_key=>$param_heading_value){
				foreach($param_heading_value['params'] as $key=>$value){
					if(isset($databases_parametrs[$key])){
						$this->options[$param_heading_key]['params'][$key]["value"]=$databases_parametrs[$key];
					}else{
						$this->options[$param_heading_key]['params'][$key]["value"]=$this->options[$param_heading_key]['params'][$key]["default_value"];
					}
				}
			}
			return $theme_params->name;
		}
	}
	
    /*###################### Add/Edit the theme function ##################*/	
	
	private function add_edit_theme($id=0){
		global $wpdb;
		$wpda_gallname=$this->generete_theme_parametrs($id);
		?>		         
		<form action="admin.php?page=wpdevart_gallery_themes<?php if($id) echo '&id='.$id; ?>" method="post" name="adminForm" class="top_description_table" id="adminForm">
            <div class="conteiner">
                <div class="header">
                    <span><h2 class="wpda_galltheme_title">Add Theme</h2></span>
                    <div class="header_action_buttons">
                        <span><input type="button" onclick="submitbutton('save_gallery')" value="Save" class="button-primary action"> </span> 
                        <span><input type="button" onclick="submitbutton('update_gallery')" value="Apply" class="button-primary action"> </span> 
                        <span><input type="button" onclick="window.location.href='admin.php?page=wpdevart_gallery_themes'" value="Cancel" class="button-secondary action"> </span> 
                    </div>
                </div>
                <div class="option_panel">            
                    <div class="parametr_name"></div>
                    <div class="all_options_panel">
                        <input type="text" class="wpda_gallname" name="wpda_gall_theme_name" placeholder="Enter name here" value="<?php echo isset($wpda_gallname)?$wpda_gallname:'' ?>" >
                        <div class="wpda_gall_theme_link_tabs">
							<?php
								echo "<ul>";
								foreach($this->options as $params_grup_name =>$params_group_value){ 
									echo '<li id="'.$params_grup_name.'_tab">'.$params_group_value['heading_name'].'</li>';
								}
								echo "</ul>";
							?>
						</div>
                        <table>
						<?php 
						foreach($this->options as $params_grup_name =>$params_group_value){ 
							wpdevart_gallery_library::create_table_heading($params_group_value['heading_name'],$params_grup_name);
							foreach($params_group_value['params'] as $param_name => $param_value){
								$args=array(
									"name"=>$param_name,
									"heading_name"=>$params_group_value['heading_name'],
									"heading_group"=>$params_grup_name,
								);
								$args=array_merge($args,$param_value);		
								$function_name=$param_value['function_name'];
								wpdevart_gallery_library::$function_name($args);
							}
						}

						?>
					</table>
                    </div>
                </div>
            </div>
		</form>
		<?php

		 
	}
	
	/*###################### Function for the border types ##################*/		
	
	private function border_types(){
		$border_type[ 'dotted' ] = 'dotted';
		$border_type[ 'dashed' ] = 'dashed';
		$border_type[ 'solid' ] = 'solid';
		$border_type[ 'double' ] = 'double';
		$border_type[ 'groove' ] = 'groove';
		$border_type[ 'ridge' ] = 'ridge';
		$border_type[ 'inset' ] = 'inset';	
		$border_type[ 'outset' ] = 'outset';
		return $border_type;
	}
	
    /*###################### Fonts function ##################*/		
	
	private function fonts_options(){
		  $font_choices[ 'Arial,Helvetica Neue,Helvetica,sans-serif' ] = 'Arial *';
		  $font_choices[ 'Arial Black,Arial Bold,Arial,sans-serif' ] = 'Arial Black *';
		  $font_choices[ 'Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif' ] = 'Arial Narrow *';
		  $font_choices[ 'Courier,Verdana,sans-serif' ] = 'Courier *';
		  $font_choices[ 'Georgia,Times New Roman,Times,serif' ] = 'Georgia *';
		  $font_choices[ 'Times New Roman,Times,Georgia,serif' ] = 'Times New Roman *';
		  $font_choices[ 'Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif' ] = 'Trebuchet MS *';
		  $font_choices[ 'Verdana,sans-serif' ] = 'Verdana *';
		  $font_choices[ 'American Typewriter,Georgia,serif' ] = 'American Typewriter';
		  $font_choices[ 'Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif' ] = 'Andale Mono';
		  $font_choices[ 'Baskerville,Times New Roman,Times,serif' ] = 'Baskerville';
		  $font_choices[ 'Bookman Old Style,Georgia,Times New Roman,Times,serif' ] = 'Bookman Old Style';
		  $font_choices[ 'Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif' ] = 'Calibri';
		  $font_choices[ 'Cambria,Georgia,Times New Roman,Times,serif' ] = 'Cambria';
		  $font_choices[ 'Candara,Verdana,sans-serif' ] = 'Candara';
		  $font_choices[ 'Century Gothic,Apple Gothic,Verdana,sans-serif' ] = 'Century Gothic';
		  $font_choices[ 'Century Schoolbook,Georgia,Times New Roman,Times,serif' ] = 'Century Schoolbook';
		  $font_choices[ 'Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif' ] = 'Consolas';
		  $font_choices[ 'Constantia,Georgia,Times New Roman,Times,serif' ] = 'Constantia';
		  $font_choices[ 'Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif' ] = 'Corbel';
		  $font_choices[ 'Franklin Gothic Medium,Arial,sans-serif' ] = 'Franklin Gothic Medium';
		  $font_choices[ 'Garamond,Hoefler Text,Times New Roman,Times,serif' ] = 'Garamond';
		  $font_choices[ 'Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif' ] = 'Gill Sans MT';
		  $font_choices[ 'Helvetica Neue,Helvetica,Arial,sans-serif' ] = 'Helvetica Neue';
		  $font_choices[ 'Hoefler Text,Garamond,Times New Roman,Times,sans-serif' ] = 'Hoefler Text';
		  $font_choices[ 'Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif' ] = 'Lucida Bright';
		  $font_choices[ 'Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif' ] = 'Lucida Grande';
		  $font_choices[ 'Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif' ] = 'Palatino Linotype';
		  $font_choices[ 'Tahoma,Geneva,Verdana,sans-serif' ] = 'Tahoma';
		  $font_choices[ 'Rockwell, Arial Black, Arial Bold, Arial, sans-serif' ] = 'Rockwell';
		  $font_choices[ 'Segoe UI' ] = 'Segoe UI';
		  return $font_choices;
	}
	
	/*###################### Function for the font width label ##################*/		
	
	private function select_font_with_label($select_name,$main_value='',$bind=''){
		?>
        
		<select class="wpda_gallselect" name="<?php echo 'parametrs['.$select_name.']'; ?>" id="<?php echo $select_name ?>" >
		<?php
		
		foreach($this->fonts_options() as $key => $value){
			?>
			<option <?php selected($key,$main_value) ?> value="<?php echo $key ?>" ><?php echo $value ?></option>
			<?php 					
		}
		?>
		</select>																

		<?php
	}
	
	/*###################### Function for the border width label ##################*/		
	
	private function select_border_with_label($select_name,$main_value='',$bind=''){
		?>
		<select class="wpda_gallselect" name="<?php echo 'parametrs['.$select_name.']'; ?>" id="<?php echo $select_name ?>" >
		<?php
		
		foreach($this->border_types() as $key => $value){
			?>
			<option <?php selected($key,$main_value) ?> value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php 					
		}
		?>
		</select>																

		<?php
	}
	
	/*###################### Function for the hex2rgba ##################*/	
	
	private function hex2rgba($color, $opacity = false) {

		$default = 'rgba(0,0,0,1)';
		$opacity=$opacity/100;
		if(empty($color))
			  return $default; 
			if ($color[0] == '#' ) {
				$color = substr( $color, 1 );
			}	
			if (strlen($color) == 6) {
					$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
					$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
					return $default;
			}
			$rgb =  array_map('hexdec', $hex);
			if($opacity){
				if(abs($opacity) > 1)
					$opacity = 1.0;
				$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
			} else {
				$output = 'rgb('.implode(",",$rgb).')';
			}
			return $output;
	}
	
}


 ?>
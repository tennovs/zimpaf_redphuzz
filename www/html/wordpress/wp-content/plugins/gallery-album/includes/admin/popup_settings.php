<?php
class wpda_gall_popup_themes{
	private $options;
	function __construct(){
		
		$this->options=self::return_params_array();	
		$this->controller_page();
		
	}

    /*###################### Return parameters function ##################*/		
	
	public static function return_params_array(){
		return array(
			"popup_general_settings"=>array(
				"heading_name"=>"General",
				"params"=>array(
					
				"wpdeva_gall_popup_base_cornice"=>array(
						"title"=>"Distance of the Popup from the screen",
						"description"=>"Type the distance of the popup from the screen",
						"default_value"=>"20",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
                        "pro"=>true,						
					),			
					"wpdeva_gall_popup_start_rotate"=>array(
						"title"=>"Popup rotation when its started",
						"description"=>"Type here the Popup rotation degree when its started",
						"default_value"=>"0",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(deg)",
                        "pro"=>true,
					),
					"wpdeva_gall_popup_position"=>array(
						"title"=>"Popup Position",
						"description"=>"Choose the popup Position",
						"values"=>array("fixed"=>"Fixed","absolute"=>"Absolute"),
						"default_value"=>"fixed",
						"function_name"=>"simple_select",
					),	
					"wpdeva_gall_start_popup_location"=>array(
						"title"=>"Popup location at its started",
						"description"=>"Choose the Popup location at its started",
						"values"=>array("0"=>"Image","1"=>"Left top","2"=>"Middle top","3"=>"Right top","4"=>"Left middle","5"=>"Middle middle","6"=>"Middle right","7"=>"Left bottom","8"=>"Middle bottom","9"=>"Right bottom","9"=>"All","10"=>"All"),
						"default_value"=>"0",
						"function_name"=>"simple_select",
                        "pro"=>true,
					),	
					"wpdeva_gall_popup_location"=>array(
						"title"=>"Popup location",
						"description"=>" Choose the popup location ",
						"values"=>array("1"=>"Left top","2"=>"Middle top","3"=>"Right top","4"=>"Left middle","5"=>"Middle middle","6"=>"Middle right","7"=>"Left bottom","8"=>"Middle bottom","9"=>"Right bottom","10"=>"All"),
						"default_value"=>"5",
						"function_name"=>"simple_select",
                        "pro"=>true,
					),
					"wpdeva_gall_popup_brd_width"=>array(
						"title"=>"Popup border width",
						"description"=>"Type the popup border width",
						"default_value"=>"5",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",						
					),
					"wpdeva_gall_popup_brd_color"=>array(
						"title"=>"Popup border color",
						"description"=>"Select the popup border color",
						"default_value"=>"#0085ba",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_popup_brd_opasity"=>array(
						"title"=>"Popup border color opacity",
						"description"=>"Select the popup border color opacity",
						"default_value"=>"100",
						"function_name"=>"range_input",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_popup_brd_radius"=>array(
						"title"=>"Popup border radius",
						"description"=>"Type the popup border radius",
						"default_value"=>"1",
						"function_name"=>"simple_input",
						"type"=>"number",												
					),
					"wpdeva_gall_pixel"=>array(
						"title"=>"Popup radius metric type",
						"description"=>"Select the popup radius metric type",
						"values"=>array("px"=>"Pixels","%"=>"Pracents","em"=>"Em","cm"=>"Cantimetrs"),
						"default_value"=>"%",
						"function_name"=>"simple_select",
					),
					"wpdeva_gall_popup_min_width"=>array(
						"title"=>"Minimum Popup width",
						"description"=>"Type the minimum Popup width",
						"default_value"=>"400",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",						
					),
					"wpdeva_gall_popup_start_width"=>array(
						"title"=>"Popup width at its starting",
						"description"=>"Type the width of the popup window when starting",
						"default_value"=>"200",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",						
					),
					"wpdeva_gall_close_location"=>array(
						"title"=>"Popup size at its start and close",
						"description"=>"Select the size of the Popup when you start and close its window",
						"values"=>array("0"=>"image thumbnail size","1"=>"popup start size"),
						"default_value"=>"0",
						"function_name"=>"simple_select",
                        "pro"=>true,
					),
					"wpdeva_gall_loading_img"=>array(
						"title"=>"Popup loading image",
						"description"=>" Choose the popup loading image ",
						"values"=>array("load1.gif"=>"load1","load2.gif"=>"load2","load3.gif"=>"load3","load4.gif"=>"load4","load5.gif"=>"load5","load6.gif"=>"load6","load7.gif"=>"load7","load8.gif"=>"load8","load9.gif"=>"load9","load10.gif"=>"load10","load11.gif"=>"load11","load12.gif"=>"load12","load13.gif"=>"load13","load14.gif"=>"load14","load15.gif"=>"load15","load16.gif"=>"load16","load17.gif"=>"load17","load18.gif"=>"load18","load19.gif"=>"load19","load20.gif"=>"load20","load21.gif"=>"load21","load22.gif"=>"load22"),
						"default_value"=>"load1.gif",
						"function_name"=>"simple_select"
					),
					/*12-09-2017*/
					"wpdeva_gall_video"=>array(
						"title"=>"Video display",
						"description"=>"Choose video display format",
						"values"=>array("video_"=>"video","image__"=>"image"),
						"default_value"=>"image__",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					/*10-10-2018*/
					"wpdeva_gall_popup_cover_box"=>array(
						"title"=>"Popup cover box",
						"description"=>"Choose Popup cover box fixed or dynamic",
						"values"=>array("1"=>"fixed","0"=>"dynamic"),
						"default_value"=>"0",
						"function_name"=>"simple_select",						
					),
											
					"wpdeva_gall_popup_cover_box_width"=>array(
						"title"=>"Popup fixed cover box width",
						"description"=>"Type the Popup fixed cover box width",
						"default_value"=>"800",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",						
					),
					
					"wpdeva_gall_popup_cover_box_height"=>array(
						"title"=>"Popup fixed cover box height",
						"description"=>"Type the Popup fixed cover box height",
						"default_value"=>"600",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",						
					),																					
				),
			),
			"popup_description_settings"=>array(
				"heading_name"=>"Description panel",
				"params"=>array(
							
					"wpdeva_gall_image_description_bg_color"=>array(
						"title"=>"Description panel background color",
						"description"=>"Set the description panel background color",
						"default_value"=>"#FFFFFF",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_image_description_bg_color_hover"=>array(
						"title"=>"Description panel background color <span style='color:blue'>on hover</span>",
						"description"=>"Set the description panel on hover background color",
						"default_value"=>"#FFFFFF",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_image_description_bg_color_opacity"=>array(
						"title"=>"Description panel opacity",
						"description"=>"Set the description panel opacity",
						"default_value"=>"100",
						"function_name"=>"range_input",
						"small_text"=>"(%)",						
					),			
					"wpdeva_gall_image_description_bg_color_opacity_hover"=>array(
						"title"=>"Description panel opacity <span style='color:blue'>on hover</span>",
						"description"=>"Set the description panel on hover opacity",
						"default_value"=>"100",
						"function_name"=>"range_input",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_image_description_text_color"=>array(
						"title"=>"Description panel text color",
						"description"=>"Set the text color of the description panel",
						"default_value"=>"#000000",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_image_description_text_color_hover"=>array(
						"title"=>"Description panel text color <span style='color:blue'>on hover</span>",
						"description"=>"Set the on hover text color of the description panel",
						"default_value"=>"#000000",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_image_description_distacne_top"=>array(
						"title"=>"Description panel text distance from top",
						"description"=>"Type the text distance of the description panel from the top",
						"default_value"=>"15",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",								
					),
					"wpdeva_gall_image_description_distacne_left_right"=>array(
						"title"=>"Text distance from left and right side",
						"description"=>"Type the text distance of the description panel from the left and right side",
						"default_value"=>"10",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",								
					),
					"wpdeva_gall_image_description_font_size"=>array(
						"title"=>"Description panel text font size",
						"description"=>"Type the text font size of the description panel",
						"default_value"=>"18",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",									
					),					
				)
			),
			"popup_icons_settings"=>array(
				"heading_name"=>"Icons general settings",
				"params"=>array(
					"wpdeva_gall_icons_inBar_height"=>array(
						"title"=>"Control bar height",
						"description"=>"Type the control bar height",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",						
					),
					"wpdeva_gall_icons_distance"=>array(
						"title"=>"Distance between control bar icons",
						"description"=>"Type distance between control bar icons",
						"default_value"=>"15",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",						
					),
					"wpdeva_gall_icons_inBar_yes_no"=>array(
						"title"=>"Control bar display settings",
						"description"=>"Choose when to show or hide the control bar",
						"values"=>array("02"=>"Always hide","1"=>"Always show","0"=>"Show when mouse near to the bar","01"=>"Show when mouse inside Popup"),
						"default_value"=>"1",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_outBar_icons_center"=>array(
						"title"=>"Control bar icons position",
						"description"=>"Select control bar icons position",
						"values"=>array("0"=>"Center","1"=>"Left right"),
						"default_value"=>"1",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
			
					"wpdeva_gall_icons_in_out"=>array(
						"title"=>"Control bar position",
						"description"=>"Select the control bar position",
						"values"=>array("in"=>"Inside popup","out"=>"Outside popup"),
						"default_value"=>"in",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_icons_top_bottom"=>array(
						"title"=>"Control bar vertical position",
						"description"=>"Choose the control bar vertical position",
						"values"=>array("0"=>"Bottom","1"=>"Top"),
						"default_value"=>"0",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_popup_bg_color"=>array(
						"title"=>"Control bar background color",
						"description"=>"Select the control bar background color",
						"default_value"=>"#ffffff",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_icons_color"=>array(
						"title"=>"Control bar icons color",
						"description"=>"Select the control bar icons color",
						"default_value"=>"#0085ba",
						"function_name"=>"color_input",
						"pro"=>true,
					),
					"wpdeva_gall_icons_hover_color"=>array(
						"title"=>"Control bar icons color <span style='color:blue'>on hover</span> ",
						"description"=>"Select the control bar icons on hover color",
						"default_value"=>"#006799",
						"function_name"=>"color_input",
						"pro"=>true,
					),					
					"wpdeva_gall_icons_scale_hover"=>array(
						"title"=>"Control bar icons size changing <span style='color:blue'>on hover</span>",
						"description"=>"Type here how to zoom in or zoom out icons on hover",
						"default_value"=>"100",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(100/100)",
						"pro"=>true,
					),					
				)
			),
			
			"popup_select_icons_settings"=>array(
				"heading_name"=>"Control bar icons",
				"params"=>array(
					// left right
					"wpdeva_gall_right_icon_relative_font_size"=>array(
						"title"=>"Previous and Next image icons size",
						"description"=>"Type the Previous and Next image icons size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_play_icon_relative_font_size"=>array(
						"title"=>"Play and Pause icon size",
						"description"=>"Type the Play and Pause icon size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_count_icon"=>array(
						"title"=>"Counter separating symbol",
						"description"=>"Type the counter separating symbol",
						"default_value"=>"/",
						"function_name"=>"simple_input",						
					),	
					"wpdeva_gall_count_icon_relative_font_size"=>array(
						"title"=>"Counter separating symbol size",
						"description"=>"Type the counter separating symbol size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_full_icon_relative_font_size"=>array(
						"title"=>"Full and small size images icon size",
						"description"=>"Type the full and small size images icon size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_setting_icon_relative_font_size"=>array(
						"title"=>"Image description icon size",
						"description"=>"Type the image description icon size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),
					"wpdeva_gall_close_icon_relative_font_size"=>array(
						"title"=>"Close icon size",
						"description"=>"Type the close icon size",
						"default_value"=>"80",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),					
					"wpdeva_gall_right_icon"=>array(
						"title"=>"Previous and Next image icons",
						"description"=>"Choose the Previous and Next image icons",
						"values"=>array(
							"fa fa-arrow-"=>"&#xf060; &#xf061;",
							"fa fa-angle-double-"=>"&#xf100; &#xf101;",
							"fa fa-angle-"=>"&#xf104; &#xf105;",
							"fa fa-chevron-"=>"&#xf053; &#xf054;",							
							"fa fa-arrow-circle-"=>"&#xf0a8; &#xf0a9;",
							"fa fa-arrow-circle-o-"=>"&#xf190; &#xf18e;",
							"fa fa-caret-square-o-"=>"&#xf191; &#xf152;",
							"fa fa-chevron-circle-"=>"&#xf137; &#xf138;",
							"fa fa-hand-o-"=>"&#xf0a5; &#xf0a4;",
							"fa fa-long-arrow-"=>"&#xf177; &#xf178;",
							"wpdeva_gall_display_none"=>"Disable",
						),
						"default_value"=>"fa fa-chevron-circle-",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					// play stop
					"wpdeva_gall_play_icon"=>array(
						"title"=>"Play icon",
						"description"=>"Choose the play icon",
						"values"=>array(
							"fa fa-play"=>"&#xf04b;",
							"fa fa-play-circle"=>"&#xf144;",
							"fa fa-play-circle-o"=>"&#xf01d;",
							"fa fa-youtube-play"=>"&#xf16a;",
							"wpdeva_gall_display_none"=>"Disable",			
						),
						"default_value"=>"fa fa-play",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_stop_icon"=>array(
						"title"=>"Pause icon",
						"description"=>"Choose the pause icon",
						"values"=>array(
							"fa fa-pause"=>"&#xf04c;",
							"fa fa-pause-circle"=>"&#xf28b;",
							"fa fa-pause-circle-o"=>"&#xf28c;",
							"fa fa-stop"=>"&#xf04d;",
							"fa fa-stop-circle"=>"&#xf28d;",
							"fa fa-stop-circle-o"=>"&#xf28e;",
							"fa fa-hand-paper-o"=>"&#xf256;",
							"wpdeva_gall_display_none"=>"Disable",			
						),
						"default_value"=>"fa fa-pause",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
						
					"wpdeva_gall_full_icon"=>array(
						"title"=>"Full and small size images icon",
						"description"=>"Choose the full and small size images icon",
						"values"=>array(
							"fa fa-compress"=>array("&#xf065; &#xf066;",''),
							"material-icons"=>array("fullscreen fullscreen_exit","material-icons"),
							"wpdeva_gall_display_none"=>array("Disable","arial"),
						),
						"default_value"=>"fa fa-compress",
						"function_name"=>"simple_select_extend_font_size",
						"pro"=>true,
					),
						
					// go to load icon
					"wpdeva_gall_load_icon"=>array(
						"title"=>"Image load icon",
						"description"=>"Choose the image load icon",
						"values"=>array(
							"fa fa-download"=>"&#xf019;",
							"fa fa-cloud-download"=>"&#xf0ed;",							
							"wpdeva_gall_display_none"=>"Disable",
						),
						"default_value"=>"fa fa-download",
						"function_name"=>"simple_select",
					),
					"wpdeva_gall_load_icon_relative_font_size"=>array(
						"title"=>"Image load icon size",
						"description"=>"Type the image load icon size",
						"default_value"=>"50",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(%)",						
					),	
										
					// go to description icon
					"wpdeva_gall_setting_icon"=>array(
						"title"=>"Image description icon",
						"description"=>"Choose the image description icon",
						"values"=>array(
							"fa fa-commenting-o"=>"&#xf27b;",
							"fa fa-file-text"=>"&#xf15c;",
							"fa fa-reorder"=>"&#xf0c9;",	
							"wpdeva_gall_display_none"=>"Disable",			
						),
						"default_value"=>"fa fa-file-text",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
				
					// close
					"wpdeva_gall_close_icon"=>array(
						"title"=>"Close icon",
						"description"=>"Choose the close icon",
						"values"=>array(
							"fa fa-close"=>"&#xf00d;",
							"fa fa-times-circle"=>"&#xf057;",
							"fa fa-times-circle-o"=>"&#xf05c;",
							"fa fa-remove"=>"&#xf00d;",
							"wpdeva_gall_display_none"=>"Disable",
						),
						"default_value"=>"fa fa-close",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					
				)
			),

			"popup_window_navigation_settings"=>array(
				"heading_name"=>"Outside Icons conteiner",
				"params"=>array(
					"wpdeva_gall_icons_outBar_distance_from_html"=>array(
						"title"=>"Outside Icons distance from screen",
						"description"=>"Type here outside Icons distance from screen",
						"default_value"=>"0",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",
						"pro"=>true,
					),
					"wpdeva_gall_icons_outBar_bg_color"=>array(
						"title"=>"Outside Icons panel background color",
						"description"=>"Select outside Icons panel background color",
						"default_value"=>"#000000",
						"function_name"=>"color_input",	
						"pro"=>true,
					),	
					"wpdeva_gall_icons_outBar_bg_opasaty"=>array(
						"title"=>"Outside Icons panel background opacity",
						"description"=>"Select outside Icons panel background opacity",
						"default_value"=>"0",
						"function_name"=>"range_input",
						"small_text"=>"(%)",	
						"pro"=>true,
					),	
					"wpdeva_gall_icons_outBar_brd_radius"=>array(
						"title"=>"Outside Icons panel border radius",
						"description"=>"Type outside Icons panel border radius",
						"default_value"=>"0",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(Px)",	
						"pro"=>true,
					),
				)
			),
			"popup_overlay_settings"=>array(
				"heading_name"=>"Overlay",
				"params"=>array(
					"wpdeva_gall_overlay_bg_color"=>array(
						"title"=>"Overlay background color",
						"description"=>"Set the background color of the overlay",
						"default_value"=>"#000000",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_overlay_opacity"=>array(
						"title"=>"Overlay opacity",
						"description"=>"Set the opacity of the overlay",
						"default_value"=>"20",
						"function_name"=>"range_input",
						"small_text"=>"(%)",						
					),				
				)
			),
			"popup_animation_settings"=>array(
				"heading_name"=>"Slide effects",
				"params"=>array(
					"wpdeva_gall_pause_duration"=>array(
						"title"=>"Pause time",
						"description"=>"Type pause time",
						"default_value"=>"5000",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(ms)",						
					),
					"wpdeva_gall_slide_delay"=>array(
						"title"=>"When start the slide effect",
						"description"=>"Choose when the slide effect should start",
						"values"=>array(
							"0"=>"With Image resizing",
							"1"=>"After Image resizing"
						),
						"default_value"=>"0",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_slide_show_effect"=>array(
						"title"=>"Slide type",
						"description"=>"Select the slide effect type",
						"values"=>array(
							"0"=>"Canvas",
							"1"=>"Standard",
							"2"=>"None"	
						),
						"default_value"=>"0",
						"function_name"=>"simple_select",
						"pro"=>true,
					),	
					"wpdeva_gall_slide_show_effect_standart"=>array(
						"title"=>"Standard effects list",
						"description"=>"Choose what standard effects to use",
						"values"=>array(
							"0"=>"From bottom to top",
							"5"=>"Scale & From bottom to top",										
							"1"=>"From right to left",
							"6"=>"Scale & From right to left",							
							"2"=>"From left to right",
							"7"=>"Scale & From left to right",
							"4"=>"From top to bottom",														
							"8"=>"Scale effect & Opacity",							
							"3"=>"Opacity effect",
							"9"=>"Scale & From top to bottom",
							"10"=>"FROM left to right",
							"11"=>"FROM right to left",
							"12"=>"FROM botom to top",
							"13"=>"FROM top to bottom",	
						),
						"default_value"=>array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10","11"=>"11","12"=>"12","13"=>"13"),
						"function_name"=>"simple_checkbox",
						"pro"=>true,
					),
					"wpdeva_gall_slide_show_effect_canvas"=>array(
						"title"=>"Canvas effects list",
						"description"=>"Choose what Canvas effects to use",
						"values"=>array(
							"0"=>"Effect 0",
							"1"=>"Effect 1",
							"2"=>"Effect 2",
							"3"=>"Effect 3",
							"4"=>"Effect 4",
							"5"=>"Effect 5",
							"6"=>"Effect 6",
							"7"=>"Effect 7",
							"8"=>"Effect 8",
							"9"=>"Effect 9",
							"10"=>"Effect 10",
							"11"=>"Effect 11",
							"12"=>"Effect 12",
							"13"=>"Effect 13",
							"14"=>"Effect 14",
							"15"=>"Effect 15",
							"16"=>"Effect 16",
							"17"=>"Effect 17",
							"18"=>"Effect 18",
							"19"=>"Effect 19",
							"20"=>"Effect 20",
							"21"=>"Effect 21",
							"22"=>"Effect 22",
							"23"=>"Effect 23",
							"24"=>"Effect 24",
							"25"=>"Effect 25",
							"26"=>"Effect 26",
							"27"=>"Effect 27",
							"28"=>"Effect 28",
						),
						"default_value"=>array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14","15"=>"15","16"=>"16","17"=>"17","18"=>"18","19"=>"19","20"=>"20","21"=>"21","22"=>"22","23"=>"23","24"=>"24","25"=>"25","26"=>"26","27"=>"27",),
						"type"=>"multiple",
						"function_name"=>"simple_checkbox",
						"pro"=>true,
					),		
					"wpdeva_gall_transition_duration"=>array(
						"title"=>"Slide animation time",
						"description"=>"Type slide animation time",
						"default_value"=>"700",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(ms)",	
						"pro"=>true,
					),
					
				)
			),
			"popup_select_outside_icons_settings"=>array(
				"heading_name"=>"Popup Previous and Next icons",
				"params"=>array(
					// left right
					"wpdeva_gall_popup_right_icon"=>array(
						"title"=>"Popup Previous and Next icons",
						"description"=>"Select the Previous and Next icons for the Popup",
						"values"=>array(
							"fa fa-arrow-"=>"&#xf060; &#xf061;",
							"fa fa-angle-double-"=>"&#xf100; &#xf101;",
							"fa fa-angle-"=>"&#xf104; &#xf105;",
							"fa fa-chevron-"=>"&#xf053; &#xf054;",							
							"fa fa-arrow-circle-"=>"&#xf0a8; &#xf0a9;",
							"fa fa-arrow-circle-o-"=>"&#xf190; &#xf18e;",
							"fa fa-caret-square-o-"=>"&#xf191; &#xf152;",
							"fa fa-chevron-circle-"=>"&#xf137; &#xf138;",
							"fa fa-hand-o-"=>"&#xf0a5; &#xf0a4;",
							"fa fa-long-arrow-"=>"&#xf177; &#xf178;",
							"wpdeva_gall_display_none"=>"Disable",
						),
						"default_value"=>"fa fa-angle-double-",
						"function_name"=>"simple_select",
					),
					"wpdeva_gall_popup_icons_color"=>array(
						"title"=>"Previous and Next icons color",
						"description"=>"Selcet Previous and Next icons color",
						"default_value"=>"#006799",
						"function_name"=>"color_input",									
					),
					"wpdeva_gall_popup_right_icon_font_size"=>array(
						"title"=>"Previous and Next icons size",
						"description"=>"Type the Previous and Next icons size",
						"default_value"=>"36",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",						
					),
					"wpdeva_gall_popup_icon_distance"=>array(
						"title"=>"Previous and Next icons position",
						"description"=>"Type the distance for Previous and Next icons from popup border",
						"default_value"=>"5",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",						
					),					
				)
			),
			"popup_thumbanils_line_settings"=>array(
				"heading_name"=>"Popup thumbnail line",
				"params"=>array(
					// left right
					"wpdeva_gall_thumbanils_line_visibility"=>array(
						"title"=>"Show popup thumbnail line",
						"description"=>"Choose to show or hide popup thumbnail line",
						"values"=>array("visible"=>'Show',"hidden"=>'hide'),
						"default_value"=>"visible",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_top"=>array(
						"title"=>"Distance between control bar icons and thumbnail line",
						"description"=>"Type the distance between control bar icons and thumbnail line",
						"default_value"=>"0",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_width"=>array(
						"title"=>"Popup thumbnail line width",
						"description"=>"Select the thumbnail line width",
						"default_value"=>"95",
						"function_name"=>"range_input",
						"small_text"=>"(%)",
						"pro"=>true,
					),
					"wpdeva_gall_scrubber_height"=>array(
						"title"=>"Active thumbnail line height",
						"description"=>"Type the active thumbnail image line height",
						"default_value"=>"2",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_bg_color"=>array(
						"title"=>"Line color of the popup thumbnail",
						"description"=>"Select the popup thumbnail line color",
						"default_value"=>"#ffffff",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_scrubber_bg_color"=>array(
						"title"=>"Line color of the active image",
						"description"=>"Select the active image line color",
						"default_value"=>"#559dba",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_button_bg_color_click"=>array(
						"title"=>"Line color of the viewed image",
						"description"=>"Select the viewed image line color",
						"default_value"=>"#e2b7b7",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_button_bg_color_hover"=>array(
						"title"=>"Line color of the hovered image",
						"description"=>"Select the hovered image line color",
						"default_value"=>"#1e73be",
						"function_name"=>"color_input",	
						"pro"=>true,
					)											
				)
			),
			"popup_thumbanils_image_settings"=>array(
				"heading_name"=>"Popup thumbnail images",
				"params"=>array(
					// left right
					"wpdeva_gall_progress_bar_screen_width"=>array(
						"title"=>"Width",
						"description"=>"Type the width of the thumbnail image",
						"default_value"=>"150",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",	
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_height"=>array(
						"title"=>"Height",
						"description"=>"Type the height of the thumbnail image",
						"default_value"=>"100",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_brd_width"=>array(
						"title"=>"Thumbnail border",
						"description"=>"Type the border size of the Popup thumbnail",
						"default_value"=>"3",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_bottom"=>array(
						"title"=>"Type the distance between the thumbnail line and thumbnail images",
						"description"=>"Type here distance between the thumbnail line(or control bar) and thumbnail images",
						"default_value"=>"10",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_brd_color"=>array(
						"title"=>"Set the thumbnail border color",
						"description"=>"Type the Popup thumbnail border color",
						"default_value"=>"#559dba",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_bg_color"=>array(
						"title"=>"Set the background color of the residual space",
						"description"=>"Select the background color of Thumbnail image residual space",
						"default_value"=>"#000000",
						"function_name"=>"color_input",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_screen_opacity"=>array(
						"title"=>"Thumbnail opacity",
						"description"=>"Set the opacity of the Popup thumbnail",
						"default_value"=>"80",
						"function_name"=>"range_input",
						"small_text"=>"(%)",
						"pro"=>true,
					),
					
				)
			),
			"popup_thumbnail_description_settings"=>array(
				"heading_name"=>"Thumbnail image counter",
				"params"=>array(
					"wpdeva_gall_progress_bar_count_screen_visibility"=>array(
						"title"=>"Show image counter",
						"description"=>"Choose to show or hide the image counter",
						"values"=>array("visible"=>'Show',"hidden"=>'hide'),
						"default_value"=>"visible",
						"function_name"=>"simple_select",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_count_screen_width"=>array(
						"title"=>"Counter width",
						"description"=>"Type the counter width",
						"default_value"=>"40",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_count_screen_height"=>array(
						"title"=>"Counter height",
						"description"=>"Type the counter height",
						"default_value"=>"30",
						"function_name"=>"simple_input",
						"type"=>"number",
						"small_text"=>"(px)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_count_screen_bg_color"=>array(
						"title"=>"Counter background color",
						"description"=>"Set the counter background color",
						"default_value"=>"#5e5e5e",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_count_screen_opacity"=>array(
						"title"=>"Counter opacity",
						"description"=>"Set the counter opacity",
						"default_value"=>"50",
						"function_name"=>"range_input",
						"small_text"=>"(%)",
						"pro"=>true,
					),
					"wpdeva_gall_progress_bar_count_screen_color"=>array(
						"title"=>"Text color of numbers",
						"description"=>"Set the color of the numbers",
						"default_value"=>"#FFFFFF",
						"function_name"=>"color_input",	
						"pro"=>true,
					),
					
				)
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
			$this->add_edit_popup_theme($id);
			break;
			
		case 'add_edit_popup_theme':	
			$this->add_edit_popup_theme($id);
			break;
		
		case 'save_gallery':		
			if($id)	
				$this->update_gallery($id);
			else
				$this->save_gallery();
				
			$this->display_table_list_popup_theme();	
			break;
			
			
		case 'update_gallery':		
			if($id){
				$this->update_gallery($id);
			}else{
				$this->save_gallery();
				$_GET['id']=$wpdb->get_var("SELECT MAX(id) FROM ".wpdevart_gallery_databese::$table_names['popup_theme']);
				$id=$_GET['id'];
			}
			$this->add_edit_popup_theme($id);
			break;
		case 'set_default_popup_theme':
			$this->set_default_popup_theme($id);
			$this->display_table_list_popup_theme();	
		break;
		case 'remove_popup_theme':	
			$this->remove_popup_theme($id);
			$this->display_table_list_popup_theme();
			break;
				
		default:
			$this->display_table_list_popup_theme();
		}
	}
	
/*############  Save function  ################*/
	
	private function save_gallery(){
		global $wpdb;
		if(count($_POST)==0)
			return;		
		$params_array=array();
		if(isset($_POST['wpda_gall_popup_theme_name'])){
			$name=sanitize_text_field($_POST['wpda_gall_popup_theme_name']);
		}else{
			$name="Gallery theme";
		}
		$params_array=array('name'=>sanitize_text_field($_POST['wpda_gall_popup_theme_name']));
		foreach($this->options as $param_heading_key=>$param_heading_value){
			foreach($param_heading_value['params'] as $key=>$value){
				if(isset($_POST[$key])){
					$params_array[$key]=$_POST[$key];
				}else{
					$params_array[$key]=$value['default_value'];
				}
			}
		}	
		$save_or_no=$wpdb->insert( wpdevart_gallery_databese::$table_names['popup_theme'], 
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
		if(isset($_POST['wpda_gall_popup_theme_name'])){
			$name=sanitize_text_field($_POST['wpda_gall_popup_theme_name']);
		}else{
			$name="Gallery theme";
		}
		$params_array=array('name'=>sanitize_text_field($_POST['wpda_gall_popup_theme_name']));
		foreach($this->options as $param_heading_key=>$param_heading_value){
			foreach($param_heading_value['params'] as $key=>$value){
				if(isset($_POST[$key])){
					$params_array[$key]=$_POST[$key];
				}else{
					$params_array[$key]=$value['default_value'];
				}
			}
		}		
		$wpdb->update( wpdevart_gallery_databese::$table_names['popup_theme'], 
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
	
	/*###################### Remove popup theme function ##################*/	
	
	private function remove_popup_theme($id){
		global $wpdb;
		$default_popup_theme = $wpdb->get_var($wpdb->prepare('SELECT `default` FROM ' . wpdevart_gallery_databese::$table_names['popup_theme'].' WHERE id="%d"', $id));
		if (!$default_popup_theme) {
			$wpdb->query($wpdb->prepare('DELETE FROM ' . wpdevart_gallery_databese::$table_names['popup_theme'].' WHERE id="%d"', $id));
		}
		else{
			?><div id="message" class="error"><p>You cannot remove default theme</p></div> <?php
		}
	}

	/*###################### Default popup theme function ##################*/
	
	private function set_default_popup_theme($id){
		global $wpdb;
		$wpdb->update(wpdevart_gallery_databese::$table_names['popup_theme'], array('default' => 0), array('default' => 1));
		$save = $wpdb->update(wpdevart_gallery_databese::$table_names['popup_theme'], array('default' => 1), array('id' => $id));		
	}
	
    /*###################### Table list function ##################*/	
	
	private function display_table_list_popup_theme(){
		
		
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
			<h2>Gallery Popup Themes <a href="admin.php?page=wpdevart_gallery_popup&task=add_wpda_galltheme" class="add-new-h2">Add New</a></h2>            
   
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
                         <td><a href="admin.php?page=wpdevart_gallery_popup&task=add_edit_popup_theme&id={{rows.id}}">{{rows.name}}</a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_popup&task=set_default_popup_theme&id={{rows.id}}"><img src="<?php echo wpdevart_gallery_plugin_url.'includes/admin/images/default' ?>{{rows.default}}.png"></a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_popup&task=add_edit_popup_theme&id={{rows.id}}">Edit</a></td>
                         <td><a href="admin.php?page=wpdevart_gallery_popup&task=remove_popup_theme&id={{rows.id}}">Delete</a></td>
                               
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
	
    /*###################### Generate Jsone list function ##################*/		
	
	private function generete_jsone_list(){
		global $wpdb;
		$query = "SELECT `id`,`name`,`default` FROM ".wpdevart_gallery_databese::$table_names['popup_theme'];
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
	
    /*###################### Generating the popup themes parameters ##################*/		
	
	private function generete_popup_theme_parametrs($id=0){
		global $wpdb;		
		if($id){
			$popup_theme_params = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.wpdevart_gallery_databese::$table_names['popup_theme'].' WHERE id=%d',$id));	
		}else{
			
			$popup_theme_params = $wpdb->get_row('SELECT * FROM '.wpdevart_gallery_databese::$table_names['popup_theme'].' WHERE `default`=1');	
			$popup_theme_params->name='';
		}
		if($popup_theme_params==NULL){
			foreach($this->options as $param_heading_key=>$param_heading_value){
				foreach($param_heading_value['params'] as $key=>$value){					
						$this->options[$param_heading_key]['params'][$key]["value"]=$this->options[$param_heading_key]['params'][$key]["default_value"];
				}
			}
		}else{
			$databases_parametrs=json_decode($popup_theme_params->option_value, true);
			foreach($this->options as $param_heading_key=>$param_heading_value){
				foreach($param_heading_value['params'] as $key=>$value){
					if(isset($databases_parametrs[$key])){
						$this->options[$param_heading_key]['params'][$key]["value"]=$databases_parametrs[$key];
					}else{
						$this->options[$param_heading_key]['params'][$key]["value"]=$this->options[$param_heading_key]['params'][$key]["default_value"];
					}
				}
			}
			return $popup_theme_params->name;
		}
	}
	
	/*###################### Add/Edit popup theme function ##################*/
	
	private function add_edit_popup_theme($id=0){
		global $wpdb;
		$wpda_gallname=$this->generete_popup_theme_parametrs($id);
		?>       
		<div class="wpda_gall_head">
			<h2 style="display: inline-block"><span>Popup settings</span></h2>				 
			<div class="header_action_buttons">
				<span><input type="button" onclick="submitbutton('save_gallery')" value="Save" class="button-primary action"> </span> 
				<span><input type="button" onclick="submitbutton('update_gallery')" value="Apply" class="button-primary action"> </span> 
				<span><input type="button" onclick="window.location.href='admin.php?page=wpdevart_gallery_popup'" value="Cancel" class="button-secondary action"> </span> 
			</div>            
		</div> 
		<form id="adminForm" action="admin.php?page=wpdevart_gallery_popup<?php if($id) echo '&id='.$id; ?>" method="post" id="wpda_gall_pupup_form">
		<input type="text" class="wpda_gallname" name="wpda_gall_popup_theme_name" placeholder="Enter name here" value="<?php echo isset($wpda_gallname)?$wpda_gallname:'' ?>" >
			<div class="wpda_gall_content">
				<div class="wpda_gall_tabs_content">
				<?php
					foreach($this->options as $params_grup_name =>$params_group_value){ 
						echo '<span id="'.$params_grup_name.'_tab">'.$params_group_value['heading_name'].'</span>';
					}
				?>
				</div>

					<div class="wpda_gall_main_settings_div">

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
					 <?php wp_nonce_field('wpda_gall_popup_page_nonce_action','wpda_gall_popup_page_nonce_name'); ?>

				<div class="wpda_gall_map_settings_map">    
					<div id="wpda_gall_wrap_overlay"></div>         
					<div id="wpda_gall_overlay"  title="overlay">                 
						<div id="wpda_gall_bar_icons_out" class="wpda_gall_bar_icons_out" title="bar_icons_out"></div>    	
						<div id="wpda_gall_map_conteiner" class="wpda_gall_map_conteiner">
							<div id="wpda_gall_map_popup" class="wpda_gall_map_popup" title="popup">          
								<img id="wpda_gall_map_popup_img" class="wpda_gall_map_popup_img_content" src="<?php echo wpdevart_gallery_plugin_url; ?>includes/admin/images/popup_map_content.jpg" style="width: 500px; height: 313px;">
								<div id="wpda_gall_map_popup_icon_left_bar" class="wpda_gall_map_popup_icon_left_bar" style="z-index: 10;"  title="popup_left_icon">
									<div class="wpda_gall_map_popup_icon_left_base">
										<i id="wpda_gall_map_popup_icon_left_bar_"  class="fa fa-chevron-left wpda_gall_map_popup_icon_left"></i>
									</div>
								</div>
								<div id="wpda_gall_map_popup_icon_right_bar" class="wpda_gall_map_popup_icon_right_bar" style="z-index: 10; display: inline;"  title="popup_right_icon">
									<div class="wpda_gall_map_popup_icon_right_base">
										<i id="wpda_gall_map_popup_icon_right_bar_" class="fa fa-chevron-right wpda_gall_map_popup_icon_right"></i>
									</div>
								</div>           
								<div dir="ltl" id="wpda_gall_left_bar_icons" class="wpda_gall_left_bar_icons" title="barIconsIn">
									<div id="wpda_gall_map_thumbnail" class="wpda_gall_map_thumbnail" style=" left: 271px; background-image: url(<?php echo wpdevart_gallery_plugin_url; ?>includes/admin/images/popup_map_thumbnail.jpg)" title="screen">
										<div id="wpda_gall_map_thumbnail_count" class="wpda_gall_map_thumbnail_count" title="count_screen">4</div>
									</div>
									<div id="wpda_gall_map_thumbnail_controll_bar" class="wpda_gall_map_thumbnail_controll_bar" title="bar_thumbnail_images">
										<div id="wpda_gall_bar_thumbnail_imgs" class="wpda_gall_bar_thumbnail_imgs" style="height: 6px; top: -1px;">
											<div id="wpda_gall_thumbnail_imgs_1" class="wpda_gall_thumbnail_imgs_" style="width: 20%;">
												<div class="wpda_gall_thumbnail_imgs" style="height: 24px; top: -9px;"></div>
											</div>
											<div id="wpda_gall_thumbnail_imgs_2" class="wpda_gall_thumbnail_imgs_" style="width: 20%;">
												<div class="wpda_gall_thumbnail_imgs" style="height: 24px; top: -9px;"></div>
											</div>
											<div id="wpda_gall_thumbnail_imgs_3" class="wpda_gall_thumbnail_imgs_" style="width: 20%; background-color: rgb(0, 0, 255);">
												<span class="wpda_gall_pntik" style="height: 8px; top: -1px;" title="pntik"></span>
												<div class="wpda_gall_thumbnail_imgs" style="height: 24px; top: -9px;" title="pntik"></div>
											</div>
											<div id="wpda_gall_thumbnail_imgs_" class="wpda_gall_thumbnail_imgs_" style="width: 20%; background-color: rgb(0, 0, 255);" title="thumbnail-image">
												<div class="wpda_gall_thumbnail_imgs" style="height: 24px; top: -9px;" title="thumbnail-image"></div>
											</div>
											<div id="wpda_gall_thumbnail_imgs_4" class="wpda_gall_thumbnail_imgs_" style="width: 20%;">
												<div class="wpda_gall_thumbnail_imgs" style="height: 24px; top: -9px;"></div>
											</div>
										 </div>
									 </div>
									<i id="wpda_gall_left_icon" class="fa fa-arrow-left wpda_gall_class_icon" style="font-size: 14.4px;" title="left_icon"></i>
									<i id="wpda_gall_play_icon" class="fa fa-play wpda_gall_class_icon" style="font-size: 14.4px;" title="play_icon"></i>
									<i id="wpda_gall_right_icon" class="fa fa-arrow-right wpda_gall_class_icon" style="font-size: 14.4px;" title="right_icon"></i>
									<span id="wpda_gall_imgs_count" class="wpda_gall_number_image " style="font-size: 14.4px;" title="imgs_count">3/5</span>
									<div dir="rtl" id="wpda_gall_right_bar_icons" class="wpda_gall_right_bar_icons">
										<i id="wpda_gall_close_icon"  class="fa fa-close wpda_gall_class_icon" style="font-size: 14.4px; margin-right: 12px;" title="close_icon"></i>
										<i id="wpda_gall_setting_icon" class="fa fa-file-text wpda_gall_class_icon" style="font-size: 14.4px;" title="setting_icon"></i>
										<i id="wpda_gall_full_icon"  class="fa fa-expand wpda_gall_class_icon" style="font-size: 14.4px;" title="full_icon"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> 	
		</form>	
		<?php
	}
	
	/*###################### Border types function ##################*/		
	
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
	
	/*###################### Font width label ##################*/	
	
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
	
    /*###################### Border with label function ##################*/
	
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
	
    /*###################### Hex2rgba function ##################*/		
	
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
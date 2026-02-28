<?php
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: ToTop Link
Plugin URI: http://www.daobydesign.com/free-plugins/totop-link-for-wordpress
Description: A simple plugin for WordPress that adds an unobtrusive smooth scrolling "back to top" link to your site or blog.
Author: Dao By Design
Author URI: http://www.daobydesign.com
Version: 1.7
License: GPL2
*/ 

/*  Copyright 2014  Dao By Design  (email : info@daobydesign.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

global $dbd_totoplink_version;
$dbd_totoplink_version = 1.7;

/* ***********
*
* Initialize plugin on activation
*
*********** */
function dbd_totop_on_activation() {
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );

	// Initialize option values
	return dbd_totop_restore_config('init');
}

register_activation_hook(__FILE__, 'dbd_totop_on_activation');


/* ***********
*
* Restore built-in defaults, optionally overwriting existing values
*
*********** */
function dbd_totop_restore_config($type=false) {
	// Make sure the current user can manage options
	if (!current_user_can('manage_options')) {
		return;
	}

	$totop_vals = get_option('dbd_totoplink');
	
	if (empty($totop_vals) || 'reset' == $type) {
		global $dbd_totoplink_version;

		$totop_default_vals = array(
			'totop_version'			=> $dbd_totoplink_version,
			'totop_enabled'			=> 'disabled',
			'totop_position' 		=> 'br',
			'totop_position_c'		=> array('top' => '', 'right' => '', 'bottom' => '', 'left' => ''),
			'totop_style' 			=> 'circle-dark',
			'totop_style_c'			=> null,
			'totop_style_w'			=> null,
			'totop_style_h'			=> null,
			'totop_scroll_offset'	=> '',
			'totop_scroll_speed'	=> '',
			'totop_link_text'		=> '',
			'totop_link_style1'		=> '',
			'totop_link_style2'		=> '',
			'totop_rwd_max_width' 	=> ''
		);
	
		$totop_vals = $totop_default_vals;
	}

	update_option( 'dbd_totoplink', $totop_vals );
}

add_action('admin_menu', 'dbd_totop_admin_menu');
function dbd_totop_admin_menu() {
	add_submenu_page('options-general.php', 'ToTop Link Options', 'ToTop Link', 'manage_options', 'dbd_totop', 'dbd_totop_settings');
}

/* ***********
*
* Setup the Options page
*
*********** */
function dbd_totop_settings() {
	// Reset Values
	if (!empty($_POST['clear'])) {
		dbd_totop_restore_config('reset');
		echo '<div id="message" class="updated"><p>The settings have been reset to their defaults.</p></div>';
	}

	// Get Options
	$totop_vals = get_option('dbd_totoplink');

	// Save Values
	if (!empty($_POST['save'])) {
		// Check nonce and user ability
		if ( !empty($_POST) && check_admin_referer('dbd_totop-submit', 'dbd_totop_nonce') && current_user_can('manage_options') ) {
			// Validate & Sanitize
			$totop_vals['totop_enabled'] = sanitize_text_field($_POST['totop_enabled']);
			$totop_vals['totop_position'] = sanitize_text_field($_POST['totop_position']);
			$totop_vals['totop_position_c'] = array_map('sanitize_text_field',$_POST['totop_position_c']);
			$totop_vals['totop_style'] = sanitize_text_field($_POST['totop_style']);
			$totop_vals['totop_style_c'] = esc_url_raw($_POST['totop_style_c']);
			$totop_vals['totop_style_w'] = sanitize_text_field($_POST['totop_style_w']);
			$totop_vals['totop_style_h'] = sanitize_text_field($_POST['totop_style_h']);			
			$totop_vals['totop_scroll_offset'] = sanitize_text_field($_POST['totop_scroll_offset']);
			$totop_vals['totop_scroll_speed'] = sanitize_text_field($_POST['totop_scroll_speed']);
			$totop_vals['totop_link_text'] = sanitize_text_field($_POST['totop_link_text']);
			$totop_vals['totop_link_style1'] = sanitize_text_field($_POST['totop_link_style1']);
			$totop_vals['totop_link_style2'] = sanitize_text_field($_POST['totop_link_style2']);
			$totop_vals['totop_rwd_max_width'] = sanitize_text_field($_POST['totop_rwd_max_width']);

			update_option( 'dbd_totoplink', $totop_vals );

			echo '<div id="message" class="updated"><p>The changes have been saved.</p></div>';
		}
	}
	?>
	
	<style>
		.totoplink-title small {font-size:14px;color:#999;font-style: italic;float:right;}
		.form-table {border:1px solid #ddd;}
		.form-table td, .form-table th {padding:15px 10px;}
		.form-table th {font-weight:bold;background:#eee;border-right:1px solid #ccc;border-bottom:1px solid #ddd;}
		.form-table td {background:#f3f3f3;border-bottom:1px solid #ddd;}
		.form-table td .description {padding:5px;border:1px solid #eee;background:#f6f6f6;color:#999;}
		.dbd-credit th {font-weight:bold;background:#ffffdd;text-align:right;vertical-align:middle;padding:5px 10px;}
		.dbd-credit a:link, .dbd-credit a:visited {color:#22AFC5;text-decoration:none;}
		.dbd-credit a:hover, .dbd-credit a:active {color:#ff0000;}
		a.buyusacoffee img {vertical-align:middle;}
		#message, .description {clear:both;}

		.totop-style-item {float:left;width:21%;min-height:88px;background:#eee;border:0px solid #ccc;text-align:center;padding:1%;margin:1%;}
  		.totop-style-item img {display:block;margin:0 auto 10px}
	</style>
    	
	<div class="wrap" id="totop_options">
	    <div class="icon32" id="icon-options-general"><br></div>
		<h2 class="totoplink-title">ToTop Link Settings <small>ver. <?php echo $totop_vals['totop_version']; ?></small></h2>
        <form action="" method="post" id="dbd_totop_options_form" name="dbd_totop_options_form">
                <?php wp_nonce_field('dbd_totop-submit', 'dbd_totop_nonce'); ?>
                <table class="form-table">
                	<tbody>
                        <tr valign="middle" class="dbd-credit">
        					<th colspan="2">Like this plugin? <a href="http://www.twitter.com/daobydesign" target="_blank">Follow us on Twitter</a>, <a href="http://www.facebook.com/daobydesign" target="_blank">Like us on Facebook</a> or just <a href="http://www.daobydesign.com/buy-us-a-coffee/" class="buyusacoffee buyusacoffee-top" target="_blank" title="... because we'd sure appreciate it!">buy us a coffee! <img src="<?php echo plugin_dir_url(__FILE__); ?>/images/coffee_mug.png" /></a></th>
                        </tr>

                    	<tr valign="top">
                    		<?php $enabled = $totop_vals['totop_enabled']; ?>
                    		<th>Enable / Disable Button</th>
                           	<td>
                            	<input type="radio" name="totop_enabled" id="totop_enabled" value="enabled" <?php checked($enabled,'enabled'); ?>><label for="totop_enabled"> Enabled</label>
                            	<br />
                                <input type="radio" name="totop_enabled" id="totop_disabled" value="disabled" <?php checked($enabled,'disabled'); ?>><label for="totop_disabled"> Disabled</label>
                            </td>
                        </tr>
                        
                    	<tr valign="top">
                        	<th>ToTop Link Position</th>
                    		<td>
                    			<?php $pos = $totop_vals['totop_position']; ?>
                    			<select type="select" name="totop_position" id="totop_position">
                                    <option value="bl" id="totop_bl" <?php selected($pos,'bl'); ?>>Bottom Left</option>
                                    <option value="br" id="totop_br" <?php selected($pos,'br'); ?>>Bottom Right</option>
                                    <option value="bm" id="totop_bm" <?php selected($pos,'bm'); ?>>Bottom Middle</option>
                                    <option value="tl" id="totop_tl" <?php selected($pos,'tl'); ?>>Top Left</option>
                                    <option value="tr" id="totop_tr" <?php selected($pos,'tr'); ?>>Top Right</option>
                                    <option value="tm" id="totop_tm" <?php selected($pos,'tm'); ?>>Top Middle</option>
                                    <option value="ml" id="totop_ml" <?php selected($pos,'ml'); ?>>Middle Left</option>
                                    <option value="mr" id="totop_mr" <?php selected($pos,'mr'); ?>>Middle Right</option>
                                    <option value="custom" id="totop_custom" <?php selected($pos,'custom'); ?>>Custom</option>
                                </select>
                                <p class="description">With this setting you can choose where you want the ToTop link to be displayed.</p>
                                <p>&nbsp;</p>
                                <p><strong>Custom Offset Position:</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                                	<label for="totop_position_ct]">Top:</label>
                                	<input type="text" name="totop_position_c[top]" value="<?php esc_attr_e($totop_vals['totop_position_c']['top']); ?>" id="totop_position_ct" size="2">
                                    <label for="totop_position_cl]">Left:</label>
                                    <input type="text" name="totop_position_c[left]" value="<?php esc_attr_e($totop_vals['totop_position_c']['left']); ?>" id="totop_position_cl" size="2">
                                    <label for="totop_position_cb]">Bottom:</label>
                                    <input type="text" name="totop_position_c[bottom]" value="<?php esc_attr_e($totop_vals['totop_position_c']['bottom']); ?>" id="totop_position_cb" size="2">
                                    <label for="totop_position_cr]">Right:</label> 
                                    <input type="text" name="totop_position_c[right]" value="<?php esc_attr_e($totop_vals['totop_position_c']['right']); ?>" id="totop_position_cr" size="2">
                                </p>
                                <p class="description">Use the above fields to set the custom offsets for the ToTop image/link. Be sure to only set one horizontal and one vertical position (ie. Top and Left, not Top and Bottom). <em>Leave unused positions blank</em>.</p>
                            </td>
                        </tr>    
                        
                        <tr valign="top">
		                    <th>ToTop Style</th>
							<td>
								<?php $style = $totop_vals['totop_style']; ?>
								<div class="totop-style-item">
                                	<img src="<?php echo plugin_dir_url(__FILE__).'images/totop-circle-dark.svg'; ?>" width="50" height="50">
                                	<input type="radio" name="totop_style" value="circle-dark" id="totop_circle_dark" <?php checked($style,'circle-dark'); ?>><label for="totop_circle_dark"> Circle - Dark</label>
                                </div>
                            	<div class="totop-style-item">
	                                <img src="<?php echo plugin_dir_url(__FILE__).'images/totop-circle-light.svg'; ?>" width="50" height="50">
	                                <input type="radio" name="totop_style" value="circle-light" id="totop_circle_light" <?php checked($style,'circle-light'); ?>><label for="totop_circle_light"> Circle - Light</label>
                                </div>
                                <div class="totop-style-item">
	                                <img src="<?php echo plugin_dir_url(__FILE__).'images/totop-dark.png'; ?>" width="40" height="48">
	                                <input type="radio" name="totop_style" value="dark" id="totop_dark" <?php checked($style,'dark'); ?>><label for="totop_dark"> Default - Dark</label>
                                </div>
                                <div class="totop-style-item">
                            		<img src="<?php echo plugin_dir_url(__FILE__).'images/totop-light.png'; ?>" width="40" height="48">
                            		<input type="radio" name="totop_style" value="light" id="totop_light" <?php checked($style,'light'); ?>><label for="totop_light"> Default - Light</label>
                                </div>
								<div class="totop-style-item">
                                	<input type="radio" name="totop_style" value="text" id="totop_text" <?php checked($style,'text'); ?>><label for="totop_text"> Text Link</label>
                                </div>
                                <div class="totop-style-item">
	                                <input type="radio" name="totop_style" value="custom" id="totop_custom_img" <?php checked($style,'custom'); ?>><label for="totop_custom_img"> Custom Image</label>
			                    </div>
			                    <p class="description">Choose "Light" if your Web site's background colour is a dark colour. Choose "Dark" if your background is a light colour. If you prefer to use your own image, select "Custom", and include the URL to the image in the field below.</p>
                            </td>
                        </tr>

						<tr valign="top">
		                    <th>Custom Image URL</th>
							<td>
                            	<label for="totop_style_c">Custom Image URL:</label><br /><input type="text" name="totop_style_c" value="<?php echo esc_url($totop_vals['totop_style_c']); ?>" id="totop_style_c" style="width:60%;">
                            </td>
                        </tr>

                        <tr valign="top">
                        	<th>Custom Image Size</th>
                        	<td>
                                <label for="totop_style_w">Custom Image Width:</label> <input type="text" name="totop_style_w" value="<?php esc_attr_e($totop_vals['totop_style_w']); ?>" id="totop_style_w" size="3" >
                                <label for="totop_style_h">Custom Image Height:</label> <input type="text" name="totop_style_h" value="<?php esc_attr_e($totop_vals['totop_style_h']); ?>" id="totop_style_h" size="3" >
			                    <p class="description">The height and width should auto-populate with the size of the image supplied in the "Custom Image URL" field above. If not, or if you'd like to customize the height and width of any image style, please add the values above.</p>
                            </td>
                        </tr>


						<tr valign="top">
                    		<th>ToTop Text Link Config.<br /></th>
                        	<td>
                            	<input type="text" name="totop_link_text" value="<?php esc_attr_e($totop_vals['totop_link_text']); ?>" id="totop_link_text"><label for="totop_link_text"> Text for Link</label>
                                <p class="description">You can customize the link text displayed. Leave blank for default: <strong><?php _e('Return to Top ▲'); ?></strong></p>
                            	<input type="text" name="totop_link_style1" value="<?php esc_attr_e($totop_vals['totop_link_style1']); ?>" id="totop_link_style1"><label for="totop_link_style1"> Regular Link Colour</label>
                        		<br />
                                <input type="text" name="totop_link_style2" value="<?php esc_attr_e($totop_vals['totop_link_style2']); ?>" id="totop_link_style2"><label for="totop_link_style2"> Hover Link Colour</label>
                    			<p class="description">Insert the HEX value with hash symbol (e.g. #336600) for the regular and hover link colours. Leave blank if you wish to use your theme's defaults.</p>
							</td>
                        </tr>

                        <tr valign="top">
		                    <th>ToTop Scroll Offset</th>
							<td>
                            	<input type="text" name="totop_scroll_offset" value="<?php esc_attr_e($totop_vals['totop_scroll_offset']); ?>" id="totop_scroll_offset" size="3"><label for="totop_scroll_offset">px</label> 
			                    <p class="description">This setting allows you to set how far down the page a user must scroll before the ToTop link appears. <strong>Do not include "px"</strong></p>
                            </td>
                        </tr>

                        <tr valign="top">
		                    <th>ToTop Scroll Speed</th>
							<td>
                            	<input type="text" name="totop_scroll_speed" value="<?php esc_attr_e($totop_vals['totop_scroll_speed']); ?>" id="totop_scroll_speed" size="4"><label for="totop_scroll_speed">ms</label> 
			                    <p class="description">This setting allows you to set how fast the page will scroll back to the top once the ToTop Link is clicked. <strong>Include an integer in milliseconds. If left blank, the default '800' will be used.</strong></p>
                            </td>
                        </tr>

                        <tr valign="top">
		                    <th>ToTop Responsive Settings</th>
							<td>
                            	<input type="text" name="totop_rwd_max_width" value="<?php esc_attr_e($totop_vals['totop_rwd_max_width']); ?>" id="totop_rwd_max_width" size="5">
			                    <p class="description">Set a media query max-width value here to control whether the ToTop link should be hidden on smaller screens. If left empty, it will always display. A good value would be 599px, for hiding link on all devices smaller than a small tablet. <strong>Note: you must include the unit (e.g. <em>px</em>, <em>em</em>) </strong>.</p>
                            </td>
                        </tr>

                        <tr valign="middle" class="dbd-credit">
        					<th colspan="2">Like this plugin? <a href="http://www.twitter.com/daobydesign" target="_blank">Follow us on Twitter</a>, <a href="http://www.facebook.com/daobydesign" target="_blank">Like us on Facebook</a> or just <a href="http://www.daobydesign.com/buy-us-a-coffee/" class="buyusacoffee buyusacoffee-top" target="_blank" title="... because we'd sure appreciate it!">buy us a coffee! <img src="<?php echo plugin_dir_url(__FILE__); ?>/images/coffee_mug.png" /></a></th>
                        </tr>

                	</tbody>
          		</table>
                <p class="submit">
                    <input name="save" id="save" class="button button-primary" value="Save Changes" type="submit" />
                    <input name="clear" id="reset" class="button" value="Reset Options" type="submit" />
	                
                </p>
        </form>
	</div>
    <script type="text/javascript" language="javascript">
		jQuery('#totop_style_c').change(function() {
			var $cimg = new Image();
			$cimg.onload = function() {
				if (!jQuery('#totop_style_w').val()) { jQuery('#totop_style_w').val(this.width); }
				if (!jQuery('#totop_style_h').val()) { jQuery('#totop_style_h').val(this.height); }
			}
			$cimg.src = jQuery('#totop_style_c').val();
		});
	</script>
	
<?php
}


/* ***********
*
* Add HTML to page body
*
*********** */
add_action('wp_footer', 'dbd_totop_body_hook');
function dbd_totop_body_hook() {
	// Get Options
	$totop_vals = get_option('dbd_totoplink');
	if ($totop_vals['totop_enabled'] == 'enabled' && !is_admin()) {	
		// Set Text
		$totop_link_text = ($totop_vals['totop_link_text']) ? sanitize_text_field($totop_vals['totop_link_text']) : __('Return to Top ▲');

		// Set Image
		$totop_img = '';
		if ($totop_vals['totop_style'] != 'text') { 
			if ($totop_vals['totop_style'] == 'custom') {
				$totop_img_src = $totop_vals['totop_style_c'];
				$totop_img_w = $totop_vals['totop_style_w'];
				$totop_img_h = $totop_vals['totop_style_h'];		
			} elseif ($totop_vals['totop_style'] == 'dark' || $totop_vals['totop_style'] == 'light') {
				$totop_img_src = plugin_dir_url(__FILE__).'images/totop-'.$totop_vals['totop_style'].'.png';
				$totop_img_w = (!empty($totop_vals['totop_style_w'])) ? $totop_vals['totop_style_w'] : '40';	
				$totop_img_h = (!empty($totop_vals['totop_style_h'])) ? $totop_vals['totop_style_h'] : '48';	
			} elseif ($totop_vals['totop_style'] == 'circle-dark' || $totop_vals['totop_style'] == 'circle-light') {
				$totop_img_src = plugin_dir_url(__FILE__).'images/totop-'.$totop_vals['totop_style'].'.svg';
				$totop_img_w = (!empty($totop_vals['totop_style_w'])) ? $totop_vals['totop_style_w'] : '50';	
				$totop_img_h = (!empty($totop_vals['totop_style_h'])) ? $totop_vals['totop_style_h'] : '50';	
			}

			$totop_img = '<img src="'.esc_url($totop_img_src).'" alt="'.esc_attr($totop_link_text).'" width="'.esc_attr($totop_img_w).'" height="'.esc_attr($totop_img_h).'" />';
		}


		$totop_class = 'totop-'.$totop_vals['totop_position'].' totop-'.$totop_vals['totop_style'];

		$totop_scroll_offset = (!empty($totop_vals['totop_scroll_offset'])) ? $totop_vals['totop_scroll_offset'] : 0;
		$totop_scroll_speed = (!empty($totop_vals['totop_scroll_speed'])) ? $totop_vals['totop_scroll_speed'] : 800;

		echo '<a id="toTop" title="'.esc_attr($totop_link_text).'" class="'.esc_attr($totop_class).'" data-scroll-offset="'.esc_attr($totop_scroll_offset).'" data-scroll-speed="'.esc_attr($totop_scroll_speed).'">'.$totop_img.'<span>'.$totop_link_text.'</span></a>';
	}
}

/* ***********
*
* Enqueue scripts and style
*
*********** */
add_action('init','dbd_totop_init_hook');
function dbd_totop_init_hook() {
	// Get Options
	$totop_vals = get_option('dbd_totoplink');
	if ($totop_vals['totop_enabled'] == 'enabled' && !is_admin()) {
		$totop_css_vars = array();
		
		// Custom Position
		if ($totop_vals['totop_position'] == 'custom') {
			$totop_css_vars['pos'] = $totop_vals['totop_position_c'];
		}

		// Width and Height
		if ($totop_vals['totop_style'] == 'dark' || $totop_vals['totop_style'] == 'light') {
			$totop_css_vars['width'] = (!empty($totop_vals['totop_style_w'])) ? intval($totop_vals['totop_style_w']) : '40';	
			$totop_css_vars['height'] = (!empty($totop_vals['totop_style_h'])) ? intval($totop_vals['totop_style_h']) : '48';	
		} elseif ($totop_vals['totop_style'] == 'circle-dark' || $totop_vals['totop_style'] == 'circle-light') {
			$totop_css_vars['width'] = (!empty($totop_vals['totop_style_w'])) ? intval($totop_vals['totop_style_w']) : '50';	
			$totop_css_vars['height'] = (!empty($totop_vals['totop_style_h'])) ? intval($totop_vals['totop_style_h']) : '50';	
		} else {
			$totop_css_vars['width'] = (!empty($totop_vals['totop_style_w'])) ? intval($totop_vals['totop_style_w']) : 'auto';	
			$totop_css_vars['height'] = (!empty($totop_vals['totop_style_h'])) ? intval($totop_vals['totop_style_h']) : 'auto';			
		}

		// Link Style
		$totop_css_vars['text-style'][0] = esc_attr($totop_vals['totop_link_style1']);
		$totop_css_vars['text-style'][1] = esc_attr($totop_vals['totop_link_style2']);

		// Responsive
		$totop_css_vars['rwd_max_width'] = esc_attr($totop_vals['totop_rwd_max_width']);

		//$totop_js_vars = ($totop_vals['totop_scroll_speed']) ? '?speed='.esc_attr($totop_vals['totop_scroll_speed']) : '';
		$totop_css = '?vars='.base64_encode(serialize($totop_css_vars));
		wp_enqueue_style('totop',  plugin_dir_url(__FILE__).'totop-link.css.php'.$totop_css);
		wp_enqueue_script('jquery');
		wp_enqueue_script('totop', plugin_dir_url(__FILE__).'totop-link.js','jquery', '1.6', true);
	}
}

add_action('admin_init', 'dbd_totop_version_check');
function dbd_totop_version_check() {
	global $dbd_totoplink_version;
	$totop_vals = get_option('dbd_totoplink');
	if (empty($totop_vals['totop_version']) || $totop_vals['totop_version'] < $dbd_totoplink_version) {
		// Migrate old (pre 1.6) values to new single options array.
		if (current_user_can('manage_options') && empty($totop_vals['totop_version'])) {
			$totop_old_vals = array(
				'totop_version'			=> $dbd_totoplink_version,
				'totop_enabled'			=> get_option('totop_enabled'),
				'totop_position' 		=> get_option('totop_position'),
				'totop_position_c'		=> get_option('totop_position_c'),
				'totop_style' 			=> get_option('totop_style'),
				'totop_style_c'			=> get_option('totop_style_c'),
				'totop_style_w'			=> get_option('totop_style_w'),
				'totop_style_h'			=> get_option('totop_style_h'),
				'totop_scroll_offset'	=> get_option('totop_scroll_offset'),
				'totop_scroll_speed'	=> get_option('totop_scroll_speed'),
				'totop_link_text'		=> get_option('totop_link_text'),
				'totop_link_style1'		=> get_option('totop_link_style1'),
				'totop_link_style2'		=> get_option('totop_link_style2'),
				'totop_rwd_max_width' 	=> ''
			);
			$totop_vals = $totop_old_vals;
			// Remove old values
			delete_option('totop_enabled');
			delete_option('totop_position');
			delete_option('totop_position_c');
			delete_option('totop_style');
			delete_option('totop_style_c');
			delete_option('totop_style_w');
			delete_option('totop_style_h');
			delete_option('totop_scroll_offset');
			delete_option('totop_scroll_speed');
			delete_option('totop_link_text');
			delete_option('totop_link_style1');
			delete_option('totop_link_style2');
		
			update_option( 'dbd_totoplink', $totop_vals );
		}
	}
}
?>
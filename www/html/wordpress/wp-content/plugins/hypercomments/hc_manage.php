<div class="hc_wrap_box">
	<div class="hc_header">
		<a target="_blank" href="http://hypercomments.com"><span class="hc_logo"></span></a>

		<?php if(get_option('hc_wid')):?>
			<div class="hc_top_menu">
				<span data-tab="comments" class="hc_menu_item hc_menu_active e_menu_item"><?php _e('Comments', 'hypercomments' ) ?></span>
				<span data-tab="settings" class="hc_menu_item e_menu_item"><?php _e('Settings', 'hypercomments' ) ?></span>
			</div>
		<?php endif;?>
	</div>

	<div class="no_folder hc_notify_error"><?php _e("You need to create a folder \"uploads\" with 777 permissions in the \"/path/to/wordpress/wp-content\" directory.", "hypercomments" ); ?></div>
	<div class="import_hc hc_notify_ok"><?php _e("Comments will be imported at least 15 minutes.", "hypercomments" ); ?></div>
	<div class="err_hc_import hc_notify_error"><?php _e('Error when trying to generate XML', 'hypercomments' ); ?></div>
	<div class="err_opt hc_notify_error"><?php _e('Invalid format', 'hypercomments' ); ?></div>
	<div class="err_install hc_notify_error"></div>
	<div class="opt_saved hc_notify_ok"><?php _e("The settings are saved", "hypercomments" ); ?></div>
	
	<?php if(!get_option('hc_wid')):?>
		<div class="hc_btn_settings e_hc_login"><?php _e('Login with Google', 'hypercomments' ) ?></div>
	<?php else:?>
		<div class="hc_content_wrap">
			<!-- Comments tab -->
			<div class="hc_box e_box_comments">
				<?php echo hc_get_adm_widget(get_option('hc_wid'), hc_get_language("adm"));?>
			</div>
			<!-- Settings tab -->
			<div class="hc_box e_box_settings" style="display:none">
				<!-- Import -->
				<div class="hc_subbox">
					<div class="hc_title_sub"><?php _e('Import comments', 'hypercomments' ) ?></div>
					<table>
						<tr>
							<td width="300px"><?php _e('Import comments to HyperComments', 'hypercomments' ) ?></td>
							<td width="450px">
								<button id="wp_to_hc" class="button"><?php _e('Import comments to HyperComments', 'hypercomments' ) ?></button>
								<div class="hc_import_boxlog hc_load">
									<img id="load_import" src="<?php echo plugins_url('/css/loading.gif',  __FILE__ );?>" alt="loading" />
									<span>
										<?php _e('Formation file', 'hypercomments' );?>, Page ID:
										<span class="hc_im_page"></span>
										<span class="hc_f_status"></span>
										<span><?php _e('Import', 'hypercomments' );?>:</span>
										<span class="hc_im_status"></span>									
									</span>
									
								</div>
							</td>
							
						</tr>
					</table>
				</div>

				<!-- Widget Comments -->
				<div class="hc_subbox">
					<div class="hc_title_sub"><?php _e('Comments widget', 'hypercomments' ) ?></div>
					<table>
						<tr>
							<td width="300px"><?php _e('Block to insert the widget code', 'hypercomments' ) ?></td>
							<td><input data-opt="hc_append" type="text" class="e_wc_settings" value="<?php echo (get_option('hc_append')) ? get_option('hc_append') : '#hypercomments_widget'; ?>" /></td>
						</tr>
						<tr>
							<td width="300px"><?php _e('Number of words that are allowed to hypercomment on', 'hypercomments' ) ?></td>
							<td><input data-opt="hc_word_limit" type="text" class="e_wc_settings" value="<?php echo (get_option('hc_word_limit')) ? get_option('hc_word_limit') : 10; ?>" /></td>
						</tr>
						<tr>
							<td width="300px"><?php _e('Realtime comments display', 'hypercomments' ) ?></td>
							<td>
								<input data-opt="hc_realtime" type="checkbox" class="e_wc_settings"  <?php echo (get_option('hc_realtime') ? 'checked="checked"' : '');?> />
								<?php _e('on/off', 'hypercomments' ); ?>
							</td>
						</tr>
						<tr>
							<td width="300px"><?php _e('The level of comments nesting for "All" tab', 'hypercomments' ) ?></td>
							<td><input data-opt="hc_comments_level" type="text" class="e_wc_settings" value="<?php echo (get_option('hc_comments_level')) ? get_option('hc_comments_level') : 4; ?>" /></td>
						</tr>
						<tr>
							<td width="300px">
								<?php _e('Path to css file', 'hypercomments' ) ?>
								<span class="hc_tariff"><sub><?php _e('for tariffs above Professional', 'hypercomments' ) ?></sub></span>
							</td>
							<td>
								<input data-opt="hc_css" type="text" class="e_wc_settings e_hc_css_in" value="<?php echo (get_option('hc_css')) ? get_option('hc_css') : ''; ?>" />
								<a class="e_hc_css" href=""><?php _e('Create CSS file', 'hypercomments' ) ?></a>
								<div class="hc_css_background hc_hide"></div>
								<div class="hc_css_box hc_hide">
									<img src="<?php echo plugins_url('/css/close_hc.png',  __FILE__ );?>" class="hc_css_close e_hc_css_close">
									<span class="hc_css_title"><?php _e('CSS Editor', 'hypercomments' ) ?></span>
									<textarea class="hc_css_content e_hc_css_content" contenteditable="true">
										
									</textarea>
									<div class="hc_css_save">
										<div class="hc_css_btn hc_btn_settings" id="save_css"><?php _e('Save CSS', 'hypercomments' ) ?></div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td width="300px">
								<?php _e('Localization', 'hypercomments' ) ?>
							</td>
							<td>
								<select data-opt="hc_locale" type="text" class="e_wc_settings">
									<?php foreach (hc_get_locales() as $lang): ?>
										<?php if($lang == get_option('hc_locale')):?>
										<option selected="selected" value="<?php echo $lang;?>"><?php echo $lang;?></option>
										<?php else: ?>
										<option value="<?php echo $lang;?>"><?php echo $lang;?></option>
										<?php endif; ?>
									<?php endforeach;?>
									<option <?php if(get_option('hc_locale') == 'default'):?> selected="selected" <?php endif; ?> value="default"><?php _e('Default browser localization', 'hypercomments' ) ?></option>
								</select>
							</td>
						</tr>
					</table>
				</div>

				<!-- Widget Counter -->
				<div class="hc_subbox">
					<div class="hc_title_sub"><?php _e('Counter widget', 'hypercomments' ) ?></div>
					<table>
						<tr>
							<td width="300px"><?php _e('HTML - selector to insert the counter comments', 'hypercomments' ) ?></td>
							<td>
								<input data-opt="hc_selector" type="text" class="e_wc_settings" value="<?php echo get_option('hc_selector'); ?>" />
							</td>
						</tr>
						<tr>
							<td width="300px"><?php _e('Label counter comments', 'hypercomments' ) ?></td>
							<td>
								<input data-opt="hc_label_counter" type="text" class="e_wc_settings" value="<?php echo get_option('hc_label_counter'); ?>" />
								<p><?php _e('{%COUNT%} - displays the number of comments. Default: Comments({%COUNT%})', 'hypercomments');?></p>
							</td>
						</tr>
					</table>
				</div>

				<!-- General settings -->
				<div class="hc_subbox">
					<div class="hc_title_sub"><?php _e('General settings', 'hypercomments' ) ?></div>
					<table>
						<tr>
							<td width="300px"><?php _e('Synchronizing users and comments', 'hypercomments' ) ?></td>
							<td>
								<label>
								<input data-opt="hc_synch" type="checkbox" class="e_wc_settings" <?php echo (get_option('hc_synch') ? 'checked="checked"' : '');?> />
								<?php _e('on/off', 'hypercomments' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td width="300px"><?php _e('Site\'s root  is in the subdirectory', 'hypercomments' ) ?></td>
							<td>
								<label>
								<input data-opt="hc_root" type="checkbox"  class="e_wc_settings"  <?php echo (get_option('hc_root') ? 'checked="checked"' : '');?> />                        
		                        <?php _e('yes/no', 'hypercomments' ); ?><br />
		                        <?php _e('Mark the checkbox if the site is located in a subdirectory and you import comments via hypercomments.com website', 'hypercomments' ); ?>
		                        </label>
							</td>
						</tr>
					</table>
				</div>

				<div class="hc_subbox hc_upd_box">
					<div class="hc_btn_settings" id="save_options"><?php _e('Update Options', 'hypercomments' ) ?></div>
					<img class="hc_hide hc_load_opt" src="<?php echo plugins_url('/css/loading.gif',  __FILE__ );?>" alt="loading" />
				</div>
			</div>
		</div>

	<?php endif;?>
	
</div>
<script type="text/javascript">
    jQueryHC(document).ready(function(){
    	var posts = [];
    	<?php foreach (hc_get_post_export() as $p): ?>
        posts.push(<?php echo $p->ID;?>);
        <?php endforeach;?> 

    	var init_param = {
    		'hc_url'        : '<?php echo HC_URL; ?>',
    		'hc_lang'       : '<?php echo hc_get_language(); ?>',
    		'hc_siteurl'    : '<?php echo get_option("siteurl");?>',
    		'hc_blogname'   : '<?php echo get_option("blogname");?>',
    		'hc_notify_url' : '<?php echo admin_url("index.php");?>?hc_action=notify',
    		'hc_admin_url'  : '<?php echo admin_url("index.php"); ?>',
    		'hc_posts'      : posts
    	};
    	HCmanage.init(init_param);
    });
</script>
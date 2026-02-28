<?php
	global $wpdb,$arp_pricingtable,$arprice_ab_testing,$arpricelite_version;
	if (is_ssl()){
	    $google_font_url = "https://fonts.googleapis.com/css?family=Ubuntu:400,500,700";
	} else {
	    $google_font_url = "http://fonts.googleapis.com/css?family=Ubuntu:400,500,700";
	}

	$get_table_data = array();

	$table_data = array();
	$variation_tables = array();
	$shortcodeCls = "";
	if( !empty( $get_table_data ) ){
		$table_data = json_decode( $get_table_data->options,true );
		$variation_tables = $table_data['variation_table'];
		$shortcodeCls = "active";
	}
	
	
    $setact = 1;
    $table_last_updated = isset( $get_table_data->last_updated_date ) ? $get_table_data->last_updated_date : '';
?>
<input type="hidden" id="arp_ab_analytic_title" value="<?php echo esc_html_e('A/B Testing Analytics','arprice-responsive-pricing-table').' ( ' . esc_html__( 'From', 'arprice-responsive-pricing-table' ) . ' ' . $table_last_updated . ' ' . esc_html__('till now','arprice-responsive-pricing-table').' )'; ?>" />
<input type="hidden" id="yAxis_title" value="<?php esc_html_e('Clicks & Views in %','arprice-responsive-pricing-table'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $google_font_url; ?>" />
<style type="text/css">
	#wpcontent, #wpfooter,#wpwrap{
        background: #fff;
    }
</style>
<div class="success_message" id="arp_ab_testing_success_msg">
	<div class="message_description"></div>
</div>
<div class="dashboard_error_message" id="arp_ab_testing_error_message">
    <div class="message_description"></div>
</div>
<div class="arp_ab_testing_main arplite_restricted_view">
	<div class="arp_ab_testing_main_title"><?php esc_html_e('A/B Testing','arprice-responsive-pricing-table'); ?></div>	
	<div class="arp_ab_testing_main_inner">
		<div class="arprice_ab_testing">
			<div class="arp_ab_testing_sub_title"><?php esc_html_e('A/B Testing','arprice-responsive-pricing-table'); ?> 
			<?php if( is_rtl() ){?>
				<span class="arp_ab_testing_shortcode <?php echo $shortcodeCls; ?>">
					<span class="arp_ab_testing_shortcode_text arplite_restricted_view" title="click to copy" data-shortcode="[ARPrice_ab]">[ARPrice_ab]</span>
					<span class="arp_abtest_shortcode_label">Shortcode:</span>
				</span><input type="text" id="arp_ab_testing_shortcode" value="[ARPrice_ab]" style="position: absolute;left:-999%" />
			<?php }else{ ?>
				<span class="arp_ab_testing_shortcode <?php echo $shortcodeCls; ?>">
					<span class="arp_abtest_shortcode_label">Shortcode:</span>
					<span class="arp_ab_testing_shortcode_text arplite_restricted_view" title="click to copy" data-shortcode="[ARPrice_ab]">[ARPrice_ab]</span>
				</span><input type="text" id="arp_ab_testing_shortcode" value="[ARPrice_ab]" style="position: absolute;left:-999%" />
			<?php }?>
			</div>
			
			<form id="arp_ab_testing_form" method="post">
				<div class="arprice_ab_testing_inner_container">
					<div class="arprice_ab_testing_header_row">
						<?php esc_html_e('Primary Table','arprice-responsive-pricing-table'); ?>
					</div>
					<div class="arprice_ab_testing_primary_table_row">
						<div class="arprice_ab_testing_label_wrapper">
							<div class="arprice_ab_testing_row_label"><?php esc_html_e('Select Primary Table','arprice-responsive-pricing-table'); ?></div>
							<div class="arprice_ab_testing_impression_label" id="arprice_ab_testing_primary_impression"><?php esc_html_e('Weightage','arprice-responsive-pricing-table'); ?></div>
						</div>
						<div class="arprice_ab_testing_row_input">
							<?php
								$primary_table = isset( $table_data['primary_table'] ) ? $table_data['primary_table'] : '';
								$primary_table_name = esc_html__('Select Table','arprice-responsive-pricing-table');
								if( '' != $primary_table ){
									$table_db_data = $wpdb->get_row( $wpdb->prepare("SELECT id,table_name FROM `".$wpdb->prefix."arp_arprice` WHERE id = %d",$primary_table) );
									$primary_table_name = $table_db_data->table_name.' (ID:'.$table_db_data->id.')';
								}
							?>
							<input type="hidden" name="arprice_ab_testing_primary_table" id="arprice_ab_testing_primary_table" value="<?php echo $primary_table; ?>" />
							<dl class="arprice_selectbox arplite_restricted_view" data-id="arprice_ab_testing_primary_table">
								<dt>
									<span><?php echo $primary_table_name; ?></span>
									<svg viewBox="0 0 2000 1000" width="15px" height="15px"><g fill="#000000"><path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path></g></svg>
								</dt>
								<dd>
									<ul data-id="arprice_ab_testing_primary_table">
										<li data-value="" data-label="<?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?>"><?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?></li>
									</ul>
								</dd>
							</dl>
						</div>
						<?php 
							$primary_table_impression = isset( $table_data['primary_table_impression'] ) ? $table_data['primary_table_impression'] : '';
						?>
						<div class="arprice_ab_testing_impression_wrapper">
							<div class="arprice_db_testing_impression_input_wrapper primary_tbl_prtg_wrap" >
								<input type="text" name="arp_ab_primary_tbl_impression" class="arprice_db_testing_primary_impression_input arp_numeric primary_tbl_prtg_wrap arplite_restricted_view" readonly="readonly" value="<?php echo $primary_table_impression; ?>"/>
								<span>%</span>
							</div>
						</div>
						<!-- ////////////////// changelog -->
					</div>
					<div class="arprice_ab_testing_header_row">
						<div id="variation_table_tile"><?php esc_html_e('Variation Tables','arprice-responsive-pricing-table'); ?></div>
						<span class="column_opt_label_help" id="arp_variation_title_msg"><?php esc_html_e( '( You can add more than one variation table. The total weightage of variation table should be less than 100. The rest of the weightage will be applied to the primary table. )', 'arprice-responsive-pricing-table' ); ?></span>
					
					</div>
					<div class="arprice_ab_testing_variation_table_row">
						<div id="arprice_ab_testing_variation_tables">
							<div class="arprice_ab_testing_row_input_wrapper">
								<div class="arprice_ab_testing_label_wrapper">
									<div class="arprice_ab_testing_row_label"><?php esc_html_e('Select Variation Table','arprice-responsive-pricing-table'); ?></div>
									
								</div>
							</div>
								<?php if( !isset($variation_tables) || count($variation_tables) < 1) {?>
									<div class="arprice_ab_testing_row_input_wrapper">
										<div class="arprice_ab_testing_row_input">
											<input type="hidden" name="arprice_ab_testing_variation_table[]" id="arprice_ab_testing_variation_table_0" />
											<dl class="arprice_selectbox arplite_restricted_view" data-id="arprice_ab_testing_variation_table_0">
												<dt>
													<span><?php esc_html_e('Select table','arprice-responsive-pricing-table'); ?></span>
													<svg viewBox="0 0 2000 1000" width="15px" height="15px"><g fill="#000000"><path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path></g></svg>
												</dt>
												<dd>
													<ul data-id="arprice_ab_testing_variation_table_0">
														<li data-value="" data-label="<?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?>"><?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?></li>
													</ul>
												</dd>
											</dl>
										</div>
										<div class="arprice_ab_testing_impression_wrapper">
											<div class="arprice_db_testing_impression_input_wrapper arplite_restricted_view">
												<input type="text" name="arprice_ab_testing_impression[]" data-row-id="0" class="arprice_db_testing_impression_input arp_numeric" />
												<span>%</span>
											</div>
										</div>
										<div class="arprice_db_testing_action_button_wrapper">
											<span id="arprice_add_ab_testing_table" class="arprice_ab_testing_button arplite_restricted_view"> + </span>
										</div>
									</div>
								<?php
								} else {
									$n = 0;
									$variation_table_label = esc_html__('Select Table','arprice-responsive-pricing-table');
									foreach( $variation_tables as $vkey => $vtable ){
										$vtable_id = $vtable['id'];
										$vtable_name = $wpdb->get_var( $wpdb->prepare( "SELECT table_name FROM `".$wpdb->prefix."arp_arprice` WHERE id = %d", $vtable_id ) );
										$variation_table_label = $vtable_name.' (ID:'.$vtable_id.')';
										$vtable_impression = $vtable['impression'];
										
									?>
									<div class="arprice_ab_testing_row_input_wrapper">
									<div class="arprice_ab_testing_row_input">
										<input type="hidden" name="arprice_ab_testing_variation_table[]" id="arprice_ab_testing_variation_table_<?php echo $n; ?>" value="<?php echo $vtable_id; ?>" />
										<dl class="arprice_selectbox" data-id="arprice_ab_testing_variation_table_<?php echo $n; ?>">
											<dt>
												<span><?php echo $variation_table_label; ?></span>
												<svg viewBox="0 0 2000 1000" width="15px" height="15px"><g fill="#000000"><path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path></g></svg>
											</dt>
											<dd>
												<ul data-id="arprice_ab_testing_variation_table_<?php echo $n; ?>">
													<li data-value="" data-label="<?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?>"><?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?></li>
												</ul>
											</dd>
										</dl>
									</div>
									<div class="arprice_ab_testing_impression_wrapper">
										<div class="arprice_db_testing_impression_input_wrapper">
											<input type="text" name="arprice_ab_testing_impression[]" data-row-id="<?php echo $n; ?>" class="arprice_db_testing_impression_input arp_numeric" value="<?php echo $vtable_impression; ?>" />
											<span>%</span>
										</div>
									</div>
									<div class="arprice_db_testing_action_button_wrapper">
										<span id="arprice_add_ab_testing_table" class="arprice_ab_testing_button"><i class="fas fa-plus fa-lg"></i></span>
									</div>
								<?php
									if( $n > 0 ){
								?>
										<span id="arprice_remove_ab_testing_table" data-row-id="<?php echo $n; ?>" class="arprice_ab_testing_remove_button"><i class="far fa-trash-alt fa-lg"></i></span>
								<?php
									}
								?></div><?php
									$n++;
									}
								} ?>
						</div>
					</div>
					
					<div class="arp_save_ab_testing_shortcode_wrapper">
						<button type="button" id="save_ab_testing_shortcode" class="save_ab_testing_shortcode arplite_restricted_view"><?php esc_html_e('Save','arprice-responsive-pricing-table'); ?></button>
						<div class="arp_save_ab_testing_notice_wrapper">
							<span><?php esc_html_e('Saving the A/B testing settings will reset the Analytics. Do you want to continue?', 'arprice-responsive-pricing-table') ?></span>
							<div class="arp_save_ab_testing_notice_button_wrapper">
								<span class="arp_save_ab_setting_btns arp_ok_btn" id="arp_ab_save_settings_ok"><?php esc_html_e('OK','arprice-responsive-pricing-table'); ?></span>
								<span class="arp_save_ab_setting_btns arp_cancel_btn" id="arp_ab_save_settings_cancel"><?php esc_html_e('Cancel','arprice-responsive-pricing-table'); ?></span>
							</div>
						</div>
					</div>
					<i class="fas fa-spinner fa-spin arp_ab_testing_shortcode_loader"></i>
				</div>
			</form>
			<div class="arp_ab_testing_sub_title arp_no_overflow"><?php esc_html_e('A/B Testing Analytics','arprice-responsive-pricing-table'); ?></div>
			<div class="arprice_ab_testing_inner_container">
				<div id="arprice_ab_testing_chart_container" class="arprice_ab_testing_chart_container"><span class="arp_ab_testing_no_chart_data" id="arp_nodata_msg">No Analytics to display</span></div>
			</div>
		</div>
	</div>
</div>

<div id="arprice_analytic_table" class="arprice_analytic_table" style="display:none;">
	<table id="chart_data">
		<thead>
			<tr>
				<th></th>
				<th><?php esc_html_e('Views','arprice-responsive-pricing-table'); ?></th>
				<th><?php esc_html_e('Clicks','arprice-responsive-pricing-table'); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<div class="arprice_ab_testing_raw_wrapper" style="display: none;">
	<div class="arprice_ab_testing_row_input_wrapper" data-row-id="{index}">
		<div class="arprice_ab_testing_row_input" >
			<input type="hidden" name="arprice_ab_testing_variation_table[]" id="arprice_ab_testing_variation_table_{index}" />
			<dl class="arprice_selectbox" data-id="arprice_ab_testing_variation_table_{index}">
				<dt>
					<span><?php esc_html_e('Select table','arprice-responsive-pricing-table'); ?></span>
					<svg viewBox="0 0 2000 1000" width="15px" height="15px"><g fill="#000000"><path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path></g></svg>
				</dt>
				<dd>
					<ul data-id="arprice_ab_testing_variation_table_{index}">
						<li data-value="" data-label="<?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?>"><?php esc_html_e('Select Table','arprice-responsive-pricing-table'); ?></li>
					</ul>
				</dd>
			</dl>
		</div>
		<div class="arprice_ab_testing_impression_wrapper">
			<div class="arprice_db_testing_impression_input_wrapper">
				<input type="text" name="arprice_ab_testing_impression[]" data-row-id="{index}" class="arprice_db_testing_impression_input arp_numeric" />
				<span>%</span>
			</div>
		</div>
		<div class="arprice_db_testing_action_button_wrapper">
			<span id="arprice_add_ab_testing_table" class="arprice_ab_testing_button"><i class="fas fa-plus fa-lg"></i></span>
		</div>
		<span id="arprice_remove_ab_testing_table" data-row-id="{index}" class="arprice_ab_testing_remove_button"><i class="far fa-trash-alt fa-lg"></i></span>
	</div>
</div>
<div class="arp_upgrade_modal" id="arplite_custom_css_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('Please upgrade to premium version to unlock this feature.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <a href="#" class="buy_now_button_link"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></a>
        <a href="#" class="learn_more_button_link"><?php esc_html_e('Learn More', 'arprice-responsive-pricing-table'); ?></a>
        <input type="hidden" name="arp_version" id="arp_version" value="<?php echo esc_html( $arpricelite_version ); ?>" />
        <input type="hidden" name="arp_request_version" id="arp_request_version" value="<?php echo esc_html( get_bloginfo('version') ); ?>" />

    </div>
</div>
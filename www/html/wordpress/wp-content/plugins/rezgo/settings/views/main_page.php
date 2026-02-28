<div class="wrap" id="rezgo_settings">
	<img src="<?php echo rezgo_embed_settings_image('rezgo-logo.svg'); ?>" id="rezgo-logo" />
	
  <div id="rezgo_notices">
	<?php if ($rezgoPluginUpdated) { ?>
      <div class="notice notice-success">
        <p>Your Rezgo options have been updated.</p>
      </div>
    <?php } ?>
  
    <?php if ($safe_mode_on) { ?>
      <div class="notice notice-error">	
        <p>It appears that <em>safe_mode</em> is enabled in your server's PHP settings. The Rezgo plugin requires safe_mode to be off for proper functioning.</p>
      </div>
    <?php } ?>
  
    <?php if ($open_basedir) { ?>
      <div class="notice notice-error">
        <p>A <em>open_basedir</em> restriction is in effect on your server. The Rezgo plugin requires there to be no open_basedir restriction for full functionality.</p>
      </div>
    <?php } ?>

    <?php if (rezgo_check_dir('write', WP_CONTENT_DIR) && file_exists(REZGO_CUSTOM_TEMPLATES) && is_dir(REZGO_CUSTOM_TEMPLATES)) { ?>
      <div class="notice notice-warning is-dismissible">
        <p>The <em>/wp-content</em> directory is currently writable. If you have already created a custom template directory, you should <a href="https://codex.wordpress.org/Changing_File_Permissions" target="_blank">change the directory permissions</a> back to unwritable.</p>
      </div>
    <?php } ?>
    
  </div>

	<p>Rezgo makes it easy for you to accept bookings on your tour or activity business WordPress site. To manage your Rezgo account, <a href="http://login.rezgo.com" target="_blank">login here</a>.</p>

	<h3>Getting Started</h3>

	<p>
		<ol>
			<li><a href="http://www.rezgo.com">Sign-up for a Rezgo account</a>.</li>
			<li>Setup your inventory and configure your account on Rezgo.</li>
			<li>Complete the Rezgo WordPress Plugin settings below.</li>
			<li>Create a Page and embed the Rezgo booking engine by using the shortcode: [rezgo_shortcode].</li>
			<li>
				<span>Ensure you are using a non default permalink structure.</span>&nbsp;
				<?php if ($permalinkStructure == '') { ?>
					<div id="rzg-perm-error" class="notice notice-error">
						<strong>You are currently using the default structure, which may not work correctly. <a href="/wp-admin/options-permalink.php">Click here</a> to change it.</strong>
					</div>
				<?php } else { ?>
					<span>Your current structure should work!</span>
				<?php } ?>
			</li>
		</ol>
	</p>

	<form method="post" action="">
		<?php echo settings_fields('rezgo_options'); ?>

		<div class="field_frame">
			<fieldset>
				<legend class="account_info">Account Information</legend>

				<dl>
					<dt class=note>Your Company Code and API Key can be found on the Rezgo settings page.</dt>

					<br><br>

					<dt>Rezgo Company Code:</dt>

					<dd>
						<input type="text" name="rezgo_cid" id="rezgo_cid" size="10" value="<?php echo $rezgoCID ?>" onkeyup="check_values()" />
					</dd>

					<dt>Rezgo API Key:</dt>

					<dd>
						<input type="text" name="rezgo_api_key" id="rezgo_api_key" size="20" value="<?php echo $rezgoApiKey ?>" onkeyup="check_values()" />
					</dd>

					<div class="api_box" id="check_values">
						<?php if ($rezgoCID && $rezgoApiKey) { ?>
							<?php if (!empty($companyName)) { ?>
								<span class="ajax_success">XML API Connected</span><br>
								<span class="ajax_success_message"><?php echo $companyName ?></span>
								<a href="http://<?php echo $companyDomain ?>.rezgo.com" class="ajax_success_url" target="_blank">
									<span><?php echo $companyDomain ?>.rezgo.com</span>
								</a>
							<?php } else { ?>
								<span class="ajax_error">XML API Error</span><br>
								<span class="ajax_error_message"><?php echo $companyError ?></span>
							<?php } ?>
						<?php } else { ?>
							<span style="required_missing">Information is missing</span>
						<?php } ?>
					</div>
				</dl>
			</fieldset>
		</div>

		<div class="field_frame">
			<fieldset>
				<legend class="recaptcha_key">Google Maps API Key</legend>

				<dl>
					<dt class=note>
						You must enter your own Google Maps API key and enable the Maps Embed API to display maps on your tour pages. <br />You can get your <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">maps API key here</a>.
					</dt>
					<br><br>
					<dt>Google Maps Key:</dt>
					<dd>
						<input type="text" name="rezgo_google_key" size="50"	value="<?php echo get_option('rezgo_google_key') ?>" />
					</dd>
				</dl>
			</fieldset>
		</div>

		<div class="field_frame">
			<fieldset>
				<legend class="recaptcha_key">Recaptcha API Keys</legend>

				<dl>
					<dt class=note>
						If you wish to use Recaptcha on your contact page, enter your API credentials here. You can get Recaptcha for free from
						<a href="http://www.google.com/recaptcha" target="_blank">Google</a>
						<div id="rezgo-recaptcha-notice">
							Rezgo only supports <b>reCAPTCHA v3</b>. Please ensure that your API credentials are compatible with reCAPTCHA v3.
						</div>
					</dt>
					<br><br>
					<dt>reCAPTCHA v3 Site Key:</dt>
					<dd>
						<input type="text" name="rezgo_captcha_pub_key" size="50"	value="<?php echo get_option('rezgo_captcha_pub_key') ?>" />
					</dd>
					<dt>reCAPTCHA v3 Secret Key:</dt>
					<dd>
						<input type="text" name="rezgo_captcha_priv_key" size="50" value="<?php echo get_option('rezgo_captcha_priv_key') ?>"/>
					</dd>
				</dl>
			</fieldset>
		</div>

		<div class="field_frame">
			<fieldset>
				<legend class="general_settings">General Settings</legend>

				<?php
				// OPTION rezgo_forward_secure
				// if forward secure is not yet set to anything, check it as default
				if (get_option('rezgo_forward_secure') === '' || get_option('rezgo_forward_secure') === false) {
					$forward_secure_checked = 'checked';
				} else {
					$forward_secure_checked = (get_option('rezgo_forward_secure')) ? 'checked' : '';
				}
				// OPTION rezgo_custom_template_use
				$rezgo_custom_template_use = get_option('rezgo_custom_template_use');
				?>

				<dl class="dl_general_settings">
					<!-- RESULTS LIMIT -->
					<div class="rezgo-general-settings-wrp" id="rezgo-results-limit-wrp">
						<dt class=note>How many results do you want to show on each page? We suggest 10. Higher numbers may have an impact on performance.</dt>
						<?php
						$results_num = get_option('rezgo_result_num');
						if (!$results_num) $results_num = 10;
						?>
						<dt>Number of results:</dt>
						<dd>
							<input type="text" name="rezgo_result_num" size="5" value="<?php echo $results_num ?>" />
						</dd>
						<div class="clear"></div>
					</div>

					<!-- TEMPLATES USE-->
					<div class="rezgo-general-settings-wrp" id="rezgo-cus-tmp-use-wrp">
						<dt class=note>If you wish to use your own custom template, check the option below. <a href="https://www.rezgo.com/support-article/create-custom-templates-for-the-rezgo-wordpress-plugin" target="_blank">Click here</a> to learn more about creating and using custom templates.</dt>
						<dt>Use a custom Rezgo template:</dt>
						<dd><input type="checkbox" id="rzg-cus-tmp-checkbox" name="rezgo_custom_template_use" value="1" <?php echo ($rezgo_custom_template_use) ? 'checked' : ''; ?> /></dd>
						<div class="clear"></div>
						<div id="rzg-use-tmp-msg"></div>
					</div>

					<!-- TEMPLATES SELECT-->
					<div class="rezgo-general-settings-wrp" id="rzg-cus-tmp-wrp" <?php echo ($rezgo_custom_template_use) ? '' : 'style="display: none""'; ?>>
						<div id="rezgo-template-select">
							<dt class=note>
								<span>Choose the Rezgo template you wish to use.<br />You can add new templates to <?php echo REZGO_CUSTOM_TEMPLATES; ?></span><br />
								<span>Reload this page to select it from the drop-down menu below.</span>
							</dt>
							<dt>Template:</dt>
							<dd>
								<select name="rezgo_template" id="template_select">
									<?php foreach(rezgo_get_tmp() as $v) { ?>
										<option value='<?php echo $v; ?>' <?php echo (get_option('rezgo_template') == $v) ? 'selected' : ''; ?>><?php echo $v; ?></option>
									<?php } ?>
								</select>
							</dd>
						</div>
						<div class="clear"></div>
						<div id="rzg-cus-tmp-msg"></div>
					</div>

					<!-- TEMPLATE MSG -->
					<div id="rzg-msg-wrp"></div>

					<!-- FORWARD SECURE -->
					<div class="rezgo-general-settings-wrp" id="rezgo-forward-secure-wrp">
						<dt class="note">If you do not have your own security certificate (SSL), you can forward users to the Rezgo white-label for bookings or gift card purchases.</dt>
						<dt>Forward secure page to Rezgo:</dt>
						<dd><input type="checkbox" class="rezgo-forward-secure-checkbox" name="rezgo_forward_secure" value="1" <?php echo $forward_secure_checked; ?> /></dd>
						<div class="clear"></div>
					</div>

					<!-- ALTERNATE URL -->
					<div class="rezgo-general-settings-wrp" id="rezgo-alternate-url-wrp" <?php echo ($forward_secure_checked) ? '' : 'style="display: none""'; ?>>
						<dt class="note">By default, Rezgo will use your current domain for the secure site. If you have another secure domain you want to use (such as secure.mysite.com) you can specify it here. Otherwise leave this blank.
						</dt>
						<dt>Alternate Secure URL:</dt>
						<dd><input type="text" name="rezgo_secure_url" size="50" value="<?php echo get_option('rezgo_secure_url'); ?>"/></dd>
						<div class="clear"></div>
					</div>
				</dl>	
			</fieldset>
		</div>

		<br/>

		<input type="submit" class="button-primary" value="Save Changes"/>

		<input type="hidden" name="rezgo_update" value="1"/>

		<input type="hidden" name="action" value="update"/>

		<input type="hidden" name="page_options" value="rezgo_cid,rezgo_api_key,rezgo_uri,rezgo_result_num"/>
	</form>

	<br clear="all"/>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	var cid_value = '<?php echo $rezgoCid; ?>';
	var key_value = '<?php echo $rezgoApiKey; ?>';
	var tmp = '<?php echo get_option('rezgo_template'); ?>';

	function check_values() {
		var cid = $('#rezgo_cid').val();
		var key = $('#rezgo_api_key').val();

		// do nothing if we changed nothing
		if (cid_value != cid || key_value != key) {
			cid_value = cid;
			key_value = key;

			if (cid && key) {
				$('#check_values').html('<img src="<?php echo rezgo_embed_settings_image('load.gif') ?>">');

				$('#check_values').load('<?php echo REZGO_URL_BASE.'/settings/settings_ajax.php'?>?cid=' + cid.trim() + '&key=' + key.trim());
			} else {
				reset_check();
			}
		}
	}
	function reset_check() {
		$('#check_values').html('<span style="required_missing">Information is missing.</span>');
	}
	function notify(sta, msg) {
		if (msg && msg.length > 0) {
			var $n;

			$n = '<div id="rzg-tmp-'+sta+'" class="notice notice-'+sta+' is-dismissible">';

			$n += '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';

			$n += '<ul>';

			msg.forEach(function(e){ $n += '<li>'+e+'</li>'; });

			$n += '</ul>';

			$n += '</div>';

			$('#rzg-msg-wrp').append($n);
		}
	}

	function use_tmp(req) {
		$('#rzg-use-tmp-msg').empty();

		if (req) { // Use custom template
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: {
					action: 'rezgo',
					method: 'template_ajax',
					event: 'rzg_use_cus_tmp'
				},
				success: function(data){
					if(data) var res = JSON.parse(data);

					if(typeof res !== 'undefined') {
						var tmp_status = (res.status)?'success':'error';

						notify(tmp_status, res.msg);

						if (res.status) {
							$('#rzg-cus-tmp-wrp').show();

							get_tmp();
						} else {
							$('#rzg-cus-tmp-checkbox').prop('checked',false);
						}
					}
				}
			});
		} else { // Use default template
			$('#rzg-cus-tmp-wrp').hide();

			$('#template_select').find('option').prop('selected',false);

			$('#template_select').find('option[name=default]').prop('selected', true);

			tmp = 'default';

			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: {
					action: 'rezgo',
					method: 'template_ajax',
					event: 'rzg_use_def_tmp'
				},
				success: function(data){
					if(data) var res = JSON.parse(data);
					var tmp_status = (res.status)?'success':'error';
					if(typeof res !== 'undefined') notify(tmp_status, res.msg);
				}
			});
		}
	}
	function set_tmp(req) {
		$('#rzg-cus-tmp-msg').empty();

		$.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: {
				action: 'rezgo',
				method: 'template_ajax',
				event: 'rzg_set_tmp',
				name: req
			},
			success: function(data){
				if(data) var res = JSON.parse(data);
				var tmp_status = (res.status)?'success':'error';
				if(typeof res !== 'undefined') {
					notify(tmp_status, res.msg);
				}
			}
		});
	}
	function get_tmp() {
		var opt, sel, $ta;

		$ta = $('#template_select');
		
		$ta.prop('disabled', true);

		$.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: {
				action: 'rezgo',
				method: 'template_ajax',
				event: 'rzg_get_tmp',
				type: 'json'
			},
			success: function(data){
				if(data) var res = JSON.parse(data);

				if(typeof res !== 'undefined') {
					$ta.empty();

					res.forEach(function(e) {
						sel = (tmp===e) ? 'selected' : '';

						opt = '<option value="'+e+'" '+sel+'>'+e+'</option>';

						$ta.append(opt);
					});
				}

				$ta.prop('disabled', false);
			}
		});
	}

	$('#template_select').change(function(){
		var req = $(this).val();

		set_tmp(req);
	});
	$(document).on('click','.rezgo-forward-secure-checkbox',function(e){
		if ($(this).prop('checked')) {
			$('#rezgo-alternate-url-wrp').show();
		} else {
			$('#rezgo-alternate-url-wrp').hide();
		}
	});
	$(document).on('click','#rzg-cus-tmp-checkbox',function(e){
		if ($(this).prop('checked')) {
			use_tmp(1);
		} else {
			use_tmp(0);
		}
	});
	$(document).on('click','.notice-dismiss',function(){
		$(this).parent('.notice').remove();
	})
	$(document).ready(function(){
		<?php if ($rezgo_custom_template_use) { ?>
			use_tmp(1);

			set_tmp('<?php echo get_option('rezgo_template'); ?>');
		<?php } ?>
	})
});
</script>

<?php

function rezgo_register_settings() {
	register_setting('rezgo_options', 'rezgo_cid');
	register_setting('rezgo_options', 'rezgo_api_key');
	register_setting('rezgo_options', 'rezgo_version');

	register_setting('rezgo_options', 'rezgo_google_key');
	
	register_setting('rezgo_options', 'rezgo_captcha_pub_key');
	register_setting('rezgo_options', 'rezgo_captcha_priv_key');

	register_setting('rezgo_options', 'rezgo_result_num');

	register_setting('rezgo_options', 'rezgo_custom_template_use');
	register_setting('rezgo_options', 'rezgo_template');

	register_setting('rezgo_options', 'rezgo_forward_secure');
	register_setting('rezgo_options', 'rezgo_secure_url');
	register_setting('rezgo_options', 'rezgo_all_secure');

	wp_register_style('rezgo_settings_css', plugins_url('/css/settings.css', __FILE__), array(), REZGO_PLUGIN_VERSION );

	if (!get_option('rezgo_version')) {
		add_option('rezgo_version', REZGO_PLUGIN_VERSION);
		update_option('rezgo_template', 'default');
	} else {
		update_option('rezgo_version', REZGO_PLUGIN_VERSION);
	}
}

function rezgo_plugin_menu() {
	//$icon = rezgo_embed_settings_image('icon.png');
    $icon = rezgo_base64_svg();
	$menu_page = add_menu_page('Rezgo Settings', 'Rezgo', 'manage_options', 'rezgo-settings', 'rezgo_plugin_settings', $icon);
	add_action('admin_print_styles-' . $menu_page, 'rezgo_plugin_admin_styles');
}

function rezgo_ajax() {
	global $site;

	$site = new RezgoSite();

	$method = sanitize_text_field($_REQUEST['method']);

	$get = '';

	if (preg_match('/(.+)(\?com=.+)/', $method, $matches)) {
		if (isset($matches[1])) {
			$method = $matches[1];
		}

		if (isset($matches[2])) {
			$get = $matches[2];
		}

		$method = str_replace('.php', '', $method);
	}

	include( dirname(plugin_dir_path(__FILE__)) . '/' . $method . '.php');

	die();
}

function rezgo_query_vars($query_vars){
	$query_vars[] = 'rezgo';

	return $query_vars;
}

function rezgo_parse_request($items){
	if (array_key_exists('rezgo', $items->query_vars)) {
		rezgo_plugin_scripts_and_styles();

		include( dirname(plugin_dir_path(__FILE__)) . '/frame_router.php');

		die();
	}
}

function rezgo_plugin_scripts_and_styles() {
	global $wp_styles;

	$path = ((REZGO_CUSTOM_TEMPLATE_USE) ? content_url() : plugins_url().'/rezgo') . '/rezgo/templates/' . REZGO_TEMPLATE . '/';

	// JS FILES 
	$jsIframeresizer = $path.'js/iframeResizer/iframeResizer.min.js';
	$jsIframeresizerContentWindow = $path.'js/iframeResizer/iframeResizer.contentWindow.min.js';
	$jsBootstrap = $path.'js/bootstrap.min.js';
	$jsJqueryForm = $path.'js/jquery.form.js';
	$jsJqueryValidate = $path.'js/jquery.validate.min.js';
	$jsJquerySelect = $path.'js/jquery.selectboxes.js';
	$jsCalendar = $path.'js/responsive-calendar.min.js';
	$jsBarcode = $path.'js/JsBarcode.all.min.js';
	$jsIntlTelInput = $path.'js/intlTelInput/intlTelInput.min.js';
	//$jsBirthday = $path.'js/bootstrap-birthday.js';
	$jsSig = $path.'js/signature_pad.min.js';
	$jsSigBlank = $path.'js/signature_pad_remove_blank.js';
	//$jsDatePicker = $path.'js/bootstrap-datepicker.min.js';
	$jsChosen = $path.'js/chosen.jquery.min.js';
	$jsReadmore = $path.'js/jquery.readmore.min.js';

	// CSS FILES
	$cssBootstrap = $path.'css/bootstrap.min.css';
	$cssFontAwesome = $path.'css/font-awesome/css/all.min.css';
	$cssRezgo = $path.'css/rezgo.css';
	$cssRezgoNew = $path.'css/rezgo-2020.css';
	$cssModal = $path.'css/rezgo-modal.css';
	$cssBootModal = $path.'css/bootstrap-modal.css';
	$cssSignature = $path.'css/signature-pad.css';
	$cssCalendar = $path.'css/responsive-calendar.css';
	$cssCalendarRezgo = $path.'css/responsive-calendar.rezgo.css';
	$cssIntlTelInput = $path.'css/intlTelInput.css';
	$cssChosen = $path.'css/chosen.min.css';

	// ENQUEUES
	wp_enqueue_style( 'css-bootmodal', $cssBootModal);
	wp_enqueue_style( 'css-modal', $cssModal, array(), REZGO_PLUGIN_VERSION );
	
	//if(!isset($_REQUEST['headless'])) {
		wp_enqueue_script('iframe-resizer', $jsIframeresizer, array('jquery'), null, false);
		wp_enqueue_script( 'js-iframe-content-window', $jsIframeresizerContentWindow );
	//}

	if (isset($_REQUEST['rezgo'])) {
		wp_enqueue_style( 'css-bootstrap', $cssBootstrap, array(), '3.3.5' );
		wp_enqueue_style( 'css-font-awesome', $cssFontAwesome, array(), '4.6.3' );
		wp_enqueue_style( 'css-rezgo', $cssRezgo, array(), REZGO_PLUGIN_VERSION );
		wp_enqueue_script( 'js-bootstrap', $jsBootstrap, array('jquery') );
		// load new rezgo styles on print versions
		if (strpos($_SERVER['REQUEST_URI'], '/print')) wp_enqueue_style( 'css-rezgo-new', $cssRezgoNew, array(), REZGO_PLUGIN_VERSION );
	}

	$pages = array(
		'page_order',
		'page_book',
		'gift_card',
		'gift_card_not_found',
		'booking_payment',
		'booking_complete',
	);

	if (in_array($_REQUEST['mode'], $pages)) {
		wp_enqueue_style( 'css-rezgo-new', $cssRezgoNew, array(), REZGO_PLUGIN_VERSION );
		// wp_enqueue_script( 'js-form', $jsJqueryForm);
	}

	if (!isset($_REQUEST['mode'])) {
		return;
	}

	$arr = array(
		'page_details',
		'page_contact',
		'page_order',
		'page_book',
		'gift_card',
	);

	if (in_array($_REQUEST['mode'], $arr) || (isset($_REQUEST['method']) && $_REQUEST['method'] == 'booking_payment')) {
		wp_enqueue_script( 'js-form', $jsJqueryForm);
		wp_enqueue_script( 'js-validate', $jsJqueryValidate);
		wp_enqueue_script( 'js-selectbox', $jsJquerySelect);
	}

	if ($_REQUEST['mode'] == 'page_details') {
		wp_enqueue_style( 'css-calendar', $cssCalendar);
		wp_enqueue_style( 'css-calendar-rezgo', $cssCalendarRezgo);
		wp_enqueue_script( 'js-calendar', $jsCalendar);  
		wp_enqueue_script( 'js-readmore', $jsReadmore);
	}

	$arr = array(
		'booking_voucher',
		'gift_card_details',
		'gift_card_print'
	);

	if (in_array($_REQUEST['mode'], $arr)) {
		wp_enqueue_script( 'js-barcode', $jsBarcode);
	}

	if ($_REQUEST['mode'] == 'page_book') {
		wp_enqueue_script( 'js-IntlTelInput', $jsIntlTelInput);
		//wp_enqueue_script( 'js-datepicker', $jsDatePicker);
		wp_enqueue_script( 'js-chosen', $jsChosen);
		wp_enqueue_style( 'css-IntlTelInput', $cssIntlTelInput);
		wp_enqueue_style( 'css-modal', $cssModal);
		wp_enqueue_style( 'css-bootmodal', $cssBootModal);
		wp_enqueue_style( 'css-chosen', $cssChosen);
	}

	if ($_REQUEST['mode'] == 'modal') {
		wp_enqueue_script( 'js-form', $jsJqueryForm);
		wp_enqueue_script( 'js-validate', $jsJqueryValidate);
		//wp_enqueue_script( 'js-birthday', $jsBirthday);
		wp_enqueue_script( 'js-signature', $jsSig);
		wp_enqueue_script( 'js-signature-blank', $jsSigBlank);
		wp_enqueue_style( 'css-modal', $cssModal);
		wp_enqueue_style( 'css-bootmodal', $cssBootModal);
		wp_enqueue_style( 'css-signature', $cssSignature);
	}
	
}

function rezgo_plugin_admin_styles() {
	wp_enqueue_style('rezgo_settings_css');
	wp_enqueue_script('rezgo_settings_js');
}

function rezgo_plugin_settings_link($links){
	$settings_link = '<a href="admin.php?page=rezgo-settings">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function rezgo_plugin_settings() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	$rezgoPluginUpdated = false;

	if (isset($_POST['rezgo_update'])) {
		rezgo_plugin_settings_update();
		$rezgoPluginUpdated = true;
	}

	$rezgoCID = get_option('rezgo_cid');
	$rezgoApiKey = get_option('rezgo_api_key');
	$companyName = '';
	$companyDomain = '';

	if (!empty($rezgoCID) && !empty($rezgoApiKey)) {
		$xmlCheckOutput = rezgo_curl_get_page('http://xml.rezgo.com/xml?transcode=' . $rezgoCID . '&key=' . $rezgoApiKey . '&i=company');
		/**
		* @TODO change check of output result
		*/
		if ((string)$xmlCheckOutput->company_name) {
			$companyName = (string)$xmlCheckOutput->company_name;
			$companyDomain = (string)$xmlCheckOutput->domain;
		}
	}

	rezgo_render_settings_view('main_page.php', array(
		'permalinkStructure' => get_option('permalink_structure'),
		'rezgoCID' => get_option('rezgo_cid'),
		'rezgoApiKey' => get_option('rezgo_api_key'),
		'companyName' => $companyName,
		'companyDomain' => $companyDomain,
		'rezgoPluginUpdated' => $rezgoPluginUpdated,
		'safe_mode_on' => ini_get('safe_mode'),
		'open_basedir' => ini_get('open_basedir')
		)
	);
}

function rezgo_plugin_settings_update() {
	if (isset($_POST['rezgo_secure_url'])) {
		$_POST['rezgo_secure_url'] = str_replace("http://", "", $_POST['rezgo_secure_url']);
		$_POST['rezgo_secure_url'] = str_replace("https://", "", $_POST['rezgo_secure_url']);
	}

	if (!isset($_POST['rezgo_result_num'])) {
		$_POST['rezgo_result_num'] = 10;
	}

	update_option('rezgo_cid', $_POST['rezgo_cid']);
	update_option('rezgo_api_key', $_POST['rezgo_api_key']);

	update_option('rezgo_google_key', $_POST['rezgo_google_key']);

	update_option('rezgo_captcha_pub_key', $_POST['rezgo_captcha_pub_key']);
	update_option('rezgo_captcha_priv_key', $_POST['rezgo_captcha_priv_key']);

	update_option('rezgo_result_num', $_POST['rezgo_result_num']);
	
	if (!isset($_POST['rezgo_custom_template_use']) || $_POST['rezgo_template']=='default') {
		$_POST['rezgo_custom_template_use'] = 0;
	}
	update_option('rezgo_custom_template_use', $_POST['rezgo_custom_template_use']);

	if (!isset($_POST['rezgo_forward_secure'])) {
		$_POST['rezgo_forward_secure'] = 0;
	}
	update_option('rezgo_template', $_POST['rezgo_template']);
	
	if (!isset($_POST['rezgo_all_secure'])) {
		$_POST['rezgo_all_secure'] = 0;
	}
	update_option('rezgo_all_secure', $_POST['rezgo_all_secure']);

	update_option('rezgo_forward_secure', $_POST['rezgo_forward_secure']);
	update_option('rezgo_secure_url', $_POST['rezgo_secure_url']);
	
	return true;
}

function rezgo_check_dir($action, $dir){
	// CHECK IF $DIR EXIST
	if (file_exists($dir) && is_dir($dir)) {
		if($action==='write') {
			if(is_writable($dir)) {
				return 1;
			} else {
				return 0;
			}
		}

		if($action==='subdir') {
			$tmp = glob($dir . '/*' , GLOB_ONLYDIR);

			if ($tmp) {
				return $tmp;
			} else {
				return 0;
			}
		}
	} else {
		return 0;
	}
}

function rezgo_recurse_copy($src, $dst){
	$dir = opendir($src);

	@mkdir($dst,0755, true); 

	while(false !== ($file = readdir($dir))) { 
		if (($file != '.') && ($file != '..')) { 
			if (is_dir($src . '/' . $file)) { 
				rezgo_recurse_copy($src . '/' . $file,$dst . '/' . $file); 
			} else { 
				copy($src . '/' . $file,$dst . '/' . $file); 
			}
		}
	}

	closedir($dir); 
}

function rezgo_copy_templates($tmp){
	foreach($tmp as $v){
		$basename = basename($v);

		if(!file_exists(REZGO_CUSTOM_TEMPLATES.'/'.$basename) && !is_dir(REZGO_CUSTOM_TEMPLATES.'/'.$basename)){
			if($basename !== 'default') {
				rezgo_recurse_copy(REZGO_DEFAULT_TEMPLATES.'/'.$basename, REZGO_CUSTOM_TEMPLATES.'/'.$basename);
			} else {
				rezgo_recurse_copy(REZGO_DEFAULT_TEMPLATES.'/'.$basename, REZGO_CUSTOM_TEMPLATES.'/rezgo-custom');
			}
		}
	}
}

function rezgo_use_cus_tmp() {
	$msg = array();

	$status = 1;

	$old_umask = umask(0);

	// Check if /wp-content/rezgo/templates/ dir exists..
	if(file_exists(REZGO_CUSTOM_TEMPLATES) && is_dir(REZGO_CUSTOM_TEMPLATES)) {
		
		// if empty /wp-content/rezgo/templates/
		if (!rezgo_check_dir('subdir', REZGO_CUSTOM_TEMPLATES)) {
			
			// copy all templates from REZGO_DEFAULT_TEMPLATES to REZGO_CUSTOM_TEMPLATES
			$dir = rezgo_check_dir('subdir', REZGO_DEFAULT_TEMPLATES);

			$opt = get_option('rezgo_template');

			rezgo_copy_templates($dir);

			$msg[] = 'New custom templates created in <em>‘/wp-content/rezgo/templates’</em>';
			
		}
		
	} else {
		
		if(rezgo_check_dir('write', WP_CONTENT_DIR)) {
			
			if(mkdir(WP_CONTENT_DIR.'/rezgo', 0755, true) && mkdir(REZGO_CUSTOM_TEMPLATES, 0755, true)) {
				
				$tmp = rezgo_check_dir('subdir', REZGO_DEFAULT_TEMPLATES);

				rezgo_copy_templates($tmp);

				$msg[] = 'New custom templates created in <em>‘/wp-content/rezgo/templates’</em>';
				
			} else {
				
				$msg[] = 'Plugin failed creating custom template. Custom templates should be moved into new location <em>‘/wp-content/rezgo/templates/’</em>';

				$status = 0;
			}
			
		} else {
			$status = 0;
		}
		
	}

	umask($old_umask);

	if(!$status) {
		$msg[] = 'If you wish to use a custom plugin template, you will need to ensure that the <em>‘wp-content‘</em> directory is writable.';
	}

	$res = array('status'=>$status,'msg'=>$msg);

	echo json_encode($res);
}

function rezgo_use_def_tmp() {
	update_option('rezgo_custom_template_use', 0);

	update_option('rezgo_template', 'default');

	echo json_encode(array('status'=>1,'msg'=>null));
}

function rezgo_set_tmp() {
	$status = 1;
	$msg = array();

	if ($_POST['name'] == 'default') {
		update_option('rezgo_template', 'default');
	} else {
		$dir = REZGO_CUSTOM_TEMPLATES .'/'. $_POST['name'];

		if (file_exists($dir) && is_dir($dir)) {
			update_option('rezgo_template', $_POST['name']);
		} else {
			update_option('rezgo_template', 'default');

			$status = 0;
			$msg[] = 'Warning, your custom directory does not exist.';
		}
	}

	echo json_encode(array('status'=>$status,'msg'=>$msg));
}

function rezgo_get_tmp() {
	$res = array('default');

	if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
		if ($tmp = rezgo_check_dir('subdir', REZGO_CUSTOM_TEMPLATES)) {
			foreach($tmp as $v) {
				if(basename($v) !== 'default') $res[] = basename($v);
			}
		}
	}

	if ($_POST['type']=='json') {
		echo json_encode($res);
		exit;
	} else {
		return $res;
	}
}

function update_recaptcha_notice() {
	$plugin_ver = 4.0;
	$update_notice = ((int) get_option('rezgo_version') >= $plugin_ver) ? true : false;
    $user_id = get_current_user_id();

    if ( !get_user_meta( $user_id, 'dismiss_recaptcha_notice' ) && $update_notice ) {
        echo '
		<div class="wrap">
			<div id="rezgo-recaptcha-notice" class="notice notice-success" style="position:relative;">
				<p>Rezgo has been successfully updated to version 4.0 and now only supports reCAPTCHA v3. 
				<br>Here is <a href ="https://www.rezgo.com/support-article/using-recaptcha-v3-with-the-rezgo-wordpress-plugin" target="_blank">a quick guide</a> on how to update your reCAPTCHA credentials.</p>

				<a href="?dismiss-recaptcha-notice" id="dismiss-recaptcha-notice" type="button" class="notice-dismiss" style="text-decoration:none;"><span class="screen-reader-text">Dismiss this notice.</span></a>
			</div>
		</div>';
	}
}

function dismiss_recaptcha_notice() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['dismiss-recaptcha-notice'] ) ) {
        add_user_meta( $user_id, 'dismiss_recaptcha_notice', 'true', true );
		// refresh current page
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
}

add_action('admin_init', 'rezgo_register_settings');
add_action('admin_menu', 'rezgo_plugin_menu');
add_filter('query_vars', 'rezgo_query_vars');
add_action('parse_request', 'rezgo_parse_request');
add_action('wp_ajax_nopriv_rezgo', 'rezgo_ajax');
add_action('wp_ajax_rezgo', 'rezgo_ajax');
add_action('wp_enqueue_scripts', 'rezgo_plugin_scripts_and_styles');
add_filter('plugin_action_links_rezgo/rezgo.php', 'rezgo_plugin_settings_link');
add_action('admin_notices', 'update_recaptcha_notice');
add_action('admin_init', 'dismiss_recaptcha_notice');
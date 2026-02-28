<?php
/*
  Plugin Name: ARPrice Lite
  Description: Responsive WordPress Pricing Table / Team Showcase Plugin
  Version: 3.6
  Plugin URI: http://arpriceplugin.com
  Author: Repute InfoSystems
  Author URI: http://arpriceplugin.com
  Text Domain: arprice-responsive-pricing-table
  Domain Path: /languages
 */


$arplite_plugin_url = plugins_url( '', __FILE__ );
define( 'ARPLITEURL', $arplite_plugin_url );

define( 'ARPLITE_PRICINGTABLE_DIR', plugin_dir_path( __FILE__ ) );
define( 'ARPLITE_PRICINGTABLE_URL', ARPLITEURL );
define( 'ARPLITE_PRICINGTABLE_CORE_DIR', ARPLITE_PRICINGTABLE_DIR . '/core' );
define( 'ARPLITE_PRICINGTABLE_CLASSES_DIR', ARPLITE_PRICINGTABLE_DIR . '/core/classes' );
define( 'ARPLITE_PRICINGTABLE_CLASSES_URL', ARPLITE_PRICINGTABLE_URL . '/core/classes' );
define( 'ARPLITE_PRICINGTABLE_IMAGES_URL', ARPLITE_PRICINGTABLE_URL . '/images' );
define( 'ARPLITE_PRICINGTABLE_INC_DIR', ARPLITE_PRICINGTABLE_DIR . '/inc' );
define( 'ARPLITE_PRICINGTABLE_VIEWS_DIR', ARPLITE_PRICINGTABLE_DIR . '/core/views' );
define( 'ARPLITE_PRICINGTABLE_MODEL_DIR', ARPLITE_PRICINGTABLE_DIR . '/core/models' );

if ( ! defined( 'FS_METHOD' ) ) {
	define( 'FS_METHOD', 'direct' );
}

if ( is_ssl() ) {
	define( 'ARPLITE_HOME_URL', home_url( '', 'https' ) );
} else {
	define( 'ARPLITE_HOME_URL', home_url() );
}

if ( file_exists( ARPLITE_PRICINGTABLE_CORE_DIR . '/vc/class_vc_extend.php' ) ) {
	include_once ARPLITE_PRICINGTABLE_CORE_DIR . '/vc/class_vc_extend.php';
	global $arpricelite_vdextend;
	$arpricelite_vdextend = new ARPrice_lite_VCExtendArp();
}


$wpupload_dir = wp_upload_dir();
$upload_dir   = $wpupload_dir['basedir'] . '/arprice-responsive-pricing-table';
$upload_url   = $wpupload_dir['baseurl'] . '/arprice-responsive-pricing-table';

if ( is_ssl() ) {
	$upload_url = str_replace( 'http://', 'https://', $wpupload_dir['baseurl'] . '/arprice-responsive-pricing-table' );
} else {
	$upload_url = $wpupload_dir['baseurl'] . '/arprice-responsive-pricing-table';
}

wp_mkdir_p( $upload_dir );

$css_upload_dir = $upload_dir . '/css';
wp_mkdir_p( $css_upload_dir );

$template_images_upload_dir = $upload_dir . '/template_images';
wp_mkdir_p( $template_images_upload_dir );

$arp_import_dir = $upload_dir . '/import';
wp_mkdir_p( $arp_import_dir );

define( 'ARPLITE_PRICINGTABLE_UPLOAD_DIR', $upload_dir );

define( 'ARPLITE_PRICINGTABLE_UPLOAD_URL', $upload_url );

global $arplite_pricingtable;
$arplite_pricingtable = new ARPlite_PricingTable();

/* Defining Pricing Table Version */
global $arpricelite_version;
$arpricelite_version = '3.6';

global $arpricelite_assset_version;
$arpricelite_assset_version = $arpricelite_version . '_' . rand(1000,9999);

global $arpricelite_img_css_version;
$arpricelite_img_css_version = '2.0';

global $arplite_images_css_previous_version;
$arplite_images_css_previous_version = '1.0';

/* Defining Rolls for Pricing table Plugin */
global $arplite_allrole;
$arplite_allrole = array( 'editor', 'author', 'contributor', 'subscriber' );

global $arplite_subscription_time;
$arplite_subscription_time = '15';

global $pricingtableliteajaxurl;
$pricingtableliteajaxurl = admin_url( 'admin-ajax.php' );

if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice.php';
}

if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_form.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_form.php';
}

if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_analytics.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_analytics.php';
}

if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_import_export.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_import_export.php';
}
if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arp_fonts.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arp_fonts.php';
}

if ( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arp_default_settings.php' ) ) {
	require_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arp_default_settings.php';
}

if( file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_file_handler.php' ) ){
    require_once( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_file_handler.php' );
}

if ( is_plugin_active( 'elementor/elementor.php' ) && file_exists( ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arpricelite_elementor.php' ) ) {
	include_once ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arpricelite_elementor.php';

	global $arpricelite_elementor;
	$arpricelite_elementor = new arpriceliteelementcontroller();
}

global $arpricelite_class;
$arpricelite_class = new arpricelite();

global $arpricelite_form;
$arpricelite_form = new arpricelite_form();

global $arpricelite_analytics;
$arpricelite_analytics = new arpricelite_analytics();

global $arpricelite_import_export;
$arpricelite_import_export = new arpricelite_import_export();

global $arpricelite_fonts;
$arpricelite_fonts = new arpricelite_fonts();

global $arpricelite_default_settings;
$arpricelite_default_settings = new arplite_default_settings();

global $arplite_mainoptionsarr;
global $arplite_coloptionsarr;
global $arplite_tempbuttonsarr;
global $arplite_templateorderarr;
global $arplite_templatecssinfoarr;
global $arplite_templateresponsivearr;
global $arplite_template_editor_arr;
global $arplite_templatesectionsarr;
global $arplite_templatecustomskinarr;
global $arplite_templatehoverclassarr;

global $arplite_is_animation, $arplite_has_tooltip, $arplite_has_fontawesome, $arplite_effect_css, $arplite_switch_css;
$arplite_is_animation    = 0;
$arplite_has_tooltip     = 0;
$arplite_has_fontawesome = 0;
$arplite_effect_css      = 0;
$arplite_switch_css      = 0;

if ( class_exists( 'WP_Widget' ) ) {
	include_once ARPLITE_PRICINGTABLE_DIR . '/core/widgets/arprice_widget.php';
	add_action( 'widgets_init', 'arpricelite_widget_function' );

	function arpricelite_widget_function() {
		return register_widget( 'arpricelite_widget' );
	}
}

class ARPlite_PricingTable {


	function __construct() {
		register_activation_hook( __FILE__, array( 'ARPlite_PricingTable', 'arpricelite_install' ) );

		register_activation_hook( __FILE__, array( 'ARPlite_PricingTable', 'arpricelite_check_network_activation' ) );

		register_uninstall_hook( __FILE__, array( 'ARPlite_PricingTable', 'uninstall' ) );

		add_action( 'admin_menu', array( $this, 'pricingtablelite_menu' ), 27 );

		add_action( 'wp_ajax_editplan', array( $this, 'editplan' ) );

		add_action( 'wp_ajax_editpackage', array( $this, 'editpackage' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'set_css' ), 10 );

		add_action( 'admin_enqueue_scripts', array( $this, 'set_js' ), 10 );

		add_action( 'wp_head', array( $this, 'set_front_css' ), 1 );

		add_action( 'wp_head', array( $this, 'set_front_js' ), 1 );

		add_action( 'init', array( $this, 'arplite_pricing_table_main_settings' ) );

		add_action( 'plugins_loaded', array( $this, 'arplite_pricing_table_load_textdomain' ) );

		add_action( 'wp_head', array( $this, 'arplite_enqueue_template_css' ), 1, 0 );
		add_action( 'wp_head', array( $this, 'arplite_front_assets' ), 1, 0 );

		add_action( 'arplite_enqueue_preview_style', array( $this, 'arplite_enqueue_preview_css' ), 1, 4 );

		add_action( 'admin_init', array( $this, 'arplite_db_check' ) );

		add_filter( 'admin_footer_text', array( $this, 'replace_footer_admin' ) );

		add_filter( 'update_footer', array( $this, 'arplite_replace_footer_version' ), '1234' );

		add_action( 'admin_head', array( $this, 'arplite_hide_update_notice_to_all_admin_users' ), 10000 );

		add_action( 'wp_footer', array( $this, 'footer_js' ), 1, 0 );

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'wp-rocket/wp-rocket.php' ) && ! is_admin() ) {
			add_filter( 'script_loader_tag', array( $this, 'arp_prevent_rocket_loader_script' ), 10, 2 );
		}

		add_action( 'user_register', array( $this, 'arp_add_capabilities_to_new_user' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'arplite_gutenberg_capability' ) );

		add_action( 'set_user_role', array( $this, 'arp_add_capabilities_to_user_role' ), 10, 3 );

		add_filter( 'safe_style_css', array( $this, 'arp_allow_style_attr' ) );

		add_action( 'arplite_enqueue_internal_script', array( $this, 'arplite_enqueue_inline_editor_js' ) );

		add_action( 'arplite_add_tour_guide_js', array( $this, 'arplite_add_tour_guide_js' ) );

		add_action( 'arplite_enqueue_internal_style', array( $this, 'arplite_enqueue_inline_editor_css' ) );

		add_action( 'admin_init', array( $this, 'arplite_invalid_template_redirection' ) );

		add_action( 'admin_init', array( $this, 'arplite_redirect_with_nonce_url' ) );

		if( is_plugin_active( 'elementor/elementor.php' ) ){
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'arplite_enqueue_elementor_css' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'arplite_admin_editor_styles') );

		add_action( 'arplite_front_inline_css', array( $this, 'arplite_front_inline_css_callback' ), 10, 2 );

		add_action( 'arplite_display_ratenow_popup', array( $this, 'arplite_display_ratenow_popup_callback') );

		add_action( 'admin_notices', array( $this, 'arplite_display_notice_for_rating') );

		add_action( 'admin_notices', array( $this, 'arplite_display_news_notices' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'arplite_enqueue_notice_assets') );

		add_action( 'wp_ajax_arplite_dismiss_rate_notice', array( $this, 'arplite_reset_ratenow_notice') );

		add_action( 'wp_ajax_arplite_dismiss_rate_notice_no_display', array( $this, 'arplite_reset_ratenow_notice_never') );

		add_action( 'wp_ajax_arplite_dismiss_news', array( $this, 'arplite_dismiss_news_notice' ) );

		add_action( 'admin_init', array( $this, 'arplite_display_sale_popup' ) );

		add_filter( 'cron_schedules', array( $this, 'arplite_add_cron_schedule' ) );

		add_action( 'arplite_display_sale_upgrade_popup', array( $this, 'arplite_enable_sale_popup' ) );

		add_action( 'wp_ajax_arplite_disable_sale_popup', array( $this, 'arplite_disable_sale_popup') );

		add_action( 'wp_ajax_arplite_regenerate_nonce', array( $this, 'arplite_regenerate_nonce' ) );

		add_action( 'login_footer', array( $this, 'arplite_login_footer' ), 30 );

	}

	function arplite_login_footer(){

		$arplite_script  = '<script type="text/javascript" data-cfasync="false">';

			$arplite_script .= 'if( typeof window.parent.adminpage != "undefined" && window.parent.adminpage == "toplevel_page_arpricelite" ){';
                $arplite_script .= 'if( document.getElementById("loginform") == null && window.parent.arplite_regenerate_nonce != null ){';
                    $arplite_script .= ' window.parent.arplite_regenerate_nonce(); ';
                $arplite_script .= '}';
            $arplite_script .= '} else if( window.opener != null && typeof window.opener.adminpage != "undefined" && window.opener.adminpage == "toplevel_page_arpricelite"){';
                $arplite_script .= 'if( document.getElementById("loginform") == null && window.opener != null && window.opener.arplite_regenerate_nonce != null ){';
                    $arplite_script .= ' window.opener.arplite_regenerate_nonce( true ); ';
                    $arplite_script .= ' window.close() ';
                $arplite_script .= '}';
            $arplite_script .= '}';

		$arplite_script .= '</script>';

		echo $arplite_script;

	}

	function arplite_regenerate_nonce(){
		echo json_encode(
            array(
            	'arplite_page_nonce' => wp_create_nonce('arplite_page_nonce'),
                '_wpnonce_arplite' => wp_create_nonce( 'arplite_wp_nonce' )
            )
        );
        die;
	}

	function arplite_disable_sale_popup(){
		update_option( 'arplite_display_bf_sale_popup', 0 );
	}

	function arplite_enable_sale_popup(){
		update_option( 'arplite_display_bf_sale_popup', 1 );
	}

	function arplite_add_cron_schedule( $schedules ){

		$schedules['every_twelve_hours'] = array(
			'interval' => 43200,
			'display' => __( 'Every 12 hours', 'arprice-responsive-pricing-table' )
		);

		return $schedules;
	}

	function arplite_display_sale_popup(){

		if( !empty( $_GET['page'] ) && 'arpricelite' == $_GET['page'] && empty( $_GET['arp_action'] ) ){
			if( current_time( 'timestamp' ) < strtotime('2020-12-06') ){
				if( !wp_next_scheduled( 'arplite_display_sale_upgrade_popup' ) ){
					wp_schedule_event( time(), 'every_twelve_hours', 'arplite_display_sale_upgrade_popup' );
				}
			} else {
				update_option( 'arplite_display_bf_sale_popup', 0 );
			}

		}

	}

	function arplite_reset_ratenow_notice_never(){
		update_option('arplite_display_rating_notice', 'no');
		update_option('arplite_never_display_rating_notice','true');
		die;
	}

	function arplite_reset_ratenow_notice(){

		$nextEvent = strtotime( '+60 days' );

		wp_schedule_single_event( $nextEvent, 'arplite_display_ratenow_popup' );

		update_option( 'arplite_display_rating_notice', 'no' );

		die;
	}

	function arplite_enqueue_notice_assets(){
		global $arpricelite_version;
		wp_register_script( 'arplite-admin-notice-script', ARPLITE_PRICINGTABLE_URL . '/js/arplite-admin-notice.js', array(), $arpricelite_version );

		wp_enqueue_script( 'arplite-admin-notice-script' );
	}

	function arplite_display_ratenow_popup_callback(){
		update_option('arplite_display_rating_notice','yes');
	}

	function arplite_dismiss_news_notice(){

		$noticeId = isset( $_POST['notice_id'] ) ? $_POST['notice_id'] : '';

		if( '' != $noticeId ){
			update_option( 'arp_' . $noticeId . '_is_dismissed', true );
		}

	}

	function arplite_display_news_notices(){

		$arplite_news = get_transient( 'arplite_news' );

		if( false == $arplite_news ){

			$url = 'https://www.arpriceplugin.com/download_samples/arpricelite_news.php';

			$raw_response = wp_remote_post(
				$url,
				array(
					'timeout' => 5000
				)
			);

			if( !is_wp_error( $raw_response ) && 200 == $raw_response['response']['code'] ){

				$news = json_decode( $raw_response['body'], true );

			} else {
				$news = array();
			}

			set_transient( 'arplite_news', json_encode( $news ), DAY_IN_SECONDS );
		} else {
			$news = json_decode( $arplite_news, true );
		}

		$current_date = date('Y-m-d');

		foreach( $news as $news_id => $news_data ){
			$isAlreadyDismissed = get_option( 'arp_' . $news_id . '_is_dismissed' );

			if( '' == $isAlreadyDismissed ){
				$class = 'notice notice-warning arplite-news-notice is-dismissible';
				$message = $news_data['description'];
				$start_date = strtotime( $news_data['start_date'] );
				$end_date = strtotime( $news_data['end_date'] );

				$current_timestamp = strtotime( $current_date );

				if( $current_timestamp >= $start_date && $current_timestamp <= $end_date ){
					$background_color = ( isset( $news_data['background'] )  && '' != $news_data['background'] ) ? 'background:' . $news_data['background'] .';' : '';
					$font_color = ( isset( $news_data['color'] )  && '' != $news_data['color'] ) ? 'color:' . $news_data['color'] .';' : '';
					$border_color = ( isset( $news_data['border'] )  && '' != $news_data['border'] ) ? 'border-left-color:' . $news_data['border'] .';' : '';

					printf(
						'<div class="%1$s" style="%2$s%3$s%4$s" id="%5$s"><p>%6$s</p></div>',
						esc_attr( $class ),
						esc_attr( $background_color ),
						esc_attr( $font_color ),
						esc_attr( $border_color ),
						esc_attr( $news_id ),
						wp_kses( $message, $this->arpricelite_allowed_html_tags() )
					);
				}

			}
		}

	}

	function arplite_display_notice_for_rating(){
		$display_notice = get_option('arplite_display_rating_notice');
		$display_notice_never = get_option('arplite_never_display_rating_notice');

		if( '' != $display_notice && 'yes' == $display_notice && ( '' == $display_notice_never || 'yes' != $display_notice_never ) ){
			$class = 'notice notice-warning arplite-rate-notice is-dismissible';
			$message = "Hey, you've been using <strong>ARPrice Lite</strong> for a long time. <br/>Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation. <br/><br/>Your help is much appreciated. Thank you very much - <strong>Repute InfoSystems</strong>";
			$rate_link = 'https://wordpress.org/support/plugin/arprice-responsive-pricing-table/reviews/';
			$rate_link_text = esc_html__('OK, you deserve it','arprice-responsive-pricing-table');
			$close_btn_text = esc_html__('No, Maybe later','arprice-responsive-pricing-table');
			$rated_link_text = esc_html__('I already did','arprice-responsive-pricing-table');

			printf( '<div class="%1$s"><p>%2$s</p><br/><br/><a href="%3$s" class="arplite_rate_link" target="_blank">%4$s</a><br/><a class="arplite_maybe_later_link" href="javascript:void(0);">%5$s</a><br/><a class="arplite_already_rated_link" href="javascript:void(0)">%6$s</a><br/>&nbsp;</div>', esc_attr( $class ), wp_kses( $message, $this->arpricelite_allowed_html_tags() ), esc_url( $rate_link ), esc_html( $rate_link_text ), esc_attr( $close_btn_text ), esc_html( $rated_link_text ) );
		}
	}

	function arplite_redirect_with_nonce_url(){

		if( is_admin() ){

			if( isset( $_GET['page'] ) && 'arpricelite' == $_GET['page'] ){

				if( ! isset( $_GET['arplite_page_nonce'] ) ){
					$url = admin_url( 'admin.php?page=arpricelite&arplite_page_nonce='.wp_create_nonce('arplite_page_nonce') );
					if( isset($_GET['arp_action']) ){
						$query_args = '';
						unset( $_GET['page'] );
						foreach( $_GET as $k => $v ){
							$query_args .= '&'.$k.'='.$v;
						}
						$url .= $query_args;
					}				
					wp_redirect( $url );
					die;
				} else if( isset( $_GET['arplite_page_nonce'] ) && !wp_verify_nonce( $_GET['arplite_page_nonce'], 'arplite_page_nonce' ) ){
					wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
				}
			} else if( isset( $_GET['page'] ) && 'arplite_import_export' == $_GET['page'] ){

				if( ! isset( $_GET['arplite_import_export_nonce'] ) ){					
					$url = admin_url( 'admin.php?page=arplite_import_export&arplite_import_export_nonce='.wp_create_nonce('arplite_import_export_nonce') );

					wp_redirect( $url );
					die;
				} else if( isset( $_GET['arplite_import_export_nonce'] ) && !wp_verify_nonce( $_GET['arplite_import_export_nonce'], 'arplite_import_export_nonce' ) ){
					wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
				}

			} else if( isset( $_GET['page'] ) && 'arplite_global_settings' == $_GET['page'] ){

				if( ! isset( $_GET['arplite_global_settings_nonce'] ) ){
					$url = admin_url( 'admin.php?page=arplite_global_settings&arplite_global_settings_nonce=' . wp_create_nonce( 'arplite_global_settings_nonce' ) );

					wp_redirect( $url );
					die;
				} else if( isset( $_GET['arplite_global_settings_nonce'] ) && ! wp_verify_nonce( $_GET['arplite_global_settings_nonce'], 'arplite_global_settings_nonce' ) ) {
					wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
				}

			} else if( isset( $_GET['page'] ) && 'arplite_ab_testing' == $_GET['page'] ){

				if( ! isset( $_GET['arplite_ab_testing_nonce'] ) ){
					$url = admin_url( 'admin.php?page=arplite_ab_testing&arplite_ab_testing_nonce=' . wp_create_nonce( 'arplite_ab_testing_nonce' ) );

					wp_redirect( $url );
					die;
				} else if( isset( $_GET['arplite_ab_testing_nonce'] ) && ! wp_verify_nonce( $_GET['arplite_ab_testing_nonce'], 'arplite_ab_testing_nonce' ) ) {
					wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
				}

			}

		}

	}

	function arplite_invalid_template_redirection() {
		global $wpdb;

		if ( isset( $_GET['page'] ) && 'arpricelite' == $_GET['page'] && isset( $_GET['arp_action'] ) && ( 'edit' == $_GET['arp_action'] || 'new' == $_GET['arp_action'] ) && isset( $_GET['eid'] ) && '' != $_GET['eid'] ) {
			$id = isset( $_GET['eid'] ) ? sanitize_text_field( $_GET['eid'] ) : 0;

			$check_table = $wpdb->get_row( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . "arplite_arprice WHERE ID='%d'", $id ) );

			if ( ! $check_table ) {
				wp_redirect( admin_url( 'admin.php?page=arpricelite' ) );
				die;
			}

			$sql           = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'arplite_arprice WHERE ID = %d', $id ) );
			$table_name    = $sql[0]->table_name;
			$is_template   = $sql[0]->is_template;
			$status        = $sql[0]->status;
			$template_name = $sql[0]->template_name;
			$arpreference  = isset( $_GET['ref'] ) ? sanitize_text_field( $_GET['ref'] ) : '';
			if ( ( $is_template == 1 && $arpreference == '' && $id != $arpreference && sanitize_text_field( $_GET['arp_action'] ) !== 'new' ) || $status == 'draft' ) {
				wp_redirect( admin_url( 'admin.php?page=arpricelite' ) );
				die;
			}
		}

	}

	function arp_add_capabilities_to_user_role( $user_id, $role, $old_roles ) {
		if ( $user_id == '' ) {
			return;
		}
		if ( $role == 'administrator' && $user_id ) {
			global $arplite_pricingtable;
			$arproles = $arplite_pricingtable->arp_capabilities();
			$userObj  = new WP_User( $user_id );
			foreach ( $arproles as $arprole => $arproledescription ) {
				if ( ! user_can( $user_id, $arprole ) ) {
					$userObj->add_cap( $arprole );
				}
			}
			unset( $arprole );
			unset( $arproles );
			unset( $arproledescription );
		}
	}

	function arplite_gutenberg_capability() {
		global $wpdb;

		wp_register_script( 'arprice_lite_script_for_gutenberg', ARPLITEURL . '/js/arprice_gutenberg.js', array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components' ) );

		wp_enqueue_script( 'arprice_lite_script_for_gutenberg' );

		$pricing_table = $wpdb->prefix . 'arplite_arprice';

		$pricing_table_data = $wpdb->get_results( 'SELECT ID,table_name FROM `' . $pricing_table . '` WHERE is_template != 1 ORDER BY ID DESC' );

		$pricing_table_list = array();
		$n                  = 0;
		foreach ( $pricing_table_data as $k => $value ) {
			$pricing_table_list[ $n ]['id']    = $value->ID;
			$pricing_table_list[ $n ]['label'] = $value->table_name . ' (ID: ' . $value->ID . ')';
			$n++;
		}

		wp_localize_script( 'arprice_lite_script_for_gutenberg', 'arprice_lite_list_for_gutenberg', $pricing_table_list );
	}

	function arp_add_capabilities_to_new_user( $user_id ) {
		if ( $user_id == '' ) {
			return;
		}

		if ( user_can( $user_id, 'administrator' ) ) {
			$arproles = $this->arp_capabilities();
			$userObj  = new WP_User( $user_id );

			foreach ( $arproles as $arprole => $arproledescription ) {
				$userObj->add_cap( $arprole );
			}

			unset( $arproles );
			unset( $arprole );
			unset( $arproledescription );
		}
	}

	function arp_prevent_rocket_loader_script( $tag, $handle ) {
		$pattern = '/(.*?)(data\-cfasync\=)(.*?)/';
		preg_match_all( $pattern, $tag, $matches );
		if ( ! is_array( $matches ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && ! empty( $matches[2] ) && ! empty( $matches[2][0] ) && strtolower( trim( $matches[2][0] ) ) != 'data-cfasync=' ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && empty( $matches[2] ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} else {
			return $tag;
		}
	}

	function replace_footer_admin() {
		echo '<span id="footer-thankyou"></span>';
	}

	function arplite_replace_footer_version() {
		return ' ';
	}

	/* Loading plugin text domain */

	function arplite_pricing_table_load_textdomain() {
		load_plugin_textdomain( 'arprice-responsive-pricing-table', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public static function arpricelite_check_network_activation( $network_wide ) {
		if ( ! $network_wide ) {
			return;
		}

		deactivate_plugins( plugin_basename( __FILE__ ), true, true );

		header( 'Location: ' . network_admin_url( 'plugins.php?deactivate=true' ) );
		exit;
	}

	function arplite_pricing_table_main_settings() {
		global $arplite_mainoptionsarr, $arplite_pricingtable, $arpricelite_default_settings;
		$arplite_mainoptionsarr = $arplite_pricingtable->arp_mainoptions();

		global $arplite_coloptionsarr;
		$arplite_coloptionsarr = $arplite_pricingtable->arp_columnoptions();

		global $arplite_tempbuttonsarr;
		$arplite_tempbuttonsarr = $arplite_pricingtable->arp_tempbuttonsoptions();

		global $arplite_templateorderarr;
		$arplite_templateorderarr = $arplite_pricingtable->arp_template_order();

		global $arplite_templateresponsivearr;
		$arplite_templateresponsivearr = $arplite_pricingtable->arp_template_responsive_type_array();

		global $arplite_template_editor_arr;
		$arplite_template_editor_arr = $arplite_pricingtable->arp_template_editor_array();

		global $arplite_templatesectionsarr;
		$arplite_templatesectionsarr = $arpricelite_default_settings->arp_template_sections_array();

		global $arplite_templatecustomskinarr;
		$arplite_templatecustomskinarr = $arpricelite_default_settings->arplite_template_custom_skin_array();

		global $arplite_templatehoverclassarr;
		$arplite_templatehoverclassarr = $arpricelite_default_settings->arp_template_hover_class_array();
	}

	/* Setting General Options for Pricing table */

	function arp_mainoptions() {
		$arpoptionsarr = apply_filters(
			'arplite_pricing_table_available_main_settings',
			array(
				'general_options' => array(
					'template_options'              => array(
						'templates'             => array( 'arplitetemplate_7', 'arplitetemplate_2', 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
						'skins'                 => array(
							'arplitetemplate_7'  => array( 'blue', 'black', 'cyan', 'lightblue', 'red', 'yellow', 'olive', 'darkpurple', 'darkred', 'pink', 'brown' ),
							'arplitetemplate_2'  => array( 'blue', 'lightviolet', 'yellow', 'limegreen', 'orange', 'softblue', 'limecyan', 'brightred', 'red', 'pink', 'lightblue', 'darkpink', 'darkcyan' ),
							'arplitetemplate_1'  => array( 'green', 'yellow', 'darkorange', 'darkred', 'red', 'violet', 'pink', 'blue', 'darkblue', 'lightgreen', 'darkestblue', 'cyan', 'black', 'multicolor' ),
							'arplitetemplate_8'  => array( 'purple', 'skyblue', 'red', 'green', 'blue', 'orange', 'darkcyan', 'yellow', 'pink', 'teal', 'multicolor' ),
							'arplitetemplate_11' => array( 'yellow', 'limegreen', 'red', 'blue', 'pink', 'cyan', 'lightpink', 'violet', 'gray', 'green' ),
							'arplitetemplate_26' => array( 'blue', 'red', 'lightblue', 'cyan', 'yellow', 'pink', 'lightviolet', 'gray', 'orange', 'darkblue', 'turquoise', 'grayishyellow', 'green' ),
						),
						'skin_color_code'       => array(
							'arplitetemplate_7'  => array( '3473DC', '3E3E3C', '1EAE8B', '1BACE1', 'F33C3E', 'FFA800', '8FB021', '5B48A2', '79302A', 'ED1374', 'B11D00' ),
							'arplitetemplate_2'  => array( '02a3ff', '6c62d3', 'ffba00', '6ed563', 'ff9525', '4476d9', '37ba5a', 'f34044', 'de1a4c', 'de199a', '1a5fde', 'a51143', '11a599' ),
							'arplitetemplate_1'  => array( '6dae2e', 'fbb400', 'e75c01', 'c32929', 'e52937', '713887', 'EB005C', '29A1D3', '2F3687', '1BA341', '2F4251', '009E7B', '5C5C5C', 'Multicolor' ),
							'arplitetemplate_8'  => array( 'AB6ED7', '44B7E4', 'F15859', '7FB948', '595EB7', 'FF6E3D', '54CAB0', 'FFC74B', 'EC3E9A', '25D0D7', 'Multicolor' ),
							'arplitetemplate_11' => array( 'EFA738', '43B34D', 'FF3241', '09B1F8', 'E3328C', '11B0B6', 'F15F74', '8F4AFF', '949494', '78C335' ),
							'arplitetemplate_26' => array( '2fb8ff', 'ff2d46', '4196ff', '00d29d', 'f1bc16', 'ff2476', '6b68ff', 'b7bdcb', 'fd9a25', '337cff', '00dbef', 'cfc5a1', '16d784' ),
						),
						'template_type'         => array(
							'arplitetemplate_7'  => 'normal',
							'arplitetemplate_2'  => 'normal',
							'arplitetemplate_1'  => 'normal',
							'arplitetemplate_8'  => 'normal',
							'arplitetemplate_11' => 'normal',
							'arplitetemplate_26' => 'normal',
						),
						'features'              => array(
							'arplitetemplate_7'  => array(
								'column_description'       => 'enable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'position_1',
								'caption_style'            => 'none',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'style_3',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'normal',
								'header_shortcode_position' => 'position_1',
								'tooltip_position'         => 'top-left',
								'tooltip_style'            => 'style_1',
								'second_btn'               => false,
								'additional_shortcode'     => true,
								'is_animated'              => 0,
								'has_footer_content'       => 0,
								'button_border_customization' => 1,
							),
							'arplitetemplate_2'  => array(
								'column_description'       => 'disable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'default',
								'caption_style'            => 'default',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'default',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'rounded_border',
								'header_shortcode_position' => 'default',
								'tooltip_position'         => 'top',
								'tooltip_style'            => 'style_2',
								'second_btn'               => false,
								'is_animated'              => 0,
								'has_footer_content'       => 1,
								'button_border_customization' => 1,
							),
							'arplitetemplate_1'  => array(
								'column_description'       => 'disable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'default',
								'caption_style'            => 'default',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'default',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'normal',
								'header_shortcode_position' => 'none',
								'tooltip_position'         => 'top-left',
								'tooltip_style'            => 'default',
								'second_btn'               => false,
								'additional_shortcode'     => false,
								'is_animated'              => 0,
								'has_footer_content'       => 1,
								'button_border_customization' => 1,
							),
							'arplitetemplate_8'  => array(
								'column_description'       => 'disable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'position_2',
								'caption_style'            => 'default',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'default',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'rounded_corner',
								'header_shortcode_position' => 'position_1',
								'tooltip_position'         => 'top',
								'tooltip_style'            => 'style_2',
								'second_btn'               => false,
								'additional_shortcode'     => true,
								'is_animated'              => 0,
								'has_footer_content'       => 0,
								'button_border_customization' => 1,
							),
							'arplitetemplate_11' => array(
								'column_description'       => 'enable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'position_1',
								'caption_style'            => 'none',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'style_4',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'normal',
								'header_shortcode_position' => 'none',
								'tooltip_position'         => 'top-left',
								'tooltip_style'            => 'default',
								'second_btn'               => false,
								'additional_shortcode'     => false,
								'is_animated'              => 0,
								'has_footer_content'       => 0,
								'button_border_customization' => 1,
							),
							'arplitetemplate_26' => array(
								'column_description'       => 'disable',
								'custom_ribbon'            => 'disable',
								'button_position'          => 'default',
								'caption_style'            => 'default',
								'amount_style'             => 'default',
								'list_alignment'           => 'default',
								'ribbon_type'              => 'default',
								'column_description_style' => 'default',
								'caption_title'            => 'default',
								'header_shortcode_type'    => 'rounded_border',
								'header_shortcode_position' => 'default',
								'tooltip_position'         => 'top',
								'tooltip_style'            => 'style_2',
								'second_btn'               => false,
								'is_animated'              => 0,
								'button_border_customization' => 1,
								'has_footer_content'       => 0,
							),
						),
						'arp_ribbons'           => array(
							'arp_ribbon_1' => 'Ribbon Style 1',
							'arp_ribbon_2' => 'Ribbon Style 2 <span class="pro_version_info">(' . esc_html__( 'Pro Version', 'arprice-responsive-pricing-table' ) . ')</span>',
							'arp_ribbon_3' => 'Ribbon Style 3 <span class="pro_version_info">(' . esc_html__( 'Pro Version', 'arprice-responsive-pricing-table' ) . ')</span>',
							'arp_ribbon_4' => 'Ribbon Style 4 <span class="pro_version_info">(' . esc_html__( 'Pro Version', 'arprice-responsive-pricing-table' ) . ')</span>',
							'arp_ribbon_5' => 'Ribbon Style 5 <span class="pro_version_info">(' . esc_html__( 'Pro Version', 'arprice-responsive-pricing-table' ) . ')</span>',
							'arp_ribbon_6' => 'Custom Ribbon <span class="pro_version_info">(' . esc_html__( 'Pro Version', 'arprice-responsive-pricing-table' ) . ')</span>',
						),
						'arp_template_ribbons'  => array(
							'arplitetemplate_7'  => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_6' ),
							'arplitetemplate_2'  => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_4', 'arp_ribbon_5', 'arp_ribbon_6' ),
							'arplitetemplate_1'  => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_4', 'arp_ribbon_6', 'arp_ribbon_6' ),
							'arplitetemplate_8'  => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_4', 'arp_ribbon_5', 'arp_ribbon_6' ),
							'arplitetemplate_11' => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_4', 'arp_ribbon_6' ),
							'arplitetemplate_26' => array( 'arp_ribbon_1', 'arp_ribbon_2', 'arp_ribbon_3', 'arp_ribbon_4', 'arp_ribbon_6' ),
						),
						'arp_tablet_view_width' => array(
							'arplitetemplate_7'  => '23',
							'arplitetemplate_2'  => '23',
							'arplitetemplate_1'  => '19.5',
							'arplitetemplate_8'  => '23',
							'arplitetemplate_11' => '23',
							'arplitetemplate_26' => '23',
						),
					),
					'arp_basic_colors'              => array( '#ff7525', '#ffcf33', '#e3e500', '#00d2d7', '#4fe3fe', '#ff67b4', '#c96098', '#ff1515', '#ffcea6', '#ffc22f', '#dbd423', '#0bc124', '#00e430', '#00a9ff', '#a1bed6', '#006be1', '#90d73d', '#00825f', '#04d2ab', '#ff5c77', '#6951ff', '#ac3f07', '#b5fe01', '#666666', '#ffe217', '#5d9cec', '#bbea8a', '#496b90', '#9943d8', '#d6a153', '#bd0101', '#0385a0', '#45487d', '#8d5d17', '#f2f2f2', '#514e4e' ),
					'arp_basic_colors_gradient'     => array( '#d24c00', '#c99a00', '#8aa301', '#00a5a9', '#46aec1', '#ce0f70', '#7b164c', '#c80202', '#d47f46', '#f48a00', '#876705', '#006400', '#00951f', '#0182c4', '#5f7c97', '#003a7a', '#145502', '#003f32', '#16a086', '#a0132a', '#2105cc', '#5e1d0b', '#699001', '#3c3c3c', '#c09505', '#3a72b9', '#699f2f', '#1e2a36', '#531084', '#8f6229', '#590101', '#02414e', '#151845', '#633b00', '#c0c0c0', '#0c0b0b' ),
					'arp_ribbon_border_color'       => array( '#f1732b', '#f1732b', '#a0b419', '#00b3b8', '#33a0b4', '#dc2783', '#a33c73', '#ff1515', '#ed9e67', '#ed9e67', '#b3a015', '#07a318', '#00af25', '#0095e0', '#809cb6', '#0052ab', '#559921', '#003f32', '#14a68a', '#d73b54', '#472de7', '#7f2b09', '#8dc401', '#4e4e4e', '#d3ac07', '#4680ca', '#7cb144', '#2b3e52', '#6d23a4', '#aa7a39', '#650101', '#035a6d', '#272a5a', '#714608', '#b5b5b5', '#1a1818' ),
					'fontoption'                    => array(
						'header_fonts'     => array(
							'font_family' => 'Arial',
							'font_size'   => '32',
							'font_color'  => '#ffffff',
							'font_style'  => 'normal',
						),
						'price_fonts'      => array(
							'font_family' => 'Arial',
							'font_size'   => '16',
							'font_color'  => '#ffffff',
							'font_style'  => 'normal',
						),
						'price_text_fonts' => array(
							'font_family' => 'Arial',
							'font_size'   => '16',
							'font_color'  => '#ffffff',
							'font_style'  => 'normal',
						),
						'content_fonts'    => array(
							'font_family' => 'Arial',
							'font_size'   => '12',
							'font_color'  => '#364762',
							'font_style'  => 'bold',
						),
						'button_fonts'     => array(
							'font_family' => 'Arial',
							'font_size'   => '14',
							'font_color'  => '#ffffff',
							'font_style'  => 'bold',
						),
					),
					'column_animation'              => array(
						'is_enable'                => 0,
						'visible_column_count'     => 2,
						'columns_to_scroll'        => 2,
						'is_navigation'            => 1,
						'autoplay'                 => 1,
						'sliding_effect'           => array( 'slide', 'fade', 'crossfade', 'directscroll', 'cover', 'uncover' ),
						'sliding_transition_speed' => 750,
						'navigation_style'         => array( 'arp_nav_style_1', 'arp_nav_style_2' ),
						'pagination'               => 1,
						'pagination_style'         => array( 'arp_paging_style_1', 'arp_paging_style_2' ),
						'pagination_position'      => array( 'Top', 'Bottom', 'Both' ),
						'easing_effect'            => array( 'swing', 'linear', 'cubic', 'elastic', 'quadratic' ),
						'infinite'                 => 1,
						'sticky_caption'           => 0,
						'pagi_nav_btns'            => array(
							'pagination_top'    => esc_html__( 'Top', 'arprice-responsive-pricing-table' ),
							'pagination_bottom' => esc_html__( 'Bottom', 'arprice-responsive-pricing-table' ),
							'none'              => esc_html__( 'Off', 'arprice-responsive-pricing-table' ),
						),
						'navi_nav_btns'            => array(
							'navigation' => esc_html__( 'On', 'arprice-responsive-pricing-table' ),
							'none'       => esc_html__( 'Off', 'arprice-responsive-pricing-table' ),
						),
						'def_pagin_nav'            => 'both',
					),
					'is_spacebetweencolumns'        => 'no',
					'spacebetweencolumns'           => '0px',
					'tooltipsetting'                => array(
						'width'                   => '',
						'background_color'        => '#000000',
						'text_color'              => '#FFFFFF',
						'animation'               => array( 'grow', 'fade', 'swing', 'slide', 'fall' ),
						'position'                => array( 'top', 'bottom', 'left', 'right' ),
						'style'                   => array( 'normal', 'alert', 'glass' ),
						'trigger_on'              => array( 'hover', 'click' ),
						'tooltip_display_style'   => array( 'default', 'informative' ),
						'informative_tootip_icon' => array( '<i class="fa fa-info-circle fa-tp"></i>' ),
					),
					'is_responsive'                 => 1,
					'hide_caption_column'           => 0,
					'highlightcolumnonhover'        => array(
						'hover_effect'          => esc_html__( 'Hover Effect', 'arprice-responsive-pricing-table' ),
						'shadow_effect'         => esc_html__( 'Shadow effect', 'arprice-responsive-pricing-table' ),
						'arp-pulse'             => esc_html__( 'Pulse', 'arprice-responsive-pricing-table' ),
						'arp-shake'             => esc_html__( 'Shake', 'arprice-responsive-pricing-table' ),
						'arp-swing'             => esc_html__( 'Swing', 'arprice-responsive-pricing-table' ),
						'arp-bob'               => esc_html__( 'Bob', 'arprice-responsive-pricing-table' ),
						'arp-hang'              => esc_html__( 'Hang', 'arprice-responsive-pricing-table' ),
						'arp-wobble-horizontal' => esc_html__( 'Wobble', 'arprice-responsive-pricing-table' ),
						'no_effect'             => esc_html__( 'None', 'arprice-responsive-pricing-table' ),
					),
					'button_settings'               => array(
						'button_shadow_color' => '#FFFFFF',
						'button_radius'       => 0,
					),
					'column_opacity'                => array( 1, 0.90, 0.80, 0.70, 0.60, 0.50, 0.40, 0.30, 0.20, 0.10 ),
					'wrapper_width'                 => '1000',
					'wrapper_width_style'           => array( 'px', '%' ),
					'default_column_radius_value'   => array(
						'arplitetemplate_7'  => array(
							'top_left'     => 0,
							'top_right'    => 0,
							'bottom_right' => 0,
							'bottom_left'  => 0,
						),
						'arplitetemplate_2'  => array(
							'top_left'     => 7,
							'top_right'    => 7,
							'bottom_right' => 7,
							'bottom_left'  => 7,
						),
						'arplitetemplate_1'  => array(
							'top_left'     => 0,
							'top_right'    => 0,
							'bottom_right' => 0,
							'bottom_left'  => 0,
						),
						'arplitetemplate_8'  => array(
							'top_left'     => 0,
							'top_right'    => 0,
							'bottom_right' => 0,
							'bottom_left'  => 0,
						),
						'arplitetemplate_11' => array(
							'top_left'     => 0,
							'top_right'    => 0,
							'bottom_right' => 0,
							'bottom_left'  => 0,
						),
						'arplitetemplate_26' => array(
							'top_left'     => 15,
							'top_right'    => 15,
							'bottom_right' => 15,
							'bottom_left'  => 15,
						),
					),
					'footer_content_position'       => array( esc_html__( 'Below Button', 'arprice-responsive-pricing-table' ), esc_html__( 'Above Button', 'arprice-responsive-pricing-table' ) ),
					'column_box_shadow_effect'      => array(
						'shadow_style_none' => 'None',
						'shadow_style_1'    => 'Style1',
						'shadow_style_2'    => 'Style2',
						'shadow_style_3'    => 'Style3',
						'shadow_style_4'    => 'Style4',
						'shadow_style_5'    => 'Style5',
					),
					'arp_color_skin_template_types' => array(
						'type_1' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_26', 'arplitetemplate_2' ),
						'type_2' => array( 'arplitetemplate_11', 'arplitetemplate_7' ),
						'type_3' => array(),
						'type_4' => array(),
					),
					'template_bg_section_classes'   => array(
						'arplitetemplate_7'  => array(
							'caption_column' => array(),
							'other_column'   => array(
								'header_section' => 'arppricetablecolumntitle',
								'button_section' => 'bestPlanButton',
								'desc_selection' => 'column_description,arppricetablecolumnprice',
								'body_section'   => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
							),
						),
						'arplitetemplate_2'  => array(
							'caption_column' => array(),
							'other_column'   => array(
								'column_section' => '.arp_column_content_wrapper',
								'button_section' => 'bestPlanButton',
							),
						),
						'arplitetemplate_1'  => array(
							'caption_column' => array(
								'column_section' => '.arp_column_content_wrapper',
								'header_section' => 'arpcolumnheader',
								'footer_section' => 'arpcolumnfooter',
								'body_section'   => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
							),
							'other_column'   => array(
								'column_section'  => '.arp_column_content_wrapper',
								'header_section'  => 'arppricetablecolumntitle',
								'pricing_section' => 'arppricetablecolumnprice',
								'button_section'  => 'bestPlanButton',
								'footer_section'  => 'arpcolumnfooter',
								'body_section'    => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
							),
						),
						'arplitetemplate_8'  => array(
							'caption_column' => array(
								'footer_section' => 'arpcolumnfooter',
								'body_section'   => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
								'column_section' => '.arp_column_content_wrapper',

							),
							'other_column'   => array(
								'header_section' => 'arpcolumnheader',
								'button_section' => 'bestPlanButton',
								'body_section'   => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
								'column_section' => '.arp_column_content_wrapper',
							),

						),
						'arplitetemplate_11' => array(
							'caption_columns' => array(),
							'other_column'    => array(
								'column_section' => '.arp_column_content_wrapper',
								'header_section' => 'arppricetablecolumntitle',
								'desc_selection' => 'arppricetablecolumnprice',
								'button_section' => 'bestPlanButton',
								'body_section'   => array(
									'odd_row'  => 'arp_odd_row',
									'even_row' => 'arp_even_row',
								),
							),
						),
						'arplitetemplate_26' => array(
							'caption_column' => array(),
							'other_column'   => array(
								'header_section' => 'arppricetablecolumntitle,rounded_corder',
								'column_section' => '.arp_column_content_wrapper',
								'button_section' => 'bestPlanButton',
							),
						),
					),
					'template_border_color'         => array(
						'arplitetemplate_1' => array(
							'caption_column' => array(
								'border_color' => '#E3E3E3',
							),
						),
					),
				),
			)
		);
		return $arpoptionsarr;
	}

	/* Setting Default Options */

	function arp_columnoptions() {
		$arptempbutoptionsarr = apply_filters(
			'arplite_pricing_table_available_column_settings',
			array(
				'column_options'        => array(
					'width'            => 'auto',
					'alignment'        => array( 'left', 'center', 'right' ),
					'column_highlight' => 0,
					'show_column'      => 1,
					'ribbon_icon'      => array(),
					'ribbon_position'  => array( 'left', 'right' ),
				),
				'header_options'        => array(
					'column_title'                  => '',
					'price'                         => '',
					'html_content'                  => '',
					'html_shortcode_options'        => array(
						'image' => array( 'image' => esc_html__( 'Image', 'arprice-responsive-pricing-table' ) ),
						'video' => array(
							'youtube'     => esc_html__( 'Youtube video', 'arprice-responsive-pricing-table' ),
							'vimeo'       => esc_html__( 'Vimeo Video', 'arprice-responsive-pricing-table' ),
							'screenr'     => esc_html__( 'Screenr Video', 'arprice-responsive-pricing-table' ),
							'video'       => esc_html__( 'html5 Video', 'arprice-responsive-pricing-table' ),
							'dailymotion' => esc_html__( 'Dailymotion Video', 'arprice-responsive-pricing-table' ),
							'metacafe'    => esc_html__( 'Metacafe Video', 'arprice-responsive-pricing-table' ),
						),
						'audio' => array(
							'audio'      => esc_html__( 'html5 Audio', 'arprice-responsive-pricing-table' ),
							'soundcloud' => esc_html__( 'Soundcloud Audio', 'arprice-responsive-pricing-table' ),
							'mixcloud'   => esc_html__( 'Mixcloud Audio', 'arprice-responsive-pricing-table' ),
							'beatport'   => esc_html__( 'Beatport Audio', 'arprice-responsive-pricing-table' ),
						),
						'other' => array(
							'googlemap' => esc_html__( 'Google Map', 'arprice-responsive-pricing-table' ),
							'embed'     => esc_html__( 'Embed Block', 'arprice-responsive-pricing-table' ),
						),
					),
					'image_shortcode_options'       => array(
						'url'    => esc_html__( 'Image URL', 'arprice-responsive-pricing-table' ),
						'height' => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'width'  => esc_html__( 'Width', 'arprice-responsive-pricing-table' ),
					),
					'youtube_shortcode_options'     => array(
						'id'       => esc_html__( 'Video id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'vimeo_shortcode_options'       => array(
						'id'       => esc_html__( 'Video id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'screenr_shortcode_options'     => array(
						'id'     => esc_html__( 'Video id', 'arprice-responsive-pricing-table' ),
						'height' => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
					),
					'video_shortcode_options'       => array(
						'mp4'      => esc_html__( 'MP4 source', 'arprice-responsive-pricing-table' ),
						'webm'     => esc_html__( 'Webm source', 'arprice-responsive-pricing-table' ),
						'ogg'      => esc_html__( 'Ogg source', 'arprice-responsive-pricing-table' ),
						'poster'   => esc_html__( 'Poster image source', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
						'loop'     => esc_html__( 'Loop', 'arprice-responsive-pricing-table' ),
					),
					'audio_shortcode_options'       => array(
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
						'loop'     => esc_html__( 'Loop', 'arprice-responsive-pricing-table' ),
						'mp3'      => esc_html__( 'MP3 source', 'arprice-responsive-pricing-table' ),
						'ogg'      => esc_html__( 'Ogg source', 'arprice-responsive-pricing-table' ),
						'wav'      => esc_html__( 'Wav source', 'arprice-responsive-pricing-table' ),
					),
					'googlemap_shortcode_options'   => array(
						'address'              => esc_html__( 'Address', 'arprice-responsive-pricing-table' ),
						'height'               => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'zoom_level'           => esc_html__( 'Zoom level', 'arprice-responsive-pricing-table' ),
						'marker_image'         => esc_html__( 'Marker image source', 'arprice-responsive-pricing-table' ),
						'mapinfo_title'        => esc_html__( 'Marker title', 'arprice-responsive-pricing-table' ),
						'mapinfo_content'      => esc_html__( 'Map info window content', 'arprice-responsive-pricing-table' ),
						'mapinfo_show_default' => esc_html__( 'Info window by default?', 'arprice-responsive-pricing-table' ),
					),
					'dailymotion_shortcode_options' => array(
						'id'       => esc_html__( 'Video id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'metacafe_shortcode_options'    => array(
						'id'       => esc_html__( 'Video id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'soundcloud_shortcode_options'  => array(
						'id'       => esc_html__( 'Track id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'mixcloud_shortcode_options'    => array(
						'url'      => esc_html__( 'Track url', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'beatport_shortcode_options'    => array(
						'id'       => esc_html__( 'Track id', 'arprice-responsive-pricing-table' ),
						'height'   => esc_html__( 'Height', 'arprice-responsive-pricing-table' ),
						'autoplay' => esc_html__( 'Autoplay', 'arprice-responsive-pricing-table' ),
					),
					'embed_shortcode_options'       => array(
						'id' => esc_html__( 'Embed', 'arprice-responsive-pricing-table' ),
					),
				),
				'column_body_options'   => array(
					'body_description'              => '',
					'description_shortcode_options' => array( 'icons', 'icon_alignment' ),
					'icon_shortcode_options'        => array(),
					'description_alignment'         => 'center',
					'tooltip_text'                  => '',
				),
				'column_button_options' => array(
					'button_size'             => array(
						'small'  => esc_html__( 'Small', 'arprice-responsive-pricing-table' ),
						'medium' => esc_html__( 'Medium', 'arprice-responsive-pricing-table' ),
						'large'  => esc_html__( 'Large', 'arprice-responsive-pricing-table' ),
					),
					'button_type'             => array(
						'button'        => esc_html__( 'Button', 'arprice-responsive-pricing-table' ),
						'submit_button' => esc_html__( 'Submit', 'arprice-responsive-pricing-table' ),
					),
					'button_text'             => '',
					'button_icon'             => array(),
					'button_link'             => '',
					'open_link_in_new_window' => '0',
					'button_custom_image'     => '',
				),
			)
		);

		return $arptempbutoptionsarr;
	}

	/* Setting Template Button Options for Pricing table */

	function arp_tempbuttonsoptions() {
		$rpttempbutoptionsarr = apply_filters(
			'arplite_pricing_table_available_column_button_settings',
			array(
				'template_button_options' => array(
					'features' => array(
						'arplitetemplate_1'  => array(
							'column_level_options'  => array(
								'caption_column_buttons' => array(
									'column_level_options__button_1' => array(
										'column_width',
										'caption_border',
										'caption_row_border',
										'set_hidden',
										'column_level_caption_arp_ok_div__button_1'
									),
									'column_level_options__button_2' => array(
										'arp_custom_color_tab_column',
										'arp_normal_custom_color_tab_column',
										'arp_header_color_div',
										'header_background_color_div',
										'header_font_color_div',
										'arp_header_hover_color_div',
										'header_hover_background_color_div',
										'header_hover_font_color_div',
										'arp_footer_color_div',
										'footer_background_color_div',
										'footer_font_color_div',
										'arp_body_background_color_div',
										'arp_body_background_color_div_title',
										'arp_odd_color_div',
										'odd_background_color_div',
										'odd_font_color_div',
										'arp_even_color_div',
										'even_background_color_div',
										'even_font_color_div',
										'arp_footer_hover_color_div',
										'footer_hover_background_color_div',
										'footer_hover_font_color_div',
										'arp_body_hover_background_color_div',
										'arp_body_hover_background_color_div_title',
										'arp_odd_hover_color_div',
										'odd_hover_background_color_div',
										'odd_hover_font_color_div',
										'arp_even_hover_color_div',
										'even_hover_background_color_div',
										'even_hover_font_color_div',
										'column_level_other_arp_ok_div__button_2',
										'arp_border_color_div',
										'arp_border_color_div_sub',
										'row_border_color_div',
										'column_border_color_div' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array(
										'column_other_background_image',
										'column_highlight',
										'set_hidden',
										'select_ribbon',
										'is_post_variable',
										'post_variables_content',
										'column_level_other_arp_ok_div__button_1',
										'is_column_clickable_wrapper'
									),
									'column_level_options__button_2' => array(
										'arp_custom_color_tab_column',
										'arp_custom_color_tab_other_column',
										'arp_normal_custom_color_tab_column',
										'arp_header_color_div',
										'header_background_color_div_other_col',
										'header_font_color_div',
										'arp_header_hover_color_div',
										'header_hover_background_color_div',
										'header_hover_font_color_div',
										'arp_price_color_div',
										'price_background_color_div',
										'price_font_color_div',
										'arp_price_hover_color_div',
										'price_hover_background_color_div',
										'price_hover_font_color_div',
										'arp_footer_color_div',
										'footer_background_color_div',
										'footer_font_color_div',
										'arp_button_color_div',
										'button_background_color_div',
										'button_font_color_div',
										'arp_body_background_color_div',
										'arp_body_background_color_div_title',
										'arp_odd_color_div',
										'odd_background_color_div',
										'odd_font_color_div',
										'arp_even_color_div',
										'even_background_color_div',
										'even_font_color_div',
										'arp_footer_hover_color_div',
										'footer_hover_background_color_div',
										'footer_hover_font_color_div',
										'arp_hover_button_color_div',
										'button_hover_background_color_div',
										'button_hover_font_color_div',
										'arp_body_hover_background_color_div',
										'arp_body_hover_background_color_div_title',
										'arp_odd_hover_color_div',
										'odd_hover_background_color_div',
										'odd_hover_font_color_div',
										'arp_even_hover_color_div',
										'even_hover_background_color_div',
										'even_hover_font_color_div',
										'column_level_other_arp_ok_div__button_2'
									),
									'column_level_options__button_3' => array(
										'arp_custom_color_tab_column'
									),
								),
							),
							'header_level_options'  => array(
								'caption_column_buttons' => array(
									'header_level_options__button_1' => array( 'column_title', 'header_text_alignment', 'header_caption_font_family', 'header_caption_font_size', 'header_caption_font_style', 'header_caption_font_color', 'arp_object', 'arp_fontawesome', 'header_level_caption_arp_ok_div__button_1' ),
								),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_object', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
								),
							),
							'pricing_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'pricing_level_options__button_1' => array( 'price_text', 'arp_fontawesome', 'pricing_level_other_arp_ok_div__button_1' ),
								),
							),
							'body_level_options'    => array(
								'caption_column_buttons' => array(
									'body_level_options__button_1' => array( 'text_alignment', 'body_li_caption_font_family', 'body_li_caption_font_size', 'body_li_caption_font_style', 'body_level_caption_arp_ok_div__button_1' ),
								),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options' => array(
								'caption_column_buttons' => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_object', 'description', 'body_li_level_caption_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_caption_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'footer_level_options'  => array(
								'caption_column_buttons' => array(
									'footer_level_options__button_1' => array( 'footer_text', 'footer_text_alignment', 'footer_level_options_font_family', 'footer_level_options_background', 'footer_level_options_font_size', 'footer_level_options_font_style', 'footer_level_options_arp_ok_div__button_1' ),
								),
								'other_columns_buttons'  => array(
									'footer_level_options__button_1' => array( 'footer_text', 'above_below_button', 'footer_level_options_arp_ok_div__button_1' ),
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_size', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
						),
						'arplitetemplate_7'  => array(
							'column_level_options'     => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array( 'column_highlight', 'set_hidden', 'select_ribbon', 'is_post_variable', 'post_variables_content', 'column_level_other_arp_ok_div__button_1', 'is_column_clickable_wrapper' ),
									'column_level_options__button_2' => array( 'arp_custom_color_tab_column', 'arp_normal_custom_color_tab_column', 'arp_header_color_div', 'header_background_color_div_other_col', 'header_font_color_div', 'arp_header_hover_color_div', 'header_hover_background_color_div', 'header_hover_font_color_div', 'arp_price_color_div', 'price_font_color_div', 'arp_price_hover_color_div', 'price_hover_font_color_div', 'arp_button_color_div', 'button_background_color_div', 'button_font_color_div', 'arp_body_background_color_div', 'arp_body_background_color_div_title', 'arp_odd_color_div', 'odd_background_color_div', 'odd_font_color_div', 'arp_even_color_div', 'even_background_color_div', 'even_font_color_div', 'arp_hover_button_color_div', 'button_hover_background_color_div', 'button_hover_font_color_div', 'arp_body_hover_background_color_div', 'arp_body_hover_background_color_div_title', 'arp_odd_hover_color_div', 'odd_hover_background_color_div', 'odd_hover_font_color_div', 'arp_even_hover_color_div', 'even_hover_background_color_div', 'even_hover_font_color_div', 'arp_desc_color_div', 'desc_background_color_div', 'desc_font_color_div', 'arp_desc_hover_color_div', 'desc_hover_background_color_div', 'desc_hover_font_color_div', 'column_level_other_arp_ok_div__button_2' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
							),
							'header_level_options'     => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
									'header_level_options__button_2' => array( 'additional_shortcode', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_2' ),
								),
							),
							'pricing_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'pricing_level_options__button_1' => array( 'price_text', 'arp_fontawesome', 'pricing_level_other_arp_ok_div__button_1' ),
								),
							),
							'body_level_options'       => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'footer_level_options'     =>
							array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_size', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
							'column_description_level' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_description_level__button_1' => array( 'column_description', 'arp_fontawesome', 'column_description_level_other_arp_ok_div__button_1' ),
								),
							),
						),
						'arplitetemplate_8'  => array(
							'column_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array( 'column_highlight', 'set_hidden', 'select_ribbon', 'is_post_variable', 'post_variables_content', 'column_level_other_arp_ok_div__button_1', 'is_column_clickable_wrapper', 'column_other_background_image' ),
									'column_level_options__button_2' => array( 'arp_custom_color_tab_column', 'arp_normal_custom_color_tab_column', 'arp_header_color_div', 'header_background_color_div_other_col', 'header_font_color_div', 'arp_header_hover_color_div', 'header_hover_background_color_div', 'header_hover_font_color_div', 'arp_price_color_div', 'price_font_color_div', 'arp_price_hover_color_div', 'price_hover_font_color_div', 'arp_button_color_div', 'button_background_color_div', 'button_font_color_div', 'arp_body_background_color_div', 'arp_body_background_color_div_title', 'arp_odd_color_div', 'odd_background_color_div', 'odd_font_color_div', 'arp_even_color_div', 'even_background_color_div', 'even_font_color_div', 'arp_hover_button_color_div', 'button_hover_background_color_div', 'button_hover_font_color_div', 'arp_body_hover_background_color_div', 'arp_body_hover_background_color_div_title', 'arp_odd_hover_color_div', 'odd_hover_background_color_div', 'odd_hover_font_color_div', 'arp_even_hover_color_div', 'even_hover_background_color_div', 'even_hover_font_color_div', 'arp_shortcode_div', 'arp_shortcode_background', 'arp_shortcode_font_color', 'arp_shortcode_hover_div', 'arp_shortcode_hover_background', 'arp_shortcode_hover_font_color', 'column_level_other_arp_ok_div__button_2' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
							),
							'header_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
									'header_level_options__button_2' => array( 'additional_shortcode', 'arp_object', 'arp_fontawesome', 'arp_shortcode_customization_style_div', 'arp_shortcode_customization_size_div', 'header_level_other_arp_ok_div__button_2' ),
								),
							),
							'pricing_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'pricing_level_options__button_1' => array( 'price_text', 'arp_fontawesome', 'pricing_level_other_arp_ok_div__button_1' ),
								),
							),
							'body_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'footer_level_options'  =>
							array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_size', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
						),
						'arplitetemplate_11' => array(
							'column_level_options'     => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array( 'column_highlight', 'set_hidden', 'select_ribbon', 'is_post_variable', 'post_variables_content', 'column_level_other_arp_ok_div__button_1', 'is_column_clickable_wrapper', 'column_other_background_image' ),
									'column_level_options__button_2' => array( 'arp_custom_color_tab_column', 'arp_normal_custom_color_tab_column', 'arp_header_color_div', 'header_background_color_div_other_col', 'header_font_color_div', 'arp_header_hover_color_div', 'header_hover_background_color_div', 'header_hover_font_color_div', 'arp_price_color_div', 'price_font_color_div', 'arp_price_hover_color_div', 'price_hover_font_color_div', 'arp_button_color_div', 'button_background_color_div', 'button_font_color_div', 'arp_body_background_color_div', 'arp_body_background_color_div_title', 'arp_odd_color_div', 'odd_background_color_div', 'odd_font_color_div', 'arp_even_color_div', 'even_background_color_div', 'even_font_color_div', 'arp_hover_button_color_div', 'button_hover_background_color_div', 'button_hover_font_color_div', 'arp_body_hover_background_color_div', 'arp_body_hover_background_color_div_title', 'arp_odd_hover_color_div', 'odd_hover_background_color_div', 'odd_hover_font_color_div', 'arp_even_hover_color_div', 'even_hover_background_color_div', 'even_hover_font_color_div', 'arp_desc_color_div', 'desc_background_color_div', 'desc_font_color_div', 'arp_desc_hover_color_div', 'desc_hover_background_color_div', 'desc_hover_font_color_div', 'column_level_other_arp_ok_div__button_2' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
							),
							'header_level_options'     => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_object', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
								),
							),
							'pricing_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'pricing_level_options__button_1' => array( 'price_text', 'arp_fontawesome', 'pricing_level_other_arp_ok_div__button_1' ),
								),
							),
							'body_level_options'       => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'column_description_level' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_description_level__button_1' => array( 'column_description', 'arp_fontawesome', 'column_description_level_other_arp_ok_div__button_1' ),
								),
							),
							'footer_level_options'     =>
							array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_size', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
						),
						'arplitetemplate_2'  => array(
							'column_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array( 'column_highlight', 'set_hidden', 'column_background', 'column_hover_background', 'select_ribbon', 'is_post_variable', 'post_variables_content', 'column_level_other_arp_ok_div__button_1', 'is_column_clickable_wrapper', 'column_other_background_image' ),
									'column_level_options__button_2' => array( 'arp_custom_color_tab_column', 'arp_normal_custom_color_tab_column', 'arp_header_color_div', 'header_font_color_div', 'arp_header_hover_color_div', 'header_hover_font_color_div', 'arp_column_color_div', 'column_background_color_div', 'arp_column_hover_color_div_column', 'column_hover_background_color_div', 'arp_price_color_div', 'price_font_color_div', 'arp_price_hover_color_div', 'price_hover_font_color_div', 'arp_footer_color_div', 'footer_font_color_div', 'arp_button_color_div', 'button_background_color_div', 'button_font_color_div', 'arp_body_background_color_div', 'arp_body_background_color_div_title', 'arp_odd_color_div', 'odd_font_color_div', 'arp_even_color_div', 'even_font_color_div', 'arp_footer_hover_color_div', 'footer_hover_font_color_div', 'arp_hover_button_color_div', 'button_hover_background_color_div', 'button_hover_font_color_div', 'arp_body_hover_background_color_div', 'arp_body_hover_background_color_div_title', 'arp_odd_hover_color_div', 'odd_hover_font_color_div', 'arp_even_hover_color_div', 'even_hover_font_color_div', 'arp_shortcode_div', 'arp_shortcode_background', 'arp_shortcode_font_color', 'arp_shortcode_hover_div', 'arp_shortcode_hover_background', 'arp_shortcode_hover_font_color', 'column_level_other_arp_ok_div__button_2' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
							),
							'header_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
									'header_level_options__button_2' => array( 'additional_shortcode', 'arp_object', 'arp_fontawesome', 'arp_shortcode_customization_style_div', 'arp_shortcode_customization_size_div', 'header_level_other_arp_ok_div__button_2' ),
								),
							),
							'pricing_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'pricing_level_options__button_1' => array( 'price_text', 'arp_fontawesome', 'pricing_level_other_arp_ok_div__button_1' ),
								),
							),
							'body_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'footer_level_options'  =>
							array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'footer_level_options__button_1' => array( 'footer_text', 'above_below_button', 'footer_level_options_arp_ok_div__button_1' ),
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_size', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
						),
						'arplitetemplate_26' => array(
							'column_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'column_level_options__button_1' => array( 'column_highlight', 'set_hidden', 'column_background', 'column_hover_background', 'select_ribbon', 'is_post_variable', 'post_variables_content', 'column_level_other_arp_ok_div__button_1', 'is_column_clickable_wrapper', 'column_other_background_image' ),
									'column_level_options__button_2' => array( 'arp_custom_color_tab_column', 'arp_normal_custom_color_tab_column', 'arp_header_color_div', 'header_background_color_div_other_col', 'header_font_color_div', 'arp_header_hover_color_div', 'header_hover_background_color_div', 'header_hover_font_color_div', 'arp_body_background_color_div', 'arp_body_background_color_div_title', 'arp_odd_color_div', 'odd_font_color_div', 'arp_even_color_div', 'even_font_color_div', 'arp_body_hover_background_color_div', 'arp_body_hover_background_color_div_title', 'arp_odd_hover_color_div', 'odd_hover_font_color_div', 'arp_even_hover_color_div', 'even_hover_font_color_div', 'arp_column_color_div', 'column_background_color_div', 'arp_column_hover_color_div_column', 'column_hover_background_color_div', 'arp_button_color_div', 'button_background_color_div', 'button_font_color_div', 'button_hover_background_color_div', 'button_hover_font_color_div', 'arp_hover_button_color_div', 'arp_shortcode_div', 'arp_shortcode_background', 'arp_shortcode_font_color', 'arp_shortcode_hover_div', 'arp_shortcode_hover_background', 'arp_shortcode_hover_font_color', 'column_level_other_arp_ok_div__button_2' ),
									'column_level_options__button_3' => array( 'arp_custom_color_tab_column' ),
								),
							),
							'header_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'header_level_options__button_1' => array( 'column_title', 'arp_fontawesome', 'header_level_other_arp_ok_div__button_1' ),
									'header_level_options__button_2' => array( 'additional_shortcode', 'arp_object', 'arp_fontawesome', 'arp_shortcode_customization_style_div', 'arp_shortcode_customization_size_div', 'header_level_other_arp_ok_div__button_2' ),
								),
							),
							'body_level_options'    => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(),
							),
							'body_li_level_options' => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'body_li_level_options__button_1' => array( 'body_li_add_shortcode', 'arp_li_content_type', 'arp_object', 'description', 'body_li_level_other_arp_ok_div__button_1' ),
									'body_li_level_options__button_2' => array( 'tooltip', 'arp_fontawesome', 'body_li_level_other_arp_ok_div__button_2' ),
									'body_li_level_options__button_3' => array( 'custom_css', 'body_li_level_caption_arp_ok_div__button_3' ),
								),
							),

							'footer_level_options'  => array(
								'caption_column_buttons' => array(),
								'other_columns_buttons'  => array(
									'footer_level_options__button_2' => array( 'button_text', 'add_icon', 'button_options_other_arp_ok_div__button_1' ),
									'footer_level_options__button_3' => array( 'button_image', 'add_shortcode', 'button_options_other_arp_ok_div__button_2' ),
									'footer_level_options__button_4' => array( 'redirect_link', 'open_in_new_window', 'open_in_new_window_actual', 'external_btn', 'hide_default_btn', 'nofollow_link_option', 'button_options_other_arp_ok_div__button_3' ),
								),
							),
						),
					),
				),
			)
		);
		return $rpttempbutoptionsarr;
	}

	function set_css() {
		global $arpricelite_version,$pagenow;
		wp_register_style( 'arplite_admin_css', ARPLITE_PRICINGTABLE_URL . '/css/arprice_admin.css', array(), $arpricelite_version );

		wp_register_style( 'fontawesome', ARPLITE_PRICINGTABLE_URL . '/css/font-awesome.css', array(), $arpricelite_version );

		wp_register_style( 'tipso', ARPLITE_PRICINGTABLE_URL . '/css/tipso.min.css', array(), $arpricelite_version );

		wp_register_style( 'arplite_font_css_admin', ARPLITE_PRICINGTABLE_URL . '/fonts/arp_fonts.css', array(), $arpricelite_version );

		wp_register_style( 'bootstrap-tour-standalone', ARPLITE_PRICINGTABLE_URL . '/css/bootstrap-tour-standalone.css', array(), $arpricelite_version );

		wp_register_style( 'arplite_menu_css', ARPLITE_PRICINGTABLE_URL . '/css/arplite_menu.css', array(), $arpricelite_version );

		wp_register_style( 'arplite_admin_css_3.8', ARPLITE_PRICINGTABLE_URL . '/css/arprice_admin_3.8.css', array(), $arpricelite_version );

		wp_enqueue_style( 'arplite_menu_css' );

		wp_register_style( 'arplite_editor_front_css', ARPLITE_PRICINGTABLE_URL . '/css/arprice_front.css', array(), $arpricelite_version );

		if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'arpricelite' || $_GET['page'] == 'arplite_add_pricing_table' || $_GET['page'] == 'arp_analytics' || $_GET['page'] == 'arplite_import_export' || $_GET['page'] == 'arplite_global_settings' || $_GET['page'] == 'arplite_upgrade_to_premium' || $_GET['page'] == 'arplite_ab_testing' ) ) {
			if ( version_compare( $GLOBALS['wp_version'], '3.7', '>' ) ) {
				wp_enqueue_style( 'arplite_admin_css_3.8' );
			}

			wp_enqueue_style( 'arplite_admin_css' );

			do_action( 'arplite_enqueue_internal_style' );
			if ( $_GET['page'] != 'arplite_global_settings' && $_GET['page'] != 'arplite_import_export' && $_GET['page'] != 'arplite_ab_testing') {

				wp_enqueue_style( 'fontawesome' );

				wp_enqueue_style( 'bootstrap-tour-standalone' );
			}

			if ( isset( $_GET['page'] ) and $_GET['page'] == 'arpricelite' ) {
				wp_enqueue_style( 'tipso' );
			}
		}

		if ( $pagenow == 'plugins.php' ) {
			wp_register_style( 'arplite-feedback-popup-style', ARPLITE_PRICINGTABLE_URL . '/css/arplite_deactivation_style.css', array(), $arpricelite_version );
			wp_enqueue_style( 'arplite-feedback-popup-style' );
		}
	}

	/* Setting Frond CSS */
	function set_front_css() {
		global $arpricelite_version,$arpricelite_assset_version;
		if ( ! is_admin() ) {
			/* Common CSS */
			wp_register_style( 'arplite_front_css', ARPLITE_PRICINGTABLE_URL . '/css/arprice_front.css', array(), $arpricelite_assset_version );

			/* Font Awesome CSS */
			wp_register_style( 'fontawesome', ARPLITE_PRICINGTABLE_URL . '/css/font-awesome.css', array(), $arpricelite_assset_version );

			/* Font CSS */
			wp_register_style( 'arplite_font_css_front', ARPLITE_PRICINGTABLE_URL . '/fonts/arp_fonts.css', array(), $arpricelite_assset_version );
		}
	}

	function arplite_front_assets() {
		$arp_load_js_css = get_option( 'arplite_load_js_css' );
		if ( isset( $arp_load_js_css ) && $arp_load_js_css == 'arplite_load_js_css' ) {

			wp_enqueue_script( 'arplite_front_js' );

			wp_enqueue_style( 'arplite_front_css' );

			$is_enable_font_awesome = get_option( 'enable_font_loading_icon' );
			if ( in_array( 'enable_fontawesome_icon', $is_enable_font_awesome ) ) {
				wp_enqueue_style( 'fontawesome' );
			}

			wp_enqueue_style( 'arplite_font_css_front' );
		}
	}

	/* Setting CSS as per Selected Template */

	function arplite_enqueue_template_css() {

		global $post, $arpricelite_form, $arpricelite_version;

		$upload_main_url = ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css';

		$post_content = isset( $post->post_content ) ? $post->post_content : '';
		$parts        = explode( '[ARPLite', $post_content );
		if ( is_array( $parts ) && key_exists( 1, $parts ) ) {
			$myidpart = explode( 'id=', $parts[1] );
			if ( is_array( $myidpart ) && key_exists( 1, $myidpart ) ) {
				$myid = explode( ']', $myidpart[1] );
			}
		}

		if ( ! is_admin() ) {
			global $wp_query;
			$posts   = $wp_query->posts;
			$pattern = '\[(\[?)(ARPLite)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			$frm_ids = array();
			if ( is_array( $posts ) ) {

				foreach ( $posts as $post ) {
					if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'ARPLite', $matches[2] ) ) {
						$frm_ids[] = $matches;
					}
				}

				$formids = array();
				if ( is_array( $frm_ids ) && count( $frm_ids ) > 0 ) {

					foreach ( $frm_ids as $mat ) {

						if ( is_array( $mat ) and count( $mat ) > 0 ) {
							foreach ( $mat as $k => $v ) {

								foreach ( $v as $key => $val ) {
									$parts = explode( 'id=', $val );
									if ( $parts > 0 && isset( $parts[1] ) ) {

										if ( stripos( $parts[1], ']' ) !== false ) {
											$partsnew  = explode( ']', $parts[1] );
											$formids[] = $partsnew[0];
										} elseif ( stripos( $parts[1], ' ' ) !== false ) {

											$partsnew  = explode( ' ', $parts[1] );
											$formids[] = $partsnew[0];
										} else {

										}
									}
								}
							}
						}
					}
				}
			}

			$newvalarr = array();

			if ( isset( $formids ) and is_array( $formids ) && count( $formids ) > 0 ) {
				foreach ( $formids as $newkey => $newval ) {
					$newval = str_replace( '"', '', $newval );
					$newval = str_replace( "'", '', $newval );
					if ( stripos( $newval, ' ' ) !== false ) {
						$partsnew    = explode( ' ', $newval );
						$newvalarr[] = $partsnew[0];
					} else {
						$newvalarr[] = $newval;
					}
				}
			}

			if ( $newvalarr ) {
				$newvalues_enqueue = $arpricelite_form->get_table_enqueue_data( $newvalarr );
			}

			if ( isset( $newvalues_enqueue ) && is_array( $newvalues_enqueue ) && count( $newvalues_enqueue ) > 0 ) {
				$to_google_map = 0;
				$templates     = array();
				$is_template   = 0;

				foreach ( $newvalues_enqueue as $n => $newqnqueue ) {
					if ( $newqnqueue['googlemap'] ) {
						$to_google_map = 1;
					}

					if ( $newqnqueue['template_name'] != 0 ) {
						$templates[] = $newqnqueue['template_name'];
					} else {
						$templates[] = $n;
					}

					if ( ! empty( $newqnqueue['is_template'] ) ) {
						$is_template = $newqnqueue['is_template'];
					}
				}

				$templates = array_unique( $templates );

				if ( $templates ) {
					wp_enqueue_script( 'arplite_front_js' );

					wp_enqueue_style( 'arplite_front_css' );

					foreach ( $newvalues_enqueue as $template_id => $newqnqueue ) {
						if ( isset( $newqnqueue['is_template'] ) && ! empty( $newqnqueue['is_template'] ) ) {
							wp_register_style( 'arplitetemplate_' . $newqnqueue['template_name'] . '_css', ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $newqnqueue['template_name'] . '.css', array(), $arpricelite_version );
							wp_enqueue_style( 'arplitetemplate_' . $newqnqueue['template_name'] . '_css' );
							do_action( 'arplite_front_inline_css', $newqnqueue['template_name'], 0 );
						} else {
							wp_register_style( 'arplitetemplate_' . $template_id . '_css', ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $template_id . '.css', array(), $arpricelite_version );
							wp_enqueue_style( 'arplitetemplate_' . $template_id . '_css' );
							do_action( 'arplite_front_inline_css', $template_id, 0 );
						}


					}
				}
			}
		}
	}

	function arplite_front_inline_css_callback( $template_id, $tbl_preview = 0, $print_styles = false ){
		
		if( '' != $template_id ){
			
			global $wpdb, $arpricelite_form, $arpricelite_default_settings, $arplite_mainoptionsarr;
			if( empty( $arplite_mainoptionsarr ) ){
    			$arplite_mainoptionsarr = $this->arp_mainoptions();
    		}
			$arplite_front_inline_css = '';

			if( 1 == $tbl_preview ){
				$post_values = get_option($_REQUEST['optid']);
				$filtered_data = json_decode( $post_values, true );

                $arp_template_name = $filtered_data['table_opt']['table_name'];
                $general_option = maybe_unserialize( $filtered_data['table_opt']['general_options'] );
                $opts = maybe_unserialize( $filtered_data['table_col_opt'] );
                $id = $table_id = intval( $_REQUEST['tbl'] );
                $is_animated = $filtered_data['table_opt']['is_animated'];
                $is_template = $filtered_data['table_opt']['is_template'];
			} else {
				$sql = $wpdb->get_row( $wpdb->prepare( "SELECT a.*, ao.* FROM `" . $wpdb->prefix . "arplite_arprice` a LEFT JOIN `". $wpdb->prefix . "arplite_arprice_options` ao ON a.ID = ao.table_id WHERE a.ID = %d", $template_id ) );

				$general_option = maybe_unserialize( $sql->general_options );

				$template_name = $sql->template_name;
				
				$is_template = $sql->is_template;
	            
	            $is_animated = $sql->is_animated;

	            $opts = maybe_unserialize( $sql->table_options );

			}
            $column_settings = $general_option['column_settings'];

            $ref_template = $general_option['general_settings']['reference_template'];

            if ($column_settings['column_border_radius_top_left'] > 0 or $column_settings['column_border_radius_top_right'] > 0 or $column_settings['column_border_radius_bottom_right'] > 0 or $column_settings['column_border_radius_bottom_left'] > 0) {

	            $arplite_front_inline_css .= ".arplitetemplate_" . $template_id ." .arp_column_content_wrapper { ";

	            $arplite_front_inline_css .= "border-radius:" . $column_settings['column_border_radius_top_left'] . "px " . $column_settings['column_border_radius_top_right'] . "px " . $column_settings['column_border_radius_bottom_right'] . "px " . $column_settings['column_border_radius_bottom_left'] . "px !important;overflow:hidden !important;";

	            $arplite_front_inline_css .= "-webkit-border-radius:" . $column_settings['column_border_radius_top_left'] . "px " . $column_settings['column_border_radius_top_right'] . "px " . $column_settings['column_border_radius_bottom_right'] . "px " . $column_settings['column_border_radius_bottom_left'] . "px !important;overflow:hidden !important;";

	            $arplite_front_inline_css .= "-o-border-radius:" . $column_settings['column_border_radius_top_left'] . "px " . $column_settings['column_border_radius_top_right'] . "px " . $column_settings['column_border_radius_bottom_right'] . "px " . $column_settings['column_border_radius_bottom_left'] . "px !important;overflow:hidden !important;";

	            $arplite_front_inline_css .= "-moz-border-radius:" . $column_settings['column_border_radius_top_left'] . "px " . $column_settings['column_border_radius_top_right'] . "px " . $column_settings['column_border_radius_bottom_right'] . "px " . $column_settings['column_border_radius_bottom_left'] . "px !important;overflow:hidden !important;";

	            $arplite_front_inline_css .= "}";

	        }

	        $arplite_front_inline_css .= '[class*="fa-"] {width: auto; height: auto; top: 0px; vertical-align: unset;}';
	        $arplite_front_inline_css .= '[class*="fab-"] {width: auto; height: auto; top: 0px; vertical-align: unset;}';
	        $arplite_front_inline_css .= '[class*="far-"] {width: auto; height: auto; top: 0px; vertical-align: unset;}';
	        $arplite_front_inline_css .= '[class*="fas-"] {width: auto; height: auto; top: 0px; vertical-align: unset;}';

	        if( isset( $tbl_preview ) && ( 1 == $tbl_preview || 2 == $tbl_preview ) ){
	        	$arplite_front_inline_css .= 'body::before,body::after{ display:none !important; }';
	        }
	        
	        $arplite_front_inline_css .= $arpricelite_form->arp_render_customcss( $template_id, $general_option, $tbl_preview, $opts, $is_animated );

	        $arprice_hide_section_array = $arpricelite_default_settings->arprice_hide_section_array();
	        $arprice_hide_section_array = $arprice_hide_section_array[$ref_template];

	        if (isset($column_settings['hide_footer_global']) && $column_settings['hide_footer_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_footer'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . " " . $css_classs . " {display : none !important;}";
	            }
	        }
	        if (isset($column_settings['hide_header_global']) && $column_settings['hide_header_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_header'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . "  " . $css_classs . " {display : none !important;}";
	            }
	        }

	        if (isset($column_settings['hide_price_global']) && $column_settings['hide_price_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_price'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . "  " . $css_classs . "  {display : none !important;}";
	            }
	        }
	        if (isset($column_settings['hide_feature_global']) && $column_settings['hide_feature_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_feature'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . "  " . $css_classs . " {display : none !important;}";
	            }
	        }
	        if (isset($column_settings['hide_description_global']) && $column_settings['hide_description_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_description'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . "  " . $css_classs . "  {display : none !important;}";
	            }
	        }
	        if (isset($column_settings['hide_header_shortcode_global']) && $column_settings['hide_header_shortcode_global'] == '1') {
	            foreach ($arprice_hide_section_array['arp_header_shortcode'] as $css_classs) {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . "  " . $css_classs . "  {display : none !important;}";
	            }
	        }

	        if (get_option('arplite_desktop_responsive_size') and get_option('arplite_desktop_responsive_size') > 0 and $general_option['column_settings']['is_responsive'] == 1) {
	            $arplite_front_inline_css .= ".arplite_template_main_container{ max-width:" . get_option('arplite_desktop_responsive_size') . "px !important; }";
	        }

	        if (get_option('arplite_mobile_responsive_size') and get_option('arplite_mobile_responsive_size') > 0 and $general_option['column_settings']['is_responsive'] == 1) {
	            $arplite_front_inline_css .= "
				@media all and (max-width:" . get_option('arplite_mobile_responsive_size') . "px){";
	            $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . " .ArpPricingTableColumnWrapper.no_animation.maincaptioncolumn, .arplitetemplate_" . $template_id . " .ArpPricingTableColumnWrapper.no_animation, .arplitetemplate_" . $template_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn, .arplitetemplate_" . $template_id . " .ArpPricingTableColumnWrapper{";

	            $arplite_front_inline_css .= "width:100%;";
	            $arplite_front_inline_css .="margin-left:auto !important;";

	            $arplite_front_inline_css .="margin-right:auto !important;";

	            $arplite_front_inline_css .= "max-width:320px !important;";
	            $arplite_front_inline_css .= "float:none !important;";
	            $arplite_front_inline_css .= "display:inline-block !important;";
	            $arplite_front_inline_css .= "}";

	            $arplite_front_inline_css .= ".ArpTemplate_main ,";
	            $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . " .arp_inner_wrapper_all_columns ,";
	            $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . " .arp_allcolumnsdiv {";
	            $arplite_front_inline_css .= "width:100%;";
	            $arplite_front_inline_css .= "text-align:center;";
	            $arplite_front_inline_css .= "}";

	            $arplite_front_inline_css .= "}";
	        }

	        if (get_option('arplite_mobile_responsive_size') and get_option('arplite_mobile_responsive_size') > 0) {
	            $arplite_front_inline_css .= "@media all and (max-width:" . get_option('arplite_mobile_responsive_size') . "px){";

	            if ($ref_template == 'arplitetemplate_1') {
	                $arplite_front_inline_css .= ".arplitetemplate_" . $template_id . " .maincaptioncolumn .arpplan{";
	                $arplite_front_inline_css .= "border-right:1px solid #E3E3E3 !important;";
	                $arplite_front_inline_css .= "}";
	            }

	            $arplite_front_inline_css .= "}";
	        }

	        foreach( $opts['columns'] as $j => $columns ){
	        	if( isset( $columns['ribbon_setting'] ) && '' != $columns['ribbon_setting']['arp_ribbon'] && '' != $columns['ribbon_setting']['arp_ribbon_content'] ){
	        		
	        		$basic_col = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
                    $ribbon_bg_col = $columns['ribbon_setting']['arp_ribbon_bgcol'];
                    $base_color = $ribbon_bg_col;
                    $base_color_key = array_search($base_color, $basic_col);
                    $gradient_color = $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'][$base_color_key];
                    $ribbon_border_color = $arplite_mainoptionsarr['general_options']['arp_ribbon_border_color'][$base_color_key];
		        	if ($columns['ribbon_setting']['arp_ribbon'] != 'arp_ribbon_6') {
		        		if ( in_array( $base_color, $basic_col ) ) {
		        			if ( 'arp_ribbon_1' == $columns['ribbon_setting']['arp_ribbon'] ) {
	        					$arplite_front_inline_css .= ".arplite_price_table_" . $template_id . " #main_" . $j . " .arp_ribbon_content:before, .arplite_price_table_" . $template_id . " #main_" . $j . " .arp_ribbon_content:after{";
                                $arplite_front_inline_css .= "border-top-color:" . $gradient_color . " !important;";
                                $arplite_front_inline_css .= "}";

                                $arplite_front_inline_css .= ".arplite_price_table_" . $template_id . " #main_" . $j . " .arp_ribbon_content{";
                                $arplite_front_inline_css .= "background:" . $gradient_color . ";";
                                $arplite_front_inline_css .= "background-color:" . $gradient_color . ";";
                                $arplite_front_inline_css .= "background-image:-moz-linear-gradient(0deg," . $gradient_color . "," . $base_color . "," . $gradient_color . ")";
                                $arplite_front_inline_css .= "background-image:-webkit-gradient(linear, 0 0, 0 0, color-stop(0%," . $gradient_color . "), color-stop(50%," . $base_color . "), color-stop(100%," . $gradient_color . "));";
                                $arplite_front_inline_css .= "background-image:-webkit-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
                                $arplite_front_inline_css .= "background-image:-o-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
                                $arplite_front_inline_css .= "background-image:linear-gradient(90deg," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
                                $arplite_front_inline_css .= "background-image:-ms-linear-gradient(left," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
                                $arplite_front_inline_css .= "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" . $base_color . "', endColorstr='" . $gradient_color . "', GradientType=1);";
                                $arplite_front_inline_css .= '-ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="' . $base_color . '", endColorstr="' . $gradient_color . '", GradientType=1)";';
                                $arplite_front_inline_css .= "background-repeat:repeat-x;";
                                $arplite_front_inline_css .= "border-top:1px solid {$ribbon_border_color};";
                                $arplite_front_inline_css .= "box-shadow:13px 1px 2px rgba(0,0,0,0.6);";
                                $arplite_front_inline_css .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                $arplite_front_inline_css .= "}";

		        			}
		        		} else {
		        			if ( 'arp_ribbon_1' == $columns['ribbon_setting']['arp_ribbon'] ) {
                                $arplite_front_inline_css .= ".arplite_price_table_" . $template_id . " #main_" . $j . " .arp_ribbon_content:before,#main_" . $j . " .arp_ribbon_content:after{";
                                	$arplite_front_inline_css .= "border-top-color:" . $base_color . "  !important;";
                                $arplite_front_inline_css .= "}";
                            }

                            $arplite_front_inline_css .= ".arplite_price_table_" . $template_id . " #main_" . $j . " .arp_ribbon_content{";
                            $arplite_front_inline_css .= "background:" . $base_color . ";";
                            $arplite_front_inline_css .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                            $arplite_front_inline_css .= "}";
		        		}
		        	}
	        	}
	        }

	        wp_add_inline_style( 'arplite_front_css', $arplite_front_inline_css );
			if( $print_styles ){
				wp_add_inline_style( 'arplitetemplate_' . $template_id. '_css', $arplite_front_inline_css );
			}
		}

	}

	/* Setting Front Side JavaScript */

	function set_front_js() {
		global $arpricelite_version,$arpricelite_assset_version;
		if ( ! is_admin() ) {
			// Setting jQuery
			wp_enqueue_script( 'jquery' );

			// Common JS
			wp_register_script( 'arplite_front_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice_front.js', array(), $arpricelite_assset_version );

			wp_enqueue_script( 'jquery-ui-core' );

			wp_enqueue_script( 'jquery-effects-slide' );
		}
	}

	/* Setting Admin JavaScript */

	function set_js() {
		global $arpricelite_version, $pagenow;
		if ( $pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			return;
		}
		wp_register_script( 'arplite_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice.js', array(), $arpricelite_version );

		wp_register_script( 'arplite_sortable_resizable_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice_sortable_resizable.js', array(), $arpricelite_version );

		wp_register_script( 'bpopup', ARPLITE_PRICINGTABLE_URL . '/js/jquery.bpopup.min.js', array(), $arpricelite_version );

		wp_register_script( 'tipso', ARPLITE_PRICINGTABLE_URL . '/js/tipso.min.js', array(), $arpricelite_version );


		wp_register_script( 'arplite_editor_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice_editor.js', array(), $arpricelite_version );

		wp_register_script( 'arpricelite-sortable-js', ARPLITE_PRICINGTABLE_URL . '/js/sortable.min.js', array(), $arpricelite_version);

		wp_register_script( 'html2canvas', ARPLITE_PRICINGTABLE_URL . '/js/html2canvas.js', array(), $arpricelite_version );

		wp_register_script( 'bootstrap-tour-standalone', ARPLITE_PRICINGTABLE_URL . '/js/bootstrap-tour-standalone.js', array(), $arpricelite_version );

		wp_register_script( 'arplite_tour_guide', ARPLITE_PRICINGTABLE_URL . '/js/arprice_tour_guide.js', array(), $arpricelite_version );

		wp_register_script( 'arplite_dashboard_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice_dashboard.js', array(), $arpricelite_version );

		if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'arpricelite' || $_GET['page'] == 'arplite_add_pricing_table' || $_GET['page'] == 'arplite_analytics' || $_GET['page'] == 'arplite_import_export' || $_GET['page'] == 'arplite_global_settings' || $_GET['page'] == 'arplite_ab_testing') && ( $pagenow !== 'edit.php' && $pagenow !== 'post.php' && $pagenow !== 'post-new.php' ) ) {

			wp_register_script( 'jscolor', ARPLITE_PRICINGTABLE_URL . '/js/jscolor.js', array(), $arpricelite_version );
			wp_enqueue_script( 'jquery' );

			if ( $_GET['page'] == 'arpricelite' ) {
				wp_enqueue_script( 'bootstrap-tour-standalone' );
				wp_enqueue_script( 'arplite_tour_guide' );
				do_action( 'arplite_add_tour_guide_js' );
			}

			if ( $_GET['page'] != 'arplite_import_export' ) {
				wp_enqueue_script( 'bpopup' );
			}

			if ( isset( $_GET['page'] ) and ( $_GET['page'] == 'arpricelite' || $_GET['page'] == 'arplite_global_settings' || $_GET['page'] == 'arplite_ab_testing'|| $_GET['page'] == 'arplite_import_export' ) ) {
				if ( $_GET['page'] == 'arpricelite' && isset( $_GET['arp_action'] ) ) {
					wp_enqueue_script( 'arplite_js' );
					wp_enqueue_script( 'arplite_sortable_resizable_js' );
					wp_enqueue_script( 'arplite_editor_js' );

					do_action( 'arplite_enqueue_internal_script' );

					wp_enqueue_script( 'html2canvas' );
					wp_enqueue_script( 'jquery-ui-core' );

					wp_enqueue_script( 'jquery-effects-slide' );

					wp_enqueue_script( 'arpricelite-sortable-js' );

					//wp_enqueue_script( 'jquery-ui-sortable' );

					wp_enqueue_script( 'jquery-ui-slider' );

					wp_enqueue_script( 'media-upload' );
					wp_enqueue_script( 'jscolor' );
					wp_enqueue_script( 'sack' );

					wp_enqueue_script( 'bootstrap-tour-standalone' );
					wp_enqueue_script( 'arplite_tour_guide' );
				}

				if ( ( $_GET['page'] == 'arpricelite' && ! isset( $_GET['arp_action'] ) ) || $_GET['page'] == 'arplite_global_settings' || $_GET['page'] == 'arplite_import_export' || $_GET['page'] == 'arplite_ab_testing') {
					wp_enqueue_script( 'arplite_dashboard_js' );
					if ( $_GET['page'] == 'arpricelite' && isset( $_GET['arp_action'] ) && $_GET['arp_action'] == '' ) {
						wp_enqueue_script( 'bootstrap-tour-standalone' );
						wp_enqueue_script( 'arplite_tour_guide' );
					}
				}

				wp_enqueue_script( 'tipso' );

				wp_localize_script( 'arplite_editor_js', 'arplite_editor_obj', array(
                    'inherit_font' => esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table')
                ) );
			}
		}

		if ( $pagenow == 'plugins.php' ) {

			wp_register_script( 'arplite-feedback-popup-script', ARPLITE_PRICINGTABLE_URL . '/js/arplite_deactivation_script.js', array( 'jquery' ), $arpricelite_version );
			wp_enqueue_script( 'arplite-feedback-popup-script' );

			$scriptData = 'var arplite_detailsStrings = {
				"setup-difficult":"' . esc_html__( 'What was the dificult part?', 'arprice-responsive-pricing-table' ) . '",
				"docs-improvement":"' . esc_html__( 'What can we describe more?', 'arprice-responsive-pricing-table' ) . '",
				"features":"' . esc_html__( 'How could we improve?', 'arprice-responsive-pricing-table' ) . '",
				"better-plugin":"' . esc_html__( 'Can you mention it?', 'arprice-responsive-pricing-table' ) . '",
				"incompatibility":"' . esc_html__( 'With what plugin or theme is incompatible?', 'arprice-responsive-pricing-table' ) . '",
				"bought-premium":"' . esc_html__( 'Please specify experience', 'arprice-responsive-pricing-table' ) . '",
				"maintenance":"' . esc_html__( 'Please specify', 'arprice-responsive-pricing-table' ) . '"
			};

			var pluginName = "' . esc_attr( 'arprice-responsive-pricing-table' ) . '";
			var pluginSecurity = "' . wp_create_nonce( 'arplite_deactivate_plugin' ) . '";
			';

			wp_add_inline_script( 'arplite-feedback-popup-script', $scriptData );
		}
	}

	function arplite_enqueue_inline_editor_css() {
		global $arplite_mainoptionsarr,$arpricelite_fonts,$arpricelite_version,$arplite_editor_css;
		$handler      = 'arplite_admin_css';
		$data         = '';

		if( isset($_GET['page']) && 'arpricelite' == $_GET['page'] && isset($_GET['arp_action']) && '' != $_GET['arp_action'] ){

			$basic_colors = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
			foreach ( $basic_colors as $key => $colors ) {
				$base_color     = $colors;
				$base_color_key = array_search( $base_color, $basic_colors );
				$gradient_color = $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'][ $base_color_key ];
				$data          .= '.basic_color_box.basic_color_' . $key . '{
	                background:' . $base_color . ';
	                background-color:' . $base_color . ';
	                background-image:-moz-linear-gradient(top, ' . $base_color . ', ' . $gradient_color . ');";
	                background-image:-webkit-gradient(linear, 0 0, 0 100%, from(' . $base_color . '), to(' . $gradient_color . '));
	                background-image:-webkit-linear-gradient(top, ' . $base_color . ', ' . $gradient_color . ');
	                background-image:-o-linear-gradient(top, ' . $base_color . ', ' . $gradient_color . ');
	                background-image:linear-gradient(to bottom, ' . $base_color . ', ' . $gradient_color . ');
	                filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=' . $base_color . ', endColorstr=' . $gradient_color . ', GradientType=0);
	                -ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="' . $base_color . '", endColorstr="' . $gradient_color . '", GradientType=0)";
	                    background-repeat:repeat-x;
	            }';
			}
			$google_fonts = $arpricelite_fonts->google_fonts_list();
			
			$font_array = array_chunk($google_fonts, 150);

			foreach ($font_array as $key => $font_values) {
			    $google_fonts_string = implode('|', $font_values);

			    if (is_ssl()) {
			        $google_font_url = "https://fonts.googleapis.com/css?family=" . $google_fonts_string;
			    } else {
			        $google_font_url = "http://fonts.googleapis.com/css?family=" . $google_fonts_string;
			    }

			    wp_enqueue_style( 'arplite-editor-google-fonts'.$key, $google_font_url, array(), $arpricelite_version );

			}
		} else if ( isset($_GET['page'] ) && 'arpricelite' == $_GET['page'] ){

			$arplite_editor_data = '@import url(https://fonts.googleapis.com/css?family=Ubuntu:400,500,700);';
			wp_add_inline_style( $handler, $arplite_editor_data );

		} else if( isset( $_GET['page'] ) && 'arplite_import_export' == $_GET['page'] ){
			if (is_ssl()){
			    $google_font_url = "https://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			} else {
			    $google_font_url = "http://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			}
			$data .= '@import url('.$google_font_url.');#wpcontent,#wpfooter{background:#fff}';
		} else if( isset( $_GET['page'] ) && 'arplite_ab_testing' == $_GET['page'] ){
			if (is_ssl()){
			    $google_font_url = "https://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			} else {
			    $google_font_url = "http://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			}
			$data .= '@import url('.$google_font_url.');#wpcontent,#wpfooter{background:#fff}';
		} else if( isset( $_GET['page'] ) && 'arplite_global_settings' == $_GET['page'] ){
			if (is_ssl()){
			    $google_font_url = "https://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			} else {
			    $google_font_url = "http://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Open+Sans";
			}
			
			$data .= '@import url('.$google_font_url.');#wpcontent,#wpfooter{background:#fff}.purchased_info{color:#7cba6c;font-weight:700;font-size:15px}#license_success{color:#8ccf7a!important}#arfresetlicenseform{border-radius:0;text-align:center;width:700px;height:500px;left:35%;border:none;background:#fff!important;padding-top:15px;margin-top:8%!important;margin:0 auto}.arfnewmodalclose{font-size:15px;font-weight:700;height:19px;position:absolute;right:3px;top:5px;width:19px;cursor:pointer;color:#D1D6E5}.newform_modal_title{font-size:25px;line-height:25px;margin-bottom:10px}.newmodal_field_title{font-size:16px;line-height:16px;margin-bottom:10px}.license-details-block{padding:20px;width:450px;margin:0 auto;position:relative;background:#fff;border:1px solid #b3b3b3;color:#333;border-radius:5px;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;height:110px}.arp_version_detail{padding:5px 0 0;width:100%;height:auto;font-size:16px}.arp_version_table{margin-top:50px;width:100%;text-align:left}.arp_version_table_header th{background:#31363d;color:#fff;padding:15px;font-size:18px}.arp_version_feature_detail td{padding:15px}.arp_version_feature_detail td:first-child,.arp_version_table_header th:first-child{width:370px}td#arp_premium_row{background:#f0f7f8;font-weight:700;width:300px}.arp_premium_version_info_belt{box-shadow:0 0 10px #ffa800;float:left;font-size:24px;font-weight:700;margin-bottom:50px;margin-top:90px;min-height:75px;line-height:70px;text-align:center;width:100%;color:#ffa800}.arp_premium_img{margin:15px 0;padding:5px;text-align:center}h1.arp_highlighted_points{font-size:18px;color:#1f98ff}#arp_sub_label{font-weight:700;margin:15px 0}.btn-gold{background:#62ca24;box-shadow:0 1px 1px 0 #747B73;border-radius:6px;color:#fff;cursor:pointer;display:inline-block;font-weight:700;line-height:16px;padding:20px;width:250px;font-size:16px}';
		}
		wp_add_inline_style( $handler, $data );
	}	

	function arplite_enqueue_inline_editor_js() {
		global $arplite_tempbuttonsarr,$arplite_mainoptionsarr,$arplite_templatecssinfoarr,$arplite_templateresponsivearr,$arplite_template_editor_arr,$arpricelite_default_settings,$arplite_templatesectionsarr,$arplite_templatecustomskinarr;
		$handler = 'arplite_js';

		$content_url = ARPLITE_PRICINGTABLE_URL . '/core/views/arplite_column_background_color_min.json';
		$arguments = array(
			'sslverify' => false
		);
		$response = wp_remote_get( $content_url, $arguments );

		$content = $response['body'];
		
		$data    = 'var arplite_column_background_color_json = ' . $content . ';';

		$data .= 'function global_template_options(){
            var tmpbuttonoptions;
            tmpbuttonoptions = ' . wp_json_encode( $arplite_tempbuttonsarr ) . ';
            return tmpbuttonoptions;
        }';

		$data .= 'function global_ribbon_array(){
            var arpribbonarr;
            arpribbonarr = ' . wp_json_encode( $arplite_mainoptionsarr['general_options']['template_options']['arp_template_ribbons'] ) . ';
            return arpribbonarr;
        }';

		$data .= 'function ribbon_basic_colors() {
            var arp_basic_ribbon_colors;
            arp_basic_ribbon_colors = ' . wp_json_encode( $arplite_mainoptionsarr['general_options']['arp_basic_colors'] ) . ';
            return arp_basic_ribbon_colors;
        }

        function ribbon_gradient_colors() {
            var arp_gradient_ribbon_colors;
            arp_gradient_ribbon_colors = ' . wp_json_encode( $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'] ) . ';
            return arp_gradient_ribbon_colors;
        }

        function ribbon_border_colors() {
            var arp_ribbon_border_color;
            arp_ribbon_border_color = ' . wp_json_encode( $arplite_mainoptionsarr['general_options']['arp_ribbon_border_color'] ) . ';
            return arp_ribbon_border_color;
        }

        function arp_template_css_class_info() {
            var arp_templatecssinfo;
            arp_templatecssinfo = ' . wp_json_encode( $arplite_templatecssinfoarr ) . ';
            return arp_templatecssinfo;
        }

        function arp_template_responsive_array_types() {
            var arp_template_responsive_array;
            arp_template_responsive_array = ' . wp_json_encode( $arplite_templateresponsivearr ) . ';
            return arp_template_responsive_array;
        }

        function arp_template_editor_handler() {
            var arp_template_editro_handler_var;
            arp_template_editro_handler_var = ' . wp_json_encode( $arplite_template_editor_arr ) . ';
            return arp_template_editro_handler_var;
        }

        function global_column_background_colors() {
            var arp_column_background_colors_var;
            arp_column_background_colors_var = ' . wp_json_encode( $arpricelite_default_settings->arp_column_section_background_color() ) . ';
            return arp_column_background_colors_var;
        }

        function global_column_footer_type_templates() {
            var arp_column_footer_templates;
            arp_column_footer_templates = ' . wp_json_encode( $arpricelite_default_settings->arp_footer_section_template_types() ) . ';
            return arp_column_footer_templates;
        }

        function global_arp_color_skin_templats() {
            var arp_column_color_skin_templates;
            arp_column_color_skin_templates = ' . wp_json_encode( $arpricelite_default_settings->arp_color_skin_template_types() ) . ';
            return arp_column_color_skin_templates;
        }

        function global_column_sections_array() {
            var arp_column_sections_colors_array;
            arp_column_sections_colors_array = ' . wp_json_encode( $arplite_templatesectionsarr ) . ';
            return arp_column_sections_colors_array;
        }

        function arp_global_skin_array() {
            var arp_template_custom_skin;
            arp_template_custom_skin = ' . wp_json_encode( $arplite_templatecustomskinarr ) . ';
            return arp_template_custom_skin;
        }

        function arp_global_default_gradient_templates() {
            var arp_template_gradient_templates;
            arp_template_gradient_templates = ' . wp_json_encode( $arpricelite_default_settings->arplite_default_gradient_templates() ) . ';
            return arp_template_gradient_templates;
        }

        function arp_global_default_gradient_colors() {
            var arp_global_default_gradient_color;
            arp_global_default_gradient_color = ' . wp_json_encode( $arpricelite_default_settings->arplite_default_gradient_templates_colors() ) . ';
            return arp_global_default_gradient_color;
        }

        function arp_global_default_rgba_colors() {
            var arp_global_rgba_colors;
            arp_global_rgba_colors = ' . wp_json_encode( $arpricelite_default_settings->arp_default_rgba_color_array() ) . ';
            return arp_global_rgba_colors;
        }

        function arplite_depended_section_color_codes() {
            var arp_global_depended_section_colors;
            arp_global_depended_section_colors = ' . wp_json_encode( $arpricelite_default_settings->arplite_depended_section_color_codes() ) . ';
            return arp_global_depended_section_colors;
        }

        function arp_custom_skin_selection_section_color() {
            var arplite_custom_skin_selection_colors;
            arplite_custom_skin_selection_colors = ' . wp_json_encode( $arpricelite_default_settings->arp_custom_skin_selection_section_color() ) . ';
            return arplite_custom_skin_selection_colors
        }

        function arp_background_image_section_array() {
            var arp_background_image_section_array;
            arp_background_image_section_array = ' . wp_json_encode( $arpricelite_default_settings->arp_background_image_section_array() ) . ';
            return arp_background_image_section_array;
        }

        function arprice_default_template_skins() {
            var arp_background_image_section_array;
            arp_background_image_section_array = ' . wp_json_encode( $arpricelite_default_settings->arprice_default_template_skins() ) . ';
            return arp_background_image_section_array;
        }

        function arp_column_border_array_global() {
            var arp_column_border_array;
            arp_column_border_array = ' . wp_json_encode( $arpricelite_default_settings->arp_column_border_array() ) . ';
            return arp_column_border_array;
        }
        function arprice_css_pseudo_elements() {
            var arprice_css_pseudo_elements;
            arprice_css_pseudo_elements = ' . wp_json_encode( $arpricelite_default_settings->arplite_css_pseudo_elements_array() ) . ';
            var string = "";
            jQuery(arprice_css_pseudo_elements).each(function (i) {
                string += arprice_css_pseudo_elements[i] + "|";
            });
            var strlen = string.length;
            var str = "";
            for (var n = 0; n < strlen - 1; n++) {
                str += string[n];
            }
            var regex = new RegExp("(" + str + ")", "ig");
            return regex;
        }

        function arprice_border_color() {
            var arprice_border_colors;
            arprice_border_colors = ' . wp_json_encode( $arpricelite_default_settings->arp_border_color() ) . ';
            return arprice_border_colors;
        }

        function arplite_exclude_caption_column_for_color_skin() {
            var arprice_exclude_caption;
            arprice_exclude_caption = ' . wp_json_encode( $arpricelite_default_settings->arplite_exclude_caption_column_for_color_skin() ) . ';
            return arprice_exclude_caption;
        }

        function arp_editor_width() {
            var arp_editor_width;
            arp_editor_width = ' . wp_json_encode( $arpricelite_default_settings->arprice_responsive_width_array() ) . ';
            return arp_editor_width;
        }

        function arp_section_text_alignment() {
            var arp_section_text_alignment_array;
            arp_section_text_alignment_array = ' . wp_json_encode( $arpricelite_default_settings->arp_section_text_alignment() ) . ';
            return arp_section_text_alignment_array;
        }

        function arp_hide_section_class_global() {
            var arp_hide_section_class;
            arp_hide_section_class = ' . wp_json_encode( $arpricelite_default_settings->arprice_hide_section_array() ) . ';
            return arp_hide_section_class;
        }

		function arp_row_level_border_global(){
			var arp_row_level_border_array;
			arp_row_level_border_array = ' . wp_json_encode($arpricelite_default_settings->arp_row_level_border()) .';
			return arp_row_level_border_array;
		}

        function arp_row_level_border_remove_from_last_child_global() {
            var arp_row_level_border_remove_from_last_child_array;
            arp_row_level_border_remove_from_last_child_array = ' . wp_json_encode( $arpricelite_default_settings->arp_row_level_border_remove_from_last_child() ) . ';
            return arp_row_level_border_remove_from_last_child_array;
        }

        function arp_exclude_caption_column_for_color_skin() {
            var arprice_exclude_caption;
            arprice_exclude_caption = ' . wp_json_encode( $arpricelite_default_settings->arp_exclude_caption_column_for_color_skin() ) . ';
            return arprice_exclude_caption;
        }

        function arp_select_previous_skin_for_multicolor_array() {
            var arp_select_previous_skin_for_multicolor;
            arp_select_previous_skin_for_multicolor = ' . wp_json_encode( $arpricelite_default_settings->arp_select_previous_skin_for_multicolor() ) . ';
            return arp_select_previous_skin_for_multicolor;
        }

        function arp_navigation_section_class_array() {
            var arp_navigation_section_class_array;
            arp_navigation_section_class_array = ' . wp_json_encode( $arpricelite_default_settings->arp_navigation_section_array() ) . ';
            return arp_navigation_section_class_array;
        }

        function arp_shortcode_custom_type_array() {
            var arp_shortcode_custom_type_sections;
            arp_shortcode_custom_type_sections = ' . wp_json_encode( $arpricelite_default_settings->arp_shortcode_custom_type() ) . ';
            return arp_shortcode_custom_type_sections;
        }
        
        function arp_custom_css_inner_sections() {
            var arp_custom_css_inner_sections;
            arp_custom_css_inner_sections = ' . wp_json_encode( $arpricelite_default_settings->arp_custom_css_inner_sections() ) . ';
            return arp_custom_css_inner_sections;
        }

        function arp_custom_button_type() {
	        var arp_custom_button_type_sections;
	        arp_custom_button_type_sections = ' . wp_json_encode( $arpricelite_default_settings->arp_button_type() ) .';
	        return arp_custom_button_type_sections;
	    }

	    function arp_shortcode_custom_type_array() {
	        var arp_shortcode_custom_type_sections;
	        arp_shortcode_custom_type_sections = '. wp_json_encode($arpricelite_default_settings->arp_shortcode_custom_type()) . ';
	        return arp_shortcode_custom_type_sections;
	    }

	    function arp_button_size_new_array() {
	        var arp_button_size_new_class_array;
	        arp_button_size_new_class_array = ' . wp_json_encode($arpricelite_default_settings->arp_button_size_new()) .';
	        return arp_button_size_new_class_array;
	    }
	    
	    function arp_column_image_bg_color(){
	        var arp_column_image_bg_color;
	        arp_column_image_bg_color = '. wp_json_encode($arpricelite_default_settings->arp_column_bg_image_colors()) . ';
	        return arp_column_image_bg_color;
	    }

        __DISABLED_RIBBON = "' . esc_html__( 'This ribbon is not supported in this template.', 'arprice-responsive-pricing-table' ) . '";
        __OK_BUTTON_TEXT = "' . esc_html__( 'Ok', 'arprice-responsive-pricing-table' ) . '";
        __CANCEL_BUTTON_TXT = "' . esc_html__( 'Cancel', 'arprice-responsive-pricing-table' ) . '";
        __DELETE_COLUMN_TXT = "' . esc_html__( 'Are you sure want to delete this column?', 'arprice-responsive-pricing-table' ) . '";
        __HIDE_FOOTER_TXT = "' . esc_html__( 'Footer section is hidden.', 'arprice-responsive-pricing-table' ) . '";

        __HIDE_HEADER_TXT = "' . esc_html__( 'Header section is hidden.', 'arprice-responsive-pricing-table' ) . '";
        __HIDE_PRICE_TXT = "' . esc_html__( 'Price section is hidden.', 'arprice-responsive-pricing-table' ) . '";
        __HIDE_FEATURE_TXT = "' . esc_html__( 'Feature section is hidden.', 'arprice-responsive-pricing-table' ) . '";
        __HIDE_DISCRIPTION_TXT = "' . esc_html__( 'Description section is hidden.', 'arprice-responsive-pricing-table' ) . '";
        __HIDE_HEADER_SHORTCODE_TXT = "' . esc_html__( 'Header shortcode section is hidden.', 'arprice-responsive-pricing-table' ) . '";

        __HIDE_COLUMN_TXT = "' . esc_html__( 'Column is hidden.', 'arprice-responsive-pricing-table' ) . '";
        __DESCRIPTION_TEXT = "' . esc_html__( 'Description', 'arprice-responsive-pricing-table' ) . '";
        __ADD_MEDIA_TEXT = "' . esc_html__( 'Add Media', 'arprice-responsive-pricing-table' ) . '";
        __ADD_FONT_ICON = "' . esc_html__( 'Add Font Icon', 'arprice-responsive-pricing-table' ) . '";
        __IMAGE_URL_TEXT = "' . esc_html__( 'Image URL', 'arprice-responsive-pricing-table' ) . '";
        __IMAGE_DIMENSION = "' . esc_html__( 'Dimension ( height X width )', 'arprice-responsive-pricing-table' ) . '";
        __ADD_FILE = "' . esc_html__( 'Add File', 'arprice-responsive-pricing-table' ) . '";
        __ADD_TEXT = "' . esc_html__( 'Add', 'arprice-responsive-pricing-table' ) . '";
        __REMOVE_TEXT = "' . esc_html__( 'Remove', 'arprice-responsive-pricing-table' ) . '";
        __CSS_PROPERTY_TEXT = "' . esc_html__( 'CSS Property', 'arprice-responsive-pricing-table' ) . '";
        __NORMAL_STATE_TEXT = "' . esc_html__( 'Normat State', 'arprice-responsive-pricing-table' ) . '";
        __HOVER_STATE_TEXT = "' . esc_html__( 'Hover State', 'arprice-responsive-pricing-table' ) . '";
        __FOR_EXAMPLE_TEXT = "' . esc_html__( 'For Example', 'arprice-responsive-pricing-table' ) . '";
        __TOOLTIP_TEXT = "' . esc_html__( 'Tooltip', 'arprice-responsive-pricing-table' ) . '";
        __LABEL_TEXT = "' . esc_html__( 'Label', 'arprice-responsive-pricing-table' ) . '";
        __OK_TEXT = "' . esc_html__( 'Ok', 'arprice-responsive-pricing-table' ) . '";
        __ARP_HTML_TEXT = "' . esc_html__( 'HTML/Text', 'arprice-responsive-pricing-table' ) . '";
        __ARP_BUTTON_TEXT = "' . esc_html__( 'Button', 'arprice-responsive-pricing-table' ) . '";
        __ARP_DEL_COL = "' . esc_html__( 'Are you sure want to delete this column ?', 'arprice-responsive-pricing-table' ) . '";
        __ARP_DEL_ROW = "' . esc_html__( 'Are you sure want to delete this row ?', 'arprice-responsive-pricing-table' ) . '";
        __ARP_DEL_TMP = "' . esc_html__( 'Are you sure you want to delete this table ?', 'arprice-responsive-pricing-table' ) . '";
        __ARP_GROUP_IMG = "' . esc_html__( 'Image', 'arprice-responsive-pricing-table' ) . '";
        __ARP_GROUP_VIDEO = "' . esc_html__( 'Video', 'arprice-responsive-pricing-table' ) . '";
        __ARP_GROUP_AUDIO = "' . esc_html__( 'Audio', 'arprice-responsive-pricing-table' ) . '";
        __ARP_GROUP_OTHER = "' . esc_html__( 'Other', 'arprice-responsive-pricing-table' ) . '";
        __ARP_EDIT_TEXT = "' . esc_html__( 'Edit', 'arprice-responsive-pricing-table' ) . '";
        ';

		wp_add_inline_script( $handler, $data, 'after' );

	}

	function arplite_add_tour_guide_js() {
		$data2 = '__ARP_NEXT_TEXT = "' . esc_html__( 'Next', 'arprice-responsive-pricing-table' ) . '";
        __ARP_PREV_TEXT = "' . esc_html__( 'Prev', 'arprice-responsive-pricing-table' ) . '";
        __END_TOUR = "' . esc_html__( 'End tour', 'arprice-responsive-pricing-table' ) . '";
        __CHOOSE_TEMPLATE = "' . esc_html__( 'Choose your template', 'arprice-responsive-pricing-table' ) . '";
        __TOUR_NEXT_LABEL = "' . esc_html__( "Click 'Next' button to clone selected template.", 'arprice-responsive-pricing-table' ) . '";
        __REAL_TIME_EDITOR_TEXT = "' . esc_html__( 'Real Time Editor', 'arprice-responsive-pricing-table' ) . '";
        __REAL_TIME_EDITOR_CONT = "' . esc_html__( 'Below you can see that your selected template is loaded in editor. Here you can modify it as per your need.', 'arprice-responsive-pricing-table' ) . '";
        __CHOOSE_COLOR = "' . esc_html__( 'Choose color', 'arprice-responsive-pricing-table' ) . '";
        __CHOOSE_COLOR_TEXT = "' . esc_html__( 'Select color of your template by clicking the button. you will see color change is applied right away', 'arprice-responsive-pricing-table' ) . '";
        __COLUMN_LEVEL_CHANGES = "' . esc_html__( 'Column level changes', 'arprice-responsive-pricing-table' ) . '";
        __COLUMN_LEVEL_CHANGES_TEXT = "' . esc_html__( 'Hover on the column and you will see option bar on the top of the column. hover on the header area to see options for header part.', 'arprice-responsive-pricing-table' ) . '";
        __PRICING_AREA_TEXT = "' . esc_html__( 'Pricing area change', 'arprice-responsive-pricing-table' ) . '";
        __PRICING_AREA_CONT = "' . esc_html__( 'Set pricing and its interval from this area.', 'arprice-responsive-pricing-table' ) . '";
        __PREVIEW_BTN = "' . esc_html__( 'Preview Button', 'arprice-responsive-pricing-table' ) . '";
        __PREVIEW_BTN_TEXT = "' . esc_html__( "click 'Next' or 'Preview' button to view your applied changes in separate responsive tab.", 'arprice-responsive-pricing-table' ) . '";
        __CHANGE_VIEW = "' . esc_html__( 'Change View', 'arprice-responsive-pricing-table' ) . '";
        __CHANGE_VIEW_TEXT = "' . esc_html__( 'Hit Next or Mobile button to view pricing table preview in mobile view.', 'arprice-responsive-pricing-table' ) . '";
        __MOBILE_VIEW = "' . esc_html__( 'Mobile View', 'arprice-responsive-pricing-table' ) . '";
        __MOBILE_VIEW_TEXT = "' . esc_html__( "Click 'Next' or close button to get back to editor area.", 'arprice-responsive-pricing-table' ) . '";
        __GENERAL_SETTINGS = "' . esc_html__( 'General settings', 'arprice-responsive-pricing-table' ) . '";
        __GENERAL_SETTINGS_CONT = "' . esc_html__( 'All the template level options like column options, animation effects, tooltip settings etc can be changed in general setting area.', 'arprice-responsive-pricing-table' ) . '";
        __FINISH = "' . esc_html__( 'Finish', 'arprice-responsive-pricing-table' ) . '";
        __FINISH_TEXT = "' . sprintf( esc_html__( 'Once you click save button all your changes will be saved as clone of existing template. you can right away copy short code and put it on page. %s Thank you', 'arprice-responsive-pricing-table' ), '</br>' ) . '";
        ';

		wp_add_inline_script( 'arplite_tour_guide', $data2, 'before' );
	}

	/* Setting Menu Position */

	function get_free_menu_position( $start, $increment = 0.1 ) {
		foreach ( $GLOBALS['menu'] as $key => $menu ) {
			$menus_positions[] = floatval( $key );
		}
		if ( ! in_array( $start, $menus_positions ) ) {
			$start = strval( $start );
			return $start;
		} else {
			$start += $increment;
		}
		/* the position is already reserved find the closet one */
		while ( in_array( $start, $menus_positions ) ) {
			$start += $increment;
		}
		$start = strval( $start );
		return $start;
	}

	/* Setting Capabilities for user */

	function arp_capabilities() {
		$cap = array(
			'arplite_view_pricingtables'            => esc_html__( 'View And Manage Arpricelite Pricing Tables', 'arprice-responsive-pricing-table' ),
			'arplite_add_udpate_pricingtables'      => esc_html__( 'Add/Edit Arpricelite Pricing Tables', 'arprice-responsive-pricing-table' ),
			'arplite_analytics_pricingtables'       => esc_html__( 'View Analytics of Arpricelite Pricing Tables', 'arprice-responsive-pricing-table' ),
			'arplite_import_export_pricingtables'   => esc_html__( 'Import/Export Arpricelite Pricing Tables', 'arprice-responsive-pricing-table' ),
			'arplite_ab_testing_pricingtables'   => esc_html__( 'A/B Testing', 'arprice-responsive-pricing-table' ),
			'arplite_global_settings_pricingtables' => esc_html__( 'Global Settings Arpricelite Pricing Tables', 'arprice-responsive-pricing-table' ),
		);

		return $cap;
	}

	// Adding Pricing Table Menu
	function pricingtablelite_menu() {
		global $arplite_pricingtable;

		$place = $arplite_pricingtable->get_free_menu_position( 26.1, .1 );

		// add custom role to these menu links

		add_menu_page( 'ARPricelite', 'ARPrice Lite', 'arplite_view_pricingtables', 'arpricelite', array( $this, 'route' ), ARPLITE_PRICINGTABLE_IMAGES_URL . '/pricing_table_icon.png', $place );

		add_submenu_page( 'arpricelite', esc_html__( 'Import/Export', 'arprice-responsive-pricing-table' ), esc_html__( 'Import/Export', 'arprice-responsive-pricing-table' ), 'arplite_import_export_pricingtables', 'arplite_import_export', array( $this, 'route' ) );

		add_submenu_page( 'arpricelite', esc_html__( 'A/B Testing', 'arprice-responsive-pricing-table' ), esc_html__( 'A/B Testing', 'arprice-responsive-pricing-table' ), 'arplite_ab_testing_pricingtables', 'arplite_ab_testing', array( $this, 'route' ) );

		add_submenu_page( 'arpricelite', esc_html__( 'Settings', 'arprice-responsive-pricing-table' ), esc_html__( 'Settings', 'arprice-responsive-pricing-table' ), 'arplite_global_settings_pricingtables', 'arplite_global_settings', array( $this, 'route' ) );

		$this->set_premium_link();
	}

	function set_premium_link() {

		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_upgrade_to_premium.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_upgrade_to_premium.php';
		}
	}

	function route() {
		global $arplite_pricingtable, $arpricelite_form;
		if ( isset( $_GET['page'] ) and $_GET['page'] == 'arpricelite' && isset( $_GET['arp_action'] ) && $_GET['arp_action'] == '' ) {
			$arplite_pricingtable->addnew();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_add_pricing_table' ) {
			if ( isset( $_GET['arpaction'] ) and $_GET['arpaction'] == 'create_new' ) {
				$arpricelite_form->edit_template();
			} else {
				$arplite_pricingtable->addnew();
			}
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_analytics' ) {
			$arplite_pricingtable->analytics();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_import_export' ) {
			$arplite_pricingtable->import_export();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_global_settings' ) {
			$arplite_pricingtable->load_global_settings();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_ab_testing' ) {
			$arplite_pricingtable->load_abtesting_settings();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arplite_upgrade_to_premium' ) {
			$arplite_pricingtable->arplite_upgrade_to_premium();
		} elseif ( isset( $_GET['page'] ) and $_GET['page'] == 'arpricelite' and isset( $_GET['arp_action'] ) and $_GET['arp_action'] != '' ) {
			$arplite_pricingtable->pricing_table_content();
		} else {
			$arplite_pricingtable->addnew();
		}
	}



	function arplite_upgrade_to_premium() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_upgrade_to_premium.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_upgrade_to_premium.php';
		}
	}

	function addnew() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_template_listing_2.0.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_template_listing_2.0.php';
		}
	}

	function pricing_table_content() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_listing_editor.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_listing_editor.php';
		}
	}

	function import_export() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_import_export.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_import_export.php';
		}
	}

	function load_global_settings() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_global_settings.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_global_settings.php';
		}
	}

	function load_abtesting_settings() {
		if ( file_exists( ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_ab_testing.php' ) ) {
			include ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_ab_testing.php';
		}
	}

	public static function arplite_db_check() {
		global $arplite_pricingtable;
		$arpricelite_version = get_option( 'arpricelite_version' );

		if ( ! isset( $arpricelite_version ) || $arpricelite_version == '' && is_multisite() ) {
			$arplite_pricingtable->arpricelite_install();
		}
	}

	public static function arpricelite_install() {

		global $arplite_pricingtable;

		$arpricelite_version = get_option( 'arpricelite_version' );

		if ( ! isset( $arpricelite_version ) || $arpricelite_version == '' ) {
			$arplite_pricingtable->arplite_pricing_table_main_settings();

			include_once ABSPATH . 'wp-admin/includes/upgrade.php';

			global $wpdb, $arpricelite_version;

			$charset_collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {

				if ( ! empty( $wpdb->charset ) ) {
					$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
				}

				if ( ! empty( $wpdb->collate ) ) {
					$charset_collate .= " COLLATE $wpdb->collate";
				}
			}

			update_option( 'arpricelite_version', sanitize_text_field( $arpricelite_version ) );
			update_option( 'arplite_is_new_installation', 1 );

			update_option( 'arplite_already_subscribe', sanitize_text_field( 'no' ) );
			update_option( 'arplite_popup_display', sanitize_text_field( 'no' ) );

			update_option( 'arpricelite_tour_guide_value', sanitize_text_field( 'yes' ) );

			$enable_fonts = array( 'enable_fontawesome_icon' );
			update_option( 'enable_font_loading_icon', $enable_fonts );

			$table = $wpdb->prefix . 'arplite_arprice';

			$sql_table = "CREATE TABLE IF NOT EXISTS `{$table}`(			
                 ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                 table_name VARCHAR(255) NOT NULL, 
                 template_name int(11) NOT NULL,
                 general_options LONGTEXT NOT NULL, 
                 is_template int(1) NOT NULL,
                 is_animated int(1) NOT NULL,
                 status VARCHAR(255) NOT NULL, 
                 create_date DATETIME NOT NULL, 
                 arp_last_updated_date DATETIME NOT NULL 
             ){$charset_collate}";

			dbDelta( $sql_table );

			$table_opt = $wpdb->prefix . 'arplite_arprice_options';

			$sql_table_opt = "CREATE TABLE IF NOT EXISTS `{$table_opt}`( 
                ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                table_id INT(11) NOT NULL,
                table_options LONGTEXT NOT NULL
            ){$charset_collate}";

			dbDelta( $sql_table_opt );

			$tablecreate = $wpdb->prefix . 'arplite_arprice_analytics';

			$sqltable = "CREATE TABLE IF NOT EXISTS `{$tablecreate}`(
                tracking_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                pricing_table_id int NOT NULL,
                browser_name VARCHAR(255) NOT NULL,
                browser_version VARCHAR(255) NOT NULL,
                page_url varchar(255) NOT NULL,
                ip_address varchar(255) NOT NULL,
                country_name varchar(255) NOT NULL,
                session_id varchar(255) NOT NULL,
                added_date DATETIME NOT NULL,
                is_click int(1) NOT NULL DEFAULT '0',
                plan_id varchar(25) NOT NULL 
            ){$charset_collate}";
			dbDelta( $sqltable );

			$arplite_pricingtable->arp_pricing_table_templates();

			$wpdb->query( "ALTER TABLE `{$table}` AUTO_INCREMENT = 100" );

			$wpdb->query( "ALTER TABLE `{$table_opt}` AUTO_INCREMENT = 100" );

			$arplite_pricingtable->arp_set_global_settings();

			$nextEvent = strtotime('+60 days');

			wp_schedule_single_event( $nextEvent, 'arplite_display_ratenow_popup' );
		}

		$args  = array(
			'role'   => 'administrator',
			'fields' => 'id',
		);
		$users = get_users( $args );
		if ( count( $users ) > 0 ) {
			foreach ( $users as $key => $user_id ) {
				$arproles = $arplite_pricingtable->arp_capabilities();
				$userObj  = new WP_User( $user_id );

				foreach ( $arproles as $arprole => $arproledescription ) {
					$userObj->add_cap( $arprole );
				}

				unset( $arproles );
				unset( $arprole );
				unset( $arproledescription );
			}
		}
	}

	public static function uninstall() {

		global $wpdb;
		if ( is_multisite() ) {
			$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
			if ( $blogs ) {
				foreach ( $blogs as $blog ) {
					switch_to_blog( $blog['blog_id'] );

					delete_option( 'arpricelite_version' );
					delete_option( 'arpricelite_tour_guide_value' );
					delete_option( 'arplite_mobile_responsive_size' );
					delete_option( 'arplite_tablet_responsive_size' );
					delete_option( 'arplite_desktop_responsive_size' );
					delete_option( 'arplite_global_custom_css' );
					delete_option( 'arplite_css_character_set' );
					delete_option( 'arplite_wp_get_version' );
					delete_option( 'arplite_previewoptions' );
					delete_option( 'arplite_tablegeneraloption' );
					delete_option( 'arplite_tablecolumnoption' );
					delete_option( 'arplite_is_new_installation' );
					delete_option( 'arplite_is_dashboard_visited' );
					delete_option( 'arplite_load_js_css' );
					delete_option( 'arplite_already_subscribe' );
					delete_option( 'arplite_popup_display' );
					delete_option( 'arplite_display_popup_date' );

					$wpdb->query( 'DELETE FROM ' . $wpdb->options . " WHERE option_name LIKE '%arplite_previewtabledata_%'" );
					$table           = $wpdb->prefix . 'arplite_arprice';
					$table_opt       = $wpdb->prefix . 'arplite_arprice_options';
					$table_analytics = $wpdb->prefix . 'arplite_arprice_analytics';
					$wpdb->query( "DROP TABLE IF EXISTS $table" );
					$wpdb->query( "DROP TABLE IF EXISTS $table_opt" );
					$wpdb->query( "DROP TABLE IF EXISTS $table_analytics" );
				}
				restore_current_blog();
			}
		} else {
			delete_option( 'arpricelite_version' );
			delete_option( 'arpricelite_tour_guide_value' );
			delete_option( 'arplite_mobile_responsive_size' );
			delete_option( 'arplite_tablet_responsive_size' );
			delete_option( 'arplite_desktop_responsive_size' );
			delete_option( 'arplite_global_custom_css' );
			delete_option( 'arplite_css_character_set' );
			delete_option( 'arplite_wp_get_version' );
			delete_option( 'arplite_previewoptions' );
			delete_option( 'arplite_tablegeneraloption' );
			delete_option( 'arplite_tablecolumnoption' );
			delete_option( 'arplite_load_js_css' );
			delete_option( 'arplite_already_subscribe' );
			delete_option( 'arplite_popup_display' );
			delete_option( 'arplite_display_popup_date' );
			delete_option( 'arplite_is_new_installation' );

			$wpdb->query( 'DELETE FROM ' . $wpdb->options . " WHERE option_name LIKE '%arplite_previewtabledata_%'" );
			$table           = $wpdb->prefix . 'arplite_arprice';
			$table_opt       = $wpdb->prefix . 'arplite_arprice_options';
			$table_analytics = $wpdb->prefix . 'arplite_arprice_analytics';
			$wpdb->query( "DROP TABLE IF EXISTS $table" );
			$wpdb->query( "DROP TABLE IF EXISTS $table_opt" );
			$wpdb->query( "DROP TABLE IF EXISTS $table_analytics" );
		}
	}

	public static function arp_pricing_table_templates() {
		include ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_default_templates.php';
	}

	function arplite_enqueue_preview_css( $id, $template_id, $is_admin_preview, $is_template ) {
		global $arpricelite_version, $arpricelite_img_css_version;

		if ( $is_template == 1 ) {
			wp_register_style( 'arplite_preview_css_' . $id . '_v' . $arpricelite_img_css_version, ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $template_id . '_v' . $arpricelite_img_css_version . '.css', array(), $arpricelite_version );
			wp_print_styles( 'arplite_preview_css_' . $id . '_v' . $arpricelite_img_css_version );
		} else {
			wp_register_style( 'arplite_preview_css_' . $id, ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $template_id . '.css', array(), $arpricelite_version );

			wp_print_styles( 'arplite_preview_css_' . $id );
		}

		if ( $is_admin_preview == 1 ) {
			wp_register_style( 'arplite_front_css', ARPLITE_PRICINGTABLE_URL . '/css/arprice_front.css', array(), $arpricelite_version );

			wp_register_script( 'arplite_front_js', ARPLITE_PRICINGTABLE_URL . '/js/arprice_front.js', array(), $arpricelite_version );
		}

		wp_print_scripts( 'arplite_front_js' );
	}

	function arplite_hide_update_notice_to_all_admin_users() {
		if ( isset( $_GET ) and ( isset( $_GET['page'] ) and preg_match( '/arp*/', sanitize_text_field( $_GET['page'] ) ) ) ) {

			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	function footer_js( $location = 'footer' ) {
		global $arplite_is_animation, $arplite_has_tooltip, $arplite_has_fontawesome, $arplite_effect_css, $arplite_switch_css,$arpricelite_version;
		if ( $arplite_has_fontawesome == 1 ) {
			$is_enable_font_awesome = get_option( 'enable_font_loading_icon' );

			if ( in_array( 'enable_fontawesome_icon', $is_enable_font_awesome ) ) {
				wp_enqueue_style( 'fontawesome' );
			}
		}

		if( isset( $_REQUEST['arpaction'] ) && 'preview' == $_REQUEST['arpaction'] ){
			$arp_preview_inline_style = 'input, select, textarea { outline:none; } body{ padding:20px; } .bestPlanButton{ cursor:pointer; } html{ overflow-y:auto; float:left; width:100%; height:auto; padding-top:0px; } .arp_body_content { background:none;  background-color:#FFFFFF;  padding:20px 30px 20px 30px;  margin:20px 0 0;  overflow:hidden;  width:100%; -webkit-box-sizing: border-box;     -moz-box-sizing: border-box; -o-box-sizing:border-box; box-sizing: border-box; float:left; height:auto; }';

			$mobile_size = get_option('arplite_mobile_responsive_size');
			$tablet_size = get_option('arplite_tablet_responsive_size');

			$arp_preview_inline_style .= "@media screen and (min-width:".($mobile_size + 1)."px) and (max-width:".$tablet_size."px){ .arp_body_content { padding: 20px 15px 20px 15px; }}";

			wp_add_inline_style( 'arplite_front_css', $arp_preview_inline_style );
		}
	}

	function arplite_enqueue_elementor_css(){
		global $arpricelite_version;
		wp_enqueue_style( 'arplite_elementor_css', ARPLITE_PRICINGTABLE_URL.'/css/arplite_elementor.css', array(), $arpricelite_version );		
	}

	function arp_template_order() {

		$arptmparr = apply_filters(
			'arplite_pricing_template_order_managed',
			array(
				'arplitetemplate_7'  => 1,
				'arplitetemplate_2'  => 2,
				'arplitetemplate_26' => 3,
				'arplitetemplate_1'  => 4,
				'arplitetemplate_8'  => 5,
				'arplitetemplate_11' => 6,
			)
		);

		return $arptmparr;
	}

	function arp_set_global_settings() {
		add_option( 'arplite_mobile_responsive_size', 480 );
		add_option( 'arplite_tablet_responsive_size', 768 );
		add_option( 'arplite_desktop_responsive_size', 0 );
	}

	function arp_template_responsive_type_array() {

		$array = apply_filters(
			'arpricelite_responsive_type_array_filter',
			array(
				'header_level_types'               => array(
					'type_1' => array(),
					'type_2' => array(),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_5' ),
					'type_6' => array( 'arplitetemplate_7' ),
					'type_7' => array(),
					'type_8' => array(),
				),
				'header_title_types'               => array(
					'type_1' => array( 'arplitetemplate_1' ),
					'type_2' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_7', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'header_level_types_front_array_1' => array(
					'type_1' => array( 'arplitetemplate_1' ),
					'type_2' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array( 'arplitetemplate_7' ),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'header_level_types_front_array_2' => array(
					'type_1' => array(),
					'type_2' => array(),
					'type_3' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2' ),
					'type_4' => array( 'arplitetemplate_1' ),
					'type_5' => array(),
					'type_6' => array( 'arplitetemplate_7' ),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_wrapper_height'            => array(
					'type_1' => array(),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_7', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'price_wrapper_types'              => array(
					'type_1' => array( 'arplitetemplate_11', 'arplitetemplate_8', 'arplitetemplate_2' ),
					'type_2' => array( 'arplitetemplate_7' ),
					'type_3' => array( 'arplitetemplate_8' ),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'price_level_types'                => array(
					'type_1' => array( 'arplitetemplate_1', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_7' ),
					'type_2' => array( 'arplitetemplate_8', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'price_label_level_types'          => array(
					'type_1' => array( 'arplitetemplate_11', 'arplitetemplate_7' ),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_26', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'body_li_level_types'              => array(
					'type_1' => array( 'arplitetemplate_8' ),
					'type_2' => array(),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_description_types'         => array(
					'type_1' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_2' ),
					'type_2' => array( 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'button_level_types'               => array(
					'type_1' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_7', 'arplitetemplate_2' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'slider_types'                     => array(
					'type_1' => array( 'arplitetemplate_8' ),
					'type_2' => array(),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array( 'arplitetemplate_1', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
			)
		);

		return $array;
	}

	function arp_template_editor_array() {

		$arptemplate_editor_array = apply_filters(
			'arplitetemplate_editor_handler',
			array(
				'column_header_click_handler'        => array(
					'type_1' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
					'type_2' => array( 'arplitetemplate_7' ),
					'type_3' => array( 'arplitetemplate_1', 'arplitetemplate_2' ),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_header_click_handler_type_1' => array(
					'type_1' => array(),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_button_click_handler'        => array(
					'type_1' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
					'type_2' => array( 'arplitetemplate_7' ),
					'type_3' => array( 'arplitetemplate_1', 'arplitetemplate_2' ),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'body_li_click_handler'              => array(
					'type_1' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
					'type_2' => array( 'arplitetemplate_7' ),
					'type_3' => array( 'arplitetemplate_1', 'arplitetemplate_2' ),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_price_click_handler'         => array(
					'type_1' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26' ),
					'type_2' => array( 'arplitetemplate_7' ),
					'type_3' => array( 'arplitetemplate_1', 'arplitetemplate_2' ),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'price_text_keyup_handler'           => array(
					'type_1' => array( 'arplitetemplate_1' ),
					'type_2' => array( 'arplitetemplate_11' ),
					'type_3' => array( 'arplitetemplate_8', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_4' => array( '' ),
					'type_5' => array( '' ),
					'type_6' => array( '' ),
					'type_7' => array( '' ),
					'type_8' => array( '' ),
				),
				'price_label_keyup_handler'          => array(
					'type_1' => array( 'arplitetemplate_1' ),
					'type_2' => array( 'arplitetemplate_11' ),
					'type_3' => array( 'arplitetemplate_8', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_4' => array( '' ),
					'type_5' => array( '' ),
					'type_6' => array( '' ),
					'type_7' => array( '' ),
					'type_8' => array( '' ),
				),
				'price_font_size_handler'            => array(
					'type_1' => array(),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'price_text_font_size_handler'       => array(
					'type_1' => array(),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_title_handler'               => array(
					'type_1' => array(),
					'type_2' => array( 'arplitetemplate_1', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
				'column_style_btn_handler'           => array(
					'type_1' => array( 'arplitetemplate_1' ),
					'type_2' => array( 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26', 'arplitetemplate_2', 'arplitetemplate_7' ),
					'type_3' => array(),
					'type_4' => array(),
					'type_5' => array(),
					'type_6' => array(),
					'type_7' => array(),
					'type_8' => array(),
				),
			)
		);

		return $arptemplate_editor_array;
	}

	function arprice_font_icon_size_parser( $string = '' ) {

		$pattern = '/<i (.*?)>(.*?)<\/i>/i';

		$size_pattern = '/arpsize-ico-[0-9]*/';
		preg_match_all( $pattern, $string, $matches, PREG_SET_ORDER );

		if ( is_array( $matches ) and ! empty( $matches ) ) {
			foreach ( $matches as $key => $value ) {

				preg_match( $size_pattern, $value[0], $matches_n );

				if ( ! empty( $matches_n[0] ) ) {
					$font_size = str_replace( 'arpsize-ico-', '', $matches_n[0] );
					$style     = 'font-size:' . $font_size . 'px;';
					$dom       = new DOMDocument();
					$dom->loadHTML( $value[0] );
					$n = new DOMXPath( $dom );
					foreach ( $n->query( '//i' ) as $node ) {
						$node->setAttribute( 'style', $style );
					}
					$newHTML = $dom->saveHTML();

					preg_match_all( $pattern, $newHTML, $matches_ );

					if ( is_array( $matches_[0] ) && ! empty( $matches_[0] ) ) {
						foreach ( $matches_[0] as $key => $mat ) {
							$string = str_replace( $value[0], $mat, $string );
						}
					}
				}
			}
		}

		return $string;
	}

	function arp_remove_style_tag( $tablestring = '' ) {

		$pattern_ = '/\<style(.*?)\>(.*?)\<\/style\>/';

		preg_match_all( $pattern_, $tablestring, $matches );

		if ( ! empty( $matches[1] ) && is_array( $matches[1] ) ) {
			foreach ( $matches[1] as $key => $match ) {
				if ( $match == '' || empty( $match ) ) {
					$tablestring = str_replace( $matches[2][ $key ], '', $tablestring );
				} else {
					$id_pattern = '/id=(.*)/';
					preg_match_all( $id_pattern, $match, $matches_ );
					if ( ! empty( $matches_[1] ) && is_array( $matches_[1] ) ) {
						foreach ( $matches_[1] as $k => $mat ) {
							if ( ! preg_match_all( '/arplite_render_css|border_radius_style|arplite_ribbon_style/', $mat, $matche_ ) ) {
								$tablestring = str_replace( $matches[2][ $key ], '', $tablestring );
							}
						}
					}
				}
			}
		}

		return $tablestring;
	}

	function arp_template_pro_images() {

		$arp_template_pro_images = apply_filters(
			'arp_template_pro_images',
			array( 'arptemplate_25', 'arptemplate_20', 'arptemplate_21', 'arptemplate_23', 'arptemplate_22', 'arptemplate_24', 'arptemplate_3', 'arptemplate_4', 'arptemplate_5', 'arptemplate_6', 'arptemplate_9', 'arptemplate_10', 'arptemplate_13', 'arptemplate_14', 'arptemplate_15', 'arptemplate_16' )
		);

		return $arp_template_pro_images;
	}

	function arplite_copy_folder( $source, $dest, $permissions = 0755 ) {

		if ( is_file( $source ) ) {

			$arpfile_obj = new ARPLiteFilecontroller( $source, true );

			$arpfile_obj->check_cap = true;
			$arpfile_obj->capabilities( 'activate_plugins' );

			$arpfile_obj->check_nonce = false;

			$arpfile_obj->arplite_process_upload( $dest );
		}

		if ( ! is_dir( $dest ) ) {
			wp_mkdir_p( $dest, $permissions );
		}

		$dir = dir( $source );
		while ( false !== $entry = $dir->read() ) {

			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}

			$this->arplite_copy_folder( "$source/$entry", "$dest/$entry", $permissions );
		}

		$dir->close();
		return true;
	}

	function arplite_check_user_cap( $arplite_cap = '', $arplite_is_ajax_call = '' ) {

		$errors = array();
		if ( $arplite_is_ajax_call == true ) {
			if ( ! current_user_can( $arplite_cap ) ) {
				$msg = esc_html__( 'Sorry, you do not have permission to perform this action', 'arprice-responsive-pricing-table' );
				array_push( $errors, $msg );
				array_push( $errors, 'capability_error' );
				return wp_json_encode( $errors );
			}
		}

		$wpnonce = isset( $_REQUEST['_wpnonce_arplite'] ) ? $_REQUEST['_wpnonce_arplite'] : '';
		if ( $wpnonce == '' ) {
			$wpnonce = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
		}
		$arplite_verify_nonce_flag = wp_verify_nonce( $wpnonce, 'arplite_wp_nonce' );

		if ( ! $arplite_verify_nonce_flag ) {
			$msg = esc_html__( 'Sorry, your request cannot be processed due to security reason.', 'arprice-responsive-pricing-table' );
			array_push( $errors, $msg );
			array_push( $errors, 'security_error' );
			return wp_json_encode( $errors );
		}

		return 'success';
	}

	function arp_allow_style_attr( $styles ) {
		$styles[] = 'display';
		return $styles;
	}

	function arplite_admin_editor_styles(){

		if( isset( $_REQUEST['page'] ) && 'arpricelite' == $_REQUEST['page'] && isset( $_GET['arp_action'] ) && isset( $_GET['eid'] ) && '' != $_GET['eid'] ){
			global $arpricelite_version, $wpdb, $arpricelite_img_css_version,$arpricelite_form,$arplite_mainoptionsarr;
			$id = intval( $_GET['eid'] );
			$arplite_table = $wpdb->get_row( $wpdb->prepare( "SELECT a.*, ao.table_options FROM `".$wpdb->prefix."arplite_arprice` a LEFT JOIN `".$wpdb->prefix."arplite_arprice_options` ao ON a.ID = ao.table_id WHERE a.ID = %d AND a.status = %s", $id, 'published' ));
			
			$is_template = $arplite_table->is_template;

			if ($is_template == 1) {
	            $template_name = $arplite_table->template_name;
	        } else {
	            $template_name = $id;
	        }
	        $arguments = array(
	            'sslverify' => false
	        );
	        $template_css = '';
	        $css_url = '';
	        if ($is_template == 1) {
	            if (file_exists(ARPLITE_PRICINGTABLE_DIR . '/css/templates/arplitetemplate_' . $arplite_table->template_name . '_v' . $arpricelite_img_css_version . '.css')) {
	                $css_url = ARPLITE_PRICINGTABLE_URL . "/css/templates/arplitetemplate_" . $arplite_table->template_name . '_v' . $arpricelite_img_css_version . ".css";
	            }
	        } else {
	            if (file_exists(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $id . '.css')) {
	                $css_url = ARPLITE_PRICINGTABLE_UPLOAD_URL . "/css/arplitetemplate_" . $id . ".css";
	            }
	        }

	        wp_register_style( 'arplite_template_css-'.$id, $css_url, array(), $arpricelite_img_css_version );

	        wp_enqueue_style( 'arplite_template_css-'.$id);

	        wp_enqueue_style( 'arplite_editor_front_css');

	        $general_option = maybe_unserialize( $arplite_table->general_options );

	        $opts = maybe_unserialize($arplite_table->table_options);

	        $is_animated = $arplite_table->is_animated;

	        $template_css = $arpricelite_form->arp_render_customcss($template_name, $general_option, 0, $opts, $is_animated);

	        $column_settings = $general_option['column_settings'];

	        $general_settings = $general_option['general_settings'];

	        $ref_template = $general_settings['reference_template'];

	        if ($column_settings['column_border_radius_top_left'] != '' || $column_settings['column_border_radius_top_left'] == 0) {
                $column_border_radius_top_left = $column_settings['column_border_radius_top_left'];
            } else {
                $column_border_radius_top_left = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['top_left'];
            }

            if ($column_settings['column_border_radius_top_right'] != '' || $column_settings['column_border_radius_top_right'] == 0) {
                $column_border_radius_top_right = $column_settings['column_border_radius_top_right'];
            } else {
                $column_border_radius_top_right = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['top_right'];
            }

            if ($column_settings['column_border_radius_bottom_right'] != '' || $column_settings['column_border_radius_bottom_right'] == 0) {
                $column_border_radius_bottom_right = $column_settings['column_border_radius_bottom_right'];
            } else {
                $column_border_radius_bottom_right = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['bottom_right'];
            }

            if ($column_settings['column_border_radius_bottom_left'] != '' || $column_settings['column_border_radius_bottom_left'] == 0) {
                $column_border_radius_bottom_left = $column_settings['column_border_radius_bottom_left'];
            } else {
                $column_border_radius_bottom_left = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['bottom_left'];
            }

            $template_feature = $arplite_mainoptionsarr['general_options']['template_options']['features'][$ref_template];

            if ($column_border_radius_top_left == 0 && $column_border_radius_top_right == 0 && $column_border_radius_bottom_right == 0 && $column_border_radius_bottom_left == 0) {
            } else {
            	if ( 0 == $template_feature['is_animated'] ) {
            		$template_css .= ".arplitetemplate_$template_name .ArpPricingTableColumnWrapper .arp_column_content_wrapper{";

	                $template_css .= "border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;";

	                $template_css .= "-moz-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;";

	                $template_css .= "-webkit-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;";

	                $template_css .= "-o-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px  !important;";

	                $template_css .= "overflow:hidden !important;";

	                $template_css .= "}";
            	} else {
            		$template_css .= ".arplitetemplate_$template_name .ArpPricingTableColumnWrapper { ";

	                $template_css .= "border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;overflow:hidden !important;";

	                $template_css .= " -moz-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;overflow:hidden !important;";

	                $template_css .= "-webkit-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;overflow:hidden !important;";

	                $template_css .= "-o-border-radius:{$column_border_radius_top_left}px {$column_border_radius_top_right}px {$column_border_radius_bottom_right}px {$column_border_radius_bottom_left}px !important;overflow:hidden !important;";

	                $template_css .= "}";
            	}
            }

            foreach( $opts['columns'] as $j => $columns ){
            	if( isset( $columns['ribbon_setting'] ) ){
	            	$basic_col = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
	                $ribbon_bg_col = $columns['ribbon_setting']['arp_ribbon_bgcol'];
	                $base_color = $ribbon_bg_col;
	                $base_color_key = array_search($base_color, $basic_col);
	                $gradient_color = $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'][$base_color_key];
	                $ribbon_border_color = $arplite_mainoptionsarr['general_options']['arp_ribbon_border_color'][$base_color_key];
	            	if ($columns['ribbon_setting']['arp_ribbon'] != 'arp_ribbon_6') {
	                    if (in_array($base_color, $basic_col)) {
	                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_1') {
	                            $template_css .= "#main_" . $j . " .arp_ribbon_content:before, #main_" . $j . " .arp_ribbon_content:after{";
	                            $template_css .= "border-top-color:" . $gradient_color . " !important;";
	                            $template_css .= "}";
	                        }
	                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3') {
	                            $template_css .= "#main_" . $j . " .arp_ribbon_content:before, #main_" . $j . " .arp_ribbon_content:after{";
	                            $template_css .= "border-top-color:" . $base_color . " !important;";
	                            $template_css .= "}";
	                            $template_css .= "#main_" . $j . " .arp_ribbon_content{";
	                            $template_css .= "border-top:75px solid " . $base_color . ";";
	                            $template_css .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
	                            $template_css .= "}";
	                        } else {
	                            $template_css .= ".arp_admin_template_editor.arplite_price_table_" . $template_name . " .arp_ribbon_content{";
	                            $template_css .= "background:" . $gradient_color . ";";
	                            $template_css .= "background-color:" . $gradient_color . ";";
	                            $template_css .= "background-image:-moz-linear-gradient(0deg," . $gradient_color . "," . $base_color . "," . $gradient_color . ")";
	                            $template_css .= "background-image:-webkit-gradient(linear, 0 0, 0 0, color-stop(0%," . $gradient_color . "), color-stop(50%," . $base_color . "), color-stop(100%," . $gradient_color . "));";
	                            $template_css .= "background-image:-webkit-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
	                            $template_css .= "background-image:-o-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
	                            $template_css .= "background-image:linear-gradient(90deg," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
	                            $template_css .= "background-image:-ms-linear-gradient(left," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
	                            $template_css .= "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" . $base_color . "', endColorstr='" . $gradient_color . "', GradientType=1);";
	                            $template_css .= '-ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="' . $base_color . '", endColorstr="' . $gradient_color . '", GradientType=1)";';
	                            $template_css .= "background-repeat:repeat-x;";
	                            $template_css .= "border-top:1px solid {$ribbon_border_color};";
	                            $template_css .= "box-shadow:3px 1px 2px rgba(0,0,0,0.6);";
	                            $template_css .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
	                            $template_css .= "}";
	                        }
	                    } else {
	                        if ( $columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_1') {
	                            $template_css .= "#main_" . $j . " .arp_ribbon_content:before,#main_" . $j . " .arp_ribbon_content:after{";
	                            $template_css .= "border-top-color:" . $base_color . "  !important;";
	                            $template_css .= "}";
	                        }

	                        $template_css .= "#main_" . $j . " .arp_ribbon_content{";
	                        $template_css .= "background:" . $base_color . ";";
	                        $template_css .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
	                        $template_css .= "}";
	                    }
	                }
                }
            }

	        wp_add_inline_style( 'arplite_admin_css', $template_css );

		}

	}

	function arpricelite_allowed_html_tags(){

		$arplite_allowed_html = array(
			'a' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'href' => array(),
					'rel' => array(),
					'target' => array(),
				)
			),
			'b' => $this->arpricelite_global_attributes(),
			'br' => $this->arpricelite_global_attributes(),
			'button' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'autofocus' => array(),
					'disabled' => array(),
					'formaction' => array(),
					'name' => array(),
					'type' => array(),
					'value' => array()
				)
			),
			'code' => $this->arpricelite_global_attributes(),
			'del' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'cite' => array(),
					'datetime' => array()
				)
			),
			'div' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'embed' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'height' => array(),
					'onabort' => array(),
					'oncanplay' => array(),
					'onerror' => array(),
					'src' => array(),
					'type' => array(),
					'width' => array(),
				)
			),
			'font' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'color' => array(),
					'face' => array(),
					'size' => array()
				)
			),
			'form' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'accept-charset' => array(),
					'action' => array(),
					'autocomplete' => array(),
					'enctype' => array(),
					'method' => array(),
					'name' => array(),
					'novalidate' => array(),
					'onreset' => array(),
					'onsubmit' => array(),
					'target' => array()
				)
			),
			'h1' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'h2' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'h3' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'h4' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'h5' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'h6' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes()
			),
			'hr' => $this->arpricelite_global_attributes(),
			'i' => $this->arpricelite_global_attributes(),
			'img' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'alt' => array(),
					'height' => array(),
					'ismap' => array(),
					'onabort' => array(),
					'onerror' => array(),
					'onload' => array(),
					'sizes' => array(),
					'src' => array(),
					'srcset' => array(),
					'usemap' => array(),
					'width' => array()
				)
			),
			'input' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'accept' => array(),
					'alt' => array(),
					'autocomplete' => array(),
					'autofocus' => array(),
					'checked' => array(),
					'dirname' => array(),
					'disabled' => array(),
					'height' => array(),
					'list' => array(),
					'max' => array(),
					'maxlength' => array(),
					'min' => array(),
					'multiple' => array(),
					'name' => array(),
					'onload' => array(),
					'onsearch' => array(),
					'pattern' => array(),
					'placeholder' => array(),
					'readonly' => array(),
					'required' => array(),
					'size' => array(),
					'src' => array(),
					'step' => array(),
					'type' => array(),
					'value' => array(),
					'width' => array()
				)
			),
			'ins' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'cite' => array(),
					'datetime' => array()
				)
			),
			'label' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'for' => array(),
				)
			),
			'li' => $this->arpricelite_global_attributes(),
			'object' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'data' => array(),
					'height' => array(),
					'name' => array(),
					'onabort' => array(),
					'oncanplay' => array(),
					'onerror' => array(),
					'type' => array(),
					'usemap' => array(),
					'width' => array(),
				)
			),
			'ol' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'reversed' => array(),
					'start' => array()
				)
			),
			'optgroup' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'disabled' => array(),
					'label' => array()
				)
			),
			'option' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'disabled' => array(),
					'label' => array(),
					'selected' => array(),
					'value' => array()
				)
			),
			'p' => $this->arpricelite_global_attributes(),
			'script' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'async' => array(),
					'charset' => array(),
					'defer' => array(),
					'onerror' => array(),
					'onload' => array(),
					'src' => array(),
					'type' => array()
				)
			),
			'section' => $this->arpricelite_global_attributes(),
			'select' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'autofocus' => array(),
					'disabled' => array(),
					'multiple' => array(),
					'name' => array(),
					'required' => array(),
					'size' => array()
				)
			),
			'small' => $this->arpricelite_global_attributes(),
			'span' => $this->arpricelite_global_attributes(),
			'strike' => $this->arpricelite_global_attributes(),
			'strike' => $this->arpricelite_global_attributes(),
			'strong' => $this->arpricelite_global_attributes(),
			'sub' => $this->arpricelite_global_attributes(),
			'sup' => $this->arpricelite_global_attributes(),
			'table' => $this->arpricelite_global_attributes(),
			'tbody' => $this->arpricelite_global_attributes(),
			'thead' => $this->arpricelite_global_attributes(),
			'tfooter' => $this->arpricelite_global_attributes(),
			'th' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'colspan' => array(),
					'headers' => array(),
					'rowspan' => array(),
					'scope' => array()
				)
			),
			'td' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'colspan' => array(),
					'headers' => array(),
					'rowspan' => array()
				)
			),
			'tr' => $this->arpricelite_global_attributes(),
			'textarea' => array_merge(
				$this->arpricelite_global_attributes(),
				$this->arpricelite_visible_tag_attributes(),
				array(
					'autofocus' => array(),
					'cols' => array(),
					'dirname' => array(),
					'disabled' => array(),
					'maxlength' => array(),
					'name' => array(),
					'placeholder' => array(),
					'readonly' => array(),
					'required' => array(),
					'rows' => array(),
					'wrap' => array()
				)
			),
			'time' => array_merge(
				$this->arpricelite_global_attributes(),
				array(
					'datetime' => array()
				)
			),
			'u' => $this->arpricelite_global_attributes(),
			'ul' => $this->arpricelite_global_attributes(),
		);

		return $arplite_allowed_html;
	}

	function arpricelite_global_attributes(){
		return array(
			'class' => array(),			
			'id' => array(),
			'title' => array(),
			'tabindex' => array(),
			'lang' => array(),
			'style' => array(),
		);
	}

	function arpricelite_visible_tag_attributes(){
		return array(
			'onblur' => array(),
			'onchange' => array(),
			'onclick' => array(),
			'oncontextmenu' => array(),
			'oncopy' => array(),
			'oncut' => array(),
			'ondblclick' => array(),
			'ondrag' => array(),
			'ondragend' => array(),
			'ondragenter' => array(),
			'ondragleave' => array(),
			'ondragover' => array(),
			'ondragstart' => array(),
			'ondrop' => array(),
			'onfocus' => array(),
			'oninput' => array(),
			'oninvalid' => array(),
			'onkeydown' => array(),
			'onkeypress' => array(),
			'onkeyup' => array(),
			'onmousedown' => array(),
			'onmousemove' => array(),
			'onmouseout' => array(),
			'onmouseover' => array(),
			'onmouseup' => array(),
			'onmousewheel' => array(),
			'onpaste' => array(),
			'onscroll' => array(),
			'onselect' => array(),
			'onwheel' => array()
		);
	}

	function arplite_reset_colorpicker( $options ){

        if( !empty( $options ) ){
            foreach( $options as $opt_k => $opt_val ){
                if( is_array( $opt_val ) ){
                    $options[ $opt_k ] = $this->arplite_reset_colorpicker( $opt_val );
                } else {
                    if( preg_match( '/^#([a-fA-F0-9]{6})$/', $opt_val ) ){
                        $opt_val = $opt_val.'FF';
                    }
                    $options[$opt_k] = $opt_val;
                }
            }
        }

        return $options;
    }
}

function arpricelite_load_table( $id = '' ) {

	global $arpricelite_form, $arpricelite_img_css_version, $arpricelite_version;

	$formids = array();

	$formids[] = $id;

	if ( isset( $formids ) and is_array( $formids ) && count( $formids ) > 0 ) {
		foreach ( $formids as $newkey => $newval ) {
			$newval = str_replace( '"', '', $newval );
			$newval = str_replace( "'", '', $newval );
			if ( stripos( $newval, ' ' ) !== false ) {
				$partsnew    = explode( ' ', $newval );
				$newvalarr[] = $partsnew[0];
			} else {
				$newvalarr[] = $newval;
			}
		}
	}

	if ( $newvalarr ) {
		$newvalues_enqueue = $arpricelite_form->get_table_enqueue_data( $newvalarr );
	}

	if ( is_array( $newvalues_enqueue ) && count( $newvalues_enqueue ) > 0 ) {
		$templates   = array();
		$is_template = 0;

		foreach ( $newvalues_enqueue as $n => $newqnqueue ) {

			if ( $newqnqueue['template_name'] != 0 ) {
				$templates[] = $newqnqueue['template_name'];
			} else {
				$templates[] = $n;
			}

			if ( ! empty( $newqnqueue['is_template'] ) ) {
				$is_template = $newqnqueue['is_template'];
			}
		}

		$templates = array_unique( $templates );

		if ( $templates ) {
			wp_enqueue_script( 'arplite_front_js' );

			wp_enqueue_style( 'arplite_front_css' );

			foreach ( $newvalues_enqueue as $template_id => $newqnqueue ) {

				if ( isset( $newqnqueue['is_template'] ) && ! empty( $newqnqueue['is_template'] ) ) {
					wp_register_style( 'arplitetemplate_' . $newqnqueue['template_name'] . '_css', ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $newqnqueue['template_name'] . '_v' . $arpricelite_img_css_version . '.css', array(), $arpricelite_version );
					wp_enqueue_style( 'arplitetemplate_' . $newqnqueue['template_name'] . '_css' );
				} else {

					wp_register_style( 'arplitetemplate_' . $template_id . '_css', ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $template_id . '.css', array(), $arpricelite_version );
					wp_enqueue_style( 'arplitetemplate_' . $template_id . '_css' );
				}
			}
		}
	}

	return do_shortcode( '[ARPLite id=' . $id . ']' );
}

function arplite_rmdir($src){
	if (file_exists($src)) {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    arplite_rmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
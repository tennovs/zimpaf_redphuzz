<?php

class arpricelite {

    function __construct() {
        add_action('wp_ajax_arpricelite_delete', array($this, 'arpricelite_delete'));
        add_action('wp_ajax_arplite_pro_preview', array($this, 'arplite_pro_preview'));
        add_action('wp_ajax_arpsubscribe', array($this, 'arpreqact'));
        add_action('admin_init', array($this, 'upgrade_data'));

        add_action('wp_ajax_arpricelite_get_sample_template_list', array($this,'arpricelite_get_sample_template_list'));

        add_action('arplite_remove_backup_data', array( $this, 'arplite_remove_backup_data') );

        add_filter( 'plugin_action_links', array( $this, 'arplite_plugin_action_links' ), 10, 2 );

        add_action( 'admin_footer', array( $this, 'arplite_deactivate_feedback_popup' ), 1 );

        add_action( 'wp_ajax_arplite_deactivate_plugin', array( $this, 'arplite_deactivate_plugin_func') );
    }

    function arplite_plugin_action_links($links , $file){

    	if ( $file == 'arprice-responsive-pricing-table/arprice-responsive-pricing-table.php' ) {

			if ( isset( $links['deactivate'] ) ) {

				$deactivation_link = $links['deactivate'];

				$deactivation_link   = str_replace(
					'<a ',
					'<div class="arplite-deactivate-form-wrapper">
                         <span class="arplite-deactivate-form" id="arplite-deactivate-form-' . esc_attr( 'ARPricelite' ) . '"></span>
                     </div><a id="arplite-deactivate-link-' . esc_attr( 'ARPricelite' ) . '" ',
					$deactivation_link
				);
				$links['deactivate'] = $deactivation_link;
			}
		}
		return $links;
    }

    function arplite_deactivate_feedback_popup(){
    	global $pagenow;

    	if($pagenow == 'plugins.php'){

    		$question_options = array();

    		$question_options['list_data_options'] = array(
				'setup-difficult'  => __( 'Set up is too difficult', 'arprice-responsive-pricing-table' ),
				'docs-improvement' => __( 'Lack of documentation', 'arprice-responsive-pricing-table' ),
				'features'         => __( 'Not the features I wanted', 'arprice-responsive-pricing-table' ),
				'better-plugin'    => __( 'Found a better plugin', 'arprice-responsive-pricing-table' ),
				'incompatibility'  => __( 'Incompatible with theme or plugin', 'arprice-responsive-pricing-table' ),
				'bought-premium'   => __( 'I bought premium version of ARPrice', 'arprice-responsive-pricing-table' ),
				'maintenance'      => __( 'Other', 'arprice-responsive-pricing-table' ),
			);

			$html = '<div class="arplite-deactivate-form-head"><strong>' . esc_html( __( 'ARPrice Lite - Sorry to see you go', 'arprice-responsive-pricing-table' ) ) . '</strong></div>';

			$html .= '<div class="arplite-deactivate-form-body">';

			if ( is_array( $question_options['list_data_options'] ) ) {

				$html .= '<div class="arplite-deactivate-options">';

					$html .= '<p><strong>' . esc_html( __( 'Before you deactivate the ARPrice Lite plugin, would you quickly give us your reason for doing so?', 'arprice-responsive-pricing-table' ) ) . '</strong></p><p>';

				foreach ( $question_options['list_data_options'] as $key => $option ) {
					$html .= '<input type="radio" name="arplite-deactivate-reason" id="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '">' . esc_attr( $option ) . '</label><br>';
				}

					$html .= '</p><label id="arplite-deactivate-details-label" for="arplite-deactivate-reasons"><strong>' . esc_html( __( 'How could we improve ?', 'arprice-responsive-pricing-table' ) ) . '</strong></label><textarea name="arplite-deactivate-details" id="arplite-deactivate-details" rows="2"></textarea>';

					$html .= '</div>';
			}

			$html .= '<hr/>';

			$html .= '</div>';

			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __( 'Submitting form', 'arprice-responsive-pricing-table' ) . '</p>';

			$html .= '<div class="arplite-deactivate-form-footer"><p>';

				$html .= '<label for="arplite_anonymous" title="'
					. __( 'If you UNCHECK this then your email address will be sent along with your feedback. This can be used by arplite to get back to you for more info or a solution.', 'arprice-responsive-pricing-table' )
					. '"><input type="checkbox" name="arplite-deactivate-tracking" checked="checked" id="arplite_anonymous"> ' . esc_html__( 'Send anonymous', 'arprice-responsive-pricing-table' ) . '</label><br>';

				$html .= '<a id="arplite-deactivate-submit-form" class="button button-primary" href="#">'
					. sprintf( __( '%s Submit and%s Deactivate', 'arprice-responsive-pricing-table' ),'<span>','</span>')
					. '</a>';

			$html .= '</p></div>';
			?>
			<div class="arplite-deactivate-form-skeleton" id="arplite-deactivate-form-skeleton"><?php echo $html; ?></div>
			<div class="arplite-deactivate-form-bg"></div>
			<?php
    	}
    }

    function arplite_deactivate_plugin_func(){

    	check_ajax_referer( 'arplite_deactivate_plugin', 'security' );
        
    	if( ! empty( $_POST['arplite_reason'] ) && isset( $_POST['arplite_details'] ) ) {
    		$arplite_anonymous        = isset( $_POST['arplite_anonymous'] ) && $_POST['arplite_anonymous'];
			$args                     = $_POST;
			$args['arplite_site_url'] = ARPLITE_HOME_URL;

			if ( ! $arflite_anonymous ) {
				$args['arp_lite_site_email'] = get_option( 'admin_email' );
			}

			$url = 'https://www.arpriceplugin.com/download_samples/arplite_feedback.php';

			$response = wp_remote_post(
				$url,
				array(
					'body'    => $args,
					'timeout' => 500,
				)
			);
		}
		echo json_encode(
			array(
				'status' => 'OK',
			)
		);
		die();
    }

    function arplite_remove_backup_data(){
        global $wpdb;

        $wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `".$wpdb->prefix."arplite_arprice_backup_v2.6`" ) );
        $wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `".$wpdb->prefix."arplite_arprice_options_backup_v2.6`" ) );

        $wp_upload_dir = wp_upload_dir();
        $backup_dir = $wp_upload_dir['basedir'].'/arprice-responsive-pricing-table_backup_v6';
        if( is_dir($backup_dir) ){
            arp_rmdir( $backup_dir );
        }
    }

    function arpricelite_get_sample_template_list(){
        $return = array();

        $arp_sample_page = isset($_REQUEST['sample_page']) ? sanitize_text_field( $_REQUEST['sample_page'] ) : 1;

        $return['current_page'] = $arp_sample_page;
        $return['is_last_page'] = 0;
        $return['arp_content'] = '';

        $arp_posturl = 'https://www.arpriceplugin.com/download_samples/arp_samples_list.php';

        $arp_response = wp_remote_post($arp_posturl, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array('arp_req_page' => $arp_sample_page),
            'cookies' => array()
        ));

        if (is_wp_error($arp_response) || $arp_response['response']['code'] != 200) {
            $return['error'] = true;
        } else {
            $return['error'] = false;
            $arp_samples = maybe_unserialize(base64_decode($arp_response['body']));

            $arp_content = '';

            if(isset($arp_samples['is_last_page'])){
                $return['is_last_page'] = 1;
                unset($arp_samples['is_last_page']);
            }

            foreach ($arp_samples as $arp_slug => $arp_sample) {
                $arpsample_image = isset($arp_sample['image']) ? $arp_sample['image'] : '';
                $arpsample_redirect = ( isset($arp_sample['redirect_url']) && $arp_sample['redirect_url'] != '' ) ? $arp_sample['redirect_url'] : '#';
                $arpsample_id = ( isset($arp_sample['template_id']) && $arp_sample['template_id'] != '' ) ? $arp_sample['template_id'] : '';

                $arp_content .= '<div class="arprice_select_template_container_item arprice_download_sample_container_item">';
                $arp_content .= '<div class="arprice_select_template_inner_container arprice_download_sample_inner_container">';
                $arp_content .= '<div class="arprice_select_template_bg_img arprice_download_sample_bg_img" style="background:url('.$arpsample_image.') no-repeat top left;"></div>';
                $arp_content .= '<div class="arprice_select_template_action_div arprice_download_sample_action_div">';
                $arp_content .= '<div class="arprice_select_template_action_btn arprice_download_sample_action_btn arprice_download_sample" id="arprice_download_sample" title="'.esc_html__('Install', 'arprice-responsive-pricing-table').'" onClick="arp_download_sample(\''.$arpsample_id.'\');"></div>';
                $arp_content .= '<div class="arprice_select_template_action_btn arprice_download_sample_action_btn arprice_redirect_sample" id="arprice_redirect_sample" title="'.esc_html__('Preview', 'arprice-responsive-pricing-table').'" onClick="arp_redirect_to_sample(\''.$arpsample_redirect.'\');"></div>';
                $arp_content .= '</div>';
                $arp_content .= '</div>';
                $arp_content .= '</div>';
            }

            $return['arp_content'] = $arp_content;
        }

        echo wp_json_encode($return);
        die;
    }

    function upgrade_data() {
        global $wpdb, $arpricelite_version;
        $checkupdate = "";
        $checkupdate = get_option('arpricelite_version');

        if (version_compare($checkupdate, '1.1', '<')) {
            update_option('arpricelite_version', sanitize_text_field($arpricelite_version));
            update_option('arplite_popup_display',sanitize_text_field('yes'));
            update_option('arplite_already_subscribe', sanitize_text_field('no'));
        }

        if (version_compare($checkupdate, '3.4', '<')) {
            $path = ARPLITE_PRICINGTABLE_VIEWS_DIR . '/upgrade_latest_data.php';
            include($path);
        }
    }

    function arpreqact() {
        global $arpricelite_class;
        $plugres = $arpricelite_class->arpsubscribeuser();

        if (isset($plugres) && $plugres != "") {
            $responsetext = $plugres;

            if ($responsetext == "Subscribed Successfully.") {
                update_option('arplite_popup_display', sanitize_text_field('no'));
                update_option('arplite_already_subscribe', sanitize_text_field('yes'));
                echo "VERIFIED";
                exit;
            } else {
                echo $plugres;
                exit;
            }
        } else {
            echo "Invalid Request";
            exit;
        }
    }

    function arpsubscribeuser() {
        global $arpricelite_class;
        $lidata = array();

        $lidata[] = sanitize_email( $_POST["cust_email"] );

        if (!isset($_POST["cust_email"]) || sanitize_email( $_POST["cust_email"] ) == "") {
            echo "Invalid Email";
            exit;
        }

        $pluginuniquecode = $arpricelite_class->generateplugincode();
        $lidata[] = $pluginuniquecode;
        $lidata[] = ARPLITEURL;
        $lidata[] = get_option("arpricelite_version");

        $valstring = implode("||", $lidata);
        $encodedval = base64_encode($valstring);

        $urltopost = "https://www.arpriceplugin.com/premium/arprice_subscribe.php";


        $response = wp_remote_post($urltopost, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array('verifysubscribe' => $encodedval),
            'cookies' => array()
                )
        );

        if (array_key_exists('body', $response) && isset($response["body"]) && $response["body"] != "")
            $responsemsg = $response["body"];
        else
            $responsemsg = "";


        if ($responsemsg != "" && $responsemsg == "Subscribed Successfully.") {
            update_option('arplite_popup_display', sanitize_text_field('no'));
            update_option('arplite_already_subscribe', sanitize_text_field('yes'));
            return "Subscribed Successfully.";
            exit;
        } else {
            return "Invalid Request";
            exit;
        }
    }

    function arpricelite_delete() {
        global $wpdb,$arplite_pricingtable;
        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';
        $table = $wpdb->prefix . 'arplite_arprice';
        $tbl_option = $wpdb->prefix . 'arplite_arprice_options';
        $table_analytics = $wpdb->prefix . 'arplite_arprice_analytics';

        $check_caps = $arplite_pricingtable->arplite_check_user_cap('arplite_add_udpate_pricingtables',true);

        if( $check_caps != 'success' ){
            $check_caps_msg = json_decode($check_caps,true);
            echo 'error~|~'.$check_caps_msg[0];
            die;
        }
        $sql = $wpdb->get_row($wpdb->prepare('SELECT is_template FROM ' . $table . ' WHERE ID = %d', $id));
        $is_template = $sql->is_template;

        if ($is_template != 1) {
            if (file_exists(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $id . '.css')){
                unlink(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $id . '.css');
            }
            if (file_exists(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $id . '.png')) {
                unlink(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $id . '.png');
                unlink(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $id . '_big.png');
                unlink(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $id . '_large.png');
            }
        }

        $wpdb->query($wpdb->prepare('DELETE FROM ' . $table . ' WHERE ID = %d', $id));

        $wpdb->query($wpdb->prepare('DELETE FROM ' . $tbl_option . ' WHERE table_id = %d', $id));

        $wpdb->query($wpdb->prepare('DELETE FROM ' . $table_analytics . ' WHERE pricing_table_id = %d', $id));

        die();
    }

    function generateplugincode() {
        $siteinfo = array();

        $siteinfo[] = get_bloginfo('name');
        $siteinfo[] = get_bloginfo('description');
        $siteinfo[] = home_url();
        $siteinfo[] = get_bloginfo('admin_email');
        $siteinfo[] = $_SERVER['SERVER_ADDR'];

        $newstr = implode("^", $siteinfo);
        $postval = base64_encode($newstr);

        return $postval;
    }

    function table_dropdown_widget($field_name = '', $field_id = '', $default_value = '') {
        global $wpdb;
        $tables = $wpdb->get_results($wpdb->prepare("SELECT ID, table_name FROM " . $wpdb->prefix . "arplite_arprice WHERE status = '%s' and is_template != '%d'", array('published', '1')));
        $price_tabel = '';
        if ($tables) {
            $price_tabel .= '<select name="' . $field_name . '" id="' . $field_id . '" class="arp_table_list">';
            foreach ($tables as $table) {
                $price_tabel .= '<option value="' . esc_html( $table->ID ) . '" ' . selected($table->ID, $default_value, false) . '>' . $table->table_name . '</option>';
            }
            $price_tabel .= '</select>';
        }
        return $price_tabel;
    }

    function arplite_pro_preview() {
        global $arpricelite_img_css_version;

        $template_id = isset( $_REQUEST['template_id'] ) ? sanitize_text_field( $_REQUEST['template_id'] ) : '';

        echo "<image src='" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/" . $template_id . "_v" . $arpricelite_img_css_version . "_preview.png' style='width:1000px;position:relative;left:45px;' />";
        die();
    }

}

?>
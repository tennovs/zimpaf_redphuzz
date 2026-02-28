<?php
/**
 * PayPal Web Payment Plus Class
 *
 * @class    PAYPAL_WPP_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class PAYPAL_WPP_SETTLEMENT
{
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	protected $paymod_id;			//決済代行会社ID
	protected $pay_method;			//決済種別
	protected $acting_name;			//決済代行会社略称
	protected $acting_formal_name;	//決済代行会社正式名称
	protected $acting_company_url;	//決済代行会社URL
	protected $unavailable_method;	//併用不可決済モジュール

	protected $error_mes;

	public function __construct() {

		$this->paymod_id = 'paypal_wpp';
		$this->pay_method = array(
			'acting_paypal_wpp',
		);
		$this->acting_name = __( 'PayPal(WPP)', 'usces' );
		$this->acting_formal_name = __( 'PayPal Web Payment Plus', 'usces' );

		$this->initialize_data();

		if( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if( $this->is_activate_paypal_wpp() ) {
			add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
			add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initialize
	 */
	public function initialize_data() {

		$options = get_option( 'usces' );
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['paypal_wpp'] ) ) {
			$options['acting_settings']['paypal_wpp']['sandbox'] = '';
			$options['acting_settings']['paypal_wpp']['paypal_id'] = '';
			$options['acting_settings']['paypal_wpp']['agree'] = '';
			$options['acting_settings']['paypal_wpp']['wpp_activate'] = 'off';
			update_option( 'usces', $options );
		}

		$this->unavailable_method = array( 'acting_paypal_ec' );
	}

	/**
	 * 決済有効判定
	 * 支払方法で使用している場合に true
	 * @param  -
	 * @return boolean
	 */
	public function is_validity_acting() {

		$acting_opts = $this->get_acting_settings();
		if( empty( $acting_opts ) ) {
			return false;
		}

		$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
		$method = false;

		foreach( $payment_method as $payment ) {
			if( 'acting_paypal_wpp' == $payment['settlement'] && 'activate' == $payment['use'] ) {
				$method = true;
				break;
			}
		}

		if( $method && $this->is_activate_paypal_wpp() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 決済利用判定
	 * PayPalウェブペイメントプラスを「利用する」のとき true
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_paypal_wpp() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['wpp_activate'] ) && 'on' == $acting_opts['wpp_activate'] ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * @fook   admin_print_footer_scripts
	 * @param  -
	 * @return -
	 * @echo   js
	 */
	public function admin_scripts() {

		$admin_page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : '';
		switch( $admin_page ):
		case 'usces_settlement':
			$settlement_selected = get_option( 'usces_settlement_selected' );
			if( in_array( 'paypal_wpp', (array)$settlement_selected ) ):
?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	if( 'on' == $( "input[name='wpp_activate']:checked" ).val() ) {
		$( ".paypal_wpp_form" ).css( "display", "" );
		$( ".paypal_wpp_form_agree" ).css( "display", "" );
	} else {
		$( ".paypal_wpp_form" ).css( "display", "none" );
		$( ".paypal_wpp_form_agree" ).css( "display", "none" );
	}
	$( document ).on( "change", "input[name='wpp_activate']", function() {
		if( 'on' == $( "input[name='wpp_activate']:checked" ).val() ) {
			$( ".paypal_wpp_form" ).css( "display", "" );
			$( ".paypal_wpp_form_agree" ).css( "display", "" );
		} else {
			$( ".paypal_wpp_form" ).css( "display", "none" );
			$( ".paypal_wpp_form_agree" ).css( "display", "none" );
		}
	});
});
</script>
<?php
			endif;
			break;
		endswitch;
	}

	/**
	 * 決済オプション登録・更新
	 * @fook   usces_action_admin_settlement_update
	 * @param  -
	 * @return -
	 */
	public function settlement_update() {
		global $usces;

		if( $this->paymod_id != $_POST['acting'] ) {
			return;
		}

		$this->error_mes = '';
		$options = get_option( 'usces' );
		$payment_method = usces_get_system_option( 'usces_payment_method', 'settlement' );

		unset( $options['acting_settings']['paypal_wpp'] );
		$options['acting_settings']['paypal_wpp']['wpp_activate'] = ( isset( $_POST['wpp_activate'] ) ) ? $_POST['wpp_activate'] : 'off';
		$options['acting_settings']['paypal_wpp']['sandbox'] = ( isset( $_POST['sandbox'] ) ) ? $_POST['sandbox'] : '';
		$options['acting_settings']['paypal_wpp']['paypal_id'] = ( isset( $_POST['paypal_id'] ) ) ? trim( $_POST['paypal_id'] ) : '';

		$options['acting_settings']['paypal_wpp']['agree'] = ( isset( $_POST['agree_paypal_wpp'] ) ) ? $_POST['agree_paypal_wpp'] : '';

		if( 'on' == $options['acting_settings']['paypal_wpp']['wpp_activate'] ) {
			$unavailable_activate = false;
			foreach( $payment_method as $settlement => $payment ) {
				if( in_array( $settlement, $this->unavailable_method ) && 'activate' == $payment['use'] ) {
					$unavailable_activate = true;
					break;
				}
			}
			if( $unavailable_activate ) {
				$this->error_mes .= __( '* Settlement that can not be used together is activated.', 'usces' ).'<br />';
			} else {
				if( !isset( $_POST['sandbox'] ) || empty( $_POST['sandbox'] ) ) {
					$this->error_mes .= __( '* Operating environment is invalid.', 'usces' ).'<br />';
				}
				if( WCUtils::is_blank( $_POST['paypal_id'] ) ) {
					$this->error_mes .= __( '* Enter your PayPal account (email address).', 'usces' ).'<br />';
				} elseif( !is_email( $_POST['paypal_id'] ) ) {
					$this->error_mes .= __( '* Enter your PayPal account (email address) correctly.', 'usces' ).'<br />';
				}
				if( !isset( $_POST['agree_paypal_wpp'] ) ) {
					$this->error_mes .= __( "* You haven't agreed to the Terms of Use.", 'usces' ).'<br />';
				}
			}
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['paypal_wpp']['wpp_activate'] ) {
				$options['acting_settings']['paypal_wpp']['activate'] = 'on';
				if( $options['acting_settings']['paypal_wpp']['sandbox'] == 1 ) {
					$options['acting_settings']['paypal_wpp']['host_url'] = 'www.sandbox.paypal.com';
					$options['acting_settings']['paypal_wpp']['paypal_url'] = 'https://securepayments.sandbox.paypal.com/cgi-bin/acquiringweb';
				} else {
					$options['acting_settings']['paypal_wpp']['host_url'] = 'www.paypal.com';
					$options['acting_settings']['paypal_wpp']['paypal_url'] = 'https://securepayments.paypal.com/cgi-bin/acquiringweb';
				}
				$usces->payment_structure['acting_paypal_wpp'] = __( 'PayPal(WPP)', 'usces' );
				usces_admin_orderlist_show_wc_trans_id();
				$toactive = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( 'acting_paypal_wpp' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['paypal_wpp']['activate'] = 'off';
				unset( $usces->payment_structure['acting_paypal_wpp'] );
				$deactivate = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( !array_key_exists( $settlement, $usces->payment_structure ) ) {
						if( 'deactivate' != $payment['use'] ) {
							$payment['use'] = 'deactivate';
							$deactivate[] = $payment['name'];
							usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
						}
					}
				}
				if( 0 < count( $deactivate ) ) {
					$deactivate_message = sprintf( __( "\"Deactivate\" %s of payment method.", 'usces' ), implode( ',', $deactivate ) );
					$usces->action_message .= $deactivate_message;
				}
			}
		} else {
			$usces->action_status = 'error';
			$usces->action_message = __( 'Data have deficiency.', 'usces' );
			$options = get_option( 'usces' );
			$options['acting_settings']['paypal_wpp']['activate'] = 'off';
			$options['acting_settings']['paypal_wpp']['agree'] = ( isset( $_POST['agree_paypal_wpp'] ) ) ? $_POST['agree_paypal_wpp'] : '';
			unset( $usces->payment_structure['acting_paypal_wpp'] );
			$deactivate = array();
			foreach( $payment_method as $settlement => $payment ) {
				if( in_array( $settlement, $this->pay_method ) ) {
					if( 'deactivate' != $payment['use'] ) {
						$payment['use'] = 'deactivate';
						$deactivate[] = $payment['name'];
						usces_update_system_option( 'usces_payment_method', $payment['id'], $payment );
					}
				}
			}
			if( 0 < count( $deactivate ) ) {
				$deactivate_message = sprintf( __( "\"Deactivate\" %s of payment method.", 'usces' ), implode( ',', $deactivate ) );
				$usces->action_message .= $deactivate_message.__( "Please complete the setup and update the payment method to \"Activate\".", 'usces' );
			}
		}
		ksort( $usces->payment_structure );
		update_option( 'usces', $options );
		update_option( 'usces_payment_structure', $usces->payment_structure );
	}

	/**
	 * クレジット決済設定画面タブ
	 * @fook   usces_action_settlement_tab_title
	 * @param  -
	 * @return -
	 * @echo   html
	 */
	public function settlement_tab_title() {

		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( 'paypal_wpp', (array)$settlement_selected ) ) {
			echo '<li><a href="#uscestabs_paypal_wpp">PayPal(WPP)</a></li>';
		}
	}

	/**
	 * クレジット決済設定画面フォーム
	 * @fook   usces_action_settlement_tab_body
	 * @param  -
	 * @return -
	 * @echo   html
	 */
	public function settlement_tab_body() {
		global $usces;

		$acting_opts = $this->get_acting_settings();
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( 'paypal_wpp', (array)$settlement_selected ) ):
?>
	<div id="uscestabs_paypal_wpp">
	<div class="settlement_service"><span class="service_title"><?php _e( 'PayPal Web Payment Plus', 'usces' ); ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'paypal_wpp' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php echo $this->error_mes; ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message"><?php _e( 'Test thoroughly before use.', 'usces' ); ?></div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="paypal_wpp_form" id="paypal_wpp_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_wpp_activate_paypal"><?php _e( 'PayPal<br />Web Payment Plus', 'usces' ); ?></a></th>
				<td><label><input name="wpp_activate" type="radio" id="wpp_activate_paypal_1" value="on"<?php if( isset( $acting_opts['wpp_activate'] ) && $acting_opts['wpp_activate'] == 'on' ) echo ' checked'; ?> /><span><?php _e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="wpp_activate" type="radio" id="wpp_activate_paypal_2" value="off"<?php if( isset( $acting_opts['wpp_activate'] ) && $acting_opts['wpp_activate'] == 'off' ) echo ' checked'; ?> /><span><?php _e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_wpp_activate_paypal" class="explanation"><td colspan="2"><?php _e( 'Choose if to use PayPal Web Payment Plus.', 'usces' ); ?></td></tr>
			<tr class="paypal_wpp_form">
				<th><a class="explanation-label" id="label_ex_sandbox_paypal_wpp"><?php _e( 'Operation Environment', 'usces' ); ?></a></th>
				<td><label><input name="sandbox" class="wp_sandbox" type="radio" id="sandbox_paypal_wpp_1" value="1"<?php if( isset( $acting_opts['sandbox'] ) && $acting_opts['sandbox'] == 1 ) echo ' checked'; ?> /><span><?php _e( 'Test (Sandbox)', 'usces' ); ?></span></label><br />
					<label><input name="sandbox" class="wp_sandbox" type="radio" id="sandbox_paypal_wpp_2" value="2"<?php if( isset( $acting_opts['sandbox'] ) && $acting_opts['sandbox'] == 2 ) echo ' checked'; ?> /><span><?php _e( 'Formal Installment', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_sandbox_paypal_wpp" class="explanation paypal_wpp_form"><td colspan="2"><?php _e( "Choose 'Test (Sandbox)' when testing payment settlement by Sandbox.", 'usces' ); ?></td></tr>
			<tr class="paypal_wpp_form">
				<th><a class="explanation-label" id="label_ex_id_paypal_wpp"><?php _e( 'PayPal Acount Email address', 'usces' ); ?></a></th>
				<td><input name="paypal_id" type="text" id="id_paypal_wpp" value="<?php echo esc_html( isset( $acting_opts['paypal_id'] ) ? $acting_opts['paypal_id'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_id_paypal_wpp" class="explanation paypal_wpp_form"><td colspan="2"><?php _e( 'Email address associated with your PayPal account.', 'usces' ); ?></td></tr>
		</table>
		<input name="acting" type="hidden" value="paypal_wpp" />
		<input name="usces_option_update" id="paypal_wpp" type="submit" class="button button-primary" value="<?php _e( 'Update PayPal Web Payment Plus settings', 'usces' ); ?>" />
		<span class="paypal_wpp_form_agree"><input name="agree_paypal_wpp" id="agree_paypal_wpp" type="checkbox" value="agree"<?php if( isset( $acting_opts['agree'] ) && 'agree' == $acting_opts['agree'] ) echo ' checked="checked"'; ?> /><label for="agree_paypal_wpp"><?php _e( 'I agree to the following Terms of Use.', 'usces' ); ?></label></span>
		<p class="agree_paypal_exp paypal_wpp_form_agree"><?php _e( "You agree that the customer information submitted at the time of application will be provided to partner company PayPal Pte. Ltd. and it's used for its service evaluation, improvement, progress and marketing purpose, and may be offered guidance on marketing and campaign purposes (including sending emails etc.) from PayPal Pte. Ltd..", 'usces' ); ?></p>
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php _e( 'PayPal Web Payment Plus', 'usces' ); ?></strong></p>
		<a href="https://ad.doubleclick.net/ddm/clk/411330215;212055397;p" target="_blank"><?php _e( 'For the details on PayPal Web Payment Plus, click here >>', 'usces' ); ?></a>
		<p><?php printf( __( "A PayPal Business Account is required to use the PayPal Web Payment Plus Payment Service. You can open a business account from <a href=\"%s\" target=\"_blank\">here</a>.", 'usces' ), "https://ad.doubleclick.net/ddm/clk/411330218;212055400;d" ); ?></p>
		<p><?php printf( __( "Click <a href=\"%s\" target=\"_blank\">here</a> for the procedure for opening a business account.", 'usces' ), "https://www.paypal.com/jp/webapps/mpp/merchant/how-to-signup-business" ); ?></p>
		<p><a href="https://www.paypal.com/jp/webapps/mpp/support/kyc-corp" target="_blank"><?php _e( 'About a submission of identity verification documents of a business account', 'usces' ); ?></a></p>
		<p><?php printf( __( "Examination is required to use Web Payment Plus. Click <a href=\"%s\" target=\"_blank\">here</a> for the examination.", 'usces' ), "https://www.paypal.com/jp/webapps/mpp/developer/standard-service/wpp/how-to-start" ); ?></a></p>
		<p><a href="https://www.welcart.com/documents/manual-2/%E3%82%AF%E3%83%AC%E3%82%B8%E3%83%83%E3%83%88%E6%B1%BA%E6%B8%88%E8%A8%AD%E5%AE%9A#paypal_wpp" target="_blank"><?php _e( 'Online manual', 'usces' ); ?></a></p>
		<p><?php _e( 'Contact information', 'usces' ); ?><br />
<?php _e( 'Inquiry about new application / introduction (sales counter)', 'usces' ); ?><br />
<?php _e( 'Tel: 03-6739-7135 Weekdays 9:30-18:00 (except Saturdays, Sundays and public holidays Note:Calls will be charged.)', 'usces' ); ?><br />
E-mail：wpp@paypal.com</p>
<p><?php _e( 'If you already have a PayPal account (Customer Service)', 'usces' ); ?><br />
<?php _e( 'Tel: 0120-271-888 or 03-6739-7360 (These for from mobile phones or from overseas Note:Calls will be charged.)', 'usces' ); ?><br />
<?php _e( '9:00-20:00 (open all year round)', 'usces' ); ?></p>
	</div>
	</div><!--uscestabs_paypal_wpp-->
<?php
		endif;
	}

	/**
	 * 内容確認ページ [注文する] ボタン
	 * @fook   usces_filter_confirm_inform
	 * @param  $html $payments $acting_flg $rand $purchase_disabled
	 * @return string $html
	 */
	public function confirm_inform( $html, $payments, $acting_flg, $rand, $purchase_disabled ) {
		global $usces;

		if( !in_array( $acting_flg, $this->pay_method ) ) {
			return $html;
		}

		$usces_entries = $usces->cart->get_entry();
		$cart = $usces->cart->get_cart();
		if( !$usces_entries || !$cart ) {
			return $html;
		}
		if( !$usces_entries['order']['total_full_price'] ) {
			return $html;
		}

		usces_save_order_acting_data( $rand );
		$acting_opts = $usces->options['acting_settings']['paypal_wpp'];
		$currency_code = $usces->get_currency_code();
		$name1 = esc_attr( $usces_entries['customer']['name1'] );
		$name2 = esc_attr( $usces_entries['customer']['name2'] );
		$address2 = esc_attr( $usces_entries['customer']['address2'] );
		$address3 = esc_attr( $usces_entries['customer']['address3'] );
		$address1 = esc_attr( $usces_entries['customer']['address1'] );
		$pref = esc_attr( $usces_entries['customer']['pref'] );
		$country = ( !empty( $usces_entries['customer']['country'] ) ) ? $usces_entries['customer']['country'] : usces_get_base_country();
		$zip = str_replace( '-', '', $usces_entries['customer']['zipcode'] );
		$tel = ltrim( str_replace( '-', '', $usces_entries['customer']['tel'] ), '0' );
		$amount = usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false );
		$return_url = USCES_CART_URL.$usces->delim.'acting=paypal_wpp&acting_return=1&order_id='.$rand;
		$cancel_url = USCES_CART_URL.$usces->delim.'confirm=1';
		$notify_url = USCES_CART_URL.$usces->delim.'acting=paypal_wpp&acting_return=1';
		$iframe_width = apply_filters( 'usces_filter_paypal_wpp_iframe_width', '560' );
		$iframe_height = apply_filters( 'usces_filter_paypal_wpp_iframe_height', '400' );
		$template = apply_filters( 'usces_filter_paypal_wpp_template', 'templateD' );

		$html = '<div id="paypal_wpp_iframe" style="width:'.$iframe_width.'px; margin: 0 auto;">
			<iframe name="hss_iframe" width="100%" height="'.$iframe_height.'px"></iframe>
			<form style="display:none" target="hss_iframe" name="form_iframe" method="post" action="'.$acting_opts['paypal_url'].'">
			<input type="hidden" name="cmd" value="_hosted-payment">
			<input type="hidden" name="subtotal" value="'.$amount.'">
			<input type="hidden" name="business" value="'.$acting_opts['paypal_id'].'">
			<input type="hidden" name="paymentaction" value="sale">
			<input type="hidden" name="template" value="'.$template.'">
			<input type="hidden" name="billing_address1" value="'.$address2.'">
			<input type="hidden" name="billing_address2" value="'.$address3.'">
			<input type="hidden" name="billing_city" value="'.$address1.'">
			<input type="hidden" name="billing_country" value="'.$country.'">
			<input type="hidden" name="billing_first_name" value="'.$name1.'">
			<input type="hidden" name="billing_last_name" value="'.$name2.'">
			<input type="hidden" name="billing_state" value="'.$pref.'">
			<input type="hidden" name="billing_zip" value="'.$zip.'">
			<input type="hidden" name="currency_code" value="'.$currency_code.'">
			<input type="hidden" name="return" value="'.$return_url.'">
			<input type="hidden" name="cancel_return" value="'.$cancel_url.'">
			<input type="hidden" name="notify_url" value="'.$notify_url.'">
			<input type="hidden" name="showHostedThankyouPage" value="false">
			<input type="hidden" name="custom" value="'.$rand.'">
			<input type="hidden" name="bn" value="uscons_cart_WPS_JP">';
		if( usces_have_shipped( $cart ) && ( isset( $usces_entries['delivery']['delivery_flag'] ) && 2 != $usces_entries['delivery']['delivery_flag'] ) ) {
			$delivery_name1 = apply_filters( 'usces_filter_paypal_wpp_first_name', $usces_entries['delivery']['name1'] );
			$delivery_name2 = apply_filters( 'usces_filter_paypal_wpp_last_name', $usces_entries['delivery']['name2'] );
			$delivery_address2 = apply_filters( 'usces_filter_paypal_wpp_address1', $usces_entries['delivery']['address2'] );
			$delivery_address3 = apply_filters( 'usces_filter_paypal_wpp_address2', $usces_entries['delivery']['address3'] );
			$delivery_address1 = apply_filters( 'usces_filter_paypal_wpp_city', $usces_entries['delivery']['address1'] );
			$delivery_pref = apply_filters( 'usces_filter_paypal_wpp_state', $usces_entries['delivery']['pref'] );
			$delivery_country = ( !empty( $usces_entries['delivery']['country'] ) ) ? $usces_entries['delivery']['country'] : usces_get_base_country();
			$delivery_country_code = apply_filters( 'usces_filter_paypal_wpp_country', $delivery_country );
			$delivery_zip = apply_filters( 'usces_filter_paypal_wpp_zip', str_replace( '-', '', $usces_entries['delivery']['zipcode'] ) );
			$html .= '<input type="hidden" name="address_override" value="true">
			<input type="hidden" name="address1" value="'.$delivery_address2.'">
			<input type="hidden" name="address2" value="'.$delivery_address3.'">
			<input type="hidden" name="city" value="'.$delivery_address1.'">
			<input type="hidden" name="country" value="'.$delivery_country_code.'">
			<input type="hidden" name="first_name" value="'.$delivery_name1.'">
			<input type="hidden" name="last_name" value="'.$delivery_name2.'">
			<input type="hidden" name="state" value="'.$delivery_pref.'">
			<input type="hidden" name="zip" value="'.$delivery_zip.'">';
		} else {
			$html .= '<input type="hidden" name="address_override" value="false">';
		}
		$html .= '</form>
			<script type="text/javascript">
				document.form_iframe.submit();
			</script></div>';
		$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
			<div class="send"><input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__( 'Back', 'usces' ).'"'.apply_filters( 'usces_filter_confirm_prebutton', NULL ).' /></div>';
		return $html;
	}

	/**
	 * 受注データ登録
	 * Call from usces_reg_orderdata() and usces_new_orderdata().
	 * @fook   usces_action_reg_orderdata
	 * @param  @array $cart, $entry, $order_id, $member_id, $payments, $charging_type, $results
	 * @return -
	 * @echo   -
	 */
	public function register_orderdata( $args ) {
		global $usces;
		extract( $args );

		$acting_flg = $payments['settlement'];
		if( !in_array( $acting_flg, $this->pay_method ) ) {
			return;
		}

		if( !$entry['order']['total_full_price'] ) {
			return;
		}

		if( isset( $_POST['txn_type'] ) && 'pro_hosted' == $_POST['txn_type'] ) {
			$data['txn_id'] = esc_sql( $_POST['txn_id'] );
			$usces->set_order_meta_value( 'acting_paypal_wpp', serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_POST['txn_id'], $order_id );
		}
	}

	/**
	 * 決済オプション取得
	 * @param  -
	 * @return array $acting_settings
	 */
	protected function get_acting_settings() {
		global $usces;

		$acting_settings = ( isset( $usces->options['acting_settings'][$this->paymod_id] ) ) ? $usces->options['acting_settings'][$this->paymod_id] : array();
		return $acting_settings;
	}
}

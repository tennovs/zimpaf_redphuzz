<?php
/**
 * PayPal Express Checkout Class
 *
 * @class    PAYPAL_EC_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class PAYPAL_EC_SETTLEMENT
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

		$this->paymod_id = 'paypal';
		$this->pay_method = array(
			'acting_paypal_ec',
		);
		$this->acting_name = 'PayPal(EC)';
		$this->acting_formal_name = __( 'PayPal Express Checkout', 'usces' );

		$this->initialize_data();

		if( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if( $this->is_activate_paypal_ec() ) {
			add_action( 'usces_after_cart_instant', array( $this, 'acting_main' ) );
			add_filter( 'usces_filter_confirm_inform', array( $this, 'confirm_inform' ), 10, 5 );
			add_action( 'usces_action_reg_orderdata', array( $this, 'register_orderdata' ) );
			add_action( 'usces_filter_completion_settlement_message', array( $this, 'completion_settlement_message' ), 10, 2 );
		}

		if( $this->is_validity_acting() ) {
			add_action( 'init', array( $this, 'add_stylesheet' ) );
			add_action( 'usces_after_main', array( $this, 'add_script' ) );
			add_action( 'usces_front_ajax', array( $this, 'front_ajax' ) );
			add_filter( 'usces_filter_uscesL10n', array( $this, 'set_uscesL10n' ), 12, 2 );
			//add_action( 'usces_action_cart_page_footer', array( $this, 'e_cart_page_footer' ) );
			//add_filter( 'usces_filter_cartContent', array( $this, 'cart_page_footer' ) );
			add_action( 'usces_action_customerinfo', array( $this, 'customerinfo' ) );
			add_action( 'usces_purchase_validate', array( $this, 'purchase_validate' ) );
			add_action( 'wp_print_footer_scripts', array( $this, 'footer_scripts' ) );
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
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['paypal'] ) ) {
			$options['acting_settings']['paypal']['sandbox'] = '';
			$options['acting_settings']['paypal']['user'] = '';
			$options['acting_settings']['paypal']['pwd'] = '';
			$options['acting_settings']['paypal']['signature'] = '';
			$options['acting_settings']['paypal']['paypal_acount'] = '';
			$options['acting_settings']['paypal']['continuation'] = '';
			$options['acting_settings']['paypal']['logoimg'] = '';
			$options['acting_settings']['paypal']['set_cartbordercolor'] = 'off';
			$options['acting_settings']['paypal']['cartbordercolor'] = '';

			$options['acting_settings']['paypal']['set_liwp'] = 'off';
			$options['acting_settings']['paypal']['liwp_client_id'] = '';
			$options['acting_settings']['paypal']['liwp_secret'] = '';
			$options['acting_settings']['paypal']['liwp_client_id_sand'] = '';
			$options['acting_settings']['paypal']['liwp_secret_sand'] = '';

			$options['acting_settings']['paypal']['agree'] = '';
			$options['acting_settings']['paypal']['ec_activate'] = 'off';
			update_option( 'usces', $options );
		}

		$this->unavailable_method = array( 'acting_paypal_wpp' );
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
			if( 'acting_paypal_ec' == $payment['settlement'] && 'activate' == $payment['use'] ) {
				$method = true;
				break;
			}
		}
		if( $method && $this->is_activate_paypal_ec() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 決済利用判定
	 * PayPal Express Checkout を「利用する」のとき true
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_paypal_ec() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['ec_activate'] ) && 'on' == $acting_opts['ec_activate'] ) ) {
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
			if( in_array( 'paypal_ec', (array)$settlement_selected ) ):
?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	if( 'on' == $( "input[name='ec_activate']:checked" ).val() ) {
		$( ".paypal_ec_form" ).css( "display", "" );
		$( ".paypal_ec_form_agree" ).css( "display", "" );
	} else {
		$( ".paypal_ec_form" ).css( "display", "none" );
		$( ".paypal_ec_form_agree" ).css( "display", "none" );
	}
	$( document ).on( "change", "input[name='ec_activate']", function() {
		if( 'on' == $( "input[name='ec_activate']:checked" ).val() ) {
			$( ".paypal_ec_form" ).css( "display", "" );
			$( ".paypal_ec_form_agree" ).css( "display", "" );
		} else {
			$( ".paypal_ec_form" ).css( "display", "none" );
			$( ".paypal_ec_form_agree" ).css( "display", "none" );
		}
	});
	if( 'on' == $( "input[name='set_liwp']:checked" ).val() ) {
		$( ".paypal_ec_form_login_with_paypal" ).css( "display", "" );
	} else {
		$( ".paypal_ec_form_login_with_paypal" ).css( "display", "none" );
	}
	$( document ).on( "change", "input[name='set_liwp']", function() {
		if( 'on' == $( "input[name='set_liwp']:checked" ).val() ) {
			$( ".paypal_ec_form_login_with_paypal" ).css( "display", "" );
		} else {
			$( ".paypal_ec_form_login_with_paypal" ).css( "display", "none" );
		}
	});

	if( 'on' == $( "input[name='set_cartbordercolor']:checked" ).val() ) {
		$( "#cartbordercolor" ).css( "display", "" );
	} else {
		$( "#cartbordercolor" ).css( "display", "none" );
	}
	$( document ).on( "click", "input[name='set_cartbordercolor']", function() {
		if( 'on' == $( "input[name='set_cartbordercolor']:checked" ).val() ) {
			$( "#cartbordercolor" ).css( "display", "" );
		} else {
			$( "#cartbordercolor" ).css( "display", "none" );
		}
	});

	$( document ).on( "click", ".ec_sandbox", function() {
		if( "1" == $( this ).val() ) {
			$( "#get_paypal_signature" ).html( '<br />テスト環境（Sandbox）用APIユーザ名、APIパスワード、署名の情報は<a target="_blank" href="https://www.sandbox.paypal.com/jp/ja/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true">こちら</a>から取得可能です。' );
		} else {
			$( "#get_paypal_signature" ).html( '<br />本番環境用APIユーザ名、APIパスワード、署名の情報は<a target="_blank" href="https://www.paypal.com/jp/ja/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true">こちら</a>から取得可能です。' );
		}
	});
	if( 1 == $( ".ec_sandbox:checked" ).val() ) {
		$( "#get_paypal_signature" ).html( '<br />テスト環境（Sandbox）用APIユーザ名、APIパスワード、署名の情報は<a target="_blank" href="https://www.sandbox.paypal.com/jp/ja/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true">こちら</a>から取得可能です。' );
	} else {
		$( "#get_paypal_signature" ).html( '<br />本番環境用APIユーザ名、APIパスワード、署名の情報は<a target="_blank" href="https://www.paypal.com/jp/ja/cgi-bin/webscr?cmd=_get-api-signature&generic-flow=true">こちら</a>から取得可能です。' );
	}
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

		unset( $options['acting_settings']['paypal'] );
		$options['acting_settings']['paypal']['ec_activate'] = ( isset( $_POST['ec_activate'] ) ) ? $_POST['ec_activate'] : '';
		$options['acting_settings']['paypal']['sandbox'] = ( isset( $_POST['sandbox'] ) ) ? $_POST['sandbox'] : '';
		$options['acting_settings']['paypal']['user'] = ( isset( $_POST['user'] ) ) ? trim( $_POST['user'] ) : '';
		$options['acting_settings']['paypal']['pwd'] = ( isset( $_POST['pwd'] ) ) ? trim( $_POST['pwd'] ) : '';
		$options['acting_settings']['paypal']['signature'] = ( isset( $_POST['signature'] ) ) ? trim( $_POST['signature'] ) : '';
		$options['acting_settings']['paypal']['paypal_acount'] = ( isset( $_POST['paypal_acount'] ) ) ? trim( $_POST['paypal_acount'] ) : '';
		$options['acting_settings']['paypal']['continuation'] = ( isset( $_POST['continuation'] ) ) ? $_POST['continuation'] : '';
		$options['acting_settings']['paypal']['logoimg'] = ( isset( $_POST['logoimg'] ) ) ? $_POST['logoimg'] : '';
		$options['acting_settings']['paypal']['set_cartbordercolor'] = ( isset( $_POST['set_cartbordercolor'] ) ) ? $_POST['set_cartbordercolor'] : 'off';
		$options['acting_settings']['paypal']['cartbordercolor'] = ( 'on' == $options['acting_settings']['paypal']['set_cartbordercolor'] ) ? $_POST['cartbordercolor'] : '';

		$options['acting_settings']['paypal']['set_liwp'] = ( isset( $_POST['set_liwp'] ) ) ? $_POST['set_liwp'] : 'off';
		$options['acting_settings']['paypal']['liwp_client_id'] = ( isset( $_POST['liwp_client_id'] ) ) ? trim( $_POST['liwp_client_id'] ) : '';
		$options['acting_settings']['paypal']['liwp_secret'] = ( isset( $_POST['liwp_secret'] ) ) ? trim( $_POST['liwp_secret'] ) : '';
		$options['acting_settings']['paypal']['liwp_client_id_sand'] = ( isset( $_POST['liwp_client_id_sand'] ) ) ? trim( $_POST['liwp_client_id_sand'] ) : '';
		$options['acting_settings']['paypal']['liwp_secret_sand'] = ( isset( $_POST['liwp_secret_sand'] ) ) ? trim( $_POST['liwp_secret_sand'] ) : '';

		$options['acting_settings']['paypal']['agree'] = ( isset( $_POST['agree_paypal_ec'] ) ) ? $_POST['agree_paypal_ec'] : '';

		if( 'on' == $options['acting_settings']['paypal']['ec_activate'] ) {
			$unavailable_activate = false;
			foreach( $payment_method as $settlement => $payment ) {
				if( in_array( $settlement, $this->unavailable_method ) && 'deactivate' != $payment['use'] ) {
					$unavailable_activate = true;
					break;
				}
			}
			if( $unavailable_activate ) {
				$this->error_mes .= __( '* Settlement that can not be used together is activated.', 'usces' ).'<br />';
			} else {
				if( !isset( $_POST['sandbox'] ) || empty( $_POST['sandbox'] ) ) {
					$this->error_mes .= '※動作環境が不正です<br />';
				}
				if( WCUtils::is_blank( $_POST['user'] ) ) {
					$this->error_mes .= '※APIユーザー名を入力してください<br />';
				}
				if( WCUtils::is_blank( $_POST['pwd'] ) ) {
					$this->error_mes .= '※APIパスワードを入力してください<br />';
				}
				if( WCUtils::is_blank( $_POST['signature'] ) ) {
					$this->error_mes .= '※署名を入力してください<br />';
				}
				if( WCUtils::is_blank( $_POST['paypal_acount'] ) ) {
					$this->error_mes .= '※PayPalアカウント（メールアドレス）を入力してください<br />';
				}
				if( !is_email( $_POST['paypal_acount'] ) ) {
					$this->error_mes .= '※PayPalアカウント（メールアドレス）を正しく入力してください<br />';
				}
				if( 'on' == $options['acting_settings']['paypal']['set_liwp'] ) {
					if( isset( $_POST['liwp_client_id'] ) && empty( $_POST['liwp_client_id'] ) ) {
						$this->error_mes .= '※Client ID を入力してください<br />';
					}
					if( isset( $_POST['liwp_secret'] ) && empty( $_POST['liwp_secret'] ) ) {
						$this->error_mes .= '※Secret を入力してください<br />';
					}
				}
				if( !isset( $_POST['agree_paypal_ec'] ) ) {
					$this->error_mes .= '※ご利用条件の同意がありません<br />';
				}
			}
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['paypal']['ec_activate'] ) {
				$options['acting_settings']['paypal']['activate'] = 'on';
				if( $options['acting_settings']['paypal']['sandbox'] == 1 ) {
					$options['acting_settings']['paypal']['api_host'] = 'api-3t.sandbox.paypal.com';
					$options['acting_settings']['paypal']['api_endpoint'] = 'https://api-3t.sandbox.paypal.com/nvp';
					$options['acting_settings']['paypal']['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
					$options['acting_settings']['paypal']['liwp_authorize'] = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
					$options['acting_settings']['paypal']['liwp_tokenservice'] = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice';
					$options['acting_settings']['paypal']['liwp_userinfo'] = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo';
				} else {
					$options['acting_settings']['paypal']['api_host'] = 'api-3t.paypal.com';
					$options['acting_settings']['paypal']['api_endpoint'] = 'https://api-3t.paypal.com/nvp';
					$options['acting_settings']['paypal']['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr';
					$options['acting_settings']['paypal']['liwp_authorize'] = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
					$options['acting_settings']['paypal']['liwp_tokenservice'] = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice';
					$options['acting_settings']['paypal']['liwp_userinfo'] = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo';
				}
				$usces->payment_structure['acting_paypal_ec'] = 'PayPal決済(EC)';
				usces_admin_orderlist_show_wc_trans_id();
				$toactive = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( 'acting_paypal_ec' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['paypal']['activate'] = 'off';
				unset( $usces->payment_structure['acting_paypal_ec'] );
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
			$options['acting_settings']['paypal']['activate'] = 'off';
			$options['acting_settings']['paypal']['agree'] = ( isset( $_POST['agree_paypal_ec'] ) ) ? $_POST['agree_paypal_ec'] : '';
			unset( $usces->payment_structure['acting_paypal_ec'] );
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
		if( in_array( 'paypal_ec', (array)$settlement_selected ) ) {
			echo '<li><a href="#uscestabs_paypal_ec">PayPal(EC)</a></li>';
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
		if( in_array( 'paypal_ec', (array)$settlement_selected ) ):
?>
	<div id="uscestabs_paypal_ec">
	<div class="settlement_service"><span class="service_title"><?php _e( 'PayPal Express Checkout', 'usces' ); ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'paypal' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php echo $this->error_mes; ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message"><?php _e( 'Test thoroughly before use.', 'usces' ); ?></div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="paypal_form" id="paypal_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_ec_activate_paypal"><?php _e( 'PayPal<br />Express Checkout', 'usces' ); ?></a></th>
				<td><label><input name="ec_activate" type="radio" id="ec_activate_paypal_1" value="on"<?php if( isset( $acting_opts['ec_activate'] ) && $acting_opts['ec_activate'] == 'on' ) echo ' checked'; ?> /><span><?php _e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="ec_activate" type="radio" id="ec_activate_paypal_2" value="off"<?php if( isset( $acting_opts['ec_activate'] ) && $acting_opts['ec_activate'] == 'off' ) echo ' checked'; ?> /><span><?php _e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_ec_activate_paypal" class="explanation"><td colspan="2"><?php _e( 'Choose if to use PayPal Express Checkout.', 'usces' ); ?></td></tr>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_sandbox_paypal"><?php _e( 'Operation Environment', 'usces' ); ?></a></th>
				<td><label><input name="sandbox" class="ec_sandbox" type="radio" id="sandbox_paypal_1" value="1"<?php if( isset( $acting_opts['sandbox'] ) && $acting_opts['sandbox'] == 1 ) echo ' checked'; ?> /><span><?php _e( 'Test (Sandbox)', 'usces' ); ?></span></label><br />
					<label><input name="sandbox" class="ec_sandbox" type="radio" id="sandbox_paypal_2" value="2"<?php if( isset( $acting_opts['sandbox'] ) && $acting_opts['sandbox'] == 2 ) echo ' checked'; ?> /><span><?php _e( 'Formal Installment', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_sandbox_paypal" class="explanation paypal_ec_form"><td colspan="2"><?php _e( "Choose 'Test (Sandbox)' when testing payment settlement by Sandbox.", 'usces' ); ?></td></tr>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_user_paypal"><?php _e( 'API User Name', 'usces' ); ?></a></th>
				<td><input name="user" type="text" id="user_paypal" value="<?php echo esc_html( isset( $acting_opts['user'] ) ? $acting_opts['user'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_user_paypal" class="explanation paypal_ec_form"><td colspan="2"><?php _e( 'Type in the API user name from API credential. User name will be different in the formal installment of Sandbox.', 'usces' ); ?></td></tr>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_pwd_paypal"><?php _e( 'API Password', 'usces' ); ?></a></th>
				<td><input name="pwd" type="text" id="pwd_paypal" value="<?php echo esc_html( isset( $acting_opts['pwd'] ) ? $acting_opts['pwd'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_pwd_paypal" class="explanation paypal_ec_form"><td colspan="2"><?php _e( 'Type in the API password from API credential. Password will be different in formal installment of Sandbox.', 'usces' ); ?></td></tr>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_signature_paypal"><?php _e( 'Signature', 'usces' ); ?></a></th>
				<td><input name="signature" type="text" id="signature_paypal" value="<?php echo esc_html( isset( $acting_opts['signature'] ) ? $acting_opts['signature'] : '' ); ?>" class="regular-text" /><span id="get_paypal_signature"></span></td>
			</tr>
			<tr id="ex_signature_paypal" class="explanation paypal_ec_form"><td colspan="2"><?php _e( 'Type in the signature from API credential. Signature will be different in the formal installment of Sandbox.', 'usces' ); ?></td></tr>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_paypal_acount"><?php _e( 'PayPal Acount Email address', 'usces' ); ?></a></th>
				<td><input name="paypal_acount" type="text" id="acount_paypal" value="<?php echo esc_html( isset( $acting_opts['paypal_acount'] ) ? $acting_opts['paypal_acount'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_paypal_acount" class="explanation paypal_ec_form"><td colspan="2">PayPalアカウントに関連付けられているメールアドレス。</td></tr>
			<?php if( defined( 'WCEX_DLSELLER' ) ): ?>
			<tr class="paypal_ec_form">
				<th><a class="explanation-label" id="label_ex_continuation_paypal"><?php _e( 'Recurring Payment', 'usces' ); ?></a></th>
				<td><label><input name="continuation" type="radio" id="continuation_paypal_1" value="on"<?php if( isset( $acting_opts['continuation'] ) && $acting_opts['continuation'] == 'on' ) echo ' checked'; ?> /><span><?php _e('Use', 'usces' ); ?></span></label><br />
					<label><input name="continuation" type="radio" id="continuation_paypal_2" value="off"<?php if( isset( $acting_opts['continuation'] ) && $acting_opts['continuation'] == 'off' ) echo ' checked'; ?> /><span><?php _e('Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_continuation_paypal" class="explanation paypal_ec_form"><td colspan="2"><?php _e( 'It is a function that enables the automation of tedious payment settlement such as monthly membership fee that occurs regularly. <br /> For details, contact PayPal.', 'usces' ); ?></td></tr>
			<?php endif; ?>
		</table>
		<!--<table class="settle_table paypal_ec_form">
			<tr>
				<th><a class="explanation-label" id="label_ex_logoimg"><?php _e( 'URL for the image of the payment page', 'usces' ); ?></a></th>
				<td><input name="logoimg" type="text" id="logoimg" value="<?php echo esc_html(isset( $acting_opts['logoimg'] ) ? $acting_opts['logoimg'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_logoimg" class="explanation"><td colspan="2"><?php _e( 'a URL to an image of your logo. The image has a maximum size of 190 pixels wide by 60 pixels high. The available file format is jpg, png, gif. PayPal recommends that you provide an image that is stored on a secure (https) server. If you do not specify an image, the business name displays.', 'usces' ); ?><br /><?php _e('127 single-byte alphanumeric characters', 'usces' ); ?></td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_cartbordercolor"><?php _e( 'The background color for the payment page', 'usces' ); ?></a></th>
				<td><label><input name="set_cartbordercolor" type="radio" id="set_cartbordercolor_1" value="on"<?php if( isset( $acting_opts['set_cartbordercolor'] ) && $acting_opts['set_cartbordercolor'] == 'on' ) echo ' checked'; ?> /><span><?php _e('Set', 'usces' ); ?></span></label><br />
					<label><input name="set_cartbordercolor" type="radio" id="set_cartbordercolor_2" value="off"<?php if( isset( $acting_opts['set_cartbordercolor'] ) && $acting_opts['set_cartbordercolor'] == 'off' ) echo ' checked'; ?> /><span><?php _e('Not set', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_cartbordercolor" class="explanation"><td colspan="2"><?php _e( 'Your principal identifying color. PayPal blends your color to white in a gradient fill that borders the cart review area.', 'usces' ); ?><br /><?php _e( '6-character HTML hexadecimal ASCII color code', 'usces' ); ?></td></tr>
			<tr id="cartbordercolor">
				<th><?php _e( 'The background color for the payment page', 'usces' ); ?></th>
				<td>#<input name="cartbordercolor" type="text" value="<?php echo esc_html( isset( $acting_opts['cartbordercolor'] ) ? $acting_opts['cartbordercolor'] : '' ); ?>" class="regular-text" class="color" /></td>
			</tr>
		</table>-->
		<table class="settle_table paypal_ec_form">
			<tr>
				<th><a class="explanation-label" id="label_ex_loginwithpaypal"><?php _e( 'Log In with PayPal', 'usces' ); ?></a></th>
				<td><label><input name="set_liwp" type="radio" id="set_liwp_1" value="on"<?php if( isset( $acting_opts['set_liwp'] ) && $acting_opts['set_liwp'] == 'on' ) echo ' checked'; ?> /><span><?php _e( 'Use', 'usces' ); ?></span></label><br />
					<label><input name="set_liwp" type="radio" id="set_liwp_2" value="off"<?php if( isset( $acting_opts['set_liwp'] ) && $acting_opts['set_liwp'] == 'off' ) echo ' checked'; ?> /><span><?php _e( 'Do not Use', 'usces' ); ?></span></label>
				</td>
			</tr>
			<tr id="ex_loginwithpaypal" class="explanation"><td colspan="2">Paypal のログインと Welcart のログインを連携させます。</td></tr>
			<tr class="paypal_ec_form_login_with_paypal">
				<th><a class="explanation-label" id="label_ex_liwp_client_id"><?php _e( 'Live Client ID', 'usces' ); ?></a></th>
				<td><input name="liwp_client_id" type="text" id="liwp_client_id" value="<?php echo esc_html( isset( $acting_opts['liwp_client_id'] ) ? $acting_opts['liwp_client_id'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_liwp_client_id" class="explanation paypal_ec_form_login_with_paypal"><td colspan="2">REST API apps の Client ID</td></tr>
			<tr class="paypal_ec_form_login_with_paypal">
				<th><a class="explanation-label" id="label_ex_liwp_secret"><?php _e( 'Live Secret', 'usces' ); ?></a></th>
				<td><input name="liwp_secret" type="text" id="liwp_secret" value="<?php echo esc_html( isset( $acting_opts['liwp_secret'] ) ? $acting_opts['liwp_secret'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_liwp_secret" class="explanation paypal_ec_form_login_with_paypal"><td colspan="2">REST API apps の Secret</td></tr>
			<tr class="paypal_ec_form_login_with_paypal">
				<th><a class="explanation-label" id="label_ex_liwp_client_id_sand"><?php _e( 'SandBox Client ID', 'usces' ); ?></a></th>
				<td><input name="liwp_client_id_sand" type="text" id="liwp_client_id_sand" value="<?php echo esc_html( isset( $acting_opts['liwp_client_id_sand'] ) ? $acting_opts['liwp_client_id_sand'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_liwp_client_id_sand" class="explanation paypal_ec_form_login_with_paypal"><td colspan="2">REST API apps の SandBox用 Client ID</td></tr>
			<tr class="paypal_ec_form_login_with_paypal">
				<th><a class="explanation-label" id="label_ex_liwp_secret_sand"><?php _e( 'SandBox Secret', 'usces' ); ?></a></th>
				<td><input name="liwp_secret_sand" type="text" id="liwp_secret_sand" value="<?php echo esc_html( isset( $acting_opts['liwp_secret_sand'] ) ? $acting_opts['liwp_secret_sand'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_liwp_secret_sand" class="explanation paypal_ec_form_login_with_paypal"><td colspan="2">REST API apps の SandBox用 Secret</td></tr>
			<tr class="paypal_ec_form_login_with_paypal">
				<th></th>
				<td>REST API apps の登録は<a href="https://developer.paypal.com/developer" target="_blank">こちら</a>から行えます。</td>
			</tr>
		</table>
		<input name="acting" type="hidden" value="paypal" />
		<input name="usces_option_update" id="paypal_ec" type="submit" class="button button-primary" value="<?php _e( 'Update PayPal Express Checkout settings', 'usces' ); ?>" />
		<span class="paypal_ec_form_agree"><input name="agree_paypal_ec" id="agree_paypal_ec" type="checkbox" value="agree"<?php if( isset( $acting_opts['agree'] ) && 'agree' == $acting_opts['agree'] ) echo ' checked="checked"'; ?> /><label for="agree_paypal_ec">下記ご利用条件に同意する</label></span>
		<p class="agree_paypal_exp paypal_ec_form_agree">お申込みの際に送信いただいたお客様の情報は、提携会社である PayPal Pte. Ltd. に提供され、同社のサービス評価、改善、向上およびマーケティング目的のため使用されること、また、同社からお客様に対してマーケティング及びキャンペーンの目的のご案内（Ｅメール等の送信を含みます）が行われる場合があることにご同意頂きます。</p>
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php _e( 'PayPal Express Checkout', 'usces' ); ?></strong></p>
		<a href="https://www.paypal.com/jp/webapps/mpp/lp/partner" target="_blank"><?php _e( 'For the details on PayPal Express Checkout, click here >>', 'usces' ); ?></a>
		<p>PayPalエクスプレスチェックアウト決済サービスの利用には、ペイパルビジネスアカウントが必要です。ビジネスアカウントの開設は<a href="https://ad.doubleclick.net/ddm/clk/411330218;212055400;d" target="_blank">こちら</a>から行えます。</p>
		<p>ビジネスアカウントの開設手順は<a href="https://www.paypal.com/jp/webapps/mpp/merchant/how-to-signup-business" target="_blank">こちら</a>をご覧ください。</p>
		<p><a href="https://www.paypal.com/jp/webapps/mpp/support/kyc-corp" target="_blank">ビジネスアカウントの本人確認書類の提出について</a></p>
		<p><a href="https://www.welcart.com/documents/manual-2/%E3%82%AF%E3%83%AC%E3%82%B8%E3%83%83%E3%83%88%E6%B1%BA%E6%B8%88%E8%A8%AD%E5%AE%9A#paypal_ec" target="_blank">オンラインマニュアル</a></p>
		<p><?php _e( "If the 'OpenSSL' module is not installed in the server you're using, you cannot settle payments by 'ExpressCheckout'.", 'usces' ); ?></p>
		<p>問い合わせ先<br />
新規お申込み・導入に関するお問い合わせ（営業窓口）<br />
Tel：03-6739-7135 平日 9:30 - 18:00（土・日・祝祭日は除く）※通話料がかかります<br />
E-mail：wpp@paypal.com</p>
<p>すでにペイパルアカウントをお持ちの方（カスタマーサービス）<br />
Tel：0120-271-888 または 03-6739-7360（携帯電話と海外からはこちら ※通話料がかかります）<br />
9:00～20:00（年中無休）</p>
	</div>
	</div><!--uscestabs_paypal_ec-->
<?php
		endif;
	}

	/**
	 * @fook   usces_after_cart_instant
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function acting_main() {
		global $usces;

		$usces->paypal = new usces_paypal();
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

		$html = $this->purchase_form( $rand, false );
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

		if( ( isset( $_REQUEST['acting'] ) && 'paypal_ec' == $_REQUEST['acting'] ) && isset( $_REQUEST['token'] ) ) {
			if( isset( $results['settlement_id'] ) ) {
				$usces->set_order_meta_value( 'wc_trans_id', $results['settlement_id'], $order_id );
			} elseif( isset( $results['profile_id'] ) ) {
				$usces->set_order_meta_value( 'wc_trans_id', $results['profile_id'], $order_id );
			}
		}
	}

	/**
	 * 購入完了メッセージ
	 * @fook   usces_filter_completion_settlement_message
	 * @param  $html, $usces_entries
	 * @return string $html
	 */
	public function completion_settlement_message( $html, $usces_entries ) {
		global $usces;

		if( isset( $usces->payment_results['mc_gross'] ) ) {
			$html .= '<div id="status_table"><h5>PayPal</h5>'."\n";
			$html .= '<table>'."\n";
			$html .= '<tr><th>'.__( 'Purchase date', 'usces' ).'</th><td>' . esc_html( $usces->payment_results['payment_date'] ) . "</td></tr>\n";
			$html .= '<tr><th>'.__( 'Status', 'usces' ).'</th><td>' . esc_html( $usces->payment_results['payment_status'] ) . "</td></tr>\n";
			$html .= '<tr><th>'.__( 'Full name', 'usces' ).'</th><td>' . esc_html( $usces->payment_results['first_name'] ) . esc_html( $usces->payment_results['last_name'] ) . "</td></tr>\n";
			$html .= '<tr><th>'.__( 'e-mail', 'usces' ).'</th><td>' . esc_html( $usces->payment_results['payer_email'] ) . "</td></tr>\n";
			$html .= '<tr><th>'.__( 'Items','usces' ).'</th><td>' . esc_html( $usces->payment_results['item_name'] ) . "</td></tr>\n";
			$html .= '<tr><th>'.__( 'Payment amount', 'usces' ).'</th><td>' . esc_html( $usces->payment_results['mc_gross'] ) . "</td></tr>\n";
			$html .= '</table>';

			if( $usces->payment_results['payment_status'] != 'Completed' ) {
				$html .= __( '<p>The settlement is not completed.<br />Please remit the price from the PayPal Maia count page.After receipt of money confirmation, I will prepare for the article shipment.</p>', 'usces' ) . "\n";
			}
			$html .= "</div>\n";
		}
		return $html;
	}

	/**
	 * @fook   init
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function add_stylesheet() {
		global $usces;

		if( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
			$jquery_usces_paypal_style_url = USCES_FRONT_PLUGIN_URL.'/css/usces_paypal_style.css';
			wp_register_style( 'usces_paypal_style', $jquery_usces_paypal_style_url );
			wp_enqueue_style( 'jquery-ui-style' );
			wp_enqueue_style( 'usces_paypal_style' );
		}
	}

	/**
	 * @fook   usces_after_main
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function add_script() {
		global $usces;

		if( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_style( 'jquery-ui-dialog-min-css', includes_url() . 'css/jquery-ui-dialog.min.css' );
		}
	}

	/**
	 * @fook   usces_front_ajax
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function front_ajax() {
		switch( $_POST['usces_ajax_action'] ) {
		case 'paypal_delivery_method':
			$this->delivery_method( $_POST['selected'], $_POST['delivery_date'], $_POST['delivery_time'] );
			break;
		case 'paypal_use_point':
			$this->use_point( $_POST['usepoint'], $_POST['total_price'], $_POST['item_price'], $_POST['tax'] );
			break;
		case 'paypal_delivery_date_select':
			$this->delivery_date_select( $_POST['selected'] );
			break;
		case 'paypal_delivery_time_select':
			$this->delivery_time_select( $_POST['selected'] );
			break;
		}
	}

	/**
	 * @fook   usces_filter_uscesL10n
	 * @param  $l10n $post_id
	 * @return string $l10n
	 */
	public function set_uscesL10n( $l10n, $post_id ) {
		global $usces;

		if( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
			$l10n .= "'frontAjaxUrl': '".USCES_SSL_URL."',\n";
		}
		return $l10n;
	}

	/**
	 * @fook   usces_action_cart_page_footer
	 * @param  -
	 * @echo   cart_page_footer()
	 */
	public function e_cart_page_footer() {
		$footer = $this->cart_page_footer( '' );
		echo $footer;
	}

	/**
	 * @fook   usces_filter_cartContent
	 * @param  $html
	 * @return string $html
	 */
	public function cart_page_footer( $html ) {
		global $usces;

		//$html = '';
		if( !usces_is_cart_page() ) return $html;
		$member = $usces->get_member();
		if( !$this->set_session( $member['ID'] ) ) return $html;
		if( false === $usces->cart->num_row() ) return $html;
		if( usces_have_regular_order() ) return $html;
		if( !usces_have_shipped() ) return $html;

		$usces_entries = $usces->cart->get_entry();
		$usces->set_cart_fees( $member, $usces_entries );
		$checkout_button = usces_paypal_checkout_button();

		$html .= '
		<div class="send"><input type="image" src="'.$checkout_button.'" border="0" class="paypal_button" id="paypal_button" alt="PayPal" /></div>
		<div id="paypal_dialog">
			<div id="paypal_confirm">'.$this->confirm_form().'</div>
			<div id="paypal_shipping">'.$this->shipping_form().'</div>
			<div id="paypal_point">'.$this->point_form( $member['point'] ).'</div>
			<div id="paypal_purchase">'.$this->purchase_form().'</div>
			<div class="send"><input name="paypal_close" type="button" id="paypal_close" class="back_to_delivery_button" value="'.__( 'Cancel', 'usces' ).'" /></div>
		</div>';
		return $html;
	}

	/**
	 * @fook   usces_action_customerinfo
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function customerinfo() {
		global $usces;

		if( $usces->is_member_logged_in() ) {
			$usces->cart->set_order_entry( array( 'payment_name' => '' ) );
		}
	}

	/**
	 * @fook   wp_print_footer_scripts
	 * @param  -
	 * @return -
	 * @echo   -
	 */
	public function footer_scripts() {
		global $usces;

		if( !usces_is_cart_page() ) return;
		$member = $usces->get_member();
		if( !$this->set_session( $member['ID'] ) ) return;
		if( false === $usces->cart->num_row() ) return;
		if( usces_have_regular_order() ) return;
		if( !usces_have_shipped() ) return;

		$usces_entries = $usces->cart->get_entry();
		$total_price = 0;
		$item_price = 0;
		$tax = 0;
		if ( ! empty( $usces_entries['order']['total_items_price'] ) ) {
			$total_price += $usces_entries['order']['total_items_price'];
			$item_price += $usces_entries['order']['total_items_price'];
		}
		if ( ! empty( $usces_entries['order']['discount'] ) ) {
			$total_price += $usces_entries['order']['discount'];
			$item_price += $usces_entries['order']['discount'];
		}
		if ( ! empty( $usces_entries['order']['shipping_charge'] ) ) {
			$total_price += $usces_entries['order']['shipping_charge'];
		}
		if ( ! empty( $usces_entries['order']['cod_fee'] ) ) {
			$total_price += $usces_entries['order']['cod_fee'];
		}
		if ( ! empty( $usces_entries['order']['tax'] ) ) {
			$total_price += $usces_entries['order']['tax'];
			$tax = $usces_entries['order']['tax'];
		}
		if( $total_price < 0 ) $total_price = 0;
		if( $item_price < 0 ) $item_price = 0;
?>
<script type="text/javascript">
jQuery( function( $ ) {
	paypalfunc = {
		settings: {
			url: uscesL10n.frontAjaxUrl + "/",
			type: "POST",
			cache: false
		},
		deliveryMethodSelect: function() {
			var s = this.settings;
			s.data = {
				usces_ajax_action: 'paypal_delivery_method',
				selected: $( "#delivery_method_select option:selected" ).val(),
				delivery_date: $( "#delivery_date_select" ).val(),
				delivery_time: $( "#delivery_time_select" ).val()
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				if( data.status == "error" ) {
					$( "#paypal_error_message_delivery_method" ).html( data.message );
				} else {
					$( "#paypal_error_message_delivery_method" ).empty();
					$( "#paypal_confirm" ).empty();
					$( "#paypal_purchase" ).empty();
					$( "#paypal_confirm" ).html( data.confirm_form );
					$( "#paypal_purchase" ).html( data.purchase_form );
					$( "#delivery_date_select" ).bind( "change", function(){ paypalfunc.deliveryDateSelect(); });
					$( "#delivery_time_select" ).bind( "change", function(){ paypalfunc.deliveryTimeSelect(); });
					$( "input[name='delivery_method']" ).val( $( "#delivery_method_select option:selected" ).val() );
				}
			}).fail(function( res ){
				console.log( res );
			})
			return false;
		},
		deliveryDateSelect: function() {
			var s = this.settings;
			s.data = {
				usces_ajax_action: 'paypal_delivery_date_select',
				selected: $( "#delivery_date_select option:selected" ).val()
			}
			$.ajax( s ).done(function( res ) {
				$( "input[name='delivery_date']" ).val( $( "#delivery_date_select option:selected" ).val() );
			}).fail(function( res ){
				console.log(res);
			});
			return false;
		},
		deliveryTimeSelect: function() {
			var s = this.settings;
			s.data = {
				usces_ajax_action: 'paypal_delivery_time_select',
				selected: $( "#delivery_time_select option:selected" ).val()
			}
			$.ajax( s ).done(function( res ) {
				$( "input[name='delivery_time']" ).val( $( "#delivery_time option:selected" ).val() );
			}).fail(function( res ){
				console.log(res);
			})
			return false;
		},
		usePoint: function() {
			var s = this.settings;
			s.data = {
				usces_ajax_action: 'paypal_use_point',
				usepoint: $( "#set_usedpoint" ).val(),
				total_price: <?php echo $total_price; ?>,
				item_price: <?php echo $item_price; ?>,
				tax: <?php echo $tax; ?>
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				if( data.status == "error" ) {
					$( "#paypal_error_message_use_point" ).html( data.message );
				} else {
					$( "#paypal_error_message_use_point" ).empty();
					$( "#paypal_confirm" ).empty();
					$( "#paypal_purchase" ).empty();
					$( "#paypal_confirm" ).html( data.confirm_form );
					$( "#paypal_purchase" ).html( data.purchase_form );
					$( "#delivery_date_select" ).bind( "change", function(){ paypalfunc.deliveryDateSelect(); });
					$( "#delivery_time_select" ).bind( "change", function(){ paypalfunc.deliveryTimeSelect(); });
					$( "#usedpoint" ).val( $( "#set_usedpoint" ).val() );
				}
			}).fail(function( res ){
				console.log(res);
			})
			return false;
		}
	};

	$( "#paypal_dialog" ).dialog({
		bgiframe: true,
		autoOpen: false,
		height: "auto",
		width: 400,
		resizable: true,
		modal: true,
		open: function( event, ui ) {
			$( ".ui-dialog-titlebar", ui.panel ).hide();
		}
	});

	$( document ).on( "click", "#paypal_button", function() {
		$( "#paypal_dialog" ).dialog( "open" );
		$( "input[name='delivery_method']" ).prop( "selectedIndex", 0 );
		$( "input[name='delivery_date']" ).prop( "selectedIndex", 0 );
		$( "input[name='delivery_time']" ).prop( "selectedIndex", 0 );
	});

	$( document ).on( "click", "#paypal_close", function() {
		$( "#paypal_dialog" ).dialog( "close" );
	});

	if( $( "#delivery_method_select option" ).length > 1 ) {
		$( "#delivery_method_select" ).bind( "change", function(){ paypalfunc.deliveryMethodSelect(); });
	}
	$( "#delivery_date_select" ).bind( "change", function(){ paypalfunc.deliveryDateSelect(); });
	$( "#delivery_time_select" ).bind( "change", function(){ paypalfunc.deliveryTimeSelect(); });
	if( $( "#paypal_use_point" ) != undefined ) {
		$( "#paypal_use_point" ).bind( "click", function(){ paypalfunc.usePoint(); });
	}

	$( "#paypal_checkout" ).click( function( e ) {
		$( "input[name='delivery_method']" ).val( $( "#delivery_method_select option:selected" ).val() );
		$( "input[name='delivery_date']" ).val( $( "#delivery_date_select option:selected" ).val() );
		$( "input[name='delivery_time']" ).val( $( "#delivery_time option:selected" ).val() );
		$( "#purchase_form" ).submit();
		return true;
	});
});
</script>
	<?php
	}

	function purchase_validate() {
		global $usces;

		if( isset( $_POST['paypal_from_cart'] ) ) {
			$usces_entries = $usces->cart->get_entry();
			if( WCUtils::is_blank( $usces_entries['order']['delivery_method'] ) && isset( $_POST['delivery_method'] ) ) {
				$usces->cart->set_order_entry( array( 'delivery_method' => $_POST['delivery_method'] ) );
			}
			if( WCUtils::is_blank( $usces_entries['order']['delivery_date'] ) && isset( $_POST['delivery_date'] ) ) {
				$usces->cart->set_order_entry( array( 'delivery_date' => $_POST['delivery_date'] ) );
			}
			if( WCUtils::is_blank( $usces_entries['order']['delivery_time'] ) && isset( $_POST['delivery_time'] ) ) {
				$usces->cart->set_order_entry( array( 'delivery_time' => $_POST['delivery_time'] ) );
			}
		}
	}

	/**
	 * カートページ・セッション
	 * @param  $member_id ($uscesid)
	 * @return boolean
	 */
	function set_session( $member_id, $uscesid = NULL ) {
		global $usces, $wpdb;

		$member_table = usces_get_tablename( 'usces_member' );
		$query = $wpdb->prepare( "SELECT * FROM $member_table WHERE ID = %d", $member_id );
		$member = $wpdb->get_row( $query, ARRAY_A );
		if( empty( $member ) ) return false;

		$_SESSION['usces_member']['ID'] = $member['ID'];
		$_SESSION['usces_member']['mailaddress1'] = $member['mem_email'];
		$_SESSION['usces_member']['mailaddress2'] = $member['mem_email'];
		$_SESSION['usces_member']['point'] = $member['mem_point'];
		$_SESSION['usces_member']['name1'] = $member['mem_name1'];
		$_SESSION['usces_member']['name2'] = $member['mem_name2'];
		$_SESSION['usces_member']['name3'] = $member['mem_name3'];
		$_SESSION['usces_member']['name4'] = $member['mem_name4'];
		$_SESSION['usces_member']['zipcode'] = $member['mem_zip'];
		$_SESSION['usces_member']['pref'] = $member['mem_pref'];
		$_SESSION['usces_member']['address1'] = $member['mem_address1'];
		$_SESSION['usces_member']['address2'] = $member['mem_address2'];
		$_SESSION['usces_member']['address3'] = $member['mem_address3'];
		$_SESSION['usces_member']['tel'] = $member['mem_tel'];
		$_SESSION['usces_member']['fax'] = $member['mem_fax'];
		$_SESSION['usces_member']['delivery_flag'] = $member['mem_delivery_flag'];
		$_SESSION['usces_member']['delivery'] = ( !empty( $member['mem_delivery'] ) ) ? unserialize( $member['mem_delivery'] ) : '';
		$_SESSION['usces_member']['registered'] = $member['mem_registered'];
		$_SESSION['usces_member']['nicename'] = $member['mem_nicename'];
		$_SESSION['usces_member']['country'] = $usces->get_member_meta_value( 'customer_country', $member['ID'] );
		$_SESSION['usces_member']['status'] = $member['mem_status'];
		$usces->set_session_custom_member( $member['ID'] );

		foreach( $_SESSION['usces_member'] as $key => $value ) {
			if( 'custom_member' == $key ) {
				foreach( $_SESSION['usces_member']['custom_member'] as $mbkey => $mbvalue ) {
					if( is_array( $mbvalue ) ) {
						foreach( $mbvalue as $k => $v ) {
							$_SESSION['usces_entry']['custom_customer'][$mbkey][$v] = $v;
						}
					} else {
						$_SESSION['usces_entry']['custom_customer'][$mbkey] = $mbvalue;
					}
				}
			} else {
				if( 'country' == $key and empty( $value ) ) {
					$_SESSION['usces_entry']['customer'][$key] = usces_get_base_country();
				} else {
					$_SESSION['usces_entry']['customer'][$key] = trim( $value );
				}
			}
		}
		$delivery_flag = ( isset( $_SESSION['usces_entry']['delivery']['delivery_flag'] ) ) ? (int)$_SESSION['usces_entry']['delivery']['delivery_flag'] : 0;
		if( $delivery_flag == 0 ) {
			foreach( $_SESSION['usces_entry']['customer'] as $key => $value ) {
				if( 'country' == $key and empty( $value ) ) {
					$_SESSION['usces_entry']['delivery'][$key] = usces_get_base_country();
				} else {
					$_SESSION['usces_entry']['delivery'][$key] = trim( $value );
				}
			}
		}
		do_action( 'usces_action_paypal_set_session' );
		return true;
	}

	/**
	 * カートページ・内容確認フォーム
	 * @param  -
	 * @return string $html
	 */
	protected function confirm_form() {
		global $usces;

		$usces_entries = $usces->cart->get_entry();
		$html = '
				<table>
				<tr>
					<th>'.__( 'total items', 'usces' ).'</th>
					<td>'.usces_crform( $usces_entries['order']['total_items_price'], true, false, 'return', true ).'</td>
				</tr>';
		if( !empty( $usces_entries['order']['discount'] ) ) {
			$html .= '
				<tr>
					<th>'.apply_filters( 'usces_confirm_discount_label', __( 'Campaign discount', 'usces' ) ).'</th>
					<td>'.usces_crform( $usces_entries['order']['discount'], true, false, 'return', true ).'</td>
				</tr>';
		}
		if( usces_is_tax_display() && 'products' == usces_get_tax_target() ) {
			$html .= '
				<tr>
					<th>'.usces_tax_label( array(), 'return' ).'</th>
					<td>'.usces_tax( $usces_entries, 'return' ).'</td>
				</tr>';
		}
		if( usces_is_member_system() && usces_is_member_system_point() && !empty( $usces_entries['order']['usedpoint'] ) && 0 == usces_point_coverage() ) {
			$html .= '
				<tr>
					<th>'.__( 'Used points', 'usces' ).'</th>
					<td><span class="confirm_usedpoint">'.usces_crform( $usces_entries['order']['usedpoint'], false, false, 'return', true ).'</span></td>
				</tr>';
		}
		$html .= '
				<tr>
					<th>'.__( 'Shipping', 'usces' ).'</th>
					<td>'.usces_crform( $usces_entries['order']['shipping_charge'], true, false, 'return', true ).'</td>
				</tr>';
		if( !empty( $usces_entries['order']['cod_fee'] ) ) {
			$html .= '
				<tr>
					<th>'.apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ) ).'</th>
					<td>'.usces_crform( $usces_entries['order']['cod_fee'], true, false, 'return', true ).'</td>
				</tr>';
		}
		if( usces_is_tax_display() && 'all' == usces_get_tax_target() ) {
			$html .= '
				<tr>
					<th>'.usces_tax_label( array(), 'return' ).'</th>
					<td>'.usces_tax( $usces_entries, 'return' ).'</td>
				</tr>';
		}
		if( usces_is_member_system() && usces_is_member_system_point() && !empty( $usces_entries['order']['usedpoint'] ) && 1 == usces_point_coverage() ) {
			$html .= '
				<tr>
					<th>'.__( 'Used points', 'usces' ).'</th>
					<td><span class="confirm_usedpoint">'.usces_crform( $usces_entries['order']['usedpoint'], false, false, 'return', true ).'</span></td>
				</tr>';
		}
		$html .= '
				<tr>
					<th>'.__( 'Total Amount', 'usces' ).'</th>
					<td>'.usces_crform( $usces_entries['order']['total_full_price'], true, false, 'return', true ).'</td>
				</tr>
				</table>';
		return $html;
	}

	/**
	 * カートページ・発送支払方法フォーム
	 * @param  -
	 * @return string $html
	 */
	protected function shipping_form() {
		global $usces;

		$html = '';
		if( usces_have_shipped() ) {
			$usces_entries = $usces->cart->get_entry();
			$html = '
				<div class="error_message" id="paypal_error_message_delivery_method"></div>
				<table>
				<tr>
					<th>'.__( 'shipping option', 'usces' ).'</th>
					<td>'.usces_the_delivery_method( $usces_entries['order']['delivery_method'], 'return' ).'</td>
				</tr>
				<tr>
					<th>'.__( 'Delivery date', 'usces' ).'</th>
					<td>'.usces_the_delivery_date( $usces_entries['order']['delivery_date'], 'return' ).'</td>
				</tr>
				<tr>
					<th>'.__( 'Delivery Time', 'usces' ).'</th>
					<td>'.usces_the_delivery_time( $usces_entries['order']['delivery_time'], 'return' ).'</td>
				</tr>
				</table>';
		}
		return $html;
	}

	/**
	 * カートページ・ポイントフォーム
	 * @param  -
	 * @return string $html
	 */
	protected function point_form( $point ) {
		global $usces;

		$html = '';
		if( usces_is_member_system_point() ) {
			$usces_entries = $usces->cart->get_entry();
			$usedpoint = ( 0 < $usces_entries['order']['usedpoint'] ) ? $usces_entries['order']['usedpoint'] : '';
			$html = '
				<div class="error_message" id="paypal_error_message_use_point"></div>
				<table>
				<tr>
					<th>'.__( 'The current point', 'usces' ).'</th>
					<td><span class="point">'.$point.'</span>'.__( 'points', 'usces' ).'</td>
				</tr>
				<tr>
					<th>'.__( 'Points you are using here', 'usces' ).'</th>
					<td><input name="offer[usedpoint]" class="used_point" id="set_usedpoint" type="text" value="'.$usedpoint.'" />'.__( 'points', 'usces' ).'</td>
				</tr>
				<tr>
					<td colspan="2"><input name="use_point" type="button" class="use_point_button" id="paypal_use_point" value="'.__( 'Use the points', 'usces' ).'" /></td>
				</tr>
				</table>';
		}
		return $html;
	}

	/**
	 * カートページ・配送方法
	 * @param  $delivery_method_select $delivery_date $delivery_time
	 * @return ajax
	 */
	protected function delivery_method( $delivery_method_select, $delivery_date, $delivery_time ) {
		global $usces;

		$data = array( 'status' => 'ok', 'message' => '', 'confirm_form' => '', 'purchase_form' => '' );
		$member = $usces->get_member();
		$usces_entries = $usces->cart->get_entry();
		$usces->cart->set_order_entry( array( 'delivery_method' => $delivery_method_select, 'delivery_date' => $delivery_date, 'delivery_time' => $delivery_time ) );
		$usces_entries['order']['delivery_method'] = $delivery_method_select;
		$usces->set_cart_fees( $member, $usces_entries );
		$data['confirm_form'] = $this->confirm_form();
		$data['purchase_form'] = $this->purchase_form();
		wp_send_json( $data );
	}

	/**
	 * カートページ・使用ポイント
	 * @param  $usepoint $total_price $item_price $tax
	 * @return ajax
	 */
	protected function use_point( $usepoint, $total_price, $item_price, $tax ) {
		global $usces;

		$data = array( 'status' => 'ok', 'message' => '', 'confirm_form' => '', 'purchase_form' => '' );
		$member = $usces->get_member();
		$usces_entries = $usces->cart->get_entry();

		if( WCUtils::is_blank( $usepoint ) || !preg_match( "/^[0-9]+$/", $usepoint ) || (int)$usepoint < 0 ) {
			$mes = __( 'Invalid value. Please enter in the numbers.', 'usces' );
		} else {
			if( $usepoint > (int)$member['point'] ) {
				$mes = __( 'You have exceeded the maximum available.', 'usces' ).' '.__( 'Max', 'usces' ).' '.(int)$member['point'].' '.__( 'points', 'usces' );
			} else {
				if( 1 == usces_point_coverage() && $usepoint >= $total_price ) {
					$item_price = $total_price;
				} elseif( $usces->options['tax_target'] == 'products' ) {
					$item_price += $tax;
				}
				if( $usepoint > $item_price ) {
					$mes = __( "In the case of settlement method you choose, the upper limit of the point you'll find that will change. If you became a settlement error, please reduce the point that you want to use.", 'usces' );
				}
			}
		}

		if( '' != $mes ) {
			$usces->cart->set_order_entry( array( 'usedpoint' => 0 ) );
			$data['status'] = 'error';
			$data['message'] = $mes;
		} else {
			$usces->cart->set_order_entry( array( 'usedpoint' => $usepoint ) );
			$usces_entries['order']['usedpoint'] = $usepoint;
			$usces->set_cart_fees( $member, $usces_entries );
			$data['confirm_form'] = $this->confirm_form();
			$data['purchase_form'] = $this->purchase_form();
		}
		wp_send_json( $data );
	}

	/**
	 * カートページ・配送希望日
	 * @param  $selected
	 * @return ajax
	 */
	protected function delivery_date_select( $selected ) {
		global $usces;
		$usces->cart->set_order_entry( array( 'delivery_date' => $selected ) );
		die( "ok" );
	}

	/**
	 * カートページ・配送希望時間
	 * @param  $selected
	 * @return ajax
	 */
	protected function delivery_time_select( $selected ) {
		global $usces;
		$usces->cart->set_order_entry( array( 'delivery_time' => $selected ) );
		die( "ok" );
	}

	/**
	 * [注文する] ボタン
	 * @param  $rand $from_cart
	 * @return string $html
	 */
	protected function purchase_form( $rand = '', $from_cart = true ) {
		global $usces;

		if( empty( $rand ) ) {
			$rand = usces_acting_key();
		}
		$purchase_disabled = '';

		if( $from_cart ) {
			$payment_method = usces_get_system_option( 'usces_payment_method', 'name' );
			foreach( (array)$payment_method as $id => $payment ) {
				if( 'acting_paypal_ec' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$usces->cart->set_order_entry( array( 'payment_name' => $payment['name'] ) );
					break;
				}
			}
		}

		$usces_entries = $usces->cart->get_entry();
		$cart = $usces->cart->get_cart();

		usces_save_order_acting_data( $rand );
		$acting_opts = $this->get_acting_settings();
		$currency_code = $usces->get_currency_code();
		$have_shipped = usces_have_shipped( $cart );
		$multiple_shipping = ( isset( $usces_entries['delivery']['delivery_flag'] ) && 2 == $usces_entries['delivery']['delivery_flag'] ) ? true : false;
		$checkout_button = usces_paypal_checkout_button();

		$html = '<form id="purchase_form" action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
			<input type="hidden" name="SOLUTIONTYPE" value="Sole">
			<input type="hidden" name="LANDINGPAGE" value="Billing">
			<input type="hidden" name="EMAIL" value="'.esc_attr( $usces_entries['customer']['mailaddress1'] ).'">
			<input type="hidden" name="PAYMENTREQUEST_0_CURRENCYCODE" value="'.$currency_code.'">
			<input type="hidden" name="PAYMENTREQUEST_0_CUSTOM" value="'.$rand.'">';
		if( ( $have_shipped || usces_get_member_reinforcement() ) && !$multiple_shipping ) {
			$shipto = ( $have_shipped ) ? 'delivery' : 'customer';
			$name = apply_filters( 'usces_filter_paypalec_shiptoname', esc_attr( $usces_entries[$shipto]['name2'].' '.$usces_entries[$shipto]['name1'] ) );
			$address2 = apply_filters( 'usces_filter_paypalec_shiptostreet', esc_attr( $usces_entries[$shipto]['address2'] ) );
			$address3 = apply_filters( 'usces_filter_paypalec_shiptostreet2', esc_attr( $usces_entries[$shipto]['address3'] ) );
			$address1 = apply_filters( 'usces_filter_paypalec_shiptocity', esc_attr( $usces_entries[$shipto]['address1'] ) );
			$pref = apply_filters( 'usces_filter_paypalec_shiptostate', esc_attr( $usces_entries[$shipto]['pref'] ) );
			$country = ( !empty( $usces_entries[$shipto]['country'] ) ) ? $usces_entries[$shipto]['country'] : usces_get_base_country();
			$country_code = apply_filters( 'usces_filter_paypalec_shiptocountrycode', $country );
			if( $country_code == 'TW' ) {
				$city = $address1;
				$address1 = $pref;
				$pref = $city;
			}
			$zip = apply_filters( 'usces_filter_paypalec_shiptozip', $usces_entries[$shipto]['zipcode'] );
			$tel = apply_filters( 'usces_filter_paypalec_shiptophonenum', ltrim( str_replace( '-', '', $usces_entries[$shipto]['tel'] ), '0' ) );
			$html .= '
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTONAME" value="'.$name.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOSTREET" value="'.$address2.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOSTREET2" value="'.$address3.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOCITY" value="'.$address1.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOSTATE" value="'.$pref.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE" value="'.$country_code.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOZIP" value="'.$zip.'">
			<input type="hidden" name="PAYMENTREQUEST_0_SHIPTOPHONENUM" value="'.$tel.'">';
		}
		if( !$have_shipped || $multiple_shipping ) {
			$html .= '<input type="hidden" name="NOSHIPPING" value="1">';
		}
		if( !usces_have_continue_charge( $cart ) ) {
			//通常購入
			$item_total_price = 0;
			$i = 0;
			foreach( $cart as $cart_row ) {
				$html .= '
					<input type="hidden" name="L_PAYMENTREQUEST_0_NAME'.$i.'" value="'.esc_attr( $usces->getItemName( $cart_row['post_id'] ) ).'">
					<input type="hidden" name="L_PAYMENTREQUEST_0_AMT'.$i.'" value="'.usces_crform( $cart_row['price'], false, false, 'return', false ).'">
					<input type="hidden" name="L_PAYMENTREQUEST_0_NUMBER'.$i.'" value="'.esc_attr( $usces->getItemCode( $cart_row['post_id'] ) ).' '.esc_attr( urldecode( $cart_row['sku'] ) ).'">
					<input type="hidden" name="L_PAYMENTREQUEST_0_QTY'.$i.'" value="'.esc_attr( $cart_row['quantity'] ).'">';
				$options = ( isset( $cart_row['options'] ) && is_array( $cart_row['options'] ) ) ? $cart_row['options'] : array();
				if( count( $options ) > 0 ) {
					$optstr = '';
					foreach( $options as $key => $value ) {
						if( !empty( $key ) ) {
							$key = urldecode( $key );
							$value = maybe_unserialize( $value );
							if( is_array( $value ) ) {
								$c = '';
								$optstr .= $key.' : ';
								foreach( $value as $v ) {
									$optstr .= $c.urldecode( $v );
									$c = ', ';
								}
								$optstr .= "\r\n";
							} else {
								$optstr .= $key.' : '.urldecode( $value )."\r\n";
							}
						}
					}
					if( $optstr != '' ) {
						if( 60 < mb_strlen( $optstr, 'UTF-8' ) ) $optstr = mb_substr( $optstr, 0, 60, 'UTF-8' ).'...';
						$html .= '
							<input type="hidden" name="L_PAYMENTREQUEST_0_DESC'.$i.'" value="'.esc_attr( $optstr ).'">';
					}
				}
				$item_total_price += ( $cart_row['price'] * $cart_row['quantity'] );
				$i++;
			}
			if( !empty( $usces_entries['order']['discount'] ) ) {
				$html .= '
					<input type="hidden" name="L_PAYMENTREQUEST_0_NAME'.$i.'" value="'.esc_attr( __( 'Campaign discount', 'usces' ) ).'">
					<input type="hidden" name="L_PAYMENTREQUEST_0_AMT'.$i.'" value="'.usces_crform( $usces_entries['order']['discount'], false, false, 'return', false ).'">';
				$item_total_price += $usces_entries['order']['discount'];
				$i++;
			}
			if( !empty( $usces_entries['order']['usedpoint'] ) ) {
				$html .= '
					<input type="hidden" name="L_PAYMENTREQUEST_0_NAME'.$i.'" value="'.esc_attr( __( 'Used points', 'usces' ) ).'">
					<input type="hidden" name="L_PAYMENTREQUEST_0_AMT'.$i.'" value="'.usces_crform( $usces_entries['order']['usedpoint']*(-1), false, false, 'return', false ).'">';
				$item_total_price -= $usces_entries['order']['usedpoint'];
				$i++;
			}
			$html .= '
				<input type="hidden" name="PAYMENTREQUEST_0_ITEMAMT" value="'.usces_crform( $item_total_price, false, false, 'return', false ).'">
				<input type="hidden" name="PAYMENTREQUEST_0_SHIPPINGAMT" value="'.usces_crform( $usces_entries['order']['shipping_charge'], false, false, 'return', false ).'">
				<input type="hidden" name="PAYMENTREQUEST_0_AMT" value="'.usces_crform( $usces_entries['order']['total_full_price'], false, false, 'return', false ).'">
				';
			if( !empty( $usces_entries['order']['cod_fee'] ) ) $html .= '<input type="hidden" name="PAYMENTREQUEST_0_HANDLINGAMT" value="'.usces_crform( $usces_entries['order']['cod_fee'], false, false, 'return', false ).'">';
			if( !empty( $usces_entries['order']['tax'] ) ) $html .= '<input type="hidden" name="PAYMENTREQUEST_0_TAXAMT" value="'.usces_crform( $usces_entries['order']['tax'], false, false, 'return', false ).'">';
		} else {
			//定期支払い
			$desc = usces_make_agreement_description( $cart, $usces_entries['order']['total_full_price'] );
			$html .= '<input type="hidden" name="L_BILLINGTYPE0" value="RecurringPayments">
				<input type="hidden" name="L_BILLINGAGREEMENTDESCRIPTION0" value="'.esc_attr( $desc ).'">
				<input type="hidden" name="PAYMENTREQUEST_0_AMT" value="0">';
		}
		//if( !empty( $acting_opts['logoimg'] ) ) $html .= '<input type="hidden" name="LOGOIMG" value="'.esc_attr( $acting_opts['logoimg'] ).'">';
		//if( !empty( $acting_opts['cartbordercolor'] ) ) $html .= '<input type="hidden" name="CARTBORDERCOLOR" value="'.esc_attr( $acting_opts['cartbordercolor'] ).'">';
		$html .= '<input type="hidden" name="purchase" value="acting_paypal_ec">';
		if( $from_cart ) {
			$html .= '<div class="send"><input type="image" src="'.$checkout_button.'" id="paypal_checkout" border="0" alt="PayPal"'.apply_filters( 'usces_filter_confirm_nextbutton', NULL ).$purchase_disabled.' /></div>';
			$html .= '<input type="hidden" name="paypal_from_cart" value="1">';
			$html .= '<input type="hidden" name="delivery_method" value="">';
			$html .= '<input type="hidden" name="delivery_date" value="">';
			$html .= '<input type="hidden" name="delivery_time" value="">';
			$html .= '<input type="hidden" name="usedpoint" value="">';
			$noncekey = 'wc_purchase_nonce'.$usces->get_uscesid( false );
			$html .= wp_nonce_field( $noncekey, 'wc_nonce', false, false );
			$html .= '</form>';
		} else {
			$payments = usces_get_payments_by_name( $usces_entries['order']['payment_name'] );
			$acting_flg = 'acting_paypal_ec';
			$html .= '<div class="send"><input type="image" src="'.$checkout_button.'" border="0" name="submit" value="submit" alt="PayPal"'.apply_filters( 'usces_filter_confirm_nextbutton', NULL ).$purchase_disabled.' /></div>';
			$html .= '</form>';
			$html .= '<form action="'.USCES_CART_URL.'" method="post" onKeyDown="if(event.keyCode == 13){return false;}">
				<div class="send">
					'.apply_filters( 'usces_filter_confirm_before_backbutton', NULL, $payments, $acting_flg, $rand ).'
					<input name="backDelivery" type="submit" id="back_button" class="back_to_delivery_button" value="'.__( 'Back', 'usces' ).'"'.apply_filters( 'usces_filter_confirm_prebutton', NULL ).' />
				</div>';
		}
		return $html;
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

/**
 * PayPal Express Checkout API
 *
 * @class    usces_paypal
 */
class usces_paypal {

	var $options;

	var $API_UserName;
	var $API_Password;
	var $API_Signature;

	var $sBNCode;
	var $version;

	var $method;
	var $data;
	var $nvpreq;
	var $resArray;

	function __construct() {
		$this->options = get_option( 'usces' );
		$this->API_UserName = urlencode( $this->options['acting_settings']['paypal']['user'] );
		$this->API_Password = urlencode( $this->options['acting_settings']['paypal']['pwd'] );
		$this->API_Signature = urlencode( $this->options['acting_settings']['paypal']['signature'] );
		$this->sBNCode = urlencode( "uscons_cart_EC_JP" );
		$this->version = urlencode( "87.0" );
		$this->method = '';
		$this->data = '';
		$this->nvpreq = '';
		$this->resArray = array();
	}

	function setMethod( $method ) { $this->method = $method; }
	function setData( $data ) { $this->data = $data; }
	function getResponse() { return $this->resArray; }

	function doExpressCheckout() {
		$status = true;

		$this->nvpreq = "METHOD=".$this->method
			."&VERSION=".$this->version
			."&USER=".$this->API_UserName
			."&PWD=".$this->API_Password
			."&SIGNATURE=".$this->API_Signature
			.$this->data
			."&BUTTONSOURCE=".$this->sBNCode;

		if( extension_loaded( 'curl' ) ) {
			//setting the curl parameters.
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $this->options['acting_settings']['paypal']['api_endpoint'] );
			curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

			//turning off the server and peer verification(TrustManager Concept).
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );

			//setting the nvpreq as POST FIELD to curl
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->nvpreq );

			//getting response from server
			$response = curl_exec( $ch );

			if( curl_errno( $ch ) ) {
				usces_log( 'PayPal : API call failed. curl_error_no:['.curl_errno( $ch ).'] curl_error_msg:'.curl_error( $ch ), 'acting_transaction.log' );
				$status = false;

			} else {
				//closing the curl
				curl_close( $ch );
			}

			$this->resArray = $this->deformatNVP( $response );

		} else {
			$r = new usces_httpRequest( $this->options['acting_settings']['paypal']['api_host'], '/nvp', 'POST', true );
			$result = $r->connect( $this->nvpreq );
			if( $result >= 400 ) {
				usces_log( 'PayPal : API call failed. result:['.$result.']', 'acting_transaction.log' );
				$status = false;
			}

			$this->resArray = $this->deformatNVP( $r->getContent() );
		}
		return $status;
	}

	private function deformatNVP( $nvpstr ) {
		$intial = 0;
		$nvpArray = array();

		while( strlen( $nvpstr ) ) {
			//postion of Key
			$keypos = strpos( $nvpstr, '=' );
			//position of value
			$valuepos = strpos( $nvpstr, '&' ) ? strpos( $nvpstr, '&' ) : strlen( $nvpstr );

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval = substr( $nvpstr, $intial, $keypos );
			$valval = substr( $nvpstr, $keypos+1, $valuepos-$keypos-1 );
			//decoding the respose
			$nvpArray[urldecode( $keyval )] = urldecode( $valval );
			$nvpstr = substr( $nvpstr, $valuepos+1, strlen( $nvpstr ) );
		}
		return $nvpArray;
	}
}

/**
 * PayPal IPN Connection
 *
 */
function usces_paypal_ipn_check( $usces_paypal_url ) {

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';

	foreach( $_POST as $key => $value ) {
		$value = urlencode( stripslashes( $value ) );
		$req .= '&'.$key.'='.$value;
	}

	// post back to PayPal system to validate
	$header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Host: ".$usces_paypal_url."\r\n";
	$header .= "Content-Length: ".strlen( $req )."\r\n";
	$header .= "Connection: close\r\n\r\n";
	$fp = fsockopen( 'ssl://'.$usces_paypal_url, 443, $errno, $errstr, 30 );

	$order_id = isset( $_POST['custom'] ) ? $_POST['custom'] : '';
	$txn_id = isset( $_POST['txn_id'] ) ? $_POST['txn_id'] : '';

	$results = array();
	if( !$fp ) {
		$results[0] = false;
		usces_log( __( 'IPN Connection Error', 'usces' ), 'acting_transaction.log' );

	} else {
		fputs( $fp, $header.$req );
		// read the body data
		$res = '';
		$headerdone = false;
		while( !feof( $fp ) ) {
			$line = fgets( $fp, 1024 );
			if( strcmp( $line, "\r\n" ) == 0 ) {
				// read the header
				$headerdone = true;
			} elseif( $headerdone ) {
				// header has been read. now read the contents
				$res .= $line;
			}
		}

		// parse the data
		$lines = explode( "\n", $res );
		if( preg_match( "/VERIFIED/mi", $res ) == 1 ) {
			$results[0] = true;
			$results['order_id'] = $order_id;
			$results['txn_id'] = $txn_id;
			usces_log( 'IPN [SUCCESS]', 'acting_transaction.log' );

		} else {
			$results[0] = false;
		}

		fclose( $fp );
	}
	return $results;
}

/**
 * PayPal チェックアウトボタン
 * @return ボタン画像のURL
 */
function usces_paypal_checkout_button() {
	$checkout_button = ( USCES_JP ) ? "https://www.paypalobjects.com/ja_JP/JP/i/btn/btn_paynowCC_LG.gif" : "https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif";
	$checkout_button = apply_filters( 'usces_filter_paypalec_checkout_button', $checkout_button );
	return $checkout_button;
}

/**
 * 支払方法の説明書き
 * @param  array
 * @return array
 */
function usces_add_payment_method_paypal_explanation( $newvalue ) {
	if( USCES_JP ) {
		$newvalue['explanation'] .= "
<!-- PayPal Logo --><div class=\"paypal-logo-box\">
<div class=\"paypal-logo\"><a href=\"#\" onclick=\"javascript:window.open( 'https://www.paypal.com/jp/webapps/mpp/logo/about', 'olcwhatispaypal', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=900, height=700' );\"><img src=\"https://www.paypalobjects.com/digitalassets/c/website/marketing/apac/jp/developer/319x110_b.png\" border=\"0\" alt=\"ペイパル｜カード情報も、口座番号も、ペイパルが守ります。｜VISA, Mastercard, JCB, American Express, Union Pay, 銀行\"></a></div>
<div class=\"paypal-message\">カードでも銀行口座からでも、IDとパスワードでかんたん・安全にお支払い。新規登録・振込手数料も無料です。</div>
<div class=\"paypal-message\"><a href=\"https://www.paypal.com/jp/webapps/mpp/lp/about-paypal\" target=\"_blank\">ペイパルについて</a></div>
</div><!-- PayPal Logo -->";
	} else {
		$newvalue['explanation'] .= "
<!-- PayPal Logo --><div class=\"paypal-logo-box\">
<div class=\"paypal-logo\"><a href=\"#\" onclick=\"javascript:window.open( 'https://www.paypal.com/jp/webapps/mpp/logo/about-en', 'olcwhatispaypal', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=900, height=700' );\"><img src=\"https://www.paypalobjects.com/digitalassets/c/website/marketing/apac/jp/developer/CBT_logo_203_100.png\" border=\"0\" alt=\"PayPal|Mastercard,VISA,American Express,JCB\"></a></div>
</div><!-- PayPal Logo -->";
	}
	return $newvalue;
}

/**
 * かんたん銀行決済を支払方法に追加
 * @param  array
 * @return index
 */
function usces_add_payment_method_paypal_bank( $newvalue ) {
	$newvalue['name'] = 'PayPal（かんたん銀行決済）';
	$newvalue['explanation'] = "
<div class=\"paypal-logo-box\">
<div class=\"paypal-message\">銀行口座からのお支払いでも、一度設定すれば素早くかんたん。新規登録・振込手数料も無料です。<br />※ご利用可能な銀行は、みずほ銀行、三井住友銀行、三菱UFJ銀行、ゆうちょ銀行、りそな銀行・埼玉りそな銀行です。</div>
<div class=\"paypal-message\"><a href=\"https://www.paypal.com/jp/webapps/mpp/lp/set-up-bank\" target=\"_blank\">銀行口座のご利用について</a></div>
</div>";
	$lid = usces_add_system_option( 'usces_payment_method', $newvalue );
	return $lid;
}

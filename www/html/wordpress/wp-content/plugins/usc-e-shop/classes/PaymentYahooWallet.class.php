<?php
/*
Yahoo Wallet Settlement module
Version: 1.0.1
Author: Collne Inc.

*/

class YAHOOWALLET_SETTLEMENT
{
	private $error_mes, $pay_method;
	
	public function __construct(){
	
		$this->pay_method = array(
			'acting_yahoo_wallet'
		);

		self::set_available_settlement();
	
		if( is_admin() ){
		
			add_action( 'usces_action_settlement_tab_title', array( $this, 'tab_title') );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'tab_body') );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'data_update') );
			add_filter( 'usces_filter_settle_info_field_keys', array( $this, 'settle_info_field_keys') );
			
		}else{
		
			add_filter( 'usces_filter_reg_orderdata_status', array( $this, 'reg_orderdata_status'), 10, 2 );
			add_action( 'usces_action_cartcompletion_page_body', array( $this, 'cartcompletion_page_body'), 10, 2 );
			add_action( 'init', array( $this, 'settlement_process') );
			add_action( 'usces_post_reg_orderdata', array( $this, 'reg_orderdata'), 10, 2 );
			add_filter( 'usces_filter_send_order_mail_payment', array( $this, 'order_mail_payment'), 10, 6 );

		}
	}

	/**********************************************
	* usces_filter_available_settlement
	* @param  -
	* @return -
	***********************************************/
	private function set_available_settlement() {
		$available_settlement = get_option( 'usces_available_settlement' );
		if( !in_array( 'yahoo', $available_settlement ) ) {
			$available_settlement['yahoo'] = 'Yahoo!ウォレット';
			update_option( 'usces_available_settlement', $available_settlement );
		}
	}

	/***************************************************************************/
	// 決済処理
	/***************************************************************************/
	public function settlement_process(){
		
		if( isset($_REQUEST['yahoo']) && 'conf' == $_REQUEST['yahoo'] ){
			$this->yahoo_responce();
		}
	}

	public function reg_orderdata( $order_id, $results ){
		global $usces;
		$usces_entries = $usces->cart->get_entry();
		$payments = $usces->getPayments($usces_entries['order']['payment_name']);
		if( $order_id && 'acting_yahoo_wallet' == $payments['settlement'] ){
			$this->yahoo_settlement( $order_id );
		}
	
	}

	public function order_mail_payment( $msg_payment, $order_id, $payment, $cart, $entry, $data){
		if( 'acting_yahoo_wallet' != $payment['settlement'] )
			return $msg_payment;
			
		global $usces;
		$sett_url = $usces->get_order_meta_value( 'redirect_order_url', $data['ID']);
		$expire_time = $usces->get_order_meta_value( 'url_expire', $data['ID']);
		if( ! $sett_url )
			return $msg_payment;
			
		$mes = "※ まだYahooウォレットでのお支払いがお済でない場合は、\r\n";
		$mes .= "こちらの「お支払いURL」をクリックすることで決済を行うことができます。\r\n";
		$mes .= "お支払いの有効期限は " . $expire_time . " までとなっております。\r\n";
		$mes .= "有効期限が過ぎた場合は、ご注文はキャンセルとなりますのでご注意ください。\r\n\r\n";
		$mes .= "【お支払いURL】\r\n";
		$mes .= $sett_url . "\r\n\r\n";

		return $msg_payment . $mes;
	}

	/***************************************************************************/
	// 未入金ステータス追加
	/***************************************************************************/
	public function reg_orderdata_status($status, $entry ){
		global $usces;

		$payments = $usces->getPayments($entry['order']['payment_name']);
		if( 'acting_yahoo_wallet' == $payments['settlement'] ){
			return 'noreceipt';
		}
		
		return $status;
	}

	/***************************************************************************/
	// acting meta データ用キー
	/***************************************************************************/
	public function settle_info_field_keys( $keys ){
		$keys[] = 'yahoo_wallet_device';
		return $keys;
	}

	/***************************************************************************/
	// Welcart注文確定ページ
	/***************************************************************************/
	public function cartcompletion_page_body( $usces_entries, $usces_carts ){
		$payments = usces_get_payments_by_name($usces_entries['order']['payment_name']);
		if( 'acting_yahoo_wallet' != $payments['settlement'] )
			return;
		
		global $usces;
		$sett_url = $usces->get_order_meta_value( 'redirect_order_url', $usces_entries['order']['ID']);
		$expire_time = $usces->get_order_meta_value( 'url_expire', $usces_entries['order']['ID']);
		$message = '<div class="acting_message">
		<p>お支払い手続きに入ります。下記の「Yahoo!ウォレット決済手続きへ」をクリックしてください。<br />お支払いの<span class="expire_notice">有効期限は ' . $expire_time . ' まで</span>となっております。有効期限が過ぎた場合は、ご注文はキャンセルとなりますのでご注意ください。<br>なお、お支払いのためのURLは、ご注文確認メールにも記載されています。<p>
		<p class="acting_link"><a href="' . $sett_url . '">Yahoo!ウォレット決済手続きへ</a><p>
		</div>';
		echo $message;
	}

	/***************************************************************************/
	// YahooリダイレクトURLの要求とリダイレクト
	/***************************************************************************/
	public function yahoo_settlement($order_id){
		global $usces;

		if ( !$order_id )
			die('NG verify2');
		
		$usces_opt = get_option('usces');
		$order = $usces->get_order_data($order_id, 'direct' );
		if ( !$order )
			die('NG verify5');
		
		$expire = isset($options['acting_settings']['yahoo']['expire']) ? (int)$options['acting_settings']['yahoo']['expire'] : 10;
		$order_done_url = home_url('/?yahoo=done');
		$expire_time = date('Y-m-d H:i:s', current_time('timestamp')+(60*60*$expire));
		$date_time = date('Y-m-d\TH:i:s+09:00', current_time('timestamp')+(60*60*$expire));
		
		if( 'public' == $usces_opt['acting_settings']['yahoo']['ope'] ){
			$confirmations_url = str_replace( 'http://', 'https://', home_url('/?yahoo=conf') );
		}else{
			$confirmations_url = home_url('/?yahoo=conf');
		}
		
		$cart = unserialize($order['order_cart']);
		$shipping_charge = $order['order_shipping_charge'];
		$tax = $order['order_tax'];
		$usedpoint = $order['order_usedpoint'];
		$discount = $order['order_discount'];
		$cod_fee = $order['order_cod_fee'];

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<wallet_shopping_cart xmlns="urn:yahoo:jp:wallet">
		<xml_info>
		<version>1.0</version>
		</xml_info>
		<wallet_flow_support>
		<merchant_wallet_flow_support>
		<merch_id>' . $usces_opt['acting_settings']['yahoo']['merchant_id'] . '</merch_id>
		<merch_mgt_id>' . $order_id . '</merch_mgt_id>
		<ship_fee>' . usces_crform( $shipping_charge, false, false, 'return', false ) . '</ship_fee>
		<order_done_url>' . $order_done_url . '</order_done_url>
		<expire>' . $date_time . '</expire>';
		$xml .= '<order_confirmations>
		<order_confirmations_url>' . $confirmations_url . '</order_confirmations_url>
		</order_confirmations>
		';
		$xml .= '</merchant_wallet_flow_support>
		</wallet_flow_support>
		<shopping_cart>
		<items>
		';
		$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
		for( $i=0; $i<$cart_count; $i++ ){
			$cart_row = $cart[$i];
			$post_id = $cart_row['post_id'];
			$sku = urldecode($cart_row['sku']);
			$quantity = $cart_row['quantity'];
			$options = $cart_row['options'];
			$itemCode = $usces->getItemCode($post_id);
			$itemName = $usces->getItemName($post_id);
			$cartItemName = $usces->getCartItemName($post_id, $sku);
			$cartItemName = str_replace('(', '（', $cartItemName);
			$cartItemName = str_replace(')', '）', $cartItemName);
			$cartItemName = str_replace('&', '＆', $cartItemName);
			$cartItemName = str_replace('!', '！', $cartItemName);
			$cartItemName = str_replace('<', '＜', $cartItemName);
			$cartItemName = str_replace('>', '＞', $cartItemName);
			if( 100 < strlen($cartItemName) ){
				$cartItemName = mb_substr($cartItemName, 0, 40, 'UTF-8') . '･･･';
			}
			$skuPrice = $cart_row['price'];
			$xml .= '<item>
			<item_line_id>' . ($i+1) . '</item_line_id>
			<item_id>' . $itemCode . '</item_id>
			<item_name>' . $cartItemName . '</item_name>
			<item_price>' . usces_crform( $skuPrice, false, false, 'return', false ) . '</item_price>
			<item_qty>' . $quantity . '</item_qty>
			<item_tax_flg>0</item_tax_flg>
			<item_tax>0</item_tax>
			</item>
			';
		}
		$usedpoint = usces_crform( $order['order_usedpoint'], false, false, 'return', false );
		if( $usedpoint ){
			$i++;
			$xml .= '<item>
			<item_line_id>' . $i . '</item_line_id>
			<item_id>usedpoint</item_id>
			<item_name>ご利用ポイント</item_name>
			<item_price>' . ($usedpoint*(-1)) . '</item_price>
			<item_qty>1</item_qty>
			<item_tax_flg>0</item_tax_flg>
			<item_tax>0</item_tax>
			</item>
			';
		}
		$discount = usces_crform( $order['order_discount'], false, false, 'return', false );
		if( $discount ){
			$i++;
			$xml .= '<item>
			<item_line_id>' . $i . '</item_line_id>
			<item_id>discount</item_id>
			<item_name>お値引き</item_name>
			<item_price>' . $discount . '</item_price>
			<item_qty>1</item_qty>
			<item_tax_flg>0</item_tax_flg>
			<item_tax>0</item_tax>
			</item>
			';
		}
		$tax = usces_crform( $order['order_tax'], false, false, 'return', false );
		if( $tax ){
			$i++;
			$xml .= '<item>
			<item_line_id>' . $i . '</item_line_id>
			<item_id>tax</item_id>
			<item_name>消費税</item_name>
			<item_price>' . $tax . '</item_price>
			<item_qty>1</item_qty>
			<item_tax_flg>0</item_tax_flg>
			<item_tax>0</item_tax>
			</item>
			';
		}
		$cod_fee = usces_crform( $order['order_cod_fee'], false, false, 'return', false );
		if( $cod_fee ){
			$i++;
			$xml .= '<item>
			<item_line_id>' . $i . '</item_line_id>
			<item_id>cod_fee</item_id>
			<item_name>代引き手数料</item_name>
			<item_price>' . $cod_fee . '</item_price>
			<item_qty>1</item_qty>
			<item_tax_flg>0</item_tax_flg>
			<item_tax>0</item_tax>
			</item>
			';
		}
		
		$xml .= '</items>
		</shopping_cart>
		';
		
		$xml .= '</wallet_shopping_cart>';
		$res = $this->get_yahoo_xml( $xml );
		$responce = $this->xml2assoc($res);
		if( isset($responce['wallet_shopping_result']) ){
			$results = $responce['wallet_shopping_result'];
		}else{
			usces_log('redirect_url : '.print_r($responce, true), 'yahoo_error.log', 'test');
		}
		if( 'true' != $results['result']['is_successful'] ){
			header( 'Content-Type: application/xml; charset=UTF-8');
			usces_log('redirect_url : '.print_r($results['error']['code'], true), 'yahoo_error.log', 'test');
		}else{
			if( 'public' == $usces_opt['acting_settings']['yahoo']['ope'] ){
				$url = $results['result']['redirect_order_url'];
			}else{
				$url = str_replace( 'https://', 'https://sandbox.', $results['result']['redirect_order_url']);
			}
			$usces->set_order_meta_value('redirect_order_url', $url, $order_id);
			$usces->set_order_meta_value('url_expire', $expire_time, $order_id);

		}
	}

	/**********************************************
	* usces_xml2assoc
	* @param  $xml
	* @return array $arr
	***********************************************/
	protected function xml2assoc( $xml ) {

		$arr = array();
		if( !preg_match_all('|\<\s*?(\w+).*?\>(.*)\<\/\s*\\1.*?\>|s', $xml, $m) ) return $xml;
		if( is_array($m[1]) ) {
			for( $i = 0; $i < sizeof($m[1]); $i++ ) {
				$arr[$m[1][$i]] = $this->xml2assoc($m[2][$i]);
			}
		} else {
			$arr[$m[1]] = $this->xml2assoc($m[2]);
		}
		return $arr;
	}

	/***************************************************************************/
	// 認証付非同期通信
	/***************************************************************************/
	public function get_yahoo_xml( $paras ){
		$options = get_option('usces');
		$interface = parse_url($options['acting_settings']['yahoo']['send_url']);
		$header  = "POST " . $interface['path'] . " HTTP/1.1\r\n";
		$header .= "Host: " . $_SERVER['HTTP_HOST'] . "\r\n";
		$header .= "Authorization: Basic " . base64_encode($options['acting_settings']['yahoo']['merchant_id'] . ':' . $options['acting_settings']['yahoo']['merchant_key']) . "\r\n";
		$header .= "Accept: application/xml; charset=UTF-8\r\n";
		$header .= "Content-Type: application/xml; charset=UTF-8\r\n";
		$header .= "Content-Length: " . strlen($paras) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$header .= $paras;
		$fp = fsockopen('ssl://'.$interface['host'],443,$errno,$errstr,30);
		
		$xml = '';
		if ($fp){
			fwrite($fp, $header);
			while ( !feof($fp) ) {
				$xml .= fgets($fp, 1024);
			}
			fclose($fp);
		}
		
		return $xml;
	}
	/***************************************************************************/
	// Yahoo注文前確認API用レスポンス
	/***************************************************************************/
	public function yahoo_responce(){
		$options = get_option('usces');
		$flag = true;
		// リクエストヘッダ上のHTTP基本認証のユーザ名とパスワードを取得
		$user = $_SERVER['PHP_AUTH_USER'];
		$passwd = $_SERVER['PHP_AUTH_PW'];
		// HTTP基本認証を実施
		if($user !== $options['acting_settings']['yahoo']['merchant_id'] || $passwd !== $options['acting_settings']['yahoo']['merchant_key']) {
			// 認証に失敗
			header('WWW-Authenticate: Basic realm=""');
			header('Content-Type: application/xml; charset=UTF-8');
			header('HTTP/1.1 401 Unauthorized');
			exit;
		}else{
			$flag = true;
		}
		
		// $HTTP_RAW_POST_DATAにアクセスし通知情報を取得する
		$xmlstr = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
		if(!$simplexml = new SimpleXMLElement($xmlstr)) {
			usces_log("failed to create SimpleXml object", 'acting_transaction.log');
			$flag = false;
		}else{
			global $usces, $wpdb;
			$order_id = (int)$simplexml->merch_mgt_id;
			$amount = (int)$simplexml->prices->total_price;
			$order = $usces->get_order_data($order_id, 'direct' );
			if ( !$order )
				$flag = false;
			
			$fulprice = $order['order_item_total_price'] - $order['order_usedpoint'] + $order['order_discount'] + $order['order_cod_fee'] + $order['order_shipping_charge'] + $order['order_tax'];
			if ( $fulprice != $amount )
				$flag = false;
				
			$meta_value = serialize( array( 'yahoo_wallet_device' => (int)$simplexml->device ) );
			$acting_meta = $usces->get_order_meta_value( 'acting_yahoo_wallet', $order_id );
			if( !$acting_meta ){
				usces_action_acting_getpoint( $order_id );
			}
			$usces->set_order_meta_value( 'acting_yahoo_wallet', $meta_value, $order_id );
			
			//オーダーステータス変更
			usces_change_order_receipt( $order_id, 'receipted' );
			
		}

		if( $flag ){
			usces_log("yahoo=SUCCESS", 'acting_transaction.log');
			ob_clean();
			header( 'Content-Type: application/xml; charset=UTF-8');
			header( 'HTTP/1.1 200 OK');
			header( 'Date: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
			header( 'Connection: close');
			$res = '<?xml version="1.0" encoding="UTF-8" ?>
			<order_confirmation_results xmlns="urn:yahoo:jp:wallet">
			<xml_info><version>1.0</version></xml_info>
			<result>
			<res_code>0</res_code>
			<details_url>' . home_url('/?yahoo=SUCCESS') . '</details_url>
			</result>
			</order_confirmation_results>';
			echo $res;
		}else{
			usces_log("yahoo=Unavailable", 'acting_transaction.log');
			header('Content-Type: application/xml; charset=UTF-8');
			header('HTTP/1.1 503 Service Unavailable');
		}
		die();
	}



	/**********************************************
	* Settlement setting data update
	* @param  -
	* @return -
	***********************************************/
	public function data_update(){
		global $usces;
		
		if( 'yahoo' != $_POST['acting'])
			return;
	
		$this->error_mes = '';
		$options = get_option('usces');
		$payment_method = usces_get_system_option( 'usces_payment_method', 'settlement' );

		unset( $options['acting_settings']['yahoo'] );
		$options['acting_settings']['yahoo']['wallet_activate'] = isset($_POST['wallet_activate']) ? $_POST['wallet_activate'] : '';
		$options['acting_settings']['yahoo']['merchant_id'] = isset($_POST['merchant_id']) ? $_POST['merchant_id'] : '';
		$options['acting_settings']['yahoo']['merchant_key'] = isset($_POST['merchant_key']) ? $_POST['merchant_key'] : '';
		$options['acting_settings']['yahoo']['ope'] = isset($_POST['ope']) ? $_POST['ope'] : '';
		$options['acting_settings']['yahoo']['expire'] = ( isset($_POST['expire_yahoo']) && 10 >= $_POST['expire_yahoo'] ) ? (int)$_POST['expire_yahoo'] : '10';

		if( WCUtils::is_blank($_POST['merchant_id']) )
			$this->error_mes .= '※マーチャントIDを入力してください<br />';
		if( WCUtils::is_blank($_POST['merchant_key']) )
			$this->error_mes .= '※マーチャントキーを入力してください<br />';

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['yahoo']['wallet_activate'] ){
				$options['acting_settings']['yahoo']['send_url'] = 'https://api.settle.wallet.yahoo.co.jp/v1/redirect_url';
				$usces->payment_structure['acting_yahoo_wallet'] = 'ウォレット決済（Yahoo!ウォレット）';
				$toactive = array();
				foreach( $payment_method as $settlement => $payment ) {
					if( 'acting_paypal_ec' == $settlement && 'deactivate' == $payment['use'] ) {
						$toactive[] = $payment['name'];
					}
				}
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			}else{
				unset($usces->payment_structure['acting_yahoo_wallet']);
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
		}else{
			$usces->action_status = 'error';
			$usces->action_message = __('Data have deficiency.','usces');
			$options['acting_settings']['yahoo']['wallet_activate'] = 'off';
			unset($usces->payment_structure['acting_yahoo_wallet']);
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
		ksort($usces->payment_structure);
		update_option('usces', $options);
		update_option('usces_payment_structure', $usces->payment_structure);
	}

	/**********************************************
	* Settlement setting page tab title
	* @param  -
	* @return -
	* @echo   str
	***********************************************/
	public function tab_title(){
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( 'yahoo', (array)$settlement_selected ) ) {
			echo '<li><a href="#uscestabs_yahoo">Yahoo!ウォレット</a></li>';
		}
	}

	/**********************************************
	* Settlement setting page tab body
	* @param  -
	* @return -
	* @echo   str
	***********************************************/
	public function tab_body(){
		global $usces;
		$opts = $usces->options['acting_settings'];
		$settlement_selected = get_option( 'usces_settlement_selected' );
		if( in_array( 'yahoo', (array)$settlement_selected ) ):
?>
	<div id="uscestabs_yahoo">
	<div class="settlement_service"><span class="service_title">Yahoo!ウォレット決済（YYタイプ）</span></div>

	<?php if( isset($_POST['acting']) && 'yahoo' == $_POST['acting'] ){ ?>
		<?php if( '' != $this->error_mes ){ ?>
		<div class="error_message"><?php echo $this->error_mes; ?></div>
		<?php }else if( isset($opts['yahoo']['activate']) && 'on' == $opts['yahoo']['activate'] ){ ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
		<?php } ?>
	<?php } ?>
	<form action="" method="post" name="yahoo_form" id="yahoo_form">
		<table class="settle_table">
			<tr>
				<th>ウォレット決済の利用</th>
				<td><label><input name="wallet_activate" type="radio" id="wallet_activate_yahoo_1" value="on"<?php if( isset($opts['yahoo']['wallet_activate']) && $opts['yahoo']['wallet_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="wallet_activate" type="radio" id="wallet_activate_yahoo_2" value="off"<?php if( isset($opts['yahoo']['wallet_activate']) && $opts['yahoo']['wallet_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_merchant_id_yahoo">マーチャントID</a></th>
				<td><input name="merchant_id" type="text" id="merchant_id_yahoo" value="<?php echo esc_html(isset($opts['yahoo']['merchant_id']) ? $opts['yahoo']['merchant_id'] : ''); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchant_id_yahoo" class="explanation"><td colspan="2">契約時にYahoo! JAPANから発行されるマーチャントID（半角数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_merchant_key_yahoo">マーチャントキー</a></th>
				<td><input name="merchant_key" type="text" id="merchant_key_yahoo" value="<?php echo esc_html(isset($opts['yahoo']['merchant_key']) ? $opts['yahoo']['merchant_key'] : ''); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchant_key_yahoo" class="explanation"><td colspan="2">契約時にYahoo! JAPANから発行される マーチャントキー（半角英数）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_yahoo"><?php _e('Operation Environment', 'usces'); ?></a></th>
				<td><label><input name="ope" type="radio" id="ope_yahoo_1" value="test"<?php if( isset($opts['yahoo']['ope']) && $opts['yahoo']['ope'] == 'test' ) echo ' checked="checked"'; ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_yahoo_2" value="public"<?php if( isset($opts['yahoo']['ope']) && $opts['yahoo']['ope'] == 'public' ) echo ' checked="checked"'; ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_yahoo" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_expire_yahoo">支払期限時間</a></th>
				<td><input name="expire_yahoo" type="text" id="expire_yahoo" value="<?php echo esc_html(isset($opts['yahoo']['expire']) ? $opts['yahoo']['expire'] : '10'); ?>" class="small-text" />時間</td>
			</tr>
			<tr id="ex_expire_yahoo" class="explanation"><td colspan="2">支払URLの有効時間。初期値は10時間です。</td></tr>
		</table>
		<input name="acting" type="hidden" value="yahoo" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="Yahoo!ウォレットの設定を更新する" />
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong>Yahoo!ウォレット決済 ＹＹタイプ</strong></p>
		<a href="http://wallet.yahoo.co.jp/about/" target="_blank">Yahoo!ウォレット決済の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済はYahoo!ウォレット ＹＹタイプのみ決済システムです。API設定で下記のオプションを「利用する」に設定してください。</p>
		<p>「リダイレクトURL生成API」、「マーチャント計算API」、「注文確認API」、「MerchantCenterを利用する」</p>
	</div>
	</div><!--uscestabs_yahoo-->
<?php
		endif;
	}
}

<?php
/**
 * ベリトランス
 *
 * @class    VERITRANS_SETTLEMENT
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    1.9.20
 */
class VERITRANS_SETTLEMENT
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

	protected $error_mes;

	public function __construct() {

		$this->paymod_id = 'veritrans';
		$this->pay_method = array(
			'acting_veritrans_card',
			'acting_veritrans_conv',
		);
		$this->acting_name = 'ベリトランス';
		$this->acting_formal_name = 'ベリトランス Air-Web';
		$this->acting_company_url = 'https://www.veritrans.co.jp/air/';
		$this->initialize_data();

		if( is_admin() ) {
			//add_action( 'admin_print_footer_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'usces_action_admin_settlement_update', array( $this, 'settlement_update' ) );
			add_action( 'usces_action_settlement_tab_title', array( $this, 'settlement_tab_title' ) );
			add_action( 'usces_action_settlement_tab_body', array( $this, 'settlement_tab_body' ) );
		}

		if( $this->is_activate_card() ) {
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
		if( !isset( $options['acting_settings'] ) || !isset( $options['acting_settings']['veritrans'] ) ) {
			$options['acting_settings']['veritrans']['merchant_id'] = '';
			$options['acting_settings']['veritrans']['merchanthash'] = '';
			$options['acting_settings']['veritrans']['ope'] = '';
			$options['acting_settings']['veritrans']['mailaddress'] = '';
			$options['acting_settings']['veritrans']['card_activate'] = 'off';
			$options['acting_settings']['veritrans']['card_capture_flag'] = '';
			$options['acting_settings']['veritrans']['conv_activate'] = 'off';
			$options['acting_settings']['veritrans']['conv_timelimit'] = '60';
			update_option( 'usces', $options );
		}
	}

	/**
	 * 決済有効判定
	 * 引数が指定されたとき、支払方法で使用している場合に「有効」とする
	 * @param  ($type)
	 * @return boolean
	 */
	public function is_validity_acting( $type = '' ) {

		$acting_opts = $this->get_acting_settings();
		if( empty( $acting_opts ) ) {
			return false;
		}

		$payment_method = usces_get_system_option( 'usces_payment_method', 'sort' );
		$method = false;

		switch( $type ) {
		case 'card':
			foreach( $payment_method as $payment ) {
				if( 'acting_veritrans_card' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$method = true;
					break;
				}
			}
			if( $method && $this->is_activate_card() ) {
				return true;
			} else {
				return false;
			}
			break;

		case 'conv':
			foreach( $payment_method as $payment ) {
				if( 'acting_veritrans_conv' == $payment['settlement'] && 'activate' == $payment['use'] ) {
					$method = true;
					break;
				}
			}
			if( $method && $this->is_activate_conv() ) {
				return true;
			} else {
				return false;
			}
			break;

		default:
			if( 'on' == $acting_opts['activate'] ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * クレジットカード決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_card() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['card_activate'] ) && ( 'on' == $acting_opts['card_activate'] ) ) ) {
			$res = true;
		} else {
			$res = false;
		}
		return $res;
	}

	/**
	 * コンビニ決済有効判定
	 * @param  -
	 * @return boolean $res
	 */
	public function is_activate_conv() {

		$acting_opts = $this->get_acting_settings();
		if( ( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ) && 
			( isset( $acting_opts['conv_activate'] ) && 'on' == $acting_opts['conv_activate'] ) ) {
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

		unset( $options['acting_settings']['veritrans'] );
		$options['acting_settings']['veritrans']['merchant_id'] = ( isset( $_POST['merchant_id'] ) ) ? trim( $_POST['merchant_id'] ) : '';
		$options['acting_settings']['veritrans']['merchanthash'] = ( isset( $_POST['merchanthash'] ) ) ? trim( $_POST['merchanthash'] ) : '';
		$options['acting_settings']['veritrans']['ope'] = ( isset( $_POST['ope'] ) ) ? $_POST['ope'] : '';
		$options['acting_settings']['veritrans']['mailaddress'] = ( isset( $_POST['mailaddress'] ) ) ? trim( $_POST['mailaddress'] ) : '';
		$options['acting_settings']['veritrans']['card_activate'] = ( isset( $_POST['card_activate'] ) ) ? $_POST['card_activate'] : 'off';
		$options['acting_settings']['veritrans']['card_capture_flag'] = ( isset( $_POST['card_capture_flag'] ) ) ? $_POST['card_capture_flag'] : '';
		$options['acting_settings']['veritrans']['conv_activate'] = ( isset( $_POST['conv_activate'] ) ) ? $_POST['conv_activate'] : 'off';
		$options['acting_settings']['veritrans']['conv_timelimit'] = ( isset( $_POST['conv_timelimit'] ) ) ? $_POST['conv_timelimit'] : '60';

		if( WCUtils::is_blank( $options['acting_settings']['veritrans']['merchant_id'] ) ) {
			$this->error_mes .= '※マーチャントIDを入力してください<br />';
		}
		if( WCUtils::is_blank( $options['acting_settings']['veritrans']['merchanthash'] ) ) {
			$this->error_mes .= '※マーチャントハッシュキーを入力してください<br />';
		}
		if( WCUtils::is_blank( $options['acting_settings']['veritrans']['ope'] ) ) {
			$this->error_mes .= '※稼働環境を選択してください<br />';
		}
		if( 'on' == $options['acting_settings']['veritrans']['card_activate'] ) {
			if( WCUtils::is_blank( $options['acting_settings']['veritrans']['card_capture_flag'] ) ) {
				$this->error_mes .= '※カード売上フラグを選択してください<br />';
			}
		}

		if( '' == $this->error_mes ) {
			$usces->action_status = 'success';
			$usces->action_message = __( 'Options are updated.', 'usces' );
			if( 'on' == $options['acting_settings']['veritrans']['card_activate'] || 'on' == $options['acting_settings']['veritrans']['conv_activate'] ) {
				$options['acting_settings']['veritrans']['activate'] = 'on';
				$options['acting_settings']['veritrans']['regist_url'] = "https://air.veritrans.co.jp/web/commodityRegist.action";
				$options['acting_settings']['veritrans']['payment_url'] = "https://air.veritrans.co.jp/web/paymentStart.action";
				$toactive = array();
				if( 'on' == $options['acting_settings']['veritrans']['card_activate'] ) {
					$usces->payment_structure['acting_veritrans_card'] = 'カード決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_veritrans_card' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_veritrans_card'] );
				}
				if( 'on' == $options['acting_settings']['veritrans']['conv_activate'] ) {
					$usces->payment_structure['acting_veritrans_conv'] = 'コンビニ決済（'.$this->acting_name.'）';
					foreach( $payment_method as $settlement => $payment ) {
						if( 'acting_veritrans_conv' == $settlement && 'deactivate' == $payment['use'] ) {
							$toactive[] = $payment['name'];
						}
					}
				} else {
					unset( $usces->payment_structure['acting_veritrans_conv'] );
				}
				usces_admin_orderlist_show_wc_trans_id();
				if( 0 < count( $toactive ) ) {
					$usces->action_message .= __( "Please update the payment method to \"Activate\". <a href=\"admin.php?page=usces_initial#payment_method_setting\">General Setting > Payment Methods</a>", 'usces' );
				}
			} else {
				$options['acting_settings']['veritrans']['activate'] = 'off';
				unset( $usces->payment_structure['acting_veritrans_card'] );
				unset( $usces->payment_structure['acting_veritrans_conv'] );
			}
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
		} else {
			$usces->action_status = 'error';
			$usces->action_message = __( 'Data have deficiency.', 'usces' );
			$options['acting_settings']['veritrans']['activate'] = 'off';
			unset( $usces->payment_structure['acting_veritrans_card'] );
			unset( $usces->payment_structure['acting_veritrans_conv'] );
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
		if( in_array( $this->paymod_id, (array)$settlement_selected ) ) {
			echo '<li><a href="#uscestabs_'.$this->paymod_id.'">'.$this->acting_name.'</a></li>';
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
		if( in_array( $this->paymod_id, (array)$settlement_selected ) ):
?>
	<div id="uscestabs_veritrans">
	<div class="settlement_service"><span class="service_title"><?php echo $this->acting_formal_name; ?></span></div>
	<?php if( isset( $_POST['acting'] ) && 'veritrans' == $_POST['acting'] ): ?>
		<?php if( '' != $this->error_mes ): ?>
		<div class="error_message"><?php echo $this->error_mes; ?></div>
		<?php elseif( isset( $acting_opts['activate'] ) && 'on' == $acting_opts['activate'] ): ?>
		<div class="message">十分にテストを行ってから運用してください。</div>
		<?php endif; ?>
	<?php endif; ?>
	<form action="" method="post" name="veritrans_form" id="veritrans_form">
		<table class="settle_table">
			<tr>
				<th><a class="explanation-label" id="label_ex_merchant_id_veritrans">マーチャントID</a></th>
				<td><input name="merchant_id" type="text" id="merchant_id_veritrans" value="<?php echo esc_html( isset( $acting_opts['merchant_id'] ) ? $acting_opts['merchant_id'] : '' ); ?>" maxlength="22" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchant_id_veritrans" class="explanation"><td colspan="2">契約時に<?php echo $this->acting_name; ?>から発行されるマーチャントID（半角英数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_merchanthash_veritrans">マーチャントハッシュキー</a></th>
				<td><input name="merchanthash" type="text" id="merchanthash_veritrans" value="<?php echo esc_html(isset( $acting_opts['merchanthash'] ) ? $acting_opts['merchanthash'] : '' ); ?>" class="regular-text" /></td>
			</tr>
			<tr id="ex_merchanthash_veritrans" class="explanation"><td colspan="2">契約時に<?php echo $this->acting_name; ?>から発行されるマーチャントハッシュキー（半角英数字）</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_ope_veritrans">稼働環境</a></th>
				<td><label><input name="ope" type="radio" id="ope_veritrans_1" value="test"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'test' ) echo ' checked="checked"'; ?> /><span>テスト環境</span></label><br />
					<label><input name="ope" type="radio" id="ope_veritrans_2" value="public"<?php if( isset( $acting_opts['ope'] ) && $acting_opts['ope'] == 'public' ) echo ' checked="checked"'; ?> /><span>本番環境</span></label>
				</td>
			</tr>
			<tr id="ex_ope_veritrans" class="explanation"><td colspan="2">動作環境を切り替えます。</td></tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_mailaddress_veritrans">決済完了通知</a></th>
				<td><label><input name="mailaddress" type="radio" id="mailaddress_veritrans_1" value="on"<?php if( isset( $acting_opts['mailaddress'] ) && $acting_opts['mailaddress'] == 'on' ) echo ' checked="checked"'; ?> /><span>送信する</span></label><br />
					<label><input name="mailaddress" type="radio" id="mailaddress_veritrans_2" value="off"<?php if( isset( $acting_opts['mailaddress'] ) && $acting_opts['mailaddress'] == 'off' ) echo ' checked="checked"'; ?> /><span>送信しない</span></label>
				</td>
			</tr>
			<tr id="ex_mailaddress_veritrans" class="explanation"><td colspan="2">購入者に<?php echo $this->acting_name; ?>からメール通知を行います。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>クレジットカード決済</th>
				<td><label><input name="card_activate" type="radio" id="card_activate_veritrans_1" value="on"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="card_activate" type="radio" id="card_activate_veritrans_2" value="off"<?php if( isset( $acting_opts['card_activate'] ) && $acting_opts['card_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_card_capture_flag_veritrans">カード売上フラグ</a></th>
				<td><label><input name="card_capture_flag" type="radio" id="card_capture_flag_veritrans_0" value="auhtorize"<?php if( isset( $acting_opts['card_capture_flag'] ) && $acting_opts['card_capture_flag'] == 'auhtorize' ) echo ' checked'; ?> /><span>与信</span></label><br />
					<label><input name="card_capture_flag" type="radio" id="card_capture_flag_veritrans_1" value="capture"<?php if( isset( $acting_opts['card_capture_flag'] ) && $acting_opts['card_capture_flag'] == 'capture' ) echo ' checked'; ?> /><span>与信同時売上</span></label>
				</td>
			</tr>
			<tr id="ex_card_capture_flag_veritrans" class="explanation"><td colspan="2">決済の処理方式を指定します。</td></tr>
		</table>
		<table class="settle_table">
			<tr>
				<th>コンビニ決済</th>
				<td><label><input name="conv_activate" type="radio" id="conv_activate_veritrans_1" value="on"<?php if( isset( $acting_opts['conv_activate'] ) && $acting_opts['conv_activate'] == 'on' ) echo ' checked="checked"'; ?> /><span>利用する</span></label><br />
					<label><input name="conv_activate" type="radio" id="conv_activate_veritrans_2" value="off"<?php if( isset( $acting_opts['conv_activate'] ) && $acting_opts['conv_activate'] == 'off' ) echo ' checked="checked"'; ?> /><span>利用しない</span></label>
				</td>
			</tr>
			<tr>
				<th><a class="explanation-label" id="label_ex_conv_timelimit_veritrans">支払期限</a></th>
				<td>
				<?php
					$selected = array_fill( 1, 60, '' );
					if( isset( $acting_opts['conv_timelimit'] ) ) {
						$selected[$acting_opts['conv_timelimit']] = ' selected';
					} else {
						$selected[60] = ' selected';
					}
				?>
				<select name="conv_timelimit" id="conv_timelimit">
				<?php for( $i = 1; $i <= 60; $i++ ): ?>
					<option value="<?php echo esc_html( $i ); ?>"<?php echo esc_html( $selected[$i] ); ?>><?php echo esc_html( $i ); ?></option>
				<?php endfor; ?>
				</select>（日数）</td>
			</tr>
			<tr id="ex_conv_timelimit_veritrans" class="explanation"><td colspan="2">コンビニ店頭でお支払いいただける期限となります。</td></tr>
		</table>
		<input name="acting" type="hidden" value="veritrans" />
		<input name="usces_option_update" type="submit" class="button button-primary" value="<?php echo $this->acting_name; ?>の設定を更新する" />
		<?php wp_nonce_field( 'admin_settlement', 'wc_nonce' ); ?>
	</form>
	<div class="settle_exp">
		<p><strong><?php echo $this->acting_formal_name; ?></strong></p>
		<a href="<?php echo $this->acting_company_url; ?>" target="_blank"><?php echo $this->acting_name; ?>の詳細はこちら 》</a>
		<p>　</p>
		<p>この決済は「外部リンク型」の決済システムです。</p>
		<p>「外部リンク型」とは、決済会社のページへ遷移してカード情報を入力する決済システムです。</p>
	</div>
	</div><!--uscestabs_veritrans-->
<?php
		endif;
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

		if( isset( $_GET['acting'] ) && 'veritrans_card' == $_GET['acting'] && isset( $_POST['orderId'] ) ) {
			$usces->set_order_meta_value( 'orderId', $_POST['orderId'], $order_id );
			$usces->set_order_meta_value( 'acting_'.$_GET['acting'], serialize( $_POST ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_POST['orderId'], $order_id );

		} elseif( isset( $_GET['acting'] ) && 'veritrans_conv' == $_GET['acting'] && isset( $_POST['orderId'] ) ) {
			$usces->set_order_meta_value( 'orderId', $_POST['orderId'], $order_id );
			$data['mStatus'] = esc_sql( $_POST['mStatus'] );
			$data['vResultCode'] = esc_sql( $_POST['vResultCode'] );
			$data['orderId'] = esc_sql( $_POST['orderId'] );
			$usces->set_order_meta_value( 'acting_'.$_GET['acting'], serialize( $data ), $order_id );
			$usces->set_order_meta_value( 'wc_trans_id', $_POST['orderId'], $order_id );
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

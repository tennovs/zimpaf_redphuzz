<?php

function usces_filter_get_post_metadata( $null, $object_id, $meta_key, $single){
	global $wpdb;
	$query = $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s", $object_id, $meta_key);
	$metas = $wpdb->get_col($query);
	if ( !empty($metas) ) {
			return array_map('maybe_unserialize', $metas);
	}

	if ($single)
		return '';
	else
		return array();
}

/**
 * Save decorated order id
 *
 * @param array $args ('cart', 'entry', 'order_id', 'member_id', 'payments', 'charging_type', 'results').
 */
function usces_action_reg_orderdata( $args ) {
	global $usces;
	extract( $args );

	$options = get_option('usces');
	$prefix  = $options['system']['dec_orderID_prefix'];
	$prefix  = apply_filters( 'usces_filter_dec_order_id_prefix', $prefix, $args );

	$dec_order_id = usces_make_deco_order_id( $order_id );
	$dec_order_id = apply_filters( 'usces_filter_dec_order_id', $dec_order_id, $args );

	$dec_order_id = $prefix . $dec_order_id;

	$usces->set_order_meta_value( 'dec_order_id', $dec_order_id, $order_id );
}

function usces_action_reg_orderdata_stocks( $args ) {
	global $usces;
	extract( $args );

	foreach ( $cart as $cartrow ) {

		$itemOrderAcceptable = (int) $usces->getItemOrderAcceptable( $cartrow['post_id'], false );
		$sku                 = urldecode( $cartrow['sku'] );
		$zaikonum            = $usces->getItemZaikoNum( $cartrow['post_id'], $sku, false );
		if ( WCUtils::is_blank( $zaikonum ) ) {
			continue;
		}
		$zaikonum = (int) $zaikonum - (int) $cartrow['quantity'];
		if ( 1 !== $itemOrderAcceptable ) {
			if ( $zaikonum < 0 ) {
				$zaikonum = 0;
			}
		}

		$usces->updateItemZaikoNum( $cartrow['post_id'], $sku, $zaikonum );

		if ( 1 !== $itemOrderAcceptable ) {
			if ( $zaikonum <= 0 ) {
				$default_empty_status = apply_filters( 'usces_filter_default_empty_status', 2 );
				$usces->updateItemZaiko( $cartrow['post_id'], $sku, $default_empty_status );
				do_action( 'usces_action_outofstock', $cartrow['post_id'], $sku, $cartrow, $args );
			}
		}
	}
}

function usces_action_ogp_meta() {
	global $usces, $post;
	if ( empty( $post ) || ! $usces->is_item( $post ) || ! is_single() ){
		return;
	}
	$item       = wel_get_product( $post->ID );
	$pictid     = $usces->get_mainpictid( $item['itemCode'] );
	$image_info = wp_get_attachment_image_src( $pictid, 'thumbnail' );

	$ogs['title']       = $item['itemName'];
	$ogs['type']        = 'product';
	$ogs['description'] = strip_tags( get_the_title( $post->ID ) );
	$ogs['url']         = get_permalink( $post->ID );
	$ogs['image']       = ( isset( $image_info[0] ) ) ? $image_info[0] : '';
	$ogs['site_name']   = get_option('blogname');

	$ogs = apply_filters( 'usces_filter_ogp_meta', $ogs, $post->ID );

	foreach ( $ogs as $key => $value ){
		echo "\n" . '<meta property="og:' . $key . '" content="' . $value . '">';
	}
}

/**
 * 構造化データのJSON出力.
 */
function usces_action_structured_data_json() {
	global $post, $usces;

	if ( 0 === USCES_STRUCTURED_DATA_PRODUCT::$opts['status'] || empty( $post ) ) {
		return;
	}
	
	global $post, $usces;
	if ( empty( $post ) || ! $usces->is_item( $post ) ) {
		return;
	}

	$product = wel_get_product( $post->ID );
	if ( is_admin() || ! is_single() || ! $product["itemCode"] ) {
		return;
	}

	$itemName              = $product['itemName'];
	$skus                  = $product['_sku'];
	$target_sku            = null;
	$structuredDataSkuCode = $product['structuredDataSku'];
	$patch_skus            = array();

	foreach ( $skus as $sku ) {
		// 廃盤は削除.
		if ( 4 === (int) $usces->getItemZaikoStatusId( $post->ID, $sku['code'] ) ) {
			continue;
		}
		// 特定のSKUがあればそれを対象とする.
		if ( $structuredDataSkuCode === $sku['code'] ) {
			$target_sku = $sku;
		}
		$patch_skus[] = $sku;
	}
	if ( null === $target_sku ) {
		foreach ( $patch_skus as $sku ) {
			// １番目のSKU.
			if ( 'first' === USCES_STRUCTURED_DATA_PRODUCT::$opts['default_price'] ) {
				$target_sku = $sku;
				break;
			}
			// 1番安価のSKU.
			if ( 'minimum' === USCES_STRUCTURED_DATA_PRODUCT::$opts['default_price'] ) {
				if ( $target_sku && (int) $target_sku['price'] <= (int) $sku['price'] ) {
					continue;
				}
				$target_sku = $sku;
			} else {
				if ( $target_sku && (int) $target_sku['price'] >= (int) $sku['price'] ) { // 1番高価のSKU.
					continue;
				}
				$target_sku = $sku;
            }
		}
	}
	if ( empty( $target_sku ) ) {
		return;
	}
	$target_sku['availability'] = $usces->is_item_zaiko( $post->ID, $sku['code'] ) ? "https://schema.org/InStock" : "https://schema.org/SoldOut";

	$url         = get_the_permalink();
	$image       = usces_the_itemImageURL( 0, 'return' );
	$description = function_exists( 'get_field' ) ? get_field( 'field_rich_result_product_description' ) : '';
	$stock       = usces_have_zaiko_anyone( $post->ID ) ? "https://schema.org/InStock" : "https://schema.org/SoldOut";
	$cr = $usces->options['system']['currency'];
	if ( isset( $usces_settings['currency'][ $cr ] ) ) {
		list( $code, $decimal, $point, $seperator, $symbol ) = $usces_settings['currency'][ $cr ];
	} else {
		$code = 'JPY';
	}
	$data        = array(
		"@context"    => "https://schema.org/",
		"@type"       => "Product",
		"name"        => $itemName,
		"image"       => $image,
		"description" => $description,
		"productID"   => $product["itemCode"],
		"offers"      => [
			"@type"         => "Offer",
			"sku"           => $product["itemCode"] . '-' . $target_sku['code'],
			"priceCurrency" => $code,
			"price"         => $target_sku['price'],
			"availability"  => $target_sku['availability']
		]
	);
	$script = json_encode( apply_filters( 'usces_filter_structured_data', $data, $post ) );

	echo "\n" . '<script type="application/ld+json">' . $script . '</script>';
}

/**
 *  特定のSKUデータを構造化データのに利用するためのフォーム.
 *
 * @param int   $post_id Post Id.
 * @param mixed $post Post Data.
 */
function usces_item_master_structured_data($html, $post_ID){
	if ( 1 === USCES_STRUCTURED_DATA_PRODUCT::$opts['status'] ) {
		$product           = wel_get_product( $post_ID );
		$structuredDataSku = $product['structuredDataSku'];
		?>
        <tr>
            <th><?php _e('SKUs Code used for structured data', 'usces') ; ?></th>
            <td>
                <input type="text" name="structuredDataSku" id="structuredDataSku" class="structuredDataSku" value="<?php echo esc_attr( $structuredDataSku ); ?>" />
                <input type="hidden" name="structuredDataSku_nonce" id="structuredDataSku_nonce" value="<?php echo wp_create_nonce( 'structuredDataSku_nonce' ); ?>" />
            </td>
        </tr>
		<?php
	}
}

function wc_mkdir(){
	global $usces;
	if( is_admin() && !WCUtils::is_blank($usces->options['logs_path']) && false !== strpos($_SERVER['SERVER_SOFTWARE'],'Apache')){
		$welcart_file_dir = $usces->options['logs_path'] . '/welcart';
		$logs_dir = $welcart_file_dir . '/logs';
		if( !file_exists($welcart_file_dir) ){
			$res = @mkdir($welcart_file_dir, 0700);
			if(!$res){
				$msg = '<div class="error"><p>下記のディレクトリーを、所有者：' . get_current_user() . '、パーミッション：700 で作成してください。 <br />' . $welcart_file_dir . '</p></div>';
				add_action('admin_notices', function(){ echo addcslashes($msg,'"'); });
			}
		}
		$stat = stat($welcart_file_dir);
		print_r($stat);
	}
}

/**
 * Save the oreder cart data.
 *
 * @param  array $args 'cart', 'entry', 'order_id', 'member_id', 'payments', 'charging_type'.
 * @return void
 */
function usces_reg_ordercartdata( $args ) {
	global $usces, $wpdb, $usces_settings;
	extract( $args );

	if ( ! $order_id ) {
		return;
	}
	$cart_table      = $wpdb->prefix . 'usces_ordercart';
	$cart_meta_table = $wpdb->prefix . 'usces_ordercart_meta';

	foreach ( $cart as $row_index => $value ) {
		$post_id     = (int) $value['post_id'];
		$product     = wel_get_product( $post_id, false );
		$item_code   = $product['itemCode'];
		$item_name   = $product['itemName'];
		$skus        = $usces->get_skus( $value['post_id'], 'code', false );
		$sku_encoded = $value['sku'];
		$skucode     = urldecode( $value['sku'] );
		$sku         = $skus[ $skucode ];
		$tax         = 0;
		$wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$cart_table} 
				(order_id, row_index, post_id, item_code, item_name, sku_code, sku_name, cprice, price, quantity, 
				unit, tax, destination_id, cart_serial ) 
				VALUES (%d, %d, %d, %s, %s, %s, %s, %f, %f, %f, %s, %d, %d, %s)",
				$order_id,
				$row_index,
				$post_id,
				$item_code,
				$item_name,
				$skucode,
				$sku['name'],
				$sku['cprice'],
				$value['price'],
				$value['quantity'],
				$sku['unit'],
				$tax,
				null,
				$value['serial']
			)
		);

		$cart_id    = $wpdb->insert_id;
		$opt_fields = wel_get_opts( $post_id, 'sort', false );

		if ( $value['options'] ) {

			foreach ( (array) $opt_fields as $okey => $val ) {

				$enc_key = urlencode( $val['name'] );
				$means   = $opt_fields[ $okey ]['means'];

				if ( 3 === (int) $means ) {

					if ( '' == $value['options'][ $enc_key ] ) {
						$ovalue = $value['options'][ $enc_key ];
					} else {
						$ovalue = urldecode( $value['options'][ $enc_key ] );
					}
				} elseif ( 4 === (int) $means ) {

					if ( is_array( $value['options'][ $enc_key ] ) ) {

						$temp = array();
						foreach ( $value['options'][ $enc_key ] as $v ) {
							$temp[] = urldecode( $v );
						}
						$ovalue = serialize( $temp );
					} elseif ( '' == $value['options'][ $enc_key ] ) {

						$ovalue = $value['options'][ $enc_key ];
					} else {

						$ovalue = urldecode( $value['options'][ $enc_key ] );
					}
				} else {

					if ( is_array( $value['options'][ $enc_key ] ) ) {
						$temp = array();
						foreach ( $value['options'][ $enc_key ] as $k => $v ) {
							$temp[ $k ] = urldecode( $v );
						}
						$ovalue = serialize( $temp );
					} else {
						$ovalue = urldecode( $value['options'][ $enc_key ] );
					}
				}
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$cart_meta_table} 
						( cart_id, meta_type, meta_key, meta_value ) VALUES (%d, %s, %s, %s)",
						$cart_id,
						'option',
						$val['name'],
						$ovalue
					)
				);
			}
		}

		if ( $value['advance'] ) {

			foreach ( (array) $value['advance'] as $akey => $avalue ) {
				$advance = $usces->cart->wc_unserialize( $avalue );

				if ( is_array( $advance ) ) {

					if ( isset( $advance[ $post_id ][ $sku_encoded ] ) && is_array( $advance[ $post_id ][ $sku_encoded ] ) ) {
						$akeys = array_keys( $advance[ $post_id ][ $sku_encoded ] );

						foreach ( (array) $akeys as $akey ) {
							if ( is_array( $advance[ $post_id ][ $sku_encoded ][ $akey ] ) ) {
								$avalue = serialize( $advance[ $post_id ][ $sku_encoded ][ $akey ] );
							} else {
								$avalue = $advance[ $post_id ][ $sku_encoded ][ $akey ];
							}
							$wpdb->query(
								$wpdb->prepare(
									"INSERT INTO {$cart_meta_table} 
									( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, 'advance', %s, %s )",
									$cart_id,
									$akey,
									$avalue
								)
							);
						}
					} else {
						$akeys  = array_keys( $advance );
						$akey   = empty( $akeys[0] ) ? 'advance' : $akeys[0];
						$avalue = serialize( $advance );
						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$cart_meta_table} 
								( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, 'advance', %s, %s )",
								$cart_id,
								$akey,
								$avalue
							)
						);
					}
				} else {
					$avalue = urldecode( $avalue );
					$wpdb->query(
						$wpdb->prepare(
							"INSERT INTO {$cart_meta_table} 
							( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, 'advance', %s, %s )",
							$cart_id,
							$akey,
							$avalue
						)
					);
				}
			}
		}

		if ( $usces->is_reduced_taxrate() ) {
			if ( isset( $sku['taxrate'] ) && 'reduced' == $sku['taxrate'] ) {
				$tkey   = 'reduced';
				$tvalue = $usces->options['tax_rate_reduced'];
			} else {
				$tkey   = 'standard';
				$tvalue = $usces->options['tax_rate'];
			}
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$cart_meta_table} 
					( cart_id, meta_type, meta_key, meta_value ) VALUES ( %d, 'taxrate', %s, %s )",
					$cart_id,
					$tkey,
					$tvalue
				)
			);
		}

		do_action( 'usces_action_reg_ordercart_row', $cart_id, $row_index, $value, $args );
	}
}

function filter_mainTitle( $title, $sep = '' ) {
	return fiter_mainTitle( $title, $sep );
}
function fiter_mainTitle($title, $sep = ''){
	global $usces;
	if( empty($sep) ) $sep = '|';

    switch($usces->page){
        case 'cart':
            $newtitle = apply_filters('usces_filter_title_cart', __('In the cart', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'customer':
            $newtitle = apply_filters('usces_filter_title_customer', __('Customer Information', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'delivery':
            $newtitle = apply_filters('usces_filter_title_delivery', __('Shipping / Payment options', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'confirm':
            $newtitle = apply_filters('usces_filter_title_confirm', __('Confirmation', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'ordercompletion':
            $newtitle = apply_filters('usces_filter_title_ordercompletion', __('Order Complete', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'error':
            $newtitle = apply_filters('usces_filter_title_error', __('Error', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name'); //new fitler name
            break;

        case 'search_item':
            $newtitle = apply_filters('usces_filter_title_search_item', __("'AND' search by categories", 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'maintenance':
            $newtitle = apply_filters('usces_filter_title_maintenance', __('Under Maintenance', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'login':
            $newtitle = apply_filters('usces_filter_title_login', __('Log-in for members', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'member':
            $newtitle = apply_filters('usces_filter_title_member', __('Membership information', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'newmemberform':
            $newtitle = apply_filters('usces_filter_title_newmemberform', __('New enrollment form', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'newcompletion':
            $newtitle = apply_filters('usces_filter_title_newcompletion', __('New enrollment complete', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');//new fitler name
            break;

        case 'editmemberform':
            $newtitle = apply_filters('usces_filter_title_editmemberform', __('Member information editing', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');//new fitler name
            break;

        case 'editcompletion':
            $newtitle = apply_filters('usces_filter_title_editcompletion', __('Membership information change is completed', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');//new fitler name
            break;

        case 'lostmemberpassword':
            $newtitle = apply_filters('usces_filter_title_lostmemberpassword', __('The new password acquisition', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'lostcompletion':
            $newtitle = apply_filters('usces_filter_title_lostcompletion', __('New password procedures for obtaining complete', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');//new fitler name
            break;

        case 'changepassword':
            $newtitle = apply_filters('usces_filter_title_changepassword', __('Change password', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        case 'changepasscompletion':
            $newtitle = apply_filters('usces_filter_title_changepasscompletion', __('Password change is completed', 'usces')) . ' ' . $sep . ' ' . get_bloginfo('name');
            break;

        default:
            $newtitle = $title;
    }
	return $newtitle;
}

function usces_document_title_separator( $sep ) {
	$sep = "|";
	return $sep;
}

//Univarsal Analytics( Dashboard )
function usces_Universal_trackPageview(){
	global $usces;

	switch($usces->page){
		case 'cart':
			$push = array();
			$push[] = "'page' : '/wc_cart'";
			break;

		case 'customer':
			$push = array();
			$push[] = "'page' : '/wc_customer'";
			break;

		case 'delivery':
			$push = array();
			$push[] = "'page' : '/wc_delivery'";
			break;

		case 'confirm':
			$push = array();
			$push[] = "'page' : '/wc_confirm'";
			break;

		case 'ordercompletion':
			$push =array();
			$push[] = "'page' : '/wc_ordercompletion'";
			$sesdata =  $usces->cart->get_entry();
			if( isset($sesdata['order']['ID']) && !empty($sesdata['order']['ID']) ){
				$order_id = $sesdata['order']['ID'];
				$data = $usces->get_order_data($order_id, 'direct');
				$cart = unserialize($data['order_cart']);
				$total_price = $usces->get_total_price( $cart ) + $data['order_discount'] - $data['order_usedpoint'];
				if( $total_price < 0 ) $total_price = 0;

				$push[] = "'require', 'ecommerce', 'ecommerce.js'";
				$push[] = "'ecommerce:addTransaction', { 
							id: '". $order_id ."', 
							affiliation: '". get_option('blogname') ."',
							revenue: '". $total_price ."',
							shipping: '". $data['order_shipping_charge'] ."',
							tax: '". $data['order_tax'] ."' }";
				$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
				for( $i=0; $i<$cart_count; $i++ ){
					$cart_row = $cart[$i];
					$post_id  = $cart_row['post_id'];
					$sku = urldecode($cart_row['sku']);
					$quantity = $cart_row['quantity'];
					$itemName = $usces->getItemName($post_id);
					$skuPrice = $cart_row['price'];
					$cats = $usces->get_item_cat_genre_ids( $post_id );
					if( is_array($cats) )
						sort($cats);
					$category = ( isset($cats[0]) ) ? get_cat_name($cats[0]): '';

					$push[] = "'ecommerce:addItem', {
								id: '". $order_id ."',
								sku: '". $sku ."',
								name: '". $itemName."',
								category: '". $category."',
								price: '". $skuPrice."',
								quantity: '". $quantity."' }";
				}
				$push[] = "'ecommerce:send'";
			}
			break;

		case 'error':
			$push = array();
			$push[] = "'page' : '/wc_error'";
			break;

		case 'search_item':
			$push = array();
			$push[] = "'page' : '/wc_search_item'";
			break;

		case 'maintenance':
			$push = array();
			$push[] = "'page' : '/wc_maintenance'";
			break;

		case 'login':
			$push = array();
			$push[] = "'page' : '/wc_login'";
			break;

		case 'member':
			$push = array();
			$push[] = "'page' : '/wc_member'";
			break;

		case 'newmemberform':
			$push = array();
			$push[] = "'page' : '/wc_newmemberform'";
			break;

		case 'newcompletion':
			$push = array();
			$push[] = "'page' : '/wc_newcompletion'";
			break;

		case 'editmemberform':
			$push = array();
			$push[] = "'page' : '/wc_editmemberform'";
			break;

		case 'editcompletion':
			$push = array();
			$push[] = "'page' : '/wc_editcompletion'";
			break;

		case 'lostmemberpassword':
			$push = array();
			$push[] = "'page' : '/wc_lostmemberpassword'";
			break;

		case 'lostcompletion':
			$push = array();
			$push[] = "'page' : '/wc_lostcompletion'";
			break;

		case 'changepassword':
			$push = array();
			$push[] = "'page' : '/wc_changepassword'";
			break;

		case 'changepasscompletion':
			$push = array();
			$push[] = "'page' : '/wc_changepasscompletion'";
			break;

		default:
			$push = array();
			break;
	}
	return $push;
}

//Classic Analytics ( Dashboard )
function usces_Classic_trackPageview(){
	global $usces;

	switch($usces->page){
		case 'cart':
			$push = array();
			$push = usces_trackPageview_cart($push);
			break;

		case 'customer':
			$push = array();
			$push = usces_trackPageview_customer($push);
			break;

		case 'delivery':
			$push = array();
			$push = usces_trackPageview_delivery($push);
			break;

		case 'confirm':
			$push = array();
			$push = usces_trackPageview_confirm($push);
			break;

		case 'ordercompletion':
			$push =array();
			$push = usces_trackPageview_ordercompletion($push);
			break;

		case 'error':
			$push = array();
			$push = usces_trackPageview_error($push);
			break;

		case 'login':
			$push = array();
			$push = usces_trackPageview_login($push);
			break;

		case 'member':
			$push = array();
			$push = usces_trackPageview_member($push);
			break;

		case 'newmemberform':
			$push = array();
			$push = usces_trackPageview_newmemberform($push);
			break;

		case 'newcompletion':
			$push = array();
			$push = usces_trackPageview_newcompletion($push);
			break;

		case 'editmemberform':
			$push = array();
			$push = usces_trackPageview_editmemberform($push);
			break;

		case 'search_item':
			$push = array();
			$push = usces_trackPageview_search_item($push);
			break;

		case 'maintenance':
		case 'editcompletion':
		case 'lostmemberpassword':
		case 'lostcompletion':
		case 'changepassword':
		case 'changepasscompletion':
		default:
			$push = array();
			break;
	}
	return $push;
}

//Univarsal Analytics( Yoast )
function usces_Universal_trackPageview_by_Yoast($push){
	global $usces;

	foreach($push as $p_key => $p_val){
		$pos1 = strpos((string)$p_val, "'send'");
		$pos2 = strpos((string)$p_val, "'pageview'");
		if( $pos1 !== false && $pos2 !== false ){
			unset($push[$p_key]);
		}
	}
	switch($usces->page){
		case 'cart':
			$push[] = "'send', 'pageview', {'page' : '/wc_cart'}";
			break;

		case 'customer':
			$push[] = "'send', 'pageview', {'page' : '/wc_customer'}";
			break;

		case 'delivery':
			$push[] = "'send', 'pageview', {'page' : '/wc_delivery'}";
			break;

		case 'confirm':
			$push[] = "'send', 'pageview', {'page' : '/wc_confirm'}";
			break;

		case 'ordercompletion':
			$push[] = "'send', 'pageview', {'page' : '/wc_ordercompletion'}";
			$sesdata =  $usces->cart->get_entry();
			if( isset($sesdata['order']['ID']) && !empty($sesdata['order']['ID']) ){
				$order_id = $sesdata['order']['ID'];
				$data = $usces->get_order_data($order_id, 'direct');
				$cart = unserialize($data['order_cart']);
				$total_price = $usces->get_total_price( $cart ) + $data['order_discount'] - $data['order_usedpoint'];
				if( $total_price < 0 ) $total_price = 0;

				$push[] = "'require', 'ecommerce', 'ecommerce.js'";
				$push[] = "'ecommerce:addTransaction', { 
								id: '". $order_id ."', 
								affiliation: '". esc_js(get_option('blogname')) ."',
								revenue: '". $total_price ."',
								shipping: '". $data['order_shipping_charge'] ."',
								tax: '". $data['order_tax'] ."'
							}";
				$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
				for( $i=0; $i<$cart_count; $i++ ){
					$cart_row = $cart[$i];
					$post_id  = $cart_row['post_id'];
					$sku = urldecode($cart_row['sku']);
					$quantity = $cart_row['quantity'];
					$itemName = $usces->getItemName($post_id);
					$skuPrice = $cart_row['price'];
					$cats = $usces->get_item_cat_genre_ids( $post_id );
					if( is_array($cats) )
						sort($cats);
					$category = ( isset($cats[0]) ) ? get_cat_name($cats[0]): '';

					$push[] = "'ecommerce:addItem', {
									id: '". $order_id ."',
									sku: '". esc_js($sku) ."',
									name: '". esc_js($itemName)."',
									category: '". esc_js($category)."',
									price: '". $skuPrice."',
									quantity: '". $quantity."'
								}";
				}
				$push[] = "'ecommerce:send'";
			}
			break;

		case 'error':
			$push[] = "'send', 'pageview', {'page' : '/wc_error'}";
			break;

		case 'search_item':
			$push[] = "'send', 'pageview', {'page' : '/wc_search_item'}";
			break;

		case 'maintenance':
			$push[] = "'send', 'pageview', {'page' : '/wc_maintenance'}";
			break;

		case 'login':
			$push[] = "'send', 'pageview', {'page' : '/wc_login'}";
			break;

		case 'member':
			$push[] = "'send', 'pageview', {'page' : '/wc_member'}";
			break;

		case 'newmemberform':
			$push[] = "'send', 'pageview', {'page' : '/wc_newmemberform'}";
			break;

		case 'newcompletion':
			$push[] = "'send', 'pageview', {'page' : '/wc_newcompletion'}";
			break;

		case 'editmemberform':
			$push[] = "'send', 'pageview', {'page' : '/wc_editmemberform'}";
			break;

		case 'editcompletion':
			$push[] = "'send', 'pageview', {'page' : '/wc_editcompletion'}";
			break;

		case 'lostmemberpassword':
			$push[] = "'send', 'pageview', {'page' : '/wc_lostmemberpassword'}";
			break;

		case 'lostcompletion':
			$push[] = "'send', 'pageview', {'page' : '/wc_lostcompletion'}";
			break;

		case 'changepassword':
			$push[] = "'send', 'pageview', {'page' : '/wc_changepassword'}";
			break;

		case 'changepasscompletion':
			$push[] = "'send', 'pageview', {'page' : '/wc_changepasscompletion'}";
			break;

		default:
			$push[] = "'send', 'pageview'";
			break;
	}
	return $push;
}

function usces_ecommerce_reporting(){
	global $usces;

	if($usces->page !== 'ordercompletion') return;

	$sesdata =  $usces->cart->get_entry();
	if( isset($sesdata['order']['ID']) && !empty($sesdata['order']['ID']) ){
		$order_id = $sesdata['order']['ID'];
		$data = $usces->get_order_data($order_id, 'direct');
		$cart = unserialize($data['order_cart']);
		$total_price = $usces->get_total_price( $cart ) + $data['order_discount'] - $data['order_usedpoint'];
		if( $total_price < 0 ) $total_price = 0;
		$ecommerce_reporting = "__gtagTracker('event', 'purchase', {
			transaction_id: '". $order_id ."', 
			affiliation: '". esc_js(get_option('blogname')) ."',
			value: '". $total_price ."',
			currency: '". $usces->get_currency_code() . "',
			tax: '". $data['order_tax'] ."',
			shipping: '". $data['order_shipping_charge'] ."',
			items: [";

		$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
		for( $i=0; $i<$cart_count; $i++ ){
			$cart_row = $cart[$i];
			$post_id  = $cart_row['post_id'];
			$sku = urldecode($cart_row['sku']);
			$quantity = $cart_row['quantity'];
			$itemName = $usces->getItemName($post_id);
			$skuPrice = $cart_row['price'];
			$cats = $usces->get_item_cat_genre_ids( $post_id );
			if( is_array($cats) )
				sort($cats);
			$category = ( isset($cats[0]) ) ? get_cat_name($cats[0]): '';

			$ecommerce_reporting .= "{
							id: '". $post_id ."',
							sku: '". esc_js($sku) ."',
							name: '". esc_js($itemName)."',
							category: '". esc_js($category)."',
							price: '". $skuPrice."',
							quantity: '". $quantity."'
						},";
		}
		$ecommerce_reporting .= "]});";

		$monsterinsights_current_version = usces_MonsterInsights_get_version();
		if ( version_compare( $monsterinsights_current_version, '8.0.0', '>=' ) ) {
			wp_register_script( 'after-monsterinsights-frontend-script', '', array( 'monsterinsights-frontend-script' ) );
			wp_enqueue_script( 'after-monsterinsights-frontend-script' );
			wp_add_inline_script( 'after-monsterinsights-frontend-script', $ecommerce_reporting );
		} else {
			echo $ecommerce_reporting;
		}
	}
}
function usces_Universal_trackPageview_by_monsterInsight( $push ) {
	global $usces;

	$monsterinsights_current_version = usces_MonsterInsights_get_version();
	if ( version_compare( $monsterinsights_current_version, '8.0.0', '>=' ) ) {
		switch ( $usces->page ) {
			case 'cart':
				$push['page_path'] = 'wc_cart';
				break;

			case 'customer':
				$push['page_path'] = 'wc_customer';
				break;

			case 'delivery':
				$push['page_path'] = 'wc_delivery';
				break;

			case 'confirm':
				$push['page_path'] = 'wc_confirm';
				break;

			case 'ordercompletion':
				$push['page_path'] = 'wc_ordercompletion';
				break;

			case 'error':
				$push['page_path'] = 'wc_error';
				break;

			case 'search_item':
				$push['page_path'] = 'wc_search_item';
				break;

			case 'maintenance':
				$push['page_path'] = 'wc_maintenance';
				break;

			case 'login':
				$push['page_path'] = 'wc_login';
				break;

			case 'member':
				$push['page_path'] = 'wc_member';
				break;

			case 'newmemberform':
				$push['page_path'] = 'wc_newmemberform';
				break;

			case 'newcompletion':
				$push['page_path'] = 'wc_newcompletion';
				break;

			case 'editmemberform':
				$push['page_path'] = 'wc_editmemberform';
				break;

			case 'editcompletion':
				$push['page_path'] = 'wc_editcompletion';
				break;

			case 'lostmemberpassword':
				$push['page_path'] = 'wc_lostmemberpassword';
				break;

			case 'lostcompletion':
				$push['page_path'] = 'wc_lostcompletion';
				break;

			case 'changepassword':
				$push['page_path'] = 'wc_changepassword';
				break;

			case 'changepasscompletion':
				$push['page_path'] = 'wc_changepasscompletion';
				break;

			default: // keep origin page path
				break;
		}
	} else {
		switch ( $usces->page ) {
			case 'cart':
				$push['page_path'] = "'/wc_cart'";
				break;

			case 'customer':
				$push['page_path'] = "'/wc_customer'";
				break;

			case 'delivery':
				$push['page_path'] = "'/wc_delivery'";
				break;

			case 'confirm':
				$push['page_path'] = "'/wc_confirm'";
				break;

			case 'ordercompletion':
				$push['page_path'] = "'/wc_ordercompletion'";
				break;

			case 'error':
				$push['page_path'] = "'/wc_error'";
				break;

			case 'search_item':
				$push['page_path'] = "'/wc_search_item'";
				break;

			case 'maintenance':
				$push['page_path'] = "'/wc_maintenance'";
				break;

			case 'login':
				$push['page_path'] = "'/wc_login'";
				break;

			case 'member':
				$push['page_path'] = "'/wc_member'";
				break;

			case 'newmemberform':
				$push['page_path'] = "'/wc_newmemberform'";
				break;

			case 'newcompletion':
				$push['page_path'] = "'/wc_newcompletion'";
				break;

			case 'editmemberform':
				$push['page_path'] = "'/wc_editmemberform'";
				break;

			case 'editcompletion':
				$push['page_path'] = "'/wc_editcompletion'";
				break;

			case 'lostmemberpassword':
				$push['page_path'] = "'/wc_lostmemberpassword'";
				break;

			case 'lostcompletion':
				$push['page_path'] = "'/wc_lostcompletion'";
				break;

			case 'changepassword':
				$push['page_path'] = "'/wc_changepassword'";
				break;

			case 'changepasscompletion':
				$push['page_path'] = "'/wc_changepasscompletion'";
				break;

			default: // keep origin page path
				break;
		}
	}
	return $push;
}
//Classic Analytics ( Yoast )
function usces_Classic_trackPageview_by_Yoast($push){
	global $usces;

	foreach($push as $p_key => $p_val){
		$pos1 = strpos((string)$p_val, "'_trackPageview");
		if( $pos1 !== false ){
			unset($push[$p_key]);
		}
	}
	switch($usces->page){
		case 'cart':
			$push[] = "'_trackPageview', '/wc_cart'";
			break;

		case 'customer':
			$push[] = "'_trackPageview', '/wc_customer'";
			break;

		case 'delivery':
			$push[] = "'_trackPageview', '/wc_delivery'";
			break;

		case 'confirm':
			$push[] = "'_trackPageview', '/wc_confirm'";
			break;

		case 'ordercompletion':
			global $usces;

			$push[] = "'_trackPageview','/wc_ordercompletion'";
			$sesdata = $usces->cart->get_entry();
			if( isset($sesdata['order']['ID']) && !empty($sesdata['order']['ID']) ){
				$order_id = $sesdata['order']['ID'];
				$data = $usces->get_order_data($order_id, 'direct');
				$cart = unserialize($data['order_cart']);
				$total_price = $usces->get_total_price( $cart ) + $data['order_discount'] - $data['order_usedpoint'];
				if( $total_price < 0 ) $total_price = 0;

				$push[] = "'_addTrans', '" . $order_id . "', '" . esc_js(get_option('blogname')) . "', '" . $total_price . "', '" . $data['order_tax'] . "', '" . $data['order_shipping_charge'] . "', '" . esc_js($data['order_address1'].$data['order_address2']) . "', '" . esc_js($data['order_pref']) . "', '" . get_locale() . "'";
				$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
				for($i=0; $i<$cart_count; $i++) {
					$cart_row = $cart[$i];
					$post_id = $cart_row['post_id'];
					$sku = urldecode($cart_row['sku']);
					$quantity = $cart_row['quantity'];
					$itemName = $usces->getItemName($post_id);
					$skuPrice = $cart_row['price'];
					$cats = $usces->get_item_cat_genre_ids( $post_id );
					if( is_array($cats) )
						sort($cats);
					$category = ( isset($cats[0]) ) ? get_cat_name($cats[0]): '';
					$push[] = "'_addItem', '" . $order_id . "', '" . esc_js($sku) . "', '" . esc_js($itemName) . "', '" . esc_js($category) . "', '" . $skuPrice . "', '" . $quantity . "'";
				}
				$push[] = "'_trackTrans'";
			}
			break;

		case 'error':
			$push[] = "'_trackPageview', '/wc_error'";
			break;

		case 'login':
			$push[] = "'_trackPageview', '/wc_login'";
			break;

		case 'member':
			$push[] = "'_trackPageview', '/wc_member'";
			break;

		case 'newmemberform':
			$push[] = "'_trackPageview', '/wc_newmemberform'";
			break;

		case 'newcompletion':
			$push[] = "'_trackPageview', '/wc_newcompletion'";
			break;

		case 'editmemberform':
			$push[] = "'_trackPageview', '/wc_editmemberform'";
			break;

		case 'search_item':
			$push[] = "'_trackPageview', '/wc_search_item'";
			break;

		case 'maintenance':
			$push[] = "'_trackPageview', '/wc_maintenance'";
			break;

		case 'editcompletion':
			$push[] = "'_trackPageview', '/wc_editcompletion'";
			break;

		case 'lostmemberpassword':
			$push[] = "'_trackPageview', '/wc_lostmemberpassword'";
			break;

		case 'lostcompletion':
			$push[] = "'_trackPageview', '/wc_lostcompletion'";
			break;

		case 'changepassword':
			$push[] = "'_trackPageview', '/wc_changepassword'";
			break;

		case 'changepasscompletion':
			$push[] = "'_trackPageview', '/wc_changepasscompletion'";
			break;

		default:
			$push[] = "'_trackPageview'";
			break;
	}
	return $push;
}
function usces_monsterinsights() {
	$monsterinsights_current_version = usces_MonsterInsights_get_version();
	if ( version_compare( $monsterinsights_current_version, '6.0.11', '>' ) ) {
		add_filter( 'monsterinsights_frontend_tracking_options_analytics_end', 'usces_Universal_trackPageview_by_Yoast' );
		add_filter( 'monsterinsights_frontend_tracking_options_gtag_end', 'usces_Universal_trackPageview_by_monsterInsight' );
		if ( version_compare( $monsterinsights_current_version, '8.0.0', '>=' ) ) {
			add_action( 'wp_enqueue_scripts', 'usces_ecommerce_reporting', 10 );
		} else {
			add_action( 'monsterinsights_frontend_tracking_gtag_after_pageview', 'usces_ecommerce_reporting' );
		}
	} else {
		add_filter( 'yoast-ga-push-array-universal', 'usces_Universal_trackPageview_by_Yoast' );
		add_filter( 'yoast-ga-push-array-ga-js', 'usces_Classic_trackPageview_by_Yoast' );
	}
}
function usces_monsterinsights_get_version() {
	if ( ! function_exists( 'MonsterInsights_Pro' ) && ! function_exists( 'MonsterInsights_Lite' ) ) {
		return;
	}
	$instance = class_exists( 'MonsterInsights' ) ? MonsterInsights_Pro() : MonsterInsights_Lite();
	return $instance->version;
}

function usces_order_memo_form_detail_top( $data, $csod_meta ){
	global $usces;

	$order_memo = '';
	if( !empty($data['ID']) ){
		$order_memo = $usces->get_order_meta_value('order_memo', $data['ID']);
	}
	$res = '<tr>
				<td class="label border">'. __('Administrator Note', 'usces') .'</td>
				<td colspan="5" class="col1 border memo">
					<textarea name="order_memo" class="order_memo">'.esc_html($order_memo).'</textarea>
				</td>
			</tr>';
	echo $res;
}

function usces_update_order_memo($new_orderdata){
	global $usces;

	if( isset($_POST['order_memo']))
		$usces->set_order_meta_value('order_memo', $_POST['order_memo'], $new_orderdata->ID);
}

function usces_register_order_memo( $args ) {
	global $usces;
	/*
	$args = array(
	'cart'=>$cart, 'entry'=>$entry, 'order_id'=>$order_id, 'member_id'=>$member['ID'],
	'payments'=>$set, 'charging_type'=>$charging_type);
	*/
	extract($args);

	if( isset($_POST['order_memo']) && $order_id ) {
		$usces->set_order_meta_value( 'order_memo', $_POST['order_memo'], $order_id );
	}
}

function usces_add_tracking_number_field($data, $cscs_meta, $action_args){
	global $usces;
	$locale = get_locale();

	$deli_comps = array('クロネコヤマト', 'ヤマト運輸', '佐川急便', '日本通運', 'ゆうパック', '日本郵便', '郵便書留', '西濃運輸', '福山通運', '名鉄運輸', '新潟運輸', 'トナミ運輸', '第一貨物', '飛騨倉庫運輸', '西武運輸', 'クリックポスト', 'トールエクスプレス', 'セイノーエクスプレス', '信州名鉄運輸', '大川配送サービス', 'その他');
	$deli_comps = apply_filters( 'usces_filter_deli_comps', $deli_comps );

	$tracking_number = '';
	if( !empty($data['ID']) ){
		$tracking_number = $usces->get_order_meta_value(apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number'), $data['ID']);
		$delivery_company = $usces->get_order_meta_value('delivery_company', $data['ID']);
	}else{
		$tracking_number = '';
		$delivery_company = '';
	}
	$res = '';
	$res .= '
			<tr>
				<td class="label">' . __('Delivery company', 'usces') . '</td>
				<td class="col1">';
	if( 'ja' == $locale ){
		$res .= '		<select name="delivery_company" style="width:100%;" >'."\n";
			$res .= '<option value="">' . __('-- Select --', 'usces') . '</option>' . "\n";
		foreach( $deli_comps as $comp ){
			$res .= '<option value="' . esc_attr($comp) . '"' . ($comp == $delivery_company ? ' selected="selected"' : ''). '>' . esc_html($comp) . '</option>' . "\n";
		}
		$res .= '		</select>'."\n";
	}else{
		$res .= '		<input name="delivery_company" type="text" style="width:100%;" value="' . esc_attr($delivery_company). '">';
	}
	$res .= '	</td>
			</tr>';
	$res .= '<tr>
				<td class="label">' . __('Tracking number', 'usces') . '</td>
				<td class="col1">
					<input name="tracking_number" type="text" style="width:100%;" value="' . esc_attr($tracking_number) . '">
				</td>
			</tr>
			';
	echo $res;
}

function usces_update_tracking_number($new_orderdata){
	global $usces;

	if( isset($_POST['tracking_number']))
		$usces->set_order_meta_value(apply_filters( 'usces_filter_tracking_meta_key', 'tracking_number'), $_POST['tracking_number'], $new_orderdata->ID);

	if( isset($_POST['delivery_company']))
		$usces->set_order_meta_value('delivery_company', $_POST['delivery_company'], $new_orderdata->ID);
}

// function get_jquery_version(){
// 	global $wp_version;
// 	// Get jquery handle - WP 3.6 or newer changed the jQuery handle (once we're on 3.6+ we can remove this logic)
// 	$jquery_handle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';
// 	// returm the WP built-in version
// 	return $GLOBALS['wp_scripts']->registered[$jquery_handle]->ver ?? '1.11.2';
// }

function usces_admin_enqueue_scripts( $hook_suffix ){
	global $wp_scripts;
	if( false !== strpos($hook_suffix, 'usc-e-shop')
		|| false !== strpos($hook_suffix, 'welcart')
		|| false !== strpos($hook_suffix, 'usces')
	){
		// $jquery_version = get_jquery_version();

		/*
		If jquery version start with 3x -> load jquery ui 1.12.1 stylesheet
		another version -> load jquery ui 1.11.2 stylesheet
		*/
		// if(0 === strpos($jquery_version, '3.')){
		// 	$style_jqueryui_url = USCES_FRONT_PLUGIN_URL.'/css/jquery/jquery-ui-1.12.1.min.css';
		// 	wp_enqueue_style( 'jquery-ui-welcart', $style_jqueryui_url, array(), '1.12.1', 'all' );
		// } else {
		// 	$style_jqueryui_url = USCES_FRONT_PLUGIN_URL.'/css/jquery/jquery-ui-1.11.2.min.css';
		// 	wp_enqueue_style( 'jquery-ui-welcart', $style_jqueryui_url, array(), '1.11.2', 'all' );
		// }
		$ui = $wp_scripts->query( 'jquery-ui-core' );
		$ui_themes = apply_filters( 'usces_filter_jquery_ui_themes_welcart', 'smoothness' );
		wp_enqueue_style( 'jquery-ui-welcart', "//code.jquery.com/ui/{$ui->ver}/themes/{$ui_themes}/jquery-ui.css" );
	}
	//if( 'welcart-shop_page_usces_settlement' == $hook_suffix ){
	//	$shop_page_usces_settlement = USCES_FRONT_PLUGIN_URL.'/js/usces_admin_settlement.js';
	//	wp_enqueue_script( 'shop_page_usces_settlement', $shop_page_usces_settlement, array(), '1.4.11', true );
	//}
	if(
		'welcart-management_page_usces_memberlist' == $hook_suffix
		|| 'toplevel_page_usces_orderlist' == $hook_suffix

	){
		$path = USCES_FRONT_PLUGIN_URL.'/js/jquery/jquery.cookie.js';
		wp_enqueue_script( 'usces_member_cookie', $path, array('jquery'), USCES_VERSION, true );
	}
}

function usces_schedules_intervals( $schedules ){
	$schedules['weekly'] = array(
		'interval' => 604800,
		'display' => 'Weekly'
	);
	return $schedules;
}

function usces_responce_wcsite() {
	$my_wcid = get_option('usces_wcid');


	if( isset($_POST['sname']) && isset($_POST['wcid']) && '54.64.221.23' == $_SERVER['REMOTE_ADDR'] ){
		$data['usces'] = get_option('usces');
		$data['usces_settlement_selected'] = get_option('usces_settlement_selected');
		$res = json_encode($data);
		header( 'Content-Type: application/json' );
		echo $res;
		exit;
	}
}

function usces_wcsite_activate(){
	$usces = get_option('usces');
	$metas['usces_company_name'] = $usces['company_name'];
	$metas['usces_inquiry_mail'] = $usces['inquiry_mail'];
	$metas['usces_base_country'] = $usces['system']['base_country'];
	$acting_settings = get_option('usces_settlement_selected', array() );
	$metas['usces_used_sett'] = '';
	foreach( $acting_settings as $acting ){
			$metas['usces_used_sett'] .= $acting . ',';
	}
	$metas['usces_used_sett'] = trim( $metas['usces_used_sett'], ',' );

	$base_metas = array(
		'wcid' => get_option('usces_wcid'),
		'wchost' => $_SERVER['SERVER_NAME'],
		'refer' => get_option('home'),
		'act' => 1,
	);
	$params = array_merge($base_metas, $metas);
	usces_wcsite_connection($params);
}

function usces_wcsite_deactivate(){
	$params = array(
		'wcid' => get_option('usces_wcid'),
		'wchost' => $_SERVER['SERVER_NAME'],
		'refer' => get_option('home'),
		'act' => 0,
	);
	usces_wcsite_connection($params);
}

function usces_session_cache_limiter(){
	global $usces;

	if( $usces->is_cart_page($_SERVER['REQUEST_URI']) && isset( $_REQUEST['usces_page'] ) && 'search_item' == $_REQUEST['usces_page'] ){
		session_cache_limiter('private_no_expire');
	}
}

function usces_action_login_page_liwpp(){
	$options = get_option('usces');
	if( !isset($options['acting_settings']['paypal']['set_liwp'])
	|| 'off' == $options['acting_settings']['paypal']['set_liwp']
	|| usces_is_login()
	){ return; }

	$ulr = add_query_arg(
		array( 'state'=>urlencode('liwppact=request&liwppref=login&liwpp_nonce='.wp_create_nonce('liwpp') ) ), home_url('/')
	 );

	$html = '<div class="liwpp_area">';
	$html .= '<a href="' . $ulr . '" title="' . __('Login with PayPal', 'usces') . '" class="liwpp_button"><img src="' . USCES_PLUGIN_URL . '/images/loginwithpaypalbutton.png" /></a>' . "<br />";
	$html .= __('You can log in with your PayPal account.', 'usces') . "</div>\n";

	echo $html;
}

function usces_filter_login_page_liwpp( $html ){
	$options = get_option('usces');
	if( !isset($options['acting_settings']['paypal']['set_liwp'])
	|| 'off' == $options['acting_settings']['paypal']['set_liwp']
	|| usces_is_login()
	){ return $html; }

	$ulr = add_query_arg(
		array( 'state'=>urlencode('liwppact=request&liwppref=login&liwpp_nonce='.wp_create_nonce('liwpp') ) ), home_url('/')
	 );

	$html .= '<div class="liwpp_area">';
	$html .= '<a href="' . $ulr . '" title="' . __('Login with PayPal', 'usces') . '" class="liwpp_button"><img src="' . USCES_PLUGIN_URL . '/images/loginwithpaypalbutton.png" /></a>' . "<br />";
	$html .= __('You can log in with your PayPal account.', 'usces') . "</div>\n";

	return $html;
}

function usces_action_customer_page_liwpp(){
	$options = get_option('usces');
	if( !isset($options['acting_settings']['paypal']['set_liwp'])
	|| 'off' == $options['acting_settings']['paypal']['set_liwp']
	|| usces_is_login()
	){ return; }

	$ulr = add_query_arg(
		array( 'state'=>urlencode('liwppact=request&liwppref=usces_cart&liwpp_nonce='.wp_create_nonce('liwpp') ) ), home_url('/')
	 );

	$html = '<div class="liwpp_area">';
	$html .= '<a href="' . $ulr . '" title="' . __('Login with PayPal', 'usces') . '" class="liwpp_button"><img src="' . USCES_PLUGIN_URL . '/images/loginwithpaypalbutton.png" /></a>' . "<br />";
	$html .= __('You can log in with your PayPal account.', 'usces') . "</div>\n";

	echo $html;
}

function usces_filter_customer_page_liwpp( $html ){
	$options = get_option('usces');
	if( !isset($options['acting_settings']['paypal']['set_liwp'])
	|| 'off' == $options['acting_settings']['paypal']['set_liwp']
	|| usces_is_login()
	){ return $html; }

	$ulr = add_query_arg(
		array( 'state'=>urlencode('liwppact=request&liwppref=usces_cart&liwpp_nonce='.wp_create_nonce('liwpp') ) ), home_url('/')
	 );

	$html .= '<div class="liwpp_area">';
	$html .= '<a href="' . $ulr . '" title="' . __('Login with PayPal', 'usces') . '" class="liwpp_button"><img src="' . USCES_PLUGIN_URL . '/images/loginwithpaypalbutton.png" /></a>' . "<br />";
	$html .= __('You can log in with your PayPal account.', 'usces') . "</div>\n";

	return $html;
}

function usces_filter_login_widget_liwpp( $html ){
	$options = get_option('usces');
	if( !isset($options['acting_settings']['paypal']['set_liwp'])
	|| 'off' == $options['acting_settings']['paypal']['set_liwp']
	|| usces_is_login()
	){ return $html; }

	$ulr = add_query_arg(
		array( 'state'=>urlencode('liwppact=request&liwppref=login&liwpp_nonce='.wp_create_nonce('liwpp') ) ), home_url('/')
	 );

	$html .= '<div class="liwpp_area">';
	$html .= '<a href="' . $ulr . '" title="' . __('Login with PayPal', 'usces') . '" class="liwpp_button"><img src="' . USCES_PLUGIN_URL . '/images/loginwithpaypalbutton.png" /></a>' . "<br />";
	$html .= __('You can log in with your PayPal account.', 'usces') . "</div>\n";

	return $html;
}

function usces_login_width_paypal(){
	global $usces;
	$options = get_option('usces');


	if( !isset($_GET['state']) ){
		return;
	}

	$state = urldecode($_GET['state']);
	parse_str($state, $parts);

	if( !isset($parts['liwppact']) ){
		return;
	}
	if( !isset($parts['liwpp_nonce']) || !wp_verify_nonce( $parts['liwpp_nonce'], 'liwpp' ) ){
		return;
	}

	require_once( USCES_PLUGIN_DIR . "/functions/paypal_login_width.php");

	if( isset( $parts['liwppref']) && 'usces_cart' == $parts['liwppref'] ){
		$CALLBACK_URL = home_url('/');
	}else{
		$CALLBACK_URL = home_url('/');
	}

	if($options['acting_settings']['paypal']['sandbox'] == 1){
		$liwp_client_id = $options['acting_settings']['paypal']['liwp_client_id_sand'];
		$liwp_secret = $options['acting_settings']['paypal']['liwp_secret_sand'];
	}else{
		$liwp_client_id = $options['acting_settings']['paypal']['liwp_client_id'];
		$liwp_secret = $options['acting_settings']['paypal']['liwp_secret'];
	}

	$action = $parts['liwppact'];

	switch( $action ){

	case 'request':
		$auth_url = sprintf("%s?scope=%s&response_type=code&redirect_uri=%s&client_id=%s&state=%s&nonce=%s",
					$options['acting_settings']['paypal']['liwp_authorize'],
					'profile+email+address+phone+https%3A%2F%2Furi.paypal.com%2Fservices%2Fpaypalattributes+'.urlencode('https://uri.paypal.com/services/expresscheckout'),
					urlencode($CALLBACK_URL),
					$liwp_client_id,
					urlencode('liwppact=liwpp&liwppref=' . $parts['liwppref'] . '&liwpp_nonce='.wp_create_nonce('liwpp') ),
					time().base64_encode ( mt_rand() )
					);
		header("Location: $auth_url");
		exit;
		break;

	case 'liwpp':
		//capture code from auth
		$code = $_GET["code"];
		if( !$code ){
			wp_redirect(add_query_arg( array('liwppact'=>'error1'), USCES_LOGIN_URL));
			exit;
		}

		//construct POST object for access token fetch request
		$postvals = sprintf("client_id=%s&client_secret=%s&grant_type=authorization_code&code=%s&redirect_uri=%s",
					$liwp_client_id, $liwp_secret, $code, urlencode($CALLBACK_URL));

		//get JSON access token object (with refresh_token parameter)
		$token = json_decode(usces_run_curl($options['acting_settings']['paypal']['liwp_tokenservice'], 'POST', $postvals));
		usces_log('liwpp_liwppact_token : '.print_r($token, true), 'acting_transaction.log');

		//construct URI to fetch profile information for current user
		$profile_url = sprintf("%s?schema=openid&oauth_token=%s", $options['acting_settings']['paypal']['liwp_userinfo'], $token->access_token);

		//fetch profile of current user
		$profile = usces_run_curl($profile_url);
		$profile = json_decode($profile);
		usces_log('liwpp_profile : '.print_r($profile, true), 'acting_transaction.log');

		if( !$profile->email ){
			wp_redirect(add_query_arg( array('liwppact'=>'error2'), USCES_LOGIN_URL));
			exit;
		}

		$_SESSION['liwpp'] = array( 'token'=>$token->access_token, 'profile'=>$profile);
		$_SESSION['usces_member']['mailaddress1'] = $profile->email;
		$_SESSION['usces_member']['mailaddress2'] = $profile->email;
		$_SESSION['usces_member']['name1'] = $profile->family_name;
		$_SESSION['usces_member']['name2'] = $profile->given_name;
		$_SESSION['usces_member']['zipcode'] = $profile->address->postal_code;
		$_SESSION['usces_member']['pref'] = $profile->address->region;
		$_SESSION['usces_member']['address1'] = $profile->address->locality;
		$_SESSION['usces_member']['address2'] = $profile->address->street_address;
		$_SESSION['usces_member']['tel'] = $profile->phone_number;
		$_SESSION['usces_member']['country'] = $profile->address->country;

		if( usces_login_with_openid($profile->email) ){
			if( isset( $parts['liwppref']) && 'usces_cart' == $parts['liwppref'] ){
				wp_redirect(USCES_CUSTOMER_URL);
				exit;
			}else{
				wp_redirect(USCES_MEMBER_URL);
				exit;
			}

		}else{
			wp_redirect(USCES_NEWMEMBER_URL);
			exit;
		}
		break;
	}
}

function usces_atobarai_each_availability( $second_section, $post_id ){

	$product = wel_get_product( $post_id );

	$deferred_payment_propriety = (int) $product['atobarai_propriety'];

	$second_section .= '
	<tr>
		<th>' . __('Atobarai Propriety', 'usces') . '</th>
		<td>
			<label for="deferred_payment_propriety0"><input name="deferred_payment_propriety" id="deferred_payment_propriety0" type="radio" value="0"' . (!$deferred_payment_propriety ? ' checked="checked"' : '') . '>' . __('available', 'usces') . '</label>
			<label for="deferred_payment_propriety1"><input name="deferred_payment_propriety" id="deferred_payment_propriety1" type="radio" value="1"' . ($deferred_payment_propriety ? ' checked="checked"' : '') . '>' . __('not available', 'usces') . '</label>
		</td>
	</tr>
	';
	return $second_section;
}

function usces_atobarai_update_each_availability( $post_id, $post ){

	// This process has moved to item_post.php 1568.

	// if(isset($_POST['deferred_payment_propriety'])){
	// 	$deferred_payment_propriety = (int)$_POST['deferred_payment_propriety'];
	// 	update_post_meta($post_id, 'atobarai_propriety', $deferred_payment_propriety);
	// }
}

function usces_instance_settlement(){
	global $usces;
	$usces->settlement->setup();
	// $zeus_settlement = ZEUS_SETTLEMENT::get_instance();
	// $escott_settle = ESCOTT_SETTLEMENT::get_instance();
	// $yahoowallet_settle = new YAHOOWALLET_SETTLEMENT();
	// $epsilon_settlement = new EPSILON_SETTLEMENT();
	// $welcartpay_settlement = WELCARTPAY_SETTLEMENT::get_instance();
	// $remise_settlement = REMISE_SETTLEMENT::get_instance();
	// $sbps_settlement = SBPS_SETTLEMENT::get_instance();
	// $dsk_settlement = DSK_SETTLEMENT::get_instance();
	// $paypal_ec = PAYPAL_EC_SETTLEMENT::get_instance();
	// $paypal_wpp = PAYPAL_WPP_SETTLEMENT::get_instance();
	// $jpayment_settlement = JPAYMENT_SETTLEMENT::get_instance();
	// $telecom_settlement = TELECOM_SETTLEMENT::get_instance();
	// $digitalcheck_settlement = DIGITALCHECK_SETTLEMENT::get_instance();
	// $mizuho_settlement = MIZUHO_SETTLEMENT::get_instance();
	// $anotherlane_settlement = ANOTHERLANE_SETTLEMENT::get_instance();
	// $veritrans_settlement = VERITRANS_SETTLEMENT::get_instance();
	// $paygent_settlement = PAYGENT_SETTLEMENT::get_instance();
	// $paypal_cp = PAYPAL_CP_SETTLEMENT::get_instance();
}

function usces_instance_extentions()
{
	global $ganbare_tencho, $order_stock_linkage, $data_list_upgrade, $data_google_recaptcha, $brute_force;

	require_once(USCES_EXTENSIONS_DIR . "/GanbareTencho/GanbareTencho.class.php");
	$ganbare_tencho = new USCES_GANBARE_TENCHO();
	require_once(USCES_EXTENSIONS_DIR . "/OrderStockLinkage/order_stock_linkage.php");
	$order_stock_linkage = new USCES_STOCK_LINKAGE();
	require_once(USCES_EXTENSIONS_DIR . "/DataListUpgrade/data_list_upgrade.php");
	$data_list_upgrade = new USCES_DATALIST_UPGRADE();
	require_once(USCES_EXTENSIONS_DIR . "/VerifyMembersEmail/verify_members_email.php");
	$data_list_upgrade = new USCES_VERIFY_MEMBERS_EMAIL();
	require_once(USCES_EXTENSIONS_DIR . "/GoogleRecaptcha/google_recaptcha.php");
	$data_google_recaptcha = new USCES_GOOGLE_RECAPTCHA();

	require_once(USCES_EXTENSIONS_DIR . "/BruteForceCountermeasures/brute_force_countermeasures.php");
	$brute_force = new USCES_BRUTE_FORCE_COUNTER_MEASURES();

	require_once(USCES_EXTENSIONS_DIR . '/StructuredDataProduct/structured_data_product.php');
	$brute_force = new USCES_STRUCTURED_DATA_PRODUCT();

	require_once( USCES_EXTENSIONS_DIR . "/NewProductImageRegister/class-new-product-image-register.php" );
	$new_product_image_register = new NEW_PRODUCT_IMAGE_REGISTER();
}

function usces_get_attachment_image_attributes( $attr, $attachment, $size ) {
	global $usces;
	if( $usces->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $usces->is_inquiry_page($_SERVER['REQUEST_URI']) ) {
		if( $usces->use_ssl && isset($attr['srcset']) ) {
			$srcset = $attr['srcset'];
			$attr['srcset'] = str_replace( get_option('siteurl'), USCES_SSL_URL_ADMIN, $srcset );
		}
	}
	return $attr;
}

function usces_ssl_charm() {
	global $usces;
	if( $usces->use_ssl && ( $usces->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $usces->is_inquiry_page($_SERVER['REQUEST_URI']) ) ) {
		if( function_exists( 'usces_ob_callback' ) ) {
			ob_start( 'usces_ob_callback' );
		} else {
			ob_start( 'usces_ob_rewrite' );
		}
	}
}

function usces_ob_rewrite( $buffer ) {
	$pattern = array(
		'|(<[^<]*)href=\"'.get_option('siteurl').'([^>]*)\.css([^>]*>)|',
		'|(<[^<]*)src=\"'.get_option('siteurl').'([^>]*>)|'
	);
	$replacement = array(
		'${1}href="'.USCES_SSL_URL_ADMIN.'${2}.css${3}',
		'${1}src="'.USCES_SSL_URL_ADMIN.'${2}'
	);
	$buffer = preg_replace( $pattern, $replacement, $buffer );
	return $buffer;
}

function usces_wp_enqueue_scripts(){
	global $usces;
	$no_cart_css = isset($usces->options['system']['no_cart_css']) ? $usces->options['system']['no_cart_css'] : 0;

	wp_enqueue_style( 'usces_default_css', USCES_FRONT_PLUGIN_URL . '/css/usces_default.css', array(), USCES_VERSION );
	wp_enqueue_style('dashicons');

	if( !$no_cart_css ){
		wp_enqueue_style( 'usces_cart_css', USCES_FRONT_PLUGIN_URL . '/css/usces_cart.css', array('usces_default_css'), USCES_VERSION );
	}

	$theme_version = defined( 'USCES_THEME_VERSION' ) ? USCES_THEME_VERSION : USCES_VERSION;
	if( file_exists(get_stylesheet_directory() . '/usces_cart.css') ){
		wp_enqueue_style( 'theme_cart_css', get_stylesheet_directory_uri() . '/usces_cart.css', array('usces_default_css'), $theme_version );
	}

	if( $usces->is_cart_or_member_page($_SERVER['REQUEST_URI']) ) {

		if( isset($usces->options['address_search']) && $usces->options['address_search'] == 'activate' ) {
			wp_enqueue_script( 'usces_ajaxzip3', "https://ajaxzip3.github.io/ajaxzip3.js" );
		}

		if( 'confirm' == $usces->page ){
			$cart_comfirm = USCES_FRONT_PLUGIN_URL . '/js/cart_confirm.js';
			wp_enqueue_script( 'usces_cart_comfirm', $cart_comfirm, array('jquery'), current_time('timestamp') );
		}
	}
}

function welcart_confirm_check_ajax(){
	$nonce = isset( $_POST['wc_nonce'] ) ? $_POST['wc_nonce'] : '';
	$action = isset( $_POST['action'] ) ? $_POST['action'] : '';

	if( 'welcart_confirm_check' != $action )
		die('not permitted1');

	if( !wp_verify_nonce( $nonce, 'wc_confirm') )
		die('not permitted2');

	global $usces;
	$current['entry'] = $usces->cart->get_entry();
	$current['cart'] = $usces->cart->get_cart();
	$condition = $_POST['wc_condition'];
	$condition = unserialize(urldecode($condition));


	if( $condition ==  $current ){
		$res = 'same';
	}elseif( empty($current['cart']) ){
		$res = 'timeover';
	}elseif( $current['cart'] == $condition['cart'] && $current['entry'] != $condition['entry'] ){
		$res = 'entrydiff';
	}else{
		$res = 'different';
	}
	die($res);
}

function usces_confirm_uscesL10n ( $nouse, $post_id ){
	global $usces;

	if( 'confirm' == $usces->page ){
		$condition['entry'] = $usces->cart->get_entry();
		$condition['cart'] = $usces->cart->get_cart();

		$js = '';
		$js .= "'condition': '" . urlencode(serialize($condition)) . "',\n";
		$js .= "'cart_url': '" . USCES_CART_URL . "',\n";
		$js .= "'check_mes': '" . __( 'Purchase information has been updated. Please repeat the procedure.\n\nPlease do not open and work more than one tab (window).\n', 'usces') . "',\n";
		return $js;
	}
}

function usces_search_zipcode_check( $js ){
	global $usces;
	$option = get_option('usces');
	if( !isset($option['address_search']) || $option['address_search'] != 'activate' )
		return $js;

	if(( ($usces->use_js && ( is_page(USCES_MEMBER_NUMBER) || $usces->is_member_page($_SERVER['REQUEST_URI']) ))
			&& ((true === $usces->is_member_logged_in() && WCUtils::is_blank($usces->page)) || 'member' == $usces->page || 'editmemberform' == $usces->page || 'newmemberform' == $usces->page ) )
			||
		( ($usces->use_js && ( is_page(USCES_CART_NUMBER) || $usces->is_cart_page($_SERVER['REQUEST_URI']) ))
			&& ( 'customer' == $usces->page || 'delivery' == $usces->page ) )){

		$zip_id = (isset($_REQUEST['usces_page']) && 'msa_setting' == $_REQUEST['usces_page'] )? 'msa_zip' : 'zipcode';
		$js .= '
	<script type="text/javascript">
	(function($) {
	$("#search_zipcode").click(function () {
		var str = $("#' . $zip_id . '").val();
		if( !str.match(/^\d{7}$|^\d{3}-\d{4}$/) ){
			alert("' . __('Please enter the zip code correctly.', 'usces') . '");
			$("#' . $zip_id . '").focus();
		}
	});
		
	})(jQuery);
	</script>
	';

	}
	return $js;
}

function usces_admin_member_list_hook(){

	if( !isset( $_POST['member_list_options_apply']) )
		return;

	$list_option = get_option( 'usces_memberlist_option' );
	foreach( $list_option['view_column'] as $key => $value ){
		if( isset($_POST['hide'][$key] ) ){
			$list_option['view_column'][$key] = 1;
		}else{
			$list_option['view_column'][$key] = 0;
		}
	}
	$list_option['max_row'] = (int)$_POST['member_list_per_page'];

	update_option( 'usces_memberlist_option', $list_option );

}

function usces_admin_order_list_hook($hook){
	if( !isset( $_POST['order_list_options_apply']) )
		return;

	$list_option = get_option( 'usces_orderlist_option' );
	foreach( $list_option['view_column'] as $key => $value ){
		if( isset($_POST['hide'][$key] ) ){
			$list_option['view_column'][$key] = 1;
		}else{
			$list_option['view_column'][$key] = 0;
		}
	}
	$list_option['max_row'] = (int)$_POST['order_list_per_page'];

	update_option( 'usces_orderlist_option', $list_option );

}

function usces_memberlist_screen_settings($screen_settings, $screen ){
	if( 'welcart-management_page_usces_memberlist' != $screen->id
	|| ( isset($_REQUEST['member_action']) && 'edit' == $_REQUEST['member_action'] ) )
		return $screen_settings;

	require_once( USCES_PLUGIN_DIR . "/classes/memberList.class.php" );
	$DT = new WlcMemberList();
	$arr_column = $DT->get_column();
	$list_option = get_option( 'usces_memberlist_option' );
	$init_view = array('ID', 'name1', 'name2', 'pref', 'address1', 'tel', 'email', 'entrydate', 'rank', 'point' );

	$screen_settings = '
	<fieldset class="metabox-prefs">
		<legend>' . __('Columns') . '</legend>';
	foreach( $arr_column as $key => $value ){
		if( 'ID' == $key || 'csod_' == substr($key, 0, 5) )
			continue;

		if( !isset($list_option['view_column'][$key]) && in_array( $key, $init_view ) ){
			$list_option['view_column'][$key] = 1;
		}elseif( !isset($list_option['view_column'][$key]) ){
			$list_option['view_column'][$key] = 0;
		}

		$checked = $list_option['view_column'][$key] ? ' checked="checked"' : '';
		$screen_settings .= '<label><input class="hide-column-tog" name="hide[' . $key . ']" type="checkbox" id="' . $key . '-hide" value="' . esc_attr($value) . '"' . $checked . ' />' . esc_html($value) . '</label>'."\n";
	}
	$screen_settings .= '</fieldset>';

	if( !isset($list_option['max_row']) )
		$list_option['max_row'] = 50;

	$screen_settings .= '<fieldset class="screen-options">
		<legend>' . __('Pagination') . '</legend>
		<label for="edit_post_per_page">' . __('Number of items per page:') . '</label>
		<input type="number" step="1" min="1" max="999" class="screen-per-page" name="member_list_per_page" id="member_list_per_page" maxlength="3" value="' . (int)$list_option['max_row'] . '" />
	</fieldset>
	<p class="submit"><input type="submit" name="member_list_options_apply" id="screen-options-apply" class="button button-primary" value="' . __('Apply') . '"  /></p>';

	update_option( 'usces_memberlist_option', $list_option );
	return $screen_settings;
}

function usces_orderlist_screen_settings($screen_settings, $screen ){
	if( 'toplevel_page_usces_orderlist' != $screen->id
	|| ( isset($_REQUEST['order_action']) && 'edit' == $_REQUEST['order_action'] ) )
		return $screen_settings;

	require_once( USCES_PLUGIN_DIR . "/classes/orderList2.class.php" );
	$DT = new WlcOrderList();
	$arr_column = $DT->get_all_column();
	$list_option = get_option( 'usces_orderlist_option' );
	$init_view = array( 'deco_id', 'order_date', 'process_status', 'payment_name', 'receipt_status', 'total_price', 'deli_method', 'mem_id', 'name1', 'name2', 'pref' );
	if ( defined( 'WCEX_AUTO_DELIVERY' ) && version_compare( WCEX_AUTO_DELIVERY_VERSION, '1.4.0', '>=' ) ) {
		$init_view[] = 'reg_id';
	}
	$init_view = apply_filters( 'usces_filter_orderlist_column_init_view', $init_view );

	$screen_settings = '
	<fieldset class="metabox-prefs">
		<legend>' . __('Columns') . '</legend>';
	foreach( $arr_column as $key => $value ){

		if( !isset($list_option['view_column'][$key]) && in_array( $key, $init_view ) ){
			$list_option['view_column'][$key] = 1;
		}elseif( !isset($list_option['view_column'][$key]) ){
			$list_option['view_column'][$key] = 0;
		}

		$checked = (isset($list_option['view_column'][$key]) && $list_option['view_column'][$key]) ? ' checked="checked"' : '';
		$screen_settings .= '<label><input class="hide-column-tog" name="hide[' . $key . ']" type="checkbox" id="' . $key . '-hide" value="' . $key . '"' . $checked . ' />' . esc_html($value) . '</label>'."\n";
	}
	$screen_settings .= '</fieldset>';

	if( !isset($list_option['max_row']) )
		$list_option['max_row'] = 50;

	$screen_settings .= '<fieldset class="screen-options">
		<legend>' . __('Pagination') . '</legend>
		<label for="edit_post_per_page">' . __('Number of items per page:') . '</label>
		<input type="order" step="1" min="1" max="999" class="screen-per-page" name="order_list_per_page" id="order_list_per_page" maxlength="3" value="' . (int)$list_option['max_row'] . '" />
	</fieldset>
	<p class="submit"><input type="submit" name="order_list_options_apply" id="screen-options-apply" class="button button-primary" value="' . __('Apply') . '"  /></p>';

	update_option( 'usces_orderlist_option', $list_option );
	return $screen_settings;
}

function usces_memberreg_spamcheck($mes) {

	if ( !WCUtils::is_blank($_POST["member"]["name1"]) && trim($_POST["member"]["name1"]) == trim($_POST["member"]["name2"]) )
		$mes .= __('Name is not correct', 'usces') . "<br />";

	if ( !WCUtils::is_blank($_POST["member"]["address1"]) && trim($_POST["member"]["address1"]) == trim($_POST["member"]["address2"]) )
		$mes .= __('Address is not correct', 'usces') . "<br />";

	if($mes)
		usces_log('memberreg_spamcheck : '.$mes, 'acting_transaction.log');

	return $mes;
}

function usces_fromcart_memberreg_spamcheck($mes) {

	if ( !WCUtils::is_blank($_POST["customer"]["name1"]) && trim($_POST["customer"]["name1"]) == trim($_POST["customer"]["name2"]) )
		$mes .= __('Name is not correct', 'usces') . "<br />";

	if ( !WCUtils::is_blank($_POST["customer"]["address1"]) && trim($_POST["customer"]["address1"]) == trim($_POST["customer"]["address2"]) )
		$mes .= __('Address is not correct', 'usces') . "<br />";

	if($mes)
		usces_log('fromcart_memberreg_spamcheck : '.$mes, 'acting_transaction.log');

	return $mes;
}

function usces_priority_active_plugins( $active_plugins, $old_value ) {
	foreach ( $active_plugins as $no=>$path ) {
		if ( $path == USCES_PLUGIN_BASENAME ) {
			unset( $active_plugins[$no] );
			array_unshift( $active_plugins, USCES_PLUGIN_BASENAME );
			break;
		}
	}
	return $active_plugins;
}

function usces_action_lostmail_inform(){
	$mem_mail = $_REQUEST['mem'];
	$lostkey = $_REQUEST['key'];
	$html = '
	<input type="hidden" name="mem" value="' . esc_attr($mem_mail) . '" />
	<input type="hidden" name="key" value="' . esc_attr($lostkey) . '" />' . "\n";
	echo $html;
}
function usces_filter_lostmail_inform($html){
	$mem_mail = $_REQUEST['mem'];
	$lostkey = $_REQUEST['key'];
	$html .= '
	<input type="hidden" name="mem" value="' . esc_attr($mem_mail) . '" />
	<input type="hidden" name="key" value="' . esc_attr($lostkey) . '" />' . "\n";
	return $html;
}

function wc_purchase_nonce($html, $payments, $acting_flag, $rand, $purchase_disabled){
	global $usces;
	$nonacting_settlements = apply_filters( 'usces_filter_nonacting_settlements', $usces->nonacting_settlements );
	if( strpos($html, 'wc_nonce') || !in_array( $payments['settlement'], $nonacting_settlements) )
		return $html;

	$noncekey = 'wc_purchase_nonce' . $usces->get_uscesid(false);
	$html .= wp_nonce_field( $noncekey, 'wc_nonce', false, false )."\n";
	return $html;
}

function wc_purchase_nonce_check(){
	global $usces;
	$entry = $usces->cart->get_entry();
	if( !isset($entry['order']['payment_name']) || empty($entry['order']['payment_name']) ){
		wp_redirect( home_url() );
		exit;
	}

	$nonacting_settlements = apply_filters( 'usces_filter_nonacting_settlements', $usces->nonacting_settlements );
	$payments = usces_get_payments_by_name($entry['order']['payment_name']);
	if( !in_array( $payments['settlement'], $nonacting_settlements) )
		return true;

	$nonce = isset($_REQUEST['wc_nonce']) ? $_REQUEST['wc_nonce'] : '';
	$noncekey = 'wc_purchase_nonce' . $usces->get_uscesid(false);
	if( wp_verify_nonce($nonce, $noncekey) )
		return true;

	wp_redirect( home_url() );
	exit;
}

//Checking in $usces->use_point()
function usces_use_point_nonce(){
	global $usces;

	$noncekey = 'use_point' . $usces->get_uscesid(false);
	wp_nonce_field( $noncekey, 'wc_nonce');
}


function usces_post_member_nonce(){
	global $usces;

	$noncekey = 'post_member' . $usces->get_uscesid(false);
	wp_nonce_field( $noncekey, 'wc_nonce');
}

function usces_member_login_nonce(){
	global $usces;

	$noncekey = 'post_member' . $usces->get_uscesid(false);
	wp_nonce_field( $noncekey, 'wel_nonce');
}

function wel_order_edit_customer_additional_information( $data, $cscs_meta, $action_args ){
	global $usces;

	$order_id = isset( $data['ID'] ) ? (int)$data['ID'] : 0;
	if( !$order_id ){
		return;
	}

	$value = $usces->get_order_meta_value( 'extra_info', $order_id );
	if( !$value ){
		return;
	}

	$infos = unserialize( $value );
	$html = '<tr>
	<td colspan="2" class="label cus_note_label">その他</td>
	<td colspan="4" class="cus_note_label">'."\n";

	$html .= '<table border="0" cellspacing="0" class="extra_info cus_info">'."\n";
	foreach( $infos as $key => $info ){
		$key = str_replace( 'USER_AGANT', 'USER_AGENT', $key );
		$html .= '<tr><td class="label cus_note_label">'.esc_html($key) . '</td><td class="cus_note_label">' . esc_html($info) . '</td></tr>' . "\n";
	}
	$html .= '</table>'."\n";

	$html .= '</td></tr>'."\n";

	echo $html;
}

function wel_save_extra_info_to_ordermeta( $order_id, $results ){
	global $usces;

	if( !$order_id ){
		return;
	}

	$info = array(
		'IP'         => ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '',
		'USER_AGENT' => ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? $_SERVER['HTTP_USER_AGENT'] : '',
	);

	$usces->set_order_meta_value( 'extra_info', serialize($info), $order_id );
}

function usces_wp_nav_menu_args( $args ){

	usces_remove_filter();

	return $args;
}

function usces_wp_nav_menu( $nav_menu, $args ){

	usces_reset_filter();

	return $nav_menu;
}

function usces_close_session(){
	if (session_status() == PHP_SESSION_ACTIVE) {
		session_write_close();
	}
}

/**
 * Handles OPTIONS requests for the server.
 *
 * This is handled outside of the server code, as it doesn't obey normal route
 * mapping.
 *
 * @since 4.4.0
 *
 * @param mixed           $response Current reponse, either response or `null` to indicate pass-through.
 * @param WP_REST_Server  $handler  ResponseHandler instance (usually WP_REST_Server).
 * @param WP_REST_Request $request  The request that was used to make current response.
 * @return WP_REST_Response Modified response, either response or `null` to indicate pass-through.
 */
function usces_close_session_loopback( $response, $handler, $request ) {
	$router = $request->get_route();
	if (!empty($router) && $router == '/wp-site-health/v1/tests/loopback-requests') {
		usces_close_session();
	}
	return $response;
}

function usces_add_google_recaptcha_v3_script(){
	global $usces, $usces_entries, $usces_carts;

	if( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) && 'newmemberform' == $usces->page ){
		print_google_recaptcha_response('create_new_member', 'memberpages');
	}

	if(( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) && 'customer' == $usces->page )){
		print_google_recaptcha_response('customer_order_page', 'memberpages', 'customer_form');

	}
}

function print_google_recaptcha_response($action, $parent_element_id, $form_name = ''){
	$option = get_option('usces_ex');
	if(isset($option['system']['google_recaptcha']['status']) && $option['system']['google_recaptcha']['status'] && !(empty($option['system']['google_recaptcha']['site_key'])) && !(empty($option['system']['google_recaptcha']['secret_key']))){
		$site_key = isset( $option['system']['google_recaptcha']['site_key'] ) ? $option['system']['google_recaptcha']['site_key'] : '';
		echo  '<script src="https://www.google.com/recaptcha/api.js?render=' . $site_key . '"></script>';
		echo  '<script>';
		echo  'grecaptcha.ready(function() {';
		echo  'grecaptcha.execute(\''. $site_key .'\', {action: "'. $action.'"}).then(function(token) {';
		if($form_name != ''){
			echo  'jQuery("form[name=\''.$form_name.'\']").append(\'<input type="hidden" id="elm_recaptcha_response" name="recaptcha_response" value="\'+ token +\'">\');';
		}
		else{
			echo  'jQuery("#' . $parent_element_id . '").find("form").append(\'<input type="hidden" id="elm_recaptcha_response" name="recaptcha_response" value="\'+ token +\'">\');';
		}
		echo 'if(!jQuery("input[type=hidden]").is("[name=recaptcha_response]")) { jQuery("input[name=member_regmode]").after(\'<input type="hidden" id="elm_recaptcha_response" name="recaptcha_response" value="\'+ token +\'">\'); }';
		echo  '});';
		echo  '});';
		echo  '</script>';

		// run time reload google captcha token v3 key after 1 minutes
		echo  '<script>';
		echo 'var max_google_reload_times = 10;';
		echo 'var google_reload_times = 1;';
		echo 'var function_token_reload = setInterval(function(){';
			echo 'if (google_reload_times > max_google_reload_times) {';
				echo 'clearInterval(function_token_reload);';
			echo '} else { ';
				echo 'google_reload_times = google_reload_times + 1;';
				echo 'grecaptcha.ready(function() {';
				echo 'grecaptcha.execute(\''. $site_key .'\', {action: "'. $action.'"}).then(function(token) {';
					if( $form_name ){
						echo 'jQuery("form[name=\''.$form_name.'\'] #elm_recaptcha_response").val(token);';
					} else {
						echo 'jQuery("#' . $parent_element_id . ' #elm_recaptcha_response").val(token);';
					}
					echo '});';
				echo '});';
			echo '}';
		echo '}, 1000*60);';
		echo  '</script>';
	}
}

function wel_item_upload_ajax() {
	$_REQUEST['action'] = 'upload_register';
	usces_item_uploadcsv();
}

/**
 * Check progress.
 *
 * @since 2.7.7
 * @return void
 */
function wel_item_progress_check_ajax(){
	require_once( USCES_PLUGIN_DIR . '/functions/progress-check.php' );
	usces_item_progress_check();
}

/**
 * Implementation of hook usces_filter_admin_custom_field_input_value()
 * Append new options from the instance of the order custom field.
 *
 * @since 2.5.3
 * @see usces_admin_custom_field_input()
 * @param string $value List of options.
 * @param string $key The custom field key.
 * @param string $entry The instance of the custom field.
 * @param string $custom_field The type of the custom field. Ex: order, delivery, customer, member...
 * @return string $value List of options appended new options from the instance.
 */
function usces_filter_admin_custom_field_input_value( $value, $key, $entry, $custom_field ) {
	if ( 'order' === $custom_field ) {
		$means                = $entry['means'];
		$is_choice_field_type = '0' === $means || '3' === $means || '4' === $means;
		if ( $is_choice_field_type ) {
			$options = explode( "\n", $value );

			if ( is_array( $entry['data'] ) ) {
				$data = $entry['data'];
			} else {
				$data = ( !empty($entry['data']) && '#NONE#' !== $entry['data'] ) ? array( $entry['data'] ) : array();
			}

			$diff_values = array_diff( $data, $options );

			if ( ! empty( $diff_values ) ) {
				$options = array_merge( $options, $diff_values );
				$value   = implode( "\n", $options );
			}
		}
	}

	return $value;
}

/**
 * Create Post type for Welcart product.
 *
 * @since 2.5.5
 */
function usces_create_product_type() {
	register_post_type(
		'product',
		array(
			'labels' => array(
				'name'          => '商品',
				'singular_name' => 'product',
			),
			'public'        => false,
			'has_archive'   => false,
			'menu_position' => 3,
			'show_in_rest'  => false,
		)
	);
}

/**
 * Executement release card update lock.
 *
 * @since 2.5.8
 */
function wel_execute_release_card_update_lock( $member_action ) {
	if ( 'editpost' === $member_action ) {
		$flag   = filter_input( INPUT_POST, 'release_card_update_lock', FILTER_SANITIZE_STRING );
		$member_id = filter_input( INPUT_POST, 'member_id', FILTER_VALIDATE_INT );
		if ( 'release' === $flag && ! empty( $member_id ) ) {
			wel_release_card_update_lock( $member_id );
		}
	}
}

/**
 * Welcart auto update control.
 *
 * @since 2.8
 * @param boolean $update
 * @param string $item
 * @return boolean $update
*/
function usces_auto_update_welcart ( $update, $item ) {
	$targets = array( 'usc-e-shop' );
	if ( in_array( $item->slug, $targets ) ) {

		$available_updates = get_site_transient( 'update_plugins' );
		if ( ! isset( $available_updates->response['usc-e-shop/usc-e-shop.php'] ) ) {
			return true; 
		}

		$plugin_info       = $available_updates->response['usc-e-shop/usc-e-shop.php'];
		$current_arr       = explode( '.', USCES_VERSION, 3 );
		$current_major     = (string) $current_arr[0] . '.' . (string) $current_arr[1];
		$new_arr           = explode( '.', $plugin_info->new_version, 3 );
		$new_major         = (string) $new_arr[0] . '.' . (string) $new_arr[1];

		if ( $current_major === $new_major ) {
			return true; 
		} else {
			return false; 
		}
	} else {
		return $update; 
	}
}

/**
 * Check if the same item code does not exist.
 *
 * @since 2.8
 * @param string $item_code
 * @return boolean $res true if present, false otherwise.
*/
function wel_item_code_exists_ajax () {

	$wc_nonce  = filter_input( INPUT_POST, 'wc_nonce' );
	$item_code = filter_input( INPUT_POST, 'item_code' );
	
	$res = array( 'result' => null, 'item_code' => $item_code, 'message' => '' );

	if ( ! wp_verify_nonce( $wc_nonce, 'check_item_code') ) {
		wp_send_json( $res );
	}

	$post_id = wel_get_id_by_item_code( $item_code, false );

	$res['result'] = $post_id; 
	$res['message'] = esc_js( sprintf( __( 'Product code %s has already been registered.', 'usces' ), $item_code ) );


	wp_send_json( $res );
}

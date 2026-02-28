<?php
/*
 * global_config.inc.php
 *
 * PHP Toolkit for PayPal v0.51
 * http://www.paypal.com/pdn
 *
 * Copyright (c) 2004 PayPal Inc
 *
 * Released under Common Public License 1.0
 * http://opensource.org/licenses/cpl.php
 *
*/

//create variable names to perform additional order processing
function rezgo_create_local_variables() {
	$local_var_arr = array(
			'business'
		, 'receiver_email'
		, 'receiver_id'
		, 'item_name'
		, 'item_number'
		, 'quantity'
		, 'invoice'
		, 'custom'
		, 'memo'
		, 'tax'
		, 'option_name1'
		, 'option_selection1'
		, 'option_name2'
		, 'option_selection2'
		, 'num_cart_items'
		, 'mc_gross'
		, 'mc_fee'
		, 'mc_currency'
		, 'settle_amount'
		, 'settle_currency'
		, 'exchange_rate'
		, 'payment_gross'
		, 'payment_fee'
		, 'payment_status'
		, 'pending_reason'
		, 'reason_code'
		, 'payment_date'
		, 'txn_id'
		, 'txn_type'
		, 'payment_type'
		, 'for_auction'
		, 'auction_buyer_id'
		, 'auction_closing_date'
		, 'auction_multi_item'
		, 'first_name'
		, 'last_name'
		, 'payer_business_name'
		, 'address_name'
		, 'address_street'
		, 'address_city'
		, 'address_state'
		, 'address_zip'
		, 'address_country'
		, 'address_status'
		, 'payer_email'
		, 'payer_id'
		, 'payer_status'
		, 'notify_version'
		, 'verify_sign'
	);

	foreach($local_var_arr as $el) {
		$array_name[$el] = "$site->requestStr($_POST[$el])";
	}

	return $array_name;
}

//post transaction data using curl
function curlPost($url,$data) {
	global $paypal;

	//build post string
	foreach($data as $i=>$v) {
		$postdata.= $i . "=" . urlencode($v) . "&";
	}

	$postdata.="cmd=_notify-validate";

	//execute curl on the command line
	exec("$paypal[curl_location] -d \"$postdata\" $url", $info);

	$info=implode(",",$info);

	return $info;
}

//posts transaction data using libCurl
function libCurlPost($url,$data) {
	//build post string
	foreach($data as $i=>$v) {
		$postdata.= $i . "=" . urlencode($v) . "&";
	}

	$postdata.="cmd=_notify-validate";

	$ch=curl_init();

	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);

	//Start ob to prevent curl_exec from displaying stuff.
	ob_start();
	curl_exec($ch);

	//Get contents of output buffer
	$info=ob_get_contents();
	curl_close($ch);

	//End ob and erase contents.
	ob_end_clean();

	return $info;
}

//posts transaction data using fsockopen.
function fsockPost($url,$data) {
	//Parse url
	$web=parse_url($url);

	//build post string
	foreach($data as $i=>$v) {
		$postdata.= $i . "=" . urlencode($v) . "&";
	}

	$postdata.="cmd=_notify-validate";

	//Set the port number
	if($web[scheme] == "https") { $web[port]="443";	$ssl="ssl://"; } else { $web[port]="80"; }

	//Create paypal connection
	$fp=@fsockopen($ssl . $web[host],$web[port],$errnum,$errstr,30);

//Error checking
if(!$fp) { echo "$errnum: $errstr"; }

//Post Data
else {

	fputs($fp, "POST $web[path] HTTP/1.1\r\n");
	fputs($fp, "Host: $web[host]\r\n");
	fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
	fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
	fputs($fp, "Connection: close\r\n\r\n");
	fputs($fp, $postdata . "\r\n\r\n");

		//loop through the response from the server
		while(!feof($fp)) { $info[]=@fgets($fp, 1024); }

		//close fp - we are done with it
		fclose($fp);

		//break up results into a string
		$info=implode(",",$info);
	}

	return $info;
}

//Display Paypal Hidden Variables
function showVariables() {
	global $paypal;

	$paypal = array_map('filter_var', $paypal);
	?>
	<!-- PayPal Configuration -->
	<input type="hidden" name="business" value="<?php echo $paypal[business]; ?>">
	<input type="hidden" name="currency_code" value="<?php echo $paypal[currency_code]; ?>">
	<input type="hidden" name="cmd" value="<?php echo $paypal[cmd]; ?>">
	<input type="hidden" name="image_url" value="<?php echo $paypal[site_url] . $paypal[image_url]; ?>">
	<input type="hidden" name="return" value="<?php echo $paypal[site_url] . $paypal[success_url]; ?>">
	<input type="hidden" name="cancel_return" value="<?php echo $paypal[cancel_url]; ?>">
	<input type="hidden" name="notify_url" value="<?php echo $paypal[site_url] . $paypal[notify_url]; ?>">
	<input type="hidden" name="rm" value="<?php echo $paypal[return_method]; ?>">

	<input type="hidden" name="lc" value="<?php echo $paypal[lc]; ?>">
	<input type="hidden" name="bn" value="<?php echo $paypal[bn]; ?>">
	<input type="hidden" name="cbt" value="<?php echo $paypal[continue_button_text]; ?>">

	<!-- Payment Page Information -->
	<input type="hidden" name="no_shipping" value="<?php echo $paypal[display_shipping_address]; ?>">
	<input type="hidden" name="no_note" value="<?php echo $paypal[display_comment]; ?>">
	<input type="hidden" name="cn" value="<?php echo $paypal[comment_header]; ?>">
	<input type="hidden" name="cs" value="<?php echo $paypal[background_color]; ?>">

	<!-- Product Information -->
	<input type="hidden" name="item_name" value="<?php echo $paypal[item_name]; ?>">
	<input type="hidden" name="amount" value="<?php echo $paypal[amount]; ?>">
	<input type="hidden" name="quantity" value="<?php echo $paypal[quantity]; ?>">
	<input type="hidden" name="item_number" value="<?php echo $paypal[item_number]; ?>">
	<input type="hidden" name="undefined_quantity" value="<?php echo $paypal[edit_quantity]; ?>">
	<input type="hidden" name="on0" value="<?php echo $paypal[on0]; ?>">
	<input type="hidden" name="os0" value="<?php echo $paypal[os0]; ?>">
	<input type="hidden" name="on1" value="<?php echo $paypal[on1]; ?>">
	<input type="hidden" name="os1" value="<?php echo $paypal[os1]; ?>">

	<!-- Shipping and Misc Information -->
	<input type="hidden" name="shipping" value="<?php echo $paypal[shipping_amount]; ?>">
	<input type="hidden" name="shipping2" value="<?php echo $paypal[shipping_amount_per_item]; ?>">
	<input type="hidden" name="handling" value="<?php echo $paypal[handling_amount]; ?>">
	<input type="hidden" name="tax" value="<?php echo $paypal[tax]; ?>">
	<input type="hidden" name="custom" value="<?php echo $paypal[custom]; ?>">
	<input type="hidden" name="invoice" value="<?php echo $paypal[invoice]; ?>">

	<!-- Customer Information -->
	<input type="hidden" name="first_name" value="<?php echo $paypal[firstname]; ?>">
	<input type="hidden" name="last_name" value="<?php echo $paypal[lastname]; ?>">
	<input type="hidden" name="address1" value="<?php echo $paypal[address1]; ?>">
	<input type="hidden" name="address2" value="<?php echo $paypal[address2]; ?>">
	<input type="hidden" name="city" value="<?php echo $paypal[city]; ?>">
	<input type="hidden" name="state" value="<?php echo $paypal[state]; ?>">
	<input type="hidden" name="zip" value="<?php echo $paypal[zip]; ?>">
	<input type="hidden" name="email" value="<?php echo $paypal[email]; ?>">
	<input type="hidden" name="night_phone_a" value="<?php echo $paypal[phone_1]; ?>">
	<input type="hidden" name="night_phone_b" value="<?php echo $paypal[phone_2]; ?>">
	<input type="hidden" name="night_phone_c" value="<?php echo $paypal[phone_3]; ?>">
<?php } ?>

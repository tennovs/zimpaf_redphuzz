<?php

	$company = $site->getCompanyDetails();
	$companyCountry = $site->getCompanyCountry();

	// non-open date date_selection elements
	$date_types = array('always', 'range', 'week', 'days', 'single'); // centralize this?

	//ticket guardian values
	$tg_supported_currencies = array('USD', 'CAD', 'GBP', 'AUD', 'MXN', 'JPY', 'BRL', 'EUR');
	//$tg_supported_locale = array('en_US', 'en_CA', 'en_GB', 'en_AU', 'es_MX'); //TODO:Use site settings to set the widget language
	$tg_display_currency = 0;
	$tg_enabled = 0;
	
?>

<script src="//code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>

<script>
	// var elements = new Array();
	// var split_total = new Array();
	var overall_total = '0';
	var form_symbol = '$';
	var form_decimals = '2';
	var form_separator = ',';
	// var tg_booking_prices = new Array();
	// var tg_booking_prices_total = 0;

	// MONEY FORMATTING
	const currency = '<?php echo $site->xml->currency_symbol?>';

	Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
		var n = this,
		decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? form_decimals : decPlaces,
		decSeparator = decSeparator == undefined ? "." : decSeparator,
		thouSeparator = thouSeparator == undefined ? form_separator : thouSeparator,
		sign = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;

		var dec;
		var out = sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator);
		if(decPlaces) dec = Math.abs(n - i).toFixed(decPlaces).slice(2);
		if(dec) out += decSeparator + dec;
		return out;
	};

	jQuery(document).ready(function($){	
		// Start international phone input
		$("#tour_sms").intlTelInput({
			initialCountry: '<?php echo $companyCountry?>',
			formatOnInit: true,
			preferredCountries: ['us', 'ca', 'gb', 'au'],
			utilsScript: '<?php echo $site->path?>/js/intlTelInput/utils.js'
		});
		$("#tour_sms").on("keyup change blur countrychange", function() {
			$('#sms').val($("#tour_sms").intlTelInput("getNumber"));
		});
		// End international phone input
	});
</script>

        <div id="rezgo-order-wrp" class="container-fluid rezgo-container">
            <div class="jumbotron rezgo-booking">

                <form id="rezgo-book-form" role="form" method="post" target="rezgo_content_frame">
                    <div id="rezgo-book-step-two">
							<div id="rezgo-book-step-two-anchor"></div>

							<div id="rezgo-book-step-two-crumb" class="row">
								<ol class="breadcrumb rezgo-breadcrumb">
										<?php // check for cart token, add to order link to preserve cart data 
											$cart_token = $_COOKIE['rezgo_cart_token_'.REZGO_CID]; ?>
										<li id="rezgo-book-step-two-your-order" class="rezgo-breadcrumb-order"><a class="link" href="<?php echo $site->base?>/order/<?php echo $cart_token?>"><span class="default">Order</span><span class="custom"></span></a></li>
										<li id="rezgo-book-step-two-info" class="rezgo-breadcrumb-info"><a class="link" href="<?php echo $site->base?>/book"><span class="default">Guest Information</span><span class="custom"></span></a></li>
										<li id="rezgo-book-step-two-billing" class="rezgo-breadcrumb-billing active"><span class="default">Payment</span><span class="custom"></span></li>
										<li id="rezgo-book-step-two-confirmation" class="rezgo-breadcrumb-confirmation"><span class="default">Confirmation</span><span class="custom"></span></li>
								</ol>
                            </div>
							<input type="hidden" name="cart_token" value="<?php echo $cart_token?>">

						<div class="flex-container confirm-page-container">

							<div class="checkout-container">
							<?php
							$cart = $site->getCart(1); // get the cart, remove any dead entries
							
							if(!count($cart)) {
								$site->sendTo($site->base);
							}
							$cart_count = count($cart);
							$lead_passenger = $site->getLeadPassenger(); // get lead passenger details
							$form_display = $site->getFormDisplay(); // get primary form addons
							$gf_form_display = $site->getGroupFormDisplay(); // get group form addons
							$booking_dates = array();
							$booking_items = array();
							?>
							
								<?php $c = 0; // start cart loop for each booking in the order ?>

								<?php foreach($cart as $item) { 
									$currency_base = $item->currency_base; 
									$site->readItem($item); ?>
								
									<?php if(DEBUG) { ?>
										<div class="row">
											<pre style="max-height:100px; overflow-y:auto; margin:15px 0"><?php var_dump($item); ?></pre>
										</div>
									<?php } ?>

								<?php if((int) $item->availability >= (int) $item->pax_count) { ?>
                                    <?php $c++; // only increment if it's still available ?>

										<?php 
											if(in_array((string) $item->date_selection, $date_types)) {
												$booking_date = date("Y-m-d", (string)$item->booking_date);
											} else {
												$booking_date = 'open'; // for open availability
											}

											if ((string) $company->gateway_id == 'tmt'){
												if(!in_array((string) $item->date_selection, $date_types)){
													$tmt_date = ($item->expiry && ($item->expiry < strtotime('+30 days'))) ? $item->expiry : strtotime('+30 days', time());
												} else {
													$tmt_date = $item->booking_date;
												}
												array_push($booking_dates, (string)$tmt_date);
												array_push($booking_items, (string)$item->item .' - '. $item->option);
											}
										?>

										<input type="hidden" name="booking[<?php echo $c?>][index]" value="<?php echo $c-1?>" />
										<input type="hidden" name="booking[<?php echo $c?>][book]" value="<?php echo $item->uid?>" />
										<input type="hidden" name="booking[<?php echo $c?>][date]" value="<?php echo $booking_date?>" />
										<input type="hidden" name="booking[<?php echo $c?>][adult_num]" value="<?php echo $item->adult_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][child_num]" value="<?php echo $item->child_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][senior_num]" value="<?php echo $item->senior_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price4_num]" value="<?php echo $item->price4_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price5_num]" value="<?php echo $item->price5_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price6_num]" value="<?php echo $item->price6_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price7_num]" value="<?php echo $item->price7_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price8_num]" value="<?php echo $item->price8_num?>" />
										<input type="hidden" name="booking[<?php echo $c?>][price9_num]" value="<?php echo $item->price9_num?>" />


									<div id="rezgo-book-step-two-item-<?php echo $item->uid?>" class="row rezgo-form-group rezgo-booking-info">

									<?php if($c==1){ ?>
										<h3 id="rezgo-review-order-header" class="text-info">1. Review Order</h3>
									<?php } ?>

										<!-- <script>split_total[<?php echo $c?>] = <?php echo $item->overall_total?>;</script> -->
										<div class="rezgo-booking-title-wrp">

													<h3 class="rezgo-booking-of rezgo-booking-title">
														<div class="rezgo-sub-title">
															<span>Booking <?php echo $c?> of <?php echo count($cart)?></span>
														</div>
													</h3>

											<h3 class="rezgo-item-title"><?php echo $item->item?> &mdash; <?php echo $item->option?></h3>	

										</div>

										<div class="">
											<table class="rezgo-table-list" border="0" cellspacing="0" cellpadding="2">

												<?php if(in_array((string) $item->date_selection, $date_types)) { ?>
													<?php $data_book_date = date("Y-m-d", (string)$item->booking_date); ?>
													<label>Date: <span class="lead"><?php echo date((string) $company->date_format, (string) $item->booking_date)?></span></label> 
												<?php } else { ?>
													<?php $data_book_date = date('Y-m-d', strtotime('+1 day')); // open date ?>
													<label><span class="lead"> Open Availability </span></label>
												<?php } ?>

												<?php if($item->duration != '') { ?>
													&nbsp;- <label><span class="lead">(Duration: <?php echo $item->duration?>)</span></label> 
												<?php } ?>


												<?php if($item->discount_rules->rule) {
													echo '<br><label class="rezgo-booking-discount">
													<span class="rezgo-discount-span">Discount:</span> ';
													unset($discount_string);
													foreach($item->discount_rules->rule as $discount) {	
														$discount_string .= ($discount_string) ? ', '.$discount : $discount;
													}
													echo '<span class="rezgo-promo-code-desc">'.$discount_string.'</span>
													</label>';
												} ?>

											</table>
										</div>

										<div class="row rezgo-form-group rezgo-cart-table-wrp rezgo-table-container">
											<div class="col-xs-12 col-wrapper">
												<table class="table table-responsive rezgo-billing-cart" id="<?php echo $item->uid?>" data-book-id="<?php echo $c?>">
													<tr class="rezgo-tr-head">
														<td class="text-left rezgo-billing-type"><label>Type</label></td>
														<td class="text-left rezgo-billing-qty"><label class="hidden-xs">Qty.</label></td>
														<td class="text-left rezgo-billing-cost"><label>Cost</label></td>
														<td class="text-right rezgo-billing-total"><label>Total</label></td>
													</tr>

													<?php foreach($site->getTourPrices($item) as $price) { ?>

														<?php if($item->{$price->name.'_num'}) { ?>
															<tr class="rezgo-tr-pax">
																<td class="text-left"><?php echo $price->label?></td>
																<td class="text-left" ><?php echo $item->{$price->name.'_num'}?></td>
																<td class="text-left">
																	<?php
																		$initial_price = (float) $price->price;
																		$strike_price = (float) $price->strike;
																		$discount_price = (float) $price->base;
																	?>
																	<?php if ( ($site->exists($price->strike)) && ($site->exists($price->base)) )  { ?>
																		<?php $show_this = max($strike_price, $discount_price); ?>

																		<span class="discount">
																			<?php echo $site->formatCurrency($show_this)?>
																		</span>

																	<?php } else if(!$site->isVendor() && $site->exists($price->strike)) { ?>

																			<!-- show only if strike price is higher -->
																			<?php if ($strike_price >= $initial_price) { ?>
																				<span class="discount">
																					<span class="rezgo-strike-price">
																						<?php echo $site->formatCurrency($strike_price)?>
																					</span>
																				</span>
																			<?php } ?>

																	<?php } else if($site->exists($price->base)) { ?>

																		<span class="discount">
																			<?php echo $site->formatCurrency($price->base)?>
																		</span>

																	<?php } ?>
																		<?php echo $site->formatCurrency($price->price)?>
																</td>		
																<td class="text-right">
																	<span>
																		<?php echo $site->formatCurrency($price->total)?>
																	</span>
																</td>
																
															</tr>
														<?php } ?>
													<?php } ?>

													<tr class="rezgo-tr-subtotal">
														<td colspan="3" class="text-right" style="padding-top:15px;"><span class="push-right"><strong>Subtotal</strong></span></td>
														<td class="text-right" style="padding-top:15px;"><span><?php echo $site->formatCurrency($item->sub_total)?></span></td>
													</tr>

													<?php $line_items = $site->getTourLineItems(); ?>
								
													<?php 	
														$pax_totals = array(
															'adult_num' => 'price_adult', 
															'child_num' => 'price_child', 
															'senior_num' => 'price_senior', 
															'price4_num' => 'price4', 
															'price5_num' => 'price5', 
															'price6_num' => 'price6', 
															'price7_num' => 'price7', 
															'price8_num' => 'price8', 
															'price9_num' => 'price9'
														);
													?>

														<?php foreach($line_items as $line) { ?>
															<?php unset($label_add); ?>

															<?php if($site->exists($line->percent) || $site->exists($line->multi)) {
																	$label_add = ' (';

																if($site->exists($line->percent)) {
																	$label_add .= $line->percent.'%';
																}

																	if($site->exists($line->multi)) {
																		if(!$site->exists($line->percent)) {
																			$label_add .= $site->formatCurrency($line->multi);
																		}
																		
																		if($site->exists($line->meta)) {
																			
																			$line_pax = 0;
																			foreach ($pax_totals as $p_num => $p_rate) {
																				
																				if ( (int) $item->{$p_num} > 0 && ((float) $item->date->{$p_rate} > (float) $line->meta)) {
																					$line_pax += (int) $item->{$p_num};
																				}
																				
																			}
																			$label_add .= ' x '.$line_pax;
																			
																		} else {
																			$label_add .= ' x '.$item->pax;
																		}
																	}

																	$label_add .= ')';
																} 
																	
																?>

													<tr class="rezgo-tr-subtotal">
														<td colspan="3" class="text-right">
														<?php if ($line->source == 'bundle') { ?>
														<strong class="rezgo-line-bundle push-right"></i>&nbsp;<?php echo $line->label?><?php echo $label_add?> (Bundle)</strong></span>
														<?php } else { ?>
														<span class="push-right"><strong><?php echo $line->label?><?php echo $label_add?></strong></span>
														<?php } ?>
														</td>
														<td class="text-right"><?php echo $site->formatCurrency($line->amount)?></td>
													</tr> 

													<?php } ?>	

													<tbody id="line_item_box_<?php echo $c?>" class="rezgo-line-item-box" data-line-uid="<?php echo $item->uid?>" data-book-id="<?php echo $c?>">
													</tbody><!-- line items -->
													<input type="hidden" id="rezgo-pickup-line-<?php echo $c?>" value="" />

													<tbody id="fee_box_<?php echo $c?>" class="rezgo-fee-box">
													</tbody><!-- extra fees -->

													<tbody id="line_item_box_<?php echo $c?>" class="rezgo-form-display-box" data-line-uid="<?php echo $item->uid?>" data-book-id="<?php echo $c?>">

													<?php if ($form_display) {

														$primary_forms = $form_display[$c-1]->primary_forms;

														if ($primary_forms->count()){
															foreach ($primary_forms->form as $form){
																if ( ($form->price !=0) && ($form->value == 'on') ){ ?>
																	<tr class="rezgo-tr-form-display">
																		<td colspan="3" class="text-right rezgo-line-item">
																			<strong class="push-right">
																				<?php echo $form->title?>
																			</strong>
																		</td>

																		<td class="text-right">
																			<span class="rezgo-form-display-total_<?php echo $c?>" rel="<?php echo $form->price?>"><?php echo $site->formatCurrency($form->price)?></span>
																		</td>
																	</tr>
																<?php $pf_form_total[$c] += $form->price; ?>
																<?php } ?>
															<?php } ?>
														<?php } ?>

													<?php } ?>

													<?php if ($gf_form_display) {
													
														$gf_array = array();
														$group_forms = $gf_form_display[$c-1];

														if ($group_forms) {
															foreach ($group_forms as $form){
																if ( ($form->price !=0) && ($form->value == 'on') ){
																	$gf_array[] = (string) $form->title . ':::' . (string) $form->price;
																}
															}

															$gf_line = array_count_values($gf_array);
															foreach ($gf_line as $k => $count) {
																$result = explode(':::', $k);
																$title = $result[0];
																$price = $result[1]; ?>

																<tr class="rezgo-tr-form-display">
																	<td colspan="3" class="text-right rezgo-line-item">
																		<strong class="push-right">
																			<?php echo $title?>
																			<?php if ($count > 1) { ?>
																				(<?php echo $site->formatCurrency($price)?> x <?php echo $count?>)
																				<?php $price = $price * $count;?>
																			<?php } ?> 
																		</strong>
																	</td>
																	<td class="text-right">
																		<span class="rezgo-form-display-total_<?php echo $c?>" rel="<?php echo $price?>"><?php echo $site->formatCurrency($price)?></span>
																	</td> 
																</tr>
																<?php $gf_form_total[$c] += $price;?>
															<?php } ?>
														<?php } ?>
													<?php } ?>
													
													</tbody><!-- form_display items -->

													<tbody class="rezgo-form-summary-box" data-line-uid="<?php echo $item->uid?>" data-book-id="<?php echo $c?>">

													<tr class="rezgo-tr-total summary-total">
														<td colspan="3" class="text-right">
															<span class="push-right"><strong>Total</strong></span>
														</td>
														<td class="text-right">
															<?php 
																$total_value[$c] = $item->overall_total + $pf_form_total[$c] + $gf_form_total[$c]; 
																$total_value[$c] = ($total_value[$c] < 0 ) ? 0 : $total_value[$c]; 
															?>
															<span class="rezgo-item-total" id="total_value_<?php echo $c?>" rel="<?php echo $total_value[$c]; ?>">
																<?php echo $site->formatCurrency($total_value[$c])?>
															</span>
															<input type="hidden" id="total_extras_<?php echo $c?>" value="" />
														</td>
													</tr>

													<tbody class="rezgo-gc-box" style="display:none">
														<tr class="rezgo-tr-gift-card">
															<td colspan="3" class="text-right">
																<span class="push-right"><strong>Gift Card</strong></span>
															</td>
															<td class="text-right">
																<strong><span>-</span> <span class="cur"></span><span class="rezgo-gc-min"></strong></span>
															</td>
														</tr>
													</tbody>

													<?php if($site->exists($item->deposit)) { ?>
														<tr class="rezgo-tr-deposit">
															<td colspan="3" class="text-right">
																<span class="push-right"><strong>Deposit to Pay Now</strong></span>
															</td>
															<td class="text-right">
																<strong class="rezgo-item-deposit" id="deposit_value_<?php echo $c?>" rel="<?php echo $item->deposit_value?>">
																	<?php echo $site->formatCurrency($item->deposit_value)?>
																</strong>
															</td>
														</tr>

														<?php $complete_booking_total += (float) $item->deposit_value; ?>
							
													<?php } else { ?>
							
														<?php $complete_booking_total += (float) $total_value[$c]; ?>

													<?php } ?>

													</tbody><!-- summary total items -->
												</table>
											</div>
										</div>
									</div>
								<?php } // end if((int) $item->availability >= (int) $item->pax_count) ?>

							<?php } // end foreach($cart as $item ) ?>

							<script>
								overall_total = '<?php echo $complete_booking_total?>';
							</script>

							<!-- BOOKING TOTAL -->						
							<div class="rezgo-total-payable-wrp">
								<div class="row">

									<div class="col-xs-11 pull-right rezgo-total-payable">

										<h5>Total Due</h5> &nbsp; &nbsp; <span id="total_value" rel="<?php echo $complete_booking_total?>"><?php echo $site->formatCurrency($complete_booking_total)?></span>
										<br>
										<input type="hidden" id="expected" name="expected" value="<?php echo $complete_booking_total?>"/>
									</div>
													
									<div class="clearfix visible-xs"></div>
								</div>
							</div>
							<hr>

							<!-- WAIVER -->
							<?php if(!$site->isVendor()) { ?>
								<?php
								$waiver = 0;
								$waiver_ids = '';
								foreach($cart as $item) {
									if((int) $item->waiver === 1 && (int) $item->waiver['type'] === 0) {
										$waiver++;
										$waiver_ids .= $item->uid.',';
									}
								}
								?>
								<?php if($waiver >= 1) { ?>
									<div id="rezgo-waiver-use">
										<div id="rezgo-waiver" class="row rezgo-form-group rezgo-booking">
											<div class="col-xs-12">
												<h3 class="text-info"><span>Waiver</span></h3>

												<div class="row">
													<div id="rezgo-waiver-info" class="col-xs-12">
														<div class="msg intro">
															<span>You must read and sign the liability waiver to complete this order.</span>
														</div>

														<div class="msg success" style="display:none">
															<i class="fa fa-check" aria-hidden="true"></i>
															<span>Thank you for signing the waiver.</span>
														</div>

														<div class="msg error" style="display:none">
															<i class="fa fa-times" aria-hidden="true"></i>
															<span>Waiver signature is required.</span>
														</div>
													</div>

													<div id="rezgo-waiver-read-btn" class="col-xs-5">
														<button id="rezgo-waiver-show" class="btn rezgo-btn-default btn-lg btn-block" type="button" data-ids="<?php echo rtrim($waiver_ids,',')?>">
															<span><i class="fa fa-pencil-square-o"></i>&nbsp;<span class="rezgo-read-waiver"><span>read and sign waiver</span></span></span>
														</button>
													</div>

													<div id="rezgo-waiver-signature" class="col-xs-7">
														<img class="signature" style="display:none">
													</div>

													<input id="rezgo-waiver-input" name="waiver" type="text" value="" required />

														<script>
														// check for signature in localStorage
														if (localStorage['signature']){
															let signature = localStorage['signature'];

															jQuery('img.signature').attr('src', signature);
															jQuery('img.signature').show();

															jQuery('#rezgo-waiver-input').val(signature);

															jQuery('.msg.intro').hide();
															jQuery('.msg.success').show();
														}
														</script>
												</div>
											</div>
										</div>
										<hr>
									</div>

								<?php } ?>
							<?php } ?>

								<div class="payment-select-container rezgo-form-group">
										<h3 class="text-info" id="payment_info_head">									
											<span> 2. Payment</span>
										</h3>

										<!-- GIFT CARD -->
										<?php if(!$site->isVendor() ) { ?>
											<div id="rezgo-gift-card-use">
												<div class="row">
													<div class="col-xs-12">
														<?php require 'gift_card_redeem.php'; ?>
													</div>
												</div>
												<input type="hidden" name="gift_card">
											</div>
											<hr id="rezgo-gift-card-use-hr">
										<?php } ?>

										<div class="rezgo-payment-frame" id="payment_info">
											<p class="select-payment">Select a payment method</p>
											
											<div class="form-group" id="payment_methods">
												<?php
													$card_fa_logos = array(
														'visa' => 'fa-cc-visa',
														'mastercard' => 'fa-cc-mastercard',
														'american express' => 'fa-cc-amex',
														'discover' => 'fa-cc-discover'
													);
													$pmc = 1; // payment method counter 1
												?>
												<?php foreach($site->getPaymentMethods() as $pay ) { ?>	

													<?php if($pay['name'] == 'Credit Cards') { ?>

														<?php $set_name = $pay['name']; ?>

														<div class="rezgo-input-radio">
															<input type="radio" name="payment_method" id="payment_method_credit" class="rezgo-payment-method required" value="Credit Cards" onclick="toggleCard();">

															<label for="payment_method_credit" class="payment-label">

																<div id="icon-container" class="icon-container">
																	<?php foreach($site->getPaymentCards() as $card ) { ?>	
																		<i class="fab <?php echo $card_fa_logos[$card]?>"></i>
																	<?php } ?>
																</div>

															</label>

															<input type="hidden" name="tour_card_token" id="tour_card_token" value="" />
                                                            
                                                            <input type="hidden" name="payment_id" id="payment_id" value="" />

															<script>
																jQuery(document).ready(function($) {
																	$('#tour_card_token').val('');
																});
															</script>
														</div>
													<?php } else { ?>
														<?php if ($pay['name'] == 'PayPal') { ?>
															<?php $set_name = '
															<img src="'.$site->path.'/img/logos/paypal.png" style="height:25px; width:auto;">'; ?>
														<?php } else { ?>
															<?php $set_name = $pay['name']; ?>
														<?php } ?>

														<div class="rezgo-input-radio">
															<input type="radio" name="payment_method" id="payment_method_<?php echo $pmc?>" class="rezgo-payment-method required" value="<?php echo $pay['name']?>" onclick="toggleCard();" />
															<label for="payment_method_<?php echo $pmc?>"><?php echo $set_name?></label>
														</div>

													<?php $pmc++; ?>
													<?php } ?>
												<?php } // end foreach($site->getPaymentMethods() ?>

													<!-- show only when payment is not required -->
													<div class="rezgo-input-radio no-payment" style="display:none;">
														<input type="radio" name="payment_method" id="no_payment_required" class="rezgo-payment-method required" value="no_payment_required" onclick="toggleCard();">

														<label for="no_payment_required">No payment required</label>
													</div>
													
											</div><!-- // #payment_methods -->

                                            <div id="payment_data">
                                                
                                                <?php $pmdc = 1; // payment method counter 1 ?>

                                                <script>
                                                    // create stripe initial error state because we use this to validate the form
                                                    var stripe_error = 0;
                                                </script>
                                                
                                                <?php foreach($site->getPaymentMethods() as $pay) { ?>
                                                    
                                                    <?php if($pay['name'] == 'Credit Cards') { ?>

                                                        <div id="payment_cards" class="payment_method_container" style="display:none;">
                                                            <h4 class="payment-method-header">Credit Card Details</h4>
                                                            
                                                            <?php if((string) $company->gateway_id == 'stripe_connect') { ?>

                                                                <!-- Stripe Elements -->
                                                                <script src="https://js.stripe.com/v3/"></script>
                                                                <style>

                                                                    /* From - https://stripe.com/docs/stripe-js */

                                                                    .rezgo-booking-payment-body {
                                                                        padding: 0 0 8px 0;
                                                                    }

                                                                    .StripeElement {
                                                                        box-sizing: border-box;
                                                                        height: 34px;
                                                                        padding: 8px 12px;
                                                                        border: 1px solid #CCC;
                                                                        border-radius: 4px;
                                                                        background-color: white;
                                                                        box-shadow: none;
                                                                        -webkit-transition: box-shadow 150ms ease;
                                                                        transition: box-shadow 150ms ease;
                                                                    }

                                                                    .StripeElement--focus {
                                                                        border-color: #66afe9;
                                                                        outline: 0;
                                                                        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
                                                                        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
                                                                    }

                                                                    .StripeElement--invalid {
                                                                        border-color: #fa755a;
                                                                    }

                                                                    .StripeElement--webkit-autofill {
                                                                        background-color: #fefde5 !important;
                                                                    }

                                                                    #stripe_cardholder_name {
                                                                        height: 34px;
                                                                    }

                                                                    #stripe_cardholder_name::placeholder {
                                                                        opacity: 1;
                                                                        color: #aab7c4;
                                                                        -webkit-font-smoothing: antialiased;
                                                                    }

                                                                    #stripe_cardholder_name_error {
                                                                        padding: 0 0 5px 0;
                                                                    }

                                                                    #stripe_cardholder_name,
                                                                    #card-element {
                                                                        font-family: 'Helvetica Neue', sans-serif;
                                                                        max-width: 400px;
                                                                    }

                                                                    .stripe-payment-title {
                                                                        font-size: 16px;
                                                                    }

                                                                    #card-element {
                                                                        margin-top: 5px;
                                                                    }

                                                                    #card-errors {
                                                                        padding: 5px 0;
                                                                        color: #a94442;
                                                                        font-size: 14px;
                                                                    }

                                                                    @media screen and (max-width: 650px) {
                                                                        #secureFrame {
                                                                            width: 100%;
                                                                            height: 400px;
                                                                        }
                                                                    }

                                                                    @media screen and (max-width: 500px) {
                                                                        #stripe_cardholder_name,
                                                                        #card-element {
                                                                            font-size: 13px;
                                                                            max-width: 270px;
                                                                        }
                                                                    }

                                                                </style>
                                                                <div class="form-row rezgo-booking-payment-body">

                                                                    <input id="stripe_cardholder_name" class="StripeElement form-control"
                                                                           name="stripe_cardholder_name" placeholder="Name on Card">
                                                                    <span id="stripe_cardholder_name_error" class="payment_method_error">Please enter the cardholder's name</span>

                                                                    <div id="card-element">
                                                                        <!-- Stripe Element will be inserted here. -->
                                                                    </div>

                                                                    <!-- Apple Pay  -->
                                                                    <div id="payment-request-button">
                                                                        <!-- A Stripe Element will be inserted here. -->
                                                                    </div>

                                                                    <!-- Used to display form errors. -->
                                                                    <div id="card-errors" role="alert"></div>

                                                                    <input type="hidden" name="client_secret" id="client_secret" value=""/>

                                                                </div>

                                                                <script>

                                                                    function createPaymentIntent() {
																		jQuery.ajax({
																			url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo',
																			context: document.body,
                                                                            dataType: "json",
																			data: {
																				method: 'gateways_stripe',
																				rezgoAction: 'stripe_create',
																				amount: order_total,
																				currency:'<?php echo $currency_base?>',
																			},
																			success: function(data){
																				
																				// if payment intent was created, place client secret
                                                                                var clientSecret = data.client_secret;
                                                                                var paymentId = data.payment_id;

                                                                                jQuery('#client_secret').val(clientSecret);
                                                                                jQuery('#payment_id').val(paymentId);
                                                                            }
                                                                        });
                                                                    }

																	function updatePaymentIntent() {
                                                                        let payment_id = jQuery('#payment_id').val();
																		jQuery.ajax({
																			context: document.body,
                                                                            dataType: "json",
																			url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo',
																			data: {
																				method: 'gateways_stripe',
																				rezgoAction: 'stripe_update_total',
																				amount: overall_total,
																				currency: '<?php echo $currency_base?>',
																				payment_id: payment_id, 
																			},
																		});
                                                                    }

                                                                    // Create a Stripe client.
                                                                    let stripe = Stripe('<?php echo REZGO_STRIPE_PUBLIC_KEY?>', {stripeAccount: '<?php echo $company->public_gateway_token?>'});
																	
																	// Create an instance of Elements.
                                                                    var elements = stripe.elements();
                                                                    var style = {
                                                                        base: {
                                                                            color: '#32325d',
                                                                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                                                                            fontSmoothing: 'antialiased',
                                                                            fontSize: '14px',
                                                                            '::placeholder': {
                                                                                color: '#aab7c4'
                                                                            }
                                                                        },
                                                                        invalid: {
                                                                            color: '#a94442',
                                                                            iconColor: '#a94442'
                                                                        }
                                                                    };

                                                                    var cardHolder = jQuery('#stripe_cardholder_name');

                                                                    var cardInput = document.getElementById('card-element');

                                                                    // Create an instance of the card Element.
                                                                    var card = elements.create('card', {
                                                                        style: style,
                                                                        hidePostalCode: true,
                                                                    });

                                                                    var displayError = document.getElementById('card-errors');

                                                                    // Add an instance of the card Element into the #card-element div.
                                                                    card.mount('#card-element');

                                                                    window.addEventListener('resize', resizeStripe);

                                                                    function resizeStripe() {
                                                                        if (window.innerWidth <= 500) {
                                                                            card.update({
                                                                                style: {base: {fontSize: '13px'}},
                                                                                hideIcon: true,
                                                                            });
                                                                        } else {
                                                                            card.update({
                                                                                style: {base: {fontSize: '14px'}},
                                                                                hideIcon: false,
                                                                            });
                                                                        }
                                                                    }

																	let order_total = jQuery("#expected").val() * 1;
																	createPaymentIntent();

                                                                    // create stripe error state to handle form error submission
                                                                    stripe_error = 1;

                                                                    // Handle real-time validation errors from the card Element.
                                                                    card.addEventListener('change', function (event) {

                                                                        if (event.error) {
                                                                            displayError.textContent = event.error.message;
                                                                            stripe_error = 1;
                                                                            cardInput.style.borderColor = '#a94442';
                                                                            // console.log('listener error');
                                                                        } else if (event.empty) {
                                                                            displayError.textContent = 'Please enter your Credit Card details';
                                                                            stripe_error = 1;
                                                                        } else {
                                                                            displayError.textContent = '';
                                                                            stripe_error = 0;
                                                                            cardInput.style.borderColor = '#ccc';
                                                                        }
                                                                    });

                                                                    cardHolder.change(function () {
                                                                        if (jQuery(this).val() == '') {
                                                                            jQuery('#stripe_cardholder_name_error').show();
                                                                            jQuery(this).css({'borderColor': '#a94442'});
                                                                            stripe_error = 1;
                                                                        } else {
                                                                            jQuery('#stripe_cardholder_name_error').hide();
                                                                            jQuery(this).css({'borderColor': '#ccc'});
                                                                            stripe_error = 0;
                                                                        }
                                                                    });

                                                                    // // Create an instance of paymentRequest.
                                                                    // var paymentRequest = stripe.paymentRequest({
                                                                    // 	country: 'US',
                                                                    // 	currency: 'usd',
                                                                    // 	total: {
                                                                    // 		label: 'Demo total',
                                                                    // 		amount: 1099,
                                                                    // 	},
                                                                    // 	requestPayerName: true,
                                                                    // 	requestPayerEmail: true,
                                                                    // 	});

                                                                    // var prButton = elements.create('paymentRequestButton', {
                                                                    // 	paymentRequest: paymentRequest,
                                                                    // });

                                                                    // // Check the availability of the Payment Request API first.
                                                                    // paymentRequest.canMakePayment().then(function(result) {
                                                                    // if (result) {
                                                                    // 	prButton.mount('#payment-request-button');
                                                                    // } else {
                                                                    // 	document.getElementById('payment-request-button').style.display = 'none';
                                                                    // }
                                                                    // });

                                                                </script>

                                                            <?php } elseif((string) $company->gateway_id == 'tmt') { ?>

                                                                <h4 class="payment-method-header">You will be asked for your credit card details when you complete your booking</h4>

																<script src="https://payment.tmtprotects.com/tmt-payment-modal.3.6.0.js"></script>
                                                                <script>
                                                                    let tmt_data;
                                                                    jQuery.ajax('<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=gateways_tmt&amount=<?php echo $complete_booking_total?>').done(function(result) {
                                                                        tmt_data = JSON.parse(result);
                                                                        //console.log('TMT DATA');
                                                                        //console.log(tmt_data);
                                                                    });

                                                                </script>
                                                            
                                                            <?php } else { ?>

																<iframe scrolling="no" frameborder="0" name="tour_payment" id="tour_payment" src="<?php echo home_url(); ?>?rezgo=1&mode=booking_payment"></iframe>

                                                                <script type="text/javascript">
                                                                    iFrameResize({
                                                                        enablePublicMethods: true,
                                                                        scrolling: false
                                                                    }, '#tour_payment');
                                                                </script>
                                                            
                                                            <?php } ?>

                                                        </div> <!-- div payment_cards -->
                                                    
                                                    <?php } else { ?>
                                                        <div id="payment_method_<?php echo $pmdc?>_box" class="payment_method_box" style="display:none;">
                                                            
                                                            <?php if($pay['add']) { ?>
                                                                <div id="payment_method_<?php echo $pmdc?>_container" class="payment_method_container">
                                                                    <h4 class="payment-method-header"><?php echo $pay['name']?></h4>
                                                                    <input type="text" id="payment_method_<?php echo $pmdc?>_field"
                                                                           class="form-control payment_method_field" name="payment_method_add"
                                                                           placeholder="<?php echo $pay['add']?>" value="" disabled="disabled"/>
                                                                    <span id="payment_method_<?php echo $pmdc?>_error" class="payment_method_error">Please enter a value</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        
                                                        <?php $pmdc++; ?>
                                                    <?php } ?>
                                                <?php } // end foreach($site->getPaymentMethods() ?>
                                            </div><!-- // #payment_data -->
										<hr>

										</div><!-- // #payment_info -->
									</div><!-- // payment-select-container -->

						 	 <!-- BILLING INFO -->
							<div class="rezgo-billing-wrp" style="display:none;">
								<div class="row">
									<div class="col-xs-12">

										<h3 id="rezgo-billing-information-header" class="text-info">
											<span>
												3. Billing Information &nbsp;
											</span>

											<span id="rezgo-copy-pax-span" style="display:none">
												<br class="visible-xs-inline"/>
												<input type="checkbox" name="copy_pax" id="rezgo-copy-pax" />
												<span id="rezgo-copy-pax-desc" class="rezgo-memo">Use first passenger information</span>
											</span>
										</h3>

										<div class="form-group">
											<label for="tour_first_name" class="control-label">Name</label>

											<div class="rezgo-form-row"> 
												<div class="col-sm-6 rezgo-form-input">
													<input type="text" class="form-control required" id="tour_first_name" name="tour_first_name" value="<?php echo $lead_passenger['first_name']?>" placeholder="First Name" />
												</div>

												<div class="col-sm-6 rezgo-form-input">
													<input type="text" class="form-control required" id="tour_last_name" name="tour_last_name" value="<?php echo $lead_passenger['last_name']?>" placeholder="Last Name" />
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="tour_address_1" class="control-label">Address</label>

											<div class="rezgo-form-input col-xs-12">
												<input type="text" class="form-control required" id="tour_address_1" name="tour_address_1" placeholder="Address 1" />
											</div>
										</div>

										<div class="form-group clearfix">
											<div class="rezgo-form-input col-xs-12">
												<input type="text" class="form-control" id="tour_address_2" name="tour_address_2" placeholder="Address 2 (optional)" />
											</div>
										</div>

										<div class="form-group">
											<div class="rezgo-form-row">
												<label for="tour_city" class="control-label col-sm-8 col-xs-12 rezgo-form-label">City</label>
												<label for="tour_postal_code" class="control-label col-sm-4 hidden-xs rezgo-form-label">Zip/Postal</label>
											</div>

											<div class="rezgo-form-row">
												<div class="col-sm-8 col-xs-12 rezgo-form-input">
													<input type="text" class="form-control required" id="tour_city" name="tour_city" placeholder="City" />
												</div>

												<label for="tour_postal_code" class="control-label col-xs-12 hidden-lg hidden-md hidden-sm rezgo-form-label">Zip/Postal</label>
												<div class="col-sm-4 col-xs-12 rezgo-form-input">
													<input type="text" class="form-control required" id="tour_postal_code" name="tour_postal_code" placeholder="Zip/Postal Code" />
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="rezgo-form-row">
												<label for="tour_country" class="control-label col-sm-8 rezgo-form-label">Country</label>
												<label for="tour_stateprov" class="control-label col-sm-4 hidden-xs rezgo-form-label">State/Prov</label>
											</div>

											<div class="rezgo-form-row">
												<div class="col-sm-8 col-xs-12 rezgo-form-input">
												<select class="form-control" name="tour_country" id="tour_country">
													<option value=""></option>
													<?php foreach($site->getRegionList() as $iso => $name ) { ?>
														<option value="<?php echo $iso?>" <?php echo (($iso == $companyCountry) ? 'selected' : '')?>><?php echo ucwords($name)?></option>
													<?php } ?>
												</select>
												</div>

												<div class="col-sm-4 col-xs-12 rezgo-form-input">
													<div class="rezgo-form-row hidden-lg hidden-md hidden-sm">
														<label for="tour_stateprov" class="control-label col-xs-12 rezgo-form-label">State/Prov</label>
													</div>
													<select class="form-control" id="tour_stateprov" style="display:<?php echo (($companyCountry != 'ca' && $companyCountry != 'us' && $companyCountry != 'au') ? 'none' : '')?>;"></select>
													<input id="tour_stateprov_txt" class="form-control" name="tour_stateprov" type="text" value="" style="display:<?php echo (($companyCountry != 'ca' && $companyCountry != 'us' && $companyCountry != 'au') ? '' : 'none')?>;" />
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="rezgo-form-row">
												<label for="tour_email_address" class="control-label col-sm-6 rezgo-form-label">Email</label>
												<label for="tour_phone_number" class="control-label col-sm-6 hidden-xs rezgo-form-label">Phone</label>
											</div>

											<div class="rezgo-form-row">
												<div class="col-sm-6 col-xs-12 rezgo-form-input">
													<input type="email" class="form-control required" id="tour_email_address" name="tour_email_address" value="<?php echo $lead_passenger['email']?>" placeholder="Email" />
												</div>

												<label for="tour_phone_number" class="control-label col-sm-6 col-xs-12 hidden-lg hidden-md hidden-sm rezgo-form-label">Phone</label>

												<div class="col-sm-6 col-xs-12 rezgo-form-input">
													<input type="text" class="form-control required" id="tour_phone_number" name="tour_phone_number" placeholder="Phone" />
												</div>
											</div>
										</div>

										<div class="form-group rezgo-sms">
											<div class="rezgo-form-row">
													<span>Would you like to receive SMS messages regarding your booking? If so, please enter your mobile number in the space provided. Please note that your provider may charge additional fees.</span>
											</div>
										</div>

										<div class="form-group rezgo-sms-input">
											<div class="rezgo-form-row">
												<label for="tour_sms" class="control-label col-sm-12 rezgo-form-label">SMS</label>
											</div>
											<div class="rezgo-form-row">
												<div class="col-sm-12 rezgo-form-input">
													<input type="text" name="tour_sms" id="tour_sms" class="form-control col-xs-12" value="" />
													<input type="hidden" name="sms" id="sms" value="" />
												</div>
											</div>
										</div>
									</div>
								</div>
							    
                                <hr>
                                
                                <input type="hidden" name="validate[language]" id="validate_language" value="">
                                <input type="hidden" name="validate[height]" id="validate_height" value="">
                                <input type="hidden" name="validate[width]" id="validate_width" value="">
                                <input type="hidden" name="validate[tz]" id="validate_tz" value="">
                                <input type="hidden" name="validate[agent]" id="validate_agent" value="">
                                <input type="hidden" name="validate[callback]" id="validate_callback" value="">
                                
                                <script>
                                    jQuery('#validate_language').val(navigator.language);
                                    jQuery('#validate_height').val(window.innerHeight);
                                    jQuery('#validate_width').val(window.innerWidth);
                                    jQuery('#validate_tz').val(new Date().getTimezoneOffset());
                                    jQuery('#validate_agent').val(navigator.userAgent);
                                    jQuery('#validate_callback').val(window.location.protocol + '//' + window.location.hostname + '<?php echo $site->base?>' + '/3DS');
                                </script>
                                
							</div>

							<?php
								// $site_base_currency = strtoupper($site->getBookingCurrency());

								// if(in_array($site_base_currency, $tg_supported_currencies) && $company->ticketguardian) {
								// 	$tg_display_currency = $site_base_currency;
								// }

								// if(($company->ticketguardian) && ($tg_display_currency !== 0) && ($site->getGateway())) {
								// 	$tg_enabled = 1;
								// }
							?>
							<script>
							//tg item prices for quote
								// tg_booking_prices = split_total.slice(0);
								// tg_booking_prices_total = tg_booking_prices.reduce(function(acc, val) { return acc + val; }, 0);
							</script>
							<!-- TICKET GUARDIAN -->
							<?php //if($tg_enabled && (string) $company->gateway_id != 'stripe_connect') {?>
							<div id="rezgo-ticket-guardian" class="form-group" style="display:none;">
								<div class="row rezgo-form-group rezgo-booking">
									<div class="col-xs-12">
										<h3 class="text-info">
											<span>Protect Your Booking With <span><img class="tg_logo" src="<?php echo $site->path?>/img/ticketguardian/TOURS-TGmirr-logo.png"/></span><span id="tg_recommended">(recommended)</span></span>
										</h3>
										<div id="tg_loader"><center>Loading Quote... <i class="fa fa-circle-o-notch fa-spin"></i></center></div>
										<div id="ticket_guardian" style="display:none;">
											<div id="tg-accept">
												<div class="rezgo-form-row">
													<div class="col-xs-8">
														<label id="tg_accept_label"><input type="radio" name="tg_selection" id="tg_selection" value="true"><span>&nbsp;&nbsp;</span>Yes, protect my booking</label>
													</div>
													<div class="col-xs-4">
														<strong class="rezgo-item-total" id="rezgo-tg-quote"></strong>
													</div>
												</div>
												<div class="form-group" id="tg_protect_list">
													<div class="rezgo-form-row">
														<div class="col-xs-12" >
																<span class="col-xs-12">Protect against:</span>
																<div class="col-xs-12" >
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-8.png">Illness & Health</span>
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-10.png">Military or Jury Duty</span>
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-9.png">Weather</span>
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-6.png">Family Emergencies</span>
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-2.png">Traffic Accidents</span>
																	<span class="col-xs-6"><img class="tg_widget_icons" src="<?php echo $site->path?>/img/ticketguardian/TSH-IC-14.png">Work Travel Conflicts</span>
																</div>
																<span class="col-xs-12">Get a full refund (minus premium) and peace of mind</span>
														</div>
													</div>
												</div>
												<div class="rezgo-form-row">
													<div class="col-xs-12">
														<div class="col-xs-12" id="tg_terms"><a href="javascript:void(0);" title="Terms and Conditions" onclick="window.open('https://www.ticketguardian.net/standard-protection-tc','ticketguardian','location=0,menubar=0,resizable=1,titlebar=0,toolbar=0,status=0,scrollbars=1,width=750,height=320');">Click here for terms and conditions</a></div>
													</div>
												</div>
												<div class="rezgo-form-row">
													<div class="col-xs-12">
														<div class="col-xs-12 tg_sub_text" id="tg_protected_by">Protected by TourShield Administered by FanShield LLC. Please see individual policy for underwriter details</div>
													</div>
												</div>
												<div class="rezgo-form-row">
													<div class="col-xs-12">
														<div class="col-xs-12 tg_sub_text" id="tg_help_block"><span>I understand that my personal information including credit card will be shared with TourShield for the purposes of purchasing ticket protection.</span></div>
													</div>
												</div>
											</div>
											<div id="tg-decline">
												<div class="form-group">
													<div class="rezgo-form-row">
														<div class="col-xs-12">
															<label id="tg_decline_label"><input type="radio" name="tg_selection" id="tg_selection" value="false"><span>&nbsp;&nbsp;</span>No, I'm willing to take the risk and understand it isn't available after booking </label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="rezgo-form-row">
														<div class="col-xs-12" id="tg-decline-sub">
															<div class="col-xs-12">
																<span><i class="fa fa-clock-o"></i>&nbsp;Don't miss out!</span> Not available after booking
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input type="hidden" name="tour_tg_insurance_coverage" id="tour_tg_insurance_coverage"/>
									</div>
								</div>
								<hr>
							</div>
                            
                            <?php //} //end if($tg_display_currency != 0)?>

							<!-- PAYMENT INFO -->
							<div id="rezgo-payment-wrp">
								<div class="row">
									<div class="col-xs-12">
                                        <div class="rezgo-form-row rezgo-terms-container">
                                            <div class="col-sm-12 rezgo-payment-terms">
                                                <div class="rezgo-form-input">

                                                    <div class="checkbox">
                                                        <label id="rezgo-terms-check">

                                                            <input type="checkbox" class="required" id="agree_terms" name="agree_terms" value="1"/>
                                                            <span>I agree to the </span>
                                                        </label>

                                                        <a data-toggle="collapse" class="rezgo-terms-link" onclick="jQuery('#rezgo-privacy-panel').hide(); jQuery('#rezgo-terms-panel').toggle();">
                                                            <span>Terms and Conditions</span>
                                                        </a>
                                                        and
                                                        <a data-toggle="collapse" class="rezgo-terms-link" onclick="jQuery('#rezgo-terms-panel').hide(); jQuery('#rezgo-privacy-panel').toggle();">
                                                            <span>Privacy Policy</span>
                                                        </a>

                                                        <div id="rezgo-terms-panel" class="collapse rezgo-terms-panel">
                                                            <?php echo $site->getPageContent('terms')?>
                                                        </div>

                                                        <div id="rezgo-privacy-panel" class="collapse rezgo-terms-panel">
                                                            <?php echo $site->getPageContent('privacy')?>
                                                        </div>

                                                    </div>
                                                    
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <?php if($company->marketing_consent == 1) { ?>
                                            <div class="rezgo-form-row rezgo-terms-container">
                                                <div class="col-sm-12 rezgo-payment-terms">
                                                    <div class="rezgo-form-input">
    
                                                        <div class="checkbox">
                                                            <label id="rezgo-marketing-terms-label">
                                                                <input type="checkbox" id="marketing_consent" name="marketing_consent" value="1"/>
                                                                <span>Please keep me up to date with news from <?php echo $company->company_name?></span>
                                                            </label>
                                                        </div>
                                                    
                                                    </div>
    
                                                </div>
                                            </div>
										<?php } ?>

										<div class="rezgo-form-row">
											<div class="col-sm-12 rezgo-payment-terms">

												<div id="rezgo-book-terms">
													<div class="help-block" id="terms_credit_card" style="display:<?php if(!$site->getPaymentMethods('Credit Cards')) { ?>none<?php } ?> ;">
														<?php if($site->getGateway() OR $site->isVendor()) { ?>
															<?php if($complete_booking_total > 0) { ?>
																<span class='terms_credit_card_over_zero'>Please note that your credit card will be charged.</span>
																<br>
															<?php } ?>
															<span>If you are satisfied with your entries, please confirm by clicking the &quot;Complete Booking&quot; button.</span>
														<?php } else { ?>
															<?php if($complete_booking_total > 0) { ?>
																<span class='terms_credit_card_over_zero'>Please note that your credit card will not be charged now. Your transaction information will be stored until your payment is processed. Please see the Terms and Conditions for more information.</span>
																<br>
															<?php } ?>
															<span>If you are satisfied with your entries, please click the &quot;Complete Booking&quot; button.</span>
														<?php } ?>
													</div>

													<div class="help-block" id="terms_other" style="display:<?php if($site->getPaymentMethods('Credit Cards')) { ?>none<?php } ?>;">
														<span>If you are satisfied with your entries, please confirm by clicking the &quot;Complete Booking&quot; button.</span>
													</div>
												</div>

												<div id="rezgo-book-message" class="row" style="display:none;">
													<div id="rezgo-book-message-body" class="col-sm-8 col-sm-offset-2"></div>
                         							 <div id="rezgo-book-message-wait" class="col-sm-2"><i class="far fa-sync fa-spin fa-3x fa-fw"></i></div>
												</div>
											</div>
                                          
										</div>

									</div>
								</div>
							</div> <!-- payment wrp -->

								<?php if (DEBUG) { ?>
									<div id="debug_container" class="text-center" style="display:none;">
										<p> DEBUG API REQUEST </p>
										<textarea id="api_request_debug" readonly="readonly" rows="10"></textarea>
										<hr>
										<button id="api_send_request" class="btn btn-default" >Send Request</button>
									</div>

									<script>
										jQuery('#api_send_request').click(function(e){
											e.preventDefault();
											submit_booking();
										})
									</script>
								<?php } ?>

								<div id="rezgo-book-step-two-nav" class="rezgo-booking-nav">
									<div class="col-sm-12 col-xs-12 rezgo-btn-wrp rezgo-complete-btn-wrp">
										<button type="submit" class="btn rezgo-btn-book btn-lg btn-block" id="rezgo-complete-booking">
											Complete Booking
										</button>
									</div>

									<div id="rezgo-bottom-cta">
										<a class="btn rezgo-btn-default btn-lg btn-block" style="display:flex; justify-content:center;" href="<?php echo $site->base?>/book"><span>Previous Step</span></a>
									</div>
								</div>
							</div> <!-- checkout container --> 

							<!-- FIXED CART -->
							<?php require('fixed_cart.php');?>

							</div> <!-- flex container -->

								<div id="rezgo-book-errors-wrp">
									<div id="rezgo-book-errors" class="alert alert-danger" style="display:none;">
										<span>Some required fields are missing. Please complete the highlighted fields.</span>
									</div>									
								</div> <!-- // book errors -->
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="scaModal" aria-hidden="true" id="sca_modal" style="bottom:20px !important; top:auto !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="position:relative; top:3px; float:left;">Card Validation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width:50px; text-decoration:none;">
                    <span aria-hidden="true" style="font-size:32px;">&times;</span>
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body" id="sca_modal_content" style="height:640px;">
                <iframe style="border:0; width:100%; height:100%;" name="sca_modal_frame" id="sca_modal_frame"></iframe>
            </div>
        </div>
    </div>
</div>

<script>

jQuery(document).ready(function($){

	var response; // needs to be global to work in timeout
	var paypalAccount = 0;

	var ca_states = <?php echo  json_encode( $site->getRegionList('ca') ); ?>;
	var us_states = <?php echo  json_encode( $site->getRegionList('us') ); ?>;
	var au_states = <?php echo  json_encode( $site->getRegionList('au') ); ?>;

	// Catch form submissions
	$('#rezgo-book-form').submit(function(evt) {
		evt.preventDefault();

		<?php if (DEBUG) { ?>
			var validate_check = validate_form();
			if (validate_check){
				// show debug window with update request
				$('#rezgo-book-form').ajaxSubmit({
					url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
					data: { rezgoAction: 'commit_debug' },
					success: function(data){
						// console.log(data);
						$('#debug_container').show();
						$('#api_request_debug').html(data);
					}
				});
			} else {
				return error_booking();
			}

		<?php } else { ?>
			submit_booking();
		<?php } ?>
	});

	$('#tour_country').change(function() {
		var country = $(this).val();

		// set SMS country
		$("#tour_sms").intlTelInput("setCountry", $(this).val());

		$('#tour_stateprov').removeOption(/.*/);

		switch (country) {
			case 'ca':
				$('#tour_stateprov_txt').hide();
				$('#tour_stateprov').addOption(ca_states, false).show();
				$('#tour_stateprov_txt').val($('#tour_stateprov').val());
				break;
			case 'us':
				$('#tour_stateprov_txt').hide();
				$('#tour_stateprov').addOption(us_states, false).show();
				$('#tour_stateprov_txt').val($('#tour_stateprov').val());
				break;
			case 'au':
				$('#tour_stateprov_txt').hide();
				$('#tour_stateprov').addOption(au_states, false).show();
				$('#tour_stateprov_txt').val($('#tour_stateprov').val());
				break;
			default:
				$('#tour_stateprov').hide();
				$('#tour_stateprov_txt').val('');
				$('#tour_stateprov_txt').show();
				break;
		}
	});

	$('#tour_stateprov').change(function() {
		var state = $(this).val();
		$('#tour_stateprov_txt').val(state);
	});

	<?php if(in_array($site->getCompanyCountry(), array('ca', 'us', 'au'))) { ?>
		$('#tour_stateprov').addOption(<?php echo $site->getCompanyCountry();?>_states, false);

		$('#tour_stateprov_txt').val($('#tour_stateprov').val());
	<?php } ?>

	if(typeof String.prototype.trim != 'function') {
		// detect native implementation
		String.prototype.trim = function () {
			return this.replace(/^\s+/, '').replace(/\s+$/, '');
		};
	}
	
	// SCA passthrough data
    let passthrough = '';

    // show the sca challenge window if the gateway requires it
	function sca_window(mode, url, data, pass) {
	   
	    //console.log("SCA CHALLENGE MODE: " + mode);
     
	    console.log(url);
	    console.log(data);

	    if(pass) {
            passthrough = pass;
            //console.log("SETTING PASSTHROUGH DATA: " + passthrough);
        }
        
	    if(mode == 'iframe') {

            $('#sca_modal').modal();

            let content = data ? JSON.parse(data) : null;
            
	        let form = '<form action="' + url + '" method="post" target="sca_modal_frame" id="sca_post">';
         
	        if(content) {
                $.each(content, function(index, value) {
                    form += '<input type="hidden" name="' + index + '" value="' + value + '">';
                });
            }
	        
            form += '</form>';
            
	        //console.log(form);
	        
            $('body').append(form);
            
            $('#sca_post').submit().remove();
            
        }
	    
    }
	
	// called by the sca challenge window callback URL
	sca_callback = function(code) {
	   
	    if(!code) return false;
	    
	    //console.log(code);

        $('#sca_modal').modal('hide');

        if(passthrough) {
            let data = JSON.parse(code); // parse data sent back from 3DS
            data.pass = passthrough; // add the passthrough data to the array
            code = JSON.stringify(data);
        }
        
        $('#tour_card_token').val(code);
        $('#payment_id').val(1); // needed to trigger the validate step on commit

        $('#rezgo-book-message-body').html('Please wait one moment ...');
        $('#rezgo-complete-booking').attr('disabled','disabled');
        $('#rezgo-book-message').fadeIn();

        booking_wait(true);
        
        $('#rezgo-book-form').ajaxSubmit({
            url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
            data: {rezgoAction: 'book'},
            success: delay_response,
            error: function () {
                var body = 'Sorry, the system has suffered an error that it can not recover from.<br />Please try again later.<br />';
                $('#rezgo-book-message-body').html(body);
                $('#rezgo-book-message-body').addClass('alert alert-warning');
            }
        });
	    
    }

	// change the modal dialog box or pass the user to the receipt depending on the response
	function show_response() {
	 
		response = response.trim();
		
		console.log('SHOWING RESPONSE: ' + response);

		let title = '';
		let body = '';

        if(response.indexOf('STOP::') != -1) {  // debug handling

            let split = response.split('<br><br>');

            try {
                response = JSON.parse(split[1]);
            } catch (error) {
                response.status = 999;
            }
            
            if(response.status != '1') {
                $('#rezgo-complete-booking').val('Complete Booking');
                $('#rezgo-complete-booking').removeAttr('disabled');
            }
            
            if(response.status == 1) {
                split[1] = '<div class="clearfix">&nbsp;</div>BOOKING COMPLETED WITHOUT ERRORS<div class="clearfix">&nbsp;</div><button type="button" class="btn btn-default" onclick="window.top.location.replace(\'<?php echo $site->base?>/complete/' + response.txid + '\');">Continue to Receipt</button><div class="clearfix">&nbsp;</div>';
            } else if(response.status == '8') {
                // an SCA challenge is required for this transaction
                sca_window('iframe', response.url, response.post, response.pass);
            } else {
                split[1] = '<br /><br />Error Code: ' + response.status + '<br />Error Message: ' + response.message + '<br />';
            }
            
            body = 'DEBUG-STOP ENCOUNTERED<br /><br />' + '<textarea id="debug_response">' + split[0] + '</textarea>' + split[1];

        } else {
            
            try {
                response = JSON.parse(response);
            } catch (error) {
                response.status = 999;
            }

            if(response.status != '1') {
                $('#rezgo-complete-booking').val('Complete Booking');
                $('#rezgo-complete-booking').removeAttr('disabled');
            }
            
            if(response.status == '2') {
                title = 'No Availability Left';
                body = 'Sorry, there is not enough availability left for this item on this date.<br />';
            }
            else if(response.status == '3') {
                title = 'Payment Error';
                body = 'Sorry, your payment could not be completed. Please verify your card details and try again.<br /';
            }
            else if(response.status == '4') {
                title = 'Booking Error';
                body = 'Sorry, there has been an error with your booking and it can not be completed at this time.<br />';
            }
            else if(response.status == '5') {
                // this error should only come up in preview mode without a valid payment method set
                title = 'Booking Error';
                body = 'Sorry, you must have a credit card attached to your Rezgo Account in order to complete a booking.<br><br>Please go to "Settings &gt; Rezgo Account" to attach a credit card.<br />';
            }
            else if(response.status == '6') {
                // this error is returned when expected total does not match actual total
                title = 'Booking Error';
                body = 'Sorry, a price on an item you are booking has changed. Please return to the shopping cart and try again.<br />';
            }
            else if(response.status == '8') {
                // an SCA challenge is required for this transaction
                sca_window('iframe', response.url, response.post, response.pass);
            }
            else {
                
                if(response.txid) {
                    
                    var currency_base = $('#currency_base').val();
                    var order_value = $('#order_value').val();
                    
                    window.top.location.replace("<?php echo $site->base?>/complete/" + response.txid);
                    return true; // stop the html replace
                    
                } else {
                    
                    title = 'Booking Error';
                    body = 'Sorry, an unknown error has occurred. Our staff have already been notified. Please try again later.<br />';
                    
                    console.log(response);
                    
                }
                
            }
            
        }

		booking_wait(false);
		
        if(body) {
            $('#rezgo-book-message-body').html(body);
            $('#rezgo-book-message-body').addClass('alert alert-warning');
        }
	}

	// this function delays the output so we see the loading graphic
	function delay_response(responseText) {
		response = responseText;
        setTimeout(function () {
            show_response();
        }, 800);
	}

	function validate_form() {
		var valid = $('#rezgo-book-form').valid();

		return valid;
	}

	function error_booking() {
		$('#rezgo-book-errors').fadeIn();

		setTimeout(function () {
			$('#rezgo-book-errors').fadeOut();
		}, 5000);
		return false;
	}

    // booking wait time
    var seconds = 0;

    function booking_wait(wait) {

        if (wait) {
            $('#rezgo-book-message-wait').show();

            timex = setTimeout(function () {
                seconds++;

                if (seconds == 10) {

                    $("#rezgo-book-message-body").fadeOut(function () {
                        $(this).html('We are still working on your request. <br class="hidden-md hidden-lg" />Thank you for your patience.').fadeIn();
                    });
                    $("#rezgo-book-message-body").effect("highlight", {color: '#FCF6B0'}, 1500);

                } else if (seconds == 25) {

                    $("#rezgo-book-message-body").fadeOut(function () {
                        $(this).html('Your request is taking longer than expected. <br class="hidden-md hidden-lg" />Please hold on ...').fadeIn();
                    });
                    $('#rezgo-book-message-body').effect("highlight", {color: '#F9F6AF'}, 2000);

                } else if (seconds == 40) {

                    $("#rezgo-book-message-body").fadeOut(function () {
                        $(this).html('Working on payment processing. <br class="hidden-md hidden-lg" />Your order should be completed soon.').fadeIn();
                    });
                    $('#rezgo-book-message-body').effect("highlight", {color: '#F6F5AE'}, 2500);

                } else if (seconds == 55) {

                    $("#rezgo-book-message-body").fadeOut(function () {
                        $(this).html('So &hellip; do you have any plans for the weekend?').fadeIn();
                    });
                    $('#rezgo-book-message-body').effect("highlight", {color: '#ECF2AB'}, 2500);

                } else if (seconds == 70) {

                    $("#rezgo-book-message-body").fadeOut(function () {
                        $(this).html('We really had hoped to be done by now. <br class="hidden-md hidden-lg" />It shouldn\'t take much longer.').fadeIn();
                    });
                    $('#rezgo-book-message-body').effect("highlight", {color: '#E2EFA7'}, 2500);

                }

                // console.log(seconds);
                booking_wait(true);

            }, 1000);

        } else {

            if((typeof timex !== 'undefined')) clearTimeout(timex);
            $('#rezgo-book-message-body').html('');
            $('#rezgo-book-message-wait').hide();

        }

    }

    submit_booking = function() {
    
        var validate_check = validate_form();
    
        $('#rezgo-complete-booking').attr('disabled','disabled');
        $('#rezgo-book-message-body').removeClass('alert alert-warning');
        $('#rezgo-book-message-body').html('');
        $('#rezgo-book-message').fadeOut();
        $('#rezgo-book-terms').fadeIn();

        // if we set a card token via a SCA challenge, clear it for a potential new one
        if(passthrough) {
            $('#tour_card_token').val('');
            $('#payment_id').val('');
        }
    
        // only activate on actual form submission, check payment info
        if(overall_total > 0) {
    
            var force_error = 0;
            var payment_method = $('input:radio[name=payment_method]:checked').val();
    
            if(payment_method == 'Credit Cards') {
    
                <?php if ((string) $company->gateway_id == 'stripe_connect') { ?>

                    // catch empty fields on stripe
                    if (cardInput.classList.contains('StripeElement--empty')){
                        cardInput.style.borderColor = '#a94442';
                        displayError.textContent = 'Please enter your Credit Card details';
                        stripe_error = 1;
                    }
                    if( cardHolder.val() == '' ){
                        $('#stripe_cardholder_name').css({'borderColor':'#a94442'});
                        $('#stripe_cardholder_name_error').show();
                        stripe_error = 1;
                    }
    
                <?php } elseif ((string) $company->gateway_id == 'tmt') { ?>
                
                <?php } else { ?>

                    if(!$('#tour_payment').contents().find('#payment').valid()) {
                        force_error = 1;
                    }
    
                <?php } ?>
    
            } else {
                // other payment methods need their additional fields filled
                var id = $('input:radio[name=payment_method]:checked').attr('id');
                if($('#' + id + '_field').length != 0 && !$('#' + id + '_field').val()) {
                    // this payment method has additional data that is empty
                    force_error = 1;
    
                    $('#' + id + '_field').css('border-color', '#a94442');
                    $('#' + id + '_error').show();
                }
            }
        } else if (overall_total <= 0) {
            stripe_error = 0;
        }
    
        if(force_error || !validate_check || stripe_error) {
            console.log('force error: ' + force_error);
            console.log('stripe error: ' + stripe_error);
    
            $('#rezgo-complete-booking').removeAttr('disabled');
    
            return error_booking();
        } else {
    
            var payment_method = $('input:radio[name=payment_method]:checked').val();
    
            if(payment_method == 'Credit Cards' && overall_total > 0) {
    
                <?php if ((string) $company->gateway_id == 'stripe_connect') { ?>

					$('#rezgo-book-message-body').html('Please wait one moment ...');
					$('#rezgo-complete-booking').attr('disabled','disabled');
					$('#rezgo-book-message').fadeIn();
					booking_wait(true);

                    if (stripe_error != 1) {
    
                        // pass postal code from booking form to stripe
                        var postal_code = $('#tour_postal_code').val();
    
                        card.update({
                            value: {postalCode: postal_code}
                        });
    
                        // grab client secret and charge the card
                        clientSecret = $('#client_secret').val();
    
                        let cardholder_name = $('#stripe_cardholder_name').val();
    
                        stripe.confirmCardPayment(clientSecret,
                            {
                                payment_method: {
                                    card: card,
                                    billing_details: {
                                        name: cardholder_name,
                                    }
                                },
                                return_url: window.top.origin + '<?php echo $site->base?>' + '/3ds_return_url'
                            },
                            // Disable the default next action handling because we want to use an iframe
                            {handleActions: false}
                        ).then(function (result) {
    
                            //console.log('STRIPE INTENT DATA');
                            //console.log(result);
    
                            if (result.error) {
                                // Show error to your customer and disable form
                                var displayError = document.getElementById('card-errors');
                                displayError.textContent = result.error.message;

                                $('#rezgo-complete-booking').removeAttr('disabled');
								booking_wait(false);
                                $('#rezgo-book-message-body').html(result.error.message);
                                $('#rezgo-book-message-body').addClass('alert alert-warning');
    
                            } else {
    
                                // check if there is a next_action
                                var activate_3DS = result.paymentIntent.next_action !== null;
    
                                var three_d_complete = function (ev) {
                                    if (ev.data === '3DS-authentication-complete') {
                                        on3DSComplete();
                                    }
                                }
                                window.top.addEventListener('message', three_d_complete, false);
    
                                // trigger 3Dsecure flow if exists
                                if (activate_3DS) {

                                    var iframe = document.createElement('iframe');
                                    iframe.setAttribute("id", "secureFrame");
                                    iframe.src = result.paymentIntent.next_action.redirect_to_url.url;
                                    iframe.width = 500;
                                    iframe.height = 600;
                                    iframe.setAttribute("style", "position:absolute; z-index: 99; bottom:30px; margin:auto; left:0; right:0; border:0;");
    
                                    var bg = document.createElement('div');
                                    bg.setAttribute("style", "position:fixed; width: 100vw; height:100%; z-index: 98; left:0; bottom:0; background:rgba(0,0,0,0.70);");
    
                                    document.getElementById('rezgo-payment-wrp').appendChild(bg);
    
                                    setTimeout(() => {
                                        document.getElementById('rezgo-payment-wrp').appendChild(iframe);
                                    }, 250);
    
                                    function on3DSComplete() {
    
                                        // Hide the 3DS UI
                                        iframe.remove();
    
                                        setTimeout(() => {
                                            bg.remove();
                                        }, 250);
    
                                        // Check the PaymentIntent
                                        stripe.retrievePaymentIntent(clientSecret).then(function (result) {

                                            console.log(result);
                                            
                                            if (result.paymentIntent.status === 'requires_capture') {
                                                // Show your customer that the payment has succeeded, requires capture in BE

                                                $('#tour_card_token').val(result.paymentIntent.id);

                                                var displayError = document.getElementById('card-errors');
                                                displayError.textContent = '';

                                                $('#rezgo-book-message-body').html('Please wait one moment ...');

                                                $('#rezgo-book-terms').fadeOut().promise().done(function () {
                                                    $('#rezgo-book-message').fadeIn();
                                                });

                                                // wait until the card token is set before continuing (with throttling)
                                                function check_card_token() {
                                                    var card_token = $('#tour_card_token').val();

                                                    if (card_token == '') {
                                                        // card token has not been set yet, wait and try again
                                                        setTimeout(function () {
                                                            check_card_token();
                                                        }, 200);
                                                    } else {
                                                        // the field is present? submit normally
                                                        // alert('3DS flow token : ' + $('#tour_card_token').val() );
    
                                                                $('#rezgo-book-form').ajaxSubmit({
																	url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
                                                                    data: {rezgoAction: 'book'},
                                                                    success: delay_response,
                                                                    error: function () {
                                                                        var body = 'Sorry, the system has suffered an error that it can not recover from.<br />Please try again later.<br />';
                                                                        $('#rezgo-book-message-body').html(body);
                                                                        $('#rezgo-book-message-body').addClass('alert alert-warning');
                                                                    }
                                                                });
                                                            }
                                                        }
    
                                                        check_card_token();
    
                                            } else {
                                                
                                                $('#rezgo-complete-booking').removeAttr('disabled');
                                                
                                                booking_wait(false);
                                                
                                                $('#rezgo-book-message-body').html('Card authentication failed, Please try again');
                                                $('#rezgo-book-message-body').addClass('alert alert-warning');
                                                $('#rezgo-book-message').fadeIn();
                                                window.top.removeEventListener('message', three_d_complete, false);

                                            }
                                            
                                        }); //--- retrieve PaymentIntent
                                        
                                    } //--- function on3DSComplete()
				
                                } else if (!activate_3DS && result.paymentIntent.status === 'requires_capture') {
    
                                    // Show your customer that the payment has succeeded
                                    var displayError = document.getElementById('card-errors');
                                    displayError.textContent = '';

                                    $('#rezgo-book-message-body').html('Please wait one moment ...');
    
                                    $('#rezgo-book-terms').fadeOut().promise().done(function () {
                                        $('#rezgo-book-message').fadeIn();
                                    });
    
                                    $('#tour_card_token').val(result.paymentIntent.id);
                                    
                                    // submit normally
                                    $('#rezgo-book-form').ajaxSubmit({
                                        url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
                                        data: {rezgoAction: 'book'},
                                        success: delay_response,
                                        error: function () {
                                            var body = 'Sorry, the system has suffered an error that it can not recover from.<br />Please try again later.<br />';
                                            $('#rezgo-book-message-body').html(body);
                                            $('#rezgo-book-message-body').addClass('alert alert-warning');
                                        }
                                    });
                                   
                                }
    
                            }
                        }); //-- confirmCardPayment
                    }
    
                <?php } elseif ((string) $company->gateway_id == 'tmt') { ?>

                    $('#rezgo-complete-booking').removeAttr('disabled');
					<?php 
						$booked_for_date =  date("Y-m-d", max($booking_dates));

						$booking_items = implode(" | ", $booking_items);
						$booking_items = substr($booking_items, 0, 1020);
					?>
                
                    const tmtPaymentModal = new parent.tmtPaymentModalSdk({
                        path: tmt_data.path,
                        environment: tmt_data.account_mode,
                        transactionType: 'authorize',
                        data: {
                            // Booking Data
                            booking_id: "0",
                            channels: tmt_data.channel,
							date: "<?php echo $booked_for_date?>",
                            currencies: "<?php echo $currency_base?>",
                            total: (overall_total * 100),
                            description: "Rezgo Order: <?php echo $booking_items?>",
                            // Authentication
                            booking_auth: tmt_data.auth_string,
                            // Lead Traveller
                            firstname: $('#tour_first_name').val() ? $('#tour_first_name').val() : 'First',
                            surname: $('#tour_last_name').val() ? $('#tour_last_name').val() : 'Last',
                            email: $('#tour_email_address').val() ? $('#tour_email_address').val() : 'email@address.com',
                            country: $('#tour_country').val() ? $('#tour_country').val().toUpperCase() : 'CA',
                            // Payment details
                            payee_name: $('#tour_first_name').val() + ' ' + $('#tour_last_name').val(),
                            payee_email: $('#tour_email_address').val() ? $('#tour_email_address').val() : 'email@address.com',
                            payee_address: $('#tour_address_1').val() ? $('#tour_address_1').val() : 'Address',
                            payee_city: $('#tour_city').val() ? $('#tour_city').val() : 'City',
                            payee_country: $('#tour_country').val().toUpperCase(),
                            payee_postcode: $('#tour_postal_code').val() ? $('#tour_postal_code').val() : '0000'
                        }
                    });
					
                    console.log('REGISTERED TMT CALLBACKS');
                
                    let lock = 0;
                    
                    // successful transaction
                    tmtPaymentModal.on("transaction_logged", function (data) {
                        console.log("TRANSACTION LOGGED - ", data);
                        
                        if(lock == 1) return;
                        lock = 1;
                        
                        $('#rezgo-book-message-body').html('Please wait one moment ...');
                        $('#rezgo-complete-booking').attr('disabled','disabled');
                        $('#rezgo-book-message').fadeIn();

                        booking_wait(true);

                        tmtPaymentModal.closeModal();

                        $('#tour_card_token').val(data.id);
                        $('#payment_id').val(1); // tmt doesn't need this value, but it is needed to trigger the validate API
                        
                        $('#rezgo-book-form').ajaxSubmit({
							url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
                            data: {rezgoAction: 'book'},
                            success: delay_response,
                            error: function () {
                                var body = 'Sorry, the system has suffered an error that it can not recover from.<br />Please try again later.<br />';
                                $('#rezgo-book-message-body').html(body);
                                $('#rezgo-book-message-body').addClass('alert alert-warning');
                            }
                        });
                        
                    });
    
                    tmtPaymentModal.on("transaction_failed", function (data) {

                        if(lock == 1) return;
                        lock = 1;
                        
                        // console.log("TRANSACTION FAILED - ", data);
                        
                        tmtPaymentModal.closeModal();

                        $('#rezgo-book-message').show();
                        
                        response = '{"status":3, "message":"Payment Declined"}';
                        show_response();
                    });
                
                <?php } else { ?>

                    booking_wait(true);
    
                    $('#rezgo-book-message-body').html('Please wait one moment ...');
    
                    $('#rezgo-book-terms').fadeOut().promise().done(function () {
                        $('#rezgo-book-message').fadeIn();
                    });
    
                    // clear the existing credit card token, just in case one has been set from a previous attempt
                    $('#tour_card_token').val('');
    
                    // submit the card token request and wait for a response
                    $('#tour_payment').contents().find('#payment').submit();
    
                    // wait until the card token is set before continuing (with throttling)
                    function check_card_token() {
                        var card_token = $('#tour_card_token').val();
    
                        if (card_token == '') {
                            // card token has not been set yet, wait and try again
                            setTimeout(function () {
                                check_card_token();
                            }, 200);
                        } else {
                            // the field is present? submit normally
                            $('#rezgo-book-form').ajaxSubmit({
								url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
                                data: {rezgoAction: 'book'},
                                success: delay_response,
                                error: function () {
                                    var body = 'Sorry, the system has suffered an error that it can not recover from.<br />Please try again later.<br />';
                                    $('#rezgo-book-message-body').html(body);
                                    $('#rezgo-book-message-body').addClass('alert alert-warning');
                                }
                            });
                        }
                    }
    
                    check_card_token();
    
                <?php } ?>
                
                } else {
                    
                    booking_wait(true);
    
                    $('#rezgo-book-message').show();
                    $('#rezgo-book-message-body').html('Please wait one moment ...');
    
                    // not a credit card payment (or $0) and everything checked out, submit via ajaxSubmit (jquery.form.js)
                    $('#rezgo-book-form').ajaxSubmit({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
                        data: {
                            rezgoAction: 'book',
                        },
                        success: delay_response,
                        error: function() {
                            var body = 'Sorry, the system has suffered an error that it can not recover from.<br><br>Please try again later.<br />';
                            $('#rezgo-book-message-body').html(body);
                            $('#rezgo-book-message-body').addClass('alert alert-warning');
                        }
                    });
                }
    
                // return false to prevent normal browser submit and page navigation
                return false;
        }
    
    }

	// Validation Setup
	$.validator.setDefaults({
		highlight: function(element) {
			if ($(element).attr("type") == "checkbox") {
				$(element).closest('.rezgo-form-checkbox').addClass('has-error');
			} else if ($(element).attr("name")=="waiver") {
				$(element).parent().find('.error').show();
			} else if ($(element).attr("type") == "radio") {
				$(element).closest('.rezgo-input-radio').addClass('has-error');
			} else {
				$(element).closest('.rezgo-form-input').addClass('has-error');
			}
			$(element).closest('.form-group').addClass('has-error');

		},
		unhighlight: function(element) {
			if ( $(element).attr("type") == "checkbox" ) {
				$(element).closest('.rezgo-form-checkbox').removeClass('has-error');
			} else if ($(element).attr("type") == "radio") {
				$(element).closest('.rezgo-input-radio').addClass('has-error');
			} else {
				$(element).closest('.rezgo-form-input').removeClass('has-error');
			}
			$(element).closest('.form-group').removeClass('has-error');
		},
		focusInvalid: false,
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if ($(element).attr("name") == "name" || $(element).attr("name") == "pan" || $(element).attr("name") == "cvv" || $(element).attr("name") == "waiver") {
				error.hide();
			} else if ($(element).attr("type") == "radio") {
				error.insertAfter(element.parent().parent());
			} else if ($(element).attr("name") == "agree_terms") {
				error.insertAfter(element.parent().parent());
			} else if ($(element).attr("type") == "checkbox") {
				error.insertAfter(element.siblings('.rezgo-form-comment'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	// WAIVER
	function receiveMessage(e) {
		// Update the div element to display the message.
		if(e.data.type && e.data.type=='modal' || e.data.mode=='order_waiver') {

			var
			waiverInfo = document.getElementById('rezgo-waiver-info'),
			waiverSignature = document.getElementById('rezgo-waiver-signature'),
			waiverInput = document.getElementById('rezgo-waiver-input'),
			waiverIntro = waiverInfo.getElementsByClassName('intro')[0],
			waiverSuccess = waiverInfo.getElementsByClassName('success')[0],
			waiverError = waiverInfo.getElementsByClassName('error')[0],
			signature = waiverSignature.getElementsByClassName('signature')[0];

			signature.src = e.data.sig;

			signature.style.display = 'inline-block';
			waiverIntro.style.display = 'none';
			waiverSuccess.style.display = 'inline-block';
			waiverError.style.display = 'none';
			waiverInput.value = e.data.sig;

			$('#rezgo-waiver-wrp').hide();

			window.top.$('#rezgo-modal').modal('toggle');

		}
	}
							
	$('#rezgo-waiver-show').click(function(){
		var
		rezgoModalTitle = 'Sign Waiver',
		ids = $(this).data('ids'),
		query = '<?php echo home_url() . $site->base; ?>?rezgo=1&mode=modal&type=order&sec=1&ids=' + ids + '&title=' + rezgoModalTitle;

		window.top.$('#rezgo-modal-loader').css({'display':'block'});
		window.top.$('#rezgo-modal-iframe').attr('src', query).attr('height', '460px');
		window.top.$('#rezgo-modal-title').html(rezgoModalTitle);
		window.top.$('#rezgo-modal').modal();

	});

	window.onload = function() {
		window.addEventListener('message', receiveMessage);
	}
	
	// check if gift card has been applied
	var req = $('#gift-card-number').val()
	gcReq(req);

	$('#rezgo-book-form').validate({
		messages: {
			tour_first_name: {
				required: "Enter your first name"
			},
			tour_last_name: {
				required: "Enter your last name"
			},
			tour_address_1: {
				required: "Enter your address"
			},
			tour_city: {
				required: "Enter your city"
			},
			tour_postal_code: {
				required: "Enter postal code"
			},
			tour_phone_number: {
				required: "Enter your phone number"
			},
			tour_email_address: {
				required: "Enter a valid email address"
			},
			payment_method: {
				required: "Please select a payment method"
			},
			agree_terms: {
				required: "You must agree to the terms"
			}
		}
	});

	toggleCard = function () {

		var animateSpeed = 250;
		$('.rezgo-billing-wrp').fadeIn(animateSpeed);

		if($('input[name=payment_method]:checked').val() == 'Credit Cards') {

			<?php if ((string) $company->gateway_id == 'stripe_connect') { ?>
				// re enable stripe_error
				stripe_error = 1;
			<?php } ?>

			<?php $pmn = 0; ?>

			<?php foreach($site->getPaymentMethods() as $pay ) { ?>
				<?php if($pay['name'] == 'Credit Cards') { ?>
				<?php } else { ?>
					<?php $pmn++; ?>
					$('#payment_method_<?php echo $pmn?>_box').hide();
					$('#payment_method_<?php echo $pmn?>_field').attr('disabled', 'disabled');
				<?php } ?>
			<?php } ?>

			$('#payment_cards').fadeIn(animateSpeed);

			document.getElementById("terms_other").style.display = 'none';
			document.getElementById("terms_credit_card").style.display = '';
			<?php //if ($tg_enabled){ ?>
				// tg_enable_widget();
			<?php //} ?>

			$(this).addClass('selected');

		} else if($('input[name=payment_method]:checked').val() == 'PayPal') {
			//reset stripe_error to allow form to be submitted 
			stripe_error = 0;

			<?php $pmn = 0; ?>

			<?php foreach($site->getPaymentMethods() as $pay ) { ?>
				<?php if($pay['name'] == 'Credit Cards') { ?>

					$('#payment_cards').hide();

				<?php } else { ?>
					<?php $pmn++; ?>
					$('#payment_method_<?php echo $pmn?>_box').fadeOut(animateSpeed);
					$('#payment_method_<?php echo $pmn?>_field').attr('disabled', 'disabled');
				<?php } ?>
			<?php } ?>

			document.getElementById("terms_credit_card").style.display = 'none';
			document.getElementById("terms_other").style.display = '';
			<?php if ($tg_enabled){ ?>
				if(overall_total > 0) {
					tg_disable_widget();
				}
			<?php } ?>
			
		} else if($('input[name=payment_method]:checked').val() == 'no_payment_required') {

			//reset stripe_error to allow form to be submitted 
			stripe_error = 0;

			<?php $pmn = 0; ?>

			<?php foreach($site->getPaymentMethods() as $pay ) { ?>
				<?php if($pay['name'] == 'Credit Cards') { ?>

					$('#payment_cards').hide();

				<?php } else { ?>
					<?php $pmn++; ?>
					$('#payment_method_<?php echo $pmn?>_box').hide();
					$('#payment_method_<?php echo $pmn?>_field').attr('disabled', 'disabled');
				<?php } ?>
			<?php } ?>

			var id = $('input[name=payment_method]:checked').attr('id');
			$('#' + id + '_box').fadeIn(animateSpeed);
			$('#' + id + '_field').attr('disabled', false);
		
			document.getElementById("terms_credit_card").style.display = 'none';
			document.getElementById("terms_other").style.display = '';
			<?php if ($tg_enabled){ ?>
				if(overall_total > 0) {
					tg_disable_widget();
				}
			<?php } ?>
			$(this).addClass('selected');

		} else {
			//reset stripe_error to allow form to be submitted 
			stripe_error = 0;

			<?php $pmn = 0; ?>

			<?php foreach($site->getPaymentMethods() as $pay ) { ?>
				<?php if($pay['name'] == 'Credit Cards') { ?>

					$('#payment_cards').hide();
					// $('.rezgo-billing-wrp').hide();

				<?php } else { ?>
					<?php $pmn++; ?>
					$('#payment_method_<?php echo $pmn?>_box').hide();
					$('#payment_method_<?php echo $pmn?>_field').attr('disabled', 'disabled');
				<?php } ?>
			<?php } ?>

			var id = $('input[name=payment_method]:checked').attr('id');
			$('#' + id + '_box').fadeIn(animateSpeed);
			$('#' + id + '_field').attr('disabled', false);
		
			document.getElementById("terms_credit_card").style.display = 'none';
			document.getElementById("terms_other").style.display = '';


			$(this).addClass('selected');
		}
	}

	// these functions do a soft-commit when you click on the paypal option so they
	// can get an express payment token from the paypal API via the XML gateway
	function getPaypalToken(force) {
		// if we aren't forcing it, don't load if we already have an id
		if(!force && paypalAccount == 1) {
			// an account is set, don't re-open the box
			return false;
		}

		$('#rezgo-book-form').ajaxSubmit({
			url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
			data: { rezgoAction: 'get_paypal_token' },
			success: function(token) {
				// this section is mostly for debug handling
				if(token.indexOf('STOP::') != -1) {
					var split = token.split('<br><br>');

					if(split[1] == '0') {
						alert('The system encountered an error with PayPal. Please try again in a few minutes or select another payment method.');
						return false;
					}

					token = split[1];
				}

				dg.startFlow("https://www.paypal.com/incontext?token=" + token.trim());
			}
		});
	}

	function paypalCancel() {
		// the paypal transaction was cancelled, uncheck the radio and close the box
		dg.closeFlow();
		$('#payment_method_paypal').attr('checked', false);
	}

	function creditConfirm(token) {
		// the credit card transaction was completed, give us the token
		$('#tour_card_token').val(token);
	}

	function b64DecodeUnicode(str) {
	    // Going backwards: from bytestream, to percent-encoding, to original string.
	    return decodeURIComponent(atob(str).split('').map(function(c) {
	        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
	    }).join(''));
	}

	function noPaymentMethod(total_due){
		if(total_due <= 0) {

			$('#rezgo-gift-card-redeem').hide();
			$('.rezgo-input-radio').not('no-payment').hide();
			$('.rezgo-input-radio.no-payment').show();
			$('.rezgo-billing-wrp').show();

			hideSelectPayment();
			setTimeout(() => {
				$("input#no_payment_required").prop("checked", true);
				$('.payment_method_container').hide();
			}, 350);

		} else {
			$('#rezgo-gift-card-redeem').show();
			$('.rezgo-input-radio').not('no-payment').show();
			$('.rezgo-input-radio.no-payment').hide();
		}
	}

	function hideSelectPayment(){
		$('.select-payment').hide();
		$('#rezgo-gift-card-use-hr').css({
			'margin' : '0px 0 35px',
			'border-color' : 'transparent'
		});
	}

	function showSelectPayment(){
		$('.select-payment').show();
		$('#rezgo-gift-card-use-hr').css({
			'margin' : '50px 0px 35px',
			'border-color' : '#eee'
		});
	}

	noPaymentMethod(<?php echo $complete_booking_total?>);
	
	let payment_count = $("input[name='payment_method']").length;
	// no_payment_method is counted towards 'payment_count'
	if ( payment_count === 2 ){
		$("input[name='payment_method']").eq(0).prop("checked", true);
		hideSelectPayment();
		toggleCard();
	} 
	
});
</script>

<style>#debug_response {width:100%; height:200px;}</style>
<style>#debug_container {width:80%; margin:30px auto;} #debug_container p{margin-bottom: 15px;font-size: 1.5rem; font-weight: 200;}</style>
<style>#api_request_debug {width:100%; height:200px;}</style>
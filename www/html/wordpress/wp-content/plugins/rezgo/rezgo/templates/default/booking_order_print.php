<?php
	// grab and decode the trans_num if it was set
	$trans_num = $site->decode(sanitize_text_field($_REQUEST['trans_num']));

	// send the user home if they shouldn't be here
	if(!$trans_num) $site->sendTo($site->base."/order-not-found:empty");
	
	// start a session so we can grab the analytics code
	session_start();

	$order_bookings = $site->getBookings('t=order_code&q='.$trans_num);

	if(!$order_bookings) { $site->sendTo("/order-not-found:".$_REQUEST['trans_num']); }

	$company = $site->getCompanyDetails();

	$rzg_payment_method = 'None';
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">
		<title>Booking - <?php echo $trans_num; ?></title>

		<?php
		rezgo_plugin_scripts_and_styles();
		wp_print_scripts();
		wp_print_styles();
		?>

		<?php if ($site->exists($site->getStyles())) { ?>
			<style>
				<?php echo $site->getStyles();?>
			</style>
		<?php } ?>
	</head>
	
	<body style="background-color: #FFF;">
		<div class="container-fluid rezgo-container rezgo-print-version">

			<div class="div-order-booking">
				<h3 class="rezgo-confirm-complete print-header">Your order <?php echo $trans_num?> contains <?php echo count($order_bookings)?> booking<?php echo ((count($order_bookings) != 1) ? 's' : '')?></h3>
			</div>

			<?php $n = 1; ?>

			<?php foreach( $order_bookings as $booking ) { ?>
				<?php 
				$item = $site->getTours('t=uid&q='.$booking->item_id, 0); 
				$share_url = urlencode('https://'.$_SERVER['HTTP_HOST'].$site->base.'/details/'.$item->com.'/'.$site->seoEncode($item->item));
				?>

				<?php $site->readItem($booking); ?>

					<div class="row rezgo-confirmation row div-order-booking">
						<div class="rezgo-booking-status col-sm-12">
							<?php if($booking->status == 1 OR $booking->status == 4) { ?>
								<p class="booking-status rezgo-status-complete"><i class="far fa-calendar-check fa-lg"></i></i>&nbsp;&nbsp;Booking Complete</p>
							<?php } ?>

							<?php if($booking->status == 2) { ?>
								<p class="booking-status rezgo-status-pending"><i class="far fa-calendar-check fa-lg"></i></i>&nbsp;&nbsp;Booking Pending</p>
							<?php } ?>

							<?php if($booking->status == 3) { ?>
								<p class="booking-status rezgo-status-cancel"><i class="far fa-times fa-lg"></i>&nbsp;&nbsp;Booking Cancelled</p>
							<?php } ?>
						</div><!-- // .rezgo-booking-status -->

						<div class="clearfix"></div>

						<h3 class="order-booking-title"><?php echo $booking->tour_name?>&nbsp;(<?php echo $booking->option_name?>)</h3>

						<div class="flex-row order-booking-cols rezgo-form-group">

						<div class="col-md-5 col-sm-12 __details-col">
							<div class="flex-table">
								<div id="rezgo-receipt-transnum" class="flex-table-group">
									<div class="flex-table-header rezgo-order-transnum"><span>Booking #</span></div>
									<div class="flex-table-info"><?php echo $booking->trans_num?></div>
								</div>

								<?php if((string) $booking->date != 'open') { ?>
									<div id="rezgo-receipt-booked-for" class="flex-table-group">
										<div class="flex-table-header"><span>Date</span></div>
										<div class="flex-table-info">
											<?php echo date((string) $company->date_format, (int) $booking->date) ?>
											<?php if ($site->exists($booking->time)) { ?> at <?php echo $booking->time?><?php } ?>
										</div>
									</div>
								<?php } else { ?>
									<?php if ($site->exists($booking->time)) { ?>
										<div id="rezgo-receipt-booked-for" class="flex-table-group">
											<div class="flex-table-header"><span>Time</span></div>
											<div class="flex-table-info">
												<?php echo $booking->time?>
											</div>
										</div>
									<?php } ?>
								<?php } ?>

								<?php if(isset($booking->expiry)) { ?>
									<div id="rezgo-receipt-expires" class="flex-table-group">
										<div class="flex-table-header"><span>Expires</span></div>
										<?php if((int) $booking->expiry !== 0) { ?>
											<div class="flex-table-info"><span><?php echo date((string) $company->date_format, (int) $booking->expiry)?></span></div>
										<?php } else { ?>
											<div class="flex-table-info"><span>Never</span></div>
										<?php } ?>
									</div>
								<?php } ?>

								<?php if($site->exists($booking->trigger_code)) { ?>
									<div id="rezgo-order-promo" class="flex-table-group">
										<div class="flex-table-header"><span>Promo Code</span></div>
										<div class="flex-table-info"><?php echo $booking->trigger_code?></div>
									</div>
								<?php } ?>

								<?php if($site->exists($booking->refid)) { ?>
									<div id="rezgo-order-refid" class="flex-table-group">
										<div class="flex-table-header"><span>Referral ID</span></div>
										<div class="flex-table-info"><?php echo (string) $booking->refid?></div>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-7 col-sm-12 __table-col">
							<table class="table-responsive">
								<table class="table rezgo-billing-cart">
									<tr class="rezgo-tr-head">
										<td class="text-left rezgo-billing-type"><label>Type</label></td>
										<td class="text-left rezgo-billing-qty"><label class="hidden-xs">Qty.</label></td>
										<td class="text-left rezgo-billing-cost"><label>Cost</label></td>
										<td class="text-right rezgo-billing-total"><label>Total</label></td>
									</tr>

									<?php foreach($site->getBookingPrices() as $price) { ?>
										<tr>
											<td class="text-left"><?php echo $price->label?></td>
											<td class="text-left"><?php echo $price->number?></td>
											<td class="text-left">
											<?php if($site->exists($price->base)) { ?>
												<span class="discount"><?php echo $site->formatCurrency($price->base)?></span>
											<?php } ?>
											&nbsp;<?php echo $site->formatCurrency($price->price)?></td>
											<td class="text-right"><?php echo $site->formatCurrency($price->total)?></td>
										</tr>
									<?php } ?>

									<tr class="rezgo-tr-subtotal">
										<td colspan="3" class="text-right"><span class="push-right"><strong>Subtotal</strong></span></td>
										<td class="text-right"><?php echo $site->formatCurrency($booking->sub_total)?></td>
									</tr>

									<?php foreach($site->getBookingLineItems() as $line) { ?>
										<?php
											unset($label_add);
											if($site->exists($line->percent) || $site->exists($line->multi)) {
												$label_add = ' (';
													if($site->exists($line->percent)) $label_add .= $line->percent.'%';
													if($site->exists($line->multi)) {
														if(!$site->exists($line->percent)) $label_add .= $site->formatCurrency($line->multi);
				
														if($site->exists($line->meta)) {
															$pax_totals = array( 'adult_num' => 'price_adult', 'child_num' => 'price_child', 'senior_num' => 'price_senior', 'price4_num' => 'price4', 'price5_num' => 'price5', 'price6_num' => 'price6', 'price7_num' => 'price7', 'price8_num' => 'price8', 'price9_num' => 'price9');
															$line_pax = 0;
															foreach ($pax_totals as $p_num => $p_rate) {
																if ( (int) $booking->{$p_num} > 0 && ((float) $booking->price_range->date->{$p_rate} > (float) $line->meta)) {
																	$line_pax += (int) $booking->{$p_num};
																}
															}
															$label_add .= ' x '.$line_pax;
														} else {
															$label_add .= ' x '.$booking->pax;
														}
					
													}
												$label_add .= ')';	
											}
										?>

										<?php if( $site->exists($line->amount) ) { ?>
										<tr>
											<td colspan="3" class="text-right"><span class="push-right"><strong><?php echo $line->label?><?php echo $label_add?></strong></span></td>
											<td class="text-right"><?php echo $site->formatCurrency($line->amount)?></td>
										</tr>
										<?php } ?>
									<?php } ?>

									<?php foreach($site->getBookingFees() as $fee){ ?>
										<?php if($site->exists($fee->total_amount)){ ?>
											<tr>
												<td colspan="3" class="text-right"><span class="push-right"><strong><?php echo $fee->label?></strong></span></td>
												<td class="text-right"><?php echo $site->formatCurrency($fee->total_amount)?></td>
											</tr>
										<?php } ?>
									<?php } ?>

									<tr class="rezgo-tr-subtotal summary-total">
										<td colspan="3" class="text-right"><span class="push-right"><strong>Total</strong></span></td>
										<td class="text-right"><strong><?php echo $site->formatCurrency($booking->overall_total)?></strong></td>
									</tr>

									<?php if($site->exists($booking->deposit)) { ?>
										<tr>
											<td colspan="3" class="text-right"><span class="push-right"><strong>Deposit</strong></span></td>
											<td class="text-right"><strong><?php echo $site->formatCurrency($booking->deposit)?></strong></td>
										</tr>
									<?php } ?>

									<?php if($site->exists($booking->overall_paid)) { ?>
										<tr>
											<td colspan="3" class="text-right"><span class="push-right"><strong>Total Paid</strong></span></td>
											<td class="text-right"><strong><?php echo $site->formatCurrency($booking->overall_paid)?></strong></td>
										</tr>
										<tr>
											<td colspan="3" class="text-right"><span class="push-right"><strong>Total&nbsp;Owing</strong></span></td>
											<td class="text-right"><strong><?php echo $site->formatCurrency(((float)$booking->overall_total - (float)$booking->overall_paid))?></strong></td>
										</tr>
									<?php } ?>
								</table>
							</table>
						</div>
					</div><!-- //  tour confirm --> 

				</div>

				<div style="page-break-after:always;"></div>

				<?php 
				$cart_total += ((float)$booking->overall_total); 
				$cart_owing += ((float)$booking->overall_total - (float)$booking->overall_paid); 

				if($booking->payment_method != 'None') {
					$rzg_payment_method = $booking->payment_method;
				} 
				?>
			<?php } ?>

			<div class="row rezgo-form-group rezgo-confirmation">
				<div class="col-md-6 col-xs-12 rezgo-billing-confirmation p-helper">
					<h3 id="rezgo-receipt-head-billing-info"><span>Billing Information</span></h3>

					<div class="flex-row">
						<?php if ($site->exists($booking->first_name)){ ?>
							<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-name">
								<p class="rezgo-receipt-pax-label"><span>Name</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $booking->first_name?> <?php echo $booking->last_name?></p>
							</div>
						<?php } ?>

						<?php if ($site->exists($booking->phone_number)){ ?>
							<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-phone">
								<p class="rezgo-receipt-pax-label"><span>Phone Number</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $booking->phone_number?></p>
							</div>
						<?php } ?>

						<?php if ($site->exists($booking->address_1)){ ?>
						<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-address">
							<p class="rezgo-receipt-pax-label"><span>Address</span></p>
							<p class="rezgo-receipt-pax-info">
								<?php echo $booking->address_1?>
								<?php echo ($site->exists($booking->address_2)) ? '<br>'.$booking->address_2 : ''; ?>
								<?php echo ($site->exists($booking->city)) ? '<br>'.$booking->city : ''; ?>
								<?php echo ($site->exists($booking->stateprov)) ? $booking->stateprov : ''; ?>
								<?php echo ($site->exists($booking->postal_code)) ? '<br>'.$booking->postal_code : ''; ?>
								<?php echo $site->countryName($booking->country)?>
							</p>
						</div>
						<?php } ?>

						<?php if ($site->exists($booking->email_address)){ ?>
							<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-email">
								<p class="rezgo-receipt-pax-label"><span>Email Address</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $booking->email_address?></p>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="col-md-6 col-xs-12 rezgo-payment-confirmation p-helper">
					<h3 id="rezgo-receipt-head-payment-info"><span>Payment Information</span></h3>
					<div class="flex-row">
						<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-email">
							<p class="rezgo-receipt-pax-label"><span>Total&nbsp;Order</span></p>
							<p class="rezgo-receipt-pax-info"><?php echo $site->formatCurrency($cart_total)?></p>
						</div>

						<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-email">
							<p class="rezgo-receipt-pax-label"><span>Total&nbsp;Owing</span></p>
							<p class="rezgo-receipt-pax-info"><?php echo $site->formatCurrency($cart_owing)?></p>
						</div>
						
						<?php if($cart_total > 0) { ?>
							<div class="flex-50 billing-payment-info-box" id="rezgo-receipt-email">
								<p class="rezgo-receipt-pax-label"><span>Payment&nbsp;Method</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $rzg_payment_method?></p>
							</div>
						<?php } ?>
					</div>
				</div>
			</div><!-- //  rezgo-confirmation --> 

			<div style="page-break-after:always;"></div>

			<div class="rezgo-cancellation-policy-address div-order-booking" id="rezgo-order-company-info">
				<h3 id="rezgo-receipt-head-provided-by"><span>Service Provided by</span></h3>

				<?php $company = $site->getCompanyDetails($booking->cid); ?>
				<strong class="company-name"><?php echo $company->company_name?></strong>
				<address>
					<?php echo $company->address_1?>
					<?php echo ($site->exists($company->address_2)) ? '<br>'.$company->address_2 : ''; ?>
					<?php echo ($site->exists($company->city)) ? '<br>'.$company->city : ''; ?>
					<?php echo ($site->exists($company->state_prov)) ? $company->state_prov : ''; ?>
					<?php echo ($site->exists($company->postal_code)) ? '<br>'.$company->postal_code : ''; ?>
					<?php echo $site->countryName($company->country)?>
				</address>

				<span>
					<?php if($site->exists($company->phone)) { ?>
						<i class="fal fa-phone fa-sm"></i>&nbsp;&nbsp;
						<a href="tel:<?php echo $company->phone?>">
							<?php echo $company->phone?>
						</a> 
					<?php } ?><br>
					<?php if($site->exists($company->email)) { ?>
						<i class="fal fa-envelope fa-sm"></i>&nbsp;&nbsp;
						<a href="mailto:<?php echo $company->email?>">
							<?php echo $company->email?>
						</a> 
					<?php } ?>
				</span>
				<?php if($site->exists($company->tax_id)) { ?><br>Tax ID: <?php echo $company->tax_id?><?php } ?>
			</div>
		</div>
	</body>
</html>
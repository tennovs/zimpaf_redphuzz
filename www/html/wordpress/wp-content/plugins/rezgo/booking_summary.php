<?php
	// any new page must start with the page_header, it will include the correct files
	// so that the rezgo parser classes and functions will be available to your templates
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	$company = $site->getCompanyDetails();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">
		<title>Booking Summary for <?php echo sanitize_text_field($_REQUEST[trans_num]); ?></title>
		<?php if ($site->exists($site->getStyles())) { ?>
			<style><?php echo $site->getStyles(); ?></style>
		<?php } ?>
	</head>

	<body>
		<?php foreach ($site->getBookings(sanitize_text_field($_REQUEST['trans_num'])) as $booking) { ?>
			<?php $site->readItem($booking); ?>

			<div class="container" id="rezgo-booking-summary">	
				<h2>
					<span>Booking details for </span>
					<span><?php echo $site->getCompanyName($booking->cid); ?></span>
				</h2>

				<h3>
					<span><?php echo $booking->tour_name; ?> - <?php echo $booking->option_name; ?></span>
					<?php if ((string) $booking->date != 'open') { ?>
						<div class="rezgo-add-cal">
							<div class="rezgo-add-cal-cell">
								<a href="https://feed.rezgo.com/b/<?php echo $booking->trans_num; ?>">
									<i class="fa fa-calendar"></i>
									<span>&nbsp;Add to my calendar</span>
								</a>
							</div>
						</div>
					<?php } ?>
				</h3>

				<small class="rezgo-booked-on">
					<span>booked on </span>
					<span><?php echo date((string) $company->date_format, (int) $booking->date_purchased_local); ?></span>
					<span> / local time</span>
				</small>

				<table class="table table-bordered table-striped rezgo-billing-cart table-responsive">
					<tr>
						<td class="text-right"><label>Type</label></td>
						<td class="text-right"><label class="hidden-xs">Qty.</label></td>
						<td class="text-right"><label>Cost</label></td>
						<td class="text-right"><label>Total</label></td>
					</tr>

					<?php foreach( $site->getBookingPrices() as $price ): ?>
						<tr>
							<td class="text-right"><?php echo $price->label; ?></td>
							<td class="text-right"><?php echo $price->number; ?></td>
							<td class="text-right">
							<?php if ($site->exists($price->base)) { ?>
								<span class="discount"><?php echo $site->formatCurrency($price->base); ?></span>
							<?php } ?>
							&nbsp;<?php echo $site->formatCurrency($price->price); ?></td>
							<td class="text-right"><?php echo $site->formatCurrency($price->total); ?></td>
						</tr>
					<?php endforeach; ?>

					<tr>
						<td colspan="3" class="text-right"><strong>Subtotal</strong></td>
						<td class="text-right"><?php echo $site->formatCurrency($booking->sub_total); ?></td>
					</tr>

					<?php foreach( $site->getBookingLineItems() as $line ) { 
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

						<tr>
							<td colspan="3" class="text-right"><strong><?php echo $line->label; ?><?php echo $label_add; ?></strong></td>
							<td class="text-right"><?php echo $site->formatCurrency($line->amount); ?></td>
						</tr>

					<?php } ?>

					<?php foreach ($site->getBookingFees() as $fee): ?>
						<?php if ($site->exists($fee->total_amount)): ?>
							<tr>
								<td colspan="3" class="text-right"><strong><?php echo $fee->label; ?></strong></td>
								<td class="text-right"><?php echo $site->formatCurrency($fee->total_amount); ?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>

					<tr>
						<td colspan="3" class="text-right"><strong>Total</strong></td>
						<td class="text-right"><strong><?php echo $site->formatCurrency($booking->overall_total); ?></strong></td>
					</tr>

					<?php if ($site->exists($booking->deposit)) { ?>
						<tr>
							<td colspan="3" class="text-right"><strong>Deposit</strong></td>
							<td class="text-right"><strong><?php echo $site->formatCurrency($booking->deposit); ?></strong></td>
						</tr>
					<?php } ?>

					<?php if ($site->exists($booking->overall_paid)) { ?>
						<tr>
							<td colspan="3" class="text-right">
								<strong>Total Paid</strong>
							</td>
							<td class="text-right">
								<strong><?php echo $site->formatCurrency($booking->overall_paid); ?></strong>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="text-right">
								<strong>Total&nbsp;Owing</strong>
							</td>
							<td class="text-right">
								<strong><?php echo $site->formatCurrency(((float)$booking->overall_total - (float)$booking->overall_paid)); ?></strong>
							</td>
						</tr>
					<?php } ?>
				</table>

				<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
					<tr>
						<td class="rezgo-td-label">Trans<span class="hidden-xs">action</span>&nbsp;#:</td>
						<td class="rezgo-td-data"><?php echo $booking->trans_num; ?></td>
					</tr>

					<?php if ((string) $booking->date != 'open') { ?>
						<tr>
							<td class="rezgo-td-label">Booked<span class="hidden-xs">&nbsp;For</span>:</td>
							<td class="rezgo-td-data"><?php echo date((string) $company->date_format, (int)$booking->date); ?>
							<?php if ($booking->time != '') { ?> at <?php echo $booking->time; ?><?php } ?>
							</td>
						</tr>
					<?php } ?>

					<?php if (isset($booking->expiry)) { ?>
						<tr>
							<td class="rezgo-td-label">Expires:</td>
							<?php if ((int) $booking->expiry !== 0) { ?>
								<td class="rezgo-td-data"><?php echo date((string) $company->date_format, (int) $booking->expiry); ?></td>
							<?php } else { ?>
								<td class="rezgo-td-data">Never</td>
							<?php } ?>
						</tr>
					<?php } ?>

					<tr>
						<td class="rezgo-td-label">Payment<span class="hidden-xs">&nbsp;Status</span>:</td>
						<td class="rezgo-td-data">
							<?php if ($booking->status == 1) { ?>
								<span>RECEIVED</span>
							<?php } ?>

							<?php if ($booking->status == 2) { ?>
								<span>PENDING</span>
							<?php } ?>

							<?php if ($booking->status == 3) { ?>
								<span>CANCELLED</span>
							<?php } ?>
						</td>
					</tr>

					<?php if ($site->exists($booking->trigger_code)) { ?>
						<tr>
							<td class="rezgo-td-label">Promo<span class="hidden-xs">tional Code</span>:</td>
							<td class="rezgo-td-data"><?php echo $booking->trigger_code; ?></td>
						</tr>
					<?php } ?>

					<?php if ($site->exists($booking->refid)) { ?>
						<tr>
							<td class="rezgo-td-label">Ref<span class="hidden-xs">erral</span>&nbsp;ID:</td>
							<td class="rezgo-td-data"><?php echo $booking->refid; ?></td>
						</tr>
					<?php } ?>
				</table>

				<div class="clearfix">&nbsp;</div>

				<h3>Billing Details</h3>

				<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
					<tr>
						<td class="rezgo-td-label">Contact:</td>
						<td class="rezgo-td-data">
							<span><?php echo $booking->first_name; ?> <?php echo $booking->last_name; ?></span>
						</td>
					</tr>

					<tr>
						<td class="rezgo-td-label">Address:</td>
						<td class="rezgo-td-data">
							<span><?php echo $booking->address_1; ?></span>

							<?php if ($site->exists($booking->address_2)) { ?>
								<span>, </span>
								<span><?php echo $booking->address_2; ?></span>
							<?php } ?>

							<?php if ($site->exists($booking->city)) { ?>
								<span>, </span>
								<span><?php echo $booking->city; ?></span>
							<?php } ?>

							<?php if ($site->exists($booking->stateprov)) { ?>
								<span>, </span>
								<span><?php echo $booking->stateprov; ?></span>
							<?php } ?>

							<?php if ($site->exists($booking->postal_code)) { ?>
								<span>, </span>
								<span><?php echo $booking->postal_code; ?></span>
							<?php } ?>

							<span>, </span>

							<span><?php echo $site->countryName($booking->country); ?></span>
						</td>
					</tr>

					<tr>
						<td class="rezgo-td-label">Tel<span class="hidden-xs">ephone</span>:</td>
						<td class="rezgo-td-data"><?php echo $booking->phone_number; ?></td>
					</tr>

					<tr>
						<td class="rezgo-td-label">Email:</td>
						<td class="rezgo-td-data"><?php echo $booking->email_address; ?></td>
					</tr>
				</table>

				<div class="clearfix">&nbsp;</div>

				<?php if ($booking->overall_total > 0) { ?>
					<h3>Payment Details</h3>

					<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
						<tr>
							<td class="rezgo-td-label">Payment<span class="hidden-xs">&nbsp;Method</span>:</td>
							<td class="rezgo-td-data"><?php echo $booking->payment_method; ?></td>
						</tr>

						<?php if ($booking->payment_method == 'Credit Cards') { ?>
							<tr>
								<td class="rezgo-td-label">Card&nbsp;Number:</td>
								<td class="rezgo-td-data"><?php echo $booking->card_number; ?></td>
							</tr>
						<?php } ?>

						<?php if ($site->exists($booking->payment_method_add->label)) { ?>
							<tr>
								<td class="rezgo-td-label"><?php echo $booking->payment_method_add->label; ?>:</td>
								<td class="rezgo-td-data"><?php echo $booking->payment_method_add->value; ?></td>
							</tr>
						<?php } ?>
					</table>

					<div class="clearfix">&nbsp;</div>
				<?php } ?>

				<?php if (count($site->getBookingForms()) > 0) { ?>
					<h3>Additional Information</h3>

					<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
						<?php foreach( $site->getBookingForms() as $form ) { ?>
							<?php if(in_array($form->type, array('checkbox','checkbox_price'))) { ?>
								<?php if($site->exists($form->answer)) { $form->answer = 'yes'; } else { $form->answer = 'no'; } ?>
							<?php } ?>

							<tr>
								<td class="rezgo-td-label"><?php echo $form->question; ?>:</td>
								<td class="rezgo-td-data"><?php echo $form->answer; ?></td>
							</tr>
						<?php } ?>
					</table>

					<div class="clearfix">&nbsp;</div>
				<?php } ?>

				<?php if (count($site->getBookingPassengers()) > 0) { ?>
					<h3>Group Details</h3>

					<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
						<?php foreach( $site->getBookingPassengers() as $passenger ) { ?>
							<tr>
								<td class="rezgo-td-label"><?php echo $passenger->label; ?> <?php echo $passenger->num; ?>:</td>
								<td class="rezgo-td-data"><?php echo $passenger->first_name; ?> <?php echo $passenger->last_name; ?></td>
							</tr>

							<?php if ((string) $passenger->phone_number != '') { ?>
								<tr>
									<td class="rezgo-td-label">Phone Number:</td>
									<td class="rezgo-td-data"><?php echo $passenger->phone_number; ?></td>
								</tr>
							<?php } ?>

							<?php if ((string) $passenger->email_address != '') { ?>
								<tr>
									<td class="rezgo-td-label">Email:</td>
									<td class="rezgo-td-data"><?php echo $passenger->email_address; ?></td>
								</tr>
							<?php } ?>

							<?php foreach ($passenger->forms->form as $form) { ?>
								<?php if(in_array($form->type, array('checkbox','checkbox_price'))) { ?>
									<?php if($site->exists($form->answer)) { $form->answer = 'yes'; } else { $form->answer = 'no'; } ?>
								<?php } ?>

								<tr>
									<td class="rezgo-td-label"><?php echo $form->question; ?>:</td>
									<td class="rezgo-td-data"><?php echo $form->answer; ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</table>

					<div class="clearfix">&nbsp;</div>
				<?php } ?>
			</div>
		<?php } ?>
	</body>
</html>
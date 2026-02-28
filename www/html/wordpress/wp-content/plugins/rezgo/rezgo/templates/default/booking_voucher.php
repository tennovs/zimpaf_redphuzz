<?php $split = explode(",", sanitize_text_field($_REQUEST['trans_num'])); ?>
<?php $i = 0; ?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">

		<?php 
		rezgo_plugin_scripts_and_styles();
		wp_print_scripts();
		wp_print_styles();
		?>

		<?php if ($site->exists($site->getStyles())) { ?>
			<style><?php echo $site->getStyles();?></style>
		<?php } ?>
	</head>

	<body>
		<?php foreach ((array) $split as $v) { ?>
			<?php
			$trans_num = $site->decode($v);
			if (!$trans_num) {
				$site->sendTo("/"); 
			}
			$booking = $site->getBookings($trans_num, 0);
			$checkin = (string) $booking->checkin;
			$checkin_state = $booking->checkin_state;
			$i++;
			?>

			<?php if ($checkin): ?>
				<div id="rezgo-voucher-body">
					<div class="container-fluid">
						<?php 
						$item = $site->getTours('t=uid&q='.$booking->item_id, 0);
						$site->readItem($booking);
						$company = $site->getCompanyDetails();
						$vUrl = 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chld=M|1&chl=http://checkin.rezgo.com/<?php echo $checkin; ?>'; 
						?>

						<div class="max-800">
							<div class="row">
								<div class="col-xs-12 col-sm-4 text-center pull-right">
									<div id="rezgo-voucher-qr">
										<img src="<?php echo $vUrl; ?>" />
									</div>

									<div id="rezgo-voucher-barcode">
										<svg id='barcode_<?php echo $i; ?>'></svg>
										<script>JsBarcode("#barcode_<?php echo $i; ?>", "<?php echo $checkin; ?>");</script>
									</div>

									<br/>
								</div>

								<div class="col-xs-12 col-sm-8 pull-left">
									<h3 id="rezgo-voucher-company">Booking Voucher for <?php echo $company->company_name; ?></h3>

									<h1 id="rezgo-voucher-tour"><?php echo $booking->tour_name; ?></h1>

									<h3 id="rezgo-voucher-option">
										<span><?php echo $booking->option_name; ?></span>

										<span class="small">(SKU: <?php echo $item->uid; ?>)</span>
									</h3>

									<?php if ((string) $booking->date != 'open') { ?>
										<h4 id="rezgo-voucher-booking-date">
											<label>Booked for Date:</label>
											<span><?php echo date((string) $company->date_format, (int) $booking->date); ?></span>
										</h4>
									<?php } ?>

									<?php if ($booking->time != '') { ?>
										<h4 id="rezgo-voucher-booking-time">
											<label>Time:</label>
											<span><?php echo $booking->time; ?></span>
										</h4>
									<?php } ?>

									<h4 id="rezgo-voucher-created-date">
										<label>Issued Date:</label>
										<span><?php echo date((string) $company->date_format, (int) $booking->date_purchased_local); ?> (local time)</span>
									</h4>

									<?php if (isset($booking->expiry)) { ?>
										<h4 id="rezgo-voucher-expiry">
											<label>Expires:</label>

											<?php if ((int) $booking->expiry !== 0): ?>
												<span><?php echo date((string) $company->date_format, (int) $booking->expiry); ?></span>
											<?php else: ?>
												<span>Never</span>
											<?php endif; ?>
										</h4>
									<?php } ?>

									<h4 id="rezgo-voucher-transnum">
										<label>Booking Reference:</label>
										<span><?php echo $booking->trans_num; ?></span>
									</h4>

									<h4 id="rezgo-voucher-contact">
										<label>Booking Contact:</label>
										<span><?php echo $booking->first_name; ?> <?php echo $booking->last_name; ?></span>
									</h4>

									<h4 id="rezgo-voucher-paxcount">
										<label>Booking Pax:</label>

										<?php foreach ($site->getBookingCounts() as $count) { ?>
											<?php if ($n) { echo ', '; } else { $n = 1; } ?><?php echo $count->num; ?> x <?php echo $count->label; ?>
										<?php } ?>
									</h4>

									<?php if ($site->exists($booking->trigger_code)) { ?>
										<h4 id="rezgo-voucher-promocode">
											<label class="rezgo-promo-label">
												<span>Promotional Code:</span>
											</label>
											<span><?php echo $booking->trigger_code; ?></span>
										</h4>
									<?php } ?>

									<p id="rezgo-voucher-paxlist" class="rezgo-voucher-para">
										<?php foreach ($site->getBookingPassengers() as $passenger) { ?>
											<label><?php echo $passenger->label; ?> <?php echo $passenger->num; ?>:</label>
											<span><?php echo $passenger->first_name; ?> <?php echo $passenger->last_name; ?></span>
											<br />
										<?php } ?>
									</p>

									<div id="rezgo-voucher-pickup" class="rezgo-voucher-para">
										<p><label>Pickup/Departure:</label></p>
										<p><?php echo htmlspecialchars_decode($item->details->pick_up); ?></p>
									</div>

									<div id="rezgo-voucher-dropoff" class="rezgo-voucher-para">
										<p><label>Dropoff:</label></p>
										<p><?php echo htmlspecialchars_decode($item->details->drop_off); ?></p>
									</div>

									<div id="rezgo-voucher-cancel" class="rezgo-voucher-para">
										<p><label>Cancellation Policy:</label></p>

										<p>
											<?php if ($site->exists($booking->rezgo_gateway)): ?>
												<span>Canceling a booking with Rezgo can result in cancellation fees being applied by Rezgo, as outlined below. Additional fees may be levied by the individual supplier/operator (see your Rezgo Voucher for specific details). When canceling any booking you will be notified via email, facsimile or telephone of the total cancellation fees.</span>
												<br/><br/>
												<span>1. Event, Attraction, Theater, Show or Coupon Ticket</span>
												<br/>
												<span>These are non-refundable in all circumstances.</span>
												<br/><br/>
												<span>2. Gift Certificate</span>
												<br/>
												<span>These are non-refundable in all circumstances.</span>
												<br/><br/>
												<span>3. Tour or Package Commencing During a Special Event Period</span>
												<br/>
												<span>These are non-refundable in all circumstances. This includes, but is not limited to, Trade Fairs, Public or National Holidays, School Holidays, New Year's, Thanksgiving, Christmas, Easter, Ramadan.</span><br/>
												<br/>
												<span>4. Other Tour Products &amp; Services</span>
												<br/>
												<span>If you cancel at least 7 calendar days in advance of the scheduled departure or commencement time, there is no cancellation fee.</span>
												<br/>
												<span>If you cancel between 3 and 6 calendar days in advance of the scheduled departure or commencement time, you will be charged a 50% cancellation fee.</span>
												<br/>
												<span>If you cancel within 2 calendar days of the scheduled departure or commencement time, you will be charged a 100% cancellation fee.</span>
												<br/><br/>
											<?php else: ?>
												<?php if ($site->exists($item->details->cancellation)) { ?>
													<?php echo htmlspecialchars_decode($item->details->cancellation); ?>

													<br/>
												<?php } ?>
											<?php endif; ?>

											<span>View terms and conditions: </span>

											<strong>http://<?php echo $site->getDomain(); ?>.rezgo.com/terms</strong>

											<br/><br/>
										</p>
									</div>
								</div>

								<div class="col-xs-12 col-sm-4 text-center pull-right">
									<?php if ($site->exists($booking->rid)) { ?>
										<div id="rezgo-voucher-customer-service">
											<p>
												<label>Customer Service Contact:</label><br/>
												<?php if($site->exists($booking->rezgo_gateway)) { ?>
													<strong>Rezgo.com</strong><br/>
													<span>Attn: Partner Bookings</span><br/>
													<span>333 Brooksbank Avenue</span><br/>
													<span>Suite 718</span><br/>
													<span>North Vancouver, BC</span><br/>
													<span>Canada V7J 3V8</span><br/>
													<span>(604) 983-0083</span><br/>
													<span>bookings@rezgo.com</span>
												<?php } else { ?>
													<?php $company = $site->getCompanyDetails('p'.$booking->rid); ?>
													<strong><?php echo $company->company_name; ?></strong><br/>
													<span><?php echo $company->address_1; ?> <?php echo $company->address_2; ?></span><br/>
													<span><?php echo $company->city; ?>, </span>
													<?php if ($site->exists($company->state_prov)) { ?>
														<span><?php echo $company->state_prov; ?>, </span> 
													<?php } ?> 
													<span><?php echo $site->countryName($company->country); ?></span><br/>
													<span><?php echo $company->postal_code; ?></span><br/>
													<span><?php echo $company->phone; ?></span><br/>
													<span><?php echo $company->email; ?></span>
												<?php } ?>
											</p>
										</div>
									<?php } ?>

									<div id="rezgo-voucher-service">
										<p>
											<label>Service Provided By:</label><br/>
											<?php $company = $site->getCompanyDetails($booking->cid); ?>
											<strong><?php echo $company->company_name; ?></strong><br/>
											<span><?php echo $company->address_1; ?> <?php echo $company->address_2; ?></span><br/>
											<span><?php echo $company->city; ?>, </span>
											<?php if ($site->exists($company->state_prov)) { ?>
												<span><?php echo $company->state_prov; ?>, </span>
											<?php } ?>
											<span><?php echo $site->countryName($company->country); ?></span><br/>
											<span><?php echo $company->postal_code; ?></span><br/>
											<span><?php echo $company->phone; ?></span><br/>
											<span><?php echo $company->email; ?></span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php echo $site->getVoucherFooter(); ?>
			<?php else: ?>
				<?php if ($booking->status == 3): ?>
					<div class="col-xs-12">
						<span>Booking <?php echo $trans_num; ?> has been cancelled. This action cannot be undone. No voucher will be available for this booking.</span>
						<br/><br/>
					</div>
				<?php else: ?>
					<div class="col-xs-12">
						<span>Voucher for Booking <?php echo $trans_num; ?> is not available until the booking has been confirmed.</span>

						<br/><br/>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if (count($split) > 1) { ?>
				<div class="col-xs-12" style="border-top:1px solid #CCC; page-break-after: always;"></div>
			<?php } ?>
		<?php } ?>
	</body>
</html>
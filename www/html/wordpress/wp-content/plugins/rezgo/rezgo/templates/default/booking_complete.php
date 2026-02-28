<?php
	$trans_num = $site->decode($_REQUEST['trans_num']);

	// send the user home if they shouldn't be here
	if(!$trans_num) $site->sendTo($site->base."/booking-not-found:empty");

	// start a session so we can grab the analytics code
	// unset promo session and cookie
	$site->resetPromoCode();
	session_start();

	$company = $site->getCompanyDetails();
?>

<div class="container-fluid rezgo-container rezgo-booking-complete-container">
	<div class="jumbotron rezgo-booking">
		<?php if(!$site->getBookings('q='.$trans_num)) {
			$site->sendTo("/booking-not-found:".$_REQUEST['trans_num']); 
		} ?>

		<?php foreach ($site->getBookings('q='.$trans_num.'&a=forms') as $booking) { ?>
			<?php $item = $site->getTours('t=uid&q='.$booking->item_id, 0); ?>
			
			<?php $site->readItem($booking); ?>

			<?php 
				if (($booking->status == 1 || $booking->status == 4) && 
					(($booking->availability_type == 'date' && (int) $booking->date > strtotime('yesterday')) || 
					($booking->availability_type == 'open' && $booking->checkin_state == 0)) ) { 
					$show_voucher = true;
				} else {
					$show_voucher = false;
				}
			?>      
		
			<div class="row rezgo-confirmation-head">

				<?php if($site->exists($booking->order_code)) { ?>
					<a id="rezgo-back-to-summary" class="underline-link text-white" href="<?php echo $site->base?>/complete/<?php echo $site->encode($booking->order_code)?>"><i class="far fa-angle-left"></i>
						<span>Back to Order Summary</span>
					</a>
				<?php } ?>

				<?php if($booking->status == 1 OR $booking->status == 4) { ?>
        			<?php $status_class = 'rezgo-complete'; ?>
					<h3 class="rezgo-confirm-complete"><span>BOOKING COMPLETE</span></h3>

					<?php if ($show_voucher) { ?>
						<p class="rezgo-confirm-complete"><span>Click on the button below for your printable <?php echo ((string) $booking->ticket_type == 'ticket') ? 'ticket' : 'voucher' ?>.</span></p>
					<?php } else { ?>
						<p class="rezgo-confirm-complete"><span>Click on the button below for your printable receipt.</span></p>
					<?php } ?>

				<?php } ?>

				<?php if($booking->status == 2) { ?>
        			<?php $status_class = 'rezgo-pending'; ?>
					<h3 class="rezgo-confirm-pending"><span>BOOKING NOT YET COMPLETE</span></h3>

					<p class="rezgo-confirm-pending">
						<span>
							Your booking will be complete once payment has been processed.
						</span>
					</p>
				<?php } ?>
          
				<?php if($booking->status == 3) { ?>
        			<?php $status_class = 'rezgo-cancelled'; ?>
					<h3 class="rezgo-confirm-cancelled"><span>This booking has been CANCELLED</span></h3>
				<?php } ?>

				<div class="center-block <?php echo $status_class;?>">
					<button class="btn btn-lg rezgo-btn-print" onclick="window.open('<?php echo $site->base?>/complete/<?php echo $site->encode($trans_num)?>/print', '_blank'); return false;"><i class="far fa-print fa-lg"></i>&nbsp;&nbsp;Print Receipt</button>

				<?php if ($show_voucher) { ?>
            		<button class="btn btn-lg rezgo-btn-print-voucher" onclick="window.open('https://<?php echo $site->getDomain();?>.<?php echo $role?>rezgo.com/tickets/<?php echo $site->encode($trans_num)?>', '_blank'); return false;"><i class="far fa-ticket fa-lg"></i>&nbsp;&nbsp;Print <?php echo ((string) $booking->ticket_type == 'ticket') ? 'Tickets' : 'Voucher' ?></button>
				<?php } ?>
          
					<?php
						if($site->isVendor()) { 
							$supplier = $site->getCompanyDetails($booking->cid);
							$show_reviews = $supplier->reviews;
						} else {
							$show_reviews = $company->reviews;
						}
          			?>
				</div>
			</div>
			<?php $domain = "https://".$site->getDomain(); ?>
			<?php if( $booking->waiver == '2' ) {  ?>
				<div class="row rezgo-waiver-count div-box-shadow">
					<?php 
						$pax_signed = $pax_count = 0;
						foreach ($site->getBookingPassengers() as $passenger ) { 
							if ($passenger->signed) $pax_signed++;
							$pax_count++;
						}

						if ($pax_signed != $pax_count) { // hide if all waivers signed
							echo '<i class="far fa-exclamation-circle fa-lg"></i>&nbsp; <span><span id="pax-signed">' . $pax_signed . '</span> of ' . $pax_count . ' passengers have signed waivers.</span><br>';
							echo '<a href="'.$domain.'.rezgo.com/waiver/'.$site->waiver_encode($booking->trans_num).'" class="btn btn-lg rezgo-waiver-btn"><span>Sign waivers</span></a>';
						} else {
							echo '<i class="far fa-check-circle fa-lg"></i>&nbsp; <span><span id="pax-signed">All passengers have signed waivers.</span></span><br>';
						}
					?>
				</div>
			<?php } ?> 

			<?php if($site->exists($booking->paypal_owed)) { ?>
				<?php $company_paypal = $site->getCompanyPaypal(); ?>
				<div id="booking-complete-paypal-container" class="div-box-shadow">

					<p class="paypal-amount-owed"><?php echo $site->formatCurrency($booking->paypal_owed)?></p>

					<p class="complete-paypal-text">
						<span class="small-text">Amount Payable Now</span>
					</p>

				<?php
					// check if plugin is installed on wordpress.com
					$path = (strpos(ABSPATH, 'wordpress/core')) ? str_replace('srv/htdocs/', '', REZGO_DIR) : REZGO_DIR
				?>
				<form role="form" method="post" action="<?php echo $path?>/php_paypal/process.php">
					<input type="hidden" name="firstname" id="firstname" value="<?php echo $booking->first_name?>" />
					<input type="hidden" name="lastname" id="lastname" value="<?php echo $booking->last_name?>" />
					<input type="hidden" name="address1" id="address1" value="<?php echo $booking->address_1?>" />
					<input type="hidden" name="address2" id="address2" value="<?php echo $booking->address_2?>" />
					<input type="hidden" name="city" value="<?php echo $booking->city?>" />
					<input type="hidden" name="state" value="<?php echo $booking->stateprov?>" />
					<input type="hidden" name="country" value="<?php echo $site->countryName($booking->country)?>" />
					<input type="hidden" name="zip" value="<?php echo $booking->postal_code?>" />
					<input type="hidden" name="email" id="email" value="<?php echo $booking->email_address?>" />
					<input type="hidden" name="phone" id="phone" value="<?php echo $booking->phone_number?>" />
					<input type="hidden" name="item_name" id="item_name" value="<?php echo $booking->tour_name?> - <?php echo $booking->option_name?>" />
					<input type="hidden" name="encoded_transaction_id" id="encoded_transaction_id" value="<?php echo $_REQUEST['trans_num']?>" />
					<input type="hidden" name="item_number" id="item_number" value="<?php echo $trans_num?>" />
					<input type="hidden" name="amount" id="amount" value="<?php echo $booking->paypal_owed?>" />
					<input type="hidden" name="quantity" id="quantity" value="1" />
					<input type="hidden" name="business" value="<?php echo $company_paypal?>" />
					<input type="hidden" name="currency_code" value="<?php echo $site->getBookingCurrency()?>" />
					<input type="hidden" name="domain" value="<?php echo $site->getDomain()?>.rezgo.com" />
					<input type="hidden" name="cid" value="<?php echo REZGO_CID?>" />
					<input type="hidden" name="paypal_signature" value="" />
					<input type="hidden" name="base_url" value="rezgo.com" />
					<input type="hidden" name="cancel_return" value="https://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>" />
					<div class="paypal_button-container">
						<input type="image"	class="paypal_button" name="submit_image" src="<?php echo $site->path?>/img/logos/paypal_pay.png" />
					</div>
				</form>
			</div>
			<?php } ?>

			<div class="row rezgo-form-group rezgo-booking-complete div-box-shadow">

				<?php
					if ($item->media->image->path) { 
						$has_bg = true;
						$bg_src = $item->media->image->path;
					}
					$no_img_bg = "style='background-image: linear-gradient(170deg, #36669d 66%, #9c9c9c 108%); height:auto;'";
				?>

				<div class="rezgo-feature-bg <?php echo ($has_bg) ? 'bg-gradient' : ''; ?>" <?php if ($has_bg) { echo "style='background-image: url(".$bg_src."); background-position: center; background-size: cover;'"; } else { echo $no_img_bg; } ?>>
					<div class="rezgo-feature-bg-text <?php echo (!$has_bg) ? 'no-bg' : ''; ?>">
						<h3 class="rezgo-book-name">
							<span><?php echo $booking->tour_name?> - <?php echo $booking->option_name?></span>
						</h3>
						<small class="rezgo-booked-on">booked on <?php echo date((string) $company->date_format, (int) $booking->date_purchased_local)?> / local time</small>

						<?php if((string) $booking->date != 'open') { ?>
							<div class="rezgo-add-cal">
								<div class="rezgo-add-cal-cell">
									<i class="fal fa-calendar-plus"></i>
									<a href="https://feed.rezgo.com/b/<?php echo $site->waiver_encode($booking->trans_num, 'rz|summaryval')?>">Add to calendar</a>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				
				<table class="table rezgo-billing-cart">
					<tr class="rezgo-tr-head">
						<td class="text-left rezgo-billing-type"><label>Type</label></td>
						<td class="text-left rezgo-billing-qty"><label class="hidden-xs">Qty.</label></td>
						<td class="text-left rezgo-billing-cost"><label>Cost</label></td>
						<td class="text-right rezgo-billing-total"><label>Total</label></td>
					</tr>

					<?php foreach ($site->getBookingPrices() as $price ) { ?>
						<tr>
							<td class="text-left">
								<?php echo $price->label?></td>
							<td class="text-left">
								<?php echo $price->number?></td>
							<td class="text-left">
								<?php if($site->exists($price->base)) { ?>
								<span class="discount"><?php echo $site->formatCurrency($price->base)?></span>
								<?php } ?>
								&nbsp;<?php echo $site->formatCurrency($price->price)?></td>
							<td class="text-right">
								<?php echo $site->formatCurrency($price->total)?></td>
						</tr>
					<?php } // end foreach ($site->getBookingPrices() as $price ) ?>

					<tr class="rezgo-tr-subtotal">
						<td colspan="3" class="text-right"><span class="push-right"><strong>Subtotal</strong></span></td>
						<td class="text-right">
							<?php echo $site->formatCurrency($booking->sub_total)?></td>
					</tr>

					<?php foreach ($site->getBookingLineItems() as $line ) { ?>
						<?php unset($label_add); ?>

						<?php if($site->exists($line->percent) || $site->exists($line->multi)) {
								$label_add = ' (';

								if($site->exists($line->percent)) $label_add .= $line->percent.'%';

								if($site->exists($line->multi)) {
									if(!$site->exists($line->percent)) $label_add .= $site->formatCurrency($line->multi);

									if($site->exists($line->meta)) {
									
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
						} ?>

						<?php if( $site->exists($line->amount) ) { ?>
							<tr>
								<td colspan="3" class="text-right"><span class="push-right"><strong><?php echo $line->label?><?php echo $label_add?></strong></span></td>
								<td class="text-right"><?php echo $site->formatCurrency($line->amount)?></td>
							</tr>
						<?php } ?>
					<?php } ?>

					<?php foreach ($site->getBookingFees() as $fee ) { ?>
						<?php if( $site->exists($fee->total_amount) ) { ?>
							<tr>
								<td colspan="3" class="text-right"><span class="push-right"><strong><?php echo $fee->label?></strong></span></td>
								<td class="text-right"><?php echo $site->formatCurrency($fee->total_amount)?></td>
							</tr>
						<?php } ?>
					<?php } ?>

					<tr class="rezgo-tr-subtotal summary-total">
						<td colspan="3" class="text-right"><span class="push-right"><strong>Total</strong></span></td>
						<td class="text-right">
							<strong><?php echo $site->formatCurrency($booking->overall_total)?></strong>
						</td>
					</tr>

					<?php if($site->exists($booking->deposit)) { ?>
						<tr class="rezgo-tr-deposit">
							<td colspan="3" class="text-right"><span class="push-right"><strong>Deposit</strong></span></td>
							<td class="text-right">
								<strong><?php echo $site->formatCurrency($booking->deposit)?></strong>
							</td>
						</tr>
					<?php } ?>

					<?php if($site->exists($booking->overall_paid)) { ?>
						<tr>
							<td colspan="3" class="text-right"><span class="push-right"><strong>Total Paid</strong></span></td>
							<td class="text-right">
								<strong><?php echo $site->formatCurrency($booking->overall_paid)?></strong>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="text-right"><span class="push-right"><strong>Total&nbsp;Owing</strong></span></td>
							<td class="text-right">
								<strong><?php echo $site->formatCurrency(((float)$booking->overall_total - (float)$booking->overall_paid))?></strong>
							</td>
						</tr>
					<?php } ?>
				</table>
				
				<div class="booking-receipt-details-container">
					<hr><br>

					<div class="flex-table">
						<div id="rezgo-receipt-transnum" class="flex-table-group flex-50">
							<div class="flex-table-header rezgo-order-transnum"><span>Booking #</span></div>
							<div class="flex-table-info"><?php echo $booking->trans_num?></div>
						</div>

						<?php if((string) $booking->date != 'open') { ?>
							<div id="rezgo-receipt-booked-for" class="flex-table-group flex-50">
								<div class="flex-table-header"><span>Date</span></div>
								<div class="flex-table-info">
									<span>
										<?php echo date((string) $company->date_format, (int) $booking->date) ?>
										<?php if ($site->exists($booking->time)) { ?> at <?php echo $booking->time?><?php } ?>
									</span>
								</div>
							</div>
						<?php } else { ?>
							<?php if ($site->exists($booking->time)) { ?>
								<div id="rezgo-receipt-booked-for" class="flex-table-group flex-50">
									<div class="flex-table-header"><span>Time</span></div>
									<div class="flex-table-info"><span><?php echo $booking->time?></span></div>
								</div>
							<?php } ?>
						<?php } ?>

						<?php if(isset($booking->expiry)) { ?>
							<div id="rezgo-receipt-expires" class="flex-table-group flex-50">
								<div class="flex-table-header"><span>Expires</span></div>
								<?php if((int) $booking->expiry !== 0) { ?>
									<div class="flex-table-info"><span><?php echo date((string) $company->date_format, (int) $booking->expiry)?></span></div>
								<?php } else { ?>
									<div class="flex-table-info"><span>Never</span></div>
								<?php } ?>
							</div>
						<?php } ?>

						<?php if ((string) $item->duration != '') { ?>
							<div id="rezgo-receipt-duration" class="flex-table-group flex-50">
								<div class="flex-table-header"><span>Duration</span></div>
								<div class="flex-table-info"><span><?php echo $item->duration?></div>
							</div>
						<?php } ?>

						<?php if($item->location_name != '') {
							$location = $item->location_name.', '.$item->location_address;
						} else {
							unset($loc);
							if($site->exists($item->city)) $loc[] = $item->city;
							if($site->exists($item->state)) $loc[] = $item->state;
							if($site->exists($item->country)) $loc[] = $site->countryName($item->country);
							if($loc) $location = implode(', ', $loc);	
						}
						if (isset($location) && $location != '') { ?>
							<div id="rezgo-receipt-location" class="flex-table-group flex-50">
								<div class="flex-table-header"><span>Location</span></div>
								<div class="flex-table-info"><span><?php echo $location?></span></div>
							</div>
						<?php } ?>

						<?php if ((string) $item->checkin_time != '') { ?>
							<div id="rezgo-receipt-checkin-time" class="flex-table-group flex-50">
								<div class="flex-table-header"><span>Check-In Time</span></div>
								<div class="flex-table-info"><span><?php echo $item->checkin_time?></span></div>
							</div>
						<?php } ?>
						
						<?php if ((string) $item->details->pick_up != '') { ?>
							<hr class="details-separator">
							<div id="rezgo-receipt-pickup" class="flex-table-group">
								<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Pickup/Departure Information</span></div>
								<div class="flex-table-info indent"><span><?php echo $item->details->pick_up?></span></div>
							</div>
						<?php } ?>

						<?php if ((string) $item->details->drop_off != '') { ?>
							<hr class="details-separator">
							<div id="rezgo-receipt-dropoff" class="flex-table-group">
								<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Dropoff/Return Information</span></div>
								<div class="flex-table-info indent"><span><?php echo $item->details->drop_off?></span></div>
							</div>
						<?php } ?>
						
						<?php if ((string) $item->details->checkin != '') { ?>
							<hr class="details-separator">
							<div id="rezgo-receipt-checkin-instructions" class="flex-table-group">
								<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Check-In Instructions</span></div>
								<div class="flex-table-info indent"><span><?php echo $item->details->checkin?></span></div>
							</div>
						<?php } ?>

						<?php if ((string) $item->details->bring != '') { ?>
							<hr class="details-separator">
							<div id="rezgo-receipt-thingstobring" class="flex-table-group">
								<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Things to bring</span></div>
								<div class="flex-table-info indent"><span><?php echo $item->details->bring?></span></div>
							</div>
						<?php } ?>

						<?php if ((string) $item->details->itinerary != '') { ?>
							<hr class="details-separator">
							<div id="rezgo-receipt-itinerary" class="flex-table-group rezgo-receipt-itinerary">
								<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Itinerary</span></div>
								<div class="flex-table-info indent"><span><?php echo $item->details->itinerary?></span></div>
							</div>
						<?php } ?>

					</div> <!-- flex-table -->

					<?php if ((string) $booking->pickup->name != '') { ?>
            			<?php $pickup_detail = $site->getPickupItem((string) $booking->item_id, (int) $booking->pickup->id); ?>
						<div id="rezgo-receipt-pickup" class="flex-table-group">
							<div class="flex-table-header"><i class="far fa-info-circle"></i><span>Pick Up Information</span></div>
							<div class="flex-table-info indent"><span>
								<?php echo $booking->pickup->name?> at <?php echo $booking->pickup->time?><br>


								<div class="row" style="margin: auto 0;">
									<?php if($site->exists($pickup_detail->lat) && $site->exists($pickup_detail->location_address)) {  ?>
										<a href="https://www.google.com/maps/place/<?php echo urlencode($pickup_detail->lat.','.$pickup_detail->lon)?>" target="_blank"><i class="fa fa-map-marker"></i> <?php echo $pickup_detail->location_address;?></a><br>
									<?php } ?>
									
									<?php
										if($site->exists($pickup_detail->lat) && GOOGLE_API_KEY) { 
										
											if(!$site->exists($pickup_detail->zoom)) { $map_zoom = 8; } else { $map_zoom = $pickup_detail->zoom; }

											if($pickup_detail->map_type != '') { 
												$embed_type = strtolower($pickup_detail->map_type); 
												if ( $embed_type == 'hybrid' ) { $embed_type = 'satellite'; }
											} else { 
												$embed_type = 'roadmap'; 
											} 
										
											echo '
												<div class="col-sm-12 rezgo-pickup-receipt-data">
												<div class="rezgo-pickup-map" id="rezgo-pickup-map">
													<iframe width="100%" height="372" frameborder="0" style="border:0;margin-bottom:0;margin-top:-130px;" src="https://www.google.com/maps/embed/v1/place?key='.GOOGLE_API_KEY.'&maptype='.$embed_type.'&q='.$pickup_detail->lat.','.$pickup_detail->lon.'&center='.$pickup_detail->lat.','.$pickup_detail->lon.'&zoom='.$map_zoom.'"></iframe>
												</div>
												</div>
											';
												
										}

										if($pickup_detail->media) { 

											echo '
												<div class="col-xs-12 rezgo-pickup-receipt-data">
												<img src="'.$pickup_detail->media->image[0]->path.'" alt="'.$pickup_detail->media->image[0]->caption.'" style="max-width:100%;"> 
												<div class="rezgo-pickup-caption">'.$pickup_detail->media->image[0]->caption.'</div>
												</div>
											';				
										
										}

										echo '
											<div class="col-xs-12 rezgo-pickup-receipt-data">
										';				
							
										if( ($site->exists($pickup_detail->pick_up)) ) {
											echo '<label>Pick Up</label> '.$pickup_detail->pick_up.'';
										}

										if( ($site->exists($pickup_detail->drop_off)) ) {
											echo '<label>Drop Off</label> '.$pickup_detail->drop_off.'';
										}	

										echo '
											</div>
										';	
									?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<!-- // tour confirmation--> 

			<?php if($item->lat != '' && $item->lon != '' && GOOGLE_API_KEY) { ?>
        
				<?php 
					
					if (!$site->exists($item->zoom)) { 
						$map_zoom = 6; 
					} else { 
						$map_zoom = $item->zoom; 
					}
					
					if ($item->map_type == 'ROADMAP') {
						$embed_type = 'roadmap';
					} else {
						$embed_type = 'satellite';
					} 
					
				?>

				<div class="row div-box-shadow" id="rezgo-receipt-map-container">
					<div class="col-xs-12 p-helper">
						<h3 id="rezgo-receipt-head-map"><span>Map</span></h3>

					<div class="rezgo-map" id="rezgo-receipt-map">
              			<iframe width="100%" height="500" frameborder="0" style="border:0;margin-bottom:0;margin-top:-130px;" src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY?>&maptype=<?php echo $embed_type?>&q=<?php echo $item->lat?>,<?php echo $item->lon?>&center=<?php echo $item->lat?>,<?php echo $item->lon?>&zoom=<?php echo $map_zoom?>"></iframe>
            		</div>

						<div class="rezgo-map-location rezgo-map-shadow">
							<?php if($item->location_name != '') { ?>
								<div class="rezgo-map-icon pull-left"><i class="fa fa-comment"></i></div> <?php echo $item->location_name?>
								<div class="rezgo-map-hr"></div>
							<?php } ?>

							<?php if($item->location_address != '') { ?>
								<div class="rezgo-map-icon pull-left"><i class="fa fa-location-arrow"></i></div> <?php echo $item->location_address?>
								<div class="rezgo-map-hr"></div>
							<?php } else { ?>
								<div class="rezgo-map-icon pull-left"><i class="fa fa-location-arrow"></i></div> <?php echo $item->city.' '.$item->state.' '.$site->countryName($item->country)?>
								<div class="rezgo-map-hr"></div>
							<?php } ?>

							<div class="rezgo-map-icon pull-left"><i class="fa fa-globe"></i></div> <?php echo round((float) $item->lat, 3)?>, <?php echo round((float) $item->lon, 3)?>
						</div>
					</div>
				</div>
				<!-- end receipt map -->
			<?php } ?>

			<div class="row rezgo-form-group rezgo-confirmation-additional-info div-box-shadow">
				<div class="p-helper">
					<h3 id="rezgo-receipt-head-billing-info"><span>Billing Information</span></h3>

					<div id="billing-info-row" class="flex-row">

						<?php if ($site->exists($booking->first_name)){ ?>
						<div class="flex-33" id="rezgo-receipt-name">
							<p class="rezgo-receipt-pax-label"><span>Name</span></p>
							<p class="rezgo-receipt-pax-info"><?php echo $booking->first_name?> <?php echo $booking->last_name?></p>
						</div>
						<?php } ?>
						
						<?php if ($site->exists($booking->phone_number)){ ?>
						<div class="flex-33" id="rezgo-receipt-phone">
							<p class="rezgo-receipt-pax-label"><span>Phone Number</span></p>
							<p class="rezgo-receipt-pax-info"><?php echo $booking->phone_number?></p>
						</div>
						<?php } ?>

						<?php if ($site->exists($booking->email_address)){ ?>
						<div class="flex-33" id="rezgo-receipt-email">
							<p class="rezgo-receipt-pax-label"><span>Email Address</span></p>
							<p class="rezgo-receipt-pax-info"><?php echo $booking->email_address?></p>
						</div>
						<?php } ?>
						
						<?php if ($site->exists($booking->address_1)){ ?>
						<div class="flex-33" id="rezgo-receipt-address">
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

						<?php if($booking->overall_total > 0) { ?>
							<?php if ($site->exists($booking->payment_method)){ ?>
								<div class="flex-33" id="rezgo-receipt-payment-method">
									<p class="rezgo-receipt-pax-label"><span>Payment Method</span></p>
									<p class="rezgo-receipt-pax-info"><?php echo $booking->payment_method?></p>
								</div>
							<?php } ?>

							<?php if($booking->payment_method == 'Credit Cards') { ?>
								<div class="flex-33" id="rezgo-receipt-cardnum">
									<p class="rezgo-receipt-pax-label"><span>Card Number</span></p>
									<p class="rezgo-receipt-pax-info">****<?php echo $booking->card_number?></p>
								</div>
							<?php } ?>

							<?php if($site->exists($booking->payment_method_add->label)) { ?>
								<div class="flex-33">
									<p class="rezgo-receipt-pax-label"><?php echo $booking->payment_method_add->label?></p>
									<p class="rezgo-receipt-pax-info"><?php echo $booking->payment_method_add->value?></p>
								</div>
							<?php } ?>
						<?php } ?>

						<div class="flex-33" id="rezgo-receipt-payment-status">
							<p class="rezgo-receipt-pax-label"><span>Payment Status</span></p>
							<p class="rezgo-receipt-pax-info">
								<?php echo (($booking->status == 1) ? 'CONFIRMED' : '')?>
								<?php echo (($booking->status == 2) ? 'PENDING' : '')?>
								<?php echo (($booking->status == 3) ? 'CANCELLED' : '')?>
							</p>
						</div>

						<?php if($site->exists($booking->trigger_code)) { ?>
							<div class="flex-33" id="rezgo-receipt-trigger">
								<p class="rezgo-receipt-pax-label"><span>Promotional Code</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $booking->trigger_code?></p>
							</div>
						<?php } ?>
	
						<?php if($site->exists($booking->refid)) { ?>
							<div class="flex-33" id="rezgo-receipt-refid">
								<p class="rezgo-receipt-pax-label"><span>Referral ID</span></p>
								<p class="rezgo-receipt-pax-info"><?php echo $booking->refid?></p>
							</div>
						<?php } ?>

					</div>

					<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list hidden">
						<tr id="rezgo-receipt-name">
							<td class="rezgo-td-label">Name</td>
							<td class="rezgo-td-data"><?php echo $booking->first_name?> <?php echo $booking->last_name?></td>
						</tr>

						<tr id="rezgo-receipt-address">
							<td class="rezgo-td-label">Address</td>
							<td class="rezgo-td-data">
								<?php echo $booking->address_1?><?php if($site->exists($booking->address_2)) { ?>, <?php echo $booking->address_2?><?php } ?><?php if($site->exists($booking->city)) { ?>, <?php echo $booking->city?><?php } ?><?php if($site->exists($booking->stateprov)) { ?>, <?php echo $booking->stateprov?><?php } ?><?php if($site->exists($booking->postal_code)) { ?>, <?php echo $booking->postal_code?><?php } ?>, <?php echo $site->countryName($booking->country)?>
							</td>
						</tr>

						<tr id="rezgo-receipt-phone">
							<td class="rezgo-td-label">Phone Number</td>
							<td class="rezgo-td-data"><?php echo $booking->phone_number?></td>
						</tr>

						<tr id="rezgo-receipt-email">
							<td class="rezgo-td-label">Email Address</td>
							<td class="rezgo-td-data"><?php echo $booking->email_address?></td>
						</tr>

						<?php if($booking->overall_total > 0) { ?>
							<tr id="rezgo-receipt-payment-method">
								<td class="rezgo-td-label">Payment Method</td>
								<td class="rezgo-td-data"><?php echo $booking->payment_method?></td>
							</tr>
							<?php if($booking->payment_method == 'Credit Cards') { ?>
							<tr id="rezgo-receipt-cardnum">
								<td class="rezgo-td-label">Card Number</td>
								<td class="rezgo-td-data"><?php echo $booking->card_number?></td>
							</tr>
							<?php } ?>
							<?php if($site->exists($booking->payment_method_add->label)) { ?>
							<tr>
								<td class="rezgo-td-label"><?php echo $booking->payment_method_add->label?></td>
								<td class="rezgo-td-data"><?php echo $booking->payment_method_add->value?></td>
							</tr>
							<?php } ?>
						<?php } ?>

						<tr id="rezgo-receipt-payment-status">
							<td class="rezgo-td-label">Payment Status</td>
							<td class="rezgo-td-data">
								<?php echo (($booking->status == 1) ? 'CONFIRMED' : '')?>
								<?php echo (($booking->status == 2) ? 'PENDING' : '')?>
								<?php echo (($booking->status == 3) ? 'CANCELLED' : '')?>
							</td>
						</tr>

						<?php if($site->exists($booking->trigger_code)) { ?>
							<tr id="rezgo-receipt-trigger">
								<td class="rezgo-td-label"><span>Promotional Code</span></td>
								<td class="rezgo-td-data"><?php echo $booking->trigger_code?></td>
							</tr>
						<?php } ?>
	
						<?php if($site->exists($booking->refid)) { ?>
							<tr id="rezgo-receipt-refid">
								<td class="rezgo-td-label">Referral ID</td>
								<td class="rezgo-td-data"><?php echo $booking->refid?></td>
							</tr>
						<?php } ?>
					</table>

					<?php if(count($site->getBookingForms()) > 0 OR count($site->getBookingPassengers()) > 0) { ?>
						<hr>
						<!-- primary forms -->
						<div class="booking-receipt-forms-container">
						
							<h3 id="rezgo-receipt-head-customer-info"><span>Customer Information</span></h3>

							<div class="p-forms-table">
								<?php foreach ($site->getBookingForms() as $form ) { ?>

									<?php if(in_array($form->type, array('checkbox','checkbox_price'))) { ?>
										<?php if($site->exists($form->answer)) { $form->answer = 'yes'; } else { $form->answer = 'no'; } ?>
									<?php } ?>

										<div id="" class="rezgo-receipt-primary-forms">
											<div class="question-flex-container">
												<i class="far fa-question-circle"></i> <p class="form-question"><?php echo $form->question?></p>
											</div>
											<hr class="form-separator">
											<p class="form-answer"><?php echo ( $site->exists($form->answer) ) ? $form->answer : 'N/A'; ?></p>
										</div>

									<?php if ($form->options_instructions) { ?>
									
										<?php
											$options = explode(',', (string) $form->options);
											$options_instructions = explode(',', (string) $form->options_instructions);
											$option_extras = array_combine($options, $options_instructions);
										?>

										<?php if ( in_array($form->type, array('select','multiselect')) ) { ?>
										
											<?php if ( $form->type == 'multiselect' ) { ?>
											
												<?php
													$multi_answers = explode(',', (string) $form->answer);
													$multi_answer_list = '';
													foreach ($multi_answers as $answer) {
														$answer_key = html_entity_decode(trim($answer), ENT_QUOTES);
														if ( array_key_exists( $answer_key, $option_extras ) ) {
															$multi_answer_list .= '<p class="form-answer rezgo-form-extras">'.$option_extras[$answer_key].'</p>';
														}
													}
												?>
												
												<?php if ( $multi_answer_list != '' ) { ?>
													<div id="" class="rezgo-receipt-primary-forms">
														<p class="form-question"></p>
														<div class="multiselect-answer-group"><?php echo $multi_answer_list?></div>
													</div>
												<?php } ?>
											
											<?php } else { ?>
												
												<?php $answer_key = html_entity_decode((string) $form->answer, ENT_QUOTES); ?>
												<?php if ( array_key_exists( $answer_key, $option_extras ) ) { ?>
													<div id="" class="rezgo-receipt-primary-forms">
														<p class="form-question"></p>
														<div class="multiselect-answer-group"><p class="form-answer rezgo-form-extras"><?php echo $option_extras[$answer_key]?></p></div>
													</div>
												<?php } ?>
											
											<?php } ?>
										
										<?php } ?>
									<?php } //if ($form->options_instructions) ?>
								<?php } ?>
							</div> <!-- p-forms-table -->
						</div> <!-- booking-receipt-forms-container -->

						<!-- guest forms -->
						<div class="booking-receipt-forms-container <?php echo $item->group;?>_pax_info">

							<div class="flex-table">
								<?php foreach ($site->getBookingPassengers() as $passenger) { ?>

									<div class="flex-table-group bordered flex-50 rezgo-receipt-pax">
										<div class="flex-table-header"><span><i class="far fa-user"></i> <?php echo $passenger->label?>&nbsp;(<?php echo $passenger->num?>)</span></div>

										<?php unset($pax_name_display, $pax_phone_display, $pax_email_display); ?>

										<?php // check if forms and guest information is present
											if ( (int)$passenger->total_forms == 0 && 
												!$site->exists($passenger->first_name) && 
												!$site->exists($passenger->last_name) && 
												!$site->exists($passenger->phone_number) && 
												!$site->exists($passenger->email_address) ) { ?>

											<p id="no-guest-info" class="rezgo-receipt-pax-info"><span>No Guest Information entered</span></p>

										<?php } else { // if forms and guest information is present ?>

										<?php if((string) $passenger->first_name == '' && (string) $passenger->last_name == '') $pax_name_display = 'style="display:none" '; ?>
										<?php if((string) $passenger->phone_number == '') $pax_phone_display = 'style="display:none" '; ?>
										<?php if((string) $passenger->email_address == '') $pax_email_display = 'style="display:none" '; ?>

											<div class="flex-row">
												<div class="flex-50 billing-payment-info-box">
													<p <?php echo $pax_name_display?> class="rezgo-receipt-pax-label" id="rezgo-label-name-<?php echo $passenger->id?>">Name</p>
													<p <?php echo $pax_name_display?> class="rezgo-receipt-pax-info" id="rezgo-pax-name-<?php echo $passenger->id?>"><?php echo $passenger->first_name?> <?php echo $passenger->last_name?></p>
												</div>
												<div class="flex-50 billing-payment-info-box">
													<p <?php echo $pax_phone_display?> class="rezgo-receipt-pax-label" id="rezgo-label-phone-<?php echo $passenger->id?>">Phone Number</p>
													<p <?php echo $pax_phone_display?> class="rezgo-receipt-pax-info" id="rezgo-pax-phone-<?php echo $passenger->id?>"><?php echo $passenger->phone_number?></p>
												</div>
											</div>

											<p <?php echo $pax_email_display?> class="rezgo-receipt-pax-label" id="rezgo-label-email-<?php echo $passenger->id?>">Email</p>
											<p <?php echo $pax_email_display?> class="rezgo-receipt-pax-info" id="rezgo-pax-email-<?php echo $passenger->id?>"><?php echo $passenger->email_address?></p>

											<?php foreach ($passenger->forms->form as $form ) { ?>              
								
												<?php if (in_array($form->type, array('checkbox','checkbox_price'))) { ?>
													<?php if($site->exists($form->answer)) { $form->answer = 'yes'; } else { $form->answer = 'no'; } ?>
												<?php } ?>

												<div id="" class="rezgo-receipt-guest-forms">
													<div class="question-flex-container">
														<i class="far fa-question-circle"></i> <span class="form-question"><?php echo $form->question?></span>
													</div>
													<hr class="form-separator">
													<span class="form-answer"><?php echo ( $site->exists($form->answer) ) ? $form->answer : 'N/A'; ?></span>
												</div>
											
												<?php if ($form->options_instructions) { ?>
											
													<?php
													$pax_options = explode(',', (string) $form->options);
													$pax_options_instructions = explode(',', (string) $form->options_instructions);
													$pax_option_extras = array_combine($pax_options, $pax_options_instructions);
													?>  
													
													<?php if ( in_array($form->type, array('select','multiselect')) ) { ?>
													
														<?php if ( $form->type == 'multiselect' ) { ?>
															
															<?php
																$pax_multi_answers = explode(',', (string) $form->answer);
																$pax_multi_answer_list = '';
																foreach ($pax_multi_answers as $pax_answer) {
																	$answer_key = html_entity_decode(trim($answer), ENT_QUOTES);
																	if ( array_key_exists( $answer_key, $pax_option_extras ) ) {
																		$pax_multi_answer_list .= '<p class="form-answer rezgo-form-extras">'.$pax_option_extras[$answer_key].'</p>';
																	}
																}
															?>

															<?php if ( $pax_multi_answer_list != '' ) { ?>
																<div id="" class="rezgo-receipt-guest-forms">
																	<p class="form-question"></p>
																	<div class="multiselect-answer-group"><?php echo $pax_multi_answer_list?></div>
																</div>
															<?php } ?>
														
														<?php } else { ?>

															<?php $answer_key = html_entity_decode((string) $form->answer, ENT_QUOTES); ?>
															<?php if ( array_key_exists( $answer_key, $pax_option_extras ) ) { ?>
																<div id="" class="rezgo-receipt-guest-forms">
																	<p class="form-question"></p>
																	<div class="multiselect-answer-group"><p class="form-answer rezgo-form-extras"><?php echo $pax_option_extras[$answer_key]?></p></div>
																</div>
															<?php } ?>

														<?php } // if ($form->type) ?>
													
													<?php } // if (in_array($form->type)) ?>
												
												<?php } // if ($form->options_instructions) ?>
											
											<?php } // foreach ($passenger->forms) ?>

										<?php } // if forms and guest information is present ?>

										<div class="flex-table-info">
											<?php if( $booking->waiver == '2' ) { ?>
												<button class="btn rezgo-btn-default btn-sm rezgo-waiver-sign" type="button" data-paxid="<?php echo $passenger->id?>" id="rezgo-sign-<?php echo $passenger->id?>" <?php echo (($passenger->signed) ? ' style="display:none;"' : '')?>  onclick="window.top.location.href='<?php echo $domain.'.rezgo.com/waiver/'.$site->waiver_encode($booking->trans_num.'-'.$passenger->id)?>'">
												<span><span id="rezgo-sign-txt-<?php echo $passenger->id?>">sign waiver</span></span>
												</button>
												
												<span id="rezgo-signed-<?php echo $passenger->id?>" <?php echo (($passenger->signed) ? '' : 'style="display:none;"')?>>
												<div class="flex-row rezgo-signed-row">
													<span class="btn btn-sm rezgo-signed"> <i class="far fa-check-circle"></i>&nbsp; waiver signed</span>
													<!-- <span class="rezgo-signed-check"><i class="fa fa-check" aria-hidden="true"></i></span> -->
												</div>
												<input type="hidden" id="rezgo-sign-count-<?php echo $passenger->id?>" class="rezgo-sign-count" value="<?php echo (($passenger->signed) ? '1' : '0')?>" />
												</span>
											<?php } ?>  
										</div>

									</div>

								<?php } // foreach ($site->getBookingPassengers() as $passenger) ?>

							</div> <!-- flex-table -->

						</div> <!-- forms-container -->
							
						<?php foreach ($site->getBookingPassengers() as $passenger ) { ?>
						
							<div class="col-sm-6 col-xs-12 hidden">
							
							<table border="0" cellspacing="0" cellpadding="2" class="rezgo-table-list">
							
								<tr class="rezgo-receipt-pax">
									<td class="rezgo-td-label"><?php echo $passenger->label?>&nbsp;<?php echo $passenger->num?></td>
									<td class="rezgo-td-data">
									<?php if( $booking->waiver == '2' ) { ?>
										<button class="btn rezgo-btn-default btn-sm rezgo-waiver-sign" type="button" data-paxid="<?php echo $passenger->id?>" id="rezgo-sign-<?php echo $passenger->id?>" <?php echo (($passenger->signed) ? ' style="display:none;"' : '')?> onclick="<?php echo LOCATION_WINDOW?>.location.href='<?php echo $site->base.'/waiver/'.$site->waiver_encode($booking->trans_num.'-'.$passenger->id)?>'">
										<span><i class="fa fa-pencil-square-o"></i>&nbsp;<span id="rezgo-sign-txt-<?php echo $passenger->id?>">sign waiver</span></span>
										</button>
										
										<span id="rezgo-signed-<?php echo $passenger->id?>" <?php echo (($passenger->signed) ? '' : 'style="display:none;"')?>>
										<span class="btn btn-sm rezgo-signed">signed</span>
										<span class="rezgo-signed-check"><i class="fa fa-check" aria-hidden="true"></i></span>
										<input type="hidden" id="rezgo-sign-count-<?php echo $passenger->id?>" class="rezgo-sign-count" value="<?php echo (($passenger->signed) ? '1' : '0')?>" />
										</span>
										
									<?php } ?>          
									&nbsp;
									</td>
								</tr>
								
								<?php unset($pax_name_display, $pax_phone_display, $pax_email_display); ?>
					
									<?php if((string) $passenger->first_name == '' && (string) $passenger->last_name == '') $pax_name_display = 'style="display:none" '; ?>
									<tr class="rezgo-receipt-name">
										<td <?php echo $pax_name_display?>class="rezgo-td-label" id="rezgo-label-name-<?php echo $passenger->id?>">Name</td>
										<td <?php echo $pax_name_display?>class="rezgo-td-data" id="rezgo-pax-name-<?php echo $passenger->id?>"><?php echo $passenger->first_name?> <?php echo $passenger->last_name?></td>
										</tr>
						
										<?php if((string) $passenger->phone_number == '') $pax_phone_display = 'style="display:none" '; ?>
										<tr class="rezgo-receipt-pax-phone">
										<td <?php echo $pax_phone_display?>class="rezgo-td-label" id="rezgo-label-phone-<?php echo $passenger->id?>">Phone Number</td>
										<td <?php echo $pax_phone_display?>class="rezgo-td-data" id="rezgo-pax-phone-<?php echo $passenger->id?>"><?php echo $passenger->phone_number?></td>
										</tr>
						
										<?php if((string) $passenger->email_address == '') $pax_email_display = 'style="display:none" '; ?>
										<tr class="rezgo-receipt-pax-email">
										<td <?php echo $pax_email_display?>class="rezgo-td-label" id="rezgo-label-email-<?php echo $passenger->id?>">Email</td>
										<td <?php echo $pax_email_display?>class="rezgo-td-data" id="rezgo-pax-email-<?php echo $passenger->id?>"><?php echo $passenger->email_address?></td>
									</tr>
					
									<?php foreach ($passenger->forms->form as $form ) { ?>              
									
									<?php if (in_array($form->type, array('checkbox','checkbox_price'))) { ?>
										<?php if($site->exists($form->answer)) { $form->answer = 'yes'; } else { $form->answer = 'no'; } ?>
									<?php } ?>
					
									<tr class="rezgo-receipt-guest-forms">
										<td class="rezgo-td-label"><?php echo $form->question?></td>
										<td class="rezgo-td-data"><?php echo $form->answer?>&nbsp;</td>
									</tr>
									
									<?php if ($form->options_instructions) { ?>
								
										<?php
										$pax_options = explode(',', (string) $form->options);
										$pax_options_instructions = explode(',', (string) $form->options_instructions);
										$pax_option_extras = array_combine($pax_options, $pax_options_instructions);
										?>  
										
										<?php if ( in_array($form->type, array('select','multiselect')) ) { ?>
										
											<?php if ( $form->type == 'multiselect' ) { ?>
												
												<?php
													$pax_multi_answers = explode(',', (string) $form->answer);
													$pax_multi_answer_list = '';
													foreach ($pax_multi_answers as $pax_answer) {
														$answer_key = html_entity_decode(trim($answer), ENT_QUOTES);
														if ( array_key_exists( $answer_key, $pax_option_extras ) ) {
															$pax_multi_answer_list .= '<li>'.$pax_option_extras[$answer_key].'</li>';
														}
													}
												?>
												
												<?php if ( $pax_multi_answer_list != '' ) { ?>
													<tr class="rezgo-receipt-guest-forms">
														<td class="rezgo-td-label">&nbsp;</td>
														<td class="rezgo-td-data rezgo-form-extras"><ul><?php echo $pax_multi_answer_list?></ul></td>
													</tr>
												<?php } ?>
											
											<?php } else { ?>
											
												<?php $answer_key = html_entity_decode((string) $form->answer, ENT_QUOTES); ?>
												<?php if ( array_key_exists( $answer_key, $pax_option_extras ) ) { ?>
													<tr class="rezgo-receipt-guest-forms">
														<td class="rezgo-td-label">&nbsp;</td>
														<td class="rezgo-td-data rezgo-form-extras"><?php echo $pax_option_extras[$answer_key]?>&nbsp;</td>
													</tr>
												<?php } ?>
											
											<?php } // if ($form->type) ?>
										
										<?php } // if (in_array($form->type)) ?>
									
									<?php } // if ($form->options_instructions) ?>
									
									<?php } // foreach ($passenger->forms) ?>
									
								</table>
								
								</div> 
							
							<?php } // foreach ($site->getBookingPassengers() as $passenger) ?>
            
					<?php } ?>
				</div>

			</div><!-- // .rezgo-confirmation-additional-info --> 

			<div class="rezgo-company-info div-box-shadow p-helper">
				<h3 id="rezgo-receipt-head-cancel"><span>Cancellation Policy</span></h3>
				<p>
				<?php if($site->exists($booking->rezgo_gateway)) { ?>
					Canceling a booking with Rezgo can result in cancellation fees being
					applied by Rezgo, as outlined below. Additional fees may be levied by
					the individual supplier/operator (see your Rezgo 
					<?php echo ((string) $booking->ticket_type == 'ticket') ? 'Ticket' : 'Voucher' ?> for specific
					details). When canceling any booking you will be notified via email,
					facsimile or telephone of the total cancellation fees.<br>
					<br>
					1. Event, Attraction, Theater, Show or Coupon Ticket<br>
					These are non-refundable in all circumstances.<br>
					<br>
					2. Gift Certificate<br>
					These are non-refundable in all circumstances.<br>
					<br>
					3. Tour or Package Commencing During a Special Event Period<br>
					These are non-refundable in all circumstances. This includes,
					but is not limited to, Trade Fairs, Public or National Holidays,
					School Holidays, New Year's, Thanksgiving, Christmas, Easter, Ramadan.<br>
					<br>
					4. Other Tour Products & Services<br>
					If you cancel at least 7 calendar days in advance of the
					scheduled departure or commencement time, there is no cancellation
					fee.<br>
					If you cancel between 3 and 6 calendar days in advance of the
					scheduled departure or commencement time, you will be charged a 50%
					cancellation fee.<br>
					If you cancel within 2 calendar days of the scheduled departure
					or commencement time, you will be charged a 100% cancellation fee. <br>
					<br>
				<?php } else { ?>
					<?php if($site->exists($item->details->cancellation)) { ?>
						<?php echo $item->details->cancellation?>
						<br>
					<?php } ?>
				<?php } ?>

				<a href="javascript:void(0);" onclick="javascript:window.open('<?php echo $site->base; ?>/terms',null,'width=800,height=600,status=no,toolbar=no,menubar=no,location=no,scrollbars=1');">View terms and conditions</a>
				</p>

				<hr>

				<div class="rezgo-receipt-footer-address-container">
					<?php if($site->exists($booking->rid)) { ?>

						<div class="rezgo-cancellation-policy-address">
							<h3 id="rezgo-receipt-head-customer-service"><span>Customer Service</span></h3>

							<?php if($site->exists($booking->rezgo_gateway)) { ?>
								<strong class="company-name">Rezgo.com</strong>
								<address>
								Rezgo.com<br>
								Attn: Partner Bookings<br>
								333 Brooksbank Avenue<br>
								Suite 718<br>
								North Vancouver, BC<br>
								Canada V7J 3V8<br>
								</address>
								<span>
									<i class="fal fa-phone fa-sm"></i>&nbsp;&nbsp;
									<a href="tel:(604) 983-0083">
										(604) 983-0083
									</a> <br>
									<i class="fal fa-envelope fa-sm"></i>&nbsp;&nbsp;
									<a href="mailto:bookings@rezgo.com">bookings@rezgo.com</a> 
								</span>

							<?php } else { ?>
								<?php $company = $site->getCompanyDetails('p'.$booking->rid); ?>
								<strong class="company-name"><?php echo $company->company_name?></strong><br>
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

							<?php } ?>
						</div>
					<?php } ?>

					<div class="rezgo-cancellation-policy-address">
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
				</div><!-- // .rezgo-receipt-footer-address-container --> 
			</div><!-- // .rezgo-company-info --> 

		<?php } // foreach ($site->getBookings('q='.$trans_num) as $booking) ?>
	</div><!-- // .rezgo-booking --> 
</div><!-- //	rezgo-container --> 

<?php if (DEBUG) { ?><pre><?php print_r($booking, 1)?></pre><?php } ?>

<?php 
	if($_SESSION['REZGO_CONVERSION_ANALYTICS']) {
		echo $_SESSION['REZGO_CONVERSION_ANALYTICS'];
		unset($_SESSION['REZGO_CONVERSION_ANALYTICS']);
	} 
?>

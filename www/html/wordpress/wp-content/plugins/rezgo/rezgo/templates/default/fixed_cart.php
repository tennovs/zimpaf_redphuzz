            <div id="rezgo-fixed-cart" class="fixed-cart <?php if ( ($_REQUEST['mode'] == 'page_book') && ($_REQUEST['step'] == 2) ){ ?> 
				fixed-last-step	<?php } ?>">

				<?php foreach ($cart as $item){
					if ($site->exists($item->deposit) ) {
						$step_one_total += (float) $item->deposit_value; 
					} else {
						$step_one_total += (float) $item->overall_total;
					}
				} ?>

					<div id ="rezgo-show-content-toggle" class="cart-summary-dropdown">
						<div>
							<h4 class="title"><i class="far fa-shopping-cart" style='font-size:20px; padding-right:10px; margin-left:-7px;vertical-align: text-bottom;'></i>
							<span id="hide-show-text">Show </span> Order <span class="hidden-xs">Summary</span> <i class="far fa-angle-down" style="font-size:19px; vertical-align:bottom;"></i> </h4>
						</div>
						<div>

						<?php if ($_REQUEST['step'] == 2) { ?>
							<span id="summary_total_value" rel="<?php echo $complete_booking_total?>"><?php echo $site->formatCurrency($complete_booking_total)?></span>
						<?php } else { ?>
							<span id="summary_total_value" data-total="<?php echo $step_one_total?>" rel="<?php echo $step_one_total?>"><?php echo $site->formatCurrency($step_one_total)?></span>
						<?php } ?>

						</div>
					</div> <!-- rezgo-show-content-toggle -->

				<div class="cart-summary">
					<div class="toggle-content">
						<div class="line-items">

							<?php $summary_count = 1; ?>
							<?php $total_savings; ?>

							<?php foreach ($cart as $item){ ?>
								
								<?php $line_items = $site->getTourLineItems($item);?>

									<?php // bundle savings
									foreach($line_items as $line) {	
										if ($line->source == 'bundle') { 
											// echo $line->label . ' bundle : ';
											// echo $line->amount;
											// echo '<br>';
											$bundle_savings += $line->amount;
										}
									} ?>

									<?php foreach($site->getTourPrices($item) as $price) { ?>
										
										<?php if($item->{$price->name.'_num'}) {

											$count = (int) $price->count;
											$strike_price = (float) $price->strike * $count;
											$base_price = (float) $price->base * $count;
											$current_price = (float) $price->price * $count;
											
											if ( ($site->exists($price->strike)) && ($site->exists($price->base)) )  {

												$show_this = max($strike_price, $base_price);
												$discount_savings = $show_this - $current_price; 
												$total_discount += $discount_savings;

											} else if(!$site->isVendor() && $site->exists($price->strike)) {

												// check if strike price higher than set price
												if ($strike_price > $current_price){
													$discount_savings = $strike_price - $current_price;
													$total_discount += $discount_savings;
												}

											} else if($site->exists($price->base)) { 

												$discount_savings = $base_price - $current_price;
												$total_discount += $discount_savings;
											
											} ?>

										<?php } ?>
									<?php } ?>
									
								<span class="summary-count"> <?php echo $summary_count?> of <?php echo count($cart)?></span>
									<div class="item">
										<h4 class="single-item">
											<span class="rezgo-summary-item-name"><?php echo $item->item?></span>
											<br> 
											<span class="rezgo-summary-option-name"><?php echo $item->option?></span>

											<?php if((int) $item->availability < (int) $item->pax_count) { ?>
												<span class="rezgo-order-unavailable" data-toggle="tooltip" data-placement="top" title="This item has become unavailable after it was added to your order"><i class="fa fa-exclamation-triangle"></i> No Longer Available</span>
											<?php } ?> 
										</h4>
										
										<?php if($site->exists($item->deposit)) { ?>
											
											<script>
												// override with set deposit 
												setTimeout(function () {
													jQuery('#summary_price_<?php echo $summary_count?>').html('<?php echo $site->formatCurrency($item->deposit_value)?>');
												}, 1500);
											</script>
											
											<div class="price-container">
												<h4 class="price"> 
													<span class="summary_deposit_value">
														<span rel="<?php echo $item->deposit_value?>" id="summary_price_<?php echo $summary_count?>">
														</span>
													</span>
												</h4>
												<h5 class="deposit">(Deposit)</h5>
											</div>
											
										<?php } else { ?>

											<div class="price-container">
												<h4 class="price">
													<span class="summary_total_value">
													<?php if ($_REQUEST['step'] == 1 || $_REQUEST['mode'] == 'page_order'){ ?>
														<span rel="<?php echo $item->overall_total?>" id="summary_price_<?php echo $summary_count?>">
															<?php echo $site->formatCurrency($item->overall_total)?>
														</span>
													<?php } elseif ($_REQUEST['step'] == 2) { ?>
														<span rel="<?php echo $total_value[$summary_count]?>" id="summary_price_<?php echo $summary_count?>">
															<?php echo $site->formatCurrency($total_value[$summary_count])?>
														</span>
													</span>
													<?php } ?>
												</h4>
											</div>
										<?php } ?>

									</div>
								<hr>

							<?php $summary_count++; }

							// make sure to return a positive value
							$bundle_savings = abs($bundle_savings);
							$total_discount = abs($total_discount);
							
							$total_savings += $bundle_savings; 
							$total_savings += $total_discount;
							
							// end foreach ($cart as $item) ?>
						</div> <!-- // line-items -->

						<?php if(!$site->isVendor()) {
								$hidden = '';
								$disabled = '';
								if ($_REQUEST['mode'] != 'page_order'){$hidden = 'hidden'; $disabled = 'disabled'; }
							?>

							<div id="rezgo-order-promo-code-wrp" class="row rezgo-form-group-short">

								<?php $trigger_code = $site->cart_trigger_code ?>
								<?php if ( (!$trigger_code) || ($trigger_code == '') ) { ?>

								<?php if ($_REQUEST['mode'] == 'page_order') {?>

                                    <span id="rezgo-promo-form-memo"></span>
									
                                    <form class="form-inline rezgo-promo-form" id="rezgo-promo-form" role="form">

										<div class="input-group <?php echo $hidden?>">
											<input type="text" class="form-control" id="rezgo-promo-code" name="promo" placeholder="Enter Promo Code" value="<?php echo ($trigger_code ? $trigger_code : '')?>" required>
											
											<span id="promo-error-msg"></span>

											<div class="input-group-btn">
												<button class="btn rezgo-btn-default" target="_parent" type="submit">
													<span>Apply</span>
												</button>
											</div>
										</div>

									</form>

									<?php } ?>

								<?php } else { ?>
							
									<span class="rezgo-booking-discount <?php echo $disabled?> rezgo-promo-label">
										<span class="rezgo-discount-span">Promo applied:</span> 
											<span id="rezgo-promo-value">
												<?php echo $trigger_code?>
											</span>
											<a id="rezgo-promo-clear" style="color:#333;" class="btn <?php echo $hidden?>" href="<?php echo $_SERVER['HTTP_REFERER']; ?>/?promo=" target="_top"><i class="fa fa-times"></i></a>
										<hr>
									</span>
									
							<?php } ?>
							</div> <!-- // rezgo-order-promo-code-wrp -->

							<script>

								jQuery('#rezgo-promo-form').submit( function(e){
									e.preventDefault();

									jQuery('#rezgo-promo-form').ajaxSubmit({
										type: 'POST',
										url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
										data: { rezgoAction: 'update_promo' },
										success: function(){
											top.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>?promo=' + jQuery('#rezgo-promo-code').val());
										}
									})
								});

								jQuery('#rezgo-promo-clear').click(function(){
									jQuery.ajax({
										type: 'POST',
										url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
										data: { rezgoAction: 'update_promo' },
										success: function(){
										}
									})
								});
								
							</script>
						<?php } ?>  
		
						<div class="row">
							<?php if ($total_savings > 0) { ?>
								<div class="col-sm-12 rezgo-savings">
									<h5><span id="rezgo-savings-description">You saved</span></h5> &nbsp; &nbsp;
									<span id="total_savings"><?php echo $site->formatCurrency($total_savings)?></span>
								</div>
							<?php } ?>

							<div class="col-sm-12 rezgo-summary-order-total">

								<?php if ($_REQUEST['step'] == 2) { ?>

									<h5>Total Due</h5> &nbsp; &nbsp;
									<span id="total_value" rel="<?php echo $complete_booking_total?>"><?php echo $site->formatCurrency($complete_booking_total)?></span>

								<?php } else { ?>

									<h5>Subtotal</h5> &nbsp; &nbsp;
									<span id="total_value" data-total="<?php echo $step_one_total?>" rel="<?php echo $step_one_total?>"><?php echo $site->formatCurrency($step_one_total)?></span>

								<?php } ?>
							</div>
						</div>
					</div><!-- // toggle-content -->

						<div class="rezgo-btn-wrp fixed-cart-btn-wrap">
							<?php if ( $_REQUEST['mode'] == 'page_order') { ?>

								<?php if(count($cart)) { ?>
									<span id="rezgo-booking-btn">
										<a href="<?php echo $site->base?>" id="rezgo-order-book-more-btn" class="btn rezgo-btn-default btn-lg btn-block">
											<span>Book More</span>
										</a>
									</span>
									<a id ="rezgo-btn-book" href="<?php echo $site->base?>/book" class="btn rezgo-btn-book btn-lg btn-block rezgo-order-step-btn-side"><span>Check Out</span></a>
								<?php } ?>

							<?php } else if ( $_REQUEST['mode'] == 'page_book'){ ?>

									<?php if($_REQUEST['step'] == 1) { ?>
										<a id="rezgo-book-step-one-btn-previous" class="btn rezgo-btn-default btn-lg btn-block rezgo-book-step-btn-previous" href="<?php echo $site->base?>/order"><span>Back to Order</span></a>
										<button id ="rezgo-book-step-one-btn-continue" class="btn rezgo-btn-book btn-lg btn-block rezgo-book-step-btn-continue" type="submit" form="rezgo-guest-form">
											<span>Continue to Payment</span>
										</button>
									<?php } else { ?>
										<style>
											#rezgo-fixed-cart .rezgo-btn-wrp{
											margin-top: 0 !important;}
										</style>
											<a class="btn rezgo-btn-default btn-lg btn-block" href="<?php echo $site->base?>/book"><span>Previous Step</span></a>
										</button>
									<?php } ?>
							<?php } ?>
						</div>  <!-- // fixed-cart-btn-wrap -->

				</div> <!-- // cart summary -->

				<?php if ( ($_REQUEST['mode'] == 'page_book') && ($_REQUEST['step'] == 1) ){ ?> 
					<!-- show error msgs only on guest info page -->
					<div id="rezgo-book-errors" class="alert alert-danger rezgo-book-errors-side">
						<span>Some required fields are missing. Please complete the highlighted fields.</span>
					</div>
				<?php } ?>

			 </div><!-- // fixed cart -->
			 
<script>

jQuery(document).ready(function($){
	// fixed summary at the side 
	function getScrollTop() {
		if (typeof window.parent.pageYOffset !== "undefined" ) {
			// Most browsers
			return window.parent.pageYOffset;
		}
		var d = document.documentElement;
		if (typeof d.clientHeight !== "undefined") {
			// IE in standards mode
			return d.scrollTop;
		}
		// IE in quirks mode
		return document.body.scrollTop;
	}

	var cart = document.getElementById("rezgo-fixed-cart");
	var container = parent.document.getElementById('rezgo_content_container');
	// account for whitelabel header
	var header = parent.document.getElementById('rezgo-default-header');

	function toggleScroll(){
		window.parent.addEventListener('scroll', function() {
			var scroll = getScrollTop();
			var offset = container.offsetTop - 10;

			if (header){
				headerHeight = 50;
			} else {
				headerHeight = 0;
			}

			cart.style.top = (scroll - offset + headerHeight) + "px";
		});
	}

	// toggle order summary on mobile
	var toggle_div = $('#rezgo-show-content-toggle');
	var toggle_content = $('.toggle-content');

	$(window).resize(function() {
		let width = this.innerWidth;
		if (width > 992){
			toggle_content.show();
			toggle_div.find('i.fa-angle-down').addClass('active');
			toggle_div.find('span#hide-show-text').text('Hide ');

			toggleScroll();
		}
	});

	toggle_div.click(function(){
		toggle_content.slideToggle(250);
		$(this).find('span#hide-show-text').text(function(i, text){
			return text === "Show " ? "Hide " : "Show ";
		});
		$(this).find('i.fa-angle-down').toggleClass('active');
	});
});
</script>
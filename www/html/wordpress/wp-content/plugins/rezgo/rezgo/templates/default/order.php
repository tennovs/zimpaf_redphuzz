<?php
$company = $site->getCompanyDetails();
// non-open date date_selection elements
$date_types = array('always', 'range', 'week', 'days', 'single'); // centralize this?
?>

<div id="rezgo-order-wrp" class="container-fluid rezgo-container">
	<div class="jumbotron rezgo-booking">
		<div id="rezgo-order-crumb" class="row">
			<ol class="breadcrumb rezgo-breadcrumb">
				<li id="rezgo-order-your-order" class="rezgo-breadcrumb-order active"><span class="default"> Order</span><span class="custom"></span></li>
				<li id="rezgo-order-info" class="rezgo-breadcrumb-info"><span class="default">Guest Information</span><span class="custom"></span></li>
				<li id="rezgo-order-billing" class="rezgo-breadcrumb-billing"><span class="default">Payment</span><span class="custom"></span></li>
				<li id="rezgo-order-confirmation" class="rezgo-breadcrumb-confirmation"><span class="default">Confirmation</span><span class="custom"></span></li>
			</ol>
		</div>

		<?php $cart = $site->getCart(); ?>
		<?php if($_SESSION['cart_status']) $cart_status =  new SimpleXMLElement($_SESSION['cart_status']); ?>

		<?php if ($cart_status){ 
			// clear promo if there is an invalid promo
			if (($cart_status->error_code == 9) || ($cart_status->error_code == 11)) $site->resetPromoCode(); ?>

			<div id="rezgo-order-error-message">

				<!-- Top level error message -->
				<span class="message">
					<?php echo $cart_status->message?>

					<?php // list items removed
						foreach ($cart_status->removed->item as $removed_item){
							$tour = $site->getTours('t=uid&q='.$removed_item->id); ?>
							<br>
							<?php echo $tour[0]->item?> - <?php echo $tour[0]->option?> (<?php echo date((string) $company->date_format, (string) $removed_item->date)?>)

						has been removed from your cart
					<?php } ?>

				</span>
				<a href="#" id="rezgo-error-dismiss" class="btn"><span>close</span></i></a>
			</div>

			<script>

				// dismiss error when user navigates away or manually closes it
				function dismissError(){
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
						data: { rezgoAction: 'reset_cart_status'},
						success: function(data){
								// console.log('reset cart status session');
								},
						error: function(error){
								console.log(error);
								}
					});
				}

				jQuery('#rezgo-error-dismiss').click(function(){
					dismissError();
				});

				setTimeout(() => {
					dismissError();
				}, 3000);

				window.onbeforeunload = dismissError();

			</script>
		<?php } ?>

		<?php if (!$cart) { ?>
			<div class="rezgo-order-empty-cart-wrp">
				<div class="rezgo-form-group cart_empty">
					<p class="lead">
						<span>There are no items in your order.</span>
					</p>
				</div>

				<div class="row" id="rezgo-booking-btn">
					<div class="col-md-4 col-xs-12 rezgo-btn-wrp">
						<a id="rezgo-order-book-more-btn" href="<?php echo $site->base?>" class="btn rezgo-btn-default btn-lg btn-block">
							<span>Book More</span>
						</a>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<?php $item_num = 0; $item_count=1;?>
			<?php 
				$contents = array(); 
				$cart_coms = array(); 
				$cross_ids = array();  
			?>

		<div class="flex-container order-page-container">
			<div class="order-summary">

			<?php foreach ($cart as $item) { ?>

				<?php $site->readItem($item); ?>

				<?php	
					$cart_coms[(int) $item->com]['uid'] = (int) $item->uid; 
					$cart_coms[(int) $item->com]['com'] = (int) $item->com; 
						
					if ((string) $item->availability_type == 'date') {
						$cart_coms[(int) $item->com]['date'] = date("Y-m-d", (string) $item->booking_date); 
					} else {
						$cart_coms[(int) $item->com]['date'] = 'open'; 
					}
	
					if($site->getCrossSell()) { 
					
						$cart_coms[(int) $item->com]['cross'] = TRUE; 
					
						foreach($site->getCrossSell() as $cross) { 
							$cross_ids[(int) $item->com][] = (int) $cross->com;
						} 
					
					} else {
					
						$cart_coms[(int) $item->com]['cross'] = FALSE; 
						
					}
					
				 ?>

				<div class="rezgo-sub-title">
					<span> &nbsp; Booking <?php echo $item_count?> of <?php echo count($cart)?></span>
				</div>

				<div id="rezgo-order-item-<?php echo $item->uid;?>" class="single-order-item">

					<div class="row rezgo-form-group rezgo-cart-title-wrp">
						<div class="col-xs-9 rezgo-cart-title">
							<h3 class="rezgo-item-title">
								<a href="<?php echo $site->base?>/details/<?php echo $item->com?>/<?php echo $site->seoEncode($item->item)?>">
									<span><?php echo $item->item?></span>
                                    <span> &mdash; <?php echo $item->option?></span>
								</a>
							</h3>

							<?php if (in_array((string) $item->date_selection, $date_types)){ ?>
								<?php $data_book_date = date("Y-m-d", (string)$item->booking_date); ?>
								<label>
									<span>Date: </span>
									<span class="lead"><?php echo date((string) $company->date_format, (string) $item->booking_date); ?></span>
								</label>
							<?php } else { ?>
								<?php $data_book_date = date('Y-m-d', strtotime('+1 day')); ?>
								<label><span class="lead"> Open Availability </span></label>
							<?php } ?>

							<?php if ($item->discount_rules->rule) {
								echo '<br><label class="rezgo-booking-discount">
								<span class="rezgo-discount-span">Discount:</span> ';
								unset($discount_string);
								foreach($item->discount_rules->rule as $discount) {	
									$discount_string .= ($discount_string) ? ', '.$discount : $discount;
								}
								echo '<span class="rezgo-promo-code-desc">'.$discount_string.'</span>
								</label>';
							} ?>

							<div class="rezgo-order-memo rezgo-order-date-<?php echo date('Y-m-d', (string) $item->booking_date)?> rezgo-order-item-<?php echo $item->uid; ?>"></div>
						</div>

						<div class="col-xs-3 column-btns">
							<div class="col-sm-12 rezgo-btn-cart-wrp">
								<button type="button" data-toggle="collapse" class="btn btn-block rezgo-pax-edit-btn" data-order-item="<?php echo $item->uid?>" data-order-com="<?php echo $item->com?>" data-cart-id="<?php echo $item_num?>" data-book-date="<?php echo $data_book_date;?>" data-target="#pax-edit-<?php echo $item_num?>">
									<span>Edit Guests</span>
								</button>
							</div>

							<div class="col-sm-12 rezgo-btn-cart-wrp">
								<button type="button" class="btn rezgo-btn-remove btn-block" data-index="<?php echo $item_num?>" data-date=<?php echo $data_book_date?> data-order-item="<?php echo $item->uid?>" data-com="<?php echo $item->com?>" data-url="<?php echo $site->base?>/order?edit[<?php echo $item->cartID?>][adult_num]=0">
									<span>Remove<span class='hidden-xs'> from Order</span></span>
								</button>
							</div>
						</div>
					</div>

					<div class="row rezgo-form-group rezgo-cart-table-wrp">
						<div class="col-xs-12">
							<table class="table rezgo-billing-cart table-responsive">
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

													<span class="discount">
														<!-- show only if strike price is higher -->
														<?php if ($strike_price >= $initial_price) { ?>
															<span class="rezgo-strike-price">
																<?php echo $site->formatCurrency($strike_price)?>
															</span>
														<?php } ?>
													</span>

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

							<?php if ((int) $item->availability < (int) $item->pax_count) { ?>
								<tr class="rezgo-tr-order-unavailable">
									<td colspan="4" class="rezgo-order-unavailable">
										<span data-toggle="tooltip" data-placement="top" title="This item has become unavailable after it was added to your order">
											<i class="fa fa-exclamation-triangle"></i>
											<span> No Longer Available</span>
										</span>
									</td>
								</tr>
							<?php } else { $cart_total += (float) $item->overall_total; } ?>

							<tr class="rezgo-tr-subtotal">
								<td colspan="3" class="text-right"><span class="push-right"><strong>Subtotal</strong></span></td>
								<td class="text-right"><?php echo $site->formatCurrency($item->sub_total); ?></td>
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

							<tr class="rezgo-tr-subtotal summary-total">
								<td colspan="3" class="text-right"><span class="push-right"><strong>Total</strong></span></td>
								<td class="text-right"><strong><?php echo $site->formatCurrency($item->overall_total)?></strong></td>
							</tr>

							<?php if($site->exists($item->deposit)) { ?>
									<tr class="rezgo-tr-deposit">
										<td colspan="3" class="text-right">
											<span class="push-right"><strong>Deposit to Pay Now</strong></span>
										</td>
										<td class="text-right">
											<span class="rezgo-item-deposit" id="deposit_value_<?php echo $c?>" rel="<?php echo $item->deposit_value?>">
												<strong><?php echo $site->formatCurrency($item->deposit_value)?></strong>
                              				</span>
										</td>
									</tr>

									<?php $complete_booking_total += (float) $item->deposit_value; ?>
                          
								<?php } else { ?>
                        
									<?php $complete_booking_total += (float) $item->overall_total; ?>
                          
								<?php } ?>
						</table>

							<?php if($site->getTourRelated()) { ?>
								
								<div class="col-lg-9 col-sm-8 col-xs-12 rezgo-related">
								<div class="rezgo-related-label"><span>Related products</span></div>
								<?php foreach($site->getTourRelated() as $related) { ?>
									<a href="<?php echo $site->base?>/details/<?php echo $related->com?>/<?php echo $site->seoEncode($related->name)?>" class="rezgo-related-link"><?php echo $related->name?></a><br>
								<?php } ?>
								</div>
                
							<?php } ?>

                    <?php  
						if ($site->getCrossSell()) {

								$cross_sell = $site->getCrossSell();
								$cross_text = $site->getCrossSellText();

								if ($cross_text->title != '') {
									$cross_btn = (string) $cross_text->title;
								} else {
									$cross_btn = 'View Similar Items';
								}

								if ((string) $item->availability_type == 'date') {
									$cross_sell_date = date("Y-m-d", (string) $item->booking_date); 
								} else {
									$cross_sell_date  = 'open'; 
								}

					?>
								
						<div class="<?php echo (!$site->getTourRelated() ? '' : '')?> rezgo-cross-order">
							<div class="rezgo-btn-cross-wrp">
								<button type="button" class="btn rezgo-btn-cross" id="rezgo-btn-cross-<?php echo $item->com?>" onclick="openCrossSell('<?php echo $item->com?>', '<?php echo $item->uid?>', '<?php echo $cross_sell_date?>')">
								<span><?php echo $cross_btn?></span>
								</button>
							</div>
						</div>
              
              		<?php } ?>

					<script>jQuery(document).ready(function($){$('.rezgo-order-unavailable span').tooltip();});</script>
						</div>
					</div>

					<div class="row rezgo-form-group-short">
						<div class="collapse rezgo-pax-edit-box" id="pax-edit-<?php echo $item_num?>"></div>
					</div>

					<div id="pax-edit-scroll-<?php echo $item_num?>" class="rezgo-cart-edit-wrp"></div>
				</div> <!-- // single-order-item -->

				<hr>

				<?php $item_num++; $item_count++; ?>
						
				<?php  
			    $contents[]['id'] = (int) $item->uid;
				$contents[]['quantity'] = (int) $item->pax_count; } //foreach end ?>

				<?php } // end if(!$cart) ?>  
				<?php
					// cart loop is done ... check for cross-sell items
					if (!empty($cross_ids)) {
						foreach ($cross_ids as $c_com => $c_array) {
							foreach ($c_array as $c_id) {
								if (array_key_exists($c_com, $cart_coms) && array_key_exists($c_id, $cart_coms)) {
									$cart_coms[$c_com]['cross'] = FALSE; 
								}
							}
						}
					}
					
					if (!empty($cart_coms)) {
						foreach ($cart_coms as $cart_id) {
							if ($cart_id['cross'] == TRUE && $_COOKIE['cross_'.$cart_id['com']] != 'shown') { // 
								echo '<script> 
								jQuery(document).ready(function() { 
									if(getCookie("cross_'.$cart_id['com'].'") != "shown"){
										openCrossSell("'.$cart_id['com'].'", "'.$cart_id['uid'].'", "'.$cart_id['date'].'"); 
										
									}
								}); 
								</script>';
								break; // only execute one cross-sell at a time
							}
						}
					}
				
				?>

			</div> <!-- // order-summary -->
			
			<?php if($cart) { ?>
				<!-- FIXED CART -->
				<?php require('fixed_cart.php');?>
			<?php } ?> 

		</div><!-- // flex-container -->

		<?php if(count($cart)) { ?>

			<?php if(!$site->isVendor()) { ?>

				<?php if ( (!$trigger_code) || ($trigger_code == '') ) { ?>

				<div id="rezgo-order-promo-code-wrp__mobile" class="row rezgo-order-promo-code-wrp__mobile rezgo-form-group-short">
					
					<form class="rezgo-promo-form__mobile" id="rezgo-promo-form__mobile" role="form">

                    	<span id="rezgo-promo-form-memo"></span>

						<div class="input-group <?php echo $hidden?>">
							<input type="text" class="form-control" id="rezgo-promo-code__mobile" name="promo" placeholder="Enter Promo Code" value="<?php echo ($trigger_code ? $trigger_code : '')?>" required>

							<div class="input-group-btn">
								<button class="btn rezgo-btn-default apply-promo-btn" target="_parent" type="submit">
									<span>Apply</span>
								</button>
							</div>
						</div>

					</form>
									
				<?php } else { ?>

				<div id="rezgo-order-promo-code-wrp__mobile" class="applied row rezgo-order-promo-code-wrp__mobile rezgo-form-group-short">

					<span class="rezgo-booking-discount <?php echo $disabled?> rezgo-promo-label">
						<span class="rezgo-discount-span">Promo applied:</span> 
							<span id="rezgo-promo-value__mobile">
								<?php echo $trigger_code?>
							</span>
							<a id="rezgo-promo-clear__mobile" style="color:#333;" class="btn <?php echo $hidden?>" href="<?php echo $_SERVER['HTTP_REFERER']; ?>/?promo=" target="_top"><i class="fa fa-times"></i></a>
						<hr>
					</span>
									
				<?php } ?>
				</div> <!-- // rezgo-order-promo-code-wrp__mobile -->

			<?php } ?>

			<div id="rezgo-bottom-cta">
				<span id="rezgo-booking-btn">
					<a href="<?php echo $site->base?>" id="rezgo-order-book-more-btn" class="btn rezgo-btn-default btn-lg btn-block">
						<span>Book More</span>
					</a>
				</span>

				<a id="rezgo-btn-book" href="<?php echo $site->base?>/book" class="btn rezgo-btn-book btn-lg btn-block rezgo-order-step-btn-bottom">
					<span>Check Out</span>
				</a>

				<script>

					jQuery('#rezgo-promo-form__mobile').submit( function(e){
						e.preventDefault();

						jQuery('#rezgo-promo-form').ajaxSubmit({
							type: 'POST',
							url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
							data: { rezgoAction: 'update_promo' },
							success: function(data){
								top.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>?promo=' + jQuery('#rezgo-promo-code__mobile').val());
							}
						})
					});

					jQuery('#rezgo-promo-clear__mobile').click(function(){
						jQuery.ajax({
							type: 'POST',
							url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
							data: { rezgoAction: 'update_promo' },
						})
					});

				</script>

			</div>
		<?php } ?>
	
	</div> <!-- // Jumbotron -->
</div><!-- // rezgo-container -->

		<?php
			// build 'share this order' link
			$pax_nums = array ('adult_num', 'child_num', 'senior_num', 'price4_num', 'price5_num', 'price6_num', 'price7_num', 'price8_num', 'price9_num');

			$order_share_link = (($this->checkSecure()) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$site->base.'/order/?order=clear';

			foreach($cart as $key => $item) {	
				if(in_array((string) $item->date_selection, $date_types)) {	
					$order_share_date = date("Y-m-d", (string)$item->booking_date);
				} else {
					$order_share_date = date('Y-m-d', strtotime('+1 day')); // for open availability
				}

				$order_share_link .= '&add['.$item->num.'][uid]='.$item->uid.'&add['.$item->num.'][date]='.$order_share_date;

				foreach($pax_nums as $pax) {	
					if($item->{$pax} != '') {
						$order_share_link .= '&add['.$item->num.']['.$pax.']='.$item->{$pax};
					}
				}
			}

			// finally, include promo/refid if set
			if($site->cart_trigger_code) {
				$order_share_link .= '&promo='.$site->cart_trigger_code;
			}
			if($site->refid) {
				$order_share_link .= '&refid='.$site->refid;
			}
		?>

		<?php if($cart) { ?>
			<div class="order-footer">
				
				<?php if(count($cart)) { ?>
						<div id="rezgo-order-share-btn-wrp" class="clearfix">
							<a href="javascript:void(0);" id="rezgo-share-order">
								<span><i class="fa fa-external-link"></i>Share this order </span>
							</a>
							<input type="text" id="rezgo-order-url" style="opacity:1;" class="form-control" onclick="this.select();" value="<?php echo $order_share_link?>" readonly>
						</div>
						
						<!-- copy to clipboard -->
						<script>
							const shareBtn = document.querySelector('#rezgo-share-order');
							const copyText = document.querySelector("#rezgo-order-url");
							const showText = document.querySelector(".link-copy-success");

							const copyMeOnClipboard = () => {
								copyText.select();
								copyText.setSelectionRange(0, 99999); //for mobile phone
								document.execCommand("copy");
								shareBtn.innerHTML = '<i class="fa fa-check"></i>Link copied';
								
								setTimeout(() => {
									shareBtn.innerHTML = '<span><i class="fa fa-external-link"></i>Share this order</span>'    
								}, 3000)
							}

							shareBtn.addEventListener('click', function(){
								copyMeOnClipboard();
							});

						</script>

				<?php } ?>

			 </div> <!--// order footer -->
			 <br>
		<?php } ?>

<script>

    function openCrossSell(com, id, date) {
		
		var
		rezgoModalTitle = 'Return Trip',
		wp_slug = '<?php echo $_REQUEST['wp_slug']; ?>',
		query = '<?php echo home_url() . $site->base; ?>?rezgo=1&mode=return_trip&com=' + com + '&id=' + id + '&date=' + date + '&wp_slug='+ wp_slug+ '&headless=1&hide_footer=1&cross_sell=1';

		//window.top.$('#rezgo-modal-loader').css({'display':'block'});
		window.top.$('#rezgo-modal-iframe').attr('src', query).attr('height', '90%');// 
		jQuery("#rezgo-modal-iframe").css({"width": "100%"});
		window.top.$('#rezgo-modal').modal();
		
	}

	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	function deleteCookie(cname) {
		document.cookie = cname + "=;Thu, 01 Jan 1970 00:00:00 UTC;path=/";
	}

	jQuery(document).ready(function($){

		// switch up the btn/promo form ids 
		window.onload = (event) => {
			let width = this.innerWidth;
			if (width < 992){
				$(".rezgo-order-step-btn-bottom").prop("id", "rezgo-btn-book");
				$(".rezgo-order-step-btn-side").prop("id", "");

				$(".rezgo-promo-form__mobile").prop("id", "rezgo-promo-form");
				$(".rezgo-promo-form").prop("id", "");
			} else {
				$(".rezgo-order-step-btn-side").prop("id", "rezgo-btn-book");
				$(".rezgo-order-step-btn-bottom").prop("id", "");

				$(".rezgo-promo-form").prop("id", "rezgo-promo-form");
				$(".rezgo-promo-form__mobile").prop("id", "");
			}
		};
		$(window).resize(function() {
			let width = this.innerWidth;
			if (width < 992){
				$(".rezgo-order-step-btn-bottom").prop("id", "rezgo-btn-book");
				$(".rezgo-order-step-btn-side").prop("id", "");

				$(".rezgo-promo-form__mobile").prop("id", "rezgo-promo-form");
				$(".rezgo-promo-form").prop("id", "");

			} else {
				$(".rezgo-order-step-btn-side").prop("id", "rezgo-btn-book");
				$(".rezgo-order-step-btn-bottom").prop("id", "");

				$(".rezgo-promo-form").prop("id", "rezgo-promo-form");
				$(".rezgo-promo-form__mobile").prop("id", "");

			}
		});

		$('.rezgo-pax-edit-btn').each(function() {
			var order_com = $(this).attr('data-order-com'); 
			var order_item = $(this).attr('data-order-item');
			var cart_id = $(this).attr('data-cart-id'); 
			var book_date = $(this).attr('data-book-date'); 
			var security = '<?php echo wp_create_nonce('rezgo-nonce'); ?>';
			var method	= 'edit_pax.php?';
					method += 'com='		+ order_com;
					method += '&id='		+ order_item;
					method += '&order_id='	+ cart_id;
					method += '&date='		+ book_date;
					method += '&parent_url=<?php echo $site->base; ?>';

			jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					action: 'rezgo',
					method: method,
					security: security
				},
				context: document.body,
				success: function(data) {
					$('#pax-edit-' + cart_id).html(data);
				}
			});
		});	

		$('.rezgo-pax-edit-btn').click(function(){

			$(this).find('span').html() == "Edit Guests" ? $(this).find('span').html('Cancel') : $(this).find('span').html('Edit Guests');

			var cart_id = $(this).attr('data-cart-id'); 
			var pax_edit_position = $('#pax-edit-scroll-' + cart_id).position();
			var pax_edit_box = $('.rezgo-pax-edit-box');

			$('.single-order-item').find('.rezgo-pax-edit-box').addClass('active');
		});

		$('.rezgo-btn-remove').click(function() {

			localStorage.clear();

			let com = $(this).data('com');
			let url = $(this).data('url');

			if ( getCookie('cross_' + com ) != '') {
				deleteCookie( 'cross_' + com );
			}

			var index = $(this).data('index');
			var item_id = $(this).data('order-item');
			var date = $(this).data('date');
			
			$.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
				data: { rezgoAction: 'remove_item',
						index : index,
						item_id : item_id,
						date : date,	
					  },
				success: function(data){
						<?php if (!DEBUG){ ?>
							window.location.reload();
						<?php } else { ?>
							alert(item_id + ' - ' + date + ' removed');
						<?php } ?>
					},
				error: function(error){
					console.log(error);
				}
			});
		});

		$('#rezgo-cross-dismiss', parent.document).click(function() {
			var com = $(this).attr('rel');
			setCookie('cross_' + com, 'shown', 2); 
		});

		$('#rezgo-error-dismiss').click(function(e) {
			e.preventDefault();
			$('#rezgo-order-error-message').fadeOut();
		});

	});
</script>

<style>#debug_response {width:100%; height:200px;}</style>
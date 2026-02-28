<?php if ($_REQUEST['cross_sell']) { ?>	
	<script type="text/javascript" src="<?php echo $site->path; ?>/js/jquery.form.js"></script>
<?php } ?>

<?php
	$company = $site->getCompanyDetails();
	$availability_title = '';	

	if ($_REQUEST['option_num']) {
		$option_num = $_REQUEST['option_num'];
	} else {
		$option_num = 1;	
		
		if ($_REQUEST['type'] != 'open') {
			
			if ($_REQUEST['js_timestamp']) {
				$now = $_REQUEST['js_timestamp'];
	  			date_default_timezone_set($_REQUEST['js_timezone']);

			} else {
				$now = time();
			}

			$php_now = time();
			
			$today = date('Y-m-d', $now);
			$selected_date = date('Y-m-d', strtotime($_REQUEST['date'] . ' ' . $company->time_format . ' hours'));
			$selected_date = date('Y-m-d', strtotime($_REQUEST['date']));
			$available_day = date('D', strtotime($_REQUEST['date']));
			$available_date = date((string) $company->date_format, strtotime($_REQUEST['date'])); 

			$availability_title = '<div class="rezgo-date-options" style="display:none;"><span class="rezgo-calendar-avail"><span>Availability&nbsp;for: </span></span> <strong><span class="rezgo-avail-day">'.$available_day.',&nbsp;</span><span class="rezgo-avail-date">'.$available_date.'</span></strong>';
      
      if($today !== $selected_date) {
        $date_diff = $site->getCalendarDiff($today, $selected_date);
				$date_diff = ($date_diff=='1 day') ? 'Tomorrow' : $date_diff . ' from today';
        $availability_title .= '<strong class="rezgo-calendar-diff"><span>('.$date_diff.')</span></strong>';
      } else {
      	$availability_title .= '<strong class="rezgo-calendar-diff"><span>(Today)</span></strong>';
      }
      
      $availability_title .= '</div>';
		}
	}

	if ($_REQUEST['date'] != 'open') {
		$date_request = '&d='.$_REQUEST['date'];
	} else {
		$date_request = '';
	}

	$options = $site->getTours('t=com&q='.$_REQUEST['com'].$date_request.'&file=calendar_day');
	
?>

<?php if ($options) { ?>
	<?php echo $availability_title?>

	<?php if ($_REQUEST['cross_sell']) { ?>
	<script>

		jQuery(document).ready(function($){

			$('.panel-collapse').on('shown.bs.collapse', function () {

				console.log('on collapsed');
				
				var panel_offset = $(this).closest('.panel');
				
				$('div.rezgo-container').animate({
						scrollTop: $(panel_offset).offset().top
				}, 500);		
				
			})




		});
		
  </script>
  <?php } ?>

	<span class="rezgo-date-memo rezgo-calendar-date-<?php echo $_REQUEST['date']?>"></span>

	<div class="panel-group" id="rezgo-select-option-<?php echo $option_num?>">
		<?php if (count($options) != 1) { // && $option_num != 1 ?>
			<span class="rezgo-choose-options">Choose one of the options below <i class="fal fa-angle-double-down"></i></span>
		<?php }

		if ($_REQUEST['type'] == 'open') {
			$sub_option = 'o1';
		} else {
			$sub_option = 'a';
		}

		// get cart digest for validation below
		$cart_data = array();
		$cart_today = array();
			
			$cart = $site->getCart();
			
			foreach ($cart as $cart_array) {
				$cart_data[] = array(
					'id' => (string) $cart_array->uid,
					'date' => (string) $cart_array->date['value'],
					'pax' => (string) $cart_array->pax
					// spaces vs pax?
				);
			}
			
			foreach ($cart_data as $data) {
				if ($data['date'] == $_REQUEST['date'] || $data['date'] == 'open') {
					$cart_today[$data['id']] += $data['pax'];
				}
			}

			foreach($options as $option) { ?>
		
				<?php $site->readItem($option);

				// if option is in cart and pax num exceeds availability
				if (array_key_exists((int) $option->uid, $cart_today) && ((int) $option->date->availability <= (int) $cart_today[(int) $option->uid])) {
					// set availability to 0 for this day
					$option->date->availability = 0;
				} elseif (array_key_exists((int) $option->uid, $cart_today) && ((int) $option->date->availability > (int) $cart_today[(int) $option->uid])) {
					// adjust availability for this day
					$option->date->availability = $option->date->availability - (int) $cart_today[(int) $option->uid];
				}
				
				// hide if block size exceeds availability
				if ( (int) $option->date->availability >= (int) $option->block_size || !$option->block_size) {
					$block_unclass = '';
					$block_available = TRUE;
				} else {
					$block_unclass = ' block-unavailable';
					$block_available = FALSE;
					$option->date->availability = 0;
				}

				// hide unavailable options
				if ($option->date->availability == 0) {
					$panel_unclass = ' panel-unavailable';
				} else {
					$panel_unclass = '';
				}
			
				// don't mix open options with calendar options
				// only return options that match the request type
				if ((($_REQUEST['type'] == 'calendar' || $_REQUEST['type'] == 'single') && (string) $option->date['value'] != 'open') 
					|| ($_REQUEST['type'] == 'open' && (string) $option->date['value'] == 'open' )
				) { ?>
					<div class="panel panel-default<?php echo $panel_unclass.$block_unclass?>">
						<script>
							var fields_<?php echo $option_num.'_'.$sub_option?> = new Array();
							var required_num_<?php echo $option_num.'_'.$sub_option?> = 0;
	
							function isInt(n) {
								 return n % 1 === 0;
							}
	
							// validate form data
							function check_<?php echo $option_num.'_'.$sub_option?>() {
								var err;
								var count_<?php echo $option_num.'_'.$sub_option?> = 0;
								var required_<?php echo $option_num.'_'.$sub_option?> = 0;
	
								for(v in fields_<?php echo $option_num.'_'.$sub_option?>) {
									
									if (jQuery('#' + v).attr('rel') == 'bundle' && jQuery('#' + v).val() >= 1) {
										
										jQuery('.' + v).each(function() {
											var multiple = jQuery(this).data('multiple');
											var val = jQuery('#' + v).val();
											var newval = multiple * val;
											var rel = jQuery(this).attr('rel');
											
											count_<?php echo $option_num.'_'.$sub_option?> += newval; // increment total
											
											if(fields_<?php echo $option_num.'_'.$sub_option?>[rel]) { required_<?php echo $option_num.'_'.$sub_option?> = 1; }
											
											if((count_<?php echo $option_num.'_'.$sub_option?> <= <?php echo $option->date->availability?>) && (count_<?php echo $option_num.'_'.$sub_option?> <= 150)) {
												jQuery(this).attr('disabled', false).val(newval);
											}
											
										});									
									
									} else {
										
										count_<?php echo $option_num.'_'.$sub_option?> += jQuery('#' + v).val() * 1; // increment total
										
									}
									
									// has a required price point been used
									if(fields_<?php echo $option_num.'_'.$sub_option?>[v] && jQuery('#' + v).val() >= 1) { required_<?php echo $option_num.'_'.$sub_option?> = 1; 
									}

									// negative (-) symbol not allowed on PAX field
									if (jQuery('#' + v).val() < 0) 
									{
									    err = 'Please enter valid number for booking.';
								    }
								}
	
								if(count_<?php echo $option_num.'_'.$sub_option?> == 0 || !count_<?php echo $option_num.'_'.$sub_option?>) {
									err = 'Please enter the number you would like to book.';
								} else if(required_num_<?php echo $option_num.'_'.$sub_option?> > 0 && required_<?php echo $option_num.'_'.$sub_option?> == 0) {
									err = 'At least one marked ( * ) price point is required to book.';
								} else if(!isInt(count_<?php echo $option_num.'_'.$sub_option?>)) {
									err = 'Please enter a whole number. No decimal places allowed.';
								} else if(count_<?php echo $option_num.'_'.$sub_option?> < <?php echo $option->per?>) {
									err = '<?php echo $option->per?> minimum required to book.';
								} else if(count_<?php echo $option_num.'_'.$sub_option?> > <?php echo $option->date->availability?>) {
									err = 'There is not enough availability to book ' + count_<?php echo $option_num.'_'.$sub_option?>;
								} else if(count_<?php echo $option_num.'_'.$sub_option?> > 250) {
									err = 'You can not book more than 250 spaces in a single booking.';
								}
	
								if(err) {
									
									<?php if(!$site->config('REZGO_MOBILE_XML')) { ?>
										jQuery('#error_text_<?php echo $option_num.'_'.$sub_option?>').html(err);
										jQuery('#error_text_<?php echo $option_num.'_'.$sub_option?>').slideDown().delay(2000).slideUp('slow');
									<?php } else { ?>
										jQuery('#error_mobile_text_<?php echo $option_num.'_'.$sub_option?>').html(err);
										jQuery('#error_mobile_text_<?php echo $option_num.'_'.$sub_option?>').slideDown().delay(2000).slideUp('slow');
									<?php } ?>
									return false;
									
								} else {
									
									// prepare inputs before submitting (*bundles)							
									var inputs = new Object(); // create new object
									
									jQuery("#checkout_<?php echo $option_num.'_'.$sub_option?> input").each(function() {
										
										if (this.name != '') {
											var index = this.name; // set variable prop as input name
											var val;
											
											if (this.value == '') { val = 0; } else { val = parseInt(this.value); }
											
											if ( inputs.hasOwnProperty(index) == true ) { // check if prop exists 
												jQuery(this).val(val + parseInt(inputs[index])); // update value of current input, adding current prop val 
												inputs[index] += val; // update this prop
											} else {
												inputs[index] = val; // set first val of this prop
											}									
										}
									});

									// addCart() request
									jQuery('#checkout_<?php echo $option_num.'_'.$sub_option?>').submit( function(e) {
										e.preventDefault();

										jQuery('#checkout_<?php echo $option_num.'_'.$sub_option?>').ajaxSubmit({
											type: 'POST',
											url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax', 
											data: { rezgoAction: 'add_item'},
											success: function(data){
												// console.log(data);
												// console.log(JSON.parse(data));
												let response = JSON.parse(data);

												// no error from adding item to cart 
												if (response == null) {

													localStorage.clear();
													<?php if (!DEBUG){ 
														// add cart token to the redirect
														$cart_token = $_COOKIE['rezgo_cart_token_'.REZGO_CID]; ?>

														<?php if ($_REQUEST['cross_sell']) { ?>	
															
															let parentContainer = window.parent.parent;
															parentContainer.document.getElementById('rezgo-cross-dismiss').click();
															parentContainer.location.reload();

														<?php } else { ?>

                                                            top.location.href = '<?php echo $site->base.'/order';?>';

														<?php } ?>
														
													<?php } else { ?>
														alert('<?php echo $option->uid?>' + ' - ' + '<?php echo $_REQUEST['date']?>' + ' added');
													<?php } ?>
												} else {
													let err = response.message;

													<?php if(!$site->config('REZGO_MOBILE_XML')) { ?>
														jQuery('#error_text_<?php echo $option_num.'_'.$sub_option?>').html(err);
														jQuery('#error_text_<?php echo $option_num.'_'.$sub_option?>').slideDown().delay(2000);
													<?php } else { ?>
														jQuery('#error_mobile_text_<?php echo $option_num.'_'.$sub_option?>').html(err);
														jQuery('#error_mobile_text_<?php echo $option_num.'_'.$sub_option?>').slideDown().delay(2000);
													<?php } ?>
												}

											},
											error: function(error){
												console.log(error);
											}
										});

									});
									
								}
							}

							<?php if ( (REZGO_WORDPRESS) && ($_REQUEST['cross_sell']) ) { ?>
								document.querySelector('#panel_<?php echo $option_num.'_'.$sub_option?>').addEventListener('click',function(){
									setTimeout(() => {
										document.querySelector('#panel_<?php echo $option_num.'_'.$sub_option?>').scrollIntoView({
												behavior :'smooth',
												block: "start",
												inline: "start"
											});
									}, 500);
								});
							<?php } ?>

						</script>
	
							<a data-toggle="collapse" data-parent="#rezgo-select-option-<?php echo $option_num.'_'.$sub_option?>" data-target="#option_<?php echo $option_num.'_'.$sub_option?>" class="panel-heading panel-title rezgo-panel-option-link" id="panel_<?php echo $option_num.'_'.$sub_option?>">

								<script>
									jQuery('#panel_<?php echo $option_num.'_'.$sub_option?>').click( function(){
										jQuery(this).find('i.fa-angle-right').toggleClass('active');
									});
								</script>

								<div class="rezgo-panel-option"><i class="fal fa-angle-right <?php echo (((count($options) == 1 && $option_num == 1) || $_REQUEST['id'] == (int) $option->uid) ? ' active' : '')?>" aria-hidden="true"></i> &nbsp; <?php echo $option->option?> 
								
								<?php if (!$site->exists($option->date->hide_availability)) { ?>
								
									<span class="rezgo-show-count">
									
									<?php if ($option->date->availability == 0) { ?>
									
									<span class="fa rezgo-full-dash"><span>&nbsp;&ndash;&nbsp;</span></span>
									<span class="rezgo-option-full"><span>full</span></span>
									
									<?php } else { ?>
									
									<span class="fa rezgo-option-dash"><span>&nbsp;&ndash;&nbsp;</span></span>
									<span class="rezgo-option-count"><?php echo (string) $option->date->availability?></span>
									<span class="rezgo-option-pax"><span>&nbsp;<?php echo ((int) $option->date->availability == 1 ? 'spot':'spots');?></span></span>
									
									<?php } ?>
									
									</span>	
									
								<?php } ?>
								
								</div>
							</a>
							<div id="option_<?php echo $option_num.'_'.$sub_option?>" class="option-panel-<?php echo $option->uid?> panel-collapse collapse<?php echo(((count($options) == 1 && $option_num == 1) || $_REQUEST['id'] == (int) $option->uid) ? ' in' : '')?>">
							<div class="panel-body">
								<?php if ($option->date->availability != 0) { 

									  if ($_REQUEST['cross_sell']) {
											$form_target = 'target="_parent"';
											$site->base = home_url('/', 'https').$_REQUEST['wp_slug'];
										} else {
											$form_target = 'target="rezgo_content_frame"';
										}  
									?>
									<span class="rezgo-option-memo rezgo-option-<?php echo $option->uid?> rezgo-option-date-<?php echo $_REQUEST['date']?>"></span>
									<form class="rezgo-order-form" method="post" id="checkout_<?php echo $option_num.'_'.$sub_option?>" <?php echo $form_target; ?>>
										<input type="hidden" name="add[0][uid]" value="<?php echo $option->uid?>" />
										<input type="hidden" name="add[0][date]" value="<?php echo $_REQUEST['date']?>" />
	
										<div class="row"> 
											<div class="col-xs-12 rezgo-order-fields">
												<?php if (!$site->exists($option->date->hide_availability)) { ?>
												<span class="rezgo-memo rezgo-availability"><strong>Availability:</strong> <?php echo ($option->date->availability == 0 ? 'full' : (string) $option->date->availability)?><br /></span>	
												<?php } ?>
								
												<?php if ($option->duration != '') { ?>
													<span class="rezgo-memo rezgo-duration"><strong>Duration:</strong> <?php echo (string) $option->duration;?><br /></span>	
												<?php } ?>
								
												<?php if ($option->time != '') { ?>
													<span class="rezgo-memo rezgo-time"><strong>Time:</strong> <?php echo (string) $option->time;?><br /></span>	
												<?php } ?>
	
												<?php $prices = $site->getTourPrices($option);	?>
	
												<?php if($site->getTourRequired() == 1) { ?>
													<span class="rezgo-memo">At least one marked ( <em><i class="fa fa-asterisk"></i></em> ) price point is required.</span>
												<?php } ?>
	
												<?php if($option->per > 1) { ?>
													<span class="rezgo-memo">At least <?php echo $option->per?> are required to book.</span>
												<?php } ?>
	
												<div class="clearfix">&nbsp;</div>
	
											<?php $total_required = 0; ?>
											<?php $animation_order = 1; ?>

											<?php foreach( $prices as $price ) { ?>
												<script>fields_<?php echo $option_num.'_'.$sub_option?>['<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>'] = <?php echo (($price->required) ? 1 : 0)?>;</script>

												<div class="edit-pax-wrp" style="--animation-order: <?php echo $animation_order?>;">
												<div class="edit-pax-label-container">
													<label for="<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>" class="control-label rezgo-pax-label rezgo-label-margin rezgo-label-padding-left">
														<?php echo $price->label?><?php echo (($price->required && $site->getTourRequired()) ? ' <em><i class="fa fa-asterisk"></i></em>' : '')?> 
													</label>
												</div>

												<div class="pax-price-container">
													<div class="form-group row pax-input-row left-col">

														<div class="edit-pax-container">
															<div class="minus-pax-container">
																<a id="decrease_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>" class="not-allowed" onclick="decreasePax_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>()">
																	<i class="fa fa-minus"></i>
																</a>
															</div>
															<div class="input-container">
																<input type="number" min="0" name="add[0][<?php echo $price->name?>_num]" value="<?php echo $_REQUEST[$price->name.'_num']?>" id="<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>" class="pax-input" value="0" min="0" placeholder="0">
															</div>
															<div class="add-pax-container">
																<a onclick="increasePax_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>()">
																	<i class="fa fa-plus"></i>
																</a>
															</div>	
														</div>
													</div>

													<div class="right-col">
														<div class="edit-pax-label-container">
															<label for="<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>" class="control-label rezgo-label-margin rezgo-label-padding-left">

																<!-- if both strike prices and discount exists, show the higher price -->
																<?php
																	$initial_price = (float) $price->price;
																	$strike_price = (float) $price->strike;
																	$discount_price = (float) $price->base;
																?>
																<span class="rezgo-pax-price">
																<?php if ( ($site->exists($price->strike)) && ($site->exists($price->base)) )  { ?>
																	<?php $show_this = max($strike_price, $discount_price); ?>

																	<span class="rezgo-strike-price">
																		<?php echo $site->formatCurrency($show_this)?>
																	</span><br>
																		
																<?php } else if(!$site->isVendor() && $site->exists($price->strike)) { ?>

																		<!-- show only if strike price is higher -->
																		<?php if ($strike_price >= $initial_price) { ?>
																			<span class="rezgo-strike-price">
																				<?php echo $site->formatCurrency($strike_price)?>
																			</span><br>
																		<?php } ?>
																		<span class="rezgo-strike-extra"><span>

																<?php } else if($site->exists($price->base)) { ?>

																		<span class="discount">
																			<?php echo $site->formatCurrency($price->base)?>
																		</span><br>

																<?php } ?>

																	<?php echo $site->formatCurrency($price->price)?>
																</span>
															</label>
														</div>
													</div>
		
													<?php if ($price->required) $total_required++; ?>

													<script>
													// prepare values insert in addCart() request 
													jQuery('#<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').change(function(){
														<?php echo $price->name?>_num = $(this).val();
														if ($(this).val() <= 0) {
															jQuery('#decrease_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').addClass('not-allowed');
														} else {
															jQuery('#decrease_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').removeClass('not-allowed');
														}
													});

													function increasePax_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>(){
															let value = parseInt(document.getElementById('<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').value);
															value = isNaN(value) ? 0 : value;
															value++;
															if (value > 0) { 
																jQuery('#decrease_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').removeClass('not-allowed');
															}
															document.getElementById('<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').value = value;
														}

													function decreasePax_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>(){
															let value = parseInt(document.getElementById('<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').value);
															value = isNaN(value) ? 0 : value;
															if (value <= 0) {
																return false;
															}
															value--;
															if (value <= 0) {
																jQuery('#decrease_<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').addClass('not-allowed');
															} 
															document.getElementById('<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>').value = value;
														}

												</script>

											</div>
											</div>
								
											<?php $animation_order++; }
											// end foreach( $site->getTourPrices() ?>
	
												<script>required_num_<?php echo $option_num.'_'.$sub_option?> = <?php echo $total_required?>;</script>
												
												<?php
												
													$bundles = $site->getTourBundles($option);	
													
													if (count($bundles) > 0) { ?>
														
														<?php
														$b = 0;
														
														foreach ($bundles as $bundle) {
															
															if ((int) $bundle->visible !== 0 && $option->date->availability >= $bundle->total) {
															
															?>
															
															<script>fields_<?php echo $option_num.'_'.$sub_option?>['<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>'] = 0;</script>
															<div class="edit-pax-wrp" style="--animation-order: <?php echo $animation_order?>;">
																<div class="edit-pax-label-container">
																	<label for="<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>" class="control-label rezgo-pax-label rezgo-label-margin rezgo-label-padding-left">
																		<?php echo $bundle->name?> <br> <span class="rezgo-bundle-makeup">- includes <?php echo $bundle->makeup?></span>
																	</label>
																</div>

																<div class="pax-price-container">
																	<div class="form-group row rezgo-bundle-hidden pax-input-row left-col">
																		<div class="edit-pax-container">
																			<div class="minus-pax-container">
																				<a id="decrease_<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>" class="not-allowed" onclick="decreaseBundle_<?php echo $option_num.'_'.$sub_option.'_'.$b?>()">
																					<i class="fa fa-minus"></i>
																				</a>
																			</div>
																			<div class="input-container">
																				<input type="number" min="0" name="" value="" id="<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>" rel="bundle" class="pax-input" value="0" min="0" placeholder="0">
																			</div>
																			<div class="add-pax-container">
																				<a onclick="increaseBundle_<?php echo $option_num.'_'.$sub_option.'_'.$b?>()">
																					<i class="fa fa-plus"></i>
																				</a>
																			</div>	
																		</div>	
																	</div>

																	<div class="right-col">
																		<div class="edit-pax-label-container rezgo-bundle-hidden">
																			<label for="<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>" class="control-label rezgo-label-margin rezgo-label-padding-left">
																				<span class="rezgo-pax-price"><?php echo $site->formatCurrency($bundle->price)?></span><br />
																			</label>
																		</div>
																	</div>
																	
																		<?php
																			foreach ($bundle->prices as $p => $c) {
																				echo '<input type="hidden" name="add[0]['.$p.'_num]" rel="'.$p.'_'.$option_num.'_'.$sub_option.'" value="" data-multiple="'.$c.'" class="'.$bundle->label.'_'.$option_num.'_'.$sub_option.'" disabled />'; ?>
																				
																				<script>
																					// copy over amt of price points in bundle
																					jQuery('#<?php echo $bundle->label;?>_<?php echo $option_num.'_'.$sub_option;?>').attr('data-<?php echo $p;?>_num' , <?php echo $c;?> );
																				</script>
																		<?php	}
																		?>
																</div>
															</div>


															<script>
																jQuery('#<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>').change(function(){
																	if (jQuery(this).val() <= 0) {
																		jQuery('#decrease_<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>').addClass('not-allowed');
																	} else {
																		jQuery('#decrease_<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>').removeClass('not-allowed');
																	}
																});

																function increaseBundle_<?php echo $option_num.'_'.$sub_option.'_'.$b?>(){
																	let value = parseInt(document.getElementById('<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>').value);
																	value = isNaN(value) ? 0 : value;
																	value++;
																	if (value > 0) {
																		jQuery('#decrease_<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>').removeClass('not-allowed');
																	}
																	document.getElementById('<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>').value = value;
																}

																function decreaseBundle_<?php echo $option_num.'_'.$sub_option.'_'.$b?>(){
																	let value = parseInt(document.getElementById('<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>').value);
																	value = isNaN(value) ? 0 : value;
																	if (value <= 0) {
																		return false;
																	}
																	value--;
																	if (value <= 0) {
																		jQuery('#decrease_<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option.'_'.$b?>').addClass('not-allowed');
																	} 
																	document.getElementById('<?php echo $bundle->label?>_<?php echo $option_num.'_'.$sub_option?>').value = value;
																}

															</script>
															
															<?php
															
															$b++;
													
															} // if ($bundle->visible)
															
														} // foreach ($bundles)
														
													} // if (count($bundles))
													
													
													if ($b >= 1) {
														echo "<script> jQuery('#option_".$option_num."_".$sub_option." .rezgo-bundle-hidden').show();</script>";
														echo "<script> jQuery('#option_".$option_num."_".$sub_option." .pax-input-row').css('display','flex');</script>";
													}
																						
												?>
	

												<div class="text-danger rezgo-option-error" id="error_text_<?php echo $option_num.'_'.$sub_option;?>" style="display:none;"></div>
												<div class="text-danger rezgo-option-error" id="error_mobile_text_<?php echo $option_num.'_'.$sub_option;?>" style="display:none;"></div>
											</div><!-- end col-sm-8-->
	
											<div class="col-lg-8 col-md-9 col-xs-12 pull-right">
												<button type="submit" class="btn btn-block rezgo-btn-book btn-lg rezgo-btn-add" onclick="return check_<?php echo $option_num.'_'.$sub_option?>();">Add to Order</button>
											</div>
										</div>
									</form>
																	
								<?php } else { ?>
									<div class="rezgo-order-unavailable"><span>Sorry, there is no availability for this option</span></div>
								<?php } // end if ($option->date->availability != 0) ?>
							</div>
						</div>
					</div>
	
					<?php $sub_option++; // increment sub option instead ?>
				<?php } // if ($_REQUEST['type']) ?>
      
    
    <?php } // end foreach($options as $option) ?>
	</div>
  
<?php } else { // no availability, hide this option ?>
	<?php echo $availability_title?>
  <div class="panel panel-default panel-none-available">
    <div class="panel-body">
      <div class="rezgo-order-none-available"><span>Sorry, there are no available options on this day</span></div>
    </div>
  </div>
<?php } ?>

<?php
	if ($_SESSION['debug']) {
		echo '<script>
		// output debug to console'."\n\n";
		foreach ($_SESSION['debug'] as $debug) {
			echo "window.console.log('".$debug."'); \n";
		}
		unset($_SESSION['debug']);
		echo '</script>';
	}
?>
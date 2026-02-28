<?php
	// handle old-style booking requests
	if($_REQUEST['uid'] && $_REQUEST['date']) {
		$for_array = array('adult', 'child', 'senior', 'price4', 'price5', 'price6', 'price7', 'price8', 'price9');
		$new_header = '/book_new?order=clear&add[0][uid]='.$_REQUEST['uid'].'&add[0][date]='.$_REQUEST['date'];
		foreach($for_array as $v) {
			if($_REQUEST[$v.'_num']) $new_header .= '&add[0]['.$v.'_num]='.$_REQUEST[$v.'_num'];
		}
		$site->sendTo($new_header);
	}

	$company = $site->getCompanyDetails();
	// non-open date date_selection elements
	$date_types = array('always', 'range', 'week', 'days', 'single'); // centralize this?

?>

<link rel="stylesheet" href="<?php echo $site->path?>/css/pretty-checkbox.min.css">
<link rel="stylesheet" href="<?php echo $site->path?>/css/chosen.min.css">

<script>

var elements = new Array();
var split_total = new Array();
	var overall_total = '0';
	var modified_total = '0';

// MONEY FORMATTING
const form_symbol = '$';
const form_decimals = '2';
const form_separator = ',';
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
</script>	

	<div id="rezgo-book-wrp" class="container-fluid rezgo-container">
					<div class="tab-content">
						<div id="rezgo-book-step-one" class="tab-pane active">
							<div class="jumbotron rezgo-booking">
								<div id="rezgo-order-crumb" class="row">
									<ol class="breadcrumb rezgo-breadcrumb">
									<?php // check for cart token, add to order link to preserve cart data 
											$cart_token = $_COOKIE['rezgo_cart_token_'.REZGO_CID]; ?>
										<li id="rezgo-book-step-one-your-order" class="rezgo-breadcrumb-order">
											<a class="link" href="<?php echo $site->base?>/order/<?php echo $cart_token?>">
												<span class="default">Order</span>
												<span class="custom"></span>
											</a>
										</li>
										<li id="rezgo-book-step-one-info" class="rezgo-breadcrumb-info active"><span class="default">Guest Information</span><span class="custom"></span></li>
										<li id="rezgo-book-step-one-billing" class="rezgo-breadcrumb-billing"><span class="default">Payment</span><span class="custom"></span></li>
										<li id="rezgo-book-step-one-confirmation" class="rezgo-breadcrumb-confirmation"><span class="default">Confirmation</span><span class="custom"></span></li>
									</ol>
								</div>
							<?php
							$complete_booking_total = 0;
							$c = 0;
							$first_index = 1; // only for the first instances of pax inputs
							$cart = $site->getCart(1); // get the cart, remove any dead entries
							$lead_passenger = $site->getLeadPassenger(); // get lead passenger details
							$cart_data = $site->getFormData();

							if(!count($cart)) {
								$site->sendTo($site->base);
							}
							$cart_count = count($cart);
							?>

							<div class="flex-container book-page-container">

								<div class="pax-info-container">

									<form id="rezgo-guest-form" role="form" method="post" target="rezgo_content_frame">

										<div class="lead-passenger-form-group rezgo-form-group">
											<h3 class="lead-passenger-header rezgo-item-title">Booking Contact</h3>
											<br>
											<div class="rezgo-form-row form-group">
												<div class="col-sm-6 rezgo-form-input">
													<label for="lead_passenger_first_name" class="col-sm-2 control-label rezgo-label-right">
														<span>First Name <em class ="fa fa-asterisk"></em></span>
													</label>
													<input type="text" class="form-control required lead-passenger-input" id="lead_passenger_first_name" name="lead_passenger_first_name" value="<?php echo $lead_passenger['first_name'];?>">
												</div>

												<div class="col-sm-6 rezgo-form-input lead-passenger-lname-group">
													<label for="lead_passenger_last_name" class="col-sm-2 control-label rezgo-label-right">
														<span>Last Name <em class="fa fa-asterisk"></em></span>
													</label>
													<input type="text" class="form-control required lead-passenger-input" id="lead_passenger_last_name" name="lead_passenger_last_name" value="<?php echo $lead_passenger['last_name'];?>">
												</div>
											</div>

											<div class="rezgo-form-row form-group">
												<div class="col-sm-12 rezgo-form-input">
													<label for="lead_passenger_email" class="col-sm-2 control-label rezgo-label-right">
														<span>Email <em class="fa fa-asterisk"></em></span>
													</label>
													<input type="email" class="form-control required lead-passenger-input" id="lead_passenger_email" name="lead_passenger_email" value="<?php echo $lead_passenger['email'];?>">
														<span class="email-note">Booking confirmation will be sent to this email</span> 
												</div>
											</div>
										</div>
										<hr>


								<?php // start cart loop for each tour in the order ?>
								<?php foreach($cart as $item) { ?>
									<?php
										$required_fields = 0;
										$site->readItem($item);
									?>
									
									<?php if((int) $item->availability >= (int) $item->pax_count) { ?>
										<?php $c++; // only increment if it's still available ?>
										
										<div id="rezgo-book-step-one-item-<?php echo $item->uid?>">

											<div class="rezgo-booking-title-wrp">
												<h3 class="rezgo-booking-title rezgo-sub-title" id="booking_title_<?php echo $c?>">

												<span>Booking <?php echo $c?> of </span>
													<span class="rezgo-cart-count"></span>
												<span>&nbsp;</span>

												</h3>
												<h3 class="rezgo-item-title"><?php echo $item->item?> &mdash; <?php echo $item->option?></h3>

												<?php if(in_array((string) $item->date_selection, $date_types)) { ?>
													<?php $data_book_date = date("Y-m-d", (string)$item->booking_date); ?>
													<label>Date: <span class="lead"><?php echo date((string) $company->date_format, (string) $item->booking_date)?></span></label> 
												<?php } else { ?>
													<?php $data_book_date = date('Y-m-d', strtotime('+1 day')); // open date ?>
													<label><span class="lead"> Open Availability </span></label>
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
												
												<input type="hidden" name="booking[<?php echo $c?>][index]" value=<?php echo $c-1?>>
												<input type="hidden" name="booking[<?php echo $c?>][uid]" value=<?php echo $item->uid?>>
												<input type="hidden" name="booking[<?php echo $c?>][date]" value="<?php echo $data_book_date?>">
											</div>

												<div class="row rezgo-booking-instructions">
													<span> To complete this booking, please fill out the following form.</span>

													<?php foreach($site->getTourForms('primary') as $form) { if($form->require) $primary_required_fields[$c]++; } ?>
													<?php foreach($site->getTourForms('group') as $form) { if($form->require) $group_required_fields[$c]++; } ?>

													<span <?php if($item->group == 'require' || $item->group == 'require_name' || $primary_required_fields[$c] ||$group_required_fields[$c])  { echo ' style="display:inline-block;"'; } else { echo ' style="display:none;"'; } ?>>
														<span id="required_note-<?php echo $c?>" >Please note that fields marked with <em class="fa fa-asterisk"></em> are required.</span>
													</span>
												</div>

												<?php if($site->getTourForms('primary')) { ?>

													<?php 
														// match form index key with form value (prevent mismatch if form is set to BE only)
														$cart_pf[$c-1] = $cart_data[$c-1]->primary_forms->form;
														if ($cart_pf[$c-1]) {
															foreach ($cart_pf[$c-1] as $k => $v) {
																$cart_pf_val[$c-1][(int)$v->num]['value'] = $v->value;
															}
														}
													?>

													<div class="row rezgo-form-group rezgo-additional-info primary-forms-container">
														<div class="col-sm-12 rezgo-sub-title form-sectional-header">
															<span>Additional Information</span>
														</div>

													<div class="clearfix rezgo-short-clearfix">&nbsp;</div>

														<?php foreach($site->getTourForms('primary') as $form) { ?>
															<?php if($form->require) $required_fields++; ?>

															<?php if($form->type == 'text') { ?>
																<div class="form-group rezgo-custom-form rezgo-form-input">
																	<label class="control-label"><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></label>
																	<input 
																		id="text-<?php echo $c?>_<?php echo $form->id?>" 
																		type="text" class="form-control<?php echo ($form->require) ? ' required' : ''; ?>" 
																		name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" 
																		value="<?php echo $cart_pf_val[$c-1][(int)$form->id]['value']?>">
																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>
																</div>
															<?php } ?>

															<?php if($form->type == 'select') { ?>
																<div class="form-group rezgo-custom-form rezgo-form-input">
																	<label class="control-label"><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></label>
																	<select id="select-<?php echo $c?>_<?php echo $form->id?>" class="chosen-select form-control<?php echo ($form->require) ? ' required' : ''; ?> rezgo-custom-select" name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]">
																		<option value=""></option>
																		<?php foreach($form->options as $option) { ?>
																			<option><?php echo $option?></option>
																		<?php } ?>
																	</select>
																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>
																	<?php
																		if ($form->options_instructions) {
																				$optex_count = 1;
																				foreach($form->options_instructions as $opt_extra) {
																				echo '<span class="opt_extra" id="optex_'.$optex_count.'" style="display:none">'.$opt_extra.'</span>';
																				$optex_count++;
																			}
																		}
																	?>
																	<input type="hidden" value='' name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" data-addon="select_primary-<?php echo $c?>_<?php echo $form->id?>_hidden">
																</div>
																<script>
																	jQuery('#select-<?php echo $c?>_<?php echo $form->id?>').change(function(){
																		jQuery(this).valid();

																		if (jQuery(this).val() == ''){
																			jQuery("input[data-addon='select_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																		} else {
																			jQuery("input[data-addon='select_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		}

																	});
																	let select_primary_<?php echo $c?>_<?php echo $form->id?> = "<?php echo addslashes(html_entity_decode($cart_pf_val[$c-1][(int)$form->id]['value'], ENT_QUOTES))?>";

																	if (select_primary_<?php echo $c?>_<?php echo $form->id?> != ''){
																		jQuery("input[data-addon='select_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		jQuery('#select-<?php echo $c?>_<?php echo $form->id?>').val(select_primary_<?php echo $c?>_<?php echo $form->id?>).trigger('chosen:updated');

																		let select_pf_options_<?php echo $c?>_<?php echo $form->id?> = document.getElementById('select-<?php echo $c?>_<?php echo $form->id?>').options;
																		for (i=0, len = select_pf_options_<?php echo $c?>_<?php echo $form->id?>.length; i<len; i++) {
																			let opt = select_pf_options_<?php echo $c?>_<?php echo $form->id?>[i];
																			if (opt.selected) {
																				jQuery('#select-<?php echo $c?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).show();
																			} else {
																				jQuery('#select-<?php echo $c?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).hide();
																			}
																		}
																	}
																</script>

															<?php } ?>

															<?php if($form->type == 'multiselect') { ?>
																<div class="form-group rezgo-custom-form rezgo-form-input">
																	<label class="control-label"><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></label>
																	
																	<select id="rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>" class="chosen-select form-control<?php echo ($form->require) ? ' required' : ''; ?> rezgo-custom-select" multiple="multiple" name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>][]">
																		<option value=""></option>
																		<?php foreach($form->options as $option) { ?>
																			<option><?php echo $option?></option>
																		<?php } ?>
																	</select>
																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>

																	<?php
																		if ($form->options_instructions) {
																				$optex_count = 1;
																				foreach($form->options_instructions as $opt_extra) {
																				echo '<span class="opt_extra" id="optex_'.$optex_count.'" style="display:none">'.$opt_extra.'</span>';
																				$optex_count++;
																			}
																		}
																	?>
																	<input type="hidden" value='' name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>][]" data-addon="multiselect_primary-<?php echo $c?>_<?php echo $form->id?>_hidden">
																</div>
																<script>
																	jQuery('#rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>').change(function(){
																		jQuery(this).valid();

																		if (jQuery(this).val() === null){
																			jQuery("input[data-addon='multiselect_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																		} else {
																			jQuery("input[data-addon='multiselect_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		}
																	});
																	let multiselect_primary_<?php echo $c?>_<?php echo $form->id?> = "<?php echo addslashes(html_entity_decode($cart_pf_val[$c-1][(int)$form->id]['value'], ENT_QUOTES))?>";

																	if (multiselect_primary_<?php echo $c?>_<?php echo $form->id?>.length > 1){
																		jQuery("input[data-addon='multiselect_primary-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		multiselect_primary_<?php echo $c?>_<?php echo $form->id?> =  multiselect_primary_<?php echo $c?>_<?php echo $form->id?>.split(', ');

																		jQuery('#rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>').val(multiselect_primary_<?php echo $c?>_<?php echo $form->id?>).trigger('chosen:updated');

																		let multiselect_pf_options_<?php echo $c?>_<?php echo $form->id?> = document.getElementById('rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>').options;
																		for (i=0, len = multiselect_pf_options_<?php echo $c?>_<?php echo $form->id?>.length; i<len; i++) {
																			let opt = multiselect_pf_options_<?php echo $c?>_<?php echo $form->id?>[i];
																			if (opt.selected) {
																				jQuery('#rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).show();
																			} else {
																				jQuery('#rezgo-custom-select-<?php echo $c?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).hide();
																			}
																		}
																	}
																</script>
															<?php } ?>

															<?php if($form->type == 'textarea') { ?>
																<div class="form-group rezgo-custom-form rezgo-form-input">
																	<label class="control-label"><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></label>
																	<textarea id="textarea-<?php echo $c?>_<?php echo $form->id?>" class="form-control<?php echo ($form->require) ? ' required' : ''; ?>" name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" cols="40" rows="4"><?php echo $cart_pf_val[$c-1][(int)$form->id]['value']?></textarea>
																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>
																</div>
															<?php } ?>

															<?php if($form->type == 'checkbox') { ?>
																<div class="rezgo-pretty-checkbox-container">

																	<div class="rezgo-form-group rezgo-custom-form rezgo-form-input rezgo-pretty-checkbox">
																		<div class="pretty p-default p-curve p-smooth">

																			<input type="checkbox"<?php echo ($form->require) ? ' class="required"' : ''; ?> 
																				id="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>" 
																				name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" 
																				data-addon="checkbox-<?php echo $c?>_<?php echo $form->id?>">

																			<div class="state p-warning">
																				<label for="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>"><span><?php echo $form->title?></span>
																				<?php if ($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?>
																				<?php if ($form->price) { ?> <em class="price"><?php echo $form->price_mod?> <?php echo $site->formatCurrency($form->price)?></em><?php } ?></label>
																			</div>
																		</div>

																		<input type='hidden' value='off' name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" data-addon="checkbox-<?php echo $c?>_<?php echo $form->id?>_hidden">
																	</div>

																<?php if ($form->instructions){ ?>
																	<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																<?php } ?>

																</div>
																
																<script>
																	jQuery("input[data-addon='checkbox-<?php echo $c?>_<?php echo $form->id?>']").change(function(){
																		if (jQuery(this).is(":checked")){
																			jQuery("input[data-addon='checkbox-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		} else {
																			jQuery("input[data-addon='checkbox-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																		}
																	});

																	<?php if ($cart_pf_val[$c-1][(int)$form->id]['value'] == 'on'){ ?>
																		jQuery("input[data-addon='checkbox-<?php echo $c?>_<?php echo $form->id?>']").prop('checked', true);
																		jQuery("input[data-addon='checkbox-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																	<?php } ?>
																</script>
															<?php } ?>

															<?php if($form->type == 'checkbox_price') { ?>
															
																<div class="rezgo-pretty-checkbox-container">
																	<div class="rezgo-form-group rezgo-custom-form rezgo-form-input rezgo-pretty-checkbox">
																		<div class="pretty p-default p-curve p-smooth">

																			<input type="checkbox" <?php echo ($form->require) ? ' class="required"' : ''; ?>
																				id="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>" 
																				name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" data-addon="checkbox_price-<?php echo $c?>_<?php echo $form->id?>">
																			<div class="state p-warning">
																				<label for="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>"><span><?php echo $form->title?></span>
																					<?php if ($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?>
																					<?php if ($form->price) { ?> <em class="price"><?php echo $form->price_mod?> <?php echo $site->formatCurrency($form->price)?></em><?php } ?>
																				</label>
																			</div>
																		</div>

																		<input type='hidden' value='off' name="booking[<?php echo $c?>][tour_forms][<?php echo $form->id?>]" data-addon="checkbox_price-<?php echo $c?>_<?php echo $form->id?>_hidden">
																	</div>

																<?php if ($form->instructions){ ?>
																	<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																<?php } ?>
																</div>

																<script>
																	jQuery("input[data-addon='checkbox_price-<?php echo $c?>_<?php echo $form->id?>']").change(function(){
																		if (jQuery(this).is(":checked")){
																			jQuery("input[data-addon='checkbox_price-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																		} else {
																			jQuery("input[data-addon='checkbox_price-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																		}
																	});

																	<?php if ($cart_pf_val[$c-1][(int)$form->id]['value'] == 'on'){ ?>
																		jQuery("input[data-addon='checkbox_price-<?php echo $c?>_<?php echo $form->id?>']").prop('checked', true);
																		jQuery("input[data-addon='checkbox_price-<?php echo $c?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																	<?php } ?>
																</script>
															<?php } ?>
														
															<?php $form_counter++; ?>
														<?php } // end foreach($site->getTourForms('primary') ?>
													</div>
												<?php } ?>
											
											<?php if($item->group == 'hide' && count ($site->getTourForms('primary')) == 0) { ?>
												<div class='rezgo-guest-info-not-required'>
													<span>Guest information is not required for booking #<?php echo $c?></span>
												</div>
											<?php } // end if getTourForms('primary') ?>

											<?php if($required_fields > 0) { ?>
												<script>jQuery(document).ready(function($){$('#required_note-<?php echo $c?>').fadeIn();});</script>
											<?php } ?>
						
											<?php if ($item->pick_up_locations) { ?>
												<div class="row rezgo-form-group rezgo-additional-info">
												<div class="col-sm-12 rezgo-sub-title form-sectional-header">
													<span>Transportation</span>
												</div>

												<div class="clearfix rezgo-short-clearfix">&nbsp;</div>
												
												<?php $pickup_locations = $site->getPickupList((int) $item->uid); ?>
												
												<div class="form-group rezgo-custom-form rezgo-form-input">
															
													<label id="rezgo-choose-pickup"><span>Choose your pickup location</span></label>
													<select id="rezgo-pickup-select-<?php echo $c?>" class="chosen-select form-control rezgo-pickup-select" name="booking[<?php echo $c?>][pickup]" data-target="rezgo-pickup-detail-<?php echo $c?>" data-id="<?php echo $c?>" data-counter="<?php echo $form_counter?>" data-option="<?php echo $item->uid?>" data-pax="<?php echo $item->pax?>">
													<option value="" data-cost="0" id="last-picked-<?php echo $c?>"></option>
														<?php
															
															foreach($pickup_locations->pickup as $pickup) {
																
																$cost = ((int) $pickup->cost > 0) ? ' ('.$site->formatCurrency($pickup->cost).')' : ''; 
														
																if($pickup->sources) { 
																
																	echo '<optgroup label="Pickup At: '.$pickup->name.' - '.$pickup->location_address.$cost.'">'."\n";
																		
																	$s=0;
																	foreach($pickup->sources->source as $source) {
																		echo '<option value="'.$pickup->id.'-'.$s.'" data-cost="'.($item->pax*$pickup->cost).'">'.$source->name.'</option>'."\n";
																		$s++;
																	}
																	echo '</optgroup>'."\n";
																	
																} else { 
																	echo '<option value="'.$pickup->id.'" data-cost="'.($item->pax*$pickup->cost).'">'.$pickup->name.' - '.$pickup->location_address.$cost.'</option>'."\n";
																} 
																
															}
														
														?>
													</select>
													<script>
														// needed for deselect option?
														jQuery("#rezgo-pickup-select-<?php echo $c?>").chosen({allow_single_deselect:true});

														jQuery("#rezgo-pickup-select-<?php echo $c?>").chosen().change(function() {
															pickup_cost_<?php echo $c?> = jQuery(this).find('option:selected').data('cost');
															pickup_id_<?php echo $c?> = jQuery(this).val();

															if (jQuery(this).find('option:selected').data('cost') != 0){
																jQuery('#last-picked-<?php echo $c?>').data('cost', pickup_cost_<?php echo $c?>*-1);
															}
															else{
																pickup_cost_<?php echo $c?> = jQuery('#last-picked-<?php echo $c?>').data('cost');
															}
														});

														// if there is existing pickup
														<?php if ( ($site->exists($cart_data[$c-1]->pickup)) && ($cart_data[$c-1]->pickup !=0) ){ ?>

															<?php if (!$site->exists($cart_data[$c-1]->pickup_source)) { ?> 

																jQuery('#rezgo-pickup-select-<?php echo $c?>').val(<?php echo $cart_data[$c-1]->pickup?>).trigger('chosen:updated');

															<?php } else { ?>

																// if there is pickup source, trigger the optgroup select
																jQuery("#rezgo-pickup-select-<?php echo $c?> optgroup option[value='<?php echo $cart_data[$c-1]->pickup?>-<?php echo $cart_data[$c-1]->pickup_source?>']").attr("selected","selected").trigger('chosen:updated');

															<?php } ?>

															let pax_num_<?php echo $c?> = jQuery('#rezgo-pickup-select-<?php echo $c?>').data('pax');
															let option_id_<?php echo $c?> = jQuery('#rezgo-pickup-select-<?php echo $c?>').data('option');
															let book_id_<?php echo $c?> = jQuery('#rezgo-pickup-select-<?php echo $c?>').data('id');
															let pickup_id_exists_<?php echo $c?> = '<?php echo $cart_data[$c-1]->pickup?>' + '<?php echo ($site->exists($cart_data[$c-1]->pickup_source)) ? "-".$cart_data[$c-1]->pickup_source :  ""?>';

															jQuery.ajax({
																url: "<?php echo admin_url('admin-ajax.php'); ?>" + '?action=rezgo&method=pickup_ajax&pickup_id=' + pickup_id_exists_<?php echo $c?> + '&option_id=' + option_id_<?php echo $c?> + '&book_id=' + book_id_<?php echo $c?> + '&pax_num=' + pax_num_<?php echo $c?> + '', 
																data: { rezgoAction: 'item'},
																context: document.body,
																success: function(data) {			
																	jQuery('#rezgo-pickup-detail-<?php echo $c?>').fadeOut().html(data).fadeIn('fast'); 
																}
															});	
														<?php } ?>
													</script>

													<?php $form_counter++; ?>
													</div>

													<div class="outer-container" style="margin-bottom: -15px;">
														<div id="rezgo-pickup-detail-<?php echo $c?>" class="rezgo-pickup-detail"></div>
													</div>

												</div>   
											<?php } ?>                                  
											
											<span class="rezgo-booking-memo rezgo-booking-memo-<?php echo $item->uid?>"></span>

											<?php if($item->group != 'hide') { ?>

												<?php foreach($site->getTourPrices($item) as $price) { ?>
													<?php foreach($site->getTourPriceNum($price, $item) as $num) { ?>
														<div class="row rezgo-form-group rezgo-additional-info">
															<div class="rezgo-sub-title form-sectional-header">
																<span><?php echo $price->label?> (<?php echo $num?>)</span>
															</div>

															<?php // create unique id for each entry
															$guest_uid = $c.'_'.$price->name.'_'.$num; ?>

															<div class="rezgo-form-row rezgo-form-one form-group rezgo-pax-first-last rezgo-first-last-<?php echo $item->uid?>">
																<div class="col-sm-6 rezgo-form-input">
																	<label for="frm_<?php echo $guest_uid?>_first_name" class="col-sm-2 control-label rezgo-label-right">
																		<span>First&nbsp;Name<?php if($item->group == 'require' || $item->group == 'require_name') { ?>&nbsp;<em class="fa fa-asterisk"></em><?php } ?></span>
																	</label>
																	<input type="text" 
																		class="form-control<?php echo ($item->group == 'require' || $item->group == 'require_name') ? ' required' : ''; ?> first_name_<?php echo $c?>_<?php echo $num?>" 
																		data-index="<?php echo ($c==1) ? 'fname_from_'.$num : 'fname_to_'.$num; ?>" 
																		id="frm_<?php echo $guest_uid?>_first_name" name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][first_name]" 
																		value="<?php echo $cart_data[$c-1]->tour_group->{$price->name}[$num-1]->first_name?>">
																</div>

																<div class="col-sm-6 rezgo-form-input">
																	<label for="frm_<?php echo $guest_uid?>_last_name" class="col-sm-2 control-label rezgo-label-right">
																		<span>Last&nbsp;Name<?php if($item->group == 'require' || $item->group == 'require_name') { ?>&nbsp;<em class="fa fa-asterisk"></em><?php } ?></span>
																	</label>
																	<input type="text" 
																		class="form-control<?php echo ($item->group == 'require' || $item->group == 'require_name') ? ' required' : ''; ?> last_name_<?php echo $c?>" 
																		data-index="<?php echo ($c==1) ? 'lname_from_'.$num : 'lname_to_'.$num; ?>" 
																		id="frm_<?php echo $guest_uid?>_last_name" name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][last_name]" 
																		value="<?php echo $cart_data[$c-1]->tour_group->{$price->name}[$num-1]->last_name?>">
																</div>
															</div>

															<?php if($item->group != 'request_name') { ?>
																<div class="rezgo-form-row rezgo-form-one form-group rezgo-pax-phone-email rezgo-phone-email-<?php echo $item->uid?>">
																	
																	<div class="col-sm-6 rezgo-form-input">
																		<label for="frm_<?php echo $guest_uid?>_phone" class="col-sm-2 control-label rezgo-label-right">Phone<?php if($item->group == 'require') { ?>&nbsp;<em class="fa fa-asterisk"></em><?php } ?></label>
																		<input type="text" 
																			class="form-control<?php echo ($item->group == 'require') ? ' required' : ''; ?>" 
																			data-index="<?php echo ($c==1) ? 'phone_from_'.$num : 'phone_to_'.$num; ?>" 
																			id="frm_<?php echo $guest_uid?>_phone" 
																			name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][phone]"
																			value="<?php echo $cart_data[$c-1]->tour_group->{$price->name}[$num-1]->phone?>">
																	</div>

																	<div class="col-sm-6 rezgo-form-input">
																		<label for="frm_<?php echo $guest_uid?>_email" class="col-sm-2 control-label rezgo-label-right">Email<?php if($item->group == 'require') { ?>&nbsp;<em class="fa fa-asterisk"></em><?php } ?></label>
																		<input type="email" 
																			class="form-control<?php echo ($item->group == 'require') ? ' required' : ''; ?>" 
																			data-index="<?php echo ($c==1) ? 'email_from_'.$num : 'email_to_'.$num; ?>" 
																			id="frm_<?php echo $guest_uid?>_email" 
																			name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][email]" 
																			value="<?php echo $cart_data[$c-1]->tour_group->{$price->name}[$num-1]->email?>">
																	</div>
																</div>

															<?php } ?>

															<?php $form_counter = 1; // form counter to create unique IDs ?>

															<?php foreach( $site->getTourForms('group') as $form ) { ?>

																<?php 
																	// match form index key with form value (prevent mismatch if form is set to BE only)
																	$cart_gf[$c-1] = $cart_data[$c-1]->tour_group->{$price->name}[(int) $num-1]->forms->form;
																	if ($cart_gf[$c-1]) {
																		foreach ($cart_gf[$c-1] as $k => $v) {
																			$cart_gf_val[$c-1][(int)$v->num]['value'] = $v->value;
																		}
																	}
																?>

																<?php if($form->require) $required_fields++; ?>

																<?php if($form->type == 'text') { ?>
																	
																	<div class="form-group rezgo-custom-form rezgo-form-input">
																		<label class="control-label"><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></label>

																		<input 
																			id="text-<?php echo $guest_uid?>" 
																			type="text" 
																			class="form-control<?php echo ($form->require) ? ' required' : ''; ?> " 
																			name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]" 
																			value="<?php echo $cart_gf_val[$c-1][(int)$form->id]['value']?>">

																		<?php if ($form->instructions){ ?>
																			<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																		<?php } ?>
																	</div>
																<?php } ?>

																<?php if($form->type == 'select') { ?>
																	
																	<div class="form-group rezgo-custom-form rezgo-form-input">
																		<label class="control-label"><span><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></span></label>

																		<select id="select-<?php echo $guest_uid?>_<?php echo $form->id?>" class="chosen-select form-control<?php echo ($form->require) ? ' required' : ''; ?> rezgo-custom-select" name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]">
																			<option value=""></option>
																			<?php foreach($form->options as $option) { ?>
																				<option><?php echo $option?></option>
																			<?php } ?>
																		</select>

																		<?php if ($form->instructions){ ?>
																			<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																		<?php } ?>
																		<?php if ($form->options_instructions) {
																			$optex_count = 1;
																			foreach($form->options_instructions as $opt_extra) {
																				echo '<span class="opt_extra" id="optex_'.$optex_count.'" style="display:none">'.$opt_extra.'</span>';
																				$optex_count++;
																			}
																		}
																		?>
																		<input type="hidden" value='' name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]" data-addon="select_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden">
																	</div> 
																	<script>
																		jQuery('#select-<?php echo $guest_uid?>_<?php echo $form->id?>').change(function(){
																			jQuery(this).valid();

																			if (jQuery(this).val() == ''){
																				jQuery("input[data-addon='select_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																			} else {
																				jQuery("input[data-addon='select_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																			}
																		});

																		let select_group_<?php echo $guest_uid?>_<?php echo $form->id?> = "<?php echo addslashes(html_entity_decode($cart_gf_val[$c-1][(int)$form->id]['value'], ENT_QUOTES))?>";

																		if (select_group_<?php echo $guest_uid?>_<?php echo $form->id?> != ''){
																			jQuery("input[data-addon='select_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																			jQuery('#select-<?php echo $guest_uid?>_<?php echo $form->id?>').val(select_group_<?php echo $guest_uid?>_<?php echo $form->id?>).trigger('chosen:updated');

																			let select_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?> = document.getElementById('select-<?php echo $guest_uid?>_<?php echo $form->id?>').options;
																			for (i=0, len = select_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?>.length; i<len; i++) {
																				let opt = select_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?>[i];
																				if (opt.selected) {
																					jQuery('#select-<?php echo $guest_uid?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).show();
																				} else {
																					jQuery('#select-<?php echo $guest_uid?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).hide();
																				}
																			}

																		}
																	</script>
																<?php } ?>

																<?php if($form->type == 'multiselect') { ?>
																	
																	<div class="form-group rezgo-custom-form rezgo-form-input">
																		<label class="control-label"><span><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></span></label>
																		<select id="rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>" class="chosen-select form-control<?php echo ($form->require) ? ' required' : ''; ?> rezgo-custom-select" multiple="multiple" name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>][]">
																			<option value=""></option>
																			<?php foreach($form->options as $option) { ?>
																				<option><?php echo $option?></option>
																			<?php } ?>
																		</select>

																		<?php if ($form->instructions){ ?>
																			<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																		<?php } ?>

																		<?php if ($form->options_instructions) {
																			$optex_count = 1;
																			foreach($form->options_instructions as $opt_extra) {
																				echo '<span class="opt_extra" id="optex_'.$optex_count.'" style="display:none">'.$opt_extra.'</span>';
																				$optex_count++;
																			}
																		}
																		?>
																		<input type="hidden" value='' name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>][]" data-addon="multiselect_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden">
																	</div>

																	<script>

																		jQuery('#rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>').change(function(){
																			jQuery(this).valid();

																			if (jQuery(this).val() === null){
																				jQuery("input[data-addon='multiselect_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", false);
																			} else {
																				jQuery("input[data-addon='multiselect_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																			}
																		});
																		let multiselect_group_<?php echo $guest_uid?>_<?php echo $form->id?> = "<?php echo addslashes(html_entity_decode($cart_gf_val[$c-1][(int)$form->id]['value'], ENT_QUOTES))?>";

																		if (multiselect_group_<?php echo $guest_uid?>_<?php echo $form->id?>.length > 1){
																			jQuery("input[data-addon='multiselect_tour_group-<?php echo $guest_uid?>_<?php echo $form->id?>_hidden']").attr("disabled", true);
																			multiselect_group_<?php echo $guest_uid?>_<?php echo $form->id?> = multiselect_group_<?php echo $guest_uid?>_<?php echo $form->id?>.split(', ');

																			jQuery('#rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>').val(multiselect_group_<?php echo $guest_uid?>_<?php echo $form->id?>).trigger('chosen:updated');

																			let multiselect_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?> = document.getElementById('rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>').options;
																			for (i=0, len = multiselect_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?>.length; i<len; i++) {
																				let opt = multiselect_gf_options_<?php echo $guest_uid?>_<?php echo $form->id?>[i];
																				if (opt.selected) {
																					jQuery('#rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).show();
																				} else {
																					jQuery('#rezgo-custom-select-<?php echo $guest_uid?>_<?php echo $form->id?>').parent().find( '#optex_' + i + '.opt_extra' ).hide();
																				}
																			}
																		}

																	</script>
																<?php } ?>

																<?php if($form->type == 'textarea') { ?>
																	
																	<div class="form-group rezgo-custom-form rezgo-form-input">
																		<label class="control-label"><span><?php echo $form->title?><?php if($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?></span></label>

																		<textarea class="form-control<?php echo ($form->require) ? ' required' : ''; ?>" name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]" cols="40" rows="4"><?php echo $cart_gf_val[$c-1][(int)$form->id]['value']?></textarea>

																		<?php if ($form->instructions){ ?>
																			<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																		<?php } ?>
																	</div>
																<?php } ?> 

																<?php if($form->type == 'checkbox') { ?>
																	
																	<?php // build unique identifier for checkbox 
																	$checkbox_uid = $c.'_'.$form->id.'_'.$num.'_'.$price->name; ?>

																	<div class="rezgo-pretty-checkbox-container">

																		<div class="rezgo-form-group rezgo-custom-form rezgo-form-input rezgo-pretty-checkbox">
																			<div class="pretty p-default p-curve p-smooth">

																				<input type="checkbox"<?php echo ($form->require) ? ' class="required"' : ''; ?> 
																					id="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>" 
																					name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]"
																					data-addon="checkbox_tour_group-<?php echo $checkbox_uid?>">

																				<div class="state p-warning">
																					<label for="<?php echo $form->id."|".base64_encode($form->title)."|".$form->price."|".$c."|".$price->name."|".$num; ?>"><span><?php echo $form->title?></span>
																					<?php if ($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?>
																					<?php if ($form->price) { ?> <em class="price"><?php echo $form->price_mod?> <?php echo $site->formatCurrency($form->price)?></em><?php } ?></label>
																				</div>

																				<input type='hidden' value='off' name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]"  data-addon="checkbox_tour_group-<?php echo $checkbox_uid?>_hidden">

																			</div>
																		</div>

																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>

																		<script>
																		jQuery("input[data-addon='checkbox_tour_group-<?php echo $checkbox_uid?>']").change(function(){
																			if (jQuery(this).is(":checked")){
																				jQuery("input[data-addon='checkbox_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", true);
																			} else {
																				jQuery("input[data-addon='checkbox_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", false);
																			}
																		});

																		<?php if ($cart_gf_val[$c-1][(int)$form->id]['value'] == 'on'){ ?>
																			jQuery("input[data-addon='checkbox_tour_group-<?php echo $checkbox_uid?>']").prop('checked', true);
																			jQuery("input[data-addon='checkbox_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", true);
																		<?php } ?>
																		</script>

																	</div>
																<?php } ?>

																<?php if($form->type == 'checkbox_price') { ?>
																	
																	<?php // build unique identifier for checkbox 
																	$checkbox_uid = $c.'_'.$form->id.'_'.$num.'_'.$price->name; ?>

																	<div class="rezgo-pretty-checkbox-container">
																		<div class="rezgo-form-group rezgo-custom-form rezgo-form-input rezgo-pretty-checkbox">
																			<div class="pretty p-default p-curve p-smooth">

																				<input type="checkbox"<?php echo ($form->require) ? ' class="required"' : ''; ?> 
																				id="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>" 
																				name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]" 
																				data-addon="checkbox_price_tour_group-<?php echo $checkbox_uid?>"> 

																				<div class="state p-warning">
																					<label for="<?php echo $form->id?>|<?php echo base64_encode($form->title)?>|<?php echo $form->price?>|<?php echo $c?>|<?php echo $price->name?>|<?php echo $num?>"><span><?php echo $form->title?></span>
																					<?php if ($form->require) { ?> <em class="fa fa-asterisk"></em><?php } ?>
																					<?php if ($form->price) { ?> <em class="price"><?php echo $form->price_mod?> <?php echo $site->formatCurrency($form->price)?></em><?php } ?></label>
																				</div>
																			</div>

																			<input type='hidden' value='off' name="booking[<?php echo $c?>][tour_group][<?php echo $price->name?>][<?php echo $num?>][forms][<?php echo $form->id?>]"  data-addon="checkbox_price_tour_group-<?php echo $checkbox_uid?>_hidden">
																		</div>

																	<?php if ($form->instructions){ ?>
																		<p class="rezgo-form-comment"><span><?php echo $form->instructions?></span></p>
																	<?php } ?>

																		<script>
																		jQuery("input[data-addon='checkbox_price_tour_group-<?php echo $checkbox_uid?>']").change(function(){
																			if (jQuery(this).is(":checked")){
																				jQuery("input[data-addon='checkbox_price_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", true);
																			} else {
																				jQuery("input[data-addon='checkbox_price_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", false);
																			}
																		});

																		<?php if ($cart_gf_val[$c-1][(int)$form->id]['value'] == 'on'){ ?>
																			jQuery("input[data-addon='checkbox_price_tour_group-<?php echo $checkbox_uid?>']").prop('checked', true);
																			jQuery("input[data-addon='checkbox_price_tour_group-<?php echo $checkbox_uid?>_hidden']").attr("disabled", true);
																		<?php } ?>
																		</script>
																	</div>
																<?php } ?>
																
																<?php $form_counter++; ?>
															<?php } // end foreach( $site->getTourForms('group') as $form ) ?>
														</div> 
													<?php } // end foreach($site->getTourPriceNum($price, $item) as $num) ?>
												<?php } // end foreach ($site->getTourPrices($item) as $price) ?>
											<?php } // end ($item->group != 'hide') ?>
									</div><!-- // rezgo-book-step-one-item -->
									<hr>
										<?php } else { $cart_count--; } ?>
								<?php } ?>
								<?php // end cart loop for each tour in the order ?>

								<div id="rezgo-bottom-cta">
									<a href="<?php echo $site->base?>/order" class="btn rezgo-btn-default btn-lg btn-block rezgo-book-step-one-previous-bottom"><span>Back to Order</span></a>
									<button class="btn rezgo-btn-book btn-lg btn-block rezgo-book-step-one-continue-bottom" type="submit" form="rezgo-guest-form">
										<span>Continue to Payment</span>
									</button>
								</div>

								<script>
									// switch up error message placement for smaller screen sizes on guest info page -->
									let append_count = 0;
									let bottom_error_msg = 
											'<div id="rezgo-book-errors" class="alert alert-danger rezgo-book-errors-bottom">' +
												'<span>Some required fields are missing. Please complete the highlighted fields.</span>' +
											'</div>';

									window.onload = (event) => {
										let width = this.innerWidth;
										if (width < 992){
											jQuery('.rezgo-book-errors-side').remove();
											if (append_count == 0){
												jQuery('#rezgo-bottom-cta').after(bottom_error_msg);
											}
										}
									};
									jQuery(window).resize(function() {
										let width = this.innerWidth;
										let bottom_error_msg = '<div id="rezgo-book-errors" class="alert alert-danger rezgo-book-errors-bottom"><span>Some required fields are missing. Please complete the highlighted fields.</span></div>';

										if (width < 992){
											jQuery('.rezgo-book-errors-side').remove();
											if (append_count == 0){
												jQuery('#rezgo-bottom-cta').after(bottom_error_msg);
											}
										}
									});
								</script>								
								
							</form>

							</div> <!-- pax-info-container -->

								<?php if($cart) { ?>
									<!-- FIXED CART -->
									<?php require('fixed_cart.php');?>
								<?php } ?> 
								
							</div> <!-- flex-container -->
							
							<?php if (DEBUG) { ?>
								<div id="debug_container" class="text-center" style='display:none;'>
									<p> DEBUG API REQUEST </p>
									<textarea id="api_request_debug" readonly="readonly" rows="10"></textarea>
									<hr>
									<button id="api_send_request" class="btn btn-default" >Send Request</button>
								</div>
								
								<script>
									jQuery('#api_send_request').click(function(e){
										e.preventDefault();

										jQuery('#rezgo-guest-form').ajaxSubmit({
											type: 'POST',
											url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
											data: { rezgoAction: 'book_step_one' },
											success: function(data){
												alert('Request Sent')
											},
											error: function(error) {
												console.log(error);
											}
										});
									})
								
								</script>
							<?php } ?>

						</div><!-- // #book_step_one -->


					<script charset="UTF-8">
						jQuery(document).ready(function($){

							// switch up the btn ids 
							$(window).resize(function() {
								let width = this.innerWidth;
								if (width < 992){
									$(".rezgo-book-step-one-previous-bottom").prop("id", "rezgo-book-step-one-btn-previous");
									$(".rezgo-book-step-one-continue-bottom").prop("id", "rezgo-book-step-one-btn-continue");

								}
							});

							$('.rezgo-cart-count').text('<?php echo $cart_count?>');

							// prefill info from lead passenger and populate fields downwards
								// let lead_first_name = $('#lead_passenger_first_name');
								// let lead_last_name = $('#lead_passenger_last_name');
								// let lead_email = $('#lead_passenger_email');

								<?php 
								// $i = 0; 
								// foreach($cart as $item) {
								// 	$i++; ?>
								/*
									lead_first_name.blur(function(){
										if (!$('#frm_<?php echo $i?>_adult_1_first_name').val()){
											$('#frm_<?php echo $i?>_adult_1_first_name').val(lead_first_name.val());	
										}
									});
									lead_last_name.blur(function(){
										if (!$('#frm_<?php echo $i?>_adult_1_last_name').val()){
											$('#frm_<?php echo $i?>_adult_1_last_name').val(lead_last_name.val());	
										}
									});
									lead_email.blur(function(){	
										if (!$('#frm_<?php echo $i?>_adult_1_email').val()){
											$('#frm_<?php echo $i?>_adult_1_email').val(lead_email.val());	
										}
									});
								*/
								<?php //} ?>

								<?php // get # of pax from the first item
								/*
								$first_item_pax;
								foreach($cart as $item) {
									if ($item->num == 1){
										$first_item_pax = $item->pax;
										break;
									}
								*/
								//} ?>

								<?php //for($j=1; $j<=$first_item_pax; $j++){ ?> 
								/*
									let fname_<?php echo $j?> = $("input[data-index='fname_from_<?php echo $j?>']");
									let lname_<?php echo $j?> = $("input[data-index='lname_from_<?php echo $j?>']");
									let phone_<?php echo $j?> = $("input[data-index='phone_from_<?php echo $j?>']");
									let email_<?php echo $j?> = $("input[data-index='email_from_<?php echo $j?>']");

									// disregards pax type and prefills downwards for each booking 
									fname_<?php echo $j?>.blur(function(){
										if(!$("input[data-index='fname_to_<?php echo $j?>']").val()){
											$("input[data-index='fname_to_<?php echo $j?>']").val(fname_<?php echo $j?>.val());
										}
									});
									lname_<?php echo $j?>.blur(function(){
										if(!$("input[data-index='lname_to_<?php echo $j?>']").val()){
											$("input[data-index='lname_to_<?php echo $j?>']").val(lname_<?php echo $j?>.val());
										}
									});
									phone_<?php echo $j?>.blur(function(){
										if(!$("input[data-index='phone_to_<?php echo $j?>']").val()){
											$("input[data-index='phone_to_<?php echo $j?>']").val(phone_<?php echo $j?>.val());
										}
									});
									email_<?php echo $j?>.blur(function(){
										if(!$("input[data-index='email_to_<?php echo $j?>']").val()){
											$("input[data-index='email_to_<?php echo $j?>']").val(email_<?php echo $j?>.val());
										}
									});
								*/
								<?php //} ?>
											
							$(".chosen-select").chosen( { width: "100%", allow_single_deselect: true } );

							$('.rezgo-custom-select').chosen().change( function() {

								var parent = $(this).parent();
								var chosen_options = this && this.options;
								var opt;

								for (var i=0, len=chosen_options.length; i<len; i++) {

									opt = chosen_options[i];

									if (opt.selected) {
										parent.find( '#optex_' + i + '.opt_extra' ).show();
									} else {
										parent.find( '#optex_' + i + '.opt_extra' ).hide();
									}
								}
							});


							$('.rezgo-pickup-select').change(function () {
								
								var pickup_id = $(this).val();
								var pickup_target = $(this).data('target');
								var count = $(this).data('counter');
								var book_id = $(this).data('id');
								var option_id = $(this).data('option');
								var pax_num = $(this).data('pax');
								
								$('#rezgo-pickup-line-' + book_id).val('');
								
								if (pickup_id) {
											
									$('#rezgo-pickup-line-' + book_id).val(pickup_id);
								
									// wait animation
									$('#' + pickup_target).html('<div class="rezgo-pickup-loading"></div>');

									$.ajax({
										url: "<?php echo admin_url('admin-ajax.php'); ?>" + '?action=rezgo&method=pickup_ajax&pickup_id=' + pickup_id + '&option_id=' + option_id + '&book_id=' + book_id + '&pax_num=' + pax_num + '', 
										data: { rezgoAction: 'item'},
										context: document.body,
										success: function(data) {			
											$('#' + pickup_target).fadeOut().html(data).fadeIn('fast'); 
										}
									});	
								
								} else {
									$('#' + pickup_target).html('');
								}
							
							});
							
							$('.lead-passenger-input').blur(function(){
								if ( $(this) != ''){
									update_lead_passenger();
								}
							});

							function update_lead_passenger(){
								let lead_passenger_first_name = $('#lead_passenger_first_name').val();
								let lead_passenger_last_name = $('#lead_passenger_last_name').val();
								let lead_passenger_email = $('#lead_passenger_email').val();

								$.ajax({
									type: 'POST',
									url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
									data: { 
											rezgoAction: 'update_lead_passenger',
											lead_passenger_first_name: lead_passenger_first_name,
											lead_passenger_last_name: lead_passenger_last_name,
											lead_passenger_email: lead_passenger_email,
										},
									success: function(data){
											// console.log('saved email');
										},
										error: function(error){
											console.log(error);
										}
								});
							}

							function error_booking() {
								$('#rezgo-book-errors').fadeIn();
								append_count = 1;

								setTimeout(function () {
									$('#rezgo-book-errors').fadeOut();
								}, 5000);
								return false;
							}

							function submit_guest_form() {
								var validate_check = validate_form();

								if(!validate_check) {
									return error_booking();
								} else {

									<?php if (DEBUG){ ?> 

										// show debug window with update request
										$('#rezgo-guest-form').ajaxSubmit({
											type: 'POST',
											url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
											data: { rezgoAction: 'update_debug', },
											success: function(data){
												console.log(data);
												$('#debug_container').show();
												$('#api_request_debug').html(data);
											},
											error: function(error) {
												console.log(error);
											}
										});

									<?php } else { ?>

										$('#rezgo-guest-form').ajaxSubmit({
											type: 'POST',
											url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
											data: { rezgoAction: 'book_step_one', },
											success: function(data){
												top.location.href = '<?php echo $site->base?>/confirm';
											},
											error: function(error) {
												console.log(error);
											}
										});

									<?php } ?>

								}
							}
							// Validation Setup
							$.validator.setDefaults({
								highlight: function(element) {
									if ($(element).attr("type") == "checkbox") {
										$(element).closest('.rezgo-pretty-checkbox-container').addClass('has-error');
									} else if ($(element).hasClass("chosen-select")) {
										// for chosen hidden select inputs
										$(element).parent().find('.chosen-single').addClass('has-error');
										$(element).parent().find('.chosen-choices').addClass('has-error');
									} else {	
										$(element).closest('.rezgo-form-input').addClass('has-error');
									}
									$(element).closest('.form-group').addClass('has-error');

								},
								unhighlight: function(element) {
									if ( $(element).attr("type") == "checkbox" ) {
										$(element).closest('.rezgo-form-checkbox').removeClass('has-error');
									} else if ($(element).is(":hidden")) {
										// for chosen hidden select inputs
										$(element).parent().find('.chosen-single').removeClass('has-error');
										$(element).parent().find('.chosen-choices').removeClass('has-error');
									} else {
										$(element).closest('.rezgo-form-input').removeClass('has-error');
									}
									$(element).closest('.form-group').removeClass('has-error');
								},
								focusInvalid: false,
								errorElement: 'span',
								errorClass: 'help-block',
								ignore: ":hidden:not(.chosen-select)",
								errorPlacement: function(error, element) {
									if ($(element).attr("type") == "checkbox") {
										error.insertAfter(element.parent().parent());
									} else if (element.is(":hidden")) {
										// for chosen hidden select inputs
										error.insertAfter(element.parent().find('.chosen-container'));
									} else {
										error.insertAfter(element);
									}
								}
							});

							$('#rezgo-guest-form').validate({
								messages: {
									lead_passenger_first_name: {
										required: "Enter your first name"
									},
									lead_passenger_last_name: {
										required: "Enter your last name"
									},
									lead_passenger_email: {
										required: "Enter your email"
									},
								}
							});

							function validate_form() {
								var valid = $('#rezgo-guest-form').valid();
								return valid;
							}
							
							// Catch form submissions
							$('#rezgo-guest-form').submit(function(e) {
								e.preventDefault();
								submit_guest_form();
							});

						});
					</script>

	</div> <!-- // rezgo-book-wrp --> 

<style>#debug_response {width:100%; height:200px;}</style>
<style>#debug_container {width:50%; margin:30px auto;} #debug_container p{margin-bottom: 15px;font-size: 1.5rem; font-weight: 200;}</style>
<style>#api_request_debug {width:100%; height:200px;}</style>
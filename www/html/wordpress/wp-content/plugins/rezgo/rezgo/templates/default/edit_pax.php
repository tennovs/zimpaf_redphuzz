<?php
	$re = explode('?',sanitize_text_field($_REQUEST['method']));

	parse_str($re[1], $data);

	$trs	= 't=uid';
	$trs .= '&q=' . $data['id'];
	$trs .= '&d=' . $data['date'];
	$trs .= '&file=edit_pax';

	$option = $site->getTours($trs);

	$site->readItem($option[0]);

	$order_id = sanitize_text_field($data['order_id']);

	$cart = $site->getCart();

	if (isset($data['parent_url'])) {
		$site->base = sanitize_text_field($data['parent_url']);
	}

	// non-open date date_selection elements
	$date_types = array('always', 'range', 'week', 'days', 'single');
?>

<div class="rezgo-edit-pax-content">
	<a id="close-pax-edit-box" class="pax-edit-cancel" data-toggle="collapse" class="pax-edit-cancel" data-target="#pax-edit-<?php echo $order_id?>">
		<i class="fas fa-times"></i>
	</a>
	<script>
		var fields_<?php echo $order_id; ?> = new Array();
		var required_num_<?php echo $order_id; ?> = 0;

		function isInt(n) {
			 return n % 1 === 0;
		}

		jQuery(document).ready(function($){
			function check_pax_<?php echo $order_id; ?>(e) {
				let err;
				let count = 0;
				let required = 0;

				for (v in fields_<?php echo $order_id; ?>) {
					// total number of spots
					count += $('#' + v).val() * 1;

					// has a required price point been used
					if (
						fields_<?php echo $order_id; ?>[v] && 
						$('#' + v).val() >= 1
					) { 
						required = 1; 
					}

					// negative (-) symbol not allowed on PAX field
					if ($('#' + v).val() < 0) 
					{
					    err = 'Please enter valid number for booking.';
				    }
				}

				if (count == 0 || !count) {
					err = 'Please enter the number you would like to book.';
				} 

				else if (required_num_<?php echo $order_id; ?> > 0 && required == 0) {
					err = 'At least one marked ( * ) price point is required to book.';
				} 

				else if (!isInt(count)) {
					err = 'Please enter a whole number. No decimal places allowed.';
				} 

				<?php if (!empty($option[0]->per)) { ?>
					else if (count < <?php echo $option[0]->per; ?>) {
						err = '<?php echo $option[0]->per; ?> minimum required to book.';
					}
				<?php } ?>

				<?php if (!empty($option[0]->date->availability)) { ?>
					else if (count > <?php echo $option[0]->date->availability; ?>) {
						err = 'There is not enough availability to book ' + count;
					} 
				<?php } ?>

				else if (count > 250) {
					err = 'You can not book more than 250 spaces in a single booking.';
				}

				if (err) {
					e.preventDefault();
					$('#error_text_<?php echo $order_id; ?>').html(err);

					$('#error_text_<?php echo $order_id; ?>').slideDown().delay(2000).slideUp('slow');

					return false;
				} else{
					// Catch form submissions
					$('#rezgo-edit-pax-<?php echo $order_id?>').submit( function(e) {
						e.preventDefault();

						$('#rezgo-edit-pax-<?php echo $order_id?>').ajaxSubmit({
							type: 'POST',
							url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
							data: { rezgoAction: 'edit_pax' },
							success: function(data){
								// console.log(JSON.parse(data));
								let response = JSON.parse(data);

								// no error from adding item to cart 
								if (response == null) {
									<?php if (!DEBUG){ ?>
										top.location.reload();
									<?php } else { ?>
										alert('Updated Pax Count');
									<?php } ?>
								} else {
									let err = response.message;
									$('#error_text_<?php echo $order_id?>').html(err);
									$('#error_text_<?php echo $order_id?>').slideDown().delay(2000);
								}

							},
							error: function(error) {
								console.log(error);
							}
						});
						
					});
				}
			}

			$('.rezgo-btn-book[rel="<?php echo $order_id; ?>"]').on('click', function(e){
				check_pax_<?php echo $order_id; ?>(e);
			});
		});
	</script>

	<?php if ($option[0]->date->availability != 0){ ?>

		<?php
			// get current cart to plug into getTourPrices
			foreach ($cart as $item){
				$options[] = $item;
			}
		?>

		<form class="rezgo-order-form clearfix" name="rezgo-edit-pax-<?php echo $order_id; ?>" id="rezgo-edit-pax-<?php echo $order_id; ?>" action="<?php echo $site->base; ?>/order" target="rezgo_content_frame">
			<input type="hidden" name="edit[index]" value="<?php echo $order_id?>">
			<input type="hidden" name="edit[uid]" value="<?php echo $option[0]->uid?>" />
			<input type="hidden" name="edit[date]" value="<?php echo $data['date']; ?>" />

			<?php $prices = $site->getTourPrices($option[0]);	?>

			<?php if($site->getTourRequired()) { ?>
				<span class="rezgo-memo">At least one marked ( <em><i class="fa fa-asterisk"></i></em> ) price point is required to book.</span>
			<?php } ?>

			<?php if ($option[0]->per > 1) { ?>
				<span class="rezgo-memo">At least <?php echo $option[0]->per; ?> are required to book.</span>
			<?php } ?>


			<?php $total_required = 0; ?>

			<?php foreach($prices as $price){
				
				/* 
				There is a mismatch between labels between cart and getTourPrices()
				Consider fixing in the class instead
				*/

				if (in_array( $price->name, array('adult', 'child', 'senior'))) {
					$price_name = 'price_'.$price->name;
				} else {
					$price_name = $price->name;
				}
			?>

			<script>
			fields_<?php echo $order_id; ?>['<?php echo $price->name.'_'.$order_id; ?>'] = <?php echo (($price->required) ? 1 : 0); ?>;
			</script>

			<div class="order-edit-pax-wrp">
				<div class="edit-pax-label-container">
					<label for="<?php echo $price->name?>_<?php echo $option_num.'_'.$sub_option?>" class="control-label rezgo-pax-label rezgo-label-padding-left">
						<?php echo $price->label?><?php echo (($price->required && $site->getTourRequired()) ? ' <em><i class="fa fa-asterisk"></i></em>' : '')?> 
					</label>
				</div>

				<div class="pax-price-container">
					<div class="form-group row pax-input-row">
						<div class="edit-pax-container">
							<div class="minus-pax-container">
								<a id="decrease_<?php echo $price->name?>_<?php echo $order_id?>" class="not-allowed" onclick="decreasePax_<?php echo $price->name?>_<?php echo $order_id?>()">
									<i class="fa fa-minus"></i>
								</a>
							</div>
							<div class="input-container">
								<input type="number" min="0" name="edit[<?php echo $price->name?>_num]" value="<?php echo (string) $cart[$order_id]->{$price->name.'_num'}?>" id="<?php echo $price->name?>_<?php echo $order_id?>" size="3" class="pax-input" value="0" min="0" placeholder="0">
							</div>
							<div class="add-pax-container">
								<a onclick="increasePax_<?php echo $price->name?>_<?php echo $order_id?>()">
									<i class="fa fa-plus"></i>
								</a>
							</div>	
						</div>
					</div>

					<div>
						<div class="edit-pax-label-container">
							<label for="<?php echo $price->name?>" class="control-label rezgo-label-padding-left">
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
									</span><br class="break-mobile">

								<?php } else if($site->exists($price->strike)) { ?>

									<!-- show only if strike price is higher -->
									<?php if ($strike_price >= $initial_price) { ?>
										<span class="rezgo-strike-price">
											<?php echo $site->formatCurrency($strike_price)?>
										</span><br class="break-mobile">
									<?php } ?>

								<?php } else if($site->exists($price->base)) { ?>

									<span class="discount">
										<?php echo $site->formatCurrency($price->base)?>
									</span><br class="break-mobile">

								<?php } ?>

									<?php echo $site->formatCurrency($price->price)?>
								</span>
							</label>
						</div>
					</div>

				</div><!-- // pax-price-container -->
			</div><!-- // order-edit-pax-wrp -->

			<script>
				if (jQuery('#<?php echo $price->name?>_<?php echo $order_id?>').val() > 0){
					jQuery('#decrease_<?php echo $price->name?>_<?php echo $order_id?>').removeClass('not-allowed');
				}
				function increasePax_<?php echo $price->name?>_<?php echo $order_id?>(){
					let value = parseInt(document.getElementById('<?php echo $price->name?>_<?php echo $order_id?>').value);
					value = isNaN(value) ? 0 : value;
					value++;
					if (value > 0) { 
						jQuery('#decrease_<?php echo $price->name?>_<?php echo $order_id?>').removeClass('not-allowed');
					}
					document.getElementById('<?php echo $price->name?>_<?php echo $order_id?>').value = value;
				}
				function decreasePax_<?php echo $price->name?>_<?php echo $order_id?>(){
					let value = parseInt(document.getElementById('<?php echo $price->name?>_<?php echo $order_id?>').value);
					value = isNaN(value) ? 0 : value;
					if (value <= 0) {
						return false;
					}
					value--;
					if (value <= 0) {
						jQuery('#decrease_<?php echo $price->name?>_<?php echo $order_id?>').addClass('not-allowed');
					} 
					document.getElementById('<?php echo $price->name?>_<?php echo $order_id?>').value = value;
				}
			</script>

			<?php
				if ($price->required) { $total_required++; }
			} // end foreach( $site->getTourPrices()	
		?>

			<script>
			required_num_<?php echo $order_id; ?> = <?php echo $total_required; ?>;
			</script>

			<div class="text-danger rezgo-option-error" id="error_text_<?php echo $order_id; ?>" style="display:none;"></div>

			<div class="form-group pull-right">
				<a 
				data-toggle="collapse" 
				data-target="#pax-edit-<?php echo $order_id; ?>"
				class="pax-edit-cancel">Cancel</a>
				<span>&nbsp;</span>
				<button 
				type="submit" 
				class="btn rezgo-btn-book rezgo-btn-update_<?php echo $order_id?>" 
				data-date=<?php echo $_REQUEST['date']?> 
				data-order-item="<?php echo $order_id?>" 
				rel="<?php echo $order_id; ?>"><span>Update Booking</span></button>
			</div>

			<br />
		</form>
	<?php } else { ?>
		<div class="rezgo-order-unavailable">
			<span>Sorry, there is no availability left for this option.</span>
		</div>
	<?php } ?>
</div>

<script>
	jQuery('.pax-edit-cancel').click(function(){
		jQuery('.rezgo-pax-edit-btn').find('span').html('Edit Guests');
	})
</script>
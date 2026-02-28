<?php 
session_start();
$company = $site->getCompanyDetails();
$companyCountry = $site->getCompanyCountry();
$site->readItem($company);
$site->setCookie('rezgo_gift_card_'.REZGO_CID, REZGO_CID);

$name = $_SESSION['gift-card']['recipient_name'];
$email = $_SESSION['gift-card']['recipient_email'];
$msg = $_SESSION['gift-card']['recipient_message'];
?>

<script>var debug = <?php echo DEBUG?>;</script> 
<script>
	// restrict custom amount to 2 decimal places
	function two_decimal(e) {
		let t = e.value;
		e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
	}
</script>

<div class="container-fluid rezgo-container">
	<div class="row">
		<div class="col-xs-12 rezgo-gift-card-inner-col">
			<div id="rezgo-gift-card-search" class="rezgo-gift-card-container clearfix">
				<div class="search-section rezgo-gift-card-group clearfix">
					<h3 id="rezgo-gift-card-search-header"><span class="">Check Your Balance</span></h3>
					<br>
					<form id="search" role="form" method="post" target="rezgo_content_frame">
						<div class="input-group">
							<input type="text" class="form-control" id="search-card-number" placeholder="Gift Card Number" />
							<span class="input-group-btn">
								<button class="btn btn-primary rezgo-check-balance rezgo-btn-default" type="submit"><span>Go!</span></button>
							</span>
						</div>
					</form>

					<div class='alert' style='display:none'>
						<span class='msg'></span>
					</div>

                    <div id="rezgo-gift-card-memo-check"><span></span></div>
				</div>
			</div>

			<?php if (!$site->isVendor() && $site->getGateway()) { ?>
				<div class="rezgo-gift-card-container clearfix">
					<form id="purchase" class="gift-card-purchase" role="form" method="post" target="" action="">
						<div class="rezgo-gift-card-group clearfix">
							<div class="rezgo-gift-card-head">
								<h3 class="rezgo-gift-card-heading gc-page-header"><span>1. Select an Amount</span></h3>
								<p class="rezgo-gift-card-desc"><span>Select the card value or choose a custom amount. (All Values are in <?php echo $company->currency_symbol?>)</span></p>
							</div>

							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">

										<div id="rezgo-gc-choose-container">
											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_50" checked type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="50" onclick="toggleAmount();">
												<label for="gc_preset_50" class="payment-label"><?php echo $site->formatCurrency(50)?></label>
											</div>

											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_100" type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="100" onclick="toggleAmount();"> 
												<label for="gc_preset_100" class="payment-label"><?php echo $site->formatCurrency(100)?></label>
											</div>

											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_150" type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="150" onclick="toggleAmount();">
												<label for="gc_preset_150" class="payment-label"><?php echo $site->formatCurrency(150)?></label>
											</div>

											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_250" type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="250" onclick="toggleAmount();">
												<label for="gc_preset_250" class="payment-label"><?php echo $site->formatCurrency(250)?></label>
											</div>

											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_300" type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="300" onclick="toggleAmount();">
												<label for="gc_preset_300" class="payment-label"><?php echo $site->formatCurrency(300)?></label>
											</div>

											<div class="rezgo-gc-choose-radio">
												<input id="gc_preset_custom" type="radio" name="billing_amount" class="rezgo-gc-preset-amount" value="custom" onclick="toggleAmount();">
												<label for="gc_preset_custom" class="payment-label">Custom Amount</label>
											</div>
										</div>

										<div class="rezgo-custom-billing-amount-container" style="display:none;">

											<div id="rezgo-custom-billing-amount-wrp">
												<span id="custom-billing-currency-placeholder"><?php echo $company->currency_symbol?></span> <input type="number" min="1" name="custom_billing_amount" id="rezgo-custom-billing-amount" class="form-control" placeholder="Enter a custom amount" oninput="two_decimal(this);">
											
											<a id="rezgo-custom-amount-cancel" class="underline-link"><span>Cancel</span></a>
											
											</div>
										</div>
									</div>
								</div>
							</div>

                            <div id="rezgo-gift-card-memo-buy"><span></span></div>
						</div>

						<div class="rezgo-gift-card-group clearfix">
							<div class="rezgo-gift-card-head">
								<h3 class="gc-page-header"><span>2. Gift Card Recipient</span></h3>
							</div>

							<div class="row">
								<div class="col-xs-12 col-sm-6">
									<div class="form-group">
										<label for="recipient_name" class="control-label">
											<span>Name</span>
										</label>

										<input class="form-control required" name="recipient_name" type="text" placeholder="Full Name" value="<?php echo ($name) ? : ''; ?>">
									</div>
								</div>

								<div class="col-xs-12 col-sm-6">
									<div class="form-group">
										<label for="recipient_email" class="control-label">
											<span>Email Address</span>
										</label>

										<input class="form-control required" name="recipient_email" type="email" placeholder="Email Address" value="<?php echo ($email) ? : ''; ?>">
									</div>
								</div>
								

							</div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="recipient_message" class="control-label">Your Message (optional)</label>

                                        <textarea class="form-control gc-recipient-message" name="recipient_message" rows="5" style="resize:none" placeholder="Your Message"><?php echo ($msg) ? : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="rezgo-gift-card-memo-sendto"><span></span></div>
						</div>

						<div id="rezgo-gift-message" class="row" style="display:none;">
							<div id="rezgo-gift-message-body" class="col-sm-8 col-sm-offset-2"></div>
                          	<div id="rezgo-gift-message-wait" class="col-sm-2"><i class="far fa-sync fa-spin fa-3x fa-fw"></i></div>
						</div>
						
						<div id="rezgo-gift-errors" style="display:none;">
							<div class="alert alert-danger">Some required fields are missing or incorrect. Please review the highlighted fields.</div>
						</div>

						<div class="cta">
							<button type="submit" class="btn rezgo-btn-book btn-lg btn-block" id="purchase-submit">
								Proceed to Checkout
								<!-- <span id="gc_total_due"></span> -->
							</button>
						</div>
			
						<input type="hidden" name="rezgoAction" value="firstStepGiftCard">
					</form>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo $site->path?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $site->path?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $site->path?>/js/jquery.selectboxes.js"></script>

<?php if (!$site->isVendor() && $site->getGateway()) { ?>
	<script>

	jQuery(document).ready(function($){	

		/* FORM (#purchase) */

		// STATES VAR
		var ca_states = <?php echo json_encode( $site->getRegionList('ca') ); ?>;
		var us_states = <?php echo json_encode( $site->getRegionList('us') ); ?>;
		var au_states = <?php echo json_encode( $site->getRegionList('au') ); ?>;

		// FORM ELEM
		var $purchaseForm = $('#purchase');
		var $purchaseBtn = $('#purchase-submit');
		var $formMessage = $('#rezgo-gift-message');
		var $formMsgBody = $('#rezgo-gift-message-body');
		var $amtSelect = $('#rezgo-billing-amount');
		var $amtCustom = $('#rezgo-custom-billing-amount');

		function error_booking() {
			$('#rezgo-gift-errors').show();

			setTimeout(function(){
				$('#rezgo-gift-errors').hide();
			}, 8000);
		}

		// FORM VALIDATE
		$purchaseForm.validate({
			messages: {
				recipient_name: {
					required: "Please enter a name"
				},
				recipient_email: {
					required: "Please enter a valid email address"
				},
				custom_billing_amount: {
					required: 'Please enter an amount'
				},
				billing_amount: {
					required: 'Please select an amount'
				},
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element);
			},
			highlight: function(element) {
				$(element).closest('.form-group').addClass('has-error');			
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			errorClass: 'help-block',
			focusInvalid: false,
			errorElement: 'span'
		});

		$purchaseForm.submit(function(e) {
			// FORM VALIDATION
			let validationCheck = $purchaseForm.valid();
			if (!validationCheck) {
				$purchaseBtn.removeAttr('disabled');
				error_booking();
			} else {
				e.preventDefault();
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					data: {
						action: 'rezgo',
						method: 'gift_card_ajax',
						rezgoAction:'giftCardPayment',
						formData: $purchaseForm.serialize(),
					},
					success: function(data)
						{
							top.location.href= '<?php echo $site->base; ?>/gift-card-payment';
						}
				});
			}
		});

	});

	</script>
<?php } ?>

<script>	

jQuery(document).ready(function($){	

	// MONEY FORMATTING
	var form_symbol = '$';
	var form_decimals = '2';
	var form_separator = ',';

	const currency = "<?php echo $company->currency_symbol?>";
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

	let gc_total_due;
	let default_amt = 50;
	let custom_input = $('#rezgo-custom-billing-amount');

	if ($('#gc_preset_custom').is(':checked')){
		$('.rezgo-custom-billing-amount-container').show();
		$('#rezgo-gc-choose-container').slideToggle();
	}

	<?php if ($_SESSION['gift-card']['billing_amount'] == 'custom'){ ?>

		$('.rezgo-custom-billing-amount-container').show();
		$('#rezgo-gc-choose-container').hide();
		custom_input.addClass('required');
		custom_input.val('<?php echo $_SESSION['gift-card']['custom_billing_amount']?>');
		$('input[name=billing_amount]:checked').val('custom');

	<?php } else { ?>

		$('input[name=billing_amount]:checked').val(<?php echo $_SESSION['gift-card']['billing_amount']?>);
		custom_input.val('');
		$('#gc_preset_<?php echo (string)$_SESSION['gift-card']['billing_amount']?>').prop("checked", true);

	<?php } ?>

	toggleAmount = function() {

		if($('input[name=billing_amount]:checked').val() == 'custom') {

			// clear any custom amount if filled previously
			$('#gc_total_due').html('');

			$('.rezgo-custom-billing-amount-container').show();
			$('#rezgo-gc-choose-container').slideToggle();
			custom_input.addClass('required');

			let custom_billing_pos = $('#purchase').position();
			let search_div_height = $('#rezgo-gift-card-search').outerHeight() + 50;
			let custom_billing_scroll = Math.round(custom_billing_pos.top);

			setTimeout(() => {
				custom_input.focus();
				window.parent.scrollTo({
					top: custom_billing_scroll + search_div_height,
					left: 0,
					behavior: 'smooth'
				});
			}, 150);

			custom_input.change(function(){
				gc_total_due = Number(custom_input.val());
			});

		} else {
			gc_total_due = Number($('input[name=billing_amount]:checked').val());
		}
	}

	// cancel custom amount and reset values
	$('#rezgo-custom-amount-cancel').click(function(){
		$('.rezgo-custom-billing-amount-container').hide();
		$('#rezgo-gc-choose-container').slideToggle();
		custom_input.removeClass('required');
		
		custom_input.val('');
		$('#gc_total_due').html('');

		// reset custom amount
		$('#rezgo-custom-billing-amount').val('');

		// select first default amount again 
		$("input[name='billing_amount']").eq(0).prop("checked", true);
		$('input[name=billing_amount]:checked').val(50);
	});

	// shorten placeholder on smaller screens
	$(window).resize(function(){
		let width = this.innerWidth;
		if (width <= 480){
			$('#rezgo-custom-billing-amount').attr('placeholder' , 'Enter amount');
		} else {
			$('#rezgo-custom-billing-amount').attr('placeholder' , 'Enter a custom amount');
		}
	})

	/* FORM (#search) */
	var $search = $('.search-section');
	var $searchForm = $('#search');
	var $searchText = $('#search-card-number');
	var $searchError = $('#search-card-empty-error');
	var gcCur = "<?php echo $company->currency_symbol?>";
	var today = parseInt('<?php echo strtotime("today");?>');

	$searchForm.submit(function(e){
		e.preventDefault();

		var search = $searchText.val();

		if (search) {
			
			$search.find('.alert').removeClass('alert-danger alert-info').hide();

			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>', 
				type: 'POST',
				data: {
					action: 'rezgo',
					method: 'gift_card_ajax',
					rezgoAction: 'getGiftCard',
					gcNum: search
				},
				success: function (data) {
					var json, success, err, msg, amt, exp, max, use;

					err = 0;
					json = data.split("|||");
					json = json.slice(-1)[0];
					gcData = JSON.parse(json);

					s = parseFloat(gcData.status);

					if (debug) console.log(gcData);

					$searchText.css({'borderColor':'#ccc'});

					if (s) {
						amt = parseFloat(gcData.amount);
						exp = parseInt(gcData.expires);
						max = parseInt(gcData.max_uses);
						use = parseInt(gcData.uses);
						msg = 'Gift Card Balance: ' + gcCur + amt.formatMoney();

						if (max && use >= max) {
							err = "Gift card max use reached.";
						}

						if (exp && today >= exp) {
							err = "Gift card expired.";
						}
					} else {
						err = 'Gift card not found. Please, make sure you entered a correct card number.';
					}

					// RESULT
					if (err) {
						$search.find('.alert .msg').html(err);
						$search.find('.alert').addClass('alert-danger').show();
					} else {
						$search.find('.alert .msg').html(msg);
						$search.find('.alert').addClass('alert-info').show();
					}
				},
				error: function () {
					var msg = 'Connection error. Please try again or contact Rezgo for customer support.';
					$search.find('.alert .msg').html(msg);
					$search.find('.alert').addClass('alert-danger').show();
				}
			});
		} else {
			$searchText.css({'borderColor':'#a94442'});
			err = "Please enter a Gift Card Number.";
			$search.find('.alert .msg').html(err);
			$search.find('.alert').addClass('alert-danger').show();
		}
	});
});
</script>
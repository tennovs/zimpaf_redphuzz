<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<?php
		rezgo_plugin_scripts_and_styles();
		wp_print_scripts();
		wp_print_styles();
		?>

		<?php if ($site->exists($site->getStyles())) { ?>
			<style><?php echo $site->getStyles();?></style>
		<?php } ?>
	</head>
	<body class="rezgo-booking-payment-body">

	<script src="//cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js"></script>
		<script>
			jQuery(document).ready(function($){
				function check_valid() {
					var valid = $("#payment").valid();

					return valid;
				}

				function creditConfirm(token) {
					// the credit card transaction was completed, give us the token
					$(parent.document).find("#tour_card_token").val(token);
					$(parent.document).find("#gift_card_token").val(token);
				}

				<?php if ($_REQUEST['rezgoAction'] == 'return') { ?>
					creditConfirm("<?php echo sanitize_text_field($_REQUEST['token']); ?>");
				<?php } ?>
			});
		</script>
	
		<form method="post" id="payment" action="https://process.rezgo.com/form" autocomplete="off">
			<input type="hidden" name="return" value="<?php echo home_url(); ?>?rezgo=1&mode=booking_payment&rezgoAction=return&" />

		<div class="cc-form-container">
			<div class="cc-field-container form-group">
				<input id="name" placeholder="Name" class="form-control" name="name" maxlength="20" type="text" value="<?php echo $site->requestStr('name');?>" placeholder="" autocomplete="off" required>
			</div>
			
			<div class="cc-field-container form-group">
				<input id="pan" placeholder="Card Number" class="form-control" name="pan" type="text" value="<?php echo $site->requestStr('pan');?>" pattern="[0-9]*" inputmode="numeric" placeholder="" autocomplete="off" required>

				<div id="ccicon" class="ccicon"></div>

			</div>

			<style>
				<?php if(!$site->getCVV()){ ?>
					input#exp{
						width: auto;	
					}
					/* no CVV field on mobile alignment fix */
					@media screen and (max-width: 480px){
						input#exp, input#rezgo-cvv {
							margin: 0;
							width: 49.5vw;
						}
					}
					
				<?php } else { ?> 
					input#exp{
						width: 100%;	
					}
				<?php } ?>
			</style>
			
			<div class="cc-field-container form-group">
				<input id="exp" placeholder="MM/YY" class="form-control" name="exp" type="text" pattern="[0-9]*" inputmode="numeric" placeholder="" autocomplete="off" required>
				
				<input type="hidden" name="exp_month" id="exp_month">
				<input type="hidden" name="exp_year" id="exp_year">

			</div>

			<?php if($site->getCVV()) { ?>
				<div class="cc-field-container form-group">
					<input id="rezgo-cvv" placeholder="CVV" class="form-control" name ="cvv" type="text" pattern="[0-9]*" inputmode="numeric" placeholder="" autocomplete="off" required>

					<div class="popover__wrapper">
						<a href="javascript:void(0);" id="what-is-cvv" class="what-is-cvv">
							<span>what is cvv?</span>
						</a>
						<div class="popover__content">
							<img class="cvv-img" alt="what is cvv?" src="<?php echo $site->path?>/img/cvv_cards.png">
						</div>
					</div>
				</div>
			<?php } ?>

		</div>
	</form>
	
	<script>

	window.onload = function () {

		const name = document.getElementById('name');
		const cardnumber = document.getElementById('pan');
		const expirationdate = document.getElementById('exp');
		const exp_month = document.getElementById('exp_month');
		const exp_year = document.getElementById('exp_year');
		
		<?php if($site->getCVV()) { ?>
			const securitycode = document.getElementById('rezgo-cvv');
		<?php } ?>

		// placeholders on top
		const namePlaceholder = document.getElementById('name-placeholder');
		const cardPlaceholder = document.getElementById('card-placeholder');
		const expiryPlaceholder = document.getElementById('exp-placeholder');
		const cvvPlaceholder = document.getElementById('cvv-placeholder');

		const output = document.getElementById('output');
		const ccicon = document.getElementById('ccicon');

		let cctype = null;

		const placeholder = document.getElementsByClassName('cc-form-placeholder');
		for(let i = 0; i < placeholder.length; i++) {
			placeholder[i].addEventListener("click", function() {
				this.classList.add('active');
				$(this).parent().find('.form-control').focus();
			})
		}

		// Mask the Credit Card Number Input
		var cardnumber_mask = new IMask(cardnumber, {
			mask: [
				{
					mask: '0000 000000 00000',
					regex: '^3[47]\\d{0,13}',
					cardtype: 'american express'
				},
				{
					mask: '0000 0000 0000 0000',
					regex: '^(?:6011|65\\d{0,2}|64[4-9]\\d?)\\d{0,12}',
					cardtype: 'discover'
				},
				{
					mask: '0000 0000 0000 0000',
					regex: '^(5[1-5]\\d{0,2}|22[2-9]\\d{0,1}|2[3-7]\\d{0,2})\\d{0,12}',
					cardtype: 'mastercard'
				},
				{
					mask: '0000 0000 0000 0000',
					regex: '^4\\d{0,15}',
					cardtype: 'visa'
				},
				{
					mask: '0000 0000 0000 0000',
					cardtype: 'Unknown'
				}
			],
			dispatch: function (appended, dynamicMasked) {
				var number = (dynamicMasked.value + appended).replace(/\D/g, '');

				for (var i = 0; i < dynamicMasked.compiledMasks.length; i++) {
					let re = new RegExp(dynamicMasked.compiledMasks[i].regex);
					if (number.match(re) != null) {
						return dynamicMasked.compiledMasks[i];
					}
				}
			}
		});

		// Mask the Expiration Date
		var expirationdate_mask = new IMask(expirationdate, {
			mask: 'MM{/}YY',
			groups: {
				MM: new IMask.MaskedPattern.Group.Range([1, 12]),
				YY: new IMask.MaskedPattern.Group.Range([20, 32]),
			},
		});


		expirationdate.addEventListener('keyup' , function(){
			const exp_string = this.value;
			let month = exp_string.slice(0,2);
			let year = exp_string.slice(3,5);

			exp_month.value = month;
			exp_year.value = year;
		})

		<?php if($site->getCVV()) { ?>
			//Mask the security code
			var securitycode_mask = new IMask(securitycode, {
				mask: '0000',
			});
		<?php } ?>

		// CC ICONS
		let card = '<i class="fas fa-2x fa-credit-card" style="transform:scale(1.1, 0.95)translateX(2px); transform-origin:center;"></i>';
		let amex = '<i class="fab fa-2x fa-cc-amex"></i>';
		let visa = '<i class="fab fa-2x fa-cc-visa"></i>';
		let discover = '<i class="fab fa-2x fa-cc-discover"></i>';
		let mastercard = '<i class="fab fa-2x fa-cc-mastercard"></i>';

		// load default card icon
		ccicon.innerHTML = card;
		function reset(){
			ccicon.classList.remove('fade-in');
		}
		function fadeIn(){
			ccicon.classList.add('fade-in');
		}

		// pop in the appropriate card icon when detected
		cardnumber_mask.on("accept", function () {
			switch (cardnumber_mask.masked.currentMask.cardtype) {
				case 'american express':
					fadeIn();
					ccicon.innerHTML = amex;
					break;
				case 'visa':
					fadeIn();
					ccicon.innerHTML = visa;
					break;
				case 'discover':
					fadeIn();
					ccicon.innerHTML = discover;
					break;
				case 'mastercard':
					fadeIn();
					ccicon.innerHTML = mastercard;
					break;
				default:
					ccicon.innerHTML = card;
					reset();
					break;
			}
			if (this != ''){

			}
		});

	};

	</script>

	</body>
</html>
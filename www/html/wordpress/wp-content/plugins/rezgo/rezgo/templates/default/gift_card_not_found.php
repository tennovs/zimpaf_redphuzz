<div class="container-fluid rezgo-container">
	<div class="row">
		<div class="col-xs-12">
			<div id="rezgo-gift-card-search" class="rezgo-gift-card-container clearfix">
				<div class="rezgo-gift-card-group search-section clearfix">
					<div class="rezgo-gift-card-head">
						<h3 id="rezgo-gift-card-search-header"><span class="">Gift Card Not Found..</span></h3>
						<h5>To check your balance, enter a gift card number.</h5>
					</div>

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
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($) {
	/* FORM (#search) */
	let $search = $('.search-section');
	let $searchForm = $('#search');
	let $searchText = $('#search-card-number');
	$searchForm.submit(function(e){
		e.preventDefault();
		let search = $searchText.val();
		if (search) {
			top.location.href = '<?php echo $site->base;?>/gift-card/'+search;
		} else {
			$searchText.css({'borderColor':'#a94442'});
			err = "Please enter a Gift Card Number.";
			$search.find('.alert .msg').html(err);
			$search.find('.alert').addClass('alert-danger').show();
		}
});
	});
</script>

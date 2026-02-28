<?php $company = $site->getCompanyDetails(); ?>

<div class="container-fluid">
	<div class="jumbotron">
		<h2 id="rezgo-terms-head">Booking Terms</h2>

		<div class="row">
			<div class="rezgo-cart-wrapper">
				<?php echo $site->getPageContent('terms'); ?>

				<?php if ($company->tripadvisor_url != '') { ?>
					<!-- <p class="rezgo-ta-privacy">
						<span>Privacy Addendum</span>
						<br />
						<span>We may use third-party service providers such as TripAdvisor to process your personal information on our behalf. For example, we may share some information about you with these third parties so that they can contact you directly by email (for example: to obtain post visit reviews about your experience).</span>
					</p> -->
				<?php } ?>
			</div>
		</div>
	</div>
</div>	


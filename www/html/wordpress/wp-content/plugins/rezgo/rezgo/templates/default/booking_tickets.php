<?php $split = explode(",", sanitize_text_field($_REQUEST['trans_num'])); ?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">

		<?php 
		rezgo_plugin_scripts_and_styles();
		wp_print_scripts();
		wp_print_styles();
		?>

		<?php if ($site->exists($site->getStyles())) { ?>
			<style><?php echo $site->getStyles();?></style>
		<?php } ?>
	</head>

	<body>
		<div id="rezgo-tickets-body">
			<div class="container-fluid">
				<?php foreach ((array) $split as $v) { ?>
					<?php 
					$trans_num = $site->decode($v);
					if (!$trans_num) {
						$site->sendTo("/");
					}
					$booking = $site->getBookings($trans_num, 0);
					$checkin = (string) $booking->checkin;
					$checkin_state = $booking->checkin_state;
					$type = ((string) $booking->ticket_type != '' ? $booking->ticket_type : 'voucher');
					?>

					<?php if ($checkin): ?>
						<?php $ticket_content = $site->getTicketContent($trans_num, 0); ?>

						<?php foreach ($ticket_content->tickets as $ticket_list) { ?>
							<?php foreach ($ticket_list as $ticket) { ?>
								<span><?php echo $ticket; ?></span>

								<br />

								<!-- <div class="h6 pull-right">
									<span class="rezgo-ticket-logo">Rezgo</span>
								</div> -->

								<div class="clearfix"></div>

								<hr class="rezgo-ticket-bottom" />
							<?php } ?>
						<?php } ?>
					<?php else: ?>
						<?php if ($booking->status == 3): ?>
							<div class="col-xs-12">
								<br />
								<span>Booking </span>
								<strong><?php echo $trans_num; ?></strong>
								<span> has been cancelled, ticket is not available.</span>
								<br /><br />
							</div>
						<?php else: ?>
							<div class="col-xs-12">
								<br />
								<span><?php echo ucwords($type); ?></span>
								<span> for Booking </span>
								<strong><?php echo $trans_num; ?></strong>
								<span> is not available until the booking has been confirmed.</span>
								<br /><br />
							</div>
						<?php endif; ?>

						<!-- <div class="h6 pull-right">
							<span class="rezgo-ticket-logo">Rezgo</span>
						</div> -->
					<?php endif; ?>

					<div class="clearfix"></div>

					<?php if (count($split) > 1) { ?>
						<div class="col-xs-12" style="border-top:1px solid #CCC; page-break-after: always;"></div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</body>
</html>

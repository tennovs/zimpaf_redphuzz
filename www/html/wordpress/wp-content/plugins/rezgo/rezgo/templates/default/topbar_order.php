<?php $cart = $site->getCart(); ?>

<div class="col-xs-12 top-bar-order">
	<?php if (!$cart) { ?>
		<div id="rezgo-cart-list" class="order-spacer">
			<!-- <span class="hidden-xs">&nbsp;Your Order&nbsp;&ndash;</span>
			<span class="hidden-xs">There are</span> -->
			<span>
				<a class="empty-cart">
				<i class="far fa-shopping-cart"></i>
					No items in your order
				</a>
			</span>
		</div>
	<?php } else { ?>
		<?php foreach ($cart as $order) {
			$site->readItem($order);
			$this_order_total += (float) $order->overall_total;
		} ?>

		<div id="rezgo-cart-list" class="order-spacer">
			<span>
				<a href="<?php echo $site->base?>/order" target="_parent">
				<i class="far fa-shopping-cart"></i>
					<span><?php echo count($cart).' item'.((count($cart) == 1) ? '' : 's')?></span>
					<span class="hidden-xs">in your order </span>
					<!-- <span class="hidden-xs">Total:</span>
					<span><?php echo $site->formatCurrency($this_order_total);?></span> -->
				</a>
			</span>
			</div>
	<?php } ?>

	<?php if (!$site->isVendor() && $site->getGateway()) {  ?>
		<div id="rezgo-gift-link-use">
			<a class="rezgo-gift-link" href="<?php echo $site->base; ?>/gift-card">
				<i class="far fa-gift fa-lg"></i><span>&nbsp;Buy a gift card</span>
			</a>
		</div>
	<?php } ?>
</div>

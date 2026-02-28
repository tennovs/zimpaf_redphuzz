<div class="container-fluid">
	<div id="rezgo-seal-refid-container">
		<?php if ($site->exists($site->refid) || $_COOKIE['rezgo_refid_val']) { ?>
			<div id="rezgo-refid">
				RefID: <?php echo ($site->exists($site->refid)) ? $site->refid : $_COOKIE['rezgo_refid_val'];?>
			</div>
		<?php } ?>
	</div>

	<?php if ($_REQUEST['mode'] == 'page_details') {?>
		<div class="rezgo-social-box">
			<span id="rezgo-social-links">
				<a href="javascript:void(0);" title="Pin this on Pinterest" id="social_pinterest" onclick="window.open('https://www.pinterest.com/pin/create/button/?url=<?php echo urlencode('https://'.$_SERVER['HTTP_HOST'].$site->base.'/details/'.$item->com.'/'.$site->seoEncode($item->item));?>&media=<?php echo $pinterest_img_path;?>&description=<?php echo urlencode($item->item).'%0A'.urlencode(strip_tags($item->details->overview));?>','pinterest','location=0,status=0,scrollbars=1,width=750,height=320');">
					<i class="fab fa-pinterest-square" id="pinterest_icon">&nbsp;</i>
				</a>					
				<a href="javascript:void(0);" title="Share this on Twitter" id="social_twitter" onclick="window.open('https://twitter.com/share?text=<?php echo urlencode('I found this great thing to do! "'.$item->item.'"');?>&url=' + escape(top.location.href)<?php if($site->exists($site->getTwitterName())) { ?> + '&via=<?php echo $site->getTwitterName();?>'<?php } ?>,'tweet','location=1,status=1,scrollbars=1,width=500,height=350');">
					<i class="fab fa-twitter-square" id="social_twitter_icon">&nbsp;</i>
				</a>
				<a href="javascript:void(0);" title="Share this on Facebook" id="social_facebook" onclick="window.open('https://www.facebook.com/sharer.php?u=' + escape(top.location.href) + '&t=<?php echo urlencode($item->item);?>','facebook','location=1,status=1,scrollbars=1,width=600,height=400');">
					<i class="fab fa-facebook-square" id="social_facebook_icon">&nbsp;</i>
				</a>
				<!-- <a href="javascript:void(0);" id="social_url" data-toggle="popover" data-ajaxload="<?php echo $site->base;?>/shorturl_ajax.php?url=<?php echo urlencode('https://'.$_SERVER['HTTP_HOST'].$site->base.'/details/'.$item->com.'/'.$site->seoEncode($item->item)); ?>">
					<i class="fab fa-share-alt-square" id="social_url_icon">&nbsp;</i>
				</a> -->
			</span>
		</div>
	<?php } ?>

</div>

<script>
    // debug if set in config
    <?php
    if ($_SESSION['debug']) {
        echo '// output debug to console'."\n\n";
        foreach ($_SESSION['debug'] as $debug) {
            echo "window.console.log('".$debug."'); \n";
        }
        unset($_SESSION['debug']);
    }
    ?>
</script>
</body>

</html>
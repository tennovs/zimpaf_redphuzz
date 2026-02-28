<?php
$company = $site->getCompanyDetails();
?>
<!-- fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700">
<!-- calendar.css -->
<link href="<?php echo $site->path?>/css/responsive-calendar.css" rel="stylesheet">
<link href="<?php echo $site->path?>/css/responsive-calendar.rezgo.css?v=<?php echo REZGO_VERSION?>" rel="stylesheet">

<script src="<?php echo $site->path?>/js/jquery.form.js"></script>
<script src="<?php echo $site->path?>/js/jquery.validate.min.js"></script>
<script src="<?php echo $site->path?>/js/responsive-calendar.min.js"></script>

<script>
	function removeLoader() {
		var loader = window.parent.document.getElementById('rezgo-modal-loader');
		loader.style.display = 'none';
	}
	window.onload = function(){
		removeLoader();
	}

	// dismiss cross sell by simulating click on hidden dismiss in modal
	jQuery(document).ready(function($){
		let parentContainer = window.parent;
		parentContainer.addEventListener('click', function(){
			let modal = parentContainer.document.getElementById('rezgo-modal');
			if (modal.classList.contains('in')){
				$('#parent-dismiss', parent.document).click();
			}
		});
	})
	
</script>

<style>
	.rezgo-return-label-error {
		color: #a94442;
	}	
	#rezgo-return-wrp{
		height: 90vh;
		overflow-y: scroll;
	}
</style>

<div id="rezgo-return-wrp" class="container-fluid rezgo-container rezgo-modal-wrp">
	<div class="clearfix"></div>
  <div class="row">
  	<div class="col-xs-12" id="rezgo-cross-description"></div>
  	<div class="col-xs-12" id="rezgo-cross-list">
		<?php

			$items = $site->getTours('t=uid&q='.$_REQUEST['id'].'&d='.$_REQUEST['date'].'&a=group'); 

			$site->readItem($items);

			$modal_window = 'window.top';

			foreach ($items as $item) {

				if ($site->getCrossSell($item)) {

					$cross_text = $site->getCrossSellText($item);

					if ($cross_text->title != '') {
						$modal_title = htmlentities((string) $cross_text->title);
					} else {
						$modal_title = 'Similar Items';
					}

					echo '<script>';

					echo 'jQuery("#rezgo-cross-description").html("'.htmlentities($cross_text->desc).'");';

					echo $modal_window.'.jQuery("#rezgo-modal-title").html("'.$modal_title.'");';
					echo $modal_window.'.jQuery("#rezgo-cross-dismiss").attr("rel", '.$_REQUEST['com'].');';

					echo '</script>';

					echo '<h3 class="rezgo-return-head">' . $item->item .'</h3>';

					echo '<div class="clearfix"></div>';
				
					foreach($site->getCrossSell($item) as $cross_sell) {

						$overview_text = strip_tags($cross_sell->overview);
						$overview_text = $overview_text." ";
						$overview_text = substr($overview_text, 0, 450);
						$overview_text = substr($overview_text, 0, strrpos($overview_text,' '));

						if(strlen(strip_tags($cross_sell->overview)) > 450) {
							$overview_text .=  ' &hellip;';
						}

						if ($cross_sell->image != 'null' && $cross_sell->image != '') {
							$cross_sell_image = '<img src="'.$cross_sell->image.'" border="0" />';
						} else {
							$cross_sell_image = '<i class="far fa-file-image rezgo-cross-img-holder" aria-hidden="true"></i>';
						}

						$cross_link = $_REQUEST['wp_slug'].'/details/'.$cross_sell->com.'/'.$site->seoEncode($cross_sell->name);

						echo '
						<div class="row rezgo-cross-item" data-com="'.$cross_sell->com.'" data-name="'.$cross_sell->name.'" data-url="'.$cross_link.'">
							<div class="col-sm-4 hidden-xxs hidden-xs rezgo-cross-image pull-left">'.$cross_sell_image.'</div>
							<div class="col-sm-8 col-xs-12 rezgo-cross-text">
								<h4 class="rezgo-cross-name">' . $cross_sell->name . '</h4>
								<p class="rezgo-cross-overview">' . $overview_text . '</p>
						';

						if ($cross_sell->starting != '') {

							$starting = $site->formatCurrency($cross_sell->starting, $company);

							echo '
								<p class="rezgo-cross-price">
									<strong class="text-info rezgo-starting-label">Starting from </strong>
									<span class="rezgo-cross-starting">'.$starting.'</span>
								</p>	
							';
						}

						echo '
							</div>
							<div class="col-xs-12 col-sm-12 col-md-3 pull-right rezgo-return-detail">
								<a href="" itemprop="url" class="btn rezgo-btn-detail btn-lg btn-block"><span>More details</span></a>
							</div>
							<div class="clearfix"></div>
						</div>
						';
		
					}
				
				}

			}
		
		?>    
    
    </div>
  </div>
    
	<div class="clearfix" style="height:10px;">&nbsp;</div>
</div>



<script>
		
	var related_clicks = 0;
		
	// load calendar for related item
	jQuery('.rezgo-cross-item').click(function(e){ 
	
		e.preventDefault();
		
		var com = jQuery(this).data('com');
		var name = jQuery(this).data('name');
        var url = jQuery(this).data('url');

        parent.parent.location.href = url;

		/*
		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: {
				action: 'rezgo',
				method: 'calendar',
				parent_url: '<?php echo $site->base; ?>',
				com: com,
				name: name,
				date: '<?php echo $_REQUEST['date']; ?>',
				wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
				headless: '1',
				cross_calendar: '1',
				security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
			},
			context: document.body,
			success: function(data) {
				
				jQuery('#rezgo-cross-calendar-bg').fadeOut('fast'); 
				jQuery('#rezgo-cross-calendar').fadeOut('fast'); 
				setTimeout(function () {
						jQuery('#rezgo-cross-calendar').html(data); 
				}, 200);				
				jQuery('#rezgo-cross-calendar').fadeIn('fast'); 
				
			}
		});
		
		related_clicks++;
		
		if (related_clicks === 1) {
			jQuery('#rezgo-cross-list').toggleClass('col-sm-7 col-sm-10'); 
			jQuery('#rezgo-cross-cal-box').toggleClass('col-sm-5 col-sm-2'); 
			jQuery('.rezgo-cross-overview').fadeOut();		
			jQuery('.rezgo-cross-image').toggleClass('col-sm-7 col-sm-4'); 
			jQuery('.rezgo-cross-text').toggleClass('col-sm-5 col-sm-8'); 
		}
		*/
		
	});

</script>
<div class="container-fluid rezgo-container">
	<div class="row">
		<?php if ($site->getPageContent('intro')) { ?>
			<div class="rezgo-intro col-xs-12">
				<?php echo $site->getPageContent('intro'); ?>
			</div>
		<?php } ?>

		<?php echo $site->getTemplate('topbar_order'); ?>

		<div class="col-xs-12" id="rezgo-list-content"></div>

		<div class="col-xs-12" id="rezgo-list-content-footer"></div>

		<div class="col-xs-12" id="rezgo-list-content-more">
			<button 
			type="button" 
			class="btn btn-default btn-lg btn-block" 
			id="rezgo-more-button" 
			data-rezgo-page="<?php echo $site->requestNum('pg'); ?>">
				<i class="fa fa-list"></i>
				<span>&nbsp;View more items &hellip;</span>
			</button>
		</div>

		<div class="col-xs-12" id="rezgo-list-content-bottom">
			<span>&nbsp;</span>
		</div>
	</div>
</div>

<script>
	var start = 1;
	var search_start_date = '<?php echo $site->requestStr('start_date'); ?>';
	var search_end_date = '<?php echo $site->requestStr('end_date'); ?>';
	var search_tags = '<?php echo $site->requestStr('tags'); ?>';
	var search_in = '<?php echo $site->requestStr('search_in'); ?>';
	var search_for = '<?php echo $site->requestStr('search_for'); ?>';
	var cid = '<?php echo $site->requestNum('cid'); ?>';

	jQuery(document).ready(function($){
		var $content = $('#rezgo-list-content');

		var $footer = $('#rezgo-list-content-footer');

		$.fn.imagesLoaded = function() {
			// get all the images (excluding those with no src attribute)
			var $imgs = this.find('img[src!=""]');
			// if there's no images, just return an already resolved promise
			if (!$imgs.length) {return $.Deferred().resolve().promise();}

			// for each image, add a deferred object to the array which resolves when the image is loaded (or if loading fails)
			var dfds = [];	
			$imgs.each(function(){
				var dfd = $.Deferred();
				dfds.push(dfd);
				var img = new Image();
				img.onload = function(){dfd.resolve();}
				img.onerror = function(){dfd.resolve();}
				img.src = this.src;
			});

			// return a master promise object which will resolve when all the deferred objects have resolved
			// IE - when all the images are loaded
			return $.when.apply($,dfds);
		};

		function getRezgoFeed() {
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: {
					action: 'rezgo',
					method: 'index_ajax',
					parent_url: '<?php echo $site->base; ?>',
					wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
					pg: start,
					start_date: search_start_date,
					end_date: search_end_date,
					tags: search_tags,
					search_in: search_in,
					search_for: search_for,
					cid: cid,
					security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
				},
				context: document.body,
				success: function(data) {
					$footer.html('');

					var split = data.split('|||');

					$content.append(split[0]);

					$('#rezgo-ajax-container-' + start).fadeIn('slow', function() {
						if (split[1] == 1) {
							$('#rezgo-list-content-more').show();
							start++;	
						}
					});

					if ('parentIFrame' in window) {
						setTimeout(function(){
							parentIFrame.size();
						}, 0);
					}
				}
			});
		}

		$footer.html('<div class="rezgo-wait-div"></div>');

		getRezgoFeed();

		$('#rezgo-more-button').click(function() {
			var page_num = $(this).attr('data-rezgo-page'); 

			$footer.html('<div class="rezgo-wait-div"></div>');

			$('#rezgo-list-content-more').fadeOut();

			getRezgoFeed();
		});
	});
</script> 
<div id="rezgo-404-container" class="container-fluid">

	<div class="rezgo-404-wrapper">

		<h1 id="rezgo-404-head"> Page not found </h1>
		<h3 id="rezgo-404-subheader">Sorry, we couldn't find the page you were looking for.</h3>

		<br>

		<form class="rezgo-404-search" role="form" onsubmit="<?php echo LOCATION_HREF?>='<?php echo $site->base;?>/keyword/'+$('#rezgo-404-search').val(); return false;" target="rezgo_content_frame">
			<div class="input-group rezgo-404-input-group">
				<input class="form-control" type="text" name="search_for" id="rezgo-404-search" placeholder="what were you looking for?" value="<?php echo stripslashes(htmlentities($_REQUEST['search_for']))?>" />
				<span class="input-group-btn">
					<button class="btn btn-primary rezgo-btn-default" type="submit" id="rezgo-search-button"><span>Search</span></button>
				</span>
			</div>
			<div class="rezgo-search-empty-warning" style="display:none">
				<span>Please enter a search term</span>
			</div>
		</form>

		<img id="page-not-found-search" src="<?php echo $site->path?>/img/page_not_found.svg" alt="Page not Found">

		<a class="return-home-link underline-link" href="/" role="button" target="_parent"><i class="fas fa-arrow-left" style="margin-right:5px;"></i> Return home</a>
	</div>

	<script>
		jQuery(document).ready(function($){	

			$("#rezgo-search-button").click(function(e){
				if( $('#rezgo-404-search').val() == '' ){
					e.preventDefault();
					$('.rezgo-search-empty-warning').show();
					$('#rezgo-404-search').addClass('has-error');
				}
			});
			$('#rezgo-404-search').change( function(){
				if( $(this).val() != '' ){
					$('.rezgo-search-empty-warning').hide();
					$(this).removeClass('has-error');
				}
			});
		});
	</script>
</div>
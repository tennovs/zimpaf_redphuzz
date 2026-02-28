<?php
	// any new page must start with the page_header, it will include the correct files
	// so that the rezgo parser classes and functions will be available to your templates

	// start a new instance of RezgoSite
	$site = new RezgoSite(sanitize_text_field($_REQUEST['sec']));
	// GET COMPANY DETAILS
    $company = $site->getCompanyDetails();
	// remove the 'mode=page_type' from the query string we want to pass on
	$_SERVER['QUERY_STRING'] = preg_replace("/([&|?])?mode=([a-zA-Z_]+)/", "", $_SERVER['QUERY_STRING']);

	if ($_REQUEST['title']) {
		$site->setPageTitle( sanitize_text_field($_REQUEST['title']) );
	} else {
		$site->setPageTitle( ucwords ( str_replace ( "page_", "", sanitize_text_field($_REQUEST['mode']) ) ) );
	}

	if ($_REQUEST['mode'] == 'page_details') {
		/*
			this query searches for an item based on a com id (limit 1 since we only want one response)
			then adds a $f (filter) option by uid in case there is an option id, and adds a date in case there is a date set	
		*/

		$trs	= 't=com';
		$trs .= '&q=' .sanitize_text_field($_REQUEST['com']);
		$trs .= '&f[uid]=' .sanitize_text_field($_REQUEST['option']);
		$trs .= '&d=' .sanitize_text_field($_REQUEST['date']);
		$trs .= '&limit=1';

		$item = $site->getTours($trs, 0);

		// if the item does not exist, we want to generate an error message and change the page accordingly
		if (!$item) {
			$item = new stdClass();
			$item->unavailable = 1;
			$item->name = 'Item Not Available'; 
		}

		if ($item->seo->seo_title != '') {
			$site->setPageTitle($item->seo->seo_title);
		} 
		else {
			$site->setPageTitle($item->item);
		}

		$site->setMetaTags('
			<meta name="description" content="' . $item->seo->introduction . '" /> 
			<meta property="og:title" content="' . $item->seo->seo_title . '" /> 
			<meta property="og:description" content="' . $item->seo->introduction . '" /> 
			<meta property="og:image" content="' . $item->media->image[0]->path . '" /> 
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
		');
	}

	elseif ($_REQUEST['mode'] == 'index') {
		// expand to include keywords and dates

		if ($_REQUEST['tags']) {
			$site->setPageTitle(ucwords(sanitize_text_field($_REQUEST['tags'])));
		}

		else {
			$site->setPageTitle('Home');
		}
	}
?>

<?php
    if($_REQUEST['mode'] == 'return_trip') {
        $iframe_height = '600px';
        // $iframe_height = '90vh';
    } elseif($_REQUEST['mode'] == 'booking_complete') {
        $iframe_height = '1600px';
    } else {
        $iframe_height = '900px';
    }
?>

<div id="rezgo_content_container" style="width:100%; height:100%;">
	<?php
	$src	= home_url();
	$src .= '?rezgo=1';
	$src .= '&mode='.sanitize_text_field($_REQUEST['mode']);
	$src .= '&com='.sanitize_text_field($_REQUEST['com']);
	$src .= '&parent_url='.$wp_current_page;
	$src .= '&wp_slug='.$wp_slug;
	$src .= '&tags='.sanitize_text_field($_REQUEST['tags']);
	$src .= '&search_for='.sanitize_text_field($_REQUEST['search_for']);
	$src .= '&start_date='.sanitize_text_field($_REQUEST['start_date']);
	$src .= '&end_date='.sanitize_text_field($_REQUEST['end_date']);
	$src .= '&date='.sanitize_text_field($_REQUEST['date']);
	$src .= '&rezgo_page='.sanitize_text_field($_REQUEST['rezgo_page']);
	$src .= '&option='.sanitize_text_field($_REQUEST['option']);
	$src .= '&review_link='.sanitize_text_field($_REQUEST['review_link']);
	$src .= '&review_item='.sanitize_text_field($_REQUEST['review_item']);
	$src .= '&cid='.sanitize_text_field($_REQUEST['cid']);
	$src .= '&trans_num='.sanitize_text_field($_REQUEST['trans_num']);
	$src .= '&card='.sanitize_text_field($_REQUEST['card']);
	$src .= '&page_title='.sanitize_text_field($site->pageTitle);
	$src .= '&seo_name='.$site->seoEncode($item->item);
	$src .= '&view='.sanitize_text_field($_REQUEST['view']);
	$src .= '&type='.sanitize_text_field($_REQUEST['type']);
	$src .= '&ids='.sanitize_text_field($_REQUEST['ids']);
	$src .= '&step='.sanitize_text_field($_REQUEST['step']);
	$src .= '&cart='.sanitize_text_field($_REQUEST['cart']);

	?>

	<?php 
		if ($_REQUEST['mode'] == '3DS') {
			foreach ($_REQUEST as $key => $val) {
				$src .= '&'.$key.'||3DS'.'='.sanitize_text_field($val);
			}
		}
	?>

	<iframe id="rezgo_content_frame" name="rezgo_content_frame" src="<?php echo $src; ?>" style="width:100%; 
	height:<?php echo $iframe_height; ?>; padding:0px; margin:0px;" frameBorder="0" scrolling="no"></iframe>
</div>

<script>
    iFrameResize({
        enablePublicMethods: true,
        scrolling: true,
        checkOrigin: false,
        messageCallback: function (msg) { // send message for scrolling
            var scroll_to = msg.message;
            jQuery('html, body').animate({
                scrollTop: scroll_to
            }, 600);
        }
    });
</script>

<?php if($_REQUEST['mode'] == 'page_order' || $_REQUEST['mode'] == 'page_book'|| $_REQUEST['mode'] == 'gift_card') { 

	   if($_REQUEST['mode'] == 'page_order') { 
			$modal_size = 'modal-xl';
			$modal_scroll = 'yes';
		} else {
			$modal_size = '';
			$modal_scroll = 'no';
		}

?>

<style type="text/css">
	#rezgo-modal-iframe {
		width: 100% !important;
	}
	<?php if($_REQUEST['mode'] == 'page_order') {  ?>
		#rezgo-modal{
			overflow-y: hidden;
		}
	<?php } ?> 
</style>

  <!-- waiver modal -->
  <div id="rezgo-modal" class="modal fade" role="dialog">
    <div class="modal-dialog <?php echo $modal_size; ?>">
      <div class="modal-content">
        <div class="modal-header">
          <?php if($_REQUEST['mode'] == 'page_order') {  ?>
          	<button type="button" class="btn btn-default" rel="" data-dismiss="modal" id="rezgo-cross-dismiss"><span>No Thank You</span></button>
				<?php if (REZGO_WORDPRESS) { ?>
					<!-- add hidden span to dismiss modal on outer container click -->
					<span id="parent-dismiss" class="hidden" data-dismiss="modal"></span>
				<?php } ?>
        	<?php } else { ?>
          	<button type="button" class="close" data-dismiss="modal">&times;</button>
        	<?php } ?>
           	<h4 id="rezgo-modal-title" class="modal-title"></h4>
        </div>
  
        <iframe id="rezgo-modal-iframe" frameborder="0" scrolling="<?php echo $modal_scroll; ?>" style="width:100%; padding:0px; margin:0px;"></iframe>
  
        <div id="rezgo-modal-loader" style="display:none">
          <div class="modal-loader"></div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>

  <?php if ((string) $company->gateway_id == 'tmt') { ?>
	<script src="https://payment.tmtprotects.com/tmt-payment-modal.3.6.0.js"></script>
  <?php } ?>

<?php } ?>

<script type="text/javascript" src="https://d31qbv1cthcecs.cloudfront.net/atrk.js"></script>

<script type="text/javascript">
	_atrk_opts = { atrk_acct: "51dve1aoim00G5", domain:"rezgo.com"};
	atrk();
</script>

<noscript>
	<img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=51dve1aoim00G5" style="display:none" height="1" width="1" alt="" />
</noscript>

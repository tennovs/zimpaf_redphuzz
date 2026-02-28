<?php 

$company = $site->getCompanyDetails(); 

if($site->isVendor()) { 
	$items = $site->getTours('t=com&q='.$_REQUEST['com']);
	foreach($items as $item) {
		$site->readItem($item);
	}
	$supplier = $site->getCompanyDetails($item->cid);
	$show_reviews = $supplier->reviews;
} else {
	$show_reviews = $company->reviews;
}

?>

<?php if ($_REQUEST['trans_num'] == 'all') { $_REQUEST['com'] = 'all'; } ?>

<script type="text/javascript" src="<?php echo $this->path; ?>/js/jquery.readmore.min.js"></script>

<div class="container-fluid rezgo-container">
  <div class="rezgo-content-row" id="rezgo-list-content">
		<?php if (!$_REQUEST['com'] || $_REQUEST['com'] == '') { 
			$site->sendTo($site->base."/reviews/all")
		?>
    <p class="lead" style="margin-top:60px;">You have not specified an item to review. Please check back later.</p>
    <?php } elseif ($show_reviews != 1) { ?>
    <p class="lead" style="margin-top:60px;">Reviews are not available at this time. Please check back later.</p>
    <?php } else { ?>
    
    <?php
    
      if ($_REQUEST['com'] != 'all') {
      
        $items = $site->getTours('t=com&q='.$_REQUEST['com']);
      
        if (count($items) >= 1) {
          
          foreach($items as $item) {
            $site->readItem($item);
          }
					
					$com_search = (int) $item->com;
            
          // prepare average star rating
          $star_rating_display = '';
          
          if($item->rating_count >= 1) {
                    
            $avg_rating = round(floatval($item->rating) * 2) / 2;	
            
            for($n=1; $n<=5; $n++) {
              if($avg_rating == ($n-0.5)) $star_rating_display .= '<i class="rezgo-star fas fa-star-half-alt rezgo-star-half"></i>';
              elseif($avg_rating >= $n) $star_rating_display .= '<i class="rezgo-star fa fa-star rezgo-star-full"></i>';
              else $star_rating_display .= '<i class="rezgo-star far fa-star rezgo-star-empty"></i>';
            }	
            
          ?>
          
          <h1 id="rezgo-review-head"><span>Verified Guest Reviews for <?php echo $item->item; ?></span></h1>
          <div id="rezgo-item-rating">
            <span>Average rating of <?php echo $avg_rating?>&nbsp;</span> 
            <span id="rezgo-item-star-rating"><?php echo $star_rating_display?></span>

            <!-- add sorting options here -->
            <div id="rezgo-sort-reviews" class="form-inline">
                <div class="select-container">
                  <label for="sort-review" class="rezgo-form-label"><span id="rezgo-sort-by">Sort By</span></label>
                    <select name="sort-review" id="sort-review" class="form-control">
                      <option selected="selected" value="rating">Rating</option>
                      <option value="date">Date</option>
                    </select>
                </div>
                
                <div class="select-container">
                  <label for="order-review" class="rezgo-form-label"><span id="rezgo-order-by">Order By</span></label>
                    <select name="order-review" id="order-review" class="form-control">
                      <option selected="selected" value="desc">Highest</option>
                      <option value="asc">Lowest</option>
                    </select>
                </div>
            </div>
          </div>
          
          <div id="rezgo-review-list"></div>
          <div id="rezgo-more-reviews"></div>
            
          <?php } else { ?>
            
          <p class="lead" style="margin-top:60px;">There are no reviews for <strong><?php echo $item->item; ?></strong> at this time. Please check back later.</p>	
                      
          <?php } // if($item->rating_count)
					
				} // if (count($item)
				
				$review_total = $item->rating_count;
        
      } else {
				
				$com_search = 'all';
				$review_total = 100; // set upper limit
        
			?>
			
        <h1 id="rezgo-review-head"><span>Verified Guest Reviews for <?php echo $company->company_name; ?></span></h1>

            <!-- add sorting options here -->
            <div id="rezgo-sort-reviews" class="form-inline all-reviews">
                <div class="select-container">
                  <label for="sort-review" class="rezgo-form-label"><span id="rezgo-sort-by">Sort By</span></label>
                    <select name="sort-review" id="sort-review" class="form-control">
                      <option selected="selected" value="rating">Rating</option>
                      <option value="date">Date</option>
                    </select>
                </div>
                
                <div class="select-container">
                  <label for="order-review" class="rezgo-form-label"><span id="rezgo-order-by">Order By</span></label>
                    <select name="order-review" id="order-review" class="form-control">
                      <option selected="selected" value="desc">Highest</option>
                      <option value="asc">Lowest</option>
                    </select>
                </div>
            </div>
        
        <div id="rezgo-review-list"></div>
        <div id="rezgo-more-reviews"></div>      
			
			<?php } // if ($_REQUEST['com'] != 'all') ?>
      
    <?php } // if (!$_REQUEST[com]) ?>
  </div>
</div>

<script>

  jQuery(function ($) {
		
		var limit = 10;
    var cid = '<?php echo $site->requestNum('cid'); ?>';
		
    // load the first set
    $.ajax({
      url: '<?php echo admin_url('admin-ajax.php'); ?>',
      data: {
          action: 'rezgo',
          method: 'reviews_ajax',
          parent_url: '<?php echo $site->base; ?>',
          wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
          view:'list',
          com: '<?php echo $com_search; ?>',
          type:'inventory',
          limit:limit,
          total:'<?php echo $review_total; ?>',
          cid: cid,
          security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
        },
      context: document.body,
      success: function(data) { 
        $('#rezgo-review-list').html(data); 
      }
    });	
		
    // hold current values
    var current_sort = $('#sort-review').val();
    var current_order = $('#order-review').val();   

    // sort by rating or date
    $('#sort-review').change(function(){

      var sort = $(this).val();
      current_sort = sort;
     
      $.ajax({
         url: '<?php echo admin_url('admin-ajax.php'); ?>',
         data: {
          action: 'rezgo',
          method: 'reviews_ajax',
          parent_url: '<?php echo $site->base; ?>',
          wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
          view:'list',
          com: '<?php echo $com_search; ?>',
          type:'inventory',
          limit:limit,
          total:'<?php echo $review_total; ?>',
          sort: sort,
          order: current_order,
          security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
        },
        context: document.body,
        success: function(data) {			
          // reload the list 
          $('#rezgo-review-list').empty(); 
          // empty more reviews if it was loaded
          $('#rezgo-more-reviews').empty(); 
          
          $('#rezgo-review-list').html(data);
          $('#rezgo-review-list').hide();
          $('#rezgo-review-list').fadeIn(); 
        }
      });	

    })

    // sort by chronological order
    $('#order-review').change(function(){
      var order = $(this).val();
      current_order = order;
     
      $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
         data: {
          action: 'rezgo',
          method: 'reviews_ajax',
          parent_url: '<?php echo $site->base; ?>',
          wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
          view:'list',
          com: '<?php echo $com_search; ?>',
          type:'inventory',
          limit:limit,
          total:'<?php echo $review_total; ?>',
          sort: current_sort,
          order: order,
          security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
        },
        context: document.body,
        success: function(data) {			
          // reload the list 
          $('#rezgo-review-list').empty(); 
          // empty more reviews if it was loaded
          $('#rezgo-more-reviews').empty(); 

          $('#rezgo-review-list').html(data);
          $('#rezgo-review-list').hide();
          $('#rezgo-review-list').fadeIn(); 
        }
      });	

    })
		
		// load each following set
		$('#rezgo-list-content').on('click', '#rezgo-load-more-reviews', function() { 
		
			var limit_plus = limit + ',10';
			limit = limit + 10;
		
			$.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        data: {
          action: 'rezgo',
          method: 'reviews_ajax',
          parent_url: '<?php echo $site->base; ?>',
          wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
          view:'list',
          com: '<?php echo $com_search; ?>',
          type:'inventory',
          limit:limit_plus,
          total:'<?php echo $review_total; ?>',
          sort:current_sort,
          order:current_order,
          security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
        },
				context: document.body,
				success: function(data) {				
					$('#rezgo-more-reviews-btn').remove(); 
					$('#rezgo-more-reviews').append(data); 	
				}
			});	
      
		});

  $('#sort-review').change(function(){
      // rename asc/desc wording based on date or rating
      var asc =  $('option[value="asc"]');
      var desc =  $('option[value="desc"]');

      if (current_sort == 'rating'){
        // alert('rating');
        desc.text('Highest');
        asc.text('Lowest');
      }
      else if (current_sort = 'date'){
        // alert('date');
        desc.text('Most Recent');
        asc.text('Earliest');
      }
    });


  });

</script>
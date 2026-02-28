<?php 
	$ta_key = '2E2B919141464E31B384DE1026A2DE7B';

	if ($_SESSION['promo']) {
		$promo = $_SESSION['promo'];
	} elseif ($_COOKIE['rezgo_promo']) {
		$promo = $_COOKIE['rezgo_promo'];
	} else {
		$promo = '';
	}	

?>

<div class="container-fluid rezgo-container">
	<?php
	if (isset($_REQUEST['option']) && trim($_REQUEST['option'])) {
		$option = '&f[uid]=' . sanitize_text_field($_REQUEST['option']);
	} else {
		$option = '';
	}
	if (isset($_REQUEST['date'])) {
		$date = '&d=' . sanitize_text_field($_REQUEST['date']);
	} else {
		$date = '';
	}
	if (isset($_REQUEST['com'])) {
		$com = sanitize_text_field($_REQUEST['com']);
	} else {
		$com = '';
	}
	?>

	<?php $items = $site->getTours('t=com&q='.$com.$option.$date); ?>

	<?php if (!$items) { ?>
		<?php if ($_REQUEST['review_link']) { ?>
			<div class="jumbotron" style="margin-top:40px; padding:40px 60px;">
				<p class="lead">We're sorry, <span style="font-weight: bold;"><?php echo $_REQUEST['review_item']?></span> is no longer available.</p>
				<p style="font-size: 18px; margin-bottom: 5px; margin-left: 4px;">You can search for it here</p>
				<form role="form" class="form-inline" onsubmit="top.location.href='<?php echo $site->base?>/keyword/'+jQuery('#rezgo-404-search').val(); return false;" target="rezgo_content_frame" style="height:34px;">
					<div class="col-lg-12 row">
						<div class="input-group" style="display:flex;">
							<input class="form-control" type="text" name="search_for" id="rezgo-404-search" value="<?php echo stripslashes(htmlentities($_REQUEST['review_item']))?>" />
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit" id="rezgo-search-button"><span>Search</span></button>
							</span>
						</div>
					</div>
				</form>
				<br>
				<h4><a href="javascript:history.back()">Or return to the reviews page</a></h4>
			</div>
		<?php } else { ?>
		<div class="rezgo-item-not-found">
			<h3 id="item-not-found-header">Item not found</h3>
			<h3 id="item-not-found-subheader">Sorry, the item you are looking for is not available or has no available options.</h3>
			<img id="item-not-found-img" src="<?php echo $site->path?>/img/item_not_found.svg" alt="Item Not Found">
			<a class="return-home-link underline-link" href="/"><i class="fas fa-arrow-left" style="margin-right:5px;"></i> Return home</a>
		</div>
		<?php } ?>
	<?php } else { ?>
		<?php 
		function date_sort($a, $b) {
			if ($a['start_date'] == $b['start_date']) {
				return 0;
			}

			return ($a['start_date'] < $b['start_date']) ? -1 : 1;
		}
		
		function recursive_array_search($needle,$haystack) {
			foreach ($haystack as $key=>$value) {
				$current_key=$key;
				if ($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
					return $current_key;
				}
			}
			return false;
		}

		$day_options = array();
		$single_dates = 0;
		$calendar_dates = 0;
		$open_dates = 0;
		$item_count = 1;

		foreach ($items as $item) {
			$site->readItem($item);

			$day_start = (int) $item->start_date;

			if (recursive_array_search($day_start, $day_options) === FALSE) {
				$day_options[(int) $item->uid]['start_date'] = $day_start;
			}

			// calendar availability types
			$calendar_selects = array('always', 'range', 'week', 'days');

			// open availability types
			$open_selects = array('never', 'number', 'specific');
			
			$date_selection = (string) $item->date_selection;

			// get option availability types (single, open or calendar)
			if ($date_selection == 'single') {
				$single_dates++; 
			} elseif (in_array($date_selection, $open_selects)) {
				$open_dates++; 
			} elseif (in_array($date_selection, $calendar_selects)) {
				$calendar_dates++;
			}

			// prepare media gallery
			if ($item_count == 1) { // we only need to grab it for the first item
				$media_count = $item->media->attributes()->value;
				$item_cutoff = $item->cutoff;
				// $media_items = '';
				// $indicators = '';

				if ($media_count > 0) {
					$m = 0;

					foreach ($site->getTourMedia($item) as $media) {
						if ($m == 0) {
							$pinterest_img_path = $media->path;
						}

						$indicators .= '
						<li data-target="#rezgo-img-carousel" data-slide-to="'.$m.'"'.($m==0 ? ' class="active"' : '').'></li>'."\n";

						$media_items .= '
							<div class="item'.($m==0 ? ' active' : '').'">
								<img src="'.$media->path.'" alt="'.$media->caption.'">
								<div class="carousel-caption">'.$media->caption.'</div>
							</div>
						';

						$m++;
					}
				}
			}

			$item_count++;
		}

		// resort by date
		usort($day_options, 'date_sort'); 

		// setup calendar start days
		$company = $site->getCompanyDetails();		
		// set defaults for start of availability
		$start_day = date('j', strtotime('+'.($item_cutoff + $company->time_format).' hours'));
		$open_cal_day = date('Y-m-d', strtotime('+'.($item_cutoff + $company->time_format).' hours'));

		// get the available dates
		$site->getCalendar($item->uid, sanitize_text_field($_REQUEST['date'])); 

		$cal_day_set = FALSE;
		$calendar_events = '';

		foreach ($site->getCalendarDays() as $day) {
			if ($day->cond == 'a') { $class = ''; } // available
			elseif ($day->cond == 'p') { $class = 'passed'; }
			elseif ($day->cond == 'f') { $class = 'full'; }
			elseif ($day->cond == 'i' || $day->cond == 'u') { $class = 'unavailable'; }
			elseif ($day->cond == 'c') { $class = 'cutoff'; }

			if ($day->date) { // && (int)$day->lead != 1
				$calendar_events .= '"'.date('Y-m-d', $day->date).'":{"class": "'.$class.'"},'."\n"; 
			}

			if ($_REQUEST['date']) {
				$request_date = strtotime(sanitize_text_field($_REQUEST['date']));
				$calendar_start = date('Y-m', $request_date);
				$start_day =	date('j', $request_date);
				$open_cal_day =	date('Y-m-d', $request_date);
				$cal_day_set = TRUE;
			} else {
				if ($day->date) {
					$calendar_start = date('Y-m', (int) $day->date);
				}

				// redefine start days
				if ($day->cond == 'a' && !$cal_day_set) {
					$start_day =	date('j', $day->date);
					$open_cal_day =	date('Y-m-d', $day->date);
					$cal_day_set = TRUE;
				}
			}
		}

		$calendar_events = trim($calendar_events, ','."\n");	
	
		if($site->isVendor()) { 
			$supplier = $site->getCompanyDetails($item->cid);
			$show_reviews = $supplier->reviews;
		} else {
			$show_reviews = $company->reviews;
		}
		
		// prepare average star rating
		$star_rating_display = '';
		
		if($show_reviews == 1 && $item->rating_count >= 1) {
							
			$avg_rating = round(floatval($item->rating) * 2) / 2;	
			
			for($n=1; $n<=5; $n++) {
				if($avg_rating == ($n-0.5)) $star_rating_display .= '<i class="rezgo-star fas fa-star-half-alt rezgo-star-half"></i>';
				elseif($avg_rating >= $n) $star_rating_display .= '<i class="rezgo-star fa fa-star rezgo-star-full"></i>';
				else $star_rating_display .= '<i class="rezgo-star far fa-star rezgo-star-empty"></i>';
			}	
			
		}
		
		?>

		<div class="row tour-details-title-wrp" itemscope itemtype="http://schema.org/Product">
			<div class="col-md-8 col-sm-7 col-xs-12">
			<h1 itemprop="name" id="rezgo-item-name">
				<span id="rezgo-item-name-text"><?php echo $item->item; ?></span>&nbsp;
        
				<?php if($item->rating_count >= 1) { ?>
					<span id="rezgo-item-star-rating" class="rezgo-show-reviews" data-toggle="tooltip" data-placement="right" title="Click to view reviews"><a href="<?php echo $site->base.'/reviews/item/'.$item->com?>"><?php echo $star_rating_display; ?></a></span>
					<span class="hidden" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" style="display:none;">
					<span class="hidden" itemprop="ratingValue"><?php echo $avg_rating; ?></span>
					<span class="hidden" itemprop="reviewCount"><?php echo $item->rating_count; ?></span>                
					</span>
				<?php } ?>
        
			</h1>
		</div>

			<div class="col-md-4 col-sm-5 col-xs-12">
				<div class="row">
				<div class="rezgo-cart-link-wrp col-xs-12 col-sm-5">
					<span>&nbsp;</span>

							<?php $cart = $site->getCart(); ?>
							<?php 
							if($cart) {
								echo '<a class="rezgo-cart-link badge" href="'.$site->base.'/order">
									<span><i class="far fa-shopping-cart"></i><span>'.count($cart).' item'.((count($cart) > 1) ? 's' : '').' </span><span class="hidden-xs">in your order</span></span>
								</a>';
							}
							?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8 col-sm-7 col-xs-12 rezgo-left-wrp">
				<?php if ($media_count > 0) { ?>
					<div id="rezgo-img-carousel" class="carousel slide" data-ride="carousel">		
						<ol class="carousel-indicators">
							<?php echo $indicators; ?>
						</ol>
						<div class="carousel-inner">
							<?php echo $media_items; ?>
						</div>
						<a class="left carousel-control" data-target="#rezgo-img-carousel" data-slide="prev">
							<i class="far fa-angle-left fa-3x"></i>
						</a>
						<a class="right carousel-control" data-target="#rezgo-img-carousel" data-slide="next">
							<i class="far fa-angle-right fa-3x"></i>
						</a>
					</div>
				<?php } ?>
			</div> 

			<div class="col-md-4 col-sm-5 col-xs-12 rezgo-right-wrp pull-right">
				<?php if ($open_dates > 0) { ?>
					<div class="rezgo-calendar-wrp">
						<div class="rezgo-open-header">
							<span>Open Options</span>
						</div>

						<div class="rezgo-open-container">
							<?php $open_date = date('Y-m-d', strtotime('+1 day')); ?>

							<div class="rezgo-open-options" id="rezgo-open-option-<?php echo $opt; ?>" style="display:none;">
								<div class="rezgo-open-selector" id="rezgo-open-date-<?php echo $opt; ?>"></div>

								<script type="text/javascript">
								jQuery(document).ready(function($){
									jQuery.ajax({
										url: '<?php echo admin_url('admin-ajax.php'); ?>',
										data: {
											action: 'rezgo',
											method: 'calendar_day',
											parent_url: '<?php echo $site->base; ?>',
											com: '<?php echo $item->com; ?>',
											date: '<?php echo $open_date; ?>',
											type: 'open',
											security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
										},
										context: document.body,
										success: function(data) {
											if (data.indexOf('rezgo-option-hide') == -1) {
												$('#rezgo-open-date-<?php echo $opt; ?>').html(data).slideDown('fast');

												$('#rezgo-open-option-<?php echo $opt; ?>').fadeIn('fast');
											}
										}
									});
								});
								</script>
							</div>

							<div id="rezgo-open-memo"></div>
						</div>
					</div>
				<?php } ?>

				<?php if ($calendar_dates > 0 || $single_dates > 10) { ?>
					<div class="hidden visible-xs">&nbsp;</div>

					<div class="rezgo-calendar-wrp">
						<div class="rezgo-calendar-header">
							<span>Choose a Date</span>
						</div>

						<div class="rezgo-calendar">
							<div class="responsive-calendar rezgo-calendar-<?php echo $item->com?>" id="rezgo-calendar">
								<div class="controls">
									<a class="pull-left" data-go="prev"><div class="far fa-angle-left fa-lg"></div></a>
									<h4><span><span data-head-year></span> <span data-head-month></span></span></h4>
									<a class="pull-right" data-go="next"><div class="far fa-angle-right fa-lg"></div></a>
								</div>

								<?php if ($company->start_week == 'mon') { ?>
									<div class="day-headers">
										<div class="day header">Mon</div>
										<div class="day header">Tue</div>
										<div class="day header">Wed</div>
										<div class="day header">Thu</div>
										<div class="day header">Fri</div>
										<div class="day header">Sat</div>
										<div class="day header">Sun</div>
									</div>
								<?php } else { ?>
									<div class="day-headers">
										<div class="day header">Sun</div>
										<div class="day header">Mon</div>
										<div class="day header">Tue</div>
										<div class="day header">Wed</div>
										<div class="day header">Thu</div>
										<div class="day header">Fri</div>
										<div class="day header">Sat</div>
									</div>
								<?php } ?>

								<div class="days" data-group="days"></div>
							</div>

							<div class="rezgo-calendar-legend">
								<span class="available">&nbsp;</span><span class="text-available"><span>&nbsp;Available&nbsp;&nbsp;</span></span>
								<span class="full">&nbsp;</span><span class="text-full"><span>&nbsp;Full&nbsp;&nbsp;</span></span>
								<span class="unavailable">&nbsp;</span><span class="text-unavailable"><span>&nbsp;Unavailable</span></span>
								<div id="rezgo-calendar-memo"></div>
							</div>

							<div id="rezgo-scrollto-options"></div>

							<div class="rezgo-date-selector" style="display:none;">
								<!-- available options will populate here -->
								<div class="rezgo-date-options"></div>
							</div>

							<div id="rezgo-date-script" style="display:none;">
								<!-- ajax script will be inserted here -->
							</div>
						</div>
					</div>
					<?php } elseif (($calendar_dates == 0 || $single_dates <= 10) && $open_dates == 0 ) { ?>
					<div class="rezgo-calendar-wrp">
						<?php $opt = 1; ?>

						<?php foreach ($day_options as $option) { ?>
							<div class="rezgo-calendar-single" id="rezgo-calendar-single-<?php echo $opt; ?>" style="display:none;">
								<div class="rezgo-calendar-single-head">
									<?php
										$available_day = date('D', $option['start_date']);
										$available_date = date((string) $company->date_format, $option['start_date']);
									?>
                  					<span class="rezgo-calendar-avail">
										<span>Availability&nbsp;for:</span>
									</span> 
									<strong><span class="rezgo-avail-day"><?php echo $available_day;?>,&nbsp;</span><span class="rezgo-avail-date"><?php echo $available_date; ?></span></strong>
								</div>

								<div class="rezgo-date-selector" id="rezgo-single-date-<?php echo $opt; ?>"></div>

								<script type="text/javascript">
									jQuery(document).ready(function($){
										jQuery.ajax({
											url: '<?php echo admin_url('admin-ajax.php'); ?>',
											data: {
												action: 'rezgo',
												method: 'calendar_day',
												parent_url: '<?php echo $site->base; ?>',
												com: '<?php echo $item->com; ?>',
												date: '<?php echo date('Y-m-d', $option['start_date']); ?>',
												option_num: '<?php echo $opt; ?>',
												type: 'single',
												security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
											},
											context: document.body,
											success: function(data) {
												if (data.indexOf('rezgo-order-none-available') == -1) {
													$('#rezgo-single-date-<?php echo $opt; ?>').html(data).slideDown('fast');
													$('#rezgo-calendar-single-<?php echo $opt; ?>').fadeIn('fast');
												}
											}
										});
									});
								</script> 
							</div>

							<?php $opt++; ?>
						<?php } // end foreach ($day_options) ?> 

						<div id="rezgo-single-memo"></div>
					</div><!-- // .rezgo-calendar-wrp -->
				<!-- // single day booking -->
				<?php } ?>
				
				<?php if (!$site->isVendor() && $site->getGateway()) { ?>
					<div id="rezgo-gift-link-use" class="rezgo-gift-link-wrp">
						<a class="rezgo-gift-link" href="<?php echo $site->base; ?>/gift-card">
							<span>
								<i class="far fa-gift fa-lg"></i>
								<span>&nbsp;Buy a gift card</span>
							</span>
						</a>
					</div>
				<?php } ?>

				<?php if (!$site->isVendor()) { ?>
					<div class="clear">
						<span>&nbsp;</span>
					</div>

					<div id="rezgo-details-promo" class="rezgo-promo-<?php echo $item->com?>"><!-- hidden by default -->
						<div class="rezgo-form-group-short">

							<?php $trigger_code = $site->cart_trigger_code ?>
							<?php if (!$promo && !$trigger_code) { ?>
								<form class="form-inline" id="rezgo-promo-form" role="form">
									<label for="rezgo-promo-code">
										<span>
											<i class="far fa-tags"></i>
											<span>&nbsp;</span>
											<span class="rezgo-promo-label">
												<span>Promo code</span>
											</span>
										</span>
									</label>
									<span>&nbsp;</span>
									<div class="input-group">
										<input type="text" class="form-control" id="rezgo-promo-code" name="promo" placeholder="Enter Promo Code" value="<?php echo $promo; ?>" required>
										<span class="input-group-btn">
											<button class="btn rezgo-btn-default" type="submit">
												<span>Apply</span>
											</button>
										</span>
									</div>

								<?php if($_SESSION['cart_status']) $cart_status =  new SimpleXMLElement($_SESSION['cart_status']);

									// cart only validates the promo code if there are items in the cart
									if ( ($cart_status->error_code == 9) ) { unset($_SESSION['promo']); ?>
										<div id ="rezgo-promo-invalid" class="text-danger" style="padding-top:5px; font-size:13px;">
											<span><?php echo $cart_status->message?></span>
										</div>

										<script>
											// reset invalid promo error so it doesn't show on order page again
											setTimeout(() => {
												jQuery.ajax({
													type: 'POST',
													url: '<?php echo admin_url('admin-ajax.php'); ?>' + '?action=rezgo&method=book_ajax',
													data: { rezgoAction: 'reset_cart_status'},
													success: function(data){
																// console.log('reset cart status session');
																jQuery('#rezgo-promo-code').val('');
																jQuery('#rezgo-promo-invalid').slideUp();
															},
													error: function(error){
															console.log(error);
															}
												});
											}, 3500);
										</script>
								<?php } ?>

								</form>
							<?php } else { ?>

								<div class="input-group">
									<label for="rezgo-promo-code">
									<i class="far fa-tags"></i>
									<span>&nbsp;</span>
									<span class="rezgo-promo-label">
										<span>Promo code</span>
									</span>
									</label>
									<span>&nbsp;</span>
									<span id="rezgo-promo-value"><?php echo ($promo) ? $promo : $trigger_code ?></span>
									<span>&nbsp;</span>
									<a id="rezgo-promo-clear" class="btn rezgo-btn-default btn-sm" href="<?php echo $_SERVER['HTTP_REFERER']; ?>/?promo=" target="_top">clear</a>
								</div>
								
							<?php } ?>
						</div>
					</div>
					<script>

					jQuery('#rezgo-promo-form').submit( function(e){
						e.preventDefault();
						top.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>?promo=' + jQuery('#rezgo-promo-code').val());

					});
								
					</script>

				<?php } // end promo form ?>
			</div>

			<div class="col-md-8 col-sm-7 col-xs-12 rezgo-left-wrp pull-left" id="rezgo-details">
				<?php if ($site->exists($item->details->highlights)) { ?>
					<div class="rezgo-tour-highlights"><?php echo $item->details->highlights; ?></div>
				<?php } ?>

				<div class="rezgo-tour-description">
					<?php if($site->exists($item->details->overview)) { ?>
						<div class="lead" id="rezgo-tour-overview"><?php echo $item->details->overview; ?></div>
					<?php } ?>

					<?php
						unset($location);
						if ($site->exists($item->location_name)) $location['name'] = $item->location_name;
						if ($site->exists($item->location_address)) $location['address'] = $item->location_address;
						if ($site->exists($item->city)) $location['city'] = $item->city;
						if ($site->exists($item->state)) $location['state'] = $item->state;
						if ($site->exists($item->country)) $location['country'] = ucwords($site->countryName(strtolower($item->country)));
					?>

					<?php if (count($location ? $location : []) > 0) { ?>
						<div id="rezgo-tour-location">
							<label id="rezgo-tour-location-label"><span>Location:&nbsp;</span></label>

							<?php 
							if ($location['address'] != '') {
								echo '
								'.($location['name'] != '' ? '<span class="rezgo-location-name">'.$location['name'].' - </span>' : '').'
								<span class="rezgo-location-address">'.$location['address'].'</span>';
								} else {
								echo '
								'.($location['city'] != '' ? '<span class="rezgo-location-city">'.$location['city'].', </span>' : '').'
								'.($location['state'] != '' ? '<span class="rezgo-location-state">'.$location['state'].', </span>' : '').'
								'.($location['country'] != '' ? '<span class="rezgo-location-country">'.$location['country'].'</span>' : '');
							} 
							?>
						</div>
					<?php } ?>

					<?php if ($site->isVendor()) { ?>
						<div id="rezgo-provided-by">
							<label id="rezgo-provided-by-label"><span>Provided by:&nbsp;</span></label>
							<a href="<?php echo $site->base; ?>/supplier/<?php echo $item->cid; ?>"><?php echo $site->getCompanyName($item->cid); ?></a>
						</div>
					<?php } ?>
				</div>

				<?php if (!$site->config('REZGO_MOBILE_XML')) {
					// add 'in' class to expand collapsible for non-mobile devices
					$mclass = 'in';
				} else {
					// add 'collapsed' class to change to default collapsed chevron for mobile devices
					$mcollapsed = ' collapsed';
				} ?>

				<div class="panel-group rezgo-desc-panel" id="rezgo-tour-panels">
					<?php if($site->exists($item->details->itinerary)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-itinerary">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#itinerary">
										<div class="rezgo-section-icon"><i class="far fa-bars fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Itinerary</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>
							<div id="itinerary" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->itinerary; ?></div>
							</div>
						</div>
					<?php } ?>

					<?php if ($site->exists($item->details->pick_up)) { ?> 
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-pickup">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#pickup">
										<div class="rezgo-section-icon"><i class="far fa-map-marker fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Pickup</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="pickup" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->pick_up; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->drop_off)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-dropoff">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#dropoff">
										<div class="rezgo-section-icon"><i class="far fa-location-arrow fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Drop Off</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="dropoff" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->drop_off; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->bring)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-thingstobring">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#thingstobring">
										<div class="rezgo-section-icon"><i class="far fa-suitcase fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Things To Bring</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="thingstobring" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->bring; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->inclusions)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-inclusion">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#inclusion">
										<div class="rezgo-section-icon"><i class="far fa-plus-square fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Inclusions</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="inclusion" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->inclusions; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->exclusions)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-exclusion">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#exclusion">
										<div class="rezgo-section-icon"><i class="far fa-minus-square fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Exclusions</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="exclusion" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->exclusions; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if($site->exists($item->details->checkin)) { ?> 
					<div class="panel panel-default rezgo-panel" id="rezgo-panel-checkin">
						<div class="panel-heading rezgo-section">
							<h4 class="panel-title">
								<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#checkin">
									<div class="rezgo-section-icon"><i class="far fa-check fa-lg"></i></div>
									<div class="rezgo-section-text"><span>Check-In</span></div>
									<div class="clearfix"></div>
								</a>
							</h4>
						</div>
						<div id="checkin" class="panel-collapse collapse<?php echo $mclass?>">
						<div class="panel-body rezgo-panel-body"><?php echo $item->details->checkin; ?></div>
						</div>
					</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->description)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-addinfo">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#addinfo">
										<div class="rezgo-section-icon"><i class="far fa-info-circle fa-lg"></i></div>
										<div class="rezgo-section-text"><span><?php echo $item->details->description_name; ?></span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="addinfo" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->description; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if ($site->exists($item->details->cancellation)) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-cancellation">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link<?php echo $mcollapsed?>" data-target="#cancellation">
										<div class="rezgo-section-icon"><i class="far fa-exclamation-circle fa-lg"></i></div>
										<div class="rezgo-section-text"><span>Cancellation Policy</span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="cancellation" class="panel-collapse collapse <?php echo $mclass; ?>">
								<div class="panel-body rezgo-panel-body"><?php echo $item->details->cancellation; ?></div>
							</div>
						</div> 
					<?php } ?>

					<?php if (count(is_countable($item->details->specifications->specification) ? $item->details->specifications->specification : []) >= 1) { ?>
						<?php $s=1; ?>

						<?php foreach ($item->details->specifications->specification as $spec) { ?>
							<?php $spec_id = $site->seoEncode($spec->name); ?>

							<div class="panel panel-default rezgo-panel rezgo-spec-panel" id="rezgo-spec-<?php echo $spec_id; ?>">
								<div class="panel-heading rezgo-section">
									<h4 class="panel-title">
										<a data-toggle="collapse" class="rezgo-section-link" data-target="#spec-<?php echo $s; ?>">
											<div class="rezgo-section-icon"><i class="far fa-circle fa-lg"></i></div>
											<div class="rezgo-section-text"><span><?php echo $spec->name; ?></span></div>
											<div class="clearfix"></div>
										</a>
									</h4>
								</div>

								<div id="spec-<?php echo $s; ?>" class="panel-collapse collapse <?php echo $mclass; ?>">
									<div class="panel-body rezgo-panel-body"><?php echo $spec->value; ?></div>
								</div>
							</div>

							<?php $s++; ?>
						<?php } ?>
					<?php } ?>
			
					<?php if($show_reviews == 1 && $item->rating_count >= 1) { ?>
						<div class="panel panel-default rezgo-panel" id="rezgo-panel-reviews">
						<div class="panel-heading rezgo-section">
							<h4 class="panel-title">
							<a data-toggle="collapse" class="rezgo-section-link collapsed" data-target="#reviews" id="reviews-load">
								<div class="rezgo-section-icon"><i class="far fa-star fa-lg"></i></div>
								<div class="rezgo-section-text"><span><?php echo $item->rating_count; ?> <span class="hidden-xxs">Verified </span><span class="hidden-xs">Guest </span> Review<?php echo ($item->rating_count > 1 ? 's' : ''); ?> </span>&nbsp;
								<span id="rezgo-rating-average" class="rezgo-show-reviews" data-toggle="tooltip" data-placement="right" title="Click to view reviews"><?php echo $star_rating_display; ?></span>
								</div>
								<div class="clearfix"></div>
							</a>
							</h4>
						</div>
						<div id="reviews" class="panel-collapse collapse">
							<div class="panel-body rezgo-panel-body" id="reviews-list">&nbsp;<div class="rezgo-wait-div"></div></div>
						</div>
						</div>
					<?php } ?>

					<?php if ($company->tripadvisor_url != '') { ?>
						<?php
						$ta_id = (string) $company->tripadvisor_url;
						$ta_api_url = 'http://api.tripadvisor.com/api/partner/2.0/location/'.$ta_id.'?key='.$ta_key;
						$ta_contents = $site->getFile($ta_api_url);
						$ta_json = json_decode($ta_contents);
						?>

						<div class="panel panel-default rezgo-panel" id="rezgo-panel-tripadvisor">
							<div class="panel-heading rezgo-section">
								<h4 class="panel-title">
									<a data-toggle="collapse" class="rezgo-section-link collapsed" data-target="#tripadvisor">
										<div class="rezgo-section-icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="25px" height="20px" preserveAspectRatio="xMidYMid meet" viewBox="0 0 576 512" ><path d="M528.91 178.82L576 127.58H471.66a326.11 326.11 0 0 0-367 0H0l47.09 51.24a143.911 143.911 0 0 0 194.77 211.91l46.14 50.2l46.11-50.17a143.94 143.94 0 0 0 241.77-105.58h-.03a143.56 143.56 0 0 0-46.94-106.36zM144.06 382.57a97.39 97.39 0 1 1 97.39-97.39a97.39 97.39 0 0 1-97.39 97.39zM288 282.37c0-64.09-46.62-119.08-108.09-142.59a281 281 0 0 1 216.17 0C334.61 163.3 288 218.29 288 282.37zm143.88 100.2h-.01a97.405 97.405 0 1 1 .01 0zM144.06 234.12h-.01a51.06 51.06 0 1 0 51.06 51.06v-.11a51 51 0 0 0-51.05-50.95zm287.82 0a51.06 51.06 0 1 0 51.06 51.06a51.06 51.06 0 0 0-51.06-51.06z" fill="currentColor"></path></svg>
										</div>
										<div class="rezgo-section-text"><span>TripAdvisor<span class="hidden-xxs"> Reviews</span></span></div>
										<div class="clearfix"></div>
									</a>
								</h4>
							</div>

							<div id="tripadvisor" class="panel-collapse collapse">
								<div class="panel-body rezgo-panel-body tripadvisor-panel-body">
									<div id="TA_selfserveprop753" class="TA_selfserveprop"></div>

									<script src="//www.jscache.com/wejs?wtype=selfserveprop&amp;uniq=753&amp;locationId=<?php echo $ta_id; ?>&amp;lang=en_US&amp;rating=true&amp;nreviews=4&amp;writereviewlink=true&amp;popIdx=true&amp;iswide=true&amp;border=true&amp;display_version=2"></script>
								</div>
							</div>
						</div>

						<style> 
							#CDSWIDSSP, #CDSWIDERR { width:100% !important; } 
							.widSSPData { border:none !important; }
							.widErrCnrs { display:none; }
							.widErrData { margin:1px }
							#CDSWIDERR.widErrBx .widErrData .widErrBranding dt { width: 100%; }
						</style>
					<?php } ?>
        
          <div class="clearfix" id="scroll_reviews">&nbsp;</div>
        
				</div>

				<?php if ($site->getTourRelated()) { ?>
					<div class="rezgo-related rezgo-related-details">
						<div class="rezgo-related-label">
							<span>Related products</span>
						</div>

						<?php foreach ($site->getTourRelated() as $related) { ?>
							<a href="<?php echo $site->base; ?>/details/<?php echo $related->com; ?>/<?php echo $site->seoEncode($related->name); ?>" class="rezgo-related-link"><?php echo $related->name; ?></a>

							<br />
						<?php } ?>
					</div>
				<?php } ?>
			</div>

			<div class="col-md-4 col-sm-5 col-xs-12 rezgo-right-wrp pull-right">
				<?php if (GOOGLE_API_KEY != '' && $site->exists($item->lat)) { ?>

					<?php 
          
          if (!$site->exists($item->zoom)) { 
            $map_zoom = 8; 
          } else { 
            $map_zoom = $item->zoom; 
          }
          
          if ($item->map_type == 'ROADMAP') {
            $embed_type = 'roadmap';
          } else {
            $embed_type = 'satellite';
          } 
          
          ?>
  
          <div style="position:relative;">
            <div class="rezgo-map" id="rezgo-tour-map">
              <iframe width="100%" height="500" frameborder="0" style="border:0;margin-bottom:0;margin-top:-105px;" src="https://www.google.com/maps/embed/v1/place?key=<?php echo GOOGLE_API_KEY?>&maptype=<?php echo $embed_type?>&q=<?php echo $item->lat?>,<?php echo $item->lon?>&center=<?php echo $item->lat?>,<?php echo $item->lon?>&zoom=<?php echo $map_zoom?>"></iframe>
            </div>
            <div class="rezgo-map-labels">
              <?php if($item->location_name != '') { ?>
                <div class="rezgo-map-marker pull-left">
                  <i class="far fa-map-marker"></i>
                </div>
                <span> <?php echo $item->location_name?></span>
                <div class="rezgo-map-hr"></div>
              <?php } ?>
              
              <?php if($item->location_address != '') { ?>
                <div class="rezgo-map-marker pull-left">
                  <i class="far fa-location-arrow"></i>
                </div>
                <span> <?php echo $item->location_address?></span>
                <div class="rezgo-map-hr"></div>
              <?php } else { ?>
                <div class="rezgo-map-marker pull-left">
                  <i class="far fa-location-arrow"></i>
                </div>
                <?php
                  echo '
                  '.($item->city != '' ? $item->city.', ' : '').'
                  '.($item->state != '' ? $item->state.', ' : '').'
                  '.($item->country != '' ? ucwords($site->countryName(strtolower($item->country))) : '');
                ?>
                <div class="rezgo-map-hr"></div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>

				<?php if (count($site->getTourTags()) > 0) { ?>
					<div id="rezgo-tour-tags">
						<label id="rezgo-tour-tags-label"><span>Tags:&nbsp;</span></label>
						<?php
							$taglist = '';
							foreach($site->getTourTags() as $tag) { 
								if ($tag != '') {
									$taglist .= '<a href="'.$site->base.'/tag/'.urlencode($tag).'">'.$tag.'</a>, ';
								}
							}
							$taglist = trim($taglist, ', ');
							echo $taglist;
						?>
					</div>
				<?php } ?>
			</div>
		</div>

		<script type="text/javascript">
		jQuery(document).ready(function($){

			// current JS timestamp
			let js_timestamp = Math.round(new Date().getTime()/1000);
			let js_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

			// function returns Y-m-d date format
			(function(){
				Date.prototype.toYMD = Date_toYMD;

				function Date_toYMD() {
					let year, month, day;

					year = String(this.getFullYear());

					month = String(this.getMonth() + 1);

					if (month.length == 1) {
						month = "0" + month;
					}

					day = String(this.getDate());

					if (day.length == 1) {
						day = "0" + day;
					}

					return year + "-" + month + "-" + day;
				}
			})();

			// new Date() object for tracking months
			let rezDate = new Date('<?php echo $calendar_start; ?>-15');

			function addLeadingZero(num) {
				if (num < 10) {
					return "0" + num;
				} else {
					return "" + num;
				}
			}

			// only animate month changes if not using Safari
			let isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;

			if (isSafari) {
				monthAnimate = false;
			} else {
				monthAnimate = false;
			}

			let slideSpeed = 250;
			$('.responsive-calendar').responsiveCalendar({
				time: '<?php echo $calendar_start; ?>', 
				startFromSunday: <?php echo (($company->start_week == 'mon') ? 'false' : 'true') ?>,
				allRows: false,
				monthChangeAnimation: monthAnimate,

				onDayClick: function(events) {
					$('.days .day').each(function () {
							$(this).removeClass('select');
					});
					$(this).parent().addClass('select');

					let this_date, this_class;

					this_date = $(this).data('year')+'-'+ addLeadingZero($(this).data('month')) +'-'+ addLeadingZero($(this).data('day'));

					this_class = events[this_date].class;

					if (this_class == 'passed') {
						//jQuery('.rezgo-date-selector').html('<p class="lead">This day has passed.</p>').show();
					} else if (this_class == 'cutoff') {
						//jQuery('.rezgo-date-selector').html('<p class="lead">Inside the cut-off.</p>').show();
					} else if (this_class == 'unavailable') {
						//jQuery('.rezgo-date-selector').html('<p class="lead">No tours available on this day.</p>').show();
					} else if (this_class == 'full') {
						//jQuery('.rezgo-date-selector').html('<p class="lead">This day is fully booked.</p>').show();
					} else {
						$('.rezgo-date-options').html('<div class="rezgo-date-loading"></div>');

						if ($('.rezgo-date-selector').css('display') == 'none') {
							$('.rezgo-date-selector').slideDown('fast');
						}

						$('.rezgo-date-selector').css('opacity', '0.4');

						$.ajax({
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							data: {
								action: 'rezgo',
								method: 'calendar_day',
								parent_url: '<?php echo $site->base; ?>',
								com: '<?php echo $item->com; ?>',
								date: this_date,
								type: 'calendar',
								js_timestamp: js_timestamp,
								js_timezone: js_timezone,
								security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
							},
							context: document.body,
							success: function(data) {
								$('.rezgo-date-selector').html(data).css('opacity', '1');
								$('.rezgo-date-options').show();

							}
						});
					}
				},
				onMonthChange: function(events) { 
					rezDate.setMonth(rezDate.getMonth() + 1);
					let rezNewMonth = rezDate.toYMD();

					$.ajax({
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: {
							action: 'rezgo',
							method: 'calendar_month',
							uid: '<?php echo $item->uid; ?>',
							com: '<?php echo $item->com; ?>',
							date: rezNewMonth,
							security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
						},
						context: document.body,
						success: function(data) {
							$('#rezgo-date-script').html(data); 
						}
					});
				},
				events: {
					<?php echo $calendar_events; ?>
				}
			});

			<?php if (($calendar_dates > 0 || $single_dates > 10) && $cal_day_set === TRUE) { ?>
				// open the first available day
				$('.rezgo-date-options').html('<div class="rezgo-date-loading"></div>');

				if ($('.rezgo-date-selector').css('display') == 'none') {
					$('.rezgo-date-selector').show();
				}

				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'rezgo',
						method: 'calendar_day',
						parent_url: '<?php echo $site->base; ?>',
						com: '<?php echo $item->com; ?>',
						date: '<?php echo $open_cal_day; ?>',
						id: '<?php echo sanitize_text_field($_REQUEST['option']); ?>',
						type: 'calendar',
						js_timestamp: js_timestamp,
						security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
					},
					context: document.body,
					success: function(data) {
						$('.rezgo-date-selector').html(data).css('opacity', '1');
						$('.rezgo-date-options').show();
						$('.active [data-day="<?php echo $start_day; ?>"]').parent().addClass('select');
						$('.option-panel-<?php echo $_REQUEST['option']?>').addClass('in');	

					}
				});
				// end open first day
			<?php } ?>

			// handle short url popover
			$('*[data-ajaxload]').bind('click',function() {
				let e = $(this);

				e.unbind('click');

				$.get(e.data('ajaxload'),function(d){
					e.popover({
						html : true,
						title: false,
						placement: 'left',
						content: d,
					}).popover('show');
				});
			});

			$('body').on('click', function (e) {
				$('[data-toggle="popover"]').each(function () {
					if (!$(this).is(e.target) && e.target.id != 'rezgo-short-url' && $(this).has(e.target).length === 0) {
						$(this).popover('hide');
					}
				});
			});

			// prevent map float left
			$(window).resize(function() {
				let bodyWidth = $(document).width();

				let rightColumnHeight = $('.rezgo-right-wrp').height();

				if (bodyWidth > 760) {
					$("#rezgo-details").css({'min-height' : rightColumnHeight + 'px'});
				} else {
					$("#rezgo-details").css({'min-height' : 0});
				}
			});

			// get reviews from panel click
			$('#reviews-load').click(function(e){ 
			
				e.preventDefault();
				
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
				    data: {
				          action: 'rezgo',
				          method: 'reviews_ajax',
				          parent_url: '<?php echo $site->base; ?>',
				          wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
				          view:'details',
				          com: '<?php echo $item->com; ?>',
				          type:'inventory',
				          limit:5,
				          total:'<?php echo $item->rating_count; ?>',
				          //cid: cid,
				          security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
				        },
					// url: '<?php echo $site->base;?>/reviews_ajax.php?action=get&view=details&com=<?php echo $item->com;?>&type=inventory&limit=5&total=<?php echo $item->rating_count;?>',
					context: document.body,
					success: function(data) {
						
						$('#reviews-list').fadeOut(); 
						setTimeout(function () {
								$('#reviews-list').html(data); 
						}, 500);								
						$('#reviews-list').fadeIn('slow'); 
						
					}
				});
				
			});

			$('.rezgo-show-reviews').tooltip();
			
		});
		</script>
	<?php } ?>
</div>
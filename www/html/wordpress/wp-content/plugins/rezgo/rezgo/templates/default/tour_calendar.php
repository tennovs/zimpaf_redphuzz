
<!-- fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700">
<!-- calendar.css -->
<link href="<?php echo $this->path?>/css/responsive-calendar.css" rel="stylesheet">
<link href="<?php echo $this->path?>/css/responsive-calendar.rezgo.css?v=<?php echo REZGO_VERSION?>" rel="stylesheet">

<script type="text/javascript" src="<?php echo $this->path?>/js/responsive-calendar.min.js"></script>

<div class="tour-details-wrp container-fluid rezgo-container">

<?php
// setup calendar start days
$company = $site->getCompanyDetails();

$request_timestamp = strtotime($_REQUEST['date']);
$use_date = FALSE;
$use_opened_day = TRUE;
$date_search = '';
$item_cutoff = 0;
	
$items_check = $site->getTours('t=com&q='.$_REQUEST['com'].'&f[uid]='.$_REQUEST['option']);

if (!empty($items_check)) {
	
	foreach ($items_check as $check) {
		
		if ((int) $check->cutoff > $item_cutoff) {
			$item_cutoff = (string) $check->cutoff;
		}
		
		if ( (string) $check->date_selection == 'days' || (string) $check->date_selection == 'week' ) { 
			$use_opened_day = FALSE; 
		}
		
		if ( (string) $check->availability_type == 'open' || (string) $check->availability_type == '' || (string) $check->date_selection == 'days' || (string) $check->date_selection == 'week' ) { 
			continue; 
		}
		
		if ($request_timestamp >= (int) $check->start_date && $request_timestamp <= (int) $check->end_date) {
			$use_date = TRUE;
			break;
		}
		
	}
}

$adjusted_timestamp = strtotime('+'.($item_cutoff + $company->time_format).' hours');

if ($_REQUEST['date'] == 'open') {
	unset($_REQUEST['date']);
}

// adjust date if requested date falls within cutoff
if ($adjusted_timestamp > $request_timestamp) {
	$request_timestamp = $adjusted_timestamp;
}

if ($use_date) {
	$date_search = date('Y-m-d', $request_timestamp);
}

$items = $site->getTours('t=com&q='.$_REQUEST['com'].'&f[uid]='.$_REQUEST['option'].'&d='.$date_search);

if(!$items) {
	// try new date if cart date not available
	$date_retry = date('Y-m-d', $adjusted_timestamp);
	$use_opened_day = FALSE; 
	$items = $site->getTours('t=com&q='.$_REQUEST['com'].'&f[uid]='.$_REQUEST['option'].'&d='.$date_retry);
}

if(!$items) { ?>
  
  <div class="row">
    <div class="col-xs-12">
      <h3 class="rezgo-return-head"><?php echo stripslashes($_REQUEST['name']) ?></h3>
      <p class="lead">This item is not currently available or has no available options.</p>
    </div>
  </div>
  
<?php } else { ?>
	<?php 
	function date_sort($a, $b) {
		if ($a['start_date'] == $b['start_date']) {
				return 0;
		}
		return ($a['start_date'] < $b['start_date']) ? -1 : 1;
	}

	function recursive_array_search($needle,$haystack) {
		foreach($haystack as $key=>$value) {
				$current_key=$key;
				if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
					return $current_key;
				}
		}
		return false;
	}	

	$day_options = array();
	$single_dates = 0;
	$calendar_dates = 0;
	$open_dates = 0;

	foreach($items as $item) {
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
		
	}

	// resort by date
	usort($day_options, 'date_sort'); 

	// set defaults for start of availability
	$start_day = date('j', strtotime('+'.($item_cutoff + $company->time_format).' hours'));
	$open_cal_day = date('Y-m-d', strtotime('+'.($item_cutoff + $company->time_format).' hours'));

	// get the available dates
	$site->getCalendar($item->uid, $_REQUEST['date']); 

	$cal_day_set = FALSE;
	$calendar_events = '';

	foreach($site->getCalendarDays() as $day) {
		
		if ($day->cond == 'a') { $class = ''; } // available
		elseif ($day->cond == 'p') { $class = 'passed'; }
		elseif ($day->cond == 'f') { $class = 'full'; }
		elseif ($day->cond == 'i' || $day->cond == 'u') { $class = 'unavailable'; }
		elseif ($day->cond == 'c') { $class = 'cutoff'; }
		
		if ($day->date) { // && (int)$day->lead != 1
			$calendar_events .= '"'.date('Y-m-d', $day->date).'":{"class": "'.$class.'"},';
		}
		
		if ($_REQUEST['date']) {
			//$request_date = strtotime($_REQUEST['date']);
			$calendar_start = date('Y-m', $request_timestamp);
			$start_day =	date('j', $request_timestamp);
			if ($use_opened_day) {
				$open_cal_day =	date('Y-m-d', $request_timestamp);
				$cal_day_set = TRUE;
			}
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

	$calendar_events = trim($calendar_events, ','); 
	?>

	<div class="row">
		
		<div class="col-xs-12">	
    
    	<h3 class="rezgo-return-head"><?php echo $item->item?></h3>
      <div class="clearfix" id="rezgo-cross-cal-top">&nbsp;</div> <!--  style="height:0;" -->
      
			<?php if ((int) $open_dates > 0) { ?>
				<div class="rezgo-calendar-wrp">
					<div class="rezgo-calendar-header-empty">&nbsp;</div>
					<div class="rezgo-open-container">
						<?php $open_date = date('Y-m-d', strtotime('+1 day')); ?>
					
						<div class="rezgo-open-options cross-sell_add-border" id="rezgo-open-option-<?php echo $opt?>" style="display:none;">
							<div class="rezgo-open-selector" id="rezgo-open-date-<?php echo $opt?>"></div>

							<script type="text/javascript">						
								jQuery(document).ready(function(){
									jQuery.ajax({
										url: '<?php echo admin_url('admin-ajax.php'); ?>',
										data: {
											action: 'rezgo',
											method: 'calendar_day',
											parent_url: '<?php echo $site->base; ?>',
											com: '<?php echo $item->com; ?>',
											date: '<?php echo $open_date; ?>',
											type: 'open',
											<?php if ($_REQUEST['view'] != 'calendar') { ?>
											cross_sell: 1,
											<?php } ?>
											wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
											security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
										},
										context: document.body,
										success: function(data) {
											if (data.indexOf('rezgo-order-none-available') == -1) {
												jQuery('#rezgo-open-date-<?php echo $opt?>').html(data).slideDown('fast');
												jQuery('#rezgo-open-option-<?php echo $opt?>').fadeIn('fast');
											}										
											
										}
									});
								});
							</script> 
						</div>
					
						<div id="rezgo-open-memo"></div>
					</div>
				</div>
			<?php } // end if $open_dates > 0 ?>

			<?php if ( $calendar_dates > 0 || $single_dates > 10 ) { ?>
				<div class="hidden visible-xs">
					<span>&nbsp;</span>
				</div>

				<div class="rezgo-calendar-wrp">
					<div class="rezgo-calendar-header-empty">&nbsp;</div>
					<div class="rezgo-calendar cross-sell_add-border">
						<div class="responsive-calendar cross-sell_add-border" id="rezgo-calendar">
							<div class="controls">
								<a class="pull-left" data-go="prev"><div class="fal fa-angle-left fa-lg"></div></a>
								<h4><span><span data-head-year></span> <span data-head-month></span></span></h4>
								<a class="pull-right" data-go="next"><div class="fal fa-angle-right fa-lg"></div></a>
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
			<?php } elseif ( ($calendar_dates == 0 || $single_dates <= 10) && $open_dates == 0 ) { // single day options ?>
				<div class="rezgo-calendar-wrp">
					<?php $opt = 1; // pass an option counter to calendar day ?>

					<?php foreach ($day_options as $option) { ?>
						<div class="rezgo-calendar-single" id="rezgo-calendar-single-<?php echo $opt?>" style="display:none;">
  						<div class="rezgo-calendar-single-head">
                <?php
                $available_day = date('D', $option['start_date']);
                $available_date = date((string) $company->date_format, $option['start_date']);
                ?>
                <span class="rezgo-calendar-avail">
                  <span>Availability&nbsp;for:&nbsp;</span>
                </span>
                <strong><span class="rezgo-avail-day"><?php echo $available_day?>,&nbsp;</span><span class="rezgo-avail-date"><?php echo $available_date?></span></strong>
              </div>

  						<div class="rezgo-date-selector" id="rezgo-single-date-<?php echo $opt?>"></div>
						
						  <script type="text/javascript">
							jQuery(document).ready(function(){
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
											<?php if ($_REQUEST['view'] != 'calendar') { ?>
											cross_sell: 1,
											<?php } ?>
											wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
											security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
										},
									context: document.body,
									success: function(data) {
										
										if (data.indexOf('rezgo-order-none-available') == -1) {
											jQuery('#rezgo-single-date-<?php echo $opt?>').html(data).slideDown('fast');
											jQuery('#rezgo-calendar-single-<?php echo $opt?>').fadeIn('fast');
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
			<?php } // end single dates > 0 ?>

		</div>

	</div>
  <div class="clearfix" id="rezgo-cross-cal-bottom" >&nbsp;</div> <!-- style="height:0;" -->
 
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			// current JS timestamp
			var js_timestamp = Math.round(new Date().getTime()/1000);
			
			// function returns Y-m-d date format
			(function() {
					Date.prototype.toYMD = Date_toYMD;
					function Date_toYMD() {
							var year, month, day;
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
			var rezDate = new Date('<?php echo $calendar_start?>-15');			
			
			function addLeadingZero(num) {
				if (num < 10) {
					return "0" + num;
				} else {
					return "" + num;
				}
			}
			
			// only animate month changes if not using Safari
			var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
			
			if (isSafari) {
				monthAnimate = false;
			} else {
				monthAnimate = false;
			}
			jQuery('.responsive-calendar').responsiveCalendar({
				
				time: '<?php echo $calendar_start?>', 
				startFromSunday: <?php echo (($company->start_week == 'mon') ? 'false' : 'true') ?>,
				allRows: false,
				monthChangeAnimation: monthAnimate,
									
				onDayClick: function(events) { 

					jQuery('.days .day').each(function () {
						jQuery(this).removeClass('select');
					});
					jQuery(this).parent().addClass('select');
					
					var this_date, this_class;
					
					this_date = jQuery(this).data('year')+'-'+ addLeadingZero(jQuery(this).data('month')) +'-'+ addLeadingZero(jQuery(this).data('day'));
					
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
													
						jQuery('.rezgo-date-options').html('<div class="rezgo-date-loading"></div>');
						
						if(jQuery('.rezgo-date-selector').css('display') == 'none') {
							jQuery('.rezgo-date-selector').slideDown('fast');
						}
					
						jQuery('.rezgo-date-selector').css('opacity', '0.4');
						
						jQuery.ajax({
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							data: {
								action: 'rezgo',
								method: 'calendar_day',
								parent_url: '<?php echo $site->base; ?>',
								com: '<?php echo $item->com; ?>',
								date: this_date,
								type: 'calendar',
								js_timestamp: js_timestamp,
								<?php if ($_REQUEST['view'] != 'calendar') { ?>
								cross_sell: 1,
								<?php } ?>
								wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>', 
								security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
							},
							context: document.body,
							success: function(data) {
								
								jQuery('.rezgo-date-selector').html(data).css('opacity', '1');
								jQuery('.rezgo-date-options').show();

							}
						});
						
					}
				
				},
									
				onActiveDayClick: function(events) { 
				
					jQuery('.days .day').each(function () {
							jQuery(this).removeClass('select');
					});
					
					jQuery(this).parent().addClass('select');
				
				},
				
				onMonthChange: function(events) { 
				
					// first hide any options below ...
					// jQuery('.rezgo-date-selector').slideUp('slow');
					
					/*rezDate.setMonth(rezDate.getMonth() + 1);
					var rezNewMonth = rezDate.toYMD();*/
					
					var newMonth = this.currentYear + '-' + addLeadingZero(this.currentMonth + 1) + '-15';
							
					jQuery.ajax({
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: {
							action: 'rezgo',
							method: 'calendar_month',
							uid: '<?php echo $item->uid; ?>',
							com: '<?php echo $item->com; ?>',
							date: newMonth,
							security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
						},
						context: document.body,
						success: function(data) {
							jQuery('#rezgo-date-script').html(data); 
						}
					});
				
				},
				
				events: {
					<?php echo $calendar_events?>				
				}
					
			});			
			
			<?php if ( ( $calendar_dates > 0 || $single_dates > 10 ) && $cal_day_set === TRUE ) { ?>
			// open the first available day			
			jQuery('.rezgo-date-options').html('<div class="rezgo-date-loading"></div>');
			
			if(jQuery('.rezgo-date-selector').css('display') == 'none') {
				jQuery('.rezgo-date-selector').slideDown('fast');
			}
			
			jQuery.ajax({
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
						<?php if ($_REQUEST['view'] != 'calendar') { ?>
						cross_sell: 1,
						<?php } ?>
						wp_slug: '<?php echo $_REQUEST['wp_slug']; ?>',
						security: '<?php echo wp_create_nonce('rezgo-nonce'); ?>'
					},
				context: document.body,
				success: function(data) {
					jQuery('.rezgo-date-selector').html(data).css('opacity', '1');
					jQuery('.rezgo-date-options').fadeIn('slow');	
					jQuery('.active [data-day="<?php echo $start_day?>"]').parent().addClass('select');			
					
				}
			});
			// end open first day
			<?php } ?>		
			
			if (jQuery(document).width() <= 762) {
				jQuery('html, body').animate({
						scrollTop: (jQuery('#rezgo-cross-cal-top').offset().top)
				},500);		
			}
								
		});
		
	</script>
<?php } ?>
</div>
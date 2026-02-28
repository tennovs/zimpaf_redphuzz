<?php
function rezgo_iframe($args) {
	global $wp;

	// the pagename can hide under a couple different names
	if (isset($wp->query_vars['name']) && !empty($wp->query_vars['name'])) {
		$wp_current_page = $wp->query_vars['name'];
	} else {
		$wp_current_page = $wp->query_vars['pagename'];
	}

	parse_str($wp->matched_query, $matched_query);

	foreach($matched_query as $k => $v) {
		$_REQUEST[$k] = urldecode($v);
	}

	if (empty($_REQUEST['mode'])){
		$_REQUEST['mode'] = 'index';
	}

	if ($args) {
		foreach($args as $k => $v) {
			if (!$_REQUEST[$k]) {
				$_REQUEST[$k] = $v;
			}
		}
	}

	// unset request refID if already stored in session
	if ($_REQUEST['refid']) {
		$sid = session_id();
		if(empty($sid)) session_start();
		if (($_REQUEST['refid'] === $_SESSION['rezgo_refid_val']) || ($_REQUEST['refid'] === $_COOKIE['rezgo_refid_val'])) {
			unset($_REQUEST['refid']);
		}
	}
	
	// unset requested promo code if already stored in session
	if ($_REQUEST['promo']) {
		$sid = session_id();
		if(empty($sid)) session_start();
		if (($_REQUEST['promo'] === $_SESSION['promo']) || ($_REQUEST['promo'] === $_COOKIE['promo'])) {
			unset($_REQUEST['promo']);
		}
	}

	// save unaltered page slug
	$wp_slug = $wp_current_page;

	// page slug override
	if (isset($_REQUEST['slug']) && !empty($_REQUEST['slug'])) {
		// special handling for multisite subdir & slug override
		if (is_multisite() && !SUBDOMAIN_INSTALL) {
			if ( is_front_page() ) {
				$wp_current_page = $wp_slug.$_REQUEST['slug'];
			} else {
				$wp_current_page = str_replace( DOMAIN_CURRENT_SITE.'/', '', REZGO_WP_DIR ) .'/'. $_REQUEST['slug'];
			}
		} else {
			$wp_current_page = $_REQUEST['slug'];
		}
	}

	// account for subdirectory installs
	if (REZGO_WP_DIR != $_SERVER['HTTP_HOST'] && strpos(REZGO_WP_DIR, $_SERVER['HTTP_HOST']) !== FALSE && !is_multisite()) {
		$wp_current_page = str_replace($_SERVER['HTTP_HOST'].'/', '', REZGO_WP_DIR).'/'.$wp_current_page;
	}

    if (is_multisite() && !SUBDOMAIN_INSTALL) {
		$site_details = get_blog_details(get_current_blog_id());
		$site_slug = str_replace('/','',$site_details->path);

        // account for network primary site
		if ( is_main_site() ){
			$wp_current_page = $_REQUEST['slug'];
		} else {
			if (is_front_page()){
				$wp_current_page = $site_slug.'/'.$_REQUEST['slug'];
			} else {
				$wp_current_page = $site_slug.'/'.$wp_slug;
			}
		}
    }

	// after parsing shortcode vars, catch rezgo_page and set mode if true
	// only override to details if mode is currently index
	if (isset($_REQUEST['rezgo_page']) && $_REQUEST['rezgo_page'] == 'tour_details' && $_REQUEST['mode'] == 'index') {
		$_REQUEST['mode'] = 'page_details';
	}

	if ($_REQUEST['rezgo_page'] != 'booking_complete_print') {
		$rezgo_content = rezgo_return_file('frame.php', array(
			'wp_current_page' => $wp_current_page,
			'wp_slug' => $wp_slug
		));
	}

	return $rezgo_content;
}
function rezgo_add_rewrite_rules($wp_rewrite) {
	$new_rules = array (
		// tour details page (general)
		'(.+?)/details/([0-9]+)/([^\/]+)/?$'
		=> 'index.php?pagename=$matches[1]&com=$matches[2]&mode=page_details',

		// tour details page (date and option selected)
		'(.+?)/details/([0-9]+)/([^\/]+)/([0-9]+)/([^\/]+)/?$'
		=> 'index.php?pagename=$matches[1]&com=$matches[2]&option=$matches[4]&date=$matches[5]&mode=page_details',

		'(.+?)/tag/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=index&tags=$matches[2]',

		'(.+?)/keyword/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=index&search_in=smart&search_for=$matches[2]',

		'(.+?)/supplier/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=index&cid=$matches[2]',

		'(.+?)/order/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_order',

		// order page with cart token included
		'(.+?)/order/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_order&cart=$matches[2]',

		'(.+?)/book/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_book&sec=1&step=1', 

		// book page with cart token included
		'(.+?)/book/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_book&sec=1&step=1&cart=$matches[2]',

		'(.+?)/confirm/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_book&sec=1&step=2',

		'(.+?)/complete/([^\/]*)/print/?$'
		=> 'index.php?pagename=$matches[1]&mode=booking_complete_print&trans_num=$matches[2]',

		'(.+?)/complete/([^\/]*)/pdf/?$'
		=> 'index.php?pagename=$matches[1]&mode=booking_complete_pdf&trans_num=$matches[2]',

		'(.+?)/complete/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=booking_complete&trans_num=$matches[2]',

		'(.+?)/voucher/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=booking_voucher&trans_num=$matches[2]',

		'(.+?)/tickets/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=booking_tickets&trans_num=$matches[2]',

		'(.+?)/terms/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_terms',

		'(.+?)/contact/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_contact',

		'(.+?)/about/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_about',

		'(.+?)/waiver/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_waiver&trans_num=$matches[2]',

		'(.+?)/waiver/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_waiver',

		'(.+?)/gift-card/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card&sec=1&step=1',

		'(.+?)/gift-card-payment/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card&sec=1&step=2',

		'(.+?)/gift-card/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card_details&card=$matches[2]',

		'(.+?)/gift-receipt/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card_receipt&card=$matches[2]',

		'(.+?)/gift-print/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card_print&card=$matches[2]',

		'(.+?)/gift-not-found/?$'
		=> 'index.php?pagename=$matches[1]&mode=gift_card_not_found',

		'(.+?)/modal/?$'
		=> 'index.php?mode=modal',

		// '(.+?)/return_trip/?$'
		// => 'index.php?mode=return_trip&com=$matches[1]',

		'(.+?)/return_trip/?$'
		=> 'index.php?pagename=$matches[1]&mode=return_trip&com=$matches[2]',

		'(.+?)/privacy/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_privacy',

		'(.+?)/reviews/item/([0-9]+)/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_review&trans_num=$matches[2]&com=$matches[2]',

		'(.+?)/reviews/item/([^\/]*)/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_review&trans_num=$matches[2]&com=$matches[2]',

		'(.+?)/reviews/all/?$'
		=> 'index.php?pagename=$matches[1]&mode=page_review&trans_num=all&com=all',

		'(.+?)/3DS/?$'
		=> 'index.php?pagename=$matches[1]&mode=3DS',

		'(.+?)/3ds_return_url/?$'
		=> 'index.php?pagename=$matches[1]&mode=3ds_return_url',
	);

	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
function rezgo_no_theme($template) {
	global $wp;

	parse_str($wp->matched_query, $matched_query);

	// no header and footer for printable receipt and terms popup
	if ($matched_query['mode'] == 'page_terms') {
		return dirname( __FILE__ ) . '/rezgo_blank_template.php'; 
	} else if ($matched_query['mode'] == 'booking_complete_print') {
		$_REQUEST['rezgo'] = 1;
		$_REQUEST['trans_num'] = $matched_query['trans_num'];
		return dirname( __FILE__ ) . '/booking_complete_print.php';
	} else if ($matched_query['mode'] == 'booking_voucher') {
		$_REQUEST['rezgo'] = 1;
		$_REQUEST['mode'] = 'booking_voucher';
		$_REQUEST['trans_num'] = $matched_query['trans_num'];
		return dirname( __FILE__ ) . '/booking_voucher.php';
	} else if ($matched_query['mode'] == 'booking_tickets') {
		$_REQUEST['rezgo'] = 1;
		$_REQUEST['mode'] = 'booking_tickets';
		$_REQUEST['trans_num'] = $matched_query['trans_num'];
		return dirname( __FILE__ ) . '/booking_tickets.php';
	} else if ($matched_query['mode'] == 'gift_card_print') {
		$_REQUEST['rezgo'] = 1;
		$_REQUEST['mode'] = 'gift_card_print';
		$_REQUEST['card'] = $matched_query['card'];
		return dirname( __FILE__ ) . '/gift_card_print.php';
	} else {
		return $template;
	}
}
function rezgo_page_title( $title, $sep = '-' ) {
	global $site, $wp;

	parse_str($wp->matched_query, $matched_query);

	foreach($matched_query as $k => $v) {
		$_REQUEST[$k] = urldecode($v);
	}

	$site = new RezgoSite($_REQUEST['sec']);
	$item = $site->getTours('t=com&q='.$_REQUEST['com'].'&f[uid]='.$_REQUEST['option'].'&d='.$_REQUEST['date'].'&limit=1', 0);

	if ($_REQUEST['tags']) {
		$title = 'Tours tagged with &quot;'.$_REQUEST['tags'].'&quot;';
	} elseif ($_REQUEST['mode'] == 'page_details' && $item->item) { 
		if ($item->seo->seo_title != '') {
			$title = $item->seo->seo_title;
		} else {
			$title = $item->item;
		}
	} elseif ($_REQUEST['mode'] == 'page_order') {
		$title = get_the_title() . ' ' . $sep . ' Order';
	} elseif (($_REQUEST['mode'] == 'page_book') && ($_REQUEST['step'] == '1')) {
		$title = get_the_title() . ' ' . $sep . ' Book';
	} elseif (($_REQUEST['mode'] == 'page_book') && ($_REQUEST['step'] == '2')) {
		$title = get_the_title() . ' ' . $sep . ' Payment';
	} elseif ($_REQUEST['mode'] == 'gift_card' && ($_REQUEST['step'] == '1')) {
		$title = get_the_title() . ' ' . $sep . ' Gift Card';
	} elseif ($_REQUEST['mode'] == 'gift_card' && ($_REQUEST['step'] == '2')) {
		$title = get_the_title() . ' ' . $sep . ' Gift Card Payment';
	} elseif ($_REQUEST['mode'] == 'gift_card_details') {
		$title = get_the_title() . ' ' . $sep . ' Gift Card Details';
	} elseif ($_REQUEST['mode'] == 'gift_card_receipt') {
		$title = get_the_title() . ' ' . $sep . ' Gift Card Receipt';
	} elseif ($_REQUEST['mode'] == 'gift_card_print') {
		$title = get_the_title() . ' ' . $sep . ' Gift Card Print';
	} elseif ($_REQUEST['mode'] == 'page_contact') {
		$title = get_the_title() . ' ' . $sep . ' Contact';
	}

	return $title;
}

// return pages without WP theme template
add_filter('template_include', 'rezgo_no_theme'); 
// overwrite page title with values from Rezgo
add_filter('pre_get_document_title', 'rezgo_page_title', 999);

// remove default wordpress canonical link and replace it with the proper URI
function fix_canonical(){
	$protocol = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
    echo '<link rel="canonical" href="'.$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" />';
}
remove_action( 'wp_head', 'rel_canonical');
add_action('wp_head', 'fix_canonical');
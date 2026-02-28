<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 10/01/2017
 * Time: 1:50 CH
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$features = '';
$keyword = isset($_GET['keyword']) ? ere_clean(wp_unslash($_GET['keyword']))  : '';
$title = isset($_GET['title']) ? ere_clean(wp_unslash($_GET['title']))  : '';
$address = isset($_GET['address']) ? ere_clean(wp_unslash($_GET['address']))  : '';
$city = isset($_GET['city']) ? wp_filter_kses(wp_unslash($_GET['city']))  : '';
$status = isset($_GET['status']) ? wp_filter_kses(wp_unslash($_GET['status']))  : '';
$type = isset($_GET['type']) ? wp_filter_kses(wp_unslash($_GET['type']))  : '';
$rooms = isset($_GET['rooms']) ? ere_clean(wp_unslash($_GET['rooms']))  : '';
$bedrooms = isset($_GET['bedrooms']) ? ere_clean(wp_unslash($_GET['bedrooms']))  : '';
$bathrooms = isset($_GET['bathrooms']) ? ere_clean(wp_unslash($_GET['bathrooms']))  : '';
$min_price = isset($_GET['min-price']) ? ere_clean(wp_unslash($_GET['min-price']))  : '';
$max_price = isset($_GET['max-price']) ? ere_clean(wp_unslash($_GET['max-price']))  : '';
$min_area = isset($_GET['min-area']) ? ere_clean(wp_unslash($_GET['min-area']))  : '';
$max_area = isset($_GET['max-area']) ? ere_clean(wp_unslash($_GET['max-area']))  : '';
$country = isset($_GET['country']) ? wp_filter_kses(wp_unslash($_GET['country']))  : '';
$state = isset($_GET['state']) ? wp_filter_kses(wp_unslash($_GET['state']))  : '';
$neighborhood = isset($_GET['neighborhood']) ? wp_filter_kses(wp_unslash($_GET['neighborhood']))  : '';
$garage = isset($_GET['garage']) ? ere_clean(wp_unslash($_GET['garage'] )) : '';
$label = isset($_GET['label']) ? wp_filter_kses(wp_unslash($_GET['label']))  : '';
$min_land_area = isset($_GET['min-land-area']) ? ere_clean(wp_unslash($_GET['min-land-area']))  : '';
$max_land_area = isset($_GET['max-land-area']) ? ere_clean(wp_unslash($_GET['max-land-area']))  : '';
$property_identity = isset($_GET['property_identity']) ? ere_clean(wp_unslash($_GET['property_identity']))  : '';
$features = isset($_GET['other_features']) ? wp_filter_kses(wp_unslash($_GET['other_features'] )) : '';
if(!empty($features)) {
	$features = explode( ';',$features );
}

$meta_query = $tax_query = array();
$parameters = $keyword_array = '';
$property_item_class = array('property-item');
$property_content_class = array('property-content');
$property_content_attributes = array();

$wrapper_classes = array(
    'ere-property clearfix',
);
$custom_property_layout_style = ere_get_option( 'search_property_layout_style', 'property-grid' );
$custom_property_items_amount = ere_get_option( 'search_property_items_amount', '6' );
$custom_property_image_size = ere_get_option( 'search_property_image_size', '330x180' );
$custom_property_columns      = ere_get_option( 'search_property_columns', '3' );
$custom_property_columns_gap  = ere_get_option( 'search_property_columns_gap', 'col-gap-30' );
$custom_property_items_md = ere_get_option( 'search_property_items_md', '3' );
$custom_property_items_sm = ere_get_option( 'search_property_items_sm', '2' );
$custom_property_items_xs = ere_get_option( 'search_property_items_xs', '1' );
$custom_property_items_mb = ere_get_option( 'search_property_items_mb', '1' );

ERE_Compare::open_session();
if(isset( $_SESSION["property_view_as"] ) && !empty( $_SESSION["property_view_as"] ) && in_array($_SESSION["property_view_as"], array('property-list', 'property-grid'))) {
    $custom_property_layout_style = $_SESSION["property_view_as"];
}
$property_item_class         = array();

$wrapper_classes = array(
    'ere-property clearfix',
    $custom_property_layout_style,
    $custom_property_columns_gap
);

if($custom_property_layout_style=='property-list'){
    $wrapper_classes[] = 'list-1-column';
}

if ( $custom_property_columns_gap == 'col-gap-30' ) {
    $property_item_class[] = 'mg-bottom-30';
} elseif ( $custom_property_columns_gap == 'col-gap-20' ) {
    $property_item_class[] = 'mg-bottom-20';
} elseif ( $custom_property_columns_gap == 'col-gap-10' ) {
    $property_item_class[] = 'mg-bottom-10';
}

$wrapper_classes[]     = 'columns-' . $custom_property_columns;
$wrapper_classes[]     = 'columns-md-' . $custom_property_items_md;
$wrapper_classes[]     = 'columns-sm-' . $custom_property_items_sm;
$wrapper_classes[]     = 'columns-xs-' . $custom_property_items_xs;
$wrapper_classes[]     = 'columns-mb-' . $custom_property_items_mb;
$property_item_class[] = 'ere-item-wrap';

$orderby = 'date';
$order   = 'DESC';

$args = array(
    'posts_per_page'      => $custom_property_items_amount,
    'post_type'           => 'property',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
    'offset'              => ( max( 1, get_query_var( 'paged' ) ) - 1 ) * $custom_property_items_amount,
    'ignore_sticky_posts' => 1,
    'post_status'         => 'publish',
);
if (isset($_GET['sortby']) && in_array($_GET['sortby'], array('a_price', 'd_price', 'a_date', 'd_date', 'featured', 'most_viewed'))) {
    if ($_GET['sortby'] == 'a_price') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_price';
        $args['order'] = 'ASC';
    } else if ($_GET['sortby'] == 'd_price') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_price';
        $args['order'] = 'DESC';
    } else if ($_GET['sortby'] == 'featured') {
        /*$args['orderby'] = array(
            'meta_value_num' => 'DESC',
            'date' => 'DESC',
        );
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';*/
	    $args['ere_orderby_featured'] = true;
    }
    else if ($_GET['sortby'] == 'most_viewed') {
        /*$args['orderby'] = 'meta_value_num';
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_views_count';
        $args['order'] = 'DESC';*/
	    $args['ere_orderby_viewed'] = true;
    }
    else if ($_GET['sortby'] == 'a_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    } else if ($_GET['sortby'] == 'd_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }
}
else{
    $featured_toplist = ere_get_option('featured_toplist', 1);
    if($featured_toplist!=0)
    {
        /*$args['orderby'] = array(
            'menu_order'=>'ASC',
            'meta_value_num' => 'DESC',
            'date' => 'DESC',
        );
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';*/
	    $args['ere_orderby_featured'] = true;
    }
}


if ($keyword !== '') {
	$keyword_field = ere_get_option('keyword_field','prop_address');
	if ($keyword_field === 'prop_address') {
		$keyword_array = array(
			'relation' => 'OR',
			array(
				'key' => ERE_METABOX_PREFIX. 'property_address',
				'value' => $keyword,
				'type' => 'CHAR',
				'compare' => 'LIKE',
			),
			array(
				'key' => ERE_METABOX_PREFIX. 'property_zip',
				'value' => $keyword,
				'type' => 'CHAR',
				'compare' => 'LIKE',
			),
			array(
				'key' => ERE_METABOX_PREFIX. 'property_identity',
				'value' => $keyword,
				'type' => 'CHAR',
				'compare' => '=',
			)
		);
	} else if ($keyword_field === 'prop_city_state_county') {
		$taxlocation[] = sanitize_title(wp_kses($_GET['keyword'], array()));
		$_tax_query = array();
		$_tax_query['relation'] = 'OR';

		$_tax_query[] = array(
			'taxonomy' => 'property-state',
			'field' => 'slug',
			'terms' => $taxlocation
		);

		$_tax_query[] = array(
			'taxonomy' => 'property-city',
			'field' => 'slug',
			'terms' => $taxlocation
		);

		$_tax_query[] = array(
			'taxonomy' => 'property-neighborhood',
			'field' => 'slug',
			'terms' => $taxlocation
		);

		$tax_query[] = $_tax_query;
	} else {
		$args['s'] = $keyword;
	}
}


//Query get properties with keyword location
if (isset($address) ? $address : '') {
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_address',
        'value' => $address,
        'type' => 'CHAR',
        'compare' => 'LIKE',
    );

    $parameters.=sprintf( __('Keyword: <strong>%s</strong>; ', 'essential-real-estate'), $address );
}
if (isset($title) ? $title : '') {
    $args['s'] = $title;
    $parameters.=sprintf( __('Title: <strong>%s</strong>; ', 'essential-real-estate'), $title );
}

//tax query property type
if (isset($type) && !empty($type)) {
    $tax_query[] = array(
        'taxonomy' => 'property-type',
        'field' => 'slug',
        'terms' => $type
    );
    $parameters.=sprintf( __('Type: <strong>%s</strong>; ', 'essential-real-estate'), $type );
}

//tax query property status
if (isset($status) && !empty($status)) {
    $tax_query[] = array(
        'taxonomy' => 'property-status',
        'field' => 'slug',
        'terms' => $status
    );
    $parameters.=sprintf( __('Status: <strong>%s</strong>; ', 'essential-real-estate'), $status );
}

//tax query property label
if (isset($label) && !empty($label)) {
    $tax_query[] = array(
        'taxonomy' => 'property-label',
        'field' => 'slug',
        'terms' => $label
    );
    $parameters.=sprintf( __('Label: <strong>%s</strong>; ', 'essential-real-estate'), $label );
}

//initial cities and cities search

if (!empty($city)) {
    $tax_query[] = array(
        'taxonomy' => 'property-city',
        'field' => 'slug',
        'terms' => $city
    );
    $parameters.=sprintf( __('City / Town: <strong>%s</strong>; ', 'essential-real-estate'), $city );
}

//bathroom check
if (!empty($bathrooms)) {
    $bathrooms = sanitize_text_field($bathrooms);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_bathrooms',
        'value' => $bathrooms,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Bathrooms: <strong>%s</strong>; ', 'essential-real-estate'), $bathrooms );
}
// bedrooms check
if (!empty($bedrooms)) {
    $bedrooms = sanitize_text_field($bedrooms);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_bedrooms',
        'value' => $bedrooms,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Bedrooms: <strong>%s</strong>; ', 'essential-real-estate'), $bedrooms );
}

if (!empty($rooms)) {
	$rooms = sanitize_text_field($rooms);
	$meta_query[] = array(
		'key' => ERE_METABOX_PREFIX. 'property_rooms',
		'value' => $rooms,
		'type' => 'CHAR',
		'compare' => '=',
	);
	$parameters.=sprintf( __('Rooms: <strong>%s</strong>; ', 'essential-real-estate'), $rooms );
}

// bedrooms check
if (!empty($garage)) {
    $garage = sanitize_text_field($garage);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_garage',
        'value' => $garage,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Garage: <strong>%s</strong>; ', 'essential-real-estate'), $garage );
}

/**
 * Min Max Price & Area Property
 */
if (!empty($min_price) && !empty($max_price)) {
    $min_price = doubleval(ere_clean_double_val($min_price));
    $max_price = doubleval(ere_clean_double_val($max_price));

    if ($min_price >= 0 && $max_price >= $min_price) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Price: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_price, $max_price);
    }
} else if (!empty($min_price)) {
    $min_price = doubleval(ere_clean_double_val($min_price));
    if ($min_price >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => $min_price,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Price: <strong>%s</strong>; ', 'essential-real-estate'), $min_price);
    }
} else if (!empty($max_price)) {
    $max_price = doubleval(ere_clean_double_val($max_price));
    if ($max_price >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => $max_price,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Price: <strong>%s</strong>; ', 'essential-real-estate'), $max_price);
    }
}

// min and max area logic
if (!empty($min_area) && !empty($max_area)) {
    $min_area = intval($min_area);
    $max_area = intval($max_area);

    if ($min_area >= 0 && $max_area >= $min_area) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => array($min_area, $max_area),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Size: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_area, $max_area);
    }

} else if (!empty($max_area)) {
    $max_area = intval($max_area);
    if ($max_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => $max_area,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Area: <strong> %s</strong>; ', 'essential-real-estate'), $max_area);
    }
} else if (!empty($min_area)) {
    $min_area = intval($min_area);
    if ($min_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => $min_area,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Area: <strong> %s</strong>; ', 'essential-real-estate'), $min_area);
    }
}
// min and max land area logic
if (!empty($min_land_area) && !empty($max_land_area)) {
    $min_land_area = intval($min_land_area);
    $max_land_area = intval($max_land_area);

    if ($min_land_area >= 0 && $max_land_area >= $min_land_area) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => array($min_land_area, $max_land_area),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Land size: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_land_area, $max_land_area);
    }

} else if (!empty($max_land_area)) {
    $max_land_area = intval($max_land_area);
    if ($max_land_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => $max_land_area,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Land size: <strong>%s</strong>; ', 'essential-real-estate'), $max_land_area);
    }
} else if (!empty($min_land_area)) {
    $min_land_area = intval($min_land_area);
    if ($min_land_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => $min_land_area,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Land size: <strong>%s</strong>; ', 'essential-real-estate'), $min_land_area);
    }
}
/*Country*/
if (!empty($country)) {
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_country',
        'value' => $country,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Country: <strong>%s</strong>; ', 'essential-real-estate'), $country);
}

/*Search advanced by Province / State*/
if (!empty($state)) {
    $tax_query[] = array(
        'taxonomy' => 'property-state',
        'field' => 'slug',
        'terms' => $state
    );
    $parameters.=sprintf( __('State: <strong>%s</strong>; ', 'essential-real-estate'), $state);
}
/*Search advanced by neighborhood*/
if (!empty($neighborhood)) {
    $tax_query[] = array(
        'taxonomy' => 'property-neighborhood',
        'field' => 'slug',
        'terms' => $neighborhood
    );
    $parameters.=sprintf( __('Neighborhood: <strong>%s</strong>; ', 'essential-real-estate'), $neighborhood);
}
if (!empty($property_identity)) {
    $property_identity = sanitize_text_field($property_identity);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_identity',
        'value' => $property_identity,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Property ID: <strong>%s</strong>; ', 'essential-real-estate'), $bathrooms );
}
/* other featured query*/
if (!empty($features)) {
    foreach($features as $feature){
        $tax_query[] = array(
            'taxonomy' => 'property-feature',
            'field' => 'slug',
            'terms' => $feature
        );
        $parameters.=sprintf( __('Feature: <strong>%s</strong>; ', 'essential-real-estate'), $feature);
    }
}

$additional_fields = ere_get_search_additional_fields();
foreach ($additional_fields as $id => $title) {
	$field = ere_get_search_additional_field($id);
	if ($field === false) {
		continue;
	}
	$field_type = isset($field['field_type']) ? $field['field_type'] : 'text';
	$field_value = isset($_GET[$id]) ? ere_clean( wp_unslash( $_GET[$id] ) ) : '';
	if (!empty($field_value)) {
		if ($field_type === 'checkbox_list') {
			$meta_query[]      = array(
				'key'     => ERE_METABOX_PREFIX . $id,
				'value'   => $field_value,
				'type'    => 'CHAR',
				'compare' => 'LIKE',
			);
		} else {
			$meta_query[]      = array(
				'key'     => ERE_METABOX_PREFIX . $id,
				'value'   => $field_value,
				'type'    => 'CHAR',
				'compare' => '=',
			);
		}
		$parameters .= sprintf( sprintf( '%s: <strong>%s</strong>; ',$title , $field_value ));
	}
}



$meta_count = count($meta_query);
if ($meta_count > 0 || !empty($keyword_array)) {
	$args['meta_query'] = array(
		'relation' => 'AND',
		$keyword_array,
		array(
			'relation' => 'AND',
			$meta_query
		),
	);
}


/*$args['meta_query'] = array(
    'relation' => 'AND',
    $meta_query
);*/

$tax_count = count($tax_query);
if ($tax_count > 0) {
    $args['tax_query'] = array(
        'relation' => 'AND',
        $tax_query
    );
}



$data       = new WP_Query( $args );
$search_query=$args;
$total_post = $data->found_posts;
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'property');
wp_print_styles( ERE_PLUGIN_PREFIX . 'archive-property');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'archive-property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-archive-property' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-advanced-search-wrap ere-property-wrap">
    <?php do_action('ere_advanced_search_before_main_content');
    $enable_saved_search = ere_get_option('enable_saved_search', 1);
    if($enable_saved_search==1):
        $data_target='#ere_save_search_modal';
        if (!is_user_logged_in()){
            $data_target='#ere_signin_modal';
        }
        ?>
        <div class="advanced-saved-searches">
            <button type="button" class="btn btn-primary btn-xs btn-save-search" data-toggle="modal" data-target="<?php echo esc_attr($data_target); ?>">
                <?php esc_html_e( 'Save Search', 'essential-real-estate' ) ?></button>
        </div>
        <?php ere_get_template('global/save-search-modal.php',array('parameters'=>$parameters,'search_query'=>$search_query));
    endif; ?>
    <div class="ere-archive-property">
        <div class="above-archive-property">
            <div class="ere-heading">
                <h2><?php esc_html_e('Results', 'essential-real-estate') ?>
                    <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
            </div>
            <div class="archive-property-action sort-view-property">
                <div class="sort-property property-filter property-dropdown">
                    <span class="property-filter-placeholder"><?php esc_html_e( 'Sort By', 'essential-real-estate' ); ?></span>
                    <ul>
                        <li><a data-sortby="default" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'default' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Default Order', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Default Order', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="featured" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'featured' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Featured', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Featured', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="most_viewed" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'most_viewed' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Most Viewed', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Most Viewed', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="a_price" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'a_price' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Price (Low to High)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Price (Low to High)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="d_price" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'd_price' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Price (High to Low)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Price (High to Low)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="a_date" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'a_date' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Date (Old to New)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Date (Old to New)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="d_date" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'd_date' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_attr_e( 'Date (New to Old)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Date (New to Old)', 'essential-real-estate' ); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="view-as" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
                    <span data-view-as="property-list" class="view-as-list" title="<?php esc_attr_e( 'View as List', 'essential-real-estate' ) ?>">
                        <i class="fa fa-list-ul"></i>
                    </span>
                    <span data-view-as="property-grid" class="view-as-grid" title="<?php esc_attr_e( 'View as Grid', 'essential-real-estate' ) ?>">
                        <i class="fa fa-th-large"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="<?php echo join( ' ', $wrapper_classes ) ?>">
            <?php if ( $data->have_posts() ) :
                while ( $data->have_posts() ): $data->the_post(); ?>

                    <?php ere_get_template( 'content-property.php', array(
                        'custom_property_image_size' => $custom_property_image_size,
                        'property_item_class' => $property_item_class
                    )); ?>

                <?php endwhile;
            else: ?>
                <div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
            <?php endif; ?>
            <div class="clearfix"></div>
            <?php
            $max_num_pages = $data->max_num_pages;
            ere_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php do_action('ere_advanced_search_after_main_content'); ?>
</div>
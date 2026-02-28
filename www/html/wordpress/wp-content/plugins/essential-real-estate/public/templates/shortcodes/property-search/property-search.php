<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
/**
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$search_styles = $show_status_tab = $keyword_enable = $title_enable = $address_enable = $city_enable = $type_enable = $status_enable = $rooms_enable = $bedrooms_enable =
$bathrooms_enable =  $price_enable = $price_is_slider= $area_enable = $area_is_slider= $land_area_enable = $land_area_is_slider = $map_search_enable = $advanced_search_enable =
$country_enable = $state_enable =$neighborhood_enable = $label_enable = $garage_enable =
$property_identity_enable=$other_features_enable = $color_scheme = $el_class = $request_city='';
extract(shortcode_atts(array(
    'search_styles' => 'style-default',
    'show_status_tab' => 'true',
    'status_enable' => 'true',
    'type_enable' => 'true',
    'keyword_enable' => 'true',
    'title_enable' => 'true',
    'address_enable' => 'true',
    'country_enable' => '',
    'state_enable' => '',
    'city_enable' => '',
    'neighborhood_enable' => '',
    'rooms_enable' => '',
    'bedrooms_enable' => '',
    'bathrooms_enable' => '',
    'price_enable' => 'true',
    'price_is_slider'=>'',
    'area_enable' => '',
    'area_is_slider'=>'',
    'land_area_enable' => '',
    'land_area_is_slider'=>'',
    'label_enable' => '',
    'garage_enable' => '',
    'property_identity_enable' => '',
    'other_features_enable' => '',
    'map_search_enable' => '',
    'color_scheme' => 'color-light',
    'el_class' => '',
), $atts));


if ($search_styles == 'style-mini-line') {
	$show_status_tab = false;
}

$status_default = $show_status_tab == 'true' ?  ere_get_property_status_default_value() : '';
$request_status = isset($_GET['status']) ? ere_clean(wp_unslash($_GET['status']))  : $status_default;
$request_city = isset($_GET['city']) ? wp_filter_kses(wp_unslash($_GET['city']))  : '';
$request_keyword = isset($_GET['keyword']) ? ere_clean(wp_unslash($_GET['keyword']))  : '';
$request_title = isset($_GET['title']) ? ere_clean(wp_unslash($_GET['title']))  : '';
$request_address = isset($_GET['address']) ? ere_clean(wp_unslash($_GET['address']))  : '';
$request_type = isset($_GET['type']) ? wp_filter_kses(wp_unslash($_GET['type']))  : '';

$request_bathrooms = isset($_GET['bathrooms']) ? ere_clean(wp_unslash($_GET['bathrooms']))  : '';
$request_bedrooms = isset($_GET['bedrooms']) ? ere_clean(wp_unslash($_GET['bedrooms']))  : '';
$request_rooms = isset($_GET['rooms']) ? ere_clean(wp_unslash($_GET['rooms']))  : '';
$request_min_area = isset($_GET['min-area']) ? ere_clean(wp_unslash($_GET['min-area']))  : '';
$request_max_area = isset($_GET['max-area']) ? ere_clean(wp_unslash($_GET['max-area']))  : '';
$request_min_price = isset($_GET['min-price']) ? ere_clean(wp_unslash($_GET['min-price']))  : '';
$request_max_price = isset($_GET['max-price']) ? ere_clean(wp_unslash($_GET['max-price'] )) : '';
$request_state = isset($_GET['state']) ? wp_filter_kses(wp_unslash($_GET['state']))  : '';
$request_country = isset($_GET['country']) ? wp_filter_kses(wp_unslash($_GET['country']))  : '';
$request_neighborhood = isset($_GET['neighborhood']) ? wp_filter_kses(wp_unslash($_GET['neighborhood']))  : '';
$request_label = isset($_GET['label']) ? wp_filter_kses(wp_unslash($_GET['label']))  : '';
$request_property_identity = isset($_GET['property_identity']) ? ere_clean(wp_unslash($_GET['property_identity']))  : '';
$request_garage = isset($_GET['garage']) ? ere_clean(wp_unslash($_GET['garage']))  : '';
$request_min_land_area = isset($_GET['min-land-area']) ? ere_clean(wp_unslash($_GET['min-land-area']))  : '';
$request_max_land_area = isset($_GET['max-land-area']) ? ere_clean(wp_unslash($_GET['max-land-area']))  : '';
$request_features = isset($_GET['other_features']) ? wp_filter_kses(wp_unslash($_GET['other_features']))  : '';
if(!empty($request_features)) {
    $request_features = explode( ';',$request_features );
}
$request_advanced_search = isset($_GET['advanced']) ? ere_clean(wp_unslash($_GET['advanced']))  : '0';
$request_features_search = isset($_GET['features-search']) ? ere_clean(wp_unslash($_GET['features-search']))  : '0';
$wrapper_class='ere-search-properties clearfix';
if ($search_styles === 'style-vertical' || $search_styles === 'style-absolute') {
    $map_search_enable='true';
}
if ($map_search_enable=='true'){
    $wrapper_class.=' ere-search-properties-map';
}
if($show_status_tab=='true' && $search_styles!='style-mini-line')
{
    $wrapper_class.=' ere-show-status-tab';
}
if ($search_styles === 'style-vertical') {
    $class_col_half_map = 'col-md-6 col-no-padding';
} else {
    $class_col_half_map = '';
}
$wrapper_classes = array(
    $wrapper_class,
    $search_styles,
    $color_scheme,
    $el_class,
);

$ere_search = new ERE_Search();
$enable_filter_location = ere_get_option('enable_filter_location', 0);
$options = array();
if ($map_search_enable=='true'){
    $googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
    $pin_cluster_enable = ere_get_option('googlemap_pin_cluster', '1');
    $google_map_style = ere_get_option('googlemap_style', '');
    $google_map_needed = 'true';
    $map_cluster_icon_url = ERE_PLUGIN_URL . 'public/assets/images/map-cluster-icon.png';
    $default_cluster=ere_get_option('cluster_icon','');
    if($default_cluster!='')
    {
        if(is_array($default_cluster)&& $default_cluster['url']!='')
        {
            $map_cluster_icon_url=$default_cluster['url'];
        }
    }
	$options = array(
		'ajax_url' => ERE_AJAX_URL,
		'not_found' => esc_html__("We didn't find any results, you can retry with other keyword.", 'essential-real-estate'),
		'googlemap_default_zoom' => $googlemap_zoom_level,
		'clusterIcon' => $map_cluster_icon_url,
		'google_map_needed' => $google_map_needed,
		'google_map_style' => $google_map_style,
		'pin_cluster_enable' => $pin_cluster_enable,
		'price_is_slider'=> $price_is_slider,
		'enable_filter_location'=>$enable_filter_location
	);
}else{
	$options = array(
		'ajax_url' => ERE_AJAX_URL,
		'price_is_slider'=> $price_is_slider,
		'enable_filter_location'=>$enable_filter_location
	);
}
$geo_location = ere_get_option('geo_location');
/* Class col style for form*/
switch ($search_styles) {
    case 'style-mini-line':
        $css_class_field = 'col-lg-3 col-md-6 col-sm-6 col-xs-12';
        $css_class_half_field = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
        $show_status_tab='false';
        break;
    case 'style-default-small':
        $css_class_field = 'col-md-4 col-sm-6 col-xs-12';
        $css_class_half_field = 'col-md-2 col-sm-3 col-xs-12';
        break;
    case 'style-absolute':
        $css_class_field = 'col-md-12 col-sm-12 col-xs-12';
        $css_class_half_field = 'col-md-6 col-sm-6 col-xs-12';
        break;
    case 'style-vertical':
        $css_class_field = 'col-md-6 col-sm-6 col-xs-12';
        $css_class_half_field = 'col-md-3 col-sm-3 col-xs-12';
        break;
    default:
        $css_class_field = 'col-md-4 col-sm-6 col-xs-12';
        $css_class_half_field = 'col-md-2 col-sm-3 col-xs-12';
        break;
}
$css_class_field = apply_filters('ere_property_search_css_class_field',$css_class_field,$search_styles);
$css_class_half_field = apply_filters('ere_property_search_css_class_half_field',$css_class_half_field,$search_styles);
$map_ID = 'ere_result_map-'.rand();
?>
<div data-options="<?php echo esc_attr(json_encode($options))?>" class="<?php echo join(' ', $wrapper_classes) ?>">
    <?php if ($map_search_enable=='true'): ?>
        <div class="ere-map-search clearfix <?php if (!empty($class_col_half_map)) echo esc_attr($class_col_half_map); ?>">
            <div class="search-map-inner clearfix">
                <div id="<?php echo esc_attr($map_ID)?>" class="ere-map-result">
                </div>
                <div id="ere-map-loading">
                    <div class="block-center">
                        <div class="block-center-inner">
                            <i class="fa fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
                <?php wp_nonce_field('ere_search_map_ajax_nonce', 'ere_security_search_map'); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($search_styles === 'style-vertical'):?>
    <div class="col-scroll-vertical col-md-6 col-no-padding">
        <?php endif;?>
        <div class="form-search-wrap">
            <div class="form-search-inner">
                <div class="ere-search-content">
                    <?php $advanced_search = ere_get_permalink('advanced_search'); ?>
                    <div data-href="<?php echo esc_url($advanced_search) ?>" class="search-properties-form">
                        <?php
                        if($status_enable=='true' && $show_status_tab=='true'):?>
                            <div class="ere-search-status-tab">
                                <input class="search-field" type='hidden' name="status" value="<?php echo esc_attr($request_status); ?>" data-default-value=""/>
                                <?php
                                $property_status = ere_get_property_status_search();
                                if ($property_status) :
                                    foreach ($property_status as $status):?>
                                        <button type="button" data-value="<?php echo esc_attr($status->slug) ?>" class="btn-status-filter<?php if($request_status==$status->slug) echo " active" ?>"><?php echo esc_attr($status->name) ?></button>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                        <?php endif;?>
                        <div class="row">
                            <?php
                            $additional_fields = ere_get_search_additional_fields();
                            $search_fields = ere_get_option('search_fields', array('property_status',  'property_type', 'property_title', 'property_address','property_country', 'property_state', 'property_city', 'property_neighborhood', 'property_bedrooms', 'property_bathrooms', 'property_price', 'property_size', 'property_land', 'property_label', 'property_garage', 'property_identity', 'property_feature'));
                            if ($search_fields): foreach ($search_fields as $field) {
                                switch ($field) {
                                    case 'property_status':
                                        if($status_enable=='true' && $show_status_tab!='true')
                                        {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_status' => $request_status
                                            ));
                                        }
                                        break;
                                    case 'property_type':
                                        if($type_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_type' => $request_type
                                            ));
                                        }
                                        break;
	                                case 'keyword':
		                                if($keyword_enable=='true')
		                                {
			                                ere_get_template('property/search-fields/' . $field . '.php', array(
				                                'css_class_field' => $css_class_field,
				                                'request_keyword' => $request_keyword
			                                ));
		                                }
		                                break;
                                    case 'property_title':
                                        if($title_enable=='true')
                                        {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_title' => $request_title
                                            ));
                                        }
                                        break;
                                    case 'property_address':
                                        if($address_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_address' => $request_address
                                            ));
                                        }
                                        break;
                                    case 'property_country':
                                        if($country_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_country' => $request_country
                                            ));
                                        }
                                        break;
                                    case 'property_state':
                                        if($state_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_state' => $request_state
                                            ));
                                        }
                                        break;
                                    case 'property_city':
                                        if($city_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_city' => $request_city
                                            ));
                                        }
                                        break;
                                    case 'property_neighborhood':
                                        if($neighborhood_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_neighborhood' => $request_neighborhood
                                            ));
                                        }
                                        break;
	                                case 'property_rooms':
		                                if($rooms_enable=='true') {
			                                ere_get_template('property/search-fields/' . $field . '.php', array(
				                                'css_class_field' => $css_class_field,
				                                'request_rooms' => $request_rooms
			                                ));
		                                }
		                                break;
                                    case 'property_bedrooms':
                                        if($bedrooms_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_bedrooms' => $request_bedrooms
                                            ));
                                        }
                                        break;
                                    case 'property_bathrooms':
                                        if($bathrooms_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_bathrooms' => $request_bathrooms
                                            ));
                                        }
                                        break;
                                    case 'property_price':
                                        if($price_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'css_class_half_field' => $css_class_half_field,
                                                'request_min_price' => $request_min_price,
                                                'request_max_price' => $request_max_price,
                                                'request_status' =>$request_status,
                                                'price_is_slider'=> $price_is_slider
                                            ));
                                        }
                                        break;
                                    case 'property_size':
                                        if($area_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'css_class_half_field' => $css_class_half_field,
                                                'request_min_area' => $request_min_area,
                                                'request_max_area' => $request_max_area,
                                                'area_is_slider'=> $area_is_slider
                                            ));
                                        }
                                        break;
                                    case 'property_land':
                                        if($land_area_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'css_class_half_field' => $css_class_half_field,
                                                'request_min_land_area' => $request_min_land_area,
                                                'request_max_land_area' => $request_max_land_area,
                                                'land_area_is_slider'=> $land_area_is_slider
                                            ));
                                        }
                                        break;
                                    case 'property_label':
                                        if($label_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_label' => $request_label
                                            ));
                                        }
                                        break;
                                    case 'property_garage':
                                        if($garage_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_garage' => $request_garage
                                            ));
                                        }
                                        break;
                                    case 'property_identity':
                                        if($property_identity_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_property_identity' => $request_property_identity
                                            ));
                                        }
                                        break;
                                    case 'property_feature':
                                        if($other_features_enable=='true') {
                                            ere_get_template('property/search-fields/' . $field . '.php', array(
                                                'css_class_field' => $css_class_field,
                                                'request_features_search' => $request_features_search,
                                                'request_features' => $request_features,
                                            ));
                                        }
                                        break;
	                                default:
										if (array_key_exists($field,$additional_fields)) {
											if (isset($atts["{$field}_enable"]) && ($atts["{$field}_enable"] === 'true') ) {
												$additional_field = ere_get_search_additional_field($field);
												if ($additional_field !== false) {
													$type = isset($additional_field['field_type']) ? $additional_field['field_type'] : 'text';
													$file_type = $type;
													if ($type === 'textarea') {
														$file_type = 'text';
													}

													if ($type === 'checkbox_list' || $type === 'radio') {
														$file_type = 'select';
													}

													ere_get_template('property/search-fields/custom-fields/' . $file_type . '.php', array(
														'css_class_field' => $css_class_field,
														'field' => $additional_field
													));
												}
											}
										}
	                                	break;
                                }
                            }
                            endif;
                            ?>
                            <div class="<?php echo esc_attr($css_class_field); ?> form-group submit-search-form pull-right">
                                <button type="button" class="ere-advanced-search-btn"><i class="fa fa-search"></i>
                                    <?php esc_html_e('Search', 'essential-real-estate') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($search_styles === 'style-vertical'): ?>
            <div class="property-result-wrap">
                <div class="list-property-result-ajax ">
                    <?php
                    $args_prop = $tax_query = $meta_query  = array();
                    $args_prop = array(
                        'post_type' => 'property',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby'   => array(
                            'menu_order'=>'ASC',
                            'date' =>'DESC',
                        ),
                    );
                    $featured_toplist = ere_get_option('featured_toplist', 1);
                    if($featured_toplist!=0)
                    {
                        /*$args_prop['orderby'] = array(
                            'menu_order'=>'ASC',
                            'meta_value_num' => 'DESC',
                            'date' => 'DESC',
                        );
                        $args_prop['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';*/
	                    $args_prop['ere_orderby_featured'] = true;

                    }
                    if ($status_enable=='true' && !empty($request_status)) {
	                    $tax_query[] = array(
                            'taxonomy' => 'property-status',
                            'field' => 'slug',
                            'terms' => $request_status
                        );
                    }


                    if (($price_is_slider == 'true') && ($price_enable == 'true')) {

	                    $min_price = ere_get_option('property_price_slider_min',200);
	                    $max_price = ere_get_option('property_price_slider_max',2500000);
	                    if ($request_status !== '') {
		                    $property_price_slider_search_field = ere_get_option('property_price_slider_search_field','');
		                    if ($property_price_slider_search_field != '') {
			                    foreach ($property_price_slider_search_field as $data) {
				                    $term_id =(isset($data['property_price_slider_property_status']) ? $data['property_price_slider_property_status'] : '');
				                    $term = get_term_by('id', $term_id, 'property-status');
				                    if($term)
				                    {
					                    if($term->slug==$request_status)
					                    {
						                    $min_price = (isset($data['property_price_slider_min']) ? $data['property_price_slider_min'] : $min_price);
						                    $max_price = (isset($data['property_price_slider_max']) ? $data['property_price_slider_max'] : $max_price);
						                    break;
					                    }
				                    }
			                    }
		                    }
	                    }



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
		                    }
	                    } else if (!empty($max_price)) {
		                    $max_price = doubleval(ere_clean_double_val($max_price));
		                    if ($max_price >= 0) {
			                    $meta_query[] = array(
				                    'key' => ERE_METABOX_PREFIX . 'property_price',
				                    'value' => $max_price,
				                    'type' => 'NUMERIC',
				                    'compare' => '<=',
			                    );
		                    }
	                    }
                    }


                    if (($area_is_slider == 'true') && ($area_enable == 'true')) {
	                    $min_area = ere_get_option('property_size_slider_min', 0);
	                    $max_area = ere_get_option('property_size_slider_max', 1000);
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
		                    }
	                    }
                    }

                    // min and max land area logic
                    if (($land_area_is_slider == 'true') && ($land_area_enable == 'true')) {
	                    $min_land_area = ere_get_option('property_land_slider_min',0);
	                    $max_land_area = ere_get_option('property_land_slider_max',1000);
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
		                    }
	                    }
                    }

                    if (count($tax_query) > 0) {
	                    $args_prop['tax_query'] = array(
		                    'relation' => 'AND',
		                    $tax_query
	                    );
                    }

                    if (count($meta_query) > 0) {
	                    $args_prop['meta_query'] = array(
		                    'relation' => 'AND',
		                    $meta_query
	                    );
                    }

                    $args_prop = apply_filters('ere_shortcodes_property_search_query_args',$args_prop);
                    $data_vertical = new WP_Query($args_prop);
                    $total_post = $data_vertical->found_posts;
                    $custom_property_image_size = '330x180';
                    $property_item_class = array('property-item');
                    ?>
                    <div class="title-result">
                        <h2 class="uppercase">
                            <span class="number-result"><?php echo esc_html($total_post) ?></span>
                            <span class="text-result">
	                            <?php echo esc_html(_n('Property', 'Properties', $total_post, 'essential-real-estate')) ?>
                            </span>
                            <span class="text-no-result"><?php esc_html_e(' No property found', 'essential-real-estate') ?></span>
                        </h2>
                    </div>
                    <div class="ere-property property-carousel">
                        <?php
                        $owl_responsive_attributes = array();
                        $owl_responsive_attributes[] = '"0" : {"items" : 1}';
                        $owl_responsive_attributes[] = '"600" : {"items" : 2}';
                        $owl_responsive_attributes[] = '"992" : {"items" : 1}';
                        $owl_responsive_attributes[] = '"1200" : {"items" : 2}';
                        $owl_attributes = array(
                            '"items":2',
                            '"margin":30',
                            '"nav": true',
                            '"responsive": {' . implode(', ', $owl_responsive_attributes) . '}'
                        );
                        $property_content_attributes[] = "data-plugin-options='{" . implode( ', ', $owl_attributes ) . "}'";
                        ?>
                        <div class="owl-carousel" <?php echo implode( ' ', $property_content_attributes ); ?>>
                            <?php if ($data_vertical->have_posts()) :
                                $index = 0;
                                while ($data_vertical->have_posts()): $data_vertical->the_post();?>
                                    <?php ere_get_template('content-property.php', array(
                                        'custom_property_image_size' => $custom_property_image_size,
                                        'property_item_class' => $property_item_class,
                                    )); ?>
                                <?php endwhile;
                            else: ?>
                                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php wp_reset_postdata();?>
        <?php endif; ?>
        <?php if($search_styles === 'style-vertical'):?>
    </div>
<?php endif;?>
</div>
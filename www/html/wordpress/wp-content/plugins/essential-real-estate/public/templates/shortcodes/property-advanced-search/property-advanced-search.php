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
$layout = $column = $address_enable = $keyword_enable = $title_enable = $city_enable = $type_enable = $status_enable = $rooms_enable = $bedrooms_enable =
$bathrooms_enable = $price_enable = $price_is_slider = $area_enable = $area_is_slider = $land_area_enable = $land_area_is_slider = $country_enable = $state_enable = $neighborhood_enable = $label_enable = $garage_enable =
$property_identity_enable = $other_features_enable = $color_scheme = $el_class = $request_city = '';
extract(shortcode_atts(array(
    'layout' => 'tab',
    'column' => '3',
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
    'price_is_slider' => '',
    'area_enable' => '',
    'area_is_slider' => '',
    'land_area_enable' => '',
    'land_area_is_slider' => '',
    'label_enable' => '',
    'garage_enable' => '',
    'property_identity_enable' => '',
    'other_features_enable' => '',
    'color_scheme' => 'color-light',
    'el_class' => ''
), $atts));

$status_default= $layout == 'tab' ? ere_get_property_status_default_value() : '';
$request_city = isset($_GET['city']) ? wp_filter_kses(wp_unslash($_GET['city']))  : '';
$request_keyword = isset($_GET['keyword']) ? ere_clean(wp_unslash($_GET['keyword']))  : '';
$request_title = isset($_GET['title']) ? ere_clean(wp_unslash($_GET['title']))  : '';
$request_address = isset($_GET['address']) ? ere_clean(wp_unslash($_GET['address'] )) : '';
$request_type = isset($_GET['type']) ? wp_filter_kses(wp_unslash($_GET['type']))  : '';
$request_status = isset($_GET['status']) ? wp_filter_kses(wp_unslash($_GET['status']))  : $status_default;
$request_bathrooms = isset($_GET['bathrooms']) ? ere_clean(wp_unslash($_GET['bathrooms']))  : '';
$request_rooms = isset($_GET['rooms']) ? ere_clean(wp_unslash($_GET['rooms']))  : '';
$request_bedrooms = isset($_GET['bedrooms']) ? ere_clean(wp_unslash($_GET['bedrooms']))  : '';
$request_min_price = isset($_GET['min-price']) ? ere_clean(wp_unslash($_GET['min-price']))  : '';
$request_max_price = isset($_GET['max-price']) ? ere_clean(wp_unslash($_GET['max-price']))  : '';
$request_min_area = isset($_GET['min-area']) ? ere_clean(wp_unslash($_GET['min-area']))  : '';
$request_max_area = isset($_GET['max-area']) ? ere_clean(wp_unslash($_GET['max-area']))  : '';
$request_min_land_area = isset($_GET['min-land-area']) ? ere_clean(wp_unslash($_GET['min-land-area']))  : '';
$request_max_land_area = isset($_GET['max-land-area']) ? ere_clean(wp_unslash($_GET['max-land-area']))  : '';
$request_state = isset($_GET['state']) ? wp_filter_kses(wp_unslash($_GET['state']))  : '';
$request_country = isset($_GET['country']) ? wp_filter_kses(wp_unslash($_GET['country']))  : '';
$request_neighborhood = isset($_GET['neighborhood']) ? wp_filter_kses(wp_unslash($_GET['neighborhood']))  : '';
$request_label = isset($_GET['label']) ? wp_filter_kses(wp_unslash($_GET['label']))  : '';
$request_property_identity = isset($_GET['property_identity']) ? ere_clean(wp_unslash($_GET['property_identity']))  : '';
$request_garage = isset($_GET['garage']) ? ere_clean(wp_unslash($_GET['garage']))  : '';

$request_features = isset($_GET['other_features']) ? wp_filter_kses(wp_unslash($_GET['other_features']))  : '';
if (!empty($request_features)) {
    $request_features = explode(';', $request_features);
}
$request_features_search = isset($_GET['features-search']) ? ere_clean(wp_unslash($_GET['features-search']))  : '0';
$wrapper_class = 'ere-property-advanced-search clearfix';

$wrapper_classes = array(
    $wrapper_class,
    $layout,
    $color_scheme,
    $el_class,
);
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
$enable_filter_location = ere_get_option('enable_filter_location', 0);
$options = array(
	'ajax_url' => ERE_AJAX_URL,
	'price_is_slider' => $price_is_slider,
	'enable_filter_location'=>$enable_filter_location
);
$css_class_field = 'col-md-4 col-sm-6 col-xs-12';
$css_class_half_field = 'col-md-2 col-sm-3 col-xs-12';
if ($column == '1') {
    $css_class_field = 'col-md-12 col-sm-12 col-xs-12';
    $css_class_half_field = 'col-md-6 col-sm-6 col-xs-12';
} elseif ($column == '2') {
    $css_class_field = 'col-md-6 col-sm-6 col-xs-12';
    $css_class_half_field = 'col-md-3 col-sm-3 col-xs-12';
} elseif ($column == '3') {
    $css_class_field = 'col-md-4 col-sm-6 col-xs-12';
    $css_class_half_field = 'col-md-2 col-sm-3 col-xs-12';
} elseif ($column == '4') {
    $css_class_field = 'col-md-3 col-sm-6 col-xs-12';
    $css_class_half_field = 'col-md-3 col-sm-3 col-xs-12';
}
$css_class_field = apply_filters('ere_property_advanced_search_css_class_field',$css_class_field,$column);
$css_class_half_field = apply_filters('ere_property_advanced_search_css_class_half_field',$css_class_half_field,$column);

?>
<div data-options="<?php echo esc_attr(json_encode($options))?>" class="<?php echo join(' ', $wrapper_classes) ?>">
    <div class="form-search-wrap">
        <div class="form-search-inner">
            <div class="ere-search-content">
                <?php $advanced_search = ere_get_permalink('advanced_search'); ?>
                <div data-href="<?php echo esc_url($advanced_search) ?>" class="search-properties-form">
                    <?php if ($status_enable == 'true' && $layout == 'tab'): ?>
                        <div class="ere-search-status-tab">
                            <input class="search-field" type='hidden' name="status"
                                   value="<?php echo esc_attr($request_status); ?>" data-default-value=""/>
                            <?php
                            $property_status = ere_get_property_status_search();
                            if ($property_status) :
                                foreach ($property_status as $status):?>
                                    <button type="button" data-value="<?php echo esc_attr($status->slug) ?>"
                                            class="btn-status-filter<?php if ($request_status == $status->slug) echo " active" ?>"><?php echo esc_html($status->name) ?></button>
                                <?php endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <?php
                        $additional_fields = ere_get_search_additional_fields();
                        $search_fields = ere_get_option('search_fields', array('property_status',  'property_type', 'property_title', 'property_address','property_country', 'property_state', 'property_city', 'property_neighborhood', 'property_bedrooms', 'property_bathrooms', 'property_price', 'property_size', 'property_land', 'property_label', 'property_garage', 'property_identity', 'property_feature'));
                        if ($search_fields): foreach ($search_fields as $field) {
                            switch ($field) {
                                case 'property_status':
                                    if ($status_enable == 'true' && $layout != 'tab') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_status' => $request_status
                                        ));
                                    }
                                    break;
                                case 'property_type':
                                    if ($type_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_type' => $request_type
                                        ));
                                    }
                                    break;
                                case 'keyword':
	                                if ($keyword_enable == 'true') {
		                                ere_get_template('property/search-fields/' . $field . '.php', array(
			                                'css_class_field' => $css_class_field,
			                                'request_keyword' => $request_keyword
		                                ));
	                                }
	                                break;
	                            case 'property_title':
                                    if ($title_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_title' => $request_title
                                        ));
                                    }
                                    break;
                                case 'property_address':
                                    if ($address_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_address' => $request_address
                                        ));
                                    }
                                    break;
                                case 'property_country':
                                    if ($country_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_country' => $request_country
                                        ));
                                    }
                                    break;
                                case 'property_state':
                                    if ($state_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_state' => $request_state
                                        ));
                                    }
                                    break;
                                case 'property_city':
                                    if ($city_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_city' => $request_city
                                        ));
                                    }
                                    break;
                                case 'property_neighborhood':
                                    if ($neighborhood_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_neighborhood' => $request_neighborhood
                                        ));
                                    }
                                    break;
	                            case 'property_rooms':
		                            if ($rooms_enable == 'true') {
			                            ere_get_template('property/search-fields/' . $field . '.php', array(
				                            'css_class_field' => $css_class_field,
				                            'request_rooms' => $request_rooms
			                            ));
		                            }
		                            break;
                                case 'property_bedrooms':
                                    if ($bedrooms_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_bedrooms' => $request_bedrooms
                                        ));
                                    }
                                    break;
                                case 'property_bathrooms':
                                    if ($bathrooms_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_bathrooms' => $request_bathrooms
                                        ));
                                    }
                                    break;
                                case 'property_price':
                                    if ($price_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'request_min_price' => $request_min_price,
                                            'request_max_price' => $request_max_price,
                                            'request_status' => $request_status,
                                            'price_is_slider' => $price_is_slider
                                        ));
                                    }
                                    break;
                                case 'property_size':
                                    if ($area_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'request_min_area' => $request_min_area,
                                            'request_max_area' => $request_max_area,
                                            'area_is_slider' => $area_is_slider
                                        ));
                                    }
                                    break;
                                case 'property_land':
                                    if ($land_area_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'css_class_half_field' => $css_class_half_field,
                                            'request_min_land_area' => $request_min_land_area,
                                            'request_max_land_area' => $request_max_land_area,
                                            'land_area_is_slider' => $land_area_is_slider
                                        ));
                                    }
                                    break;
                                case 'property_label':
                                    if ($label_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_label' => $request_label
                                        ));
                                    }
                                    break;
                                case 'property_garage':
                                    if ($garage_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_garage' => $request_garage
                                        ));
                                    }
                                    break;
                                case 'property_identity':
                                    if ($property_identity_enable == 'true') {
                                        ere_get_template('property/search-fields/' . $field . '.php', array(
                                            'css_class_field' => $css_class_field,
                                            'request_property_identity' => $request_property_identity
                                        ));
                                    }
                                    break;
                                case 'property_feature':
                                    if ($other_features_enable == 'true') {
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
</div>
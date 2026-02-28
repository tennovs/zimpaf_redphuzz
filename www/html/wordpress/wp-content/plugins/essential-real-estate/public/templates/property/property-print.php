<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $isRTL
 * @var $property_id
 */
$the_post = get_post($property_id);
if ($the_post->post_type != 'property') {
    esc_html_e('Posts ineligible to print!', 'essential-real-estate');
    return;
}
wp_enqueue_script('jquery');
wp_add_inline_script('jquery',"jQuery(window).on('load',function(){ print(); });");
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-print');

if ($isRTL == 'true') {
    wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-print-rtl');
}

// Actions
remove_action( 'wp_head',             '_wp_render_title_tag',            1     );
remove_action( 'wp_head',             'wp_resource_hints',               2     );
remove_action( 'wp_head',             'feed_links',                      2     );
remove_action( 'wp_head',             'feed_links_extra',                3     );
remove_action( 'wp_head',             'rsd_link'                               );
remove_action( 'wp_head',             'wlwmanifest_link'                       );
remove_action( 'wp_head',             'adjacent_posts_rel_link_wp_head', 10);
remove_action( 'publish_future_post', 'check_and_publish_future_post',   10);
remove_action( 'wp_head',             'noindex',                          1    );
remove_action( 'wp_head',             'print_emoji_detection_script',     7    );
remove_action( 'wp_head',             'wp_generator'                           );
remove_action( 'wp_head',             'rel_canonical'                          );
remove_action( 'wp_head',             'wp_shortlink_wp_head',            10);
remove_action( 'wp_head',             'wp_custom_css_cb',                101   );
remove_action( 'wp_head',             'wp_site_icon',                    99    );

add_action('wp_enqueue_scripts','ere_dequeue_assets_print_property',9999);
function ere_dequeue_assets_print_property() {
    foreach (wp_styles()->registered as $k => $v) {
        if (!in_array($k,array('bootstrap','font-awesome',ERE_PLUGIN_PREFIX . 'property-print',ERE_PLUGIN_PREFIX . 'property-print-rtl'))) {
            unset(wp_styles()->registered[$k]);
        }
    }
}

$print_logo = ere_get_option('print_logo', '');
$attach_id = '';
if (is_array($print_logo) && count($print_logo) > 0) {
    $attach_id = $print_logo['id'];
}
$image_size = ere_get_option('print_logo_size', '200x100');
$image_src = '';
$width = '';
$height = '';
if ($attach_id) {
    if (preg_match('/\d+x\d+/', $image_size)) {
        $image_sizes = explode('x', $image_size);
        $image_src = ere_image_resize_id($attach_id, $image_sizes[0], $image_sizes[1], true);
    } else {
        if (!in_array($image_size, array('full', 'thumbnail'))) {
            $image_size = 'full';
        }
        $image_src = wp_get_attachment_image_src($attach_id, $image_size);
        if ($image_src && !empty($image_src[0])) {
            $image_src = $image_src[0];
        }
    }
}
if (!empty($image_src)) {
    list($width, $height) = getimagesize($image_src);
}
$page_name = get_bloginfo('name', '');

$property_meta_data = get_post_custom($property_id);

$property_label = get_the_terms($property_id, 'property-label');
$property_label_arr = array();
if ($property_label) {
    foreach ($property_label as $label) {
        $property_label_arr[] = $label->name;
    }
}
$property_types = get_the_terms($property_id, 'property-type');
$property_type_arr = array();
if ($property_types) {
    foreach ($property_types as $property_type) {
        $property_type_arr[] = $property_type->name;
    }
}

$property_status = get_the_terms($property_id, 'property-status');
$property_status_arr = array();
if ($property_status) {
	foreach ($property_status as $property_stt) {
		$property_status_arr[] = $property_stt->name;
	}
}


$property_identity = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_identity']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_identity'][0] : '';
$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
$price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
$price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';
$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
$property_rooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_rooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_rooms'][0] : '0';
$property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
$property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';
$property_garage = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0] : '0';
$property_country = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_country']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_country'][0] : '';
$property_zip = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_zip']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_zip'][0] : '';
$property_year = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0] : '';
$property_garage_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0] : '';

$property_neighborhood = get_the_terms($property_id, 'property-neighborhood');
$property_neighborhood_arr = array();
if ($property_neighborhood) {
    foreach ($property_neighborhood as $neighborhood_item) {
        $property_neighborhood_arr[] = $neighborhood_item->name;
    }
}
$property_city = get_the_terms($property_id, 'property-city');
$property_city_arr = array();
if ($property_city) {
    foreach ($property_city as $city_item) {
        $property_city_arr[] = $city_item->name;
    }
}
$property_state = get_the_terms($property_id, 'property-state');
$property_state_arr = array();
if ($property_state) {
    foreach ($property_state as $state_item) {
        $property_state_arr[] = $state_item->name;
    }
}
$property_features = get_the_terms($property_id, 'property-feature');

$property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
$property_land = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0] : '';

$additional_features = isset($property_meta_data[ERE_METABOX_PREFIX . 'additional_features']) ? $property_meta_data[ERE_METABOX_PREFIX . 'additional_features'][0] : '';
$additional_feature_title = $additional_feature_value = null;
if ($additional_features > 0) {
    $additional_feature_title = get_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_title', true);
    $additional_feature_value = get_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_value', true);
}
$measurement_units = ere_get_measurement_units();
?>
<html <?php language_attributes(); ?>>
    <head>
        <?php wp_head(); ?>
    </head>
    <body>
    <div id="property-print-wrap">
        <div class="property-print-inner">
            <?php if (!empty($image_src)): ?>
                <div class="home-page-info">
                    <img src="<?php echo esc_url($image_src) ?>" alt="<?php echo esc_attr($page_name) ?>"
                         width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>">
                </div>
            <?php endif; ?>
            <div class="property-main-info">
                <div class="property-heading">
                    <div class="pull-left">
                        <?php $title = get_the_title($property_id);
                        if (isset($title) && !empty($title)):?>
                            <h2 class="property-title"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($property_address)): ?>
                            <div class="property-location" title="<?php echo esc_attr($property_address) ?>">
                                <i class="fa fa-map-marker"></i>
                                <span><?php echo esc_html($property_address) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($price)): ?>
                            <div class="property-price">
								<span class="ere-property-price">
                                    <?php if (!empty($price_prefix)) {
                                        echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                    } ?>
                                    <?php echo ere_get_format_money($price_short, $price_unit) ?>
                                    <?php if (!empty($price_postfix)) {
                                        echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                    } ?>
                                </span>
                            </div>
                        <?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
                            <div class="property-price">
                                <span><?php echo ere_get_option('empty_price_text', '') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="property-link-api pull-right">
                        <img class="qr-image"
                             src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?php echo esc_url(get_permalink($property_id)); ?>&choe=UTF-8"
                             title="<?php echo esc_attr(get_the_title($property_id)); ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="property-info">
                    <div class="property-id">
                        <span class="fa fa-barcode"></span>

                        <div class="content-property-info">
                            <p class="property-info-value"><?php
                                if (!empty($property_identity)) {
                                    echo esc_html($property_identity);
                                } else {
                                    echo esc_html($property_id);
                                }
                                ?></p>

                            <p class="property-info-title"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></p>
                        </div>
                    </div>
                    <?php if (!empty($property_size)): ?>
                        <div class="property-area">
                            <span class="fa fa-arrows"></span>

                            <div class="content-property-info">
                                <p class="property-info-value"><?php echo ere_get_format_number($property_size) ?>
                                    <span><?php echo wp_kses_post($measurement_units) ?></span>
                                </p>

                                <p class="property-info-title"><?php esc_html_e('Size', 'essential-real-estate'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($property_bedrooms)): ?>
                        <div class="property-bedrooms">
                            <span class="fa fa-hotel"></span>

                            <div class="content-property-info">
                                <p class="property-info-value"><?php echo esc_html($property_bedrooms) ?></p>

                                <p class="property-info-title"><?php
                                    echo ere_get_number_text($property_bedrooms, esc_html__('Bedrooms', 'essential-real-estate'), esc_html__('Bedroom', 'essential-real-estate'));
                                    ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($property_bathrooms)): ?>
                        <div class="property-bathrooms">
                            <span class="fa fa-bath"></span>

                            <div class="content-property-info">
                                <p class="property-info-value"><?php echo esc_html($property_bathrooms) ?></p>

                                <p class="property-info-title"><?php
                                    echo ere_get_number_text($property_bathrooms, esc_html__('Bathrooms', 'essential-real-estate'), esc_html__('Bathroom', 'essential-real-estate'));
                                    ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="property-thumb">
                    <?php
                    $attach_id = get_post_thumbnail_id($property_id);
                    $image_src = '';

                    $image_src = ere_image_resize_id($attach_id, 1160, 500, true);
                    if (!empty($image_src)) { ?>
                        <img width="1160" height="500"
                             src="<?php echo esc_url($image_src) ?>" alt="<?php the_title(); ?>"
                             title="<?php the_title(); ?>">
                    <?php } ?>
                </div>
            </div>
            <?php $description = $the_post->post_content;
            if (isset($description) && !empty($description)):?>
                <div class="property-block description-block clearfix">
                    <h4 class="property-block-title"><?php esc_html_e('Description', 'essential-real-estate'); ?></h4>
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>
            <div class="property-block location-block clearfix">
                <h4 class="property-block-title"><?php esc_html_e('Location', 'essential-real-estate'); ?></h4>
                <?php if (!empty($property_address)): ?>
                    <div class="property-address">
                        <strong><?php esc_html_e('Address:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($property_address) ?></span>
                    </div>
                <?php endif; ?>
                <ul class="list-2-col ere-property-list">
                    <?php if (!empty($property_country)):
                        $property_country = ere_get_country_by_code($property_country); ?>
                        <li>
                            <strong><?php esc_html_e('Country:', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_country); ?></span>
                        </li>
                    <?php endif;
                    if (count($property_state_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('Province / State:', 'essential-real-estate'); ?></strong>
                            <span><?php echo join(', ', $property_state_arr); ?></span>
                        </li>
                    <?php endif;
                    if (count($property_city_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('City:', 'essential-real-estate'); ?></strong>
                            <span><?php echo join(', ', $property_city_arr); ?></span>
                        </li>
                    <?php endif;
                    if (count($property_neighborhood_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('Neighborhood:', 'essential-real-estate'); ?></strong>
                            <span><?php echo join(', ', $property_neighborhood_arr); ?></span>
                        </li>
                    <?php endif;
                    if (!empty($property_zip)): ?>
                        <li>
                            <strong><?php esc_html_e('Postal code / ZIP:', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_zip) ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="property-block overview-block clearfix">
                <h4 class="property-block-title"><?php esc_html_e('Overview', 'essential-real-estate'); ?></h4>
                <ul class="list-2-col ere-property-list">
                    <li>
                        <strong><?php esc_html_e('Property ID', 'essential-real-estate'); ?></strong>
                        <span><?php
                            if (!empty($property_identity)) {
                                echo esc_html($property_identity);
                            } else {
                                echo get_the_ID();
                            }
                            ?></span>
                    </li>
                    <?php if (!empty($price)): ?>
                        <li>
                            <strong><?php esc_html_e('Price', 'essential-real-estate'); ?></strong>
                            <span class="ere-property-price">
                                    <?php if (!empty($price_prefix)) {
                                        echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                    } ?>
                                    <?php echo ere_get_format_money($price_short, $price_unit) ?>
                                    <?php if (!empty($price_postfix)) {
                                        echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                    } ?>
                                </span>
                        </li>
                    <?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
                        <li>
                            <strong><?php esc_html_e('Price', 'essential-real-estate'); ?></strong>
                            <span><?php echo ere_get_option('empty_price_text', '') ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (count($property_type_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('Property Type', 'essential-real-estate'); ?></strong>
                            <span><?php echo join(', ', $property_type_arr) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (count($property_status_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('Property status', 'essential-real-estate'); ?></strong>
                            <span><?php echo join(', ', $property_status_arr) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_rooms)): ?>
                        <li>
                            <strong><?php esc_html_e('Rooms', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_rooms) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_bedrooms)): ?>
                        <li>
                            <strong><?php esc_html_e('Bedrooms', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_bedrooms) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_bathrooms)): ?>
                        <li>
                            <strong><?php esc_html_e('Bathrooms', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_bathrooms) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_year)): ?>
                        <li>
                            <strong><?php esc_html_e('Year Built', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_year) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_size)): ?>
                        <li>
                            <strong><?php esc_html_e('Size', 'essential-real-estate'); ?></strong>

                            <span><?php echo wp_kses_post(sprintf('%s %s', ere_get_format_number($property_size), $measurement_units)) ; ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_land)): ?>
                        <li>
                            <strong><?php esc_html_e('Land area', 'essential-real-estate'); ?></strong>
                            <span><?php $measurement_units_land_area = ere_get_measurement_units_land_area();
                                echo wp_kses_post(sprintf('%s %s', ere_get_format_number($property_land), $measurement_units_land_area)); ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if (count($property_label_arr) > 0): ?>
                        <li>
                            <strong><?php esc_html_e('Label', 'essential-real-estate'); ?></strong>
                            <?php if ($property_label_arr): ?>
                                <span><?php echo join(', ', $property_label_arr) ?></span><?php endif; ?>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($property_garage)): ?>
                        <li>
                            <strong><?php esc_html_e('Garages', 'essential-real-estate'); ?></strong>
                            <span><?php echo esc_html($property_garage) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($property_garage_size)): ?>
                        <li>
                            <strong><?php esc_html_e('Garage Size', 'essential-real-estate'); ?></strong>
                            <span><?php echo wp_kses_post(sprintf('%s %s', $property_garage_size, $measurement_units)); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php
                    $additional_fields = ere_render_additional_fields();
                    if (count($additional_fields) > 0):
                        foreach ($additional_fields as $key => $field):
                            $property_field = get_post_meta($property_id, $field['id'], true);
                            if (!empty($property_field)):?>
                                <li>
                                    <strong><?php echo esc_html($field['title']); ?></strong>
                                    <span><?php
                                        if ($field['type'] == 'checkbox_list') {
                                            $text = '';
                                            if (count($property_field) > 0) {
                                                foreach ($property_field as $value => $v) {
                                                    $text .= $v . ', ';
                                                }
                                            }
                                            $text = rtrim($text, ', ');
                                            echo esc_html($text);
                                        } else {
                                            echo esc_html($property_field);
                                        }
                                        ?></span>
                                </li>
                            <?php
                            endif;
                        endforeach;
                    endif; ?>
                    <?php for ($i = 0; $i < $additional_features; $i++) { ?>
                        <?php if (!empty($additional_feature_title[$i]) && !empty($additional_feature_value[$i])): ?>
                            <li>
                                <strong><?php echo esc_html($additional_feature_title[$i]); ?></strong>
                                <span><?php echo esc_html($additional_feature_value[$i]) ?></span>
                            </li>
                        <?php endif; ?>
                    <?php } ?>
                </ul>
            </div>
            <?php if ($property_features): ?>
                <div class="property-block features-block clearfix">
                    <h4 class="property-block-title"><?php esc_html_e('Features', 'essential-real-estate'); ?></h4>
                    <?php foreach ($property_features as $features_item) {
                        echo '<div class="feature-item"><span><i class="fa fa-check-square-o"></i> ' . $features_item->name . '</span></div>';
                    } ?>
                </div>
            <?php endif; ?>

            <?php $property_floors = get_post_meta($property_id, ERE_METABOX_PREFIX . 'floors', true);
            $property_floor_enable = isset($property_meta_data[ERE_METABOX_PREFIX . 'floors_enable']) ? $property_meta_data[ERE_METABOX_PREFIX . 'floors_enable'][0] : '';
            if ($property_floor_enable && $property_floors): ?>
                <div class="property-block floors-block">
                    <h4 class="property-block-title"><?php esc_html_e('Floor Plans', 'essential-real-estate'); ?></h4>
                </div>
                <?php $index = 0; ?>
                <?php foreach ($property_floors as $floor):
                    $image_id = $floor[ERE_METABOX_PREFIX . 'floor_image']['id'];
                    $width = '870';
                    $height = '420';
                    $image_src = ere_image_resize_id($image_id, 870, 420, true);
                    $floor_name = $floor[ERE_METABOX_PREFIX . 'floor_name'];
                    $floor_size = $floor[ERE_METABOX_PREFIX . 'floor_size'];
                    $floor_size_postfix = $floor[ERE_METABOX_PREFIX . 'floor_size_postfix'];
                    $floor_bathrooms = $floor[ERE_METABOX_PREFIX . 'floor_bathrooms'];
                    $floor_price = $floor[ERE_METABOX_PREFIX . 'floor_price'];
                    $floor_price_postfix = $floor[ERE_METABOX_PREFIX . 'floor_price_postfix'];
                    $floor_bedrooms = $floor[ERE_METABOX_PREFIX . 'floor_bedrooms'];
                    $floor_description = $floor[ERE_METABOX_PREFIX . 'floor_description'];
                    ?>
                    <div class="floor-item">
                        <div class="floor-info">
                            <?php if (isset($floor_name) && !empty($floor_name)): ?>
                                <h4><?php echo !empty($floor_name) ? esc_html($floor_name) : (esc_html__('Floor', 'essential-real-estate') . ' ' . ($index + 1)) ?></h4>
                            <?php endif; ?>
                            <div class="pull-right floor-main-info">
                                <?php if (isset($floor_size) && !empty($floor_size)): ?>
                                    <div class="floor-size">
												<span
                                                        class="floor-info-title"><?php esc_html_e('Size:', 'essential-real-estate'); ?></span>
                                        <span
                                                class="floor-info-value"><?php echo esc_html($floor_size); ?>
                                            <?php echo (isset($floor_size_postfix) && !empty($floor_size_postfix)) ? wp_kses_post($floor_size_postfix) : '' ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($floor_bedrooms) && !empty($floor_bedrooms)): ?>
                                    <div class="floor-bed">
												<span
                                                        class="floor-info-title"><?php esc_html_e('Bedrooms:', 'essential-real-estate'); ?></span>
                                        <span
                                                class="floor-info-value"><?php echo esc_html($floor_bedrooms); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($floor_bathrooms) && !empty($floor_bathrooms)): ?>
                                    <div class="floor-bath">
												<span
                                                        class="floor-info-title"><?php esc_html_e('Bathrooms:', 'essential-real-estate'); ?></span>
                                        <span
                                                class="floor-info-value"><?php echo esc_html($floor_bathrooms); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($floor_price) && !empty($floor_price)): ?>
                                    <div class="floor-price">
												<span
                                                        class="floor-info-title"><?php esc_html_e('Price:', 'essential-real-estate'); ?></span>
                                        <span
                                                class="floor-info-value"><?php echo ere_get_format_money($floor_price); ?>
                                            <?php echo (isset($floor_price_postfix) && !empty($floor_price_postfix)) ? '/' . wp_kses_post($floor_price_postfix ) : '' ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($image_src)): ?>
                            <div class="floor-image">
                                <img width="<?php echo esc_attr($width) ?>"
                                     height="<?php echo esc_attr($height) ?>"
                                     src="<?php echo esc_url($image_src); ?>"
                                     alt="<?php the_title_attribute(); ?>">
                            </div>
                        <?php endif; ?>
                        <?php if (isset($floor_description) && !empty($floor_description)): ?>
                            <div class="floor-description">
                                <?php echo wp_kses_post($floor_description); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php $index++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php
            $agent_display_option = isset($property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option']) ? $property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option'][0] : '';
            if ($agent_display_option != 'no'):?>
                <div class="property-block property-contact-agent">
                    <h4 class="property-block-title"><?php esc_html_e('Contact', 'essential-real-estate'); ?></h4>
                    <?php
                    $property_agent = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_agent']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_agent'][0] : '';
                    $property_other_contact_mail = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_mail']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_mail'][0] : '';
                    $agent_type = '';
                    $user_id = 0;
                    if ($agent_display_option == 'author_info' || ($agent_display_option == 'other_info' && !empty($property_other_contact_mail)) || ($agent_display_option == 'agent_info' && !empty($property_agent))): ?>
                        <div class="agent-info">
                            <?php
                            $email = $avatar_src = $agent_link = $agent_name = $agent_position = $agent_facebook_url = $agent_twitter_url =
                            $agent_youtube_url = $agent_vimeo_url = $agent_mobile_number = $agent_office_address = $agent_website_url = $agent_description = '';
                            if ($agent_display_option != 'other_info') {
                                $width = 270;
                                $height = 340;
                                $no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
                                $default_avatar = ere_get_option('default_user_avatar', '');
                                if ($default_avatar != '') {
                                    if (is_array($default_avatar) && $default_avatar['url'] != '') {
                                        $resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
                                        if ($resize != null && is_array($resize)) {
                                            $no_avatar_src = $resize['url'];
                                        }
                                    }
                                }
                                if ($agent_display_option == 'author_info') {
                                    $user_id = $the_post->post_author;
                                    $email = get_userdata($user_id)->user_email;
                                    $user_info = get_userdata($user_id);
                                    // Show Property Author Info (Get info via User. Apply for User, Agent, Seller)
                                    $author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $user_id);
                                    $avatar_src = ere_image_resize_id($author_picture_id, $width, $height, true);

                                    if (empty($user_info->first_name) && empty($user_info->last_name)) {
                                        $agent_name = $user_info->user_login;
                                    } else {
                                        $agent_name = $user_info->first_name . ' ' . $user_info->last_name;
                                    }
                                    $agent_facebook_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_facebook_url', $user_id);
                                    $agent_twitter_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_twitter_url', $user_id);
                                    $agent_linkedin_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id);
                                    $agent_pinterest_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id);
                                    $agent_instagram_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_instagram_url', $user_id);
                                    $agent_skype = get_the_author_meta(ERE_METABOX_PREFIX . 'author_skype', $user_id);
                                    $agent_youtube_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_youtube_url', $user_id);
                                    $agent_vimeo_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id);

                                    $agent_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $user_id);
                                    $agent_office_address = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_address', $user_id);
                                    $agent_website_url = get_the_author_meta('user_url', $user_id);
                                    $author_agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
	                                $agent_status = get_post_status($author_agent_id);
	                                if ($agent_status == 'publish') {
		                                $agent_position = esc_html__('Property Agent', 'essential-real-estate');
		                                $agent_type = esc_html__('Agent', 'essential-real-estate');
		                                $agent_link = get_the_permalink($author_agent_id);
                                    } else {
		                                $agent_position = esc_html__('Property Seller', 'essential-real-estate');
		                                $agent_type = esc_html__('Seller', 'essential-real-estate');
		                                $agent_link = get_author_posts_url($user_id);
                                    }
                                } else {
                                    $agent_post_meta_data = get_post_custom($property_agent);
                                    $email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';
                                    $agent_name = get_the_title($property_agent);
                                    $avatar_id = get_post_thumbnail_id($property_agent);
                                    $avatar_src = ere_image_resize_id($avatar_id, $width, $height, true);

                                    $agent_facebook_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url'][0] : '';
                                    $agent_twitter_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url'][0] : '';
                                    $agent_linkedin_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url'][0] : '';
                                    $agent_pinterest_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url'][0] : '';
                                    $agent_instagram_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url'][0] : '';
                                    $agent_skype = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype'][0] : '';
                                    $agent_youtube_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url'][0] : '';
                                    $agent_vimeo_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url'][0] : '';

                                    $agent_mobile_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number'][0] : '';
                                    $agent_office_address = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address'][0] : '';
                                    $agent_website_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url'][0] : '';

                                    $agent_position = esc_html__('Property Agent', 'essential-real-estate');
                                    $agent_type = esc_html__('Agent', 'essential-real-estate');
                                    $agent_link = get_the_permalink($property_agent);
                                }
                            } elseif ($agent_display_option == 'other_info') {
                                $email = $property_other_contact_mail;
                                $agent_name = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name'][0] : '';
                                $agent_mobile_number = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_phone']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_phone'][0] : '';
                                $agent_description = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_description']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_description'][0] : '';
                            }
                            ?>
                            <?php if ($agent_display_option != 'other_info'): ?>
                                <div class="list-2-col">
                                    <div class="agent-avatar">
                                        <img
                                                src="<?php echo esc_url($avatar_src) ?>"
                                                onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                                                alt="<?php echo esc_attr($agent_name) ?>"
                                                title="<?php echo esc_attr($agent_name) ?>">
                                    </div>
                                    <div class="agent-content">
                                        <div class="agent-heading">
                                            <?php if (!empty($agent_name)): ?>
                                                <h4><?php echo esc_html($agent_name) ?></h4>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_position)): ?>
                                                <span class="fw-normal"><?php echo esc_html($agent_position) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="agent-social">
                                            <?php if (!empty($agent_facebook_url)): ?>
                                                <p>
                                                    <i class="fa fa-facebook"></i><?php echo esc_url($agent_facebook_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_twitter_url)): ?>
                                                <p>
                                                    <i class="fa fa-twitter"></i><?php echo esc_url($agent_twitter_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_skype)): ?>
                                                <p><i class="fa fa-skype"></i><?php echo esc_html($agent_skype); ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_linkedin_url)): ?>
                                                <p>
                                                    <i class="fa fa-linkedin"></i><?php echo esc_url($agent_linkedin_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_pinterest_url)): ?>
                                                <p>
                                                    <i class="fa fa-pinterest"></i><?php echo esc_url($agent_pinterest_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_instagram_url)): ?>
                                                <p>
                                                    <i class="fa fa-instagram"></i><?php echo esc_url($agent_instagram_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_youtube_url)): ?>
                                                <p>
                                                    <i class="fa fa-youtube-play"></i><?php echo esc_url($agent_youtube_url); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_vimeo_url)): ?>
                                                <p><i class="fa fa-vimeo"></i><?php echo esc_url($agent_vimeo_url); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="agent-info">
                                            <?php if (!empty($agent_office_address)): ?>
                                                <p>
                                                    <i class="fa fa-map-marker"></i><?php echo esc_html($agent_office_address); ?>
                                                </p>

                                            <?php endif; ?>
                                            <?php if (!empty($agent_mobile_number)): ?>
                                                <p>
                                                    <i class="fa fa-phone"></i><?php echo esc_html($agent_mobile_number); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($email)): ?>
                                                <p>
                                                    <i class="fa fa-envelope"></i><?php echo esc_html($email); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_website_url)): ?>
                                                <p>
                                                    <i class="fa fa-link"></i><?php echo esc_url($agent_website_url); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($agent_description)): ?>
                                            <div class="description">
                                                <?php echo wp_kses_post($agent_description); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="agent-content">
                                    <div class="agent-heading">
                                        <?php if (!empty($agent_name)): ?>
                                            <h4><?php esc_html_e('Name: ', 'essential-real-estate') ?><?php echo esc_html($agent_name) ?></h4>
                                        <?php endif; ?>
                                    </div>
                                    <div class="agent-info">
                                        <?php if (!empty($agent_mobile_number)): ?>
                                            <p>
                                                <i class="fa fa-phone"></i><?php echo esc_html($agent_mobile_number); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($email)): ?>
                                            <p>
                                                <i class="fa fa-envelope"></i><?php echo esc_html($email); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($agent_description)): ?>
                                        <div class="description">
                                            <?php echo wp_kses_post($agent_description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </body>
</html>

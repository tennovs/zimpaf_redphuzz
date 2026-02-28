<?php
/**
 * @var $layout_style
 * @var $data
 * @var $color_scheme
 * @var $item_amount
 * @var $image_size2
 * @var $include_heading
 * @var $heading_sub_title
 * @var $heading_title
 * @var $heading_text_align
 * @var $property_cities
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$property_content_class = array('property-content-wrap row');
$property_item_class = array('property-item');
$property_content_attributes = array('data-type="carousel"');
$filter_class = array('hidden-mb property-filter-content');
$filter_attributes = array();

$filter_attributes[] = 'data-layout_style="' . $layout_style . '"';
$filter_attributes[] = "data-property_type='" . $property_type . "'";
$filter_attributes[] = "data-property_status='" . $property_status . "'";
$filter_attributes[] = "data-property_feature='" . $property_feature . "'";
$filter_attributes[] = "data-property_cities='" . $property_cities . "'";
$filter_attributes[] = "data-property_state='" . $property_state . "'";
$filter_attributes[] = "data-property_neighborhood='" . $property_neighborhood . "'";
$filter_attributes[] = "data-property_label='" . $property_label . "'";
$filter_attributes[] = "data-color_scheme='" . $color_scheme . "'";
$filter_attributes[] = "data-item_amount='" . $item_amount . "'";
$filter_attributes[] = "data-image_size='" . $image_size2 . "'";
$filter_attributes[] = "data-include_heading='" . $include_heading . "'";
$filter_attributes[] = "data-heading_sub_title='" . $heading_sub_title . "'";
$filter_attributes[] = "data-heading_title='" . $heading_title . "'";
$filter_attributes[] = "data-heading_text_align='" . $heading_text_align . "'";
$filter_attributes[] = "data-property_city='" . $property_city . "'";
$filter_attributes[] = "data-el_class='" . $el_class . "'";
$filter_attributes[] = 'data-item=".property-item"';
$property_content_attributes[] = 'data-filter-content="filter"';

$owl_attributes = array(
    '"dots": true',
    '"nav": false',
    '"items": 1',
    '"autoplay": true',
    '"autoplayTimeout": 1000'
);

$property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
$filter_class[] = 'property-filter-carousel';
$filter_attributes[] = 'data-filter-type="carousel"';
$property_content_attributes[] = 'data-layout="filter"';
?>
<?php $filter_id = rand(); ?>
<?php if ($include_heading && (!empty($heading_sub_title) || !empty($heading_title))) :
    $heading_class=$color_scheme.' '. $heading_text_align;
    ?>
    <div class="ere-heading <?php echo esc_attr($heading_class); ?>">
        <?php if (!empty($heading_title)): ?>
            <h2><?php echo esc_html($heading_title); ?></h2>
        <?php endif; ?>
        <?php if (!empty($heading_sub_title)): ?>
            <p><?php echo esc_html($heading_sub_title); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="<?php echo join(' ', $property_content_class); ?>">
    <div class="filter-wrap col-md-3" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
        <div data-filter_id="<?php echo esc_attr($filter_id); ?>"
             class="<?php echo join(' ', $filter_class); ?>" <?php echo join(' ', $filter_attributes); ?>>
            <?php
            if (!empty($property_cities)) {
                $property_city_arr = explode(',', $property_cities);
                $index = 0;
                foreach ($property_city_arr as $property_city) {
                    $city = get_term_by('slug', $property_city, 'property-city', 'OBJECT'); ?>
                    <a class="portfolio-filter-category<?php if ($index == 0): ?> active-filter<?php endif; ?>"
                       data-filter=".<?php echo esc_attr($property_city); ?>"><?php echo esc_html($city->name) ?></a>
                    <?php
                    $index++;
                }
            } ?>
        </div>
        <div class="visible-mb">
            <select class="property-filter-mb form-control">
                <?php
                if (!empty($property_cities)) {
                    $property_city_arr = explode(',', $property_cities);
                    $index = 0;
                    foreach ($property_city_arr as $property_city) {
                        $city = get_term_by('slug', $property_city, 'property-city', 'OBJECT'); ?>
                        <option<?php if ($index == 0): ?> selected<?php endif; ?>
                            value=".<?php echo esc_attr($property_city); ?>"><?php echo esc_html($city->name) ?></option>
                        <?php
                        $index++;
                    }
                } ?>
            </select>
        </div>
    </div>
    <div class="property-content-inner col-md-9">
        <div class="property-content owl-carousel" <?php echo join(' ', $property_content_attributes); ?>
             data-filter_id="<?php echo esc_attr($filter_id); ?>">
            <?php if ($data->have_posts()) :
                $width = 835;
                $height = 320;
                $no_image_src = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
                $default_image = ere_get_option('default_property_image', '');
                $image_size=$image_size2;
                if (preg_match('/\d+x\d+/', $image_size)) {
                    $image_sizes = explode('x', $image_size);
                    $width=$image_sizes[0];$height= $image_sizes[1];
                    if($default_image!='')
                    {
                        if(is_array($default_image)&& $default_image['url']!='')
                        {
                            $resize = ere_image_resize_url($default_image['url'], $width, $height, true);
                            if ($resize != null && is_array($resize)) {
                                $no_image_src = $resize['url'];
                            }
                        }
                    }
                } else {
                    if($default_image!='')
                    {
                        if(is_array($default_image)&& $default_image['url']!='')
                        {
                            $no_image_src = $default_image['url'];
                        }
                    }
                }
                while ($data->have_posts()): $data->the_post();
                    $property_id=get_the_ID();
                    $attach_id = get_post_thumbnail_id();
                    $image_src  = '';
                    if (preg_match('/\d+x\d+/', $image_size)) {
                        $image_sizes = explode('x', $image_size);
                        $width=$image_sizes[0];$height= $image_sizes[1];
                        $image_src = ere_image_resize_id($attach_id, $width, $height, true);
                    } else {
                        if (!in_array($image_size, array('full', 'thumbnail'))) {
                            $image_size = 'full';
                        }
                        $image_src = wp_get_attachment_image_src($attach_id, $image_size);
                        if ($image_src && !empty($image_src[0])) {
                            $image_src = $image_src[0];
                        }
                        if (!empty($image_src)) {
                            list($width, $height) = getimagesize($image_src);
                        }
                    }
                    $excerpt = get_the_excerpt();

                    $property_meta_data = get_post_custom($property_id);

                    $price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
                    $price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
                    $price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
                    $price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
                    $price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';
                    $property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
                    $property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
                    $property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';

                    $property_link = get_the_permalink();
                    ?>
                    <div class="<?php echo join(' ', $property_item_class); ?>">
                        <div class="property-inner">
                            <div class="property-image">
                                <a href="<?php echo esc_url($property_link); ?>"
                                   title="<?php the_title(); ?>"></a>
                                <img width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>"
                                     src="<?php echo esc_url($image_src) ?>"
                                     onerror="this.src = '<?php echo esc_url($no_image_src) ?>';"
                                     alt="<?php the_title(); ?>"
                                     title="<?php the_title(); ?>">
                            </div>
                            <div class="property-item-content">
                                <div class="property-heading">
                                    <a href="<?php echo esc_url($property_link); ?>"
                                       title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    <?php if (!empty($price)): ?>
                                        <span class="property-price">
                                            <?php if (!empty($price_prefix)) {
                                                echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                            } ?>
                                            <?php echo ere_get_format_money($price_short,$price_unit) ?>
                                            <?php if (!empty($price_postfix)) {
                                                echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                            } ?>
                                        </span>
                                    <?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
                                        <span
                                            class="property-price"><?php echo ere_get_option('empty_price_text', '') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="property-info">
                                    <div class="property-info-inner">
                                        <div class="property-id">
                                            <div class="property-info-item-inner">
                                                <span class="fa fa-barcode"></span>

                                                <div class="content-property-info">
                                                    <p class="property-info-value"><?php
                                                        $property_identity  = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ][0] : '';
                                                        if(!empty($property_identity))
                                                        {
                                                            echo esc_html($property_identity);
                                                        }
                                                        else
                                                        {
                                                            echo esc_html($property_id);
                                                        }?>
                                                    </p>

                                                    <p class="property-info-title"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($property_size)): ?>
                                            <div class="property-area">
                                                <div class="property-info-item-inner">
                                                    <span class="fa fa-arrows"></span>

                                                    <div class="content-property-info">
                                                        <p class="property-info-value"><?php
                                                            echo ere_get_format_number($property_size) ?>
                                                            <span><?php
                                                                $measurement_units = ere_get_measurement_units();
                                                                echo wp_kses_post($measurement_units); ?></span>
                                                        </p>

                                                        <p class="property-info-title"><?php esc_html_e('Size', 'essential-real-estate'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($property_bedrooms)): ?>
                                            <div class="property-bedrooms">
                                                <div class="property-info-item-inner">
                                                    <span class="fa fa-hotel"></span>

                                                    <div class="content-property-info">
                                                        <p class="property-info-value"><?php echo esc_html($property_bedrooms) ?></p>

                                                        <p class="property-info-title"><?php
                                                            echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' ));
                                                            ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($property_bathrooms)): ?>
                                            <div class="property-bathrooms">
                                                <div class="property-info-item-inner">
                                                    <span class="fa fa-bath"></span>

                                                    <div class="content-property-info">
                                                        <p class="property-info-value"><?php echo esc_html($property_bathrooms) ?></p>

                                                        <p class="property-info-title"><?php
                                                            echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' ));
                                                            ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;

            else: ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>





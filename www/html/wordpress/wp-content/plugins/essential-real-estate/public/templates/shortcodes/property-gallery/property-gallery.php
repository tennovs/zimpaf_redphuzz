<?php
/**
 * @var $atts
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$property_types = $property_status = $property_feature = $property_cities = $property_state =
$property_neighborhood = $property_label = $property_featured = $is_carousel = $color_scheme = $category_filter = $filter_style =
$include_heading = $heading_sub_title = $heading_title = $item_amount = $image_size = $columns_gap = $columns =
$dots = $nav = $autoplay = $autoplaytimeout = $property_type = $el_class = '';
extract(shortcode_atts(array(
    'property_types' => '',
    'property_status' => '',
    'property_feature' => '',
    'property_cities' => '',
    'property_state' => '',
    'property_neighborhood' => '',
    'property_label' => '',
    'property_featured' => '',
    'is_carousel' => '',
    'color_scheme' => 'color-dark',
    'category_filter' => '',
    'filter_style' => 'filter-isotope',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'item_amount' => '6',
    'image_size' => '290x270',
    'columns_gap' => 'col-gap-0',
    'columns' => '4',
    'dots' => '',
    'nav' => '',
    'autoplay' => 'true',
    'autoplaytimeout' => 1000,
    'property_type' => '',
    'el_class' => ''
), $atts));

$property_item_class = array('property-item');
$property_content_class = array('property-content clearfix');
$property_content_attributes = array();
$content_attributes = array();
$filter_class = array('hidden-mb property-filter-content');
$filter_attributes = array();
if (empty($property_types)) {
    $property_types_all = get_categories(array('taxonomy' => 'property-type', 'hide_empty' => 0, 'orderby' => 'ASC'));
    $property_types = array();
    if (is_array($property_types_all)) {
        foreach ($property_types_all as $property_typ) {
            $property_types[] = $property_typ->slug;
        }
        $property_types = join(',', $property_types);
    }
}

if ($category_filter) {
    $filter_attributes[] = 'data-is-carousel="' . $is_carousel . '"';
    $filter_attributes[] = 'data-columns-gap="' . $columns_gap . '"';
    $filter_attributes[] = 'data-columns="' . $columns . '"';
    $filter_attributes[] = "data-item-amount='" . $item_amount . "'";
    $filter_attributes[] = "data-image-size='" . $image_size . "'";
    $filter_attributes[] = "data-color-scheme='" . $color_scheme . "'";
    $filter_attributes[] = 'data-item=".property-item"';
    $content_attributes[] = 'data-filter-content="filter"';
}
$wrapper_classes = array(
    'ere-property-gallery clearfix',
    $color_scheme,
    $el_class,
);

if ($columns_gap == 'col-gap-30') {
    $col_gap = 30;
} elseif ($columns_gap == 'col-gap-20') {
    $col_gap = 20;
} elseif ($columns_gap == 'col-gap-10') {
    $col_gap = 10;
} else {
    $col_gap = 0;
}
if ($is_carousel) {
    $content_attributes[] = 'data-type="carousel"';
    $property_content_class[] = 'owl-carousel manual';
    $owl_attributes = array(
        '"dots": ' . ($dots ? 'true' : 'false'),
        '"nav": ' . ($nav ? 'true' : 'false'),
        '"items": 1',
        '"autoplay": ' . ($autoplay ? 'true' : 'false'),
        '"autoplayTimeout": ' . ($autoplaytimeout ? $autoplaytimeout : 1000),
        '"responsive": {"0" : {"items" : 1, "margin": 0}, "480" : {"items" : 2, "margin": ' . $col_gap . '},
		"992" : {"items" : ' . (($columns >= 3) ? 3 : $columns) . ', "margin": ' . $col_gap . '},
		"1200" : {"items" : ' . $columns . ', "margin": ' . $col_gap . '}}'
    );
    $property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
    if ($category_filter) {
        $filter_class[] = 'property-filter-carousel';
        $filter_attributes[] = 'data-filter-type="carousel"';
        $content_attributes[] = 'data-layout="filter"';
    }
} else {
    $content_attributes[] = 'data-type="grid"';
    $content_attributes[] = 'data-layout="fitRows"';
    $wrapper_classes[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $property_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $property_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $property_item_class[] = 'mg-bottom-10';
    }
    $property_content_class[] = 'row';
    $property_content_class[] = 'columns-' . $columns;
    $property_content_class[] = 'columns-md-' . ($columns >= 3 ? 3 : $columns);
    $property_content_class[] = 'columns-sm-2';
    $property_content_class[] = 'columns-xs-2';
    $property_content_class[] = 'columns-mb-1';
    $property_item_class[] = 'ere-item-wrap';
    if ($category_filter) {
        $filter_attributes[] = 'data-filter-type="filter"';
        $filter_attributes[] = 'data-filter-style="' . $filter_style . '"';
    }
}

$args = array(
    'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
    'post_type' => 'property',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
    'post_status' => 'publish',
);
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
if (!empty($author)) {
    $args['author'] = $author;
}
$args['tax_query'] = array();
if ($property_type != '') {
    $args['tax_query'][] = array(
        'taxonomy' => 'property-type',
        'field' => 'slug',
        'terms' => explode(',', $property_type),
        'operator' => 'IN'
    );
}
if (!empty($property_types) || !empty($property_status) || !empty($property_feature) || !empty($property_city)
    || !empty($property_state) || !empty($property_neighborhood) || !empty($property_label)
) {
    if (!empty($property_types) && empty($property_type)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-type',
            'field' => 'slug',
            'terms' => explode(',', $property_types),
            'operator' => 'IN'
        );
    }
    if (!empty($property_status)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-status',
            'field' => 'slug',
            'terms' => explode(',', $property_status),
            'operator' => 'IN'
        );
    }
    if (!empty($property_feature)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-feature',
            'field' => 'slug',
            'terms' => explode(',', $property_feature),
            'operator' => 'IN'
        );
    }
    if (!empty($property_city)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-city',
            'field' => 'slug',
            'terms' => explode(',', $property_city),
            'operator' => 'IN'
        );
    }
    if (!empty($property_state)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-state',
            'field' => 'slug',
            'terms' => explode(',', $property_state),
            'operator' => 'IN'
        );
    }
    if (!empty($property_neighborhood)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-neighborhood',
            'field' => 'slug',
            'terms' => explode(',', $property_neighborhood),
            'operator' => 'IN'
        );
    }
    if (!empty($property_label)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-label',
            'field' => 'slug',
            'terms' => explode(',', $property_label),
            'operator' => 'IN'
        );
    }
}

if ($property_featured == 'true') {
    $args['meta_query'] = array(
        array(
            'key' => ERE_METABOX_PREFIX . 'property_featured',
            'value' => true,
            'compare' => '=',
        )
    );
}
$args = apply_filters('ere_shortcodes_property_gallery_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
<div class="ere-property-wrap">
    <div class="<?php echo join(' ', $wrapper_classes) ?>">
        <?php $filter_id = rand(); ?>
        <?php if ($category_filter):
            $filter_item_class = 'portfolio-filter-category';
            ?>
            <div class="filter-wrap">
                <div class="filter-inner" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
                    <?php if ($include_heading && (!empty($heading_sub_title) || !empty($heading_title))) :
                        $heading_class=$color_scheme;
                        ?>
                        <div class="ere-heading <?php echo esc_attr($heading_class) ?>">
                            <?php if (!empty($heading_title)): ?>
                                <h2><?php echo esc_html($heading_title); ?></h2>
                            <?php endif; ?>
                            <?php if (!empty($heading_sub_title)): ?>
                                <p><?php echo esc_html($heading_sub_title); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div
                        data-filter_id="<?php echo esc_attr($filter_id); ?>" <?php echo implode(' ', $filter_attributes); ?>
                        class="<?php echo implode(' ', $filter_class); ?>">
                        <?php
                        if (!empty($property_types)) {
                            $property_type_arr = explode(',', $property_types);?>
                            <a data-filter="*" class="<?php echo esc_attr($filter_item_class); ?> active-filter"><?php esc_html_e('All', 'essential-real-estate') ?></a>
                            <?php
                            foreach ($property_type_arr as $type_item) {
                                $type = get_term_by('slug', $type_item, 'property-type', 'OBJECT'); ?>
                                <a class="<?php echo esc_attr($filter_item_class); ?>"
                                   data-filter=".<?php echo esc_attr($type_item); ?>"><?php echo esc_attr($type->name) ?></a>
                                <?php
                            }
                        } ?>
                    </div>
                    <div class="visible-mb">
                        <select class="property-filter-mb form-control">
                            <?php
                            if (!empty($property_types)) {
                                $property_type_arr = explode(',', $property_types);?>
                                <option value="*" selected><?php esc_html_e('All', 'essential-real-estate') ?></option>
                                <?php
                                foreach ($property_type_arr as $type_item) {
                                    $type = get_term_by('slug', $type_item, 'property-type', 'OBJECT'); ?>
                                    <option
                                        value=".<?php echo esc_attr($type_item); ?>"><?php echo esc_html($type->name) ?></option>
                                    <?php
                                }
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($is_carousel): ?>
        <div
            class="<?php echo join(' ', $property_content_class) ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
            data-callback="owl_callback" <?php echo implode(' ', $property_content_attributes); ?>
            <?php echo implode(' ', $content_attributes); ?>>
            <?php else: ?>
            <div
                class="<?php echo join(' ', $property_content_class) ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
                <?php echo implode(' ', $content_attributes); ?>>
                <?php endif; ?>
                <?php if ($data->have_posts()) :
                    $no_image_src = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
                    $default_image = ere_get_option('default_property_image', '');
                    if (preg_match('/\d+x\d+/', $image_size)) {
                        $image_sizes = explode('x', $image_size);
                        $width = $image_sizes[0];
                        $height = $image_sizes[1];
                        if ($default_image != '') {
                            if (is_array($default_image) && $default_image['url'] != '') {
                                $resize = ere_image_resize_url($default_image['url'], $width, $height, true);
                                if ($resize != null && is_array($resize)) {
                                    $no_image_src = $resize['url'];
                                }
                            }
                        }
                    } else {
                        if ($default_image != '') {
                            if (is_array($default_image) && $default_image['url'] != '') {
                                $no_image_src = $default_image['url'];
                            }
                        }
                    }
                    while ($data->have_posts()): $data->the_post();
                        $property_id=get_the_ID();
                        $attach_id = get_post_thumbnail_id();
                        $width = 290;
                        $height = 270;

                        if (preg_match('/\d+x\d+/', $image_size)) {
                            $image_sizes = explode('x', $image_size);
                            $width = $image_sizes[0];
                            $height = $image_sizes[1];
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

                        $price = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                        $price_short = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_short', true);
                        $price_unit = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_unit', true);

                        $price_prefix = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_prefix', true);
                        $price_postfix = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_postfix', true);
                        $property_address = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', true);
                        $property_link = get_the_permalink();

                        $property_type_list = get_the_terms($property_id, 'property-type');
                        $property_type_class = array();
                        if ($property_type_list) {
                            foreach ($property_type_list as $type) {
                                $property_type_class[] = $type->slug;
                            }
                        }
                        ?>
                        <div class="<?php echo join(' ', array_merge($property_item_class, $property_type_class)); ?>">
                            <div class="property-inner">
                                <div class="property-image">
                                    <img width="<?php echo esc_attr($width) ?>"
                                         height="<?php echo esc_attr($height) ?>"
                                         src="<?php echo esc_url($image_src) ?>"
                                         onerror="this.src = '<?php echo esc_url($no_image_src) ?>';"
                                         alt="<?php the_title(); ?>"
                                         title="<?php the_title(); ?>">

                                    <div class="property-item-content">
                                        <h2 class="property-title"><a
                                                href="<?php echo esc_url($property_link); ?>"
                                                title="<?php the_title(); ?>"><?php the_title() ?></a>
                                        </h2>

                                        <div class="property-info">
                                            <?php if (!empty($price)): ?>
                                                <span class="property-price">
                                                    <?php if (!empty($price_prefix)) {
                                                        echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                                    } ?>
                                                    <?php echo ere_get_format_money($price_short, $price_unit) ?>
                                                    <?php if (!empty($price_postfix)) {
                                                        echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                                    } ?>
                                                </span>
                                            <?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
                                                <span
                                                    class="property-price"><?php echo ere_get_option('empty_price_text', '') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($property_address)):
                                                $property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
                                                if ($property_location) {
                                                    $google_map_address_url = "http://maps.google.com/?q=" . $property_location['address'];
                                                } else {
                                                    $google_map_address_url = "http://maps.google.com/?q=" . $property_address;
                                                } ?>
                                                <div class="property-location"
                                                     title="<?php echo esc_attr($property_address) ?>">
                                                    <i class="fa fa-map-marker"></i>
                                                    <a target="_blank"
                                                       href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a class="property-link" href="<?php echo esc_url($property_link); ?>"
                                       title="<?php the_title(); ?>"></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                <?php endif; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$property_type = $property_status = $property_feature = $property_city = $property_state = $property_neighborhood =
$property_label = $property_featured = $item_amount = $columns_gap=$image_size = $color_scheme = $include_heading = $heading_sub_title = $heading_title = $el_class = '';
extract(shortcode_atts(array(
    'property_type' => '',
    'property_status' => '',
    'property_feature' => '',
    'property_city' => '',
    'property_state' => '',
    'property_neighborhood' => '',
    'property_label' => '',
    'property_featured' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-0',
    'image_size' => '330x180',
    'color_scheme' => 'color-dark',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'el_class' => ''
), $atts));

$property_item_class = array('property-item');
$property_content_class = array('property-content');
$property_content_attributes = array();
$wrapper_attributes = array();
$wrapper_classes = array(
    'ere-property-carousel ere-property property-carousel clearfix',
    $color_scheme,
    $el_class
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
$property_content_class[] = 'owl-carousel manual';
$owl_responsive_attributes = array();
// Mobile <= 480px
$owl_responsive_attributes[] = '"0" : {"items" : 1, "margin": '.$col_gap.'}';

// Small devices Tablets ( < 992px)
$owl_responsive_attributes[] = '"768" : {"items" : 2, "margin": '.$col_gap.'}';

// Medium devices ( > 1199px)
$owl_responsive_attributes[] = '"1200" : {"items" : 3, "margin": '.$col_gap.'}';

$owl_responsive_attributes[] = '"1820" : {"items" : 4, "margin": '.$col_gap.'}';

$owl_attributes = array(
    '"dots": false',
    '"nav": true',
    '"responsive": {' . implode(', ', $owl_responsive_attributes) . '}'
);
$property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";

$args = array(
    'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
    'post_type' => 'property',
    'post_status' => 'publish',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
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
if (!empty($property_type) || !empty($property_status) || !empty($property_feature) || !empty($property_city)
    || !empty($property_state) || !empty($property_neighborhood) || !empty($property_label)
) {
    $args['tax_query'] = array();
    if (!empty($property_type)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-type',
            'field' => 'slug',
            'terms' => explode(',', $property_type),
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
$args = apply_filters('ere_shortcodes_property_carousel_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
<div class="ere-property-wrap">
    <div class="<?php echo join(' ', $wrapper_classes) ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
        <div class="navigation-wrap">
            <?php if ($include_heading) :
                $heading_class=$color_scheme;
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
        </div>
        <div class="<?php echo join(' ', $property_content_class) ?>"
             data-callback="owl_callback" <?php echo implode(' ', $property_content_attributes); ?>>
            <?php if ($data->have_posts()) :
                while ($data->have_posts()): $data->the_post();
		                ere_get_template('content-property.php', array(
			                'custom_property_image_size' => $image_size,
			                'property_item_class' => $property_item_class,
		                ));
                    ?>

                <?php endwhile;
            else: ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
            <?php endif; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</div>


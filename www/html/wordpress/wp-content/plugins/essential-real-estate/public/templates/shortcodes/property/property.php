<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$layout_style = $property_type = $property_status = $property_feature = $property_city = $property_state = $property_neighborhood =
$property_label = $property_featured = $item_amount = $columns_gap = $columns = $items_md = $items_sm = $items_xs = $items_mb =
$view_all_link = $image_size = $show_paging = $include_heading = $heading_sub_title = $heading_title =
$dots = $nav = $move_nav = $nav_position = $autoplay = $autoplaytimeout = $paged = $author_id = $agent_id = $el_class = '';
extract(shortcode_atts(array(
    'layout_style' => 'property-grid',
    'property_type' => '',
    'property_status' => '',
    'property_feature' => '',
    'property_city' => '',
    'property_state' => '',
    'property_neighborhood' => '',
    'property_label' => '',
    'property_featured' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-30',
    'columns' => '3',
    'items_lg' => '4',
    'items_md' => '3',
    'items_sm' => '2',
    'items_xs' => '1',
    'items_mb' => '1',
    'view_all_link' => '',
    'image_size' => '330x180',
    'show_paging' => '',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'dots' => '',
    'nav' => 'true',
    'move_nav' => '',
    'nav_position' => '',
    'autoplay' => 'true',
    'autoplaytimeout' => '1000',
    'paged' => '1',
    'author_id' => '',
    'agent_id' => '',
    'el_class' => ''
), $atts));
$property_item_class = array('ere-item-wrap property-item');
$property_content_class = array('property-content');
$property_content_attributes = array();
$wrapper_attributes = array();
$wrapper_classes = array(
    'ere-property clearfix',
    $layout_style,
    $el_class
);

if ($layout_style == 'property-zigzac' || $layout_style == 'property-list') {
    $columns_gap = 'col-gap-0';
}
if ($layout_style == 'property-carousel') {
    $property_content_class[] = 'owl-carousel manual';
    if ($nav) {
        if (!$move_nav && !empty($nav_position)) {
            $property_content_class[] = 'owl-nav-' . $nav_position;
        } elseif ($move_nav) {
            $property_content_class[] = 'owl-nav-top-right';
            $wrapper_classes[] = 'owl-move-nav-par-with-heading';
        }
    }
    if ($columns_gap == 'col-gap-30') {
        $col_gap = 30;
    } elseif ($columns_gap == 'col-gap-20') {
        $col_gap = 20;
    } elseif ($columns_gap == 'col-gap-10') {
        $col_gap = 10;
    } else {
        $col_gap = 0;
    }

    $owl_responsive_attributes = array();
    // Mobile <= 480px
    $owl_responsive_attributes[] = '"0" : {"items" : ' . $items_mb . ', "margin": ' . (($items_mb > 1) ? $col_gap : '0') . '}';

    // Extra small devices ( < 768px)
    $owl_responsive_attributes[] = '"480" : {"items" : ' . $items_xs . ', "margin": ' . (($items_xs > 1) ? $col_gap : '0') . '}';

    // Small devices Tablets ( < 992px)
    $owl_responsive_attributes[] = '"768" : {"items" : ' . $items_sm . ', "margin": ' . (($items_sm > 1) ? $col_gap : '0') . '}';

    // Medium devices ( < 1199px)
    $owl_responsive_attributes[] = '"992" : {"items" : ' . $items_md . ', "margin": ' . (($items_md > 1) ? $col_gap : '0') . '}';

    // Medium devices ( > 1199px)
    $owl_responsive_attributes[] = '"1200" : {"items" : ' . (($columns >= 4) ? 4 : $columns) . ', "margin": ' . (($columns > 1) ? $col_gap : '0') . '}';

    $owl_responsive_attributes[] = '"1820" : {"items" : ' . $columns . ', "margin": ' . $col_gap . '}';

    $owl_attributes = array(
        '"dots": ' . ($dots ? 'true' : 'false'),
        '"nav": ' . ($nav ? 'true' : 'false'),
        '"autoplay": ' . ($autoplay ? 'true' : 'false'),
        '"autoplayTimeout": ' . ($autoplaytimeout ? $autoplaytimeout : 1000),
        '"responsive": {' . implode(', ', $owl_responsive_attributes) . '}'
    );
    $property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
} else {
    $wrapper_classes[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $property_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $property_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $property_item_class[] = 'mg-bottom-10';
    }

    if ($layout_style == 'property-grid') {
        $property_content_class[] = 'columns-' . $columns . ' columns-md-' . $items_md . ' columns-sm-' . $items_sm . ' columns-xs-' . $items_xs . ' columns-mb-' . $items_mb;
    }
    if ($layout_style == 'property-list') {
        //$image_size = '330x180';
        $property_item_class[] = 'mg-bottom-30';
    }
    if ($layout_style == 'property-zigzac') {
        //$image_size = '290x270';
        $property_content_class[] = 'columns-2 columns-md-2 columns-sm-1';
    }
}

if (!empty($view_all_link)) {
    $wrapper_attributes[] = "data-view-all-link='" . $view_all_link . "'";
}

$args = array(
    'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
    'post_type' => 'property',
    'paged' => $paged,
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
$args['meta_query'] = array();
if (!empty($author_id) && !empty($agent_id)) {
    $args['meta_query'] = array(
        'relation' => 'OR',
        array(
            'key' => ERE_METABOX_PREFIX . 'property_agent',
            'value' => explode(',', $agent_id),
            'compare' => 'IN'
        ),
        array(
            'key' => ERE_METABOX_PREFIX . 'property_author',
            'value' => explode(',', $author_id),
            'compare' => 'IN'
        )
    );
} else {
    if (!empty($author_id)) {
        $args['author'] = $author_id;
    } else if (!empty($agent_id)) {
        $args['meta_query'] = array(
            array(
                'key' => ERE_METABOX_PREFIX . 'property_agent',
                'value' => explode(',', $agent_id),
                'compare' => 'IN'
            )
        );
    }
}

if ($property_featured == 'true') {
    $args['meta_query'][] = array(
        'key' => ERE_METABOX_PREFIX . 'property_featured',
        'value' => true,
        'compare' => '=',
    );
}
$args = apply_filters('ere_shortcodes_property_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-property-wrap">
    <div class="<?php echo join(' ', $wrapper_classes) ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
        <?php if ($include_heading) :
            $heading_class='';
            ?>
        <div class="container">
            <div class="ere-heading ere-item-wrap <?php echo esc_attr($heading_class); ?>">
                <?php if (!empty($heading_title)): ?>
                    <h2><?php echo esc_html($heading_title); ?></h2>
                <?php endif; ?>
                <?php if (!empty($heading_sub_title)): ?>
                    <p><?php echo esc_html($heading_sub_title); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($layout_style == 'property-carousel'): ?>
        <div class="<?php echo join(' ', $property_content_class) ?>" data-section-id="<?php echo uniqid(); ?>"
             data-callback="owl_callback" <?php echo implode(' ', $property_content_attributes); ?>>
            <?php else: ?>
            <div class="<?php echo join(' ', $property_content_class) ?>">
                <?php endif; ?>
                <?php if ($data->have_posts()) :
                    $index = 0;
                    while ($data->have_posts()): $data->the_post();
                        $property_avatar_class = array();
                        $property_item_content_class = array();
                        if ($layout_style == 'property-zigzac') {
                            if (($index + 1) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-6 col-sm-push-6';
                                $property_item_content_class[] = 'col-md-pull-6 col-sm-pull-6';
                            }
                            if (($index + 2) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-6';
                                $property_item_content_class[] = 'col-md-pull-6';
                            }
                            if (($index + 3) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-0 col-sm-push-6';
                                $property_item_content_class[] = 'col-md-pull-0 col-sm-pull-6';
                            }
                        }
	                    ere_get_template('content-property.php', array(
		                    'custom_property_image_size' => $image_size,
		                    'property_item_class' => $property_item_class,
		                    'property_image_class' => $property_avatar_class,
		                    'property_item_content_class' => $property_item_content_class
	                    ));
	                    $index++;
                        ?>
                    <?php endwhile;
                else: if (empty($agent_id) && empty($author_id)): ?>
                    <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($layout_style == 'property-carousel'): ?>
            </div>
            <?php else: ?>
        </div>
        <div class="clearfix"></div>
    <?php endif; ?>
        <?php if (!empty($view_all_link)): ?>
            <div class="view-all-link">
                <a href="<?php echo esc_url($view_all_link) ?>"
                   class="btn btn-xs btn-dark btn-classic"><?php esc_html_e('View All', 'essential-real-estate') ?></a>
            </div>
        <?php endif; ?>
        <?php
        if ($show_paging == 'true') { ?>
            <div class="property-paging-wrap"
                 data-admin-url="<?php echo ERE_AJAX_URL; ?>"
                 data-layout="<?php echo esc_attr($layout_style); ?>"
                 data-items-amount="<?php echo esc_attr($item_amount); ?>"
                 data-columns="<?php echo esc_attr($columns); ?>"
                 data-image-size="<?php echo esc_attr($image_size); ?>"
                 data-columns-gap="<?php echo esc_attr($columns_gap); ?>"
                 data-view-all-link="<?php echo esc_attr($view_all_link); ?>"
                 data-property-type="<?php echo esc_attr($property_type); ?>"
                 data-property-status="<?php echo esc_attr($property_status); ?>"
                 data-property-feature="<?php echo esc_attr($property_feature); ?>"
                 data-property-city="<?php echo esc_attr($property_city); ?>"
                 data-property-state="<?php echo esc_attr($property_state); ?>"
                 data-property-neighborhood="<?php echo esc_attr($property_neighborhood); ?>"
                 data-property-label="<?php echo esc_attr($property_label); ?>"
                 data-property-featured="<?php echo esc_attr($property_featured); ?>"
                 data-author-id="<?php echo esc_attr($author_id); ?>"
                 data-agent-id="<?php echo esc_attr($agent_id); ?>">
                <?php $max_num_pages = $data->max_num_pages;
                set_query_var('paged', $paged);
                ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                ?>
            </div>
        <?php }
        wp_reset_postdata(); ?>
    </div>
</div>


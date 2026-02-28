<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header('ere');
/**
 * ere_before_main_content hook.
 *
 * @hooked ere_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action( 'ere_before_main_content' );
?>
<?php
global $post, $taxonomy_title, $taxonomy_name;

$custom_property_layout_style = ere_get_option('archive_property_layout_style', 'property-grid');

$custom_property_items_amount = ere_get_option('archive_property_items_amount', '6');
$custom_property_image_size = ere_get_option( 'archive_property_image_size', '330x180' );
$custom_property_columns = ere_get_option('archive_property_columns', '3');
$custom_property_columns_gap = ere_get_option('archive_property_columns_gap', 'col-gap-30');
$custom_property_items_md = ere_get_option('archive_property_items_md', '3');
$custom_property_items_sm = ere_get_option('archive_property_items_sm', '2');
$custom_property_items_xs = ere_get_option('archive_property_items_xs', '1');
$custom_property_items_mb = ere_get_option('archive_property_items_mb', '1');

$property_item_class = array();
ERE_Compare::open_session();
if (isset($_SESSION["property_view_as"]) && !empty($_SESSION["property_view_as"]) && in_array($_SESSION["property_view_as"], array('property-list', 'property-grid'))) {
    $custom_property_layout_style = $_SESSION["property_view_as"];
}

$wrapper_classes = array(
    'ere-property clearfix',
    $custom_property_layout_style,
    $custom_property_columns_gap
);

if ($custom_property_layout_style == 'property-list') {
    $wrapper_classes[] = 'list-1-column';
}

if ($custom_property_columns_gap == 'col-gap-30') {
    $property_item_class[] = 'mg-bottom-30';
} elseif ($custom_property_columns_gap == 'col-gap-20') {
    $property_item_class[] = 'mg-bottom-20';
} elseif ($custom_property_columns_gap == 'col-gap-10') {
    $property_item_class[] = 'mg-bottom-10';
}

$wrapper_classes[] = 'columns-' . $custom_property_columns;
$wrapper_classes[] = 'columns-md-' . $custom_property_items_md;
$wrapper_classes[] = 'columns-sm-' . $custom_property_items_sm;
$wrapper_classes[] = 'columns-xs-' . $custom_property_items_xs;
$wrapper_classes[] = 'columns-mb-' . $custom_property_items_mb;
$property_item_class[] = 'ere-item-wrap';
$args = array(
    'posts_per_page' => $custom_property_items_amount,
    'post_type' => 'property',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
    'offset' => (max(1, get_query_var('paged')) - 1) * $custom_property_items_amount,
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish'
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
	    $args['ere_orderby_featured'] = true;
    }
}
$property_status=ere_get_property_status_search();
$property_status_arr = array();
if ($property_status) {
    foreach ($property_status as $property_stt) {
        $property_status_arr[] = $property_stt->slug;
    }
}
$tax_query = array();
if (isset($_GET['status']) && in_array($_GET['status'], $property_status_arr) && $taxonomy_name != 'property-status') {
    $tax_query[] = array(
        'taxonomy' => 'property-status',
        'field' => 'slug',
        'terms' => explode(',', ere_clean(wp_unslash($_GET['status']))),
        'operator' => 'IN'
    );
}
if (is_tax()) {
    $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $taxonomy_title = $current_term->name;
    $taxonomy_name = get_query_var('taxonomy');
    if (!empty($taxonomy_name)) {
        $tax_query[] = array(
            'taxonomy' => $taxonomy_name,
            'field' => 'slug',
            'terms' => $current_term->slug
        );
    }
}

$tax_count = count($tax_query);
if ($tax_count > 0) {
    $args['tax_query'] = array(
        'relation' => 'AND',
        $tax_query
    );
}
$author_id = $agent_id = '';
if (isset($_GET['user_id']) ||isset($_GET['agent_id']) ) {
    if (isset($_GET['user_id'])) {
        $author_id = ere_clean(wp_unslash($_GET['user_id']));
        $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $author_id);
    }
    if (isset($_GET['agent_id'])) {
        $agent_id = ere_clean(wp_unslash($_GET['agent_id']));
        $author_id = get_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', true);
    }
    if (!empty($author_id) && $author_id > 0 && !empty($agent_id) && $agent_id > 0) {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => ERE_METABOX_PREFIX . 'property_agent',
                'value' => $agent_id,
                'compare' => '='
            ),
            array(
                'key' => ERE_METABOX_PREFIX . 'property_author',
                'value' => $author_id,
                'compare' => '='
            )
        );
    } else {
        if (!empty($author_id) && $author_id > 0) {
            $args['author'] = $author_id;
        } else if (!empty($agent_id) && $agent_id > 0) {
            $args['meta_query'] = array(
                array(
                    'key' => ERE_METABOX_PREFIX . 'property_agent',
                    'value' => $agent_id,
                    'compare' => '='
                )
            );
        }
    }
}
$args = apply_filters('ere_property_archive_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'property');
wp_print_styles( ERE_PLUGIN_PREFIX . 'archive-property');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'archive-property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-archive-property' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
    <div class="ere-archive-property-wrap ere-property-wrap">
        <?php do_action('ere_archive_property_before_main_content'); ?>
        <div class="ere-archive-property archive-property">
            <div class="above-archive-property">
                <?php do_action('ere_archive_property_heading', $total_post, $taxonomy_title, $agent_id, $author_id); ?>
                <?php do_action('ere_archive_property_action', $taxonomy_name); ?>
            </div>
            <div class="<?php echo join(' ', $wrapper_classes) ?>">
                <?php if ($data->have_posts()) :
                    while ($data->have_posts()): $data->the_post(); ?>
                        <?php ere_get_template('content-property.php', array(
                            'property_item_class' => $property_item_class,
                            'custom_property_image_size' => $custom_property_image_size
                        )); ?>


                    <?php endwhile;
                else: ?>
                    <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                <?php endif; ?>
                <div class="clearfix"></div>
                <?php
                $max_num_pages = $data->max_num_pages;
                ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                wp_reset_postdata(); ?>
            </div>
        </div>
        <?php do_action('ere_archive_property_after_main_content'); ?>
    </div>
<?php
/**
 * ere_after_main_content hook.
 *
 * @hooked ere_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'ere_after_main_content' );
/**
 * ere_sidebar_property hook.
 *
 * @hooked ere_sidebar_property - 10
 */
do_action('ere_sidebar_property');
get_footer('ere');
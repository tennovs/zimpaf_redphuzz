<?php
/**
 * @var $favorites
 * @var $max_num_pages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_login'));
    return;
}
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style('dashicons');
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'archive-property');

$wrapper_classes = array(
    'ere-property clearfix',
    'property-grid',
    'col-gap-10',
    'columns-3',
    'columns-md-2',
    'columns-sm-2',
    'columns-xs-1'
);
$property_item_class = array(
    'ere-item-wrap',
    'mg-bottom-10'
);
$custom_property_image_size = ere_get_option('archive_property_image_size', '330x180');
?>
<div class="row ere-user-dashboard">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ere-dashboard-sidebar">
        <?php ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_favorites')); ?>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ere-dashboard-content">
        <div class="panel panel-default ere-my-favorites">
            <div class="panel-heading"><?php esc_html_e('My Favorites ', 'essential-real-estate'); ?></div>
            <div class="panel-body">
                <div class="<?php echo join(' ', $wrapper_classes) ?>">
                    <?php if ($favorites->have_posts()) :
                        while ($favorites->have_posts()): $favorites->the_post(); ?>
                            <?php ere_get_template('content-property.php', array(
                                'property_item_class' => $property_item_class,
                                'custom_property_image_size' => $custom_property_image_size
                            )); ?>


                        <?php endwhile;
                    else: ?>
                        <div
                            class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <?php
                    $max_num_pages = $favorites->max_num_pages;
                    ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
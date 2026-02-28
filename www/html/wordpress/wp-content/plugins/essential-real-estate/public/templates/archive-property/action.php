<?php
/**
 * @var $taxonomy_name
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$current_url = $_SERVER['REQUEST_URI'];
?>
<div class="archive-property-action property-status-filter">
    <?php if ($taxonomy_name != 'property-status'): ?>
        <div class="archive-property-action-item">
            <div class="property-status property-filter">
                <ul>
                    <li class="active"><a data-status="all" href="<?php
                        $pot_link_status = add_query_arg('status', 'all', $current_url);
                        echo esc_url($pot_link_status) ?>"
                                          title="<?php esc_attr_e('All', 'essential-real-estate'); ?>"><?php esc_html_e('All', 'essential-real-estate'); ?></a>
                    </li>
                    <?php
                    $property_status = ere_get_property_status_search();
                    if ($property_status) :
                        foreach ($property_status as $status):?>
                            <li><a data-status="<?php echo esc_attr($status->slug) ?>" href="<?php
                                $pot_link_status = add_query_arg('status', $status->slug, $current_url);
                                echo esc_url($pot_link_status) ?>"
                                   title="<?php echo esc_attr($status->name) ?>"><?php echo esc_html($status->name) ?></a>
                            </li>
                        <?php endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <div class="archive-property-action-item sort-view-property">
        <div class="sort-property property-filter">
                        <span
                            class="property-filter-placeholder"><?php esc_html_e('Sort By', 'essential-real-estate'); ?></span>
            <ul>
                <li><a data-sortby="default" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'default'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Default Order', 'essential-real-estate'); ?>"><?php esc_html_e('Default Order', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="featured" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'featured'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Featured', 'essential-real-estate'); ?>"><?php esc_html_e('Featured', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="most_viewed" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'most_viewed'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Most Viewed', 'essential-real-estate'); ?>"><?php esc_html_e('Most Viewed', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="a_price" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'a_price'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Price (Low to High)', 'essential-real-estate'); ?>"><?php esc_html_e('Price (Low to High)', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="d_price" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'd_price'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Price (High to Low)', 'essential-real-estate'); ?>"><?php esc_html_e('Price (High to Low)', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="a_date" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'a_date'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Date (Old to New)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="d_date" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'd_date'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Date (New to Old)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?></a>
                </li>
            </ul>
        </div>
        <div class="view-as" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
						<span data-view-as="property-list" class="view-as-list"
                              title="<?php esc_attr_e('View as List', 'essential-real-estate') ?>">
							<i class="fa fa-list-ul"></i>
						</span>
						<span data-view-as="property-grid" class="view-as-grid"
                              title="<?php esc_attr_e('View as Grid', 'essential-real-estate') ?>">
							<i class="fa fa-th-large"></i>
						</span>
        </div>
    </div>
</div>

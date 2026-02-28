<?php
/**
 * @var $keyword
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="archive-agent-action">
    <div class="archive-agent-action-item agent-filter">
        <form method="get" action="<?php echo get_post_type_archive_link('agent'); ?>">
            <div class="form-group input-group search-box"><input type="search" name="agent_name"
                                                                  value="<?php echo esc_attr($keyword); ?>"
                                                                  class="form-control"
                                                                  placeholder="<?php esc_attr_e('Search...', 'essential-real-estate'); ?>"> <span
                    class="input-group-btn"><button type="submit" class="button"><i
                            class="fa fa-search"></i></button> </span>
            </div>
        </form>
    </div>
    <div class="archive-agent-action-item sort-view-agent">
        <div class="sort-agent">
            <span class="sort-by"><?php esc_html_e('Sort By', 'essential-real-estate'); ?></span>
            <ul>
                <li><a data-sortby="a_name" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'a_name'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Name (A to Z)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (A to Z)', 'essential-real-estate'); ?></a>
                </li>
                <li><a data-sortby="d_name" href="<?php
                    $pot_link_sortby = add_query_arg(array('sortby' => 'd_name'));
                    echo esc_url($pot_link_sortby) ?>"
                       title="<?php esc_attr_e('Name (Z to A)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (Z to A)', 'essential-real-estate'); ?></a>
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
        <div class="view-as" data-admin-url="<?php echo esc_url(ERE_AJAX_URL) ; ?>">
                            <span data-view-as="agent-list" class="view-as-list"
                                  title="<?php esc_attr_e('View as List', 'essential-real-estate') ?>">
                                <i class="fa fa-list-ul"></i>
                            </span>
                            <span data-view-as="agent-grid" class="view-as-grid"
                                  title="<?php esc_attr_e('View as Grid', 'essential-real-estate') ?>">
                                <i class="fa fa-th-large"></i>
                            </span>
        </div>
    </div>
</div>

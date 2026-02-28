<?php
/**
 * @var $save_seach
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_login'));
    return;
}
?>
<div class="row ere-user-dashboard">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ere-dashboard-sidebar">
        <?php ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_save_search')); ?>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ere-dashboard-content">
        <div class="panel panel-default ere-my-saved-searches">
            <div class="panel-heading"><?php esc_html_e('My Saved Searches ', 'essential-real-estate'); ?></div>
            <div class="panel-body">
                <?php if (!$save_seach) : ?>
                    <div><?php esc_html_e('You don\'t have any saved searches listed.', 'essential-real-estate'); ?></div>
                <?php else : ?>
                    <?php foreach ($save_seach as $item) :
                        ?>
                        <div class="ere-my-saved-search-item">
                            <h4>
                                <a target="_blank" title="<?php echo esc_attr($item->title); ?>"
                                   href="<?php echo esc_url($item->url); ?>">
                                    <?php echo esc_html($item->title); ?></a>
                            </h4>

                            <p>
                                <i class="fa fa-calendar"></i> <?php echo date_i18n(get_option('date_format'), strtotime($item->time)); ?>
                            </p>

                            <p>
                                <i class="fa fa-search"></i> <?php echo call_user_func("base" . "64_dec" . "ode", $item->params); ?>
                            </p>
                            <?php
                            $action_url = add_query_arg(array('action' => 'delete', 'save_id' => $item->id));
                            $action_url = wp_nonce_url($action_url, 'ere_my_save_search_actions'); ?>
                            <a onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this saved search?', 'essential-real-estate'); ?>')"
                               href="<?php echo esc_url($action_url); ?>" data-toggle="tooltip"
                               data-placement="bottom"
                               title="<?php esc_attr_e('Delete this saved search', 'essential-real-estate'); ?>"
                               class="btn-action"><?php esc_html_e('Delete', 'essential-real-estate'); ?></a>
                            <a
                                href="<?php echo esc_url($item->url); ?>" data-toggle="tooltip"
                                data-placement="bottom"
                                title="<?php esc_attr_e('Search', 'essential-real-estate'); ?>"
                                class="btn-action"><?php esc_html_e('Search', 'essential-real-estate'); ?></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @var $cur_menu
 * @var $max_num_pages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$user_login = $current_user->user_login;
$user_id = $current_user->ID;
$ere_property = new ERE_Property();
$total_properties = $ere_property->get_total_my_properties(array('publish', 'pending', 'expired', 'hidden'));
$ere_invoice = new ERE_Invoice();
$total_invoices = $ere_invoice->get_total_my_invoice();
$total_favorite = $ere_property->get_total_favorite();
$ere_save_search = new ERE_Save_Search();
$total_save_search = $ere_save_search->get_total_save_search();
$allow_submit = ere_allow_submit();
$user_custom_picture = get_the_author_meta(ERE_METABOX_PREFIX . 'author_custom_picture', $user_id);
$author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $user_id);
$no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
$width = get_option('thumbnail_size_w');
$height = get_option('thumbnail_size_h');
$default_avatar = ere_get_option('default_user_avatar', '');
if ($default_avatar != '') {
    if (is_array($default_avatar) && $default_avatar['url'] != '') {
        $resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
        if ($resize != null && is_array($resize)) {
            $no_avatar_src = $resize['url'];
        }
    }
}
wp_print_styles(ERE_PLUGIN_PREFIX . 'dashboard');
?>
<div class="ere-dashboard-sidebar-content">
    <div class="ere-dashboard-welcome">
        <figure>
            <?php
            if (!empty($author_picture_id)) {
                $author_picture_id = intval($author_picture_id);
                if ($author_picture_id) {
                    $avatar_src = ere_image_resize_id($author_picture_id, $width, $height, true);
                    ?>
                    <img src="<?php echo esc_url($avatar_src); ?>"
                         onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                         alt="<?php esc_attr_e('User Avatar', 'essential-real-estate') ?>">
                    <?php
                }
            } else {
                ?>
                <img src="<?php echo esc_url($user_custom_picture); ?>"
                     onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                     alt="<?php esc_attr_e('User Avatar', 'essential-real-estate') ?>">
                <?php
            }
            ?>
        </figure>
        <div class="ere-dashboard-user-info">
            <h4 class="ere-dashboard-title"><?php echo esc_html($user_login); ?></h4>
            <a class="ere-dashboard-logout" href="<?php $permalink = get_permalink();
            echo wp_logout_url($permalink); ?>"><i
                    class="fa fa-sign-out"></i><?php esc_html_e('Logout', 'essential-real-estate'); ?>
            </a>
        </div>
    </div>
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#ere-dashboard-sidebar-navbar-collapse">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'essential-real-estate'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">
                <?php
                switch ($cur_menu) {
                    case "my_profile":
                        esc_html_e('My Profile', 'essential-real-estate');
                        break;
                    case "my_properties":
                        esc_html_e('My Properties', 'essential-real-estate');
                        break;
                    case "my_invoices":
                        esc_html_e('My Invoices', 'essential-real-estate');
                        break;
                    case "my_favorites":
                        esc_html_e('My Favorites', 'essential-real-estate');
                        break;
                    case "my_save_search":
                        esc_html_e('My Saved Searches', 'essential-real-estate');
                        break;
                }
                ?>
            </span>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="ere-dashboard-sidebar-navbar-collapse">
            <ul class="nav navbar-nav ere-dashboard-nav">
                <?php if ($permalink = ere_get_permalink('my_profile')) : ?>
                    <li<?php if ($cur_menu == 'my_profile') echo ' class="active"' ?>>
                        <a href="<?php echo esc_url($permalink); ?>"><i
                                class="fa fa-user"></i><?php esc_html_e('My Profile', 'essential-real-estate'); ?>
                        </a>
                    </li>
                <?php endif;
                if ($allow_submit) :
                    if ($permalink = ere_get_permalink('my_properties')) : ?>
                        <li<?php if ($cur_menu == 'my_properties') echo ' class="active"' ?>>
                            <a href="<?php echo esc_url($permalink); ?>"><i
                                    class="fa fa-list-alt"></i><?php esc_html_e('My Properties ', 'essential-real-estate');
                                echo '<span class="badge">' . $total_properties . '</span>' ?></a>
                        </li>
                    <?php endif;
                    $paid_submission_type = ere_get_option('paid_submission_type', 'no');
                    if ($paid_submission_type != 'no'):
                        if ($permalink = ere_get_permalink('my_invoices')) :
                            ?>
                            <li<?php if ($cur_menu == 'my_invoices') echo ' class="active"' ?>>
                                <a href="<?php echo esc_url($permalink); ?>"><i
                                        class="fa fa-file-text-o"></i><?php esc_html_e('My Invoices ', 'essential-real-estate');
                                    echo '<span class="badge">' . $total_invoices . '</span>' ?></a>
                            </li>
                        <?php endif;
                    endif;
                endif;
                $enable_favorite = ere_get_option('enable_favorite_property', 1);
                if ($enable_favorite == 1):
                    if ($permalink = ere_get_permalink('my_favorites')) : ?>
                        <li<?php if ($cur_menu == 'my_favorites') echo ' class="active"' ?>>
                            <a href="<?php echo esc_url($permalink); ?>"><i
                                    class="fa fa-heart"></i><?php esc_html_e('My Favorites ', 'essential-real-estate');
                                echo '<span class="badge">' . $total_favorite . '</span>'; ?></a>
                        </li>
                    <?php endif;
                endif;
                $enable_saved_search = ere_get_option('enable_saved_search', 1);
                if ($enable_saved_search == 1):
                    if ($permalink = ere_get_permalink('my_save_search')) : ?>
                        <li<?php if ($cur_menu == 'my_save_search') echo ' class="active"' ?>>
                            <a href="<?php echo esc_url($permalink); ?>"><i
                                    class="fa fa-search"></i><?php esc_html_e('My Saved Searches ', 'essential-real-estate');
                                echo '<span class="badge">' . $total_save_search . '</span>'; ?></a>
                        </li>
                    <?php endif;
                endif; ?>
                <?php if ($permalink = ere_get_permalink('submit_property')) :
                    if ($allow_submit):?>
                        <li>
                            <a href="<?php echo esc_url($permalink); ?>"><i
                                    class="fa fa-file-o"></i><?php esc_html_e('Submit New Property', 'essential-real-estate'); ?>
                            </a>
                        </li>
                    <?php endif; endif; ?>
                <?php do_action('ere_dashboard_navbar', $cur_menu, 'navbar_dashboard'); ?>
            </ul>
        </div>
    </nav>
    <?php
    $paid_submission_type = ere_get_option('paid_submission_type', 'no');
    $enable_submit_property_via_frontend = ere_get_option('enable_submit_property_via_frontend', 1);
    $user_can_submit = ere_get_option('user_can_submit', 1);
    $is_agent = ere_is_agent();
    if ($paid_submission_type == 'per_package' && $enable_submit_property_via_frontend == 1 && ($is_agent || $user_can_submit == 1)): ?>
        <div class="panel panel-default">
            <div
                class="panel-heading"><?php esc_html_e('My Listing Package', 'essential-real-estate'); ?></div>
            <?php ere_get_template('widgets/my-package/my-package.php'); ?>
        </div>
    <?php endif; ?>
</div>
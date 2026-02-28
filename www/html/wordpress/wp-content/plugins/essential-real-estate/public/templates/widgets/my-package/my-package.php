<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 15/12/2016
 * Time: 10:59 SA
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$user_id = $current_user->ID;
$package_remaining_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_listings', $user_id);
$package_featured_remaining_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_featured', $user_id);
$package_id = get_the_author_meta(ERE_METABOX_PREFIX . 'package_id', $user_id);
$packages_link = ere_get_permalink('packages');
if ($package_remaining_listings == -1) {
    $package_remaining_listings = esc_html__('Unlimited', 'essential-real-estate');
}
if (!empty($package_id)) :
    $package_title = get_the_title($package_id);
    $package_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
    $package_unlimited_listing = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_listing', true);
    $package_featured_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
    $ere_package = new ERE_Package();
    $expired_date = $ere_package->get_expired_date($package_id, $user_id);
    ?>
    <ul class="list-group ere-my-package">
        <li class="list-group-item"><span
                class="badge"><?php echo esc_html($package_title) ?></span><?php esc_html_e('Package Name ', 'essential-real-estate') ?>
        </li>
        <li class="list-group-item"><span class="badge"><?php if ($package_unlimited_listing == 1) {
                    echo wp_kses_post($package_remaining_listings);
                } else {
                    echo esc_html($package_listings);
                }
                ?>
            </span><?php esc_html_e('Listings Included ', 'essential-real-estate') ?></li>
        <li class="list-group-item"><span
                class="badge"><?php echo wp_kses_post($package_remaining_listings); ?></span><?php esc_html_e('Listings Remaining ', 'essential-real-estate') ?>
        </li>

        <li class="list-group-item"><span
                class="badge"><?php echo esc_html($package_featured_listings) ?></span><?php esc_html_e('Featured Included ', 'essential-real-estate') ?>
        </li>

        <li class="list-group-item"><span
                class="badge"><?php echo esc_html($package_featured_remaining_listings) ?></span><?php esc_html_e('Featured Remaining ', 'essential-real-estate') ?>
        </li>

        <li class="list-group-item"><span
                class="badge"><?php echo esc_html($expired_date) ?></span><?php esc_html_e('End Date ', 'essential-real-estate') ?>
        </li>
        <li class="list-group-item">
            <a href="<?php echo esc_url($packages_link); ?>"
               class="btn btn-primary btn-block"><?php esc_html_e('Change new package', 'essential-real-estate'); ?></a>
        </li>
    </ul>
<?php else: ?>
    <div class="panel-body">
    <p class="ere-message alert alert-success"
       role="alert"><?php esc_html_e('Before you can list properties on our site, you must subscribe to a package. Currently, you don\'t have a package. So, to select a new package, please click the button below', 'essential-real-estate'); ?></p>
    <a href="<?php echo esc_url($packages_link); ?>"
       class="btn btn-primary btn-block"><?php esc_html_e('Subscribe to a package', 'essential-real-estate'); ?></a>
    </div>
<?php endif; ?>
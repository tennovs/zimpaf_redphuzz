<?php
/**
 * @var $properties
 * @var $max_num_pages
 * @var $post_status
 * @var $title
 * @var $property_identity
 * @var $property_status
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_login'));
    return;
}
$my_properties_columns = apply_filters('ere_my_properties_columns', array(
    'detail' => esc_html__('Detail', 'essential-real-estate'),
    'date' => esc_html__('Date Posted', 'essential-real-estate'),
    'featured' => esc_html__('Featured', 'essential-real-estate'),
    'status' => esc_html__('Post Status', 'essential-real-estate'),
));
$allow_submit = ere_allow_submit();
if (!$allow_submit) {
    echo ere_get_template_html('global/access-denied.php', array('type' => 'not_permission'));
    return;
}
$request_new_id = isset($_GET['new_id']) ? ere_clean(wp_unslash($_GET['new_id'])) : '';
if (!empty($request_new_id)) {
    ere_get_template('property/property-submitted.php', array('property' => get_post($request_new_id), 'action' => 'new'));
}
$request_edit_id = isset($_GET['edit_id']) ? ere_clean(wp_unslash($_GET['edit_id']))  : '';
if (!empty($request_edit_id)) {
    ere_get_template('property/property-submitted.php', array('property' => get_post($request_edit_id), 'action' => 'edit'));
}
$my_properties_page_link = ere_get_permalink('my_properties');
$ere_property = new ERE_Property();
$total_properties = $ere_property->get_total_my_properties(array('publish', 'pending', 'expired', 'hidden'));
$post_status_approved = remove_query_arg(array('new_id', 'edit_id'), add_query_arg(array('post_status' => 'publish'), $my_properties_page_link));
$total_approved = $ere_property->get_total_my_properties('publish');
$post_status_pending = remove_query_arg(array('new_id', 'edit_id'), add_query_arg(array('post_status' => 'pending'), $my_properties_page_link));
$total_pending = $ere_property->get_total_my_properties('pending');
$post_status_expired = remove_query_arg(array('new_id', 'edit_id'), add_query_arg(array('post_status' => 'expired'), $my_properties_page_link));
$total_expired = $ere_property->get_total_my_properties('expired');

$post_status_hidden = remove_query_arg(array('new_id', 'edit_id'), add_query_arg(array('post_status' => 'hidden'), $my_properties_page_link));
$total_hidden = $ere_property->get_total_my_properties('hidden');
$width = get_option('thumbnail_size_w');
$height = get_option('thumbnail_size_h');
$no_image_src = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
$default_image = ere_get_option('default_property_image', '');
if ($default_image != '') {
    if (is_array($default_image) && $default_image['url'] != '') {
        $resize = ere_image_resize_url($default_image['url'], $width, $height, true);
        if ($resize != null && is_array($resize)) {
            $no_image_src = $resize['url'];
        }
    }
}
$paid_submission_type = ere_get_option('paid_submission_type', 'no');
$ere_profile = new ERE_Profile();
global $current_user;
wp_get_current_user();
$user_id = $current_user->ID;
?>
<div class="row ere-user-dashboard">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ere-dashboard-sidebar">
        <?php ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_properties')); ?>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ere-dashboard-content">
        <div class="panel panel-default ere-my-properties">
            <div class="panel-heading"><?php esc_html_e('My Properties', 'essential-real-estate'); ?></div>
            <div class="panel-body">
                <form method="get" action="<?php echo get_page_link(); ?>" class="ere-my-properties-search">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="sr-only"
                                       for="property_status"><?php esc_html_e('Property Status', 'essential-real-estate'); ?></label>
                                <select name="property_status" id="property_status" class="form-control"
                                        title="<?php esc_attr_e('Property Status', 'essential-real-estate') ?>">
                                    <?php ere_get_property_status_search_slug($property_status); ?>
                                    <option
                                        value="" <?php if (empty($property_status)) echo esc_attr('selected'); ?>>
                                        <?php esc_html_e('All Status', 'essential-real-estate') ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="sr-only"
                                       for="property_identity"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></label>
                                <input type="text" name="property_identity" id="property_identity"
                                       value="<?php echo esc_attr($property_identity); ?>"
                                       class="form-control"
                                       placeholder="<?php esc_attr_e('Property ID', 'essential-real-estate'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="sr-only"
                                       for="title"><?php esc_html_e('Title', 'essential-real-estate'); ?></label>
                                <input type="text" name="title" id="title"
                                       value="<?php echo esc_attr($title); ?>"
                                       class="form-control"
                                       placeholder="<?php esc_attr_e('Title', 'essential-real-estate'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <?php
                                if (!empty($_REQUEST['post_status'])):
                                    $post_status = sanitize_title(wp_unslash($_REQUEST['post_status'])); ?>
                                    <input type="hidden" name="post_status"
                                           value="<?php echo esc_attr($post_status); ?>"/>
                                <?php endif; ?>
                                <input type="submit" id="search_property" class="btn btn-default display-block"
                                       value="<?php esc_attr_e('Search', 'essential-real-estate'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="ere-my-properties-filter">
                    <li class="ere-status-all<?php if (is_array($post_status)) echo ' active' ?>"><a
                            href="<?php echo esc_url($my_properties_page_link); ?>"><?php printf(__('All (%s)', 'essential-real-estate'), $total_properties); ?></a>
                    </li>
                    <li class="ere-status-publish<?php if ($post_status == 'publish') echo ' active' ?>"><a
                            href="<?php echo esc_url($post_status_approved); ?>">
                            <?php printf(__('Approved (%s)', 'essential-real-estate'), $total_approved); ?></a>
                    </li>
                    <li class="ere-status-pending<?php if ($post_status == 'pending') echo ' active' ?>"><a
                            href="<?php echo esc_url($post_status_pending); ?>">
                            <?php printf(__('Pending (%s)', 'essential-real-estate'), $total_pending); ?></a>
                    </li>
                    <li class="ere-status-expired<?php if ($post_status == 'expired') echo ' active' ?>"><a
                            href="<?php echo esc_url($post_status_expired); ?>">
                            <?php printf(__('Expired (%s)', 'essential-real-estate'), $total_expired); ?></a>
                    </li>
                    <li class="ere-status-hidden<?php if ($post_status == 'hidden') echo ' active' ?>"><a
                            href="<?php echo esc_url($post_status_hidden); ?>">
                            <?php printf(__('Hidden (%s)', 'essential-real-estate'), $total_hidden); ?></a>
                    </li>
                </ul>
                <?php if (!$properties) : ?>
                    <div><?php esc_html_e('You don\'t have any properties listed.', 'essential-real-estate'); ?></div>
                <?php else : ?>
                    <?php foreach ($properties as $property) : ?>
                        <div class="ere-post-container">
                            <div class="ere-post-thumb">
                                <span class="ere-property-status ere-<?php echo esc_attr($property->post_status); ?>">
                                    <?php
                                    switch ($property->post_status) {
                                        case 'publish':
                                            esc_html_e('Published', 'essential-real-estate');
                                            break;
                                        case 'expired':
                                            esc_html_e('Expired', 'essential-real-estate');
                                            break;
                                        case 'pending':
                                            esc_html_e('Pending', 'essential-real-estate');
                                            break;
                                        case 'hidden':
                                            esc_html_e('Hidden', 'essential-real-estate');
                                            break;
                                        default:
                                            echo esc_html($property->post_status);
                                    }?>
                                </span>
                                <?php
                                $prop_featured = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_featured', true);
                                if ($prop_featured == 1):?>
                                    <span class="ere-property-featured"><?php esc_html_e('Featured', 'essential-real-estate') ?></span>
                                <?php endif;
                                $attach_id = get_post_thumbnail_id($property);
                                $image_src = ere_image_resize_id($attach_id, $width, $height, true);
                                if ($property->post_status == 'publish') : ?>
                                    <a target="_blank" title="<?php echo esc_attr($property->post_title); ?>"
                                       href="<?php echo get_permalink($property->ID); ?>">
                                        <img width="<?php echo esc_attr($width) ?>"
                                             height="<?php echo esc_attr($height) ?>"
                                             src="<?php echo esc_url($image_src) ?>"
                                             onerror="this.src = '<?php echo esc_url($no_image_src) ?>';"
                                             alt="<?php echo esc_attr($property->post_title); ?>"
                                             title="<?php echo esc_attr($property->post_title); ?>">
                                    </a>
                                <?php else : ?>
                                    <img width="<?php echo esc_attr($width) ?>"
                                         height="<?php echo esc_attr($height) ?>"
                                         src="<?php echo esc_url($image_src) ?>"
                                         onerror="this.src = '<?php echo esc_url($no_image_src) ?>';"
                                         alt="<?php echo esc_attr($property->post_title); ?>"
                                         title="<?php echo esc_attr($property->post_title); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="ere-post-content">
                                <?php if ($property->post_status == 'publish') : ?>
                                    <h4 class="ere-post-title">
                                        <a target="_blank" title="<?php echo esc_attr($property->post_title); ?>"
                                           href="<?php echo get_permalink($property->ID); ?>"><?php echo esc_html($property->post_title); ?></a>
                                    </h4>
                                <?php else : ?>
                                    <h4 class="ere-post-title"><?php echo esc_html($property->post_title); ?></h4>
                                <?php endif; ?>
                                <span class="ere-my-property-address"><i class="fa fa-map-marker"></i>
                                    <?php echo get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_address', true); ?>
                                    </span>
                                <span class="ere-my-property-total-views"><i class="fa fa-eye"></i>
                                    <?php
                                    $total_views = $ere_property->get_total_views($property->ID);
                                    printf(_n('%s view', '%s views', $total_views, 'essential-real-estate'), ere_get_format_number($total_views));
                                    ?>
                                </span>
                                <span class="ere-my-property-date"><i class="fa fa-calendar"></i>
                                <?php echo date_i18n(get_option('date_format'), strtotime($property->post_date)); ?>
                                </span>
                                <?php
                                $listing_expire = ere_get_option('per_listing_expire_days');
                                if ($paid_submission_type == 'per_listing' && $listing_expire == 1) :
                                    $number_expire_days = ere_get_option('number_expire_days');
                                    $property_date = $property->post_date;
                                    $timestamp = strtotime($property_date) + intval($number_expire_days) * 24 * 60 * 60;
                                    $expired_date = date('Y-m-d H:i:s', $timestamp);
                                    $expired_date = new DateTime($expired_date);

                                    $now = new DateTime();
                                    $interval = $now->diff($expired_date);
                                    $days = $interval->days;
                                    $hours = $interval->h;
                                    $invert = $interval->invert;

                                    if ($invert == 0) {
                                        if ($days > 0) {
                                            echo '<span class="ere-my-property-date-expire badge">' . sprintf(__('Expire: %s days %s hours', 'essential-real-estate'), $days, $hours) . '</span>';
                                        } else {
                                            echo '<span class="ere-my-property-date-expire badge">' . sprintf(__('Expire: %s hours', 'essential-real-estate'), $hours) . '</span>';
                                        }
                                    } else {
                                        $expired_date = date_i18n(get_option('date_format'), $timestamp);
                                        echo '<span class="ere-my-property-date-expire badge badge-expired">' . sprintf(__('Expired: %s', 'essential-real-estate'), $expired_date) . '</span>';
                                    }
                                endif;?>
	                            <?php do_action('ere_my_property_before_actions',$property->ID) ?>
                                <ul class="ere-dashboard-actions">
                                    <?php
                                    $actions = array();
                                    $payment_status = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'payment_status', true);
                                    switch ($property->post_status) {
                                        case 'publish' :
                                            $prop_featured = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_featured', true);
                                            if ($paid_submission_type == 'per_package') {
                                                $current_package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
                                                $property_package_key = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'package_key', true);

                                                $check_package = $ere_profile->user_package_available($user_id);
                                                if (!empty($property_package_key) && $current_package_key == $property_package_key) {
                                                    if ($check_package != -1 && $check_package != 0) {
                                                        $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'), 'tooltip' => __('Edit property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                                    }
                                                    $package_num_featured_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_featured', $user_id);
                                                    if ($package_num_featured_listings > 0 && ($prop_featured != 1) && ($check_package != -1) && ($check_package != 0)) {
                                                        $actions['mark_featured'] = array('label' => __('Mark featured', 'essential-real-estate'), 'tooltip' => __('Make this a Featured Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to mark this property as Featured?', 'essential-real-estate'));
                                                    }
                                                } elseif ($current_package_key != $property_package_key && $check_package == 1) {
                                                    $actions['allow_edit'] = array('label' => __('Allow Editing', 'essential-real-estate'), 'tooltip' => __('This property listing belongs to an expired Package therefore if you wish to edit it, it will be charged as a new listing from your current Package.', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to allow editing this property listing?', 'essential-real-estate'));
                                                }
                                            } else {
                                                if ($paid_submission_type != 'no' && $prop_featured != 1) {
                                                    $actions['mark_featured'] = array('label' => __('Mark featured', 'essential-real-estate'), 'tooltip' => __('Make this a Featured Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to mark this property as Featured?', 'essential-real-estate'));
                                                }
                                                $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'), 'tooltip' => __('Edit Property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                            }

                                            break;
                                        case 'expired' :
                                            if ($paid_submission_type == 'per_package') {
                                                $check_package = $ere_profile->user_package_available($user_id);
                                                if ($check_package == 1) {
                                                    $actions['relist_per_package'] = array('label' => __('Reactivate Listing', 'essential-real-estate'), 'tooltip' => __('Reactivate Listing', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to reactivate this property?', 'essential-real-estate'));
                                                }
                                            }
                                            if ($paid_submission_type == 'per_listing' && $payment_status == 'paid') {
                                                $price_per_listing = ere_get_option('price_per_listing', 0);
                                                if ($price_per_listing <= 0 || $payment_status == 'paid') {
                                                    $actions['relist_per_listing'] = array('label' => __('Resend this Listing for Approval', 'essential-real-estate'), 'tooltip' => __('Resend this Listing for Approval', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to resend this property for approval?', 'essential-real-estate'));
                                                }
                                            }
                                            break;
                                        case 'pending' :
                                            $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'), 'tooltip' => __('Edit Property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                            break;
                                        case 'hidden' :
                                            $actions['show'] = array('label' => __('Show', 'essential-real-estate'), 'tooltip' => __('Show Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to show this property?', 'essential-real-estate'));
                                            break;
                                    }
                                    $actions['delete'] = array('label' => __('Delete', 'essential-real-estate'), 'tooltip' => __('Delete Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to delete this property?', 'essential-real-estate'));
                                    if ($property->post_status == 'publish') {
                                        $actions['hidden'] = array('label' => __('Hide', 'essential-real-estate'), 'tooltip' => __('Hide Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to hide this property?', 'essential-real-estate'));
                                    }

                                    if ($paid_submission_type == 'per_listing' && $payment_status != 'paid' && $property->post_status != 'hidden') {
                                        $price_per_listing = ere_get_option('price_per_listing', 0);
                                        if ($price_per_listing > 0) {
                                            $actions['payment_listing'] = array('label' => __('Pay Now', 'essential-real-estate'), 'tooltip' => __('Pay for this property listing', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to pay for this listing?', 'essential-real-estate'));
                                        }
                                    }

                                    $actions = apply_filters('ere_my_properties_actions', $actions, $property);
                                    foreach ($actions as $action => $value) {
                                        $my_properties_page_link = ere_get_permalink('my_properties');
                                        $action_url = add_query_arg(array('action' => $action, 'property_id' => $property->ID), $my_properties_page_link);
                                        if ($value['nonce']) {
                                            $action_url = wp_nonce_url($action_url, 'ere_my_properties_actions');
                                        }
                                        ?>
                                        <li>
                                            <a <?php if (!empty($value['confirm'])): ?> onclick="return confirm('<?php echo esc_html($value['confirm']); ?>')" <?php endif; ?>
                                                href="<?php echo esc_url($action_url); ?>"
                                                data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="<?php echo esc_attr($value['tooltip']); ?>"
                                                class="btn-action ere-dashboard-action-<?php echo esc_attr($action); ?>"><?php echo esc_html($value['label']); ?></a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages)); ?>
            </div>
        </div>
    </div>
</div>

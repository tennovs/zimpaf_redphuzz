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
if(!is_user_logged_in()):?>
    <a href="javascript:void(0)" class="login-link topbar-link" data-toggle="modal" data-target="#ere_signin_modal"><i class="fa fa-user"></i><span class="hidden-xs"><?php esc_html_e('Login or Register','essential-real-estate') ?></span></a>
<?php else:
    global $current_user;
    wp_get_current_user();
    $user_login = $current_user->user_login;
    $user_id = $current_user->ID;
    $allow_submit=ere_allow_submit();
    $cur_menu='';
    $ere_property=new ERE_Property();
    $total_properties = $ere_property->get_total_my_properties(array('publish', 'pending', 'expired', 'hidden'));
    $ere_invoice=new ERE_Invoice();
    $total_invoices = $ere_invoice->get_total_my_invoice();
    $total_favorite=$ere_property->get_total_favorite();
    $ere_save_search= new ERE_Save_Search();
    $total_save_search=$ere_save_search->get_total_save_search();
    ?>
    <div class="user-dropdown">
        <span class="user-display-name"><i class="fa fa-user"></i><span class="hidden-xs"><?php echo esc_html($user_login); ?></span></span>
        <ul class="user-dropdown-menu list-group">
            <?php if ($permalink = ere_get_permalink('my_profile')) : ?>
                <li class="list-group-item<?php if ($cur_menu == 'my_profile') echo ' active' ?>">
                    <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-info-circle"></i><?php esc_html_e('My Profile', 'essential-real-estate'); ?></a>
                </li>
            <?php endif;
            if ($allow_submit) :
                if ($permalink = ere_get_permalink('my_properties')) : ?>
                    <li class="list-group-item<?php if ($cur_menu == 'my_properties') echo ' active' ?>">
                        <span class="badge"><?php echo esc_html($total_properties); ?></span>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-list-alt"></i><?php esc_html_e('My Properties ', 'essential-real-estate'); ?></a>
                    </li>
                <?php endif;
                $paid_submission_type = ere_get_option( 'paid_submission_type','no');
                if($paid_submission_type!='no'):
                    if ($permalink = ere_get_permalink('my_invoices')) : ?>
                        <li class="list-group-item<?php if ($cur_menu == 'my_invoices') echo ' active' ?>">
                            <span class="badge"><?php echo esc_html($total_invoices); ?></span>
                            <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-credit-card"></i><?php esc_html_e('My Invoices ', 'essential-real-estate'); ?></a>
                        </li>
                    <?php endif;
                endif;
                if ($permalink = ere_get_permalink('submit_property')) : ?>
                    <li class="list-group-item">
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-plus-circle"></i><?php esc_html_e('Submit New Property', 'essential-real-estate'); ?></a></li>
                <?php endif;
            endif;
            $enable_favorite = ere_get_option('enable_favorite_property', 1);
            if($enable_favorite==1):
                if ($permalink = ere_get_permalink('my_favorites')) : ?>
                    <li class="list-group-item<?php if ($cur_menu == 'my_favorites') echo ' active' ?>">
                        <span class="badge"><?php echo esc_html($total_favorite); ?></span>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-heart"></i><?php esc_html_e('My Favorites ', 'essential-real-estate'); ?></a>
                    </li>
                <?php endif;
            endif;
            $enable_saved_search = ere_get_option('enable_saved_search', 1);
            if($enable_saved_search==1):
                if ($permalink = ere_get_permalink('my_save_search')) : ?>
                    <li class="list-group-item<?php if ($cur_menu == 'my_save_search') echo ' active' ?>">
                        <span class="badge"><?php echo esc_html($total_save_search); ?></span>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-search"></i><?php esc_html_e('My Saved Searches', 'essential-real-estate'); ?></a>
                    </li>
            <?php endif;
            endif; ?>

            <?php do_action('ere_dashboard_navbar', $cur_menu, 'login_menu'); ?>

            <li class="list-group-item">
                <?php $permalink=get_permalink(); ?>
                <a href="<?php echo wp_logout_url( $permalink ); ?>"><i class="fa fa-sign-out"></i><?php esc_html_e('Logout', 'essential-real-estate');?></a>
            </li>
        </ul>
    </div>
<?php endif;?>
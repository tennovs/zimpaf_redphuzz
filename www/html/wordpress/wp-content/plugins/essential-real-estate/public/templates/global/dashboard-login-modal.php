<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="modal modal-login fade" id="ere_signin_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <?php
            $enable_register_tab = ere_get_option('enable_register_tab', 1);
            if($enable_register_tab==0):
                echo do_shortcode('[ere_login redirect="current_page"]');
            else:?>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a id="ere_login_modal_tab" href="#login" data-toggle="tab"><?php esc_html_e('Log in', 'essential-real-estate'); ?></a>
                </li>
                <li><a id="ere_register_modal_tab" href="#register" data-toggle="tab"><?php esc_html_e('Register', 'essential-real-estate'); ?></a>
                </li>
            </ul>
            <div class="tab-content ">
                <div class="tab-pane active" id="login">
                    <?php echo do_shortcode('[ere_login redirect="current_page"]'); ?>
                </div>
                <div class="tab-pane" id="register">
                    <?php echo do_shortcode('[ere_register redirect="login_tab"]'); ?>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
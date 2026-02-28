<?php
/**
 * @var $parameters
 * @var $search_query
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="modal fade ere_save_search_modal" id="ere_save_search_modal" tabindex="-1" role="dialog" aria-labelledby="SaveSearchModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="SaveSearchModalLabel"><?php esc_html_e('Saved Search', 'essential-real-estate'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle"></i>
                    <?php esc_html_e('With Saved Search, You will receive an email notification whenever someone posts a new property that matches your saved search criteria.', 'essential-real-estate'); ?>
                </div>
                <form id="ere_save_search_form" method="post">
                    <div class="form-group">
                        <label for="ere_title"><?php esc_html_e('Title', 'essential-real-estate'); ?></label>
                        <input type="text" name="ere_title" id="ere_title"
                               placeholder="<?php esc_attr_e('Input title', 'essential-real-estate'); ?>" class="form-control"
                               value="" aria-describedby="parameters">
                        <input type="hidden" name="ere_params"
                               value='<?php print base64_encode($parameters); ?>'>
                        <input type="hidden" name="ere_query"
                               value='<?php print base64_encode(serialize($search_query)); ?>'>
                        <input type="hidden" name="ere_url" value="<?php echo esc_attr($_SERVER['REQUEST_URI'])?>">
                        <input type="hidden" name="action" value='ere_save_search_ajax'>
                        <input type="hidden" name="ere_save_search_ajax"
                               value="<?php echo wp_create_nonce('ere_save_search_nonce_field') ?>">
                        <small id="parameters" class="form-text text-muted"><?php echo wp_kses_post(sprintf(esc_html__('Parameters: %s','essential-real-estate'),$parameters)) ; ?></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-default"
                        data-dismiss="modal"><?php esc_html_e('Close', 'essential-real-estate'); ?></button>
                <button data-ajax-url="<?php echo esc_url(ERE_AJAX_URL) ?>" id="ere_save_search" class="btn btn-primary"
                        type="button"><?php esc_html_e('Save', 'essential-real-estate'); ?></button>
            </div>
        </div>
    </div>
</div>
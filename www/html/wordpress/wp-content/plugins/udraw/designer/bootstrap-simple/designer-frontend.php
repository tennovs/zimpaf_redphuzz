<?php

include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();
$load_frontend_navigation = true;

$loggedInUser = '';
if (is_user_logged_in()) {
    $loggedInUser = wp_get_current_user()->user_login;
}

?>
<div id="designer-wrapper">
    <div id="udraw-bootstrap" data-udraw="uDrawBootstrap" style="display:none;">
        <?php
            //Apply extra action here
            do_action('udraw_frontend_extra_items', $post);
        ?>
        <?php include_once(UDRAW_PLUGIN_DIR . '/designer/bootstrap-simple/designer-template-wrapper.php'); ?>

        <div id="udraw-preview-ui" style="display:none; padding-left:30px;">
            <div class="row" style="padding-bottom:15px;">
                <a href="#" class="btn btn-danger" id="udraw-preview-back-to-design-btn"><strong>Back to Update Design</strong></a>
                <a href="#" class="btn btn-success" id="udraw-preview-add-to-cart-btn"><strong>Approve &amp; Add to Cart</strong></a>
            </div>
            <div class="row" id="udraw-preview-design-placeholer">
            </div>
        </div>
    </div>
</div>

<form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
    <input type="hidden" name="udraw_save_product_data" value="" />
    <input type="hidden" name="udraw_save_product_preview" value="" />
    <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
    <input type="hidden" name="udraw_save_access_key" value="<?php if (isset($_GET['udraw_access_key'])) { echo $_GET['udraw_access_key']; } ?>" />
    <input type="hidden" name="udraw_is_saving_for_later" value="1" />
    <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
    <input type="hidden" name="udraw_selected_variations" value="" />
    <?php wp_nonce_field('save_udraw_customer_design'); ?>
</form>
<?php include_once(UDRAW_PLUGIN_DIR . '/designer/multi-udraw-templates.php'); ?>

<style>
    .darkroom-container div ul li button {
        font-size: 1.7em;
    }
    
    #udraw-bootstrap btn {
        text-transform: none;
    }
</style>

<?php include_once(UDRAW_PLUGIN_DIR . '/designer/designer-template-script.php'); ?>

<style type="text/css">
    @media only screen and (max-width: 767px) {
        body div#wrapper {
            overflow: auto;
        }
    }
    <?php echo $_udraw_settings['udraw_designer_css_hook']; ?>
</style>

<script>
    jQuery(document).ready(function () {
        jQuery(document).on('udraw-loaded', function(){
            //In case loading a saved design.
            var productURL = window.location.href;
            var designFile;
            if(productURL.indexOf('udraw_access_key') !== -1) {
                var urlSplit = productURL.split('udraw_');
                accessKey = 'udraw_' + urlSplit[urlSplit.length - 1];
                var storage = '<?php echo UDRAW_STORAGE_URL ?>';
                var username = '<?php echo $loggedInUser ?>';
                if (username !== '') {
                    designFile = storage + username + '/output/' + accessKey + '_usdf.xml';
                } else {
                    designFile = storage + '_' + urlSplit[urlSplit.length - 1] + '_'  + '/output/' + accessKey + '_usdf.xml';
                }
                RacadDesigner.Legacy.loadCanvasDesign(designFile);
            }
        });

        <?php echo $_udraw_settings['udraw_designer_js_hook']; ?>
        jQuery('div.mobile_modal').modal({
            'backdrop': false,
            'keyboard': false,
            'show' : false
        });
    });
</script>

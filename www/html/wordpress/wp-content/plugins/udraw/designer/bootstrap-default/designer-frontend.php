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
        <?php include_once(UDRAW_PLUGIN_DIR . '/designer/bootstrap-default/designer-template-wrapper.php'); ?>
    </div>
</div>

<div id="udraw-preview-ui" style="display:none; padding-left:30px;">
    <div class="row" style="padding-bottom:15px;">
        <button class="btn button btn-primary" id="udraw-preview-back-to-design-btn">
            <i class="fas fa-chevron-left"></i>
            <span class="left_space">Back to Design</span>
        </button>
        <button class="btn button btn-success" id="udraw-preview-add-to-cart-btn">
            <span>Approve & Add to Cart</span>
            <i class="fas fa-chevron-right left_space"></i>
        </button>
    </div>
    <div class="row" id="udraw-preview-design-placeholer">
    </div>
</div>

<form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
    <input type="hidden" name="udraw_save_product_data" value="" />
    <input type="hidden" name="udraw_save_product_preview" value="" />
    <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
    <input type="hidden" name="udraw_save_access_key" value="<?php echo (isset($_GET['udraw_access_key'])) ? $_GET['udraw_access_key'] : NULL; ?>" />
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
    <?php echo $_udraw_settings['udraw_designer_css_hook']; ?>
    div#designer-wrapper {
        left: 0px;
        position: fixed;
        top: -9999px;
        padding-top: 5%;
        background: #fff;
        z-index: 9999;
        width: 100%;
        height: 100vh;
    }
    @media only screen and (min-height: 980px) {
        div#designer-wrapper {
            padding-top: 15%;
        }
    }
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

        jQuery('div.entry-summary form.cart div.quantity input').css('width', '5em');
        <?php echo $_udraw_settings['udraw_designer_js_hook']; ?>
    });
</script>

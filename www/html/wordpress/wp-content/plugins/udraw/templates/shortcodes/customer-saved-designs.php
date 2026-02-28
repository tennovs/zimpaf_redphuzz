<?php

$uDraw = new uDraw();
if (!is_user_logged_in()) {
    ?>
    <p>
        <?php _e('You are currently not logged in. Please ', 'udraw'); ?>
        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e('log in.','udraw'); ?></a>
    </p>
    <?php
    return;
}
$current_user = wp_get_current_user();
if ( !($current_user instanceof WP_User) )
    return;            

$_designs = $uDraw->get_udraw_customer_designs($current_user->ID); 
if (count($_designs) < 1) {
    ?>
    <p>
        <?php _e('You do not have any saved designs.', 'udraw'); ?>
    </p>
    <?php
    return;
}

woocommerce_product_loop_start();
woocommerce_product_subcategories();
?>
<style>
    ul.products li.product {
        width: 21%;
    }
    .saved-designs-table tbody a {
        padding: 10px;
    }
</style>
<table class="saved-designs-table" style="width: 100%; line-height: 3">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Preview</th>
            <th>Name</th>
            <th>Date Modified</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($_designs as $design) {
            $_post = get_post($design->post_id);
            if (gettype($_post) === 'object') {
                $_product = new WC_Product($design->post_id);
                $_designURL = get_permalink($_post->ID) . '&udraw_access_key=' . $design->access_key;

                $_permalink_option = get_option('permalink_structure');            

                if ($_permalink_option != '') {
                    $_designURL = get_permalink($_post->ID) . '?udraw_access_key=' . $design->access_key;            
                }
                ?>

                <tr>
                    <td>
                        <a href="<?php echo $_designURL ?>" rel="nofollow" data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="design_btn button btn product_type_simple" id="design_btn_<?php echo $design->access_key; ?>" style="background: #004eff; margin-top: 1px; color: white;">Modify Design</a>
                        <a href="#" rel="nofollow" data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="rename_btn button btn product_type_simple" id="rename_btn_<?php echo $design->access_key; ?>" onclick='javascript:_udraw_rename_btn_click("<?php echo $design->access_key; ?>"); return false;'>Rename</a>
                        <a href="<?php echo $_designURL ?>" rel="nofollow" data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="save_btn button btn product_type_simple" style="background: #179600; margin-top: 1px; display:none; color: white;" id="save_btn_<?php echo $design->access_key; ?>" onclick='javascript:_udraw_save_btn_click("<?php echo $design->access_key; ?>"); return false;'>Save</a>
                        <a href="<?php echo $_designURL ?>" rel="nofollow" data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="cancel_btn button btn  product_type_simple" style="background: #f00; margin-top: 1px; display:none; color: white;" id="cancel_btn_<?php echo $design->access_key; ?>" onclick='javascript:_udraw_cancel_btn_click("<?php echo $design->access_key; ?>"); return false;'>Cancel</a>
                        <a href="#" rel="nofollow" data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="duplicate_btn button btn product_type_simple" id="duplicate_btn_<?php echo $design->access_key; ?>" onclick='javascript:_udraw_duplicate_btn_click("<?php echo $design->access_key; ?>"); return false;'>Duplicate</a>
                    </td>

                    <td>
                        <img style="max-width:120px;max-height:120px"; src="<?php echo $design->preview_data ?>" alt="">
                    </td>

                    <td>
                        <label id="name_lbl_<?php echo $design->access_key; ?>"><?php echo $design->name; ?></label>
                        <input type="text" id="name_txt_<?php echo $design->access_key;?>" name="name_txt_<?php echo $design->access_key;?>" value="<?php echo $design->name; ?>" style="display:none;" />
                    </td>

                    <td>
                        <strong style="font-size: 0.9em;">
                        <?php
                        if (strlen($design->modify_date) > 1) {
                            $t = strtotime($design->modify_date);
                            echo date('m/d/y g:i A',$t);

                        } else {
                            $t = strtotime($design->create_date);
                            echo date('m/d/y g:i A',$t);
                        }
                        ?>
                        </strong>
                    </td>

                    <td>
                        <a href="#" rel="nofollow" onclick='javascript:_udraw_delete_btn_click("<?php echo $design->access_key; ?>"); return false;' data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="button btn product_type_simple" style="background: #FF0202; margin-top: 1px; color: white;" >Delete</a>
                        <a href="#" rel="nofollow" onclick='javascript:_udraw_purchase_btn_click("<?php echo $design->access_key; ?>"); return false;' data-product_id="<?php echo $design->post_id; ?>" data-product_sku="" class="button btn product_type_simple" style="background: rgb(23, 150, 0); margin-top: 1px; color: white;" >Purchase</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<script>
    function _udraw_rename_btn_click(id) {
        _show_udraw_edit_ui(id);
    }

    function _udraw_cancel_btn_click(id) {
        _hide_udraw_edit_ui(id);
    }

    function _udraw_save_btn_click(id) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            data: {
                'action': 'udraw_update_customer_design',
                'name': jQuery('#name_txt_' + id).val(),
                'access_key': id,
            },
            success: function (response) {
                jQuery('#name_lbl_' + id).val(jQuery('#name_txt_' + id).val());
                jQuery('#name_lbl_' + id).text(jQuery('#name_txt_' + id).val());
                _hide_udraw_edit_ui(id);
            }
        });
    }

    function _udraw_duplicate_btn_click(id) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            data: {
                'action': 'udraw_duplicate_customer_design',
                'access_key': id
            },
            success: function (response) {
                window.location.reload();
            }
        });
    }

    function _udraw_delete_btn_click(id) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            data: {
                'action': 'udraw_remove_customer_design',
                'access_key': id,
                'customer_id': '<?php echo wp_get_current_user()->ID; ?>'
            },
            success: function (response) {
                window.location.reload();
            }
        });
    }

    function _udraw_purchase_btn_click(id) {
        var saved_access_key = id;
        jQuery.ajax({
				url 	: '<?php echo admin_url("admin-ajax.php") ?>',
				type 	: 'POST',
				data 	: {
					action              : 'udraw_purchase_saved_design',
					saved_access_key    : saved_access_key
				},
				success	: function( response ) {
					try {
						response = jQuery.parseJSON( response );			
						if (response.success) {											
							window.location = '<?php echo wc_get_cart_url() ?>';
						} else {
							console.log( response );
                            alert('There was an issue trying to add this item to your bag.');
						}
					} catch( e ) {
						console.log( e );
                        alert('There was an issue trying to add this item to your bag.');
					}
				}
			});
    }

    function _hide_udraw_edit_ui(id) {
        jQuery('#rename_btn_' + id).show();
        jQuery('#name_lbl_' + id).show();
        jQuery('#cancel_btn_' + id).hide();
        jQuery('#name_txt_' + id).hide();
        jQuery('#save_btn_' + id).hide();
    }

    function _show_udraw_edit_ui(id) {
        jQuery('#rename_btn_' + id).hide();
        jQuery('#name_lbl_' + id).hide();
        jQuery('#cancel_btn_' + id).show();
        jQuery('#name_txt_' + id).show();
        jQuery('#save_btn_' + id).show();
    }

    jQuery(document).ready(function ($) {

    });
</script>
<?php
woocommerce_product_loop_end();
wp_reset_query();
?>
<?php
global $wpdb, $post;

$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();   
$_activation_key = uDraw::get_udraw_activation_key();

$udraw_public_key = "";
// this is a new product. we'll check to see if user wants to link from template.
if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
    if ($_GET['udraw_action'] == "new-product") {
        $templateId = $_GET['udraw_template_id'];                    
        $udrawTemplate = $this->get_udraw_templates($templateId);
        if (strlen($udrawTemplate[0]->public_key) > 1) {
            $udraw_public_key = $udrawTemplate[0]->public_key;
        }
    }
} else {
    $udraw_public_key = get_post_meta($post->ID, '_udraw_public_key', true);
}            
echo "<input type=\"hidden\" name=\"udraw_public_key\" value=\"". $udraw_public_key . "\" />";
?>


<div id="udraw_product_data" class="panel woocommerce_options_panel">				
    <div class="options_group" id="udraw_template_id_form_group">
        <p class="form-field">
            <label for="udraw_template_id"><?php _e('Select uDraw Template', 'udraw'); ?></label>
            <select id="udraw_template_id" name="udraw_template_id[]" multiple="multiple" data-placeholder="<?php _e('Select uDraw Template&hellip;', 'udraw'); ?>">
                <?php
                $templates = $this->get_udraw_templates();
                $templateId = $this->get_udraw_template_ids($post->ID);
                if (count($templateId) == 0) {
                    // this is a new product. we'll check to see if user wants to link from template.
                    if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
                        if ($_GET['udraw_action'] == "new-product") {
                            $templateId = array();
                            array_push($templateId, $_GET['udraw_template_id']); // pre-select template if linking as new product.
                        }
                    }
                }
                //$template_options = array();
                foreach($templates as $template) {
                    //$template_options[esc_attr($template->id)] = esc_html($template->name . ' - ' . $template->design_width . '" x '. $template->design_height .'"');
                    
                    $found_template_id = false;

                    foreach ($templateId as $_template_id) {
                        if ($_template_id == $template->id) {
                            $found_template_id = true;                                         
                            break;
                        }
                    }
                    if ($found_template_id) {                                        
                        echo '<option value="' . esc_attr($template->id) . '" selected>' . esc_html($template->name . ' - ' . $template->design_width . '" x '. $template->design_height .'"') . '</option>';
                    } else {
                        echo '<option value="' . esc_attr($template->id) . '">' . esc_html($template->name . ' - ' . $template->design_width . '" x '. $template->design_height .'"'). '</option>';
                    }
                }

                ?>						                        
            </select>
            <img class="help_tip" data-tip='<?php _e('Link an existing template. Templates can be created from the uDraw->Add Template section.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />									
        </p>
        <p class="form-field">
            <div id="udraw_template_preview">
            </div>
        </p>
        <?php
        $allow_bypass = false;
        if (metadata_exists('post', $post->ID, '_udraw_allow_design_bypass')) {
            $allow_bypass = get_post_meta($post->ID, '_udraw_allow_design_bypass', true);
        }
        $bypass_design_cb_args = array(
            'label' => __('Allow Design Bypass', 'udraw'),
            'class' => '',
            'style' => '',
            'wrapper_class' => '',
            'value' => $allow_bypass, // if empty, retrieved from post meta where id is the meta_key
            'id' => 'udraw_allow_design_bypass', // required
            'name' => 'udraw_allow_design_bypass', //name will set from id if empty
            'cbvalue' => 'yes',
            'desc_tip' => false,
            'custom_attributes' => '', // array of attributes 
            'description' => __('Allows customers to add this product to cart without a design or uploading artwork.', 'udraw')
        );
        woocommerce_wp_checkbox($bypass_design_cb_args);
        ?>
        <p class="form-field" id="udraw_allow_customer_download_form_group">
            <label for="udraw_allow_customer_download_design">Allow Save/Download</label>
            <?php
                $allowCustomerDownloadDesign = get_post_meta($post->ID, '_udraw_allow_customer_download_design', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_allow_customer_download_design" id="udraw_allow_customer_download_design" value="yes" <?php if ($allowCustomerDownloadDesign == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow the customer to download the design as a PDF while they are designing the artwork.</span>
        </p>
        
        <?php
            $designerSkinOverride = get_post_meta($post->ID, '_udraw_designer_skin_override', true);
            $designerSkin = get_post_meta($post->ID, '_udraw_designer_skin', true);
            $display = 'display: none;';
            if ($designerSkinOverride) {
                $display = '';
            }
        ?>  
        <p class="form-field" id="udraw_designer_skin_override_form_group">
            <label for="udraw_designer_skin_override">Override Designer Skin</label>                      
            <input type="checkbox" class="checkbox" name="udraw_designer_skin_override" id="udraw_designer_skin_override" value="yes" <?php if ($designerSkinOverride == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow the product to use a skin different from the one set in uDraw Settings.</span>
        </p>
        <p class="form-field" id="udraw_designer_skin_form_group" style="<?php echo $display; ?>">;
            <label for="udraw_designer_skin">uDraw Designer Skin</label>
            <select id="udraw_designer_skin" name="udraw_designer_skin" data-placeholder="<?php _e('Select Designer Skin', 'udraw'); ?>">
                <?php
                    $skins = array (
                        'default' => 'Default',
                        'simple' => 'Simple',
                        'optimal'=> 'Optimal',
                        'sleek'=> 'Sleek',
                        'slim' => 'Slim'
                    );

                    $skins = apply_filters('udraw_designer_register_skin', $skins);

                    foreach ( $skins as $value => $name ) {
                        $selected = "";
                        if ($designerSkin == $value) {
                            $selected = "selected";
                        }
                        echo "<option class=\"level-0\" value=\"" . $value . "\" ". $selected .">". $name ."</option>";
                    }    
                ?>
            </select>
        </p>
        <?php do_action('udraw_designer_admin_product_panel'); ?>
    </div>

    <?php
    if ( strlen($_udraw_settings['goepower_api_key']) > 1 && strlen($_udraw_settings['goepower_producer_id']) > 0 ) {
    ?>

    <div class="options_group" id="udraw_pdf_template_id_form_group">
        <p class="form-field">
            <label for="udraw_block_template_id"><?php _e('Select PDF Template', 'udraw'); ?></label>
            <select id="udraw_block_template_id" name="udraw_block_template_id[]" multiple="multiple" data-placeholder="<?php _e('Select PDF Template&hellip;', 'udraw'); ?>" >
                <?php
                $uDrawPDFBlocks = new uDrawPDFBlocks();
                $block_templates = $uDrawPDFBlocks->get_company_products();
                $block_template_id = get_post_meta($post->ID, '_udraw_block_template_id', true);
                if (is_null($block_template_id)) {
                    $block_template_id = array();
                }
                // Convert String ( old type ) to Array ( new type )
                if (gettype($block_template_id) == 'string') {
                    $block_template_id = explode("HuhWhatOkay", get_post_meta($post->ID, '_udraw_block_template_id', true));
                }

                if (count($block_template_id) == 0 || count($block_template_id) == 1) {
                    if (count($block_template_id) == 1) {
                        if (strlen($block_template_id[0]) == 0) {
                            // New product, so we'll assign block template based on request params.
                            if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
                                if ($_GET['udraw_action'] == "new-block-product") {
                                    $block_template_id = array();
                                    array_push($block_template_id, $_GET['udraw_template_id']); // pre-select template if linking as new product.
                                }
                            }
                        }
                    }
                }

                foreach($block_templates as $block_template) {
                    $found_block_template_id = false;
                    if (gettype($block_template_id) == 'array') {
                        foreach ($block_template_id as $_block_template_id) {   
                            if ($_block_template_id == $block_template['ProductID'] || $_block_template_id == $block_template['UniqueID']) {
                                $found_block_template_id = true;                                         
                            }
                        }
                    }

                    if ($found_block_template_id) {                                        
                        echo '<option value="' . esc_attr($block_template['UniqueID']) . '" selected>' . esc_html($block_template['ProductName']) . '</option>';
                    } else {
                        echo '<option value="' . esc_attr($block_template['UniqueID']) . '">' . esc_html($block_template['ProductName']). '</option>';
                    }

                }

                ?>
            </select>
            <img class="help_tip" data-tip='<?php _e('Link an existing template. Templates can be created from the uDraw->Add Template section.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />									
        </p>
        <p class="form-field">
            <div id="udraw_block_template_preview">
            </div>
        </p>
        
        <?php
            $pdfLayoutOverride = get_post_meta($post->ID, '_udraw_pdf_layout_override', true);
            $pdfLayout = get_post_meta($post->ID, '_udraw_pdf_layout', true);
            $style = 'display: none;';
            if ($pdfLayoutOverride) {
                $style = '';
            }
        ?>  
        
        <p class="form-field" id="udraw_pdf_layout_override_form_group">
            <label for="udraw_pdf_layout_override">Override PDF Designer Layout</label>                      
            <input type="checkbox" class="checkbox" name="udraw_pdf_layout_override" id="udraw_pdf_layout_override" value="yes" <?php if ($pdfLayoutOverride == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow the product to use a different designer layout from the one set in uDraw GoEpower Settings.</span>
        </p>
        <p class="form-field" id="udraw_pdf_layout_form_group" style="<?php echo $style; ?>">;
            <label for="udraw_pdf_layout">PDF Designer Layout</label>
            <select name="udraw_pdf_layout" style="min-width: 300px;" class="chosen-udraw" id="udraw_pdf_layout">
                <option value="embedded" <?php if ($pdfLayout === 'embedded' || $pdfLayout === '') { echo " selected "; } ?> ><?php _e('Embedded in page', 'udraw'); ?></option>
                <option value="popup" <?php if ($pdfLayout === 'popup') { echo " selected "; } ?> ><?php _e('Pop-up', 'udraw'); ?></option>
                <option value="onepageh" <?php if ($pdfLayout === 'onepageh') { echo " selected "; } ?> ><?php _e('One Page (Price Matrix + Designer) - Horizontal Display', 'udraw'); ?></option>
                <option value="onepagev" <?php if ($pdfLayout === 'onepagev') { echo " selected "; } ?> ><?php _e('One Page (Price Matrix + Designer) - Vertical Display', 'udraw'); ?></option>
            </select>
        </p>
        <p class="form-field" id="udraw_pdf_allow_print_save_form">
            <label for="udraw_pdf_allow_print_save">Allow Saving/Printing of PDF Preview?</label>
            <?php
                $allowPDFPrintSave = get_post_meta($post->ID, '_udraw_pdf_allow_print_save', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_pdf_allow_print_save" id="udraw_pdf_allow_print_save" value="yes" <?php if ($allowPDFPrintSave == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow customer to save or print the PDF preview from within the PDF Viewer.</span>
        </p>
    </div>
    
    <div class="options_group" id="udraw_xmpie_template_id_form_group">
        <p class="form-field">
            <label for="udraw_xmpie_template_id"><?php _e('Select XmPie Template', 'udraw'); ?></label>
            <select id="udraw_xmpie_template_id" name="udraw_xmpie_template_id[]" multiple="multiple" data-placeholder="<?php _e('Select XmPie Template&hellip;', 'udraw'); ?>" >
                <?php
                $uDrawPDFXmPie = new uDrawPdfXMPie();
                $xmpie_templates = $uDrawPDFXmPie->get_company_products();
                $xmpie_template_id = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
                $xmpie_template_id_length = (gettype($xmpie_template_id) == 'array') ? count($xmpie_template_id) : ((gettype($xmpie_template_id) == 'string') ? strlen($xmpie_template_id) : NULL);
                if ($xmpie_template_id_length == 0) {
                    // New product, so we'll assign block template based on request params.
                    if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
                        if ($_GET['udraw_action'] == "new-xmpie-product") {
                            $xmpie_template_id = array();
                            array_push($xmpie_template_id, $_GET['udraw_template_id']); // pre-select template if linking as new product.
                        }
                    }
                }

                
                foreach($xmpie_templates as $xmpie_template) {
                    $found_xmpie_template_id = false; 
                    if (gettype($xmpie_template_id) == "array") {
                        foreach ($xmpie_template_id as $_xmpie_template_id) {   
                            if ($_xmpie_template_id == $xmpie_template['ProductID']) {
                                $found_xmpie_template_id = true;                                         
                            }
                        }
                    }

                    if ($found_xmpie_template_id) {                                        
                        echo '<option value="' . esc_attr($xmpie_template['ProductID']) . '" selected>' . esc_html($xmpie_template['ProductName']) . '</option>';
                    } else {
                        echo '<option value="' . esc_attr($xmpie_template['ProductID']) . '">' . esc_html($xmpie_template['ProductName']). '</option>';
                    }

                }

                ?>
            </select>
            <img class="help_tip" data-tip='<?php _e('Link an existing template. Templates can be created from the uDraw->Add Template section.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />									
        </p>
        <div id="udraw_xmpie_template_preview"></div>
        <p class="form-field" id="udraw_pdf_xmpie_allow_print_save_form">
            <label for="udraw_pdf_xmpie_allow_print_save">Allow Saving/Printing of PDF Preview?</label>
            <?php
                $allowPDFXmPiePrintSave = get_post_meta($post->ID, '_udraw_pdf_xmpie_allow_print_save', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="_udraw_pdf_xmpie_allow_print_save" id="udraw_pdf_xmpie_allow_print_save" value="yes" <?php if ($allowPDFXmPiePrintSave == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow customer to save or print the PDF preview from within the PDF Viewer.</span>
        </p>
        <p class="form-field" id="udraw_pdf_xmpie_use_colour_palette_form">
            <label for="udraw_pdf_xmpie_use_colour_palette">Use colour palette</label>
            <?php
                $udraw_pdf_xmpie_use_colour_palette = get_post_meta($post->ID, '_udraw_pdf_xmpie_use_colour_palette', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_pdf_xmpie_use_colour_palette" id="udraw_pdf_xmpie_use_colour_palette" value="yes" <?php if ($udraw_pdf_xmpie_use_colour_palette == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will restrict customers to select colours from a defined palette instead of a spectrum.</span>
        </p>
    </div>

    <?php } ?>                

    <div class="options_group">
        <p class="form-field">
            <label for="udraw_display_options_page_first">Show Options First</label>
            <?php
                $displayOptionsPageFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_display_options_page_first" id="udraw_display_options_page_first" value="yes" <?php if ($displayOptionsPageFirst == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will display the options page before showing designer and give ability to upload or design artwork.</span>
        </p>
        <p class="form-field" id="udraw_allow_upload_artwork_form">
            <label for="udraw_allow_upload_artwork">Allow Upload Artwork</label>
            <?php
                $allowUploadArtwork = get_post_meta($post->ID, '_udraw_allow_upload_artwork', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_allow_upload_artwork" id="udraw_allow_upload_artwork" value="yes" <?php if ($allowUploadArtwork == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will enable upload artwork on the product page. </span>
        </p>
        <p class="form-field" id="udraw_allow_double_upload_artwork_form">
            <label for="udraw_allow_double_sided_upload_artwork">Allow Double Sided Upload Artwork</label>
            <?php
                $allowDoubleUploadArtwork = get_post_meta($post->ID, '_udraw_double_allow_upload_artwork', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_double_allow_upload_artwork" id="udraw_double_allow_upload_artwork" value="yes" <?php if ($allowDoubleUploadArtwork == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will enable 2-sided uploads on the product page. </span>
        </p>
        <p class="form-field" id="max_files_allowed">
            <label for="max_files_allowed">Max number of uploads allowed</label>   
            <?php
                $maxUploadFiles = get_post_meta($post->ID, '_max_files_allowed', true);
            ?> 
            <input type="number" class="number" name="max_files_allowed" id="max_files_allowed" style="width: 350px" value="<?php echo $maxUploadFiles ?>"/>
            <img class="help_tip" data-tip='<?php _e('This will restrict the number of files to be uploaded.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />	
        </p>
        <?php if (isset($_activation_key) && strlen($_activation_key) > 0 ) { ?>
        <p class="form-field" id="udraw_allow_convert_pdf_form" <?php if ($displayOptionsPageFirst != 'yes' || $allowUploadArtwork != 'yes') { ?> style="display: none;" <?php } ?> >
            <label for="udraw_allow_convert_pdf_form">Convert PDF to uDraw Design</label>
            <?php
                $allowConvertPDF = get_post_meta($post->ID, '_udraw_allow_convert_pdf', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_allow_convert_pdf" id="udraw_allow_convert_pdf" value="yes" <?php if ($allowConvertPDF == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will give an option to convert the uploaded PDF file to a uDraw design (uDraw Template must be selected). </span>
        </p>
        <?php } ?>
        <p class="form-field" id="udraw_allow_post_payment_download">
            <label for="udraw_allow_post_payment_download">Allow Post Payment PDF Download</label>
            <?php
                $allowPostPaymentDownload = get_post_meta($post->ID, '_udraw_allow_post_payment_download', true);
            ?>
            <input type="checkbox" class="checkbox" name="udraw_allow_post_payment_download" id="udraw_allow_post_payment_download" value="yes" <?php if ($allowPostPaymentDownload == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will allow customers to download the PDF after purchase.</span>
        </p>
    </div>
    <div class="options_group">
        <p class="form-field">
            <label for="udraw_is_private_product">Is Product Private?</label>
            <?php
                $isUdrawPrivateProduct = get_post_meta($post->ID, '_udraw_is_private_product', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_is_private_product" id="udraw_is_private_product" value="yes" <?php if ($isUdrawPrivateProduct == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">Make this product/template visible for specific set of users only.</span>
        </p>
        <?php if ($isUdrawPrivateProduct === 'yes') { ?>
        <p class="form-field udraw-private-users-select">
            <label for="udraw_private_users_list"><?php _e('Customers', 'udraw'); ?></label>
            <select id="udraw_private_users_list" name="udraw_private_users_list[]" multiple="multiple" data-placeholder="<?php _e('Select a Customer&hellip;', 'udraw'); ?>">
                <?php
                $privateCustomers = get_post_meta($post->ID, '_udraw_private_users_list', true);
                $per_page = 500;
                $page = 1;
                do {
                    $get_users_args = array(
                        'role__in'  => [ 'customer', 'subscriber'],
                        'number'    => $per_page,
                        'paged'     => $page
                    );
                    $customers = get_users( $get_users_args );
                    $customers_count = count($customers);
                    $reversed = array_reverse($customers);
                    while (count($reversed) > 0) {
                        $customer = array_pop($reversed);
                        $foundCustomer = false;
                        $selected = '';
                        if (is_array($privateCustomers)) {
                            foreach ($privateCustomers as $privateCustomer) { 
                                if ($customer->ID == $privateCustomer) { $foundCustomer = true;  break; } 
                            }                         
                        }
                        if ($foundCustomer) {
                            $selected = ' selected ';
                        }
                        echo printf('<option value="%s" %s>%s - %s</option>', esc_attr($customer->ID), $selected, esc_html($customer->display_name), $customer->user_email);
                    }
                    $page++;
                } while ($customers_count === $per_page);
                ?>						                        
            </select>
            <img class="help_tip" data-tip='<?php _e('These users will have private access to this product/template.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />									
        </p>
        <?php } ?>
    </div>
    <div class="options_group">
        <p class="form-field">
            <label for="_manage_price_matrix"><?php _e('Define Price Matrix', 'udraw') ?></label>
            <?php
                $isPirceMatrixSet = get_post_meta($post->ID, '_udraw_is_price_matrix_set', true);
            ?>                        
            <input type="checkbox" class="checkbox" name="udraw_is_price_matrix_set" id="udraw_is_price_matrix_set" value="yes" <?php if ($isPirceMatrixSet == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description"><?php _e('Override default Price and Price Matrix.', 'udraw') ?></span>
        </p>
        <p class="form-field udraw-price-matrix-select">
            <label for="udraw_price_matrix_list"><?php _e('Price Matrix', 'udraw'); ?></label>
            <select id="udraw_price_matrix_list" name="udraw_price_matrix_list[]" multiple="multiple" data-placeholder="Select a Price matrix&hellip">
                <?php
                $udrawPriceMatrix = new uDrawPriceMatrix();
                $price_matrix_list = $udrawPriceMatrix->get_price_matrix();
                $selected_price_matrix = get_post_meta($post->ID, '_udraw_price_matrix_list', true);
                foreach ($price_matrix_list as $price_matrix_item) {
                    if (gettype($selected_price_matrix) == 'array' && $selected_price_matrix[0] == $price_matrix_item->access_key) {
                        echo '<option value="' . esc_attr($price_matrix_item->access_key) . '" selected>' . esc_html($price_matrix_item->name) . '</option>';
                    } else {
                        echo '<option value="' . esc_attr($price_matrix_item->access_key) . '">' . esc_html($price_matrix_item->name) . '</option>';
                    }
                }

                ?>						                        
            </select>
            <img class="help_tip" data-tip='<?php _e('This price matrix will override the default price and price matrix.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />									
        </p>
        <p class="form-field" id="udraw_price_matrix_disable_size_check_form">
            <label for="udraw_price_matrix_disable_size_check_label">Disable File Upload Size Check</label>
            <?php
                $disableSizeCheckPriceMatrix = get_post_meta($post->ID, '_udraw_price_matrix_disable_size_check', true);
            ?>
            <input type="checkbox" class="checkbox" name="udraw_price_matrix_disable_size_check" id="udraw_price_matrix_disable_size_check" value="yes" <?php if ($disableSizeCheckPriceMatrix == "yes") { echo "checked=\"checked\""; } ?> />
            <span class="description">This will disable the default page size check for price matrix which use sizes.</span>
        </p>
        <p class="form-field">
            <label>Set Preset Private Image Categories</label>
            <?php
                $presetPrivateImageCategories = get_post_meta($post->ID, '_udraw_preset_private_image_categories', true);
            ?>
            <input name="_udraw_preset_private_image_categories" id="_udraw_preset_private_image_categories" type="text" style="width: 350px" value="<?php echo $presetPrivateImageCategories ?>" class="">
            <img class="help_tip" data-tip='<?php _e('Add a list of preset private image library categories separated by comma.', 'udraw') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <?php do_action('udraw_admin_product_panel'); ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var udraw_templates_array = new Array();
        jQuery.getJSON(ajaxurl + '?action=udraw_get_templates&include_categories=false', function (data){
            udraw_templates_array = data;
            if (jQuery('select#udraw_template_id').val().length > 0) {
                jQuery('select#udraw_template_id').trigger('change');
            }
        });
        jQuery('#udraw_allow_upload_artwork, #udraw_display_options_page_first, select#udraw_template_id').on('change', function(){
            if (jQuery('#udraw_allow_upload_artwork').prop('checked') && jQuery('#udraw_display_options_page_first').prop('checked') && jQuery('#udraw_template_id').val() !== null) {
                jQuery('#udraw_allow_convert_pdf_form').show();
            } else {
                jQuery('#udraw_allow_convert_pdf_form').hide();
                jQuery('#udraw_allow_convert_pdf').prop('checked',false);
            }
        })
        jQuery('#_udraw_product').change(function() {
            if (jQuery(this).is(':checked')) {
                jQuery('.hide_if_udraw_product').show();
            } else {
                jQuery('.hide_if_udraw_product').hide();
            }
        }).change();

        jQuery('#_udraw_product').click(function(){
            if (jQuery(this).is(':checked')) {
                jQuery('#udraw_display_options_page_first').prop('checked', true);
                jQuery('#udraw_allow_upload_artwork_form').show();
            } else {
                jQuery('#udraw_display_options_page_first').prop('checked', false);
                jQuery('#udraw_allow_upload_artwork_form').hide();
            }
        });

        jQuery('#udraw_display_options_page_first').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#udraw_allow_upload_artwork_form').show();
                jQuery('#udraw_allow_double_upload_artwork_form').show();
            } else {
                jQuery('#udraw_allow_upload_artwork_form').hide();
                jQuery('#udraw_allow_double_upload_artwork_form').hide();
            }
        }).change();
        
        jQuery('#udraw_allow_upload_artwork').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#max_files_allowed').show();
                jQuery('#udraw_allow_double_upload_artwork_form').show();
            } else {
                jQuery('#max_files_allowed').hide();
                jQuery('#udraw_allow_double_upload_artwork_form').hide();
            }
        }).change();
        
        jQuery('#udraw_pdf_layout_override').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#udraw_pdf_layout_form_group').show();
            } else {
                jQuery('#udraw_pdf_layout_form_group').hide();
            }
        }).change();

        jQuery('#udraw_is_private_product').change(function() {
            if (jQuery(this).is(':checked')) {
                jQuery('.udraw-private-users-select').show();
            } else {
                jQuery('.udraw-private-users-select').hide();
            }
        }).change();

        jQuery('#udraw_is_price_matrix_set').change(function() {
            if (jQuery(this).is(':checked')) {
                jQuery('.udraw-price-matrix-select').show();
            } else {
                jQuery('.udraw-price-matrix-select').hide();
            }
        }).change();                    

        jQuery('select#udraw_template_id').change(function() {
            jQuery('#udraw_template_preview').empty();
            var _template_id = jQuery('select#udraw_template_id').val();
            if (_template_id) {
                var _random = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 8);
                if (_template_id.constructor === Array) {
                    for (var x = 0; x < _template_id.length; x++) {
                        for (var i = 0; i < udraw_templates_array.length; i++) {
                            if (_template_id[x] === udraw_templates_array[i].id) {
                                jQuery('#udraw_template_preview').prepend('<img src="' + udraw_templates_array[i].preview + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                            }
                        }
                    }
                } else {
                    for (var i = 0; i < udraw_templates_array.length; i++) {
                        if (_template_id === udraw_templates_array[i].id) {
                            jQuery('#udraw_template_preview').prepend('<img src="' + udraw_templates_array[i].preview + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                        }
                    }
                }
                jQuery('#udraw_pdf_template_id_form_group').hide();
                jQuery('#udraw_xmpie_template_id_form_group').hide();
                jQuery('#udraw_allow_customer_download_form_group').show();
            } else {
                jQuery('#udraw_template_preview').empty();
                jQuery('#udraw_pdf_template_id_form_group').show();
                jQuery('#udraw_xmpie_template_id_form_group').show();
                jQuery('#udraw_allow_customer_download_form_group').hide();
            }
        });                   

        jQuery('select#udraw_block_template_id').change(function() {
            var _template_id = jQuery('select#udraw_block_template_id').val();
            jQuery('#udraw_block_template_preview').empty();
            if (_template_id) {
                var _random = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 8);

                if (_template_id.constructor === Array) {
                    for (var x = 0; x < _template_id.length; x++) {
                        jQuery.getJSON(ajaxurl + '?action=udraw_pdf_block_get_templates&block-template-id=' + _template_id[x],
                            function (data) {
                                jQuery('#udraw_block_template_preview').prepend('<img src="' + data.ThumbnailLarge + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                            }
                        );
                    }
                } else {
                    jQuery.getJSON(ajaxurl + '?action=udraw_pdf_block_get_templates&block-template-id=' + _template_id,
                        function (data) {
                            jQuery('#udraw_block_template_preview').prepend('<img src="' + data.ThumbnailLarge + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                        }
                    );
                }
                jQuery('#udraw_pdf_layout_override_form_group').show();
                jQuery('#udraw_pdf_allow_print_save_form').show();
                jQuery('#udraw_template_id_form_group').hide();
                jQuery('#udraw_xmpie_template_id_form_group').hide();
                jQuery('#udraw_allow_customer_download_form_group').hide();
            } else {
                jQuery('#udraw_template_preview').empty();
                jQuery('#udraw_pdf_allow_print_save_form').hide();
                jQuery('#udraw_pdf_layout_override_form_group').hide();
                jQuery('#udraw_template_id_form_group').show();
                jQuery('#udraw_xmpie_template_id_form_group').show();
            }
        });

        jQuery('select#udraw_xmpie_template_id').change(function() {
            var _template_id = jQuery('select#udraw_xmpie_template_id').val();
            jQuery('#udraw_xmpie_template_preview').empty();
            if (_template_id) {
                var _random = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 8);

                if (_template_id.constructor === Array) {
                    for (var x = 0; x < _template_id.length; x++) {
                        jQuery.getJSON(ajaxurl + '?action=udraw_xmpie_get_templates&xmpie-template-id=' + _template_id[x],
                            function (data) {
                                jQuery('#udraw_xmpie_template_preview').prepend('<img src="' + data.ThumbnailLarge + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                            }
                        );
                    }
                } else {
                    jQuery.getJSON(ajaxurl + '?action=udraw_xmpie_get_templates&xmpie-template-id=' + _template_id,
                        function (data) {
                            jQuery('#udraw_xmpie_template_preview').prepend('<img src="' + data.ThumbnailLarge + '?' + _random + '" style="max-width:250px; border: 1px solid #9C9C9C; margin-left: 160px;" />');
                        }
                    );
                }

                jQuery('#udraw_pdf_xmpie_allow_print_save_form').show();
                jQuery('#udraw_template_id_form_group').hide();
                jQuery('#udraw_pdf_template_id_form_group').hide();
                jQuery('#udraw_allow_customer_download_form_group').hide();
            } else {
                jQuery('#udraw_template_preview').empty();
                jQuery('#udraw_pdf_xmpie_allow_print_save_form').hide();
                jQuery('#udraw_template_id_form_group').show();
                jQuery('#udraw_pdf_template_id_form_group').show();
            }
        });

        // Use 'maximumSelectionLength for select2 4.0 ( BETA ) right now.
        // Currently we need to use 'maximumSelectionSize'
        jQuery('select#udraw_template_id').css('width', '350px').select2({ maximumSelectionSize: 15 });
        jQuery('select#udraw_block_template_id').css('width', '350px').select2({ maximumSelectionSize: 15 });
        jQuery('select#udraw_xmpie_template_id').css('width', '350px').select2({ maximumSelectionSize: 15 });
        jQuery('select#udraw_private_users_list').css('width', '350px').select2();
        jQuery('select#udraw_price_matrix_list').css('width', '350px').select2({ maximumSelectionSize: 1 });
        jQuery('select#udraw_designer_skin').css('width', '350px').select2();

        <?php
        if (isset($_GET['udraw_template_id'])) {
            echo 'jQuery(\'#_udraw_product\').prop(\'checked\', true).change();';
            echo 'jQuery(\'#udraw_allow_upload_artwork_form\').show();';
        }

        if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
            if ($_GET['udraw_action'] == "new-product") {
                $templateId = $_GET['udraw_template_id'];
                $udrawTemplate = $this->get_udraw_templates($templateId);
                echo 'jQuery(\'#title\').val(\''. addslashes($udrawTemplate[0]->name) . '\');';
            } else if ($_GET['udraw_action'] == "new-block-product") {
                $block_template_id = $_GET['udraw_template_id'];
                $block_template = $uDrawPDFBlocks->get_product($block_template_id);
                echo 'jQuery(\'#title\').val(\''. addslashes($block_template['ProductName']) . '\');';
            }else if ($_GET['udraw_action'] == "new-xmpie-product") {
                $xmpie_template_id = $_GET['udraw_template_id'];
                $xmpie_template = $uDrawPDFXmPie->get_product($xmpie_template_id);
                echo 'jQuery(\'#title\').val(\''. addslashes($xmpie_template['ProductName']) . '\');';
            }
            //Automatically display options first
            echo 'jQuery("#udraw_display_options_page_first").prop("checked", "true");';
            echo 'jQuery(\'#udraw_allow_upload_artwork_form\').show();';
            // This disables WooCommerce Addons Globally.
            echo 'jQuery(\'#_product_addons_exclude_global\').prop(\'checked\', true);';
        }

        if (self::is_udraw_product($post->ID)) {
            echo 'jQuery(\'#_udraw_product\').prop(\'checked\', true).change();';
            echo 'jQuery(\'#udraw_allow_upload_artwork_form\').show();';
        }                    
        ?>

        var udraw_template_id = jQuery('select#udraw_template_id').val().length;
        var block_template_id = jQuery('select#udraw_block_template_id').val().length;
        var xmpie_template_id = jQuery('select#udraw_xmpie_template_id').val().length;

        if (udraw_template_id > 0) {
            jQuery('select#udraw_template_id').trigger('change');
        } else if (block_template_id > 0) {
            jQuery('select#udraw_block_template_id').trigger('change');
        } else if (xmpie_template_id > 0) {
            jQuery('select#udraw_xmpie_template_id').trigger('change');
        }
        jQuery('#udraw_designer_skin_override').on('change',function(){
            if(jQuery('#udraw_designer_skin_form_group').is(':visible')) {
                jQuery('#udraw_designer_skin_form_group').hide();
            } else {
                jQuery('#udraw_designer_skin_form_group').show();
            }
        });
    });
</script>
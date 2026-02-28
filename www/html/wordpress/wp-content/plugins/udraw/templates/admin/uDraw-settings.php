<?php
   if (is_user_logged_in()) {
        if (!current_user_can('edit_udraw_settings')) {
            exit;
        }
    } else {
        exit;
    }

    $goprint2 = new GoPrint2();
    $gosendex = new GoSendEx();
    $goepower = new GoEpower();
    $udrawSettings = new uDrawSettings();
    $uDraw = new uDraw();    
    
?>

<div class="wrap">
    
    <?php
    if (isset($_POST['save_udraw_settings']) ) {
        $nonce = $_REQUEST['_wpnonce'];
        if( !wp_verify_nonce( $nonce, 'save_udraw_settings' )) {
            echo '<div class="error settings-error"><p><strong>Failed to update settings. Security check failed.</strong></p></div>';
        } else {
            $udrawSettings->update_settings();
            echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings Saved</strong></p></div>';
        }
    }
        
    if (isset($_GET['create_default_pages'])) {
        if ($_GET['create_default_pages'] == 't') {
            $private_template_page_id = $udrawSettings->create_wp_post(wp_get_current_user()->ID, 'private-templates', 'uDraw - Private Templates', '[udraw_private_templates]', 'page');
            $saved_design_page_id = $udrawSettings->create_wp_post(wp_get_current_user()->ID, 'my-saved-designs', 'uDraw - My Saved Designs', '[udraw_customer_saved_designs]', 'page');
                
            $udrawSettings->update_setting('udraw_private_template_page_id', $private_template_page_id);
            $udrawSettings->update_setting('udraw_customer_saved_design_page_id', $saved_design_page_id);
        }
    }
    $_udraw_settings = $udrawSettings->get_settings();
    ?>
    
    <?php
        if (isset($_POST['save_udraw_settings']) ) {
            if (isset($_GET['tab']) && $_GET['tab'] == "goprint2") {
                // Validate GoPrint2 Key.
                if (!$goprint2->validate_key($_udraw_settings['goprint2_api_key'])) {
                    $udrawSettings->update_setting('goprint2_api_key', '');
                    $_udraw_settings['goprint2_api_key'] = '';
                    echo '<div id="setting-error-settings_updated" class="error settings-error"><p><strong>Error: Your GoPrint2 API Key is not valid. Please re-enter a valid key.</strong></p></div>';
                }
            }

            if (isset($_GET['tab']) && $_GET['tab'] == "gosendex") {
                // Validate GoPrint2 Key.
                if (!$gosendex->validate_key($_udraw_settings['gosendex_api_key'])) {
                    $udrawSettings->update_setting('gosendex_api_key', '');
                    $_udraw_settings['gosendex_api_key'] = '';
                    echo '<div id="setting-error-settings_updated" class="error settings-error"><p><strong>Error: Your GoSendEx API Key is not valid. Please re-enter a valid key.</strong></p></div>';
                }
            }
            
            if (isset($_GET['tab']) && $_GET['tab'] == "goepower") { 
                // Validate GoEpower Key.
                $isGoepowerValid = false;
                $goepower->set_api_url(UDRAW_API_1_SERVER_URL);
                if ($goepower->validate_credentials($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_producer_id'])) {
                    $isGoepowerValid = true;
                } else {
                    for ($i=2; $i <= 5; $i++) {
                        if ($i === 2) {
                            $goepower->set_api_url(UDRAW_API_2_SERVER_URL);
                        } /*else if ($i === 3) {
                            $goepower->set_api_url(UDRAW_API_3_SERVER_URL);
                        }*/ else if ($i === 4) {
                            $goepower->set_api_url(UDRAW_API_4_SERVER_URL);
                        } else if ($i === 5) {
                            $goepower->set_api_url('https://dev.goepower.com');
                        }
                        if ($goepower->validate_credentials($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_producer_id'])) {
                            $isGoepowerValid = true;
                            break;
                        }
                    }
                }
                if (!$isGoepowerValid) {
                    $udrawSettings->update_setting('goepower_api_key', '');
                    $udrawSettings->update_setting('goepower_producer_id', '');
                    $_udraw_settings['goepower_api_key'] = '';
                    $_udraw_settings['goepower_producer_id'] = '';
                    echo '<div id="setting-error-settings_updated" class="error settings-error"><p><strong>Error: Your GoEpower API Key is not valid. Please re-enter a valid key.</strong></p></div>';
                } else {
                    // Validate GoEpower Username / Password Combination.
                    $goepower_login = $goepower->get_login_object($_udraw_settings['goepower_username'], $_udraw_settings['goepower_password'], $_udraw_settings['goepower_api_key']);
                    if (strlen($goepower_login->Token) === 0) {
                        echo '<div id="setting-error-settings_updated" class="error settings-error"><p><strong>GoEpower Auth Error: '. $goepower_login->ErrorMessage .'</strong></p></div>';
                        $udrawSettings->update_setting('goepower_username', '');
                        $udrawSettings->update_setting('goepower_password', '');
                        $_udraw_settings['goepower_username'] = '';
                        $_udraw_settings['goepower_password'] = '';
                    }
                    
                    if (strlen($_udraw_settings['goepower_company_id']) == 0) {                        
                        $_goepower_companies = $goepower->get_producer_companies($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_producer_id']);
                        $udrawSettings->update_setting('goepower_company_id', $_goepower_companies->CompanyID);
                        $_udraw_settings['goepower_company_id'] = $_goepower_companies->CompanyID;                                 
                    }
                }
            }
            
            if (isset($_GET['tab']) && $_GET['tab'] == "social_media") {
                if (isset($_POST['designer_enable_facebook_functions'])) {
                    $udrawSettings->update_setting('designer_enable_facebook_functions', true);
                    $udrawSettings->update_setting('designer_facebook_app_id', $_POST['designer_facebook_app_id']);
                } else {
                    //Disable facebook photos automatically if this is disabled
                    $udrawSettings->update_setting('designer_enable_facebook_photos', false);
                    $udrawSettings->update_setting('designer_enable_facebook_functions', false);
                    $udrawSettings->update_setting('designer_facebook_app_id', '');

                }
                if (isset($_POST['designer_enable_instagram_functions'])) {
                    $udrawSettings->update_setting('designer_enable_instagram_functions', true);
                    $udrawSettings->update_setting('designer_instagram_client_id', $_POST['designer_instagram_client_id']);
                } else {
                    //Disable Instagram photos automatically if this is disabled
                    $udrawSettings->update_setting('designer_enable_instagram_photos', false);
                    $udrawSettings->update_setting('designer_enable_instagram_functions', false);
                    $udrawSettings->update_setting('designer_instagram_client_id', '');

                }
                if (isset($_POST['designer_enable_flickr_functions'])) {
                    $udrawSettings->update_setting('designer_enable_flickr_functions', true);
                    $udrawSettings->update_setting('designer_flickr_client_id', $_POST['designer_flickr_client_id']);
                    $udrawSettings->update_setting('designer_flickr_secret_id', $_POST['designer_flickr_secret_id']);
                } else {
                    //Disable Instagram photos automatically if this is disabled
                    $udrawSettings->update_setting('designer_enable_flickr_photos', false);
                    $udrawSettings->update_setting('designer_enable_flickr_functions', false);
                    $udrawSettings->update_setting('designer_flickr_client_id', '');
                    $udrawSettings->update_setting('designer_flickr_secret_id', '');
                }
                if (isset($_POST['designer_enable_google_functions'])) {
                    $udrawSettings->update_setting('designer_enable_google_functions', true);
                    $udrawSettings->update_setting('designer_google_api_key', $_POST['designer_google_api_key']);
                    $udrawSettings->update_setting('designer_google_client_id', $_POST['designer_google_client_id']);
                } else {
                    $udrawSettings->update_setting('designer_enable_google_functions', false);
                    $udrawSettings->update_setting('designer_google_api_key', '');
                    $udrawSettings->update_setting('designer_google_client_id', '');
                }
            }
        }
    ?>

    <?php
        $current_tab = 'general';
        if (isset($_GET['tab'])) {
            $current_tab = $_GET['tab'];
        }
    ?>
    
    <form method="post" action="" id="udraw_settings_form">
        <?php wp_nonce_field('save_udraw_settings'); ?>
        <div class="wrap woocommerce">
            <div class="icon32 icon32-woocommerce-status" id="icon-woocommerce"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                <?php
                    $tabs = array(
                        'general' => __( 'General', 'udraw' ),
                        'designer-ui' => __('Designer UI', 'udraw'),
                        'social_media' => __( 'Social Media', 'udraw'),
                        'pages'  => __( 'Pages', 'udraw' ),
                        //'goprint2' => __('GoPrint2', 'udraw' ),
                        'gosendex' => __('GoSendEx', 'udraw'),
                        'goepower' => __('GoEpower', 'urdaw' )
                    );
                    
                    if ($uDraw->is_udraw_okay()) {
                        $tabs['price_matrix'] = __('Price Matrix', 'udraw');
                    }
                    
                    $tabs = apply_filters('udraw_add_settings_tab', $tabs);
                    
                    $tabs['activation'] = __('Activation', 'udraw');
                    
                    foreach ( $tabs as $name => $label ) {
                        echo '<a href="' . admin_url( 'admin.php?page=edit_udraw_settings&tab=' . $name ) . '" class="nav-tab ';
                        if ( $current_tab == $name ) echo 'nav-tab-active';
                        echo '"';
                        if ($name == 'activation') {
                            if ( uDraw::is_udraw_okay() ) {
                                echo 'style="background-color: #AAFFA6;" ';
                            } else {
                                echo 'style="background-color: #FFA6A6;" ';
                            }
                        }
                        echo '>' . $label . '</a>';
                    }
                ?>
            </h2><br/>
            <?php
                switch ( $current_tab ) {
                    case "general" :
                        udraw_general_settings_html($_udraw_settings);
                        break;
                    case "designer-ui" :
                        udraw_designer_ui_settings_html($_udraw_settings);
                        break;
                    case "pages" :
                        udraw_page_settings_html($_udraw_settings);
                        break;
                    case "social_media" :
                        udraw_social_media_settings_html($_udraw_settings);
                        break;
                    case "price_matrix" : 
                        udraw_price_matrix_settings_html($_udraw_settings);
                        break;
                    case "goprint2" :
                        udraw_goprint2_settings_html($_udraw_settings);
                        break;
                    case "gosendex" :
                        udraw_gosendex_settings_html($_udraw_settings);
                        break;
                    case "goepower" :
                        udraw_goepower_settings_html($_udraw_settings);
                        break;
                    case "activation" :
                        udraw_activation_settings_html($_udraw_settings);
                        break;
                    default :
                        break;
                }
                
                do_action('udraw_handle_settings_tab', $current_tab, $_udraw_settings);
            ?>
        </div>        
        
        <p class="submit">
            <input class="button-primary" type="submit" value="<?php _e('Save changes', 'udraw') ?>" name="save_udraw_settings">
        </p>
        
    </form>
</div>

<script>
    function validate_goprint2_key() {
        var apiKey = jQuery("#goprint2_api_key").val();
        jQuery.getJSON('https://www.goprint2.com/api/V1.aspx?key=' + apiKey +'&action=validate',
            function (data) {
                return data;
            }
        );
    }
    
</script>


<?php
//Removes the invisible randomly added character in front of the json object in language text file
function remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}
function udraw_general_settings_html($_udraw_settings) {
    ?>
        <table class="form-table">
            <tbody> 
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Proofing', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Proofing', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['show_customer_preview_before_adding_to_cart']) {
                                ?><input type="checkbox" name="show_customer_preview_before_adding_to_cart" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="show_customer_preview_before_adding_to_cart" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Display a proof before adding design to shopping cart.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>                
                
                <tr valign="top" class="designer_exclude_bleed" style="display: none;">
                    <th scope="row" class="titledesc"><?php _e('Exclude Bleed', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Exclude Bleed', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['designer_exclude_bleed']) {
                                ?><input type="checkbox" name="designer_exclude_bleed" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="designer_exclude_bleed" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Exclude product bleed when creating the proof images for Design products.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Product Title', 'udraw') ?></th> 
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Product Title', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['show_product_title']) {
                                ?><input type="checkbox" name="show_product_title" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="show_product_title" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Show default product title while viewing a uDraw product.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Product Breadcrumbs', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Product Breadcrumbs', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['show_product_breadcrumbs']) {
                                ?><input type="checkbox" name="show_product_breadcrumbs" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="show_product_breadcrumbs" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Show default product breadcrumbs while viewing a uDraw product.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Product Description', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Product Description', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['show_product_description']) {
                                ?><input type="checkbox" name="show_product_description" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="show_product_description" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Show product description, reviews &amp; ratings while viewing a uDraw product.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>                
                
                <tr valign="top" class="" style="display: none;">
                    <th scope="row" class="titledesc"><?php _e('Improved Variable Product Layout', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Improved Variable Product Layout', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['split_variations_2_step']) {
                                ?><input type="checkbox" name="split_variations_2_step" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="split_variations_2_step" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Split Variable products into a 2 step process. This improves the user experience process while designing products.', 'udraw') ?></label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr valign="top" class="" style="display: none;">
                    <th scope="row" class="titledesc"><?php _e('Improved Display Options With UI Update', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span>Improved Display Options With UI Update</span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['improved_display_options']) {
                                ?><input type="checkbox" name="improved_display_options" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="improved_display_options" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Display options on a separate screen with a better default UI.', 'udraw') ?> </label>
                        </fieldset>
                    </td>
                </tr>  
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Update Designer Product Images', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Update Product Images', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['update_product_images']) {
                                ?><input type="checkbox" name="update_product_images" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="update_product_images" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Automatically update uDraw designer product images.', 'udraw') ?> </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Enable Multi-Site Mode', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php _e('Update Product Images', 'udraw') ?></span></legend>
                            <label for="udraw-proofing">
                            <?php
                            if ($_udraw_settings['udraw_is_multisite']) {
                                ?><input type="checkbox" name="udraw_is_multisite" value="true" checked="checked"/> <?php
                            } else {
                                ?><input type="checkbox" name="udraw_is_multisite" value="true" /> <?php
                            }
                            ?>
                            <?php _e('Share all uDraw data between sites configured in Wordpress Multisite.', 'udraw') ?> </label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Custom CSS Hook', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <textarea name="udraw_general_css_hook" id="udraw_general_css_hook" rows="7" cols="100" style="display:none;"><?php echo ($_udraw_settings['udraw_general_css_hook']); ?></textarea>
                            <legend class="screen-reader-text"><span><?php _e('Custom CSS Hook', 'udraw') ?></span></legend>
                            <div id="udraw_general_css_hook_ace" name="udraw_general_css_hook" style="position: relative;width: auto;height: 300px;"></div>
                            <span class="description"><br><?php _e('Custom CSS code on designer page.', 'udraw') ?></span>
                        </fieldset>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Custom JS Hook', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <textarea name="udraw_general_js_hook" id="udraw_general_js_hook" rows="7" cols="100" style="display:none;"><?php echo ($_udraw_settings['udraw_general_js_hook']); ?></textarea>
                            <legend class="screen-reader-text"><span><?php _e('Custom JS Hook', 'udraw') ?></span></legend>
                            <div id="udraw_general_js_hook_ace" name="udraw_general_js_hook" style="position: relative;width: auto;height: 300px;"></div>
                            <span class="description"><br><?php _e('Custom JS code on designer page.', 'udraw') ?></span>
                        </fieldset>
                    </td>
                </tr> 
        </tbody>
    </table>
<?php
}

function udraw_designer_ui_settings_html($_udraw_settings) {    
    ?>
        <button class="collapsible" data-collapsible="general">
            <span><?php _e('General', 'udraw'); ?></span>
            <i class="fa fa-plus"></i>
        </button>
        <div class="collapsible_content" data-collapsible="general">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php _e('uDraw Designer Skin', 'udraw'); ?></label>
                        </th>
                        <td class="forminp">
                            <select name="designer_skin" style="min-width: 300px; display: none;" class="chosen-udraw" id="designer_skin">
                                <?php
                                    $default_skins = array (
                                        'default' => 'Default',
                                        'simple' => 'Simple',
                                        'optimal' => 'Optimal',
                                        'sleek' => 'Sleek',
                                        'slim' => 'Slim'
                                    );

                                    $skins = apply_filters('udraw_designer_register_skin', $default_skins);

                                    foreach ( $skins as $value => $name ) {
                                        $selected = "";
                                        if ($_udraw_settings['designer_skin'] == $value) {
                                            $selected = "selected";
                                        }
                                        echo "<option class=\"level-0\" value=\"" . $value . "\" ". $selected .">". $name ."</option>";
                                    }    
                                ?>                        
                            </select>
                            <span class="description"><br><?php _e('Skin for uDraw Designer.', 'udraw') ?></span>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php _e('uDraw Designer Language', 'udraw'); ?></label>
                        </th>
                        <td class="forminp">
                            <select name="udraw_designer_language" style="min-width: 300px; display: none;" class="chosen-udraw" id="udraw_designer_language">
                                <?php
                                    $languages = array ();
                                    //Get all locale files
                                    $localeDirectory = dir(UDRAW_PLUGIN_DIR.'/designer/includes/locales/');
                                    $languageDirectory = dir(UDRAW_LANGUAGES_DIR);
                                    $availableLanguages = array();
                                    while(false !== $entry = $localeDirectory->read()) {
                                        if ($entry != '.' && $entry != '..') {
                                            $currentLanguage = str_replace(array('udraw-', '.txt'), '', $entry);
                                            array_push($availableLanguages, $currentLanguage);
                                        }
                                    }
                                    while(false !== $entry = $languageDirectory->read()) {
                                        if ($entry != '.' && $entry != '..') {
                                            $currentLanguage = str_replace(array('udraw-', '.txt'), '', $entry);
                                            array_push($availableLanguages, $currentLanguage);
                                        }
                                    }
                                    foreach ($availableLanguages as $thisLanguage) {
                                        if (isset($languages[$thisLanguage])) {
                                            continue;
                                        }
                                        $file_dir = (file_exists(UDRAW_LANGUAGES_DIR.'udraw-'.$thisLanguage.'.txt')) ? UDRAW_LANGUAGES_DIR.'udraw-'.$thisLanguage.'.txt' : UDRAW_PLUGIN_DIR.'/designer/includes/locales/udraw-'.$thisLanguage.'.txt';
                                        $fileContents = json_decode(remove_utf8_bom(file_get_contents($file_dir)));
                                        $languages[$thisLanguage] = (isset($fileContents->languageName)) ? $fileContents->languageName : $thisLanguage;
                                    }
                                    foreach ( $languages as $value => $name ) {
                                        $selected = "";
                                        if ($_udraw_settings['udraw_designer_language'] == $value) {
                                            $selected = "selected";
                                        }
                                        echo "<option class=\"level-0\" value=\"" . $value . "\" ". $selected .">". $name ."</option>";
                                    }
                                ?>                        
                            </select>
                            <a href="#" id="udraw_language_edit_btn" class="button button-default" onclick="javascript: edit_translation_file();" style="float: right;"><?php _e('Edit Selected File', 'udraw') ?></a>
                            <a href="#" id="udraw_language_update_btn" class="button button-default" onclick="javascript: update_translation_file();" style="float: right;"><?php _e('Check for updates', 'udraw') ?></a>
                            <span class="description"><br><?php _e('Language for uDraw Designer Only.', 'udraw') ?><br><?php _e('If your desired language is not available in our default list, try to find and select it in the list below, enter the desired display name in the textbox, and click the generate button. We will try to generate it for you. We recommend checking for updates after every plugin update. If you are unsatisfied with the translations or wish improve them, you may edit them.', 'udraw') ?><br><?php _e('Disclaimer: Sending a large amount of requests may cause Google to ban your server\'s IP or require to solve CAPTCHA.', 'udraw'); ?></span>
                            <br>
                            <select id="udraw_language_text_input" style="min-width: 200px; display: none;" class="chosen-udraw"></select>
                            <input id="udraw_language_text_input_display" style="width: 125px; margin-right: 5px;" placeholder="Display Name"/>
                            <a href="#" id="udraw_generate_language_file" class="button button-default"><span>Generate</span></a>

                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc">
                            <label for="">uDraw Designer Display Orientation</label>
                        </th>
                        <td class="forminp">
                            <select name="udraw_designer_display_orientation" style="min-width: 300px; display: none;" class="chosen-udraw" id="udraw_designer_display_orientation">
                                <?php
                                    $direction = array (
                                        'ltr' => 'Left-to-Right',
                                        'rtl' => 'Right-to-Left'
                                    );

                                    foreach ( $direction as $value => $name ) {
                                        $selected = "";
                                        if ($_udraw_settings['udraw_designer_display_orientation'] == $value) {
                                            $selected = "selected";
                                        }
                                        echo "<option class=\"level-0\" value=\"" . $value . "\" ". $selected .">". $name ."</option>";
                                    }    
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('uDraw Global Template Key', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                                <input name="udraw_designer_global_template_key" id="udraw_designer_global_template_key" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_designer_global_template_key']; ?>" class="">
                                <span class="description"><br />
                                    <?php _e('Leave empty to use default uDraw global template resource.', 'udraw') ?>
                                </span>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Display Linked Template Name', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Display Linked Template Name', 'udraw') ?></span></legend>
                                <label for="">
                                <input type="checkbox" name="udraw_designer_display_linked_template_name" value="true" <?php if ($_udraw_settings['udraw_designer_display_linked_template_name']) { echo 'checked="checked"'; } ?> />
                                <?php _e('Names for linked templates will be displayed under its thumbnail in the "Linked Templates" panel.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>                 

                </tbody>
            </table>
        </div>
        <button class="collapsible" data-collapsible="production_settings">
            <span><?php _e('Production Settings', 'udraw'); ?></span>
            <i class="fa fa-plus"></i>
        </button>
        <div class="collapsible_content" data-collapsible="production_settings">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Impose Bleed in PDF', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php 
                                $checked = ($_udraw_settings['designer_impose_bleed']) ? ' checked ' : '';
                            ?>
                            <input type="checkbox" name="designer_impose_bleed" id="designer_impose_bleed" <?php echo $checked ?> >
                            <?php _e('Check to impose bleed in all production PDFs.', 'udraw') ?>
                        </td>
                    </tr>
                    <?php $bleed_adj_display = ($_udraw_settings['designer_impose_bleed']) ? '' : 'display: none;'; ?>
                    <tr valign="top" style="<?php echo $bleed_adj_display ?>" class="designer_bleed_row">
                        <th scope="row" class="titledesc" style="text-align: center;">
                            <label for=""><?php _e('Bleed to Impose', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="number" name="designer_bleed" id="designer_bleed" value="<?php echo $_udraw_settings['designer_bleed'] ?>" min="0" step="any" >
                            <select name="designer_bleed_metric">
                                <option value="mm" <?php if ($_udraw_settings['designer_bleed_metric'] === 'mm') { echo ' selected '; } ?> >mm</option>
                                <option value="cm" <?php if ($_udraw_settings['designer_bleed_metric'] === 'cm') { echo ' selected '; } ?> >cm</option>
                                <option value="in" <?php if ($_udraw_settings['designer_bleed_metric'] === 'in') { echo ' selected '; } ?> >in</option>
                            </select>
                            <span class="description"><br />
                                <?php _e('Bleed to impose, unless already specified in template.', 'udraw') ?>
                            </span>
                        </td>
                    </tr>                    
                    <?php if (uDraw::is_udraw_okay()) { ?>                        
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Generate Production JPG', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Generate Production JPG', 'udraw'); ?></span></legend>
                                <input type="checkbox" name="udraw_generate_jpg_production" value="true" <?php if ($_udraw_settings['udraw_generate_jpg_production']) { echo ' checked '; } ?> />
                                <span class="description"><?php _e('When order is placed, generate a high resolution JPEG for print.', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr> 
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Generate Production PNG', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Generate Production PNG', 'udraw'); ?></span></legend>
                                <input type="checkbox" name="udraw_generate_png_production" value="true" <?php if ($_udraw_settings['udraw_generate_png_production']) { echo ' checked '; } ?> />
                                <span class="description"><?php _e('When order is placed, generate a high resolution PNG for print.', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Design Page Names', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Design Page Names', 'udraw'); ?></span></legend>
                                <input name="udraw_design_page_names" id="udraw_design_page_names" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_design_page_names']; ?>" class=""><br />
                                <span class="description"><?php _e('When production jpg/ png files are downloaded, this will replace page sequence no. in document name.', 'udraw'); ?></span><br />
                                <span class="description"><?php _e('<strong>Example:</strong> front,back', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Production PNG Color Replacement', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Production PNG Color Replacement', 'udraw'); ?></span></legend>
                                <textarea name="udraw_production_png_color_replacement" id="udraw_production_png_color_replacement" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_production_png_color_replacement']; ?>" class=""><?php echo $_udraw_settings['udraw_production_png_color_replacement']; ?></textarea><br />
                                <span class="description"><?php _e('Add one RGB color in each line to be replaced with another RGB color.', 'udraw'); ?></span><br />
                                <span class="description"><?php _e('<strong>Example:</strong> 255,255,255-254,254,254', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>                             
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Debug Production PDF', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Debug Production PDF', 'udraw'); ?></span></legend>
                                <input type="checkbox" name="udraw_debug_pdf_production" value="true" <?php if ($_udraw_settings['udraw_debug_pdf_production']) { echo ' checked '; } ?> />
                                <span class="description"><?php _e('Debug the PDF production process. All PDFs created while this is checked will have a watermark present.', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Production Document Format', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Production Document Format', 'udraw'); ?></span></legend>
                                <input name="udraw_order_document_format" id="udraw_order_document_format" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_order_document_format']; ?>" class=""><br />
                                <span class="description"><?php _e('<strong>Available Variables:</strong><i> %_QUANTITY_% %_ORDER_ID_% %_JOB_ID_% %_ITEM_INDEX_%</i>', 'udraw'); ?></span><br />
                                <span class="description"><?php _e('<strong>Example:</strong><i> %_ORDER_ID_%-%_JOB_ID_%-%_QUANTITY_%</i>', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>                        
                    <?php } ?>                    
                </tbody>
            </table>
        </div>
        <button class="collapsible" data-collapsible="enable_functions">
            <span><?php _e('Enable functions', 'udraw'); ?></span>
            <i class="fa fa-plus"></i>
        </button>
        <div class="collapsible_content" data-collapsible="enable_functions">
            <table class="form-table">
                <tbody>
                    <!--Show only if FB function is enabled-->
                    <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Facebook Photos', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Facebook Photos', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_enable_facebook_photos']) {
                                    ?><input type="checkbox" id="designer_enable_facebook_photos" name="designer_enable_facebook_photos" value="true" checked="checked"/>
                                    <?php
                                } else {
                                    ?><input type="checkbox" id="designer_enable_facebook_photos" name="designer_enable_facebook_photos" value="true" />
                                    <?php
                                }
                                ?>
                                <?php _e('This will enable the ability to upload Facebook Photos for your customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <?php } ?>

                    <!--Show only if Instagram function is enabled-->
                    <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Instagram Photos', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Instagram Photos', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_enable_instagram_photos']) {
                                    ?><input type="checkbox" id="designer_enable_instagram_photos" name="designer_enable_instagram_photos" value="true" checked="checked"/>
                                    <?php
                                } else {
                                    ?><input type="checkbox" id="designer_enable_instagram_photos" name="designer_enable_instagram_photos" value="true" />
                                    <?php
                                }
                                ?>
                                <?php _e('This will enable the ability to upload Instagram Photos for your customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <?php } ?>

                    <!--Show only if Flickr function is enabled-->
                    <?php if ($_udraw_settings['designer_enable_flickr_functions']) { ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Flickr Photos', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Flickr Photos', 'udraw') ?></span></legend>
                                <label>
                                <?php
                                $checked = '';
                                if ($_udraw_settings['designer_enable_flickr_photos']) {
                                    $checked = ' checked="checked" ';
                                }
                                ?>
                                <input type="checkbox" id="designer_enable_flickr_photos" name="designer_enable_flickr_photos" value="true" <?php echo $checked ?>/>
                                <?php _e('This will enable the ability to upload Flickr Photos for your customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <?php } ?>

                    <!--Show only if Google function is enabled-->
                    <!--<?php //if ($_udraw_settings['designer_enable_google_functions']) { ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php //_e('Enable Google Photos', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php //_e('Enable Google Photos', 'udraw') ?></span></legend>
                                <label>
                                <?php
                                /*$checked = '';
                                if ($_udraw_settings['designer_enable_google_photos']) {
                                    $checked = ' checked="checked" ';
                                }*/
                                ?>
                                <input type="checkbox" id="designer_enable_google_photos" name="designer_enable_google_photos" value="true" <?php //echo $checked ?>/>
                                <?php //_e('This will enable the ability to upload Google Photos for your customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <?php //} ?>-->

                    <?php if (uDraw::is_udraw_okay()) { ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Local Clipart', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Local Clipart', 'udraw') ?></span></legend>
                                <label for="designer_enable_local_clipart">
                                <?php
                                if ($_udraw_settings['designer_enable_local_clipart']) {
                                    ?><input type="checkbox" name="designer_enable_local_clipart" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_enable_local_clipart" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will enable the ability to upload a private Clipart Collection for your customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Auto Optimize Hi-Res Images', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Auto Optimize Hi-Res Images', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_enable_optimize_large_images']) {
                                    ?><input type="checkbox" name="designer_enable_optimize_large_images" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_enable_optimize_large_images" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will automatically lower hi-res images on the designer to improve performance while preserving the original image for PDF production.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <?php } ?>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable out of bounds warning', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable out of bounds warning', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_out_of_bounds_warning']) {
                                    ?><input type="checkbox" name="designer_out_of_bounds_warning" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_out_of_bounds_warning" value="true" /> <?php
                                }
                                ?>
                                <?php _e('Display a warning when an element may be outside of the design area.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Minimum DPI Requirement', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Minimum DPI Requirement', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php 
                                $dpi_enabled = false;
                                if ($_udraw_settings['designer_check_dpi']) { $dpi_enabled = true; } ?>
                                <input type="checkbox" name="designer_check_dpi" value="true" <?php if ($dpi_enabled) { ?> checked="checked" <?php } ?> />
                                <?php _e('Images uploaded will need to meet the minimum DPI requirement.', 'udraw') ?></label>
                                <br>
                                <input type="number" min="1" step="1" name="designer_minimum_dpi" value="<?php echo $_udraw_settings['designer_minimum_dpi'] ?>" <?php if (!$dpi_enabled) { ?> style="display: none;" <?php } ?> >
                            </fieldset>
                        </td>
                    </tr>
                    
                    <tr valign="top" class="dpi_enforced" <?php if (!$dpi_enabled) { ?> style="display: none;" <?php } ?> >
                        <th scope="row" class="titledesc"><?php _e('Shrink Image', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Shrink Image', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php 
                                $dpi_enforced = false;
                                if ($_udraw_settings['designer_enforce_dpi_requirement']) { $dpi_enforced = true; } ?>
                                <input type="checkbox" name="designer_enforce_dpi_requirement" value="true" <?php if ($dpi_enforced) { ?> checked="checked" <?php } ?> />
                                <?php _e('Images that do not meet the minimum DPI requirement will be shrunken.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable 3D Box Creator', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable 3D Box Creator', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php 
                                $threed_enabled = false;
                                if ($_udraw_settings['udraw_designer_enable_threed']) { $threed_enabled = true; } ?>
                                <input type="checkbox" name="udraw_designer_enable_threed" value="true" <?php if ($threed_enabled) { ?> checked="checked" <?php } ?> />
                                <?php _e('This will allow the creation of 3D boxes within the uDraw designer.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                </body>
            </table>
        </div>
        <button class="collapsible" data-collapsible="disable_functions">
            <span><?php _e('Disable functions', 'udraw'); ?></span>
            <i class="fa fa-plus"></i>
        </button>
        <div class="collapsible_content" data-collapsible="disable_functions">
            <table class="form-table">
                <tbody>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Global Clipart', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Global Clipart', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_global_clipart']) {
                                    ?><input type="checkbox" name="designer_disable_global_clipart" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_global_clipart" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable the default global Clipart Collection', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable QRCode', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable QRCode', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_qrqode']) {
                                    ?><input type="checkbox" name="designer_disable_qrqode" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_qrqode" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable QRCode on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Shapes', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Shapes', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_shapes']) {
                                    ?><input type="checkbox" name="designer_disable_shapes" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_shapes" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Shapes on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>    

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Image Cropper', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Image Cropper', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_image_cropper']) {
                                    ?><input type="checkbox" name="designer_disable_image_cropper" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_image_cropper" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Image Cropper tool on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Image Replace', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Image Replace', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_image_replace']) {
                                    ?><input type="checkbox" name="designer_disable_image_replace" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_image_replace" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Image Replace tool on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Image Filters', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Image Filters', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_image_filters']) {
                                    ?><input type="checkbox" name="designer_disable_image_filters" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_image_filters" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Image Filter tool on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>  

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Image Fill', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Image Fill', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_image_fill']) {
                                    ?><input type="checkbox" name="designer_disable_image_fill" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_image_fill" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable the ability to use image as an object filling for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>  

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Text Gradient', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Text Gradient', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_text_gradient']) {
                                    ?><input type="checkbox" name="designer_disable_text_gradient" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_text_gradient" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Text Gradient tool on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Disable Ruler', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Disable Text Ruler', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['designer_disable_ruler']) {
                                    ?><input type="checkbox" name="designer_disable_ruler" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="designer_disable_ruler" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable Ruler on designer for all customers.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Site Cleanup -->
        <button class="collapsible" data-collapsible="production_files_cleanup">
            <span><?php _e('Production Files Cleanup', 'udraw'); ?></span>
            <i class="fa fa-plus"></i>
        </button>
        <div class="collapsible_content" data-collapsible="production_files_cleanup">
            <table class="form-table">
                <tbody>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Enable Site Cleanup', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Enable Site Cleanup', 'udraw'); ?></span></legend>
                                <input type="checkbox" name="udraw_production_file_cleanup" value="true" <?php if ($_udraw_settings['udraw_production_file_cleanup']) { echo ' checked '; } ?> />
                                <span class="description"><?php _e('Enabling this will run a scheduled function everyday to cleanup old production and artwork files to free up space.', 'udraw'); ?></span>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row" class="titledesc"><?php _e('Production Files to keep', 'udraw'); ?></th>
                        <td class="forminp">
                            <select name="udraw_production_files_to_keep" style="min-width: 300px;" class="chosen-udraw" id="udraw_production_files_to_keep">
                                <?php
                                    $selected_time_period = array (
                                        '30days' => 'Past 30 Days',
                                        '60days' => 'Past 60 Days',
                                        '90days' => 'Past 90 Days',
                                        'custom' => 'Custom'
                                    );

                                    foreach ( $selected_time_period as $value => $name ) {
                                        $selected = "";
                                        if ($_udraw_settings['udraw_production_files_to_keep'] == $value) {
                                            $selected = "selected";
                                        }
                                        echo "<option class=\"level-0\" value=\"" . $value . "\" ". $selected .">". $name ."</option>";
                                    }
                                ?>                        
                            </select>
                            <span class="description"><br><?php _e('This will keep files for selected period of times.', 'udraw') ?></span>
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Custom Duration in Days', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Custom Duration in Days', 'udraw'); ?></span></legend>
                                <input name="udraw_custom_duration_days" id="udraw_custom_duration_days" type="number" max="90" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_custom_duration_days']; ?>" class=""><br />
                                <span class="description"><?php _e('If custom selected above, define the number of day to keep the production files for. Maximum 90 days.', 'udraw') ?></span>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row" class="titledesc"><?php _e('Run Cleanup Now', 'udraw'); ?></th>
                        <td class="forminp forminp-text">
                            <button class="button-primary run-cleanup-now">Run</button>
                            <span class="description"><br><?php _e('This tool will delete all production and art files older than the set period.', 'udraw') ?></span>
                        </td>
                    </tr>
                    <!--May need in future to generate cleanup reports -->
                    <!--<tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php //_e('Send Cleanup Report to', 'udraw'); ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php //_e('Send Cleanup Report to', 'udraw'); ?></span></legend>
                                <input name="udraw_send_cleanup_report" id="udraw_send_cleanup_report" type="text" style="width: 350px;" value="<?php //echo $_udraw_settings['udraw_send_cleanup_report']; ?>" class=""><br />
                                <span class="description"><?php //_e('Cleanup Report will be sent to this email. If empty, it will be sent to Site Admin.', 'udraw') ?></span>
                            </fieldset>
                        </td>
                    </tr>-->
                </tbody>
            </table>
        </div>
        <table class="form-table">
            <tbody>
                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Custom CSS Hook', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <textarea name="udraw_designer_css_hook" rows="7" cols="100" id="udraw_designer_css_hook" style="display:none;"><?php echo ($_udraw_settings['udraw_designer_css_hook']); ?></textarea>
                            <legend class="screen-reader-text"><span><?php _e('Custom CSS Hook', 'udraw') ?></span></legend>
                            <div id="udraw_designer_css_hook_ace" name="udraw_designer_css_hook" style="position: relative;width: auto;height: 300px;"></div>
                            <span class="description"><br><?php _e('Custom CSS code on designer page.', 'udraw') ?></span>
                        </fieldset>
                    </td>
                </tr>


                <tr valign="top" class="">
                    <th scope="row" class="titledesc"><?php _e('Custom JS Hook', 'udraw') ?></th>
                    <td class="forminp forminp-checkbox">
                        <fieldset>
                            <textarea name="udraw_designer_js_hook" rows="7" cols="100" id="udraw_designer_js_hook" style="display:none;"><?php echo ($_udraw_settings['udraw_designer_js_hook']); ?></textarea>
                            <legend class="screen-reader-text"><span><?php _e('Custom JS Hook', 'udraw') ?></span></legend>
                            <div id="udraw_designer_js_hook_ace" name="udraw_designer_js_hook" style="position: relative;width: auto;height: 300px;"></div>
                            <span class="description"><br><?php _e('Custom JS code on designer page.', 'udraw') ?></span>
                        </fieldset>
                    </td>
                </tr>         
            </tbody>
        </table>
    <?php add_thickbox(); ?>
    <div id="translation_file_editor" style="display: none; width: 100%; height: 100%;">
        <div id="translation_file_editor_body" style="overflow: auto; max-height: 700px; height: 700px;"></div>
        <div id="translation_file_editor_footer">
            <a href="#" class="button button-default" style="float: right;" onclick="save_translation_file_changes();"><?php _e('Save & Close', 'udraw') ?></a>
        </div>
    </div>
        
    <style>
        .collapsible {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }
        .collapsible:hover {
            background-color: #ccc;
        }
            .collapsible span {
                font-weight: bold;
            }
            .collapsible i {
                float: right;
            }
        .collapsible_content {
            max-height: 0;
            background-color: #f1f1f1;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            padding: 15px;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
            $('button.collapsible').on('click', function(){
                var collapsible_name = $(this).attr('data-collapsible');
                var linked_div = $('div.collapsible_content[data-collapsible="' + collapsible_name + '"]');
                //Make slide down effect
                if (linked_div[0].style.maxHeight){
                    linked_div[0].style.maxHeight = null;
                    //Switch to + icon
                    $('i', this).removeClass('fa-minus');
                    $('i', this).addClass('fa-plus');
                } else {
                    linked_div[0].style.maxHeight = linked_div[0].scrollHeight + "px";
                    //Switch to - icon
                    $('i', this).addClass('fa-minus');
                    $('i', this).removeClass('fa-plus');
                }
                return false;
            });

            $('.run-cleanup-now').click(function(){
                $.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                    type: 'POST',
                    data: {
                        'action': 'udraw_cleanup_old_production_files'
                    },
                    success: function (response) {
                        console.log('updated');
                    }
                });
            });
        });
    </script>
    <?php    
}

function udraw_page_settings_html($_udraw_settings) {
    $pages = get_pages();
    ?>    
        <?php
        if ($_udraw_settings['udraw_customer_saved_design_page_id'] == 0 && $_udraw_settings['udraw_private_template_page_id'] == 0) {
            ?><div id="setting-error-settings_updated" class="error settings-error" style="border-left: 4px solid #FFB800; background: #FAFFD5;"><p><strong><?= __('Default pages not created. Would you like to create them now?', 'udraw') ?> </strong><a href="?page=edit_udraw_settings&create_default_pages=t&tab=pages" class="button-primary" style="background: #FFB800; border-color: #B88400; color: #000; margin-left:25px;"><?php _e('Create Pages Now?', 'udraw') ?></a></p></div><?php
        }
        ?>        

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for=""><?php _e('Private Templates', 'udraw') ?></label>
                    </th>
                    <td class="forminp">
                        <select name="udraw_private_template_page_id" data-placeholder="Select a page…" style="min-width: 300px; display: none;" class="chosen-udraw" id="udraw_private_template_page_id">
                            <option value=""> </option>
                            <?php
                                foreach ($pages as $page) {
                                    if ($page->ID == $_udraw_settings['udraw_private_template_page_id']) {
                                        echo "<option class=\"level-0\" value=\"". $page->ID ."\" selected>". $page->post_title ."</option>";
                                    } else {
                                        echo "<option class=\"level-0\" value=\"". $page->ID ."\">". $page->post_title ."</option>";
                                    }                                    
                                }
                            ?>
                        </select>
                        <span class="description"><br><?php _e('Page must contain shortcode [udraw_private_templates] .', 'udraw') ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for=""><?php _e('Customer Saved Designs', 'udraw') ?></label>
                    </th>
                    <td class="forminp">
                        <select name="udraw_customer_saved_design_page_id" data-placeholder="Select a page…" style="min-width: 300px; display: none;" class="chosen-udraw" id="udraw_customer_saved_design_page_id">
                            <option value=""> </option>
                            <?php                                
                                foreach ($pages as $page) {
                                    if ($page->ID == $_udraw_settings['udraw_customer_saved_design_page_id']) {
                                        echo "<option class=\"level-0\" value=\"". $page->ID ."\" selected>". $page->post_title ."</option>";
                                    } else {
                                        echo "<option class=\"level-0\" value=\"". $page->ID ."\">". $page->post_title ."</option>";
                                    }                                    
                                }
                            ?>
                        </select>
                        <span class="description"><br><?php _e('Page must contain shortcode [udraw_customer_saved_designs] .', 'udraw') ?></span>
                    </td>
                </tr>                   
            </tbody>
        </table>    
    <?php
}

function udraw_social_media_settings_html($_udraw_settings) {
    ?>
    <table class="form-table">
        <tbody>
            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Enable Facebook Functions', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Enable Facebook Functions', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        if ($_udraw_settings['designer_enable_facebook_functions']) {
                            ?><input type="checkbox" id="designer_enable_facebook_functions" name="designer_enable_facebook_functions" value="true" checked="checked"/>
                            <?php
                        } else {
                            ?><input type="checkbox" id="designer_enable_facebook_functions" name="designer_enable_facebook_functions" value="true" />
                            <?php
                        }
                        ?>
                        <?php _e('This will enable any Facebook functions we may have for the Designer.', 'udraw') ?></label>
                    </fieldset>
                </td>
            </tr>
            
            <tr valign="top" class="facebook-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Facebook App ID', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Facebook App ID', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        if ($_udraw_settings['designer_enable_facebook_functions']) {
                            ?><input type="text" id="designer_facebook_app_id" name="designer_facebook_app_id" value="<?php echo $_udraw_settings['designer_facebook_app_id'] ?>"/>
                            <?php
                        } else {
                            ?><input type="text" id="designer_facebook_app_id" name="designer_facebook_app_id" value=""/>
                            <?php
                        }
                        ?></label>
                    </fieldset>
                </td>
            </tr>
            
            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Enable Instagram Functions', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Enable Instagram Functions', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        if ($_udraw_settings['designer_enable_instagram_functions']) {
                            ?><input type="checkbox" id="designer_enable_instagram_functions" name="designer_enable_instagram_functions" value="true" checked="checked"/>
                            <?php
                        } else {
                            ?><input type="checkbox" id="designer_enable_instagram_functions" name="designer_enable_instagram_functions" value="true" />
                            <?php
                        }
                        ?>
                        <?php _e('This will enable any Instagram functions we may have for the Designer.', 'udraw') ?></label>
                    </fieldset>
                </td>
            </tr>
            
            <tr valign="top" class="instagram-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Instagram Client ID', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Instagram Client ID', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        if ($_udraw_settings['designer_enable_instagram_functions']) {
                            ?><input type="text" id="designer_instagram_client_id" name="designer_instagram_client_id" value="<?php echo $_udraw_settings['designer_instagram_client_id'] ?>"/>
                            <?php
                        } else {
                            ?><input type="text" id="designer_instagram_client_id" name="designer_instagram_client_id" value=""/>
                            <?php
                        }
                        ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <!--<tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Enable Flickr Functions', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Enable Flickr Functions', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        $checked = '';
                        if ($_udraw_settings['designer_enable_flickr_functions']) {
                            $checked = ' checked="checked" ';
                        }
                        ?>
                        <input type="checkbox" id="designer_enable_flickr_functions" name="designer_enable_flickr_functions" value="true" <?php echo $checked ?>/>
                        <?php _e('This will enable any Flickr functions we may have for the Designer.', 'udraw') ?></label>
                    </fieldset>
                </td>
            </tr>-->
            <!--
            <tr valign="top" class="flickr-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Flickr Client ID', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Flickr Client ID', 'udraw') ?></span></legend>
                        <label>
                            <input type="text" id="designer_flickr_client_id" name="designer_flickr_client_id" value="<?php echo $_udraw_settings['designer_flickr_client_id'] ?>"/>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top" class="flickr-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Flickr Secret ID', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Flickr Secret ID', 'udraw') ?></span></legend>
                        <label>
                            <input type="password" id="designer_flickr_secret_id" name="designer_flickr_secret_id" value="<?php echo $_udraw_settings['designer_flickr_secret_id'] ?>"/>
                        </label>
                    </fieldset>
                </td>
            </tr>-->
            
            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Enable Google Functions', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Enable Google Functions', 'udraw') ?></span></legend>
                        <label for="udraw-proofing">
                        <?php
                        $checked = '';
                        if ($_udraw_settings['designer_enable_google_functions']) {
                            $checked = ' checked="checked" ';
                        }
                        ?>
                        <input type="checkbox" id="designer_enable_google_functions" name="designer_enable_google_functions" value="true" <?php echo $checked ?>/>
                        <?php _e('This will enable any Google functions we may have for the Designer.', 'udraw') ?></label>
                    </fieldset>
                </td>
            </tr>
                  
            <tr valign="top" class="google-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Google API Key', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Google API Key', 'udraw') ?></span></legend>
                        <label>
                            <input type="text" id="designer_google_api_key" name="designer_google_api_key" value="<?php echo $_udraw_settings['designer_google_api_key'] ?>"/>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top" class="google-app-input" style="display: none;">
                <th scope="row" class="titledesc"><?php _e('Google Client ID', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Google Client ID', 'udraw') ?></span></legend>
                        <label>
                            <input type="text" id="designer_google_client_id" name="designer_google_client_id" value="<?php echo $_udraw_settings['designer_google_client_id'] ?>"/>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </tbody>
    </table>
    <div>
        <p class="description" style="font-weight: bold;">
            <?php _e('Instructions:', 'udraw'); ?>
        </p>
        <p class="description">
            <?php _e('Register for applications at developers.facebook.com, www.instagram.com/developer, and console.developers.google.com for Facebook, Instagram, and Google Photos respectively. You will need to login to your account or create one.', 'udraw'); ?>
        </p>
        <br>
        <p class="description" style="font-weight: bold;">
            <?php _e('Facebook: ', 'udraw'); ?>
        </p>
        <p class="description">
            <?php _e('Hover over "My Apps" in the menu bar, and select "Add a New App". Follow the on-screen instructions to create a new app, and then add Facebook Login to your app. Select \'Web\' as the platform, and follow the quickstart instructions (steps to set up SDK, check login status, and add Facebook Login button can be ignored.). Click on the App ID to copy, and enter it in the Facebook App ID field above. Please do not change the Facebook Login settings.', 'udraw'); ?>
        </p>
        <p>
            <img src="<?php echo UDRAW_PLUGIN_URL ?>/assets/facebook.gif" style="max-width: 500px; cursor: pointer;" onclick="javascript: window.open('<?php echo UDRAW_PLUGIN_URL ?>/assets/facebook.gif', '_blank'); " title="<?php _e('Click to open in new tab', 'udraw'); ?>"/> 
        </p>
        <p class="description">
            <?php _e('Note: You will be required to submit your application to the Facebook team for reviewing before the application can be used by the public. You can do so by clicking on \'App Review\' in the sidebar and following the instructions that they provide. Plese be sure to check off the \'user_photos\' checkbox when you do.', 'udraw'); ?>
        </p>
        <p>
            <img src="<?php echo UDRAW_PLUGIN_URL ?>/assets/fb-user_photos.gif" style="max-width: 500px; cursor: pointer;" onclick="javascript: window.open('<?php echo UDRAW_PLUGIN_URL ?>/assets/fb-user_photos.gif', '_blank'); " title="<?php _e('Click to open in new tab', 'udraw'); ?>"/> 
        </p>
        <p class="description">
            <?php _e('Note 2: Facebook may update/change the flow for creating APPs at any time, and therefore the instructions may not be exact. However, it should not be too different.', 'udraw'); ?>
        </p>
        <br>
        <p class="description" style="font-weight: bold;">
            <?php _e('Instagram: ', 'udraw'); ?>
        </p>
        <p class="description">
            <?php _e('Click on "Manage Clients" in the top menu bar, and then "Register a New Client". Follow the instructions on screen. Please register your redirect uri as [baseURL]/wp-admin/admin-ajax.php, and uncheck the \'Disable implicit OAuth\' checkbox under the Security tab. You will be required to submit your application to Instagram for reviewing before it can Go Live for public usage. Once your application have been made, the Client ID will be shown. Please copy this and enter in the Instagram Client ID field above.', 'udraw'); ?>
        </p>
        <p>
            <img src="<?php echo UDRAW_PLUGIN_URL ?>/assets/instagram.gif" style="max-width: 500px; cursor: pointer;" onclick="javascript: window.open('<?php echo UDRAW_PLUGIN_URL ?>/assets/instagram.gif', '_blank'); " title="<?php _e('Click to open in new tab', 'udraw'); ?>"/> 
        </p>
        <br>
        <p class="description" style="font-weight: bold;">
            <?php _e('Google Photos: ', 'udraw'); ?>
        </p>
        <p class="description">
            <?php _e('You will need to create and name a new project. After the project\'s been created, click on the "Credentials" tab and create an API key as well as OAuth client ID. You will need to set up the OAuth consent screen before you can create an OAuth Client ID. Please follow the instructions on the screen, and register your authorized redirect uri as [baseURL]/wp-admin/admin-ajax.php. After these keys have been created, please copy and paste them in the correct places on this page.', 'udraw'); ?>
            <?php _e('The Google Photos API will need to be enabled. Please click on "Enable APIs and Services" under Google API dashboard, and search for Photos. Please enable it once located.'); ?>
        </p>
        <p>
            <img src="<?php echo UDRAW_PLUGIN_URL ?>/assets/google-api.gif" style="max-width: 500px; cursor: pointer;" onclick="javascript: window.open('<?php echo UDRAW_PLUGIN_URL ?>/assets/google-api.gif', '_blank'); " title="<?php _e('Click to open in new tab', 'udraw'); ?>"/> 
        </p>
    </div>
    <?php
}

function udraw_price_matrix_settings_html ($_udraw_settings) {
    $is_default = ($_udraw_settings['udraw_price_matrix_placement'] === '' || !isset($_udraw_settings['udraw_price_matrix_placement'])) ? ' checked="checked" ' : '';
    $is_custom = ($_udraw_settings['udraw_price_matrix_placement'] !== '') ? ' checked="checked" ' : '';
    ?>
    <table class="form-table">
        <tbody>
        <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('Allowed File Type(s)', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-checkbox">
                    <select name="goprint2_file_upload_types[]" id="goprint2_file_upload_types"  multiple="multiple">
                        <?php
                        $allowedExt = array (
                            'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'psd' => 'application/octet-stream', 'pdf' => 'application/pdf',
                            'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ai' => 'application/postscript', 'cdr' => 'application/octet-stream', 'eps' => 'application/postscript', 'ps' => 'application/postscript',
                            'indd' => 'application/octet-stream', 'doc|docx' => 'application/msword', 'xls|xlsx' => 'application/excel', 'ppt|pptx' => 'application/mspowerpoint',
                            'obj' => 'application/octet-stream', 'zip' => 'applicaiton/octet-stream'
                        );
                        $validExt = $_udraw_settings['goprint2_file_upload_types'];
                        if (!is_array($validExt)) {
                            // Set Default Extensions.
                            $validExt = $allowedExt;
                        }
                        
                        foreach ($allowedExt as $aKey => $aValue) {
                            $found_selected = false;
                            foreach ($validExt as $vKey => $vValue) {
                                if ($vKey == $aKey) { $found_selected = true;  break;}
                            }
                            if ($found_selected) {
                                echo '<option value="'. $aKey .':'.$aValue.'" selected>'. $aKey .'</option>';
                            } else {
                                echo '<option value="'. $aKey .':'.$aValue.'">'. $aKey .'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>                        
            </tr>                                  
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('Minimum File Upload DPI', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goprint2_file_upload_min_dpi" id="goprint2_file_upload_min_dpi" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['goprint2_file_upload_min_dpi']; ?>" class="">
                    <span class="description"><br><?php _e('This feature is for Price Matrix uploads with mode set as "area" or if "FinalDocWidth and FinalDocHeight" is defined as custom settings with the preset sizes.', 'udraw') ?></span>
                </td>
            </tr>
            <tr><td colspan="2"><hr/></td></tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Placement', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Placement (if Display Options First)', 'udraw') ?></span></legend>
                        <form action="">
                            <input type="radio" name="price_matrix_placement" value="default" <?php echo $is_default ?>><?php _e('Default (in Product Summary)', 'udraw') ?></br>
                            <input type="radio" name="price_matrix_placement" value="custom" <?php echo $is_custom ?> ><?php _e('Custom Placement (please specify)', 'udraw') ?>&nbsp;<input type="text" value="<?php echo $_udraw_settings['udraw_price_matrix_placement'] ?>" name="price_matrix_placement_input">
                        </form>
                    </fieldset>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('Price Matrix Settings Placement', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="price_matrix_settings_placement" id="price_matrix_settings_placement" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['udraw_price_matrix_settings_placement']; ?>" class="">
                    <span class="description"><br><?php _e('This feature is to change placement of quantity/ settings to "top" or "bottom".', 'udraw') ?></span>
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Custom CSS', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <textarea name="udraw_price_matrix_css_hook" id="udraw_price_matrix_css_hook" rows="7" cols="100" style="display:none;"><?php echo $_udraw_settings['udraw_price_matrix_css_hook']; ?></textarea>
                        <legend class="screen-reader-text"><span><?php _e('Custom CSS Hook', 'udraw') ?></span></legend>
                        <div id="udraw_price_matrix_css_hook_ace" style="position: relative;width: auto;height: 300px;"></div>
                    </fieldset>
                </td>
            </tr>
            
            <tr valign="top" class="">
                <th scope="row" class="titledesc"><?php _e('Custom JS', 'udraw') ?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <textarea name="udraw_price_matrix_js_hook" id="udraw_price_matrix_js_hook" rows="7" cols="100" style="display:none;"><?php echo $_udraw_settings['udraw_price_matrix_js_hook']; ?></textarea>
                        <legend class="screen-reader-text"><span><?php _e('Custom JS Hook', 'udraw') ?></span></legend>
                        <div id="udraw_price_matrix_js_hook_ace" style="position: relative;width: auto;height: 300px;"></div>
                    </fieldset>
                </td>
            </tr>
            
        </tbody>
    </table>
    <?php
}

function udraw_gosendex_settings_html($_udraw_settings) {
    ?>
    <table class="form-table">
        <tbody>   
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoSendEx API Key', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="gosendex_api_key" id="gosendex_api_key" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['gosendex_api_key']; ?>" class="">
                </td>
            </tr>
            <?php
                if (strlen($_udraw_settings['gosendex_api_key']) > 1) {
                    ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('File Submission', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('File Submission', 'udraw') ?></span></legend>
                                <label for="gosendex_send_file_after_order">
                                <?php if ($_udraw_settings['gosendex_send_file_after_order']) { ?>
                                    <input type="checkbox" name="gosendex_send_file_after_order" value="true" checked="checked"/> <?php
                                } else { ?>
                                    <input type="checkbox" name="gosendex_send_file_after_order" value="false" /> <?php
                                } ?>
                                <?php _e('Submit all orders to GoSendEx System.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Send Email after File Submission', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Send Email after File Submission', 'udraw') ?></span></legend>
                                <label for="gosendex_send_email_after_order_sent">
                                <?php error_log(print_r($_udraw_settings['gosendex_send_email_after_order_sent'], true)); if ($_udraw_settings['gosendex_send_email_after_order_sent']) { ?>
                                    <input type="checkbox" name="gosendex_send_email_after_order_sent" value="true" checked="checked"/> <?php
                                } else { ?> 
                                    <input type="checkbox" name="gosendex_send_email_after_order_sent" value="false" /> <?php
                                } ?>
                                <?php _e('If enabled will send an email with file download link.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('GoSendEx Domain', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input name="gosendex_domain" id="gosendex_domain" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['gosendex_domain']; ?>" class="">
                            <br/><span class="description">Your gosendex.com without https. <strong>Example:</strong> domain.gosendex.com</span>
                        </td>
                    </tr>  
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('GoSendEx Notification Email', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input name="gosendex_email_to_send_notification" id="gosendex_email_to_send_notification" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['gosendex_email_to_send_notification']; ?>" class="">
                            <br/><span class="description">An email notification will be sent to this email with Download Files link after order has been sent to GoSendEx.</span>
                        </td>
                    </tr>                    
                    <?php
                }
            ?>
        </tbody>
    </table>

    <?php
}

function udraw_goprint2_settings_html($_udraw_settings) {
    ?>
    <table class="form-table">
        <tbody>   
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoPrint2 API Key', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goprint2_api_key" id="goprint2_api_key" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['goprint2_api_key']; ?>" class="">
                </td>
            </tr>
            <?php
                if (strlen($_udraw_settings['goprint2_api_key']) > 1) {
                    ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('File Submission', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('File Submission', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['goprint2_send_file_after_order']) {
                                    ?><input type="checkbox" name="goprint2_send_file_after_order" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="goprint2_send_file_after_order" value="true" /> <?php
                                }
                                ?>
                                <?php _e('Submit all orders to GoPrint2 System.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr>                    
                    <?php
                }
            ?>
        </tbody>
    </table>

    <?php
}

function udraw_activation_settings_html($_udraw_settings) {
    $uDraw = new uDraw();
    if ( uDraw::is_udraw_okay() ) {
    ?>
    <div class="error settings-error" role="alert" style="width: 98%; border-left: 4px solid #00FF34; background: #D5FFD8; height: 34px; font-size: larger; padding-top: 15px;"><strong>Thank you!</strong> Your product is successfully activated.</div>
    <?php } ?>
    <table class="form-table">
        <tbody>   
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('uDraw Activation Key', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="udraw_activation_key" id="udraw_activation_key" type="text" style="width: 450px;" value="<?php echo uDraw::get_udraw_activation_key() ?>" class="">
                    <span class="description"><br><?php _e('<a href="https://draw.racadtech.com/payment/Pricing.aspx?param=uDraw-wp" target="_BLANK">Click here</a> to purchase your uDraw activation key', 'udraw') ?></span>
                </td>
            </tr>
        </tbody>
    </table>

    <?php 
}

function udraw_goepower_settings_html($_udraw_settings) {
    $goepower = new GoEpower(); 
    $udrawSettings = new uDrawSettings();
    ?>
    <table class="form-table">
        <tbody> 
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoEpower Username', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goepower_username" id="goepower_username" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['goepower_username']; ?>" class="">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoEpower Password', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goepower_password" id="goepower_password" type="password" style="width: 350px;" value="<?php echo $_udraw_settings['goepower_password']; ?>" class="">
                </td>
            </tr>                          
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoEpower API Key', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goepower_api_key" id="goepower_api_key" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['goepower_api_key']; ?>" class="">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for=""><?php _e('GoEpower Producer ID', 'udraw') ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input name="goepower_producer_id" id="goepower_producer_id" type="text" style="width: 50px;" value="<?php echo $_udraw_settings['goepower_producer_id']; ?>" class="">
                </td>
            </tr>            
            <?php
                if (strlen($_udraw_settings['goepower_api_key']) > 1) {
                    $goepower_companies = $goepower->get_producer_companies($_udraw_settings['goepower_api_key'], $_udraw_settings['goepower_producer_id']);
                    $_goepower_companies = $goepower_companies;
                    if (!is_array($goepower_companies)) {
                        $_goepower_companies = array();
                        array_push($_goepower_companies, $goepower_companies);
                    }
                    ?>
            
                    <tr>
                        <td colspan="2">
                            <h1>General Options</h1>
                            <hr />
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Order Submission', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Order Submission', 'udraw') ?></span></legend>
                                <label for="goepower_send_file_after_order">
                                <?php
                                if ($_udraw_settings['goepower_send_file_after_order']) {
                                    ?><input type="checkbox" name="goepower_send_file_after_order" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="goepower_send_file_after_order" value="true" /> <?php
                                }
                                ?>
                                <?php _e('Submit all orders to GoEpower System.', 'udraw') ?></label>
                            </fieldset>
                        </td>
                    </tr> 

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Submit On Status', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                                <?php
                                $goepower_submit_on_status = $_udraw_settings['goepower_submit_on_status'];
                                if (strlen($goepower_submit_on_status) == 0) {
                                    $goepower_submit_on_status = "submitted";
                                }
                                
                                ?>
                                <select name="goepower_submit_on_status" data-placeholder="Select a status…" style="min-width: 300px; display: none;" class="chosen-udraw" id="goepower_submit_on_status">
                                    <option value="submitted" <?php if ($goepower_submit_on_status == "submitted") { echo " selected "; } ?> >Submitted</option>
                                    <option value="paid" <?php if ($goepower_submit_on_status == "paid") { echo " selected "; } ?> >Paid</option>
                                </select>
                                <span class="description"><br><?php _e('Submit order to GoEpower on certain status. <i>Default when submitted.</i>', 'udraw') ?></span>
                        </td>
                    </tr> 
            
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('GoEpower Company ID', 'udraw') ?></label>
                        </th>
                        <td class="forminp">
                            <select name="goepower_company_id" data-placeholder="Select a company…" style="min-width: 300px; display: none;" class="chosen-udraw" id="goepower_company_id">
                                <option value=""> </option>
                            <?php                                
                                foreach ($_goepower_companies as $_company) {
                                    if ($_company->CompanyID == $_udraw_settings['goepower_company_id']) {
                                        echo "<option class=\"level-0\" value=\"". $_company->CompanyID ."\" selected>". $_company->DisplayName ."</option>";
                                    } else {
                                        echo "<option class=\"level-0\" value=\"". $_company->CompanyID ."\">". $_company->DisplayName ."</option>";
                                    }
                                }
                            ?>                           
                            </select>
                            <span class="description"><br><?php _e('Company where all jobs will be submitted to.', 'udraw') ?></span>
                        </td>
                    </tr>                         
                      
            
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Additional Notification Email', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input name="goepower_additional_notify_email" id="goepower_additional_notify_email" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['goepower_additional_notify_email']; ?>" class="">
                            <span class="description"><br><?php _e('A copy of all orders will also be sent to the above email address.', 'udraw') ?></span>
                        </td>
                    </tr>
            
                    <tr>
                        <td colspan="2">
                            <h1>PDF Options</h1>
                            <hr />
                        </td>
                    </tr>                         
            
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('PDF Preview Mode', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <?php
                            $goepower_preview_mode = $_udraw_settings['goepower_preview_mode'];
                            if (strlen($goepower_preview_mode) == 0) {
                                if ($goepower_preview_mode == "" || (isset($_udraw_settings['goepower_pdf_previwe_as_image']) && $_udraw_settings['goepower_pdf_previwe_as_image'])) {
                                    $goepower_preview_mode = "image"; // default.
                                } else if (!$_udraw_settings['goepower_pdf_previwe_as_image']) {
                                    $goepower_preview_mode = "pdf";
                                }
                            }
                            ?>
                            <select name="goepower_preview_mode" style="min-width: 300px; display: none;" class="chosen-udraw" id="goepower_preview_mode">
                                <option value="image" <?php if ($goepower_preview_mode == "image") { echo " selected "; } ?> >Image (Quicker Preview)</option>
                                <option value="pdf" <?php if ($goepower_preview_mode == "pdf") { echo " selected "; } ?> >PDF</option>
                            </select>
                        </td>                        
                    </tr>            

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Approve Button Location', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <?php
                            $goepower_approve_button_placement = $_udraw_settings['goepower_approve_button_placement'];
                            
                            if (strlen($goepower_approve_button_placement) == 0) {
                                if ($goepower_approve_button_placement == "" || !$_udraw_settings['goepower_pdf_approve_btn_below_preview']) {
                                    $goepower_approve_button_placement = "top"; // default.
                                } else if ($_udraw_settings['goepower_pdf_approve_btn_below_preview']) {
                                    $goepower_approve_button_placement = "bottom";
                                }
                            }

                            ?>
                            <select name="goepower_approve_button_placement" style="min-width: 300px; display: none;" class="chosen-udraw" id="goepower_approve_button_placement">
                                <option value="top" <?php if ($goepower_approve_button_placement == "top") { echo " selected "; } ?> >Top</option>
                                <option value="bottom" <?php if ($goepower_approve_button_placement == "bottom") { echo " selected "; } ?> >Bottom</option>
                                <option value="both" <?php if ($goepower_approve_button_placement == "both") { echo " selected "; } ?> >Both</option>
                            </select>
                        </td>                        
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Designer Location', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <?php
                            $goepower_designer_location = $_udraw_settings['goepower_designer_location'];
                            
                            ?>
                            <select name="goepower_designer_location" style="min-width: 300px; display: none;" class="chosen-udraw" id="goepower_designer_location">
                                <option value="embedded" <?php if ($goepower_designer_location === 'embedded' || $goepower_designer_location === '') { echo " selected "; } ?> ><?php _e('Embedded in page', 'udraw'); ?></option>
                                <option value="popup" <?php if ($goepower_designer_location === 'popup') { echo " selected "; } ?> ><?php _e('Pop-up', 'udraw'); ?></option>
                                <option value="onepageh" <?php if ($goepower_designer_location === 'onepageh') { echo " selected "; } ?> ><?php _e('One Page (Price Matrix + Designer) - Horizontal Display', 'udraw'); ?></option>
                                <option value="onepagev" <?php if ($goepower_designer_location === 'onepagev') { echo " selected "; } ?> ><?php _e('One Page (Price Matrix + Designer) - Vertical Display', 'udraw'); ?></option>
                            </select>
                        </td>                        
                    </tr>

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Auto Update Block Preview', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Order Submission', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['goepower_pdf_preview_auto_update']) {
                                    ?><input type="checkbox" name="goepower_pdf_preview_auto_update" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="goepower_pdf_preview_auto_update" value="true" /> <?php
                                }
                                ?>
                                <?php _e('Auto update PDF Block preview whenever a field is updated by the customer.', 'udraw') ?></label>
                            </fieldset>
                        </td> 
                    </tr>  

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Hide Label On Text Inputs', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Order Submission', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['goepower_hide_labels_on_text_input']) {
                                    ?><input type="checkbox" name="goepower_hide_labels_on_text_input" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="goepower_hide_labels_on_text_input" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will hide the labels on all text inputs on the frontend.', 'udraw') ?></label>
                            </fieldset>
                        </td>                        
                    </tr> 

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Hide Refresh Button', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Order Submission', 'udraw') ?></span></legend>
                                <label for="udraw-proofing">
                                <?php
                                if ($_udraw_settings['goepower_pdf_disable_refresh_button']) {
                                    ?><input type="checkbox" name="goepower_pdf_disable_refresh_button" value="true" checked="checked"/> <?php
                                } else {
                                    ?><input type="checkbox" name="goepower_pdf_disable_refresh_button" value="true" /> <?php
                                }
                                ?>
                                <?php _e('This will disable the refresh preview button while viewing PDF products.', 'udraw') ?></label>
                            </fieldset>
                        </td>                        
                    </tr> 
                    
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Replace Loading Animation', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input name="loading_animation_link" id="loading_animation_link" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['loading_animation_link']; ?>" class="">
                            <span class="description"><br><?php _e('Link to a loading animation. Accepted format: .gif', 'udraw') ?></span>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""><?php _e('Approve Proof Disclaimer', 'udraw') ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <textarea name="approve_proof_text" id="approve_proof_text" type="text" style="width: 350px;" value="<?php echo $_udraw_settings['approve_proof_text']; ?>" class=""><?php echo $_udraw_settings['approve_proof_text']; ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <h1>Custom Hooks</h1>
                            <hr />
                        </td>
                    </tr>

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Custom HTML Hook', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <textarea name="udraw_pdf_template_html_hook" id="udraw_pdf_template_html_hook" rows="7" cols="100" style="display:none;"><?php echo ($_udraw_settings['udraw_pdf_template_html_hook']); ?></textarea>
                                <legend class="screen-reader-text"><span><?php _e('Custom HTML Hook', 'udraw') ?></span></legend>
                                <div id="udraw_pdf_template_html_hook_ace" name="udraw_pdf_template_html_hook" style="position: relative;width: auto;height: 300px;"></div>
                                <span class="description"><br><?php _e('Custom HTML code on product page.', 'udraw') ?></span>
                            </fieldset>
                        </td>
                    </tr>  

                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Custom CSS Hook', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <textarea name="udraw_pdf_template_css_hook" id="udraw_pdf_template_css_hook" rows="7" cols="100" style="display:none;"><?php echo ($_udraw_settings['udraw_pdf_template_css_hook']); ?></textarea>
                                <legend class="screen-reader-text"><span><?php _e('Custom CSS Hook', 'udraw') ?></span></legend>
                                <div id="udraw_pdf_template_css_hook_ace" name="udraw_pdf_template_css_hook" style="position: relative;width: auto;height: 300px;"></div>
                                <span class="description"><br><?php _e('Custom CSS code on product page.', 'udraw') ?></span>
                            </fieldset>
                        </td>
                    </tr>
                     
                
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Custom JS Hook', 'udraw') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <textarea name="udraw_pdf_template_js_hook" id="udraw_pdf_template_js_hook" rows="7" cols="100" style="display:none;"><?php echo ($_udraw_settings['udraw_pdf_template_js_hook']); ?></textarea>
                                <legend class="screen-reader-text"><span><?php _e('Custom JS Hook', 'udraw') ?></span></legend>
                                <div id="udraw_pdf_template_js_hook_ace" name="udraw_pdf_template_js_hook" style="position: relative;width: auto;height: 300px;"></div>
                                <span class="description"><br><?php _e('Custom JS code on product page.', 'udraw') ?></span>
                            </fieldset>
                        </td>
                    </tr>  
                                    
                    <?php
                }
            ?>
        </tbody>
    </table>

    <?php
} ?>
    <script>
        var udraw_designer_css_editor, udraw_designer_js_editor, udraw_pdf_template_js_editor, udraw_pdf_template_html_editor, udraw_pdf_template_css_editor, udraw_general_css_editor, udraw_general_js_editor;
        //if (jQuery("#udraw_designer_language").val() == 'en') { jQuery('#udraw_language_edit_btn').addClass('disabled').removeAttr('href'); }
        jQuery(document).ready(function ($) {
            jQuery('#goprint2_file_upload_types').dropdownchecklist(
                {
                    width: 350,
                    textFormatFunction: function(options) {
                        var selectedOptions = options.filter(":selected");
                        var countOfSelected = selectedOptions.size();
                        switch(countOfSelected) {
                            case 0: return "Please Select ....";
                            default: return "View File Upload Type(s)";
                        }
                    }
                });
            jQuery(".chosen-udraw").chosen({allow_single_deselect:"true",width:"350px",disable_search_threshold:5});
            jQuery("#udraw_designer_language").chosen().change(function(){
                if (jQuery(this).val() == 'en') {
                    jQuery('#udraw_language_edit_btn').addClass('disabled').removeAttr('href');
                } else {
                    jQuery('#udraw_language_edit_btn').removeClass('disabled').attr('href', '#');
                }
            });
            jQuery("#udraw_language_text_input").chosen().change(function(){
                var language = jQuery(this).val();
                jQuery('#udraw_language_text_input_display').val(languageObject[language].nativeName);
            });
            if ($('#designer_enable_facebook_functions').prop('checked')) {
                $('.facebook-app-input').show();
            }
            $('#designer_enable_facebook_functions').click(function(){
                if ($('.facebook-app-input').is(':visible')) {
                    $('.facebook-app-input').hide();
                } else {
                    $('.facebook-app-input').show();
                }
            });
            if ($('#designer_enable_instagram_functions').prop('checked')) {
                $('.instagram-app-input').show();
            }
            $('#designer_enable_instagram_functions').click(function(){
                if ($('.instagram-app-input').is(':visible')) {
                    $('.instagram-app-input').hide();
                } else {
                    $('.instagram-app-input').show();
                }
            });
            if ($('#designer_enable_flickr_functions').prop('checked')) {
                $('.flickr-app-input').show();
            }
            $('#designer_enable_flickr_functions').click(function(){
                if ($('.flickr-app-input').is(':visible')) {
                    $('.flickr-app-input').hide();
                } else {
                    $('.flickr-app-input').show();
                }
            });
            if ($('#designer_enable_google_functions').prop('checked')) {
                $('.google-app-input').show();
            }
            $('#designer_enable_google_functions').click(function(){
                if ($('.google-app-input').is(':visible')) {
                    $('.google-app-input').hide();
                } else {
                    $('.google-app-input').show();
                }
            });
            $('#designer_impose_bleed').on('change click', function(){
                var row = $('.designer_bleed_row');
                if ($(this).prop('checked')) {
                    row.show();
                } else {
                    row.hide();
                }
            });
            <?php if ($_udraw_settings['show_customer_preview_before_adding_to_cart']) { ?>
                    $('.designer_exclude_bleed').show();
            <?php } ?>
            
            $('[name="show_customer_preview_before_adding_to_cart"]').click(function(){
               if ($('.designer_exclude_bleed').is(':visible')) {
                   $('.designer_exclude_bleed').hide();
                   $('[name="designer_exclude_bleed"]').prop('checked', false);
               } else {
                   $('.designer_exclude_bleed').show();
               }
            });
            
            $.urlParam = function (name) {
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results == null) {
                    return null;
                } else {
                    return results[1] || 0;
                }
            }
            
            var tab = decodeURIComponent($.urlParam('tab'));
                    
            if (tab != null && tab == "designer-ui") {
                udraw_designer_css_editor = ace.edit("udraw_designer_css_hook_ace");
                udraw_designer_css_editor.setTheme("ace/theme/chrome");
                udraw_designer_css_editor.getSession().setMode("ace/mode/css");
                udraw_designer_css_editor.getSession().setValue(jQuery('#udraw_designer_css_hook').val());
                udraw_designer_css_editor.resize();

                udraw_designer_js_editor = ace.edit("udraw_designer_js_hook_ace");
                udraw_designer_js_editor.setTheme("ace/theme/chrome");
                udraw_designer_js_editor.getSession().setMode("ace/mode/javascript");
                udraw_designer_js_editor.getSession().setValue(jQuery('#udraw_designer_js_hook').val());
                udraw_designer_js_editor.resize();
                
                build_language_code_dropdown();
            }

            if (tab != null && tab == "goepower") {
                udraw_pdf_template_html_editor = ace.edit("udraw_pdf_template_html_hook_ace");
                udraw_pdf_template_html_editor.setTheme("ace/theme/chrome");
                udraw_pdf_template_html_editor.getSession().setMode("ace/mode/html");
                udraw_pdf_template_html_editor.getSession().setValue(jQuery('#udraw_pdf_template_html_hook').val());
                udraw_pdf_template_html_editor.resize();

                udraw_pdf_template_css_editor = ace.edit("udraw_pdf_template_css_hook_ace");
                udraw_pdf_template_css_editor.setTheme("ace/theme/chrome");
                udraw_pdf_template_css_editor.getSession().setMode("ace/mode/css");
                udraw_pdf_template_css_editor.getSession().setValue(jQuery('#udraw_pdf_template_css_hook').val());
                udraw_pdf_template_css_editor.resize();

                udraw_pdf_template_js_editor = ace.edit("udraw_pdf_template_js_hook_ace");
                udraw_pdf_template_js_editor.setTheme("ace/theme/chrome");
                udraw_pdf_template_js_editor.getSession().setMode("ace/mode/javascript");
                udraw_pdf_template_js_editor.getSession().setValue(jQuery('#udraw_pdf_template_js_hook').val());
                udraw_pdf_template_js_editor.resize();
            }
            if (tab != null && tab == "price_matrix") {
                price_matrix_css_editor = ace.edit("udraw_price_matrix_css_hook_ace");
                price_matrix_css_editor.setTheme("ace/theme/chrome");
                price_matrix_css_editor.getSession().setMode("ace/mode/css");
                price_matrix_css_editor.getSession().setValue(jQuery('#udraw_price_matrix_css_hook').val());
                price_matrix_css_editor.resize();
                
                price_matrix_js_editor = ace.edit("udraw_price_matrix_js_hook_ace");
                price_matrix_js_editor.setTheme("ace/theme/chrome");
                price_matrix_js_editor.getSession().setMode("ace/mode/javascript");
                price_matrix_js_editor.getSession().setValue(jQuery('#udraw_price_matrix_js_hook').val());
                price_matrix_js_editor.resize();
            }
            if (tab == "null" || tab == "general") {
                udraw_general_css_editor = ace.edit("udraw_general_css_hook_ace");
                udraw_general_css_editor.setTheme("ace/theme/chrome");
                udraw_general_css_editor.getSession().setMode("ace/mode/css");
                udraw_general_css_editor.getSession().setValue(jQuery('#udraw_general_css_hook').val());
                udraw_general_css_editor.resize();

                udraw_general_js_editor = ace.edit("udraw_general_js_hook_ace");
                udraw_general_js_editor.setTheme("ace/theme/chrome");
                udraw_general_js_editor.getSession().setMode("ace/mode/javascript");
                udraw_general_js_editor.getSession().setValue(jQuery('#udraw_general_js_hook').val());
                udraw_general_js_editor.resize();
            }

            jQuery('#udraw_settings_form').submit(function () {
                if (tab != null && tab == "designer-ui") {
                    jQuery('#udraw_designer_css_hook').val(udraw_designer_css_editor.getValue());
                    jQuery('#udraw_designer_js_hook').val(udraw_designer_js_editor.getValue());
                }
                if (tab != null && tab == "goepower") {
                    jQuery('#udraw_pdf_template_css_hook').val(udraw_pdf_template_css_editor.getValue());
                    jQuery('#udraw_pdf_template_js_hook').val(udraw_pdf_template_js_editor.getValue());
                    jQuery('#udraw_pdf_template_html_hook').val(udraw_pdf_template_html_editor.getValue());
                }
                if (tab != null && tab == "price_matrix") {
                    jQuery('#udraw_price_matrix_css_hook').val(price_matrix_css_editor.getValue());
                    jQuery('#udraw_price_matrix_js_hook').val(price_matrix_js_editor.getValue());
                }
                if (tab == "general" || tab == "null") {
                    jQuery('#udraw_general_css_hook').val(udraw_general_css_editor.getValue());
                    jQuery('#udraw_general_js_hook').val(udraw_general_js_editor.getValue());
                }
            });
                        
            jQuery('#udraw_generate_language_file').click(function(){
                jQuery('#udraw_generate_language_file span').text('Please wait...');
                jQuery('#udraw_generate_language_file').addClass('disabled').removeAttr('href');
                jQuery('#udraw_language_text_input').css('border', '1px solid grey','box-shadow','none');
                var languageCode = jQuery('#udraw_language_text_input').val();
                var languageText = jQuery('#udraw_language_text_input_display').val().replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\\|\||\]|\}|\[|\{|\?|\/|\.|\>|\,|\<|\;|\:|\"/gi, '');
                jQuery('#udraw_language_text_input_display').val(languageText);
                if (languageCode.length <= 0) { 
                    jQuery('#udraw_language_text_input').css('border', '2px solid red', 'box-shadow', 'rgba(255, 0, 0,0.5) 0 0 10px');
                    jQuery('#udraw_generate_language_file span').text('Generate');
                    jQuery('#udraw_generate_language_file').removeClass('disabled').attr('href', '#');
                    return; 
                }
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                    data: {
                        action: 'udraw_designer_create_translation_file',
                        language: languageCode,
                        display_name: languageText
                    },
                    dataType: 'json',
                    success: function(response) {
                        jQuery('#udraw_generate_language_file span').text('Generate');
                        jQuery('#udraw_generate_language_file').removeClass('disabled').attr('href', '#');
                        if (response) {
                            jQuery('#udraw_designer_language').append('<option class="level-0" value="'+languageCode+'" selected>'+languageText+'</option>').trigger("chosen:updated");
                            jQuery('#udraw_language_text_input option[value="'+languageCode+'"]').remove();
                            jQuery('#udraw_language_text_input').trigger('chosen:updated');
                            jQuery('#udraw_language_text_input_display').val('');
                            if (languageCode != 'en') { jQuery('#udraw_language_edit_btn').removeClass('disabled').attr('href', '#'); }
                        } else {
                            window.alert('The file already exists or the language code was invalid.');
                        }
                    },
                    error: function (error) {
                        window.alert('An error has occured.');
                        jQuery('#udraw_generate_language_file span').text('Generate');
                        jQuery('#udraw_generate_language_file').removeClass('disabled').attr('href', '#');
                    }
                });
            });
            jQuery('[name="designer_check_dpi"]').on('change', function(){
                if (jQuery(this).prop('checked')) {
                    jQuery('[name="designer_minimum_dpi"]').show();
                    jQuery('.dpi_enforced').show();
                } else {
                    jQuery('[name="designer_minimum_dpi"]').hide();
                    jQuery('.dpi_enforced').hide();
                }
            });
        });
        function edit_translation_file () {
            //if (jQuery('#udraw_designer_language').val() == 'en') { window.alert('This file is not available for custom editing.'); return false; }
            window.tb_show();
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                data: {
                    action: 'udraw_designer_retrieve_translation_file_contents',
                    language: jQuery('#udraw_designer_language').val(),
                },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        var sourceFile = response;
                        jQuery.ajax({
                            type: 'POST',
                            url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                            data: {
                                action: 'udraw_designer_retrieve_translation_file_contents',
                                language: 'en',
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response) {
                                    var baseFile = response;
                                    build_translation_file_editor(sourceFile, baseFile);
                                } else {
                                    window.alert('There was an issue reading the master file.');
                                    window.tb_remove();
                                }
                            },
                            error: function (error) {
                                window.alert('There was an issue reading the master file.');
                                window.tb_remove();
                            }
                        });
                    } else {
                        window.alert('There was an issue reading the source file.');
                        window.tb_remove();
                    }
                },
                error: function (error) {
                    window.alert('There was an issue reading the source file.');
                    window.tb_remove();
                }
            });
        }
        function update_translation_file () {
            jQuery('#udraw_language_update_btn').text('Please wait...');
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                data: {
                    action: 'udraw_designer_update_translation_files'
                },
                dataType: 'json',
                success: function(response) {
                    if (response == false) {
                        window.alert('An error had occured.');
                    }
                    jQuery('#udraw_language_update_btn').text('Check for updates');
                },
                error: function (error) {
                    window.alert('An error had occured.');
                    jQuery('#udraw_language_update_btn').text('Check for updates');
                }
            });
        }
        
        function build_translation_file_editor (sourceFile, baseFile) {
            window.baseFile = baseFile;
            //Clear the body first
            jQuery('#translation_file_editor_body').empty();
            jQuery('#translation_file_editor_body').append('<div id="translation_file_accordion"></div>');
            for (var category in baseFile) {
                jQuery('#translation_file_accordion').append('<h3 style="margin: 0;">'+category+'</h3><div id="'+category+'_tab"><table style="width: 100%;"><tbody></tbody></table></div>');
                if (typeof baseFile[category] == 'object') {
                    for (var label in baseFile[category]) {
                        var sourceFileEntry = (label != undefined) ? (decodeURI(sourceFile[category][label])) : '';
                        jQuery('#' + category + '_tab tbody').append('<tr><td style="width: 50%;">'+baseFile[category][label]+'</td><td style="width: 50%;"><input id="'+label+'_input" style="width: 100%;" value="'+sourceFileEntry+'"/></td></tr>');
                    }
                } else {
                    var sourceFileEntry = (sourceFile[category] != undefined) ? decodeURI(sourceFile[category]) : '';
                    if (category != 'languageName') {
                        jQuery('#' + category + '_tab tbody').append('<tr><td style="width: 50%;">'+baseFile[category]+'</td><td style="width: 50%;"><input id="'+category+'_input" style="width: 100%;" value="'+sourceFileEntry+'"/></td></tr>');
                    } else {
                        jQuery('#languageName_tab').append('<tr><td style="width: 50%;"><label>Language Display Name</label></td><td style="width: 50%;"><input id="'+category+'_input" value="'+sourceFileEntry+'" placeholder="Enter a display name"/></td></tr>');
                    }
                }
            }
            if (sourceFile.languageName == undefined) {
                jQuery('#translation_file_accordion').append('<h3 style="margin: 0;">Language Display Name</h3><div id="languageName_tab"><table style="width: 100%;"><tbody></tbody></table></div>');
                jQuery('#languageName_tab').append('<tr><td style="width: 50%;"><label>Language Display Name</label></td><td style="width: 50%;"><input id="languageName_input" value="" placeholder="Enter a display name"/></td></tr>');
            }
            var caption = (sourceFile.languageName != undefined) ? sourceFile.languageName : 'Language file';
            jQuery('#translation_file_accordion').accordion({
                heightStyle: "content",
                collapsible: true,
                active: false
            });
            window.tb_show(caption, '#TB_inline?width=750&height=750&inlineId=translation_file_editor', '');
        }
        function save_translation_file_changes () {
            var json_object = new Object();
            jQuery('#translation_file_accordion div').each(function(){
                var thisCategory = jQuery(this).attr('id').replace('_tab', '');
                if (thisCategory != 'languageName') {
                    json_object[thisCategory] = new Object();
                    jQuery('input', this).each(function(){
                        var labelName = jQuery(this).attr('id').replace('_input', '');
                        var labelValue = jQuery(this).val();
                        json_object[thisCategory][labelName] = encodeURI(labelValue);
                    })
                } else {
                    json_object.languageName = jQuery('#languageName_input').val();
                }
                
            });
            
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                data: {
                    action: 'udraw_designer_edit_translation_file',
                    language: jQuery('#udraw_designer_language').val(),
                    file_contents: JSON.stringify(json_object)
                },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        jQuery('#udraw_designer_language option[value="'+ jQuery('#udraw_designer_language').val() +'"]').text(response.languageName).trigger("chosen:updated");
                        window.tb_remove();
                    } else {
                        window.alert('There was an issue applying the changes to the selected file.');
                    }
                },
                error: function (error) {
                    window.alert('There was an issue applying the changes to the selected file.');
                }
            });
        }
        function build_language_code_dropdown () {
            for (var language in languageObject) {
                if (jQuery('#udraw_designer_language option[value="'+ language +'"]').length < 1) {
                    jQuery('#udraw_language_text_input').append('<option value="'+language+'">'+ languageObject[language].name +'</option>');
                }
            }
            jQuery('#udraw_language_text_input').trigger("chosen:updated");
        }
    </script>
<style>
    div#udraw_language_text_input_chosen {
        width: 250px !important;
    }

    .ui-dropdownchecklist-dropcontainer-wrapper {
        z-index: 5 !important;
    }
</style>
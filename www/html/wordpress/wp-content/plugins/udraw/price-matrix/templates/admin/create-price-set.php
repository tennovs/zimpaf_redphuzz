<?php
$uDrawPriceMatrix = new uDrawPriceMatrix();
$_content = "<xml></xml>";
$_name = "New Price Matrix";
$_action = "new";
$_font_color = "#000000";
$_background_color = "#FFFFFF";
$access_key = '';
$_measurement_unit = 'in';
if (isset($_REQUEST['access_key'])) {
    $access_key = $_REQUEST['access_key'];
    // Load in existing design.
    $price_matrix = $uDrawPriceMatrix->get_price_matrix_by_key($_REQUEST['access_key']);
    if (!is_null($price_matrix)) {
        $_name = $price_matrix[0]->name;
        $_content = str_replace('&', '&amp;', stripslashes(base64_decode($price_matrix[0]->xml_structure)));
        $_action = "update";          
        if (strlen($price_matrix[0]->font_color) > 1) {
            $_font_color = $price_matrix[0]->font_color;               
        }
        if (strlen($price_matrix[0]->background_color) > 1) {
            $_background_color = $price_matrix[0]->background_color;
        }
        if (strlen($price_matrix[0]->measurement_label) > 0) {
            $_measurement_unit = $price_matrix[0]->measurement_label;
        }
    }
}

?>

<div class="wrap" id="manage-designs-page">
    <div class="xml_alert price_matrix_error_alert space">There was an error while trying to compile your XML. Please check that the names of your custom settings do not contain characters other than letters (a-z, A-Z), underscores (_), and hyphens (-).</div>
    <div class="xml_alert xml_error_alert">There seems to be a problem with the XML. Please review it under the Source tab, and fix any problems.</div>
    <div class="xml_alert xml_updated_alert">XML generated successfully. Please check under Source tab to ensure everything is correct, and Save.</div>
    <div class="xml_alert xml_saved_alert"><?php _e('Price matrix saved.', 'udraw') ?></div>
    <table style="width:100%; padding-bottom:10px;">
        <tr>
            <td>                
                <label for="price-matrix-name-txtbox"><strong style="font-size:12pt"><?php _e('Name:', 'udraw') ?></strong></label>
                <input type="text" name="price-matrix-name-textbox" id="price-matrix-name-textbox" value="<?php echo $_name; ?>" style="width:70%;font-size:12pt;background-color: rgb(234, 232, 255);">
            </td>
            <td style="padding-right:30px; float:right;">
                <span>
                    <a href="#" id="save-btn" class="button button-primary"><?php _e('Save Layout and Settings', 'udraw') ?></a>
                    <a href="?page=udraw_price_matrix" id="close-btn" class="button button-default"><?php _e('Close', 'udraw') ?></a>
                </span>
            </td>
        </tr>
    </table>
    
    <div class="tab_container">
        <a href="#" class="ui_tab active" data-tab="layout">Layout</a>
        <a href="#" class="ui_tab" data-tab="settings">Settings</a>
        <a href="#" class="ui_tab" data-tab="categories">Categories</a>
        <a href="#" class="ui_tab" data-tab="source">Source</a>
    </div>
    <div class="tab_content_container">
        <div class="tab_content active" data-tab="layout"><?php udraw_price_matrix_layout_html($_content, $access_key); ?></div>
        <div class="tab_content" data-tab="settings"><?php udraw_price_matrix_preview_html($uDraw); ?></div>
        <div class="tab_content" data-tab="categories"><?php udraw_price_matrix_categories_html(); ?></div>
        <div class="tab_content" data-tab="source"><?php udraw_price_matrix_source_html($_content, $_action); ?></div>
    </div>
    
    <div class="preview_container">
        <h1>Preview</h1>
        <hr />
        <div id="divSettings" class="divSettings"></div>
        <div id="canvas" class="divCanvas"></div>
        <div style="float:right; padding-top: 15px;">
            <strong style="font-size:12pt;">Total Price:</strong>
            <span style="font-size: 22pt;color: rgb(0, 128, 0);font-weight: bold;">
            <span><?php echo get_woocommerce_currency_symbol(); ?></span><span id="totalPrice"></span>
            </span>
        </div>
    </div>
</div>
<script type="text/javascript">         
    var json, bs, selectedDefault, selectedByUser, eFileName = "";
    var selectedSaved = [];
    var selectedOutput = '';
    var measurement_unit_label = '<?php echo $_measurement_unit ?>';
    var priceMatrixObj;
    function display_udraw_price_matrix_preview(access_key) {
        eFileName = '<?php echo admin_url('admin-ajax.php') ?>?action=udraw_price_matrix_get&price_matrix_id=' + access_key;

        priceMatrixObj = PriceMatrix({
            url: eFileName,
            key: '<?php echo uDraw::get_udraw_activation_key(); ?>',
            callback: function (obj) {
                json = priceMatrixObj.getFields();
                bs = json;
                AddSettings();
                selectedDefault = priceMatrixObj.getDataDefaults();
                selectedByUser = selectedDefault;
                DisplayFieldsJSON(true);
            }
        });
    }
</script>

<?php

    function price_matrix_tax_meta() {
        $cats = array();
        $access_key = (isset($_REQUEST['access_key'])) ? $_REQUEST['access_key'] : 0;
        $uDrawPriceMatrix = new uDrawPriceMatrix();
        $price_matrix_cats = $uDrawPriceMatrix->get_cat_by_price_matrix($access_key);
        foreach ($price_matrix_cats as $_price_matrix_cats) {
            array_push($cats, $_price_matrix_cats->category_id);
        }
        // Get all possible categories
        $tax_name = 'product_cat';                
        $args = array( 
            'parent' => 0,
            'hide_empty' => false
        );

        $terms = get_terms( $tax_name, $args );		
        if ( $terms ){
            echo '<ul class="rule-product-cats level-1">';
            foreach ( $terms as $term ) {
                print_tax_inputs( $term, $tax_name, $cats, 2 );
            }
            echo '</ul>';
        }
    }

    function print_tax_inputs( $term, $taxonomy_name, $cats, $level ) { 
    ?>
        <li>
            <input type="checkbox" id="_udraw_price_matrix_cat_<?php echo $term->term_id ?>" name="_udraw_price_matrix_cat_<?php echo $term->term_id ?>" <?php if ( is_array( $cats ) and in_array( $term->term_id, $cats )) echo 'checked="checked"' ?> /><?php echo $term->name; ?>
        </li>
    <?php 

        // Get any Children
        $children = get_term_children( $term->term_id, $taxonomy_name );

        // Continue to print children if they exist
        if ( $children ){
            echo '<ul class="level-' . $level . '">';
            $level++;
            foreach ( $children as $child_id ){
                $child = get_term_by( 'id', $child_id, $taxonomy_name );
                // If the child is at the second level relative to the last printed element, exclude it
                if ( is_object( $child ) and $child->parent == $term->term_id ) {
                    print_tax_inputs( $child, $taxonomy_name, $cats, $level );
                }
            }
            echo '</ul>';
        }
    }
    function udraw_price_matrix_layout_html($_content, $access_key) {
        $new_xml_string = "<?xml version='1.0' encoding='UTF-8'?><Product><Fields ShowPages=\"false\"></Fields><Options></Options><Prices></Prices></Product>";
        $xml = ($_content !== '<xml></xml>') ? simplexml_load_string($_content) : simplexml_load_string($new_xml_string);
        if ($xml) {
            $_default_settings = get_price_matrix_fields_default_settings();
            $_fields_settings = get_price_matrix_settings($xml);
            $_options = get_price_matrix_field_options($xml);
            $_prices = get_price_matrix_prices($xml);
            $_linked_prices = get_price_matrix_linked_prices($xml);
        }
        ?>
        <script>
            if (typeof check_json_string !== 'function') {
                check_json_string = function (json_string) {
                    try {
                        if (JSON.parse(json_string) !== null) {
                            return JSON.parse(json_string);
                        } else {
                            jQuery('div.xml_error_alert').show();
                                return false;
                            }
                    } catch (e) {
                        console.log(e);
                        jQuery('div.xml_error_alert').show();
                        return false;
                    }
                }
            }
            var _price_matrix = {
                _access_key: '<?php echo $access_key; ?>',
                _defaults : check_json_string('<?php echo json_encode($_default_settings)?>'),
                _settings : check_json_string('<?php echo json_encode($_fields_settings)?>'),
                _options : check_json_string('<?php echo json_encode($_options)?>'),
                _prices : check_json_string('<?php echo json_encode($_prices)?>'),
                _linked_items : check_json_string('<?php echo json_encode($_linked_prices)?>'),
                _option_defaults : [
                    {   'name' : 'weight',
                        'display_name' : 'Weight',
                        'value' : 0,
                        'type' : 'integer' },
                    {   'name' : 'setupprice',
                        'display_name' : 'Setup Price',
                        'value' : 0,
                        'type' : 'integer' },
                    {   'name' : 'mode',
                        'display_name' : 'Mode',
                        'value' : 'Total',
                        'type' : 'select' },
                    {   'name' : 'default',
                        'display_name' : 'Make Default',
                        'value' : false,
                        'type' : 'boolean' }
                ]
            };
            var _price_matrix_description = JSON.parse('<?php echo json_encode(get_price_matrix_help()) ?>');
        </script>
            <div class="layout_container">
                <table class="layout_table">
                    <tbody>
                        <tr class="header"><th class="tree_view">View</th><th class="content_view">Content</th></tr>
                        <tr>
                            <td class="tree_view">
                                <div class="tree_view_container">
                                    <ul class="tree_view_list tree_branch_list">
                                        <li class="tree_branch root"><i class="far fa-plus-square expand_branch"></i><span class="tree_branch_span" data-id="root">Price Matrix</span>
                                            <ul class="tree_branch_contents tree_branch_list"></ul>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="content_view">
                                <div class="modal edit_label_modal" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Object</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p class="name"><label>Display Label: </label><input type="text" class="display_name" value=""/></p>   
                                                <p class="type"><label>Field Type: </label>
                                                <select class="field_type">
                                                    <option id="dropdown" value="dropdown">Dropdown</option>
                                                    <option id="textbox" value="textbox">Textbox</option>
                                                    <option id="textarea" value="textarea">Textarea</option>
                                                    <option id="number" value="number">Number</option>
                                                </select></p>
                                                <p class="tooltip_text"><label>Tooltip: </label><input type="text" class="tooltip_text" value="" style="width: 90%;"/></p>  
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="button button-default" data-dismiss="modal"><?php _e('Cancel','udraw'); ?></button>
                                                <button type="button" class="button button-primary apply_label_change"><?php _e('Apply Changes','udraw'); ?></button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                                <div class="price_matrix_child_container">
                                    <div class="tab_container">
                                        <a href="#" class="option_tab active" data-tab="general_settings">Settings</a>
                                        <a href="#" class="option_tab" data-tab="settings">Settings</a>
                                        <a href="#" class="option_tab" data-tab="prices">Prices</a>
                                    </div>
                                    <div class="tab_content_container" style="margin-top: 0; height: 450px;">
                                        <div class="option_content active" data-tab="general_settings">
                                            <table class="price_matrix_settings">
                                                <tbody>
                                                    <tr class="header"><th>Setting</th><th>Value</th><th class="option_description">Description</th></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="option_content" data-tab="settings">
                                            <div class="table_container">
                                                <table class="option_settings_table">
                                                    <tbody>
                                                        <tr class="header"><th>Setting Name</th><th>Value</th><th class="option_description">Description</th><th></th></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a href="#" class="add_option_setting button">Add Custom Option</a>
                                            <a href="#" class="add_cascading_option button">Add Cascading Option</a>
                                        </div>
                                        <div class="option_content" data-tab="prices">
                                            <div class="table_container">
                                                <table class="prices_table">
                                                    <tbody>
                                                        <tr class="header"><th>Price Break</th><th>Pricing Mode</th><th>Price</th><th>Shipping</th><th></th></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a href="#" class="add_price_break button">Add Price Break</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
    }
    
    function get_price_matrix_linked_prices($xml) {
        $_linked_array = $xml->Options->Option;
        $_linked = array();
        foreach($_linked_array as $option => $properties) {
            $new_array = array();
            foreach($properties->attributes() as $attribute => $value) {
                $new_array[$attribute] = base64_encode((string)$value);
            }
            $new_array['display_name'] = base64_encode((string)$properties);
            array_push($_linked, $new_array);
        }
        return $_linked;
    }
    
    function get_price_matrix_prices($xml) {
        $_prices_array = $xml->Prices->Price;
        $_prices = array();
        foreach($_prices_array as $price => $attributes_array) {
            $_new_array = array();
            foreach ($attributes_array->attributes() as $attribute => $value) {
                $string_value = base64_encode((string)$value);
                $_new_array[$attribute] = $string_value;
            }
            array_push($_prices, $_new_array);
        }
        return $_prices;
    }
    
    function get_price_matrix_field_options($xml) {
        $_options_array = $xml->Fields->Field;
        $_new_array = array();
        foreach($_options_array as $field => $properties) {
            $attr_array = array();
            foreach ($properties->attributes() as $name => $value) {
                $attr_array[$name] = (string)$value;
            }
            $attr_array['display_name'] = base64_encode((string)$properties);
            array_push($_new_array, $attr_array);
        }
        return $_new_array;
    }
    function get_price_matrix_settings($xml) {
        $_field_options = array();
        $attributes_array = $xml->Fields->attributes();
        foreach ($attributes_array as $_property => $value) {
            $_field_options[$_property] = (string)$value;
        }
        return $_field_options;
    }
    function get_price_matrix_fields_default_settings() {
        $_fields_defaults = get_price_matrix_fields_defaults();
        $_field_options = array();
        foreach ($_fields_defaults as $_property => $value) {
            $_option_type = gettype($value);
            $_field_options[$_property] = array(
                'type' => $_option_type,
                'value' => $value
            );
        }
        $_field_options['ShowPages']['value'] = false;
        $_field_options['SizeControlType']['type'] = 'select';
        $_field_options['SizeControlType']['options'] = array('select', 'input');
        return $_field_options;
    }
    function get_price_matrix_fields_defaults () {
        $_defaults = array(
            'LayoutMode' => 1,
            'MinQty' => 1,
            'MaxQty' => 0,
            'DefaultQty' => 1,
            'Quantities' => '',
            'DefaultPages' => 1,
            'DefaultContingency' => 0,
            'ShowPages' => false,
            'EditPages' => false,
            'PagesSet' => '',
            'ShowContingency' => true,
            'EditContingency' => true,
            'ShowSize' => false,
            'MinWidth' => 1,
            'MinHeight' => 1,
            'MaxWidth' => 20,
            'MaxHeight' => 20,
            'DefaultWidth' => 1,
            'DefaultHeight' => 1,
            'SizeControlType' => 'select',
            'EditSize' => true,
            'Enable3DPricing' => false,
            'MinLength' => 1,
            'MaxLength' => 20,
            'DefaultLength' => 1,
            'ShowUpload' => true,
            'AlwaysEnablePages' => false,
            'UploadRequired' => true,
            'AddBleed' => 0,
            'MaxFiles' => 0,
            'MinPrice' => 0,
            'BasePrice' => '',
            'PriceBreaks' => '',
            'CustomQtyLabel' => '',
            'CustomPagesLabel' => '',
            'AllowedFileTypes' => '',
            'ContainerStyle' => '',
            'ContainerClass' => '',
            'PriceStyle' => '',
            'PriceClass' => '',
            'SettingsStyle' => '',
            'SettingsClass' => '',
            'UploadStyle' => '',
            'UploadClass' => '',
            'DefaultRowStyle' => '',
            'DefaultRowClass' => '',
            'DefaultLabelCellStyle' => '',
            'DefaultLabelCellClass' => '',
            'DefaultControlCellStyle' => '',
            'DefaultControlCellClass' => '',
            'DefaultControlStyle' => 'width: 250px;',
            'DefaultControlClass' => '',
            'EmptyLabel' => ''
        );
        return $_defaults;
    }
    function get_price_matrix_help() {
        $_settings = array(
            'LayoutMode' => array(
                '0 Custom Layout - no class or style added', '1 Label and Control on the same line - Applies to Cascade as well','2 Label and Control on seprate lines','3 Horizontal Layout of Cascades'
            ),
            'ShowPages' => 'Enable Pages Contol',
            'EditPages' => 'Enable Editing Pages Text Box',
            'ShowSize' => 'Show Width and Height input boxes',
            'Enable3DPricing' => 'Show Length input box in case on 3D products like boxes.',
            'MinWidth' => 'Minimum number for Width',
            'MinHeight' => 'Minimum number for Height',
            'MinLength' => 'Minimum number for Length',
            'MaxWidth' => 'Maximum number for Width',
            'MaxHeight' => 'Maximum number for Height',
            'MaxLength' => 'Maximum number for Length',
            'SizeControlType' => 'Control type for selecting page sizes',
            'EditSize' => 'Enable Editing Text Box',
            'ShowUpload' => 'Show File Upload Control',
            'AlwaysEnablePages' => 'Always Enable Pages Control even if the number of pages can be calculated (PDF for example)',
            'UploadRequired' => 'User must upload',
            'AddBleed' => 'Bleed to be added to all 4 sides of document size if greater than 0 for file upload size check',
            'MaxFiles' => 'Maximum number of uploaded files - 0 for unlimited',
            'MinPrice' => 'Minimum price. Total Price less than this number will be forced to this. 0 for no minimum',
            'BasePrice' => 'To be used as an additional variable in custom formulas if needed',
            'CustomQtyLabel' => 'Custom Label to be used for Quantity input',
            'CustomPagesLabel' => 'Custom Label to be used for Pages input',
            'AllowedFileTypes' => 'Comma separated file extenstion (without dot) - empty to allow all',
            'ContainerStyle' => 'CSS Style of the container div',
            'ContainerClass' => 'CSS Classes of the container div',
            'PriceStyle' => 'CSS Style for Price Panel',
            'PriceClass' => 'CSS Classes for Price Panel',
            'SettingsStyle' => 'CSS Style for Settings Panel (Quantity, Pages, Width, Height)',
            'SettingsClass' => 'CSS Classes for Settings Panel (Quantity, Pages, Width, Height)',
            'UploadStyle' => 'CSS Style for Upload Panel',
            'UploadClass' => 'CSS Classes for Upload Panel',
            'DefaultRowStyle' => 'CSS Row Style (Label-Control)',
            'DefaultRowClass' => 'CSS Row Classes (Label-Control)',
            'DefaultLabelCellStyle' => 'CSS Label Cell Style',
            'DefaultLabelCellClass' => 'CSS Label Cell Classes',
            'DefaultControlCellStyle' => 'CSS Control Cell Style',
            'DefaultControlCellClass' => 'CSS Control Cell Classes',
            'DefaultControlStyle' => 'CSS Control Style',
            'DefaultControlClass' => 'CSS Control Classes',
            'EmptyLabel' => 'Replacement text for empty label',
            'MinQty' => 'Minumum Quantity',
            'MaxQty' => 'Maximum Quantity (To set a max qty set a number greater than 0. Unset at 0)',
            'Quantities' => 'Comma separated list of quantities',
            'PriceBreaks' => 'Comma separated list of price breaks',
            'PagesSet' => 'Comma separated list of page numbers',
            'DefaultQty' => 'Default quantity',
            'DefaultPages' => 'Default number of pages',
            'DefaultWidth' => 'Default width',
            'DefaultHeight' => 'Default height',
            'DefaultLength' => 'Default length'
        );
        $_price = array(
            'Name' => 'Name of price table. Options will be linked to prices using this.',
            'Break' => 'Price break',
            'UnitPrice' => 'Unit price for matching price break'
        );
        $_linked_prices = array (
            'name' => 'Name of option',
            'label' => 'Label of Option (if is sub drop down)',
            'optionsname' => 'Name of Cascading Options',
            'weight' => 'Weight of option',
            'setupprice' => 'Setup price for option',
            'default' => 'Specifies if the current option is selected by default',
            'pricesname' => 'Name of Price tag',
            'mode' => 'Price Calculation Mode (hover over options in drop down for brief description)'
        );
        $_mode = array (
            'None' => 'Flat rate',
            'Quantity' => 'Quantity',
            'Records' => 'Records (or pages)',
            'Total' => 'Quantity x Records(or pages)',
            'Area' => 'Width x Height',
            'Area and Quantity' => 'Two level price breaks with different set of quantity areas based on area break. Those levels needs to defined by using custom property AreaQuantityArr',
            'Area Quantity' => 'Width x Height x Quantity',
            'Area Records' => 'Width x Height x Records(or pages)',
            'Area Total' => 'Width x Height x Quantity x Records(or pages)',
            'Linear' => '(Width + Height) x 2',
            'Linear Quantity' => '(Width + Height) x 2 x Quantity',
            'Linear Records' => '(Width + Height) x 2 x Records (or Pages)',
            'Linear Total' => '(Width + Height) x 2 x Quantity x Records (or Pages)',
            'Linear Foot' => '((Width + Height) x 2 x Quantity) / 2',
            'PerSheet' => 'Quantity / Prints Per Sheet',
            'Surface Area' => '2(Width x Height) + 2(Height x Length) + 2(Width x Length)',
            'Shipping Boxes Area' => '((Length x 2) + (Width x 2)) x (Length + Height)',
            'Mailer Style Boxes Area' => '((Height x 4) + Length) x ((Width x 2) + (Height x 2))',
            'Folding Cartons Area' => '((Length x 2) + (Width x 2) + 0.5) x (Height + (Width x 2))'
        );
        return ['settings' => $_settings, 'price' => $_price, 'linked_prices' => $_linked_prices, 'mode' => $_mode];
    }
    function udraw_price_matrix_source_html ($_content, $_action) {
        ?>
        <div>
            <?php
            $settings = array(
                'textarea_name' => 'section_content',
                'media_buttons' => false,
                'quicktags' => false,
                'tinymce' => array()
            );
            ?>
        </div>
        <textarea class="wp-editor-area hidden" rows="20" cols="40" name="section_content2" id="section_content2" style="display: none !important;"><?php echo $_content;?></textarea>
        <div name="section_content" id="section_content" style="position: relative;width: auto;height: 500px;"></div>
        <br/>
        <a href="#" id="save_xml" class="button button-primary" style="float: right;">Save XML and Settings</a>
        <input type="hidden" name="action" value="<?php echo $_action; ?>" />
        <?php if ($_action == "update") { ?>
            <input type="hidden" name="access_key" id="access_key" value="<?php echo $_REQUEST['access_key']; ?>" />
        <?php }
    }

    function udraw_price_matrix_categories_html() {
        ?>
        <table style="width:98%;" id="udraw-price-matrix-cats-table">
            <tr>
                <td>
                    <form name="udraw-price-matrix-cats" id="udraw-price-matrix-cats">
                        <?php price_matrix_tax_meta(); ?>
                    </form>
                </td>
            </tr>
        </table>
        <?php
    }

    function udraw_price_matrix_preview_html($uDraw) {
        $uDrawPriceMatrix = new uDrawPriceMatrix();
        $_font_color = "#000000";
        $_background_color = "#FFFFFF";
        $_disable_file_upload = false;
        $_disable_design_online = false;
        $_linked_template_id = 0;
        $_measurement_unit = 'in';
        
        if (isset($_REQUEST['access_key'])) {            
            $price_matrix = $uDrawPriceMatrix->get_price_matrix_by_key($_REQUEST['access_key']);
            
            if (!is_null($price_matrix)) {     
                if (strlen($price_matrix[0]->font_color) > 1) {
                    $_font_color = $price_matrix[0]->font_color;            
                }
                if (strlen($price_matrix[0]->background_color) > 1) {
                    $_background_color = $price_matrix[0]->background_color;
                }
                if (isset($price_matrix[0]->disable_file_upload)) {
                    $_disable_file_upload = $price_matrix[0]->disable_file_upload;                    
                }
                if (isset($price_matrix[0]->disable_design_online)) {
                    $_disable_design_online = $price_matrix[0]->disable_design_online;
                }
                if (strlen($price_matrix[0]->udraw_template_id) > 0) {
                    $_linked_template_id = $price_matrix[0]->udraw_template_id;
                }
                if (strlen($price_matrix[0]->measurement_label) > 0) {
                    $_measurement_unit = $price_matrix[0]->measurement_label;
                }
            }
        }
        
        ?>
        <div class="divContainer">
            <h1>Settings</h1>
            <hr />
            <div>
                <div>
                    <label for="price-matrix-measurement-unit" style="font-size:12pt; vertical-align:top;">Measurement Unit: &nbsp;</label>
                    <select id="price-matrix-measurement-unit"  style="font-size:12pt; vertical-align:top;">
                        <option id="in-unit" value="in" <?php if ($_measurement_unit == 'in') { echo 'selected'; } ?>>Inches</option>
                        <option id="ft-unit" value="ft" <?php if ($_measurement_unit == 'ft') { echo 'selected'; } ?>>Feet</option>
                        <option id="cm-unit" value="cm" <?php if ($_measurement_unit == 'cm') { echo 'selected'; } ?>>Centimeter</option>
                        <option id="m-unit" value="m" <?php if ($_measurement_unit == 'm') { echo 'selected'; } ?>>Meter</option>
                        <option id="mm-unit" value="mm" <?php if ($_measurement_unit == 'mm') { echo 'selected'; } ?>>Millimeter</option>
                    </select>
                </div>
                <div style="display:none;">
                    <label for="price-matrix-font-color" style="font-size:12pt; vertical-align:top;">Font:&nbsp;</label>
                    <input type="text" value="<?php echo $_font_color; ?>" id="price-matrix-font-color" />
                </div>
                <div style="display:none;">
                    <label for="price-matrix-background-color" style="font-size:12pt; vertical-align:top;">Background:&nbsp;</label>
                    <input type="text" value="<?php echo $_background_color; ?>" id="price-matrix-background-color" />
                </div>
                <div style="padding-top:5px;">
                    <label for="price-matrix-disable-file-upload" style="font-size:12pt; vertical-align:top;">Disable File Upload:&nbsp;</label>
                    <input type="checkbox" id="price-matrix-disable-file-upload" <?php if ($_disable_file_upload) { echo 'checked="checked"'; } ?> />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="price-matrix-disable-design-online" style="font-size:12pt; vertical-align:top;">Disable Design Online:&nbsp;</label>
                    <input type="checkbox" id="price-matrix-disable-design-online" <?php if ($_disable_design_online) { echo 'checked="checked"'; } ?> />
                </div>
                <div style="padding-top:5px;">
                    <div class="options_group">
                        <p class="form-field">
                            <label for="udraw_template_id" style="font-size:12pt; vertical-align:top;"><?php _e('Link uDraw Template:', 'udraw'); ?></label>
                            <select id="udraw_template_id" name="udraw_template_id" multiple="multiple" data-placeholder="<?php _e('Select a Template', 'udraw'); ?>">
                                <?php
                                $templateId = $_linked_template_id;
                                $templates = $uDraw->get_udraw_templates();
                                foreach ($templates as $template) { 
                                    if ($templateId == $template->id) {
                                        echo '<option value="' . esc_attr($template->id) . '" selected>' . esc_html($template->name . ' - ' . $template->design_width . '" x '. $template->design_height .'"') . '</option>';
                                    } else {
                                        echo '<option value="' . esc_attr($template->id) . '">' . esc_html($template->name . ' - ' . $template->design_width . '" x '. $template->design_height .'"'). '</option>';
                                    }
                                }
                                ?>						                        
                            </select>
                        </p>
                        <p class="form-field">
                            <div id="udraw_template_preview">
                            </div>
                        </p>
                    </div>
                </div>                
            </div>        
        </div>
        <?php
    }
?>
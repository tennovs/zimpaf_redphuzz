<?php
if (!class_exists('uDraw_Admin_Orders')) {
    class uDraw_Admin_Orders {
        function __construct() {
            
        }
        function init() {
            add_action('woocommerce_admin_order_item_headers', array( &$this, 'woo_udraw_add_order_item_header' ) );
            add_filter('woocommerce_hidden_order_itemmeta', array(&$this, 'woo_udraw_hide_order_itemmeta'), 99, 1);
            add_action('woocommerce_admin_order_item_values', array( &$this, 'woo_udraw_admin_order_item_values' ), 10, 3 );
        }
        
        public function woo_udraw_add_order_item_header() {
            ?>
                <th class="udraw-product-heading" style="min-width:260px; text-align:center;">uDraw Controls</th>
            <?php
        }

        public function woo_udraw_hide_order_itemmeta($meta = array()) {            
            $meta[] = '_udraw_pdf_path'; 
            $meta[] = '_udraw_pdf_xref';
            $meta[] = '_udraw_preview_xref';
            $meta[] = '_udraw_xml_xref';
            $meta[] = '_udraw_product_jpg';
            $meta[] = '_udraw_product_preview';
            return $meta;
        }
        
        /**
         * Adds buttons on item row when viewing order infomation from admin area.
         * Displays meta box also only if order contains uDraw product.
         */
        public function woo_udraw_admin_order_item_values( $_product, $item, $item_id ) {
            global $woocommerce, $post;
            $uDraw = new uDraw();
            $order = new WC_Order($post->ID);
            $uDrawSettings = new uDrawSettings();
            $_settings = $uDrawSettings->get_settings();
            $getIndex = 0;

            if (get_class($item) === 'WC_Order_Refund' || strpos(get_class($item), 'OrderRefund') !== false) {
                return;
            }

            foreach ( $order->get_items() as $key => $orderItem ) {
                //Get line item index
                $getIndex = $getIndex + 1;
                if ( $item_id == $key ) { 
                    break;
                }
            }

            //get order id
            $order_id = trim(str_replace('#', '', $order->get_order_number()));
            $uniquePreviewId = uniqid('preview');
            $uniquePDFPagesId = uniqid('pages');
            $_udraw_product_data = '';
            $_udraw_product_preview = '';
            $friendly_item_name = '';

            if (isset($item['udraw_data'])) {
                if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                    $product = $item['udraw_data'];
                } else {
                    $product = unserialize($item['udraw_data']);
                }

                if (!$product) {
                    $fixed = preg_replace_callback(
                        '/s:([0-9]+):\"(.*?)\";/',
                        function ($matches) { return "s:".strlen($matches[2]).':"'.$matches[2].'";';     },
                        htmlspecialchars($item['udraw_data'], ENT_NOQUOTES)
                    );
                    $product = unserialize($fixed);
                    if (isset($product['udraw_pdf_block_product_data']) && strlen($product['udraw_pdf_block_product_data']) > 0) { 
                        if (is_null(json_decode($product['udraw_pdf_block_product_data']))) {
                            $product['udraw_pdf_block_product_data'] = uDraw::fixBlocksJSONValues($product['udraw_pdf_block_product_data']);
                        }
                    }
                }

                if (!is_null($_settings['udraw_order_document_format'])) {
                    if (strlen($_settings['udraw_order_document_format']) > 0) {
                        $qty = (isset($product['udraw_price_matrix_qty'])) ? $product['udraw_price_matrix_qty'] : wc_get_order_item_meta($item_id, '_qty', true);
                        $friendly_item_name = $_settings['udraw_order_document_format'];
                        $friendly_item_name = str_replace('%_ORDER_ID_%', $order_id, $friendly_item_name);
                        $friendly_item_name = str_replace('%_JOB_ID_%', $item_id, $friendly_item_name);
                        $friendly_item_name = str_replace('%_ITEM_INDEX_%', $getIndex, $friendly_item_name);    
                        $friendly_item_name = str_replace('%_QUANTITY_%', $qty, $friendly_item_name);                                                 
                    }
                } else {
                    $friendly_item_name = $item['name'] . '-' . $order_id;
                    $friendly_item_name = preg_replace('/"/', '', $friendly_item_name);
                }

                if (isset($product['udraw_pdf_block_product_data']) && strlen($product['udraw_pdf_block_product_data']) > 0) { 
                    if (is_null(json_decode($product['udraw_pdf_block_product_data']))) {
                        $product['udraw_pdf_block_product_data'] = uDraw::fixBlocksJSONValues($product['udraw_pdf_block_product_data']);
                    }
                }
                if (isset($product['udraw_pdf_block_product_data']) && strlen($product['udraw_pdf_block_product_data']) > 0) {
                    // Add Meta Box if order contains uDraw PDF Product.
                    add_meta_box( 'udraw-pdf-order', 'uDraw PDF Viewer', array( &$this, 'woo_udraw_admin_pdf_product_order_view'), 'shop_order', 'normal', 'default' );
                }

                if (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0 ||
                   (isset($product['udraw_options_uploaded_files'])&& strlen($product['udraw_options_uploaded_files']) > 0) ) {
                    // Add Meta Box if order contains uDraw Product.
                    add_meta_box( 'udraw-order', 'uDraw Product Viewer', array( &$this, 'woo_udraw_admin_product_order_view'), 'shop_order', 'normal', 'default' );
                }

                // Get data from uDraw Product
                if (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0) {
                    $_udraw_product_data = $product['udraw_product_data'];
                    $_udraw_product_preview = $product['udraw_product_preview'];
                }

                // Get data from Price Matrix Product.
                if (isset($product['udraw_price_matrix_design_data'])) {
                    $_udraw_product_data = $product['udraw_price_matrix_design_data'];
                    $_udraw_product_preview = $product['udraw_price_matrix_design_preview'];
                }

                // Get data from PDF Block Product
                if (isset($product['udraw_pdf_block_product_data']) && strlen($product['udraw_pdf_block_product_data']) > 0) {
                    $_udraw_product_data = $product['udraw_pdf_block_product_data'];
                    $_udraw_product_preview = $product['udraw_pdf_block_product_thumbnail'];
                }

                if (strlen($_udraw_product_preview) > 0) {
                    echo '<div id="' . $uniquePreviewId . '" style="display:none;">';
                    if (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0) {
                        //Check if excel product
                        if (isset($product['udraw_options_uploaded_excel']) && strlen($product['udraw_options_uploaded_excel']) > 0) {
                            $excelObject = json_decode($product['udraw_options_uploaded_excel']);
                            $outputDir = $uDraw->get_physical_path($excelObject->outputPath . $excelObject->sessionID);
                            $entryArray = $excelObject->entryObject;
                            echo '<ul>';
                            for ($i = 0; $i < count($entryArray); $i++) {
                                echo '<li>';
                                $xml = $outputDir . '/entry_' . ($i + 1) . '.xml';
                                $designXML = simplexml_load_string(file_get_contents($xml), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
                                echo '<ul style="list-style: none;font-size: 0px;margin-left: -2.5%;">';
                                for ($p = 0; $p < count($designXML->page); $p++) {
                                    echo '<li style="width: 47.5%;display: inline-block;padding: 10px; margin: 0 0 2.5% 2.5%; background: #fff;border: 1px solid #ddd;font-size: 16px;font-size: 1rem;vertical-align: top;box-shadow: 0 0 5px #ddd;box-sizing: border-box;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;">';
                                    $preview = $designXML->page[$p]->item[0]['preview'];
                                    echo '<img style="max-width: 100%;height: auto;margin: 0 0 10px;border: 1px solid #ddd;" src="'. $preview . '" />';
                                    echo '</li>';
                                }
                                echo '</ul>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                            $designXML = simplexml_load_string(base64_decode(file_get_contents(UDRAW_STORAGE_DIR . $product['udraw_product_data'])), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
                            $_pdf_pages = wc_get_order_item_meta($item_id, '_udraw_pdf_pages', true);
                            echo '<ul style="list-style: none;font-size: 0px;margin-left: -2.5%;">';
                            for ($cp = 0; $cp < count($designXML->page); $cp++) {
                                echo '<li style="width: 47.5%;display: inline-block;padding: 10px; margin: 0 0 2.5% 2.5%; background: #fff;border: 1px solid #ddd;font-size: 16px;font-size: 1rem;vertical-align: top;box-shadow: 0 0 5px #ddd;box-sizing: border-box;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;">';
                                if ($designXML->page[$cp]->item[0]["preview"] == "undefined") {
                                    echo '<div style="max-width: 100%;min-height: 176px; max-height:200px; border: 1px solid #ddd;display: -webkit-flexbox;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-align: center;-ms-flex-align: center;-webkit-align-items: center;align-items: center;justify-content: center;"><span style="line-height:45px; text-align:center;font-size:34px; font-family:Arial;">No Preview Available</span></div>';
                                } else {                                        
                                    echo '<img style="max-width: 100%;height: auto;margin: 0 0 10px;border: 1px solid #ddd;" src="'. $designXML->page[$cp]->item[0]["preview"] . '" />';
                                }
                                echo '<span style="margin: 0 0 5px;">';
                                echo '<h3 style="display:inline;">Page '. ($cp+1) .'</h3>';
                                if (is_array($_pdf_pages)) {
                                    if (isset($_pdf_pages[$cp])) {
                                        echo '<a href="'. $_pdf_pages[$cp] .'" target="_blank" class="button button-small button-secondary" style="float:right;" download="'.$friendly_item_name . '-' . ($cp+1) .'.pdf">Download PDF</a>';
                                    }
                                }
                                echo '</span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                    } else {
                        echo '<img style="max-width:580px; max-height:540px; box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 5px;" src="' . $_udraw_product_preview .'" />';
                    }
                    echo '</div>';
                }

                
                echo '<td class="udraw-product" style="width:150px">';
                $disable_default_udraw_order_controls = apply_filters('udraw_disable_default_order_controls', false, $item);
                if (!$disable_default_udraw_order_controls) {
                    $_pdf_path = wc_get_order_item_meta($item_id, '_udraw_pdf_xref', true);
                    if (strpos($_pdf_path, UDRAW_ORDERS_DIR) !== false) {
						$_pdf_path = str_replace(UDRAW_ORDERS_DIR, UDRAW_ORDERS_URL, $_pdf_path);
					}
                    $rebuild_url = esc_url(add_query_arg('udraw_rebuild_pdf', 'true'));
                    
                    //Preview Button
                    if (isset($_udraw_product_preview) && strlen($_udraw_product_preview) > 0) {
                        echo '<a href="#TB_inline?width=600&height=550&inlineId=' . $uniquePreviewId . '" class="button button-small button-secondary udraw-preview-order-item thickbox" onclick="javascript:window.tb_show(\'View Pages\', \'#TB_inline?width=600&height=550&inlineId=' . $uniquePreviewId . '\');" style="width: 125px; text-align: center;">'.__('View Page(s)', 'udraw').'</a>';
                    }
                    //Update Design button
                    if (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0) { 
                        if (!isset($product['udraw_options_uploaded_excel']) || strlen($product['udraw_options_uploaded_excel']) === 0 || $product['udraw_options_uploaded_excel'] === NULL) {
                            echo '<a href="#" class="button button-small button-secondary udraw-show-order-item" data-product="' . $_udraw_product_data . '" data-id="' . $item_id . '" style="width: 125px; text-align: center;"><i class="fa fa-spinner fa-pulse"></i><span style="display: none;">'.__('Update Design', 'udraw').'</span></a>';
                        }
                    } else if (isset($product['udraw_pdf_block_product_data']) && strlen($product['udraw_pdf_block_product_data']) > 0) {
                        echo '<a href="#" class="button button-small button-secondary udraw-show-pdf-order-item" data-product="' . base64_encode($_udraw_product_data) . '" data-id="' . $product['udraw_pdf_block_product_id'] . '" style="width: 125px; text-align: center;">'.__('Update Design', 'udraw').'</a>';
                    }
                    
                    echo '<br />';
                    //Download PDF
                    if (isset($product['udraw_options_uploaded_excel']) && strlen($product['udraw_options_uploaded_excel']) > 0) {
                        $order_item_dir = UDRAW_ORDERS_DIR .'uDraw-Order-'.$order_id.'-'.$item_id.'/';
                        $order_item_url = UDRAW_ORDERS_URL .'uDraw-Order-'.$order_id.'-'.$item_id.'/';
                        $zipFileDir = $order_item_dir . 'uDraw-Order-' . $order_id . '-' . $item_id . '.zip';
                        $zipFileUrl = $order_item_url . 'uDraw-Order-' . $order_id . '-' . $item_id . '.zip';
                        $disabled = (file_exists($zipFileDir)) ? '' : ' disabled ';
                        
                        $excelObject = json_decode($product['udraw_options_uploaded_excel']);
                        $totalEntryCount = count($excelObject->entryObject);
                        
                        echo '<a href="#" id="download_btn-'.$order_id.'-'.$item_id.'" class="button button-small button-secondary" style="width: 125px; text-align: center;" onclick="javascript: window.open(\''.$zipFileUrl.'\', \'_blank\'); return false;" '.$disabled.'>'.__('Download PDF', 'udraw').'</a>';
                        if (!file_exists($zipFileDir)) {
                            ?>
                            <script>
                                function check_zip_file(order_id, item_id) {
                                    jQuery.ajax({
                                        url: '<?php echo admin_url("admin-ajax.php") ?>',
                                        method: 'POST',
                                        data: {
                                            action: 'udraw_excel_check_for_zip_file',
                                            order_id: order_id,
                                            item_id: item_id
                                        },
                                        success: function (response) {
                                            response = JSON.parse(response);
                                            var success = response.success;
                                            var avgTime = response.timeAverage;
                                            var currentFileCount = response.fileCount;
                                            
                                            if (success) {
                                                jQuery('#download_btn-'+order_id+'-'+item_id).attr('disabled', false);
                                            } else {
                                                if (!avgTime && currentFileCount > 0) {
                                                    jQuery('#download_btn-'+order_id+'-'+item_id).attr('title', '<?php _e("Estimated time of completion: Not available. Job will automatically resume if started.", "udraw") ?>');
                                                    return;
                                                }
                                                //Calculate estimated time remaining
                                                var totalEntryCount = parseInt('<?php echo $totalEntryCount ?>');
                                                var filesRemaining = totalEntryCount - currentFileCount;
                                                var remainingTime = (avgTime * filesRemaining) * 1000; //in milliseconds
                                                if (currentFileCount > 0 && remainingTime > 0) {
                                                    var dt = new Date();
                                                    var estimated_time = new Date(dt.getTime() + remainingTime);
                                                    jQuery('#download_btn-'+order_id+'-'+item_id).attr('title', '<?php _e("Estimated time of completion: ", "udraw") ?>' +  estimated_time);
                                                } else {
                                                    jQuery('#download_btn-'+order_id+'-'+item_id).attr('title', '<?php _e("Estimated time of completion: Not available.", "udraw") ?>');
                                                }
                                                
                                                //Check again in 30 seconds
                                                setTimeout(function(){
                                                    check_zip_file(order_id, item_id);
                                                }, 30000);
                                            }
                                        },
                                        error: function (error) {
                                            console.error(error);
                                        }
                                    });
                                }
                                check_zip_file(<?php echo $order_id ?>, <?php echo $item_id ?>);
                            </script>
                            <?php
                        }
                    } else {
                        if (isset($_pdf_path) && strlen($_pdf_path) > 3) {
                            $extension = pathinfo($_pdf_path, PATHINFO_EXTENSION);
                            echo '<a href="' . $_pdf_path . '" target="_blank" class="button button-small button-secondary udraw-download-pdf-button" style="width: 125px; text-align: center;" download>Download PDF</a>';
                        } else {
                            if (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0) {
                                echo '<a href="#" class="button button-small button-secondary udraw-download-order-item" data-friendly="' . $friendly_item_name . '" data-product="' . $_udraw_product_data . '" data-id="' . $item_id . '" style="width: 125px; text-align: center;">Download PDF</a>';
                            }
                        }
                    }

                    // Download JPG Pages
                    if (is_array(wc_get_order_item_meta($item_id, '_udraw_jpg_pages', true))) {
                        echo '<a href="#" class="button button-small button-secondary download_jpg_pages_btn" style="width: 125px; text-align: center;" data-jpgPages="'. implode(",", wc_get_order_item_meta($item_id, '_udraw_jpg_pages', true)).'">Download JPG(s)</a>';
                    }

                    // Download PNG Pages
                    if (is_array(wc_get_order_item_meta($item_id, '_udraw_png_pages', true))) {
                        echo '<a href="#" class="button button-small button-secondary download_png_pages_btn" style="width: 125px; text-align: center;" data-pngPages="'. implode(",", wc_get_order_item_meta($item_id, '_udraw_png_pages', true)).'">Download PNG(s)</a>';
                    }

                    //Rebuild PDF button
                    if ( uDraw::is_udraw_okay() && ( (isset($product['udraw_product_data']) && strlen($product['udraw_product_data']) > 0) ||  ( isset($product['udraw_pdf_block_product_id']) ) || ( isset($product['udraw_pdf_xmpie_product_id']) ) ) ) {
                        echo '<a class="button button-small button-secondary udraw-preview-order-item" href="' . $rebuild_url . '" style="width: 125px; text-align: center;">Rebuild PDF</a>';
                    }
                    
                    // Download Uploaded Files
                    if (isset($product['udraw_options_uploaded_files'])&& strlen($product['udraw_options_uploaded_files']) > 0) {
                        $uploaded_files = json_decode(stripcslashes($product['udraw_options_uploaded_files']));
                        if (is_array($uploaded_files)) {
                            echo '<a href="#" class="button button-small button-secondary download_uploaded_files_btn" style="width: 125px; text-align: center;" data-files="'. implode(",", array_column($uploaded_files, 'url')) .'">Download File(s)</a>';
                        }
                    }
                }

                do_action('udraw_admin_order_item_extras', $item, $item_id);
                echo '</td>';
            }
        }
        
        public function woo_udraw_admin_product_order_view() {  
            add_thickbox();
            ?>
            <script>
            var _udraw_order_item_action = '';
            var _udraw_current_item_id = '';
            var _udraw_selected_element;
            var _udraw_current_friendly_name = '';

            jQuery('.udraw-show-order-item').click(function (evt) {
                jQuery('html, body').animate({ scrollTop: jQuery('#udraw-order').offset().top }, 'slow');
                jQuery('#udraw-order').removeClass('closed');
                evt.preventDefault();

                _udraw_order_item_action = 'view';
                _udraw_current_item_id = jQuery(this).data("id");
                if (jQuery(this).data("product").length < 100) {
                    RacadDesigner.HideDesigner();
                    jQuery.getJSON(ajaxurl + '?action=udraw_get_template_data&template_path=' + jQuery(this).data("product"),
                        function (data) {
                            RacadDesigner.Legacy.loadCanvasDesignXML(data);
                        }
                    );
                } else {
                    RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode(jQuery(this).data("product")));
                }
            });

            jQuery('.udraw-download-order-item').click(function(evt) {
                _udraw_order_item_action = 'download';
                _udraw_current_item_id = jQuery(this).data("id");
                _udraw_current_friendly_name = jQuery(this).data("friendly");
                if (jQuery(this).data("product").length < 100) {
                    RacadDesigner.HideDesigner();
                    jQuery.getJSON(ajaxurl + '?action=udraw_get_template_data&template_path=' + jQuery(this).data("product"),
                        function (data) {
                            RacadDesigner.Legacy.loadCanvasDesignXML(data);
                        }
                    );
                } else {
                    RacadDesigner.Legacy.loadCanvasDesignXML(Base64.decode(jQuery(this).data("product")));
                }
                _udraw_selected_element = jQuery(this);
                _udraw_selected_element.text("Please Wait...");
                evt.preventDefault();
            });

            jQuery('.download_jpg_pages_btn').click( function( e ) {
                e.preventDefault();
                var pages = jQuery(this).data('jpgpages');
                if(pages.length > 0) {                    
                    download_multiple_files(pages.split(','), 0)
                }
            });

            jQuery('.download_png_pages_btn').click( function( e ) {
                e.preventDefault();
                var pages = jQuery(this).data('pngpages');
                if(pages.length > 0) {                    
                    download_multiple_files(pages.split(','), 0)
                }
            });

            jQuery('.download_uploaded_files_btn').click( function( e ) {
                e.preventDefault();
                var pages = jQuery(this).data('files');
                if(pages.length > 0) {                    
                    download_multiple_files(pages.split(','), 0)
                }
            });
            
            function download_multiple_files(files, count) {
                if (count < files.length) {
                    var tmpDownloadLink = document.createElement("a");
                    tmpDownloadLink.style.display = 'none';
                    document.body.appendChild( tmpDownloadLink );
                    tmpDownloadLink.setAttribute( 'href', files[count] );
                    tmpDownloadLink.setAttribute( 'download', '');
                    tmpDownloadLink.click();
                    setTimeout(function() {
                        download_multiple_files(files, count+1);
                    }, 500);
                } else {
                    return;
                }
            } 

            jQuery(document).ready(function() {
                jQuery('#udraw-order').removeClass('closed').addClass('closed');
                jQuery('.udraw-product-heading').css("min-width", "260px");
                jQuery('.udraw-product-heading').css("text-align", "center");
            });
            function __loaded_udraw_design() {
                if (_udraw_order_item_action == 'download') {
                    setTimeout(function () {
                        if (_udraw_current_friendly_name.length > 0) {
                            RacadDesigner.ExportToMultiPagePDF(_udraw_current_friendly_name, false);
                        } else {
                            RacadDesigner.ExportToMultiPagePDF('uDraw_Order_<?php echo($_GET['post'])?>_' + _udraw_current_item_id, false);
                        }
                        _udraw_order_item_action = '';
                        jQuery('#udraw-order').removeClass('closed').addClass('closed');

                        if (_udraw_selected_element) {
                            setTimeout(function () {
                                _udraw_selected_element.text("Download PDF");
                                _udraw_selected_element = undefined;
                            }, 5000);                            
                        }
                    }, 1000);
                }

                setTimeout(function () {
                    jQuery("body").css("overflow", "auto");
                }, 1000);
            }
            </script>
            <style>
                .order_data_column .select2-container {
                    z-index: 0 !important;
                }
            </style>
            <?php
            $uDraw = new uDraw();
            $uDraw->register_jquery_css();
            $uDraw->registerBootstrapJS();
            $uDraw->registerStyles();
            $uDraw->register_jquery_ui();
            $uDraw->register_designer_min_js();
            
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            if ($_udraw_settings['udraw_designer_enable_threed']) {
                $uDraw->register_designer_threed_min_js();
            }
            $uDraw->registerDesignerDefaultStyles();
            $uDraw->registerScripts();
                    
            require_once(UDRAW_PLUGIN_DIR . "/designer/designer-admin-order.php");
        }
        
        public function woo_udraw_admin_pdf_product_order_view() {
            add_thickbox(); 
            wp_register_style('bootstrap_css', UDRAW_PLUGIN_URL . '/assets/bootstrap/css/bootstrap.min.css');
            wp_enqueue_style('bootstrap_css');
            
            $uDraw = new uDraw();
            $uDraw->registerStyles();
            $uDraw->registerBootstrapJS();
            $uDraw->registerPDFBlocksJS();
            $uDraw->registerjQueryFileUpload();
            
            // Load up the PDF template interface
            require_once(UDRAW_PLUGIN_DIR . "/pdf-blocks/templates/admin/pdf-block-order-admin.php");
            
            $GoEpower = new GoEpower();
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            $goepower_token = '';
            if ($_udraw_settings['goepower_username'] !== null && $_udraw_settings['goepower_password'] !== null && strlen($_udraw_settings['goepower_username']) > 0 && strlen($_udraw_settings['goepower_password']) > 0)  {
                $_auth_object = $GoEpower->get_auth_object();
                $goepower_token = $_auth_object->Token;
                //echo '<script type="text/javascript">var goepower_token="'. $_auth_object->Token .'";</script>';
                //echo '<script type="text/javascript">var goepower_custom_id="'. $_auth_object->CustomID .'";</script>';
            }
            
            $preview_mode = "image";
            if ($_udraw_settings['goepower_preview_mode'] == "pdf") { $preview_mode = "pdf"; }
            
            ?>
            <script>
                var _udraw_pdf_block_product_id = '';
                var _previous_pdf_block_entries = '';
                var appPath =  '<?php echo $GoEpower->get_api_url(); ?>/';
                var _download_requested = false;
                jQuery('.udraw-show-pdf-order-item').click(function (evt) {
                    jQuery('html,body').animate({ scrollTop: jQuery('#udraw-pdf-order').offset().top - 40 }, 700);
                    jQuery('#udraw-pdf-order').removeClass('closed');
                    _udraw_pdf_block_product_id = jQuery(this).data("id");
                    _previous_pdf_block_entries = JSON.parse(Base64.decode(jQuery(this).data("product")));
                    
                    var _pdf_container_element = '#w2p-pdf-template-product'
                    jQuery(_pdf_container_element).empty();
                    window.w2p_bm = BlocksManager.init(_pdf_container_element, {
                        goepower_token : '<?php echo $goepower_token ?>',
                        product_unique_id : _udraw_pdf_block_product_id,
                        preview_element: '#w2p-pdf-template-preview',
                        hide_text_labels: false,
                        api_path: appPath,
                        pid: _udraw_pdf_block_product_id,
                        upload_path: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                        upload_file_data: {
                            action: 'udraw_pdf_block_upload',
                            session: '<?php echo uniqid() ?>'
                        },
                        previous_block_entries: _previous_pdf_block_entries,
                        preview_mode: '<?php echo $preview_mode ?>',
                        allow_download_pdf: false,
                        pdf_viewer_path: '<?php echo UDRAW_PLUGIN_URL ?>/assets/pdfjs/web/viewer.php?file='
                    });

                    jQuery(w2p_bm.options.preview_element).on('get_blocks_completed', function(){
                        jQuery('select', _pdf_container_element).addClass('udrawProductSelect2 dropdownList form-control');
                        __process_pdf_preview();
                    });         
                    evt.preventDefault();
                });

                jQuery('#pdf-block-preview-btn').click(function (evt) {
                    __process_pdf_preview();
                    evt.preventDefault();
                });

                jQuery('#pdf-block-download-btn').click(function (evt) {
                    __process_pdf_preview(function(pdf_url){
                        var goepower_url = 'https://live.goepower.com/';
                        if (w2p_bm.options.api_path.indexOf('w2pshop') > 0) {
                            goepower_url = 'https://live.w2pshop.com/';
                        }
                        if (pdf_url.indexOf(goepower_url) > -1) {
                            goepower_url = '';
                        }
                        window.open(goepower_url + pdf_url, '_blank');
                    });
                    evt.preventDefault();
                });


                jQuery(document).ready(function() {
                    jQuery('#udraw-pdf-order').removeClass('closed').addClass('closed');
                });
            </script>
            <?php
        }
    }
}
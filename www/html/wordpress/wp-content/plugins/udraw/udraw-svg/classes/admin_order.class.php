<?php
if (!class_exists('SVG_admin_orders')) {
    class SVG_admin_orders {
        public function __construct () { }
        public function init_actions () {
            add_action('woocommerce_admin_order_item_headers', array( &$this, 'admin_order_item_header' ) ); //table header in WC->Orders
            add_action('woocommerce_admin_order_item_values', array( &$this, 'admin_order_item_values' ), 10, 3 ); //table cell in WC->Orders
            add_filter('woocommerce_admin_order_item_thumbnail', array($this, 'admin_order_item_thumbnail'), 99, 3); //WC->Orders thumbnail
            add_action( 'woocommerce_admin_order_data_after_order_details', array(&$this, 'admin_order_add_svgdesigner') ); //Add hidden div to include designer.
            
            add_action('wp_ajax_udraw_svg_get_order_item_data', array(&$this, 'udraw_svg_get_order_item_data'));
            add_action('wp_ajax_udraw_svg_update_svg_url', array(&$this, 'udraw_svg_update_svg_url'));
        }
        public function admin_order_item_header ($order) {
            ?>
                <th class="udraw_svg_product" style="min-width:260px; text-align:center;">uDraw SVG</th>
            <?php
        }
        public function admin_order_item_values ($_product, $item, $item_id) {
            global $woocommerce, $post, $wp_meta_boxes;
            //get order id
            if (get_class($item) === 'WC_Order_Refund' || strpos(get_class($item), 'OrderRefund') !== false) {
                return;
            }
            ?>
                <td class="udraw_svg_product" style="width:150px">
            <?php
            if (isset($item['udraw_SVG_data'])) {
                $uDraw_SVG = new uDraw_SVG();
                $uDraw_SVG->register_jquery();
                $uDraw_SVG->register_SVGDesigner();
                
                add_thickbox();
                $data = $item['udraw_SVG_data'];
                if (isset($data['udraw_SVG_design_preview'])) {
                    $design_page_data = $data['udraw_SVG_design_preview'];
                ?>
                    <a href="#TB_inline?width=600&height=550&inlineId=<?php echo $item_id; ?>_preview" class="button button-small button-secondary udraw_svg_control thickbox" onclick="javascript:window.tb_show('View Design', '#TB_inline?width=600&height=550&inlineId=<?php echo $item_id; ?>_preview');">View Design</a>
                    <div id="<?php echo $item_id; ?>_preview" style="display:none;">
                        <div class="preview_container">
                            <div style="display: inline-block; padding: 5px;">
                                <img src="<?php echo $design_page_data ?>" style="max-width: 250px; max-height: 250px; border: 1px solid #ccc;"/>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $pdf_path = '';
                    if (isset($item['_udraw_SVG_PDF'])) {
                        $pdf_path = $item['_udraw_SVG_PDF'];
                    }
                    $disabled = '';
                    if (strlen($pdf_path) === 0) { 
                        $disabled = ' disabled ';
                    }
                    ?>
                    <a href="#TB_inline?width=800&height=800&inlineId=edit_design" 
                       class="button button-small button-secondary udraw_svg_control edit_svg_design thickbox" 
                       data-order_item_id="<?php echo $item_id ?>"
                       onclick="javascript:window.tb_show('Edit Design', '#TB_inline?width=800&height=800&inlineId=edit_design');"><?php _e('Edit Design', 'udraw_svg'); ?></a>
                    <a href="#"  class="button button-small button-secondary udraw_svg_control download_svg_pdf" data-order_item_id="<?php echo $item_id ?>" onclick="window.open('<?php echo $pdf_path ?>')" <?php echo $disabled ?> ><?php _e('Download PDF', 'udraw_svg'); ?></a>
                    <a href="#" class="button button-small button-secondary udraw_svg_control rebuild_svg_pdf" data-order_item_id="<?php echo $item_id ?>"><?php _e('Rebuild PDF', 'udraw_svg'); ?></a>
                    
                    <script>
                        jQuery(document).ready(function($){
                            if (!$('div#udraw_svg_product_viewer').hasClass('closed')) {
                                $('div#udraw_svg_product_viewer button.handlediv').trigger('click');
                            }
                            $('a.rebuild_svg_pdf[data-order_item_id="<?php echo $item_id ?>"]').on('click', function(){
                                var item_id = $(this).attr('data-order_item_id');
                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                                    type: 'POST',
                                    contentType: "application/x-www-form-urlencoded",
                                    dataType: "json",
                                    data: {
                                        action: 'udraw_svg_build_pdf',
                                        order_item_id: item_id,
                                        order_id: <?php echo $post->ID ?>
                                    },
                                    success: function(response){
                                        $('a.download_svg_pdf[data-order_item_id="' + item_id + '"]').attr('disabled', true);
                                        if (response) {
                                            window.open(response.url);
                                        } else {
                                            __listen_download(item_id, function(url){
                                                $('a.download_svg_pdf[data-order_item_id="' + item_id + '"]').attr({
                                                    onclick: 'window.open("' + url + '")',
                                                    disabled: false
                                                });
                                            });
                                            window.alert('<?php _e('PDF rebuilding... Please check back in a few moments.', 'udraw_svg'); ?>');
                                        }
                                    },
                                    error: function (){

                                    }
                                });
                            });
                            
                            if ('<?php echo $pdf_path ?>'.length === 0) {
                                __listen_download(<?php echo $item_id ?>, function(url){
                                    $('a.download_svg_pdf[data-order_item_id="' + <?php echo $item_id ?> + '"]').attr({
                                        onclick: 'window.open("' + url + '")',
                                        disabled: false
                                    });
                                });
                            }
                        
                            function __listen_download (item_id, callback) {
                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                                    type: 'POST',
                                    contentType: "application/x-www-form-urlencoded",
                                    dataType: "json",
                                    data: {
                                        action: 'udraw_svg_check_pdf_download',
                                        order_item_id: item_id,
                                        order_id: <?php echo $post->ID ?>
                                    },
                                    success: function(url){
                                        if (typeof url === 'string' && url.length > 0) {
                                            if (typeof callback === 'function') {
                                                callback(url);
                                            }
                                        } else {
                                            //Check again in 30seconds
                                            setTimeout(function(){
                                                __listen_download (item_id, callback);
                                            }, 15000);
                                        }
                                    },
                                    error: function (){

                                    }
                                });
                            }
                        });
                    </script>
                    <style>
                        a.udraw_svg_control {
                            width: 45%;
                            text-align: center;
                        }
                    </style>
                <?php
                }
                if (isset($data['udraw_SVG_uploaded_artwork'])) {
                    ?>
                    <a href="#TB_inline?width=600&height=550&inlineId=<?php echo $item_id; ?>_uploaded_files" class="button button-small button-secondary udraw_svg_control thickbox" 
                       onclick="javascript:window.tb_show('View Uploaded Files', '#TB_inline?width=600&height=550&inlineId=<?php echo $item_id; ?>_uploaded_files');">
                        <?php _e('View Uploaded Files', 'udraw_svg'); ?>
                    </a>
                    <div id="<?php echo $item_id; ?>_uploaded_files" style="display:none;">
                        <div class="preview_container">
                            <?php
                            $files = $data['udraw_SVG_uploaded_artwork'];
                            $count = 0;
                            foreach ($files as $page_name => $page_obj) {
                                $count++;
                            }
                            foreach($files as $page_name => $page_obj) {
                            ?>
                            <div class="row" style="padding: 5px;">
                                <?php
                                    $path_parts = pathinfo($page_obj->url);
                                    if ($path_parts['extension'] !== 'pdf') {
                                ?>
                                    <img src="<?php echo $page_obj->url ?>" class="col" style="max-height: 250px; border: 1px solid #ccc;"/>
                                <?php } 
                                if ($count > 1) {
                                ?>
                                <div class="col">
                                    <span><?php echo $page_name ?></span>
                                </div>
                                <?php } ?>
                                <div class="col">
                                    <a href="#" onclick="javascript: window.open('<?php echo $page_obj->url ?>', '_blank');" 
                                       class="btn btn-secondary"><?php _e('Download File', 'udraw_svg'); ?></a>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
                </td>
            <?php
        }
        public function admin_order_add_svgdesigner () {
            global $post;
            if (get_post_meta($post->ID, '_udraw_SVG_product', true)) {
                echo '<div id="edit_design" style="display:none;">';
                require_once(UDRAW_SVG_DIR . '/templates/admin/svg_admin_designer.php');
                echo '</div>';
                ?>
                <script>
                    jQuery(document).ready(function($){
                        $('[data-udrawsvg="SVGDesigner"] div.canvas_container').height($('[data-udrawsvg="SVGDesigner"] div.canvas_container').width());
                        $('a.edit_svg_design').on('click', function(){
                            var item_id = $(this).attr('data-order_item_id');
                            $.ajax({
                                url: '<?php echo admin_url('admin-ajax.php') ?>',
                                type: 'POST',
                                contentType: "application/x-www-form-urlencoded",
                                dataType: "json",
                                data: {
                                    action: 'udraw_svg_get_order_item_data',
                                    order_item_id: item_id,
                                    order_id: <?php echo $post->ID ?>
                                },
                                success: function(response){
                                    if (response) {
                                        var svg_url = response.udraw_SVG_design_data;
                                        if (svg_url.substring(svg_url.length - 3) === 'svg') {
                                            RacadSVGDesigner.Load.file(svg_url, function () {
                                                RacadSVGDesigner.settings.design_file = svg_url;
                                                var base_url = RacadSVGDesigner.settings.design_file.split('output')[0];
                                                RacadSVGDesigner.settings.upload_path =  base_url + 'assets/';
                                                RacadSVGDesigner.settings.output_path = base_url + 'output/';
                                                RacadSVGDesigner.settings.export_path = base_url + 'export/';
                                                RacadSVGDesigner.settings.item_id = parseInt(item_id);
                                                RacadSVGDesigner.settings.order_id = <?php echo $post->ID ?>;
                                                RacadSVGDesigner.Zoom.scaleToFit();
                                            });
                                        } else if (svg_url.substring(svg_url.length - 4) === 'json') {
                                            RacadSVGDesigner.Load.json_file(svg_url, function () {
                                                RacadSVGDesigner.settings.design_file = svg_url;
                                                var base_url = RacadSVGDesigner.settings.design_file.split('output')[0];
                                                RacadSVGDesigner.settings.upload_path =  base_url + 'assets/';
                                                RacadSVGDesigner.settings.output_path = base_url + 'output/';
                                                RacadSVGDesigner.settings.export_path = base_url + 'export/';
                                                RacadSVGDesigner.settings.item_id = parseInt(item_id);
                                                RacadSVGDesigner.settings.order_id = <?php echo $post->ID ?>;
                                                RacadSVGDesigner.Zoom.scaleToFit();
                                            });
                                        }
                                    }
                                },
                                error: function (){

                                }
                            });
                        });
                    });
                </script>
                <?php
            }
        }
        public function admin_order_item_thumbnail ($image, $item_id, $item) {
            if (isset($item['_udraw_canvas_preview_path'])) {
                return $image;
            }
            if (isset($item['udraw_SVG_data'])) {
                $uDraw_SVG = new uDraw_SVG();
                $domain_url = $uDraw_SVG->getDomain();
                if (isset($item['udraw_SVG_data']['udraw_SVG_design_preview'])) {
                    $preview = $item['udraw_SVG_data']['udraw_SVG_design_preview'];
                    return '<img src="'. $domain_url . $preview.'" class="attachment-thumbnail size-thumbnail wp-post-image" style="width: auto; height: auto; max-width: 40px; max-height: 40px; vertical-align: -webkit-baseline-middle;" />';
                }
            }
            return $image;
        }
        public function udraw_svg_get_order_item_data ($order_item_id = '', $order_id = '') {
            if (isset($_REQUEST['order_id'])) {
                $order_id = intval($_REQUEST['order_id']);
            }
            if (isset($_REQUEST['order_item_id'])) {
                $order_item_id = intval($_REQUEST['order_item_id']);
            }
            if ($order_id === '' || $order_item_id === '') {
                echo false;
            }
            $udraw_SVG_data = wc_get_order_item_meta($order_item_id, 'udraw_SVG_data', true);
            echo json_encode($udraw_SVG_data);
            wp_die();
        }
        public function udraw_svg_update_svg_url () {
            $updated = 0;
            if (isset($_REQUEST['url'])) {
                $url = $_REQUEST['url'];
                if (isset($_REQUEST['order_item_id'])) {
                    $order_item_id = $_REQUEST['order_item_id'];
                    $udraw_SVG_data = wc_get_order_item_meta($order_item_id, 'udraw_SVG_data', true);
                    $new_data = array();
                    foreach ($udraw_SVG_data as $key => $value) {
                        $new_data[$key] = $value;
                    }
                    $new_data['udraw_SVG_design_data'] = $url['output_path'] . $url['document_name'];
                    $new_data['udraw_SVG_design_preview'] = $url['output_path'] . $url['preview_image'];
                    wc_update_order_item_meta($order_item_id, 'udraw_SVG_data', $new_data, $udraw_SVG_data);
                    $updated = 1;
                    echo $updated;
                    wp_die();
                }
            }
            echo $updated;
            wp_die();
        }
    }
}
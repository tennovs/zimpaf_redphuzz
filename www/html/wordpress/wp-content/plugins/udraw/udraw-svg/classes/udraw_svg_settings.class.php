<?php
if (!class_exists('uDraw_SVG_settings')) {
    class uDraw_SVG_settings {
        function __construct() {}
        function get_settings(){
            $settings = '';
            if (is_multisite()) {
                $settings = get_site_option('udraw_SVG_settings');
            } else {
                $settings = get_option('udraw_SVG_settings');
            }
            $default_settings = array(
                'udraw_svg_css_hook'                        => '',
                'udraw_svg_js_hook'                         => '',
                'udraw_SVGDesigner_enable_dpi'              => false,
                'udraw_SVGDesigner_minimum_dpi'             => 0,
                'udraw_svg_debug_pdf_production'            => false,
                'udraw_SVGDesigner_tab_text_editor'         => false,
                'udraw_SVGDesigner_display_tools_top'       => false,
                'udraw_SVGDesigner_skin'                    => 'default',
                'udraw_SVGDesigner_enable_stock_images'     => true,
                'udraw_SVGDesigner_stock_images_list'       => array('clipart', 'pixabay', 'pexel', 'unsplash', 'private'),
                'udraw_SVGDesigner_display_layers'          => false,
                'udraw_SVGDesigner_load_tutorial'           => false, //Widescreen UI only
                'udraw_SVGDesigner_display_image_name'      => false,
                'udraw_SVGDesigner_embed_images'            => false,
                'udraw_SVGDesigner_display_rulers'          => false,
                'udraw_SVGDesigner_display_proof'           => false
            );
            foreach($default_settings as $key => $value) {
                if (!isset($settings[$key])) {
                    $settings[$key] = $value;
                }
            }
            if (is_multisite()) {
                update_site_option('udraw_SVG_settings', $settings);
            } else {
                update_option('udraw_SVG_settings', $settings);
            }
            return $settings;
        }
        function update_settings() {
            if ($_POST['save_udraw_settings']) {
                $settings = $this->get_settings();
                $settings['udraw_svg_css_hook']                     = (isset($_POST['udraw_svg_css_hook'])) ? stripslashes($_POST['udraw_svg_css_hook']) : '';
                $settings['udraw_svg_js_hook']                      = (isset($_POST['udraw_svg_js_hook'])) ? stripslashes($_POST['udraw_svg_js_hook']) : '';
                $settings['udraw_svg_debug_pdf_production']         = (isset($_POST['udraw_svg_debug_pdf_production'])) ? true : false;
                $settings['udraw_SVGDesigner_tab_text_editor']      = (isset($_POST['udraw_SVGDesigner_tab_text_editor'])) ? true : false;
                $settings['udraw_SVGDesigner_display_tools_top']    = (isset($_POST['udraw_SVGDesigner_display_tools_top']) && 
                                                                             $_POST['udraw_SVGDesigner_display_tools_top'] === 'top') ? true : false;
                $settings['udraw_SVGDesigner_skin']                 = (isset($_POST['udraw_SVGDesigner_skin'])) ? $_POST['udraw_SVGDesigner_skin'] : 'default';
                $settings['udraw_SVGDesigner_load_tutorial']        = ($settings['udraw_SVGDesigner_skin'] === 'widescreen' && 
                                                                       isset($_POST['udraw_SVGDesigner_load_tutorial'])) ? $_POST['udraw_SVGDesigner_load_tutorial'] : false;
                
                if (isset($_POST['udraw_SVGDesigner_enable_dpi'])) {
                    $settings['udraw_SVGDesigner_enable_dpi'] = true;
                    $dpi_value = 0;
                    if (isset($_POST['udraw_SVGDesigner_minimum_dpi'])) {
                        $dpi_value = intval($_POST['udraw_SVGDesigner_minimum_dpi']);
                    }
                    $settings['udraw_SVGDesigner_minimum_dpi'] = $dpi_value;
                } else {
                    $settings['udraw_SVGDesigner_enable_dpi'] = false;
                }
                
                $settings['udraw_SVGDesigner_enable_stock_images'] = isset($_POST['udraw_SVGDesigner_enable_stock_images']);
                $enable_array = array();
                $sources_array = array(
                    'clipart'       => isset($_POST['clipart']),
                    'pixabay'       => isset($_POST['pixabay']),
                    'pexel'         => isset($_POST['pexel']),
                    'unsplash'      => isset($_POST['unsplash']),
                    'private'       => isset($_POST['private'])
                );
                
                foreach ($sources_array as $source => $bool) {
                    if ($bool) {
                        array_push($enable_array, $source);
                    }
                }
                $settings['udraw_SVGDesigner_stock_images_list'] = $enable_array;
                //Disable enable stock images if the array is empty.
                if (count($enable_array) === 0) {
                    $settings['udraw_SVGDesigner_enable_stock_images'] = false;
                }
                
                $settings['udraw_SVGDesigner_display_layers']       = isset($_POST['udraw_SVGDesigner_display_layers']);
                $settings['udraw_SVGDesigner_display_image_name']   = isset($_POST['udraw_SVGDesigner_display_image_name']);
                $settings['udraw_SVGDesigner_embed_images']         = isset($_POST['udraw_SVGDesigner_embed_images']);
                $settings['udraw_SVGDesigner_display_rulers']       = isset($_POST['udraw_SVGDesigner_display_rulers']);
                $settings['udraw_SVGDesigner_display_proof']        = isset($_POST['udraw_SVGDesigner_display_proof']);
                    
                if (is_multisite()) {
                    update_site_option('udraw_SVG_settings', $settings);
                } else {
                    update_option('udraw_SVG_settings', $settings);
                }
            }
        }
    }
}
?>
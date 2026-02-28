<?php

if (!class_exists('uDrawPdfXMPie')) {
    class uDrawPdfXMPie {
        
        function __contsruct() { }
        
        function init() {            
            add_action( 'wp_ajax_udraw_pdf_block_get_templates', array(&$this, 'handle_ajax_get_templates') );
            add_action( 'wp_ajax_udraw_xmpie_get_templates', array(&$this, 'handle_ajax_get_templates') );
            
            add_action( 'wp_ajax_nopriv_udraw_pdf_block_blob_upload', array(&$this,'handle_ajax_upload_blob') );

        }
        
        function get_company_products() {
            $goEpower = new GoEpower();
            return $goEpower->get_company_products_by_type("xmpie");
        }
        
        function get_product($product_id) {
            $all_products = $this->get_company_products();
            for ($x = 0; $x < count($all_products); $x++) {
                if ($all_products[$x]['ProductID'] == $product_id) {
                    return $all_products[$x];
                }
            }
            return null;
        }
        
        public static function is_pdf_block_product($product_id) {
            if (get_post_meta($product_id, '_udraw_product', true) == 'true') {
                if (get_post_meta($product_id, '_udraw_block_template_id', true) > 0) {
                    return true;
                }
            }
            
            return false;
        }
        
        function handle_ajax_upload_blob() 
        {
            // Saving Blob Image.
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_REQUEST['imageData']));
            $image_name = uniqid('uatemp_') . '.png';
            file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $image_name, $data);
            echo UDRAW_TEMP_UPLOAD_URL . $image_name;
            wp_die();
        }
        
        function handle_ajax_get_templates() {
            if (isset($_REQUEST['xmpie-template-id'])) {    
                $xmpie_template = $this->get_product($_REQUEST['xmpie-template-id']);
                
                if (!is_null($xmpie_template)) {
                    echo json_encode($xmpie_template);
                } else {
                    echo json_encode(false);
                }                
            }
            wp_die();
        }
        
    }
}

?>
<?php
if (!class_exists('uDrawLocalConvert')) {
    class uDrawLocalConvert extends uDrawAjaxBase {                
        
        function __contsruct() { }
        
        public function init_actions() {
            //add_action( 'wp_ajax_udraw_local_convert_pdf', array(&$this,'convertSVGtoPDF') );
        }

        public function convertSVGtoPDF($design_path, $callback_url) {
            require_once(UDRAW_PLUGIN_DIR. '/vendor/docraptor/docraptor/autoload.php');
            $configuration = DocRaptor\Configuration::getDefaultConfiguration();
            $api_key = "YOUR_API_KEY_HERE";
            $configuration->setUsername($api_key);
            $configuration->setSSLVerification(false);
            $docraptor = new DocRaptor\DocApi();

            $doc = new DocRaptor\Doc();
            $doc->setTest(true);
            $doc->setDocumentUrl($design_path);
            $doc->setName($_SERVER['HTTP_HOST']);
            $doc->setDocumentType('pdf');
            //Callback url for when the job is finished
            $doc->setCallbackUrl($callback_url);
            
            $create_response = $docraptor->createAsyncDoc($doc);
            return $create_response;
        }
    }
}
<?php
if (!class_exists('uDrawExcelHandler')) {
    class uDrawExcelHandler extends uDrawAjaxBase {
        
        function __construct() { }
        
        function init_actions() {
            add_action ('check_udraw_pdfs', array(&$this, 'check_to_resume_job'), 10, 2);
                    
            add_action( 'wp_ajax_udraw_designer_excel_generate_entry_page_data', array(&$this, 'generate_entry_page_data') );
            add_action( 'wp_ajax_udraw_designer_excel_compile_entry_data', array(&$this, 'compile_entry_data') );
            add_action( 'wp_ajax_udraw_designer_generate_structure_file', array(&$this, 'generate_structure_file') );
            add_action( 'wp_ajax_udraw_designer_structure_file_upload', array(&$this, 'structure_file_upload') );
            add_action( 'wp_ajax_udraw_designer_read_excel_file', array(&$this, 'read_excel_file') );
            add_action( 'wp_ajax_udraw_designer_load_excel_session', array(&$this, 'load_excel_session') );
            add_action( 'wp_ajax_udraw_empty_target_folder', array(&$this, 'empty_target_folder') );
            add_action( 'wp_ajax_udraw_package_excel_designs', array(&$this, 'package_excel_designs') );
            add_action( 'wp_ajax_udraw_excel_check_for_zip_file', array(&$this, 'check_for_zip_file') );
            add_action( 'wp_ajax_udraw_excel_resume_pdf_job', array(&$this, 'resume_pdf_job') );
            
            add_action( 'wp_ajax_nopriv_udraw_designer_excel_generate_entry_page_data', array(&$this, 'generate_entry_page_data') );
            add_action( 'wp_ajax_nopriv_udraw_designer_excel_compile_entry_data', array(&$this, 'compile_entry_data') );
            add_action( 'wp_ajax_nopriv_udraw_designer_generate_structure_file', array(&$this, 'generate_structure_file') );
            add_action( 'wp_ajax_nopriv_udraw_designer_structure_file_upload', array(&$this, 'structure_file_upload') );
            add_action( 'wp_ajax_nopriv_udraw_designer_read_excel_file', array(&$this, 'read_excel_file') );
            add_action( 'wp_ajax_nopriv_udraw_designer_load_excel_session', array(&$this, 'load_excel_session') );
        }
        public function generate_structure_file ($pages = '') {
            if (isset($_REQUEST['pages'])) {
                $pages = json_decode(stripslashes($_REQUEST['pages']));
            } else {
                return $this->sendResponse('fail'); 
            }
            //require PHPexcel
            require_once(UDRAW_PLUGIN_DIR. '/assets/PHPExcel/Classes/PHPExcel.php');
            $excelObj = new PHPExcel();
            $excelObj->getProperties()
                            ->setCreator('user')
                            ->setLastModifiedBy('user')
                            ->setTitle('uDraw Structure Form')
                            ->setSubject('uDraw Structure Form')
                            ->setDescription('uDraw Structure Form')
                            ->setKeywords('uDraw')
                            ->setCategory('uDrawDesigner');
            $excelObj->setActiveSheetIndex(0);
            $worksheet = $excelObj->getActiveSheet();
            $worksheet->setTitle("Instructions");
            $worksheet->SetCellValueByColumnAndRow(0, 1, "1) Fill out the file with necessary information, starting with the row immediately under the labels.");
            $worksheet->SetCellValueByColumnAndRow(0, 2, "Each entry will require its own row, and will generate its own design file.");
            $worksheet->SetCellValueByColumnAndRow(0, 3, "Example: ");
            $worksheet->SetCellValueByColumnAndRow(0, 4, "First-name");
            $worksheet->SetCellValueByColumnAndRow(0, 5, "First entry");
            $worksheet->SetCellValueByColumnAndRow(0, 6, "Second entry");
            $worksheet->SetCellValueByColumnAndRow(0, 7, "Two design files will now be generated; One for 'first entry' and one for 'second entry'.");
            $worksheet->SetCellValueByColumnAndRow(0, 8, "4) Each page in the design has its own page in the excel file for labels.");
            $worksheet->SetCellValueByColumnAndRow(0, 9, "Be sure to check through all pages to make sure all entries are filled in. Some pages may be empty, if no items on that page is labelled.");
            $worksheet->SetCellValueByColumnAndRow(0, 10, "5) After the file have been uploaded, you will be required to preview each entry. Please follow the on-screen instructions.");
            $worksheet->SetCellValueByColumnAndRow(0, 11, "You may add or modify design elements as you see fit. We don't recommend removing any labelled elements, as it will not appear in any of the entries.");
            $worksheet->SetCellValueByColumnAndRow(0, 12, "6) You may re-upload the file if modifications needed to be made, but all previous entries will be overwritten.");
            $worksheet->SetCellValueByColumnAndRow(0, 13, "DISCLAIMER: This excel file should not be modified in any way other than entering information.");
            $worksheet->SetCellValueByColumnAndRow(0, 14, "We cannot guarentee that the design files generated from this file will be correct if the structure of this file have been tempered with.");
            $styleArray = array(
                'font' => array(
                    'italic' => true
                )
            );
            $worksheet->getStyle('A5')->applyFromArray($styleArray);
            $worksheet->getStyle('A6')->applyFromArray($styleArray);
            $worksheet->getColumnDimension('A')->setAutoSize(true);
            
            for ($i = 0; $i < count($pages); $i++) {
                $colNum = 0;
                $worksheet = $excelObj->createSheet($i);
                $worksheet->setTitle("Page " . ($i + 1));
                for ($j = 0; $j < count($pages[$i]); $j++) {
                    if ($pages[$i][$j]->isAssigned) {
                        if ($pages[$i][$j]->type === 'text') {
                            $worksheet->SetCellValueByColumnAndRow($colNum, 1, $pages[$i][$j]->name);
                            $worksheet->getColumnDimensionByColumn($colNum)->setAutoSize(true);
                            $colNum++;
                        }
                    }
                }
            }
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="udraw-structure-form.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
        
        function structure_file_upload () {
            $uDrawUpload = new uDrawUpload();
            $files = array();
            $fileObj = new stdClass();
            
            $_session_id = uniqid();
            $_upload_session_id = 'excel_'.uniqid();
            if (isset($_REQUEST['session']) && isset($_REQUEST['uploadsession'])) {
                $_session_id = $_REQUEST['session'];
                $_upload_session_id = $_REQUEST['uploadsession'];
            }

            // Set both upload folders and url location.
            $upload_dir = UDRAW_TEMP_UPLOAD_DIR . $_session_id . "/" . $_upload_session_id . "/";
            $upload_url = UDRAW_TEMP_UPLOAD_URL . $_session_id . "/" . $_upload_session_id . "/";

            // Create directory if doesn't exist.
            if (!is_dir($upload_dir)) {
                wp_mkdir_p($upload_dir);
            }
            
            // Check file exstension
            $fileName = pathinfo($_FILES['structureFile']['name'], PATHINFO_FILENAME);
            $fileExt = strtolower(pathinfo($_FILES['structureFile']['name'], PATHINFO_EXTENSION));
            
            // New Filename
            $newFile = rand(1, 32) .'_'. str_replace(' ','', $fileName) . '.' . $fileExt;
            $fileObj->name = $newFile;
            
            $validExt = array (
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'csv' => 'text/csv'
            );
            
            $uploaded_files = $uDrawUpload->handle_upload($_FILES['structureFile'], $upload_dir, $upload_url, $validExt);
            if (is_array($uploaded_files) && !key_exists('error', $uploaded_files[0])) {
                $fileObj->name = basename($uploaded_files[0]['file']);
                $fileObj->size = filesize($uploaded_files[0]['file']);
                $fileObj->url = $uploaded_files[0]['url'];
            } else {
                $fileObj->error = "Upload Failed";
            }
            
            array_push($files, $fileObj);
            $files['uploadSessionID'] = $_upload_session_id;
            echo json_encode($files);
            wp_die();
        }
        
        function read_excel_file ($excel = '') {
            //excel->filename, excel->path, excel->sessionID
            if (isset($_REQUEST['excel'])) {
                $excel = json_decode(stripslashes($_REQUEST['excel']));
            }
            $response = $this->__read_excel_file($excel);
            return $this->sendResponse($response);
        }
        
        public function empty_target_folder ($target_dir = '') {
            $uDrawUtil = new uDrawUtil();
            if (!isset($_REQUEST['target_dir'])) { return $this->sendResponse(false); }
            $target_dir = $_REQUEST['target_dir'];
            clearstatcache();
            if (!file_exists($target_dir)) { return $this->sendResponse(true); }
            $uDrawUtil->empty_target_folder($target_dir);
            return $this->sendResponse(true);
        }
        
        public function check_excel_folder_for_packaging ($order_id, $item_id) {
            $target_path = UDRAW_ORDERS_DIR . 'uDraw-Order-'. $order_id . '-' . $item_id . '/';
            if (!file_exists($target_path)) {
                return false;
            }
            
            $pdf_array = array();
            $files = glob($target_path . '*'); // get all file names
            foreach($files as $file){
                if(is_file($file)) {
                    $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (strtolower($fileExt) === 'pdf') {
                        array_push($pdf_array, $file);
                    }
                }
            }
            //Package the files into zip file
            $zipFileName = 'uDraw-Order-'. $order_id . '-' . $item_id . '.zip';
            $this->package_excel_designs($target_path, $target_path . $zipFileName, true);
        }
        
        public function package_excel_designs($target_dir = '', $destination = '', $overwrite = false) {
            if (isset($_REQUEST['target_dir'])) {
                $target_dir = base64_decode($_REQUEST['target_dir']);
            }
            if (isset($_REQUEST['destination'])) {
                $destination = $_REQUEST['destination'];
            }
            if (isset($_REQUEST['overwrite'])) {
                $overwrite = $_REQUEST['overwrite'];
            }
            //Create an array to store file information in
            $dataArray = array();
            $files = glob($target_dir . '*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file)) {
                    $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (strtolower($fileExt) === 'pdf') {
                        array_push($dataArray, (object)['directory'=>$file, 'name'=>str_replace($target_dir, '', $file)] );
                    }
                }
            }
            $uDrawUtil = new uDrawUtil();
            $this->sendResponse($uDrawUtil->create_zip_file($dataArray, $destination, $overwrite));
        }
        
        public function check_for_zip_file ($order_id = '', $item_id = '', $isAjax = true) {
            if (isset($_REQUEST['order_id']) && isset($_REQUEST['item_id'])) {
                $order_id = $_REQUEST['order_id'];
                $item_id = $_REQUEST['item_id'];
            }
            if ($order_id === '' || $item_id === '') {
                $this->sendResponse(false);
            }
            $target_path = UDRAW_ORDERS_DIR . 'uDraw-Order-'. $order_id . '-' . $item_id . '/';
            $zipFileName = 'uDraw-Order-'. $order_id . '-' . $item_id . '.zip';
            
            //Also get avg time per file
            $modifiedTimeArray = array();
            $files = glob($target_path . '*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file)) {
                    $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (strtolower($fileExt) === 'pdf') {
                        $filemtime = filemtime($file);
                        array_push($modifiedTimeArray, $filemtime);
                    }
                }
            }
            //Sort array from lowest to highest
            $timeAvg = 0;
            if (count($modifiedTimeArray) > 0) {
                sort($modifiedTimeArray);
                $timeAvg = ($modifiedTimeArray[count($modifiedTimeArray) - 1] - $modifiedTimeArray[0]) / count($modifiedTimeArray);
                //Time of last file created:
                $latest_file = $modifiedTimeArray[count($modifiedTimeArray) - 1];
                $latest_file_date = new DateTime();
                $latest_file_date->setTimestamp($latest_file);
                $dt = new DateTime();
                $date_diff = $dt->getTimestamp() - $latest_file_date->getTimestamp(); //Time difference in seconds
                if ($date_diff > (5 * 60)) { //If time difference is greater than 5 mins, the process probably stopped
                    $timeAvg = null;
                }
            }
            
            if (file_exists($target_path . $zipFileName)) {
                $success = true;
            } else {
                $success = false;
            }
            $response = array(
                'success' => $success, 
                'timeAverage' => $timeAvg, 
                'fileCount' => count($modifiedTimeArray)
            );
            if ($isAjax){
                $this->sendResponse($response);
            } else {
                return $response;
            }
        }
        
        function resume_pdf_job ($order_id = '', $item_id = '') {
            global $wpdb;
            if (isset($_REQUEST['order_id']) && isset($_REQUEST['item_id'])) {
                $order_id = $_REQUEST['order_id'];
                $item_id = $_REQUEST['item_id'];
            }
            $table_name = $wpdb->prefix . 'udraw_excel_jobs';
            $job = $wpdb->get_row("SELECT * FROM $table_name WHERE order_id=$order_id AND item_id=$item_id", ARRAY_A);
            $success = false;
            if ($job) {
                $xmlFiles = unserialize($job['xmlFiles']);
                if (count($xmlFiles) > 0) {
                    $orderObject = array(
                        'data' => base64_encode(file_get_contents($xmlFiles[0])),
                        'xmlFile' => str_replace(UDRAW_STORAGE_DIR, UDRAW_STORAGE_URL, $xmlFiles[0]),
                        'order_id' => $order_id,
                        'key' => $item_id,
                        'type' => 'designer_excel'
                    );
                    wp_schedule_single_event(time() + 1, 'build_udraw_pdf', array( (object)$orderObject, $xmlFiles ) );
                    $success = true;

                    //Check on the jobs in 10 mins to make sure it's still going
                    wp_schedule_single_event(time() + (10 * 60), 'check_udraw_pdfs', array( $order_id, $item_id ) );
                }
            }
            $this->sendResponse($success);
        }
        
        function check_to_resume_job ($order_id, $item_id) {
            global $wpdb;
            $check = $this->check_for_zip_file($order_id, $item_id, false);
            //Check if job still exists first
            $table_name = $wpdb->prefix . 'udraw_excel_jobs';
            $job = $wpdb->get_row("SELECT * FROM $table_name WHERE order_id=$order_id AND item_id=$item_id", ARRAY_A);
            if ($job) {
                if (!$check['success']) {
                    if ($check['timeAverage'] === null) {
                        $this->resume_pdf_job($order_id, $item_id);
                    } else {
                        //Check again in 5 mins
                        wp_schedule_single_event(time() + (5 * 60), 'check_udraw_pdfs', array( $order_id, $item_id ) );
                    }
                }
            }
            //Otherwise don't do anything
        }
        
        function generate_entry_page_data() {
            $uDraw = new uDraw();
            try {
                $outputPath = $_REQUEST['outputPath'];
                $userSessionID = $_REQUEST['user_session_id'];
                
                if (strlen($outputPath) == 0) { echo "false"; return;}
                $outputDir = $uDraw->get_physical_path($outputPath);
                
                if (strlen($userSessionID) == 0) { echo "false"; return;}

                $docname = basename($_REQUEST['entryNo'], '.xml'); 
                if (strlen($docname) == 0) { echo "false"; return; }
                
                // Make sure the folder exists.
                if (gettype($outputDir) == 'boolean') {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "missing folders");
                    return;
                }
                
                // Check to see if page number and data was sent in request.
                if ( !strlen($_REQUEST['pageNo']) > 0 || !strlen($_REQUEST['pageData']) > 0 )  {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "No Docs Found");
                }
                
                $pageNo = $_REQUEST['pageNo'];
                $xml = $docname . '_' . $pageNo;
                
                //Create the $userSessionID folder to save all the entries in if it doesn't exist
                if (!file_exists($outputDir . '/' . $userSessionID)) {
                    wp_mkdir_p($outputDir . '/' . $userSessionID);
                }
                
                // Save Page Data
                $xml_handle = fopen($outputDir . '/' . $userSessionID . '/' . $xml, "w");
                fwrite($xml_handle, base64_decode($_REQUEST['pageData']));
                fclose($xml_handle);
                
                echo "{\"response\": \"ok\"}";
            }
            catch (Exception $e) {
                echo $this->__generate_callBack("invalid", "invalid", "invalid", $e->getMessage());
            } 
            wp_die();
        }
        
        function compile_entry_data() {
            $uDraw = new uDraw();
            try {
                $outputPath = $_REQUEST['outputPath'];
                $userSessionID = $_REQUEST['user_session_id'];
                $entryNo = $_REQUEST['entryNo'];
                if (strlen($outputPath) == 0) { echo "false"; return;}
                $outputDir = $uDraw->get_physical_path($outputPath);
                if (strlen($userSessionID) == 0) { echo "false"; return;}
                
                $docname = basename('entry_' . $entryNo, '.xml'); 
                if (strlen($docname) == 0) { echo "false"; return; }
                
                // Make sure the folder exists.
                if (gettype($outputDir) == 'boolean') {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "missing folders");
                    return;
                }
                
                // Check to see xml and preview was sent in request.
                if ( !strlen($_REQUEST['canvasData']) > 0 || !strlen($_REQUEST['pageCount']) > 0 )  {
                    echo $this->__generate_callBack("invalid", "invalid", "invalid", "No Docs Found");
                }                                
                
                $xml = $docname . '.xml';
                $pageCount = intval($_REQUEST['pageCount']);
                
                $xmlStr = base64_decode($_REQUEST['canvasData']);
                // compile the design.
                $files_to_delete = [];
                for ($x = 1; $x <= $pageCount; $x++) {
                    if (is_file($outputDir . '/'. $userSessionID . '/' . $entryNo .'_'. $x)) {
                        array_push($files_to_delete, $outputDir . '/'. $userSessionID . '/' . $entryNo .'_'. $x);
                        $handle = fopen($outputDir . '/'. $userSessionID . '/' . $entryNo .'_'. $x, "r") or die("Couldn't get handle");
                        if ($handle) {
                            while (!feof($handle)) {
                                $xmlStr .= fgets($handle, 4096);
                                // Process buffer here..
                            }
                            fclose($handle);
                        }
                    }
                }
                $xmlStr .= '</canvas>';
                // clean up, clean up, everyone do their share! :)
                for ($z = 0; $z < count($files_to_delete); $z++) {
                    if (is_file($files_to_delete[$z])) {
                        unlink($files_to_delete[$z]);
                    }
                }
                
                // Save XML Document
                $xml_handle = fopen($outputDir . '/' . $userSessionID . '/' . $xml, "w");
                fwrite($xml_handle, $xmlStr);
                fclose($xml_handle);             
                
                echo $this->__generate_callBack("-", $xml, "-", "-");
            }
            catch (Exception $e) {
                echo $this->__generate_callBack("invalid", "invalid", "invalid", $e->getMessage());
            }
            
            wp_die();
        }
        
        public function load_excel_session ($sessionID = '') {
            $uDraw = new uDraw();
            if (isset($_REQUEST['user_session_id'])) {
                $sessionID = $_REQUEST['user_session_id'];
            }
            if (isset($_REQUEST['outputPath'])){
                $outputPath = $_REQUEST['outputPath'];
            }
            if (strlen($outputPath) == 0 || strlen($sessionID) == 0) { echo "false"; return;}
            $outputDir = $uDraw->get_physical_path($outputPath) . $sessionID . '/';
            $xmlFiles = glob($outputDir . '*.xml'); // get all xml files
            $designArray = array();
            //Loop through all xml files with entry_ in its file name
            foreach ($xmlFiles as $file) {
                if (strpos($file, 'entry_') !== false) {
                    //Read file
                    $contents = file_get_contents($file);
                    if ($contents !== false) {
                        array_push($designArray, $contents);
                    }
                }
            }
            $this->sendResponse($designArray);
        }
        
        private function  __get_excel_object ($file) {
            require_once(UDRAW_PLUGIN_DIR. '/assets/PHPExcel/Classes/PHPExcel.php');
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(false);
            $objPHPExcel = $objReader->load($file);
            return $objPHPExcel;
        }
        
        private function __read_excel_file ($excel) {
            //excel->filename, excel->path, excel->sessionID
            //$file = directory path
            $file = str_replace(UDRAW_TEMP_UPLOAD_URL, UDRAW_TEMP_UPLOAD_DIR, $excel->path);
            $filename = $excel->filename;
            $sessionID = $excel->sessionID;
            $uploadSessionID = $excel->uploadSessionID;
            $objPHPExcel = $this->__get_excel_object($file);
            
            $totalSheets = $objPHPExcel->getSheetCount();

            $excelArray = array();
            $rootfolder = str_replace(array(UDRAW_TEMP_UPLOAD_DIR, $filename, $uploadSessionID, $sessionID, '/'), '', $file);
            for ($i = 0; $i < $totalSheets; $i++) {
                $objWorksheet = $objPHPExcel->setActiveSheetIndex($i);
                $sheetName = $objPHPExcel->getActiveSheet()->getTitle();
                if ($sheetName != 'Instructions') {
                    $sheetArray = array();
                    $highestRow = $objWorksheet->getHighestRow();
                    $highestColumnString = $objWorksheet->getHighestColumn();
                    $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnString);
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowArray = array();
                        for ($column = 0; $column < $highestColumn; $column++) {
                            $cell = $objWorksheet->getCellByColumnAndRow($column, $row);
                            $cellValue = (string)$cell->getValue();
                            if (PHPExcel_Shared_Date::isDateTime($cell)) {
                                $cellValue = PHPExcel_Shared_Date::ExcelToPHPObject($cell->getValue())->format('d-M-Y H:i:s');
                            }
                            $cellTitle = $objWorksheet->getCellByColumnAndRow($column, 1)->getValue();
                            array_push($rowArray, (object)array('label'=>$cellTitle, 'value'=>$cellValue));
                        }
                        array_push($sheetArray, $rowArray);
                    }
                    array_push($excelArray, $sheetArray);
                }
            }
            return $excelArray;
        }
        
        private function dirToArray($dir, $urlpath, $isDir=false) { 
            
            $result = array(); 
            
            $cdir = scandir($dir); 
            foreach ($cdir as $key => $value) 
            { 
                if (!in_array($value,array(".",".."))) 
                { 
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
                    { 
                        $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value, $urlpath . $value.DIRECTORY_SEPARATOR,true);
                    } 
                    else 
                    { 
                        $item= array();
                        $item[] = $value;
                        $item[] = $urlpath.$value;
                        
                        $result[]= $item;
                        //$result[] = $value;
                    } 
                } 
            } 
            
            return $result; 
        }
        private function __generate_callBack($name, $xml, $preview, $error) {
            return "{\"PDFdocument\": \"" . $name . "\", \"XMLDocument\": \"" . $xml . "\", \"Preview\": \"" . $preview . "\", \"errorMessage\": \"" . $error . "\"}";
        }
    }
}
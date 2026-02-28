<?php

if (!class_exists('GoEpower')) {
    
    class GoEpower {
        
        public $_soapWSDLDomain = "https://udraw-api.goepower.com";
        public $_informationAPI = "/WebServices/InformationAPI.asmx?wsdl";
        public $_createAPI = "/Webservices/CreateAPI.asmx?wsdl";
        
        function __contsruct() { }
                
        public function get_api_url() {
            $uDrawSettings = new uDrawSettings();
            $api_url = $uDrawSettings->get_setting('goepower_api_url');
            if (strlen($api_url) == 0) {
                // default url.
                return "https://udraw-api.goepower.com";
            } else {
                return $api_url;
            }
        }
        
        public function get_server_id() {
            $current_api_url = $this->get_api_url();
            if ($current_api_url == UDRAW_API_1_SERVER_URL) { return 1; }
            if ($current_api_url == UDRAW_API_2_SERVER_URL) { return 2; }
            //if ($current_api_url == UDRAW_API_3_SERVER_URL) { return 3; }
            if ($current_api_url == UDRAW_API_4_SERVER_URL) { return 4; }
            return 1; // default;
        }
        
        public function set_api_url($new_api) {
            $uDrawSettings = new uDrawSettings();
            $uDrawSettings->update_setting('goepower_api_url', $new_api);
            return $new_api;
        }
        
        private function __createSoapHeaderLogin($key, $producer_id) {
            
            $auth = array (
                'MasterKey' => $key,
                'ProducerID' => $producer_id,
                'CompanyID' => '0'      
            );
            
            return new SoapHeader('http://goepower.com/', 'APILogin', $auth, false);
        }
        
        function __generateRandomString($length = 10) {
            return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        }
        
        function get_login_object($user, $pass, $key) {
            $uDrawUtil = new uDrawUtil();

            $data = array(
                'username' => $user,
                'password' => $pass,
                'masterkey' => $key,
                'server' => $this->get_server_id()
                );
                
            $result = json_decode($uDrawUtil->get_web_contents('https://pdflib.w2pstore.com/api/Login', http_build_query($data)));
            return $result;
        }
        
        function get_auth_object() {
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            return $this->get_login_object($_udraw_settings['goepower_username'], $_udraw_settings['goepower_password'], $_udraw_settings['goepower_api_key']);
        }
        
        function validate_credentials($key, $producer_id) {
            // Init Soap Client
            $client = new SoapClient($this->get_api_url() . $this->_informationAPI);
            
            $params = array (
                'ProducerID' => $producer_id,
                'ShowInactive' => false           
            );
            
            // Set Soap Header
            $client->__setSoapHeaders($this->__createSoapHeaderLogin($key, $producer_id));
            
            // Call Web Method
            $clientResponse = $client->__soapCall("ProducerCompaniesByProducerID", array($params));
            
            if (strlen($clientResponse->ProducerCompaniesByProducerIDResult->Message) > 0) {
                return false;
            } else {
                return true;
            }
        }
        
        function get_producer_companies($key, $producer_id) {   
            // Validate Credentials
            if (!$this->validate_credentials($key, $producer_id)) { return null; }
            
            // Init Soap Client
            $client = new SoapClient($this->get_api_url() . $this->_informationAPI);
            
            $params = array (
                'ProducerID' => $producer_id,
                'ShowInactive' => false           
            );
            
            // Set Soap Header
            $client->__setSoapHeaders($this->__createSoapHeaderLogin($key, $producer_id));
            
            // Call Web Method
            $clientResponse = $client->__soapCall("ProducerCompaniesByProducerID", array($params));
                        
            // Companies Object
            return $clientResponse->ProducerCompaniesByProducerIDResult->Companies->CompanyInfo;
        }
        
        function create_quote($key, $producer_id, $company_id, $username, $email, $additional_email, $item_external_id, $job_name, $quote_data) {
            
            // Validate Credentials
            if (!$this->validate_credentials($key, $producer_id)) { return null; }
            
            // Init Soap Client
            $client = new SoapClient("https://live.goepower.com" . $this->_createAPI); 
            
            $items = array();
            $item_object = new StdClass;
            $item_object->ProductID = 0;
            $item_object->Quantity = 1;
            $item_object->JobName = $job_name;
            $item_object->ItemExternalID = $item_external_id;
            $item_object->Source = "Wordpress - uDraw";
            $item_object->Datas = $quote_data;    
            
            array_push($items, $item_object);
            
            $headers = array (
                'MasterKey' => $key,
                'ProducerID' => $producer_id,
                'CompanyID' => $company_id,
                'Username' => $username,
                'Email' => $email,
                'EmailToNotify' => $email_to_notify,
                'Items' => $items
            );
            
            $params = array ();
            
            // Set Soap Header
            $client->__setSoapHeaders(new SoapHeader('http://goepower.com/', 'APIQuote', $headers, false));
            
            // Call Web Method
            $clientResponse = $client->__soapCall("CreateQuote", array($params));            
            
            if (strlen($clientResponse->CreateQuoteResult->Message) > 0) {
                return null;
            } else {
                return $clientResponse->CreateQuoteResult;
            }
        }
        
        function create_user($key, $username, $external_id, $producer_id, $company_id, $firstname, $lastname, $company_name, $address1, $address2,
                             $address3, $city, $province, $postal_code, $country, $phone, $email, $toll_free, $web, $extension, $fax, $mobile) {
            
            // Validate Credentials
            if (!$this->validate_credentials($key, $producer_id)) { return null; }
            
            // Init Soap Client
            $client = new SoapClient($this->get_api_url() . $this->_createAPI);
            
            $headers = array (
                'MasterKey' => $key,
                'LoginProducerID' => $producer_id,
                'LoginCompanyID' => 0,
                'Username' => $username,
                'Password' => $this->__generateRandomString(),
                'ExternalID' => $external_id,
                'ProducerID' => $producer_id,
                'CompanyID' => $company_id,
                'PartnerID' => 0,
                'FirstName' => $firstname,
                'LastName' => $lastname,
                'CompanyName' => $company_name,
                'Address1' => $address1,
                'Address2' => $address2,
                'Address3' => $address3,
                'City' => $city,
                'Province' => $province,
                'PostalCode' => $postal_code,
                'Country' => $country,
                'County' => '',
                'Phone' => $phone,
                'Email' => $email,
                'TollFree' => $toll_free,
                'Web' => $web,
                'Extension' => $extension,
                'Fax' => $fax,
                'Mobile' => $mobile,
                'Culture' => 'en-US',
                'Roles' => 'Customer',
                'RemoveExistingRoles' => false,
                'UserTaxExempt' => 0
            );
            $params = array ();
            
            // Set Soap Header
            $client->__setSoapHeaders(new SoapHeader('http://goepower.com/', 'APIUser', $headers, false));
            
            // Call Web Method
            $clientResponse = $client->__soapCall("CreateUser", array($params));             
            
            if (strlen($clientResponse->CreateUserResult->Message) > 0) {
                return null;
            } else {
                return $clientResponse->CreateUserResult;
            }
        }
        
        function create_order($key, $producer_id, $company_id, $username, $note, $email_to_notify, $order_ext_id, $delivery_date, $notify_gatekeeper,
                              $firstname, $lastname, $company_name, $address1, $address2, $address3, $city, $province, $postal_code, $country,
                              $phone, $email, $items, $price, $shipping_price, $tax_name1, $tax_name2, $tax1, $tax2, $total_price) {
            
            // Validate Credentials
            if (!$this->validate_credentials($key, $producer_id)) { return null; }

            $epower_url = $this->get_api_url();
            if ($epower_url === "https://udraw-api.goepower.com") {
                $epower_url = "https://live.goepower.com";
            } else {
                $epower_url = "https://live.w2pshop.com";
            }
            
            // Init Soap Client
            $client = new SoapClient($epower_url . $this->_createAPI, array('trace' => 1));
            
            $headers = array (
                'MasterKey' => $key,
                'ProducerID' => $producer_id,
                'CompanyID' => $company_id,
                'Username' => $username,
                'OrderNote' => $note,
                'EmailToNotify' => $email_to_notify,
                'OrderExternalID' => $order_ext_id,
                'DeliveryDate' => $delivery_date,
                'NotifyGatekeeper' => $notify_gatekeeper,
                'FirstName' => $firstname,
                'LastName' => $lastname,
                'CompanyName' => $company_name,
                'Address1' => $address1,
                'Address2' => $address2,
                'Address3' => $address3,
                'City' => $city,
                'Province' => $province,
                'PostalCode' => $postal_code,
                'Country' => $country,
                'Phone' => $phone,
                'Email' => $email,
                'Items' => $items,
                'Price' => $price,
                'ShippingPrice' => $shipping_price,
                'TaxName1' => $tax_name1,
                'TaxName2' => $tax_name2,
                'Tax1' => $tax1,
                'Tax2' => $tax2,
                'TotalPrice' => $total_price
            );            
            
            $params = array ();
            
            // Set Soap Header
            $client->__setSoapHeaders(new SoapHeader('http://goepower.com/', 'APIOrderThirdParty', $headers, false));
            
            // Call Web Method
            try {
                $clientResponse = $client->__soapCall("CreateOrderFromThirdParty", array($params));            

                if (strlen($clientResponse->CreateOrderFromThirdPartyResult->Message) > 0) {
                    return null;
                } else {
                    return $clientResponse->CreateOrderFromThirdPartyResult;
                }                
            } catch (Exception $e) {
                return null;
            }            
        }
        
        function get_company_products($key, $producer_id, $company_id) {
            // Validate Credentials
            if (!$this->validate_credentials($key, $producer_id)) { return null; }
            
            // Init Soap Client
            $client = new SoapClient($this->get_api_url() . $this->_informationAPI);            
            
            $params = array (
                'CompanyID' => $company_id,
                'ShowInactive' => false           
            );
            
            
              $auth = array (
                'MasterKey' => $key,
                'ProducerID' => $producer_id,
                'CompanyID' => $company_id      
            );            
            
            // Set Soap Header
            $client->__setSoapHeaders(new SoapHeader('http://goepower.com/', 'APILogin', $auth, false));
            
            // Call Web Method
            $clientResponse = $client->__soapCall("CompanyProductsByCompanyID", array($params));
            
            // Companies Object
            return $clientResponse->CompanyProductsByCompanyIDResult->Products->ProductInfo;
        }
        
        function get_company_products_by_type($type) {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $products = $this->get_company_products($_udraw_settings['goepower_api_key'],
                                                    $_udraw_settings['goepower_producer_id'],
                                                    $_udraw_settings['goepower_company_id']);
            
            $specificProducts = array();
            
            if (count($products) == 1) {
                if (strtolower($products->ProductType) == strtolower($type)) {
                    array_push($specificProducts, get_object_vars($products));
                }
            } else {                
                for ($x = 0; $x < count($products); $x++) {            
                    if (strtolower($products[$x]->ProductType) == strtolower($type)) {
                        $prodcut = get_object_vars($products[$x]);
                        array_push($specificProducts, $prodcut);
                    }
                }
            }
            
            return $specificProducts;
        }
        
    }
    
}

?>
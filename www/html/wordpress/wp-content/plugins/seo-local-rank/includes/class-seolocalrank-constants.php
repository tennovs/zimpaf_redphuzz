<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeoLocalRankConstants {


    const API_URL = 'https://trueranker.com/api';
    //const API_URL = 'http://seolocalrank.local/api';
    const PLUGIN_VERSION = '2.0';
    const SLR_DOMAIN = 'https://trueranker.com';
    
    const USER_URL = "/user";
    const COUNTRY_URL = "/country";
    const PROVINCE_URL = "/province";
    const PROJECT_URL = "/project";
    const DOMAIN_URL = "/domain";
    const KEYWORD_URL = "/keyword";
    const SEARCH_URL = "/search";
    const PLAN_URL = "/plan";
    const PLAN_PERIOD_URL = "/plan-period";
    const SALE_URL = "/sale";
    const NOTIFICATION_URL = "/notification";
    const PAY_URL = "/pay";
    const LOG_URL = "/log";
    
    const METHOD_USER_LOGIN = self::USER_URL."/user/login";
    const METHOD_USER_LOGIN_WITH_TOKEN = self::USER_URL."/login-token";
    const METHOD_USER_LOGIN_WITH_API_KEY = self::USER_URL."/login-apikey";
    const METHOD_USER_REGISTER = self::USER_URL."/register";
    const METHOD_USER_RECOVER_PASSWORD_SEND_KEY = self::USER_URL."/recover-password/send-key";
    const METHOD_USER_RECOVER_PASSWORD_CHECK_KEY = self::USER_URL."/recover-password/check-key";
    const METHOD_USER_RECOVER_PASSWORD_CHANGE = self::USER_URL."/recover-password/change";
    const METHOD_USER_CONTACT = self::USER_URL."/contact";
    const METHOD_USER_REMOVE = self::USER_URL."/remove";
    const METHOD_USER_SUBSCRIBE_PLAN = self::USER_URL."/subscribe-plan";
    const METHOD_GET_USER_OFFER = self::USER_URL."/get-offer";
    const METHOD_GET_USER_AVAILABLE_KEYWORDS = self::USER_URL."/available-keywords";
    const METHOD_USER_SEND_API_KEY_BY_EMAIL = self::USER_URL."/send-api-key";

    const METHOD_PROVINCES_LIST = self::PROVINCE_URL."/list";
    const METHOD_COUNTRIES_LIST = self::COUNTRY_URL."/list";
    const METHOD_COUNTRY_PROVINCES_LIST = self::COUNTRY_URL.self::PROVINCE_URL."/list";
    const METHOD_PROVINCES_SEARCH = self::PROVINCE_URL."/search";

    const METHOD_PROJECT_DOMAIN_DASHBOARD_DATA = self::PROJECT_URL.self::DOMAIN_URL."/dashboard-data";
    const METHOD_PROJECTS_LIST = self::PROJECT_URL."/list";
    const METHOD_PROJECT_ADD = self::PROJECT_URL."/add";
    const METHOD_PROJECT_REMOVE = self::PROJECT_URL."/remove";
    const METHOD_PROJECT_DOMAINS_LIST = self::PROJECT_URL.self::DOMAIN_URL."/list";
    const METHOD_PROJECT_DOMAIN_ADD = self::PROJECT_URL.self::DOMAIN_URL."/add";
    const METHOD_PROJECT_DOMAIN_REMOVE = self::PROJECT_URL.self::DOMAIN_URL."/remove";
    const METHOD_PROJECT_DOMAIN_GET_BY_NAME = self::PROJECT_URL.self::DOMAIN_URL."/get-by-name";
    
    const METHOD_DOMAIN_LIST_ALL = self::PROJECT_URL.self::DOMAIN_URL."/list/all";
    const METHOD_PROJECT_DOMAIN_ADD_DEFAULT = self::PROJECT_URL.self::DOMAIN_URL."/add/default";

    const METHOD_KEYWORD_ADD = self::KEYWORD_URL."/add";
    const METHOD_KEYWORD_CITIES_ADD = self::KEYWORD_URL."/add-cities";
    const METHOD_KEYWORD_REMOVE = self::KEYWORD_URL."/remove";
    const METHOD_KEYWORD_ACTIVATE = self::KEYWORD_URL."/activate";
    const METHOD_KEYWORD_PAUSE = self::KEYWORD_URL."/pause";
    const METHOD_KEYWORDS_LIST = self::KEYWORD_URL."/list";
    const METHOD_KEYWORDS_USER_LIST = self::KEYWORD_URL."/user-list";
    const METHOD_KEYWORD_GET_SEARCH = self::KEYWORD_URL.self::SEARCH_URL;
    const METHOD_KEYWORD_SEARCH_UPDATE = self::KEYWORD_URL.self::SEARCH_URL."/update";
    const METHOD_KEYWORD_RANK_HISTORY = self::KEYWORD_URL."/history";
    const METHOD_KEYWORD_CAN_UPDATE = self::KEYWORD_URL."/can-update";
    const METHOD_KEYWORD_GET_EXTRA = self::KEYWORD_URL."/get/extra"; 
    const METHOD_KEYWORD_SEARCH_UPDATE_SCRAPER = self::KEYWORD_URL.self::SEARCH_URL."/update-scraper";
    const METHOD_KEYWORD_UPDATE = self::KEYWORD_URL."/update";
    const METHOD_KEYWORD_GET_UPDATED_DATA =  self::KEYWORD_URL."/get-updated-data";
    
    const METHOD_PLANS_LIST = self::PLAN_URL."/list";
    const METHOD_PLAN_GET = self::PLAN_URL."/get";
    const METHOD_PLAN_PERIOD_LIST = self::PLAN_PERIOD_URL."/list";
    const METHOD_SALE_ADD_SUBSCRIPTION = self::SALE_URL. "/add-subscription";
    const METHOD_SALE_PAY_WITH_CARD = self::SALE_URL.PAY_URL."/stripe";

    const METHOD_NOTIFICATIONS_LIST = self::NOTIFICATION_URL."/list";
    const METHOD_NOTIFICATION_OPEN = self::NOTIFICATION_URL."/open";

    const METHOD_LOG_SAVE = self::LOG_URL ."/save";
    
    const API_KEY_NAME = "API key";
    
    //Payment methods
    const PAYMENT_METHOD_STRIPE = 1;
    const PAYMENT_METHOD_PAYPAL = 2;
    const PAYMENT_METHOD_GOOGLE = 3;
    const PAYMENT_METHOD_BANK = 4;
    
}

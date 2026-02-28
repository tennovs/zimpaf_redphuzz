<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
//account
require_once ERE_PLUGIN_DIR . 'public/partials/account/class-ere-profile.php';
require_once ERE_PLUGIN_DIR . 'public/partials/account/class-ere-login-register.php';
//agent
require_once ERE_PLUGIN_DIR . 'public/partials/agent/class-ere-agent.php';
//property
require_once ERE_PLUGIN_DIR . 'public/partials/property/class-ere-property.php';
require_once ERE_PLUGIN_DIR . 'public/partials/property/class-ere-search.php';
require_once ERE_PLUGIN_DIR . 'public/partials/property/class-ere-save-search.php';
require_once ERE_PLUGIN_DIR . 'public/partials/property/class-ere-compare.php';
require_once ERE_PLUGIN_DIR . 'public/partials/payment/class-ere-payment.php';
require_once ERE_PLUGIN_DIR . 'public/partials/payment/class-ere-trans-log.php';
require_once ERE_PLUGIN_DIR . 'public/partials/package/class-ere-package.php';
require_once ERE_PLUGIN_DIR . 'public/partials/invoice/class-ere-invoice.php';
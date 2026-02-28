<?php
global $site;
$rezgoPage = sanitize_text_field($_REQUEST['mode']);
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $rezgoPage . '.php');
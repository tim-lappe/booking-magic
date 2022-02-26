<?php

/**
 * PHPUnit bootstrap file.
 *
 */

//ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = "localhost";

const ABSPATH = "/var/www/html/";
const TLBM_PLUGIN_FILE = __DIR__ . "/../../booking-magic.php";
const WP_DEBUG = 1;

require_once __DIR__ . "/../../constants.php";

require_once TLBM_PLUGIN_DIR . "/vendor/autoload.php";

require_once __DIR__ . "/startup-test.php";
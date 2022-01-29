<?php
/*
Plugin Name: Booking Magic
Description: Das All-in-one Buchungstool
Version: Dev 1.0
Author: Tim Lappe
Author URI: https://www.tlappe.de
*/

if( ! defined( 'ABSPATH' ) ) {
	return;
}

const TLBM_PLUGIN_FILE = __FILE__;

require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/constants.php";

require_once __DIR__ . "/startup.php";
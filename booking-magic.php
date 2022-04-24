<?php
/*
Plugin Name: Booking Magic
Plugin URI: https://www.booking-magic.de
Requires PHP: 7.4
Text Domain: booking-magic-plugin
Description: Das All-in-one Buchungstool
Version: 1.0.2
Author: Tim Lappe
Author URI: https://www.tlappe.de
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

if( ! defined( 'ABSPATH' ) ) {
	return;
}

const TLBM_PLUGIN_FILE = __FILE__;

require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/constants.php";

require_once __DIR__ . "/startup.php";
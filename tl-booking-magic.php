<?php
/*
Plugin Name: TL Booking
Description: Das All-in-one Buchungstool
Version: Dev 1.0
Author: Tim Lappe
Author URI: https://www.tlappe.de
*/

if( ! defined( 'ABSPATH' ) ) {
	return;
}

require_once __DIR__ . "/vendor/autoload.php";


define("TLBM_VERSION", "Dev 1.0");
define("TLBM_DIR", __DIR__ );
define("TLBM_PLUGIN_FILE", __FILE__ );
define("TLBM_INCLUDES_DIR", TLBM_DIR . "/includes/");

define("TLBM_PT_CALENDAR", "tlbm_calendar");
define("TLBM_PT_RULES", "tlbm_calendar_rules");
define("TLBM_PT_FORMULAR", "tlbm_fomular");
define("TLBM_PT_BOOKING", "tlbm_booking");

define("TLBM_MB_PREFIX", "tlbm_mb_");
define("TLBM_CALENDAR_META_CALENDAR_SETUP", "calendar-setup");
define("TLBM_CALENDAR_META_CALENDAR_RULES", "calendar-rules");

define("TLBM_MAIN_CSS_SLUG", "tlbm-main");
define("TLBM_MAIN_JS_SLUG", "tlbm-main");

define("TLBM_TEXT_DOMAIN", "tl-booking-calendar");

define("TLBM_SHORTCODETAG_FORM", "booking_magic_form");

class TL_Booking_Magic {

	private static $instance = false;

	public function __construct() {
		if(!self::$instance) {
			self::$instance = $this;
		}
	}

	/**
	 * Returns the singleton
	 *
	 * @return bool|TL_Booking_Magic
	 */
	private static function GetSingleton() {
		return self::$instance;
	}

	/**
	 * Creates an global Instance of a Class
	 *
	 * @param $class
	 *
	 * @return mixed
	 */
	public static function MakeInstance($class) {
		return new $class();
	}
}

$tl_booking_object = new TL_Booking_Magic();

require_once __DIR__ . "/startup.php";
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

require_once __DIR__ . "/vendor/autoload.php";


const TLBM_VERSION = "Dev 1.0";
const TLBM_DIR = __DIR__;
const TLBM_PLUGIN_FILE = __FILE__;
const TLBM_INCLUDES_DIR = TLBM_DIR . "/includes/";
const TLBM_MAIL_TEMPLATES = TLBM_DIR . "/templats/email/";
const TLBM_TABLE_DEFINITIONS_FILE = TLBM_DIR . "/tables-def.sql";

const TLBM_PT_CALENDAR = "tlbm_calendar";
const TLBM_PT_CALENDAR_GROUPS = "tlbm_calendar_groups";
const TLBM_PT_RULES = "tlbm_calendar_rules";
const TLBM_PT_FORMULAR = "tlbm_fomular";
const TLBM_PT_BOOKING = "tlbm_booking";

const TLBM_MB_PREFIX = "tlbm_mb_";
const TLBM_CALENDAR_META_CALENDAR_SETUP = "calendar-setup";
const TLBM_CALENDAR_META_CALENDAR_RULES = "calendar-rules";

const TLBM_MAIN_CSS_SLUG = "tlbm-main";
const TLBM_MAIN_JS_SLUG = "tlbm-main";

const TLBM_TEXT_DOMAIN = "tl-booking-calendar";

const TLBM_SHORTCODETAG_FORM = "booking_magic_form";

class TLBookingMagic {

	private static $instance = false;

	public function __construct() {
		if(!self::$instance) {
			self::$instance = $this;
		}
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

$tl_booking_object = new TLBookingMagic();

require_once __DIR__ . "/startup.php";
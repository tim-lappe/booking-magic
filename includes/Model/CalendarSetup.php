<?php

namespace TL_Booking\Model;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class CalendarSetup {

	/**
	 * A List of the Daynames of a Week
	 *
	 * @var array
	 */
	public $weekdays;

	/**
	 * A List of the Monthnames of a Year
	 *
	 * @var array
	 */
	public $months;

	/**
	 * Amount of Days the user can book the calendar in the future
	 *
	 * @var int
	 */
	public $viewable_future_days = 120;

	/**
	 * Amount of Minutes the user can book first from now
	 *
	 * @var int
	 */
	public $earliest_booking_from_now = 180;

	/**
	 * Amount of Minutes the user can book first from now
	 *
	 * @var int
	 */
	public $latest_booking_from_now = 180;

	/**
	 * The Booking Mode
	 *
	 * @var string
	 */
	public $booking_mode = "";

	public function __construct() {
		$this->weekdays = self::GetDefaultWeekdays();
		$this->months = self::GetDefaultMonths();
		$this->booking_mode = "only_date";
	}

	public function GetJson() {
		return json_encode($this);
	}

	public static function GetDefaultMonths(): array {
		return array(
			__("January", TLBM_TEXT_DOMAIN),
			__("February", TLBM_TEXT_DOMAIN),
			__("March", TLBM_TEXT_DOMAIN),
			__("April", TLBM_TEXT_DOMAIN),
			__("May", TLBM_TEXT_DOMAIN),
			__("June", TLBM_TEXT_DOMAIN),
			__("July", TLBM_TEXT_DOMAIN),
			__("August", TLBM_TEXT_DOMAIN),
			__("September", TLBM_TEXT_DOMAIN),
			__("October", TLBM_TEXT_DOMAIN),
			__("November", TLBM_TEXT_DOMAIN),
			__("December", TLBM_TEXT_DOMAIN),
		);
	} 

	public static function GetDefaultWeekdays(): array {
		return  array(
			"mo" =>  __("Monday", TLBM_TEXT_DOMAIN),
			"tue" => __("Tuesday", TLBM_TEXT_DOMAIN),
			"wed" => __("Wednesday", TLBM_TEXT_DOMAIN),
			"th" => __("Thursday", TLBM_TEXT_DOMAIN),
			"fr" => __("Friday", TLBM_TEXT_DOMAIN),
			"sat" => __("Saturday", TLBM_TEXT_DOMAIN),
			"sun" => __("Sunday", TLBM_TEXT_DOMAIN),
		);
	}
}
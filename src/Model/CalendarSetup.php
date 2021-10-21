<?php

namespace TLBM\Model;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class CalendarSetup {

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
		$this->booking_mode = "only_date";
	}
}
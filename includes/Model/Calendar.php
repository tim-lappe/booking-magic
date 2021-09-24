<?php
namespace TL_Booking\Model;

use DateInterval;
use DateTime;
use Exception;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class Calendar {

	/**
	 * @var int
	 */
	public $wp_post_id;

	/**
	 * @var CalendarSetup
	 */
	public $calendar_setup;

    /**
     * @var string
     */
	public $title;


	public function __construct() {
		$this->calendar_setup = new CalendarSetup();
	}

	/**
	 * Returns the next DateTime the user could book
	 *
	 * @return DateTime
	 * @throws Exception
	 */
	public function GetNextBookableTime(): DateTime {
		 $fnbtd = $this->calendar_setup->earliest_booking_from_now;
		 $now = new DateTime();
		 $now->add(new DateInterval("PT" . $fnbtd . "M"));

		 return $now;
	}
}
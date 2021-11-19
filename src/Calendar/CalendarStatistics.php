<?php


namespace TLBM\Calendar;


use TLBM\Booking\BookingManager;

class CalendarStatistics {

	public static function GetBestSellingCalendars(\DateTime $from, \DateTime $to = null): array {
		if($to == null) {
			$to = new \DateTime();
		}

		$bookings = BookingManager::GetAllBookings(array(), "date", "DESC");
		$cals = array();
		foreach ($bookings as $booking) {
			$dt = get_post_datetime($booking->wp_post_id);
			if($from->getTimestamp() <= $dt->getTimestamp() && $dt->getTimestamp() <= $to->getTimestamp()) {
				foreach ($booking->calendar_slots as $slot) {
					if(isset($cals[$slot->booked_calendar_id])) {
						$cals[ $slot->booked_calendar_id ] ++;
					} else {
						$cals[ $slot->booked_calendar_id ] = 1;
					}
				}
			}
		}

		return $cals;
	}
}
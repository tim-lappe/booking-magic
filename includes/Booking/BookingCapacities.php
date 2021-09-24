<?php


namespace TL_Booking\Booking;


use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Model\Calendar;
use TL_Booking\Model\CalendarSlot;
use TL_Booking\Rules\Capacities;

class BookingCapacities {

	/**
	 * @param Calendar $calendar
	 * @param \DateTime $datetime
	 *
	 * @return int
	 */
	public static function GetBookedDaySeats($calendar, $datetime): int {
		$bookings = BookingManager::GetAllBookings();
		$seats = 0;
		foreach ($bookings as $booking) {
			foreach($booking->calendar_slots as $slot) {
				if($slot->booked_calendar_id == $calendar->wp_post_id) {
					if( $datetime->format("d-m-Y") == date("d-m-Y", $slot->timestamp)) {
						$seats++;
					}
				}
			}
		}
		return $seats;
	}

	/**
	 * @param $calendar
	 * @param $datetime
	 *
	 * @return int
	 */
	public static function GetFreeDaySeats($calendar, $datetime): int {
		$capacity = Capacities::GetDayCapacity( $calendar, $datetime );
		$capacity -= BookingCapacities::GetBookedDaySeats( $calendar, $datetime );

		return max(0, $capacity);
	}

	/**
	 * @param CalendarSlot $calendar_slot
	 *
	 * @return int
	 */
	public static function PreBookCalendarSeat( CalendarSlot $calendar_slot ): int {
		try {
			$datetime = new \DateTime();
			$datetime->setTimestamp($calendar_slot->timestamp);
			$freeseats = self::GetFreeDaySeats(CalendarManager::GetCalendar($calendar_slot->calendar_selection), $datetime);
			if($freeseats > 0) {
				return $calendar_slot->calendar_selection;
			}
			return false;
		} catch( \Exception $e ) {
			return false;
		}
	}
}
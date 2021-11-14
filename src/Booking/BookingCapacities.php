<?php


namespace TLBM\Booking;


use TLBM\Calendar\CalendarGroupManager;
use TLBM\Calendar\CalendarManager;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Model\Calendar;
use TLBM\Model\CalendarGroup;
use TLBM\Model\CalendarSlot;
use TLBM\Rules\Capacities;

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
	 * @param CalendarGroup $group
	 * @param \DateTime $datetime
	 *
	 * @return int
	 */
	public static function GetFreeDaySeatsForGroup($group, $datetime): int {
		$calendars = CalendarSelectionHandler::GetSelectedCalendarList($group->calendar_selection);
		$capacity = 0;
		foreach ($calendars as $calendar) {
			$capacity += self::GetFreeDaySeats($calendar, $datetime);
		}
		return $capacity;
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
			$freeseats = self::GetFreeDaySeatsForGroup(CalendarGroup::FromCalendarOrGroupId($calendar_slot->calendar_selection), $datetime);
			if($freeseats > 0) {

				return $calendar_slot->calendar_selection;
			}
			return false;
		} catch( \Exception $e ) {
			return false;
		}
	}
}
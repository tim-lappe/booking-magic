<?php


namespace TLBM\Booking;


use DateTime;
use TLBM\Calendar\CalendarGroupManager;
use TLBM\Calendar\CalendarManager;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Entity\Calendar;
use TLBM\Model\CalendarGroup;
use TLBM\Model\CalendarSelection;
use TLBM\Model\CalendarSlot;
use TLBM\Rules\Capacities;

class BookingCapacities {

	/**
	 * @param Calendar $calendar
	 * @param DateTime $datetime
	 *
	 * @return int
	 */
	public static function GetBookedDaySeats( Calendar $calendar, DateTime $datetime ): int {
		$bookings = BookingManager::GetAllBookings();
		$seats = 0;
		foreach ($bookings as $booking) {
			foreach($booking->calendar_slots as $slot) {
				if($slot->booked_calendar_id == $calendar->GetId()) {
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
	 * @param DateTime $datetime
	 *
	 * @return int
	 */
	public static function GetFreeDaySeatsForGroup(CalendarGroup $group, DateTime $datetime): int {
		$calendars = CalendarSelectionHandler::GetSelectedCalendarList($group->calendar_selection);
		$capacity = 0;
		foreach ($calendars as $calendar) {
			$capacity += self::GetFreeDaySeats($calendar, $datetime);
		}
		return $capacity;
	}

	/**
	 * @param CalendarSelection $selection
	 * @param DateTime $datetime
	 *
	 * @return ?Calendar
	 */
	public static function GetCalendarWithLeastCapacityFromSelection(CalendarSelection $selection, DateTime $datetime): ?Calendar {
		$calendars = CalendarSelectionHandler::GetSelectedCalendarList($selection);
		$min = PHP_INT_MAX;
        $cal = null;
		foreach ($calendars as $calendar) {
			$seats = self::GetFreeDaySeats($calendar, $datetime);
			if($min > $seats && $seats > 0) {
				$min = $seats;
				$cal = $calendar;
			}
		}

		return $cal;
	}

	/**
	 * @param CalendarSelection $selection
	 * @param DateTime $datetime
	 *
	 * @return ?Calendar
	 */
	public static function GetCalendarWithMostCapacityFromSelection(CalendarSelection $selection, DateTime $datetime): ?Calendar {
		$calendars = CalendarSelectionHandler::GetSelectedCalendarList($selection);
		$max = PHP_INT_MIN;
		$cal = null;
		foreach ($calendars as $calendar) {
			$seats = self::GetFreeDaySeats($calendar, $datetime);
			if($max < $seats) {
				$max = $seats;
				$cal = $calendar;
			}
		}

		return $cal;
	}

	/**
	 * @param CalendarSlot $calendar_slot
	 *
	 * @return int
	 */
	public static function PreBookCalendarSeat( CalendarSlot $calendar_slot ): int {
		try {
			$datetime = new DateTime();
			$datetime->setTimestamp($calendar_slot->timestamp);
			$group = CalendarGroup::FromCalendarOrGroupId($calendar_slot->calendar_selection_id);
			$freeseats = self::GetFreeDaySeatsForGroup($group, $datetime);
			if($freeseats > 0) {
				if($group->booking_distribution == TLBM_BOOKING_DISTRIBUTION_FILL_ONE) {
					$calendar = self::GetCalendarWithLeastCapacityFromSelection($group->calendar_selection, $datetime);
					if($calendar != null) {
						return $calendar->wp_post_id;
					}
				}
				if($group->booking_distribution == TLBM_BOOKING_DISTRIBUTION_EVENLY) {
					$calendar = self::GetCalendarWithMostCapacityFromSelection($group->calendar_selection, $datetime);
					if($calendar != null) {
						return $calendar->wp_post_id;
					}
				}
			}
			return false;
		} catch( \Exception $e ) {
			return false;
		}
	}
}
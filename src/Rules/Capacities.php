<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Admin\WpForm\DateTimeField;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Model\Calendar;
use TLBM\Model\CalendarGroup;

class Capacities {

	/**
	 * @param Calendar $calendar
	 * @param DateTime $date_time
	 *
	 * @return int
	 */
	public static function GetDayCapacity(Calendar $calendar, DateTime $date_time): int {
		$actions = RuleActionsManager::GetActionsForDateTime($calendar, $date_time);
		$capacity = 0;
		foreach($actions as $action) {
			$handler = RuleActionHandler::GetActionHandler($action);
			$capacity = $handler->ProcessCapacity($capacity);
		}

		return $capacity;
	}

	/**
	 * @param CalendarGroup $group
	 * @param DateTime $date_time
	 *
	 * @return int
	 */
	public static function GetDayCapacityForGroup(CalendarGroup $group, DateTime $date_time): int {
		$calendars = CalendarSelectionHandler::GetSelectedCalendarList($group->calendar_selection);
		$capacity = 0;

		foreach ($calendars as $calendar) {
			$capacity += self::GetDayCapacity($calendar, $date_time);
		}
		#
		return $capacity;
	}
}
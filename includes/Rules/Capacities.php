<?php


namespace TL_Booking\Rules;


use DateTime;
use TL_Booking\Admin\WpForm\DateTimeField;
use TL_Booking\Model\Calendar;

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
}
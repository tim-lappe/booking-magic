<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Admin\WpForm\DateTimeField;
use TLBM\Model\Calendar;

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
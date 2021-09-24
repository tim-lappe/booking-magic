<?php


namespace TL_Booking\Rules;


use DateTime;
use TL_Booking\Admin\WpForm\DateTimeField;
use TL_Booking\Model\Calendar;
use TL_Booking\Model\RuleAction;
use TL_Booking\Utilities\PeriodsTools;

class RuleActionsManager {

	/**
	 * @param Calendar $calendar
	 * @param DateTime $date_time
	 *
	 * @return RuleAction[]
	 */
	public static function GetActionsForDateTime(Calendar $calendar, DateTime $date_time): array {
		$rules = RulesManager::GetAllRulesForCalendarForDateTime($calendar->wp_post_id, $date_time);
		$actions = array();
		$workingactions = array();
		foreach ($rules as $rule) {
			$actions = array_merge($actions, $rule->action->actions_list);
		}

		foreach ($actions as $action) {
			if(RuleActionHandler::GetActionHandler($action)->WorksAtTime($date_time)) {
				$workingactions[] = $action;
			}
		}
		return $workingactions;
	}
}
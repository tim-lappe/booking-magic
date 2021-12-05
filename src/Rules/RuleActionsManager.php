<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Entity\Calendar;
use TLBM\Model\RuleAction;

class RuleActionsManager {

	/**
	 * @param Calendar $calendar
	 * @param DateTime $date_time
	 *
	 * @return RuleAction[]
	 */
	public static function GetActionsForDateTime(Calendar $calendar, DateTime $date_time): array {
		$rules = RulesManager::GetAllRulesForCalendarForDateTime($calendar->GetId(), $date_time);
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
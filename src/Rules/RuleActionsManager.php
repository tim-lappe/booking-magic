<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Entity\Calendar;
use TLBM\Entity\RuleAction;
use TLBM\Rules\RuleActions\DateTimeSlotActionMerge;
use TLBM\Rules\RuleActions\DateTimeTimeSlotActionMerge;
use TLBM\Rules\RuleActions\RuleActionMergingBase;

class RuleActionsManager {

    public static array $rule_actions = array(
        "date_slot" => DateTimeSlotActionMerge::class,
        "time_slot" => DateTimeTimeSlotActionMerge::class
    );

    public static function registerActionMerger(string $term, $class) {
        self::$rule_actions[$term] = $class;
    }


    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionMergingBase
     */
    public static function getActionMerger(RuleAction $action ): ?RuleActionMergingBase {
        if(isset(self::$rule_actions[$action->GetActionType()])) {
            return new self::$rule_actions[$action->GetActionType()]($action);
        }

        return null;
    }


	/**
	 * @param Calendar $calendar
	 * @param DateTime $date_time
	 *
	 * @return RuleAction[]
	 */
	public static function getActionsForDateTime(Calendar $calendar, DateTime $date_time): array {
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
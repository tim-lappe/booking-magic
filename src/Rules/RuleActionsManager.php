<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Entity\Calendar;
use TLBM\Entity\RuleAction;
use TLBM\Rules\RuleActions\DateTimeSlotActionHandlerRule;
use TLBM\Rules\RuleActions\DateTimeTimeSlotActionHandlerRule;
use TLBM\Rules\RuleActions\RuleActionHandlerBase;

class RuleActionsManager {

    public static array $rule_actions = array(
        "date_slot" => DateTimeSlotActionHandlerRule::class,
        "time_slot" => DateTimeTimeSlotActionHandlerRule::class
    );

    public static function registerActionHandler(string $term, $class) {
        self::$rule_actions[$term] = $class;
    }


    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionHandlerBase
     */
    public static function getActionHandler(RuleAction $action ): ?RuleActionHandlerBase {
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
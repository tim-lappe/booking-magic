<?php


namespace TLBM\Rules;


use TLBM\Model\RuleAction;
use TLBM\Rules\RuleActions\ActionHandlerBase;
use TLBM\Rules\RuleActions\DateTimeSlotHandler;

class RuleActionHandler {
	public static $rule_actions = array(
		"day-slot" => DateTimeSlotHandler::class
	);

	/**
	 *
	 * @param RuleAction $action
	 *
	 * @return false|ActionHandlerBase
	 */
	public static function GetActionHandler( RuleAction $action ) {
		if(isset(self::$rule_actions[$action->actiontype])) {
			return new self::$rule_actions[$action->actiontype]($action);
		}
		return false;
	}
}
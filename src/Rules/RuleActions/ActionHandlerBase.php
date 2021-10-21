<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Model\RuleAction;

abstract class ActionHandlerBase {

	/**
	 * @var RuleAction
	 */
	public $rule_action;

	/**
	 * ActionHandlerBase constructor.
	 *
	 * @param RuleAction $rule_action
	 */
	public function __construct( RuleAction $rule_action ) {
		$this->rule_action = $rule_action;
	}

	public function WorksAtTime( \DateTime $date_time ): bool {
		return true;
	}

	public function ProcessCapacity($capacity): int {
		return $capacity;
	}
}
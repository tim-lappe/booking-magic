<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Utilities\WeekdayTools;

class DateTimeSlotHandler extends ActionHandlerBase {

	public function WorksAtTime( \DateTime $date_time ): bool {
		$weekday = $this->rule_action->values->weekday;
		if($weekday) {
			return WeekdayTools::IsInWeekdayDefinition($weekday, $date_time);
		}
		return true;
	}

	public function ProcessCapacity( $capacity ): int {
	    $mode =	$this->rule_action->values->capacity_mode;
	    $amount = intval($this->rule_action->values->capacity);

	    if($mode == "set") {
	    	$capacity = $amount;
	    } else if($mode == "add") {
	    	$capacity += $amount;
	    } else if($mode == "subtract") {
	    	$capacity -= $amount;
	    }
	    return $capacity;
	}
}
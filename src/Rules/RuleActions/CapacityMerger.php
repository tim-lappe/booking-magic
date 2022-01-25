<?php

namespace TLBM\Rules\RuleActions;

use TLBM\Rules\RuleActions\MergeEntities\CapacityMerge;

class CapacityMerger {

    public object $rule_action_data;

    public function __construct(object $rule_action_data) {
        $this->rule_action_data = $rule_action_data;
    }

    public function merge(CapacityMerge $capacity_merge): CapacityMerge {
        $action_data = $this->rule_action_data;
        if(isset($action_data->mode) && isset($action_data->amount)) {
            if($action_data->mode == "set") {
                $capacity_merge->setCapacity(intval($action_data->amount));
            } else if($action_data->mode == "add") {
                $capacity_merge->setCapacity( $capacity_merge->getCapacity() + $action_data->amount);
            } else if($action_data->mode == "subtract") {
                $capacity_merge->setCapacity( $capacity_merge->getCapacity() - $action_data->amount);
            }
        }

        return $capacity_merge;
    }
}
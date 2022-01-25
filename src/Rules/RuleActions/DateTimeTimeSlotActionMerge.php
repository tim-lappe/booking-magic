<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Rules\RuleActions\MergeEntities\MergeEntityBase;
use TLBM\Rules\RuleActions\MergeEntities\TimeCapacities;

class DateTimeTimeSlotActionMerge extends RuleActionMergingBase {


    /**
     * @param MergeEntityBase $merge_obj
     * @return MergeEntityBase
     */
    public function merge(MergeEntityBase &$merge_obj): MergeEntityBase {
        if($merge_obj instanceof TimeCapacities) {
            $hour = $this->rule_action->GetTimeHour();
            $minute = $this->rule_action->GetTimeMin();

            $time_cap = $merge_obj->getTimeCapacity($hour, $minute);
            $capacity_merger = new CapacityMerger($this->action_data);
            $capacity_merger->merge($time_cap);
        }

        return $merge_obj;
    }

    /**
     * @return MergeEntityBase
     */
    public function getEmptyMergeInstance(): MergeEntityBase {
        return new TimeCapacities();
    }
}
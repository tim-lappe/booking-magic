<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Rules\RuleActions\MergeEntities\FullDateCapacites;
use TLBM\Rules\RuleActions\MergeEntities\MergeEntityBase;

class DateTimeSlotActionMerge extends RuleActionMergingBase
{

    /**
     * @param MergeEntityBase $merge_obj
     *
     * @return MergeEntityBase
     */
    public function merge(MergeEntityBase &$merge_obj): MergeEntityBase
    {
        if ($merge_obj instanceof FullDateCapacites) {
            $capacity_merger = new CapacityMerger($this->action_data);
            $merge_obj       = $capacity_merger->merge($merge_obj);
        }

        return $merge_obj;
    }

    public function getEmptyMergeInstance(): MergeEntityBase
    {
        return new FullDateCapacites();
    }
}
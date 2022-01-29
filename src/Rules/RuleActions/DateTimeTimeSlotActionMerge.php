<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;
use TLBM\Rules\RuleActions\MergeEntities\TimeCapacities;

class DateTimeTimeSlotActionMerge extends RuleActionMergingBase
{
    /**
     * @param MergeEntityInterface $mergeObj
     *
     * @return MergeEntityInterface
     */
    public function merge(MergeEntityInterface &$mergeObj): MergeEntityInterface
    {
        if ($mergeObj instanceof TimeCapacities) {
            $hour   = $this->ruleAction->getTimeHour();
            $minute = $this->ruleAction->getTimeMin();

            $timeCap        = $mergeObj->getTimeCapacity($hour, $minute);
            $capacityMerger = new CapacityMerger($this->actionData);
            $capacityMerger->merge($timeCap);
        }

        return $mergeObj;
    }

    /**
     * @return MergeEntityInterface
     */
    public function getEmptyMergeInstance(): MergeEntityInterface
    {
        return new TimeCapacities();
    }
}
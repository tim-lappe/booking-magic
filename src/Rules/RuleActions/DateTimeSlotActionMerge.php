<?php


namespace TLBM\Rules\RuleActions;


use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;
use TLBM\Rules\RuleActions\MergeEntities\FullDateCapacites;

class DateTimeSlotActionMerge extends RuleActionMergingBase
{

    /**
     * @param MergeEntityInterface $mergeObj
     *
     * @return MergeEntityInterface
     */
    public function merge(MergeEntityInterface &$mergeObj): MergeEntityInterface
    {
        if ($mergeObj instanceof FullDateCapacites) {
            $capacityMerger = new CapacityMerger($this->actionData);
            $mergeObj        = $capacityMerger->merge($mergeObj);
        }

        return $mergeObj;
    }

    public function getEmptyMergeInstance(): MergeEntityInterface
    {
        return new FullDateCapacites();
    }
}
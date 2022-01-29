<?php

namespace TLBM\Rules\RuleActions;

use TLBM\Rules\RuleActions\MergeEntities\Contracts\CapacityMerge;

class CapacityMerger
{

    public object $ruleActionData;

    public function __construct(object $ruleActionData)
    {
        $this->ruleActionData = $ruleActionData;
    }

    public function merge(CapacityMerge $capacityMerge): CapacityMerge
    {
        $actionData = $this->ruleActionData;
        if (isset($actionData->mode) && isset($actionData->amount)) {
            if ($actionData->mode == "set") {
                $capacityMerge->setCapacity(intval($actionData->amount));
            } elseif ($actionData->mode == "add") {
                $capacityMerge->setCapacity($capacityMerge->getCapacity() + $actionData->amount);
            } elseif ($actionData->mode == "subtract") {
                $capacityMerge->setCapacity($capacityMerge->getCapacity() - $actionData->amount);
            }
        }

        return $capacityMerge;
    }
}
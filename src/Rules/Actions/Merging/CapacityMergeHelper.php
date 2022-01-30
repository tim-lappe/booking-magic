<?php

namespace TLBM\Rules\Actions\Merging;

use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Merging\Contracts\CapacityMergeResultInterface;

class CapacityMergeHelper
{
    /**
     * @var CapacityMergeResultInterface
     */
    private CapacityMergeResultInterface $capacityMerge;

    public function __construct(CapacityMergeResultInterface $capacityMerge)
    {
        $this->capacityMerge = $capacityMerge;
    }

    /**
     * @param CapacityActionData $capacityActionData
     *
     * @return CapacityMergeResultInterface
     */
    public function mergeWithAction(CapacityActionData $capacityActionData): CapacityMergeResultInterface
    {
        if ($capacityActionData->getCapacityMode() == "set") {
            $this->capacityMerge->setCapacity($capacityActionData->getCapacityAmount());
        } elseif ($capacityActionData->getCapacityMode() == "add") {
            $this->capacityMerge->setCapacity($this->capacityMerge->getCapacity() + $capacityActionData->getCapacityAmount());
        } elseif ($capacityActionData->getCapacityMode() == "subtract") {
            $this->capacityMerge->setCapacity($this->capacityMerge->getCapacity() - $capacityActionData->getCapacityAmount());
        }

        return $this->capacityMerge;
    }
}
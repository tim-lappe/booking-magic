<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Merging\CapacityMergeHelper;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Results\CapacityResult;

class DateCapacityMerger extends Merger
{
    /**
     * @param MergeResultInterface|null $mergeResult
     *
     * @return CapacityResult|null
     */
    public function merge(?MergeResultInterface $mergeResult = null): ?CapacityResult
    {
        if($mergeResult == null) {
            $mergeResult = new CapacityResult();
        }

        if($mergeResult instanceof CapacityResult) {
            $capacityMergeHelper = new CapacityMergeHelper($mergeResult);
            $capacityMergeHelper->mergeWithAction($this->getActionData());

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        }

        return new CapacityResult();
    }

    /**
     * @return CapacityActionData|null
     */
    protected function getActionData(): ?CapacityActionData
    {
        $actionData = parent::getActionData();
        if($actionData instanceof CapacityActionData) {
            return $actionData;
        }

        return null;
    }

    /**
     * @return DateCapacityMerger|null
     */
    protected function getNextMerging(): ?DateCapacityMerger
    {
        $next = parent::getNextMerging();
        if($next instanceof DateCapacityMerger) {
            return $next;
        }

        return null;
    }
}
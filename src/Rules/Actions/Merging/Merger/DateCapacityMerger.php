<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\MainFactory;
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
            $mergeResult = MainFactory::create(CapacityResult::class);
        }

        if($mergeResult instanceof CapacityResult) {
            $capacityMergeHelper = new CapacityMergeHelper($mergeResult);
            $capacityMergeHelper->mergeWithAction($this->getActionData());

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        }

        return MainFactory::create(CapacityResult::class);
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

    public function sumUpResults($term, $mergeResult1, $mergeResult2)
    {
        if($term == "dateCapacity") {
            return $mergeResult1 + $mergeResult2;
        } else {
            return $mergeResult1;
        }
    }
}
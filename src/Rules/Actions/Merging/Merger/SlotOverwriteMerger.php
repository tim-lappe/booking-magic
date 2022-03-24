<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionData\SlotOverwriteData;
use TLBM\Rules\Actions\Merging\CapacityMergeHelper;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Results\CapacityResult;
use TLBM\Rules\Actions\Merging\Results\TimeCapacitiesCollectionResults;

class SlotOverwriteMerger extends Merger
{
    public function merge(?MergeResultInterface $mergeResult = null): ?MergeResultInterface
    {
        if ($mergeResult == null) {
            if ($this->getMergeTerm() == "timeCapacities") {
                $mergeResult = MainFactory::create(TimeCapacitiesCollectionResults::class);
            } else {
                $mergeResult = MainFactory::create(CapacityResult::class);
            }
        }

        $actionData = $this->getActionData();
        if ($mergeResult instanceof CapacityResult && $actionData->isFullDay()) {
            $capacityMergeHelper = new CapacityMergeHelper($mergeResult);
            $capacityMergeHelper->mergeWithAction($actionData);

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        } elseif ($mergeResult instanceof TimeCapacitiesCollectionResults) {
            $fromHour   = $actionData->getTimeHourFrom();
            $fromMinute = $actionData->getTimeMinuteFrom();
            $toHour     = $actionData->getTimeHourTo();
            $toMinute   = $actionData->getTimeMinuteTo();

            foreach ($mergeResult->getTimeCapacities() as &$timeCapacity) {
                if (($fromHour <= $timeCapacity->getHour() && $fromMinute <= $timeCapacity->getMinute() && $toHour >= $timeCapacity->getHour() && $toMinute >= $timeCapacity->getMinute()) || $actionData->isFullDay()) {
                    $capacityMergeHelper = new CapacityMergeHelper($timeCapacity);
                    $capacityMergeHelper->mergeWithAction($actionData);
                }
            }

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        }

        return MainFactory::create(CapacityResult::class);
    }

    /**
     * @return SlotOverwriteData|null
     */
    protected function getActionData(): ?SlotOverwriteData
    {
        $actionData = parent::getActionData();
        if ($actionData instanceof SlotOverwriteData) {
            return $actionData;
        }

        return null;
    }
}
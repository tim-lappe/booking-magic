<?php

namespace TLBM\Rules;

use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Rules\Actions\Merging\Results\CapacityResult;
use TLBM\Rules\Actions\Merging\Results\TimeCapacitiesCollectionResults;
use TLBM\Utilities\ExtendedDateTime;

class RulesCapacityManager implements Contracts\RulesCapacityManagerInterface
{

    /**
     * @inheritDoc
     */
    public function getOriginalCapacity(array $calendarIds, ExtendedDateTime $dateTime): int
    {
        $actionsMerging = MainFactory::create(ActionsMerging::class);
        $actionsMerging->setCalendarIds($calendarIds);
        $actionsMerging->setDateTime($dateTime);

        $mergedCollection = $actionsMerging->getRuleActionsMerged();

        $capacity = 0;
        foreach ($mergedCollection as $mergeData) {
            foreach ($mergeData->getMergeResult() as $result) {
                if($dateTime->isFullDay() && $result instanceof CapacityResult) {
                    $capacity += $result->getCapacityOriginal();
                } elseif (!$dateTime->isFullDay() && $result instanceof TimeCapacitiesCollectionResults) {
                    $timeCap = $result->getTimeCapacityAt($dateTime->getHour(), $dateTime->getMinute());
                    if($timeCap != null) {
                        $capacity += $timeCap->getCapacityOriginal();
                    }
                }
            }
        }
        
        return $capacity;
    }
}
<?php

namespace TLBM\Rules;

use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionsMerging;
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
            $actions = $mergeData->getMergedActions();
            $capacity += $actions['dateCapacity'];
        }
        
        return $capacity;
    }
}
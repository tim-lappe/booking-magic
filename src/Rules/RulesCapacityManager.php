<?php

namespace TLBM\Rules;

use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Utilities\ExtendedDateTime;

class RulesCapacityManager implements RulesCapacityManagerInterface
{

    /**
     * @param ExtendedDateTime $dateTime
     * @param Calendar $calendar
     *
     * @return int
     */
    public function getCapacitiesForCalendar(Calendar $calendar, ExtendedDateTime $dateTime): int
    {
        $query = MainFactory::create(RulesQueryInterface::class);
        $copyDateTime = $dateTime;
        $copyDateTime->setFullDay(true);

        $query->setDateTime($copyDateTime);
        $query->setTypeCalendar($calendar->getId());

        $actionsMerging = MainFactory::create(ActionsMerging::class);
        $actionsMerging->setRulesQuery($query);

        $timedMergedData = $actionsMerging->getRuleActionsMerged();
        $capacity = 0;
        foreach ($timedMergedData as $mergedDatum)  {
            $actions = $mergedDatum->getMergedActions();
            if(isset($actions['dateCapacity'])) {
                $capacity += intval($actions['dateCapacity']);
            }
        }

        return $capacity;
    }
}
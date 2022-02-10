<?php

namespace TLBM\Rules\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Utilities\ExtendedDateTime;

interface RulesCapacityManagerInterface
{
    /**
     * @param ExtendedDateTime $dateTime
     * @param array $calendarIds
     *
     * @return int
     */
    public function getOriginalCapacity(array $calendarIds, ExtendedDateTime $dateTime): int;
}
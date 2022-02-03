<?php

namespace TLBM\Rules\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Utilities\ExtendedDateTime;

interface RulesCapacityManagerInterface
{
    /**
     * @param ExtendedDateTime $dateTime
     * @param Calendar $calendar
     *
     * @return int
     */
    public function getCapacitiesForCalendar(Calendar $calendar, ExtendedDateTime $dateTime): int;
}
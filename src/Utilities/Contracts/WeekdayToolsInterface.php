<?php

namespace TLBM\Utilities\Contracts;

use DateTime;

interface WeekdayToolsInterface
{

    /**
     * @param string $weekday_def
     * @param DateTime $date_time
     *
     * @return bool
     */
    public function isInWeekdayDefinition(string $weekday_def, DateTime $date_time): bool;
}
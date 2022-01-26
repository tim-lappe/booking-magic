<?php

namespace TLBM\Utilities\Contracts;

use DateTime;
use TLBM\Entity\RulePeriod;

interface PeriodsToolsInterface
{
    /**
     * @param RulePeriod $period
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isDateTimeInPeriod(RulePeriod $period, DateTime $dateTime): bool;

    /**
     * @param RulePeriod[] $periods
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isDateTimeInPeriodCollection(array $periods, DateTime $dateTime): bool;
}
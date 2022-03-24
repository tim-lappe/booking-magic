<?php

namespace TLBM\Rules\Contracts;

use TLBM\Rules\Actions\Merging\Results\CapacityResult;
use TLBM\Utilities\ExtendedDateTime;

interface RulesCapacityManagerInterface
{
    /**
     * @param ExtendedDateTime $dateTime
     * @param array $calendarIds
     *
     * @return CapacityResult
     */
    public function getCapacityResult(array $calendarIds, ExtendedDateTime $dateTime): CapacityResult;
}
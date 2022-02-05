<?php

namespace TLBM\Repository\Query\Contracts;

use TLBM\Repository\Contracts\TimeBasedQueryInterface;
use TLBM\Rules\TimedRules;

interface FullRuleActionQueryInterface extends TimeBasedQueryInterface
{
    /**
     * @param int $calendarId
     *
     * @return void
     */
    public function setTypeCalendar(int $calendarId): void;

    /**
     * @param array $actionType
     *
     * @return void
     */
    public function setActionTypes(array $actionType): void;


    /**
     * @return TimedRules[]
     */
    public function getTimedRulesResult(): array;
}
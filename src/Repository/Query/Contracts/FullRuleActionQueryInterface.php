<?php

namespace TLBM\Repository\Query\Contracts;

use Iterator;

interface FullRuleActionQueryInterface extends TimeBasedQueryInterface
{
    /**
     * @param array $calendarIds
     *
     * @return void
     */
    public function setCalendarIds(array $calendarIds): void;

    /**
     * @param array $actionType
     *
     * @return void
     */
    public function setActionTypes(array $actionType): void;


    /**
     * @return Iterator
     */
    public function getTimedRulesResult(): Iterator;
}
<?php

namespace TLBM\Rules\Contracts;

use TLBM\Rules\Actions\TimedRuleCollection;
use TLBM\Rules\TimedRules;
use TLBM\Utilities\ExtendedDateTime;

interface RulesQueryInterface
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
     * @param ExtendedDateTime $dateTime
     *
     * @return void
     */
    public function setDateTime(ExtendedDateTime $dateTime): void;

    /**
     * @param ExtendedDateTime $dateTimeFrom
     * @param ExtendedDateTime $dateTimeTo
     *
     * @return void
     */
    public function setDateTimeRange(ExtendedDateTime $dateTimeFrom, ExtendedDateTime $dateTimeTo): void;

    /**
     * @return TimedRules[]
     */
    public function getResult(): array;
}
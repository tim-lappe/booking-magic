<?php

namespace TLBM\Rules\Contracts;

use DateTime;

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
     * @param DateTime $dateTime
     *
     * @return void
     */
    public function setDateTime(DateTime $dateTime): void;

    /**
     * @param DateTime $dateTimeFrom
     * @param DateTime $dateTimeTo
     *
     * @return void
     */
    public function setDateTimeRange(DateTime $dateTimeFrom, DateTime $dateTimeTo): void;

    /**
     * @return array
     */
    public function getResult(): array;
}
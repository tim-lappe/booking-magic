<?php

namespace TLBM\Repository\Contracts;

use TLBM\Utilities\ExtendedDateTime;
use Traversable;

interface TimeBasedQueryInterface
{

    /**
     * @param string $queryDateInterval
     *
     * @return Traversable
     */
    public function getResult(string $queryDateInterval = TLBM_EXTDATETIME_INTERVAL_DAY): Traversable;

    /**
     * @param ExtendedDateTime $fromDateTime
     */
    public function setFromDateTime(ExtendedDateTime $fromDateTime): void;

    /**
     * @return ExtendedDateTime
     */
    public function getFromDateTime(): ?ExtendedDateTime;

    /**
     * @param ExtendedDateTime $fromDt
     * @param ExtendedDateTime $toDt
     *
     * @return void
     */
    public function setDateTimeRange(ExtendedDateTime $fromDt, ExtendedDateTime $toDt);

    /**
     * @param ExtendedDateTime $dateTime
     */
    public function setDateTime(ExtendedDateTime $dateTime): void;

    /**
     * @return ExtendedDateTime
     */
    public function getToDateTime(): ?ExtendedDateTime;

    /**
     * @return ExtendedDateTime
     */
    public function getDateTime(): ?ExtendedDateTime;

    /**
     * @param ExtendedDateTime $toDateTime
     */
    public function setToDateTime(ExtendedDateTime $toDateTime): void;
}
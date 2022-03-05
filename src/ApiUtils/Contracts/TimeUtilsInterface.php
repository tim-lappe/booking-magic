<?php

namespace TLBM\ApiUtils\Contracts;

use DateTimeZone;

interface TimeUtilsInterface
{
    /**
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone;

    /**
     * @param string $format
     * @param int $timestampWithOffset
     *
     * @return string
     */
    public function formatI18n(string $format, int $timestampWithOffset): string;

    /**
     *
     * @return int
     */
    public function time(): int;
}
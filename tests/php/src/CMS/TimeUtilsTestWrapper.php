<?php

namespace TLBMTEST\CMS;

use DateTimeZone;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;

class TimeUtilsTestWrapper implements TimeUtilsInterface
{

    /**
     * @inheritDoc
     */
    public function getTimezone(): DateTimeZone
    {
        return new DateTimeZone("Europe/Berlin");
    }

    /**
     * @param string $format
     * @param int $timestampWithOffset
     *
     * @return string
     */
    public function formatI18n(string $format, int $timestampWithOffset): string
    {
        return gmdate($format, $timestampWithOffset);
    }


    /**
     * @return int
     */
    public function time(): int
    {
        return TLBM_TEST_TIMESTAMP;
    }
}
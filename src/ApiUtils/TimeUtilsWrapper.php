<?php

namespace TLBM\ApiUtils;

use DateTimeZone;

class TimeUtilsWrapper implements Contracts\TimeUtilsInterface
{

    /**
     * @inheritDoc
     */
    public function getTimezone(): DateTimeZone
    {
        return wp_timezone();
    }

    /**
     * @param string $format
     * @param int $timestampWithOffset
     *
     * @return string
     */
    public function formatI18n(string $format, int $timestampWithOffset): string
    {
        return date_i18n($format, $timestampWithOffset);
    }

    /**
     * @return int
     */
    public function time(): int
    {
        return time();
    }
}
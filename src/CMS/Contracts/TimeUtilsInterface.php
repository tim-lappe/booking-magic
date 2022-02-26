<?php

namespace TLBM\CMS\Contracts;

use DateTimeZone;

interface TimeUtilsInterface
{
    /**
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone;
}
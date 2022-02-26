<?php

namespace TLBMTEST\CMS;

use DateTimeZone;
use TLBM\CMS\Contracts\TimeUtilsInterface;

class TimeUtilsTestWrapper implements TimeUtilsInterface
{

    /**
     * @inheritDoc
     */
    public function getTimezone(): DateTimeZone
    {
        return new DateTimeZone("Europe/Berlin");
    }
}
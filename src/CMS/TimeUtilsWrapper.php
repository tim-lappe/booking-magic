<?php

namespace TLBM\CMS;

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
}
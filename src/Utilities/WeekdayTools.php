<?php


namespace TLBM\Utilities;

use DateTime;
use TLBM\Utilities\Contracts\WeekdayToolsInterface;

class WeekdayTools implements WeekdayToolsInterface
{

    public function __construct()
    {
    }

    /**
     * @param string $weekday_def
     * @param DateTime $date_time
     *
     * @return bool
     */
    public function isInWeekdayDefinition(string $weekday_def, DateTime $date_time): bool
    {
        $weekday = intval($date_time->format("N"));

        if ($weekday_def == "every_day") {
            return true;
        }
        if ($weekday_def == "mo_to_fr" && $weekday <= 5) {
            return true;
        }
        if ($weekday_def == "sat_and_sun" && $weekday >= 6) {
            return true;
        }
        if ($weekday_def == "monday" && $weekday == 1) {
            return true;
        }
        if ($weekday_def == "tuesday" && $weekday == 2) {
            return true;
        }
        if ($weekday_def == "wednesday" && $weekday == 3) {
            return true;
        }
        if ($weekday_def == "thursday" && $weekday == 4) {
            return true;
        }
        if ($weekday_def == "friday" && $weekday == 5) {
            return true;
        }
        if ($weekday_def == "saturday" && $weekday == 6) {
            return true;
        }
        if ($weekday_def == "sunday" && $weekday == 7) {
            return true;
        }

        return false;
    }
}
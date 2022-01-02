<?php

namespace TLBM\Localization;

class ScriptLocalization {

    public static function GetLabels(): array {
        $keys = self::GetLabelKeys();
        $arr = array();
        foreach ($keys as $key) {
            $arr[$key] = __($key, TLBM_TEXT_DOMAIN);
        }

        return $arr + self::GetLabelCollections();
    }

    public static function GetLabelCollections(): array {
        return array(
            "weekdays" => Labels::GetWeekdayLabels(),
            "months" => Labels::GetMonthLabels()
        );
    }

    public static function GetLabelKeys(): array {
        return array(
            "Add",
            "Weekdays",
            "Timeslot",
            "Capacity",
            "Every Day",
            "Monday to Friday",
            "Saturday and Sunday",
            "Multiple Weekdays",
            "Single Weekdays"
        );
    }
}
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
            "weekdays" => array(
                "monday" => __("Monday", TLBM_TEXT_DOMAIN),
                "tuesday" => __("Tuesday", TLBM_TEXT_DOMAIN),
                "wednesday" => __("Wednesday", TLBM_TEXT_DOMAIN),
                "thursday" => __("Thursday", TLBM_TEXT_DOMAIN),
                "friday" => __("Friday", TLBM_TEXT_DOMAIN),
                "saturday" => __("Saturday", TLBM_TEXT_DOMAIN),
                "sunday" => __("Sunday", TLBM_TEXT_DOMAIN),
            )
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
<?php

namespace TLBM\Localization;

use TLBM\Localization\Contracts\LabelsInterface;

class Labels implements LabelsInterface
{


    public function __construct()
    {
    }

    public function getMonthLabels(): array
    {
        return array(
            1  => __("January", TLBM_TEXT_DOMAIN),
            2  => __("February", TLBM_TEXT_DOMAIN),
            3  => __("March", TLBM_TEXT_DOMAIN),
            4  => __("April", TLBM_TEXT_DOMAIN),
            5  => __("May", TLBM_TEXT_DOMAIN),
            6  => __("June", TLBM_TEXT_DOMAIN),
            7  => __("July", TLBM_TEXT_DOMAIN),
            8  => __("August", TLBM_TEXT_DOMAIN),
            9  => __("September", TLBM_TEXT_DOMAIN),
            10 => __("October", TLBM_TEXT_DOMAIN),
            11 => __("November", TLBM_TEXT_DOMAIN),
            12 => __("December", TLBM_TEXT_DOMAIN),
        );
    }

    public function getWeekdayLabels(): array
    {
        return array(
            "monday"    => __("Monday", TLBM_TEXT_DOMAIN),
            "tuesday"   => __("Tuesday", TLBM_TEXT_DOMAIN),
            "wednesday" => __("Wednesday", TLBM_TEXT_DOMAIN),
            "thursday"  => __("Thursday", TLBM_TEXT_DOMAIN),
            "friday"    => __("Friday", TLBM_TEXT_DOMAIN),
            "saturday"  => __("Saturday", TLBM_TEXT_DOMAIN),
            "sunday"    => __("Sunday", TLBM_TEXT_DOMAIN),
        );
    }

    /**
     * @return array
     */
    public function getWeekdayRangeLabels(): array
    {
        return array(
            "every_day" => __("Every Day", TLBM_TEXT_DOMAIN),
            "mo_to_fr" => __("Monday to Friday", TLBM_TEXT_DOMAIN),
            "sat_and_sun" => __("Saturday and Sunday", TLBM_TEXT_DOMAIN)
        );
    }
}
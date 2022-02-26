<?php

namespace TLBM\Localization;

use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\Localization\Contracts\LabelsInterface;

class Labels implements LabelsInterface
{
    private LocalizationInterface $localization;

    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
    }

    public function getMonthLabels(): array
    {
        return array(
            1  => $this->localization->__("January", TLBM_TEXT_DOMAIN),
            2  => $this->localization->__("February", TLBM_TEXT_DOMAIN),
            3  => $this->localization->__("March", TLBM_TEXT_DOMAIN),
            4  => $this->localization->__("April", TLBM_TEXT_DOMAIN),
            5  => $this->localization->__("May", TLBM_TEXT_DOMAIN),
            6  => $this->localization->__("June", TLBM_TEXT_DOMAIN),
            7  => $this->localization->__("July", TLBM_TEXT_DOMAIN),
            8  => $this->localization->__("August", TLBM_TEXT_DOMAIN),
            9  => $this->localization->__("September", TLBM_TEXT_DOMAIN),
            10 => $this->localization->__("October", TLBM_TEXT_DOMAIN),
            11 => $this->localization->__("November", TLBM_TEXT_DOMAIN),
            12 => $this->localization->__("December", TLBM_TEXT_DOMAIN),
        );
    }

    public function getWeekdayLabels(): array
    {
        return array(
            "monday"    => $this->localization->__("Monday", TLBM_TEXT_DOMAIN),
            "tuesday"   => $this->localization->__("Tuesday", TLBM_TEXT_DOMAIN),
            "wednesday" => $this->localization->__("Wednesday", TLBM_TEXT_DOMAIN),
            "thursday"  => $this->localization->__("Thursday", TLBM_TEXT_DOMAIN),
            "friday"    => $this->localization->__("Friday", TLBM_TEXT_DOMAIN),
            "saturday"  => $this->localization->__("Saturday", TLBM_TEXT_DOMAIN),
            "sunday"    => $this->localization->__("Sunday", TLBM_TEXT_DOMAIN),
        );
    }

    /**
     * @return array
     */
    public function getWeekdayRangeLabels(): array
    {
        return array(
            "every_day" => $this->localization->__("Every Day", TLBM_TEXT_DOMAIN),
            "mo_to_fr" => $this->localization->__("Monday to Friday", TLBM_TEXT_DOMAIN),
            "sat_and_sun" => $this->localization->__("Saturday and Sunday", TLBM_TEXT_DOMAIN)
        );
    }
}
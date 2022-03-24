<?php

namespace TLBM\Localization;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
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
        return [1 => $this->localization->getText("January", TLBM_TEXT_DOMAIN),
            2 => $this->localization->getText("February", TLBM_TEXT_DOMAIN),
            3 => $this->localization->getText("March", TLBM_TEXT_DOMAIN),
            4 => $this->localization->getText("April", TLBM_TEXT_DOMAIN),
            5 => $this->localization->getText("May", TLBM_TEXT_DOMAIN),
            6 => $this->localization->getText("June", TLBM_TEXT_DOMAIN),
            7 => $this->localization->getText("July", TLBM_TEXT_DOMAIN),
            8 => $this->localization->getText("August", TLBM_TEXT_DOMAIN),
            9 => $this->localization->getText("September", TLBM_TEXT_DOMAIN),
            10 => $this->localization->getText("October", TLBM_TEXT_DOMAIN),
            11 => $this->localization->getText("November", TLBM_TEXT_DOMAIN),
            12 => $this->localization->getText("December", TLBM_TEXT_DOMAIN),
        ];
    }

    public function getWeekdayLabels(): array
    {
        return ["monday" => $this->localization->getText("Monday", TLBM_TEXT_DOMAIN),
            "tuesday" => $this->localization->getText("Tuesday", TLBM_TEXT_DOMAIN),
            "wednesday" => $this->localization->getText("Wednesday", TLBM_TEXT_DOMAIN),
            "thursday" => $this->localization->getText("Thursday", TLBM_TEXT_DOMAIN),
            "friday" => $this->localization->getText("Friday", TLBM_TEXT_DOMAIN),
            "saturday" => $this->localization->getText("Saturday", TLBM_TEXT_DOMAIN),
            "sunday" => $this->localization->getText("Sunday", TLBM_TEXT_DOMAIN),
        ];
    }

    /**
     * @return array
     */
    public function getWeekdayRangeLabels(): array
    {
        return ["every_day" => $this->localization->getText("Every Day", TLBM_TEXT_DOMAIN),
            "mo_to_fr" => $this->localization->getText("Monday to Friday", TLBM_TEXT_DOMAIN),
            "sat_and_sun" => $this->localization->getText("Saturday and Sunday", TLBM_TEXT_DOMAIN)
        ];
    }
}
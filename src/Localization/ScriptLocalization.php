<?php

namespace TLBM\Localization;

use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Localization\Contracts\ScriptLocalizationInterface;

class ScriptLocalization implements ScriptLocalizationInterface
{

    /**
     * @var LabelsInterface
     */
    private LabelsInterface $labels;

    public function __construct(LabelsInterface $labels)
    {
        $this->labels = $labels;
    }

    public function getLabels(): array
    {
        $keys = $this->getLabelKeys();
        $arr  = array();
        foreach ($keys as $key) {
            $arr[$key] = __($key, TLBM_TEXT_DOMAIN);
        }

        return $arr + $this->getLabelCollections();
    }

    public function getLabelKeys(): array
    {
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

    public function getLabelCollections(): array
    {
        return array(
            "weekdays" => $this->labels->getWeekdayLabels(),
            "weekdaysRange" => $this->labels->getWeekdayRangeLabels(),
            "months"   => $this->labels->getMonthLabels()
        );
    }
}
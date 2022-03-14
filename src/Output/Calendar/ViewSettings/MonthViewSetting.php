<?php

namespace TLBM\Output\Calendar\ViewSettings;
use TLBM\Localization\Contracts\LabelsInterface;

class MonthViewSetting
{

    public array $weekdayLabels = [];

    /**
     * @param LabelsInterface $labels
     */
    public function __construct(LabelsInterface $labels)
    {
        $this->weekdayLabels = array_values($labels->getWeekdayLabels());
    }
}

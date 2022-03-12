<?php

namespace TLBM\Output\Calendar\ViewSettings;
use TLBM\Localization\Contracts\LabelsInterface;

class MonthViewSetting
{

    public array $weekday_labels = array();

    /**
     * @param LabelsInterface $labels
     */
    public function __construct(LabelsInterface $labels)
    {
        $this->weekday_labels = $labels->getWeekdayLabels();
    }
}

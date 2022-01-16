<?php

namespace TLBM\Output\Calendar\ViewSettings;

use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;

class MonthViewSetting {

    public array $weekday_labels = array();

    public function __construct() {
        $this->weekday_labels = array_values(WeekdayLabels::GetLongWeekdayLabels());
    }
}

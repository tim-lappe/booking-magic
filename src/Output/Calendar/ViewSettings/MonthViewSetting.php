<?php

namespace TLBM\Output\Calendar\ViewSettings;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;

class MonthViewSetting
{

    public array $weekday_labels = array();

    /**
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(SettingsManagerInterface $settingsManager)
    {
        $setting = $settingsManager->getSetting(WeekdayLabels::class);
        if ($setting instanceof WeekdayLabels) {
            $this->weekday_labels = array_values($setting->getLongWeekdayLabels());
        }
    }
}

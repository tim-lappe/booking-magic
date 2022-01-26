<?php

namespace TLBM\Output\Calendar\ViewSettings;

use stdClass;

class SettingsCollection
{

    public array $settings = array();

    public function __construct()
    {
        $this->settings = $this->GetDefaultSettingClasses();
    }

    public function GetDefaultSettingClasses(): array
    {
        return array(
            "month" => MonthViewSetting::class
        );
    }

    public function CreateDefaultSettingForView(string $view)
    {
        if (isset($this->settings[$view])) {
            return new $this->settings[$view]();
        }

        return new stdClass();
    }
}
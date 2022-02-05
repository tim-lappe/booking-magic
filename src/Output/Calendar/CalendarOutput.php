<?php


namespace TLBM\Output\Calendar;

use TLBM\Output\Calendar\ViewSettings\SettingsCollection;
use TLBM\Utilities\ExtendedDateTime;

class CalendarOutput
{

    /**
     * @param int|null $calendarId
     * @param ExtendedDateTime|null $focusedDateTime
     * @param string $view
     * @param object|null $viewSettings
     * @param string $formName
     * @param bool $readonly
     *
     * @return string
     */
    public static function GetCalendarContainerShell(
        ?int $calendarId = null,
        ?ExtendedDateTime $focusedDateTime = null,
        string $view = "no-view",
        object $viewSettings = null,
        string $formName = "calendar",
        bool $readonly = false
    ): string {
        $options = array(
            "dataSourceId"   => $calendarId,
            "dataSourceType" => "calendar",
            "focusedDateTime"   => $focusedDateTime ?? new ExtendedDateTime(),
            "readonly"         => $readonly
        );

        if ($viewSettings == null) {
            $settings_collection = new SettingsCollection();
            $viewSettings        = $settings_collection->CreateDefaultSettingForView($view);
        }

        $options      = urlencode(json_encode($options));
        $viewSettings = urlencode(json_encode($viewSettings));

        return sprintf(
            '<div class="tlbm-calendar-container" data-json=\'%s\' data-view=\'%s\' data-name=\'%s\' data-view-settings=\'%s\'"></div>', $options, $view, $formName, $viewSettings
        );
    }
}
<?php


namespace TLBM\Output\Calendar;


use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\Printers\CalendarDateSelectPrinter;
use TLBM\Output\Calendar\Printers\CalendarNoPrinter;
use TLBM\Output\Calendar\Printers\CalendarPrinterBase;
use TLBM\Output\Calendar\ViewSettings\SettingsCollection;

class CalendarOutput {

    /**
     * @var CalendarPrinterBase[]
     */
    private static array $calendarPrinters = array();

    public static function RegisterCalendarPrinters() {
        self::$calendarPrinters[] = new CalendarDateSelectPrinter();
    }

	/**
	 * @param CalendarGroup $group
	 *
	 * @return CalendarPrinterBase
	 */
    public static function GetCalendarPrinterForCalendarGroup(CalendarGroup $group): CalendarPrinterBase {
        foreach(self::$calendarPrinters as $calendarPrinter) {
            if($calendarPrinter->CanPrintGroup($group)) {
                return $calendarPrinter;
            }
        }

        return new CalendarNoPrinter();
    }

    /**
     * @param int $calendar_id
     * @param int $focused_tstamp
     * @param string $view
     * @param object|null $view_settings
     * @param string $form_name
     * @param bool $readonly
     * @param string $weekday_form
     * @return string
     */
    public static function GetCalendarContainerShell(int $calendar_id, int $focused_tstamp, string $view = "no-view", object $view_settings = null, string $form_name = "calendar", bool $readonly = false, string $weekday_form = "short"): string {
        $options = array(
            "id" => $calendar_id,
            "type" => "calendar",
            "focused_tstamp" => $focused_tstamp,
            "readonly" => $readonly,
            "weekday_form" => $weekday_form,
        );

        if($view_settings == null) {
            $settings_collection = new SettingsCollection();
            $view_settings = $settings_collection->CreateDefaultSettingForView($view);
        }

        $options = urlencode(json_encode($options));
        $view_settings = urlencode(json_encode($view_settings));
        return sprintf('<div class="tlbm-calendar-container" data-json=\'%s\' data-view=\'%s\' data-name=\'%s\' data-view-settings=\'%s\'"></div>', $options, $view, $form_name, $view_settings);
    }

    public static function GetContainerShell($group_or_calendar_id, $view = "no-view"): string {

    }
}
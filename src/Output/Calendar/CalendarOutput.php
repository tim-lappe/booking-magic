<?php


namespace TLBM\Output\Calendar;


use TLBM\Model\Calendar;
use TLBM\Calendar\CalendarManager;
use TLBM\Output\Calendar\Printers\CalendarDateSelectPrinter;
use TLBM\Output\Calendar\Printers\CalendarNoPrinter;
use TLBM\Output\Calendar\Printers\CalendarPrinterBase;

class CalendarOutput {

    /**
     * @var CalendarPrinterBase[]
     */
    private static $calendarPrinters;

    public static function RegisterCalendarPrinters() {
        self::$calendarPrinters[] = new CalendarDateSelectPrinter();
    }

    /**
     * @param Calendar $calendar
     *
     * @return CalendarPrinterBase
     */
    public static function GetCalendarPrinterForCalendar(Calendar $calendar): CalendarPrinterBase {
        foreach(self::$calendarPrinters as $calendarPrinter) {
            if($calendarPrinter->CanPrintCalendar($calendar)) {
                return $calendarPrinter;
            }
        }

        return new CalendarNoPrinter();
    }

    public static function GetTsClassForCalendar(Calendar $calendar): string {
        foreach(self::$calendarPrinters as $calendarPrinter) {
            if($calendarPrinter->CanPrintCalendar($calendar)) {
                return $calendarPrinter->GetTsClass($calendar);
            }
        }

        return "";
    }

	/**
	 * @param $id
	 * @param string $weekday_form
	 * @param string $form_name
	 *
	 * @return string
	 */
    public static function GetCalendarContainerShell($id, $weekday_form = "short", $form_name = ""): string {
        $data = array(
            "id" => $id,
            "focused_tstamp" => time(),
            "selectable" => true,
	        "weekday_form" => $weekday_form,
	        "form_name" => empty($form_name) ? "calendar_" .  $id : $form_name
        );

        $calendar = CalendarManager::GetCalendar($id);
        $tsClass = self::GetTsClassForCalendar($calendar);

        $data = (json_encode($data));


        return sprintf('<div class="tlbm-calendar-container" data=\'%s\' tsClass="%s"></div>', $data, $tsClass);
    }
}
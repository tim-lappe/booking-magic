<?php


namespace TL_Booking\Output\Calendar;


use TL_Booking\Model\Calendar;
use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Output\Calendar\Printers\CalendarDateSelectPrinter;
use TL_Booking\Output\Calendar\Printers\CalendarNoPrinter;
use TL_Booking\Output\Calendar\Printers\CalendarPrinterBase;

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
	 * @param string $form_name
	 *
	 * @return string
	 */
    public static function GetCalendarContainerShell($id, $form_name = ""): string {
        $data = array(
            "id" => $id,
            "focused_tstamp" => time(),
            "selectable" => true,
	        "form_name" => empty($form_name) ? "calendar_" .  $id : $form_name
        );

        $calendar = CalendarManager::GetCalendar($id);
        $tsClass = self::GetTsClassForCalendar($calendar);

        $data = (json_encode($data));


        return sprintf('<div class="tlbm-calendar-container" data=\'%s\' tsClass="%s"></div>', $data, $tsClass);
    }
}
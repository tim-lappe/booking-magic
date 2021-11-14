<?php


namespace TLBM\Output\Calendar;


use TLBM\Model\Calendar;
use TLBM\Calendar\CalendarManager;
use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\Printers\CalendarDateSelectPrinter;
use TLBM\Output\Calendar\Printers\CalendarNoPrinter;
use TLBM\Output\Calendar\Printers\CalendarPrinterBase;

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


    public static function GetContainerShell($group_or_calendar_id, $view = "dateselect_monthview", $weekday_form = "short", $form_name = ""): string {
	    $data = array(
	    	"id" => $group_or_calendar_id,
		    "focused_tstamp" => time(),
		    "selectable" => true,
		    "weekday_form" => $weekday_form,
		    "form_name" => $form_name,
		    "view" => $view
	    );

	    $data = (json_encode($data));
	    return sprintf('<div class="tlbm-calendar-container" data=\'%s\' view=\'%s\'></div>', $data, $view);
    }
}
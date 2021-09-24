<?php


namespace TL_Booking\Output\Calendar\Printers;


use TL_Booking\Model\Calendar;

class CalendarNoPrinter extends CalendarPrinterBase {

    /**
     * @inheritDoc
     */
    public function CanPrintCalendar(Calendar $calendar): bool {
        return false;
    }

    public function GetOutput(array &$data, $process_data = false): string {
        return "There is no Calendar Printer registered for this Calendar";
    }

    public function ProcessData(array &$data) {

    }

    public function GetTsClass(Calendar $calendar): string {
        return "";
    }
}
<?php


namespace TLBM\Output\Calendar\Printers;


use TLBM\Model\Calendar;
use TLBM\Model\CalendarGroup;

class CalendarNoPrinter extends CalendarPrinterBase {

    /**
     * @inheritDoc
     */
    public function CanPrintGroup(CalendarGroup $group): bool {
        return false;
    }

    public function GetOutput(array &$data, $process_data = false): string {
        return "There is no Calendar Printer registered for this Calendar";
    }

    public function ProcessData(array &$data) {

    }
}
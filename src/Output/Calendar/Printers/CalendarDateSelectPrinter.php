<?php


namespace TLBM\Output\Calendar\Printers;


use TLBM\Model\Calendar;
use TLBM\Output\Calendar\Modules\ModuleDateSelected;

class CalendarDateSelectPrinter extends CalendarMonthViewPrinter {

    public function __construct() {
        parent::__construct();

        $this->AddModule("dateSelected", new ModuleDateSelected());
    }

    /**
     * @param Calendar $calendar
     *
     * @return bool
     */
    public function CanPrintCalendar(Calendar $calendar): bool {
        return $calendar->calendar_setup->booking_mode == "only_date";
    }

    public function GetTsClass(Calendar $calendar): string {
        return "CalendarDateSelect";
    }
}
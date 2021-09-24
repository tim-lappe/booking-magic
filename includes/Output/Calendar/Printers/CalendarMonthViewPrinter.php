<?php


namespace TL_Booking\Output\Calendar\Printers;


use DateInterval;
use DateTime;
use TL_Booking\Model\Calendar;
use TL_Booking\Output\Calendar\Modules\ModuleBodyDateSelect;
use TL_Booking\Output\Calendar\Modules\ModuleHeadMonthSelect;

class CalendarMonthViewPrinter extends CalendarPrinterBase {

    public function __construct() {
        parent::__construct();

        $this->AddModule("default", new ModuleHeadMonthSelect());
        $this->AddModule("default", new ModuleBodyDateSelect());
    }

    /**
     * @param Calendar $calendar
     *
     * @return bool
     */
    public function CanPrintCalendar(Calendar $calendar): bool {
        return true;
    }

    public function ProcessData(array &$data) {
        $date = new DateTime();
        $date->setTimestamp($data['focused_tstamp']);

        if($data['nextMonth']) {
            $date->add(DateInterval::createFromDateString("1 month"));
            unset($data['nextMonth']);
        } else if($data['prevMonth']) {
            $date->sub(DateInterval::createFromDateString("1 month"));
            unset($data['prevMonth']);
        }

        $data['focused_tstamp'] = $date->getTimestamp();

        parent::ProcessData($data);
    }

    public function GetTsClass(Calendar $calendar): string {
        return "CalendarDateSelect";
    }
}
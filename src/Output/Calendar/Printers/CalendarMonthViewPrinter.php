<?php


namespace TLBM\Output\Calendar\Printers;


use DateInterval;
use DateTime;
use TLBM\Model\Calendar;
use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\Modules\ModuleBodyDateSelect;
use TLBM\Output\Calendar\Modules\ModuleHeadMonthSelect;

class CalendarMonthViewPrinter extends CalendarPrinterBase {

    public function __construct() {
        parent::__construct();

        $this->AddModule("default", new ModuleHeadMonthSelect());
        $this->AddModule("default", new ModuleBodyDateSelect());
    }

	/**
	 * @param CalendarGroup $group
	 *
	 * @return bool
	 */
    public function CanPrintGroup(CalendarGroup $group): bool {
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
}
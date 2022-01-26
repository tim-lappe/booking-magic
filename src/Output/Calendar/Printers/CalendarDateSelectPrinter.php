<?php


namespace TLBM\Output\Calendar\Printers;


use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\Modules\ModuleDateSelected;

class CalendarDateSelectPrinter extends CalendarMonthViewPrinter
{

    public function __construct()
    {
        parent::__construct();

        $this->AddModule("dateSelected", new ModuleDateSelected());
    }

    /**
     * @param CalendarGroup $group
     *
     * @return bool
     */
    public function CanPrintGroup(CalendarGroup $group): bool
    {
        return true;
    }
}
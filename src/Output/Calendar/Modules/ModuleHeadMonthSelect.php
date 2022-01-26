<?php


namespace TLBM\Output\Calendar\Modules;


use DateTime;

class ModuleHeadMonthSelect implements ICalendarPrintModule
{

    public function GetOutput($data): string
    {
        $tstamp = $data['focused_tstamp'];

        if ( ! $tstamp) {
            $tstamp = time();
        }

        $date = new DateTime();
        $date->setTimestamp($tstamp);


        $month_title = $date->format("F, Y");

        $html = "<div class='tlbm-calendar'>";
        $html .= "<div class='tlbm-month-head'>";
        $html .= "<button class='tlbm-button tlbm-prev-month'>" . __("Previous", TLBM_TEXT_DOMAIN) . "</button>";
        $html .= "<div class='tlbm-month-title'>" . $month_title . "</div>";
        $html .= "<button class='tlbm-button tlbm-next-month'>" . __("Next", TLBM_TEXT_DOMAIN) . "</button>";
        $html .= "</div>";

        return $html;
    }
}
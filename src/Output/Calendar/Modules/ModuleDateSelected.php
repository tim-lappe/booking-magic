<?php


namespace TLBM\Output\Calendar\Modules;


use TLBM\Calendar\CalendarManager;
use TLBM\Utilities\DateTimeTools;

class ModuleDateSelected implements ICalendarPrintModule {

    /**
     * @inheritDoc
     */
    public function GetOutput($data): string {
	    $calendar = CalendarManager::GetCalendar($data['id']);

        $html = "<div class='tlbm-selected-date-finished-container'>";
        $html .= "<p>" . __("Selected date: ", TLBM_TEXT_DOMAIN) ."</p>";
        $html .= "<p class='tlbm-calendar-show-selected-date'><strong>". DateTimeTools::Format($data['selected_tstamp']) . "</strong></p>";
        $html .= "<button class='tlbm-button-select-another'>" . __("Select antoher Date", TLBM_TEXT_DOMAIN) ."</button>";
        $html .= "<input type='hidden' name='".$data['form_name']."' value='".$data['selected_tstamp']."'>";
        $html .= "</div>";

        return $html;
    }
}
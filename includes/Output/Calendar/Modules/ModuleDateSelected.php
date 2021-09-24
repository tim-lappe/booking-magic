<?php


namespace TL_Booking\Output\Calendar\Modules;


use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Utilities\DateTimeTools;

class ModuleDateSelected implements ICalendarPrintModule {

    /**
     * @inheritDoc
     */
    public function GetOutput($data): string {
	    $calendar = CalendarManager::GetCalendar($data['id']);

        $html = "<div class='tlbm-selected-date-finished-container'>";
        $html .= "<p>" . __("Selected date: ", TLBM_TEXT_DOMAIN) ."</p>";
        $html .= "<h3><strong>". DateTimeTools::Format($data['selected_tstamp']) . "</strong></h3>";
        $html .= "<button class='tlbm-button-select-another'>" . __("Select antoher Date", TLBM_TEXT_DOMAIN) ."</button>";
        $html .= "<input type='hidden' name='".$data['form_name']."' value='".$data['selected_tstamp']."'>";
        $html .= "</div>";

        return $html;
    }
}
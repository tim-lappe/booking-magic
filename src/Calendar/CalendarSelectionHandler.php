<?php


namespace TLBM\Calendar;

use TLBM\Model\CalendarSelection;

if (!defined('ABSPATH')) {
    return;
}

class CalendarSelectionHandler {

    /**
     * @param CalendarSelection $calendar_selection
     * @param int               $calendar_id
     *
     * @return bool
     */
    public static function ContainsCalendar(CalendarSelection $calendar_selection, int $calendar_id): bool {
        if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return true;
        } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return in_array($calendar_id, $calendar_selection->selected_calendar_ids);
        } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            return !in_array($calendar_id, $calendar_selection->selected_calendar_ids);
        }

        return false;
    }
}
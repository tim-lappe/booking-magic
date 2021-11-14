<?php


namespace TLBM\Calendar;

use TLBM\Model\Calendar;
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
        } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            return in_array($calendar_id, $calendar_selection->selected_calendar_ids);
        } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            return !in_array($calendar_id, $calendar_selection->selected_calendar_ids);
        }
        return false;
    }

	/**
	 * @param CalendarSelection $calendar_selection
	 *
	 * @return array|Calendar[]
	 */
    public static function GetSelectedCalendarList(CalendarSelection  $calendar_selection): array {
    	if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
    		return CalendarManager::GetAllCalendars();
	    } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
    		$list = array();
    		foreach ($calendar_selection->selected_calendar_ids as $id) {
    			$cal = CalendarManager::GetCalendar($id);
				$list[] = $cal;
		    }
    		return $list;
	    } else if($calendar_selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
    		$allcals = CalendarManager::GetAllCalendars();
    		$list = array();
		    foreach ( $allcals as $cal ) {
				if(!in_array($cal->wp_post_id, $calendar_selection->selected_calendar_ids)) {
					$list[] = $cal;
				}
    		}
		    return $list;
	    }

    	return array();
    }
}
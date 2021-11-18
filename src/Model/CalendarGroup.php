<?php


namespace TLBM\Model;


use TLBM\Calendar\CalendarGroupManager;

class CalendarGroup {

	public int $wp_post_id = 0;

	public string $title = "";

	public string $booking_distribution = TLBM_BOOKING_DISTRIBUTION_EVENLY;

	public ?CalendarSelection $calendar_selection = null;

	public static function FromCalendarOrGroupId(int $id): ?CalendarGroup {
		$group = CalendarGroupManager::GetCalendarGroup($id);
		if(!$group) {
			$group = new CalendarGroup();
			$group->calendar_selection = new CalendarSelection();
			$group->calendar_selection->selection_type = TLBM_CALENDAR_SELECTION_TYPE_ONLY;
			$group->calendar_selection->selected_calendar_ids = array( $id );
		}
		return $group;
	}
}
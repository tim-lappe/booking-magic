<?php


namespace TLBM\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Model\CalendarGroup;
use TLBM\Model\CalendarSelection;
use TLBM\Output\Calendar\CalendarOutput;


class MBCalendarPreview extends MetaBoxBase {

	function RegisterMetaBox() {
		$this->AddMetaBox("calendar_preview", "Preview");
	}

	function PrintMetaBox(\WP_Post $post) {
		$group = new CalendarGroup();
		$group->calendar_selection = new CalendarSelection();
		$group->calendar_selection->selection_type = TLBM_CALENDAR_SELECTION_TYPE_ONLY;
		$group->calendar_selection->selected_calendar_ids = array( $post->ID );

		echo CalendarOutput::GetContainerShell($post->ID);
	}

	/**
	 * @return mixed
	 */
	function GetOnPostTypes(): array {
		return array (TLBM_PT_CALENDAR);
	}
}
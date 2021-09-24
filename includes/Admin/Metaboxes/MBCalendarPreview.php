<?php


namespace TL_Booking\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TL_Booking\Output\Calendar\CalendarOutput;


class MBCalendarPreview extends MetaBoxBase {

	function RegisterMetaBox() {
		$this->AddMetaBox("calendar_preview", "Preview");
	}

	function PrintMetaBox(\WP_Post $post) {
		echo CalendarOutput::GetCalendarContainerShell($post->ID);
	}

	/**
	 * @return mixed
	 */
	function GetOnPostTypes(): array {
		return array (TLBM_PT_CALENDAR);
	}
}
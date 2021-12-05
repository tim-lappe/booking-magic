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
		echo CalendarOutput::GetContainerShell($post->ID);
	}

	/**
	 * @return mixed
	 */
	function GetOnPostTypes(): array {
		return array (TLBM_PT_CALENDAR);
	}
}
<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use WP_Post;

class MBGroupCalendars extends MetaBoxForm {

	/**
	 * @inheritDoc
	 */
	function GetOnPostTypes(): array {
		return array(TLBM_PT_CALENDAR_GROUPS);
	}

	/**
	 * @inheritDoc
	 */
	function RegisterMetaBox() {
		$this->AddMetaBox("group_select_calendars", "Select Calendars");
	}

	/**
	 * @inheritDoc
	 */
	function PrintMetaBox( WP_Post $post ) {
		$form_builder = new FormBuilder();

		$calendar_selection = get_post_meta($post->ID, "calendar_selection", true);
		$form_builder->PrintFormHead();
		$form_builder->PrintFormField(new CalendarPickerField("calendars", __("Calendars", TLBM_TEXT_DOMAIN), $calendar_selection));
		$form_builder->PrintFormFooter();
	}

	/**
	 * @inheritDoc
	 */
	function OnSave( $post_id ) {
		$calendar_selection = CalendarPickerField::GetCalendarSelectionFromRequest("calendars", $_REQUEST);
		if($calendar_selection) {
			update_post_meta($post_id, "calendar_selection", $calendar_selection);
		}
	}
}
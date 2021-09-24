<?php


namespace TL_Booking\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TL_Booking\Admin\WpForm\CalendarPickerField;
use TL_Booking\Admin\WpForm\FormBuilder;
use WP_Post;

class MBCapacityRuleCalendars extends MetaBoxForm {

	/**
	 * @return array
	 */
	function GetOnPostTypes(): array {
		return array(TLBM_PT_RULES);
	}

	/**
	 * @return mixed
	 */
	function RegisterMetaBox() {
		$this->AddMetaBox("capacity_rule_calendars", "Calendars");
	}

    /**
     * @param WP_Post $post
     */
	function PrintMetaBox(WP_Post $post) {
		$form_builder = new FormBuilder();

		$calendar_selection = get_post_meta($post->ID, "calendar_selection", true);

		$form_builder->PrintFormHead();
		$form_builder->PrintFormField(new CalendarPickerField("calendars", __("Calendars", TLBM_TEXT_DOMAIN), $calendar_selection));
		$form_builder->PrintFormFooter();
	}

	/**
	 * @param $post_id
	 *
	 */
	function OnSave( $post_id ) {
		$calendar_selection = CalendarPickerField::GetCalendarSelectionFromRequest("calendars", $_REQUEST);
		if($calendar_selection) {
			update_post_meta($post_id, "calendar_selection", $calendar_selection);
		}
	}
}
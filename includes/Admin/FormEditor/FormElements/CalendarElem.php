<?php


namespace TL_Booking\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TL_Booking\Admin\FormEditor\FormItemSettingsElements\Select;
use TL_Booking\Admin\FormEditor\FormItemSettingsElements\SettingsPrinting;
use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Output\Calendar\CalendarOutput;

class CalendarElem extends FormInputElem {

	public function __construct( ) {
		parent::__construct( "calendar", __("Calendar", TLBM_TEXT_DOMAIN) );

		$this->editor_output = "<div class='tlbm-form-item-calendar'>".__("Calendar", TLBM_TEXT_DOMAIN)."</div>";
		$this->menu_category = __("Calendar", TLBM_TEXT_DOMAIN);
		$this->description = __("Allows the user to choose from a calendar or a group of calendars", TLBM_TEXT_DOMAIN);

		$calendars = CalendarManager::GetAllCalendars();
		$calendar_kv = array();

		foreach ($calendars as $calendar) {
			$calendar = get_post( $calendar->wp_post_id );
			if ( ! empty( $calendar->post_title ) ) {
				$calendar_kv[ $calendar->ID ] = $calendar->post_title;
			} else {
				$calendar_kv[ $calendar->ID ] = __( "No Name", TLBM_TEXT_DOMAIN ) . "ID: " . $calendar->ID . ")";
			}
		}

		$calendar_select = array(
			__("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv
		);

		$default_calendar = sizeof($calendar_kv) > 0 ? array_keys($calendar_kv)[0] : "";

		$this->settings[] = new Select("selected_calendar", __("Calendar", TLBM_TEXT_DOMAIN), $calendar_select, new SettingsPrinting(), $default_calendar);
		$this->settings[] = new Select("weekdays_form", __("Weekday Labels", TLBM_TEXT_DOMAIN),
			array(
				"long" => __("Long", TLBM_TEXT_DOMAIN),
				"short" => __("Short", TLBM_TEXT_DOMAIN)
			),
			new SettingsPrinting(), "short" );

		$this->has_user_input = true;
	}

	public function Validate( $form_data, $input_vars ): bool {
		if(isset($form_data['name'])) {
			$name = $form_data['name'];
			if(isset($input_vars[$name])) {
				return !empty($input_vars[$name]);
			}
		}

		return false;
	}

	/**
     * @param      $data_obj
     * @param null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, $insert_child = null): string {
		if($data_obj->selected_calendar) {
			$calendar = CalendarManager::GetCalendar( $data_obj->selected_calendar );
			return CalendarOutput::GetCalendarContainerShell( $calendar->wp_post_id, $data_obj->weekdays_form, $data_obj->name );
		} else {
			return "<div class='tlbm-no-calendar-alert'>" . __("No calendar or calendargroup selected", TLBM_TEXT_DOMAIN) . "</div>";
		}
	}
}

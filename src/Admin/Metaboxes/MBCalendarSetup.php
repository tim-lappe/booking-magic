<?php


namespace TLBM\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Calendar\CalendarManager;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\RadioField;
use TLBM\Admin\WpForm\TimeSpanField;
use TLBM\Output\Language\CalendarSetupText;
use TLBM\Utilities\DateTimeTools;
use WP_Post;

class MBCalendarSetup extends MetaBoxForm {


	/**
	 * @inheritDoc
	 */
	function RegisterMetaBox() {
		$this->AddMetaBox("calendar_setup", "Setup");
	}

	/**
	 * @param $post_id
	 *
	 */
	function OnSave( $post_id ) {
		if(isset($_POST['booking_mode']) &&
		   isset($_POST['minimum_booking_distance_days']) &&
		   isset($_POST['minimum_booking_distance_hours']) &&
		   isset($_POST['minimum_booking_distance_minutes']) &&
		   isset($_POST['maximal_booking_distance_years']) &&
		   isset($_POST['maximal_booking_distance_days']) &&
		   isset($_POST['maximal_booking_distance_hours']) &&
		   isset($_POST['maximal_booking_distance_minutes'])) {

			$calendar_setup               = CalendarManager::GetCalendarSetup( $post_id );
			$calendar_setup->booking_mode = $_POST['booking_mode'];

			$mbd_days    = $_POST['minimum_booking_distance_days'];
			$mbd_hours   = $_POST['minimum_booking_distance_hours'];
			$mbd_minutes = $_POST['minimum_booking_distance_minutes'];

			$calendar_setup->earliest_booking_from_now = DateTimeTools::FromTimepartsToMinutes( 0, $mbd_days, $mbd_hours, $mbd_minutes );

			$lbd_years   = $_POST['maximal_booking_distance_years'];
			$lbd_days    = $_POST['maximal_booking_distance_days'];
			$lbd_hours   = $_POST['maximal_booking_distance_hours'];
			$lbd_minutes = $_POST['maximal_booking_distance_minutes'];

			$calendar_setup->latest_booking_from_now = DateTimeTools::FromTimepartsToMinutes( $lbd_years, $lbd_days, $lbd_hours, $lbd_minutes );

			if ( $calendar_setup->earliest_booking_from_now > $calendar_setup->latest_booking_from_now ) {
				$calendar_setup->latest_booking_from_now = $calendar_setup->earliest_booking_from_now + ( 60 * 24 * 2 );
			}

			CalendarManager::SaveCalendarSetup( $post_id, $calendar_setup );
		}
	}

    /**
     * @param WP_Post $post
     */
	function PrintMetaBox(WP_Post $post) {
		$calendar_setup = CalendarManager::GetCalendarSetup($post->ID);

		$options = array( "only_date" => "" , "date_range" => "" , "flexible_datetime" => "" , "slotted_datetime" => "");
		foreach($options as $key => &$value) {
			$value = __(CalendarSetupText::GetBookingModeText($key), TLBM_TEXT_DOMAIN);
		}

		$form_builder = new FormBuilder();
		$form_builder->PrintFormHead();
		$form_builder->PrintFormField(new RadioField("booking_mode", __("Booking Mode", TLBM_TEXT_DOMAIN), $options, $calendar_setup->booking_mode));
		$form_builder->PrintFormField(new TimeSpanField("minimum_booking_distance", __("Earliest Booking from now", TLBM_TEXT_DOMAIN), array("days", "hours", "minutes"), $calendar_setup->earliest_booking_from_now));
		$form_builder->PrintFormField(new TimeSpanField("maximal_booking_distance", __("Latest Booking from now", TLBM_TEXT_DOMAIN), array("years", "days", "hours", "minutes"), $calendar_setup->latest_booking_from_now));

		$form_builder->PrintFormFooter();
	}

	/**
	 * @return mixed
	 */
	function GetOnPostTypes(): array {
		return array(TLBM_PT_CALENDAR);
	}
}
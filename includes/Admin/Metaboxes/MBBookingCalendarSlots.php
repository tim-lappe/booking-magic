<?php


namespace TL_Booking\Admin\Metaboxes;


use TL_Booking\Booking\BookingManager;
use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Model\Calendar;
use TL_Booking\Utilities\DateTimeTools;
use WP_Post;

class MBBookingCalendarSlots extends MetaBoxBase {

	function GetOnPostTypes(): array {
		return array(TLBM_PT_BOOKING);
	}

	function RegisterMetaBox() {
		$this->AddMetaBox("booking_calendarslots", "Booked Calendars");
	}

	function PrintMetaBox( WP_Post $post ) {
		$booking = BookingManager::GetBooking($post->ID);
		foreach ($booking->calendar_slots as $key => $calendar_slot) {
			$calendar = CalendarManager::GetCalendar($calendar_slot->booked_calendar_id);
			$datetime = new \DateTime();
			$datetime->setTimestamp($calendar_slot->timestamp);

			echo "<h3>".date_i18n(DateTimeTools::GetDateFormat(), $calendar_slot->timestamp). " " .$datetime->format(DateTimeTools::GetTimeFormat()) . "</h3>";

			if($key < sizeof($booking->calendar_slots) - 1) {
				echo "<hr>";
			}
		}

		if(sizeof($booking->calendar_slots) == 0) {
			_e("<h3>No Calendars were booked</h3>", TLBM_TEXT_DOMAIN);
		}
	}
}
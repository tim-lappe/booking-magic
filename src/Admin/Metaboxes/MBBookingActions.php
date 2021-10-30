<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\WpForm\BookingStateSelectField;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\SelectField;
use TLBM\Booking\BookingManager;
use TLBM\Model\Booking;
use WP_Post;

class MBBookingActions extends MetaBoxForm {

	function GetOnPostTypes(): array {
		return array(TLBM_PT_BOOKING);
	}

	function RegisterMetaBox() {
		$this->AddMetaBox("booking_actions", "Manage Booking");
	}

	function PrintMetaBox( WP_Post $post ) {
		$form_builder = new FormBuilder();

		$booking = BookingManager::GetBooking($post->ID);

		$form_builder->PrintFormHead();
		$form_builder->PrintFormField(new BookingStateSelectField("booking_state", "State", $booking->state));
		$form_builder->PrintFormFooter();
	}

	function OnSave( $post_id ) {
		if(isset($_REQUEST['booking_state'])) {
			$statename = $_REQUEST['booking_state'];
			$booking = BookingManager::GetBooking($post_id);
			$booking->state = $statename;
			BookingManager::SetBooking($booking);
		}
	}
}
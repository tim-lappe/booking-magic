<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\SelectField;
use WP_Post;

define("TLBM_BOOKING_DISTRIBUTION_EVENLY", "evenly");
define("TLBM_BOOKING_DISTRIBUTION_FILL_ONE", "fill_one");

class MBGroupBookingOrder extends MetaBoxForm {

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
		$this->AddMetaBox("booking_order", "Booking");
	}

	/**
	 * @inheritDoc
	 */
	function PrintMetaBox( WP_Post $post ) {
		$form_builder = new FormBuilder();

		$calendar_selection = get_post_meta($post->ID, "booking_distribution", true);
		$form_builder->PrintFormHead();
		$form_builder->PrintFormField(new SelectField("booking_distribution", __("Distribution", TLBM_TEXT_DOMAIN),
			array(
				TLBM_BOOKING_DISTRIBUTION_EVENLY => __("Distribute Bookings Evenly", TLBM_TEXT_DOMAIN),
				TLBM_BOOKING_DISTRIBUTION_FILL_ONE => __("Fill One After The Other ", TLBM_TEXT_DOMAIN)
			),
			$calendar_selection));
		$form_builder->PrintFormFooter();
	}

	/**
	 * @inheritDoc
	 */
	function OnSave( $post_id ) {
		if(isset($_REQUEST['booking_distribution'])) {
			update_post_meta($post_id, "booking_distribution", $_REQUEST['booking_distribution']);
		}
	}
}
<?php


namespace TLBM\Request;


use TLBM\Booking\BookingProcessing;
use TLBM\Form\FormManager;
use TLBM\Output\FrontendMessenger;
use TLBM\Utilities\DateTimeTools;

class ShowBookingOverview extends RequestBase {

	public function __construct() {
		parent::__construct();

		$this->action = "showbookingoverview";

	}

	public function OnAction( $vars ) {
	    $verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
		if(isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
			$form_id = $vars['form'];
			$form = FormManager::GetForm($form_id);
			if($form) {
				$booking_processing = new BookingProcessing($vars, $form);
				$not_filled_dps = $booking_processing->Validate();
				if(sizeof($not_filled_dps) == 0) {
					$this->html_output = true;
				} else {
					FrontendMessenger::AddFrontendMsg(__("Not all required fields were filled out",TLBM_TEXT_DOMAIN));
				}
			}
		}
	}

	public function GetHtmlOutput( $vars ): string {
		$verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
		if(isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
			$form_id = $vars['form'];
			$form    = FormManager::GetForm( $form_id );
			if($form) {
				$html = "<h2>".__("Booking overview", TLBM_TEXT_DOMAIN)."</h2>";
				$booking_processing = new BookingProcessing($vars, $form);
				$booking_values = $booking_processing->ReadBookingValues();
				$booking = $booking_processing->GetProcessedBooking();

				$html .= "<form action='".$_SERVER['REQUEST_URI']."' method='post'>";
				$html .= "<div class='tlbm-booking-overview-box'><div class='tlbm-formular-content'>";

				if(isset($booking_values["first_name"]) && isset($booking_values["last_name"])) {
					$html .= "<h3 class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</h3>";
					$html .= "<span>" . $booking_values["first_name"]->value . "</span>&nbsp;<span>" . $booking_values["last_name"]->value . "</span>";
				}

				if(isset($booking_values["address"]) && isset($booking_values["zip"])  && isset($booking_values["city"])) {
					$html .= "<h3 class='tlbm-overview-section-title'>" . __("Address", TLBM_TEXT_DOMAIN) . "</h3>";
					$html .= "<span>" . $booking_values["address"]->value . "</span><br>";
					$html .= "<span>" . $booking_values["zip"]->value . "</span>&nbsp;<span>" . $booking_values["city"]->value . "</span>";
				}

				if(isset($booking_values["contact_email"])) {
					$html .= "<h3 class='tlbm-overview-section-title'>" . __("E-Mail", TLBM_TEXT_DOMAIN) . "</h3>";
					$html .= "<span>" . $booking_values["contact_email"]->value . "</span>";
				}

				$html .= "</div><div class='tlbm-formular-booked-calendar'>";

				if(sizeof($booking->calendar_slots) >= 1) {
					$html .= "<h3 class='tlbm-overview-section-title'>" . __("Booked time", TLBM_TEXT_DOMAIN) . "</h3>";
					$html .= DateTimeTools::FormatWithTime($booking->calendar_slots[0]->timestamp);
				}

				$html .= "</div></div>";

				$echovars = array_diff_key($vars, array("_wpnonce" => 0, "form" => 0));
				foreach ($echovars as $key => $value) {
					$html .= "<input type='hidden' name='".$key."' value='".$value."'>";
				}

				$html .= "<input type='hidden' name='action' value='dobooking'>";
				$html .= "<input type='hidden' name='form' value='" . $form_id . "'>";
				$html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
				$html .= "<button class='tlbm-book-now-btn'>".__("Book Now", TLBM_TEXT_DOMAIN)."</button>";
				$html .= "</form>";

				return $html;
			}
		}

		return "";
	}
}
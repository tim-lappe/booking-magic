<?php


namespace TL_Booking\Request;

use TL_Booking\Booking\BookingManager;
use TL_Booking\Booking\BookingProcessing;
use TL_Booking\Form\FormManager;
use TL_Booking\Model\Booking;
use TL_Booking\Output\FrontendMessenger;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class DoBookingRequest extends RequestBase {

    public function __construct() {
        parent::__construct();

        $this->action = "dobooking";

	    $this->html_output = true;
    }

    public function OnAction( $vars ) {
        if(isset($vars['form']) && intval($vars['form']) > 0 && wp_verify_nonce($vars['_wpnonce'], "dobooking_action")) {
            $form_id = $vars['form'];
            $form = FormManager::GetForm($form_id);
            if($form) {
                $booking_processing = new BookingProcessing($vars, $form);
                $not_filled_dps = $booking_processing->Validate();
                if(sizeof($not_filled_dps) == 0) {
                    $booking = $booking_processing->GetProcessedBooking();

                    BookingManager::SetBooking($booking);

                } else {
					FrontendMessenger::AddFrontendMsg(__("Not all required fields were filled out",TLBM_TEXT_DOMAIN));
                }
            }
        }
    }

    public function GetHtmlOutput( $vars ): string {
	    return "
	    <h2>Die Buchung ist erfolgreich eingegangen.</h2>
	    <p>Sie erhalten in kürze eine Bestätigungsmail</p>
		";
    }
}
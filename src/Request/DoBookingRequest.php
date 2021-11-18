<?php


namespace TLBM\Request;

use TLBM\Booking\BookingManager;
use TLBM\Booking\BookingProcessing;
use TLBM\Booking\MainValues;
use TLBM\Email\MailSender;
use TLBM\Form\FormManager;
use TLBM\Model\Booking;
use TLBM\Output\FrontendMessenger;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class DoBookingRequest extends RequestBase {

	public $booking_successed = false;
	public $error = false;

    public function __construct() {
        parent::__construct();

        $this->action = "dobooking";
	    $this->html_output = true;
    }

    public function OnAction( $vars ) {
    	$verifyed = wp_verify_nonce($vars['_wpnonce'], "dobooking_action");
        if(isset($vars['form']) && intval($vars['form']) > 0 && $verifyed) {
            $form_id = $vars['form'];
            $form = FormManager::GetForm($form_id);
            if($form) {
                $booking_processing = new BookingProcessing($vars, $form);
                $not_filled_dps = $booking_processing->Validate();
                if(sizeof($not_filled_dps) == 0) {
                    $booking = $booking_processing->GetProcessedBooking();
                    $mainvals = new MainValues($booking);
                    $booking->title = $mainvals->GetBookingTitle();
                    BookingManager::SetBooking($booking);

                    if($booking->booking_values['contact_email']) {
                    	$vars = array();
                    	foreach ($booking->booking_values as $value) {
                    		$vars[$value->key] = $value->value;
	                    }
	                    MailSender::SendTemplate($booking->booking_values['contact_email']->value, "email_booking_confirmation", $vars);
                    }

                    $this->booking_successed = true;

                } else {
					FrontendMessenger::AddFrontendMsg(__("Not all required fields were filled out",TLBM_TEXT_DOMAIN));
	                $this->html_output = false;
                }
            }
        } else {
	        $this->error = true;
        }
    }

    public function GetHtmlOutput( $vars ): string {
    	if($this->booking_successed === true) {
		    return "<h2>Die Buchung ist erfolgreich eingegangen.</h2><p>Sie erhalten in kürze eine Bestätigungsmail</p>";
	    } else if($this->error) {
		    return "<h2>Es ist ein Fehler aufgetreten</h2><p>Ihre Buchung konnte nicht bearbeitet werden, da ein unbekannter Fehler aufgetreten ist</p>" . "<p>" . $this->booking_successed . "</p>";
	    } else {
    		return "";
	    }
    }
}
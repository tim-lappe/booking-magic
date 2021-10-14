<?php


namespace TL_Booking\Admin\Settings\SingleSettings\Emails;


use TL_Booking\Admin\Settings\SingleSettings\SettingsBase;
use TL_Booking\Email\MailSender;

class EmailBookingConfirmation extends EmailSetting {

	public function __construct() {
		parent::__construct( "email_booking_confirmation",
            __("Booking Confirmation", TLBM_TEXT_DOMAIN),
            __("Your Booking Confirmation"));
	}

	public function GetDefaultTemplate(): string {
	    return file_get_contents(TLBM_DIR . "/templates/email/booking_confirmation.html");
	}
}
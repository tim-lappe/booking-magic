<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


class EmailBookingConfirmation extends EmailSetting
{

    public function __construct()
    {
        parent::__construct(
            "email_booking_confirmation", __("Booking Confirmation", TLBM_TEXT_DOMAIN), __("Your Booking Confirmation")
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/booking_confirmation.html");
    }
}
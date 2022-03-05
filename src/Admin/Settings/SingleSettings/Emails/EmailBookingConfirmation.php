<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\ApiUtils\Contracts\LocalizationInterface;

class EmailBookingConfirmation extends EmailSetting
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "email_booking_confirmation", $localization->__("Booking Confirmation", TLBM_TEXT_DOMAIN), $localization->__("Your Booking Confirmation", TLBM_TEXT_DOMAIN)
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/booking_confirmation.html");
    }
}
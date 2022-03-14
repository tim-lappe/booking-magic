<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\ApiUtils\Contracts\LocalizationInterface;

class EmailBookingCancelled extends EmailSetting
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "email_booking_cancelled", $localization->__("Booking has been cancelled", TLBM_TEXT_DOMAIN), $localization->__("Booking has been cancelled", TLBM_TEXT_DOMAIN)
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/bookingCancelled.html");
    }
}
<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\ApiUtils\Contracts\LocalizationInterface;

class EmailBookingReceived extends EmailSetting
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "email_booking_received", $localization->getText("Booking has been received", TLBM_TEXT_DOMAIN), $localization->getText("Booking has been received", TLBM_TEXT_DOMAIN)
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/bookingReceived.html");
    }
}
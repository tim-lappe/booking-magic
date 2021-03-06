<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\ApiUtils\Contracts\LocalizationInterface;

class EmailBookingInProcess extends EmailSetting
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "email_booking_in_process", $localization->getText("Booking is being processed", TLBM_TEXT_DOMAIN), $localization->getText("Booking is being processed", TLBM_TEXT_DOMAIN)
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/bookingInProcess.html");
    }
}
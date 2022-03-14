<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\ApiUtils\Contracts\LocalizationInterface;

class AdminEmailBookingReceived extends EmailSetting
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "email_admin_booking_received", $localization->__("Admin Notification:<br>New booking received", TLBM_TEXT_DOMAIN), $localization->__("New Booking", TLBM_TEXT_DOMAIN)
        );
    }

    public function getDefaultTemplate(): string
    {
        return file_get_contents(TLBM_DIR . "/templates/email/adminBookingReceived.html");
    }
}
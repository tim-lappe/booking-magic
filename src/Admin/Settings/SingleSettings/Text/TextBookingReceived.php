<?php


namespace TLBM\Admin\Settings\SingleSettings\Text;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TextBookingReceived extends SettingsBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "text", "text_booking_received", $localization->__("Booking Received", TLBM_TEXT_DOMAIN), $localization->__(
                      "<h2>Your booking has been received successfully</h2><p>You will receive a confirmation email soon</p>", TLBM_TEXT_DOMAIN
                  )
        );
    }

    public function display()
    {
        ?>
        <label>
            <textarea class="regular-text tlbm-admin-textarea" name="<?php
            echo $this->optionName ?>"><?php
                echo $this->getValue() ?></textarea>
        </label>
        <?php
    }
}
<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\CMS\Contracts\LocalizationInterface;

class SinglePageBooking extends SettingsBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("booking_process", "single_page_booking", $localization->__("One Page Booking", TLBM_TEXT_DOMAIN), "off");
    }

    public function display()
    {
        ?>
        <label>
            <input type="checkbox" name="<?php
            echo $this->optionName ?>" <?php
            checked($this->getValue() == "on"); ?>>
        </label>
        <?php
    }
}
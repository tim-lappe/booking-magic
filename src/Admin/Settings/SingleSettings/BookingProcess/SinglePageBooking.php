<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class SinglePageBooking extends SettingsBase
{

    public function __construct()
    {
        parent::__construct("booking_process", "single_page_booking", __("One Page Booking", TLBM_TEXT_DOMAIN), "off");
    }

    public function PrintField()
    {
        ?>
        <label>
            <input type="checkbox" name="<?php
            echo $this->option_name ?>" <?php
            checked(get_option($this->option_name) == "on"); ?>>
        </label>
        <?php
    }
}
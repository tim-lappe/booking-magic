<?php


namespace TLBM\Admin\Settings\SingleSettings\Text;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class TextBookingReceived extends SettingsBase
{

    public function __construct()
    {
        parent::__construct(
            "text",
            "text_booking_received",
            __("Booking Received", TLBM_TEXT_DOMAIN),
            __(
                "<h2>Your booking has been received successfully</h2><p>You will receive a confirmation email soon</p>",
                TLBM_TEXT_DOMAIN
            )
        );
    }

    public function PrintField()
    {
        ?>
        <label>
            <textarea class="regular-text tlbm-admin-textarea" name="<?php
            echo $this->option_name ?>"><?php
                echo get_option($this->option_name) ?></textarea>
        </label>
        <?php
    }
}
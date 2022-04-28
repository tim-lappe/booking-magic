<?php


namespace TLBM\Admin\Settings\SingleSettings\Text;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TextBookingReceived extends SettingsBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct(
            "text", "text_booking_received", $localization->getText("Booking Received", TLBM_TEXT_DOMAIN), $localization->getText(
            "<h2>Your booking has been received successfully</h2><p>You will receive a confirmation email soon</p>", TLBM_TEXT_DOMAIN
        )
        );
    }

    public function getValue()
    {
        return urldecode(parent::getValue());
    }

    public function display()
    {
        ?>
        <div class="tlbm-html-editor" data-name="<?php echo $this->escaping->escAttr($this->optionName); ?>" data-value="<?php echo $this->escaping->escAttr($this->getValue()); ?>"></div>
        <?php
    }
}
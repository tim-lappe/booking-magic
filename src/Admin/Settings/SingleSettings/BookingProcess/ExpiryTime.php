<?php

namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class ExpiryTime extends SettingsBase
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("booking_process", "expiry_time", __("Expiry Time (in Minutes)", TLBM_TEXT_DOMAIN), 10);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $val = parent::getValue();
        $val = intval($val);
        return max(1, $val);
    }

    /**
     * @return void
     */
    public function display()
    {
        ?>
        <label>
            <input type="number" class="tlbm-number-field" name="<?php echo $this->optionName ?>" value="<?php echo $this->getValue() ?>">
        </label>
        <?php
    }
}
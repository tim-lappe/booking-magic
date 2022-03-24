<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class DefaultBookingState extends SettingsBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("booking_process", "booking_default_state", $localization->getText("Default Booking State", TLBM_TEXT_DOMAIN), "new");
    }

    public function display()
    {
        ?>
        <label>
            <select name="<?php
            echo $this->optionName ?>">
                <?php
                $states = $this->settingsManager->getValue(BookingStates::class);
                foreach ($states as $state): ?>
                    <option value="<?php
                    echo $state['name'] ?>" <?php
                    selected($state['name'] == $this->getValue()) ?>><?php
                        echo $state['title'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
        <?php
    }
}
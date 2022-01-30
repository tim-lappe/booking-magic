<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class DefaultBookingState extends SettingsBase
{

    public function __construct()
    {
        parent::__construct(
            "booking_process", "booking_default_state", __("Default Booking State", TLBM_TEXT_DOMAIN), "new"
        );
    }

    public function getDefault(): array
    {
        return BookingStates::getStateByName(get_option("booking_default_state", "new"));
    }

    public function getDefaultName(): string
    {
        return get_option("booking_default_state", "new");
    }

    public function display()
    {
        ?>
        <label>
            <select name="<?php
            echo $this->optionName ?>">
                <?php
                $states = BookingStates::getStates();
                foreach ($states as $state): ?>
                    <option value="<?php
                    echo $state['name'] ?>" <?php
                    selected($state['name'] == get_option($this->optionName, $this->defaultValue)) ?>><?php
                        echo $state['title'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
        <?php
    }
}
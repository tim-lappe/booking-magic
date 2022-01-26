<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class DefaultBookingState extends SettingsBase
{

    public function __construct()
    {
        parent::__construct(
            "booking_process",
            "booking_default_state",
            __("Default Booking State", TLBM_TEXT_DOMAIN),
            "new"
        );
    }

    public static function GetDefault(): array
    {
        return BookingStates::GetStateByName(get_option("booking_default_state", "new"));
    }

    public static function GetDefaultName(): string
    {
        return get_option("booking_default_state", "new");
    }

    public function PrintField()
    {
        ?>
        <label>
            <select name="<?php
            echo $this->option_name ?>">
                <?php
                $states = BookingStates::GetStates();
                foreach ($states as $state): ?>
                    <option value="<?php
                    echo $state['name'] ?>" <?php
                    selected($state['name'] == get_option($this->option_name, $this->default_value)) ?>><?php
                        echo $state['title'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
        <?php
    }
}
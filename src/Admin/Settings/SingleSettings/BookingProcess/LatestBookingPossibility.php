<?php

namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Utilities\ExtendedDateTime;

class LatestBookingPossibility extends SettingsBase
{
    /**
     *
     */
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("booking_process", "latest_booking_possibility", $localization->__("Latest booking possibility", TLBM_TEXT_DOMAIN), ["hours" => 0,
            "days" => 1
        ]);
    }

    public function isValueValid($value): bool
    {
        if (isset($value['days']) && isset($value['hours'])) {
            return true;
        }

        return false;
    }

    public function getLatestPossibilityDateTime(): ExtendedDateTime
    {
        $value = $this->getValue();
        $dt    = new ExtendedDateTime();
        $dt->setHour($dt->getHour() + intval($value['hours']));
        $dt->setDay($dt->getDay() + intval($value['days']));

        return $dt;
    }

    /**
     * @return void
     */
    public function display()
    {
        $value = $this->getValue();

        ?>
        <label>
            <select name="<?php
            echo $this->optionName ?>[days]">
                <?php
                for ($i = 0; $i < 365; $i++): ?>
                    <option <?php
                    echo selected($i, $value['days']) ?> value="<?php
                    echo $i ?>"><?php
                        echo $i . "&nbsp;" . $this->localization->__("Days", TLBM_TEXT_DOMAIN) ?></option>
                <?php
                endfor; ?>
            </select>
            <select name="<?php
            echo $this->optionName ?>[hours]">
                <?php
                for ($i = 0; $i < 24; $i++): ?>
                    <option <?php
                    echo selected($i, $value['hours']) ?> value="<?php
                    echo $i ?>"><?php
                        echo $i . "&nbsp;" . $this->localization->__("Hours", TLBM_TEXT_DOMAIN) ?></option>
                <?php
                endfor; ?>
            </select>
            <b><?php
                echo $this->localization->__("Before booking time", TLBM_TEXT_DOMAIN) ?></b>
        </label>
        <?php
    }
}
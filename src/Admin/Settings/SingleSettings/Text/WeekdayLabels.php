<?php


namespace TLBM\Admin\Settings\SingleSettings\Text;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class WeekdayLabels extends SettingsBase
{


    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("text", "weekday_labels", $localization->__("Weekdays", TLBM_TEXT_DOMAIN), array(
            "long"  => array(
                "mon" => $localization->__("Monday", TLBM_TEXT_DOMAIN),
                "tue" => $localization->__("Tuesday", TLBM_TEXT_DOMAIN),
                "wed" => $localization->__("Wednesday", TLBM_TEXT_DOMAIN),
                "thu" => $localization->__("Thursday", TLBM_TEXT_DOMAIN),
                "fri" => $localization->__("Friday", TLBM_TEXT_DOMAIN),
                "sat" => $localization->__("Saturday", TLBM_TEXT_DOMAIN),
                "sun" => $localization->__("Sunday", TLBM_TEXT_DOMAIN),
            ),
            "short" => array(
                "mon" => $localization->__("Mon", TLBM_TEXT_DOMAIN),
                "tue" => $localization->__("Tue", TLBM_TEXT_DOMAIN),
                "wed" => $localization->__("Wed", TLBM_TEXT_DOMAIN),
                "thu" => $localization->__("Thu", TLBM_TEXT_DOMAIN),
                "fri" => $localization->__("Fri", TLBM_TEXT_DOMAIN),
                "sat" => $localization->__("Sat", TLBM_TEXT_DOMAIN),
                "sun" => $localization->__("Sun", TLBM_TEXT_DOMAIN),
            )
        ));
    }

    public function getWeekdayLabels($name): array
    {
        if ($name == "long") {
            return $this->getLongWeekdayLabels();
        } else {
            return $this->getShortWeekdayLabels();
        }
    }

    public function getLongWeekdayLabels(): array
    {
        $value = $this->settingsManager->getValue(WeekdayLabels::class);
        if (is_array($value)) {
            if (isset($value['long']) && is_array($value['long'])) {
                return $value['long'];
            }
        }

        return array();
    }

    public function getShortWeekdayLabels(): array
    {
        $value = $this->settingsManager->getValue(WeekdayLabels::class);
        if (is_array($value)) {
            if (isset($value['short']) && is_array($value['short'])) {
                return $value['short'];
            }
        }

        return array();
    }

    public function display()
    {
        if ($this->CheckOptions()) {
            $this->settingsManager->setValue(WeekdayLabels::class, $this->defaultValue);
        }

        ?>
        <table class="tlbm-inner-settings-table">
            <thead>
            <tr>
                <th><?php
                    echo $this->localization->__("Long form", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php
                    echo $this->localization->__("Short form", TLBM_TEXT_DOMAIN) ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][mon]" value="<?php
                        echo $this->getValue()["long"]["mon"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][mon]" value="<?php
                        echo $this->getValue()["short"]["mon"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][tue]" value="<?php
                        echo $this->getValue()["long"]["tue"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][tue]" value="<?php
                        echo $this->getValue()["short"]["tue"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][wed]" value="<?php
                        echo $this->getValue()["long"]["wed"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][wed]" value="<?php
                        echo $this->getValue()["short"]["wed"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][thu]" value="<?php
                        echo $this->getValue()["long"]["thu"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][thu]" value="<?php
                        echo $this->getValue()["short"]["thu"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][fri]" value="<?php
                        echo $this->getValue()["long"]["fri"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][fri]" value="<?php
                        echo $this->getValue()["short"]["fri"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][sat]" value="<?php
                        echo $this->getValue()["long"]["sat"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][sat]" value="<?php
                        echo $this->getValue()["short"]["sat"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[long][sun]" value="<?php
                        echo $this->getValue()["long"]["sun"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->optionName ?>[short][sun]" value="<?php
                        echo $this->getValue()["short"]["sun"]; ?>">
                    </label>
                </td>
            </tr>
            </tbody>
        </table>

        <?php
    }

    /**
     * @return bool
     */
    private function CheckOptions(): bool
    {
        $option = $this->settingsManager->getValue(WeekdayLabels::class);
        $keys   = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
        $fail   = false;

        if (isset($option['long']) && is_array($option['long'])) {
            foreach ($keys as $item) {
                if ( !isset($option['long'][$item])) {
                    $fail = true;
                }
            }
        }
        if (isset($option['short']) && is_array($option['short'])) {
            foreach ($keys as $item) {
                if ( !isset($option['short'][$item])) {
                    $fail = true;
                }
            }
        }

        return $fail;
    }

}
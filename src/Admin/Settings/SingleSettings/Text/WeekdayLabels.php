<?php


namespace TLBM\Admin\Settings\SingleSettings\Text;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class WeekdayLabels extends SettingsBase
{

    public function __construct()
    {
        parent::__construct("text", "weekday_labels", __("Weekdays", TLBM_TEXT_DOMAIN), array(
            "long"  => array(
                "mon" => __("Monday", TLBM_TEXT_DOMAIN),
                "tue" => __("Tuesday", TLBM_TEXT_DOMAIN),
                "wed" => __("Wednesday", TLBM_TEXT_DOMAIN),
                "thu" => __("Thursday", TLBM_TEXT_DOMAIN),
                "fri" => __("Friday", TLBM_TEXT_DOMAIN),
                "sat" => __("Saturday", TLBM_TEXT_DOMAIN),
                "sun" => __("Sunday", TLBM_TEXT_DOMAIN),
            ),
            "short" => array(
                "mon" => __("Mon", TLBM_TEXT_DOMAIN),
                "tue" => __("Tue", TLBM_TEXT_DOMAIN),
                "wed" => __("Wed", TLBM_TEXT_DOMAIN),
                "thu" => __("Thu", TLBM_TEXT_DOMAIN),
                "fri" => __("Fri", TLBM_TEXT_DOMAIN),
                "sat" => __("Sat", TLBM_TEXT_DOMAIN),
                "sun" => __("Sun", TLBM_TEXT_DOMAIN),
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
            $this->settingsManager->setValue(WeekdayLabels::class, $this->default_value);
        }

        ?>
        <table class="tlbm-inner-settings-table">
            <thead>
            <tr>
                <th><?php
                    echo __("Long form") ?></th>
                <th><?php
                    echo __("Short form") ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][mon]" value="<?php
                        echo get_option($this->option_name)["long"]["mon"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][mon]" value="<?php
                        echo get_option($this->option_name)["short"]["mon"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][tue]" value="<?php
                        echo get_option($this->option_name)["long"]["tue"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][tue]" value="<?php
                        echo get_option($this->option_name)["short"]["tue"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][wed]" value="<?php
                        echo get_option($this->option_name)["long"]["wed"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][wed]" value="<?php
                        echo get_option($this->option_name)["short"]["wed"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][thu]" value="<?php
                        echo get_option($this->option_name)["long"]["thu"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][thu]" value="<?php
                        echo get_option($this->option_name)["short"]["thu"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][fri]" value="<?php
                        echo get_option($this->option_name)["long"]["fri"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][fri]" value="<?php
                        echo get_option($this->option_name)["short"]["fri"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][sat]" value="<?php
                        echo get_option($this->option_name)["long"]["sat"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][sat]" value="<?php
                        echo get_option($this->option_name)["short"]["sat"]; ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[long][sun]" value="<?php
                        echo get_option($this->option_name)["long"]["sun"]; ?>">
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="regular-text" name="<?php
                        echo $this->option_name ?>[short][sun]" value="<?php
                        echo get_option($this->option_name)["short"]["sun"]; ?>">
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
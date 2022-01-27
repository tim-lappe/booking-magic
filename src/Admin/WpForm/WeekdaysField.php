<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

use DateTime;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;

class WeekdaysField extends FormFieldBase
{

    /**
     * @var array
     */
    public array $weekdays;

    /**
     * @param SettingsManagerInterface $settingsManager
     * @param string $name
     * @param string $title
     * @param mixed $weekdays
     */
    public function __construct(SettingsManagerInterface $settingsManager, string $name, string $title, $weekdays = false)
    {
        $weekdaySetting = $settingsManager->getSetting(WeekdayLabels::class);
        if ($weekdaySetting instanceof WeekdayLabels) {
            $this->weekdays = !$weekdays ? $weekdaySetting->getLongWeekdayLabels() : $weekdays;
        } else {
            $this->weekdays = array();
        }

        parent::__construct($name, $title);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        if ( !is_array($value)) {
            $value = array();
            foreach ($this->weekdays as $key => $weekdaytitle) {
                $value[$key]        = array();
                $value[$key]['day'] = "on";
            }
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <div class="tlbm-weekday-picker">
                    <?php
                    foreach ($this->weekdays as $key => $weekday): ?>
                        <?php
                        $datetime_from = new DateTime();
                        $datetime_from->setTimezone(wp_timezone());
                        $datetime_from->setTime(0, 0);

                        $datetime_to = new DateTime();
                        $datetime_to->setTimezone(wp_timezone());
                        $datetime_to->setTime(23, 59);

                        $day_on = isset($value[$key]) && isset($value[$key]['day']);
                        ?>
                        <div class="tlbm-weekday-pick-item <?php
                        echo $day_on ? "tlbm-weekday-pick-item-active" : "" ?>">
                            <label class="tlbm-weekday-checkbox-label"><input type="checkbox" name="<?php
                                echo $this->name ?>[<?php
                                echo $key ?>][day]" <?php
                                checked($day_on); ?>> <?php
                                echo $weekday ?> </label>

                            <?php
                            if ( !isset($value[$key]) || sizeof($value[$key]['from_hour']) == 0): ?>
                                <div class="tlbm-weekday-pick-item-timeblock-container tlbm-all-day">
                                    <?php
                                    echo __("All-day", TLBM_TEXT_DOMAIN); ?>
                                </div>
                            <?php
                            endif; ?>
                            <div class="tlbm-weekday-pick-item-timeblock-container tlbm-all-day tlbm-dummy">
                                <?php
                                echo __("All-day", TLBM_TEXT_DOMAIN); ?>
                            </div>
                            <?php
                            $this->printTimeslot(
                                $key, intval($datetime_from->format('H')), intval($datetime_from->format('i')), intval($datetime_to->format('H')), intval($datetime_to->format('i')), true
                            );
                            ?>
                            <?php
                            foreach ($value[$key]['from_hour'] as $timekey => $from_hour): ?>
                                <?php
                                $from_minute = $value[$key]['from_minute'][$timekey];
                                $to_hour     = $value[$key]['to_hour'][$timekey];
                                $to_minute   = $value[$key]['to_minute'][$timekey];

                                $this->printTimeslot(
                                    $key, $from_hour, $from_minute, $to_hour, $to_minute, false, !$day_on
                                );
                                ?>
                            <?php
                            endforeach; ?>
                            <button <?php
                            disabled(!$day_on) ?> class="button button-small button-primary-outline tlbm-add-timeslot">
                                Add Timeslot
                            </button>
                        </div>
                    <?php
                    endforeach; ?>
                </div>
            </td>
        </tr>
        <?php
    }

    private function printTimeslot($key, $from_hour, $from_minute, $to_hour, $to_minute, $dummy, $disabled = false)
    {
        ?>
        <div class="tlbm-weekday-pick-item-timeblock-container <?php
        echo $dummy ? "tlbm-dummy" : "tlbm-normal-time-block" ?>">
            <button class="button button-small button-link button-link-delete" <?php
            disabled($disabled) ?>>Remove
            </button>
            <div class="tlbm-weekday-pick-item-timeblock">
                <p><strong>From</strong></p>
                <select name="<?php
                echo $this->name ?>[<?php
                echo $key ?>][from_hour][]">
                    <?php
                    for ($i = 0; $i < 24; $i++): ?>
                        <option <?php
                        selected($i == $from_hour); ?> value="<?php
                        echo $i ?>"><?php
                            echo $i ?></option>
                    <?php
                    endfor; ?>
                </select>
                &nbsp;<strong>:</strong>&nbsp;
                <select name="<?php
                echo $this->name ?>[<?php
                echo $key ?>][from_minute][]">
                    <?php
                    for ($i = 0; $i <= 59; $i++): ?>
                        <option <?php
                        selected($i == $from_minute); ?> value="<?php
                        echo $i ?>"><?php
                            echo $i ?></option>
                    <?php
                    endfor; ?>
                </select>
            </div>
            <div class="tlbm-weekday-pick-item-timeblock">
                <p><strong>To</strong></p>
                <select name="<?php
                echo $this->name ?>[<?php
                echo $key ?>][to_hour][]">
                    <?php
                    for ($i = 0; $i < 24; $i++): ?>
                        <option <?php
                        selected($i == $to_hour); ?> value="<?php
                        echo $i ?>"><?php
                            echo $i ?></option>
                    <?php
                    endfor; ?>
                </select>
                &nbsp;<strong>:</strong>&nbsp;
                <select name="<?php
                echo $this->name ?>[<?php
                echo $key ?>][to_minute][]">
                    <?php
                    for ($i = 0; $i <= 59; $i++): ?>
                        <option <?php
                        selected($i == $to_minute); ?> value="<?php
                        echo $i ?>"><?php
                            echo $i ?></option>
                    <?php
                    endfor; ?>
                </select>
            </div>
        </div>
        <?php
    }
}
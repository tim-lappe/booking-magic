<?php


namespace TLBM\Admin\WpForm;

use DateTime;

if ( !defined('ABSPATH')) {
    return;
}

class DateTimeField extends FormFieldBase
{

    public $endless_checkbox = false;

    public function __construct($name, $title, $endless_checkbox = false)
    {
        parent::__construct($name, $title);

        $this->endless_checkbox = $endless_checkbox;
    }

    /**
     * @param $name
     * @param $request_arr
     *
     * @return DateTime|false
     */
    public function getDateTime($name, $request_arr)
    {
        $date_string = $request_arr[$name];
        $endless     = $request_arr[$name . "_isEndless"];
        $hours       = intval($request_arr[$name . "_hours"]);
        $minutes     = intval($request_arr[$name . "_minutes"]);

        $date = DateTime::createFromFormat("Y-m-d", $date_string);
        if ($date) {
            $date->setTimezone(wp_timezone());
            $date->setTime($hours, $minutes);
        }

        if ($endless) {
            return false;
        }

        return $date;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        $datetime = new DateTime();
        $datetime->setTimezone(wp_timezone());

        if ($value < PHP_INT_MAX) {
            $datetime->setTimestamp($value);
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <?php
                if ($this->endless_checkbox): ?>
                    <div class="tlbm-endless-checkbox-container">
                        <label>
                            <input type="checkbox" <?php
                            checked($value == PHP_INT_MAX) ?> name="<?php
                            echo $this->name ?>_isEndless" class="regular-text endless-checkbox"> <?php
                            echo __("endless", TLBM_TEXT_DOMAIN); ?>
                        </label>
                    </div>
                <?php
                endif; ?>
                <div class="tlbm-select-datetime">
                    <div class="tlbm-date">
                        <p><strong><?php
                                echo __("Date", TLBM_TEXT_DOMAIN); ?></strong></p><input id="<?php
                        echo $this->name ?>" class="regular-text" type="date" value="<?php
                        echo $datetime->format("Y-m-d") ?>" name="<?php
                        echo $this->name ?>">
                    </div>
                    <div class="tlbm-time">
                        <p><strong><?php
                                echo __("Time", TLBM_TEXT_DOMAIN); ?></strong></p>
                        <select name="<?php
                        echo $this->name ?>_hours">
                            <?php
                            for ($i = 0; $i < 24; $i++): ?>
                                <option <?php
                                echo intval($datetime->format('G')) == $i ? "selected='selected'" : "" ?> value="<?php
                                echo $i ?>"><?php
                                    echo $i ?></option>
                            <?php
                            endfor; ?>
                        </select>
                        &nbsp;<strong>:</strong>&nbsp;
                        <select name="<?php
                        echo $this->name ?>_minutes">
                            <?php
                            for ($i = 0; $i <= 59; $i++): ?>
                                <option <?php
                                echo intval($datetime->format('i')) == $i ? "selected='selected'" : "" ?> value="<?php
                                echo $i ?>"><?php
                                    echo $i ?></option>
                            <?php
                            endfor; ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <?php
    }
}
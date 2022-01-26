<?php


namespace TLBM\Admin\WpForm;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\CalendarSelection;

class CalendarPickerField extends FormFieldBase
{

    private CalendarManagerInterface $calendarManager;

    /**
     * @param CalendarManagerInterface $calendarManager
     * @param string $name
     * @param string $title
     * @param $value
     * @param bool $show_calendar_groups
     */
    public function __construct(
        CalendarManagerInterface $calendarManager,
        string $name,
        string $title,
        $value = null,
        bool $show_calendar_groups = true
    ) {
        $this->calendarManager = $calendarManager;

        if ( ! $value) {
            $value = new CalendarSelection();
        }

        parent::__construct($name, $title, $value);
    }

    public function displayContent(): void
    {
        $cals = $this->calendarManager->getAllCalendars();
        $calendars = array();
        foreach ($cals as $cal) {
            $calendars[$cal->GetId()] = empty($cal->GetTitle()) ? $cal->GetId() : $cal->GetTitle();
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <div
                        data-json="<?php
                        echo urlencode(json_encode($this->value)) ?>"
                        data-calendars="<?php
                        echo urlencode(json_encode($calendars)) ?>"
                        data-name="<?php
                        echo $this->name ?>"
                        class="tlbm-calendar-picker"></div>
            </td>
        </tr>
        <?php
    }

    /**
     * @param $name
     * @param $vars
     *
     * @return CalendarSelection
     */
    public function readFromVars($name, $vars): CalendarSelection
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json        = json_decode($decoded_var);
            if ($json) {
                if (isset($json->calendar_ids) && isset($json->selection_mode)) {
                    $selection = new CalendarSelection();
                    if ($selection->SetSelectionMode($json->selection_mode)) {
                        foreach ($json->calendar_ids as $calendar_id) {
                            $calendar = $this->calendarManager->getCalendar(intval($calendar_id));
                            if ($calendar) {
                                $selection->AddCalendar($calendar);
                            }
                        }
                    }

                    return $selection;
                }
            }
        }

        return new CalendarSelection();
    }
}
<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Entity\CalendarSelection;

class CalendarPickerField extends FormFieldBase implements FormFieldReadVarsInterface
{

    private CalendarRepositoryInterface $calendarManager;

    /**
     * @param CalendarRepositoryInterface $calendarManager
     * @param string $name
     * @param string $title
     */
    public function __construct(
        CalendarRepositoryInterface $calendarManager,
        string $name,
        string $title
    ) {
        $this->calendarManager = $calendarManager;

        parent::__construct($name, $title);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        if ( !$value) {
            $value = new CalendarSelection();
        }

        $cals      = $this->calendarManager->getAllCalendars();
        $calendars = array();
        foreach ($cals as $cal) {
            $calendars[$cal->getId()] = empty($cal->getTitle()) ? $cal->getId() : $cal->getTitle();
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <div
                        data-json="<?php
                        echo urlencode(json_encode($value)) ?>"
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
     * @param string $name
     * @param mixed $vars
     *
     * @return CalendarSelection
     */
    public function readFromVars(string $name, $vars): CalendarSelection
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json        = json_decode($decoded_var);
            if ($json) {
                if (isset($json->calendar_ids) && isset($json->selection_mode)) {
                    $selection = new CalendarSelection();
                    if ($selection->setSelectionMode($json->selection_mode)) {
                        foreach ($json->calendar_ids as $calendar_id) {
                            $calendar = $this->calendarManager->getCalendar(intval($calendar_id));
                            if ($calendar) {
                                $selection->addCalendar($calendar);
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
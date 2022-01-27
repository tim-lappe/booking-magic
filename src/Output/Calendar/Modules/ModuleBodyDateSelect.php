<?php


namespace TLBM\Output\Calendar\Modules;


use DateInterval;
use DatePeriod;
use DateTime;
use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;
use TLBM\Booking\BookingCapacities;
use TLBM\Model\CalendarGroup;
use TLBM\Rules\Capacities;

class ModuleBodyDateSelect implements ICalendarPrintModule
{

    /**
     * @inheritDoc
     */
    public function GetOutput($data): string
    {
        $group = CalendarGroup::FromCalendarOrGroupId((int) $data['id']);
        $date  = new DateTime();
        $date->setTimestamp($data['focused_tstamp']);

        $html = "<table class='tlbm-calendar-table'>";
        $html .= "<thead>";

        $html .= "<tr class='tlbm-head-row'>";

        $weekdays = WeekdayLabels::getWeekdayLabels($data['weekday_form']);
        foreach ($weekdays as $key => $weekday) {
            $html .= "<th class='tlbm-weekday tlbm-weekday-" . $key . "'>";
            $html .= $weekday;
            $html .= "</th>";
        }

        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";

        $first_month_day = DateTime::createFromFormat("Y-m-d H:i:s", $date->format("Y-m-1 00:00:00"));
        $last_month_day  = DateTime::createFromFormat("Y-m-d H:i:s", $date->format("Y-m-t 00:00:01"));

        $interval = new DateInterval('P1D');
        $period   = new DatePeriod($first_month_day, $interval, $last_month_day);

        $current_weekday_column = 0;
        $c                      = 0;


        $html .= "<tr>";

        /**
         * @var DateTime $dt
         */
        foreach ($period as $dt) {
            $weekday = intval($dt->format("N"));

            if ($c == 0) {
                for ($i = 1; $i < $weekday; $i++) {
                    $html                   .= $this->GetCellOutput($data, null, null);
                    $current_weekday_column = $i;
                }
            }

            if ($current_weekday_column % sizeof($weekdays) == 0 && $current_weekday_column > 0) {
                $html                   .= "</tr>";
                $html                   .= "<tr>";
                $current_weekday_column = 0;
            }

            if (in_array($weekday - 1, array_keys(array_values($weekdays)))) {
                $html .= $this->GetCellOutput($data, $group, $dt);
            }

            $current_weekday_column++;
            $c++;
        }

        $html .= "</tr>";
        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    /**
     * @param $data
     * @param CalendarGroup|null $group
     * @param DateTime|null $date
     *
     * @return string
     */
    public function GetCellOutput($data, ?CalendarGroup $group, ?DateTime $date): string
    {
        $html    = "";
        $classes = array();

        if ( !$date) {
            $html .= "<td class='tlbm-cell tlbm-cell-empty'></td>";
        } else {
            $today = new DateTime();
            if ($date->format("d.m.Y") == $today->format("d.m.Y")) {
                $classes[] = "tlbm-cell-today";
            }

            $all_capacities = 0;
            if ($group != null) {
                $all_capacities = Capacities::GetDayCapacityForGroup($group, $date);
                $capacity       = BookingCapacities::GetFreeDaySeatsForGroup($group, $date);
            } else {
                $capacity = 0;
            }

            if ($capacity <= 0) {
                $classes[] = "tlbm-cell-no-capacities";
                $classes[] = "tlbm-cell-not-bookable";
            } else {
                $classes[] = "tlbm-cell-bookable";
                $classes[] = "tlbm-cell-selectable";
            }

            $html .= "<td date='" . $date->getTimestamp() . "' class='tlbm-cell tlbm-cell-not-empty " . implode(
                    " ", $classes
                ) . "'><span class='tlbm-datenumber-span'>" . intval(
                         $date->format("d")
                     ) . "</span>&nbsp;" . $capacity . "/" . $all_capacities . "</td>";
        }

        return $html;
    }
}
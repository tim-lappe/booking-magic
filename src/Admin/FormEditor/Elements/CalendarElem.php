<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;

class CalendarElem extends FormInputElem
{

    public function __construct(
        CalendarManagerInterface $calendarManager,
        CalendarGroupManagerInterface $calendarGroupManager
    ) {
        parent::__construct("calendar", __("Calendar", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Calendar", TLBM_TEXT_DOMAIN);
        $this->description   = __(
            "Allows the user to choose from a calendar or a group of calendars",
            TLBM_TEXT_DOMAIN
        );

        $calendars   = $calendarManager->getAllCalendars();
        $calendar_kv = array();
        foreach ($calendars as $calendar) {
            $calendar_kv[$calendar->GetId()] = $calendar->GetTitle();
        }

        $groups_kv       = array();
        $calendar_groups = $calendarGroupManager->GetAllGroups();
        foreach ($calendar_groups as $group) {
            $groups_kv[$group->GetId()] = $group->GetTitle();
        }

        $calendar_select = array(
            __("Groups", TLBM_TEXT_DOMAIN)          => $groups_kv,
            __("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv,
        );

        $default_calendar = sizeof($calendar_kv) > 0 ? array_keys($calendar_kv)[0] : "";

        $setting_selected_calendar = new Select(
            "selected_calendar",
            __("Calendar", TLBM_TEXT_DOMAIN),
            $calendar_select,
            $default_calendar,
            false,
            false,
            __("Calendar Settings", TLBM_TEXT_DOMAIN)
        );
        $setting_weekdays_form     = new Select(
            "weekdays_form",
            __("Weekday Labels", TLBM_TEXT_DOMAIN),
            array(
                "long"  => __("Long", TLBM_TEXT_DOMAIN),
                "short" => __("Short", TLBM_TEXT_DOMAIN)
            ), "short",
            false,
            false,
            __("Calendar Settings", TLBM_TEXT_DOMAIN)
        );

        $this->AddSettings($setting_selected_calendar, $setting_weekdays_form);

        $this->has_user_input = true;
    }

    public function Validate($form_data, $input_vars): bool
    {
        if (isset($form_data['name'])) {
            $name = $form_data['name'];
            if (isset($input_vars[$name])) {
                return ! empty($input_vars[$name]);
            }
        }

        return false;
    }

    /**
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
    public function GetFrontendOutput($form_node, callable $insert_child = null): string
    {
        if ($form_node->formData->selected_calendar) {
            return CalendarOutput::GetCalendarContainerShell(
                $form_node->formData->selected_calendar,
                0,
                "month",
                new MonthViewSetting(),
                $form_node->formData->name
            );
        } else {
            return "<div class='tlbm-no-calendar-alert'>" . __(
                    "No calendar or calendargroup selected",
                    TLBM_TEXT_DOMAIN
                ) . "</div>";
        }
    }
}
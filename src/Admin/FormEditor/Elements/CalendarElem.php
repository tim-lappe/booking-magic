<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Utilities\ExtendedDateTime;

class CalendarElem extends FormInputElem
{

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @param CalendarRepositoryInterface $calendarManager
     * @param CalendarGroupManagerInterface $calendarGroupManager
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(
        CalendarRepositoryInterface $calendarManager,
        CalendarGroupManagerInterface $calendarGroupManager,
        SettingsManagerInterface $settingsManager
    ) {
        parent::__construct("calendar", __("Calendar", TLBM_TEXT_DOMAIN));

        $this->settingsManager = $settingsManager;

        $this->menu_category = __("Calendar", TLBM_TEXT_DOMAIN);
        $this->description   = __(
            "Allows the user to choose from a calendar or a group of calendars", TLBM_TEXT_DOMAIN
        );

        $calendars   = $calendarManager->getAllCalendars();
        $calendar_kv = [];
        foreach ($calendars as $calendar) {
            $calendar_kv[$calendar->getId()] = $calendar->getTitle();
        }

        $groups_kv       = [];
        $calendar_groups = $calendarGroupManager->GetAllGroups();
        foreach ($calendar_groups as $group) {
            $groups_kv[$group->getId()] = $group->getTitle();
        }

        $calendar_select = [
            __("Groups", TLBM_TEXT_DOMAIN)          => $groups_kv,
            __("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv,
        ];

        $default_calendar = sizeof($calendar_kv) > 0 ? array_keys($calendar_kv)[0] : "";

        $selectedCalendar = new Select(
            "selected_calendar", __("Calendar", TLBM_TEXT_DOMAIN), $calendar_select, $default_calendar, false, false, __("Calendar Settings", TLBM_TEXT_DOMAIN)
        );

        $weekdaysForm = new Select(
            "weekdays_form", __("Weekday Labels", TLBM_TEXT_DOMAIN), [
            "long"  => __("Long", TLBM_TEXT_DOMAIN),
            "short" => __("Short", TLBM_TEXT_DOMAIN)
        ],  "short", false, false, __("Calendar Settings", TLBM_TEXT_DOMAIN)
        );

        $this->addSettings($selectedCalendar, $weekdaysForm);
        $this->has_user_input = true;
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $calendar = $linkedFormData->getLinkedSettings()->getValue("selected_calendar");
        $name = $linkedFormData->getLinkedSettings()->getValue("name");

        if (!empty($calendar)) {
            return CalendarOutput::GetCalendarContainerShell($calendar, new ExtendedDateTime(), "month", new MonthViewSetting($this->settingsManager), $name);
        } else {
            return "<div class='tlbm-no-calendar-alert'>" . __(
                    "No calendar or calendargroup selected", TLBM_TEXT_DOMAIN
                ) . "</div>";
        }
    }
}

<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarDisplay;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Repository\Query\CalendarGroupQuery;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Utilities\ExtendedDateTime;

class CalendarElem extends FormInputElem
{

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(SettingsManagerInterface $settingsManager) {
        parent::__construct("calendar", __("Calendar", TLBM_TEXT_DOMAIN));

        $this->settingsManager = $settingsManager;

        $this->menu_category = __("Calendar", TLBM_TEXT_DOMAIN);
        $this->description   = __(
            "Allows the user to choose from a calendar or a group of calendars", TLBM_TEXT_DOMAIN
        );

        $calendarsQuery = MainFactory::create(CalendarQuery::class);
        $calendarGroupQuery = MainFactory::create(CalendarGroupQuery::class);

        $calendars   = iterator_to_array($calendarsQuery->getResult());
        $calendar_kv = [];
        foreach ($calendars as $calendar) {
            $calendar_kv["calendar_" . $calendar->getId()] = $calendar->getTitle();
        }

        $groups_kv       = [];
        $calendar_groups = iterator_to_array($calendarGroupQuery->getResult());
        foreach ($calendar_groups as $group) {
            $groups_kv["group_" . $group->getId()] = $group->getTitle();
        }

        $calendar_select = [
            __("Groups", TLBM_TEXT_DOMAIN)          => $groups_kv,
            __("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv,
        ];

        $default_calendar = sizeof($calendar_kv) > 0 ? array_keys($calendar_kv)[0] : "";
        $selectedCalendar = new Select(
            "sourceId", __("Calendar", TLBM_TEXT_DOMAIN), $calendar_select, $default_calendar, false, false, __("Calendar Settings", TLBM_TEXT_DOMAIN)
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
        $sourceId = $linkedFormData->getLinkedSettings()->getValue("sourceId");
        $name = $linkedFormData->getLinkedSettings()->getValue("name");

        if (!empty($sourceId)) {
            $calendarDisplay = MainFactory::create(CalendarDisplay::class);

            if(strpos($sourceId, "group_") !== false) {
                $id = intval(str_replace("group_", "", $sourceId));
                $calendarDisplay->setGroupIds([ $id ]);
            }

            if(strpos($sourceId, "calendar_") !== false) {
                $id = intval(str_replace("calendar_", "", $sourceId));
                $calendarDisplay->setCalendarIds([ $id ]);
            }

            $calendarDisplay->setView("month");
            $calendarDisplay->setViewSettings(MainFactory::create(MonthViewSetting::class));
            $calendarDisplay->setInputName($name);

            return $calendarDisplay->getDisplayContent();
        } else {
            return "<div class='tlbm-no-calendar-alert'>" . __(
                    "No calendar or calendargroup selected", TLBM_TEXT_DOMAIN
                ) . "</div>";
        }
    }
}

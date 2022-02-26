<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\Contracts\AdminElementInterface;
use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarDisplay;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Repository\Query\CalendarGroupQuery;
use TLBM\Repository\Query\CalendarQuery;

class CalendarElem extends FormInputElem implements AdminElementInterface
{
    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * @param LocalizationInterface $localization
     */
    public function __construct(LocalizationInterface $localization) {
        parent::__construct("calendar", $localization->__("Calendar", TLBM_TEXT_DOMAIN));

        $this->localization = $localization;

        $this->menu_category = $this->localization->__("Calendar", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->__(
            "Allows the user to choose from a calendar or a group of calendars", TLBM_TEXT_DOMAIN
        );

        $calendarsQuery = MainFactory::create(CalendarQuery::class);
        $calendarGroupQuery = MainFactory::create(CalendarGroupQuery::class);

        $calendar_kv = [];
        foreach ($calendarsQuery->getResult() as $calendar) {
            $calendar_kv["calendar_" . $calendar->getId()] = $calendar->getTitle();
        }

        $groups_kv       = [];
        $calendar_groups = iterator_to_array($calendarGroupQuery->getResult());
        foreach ($calendar_groups as $group) {
            $groups_kv["group_" . $group->getId()] = $group->getTitle();
        }

        $calendar_select = [
            $this->localization->__("Groups", TLBM_TEXT_DOMAIN)          => $groups_kv,
            $this->localization->__("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv,
        ];

        $default_calendar = sizeof($calendar_kv) > 0 ? array_keys($calendar_kv)[0] : "";
        $selectedCalendar = new Select(
            "sourceId", $this->localization->__("Calendar", TLBM_TEXT_DOMAIN), $calendar_select, $default_calendar, false, false, $this->localization->__("Calendar Settings", TLBM_TEXT_DOMAIN)
        );

        $weekdaysForm = new Select(
            "weekdays_form", $this->localization->__("Weekday Labels", TLBM_TEXT_DOMAIN), [
            "long"  => $this->localization->__("Long", TLBM_TEXT_DOMAIN),
            "short" => $this->localization->__("Short", TLBM_TEXT_DOMAIN)
        ],  "short", false, false, $this->localization->__("Calendar Settings", TLBM_TEXT_DOMAIN)
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
            return "<div class='tlbm-no-calendar-alert'>" . $this->localization->__(
                    "No calendar or calendargroup selected", TLBM_TEXT_DOMAIN
                ) . "</div>";
        }
    }

    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getAdminContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $sourceId = $linkedFormData->getLinkedSettings()->getValue("sourceId");
        $name = $linkedFormData->getLinkedSettings()->getValue("name");
        $title = $linkedFormData->getLinkedSettings()->getValue("title");
        $calendarBooking = $linkedFormData->getInputVarByName($name);

        if($calendarBooking instanceof CalendarBooking) {
            $html = "<div class='tlbm-fe-form-control'>";
            $html .= "<label>";
            $html .= "<span class='tlbm-input-title'>" . $title . "</span>";

            $calendarsQuery = MainFactory::create(CalendarQuery::class);

            $html .= "<div class='tlbm-admin-calendar-field'>";
            $html .= "<div>";
            $html .= "<div>";
            $html .= "<small>" . $this->localization->__("Calendar", TLBM_TEXT_DOMAIN) . "</small><br>";
            $html .= "<select name='" . $name . "[calendar_id]'>";

            /**
             * @var Calendar $calendar
             */
            foreach ($calendarsQuery->getResult() as $calendar) {
                $html .= "<option " . selected($calendar->getId(), $calendarBooking->getCalendar()->getId(), false) . " value='" . $calendar->getId() . "'>" . $calendar->getTitle() . "</option>";
            }

            $html .= "</select>";
            $html .= "</div>";
            $html .= "<div style='margin-top: 1em'>";
            $html .= "<small>" . $this->localization->__("Time", TLBM_TEXT_DOMAIN) . "</small><br>";
            $html .= "<div class='tlbm-date-range-field' data-to='" . urlencode(json_encode($calendarBooking->getToDateTime())) . "' data-from='" . urlencode(json_encode($calendarBooking->getFromDateTime())) . "'>";

            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</label>";
            $html .= "</div>";

            return $html;
        } else {
            return "";
        }
    }
}

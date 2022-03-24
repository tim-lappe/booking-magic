<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use InvalidArgumentException;
use TLBM\Admin\FormEditor\Contracts\AdminElementInterface;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Calendar\CalendarHelper;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarDisplay;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Utilities\ExtendedDateTime;

class CalendarElem extends FormInputElem implements AdminElementInterface
{
    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    /**
     * @param LocalizationInterface $localization
     */
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("calendar", $localization->getText("Appointment selection", TLBM_TEXT_DOMAIN));

        $this->localization = $localization;

        $this->menu_category = $this->localization->getText("Calendar", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->getText(
            "Allows the user to choose from a calendar or a group of calendars", TLBM_TEXT_DOMAIN
        );

        $calendarHelper = MainFactory::create(CalendarHelper::class);
        $keyValues      = $calendarHelper->getGroupAndCalendarKeyValues();

        $selectedCalendar = new Select(
            "sourceId", $this->localization->getText("Calendar", TLBM_TEXT_DOMAIN), $keyValues, "", false, false, $this->localization->getText("Calendar Settings", TLBM_TEXT_DOMAIN)
        );

        $this->addSettings($selectedCalendar);
        $this->has_user_input = true;
    }

    public function validate(LinkedFormData $linkedFormData): bool
    {
        if(parent::validate($linkedFormData)) {
            $linkedSettings = $linkedFormData->getLinkedSettings();
            $name = $linkedSettings->getValue("name");
            $value = $linkedFormData->getInputVarByName($name);

            try {
                $dt = new ExtendedDateTime();
                $dt->setFromObject(json_decode(urldecode($value), true));
                return true;
            } catch (InvalidArgumentException $exception) {
                return false;
            }
        }

        return false;
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
            return "<div class='tlbm-no-calendar-alert'>" . $this->localization->getText(
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
            $html .= "<small>" . $this->localization->getText("Calendar", TLBM_TEXT_DOMAIN) . "</small><br>";
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
            $html .= "<small>" . $this->localization->getText("Time", TLBM_TEXT_DOMAIN) . "</small><br>";
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

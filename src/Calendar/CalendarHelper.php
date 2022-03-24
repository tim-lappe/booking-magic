<?php

namespace TLBM\Calendar;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarGroupQuery;
use TLBM\Repository\Query\CalendarQuery;

class CalendarHelper
{
    private LocalizationInterface $localization;

    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
    }

    public function getGroupAndCalendarKeyValues()
    {
        $calendarsQuery     = MainFactory::create(CalendarQuery::class);
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

        return [$this->localization->getText("Groups", TLBM_TEXT_DOMAIN) => $groups_kv,
            $this->localization->getText("Single Calendar", TLBM_TEXT_DOMAIN) => $calendar_kv,
        ];
    }
}
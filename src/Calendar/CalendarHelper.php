<?php

namespace TLBM\Calendar;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Repository\Query\TagQuery;

class CalendarHelper
{
    private LocalizationInterface $localization;

    /**
     * @param LocalizationInterface $localization
     */
    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
    }

    /**
     * @return array
     */
    public function getTagKeyValues(): array
    {
        $tagQuery = MainFactory::create(TagQuery::class);

        $tagKv    = [];
        foreach ($tagQuery->getResult() as $tag) {
            $tagKv[$tag->getId()] = $tag->getTitle();
        }

        return $tagKv;
    }

    /**
     * @return array[]
     */
    public function getCalendarKeyValues(): array
    {
        $calendarsQuery     = MainFactory::create(CalendarQuery::class);

        $calendar_kv = [];
        foreach ($calendarsQuery->getResult() as $calendar) {
            $calendar_kv[$calendar->getId()] = $calendar->getTitle();
        }

        return $calendar_kv;
    }

    /**
     * @return array[]
     */
    public function getGroupAndCalendarKeyValues(): array
    {
        $calendarsQuery     = MainFactory::create(CalendarQuery::class);
        $tagQuery           = MainFactory::create(TagQuery::class);

        $calendarKv = [];
        foreach ($calendarsQuery->getResult() as $calendar) {
            $calendarKv["calendar_" . $calendar->getId()] = $calendar->getTitle();
        }

        $tagKv = [];
        foreach ($tagQuery->getResult() as $group) {
            $tagKv["tag_" . $group->getId()] = $group->getTitle();
        }

        return [$this->localization->getText("Groups", TLBM_TEXT_DOMAIN) => $tagKv,
            $this->localization->getText("Single Calendar", TLBM_TEXT_DOMAIN) => $calendarKv,
        ];
    }
}
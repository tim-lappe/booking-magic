<?php

namespace TLBM\Calendar\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;

interface CalendarSelectionHandlerInterface
{
    /**
     * @param CalendarSelection $calendar_selection
     * @param int $calendar_id
     *
     * @return bool
     */
    public function containsCalendar(CalendarSelection $calendar_selection, int $calendar_id): bool;

    /**
     * @param CalendarSelection $calendarSelection
     *
     * @return array|Calendar[]
     */
    public function getSelectedCalendarList(CalendarSelection $calendarSelection): array;
}
<?php

namespace TLBM\Calendar\Contracts;

use Exception;
use TLBM\Entity\Calendar;

interface CalendarRepositoryInterface
{

    /**
     * @param Calendar $calendar
     *
     * @throws Exception
     */
    public function saveCalendar(Calendar $calendar): int;

    /**
     * Returns the BookingCalender from the given Post-Id
     *
     * @param mixed $calendar_id The Post-Id of the Calendar
     *
     * @return Calendar|null
     */
    public function getCalendar($calendar_id): ?Calendar;

    /**
     * Return a List of all active Calendars
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Calendar[]
     */
    public function getAllCalendars(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array;

    /**
     * @param array $options
     *
     * @return int
     */
    public function getAllCalendarsCount(array $options = array()): int;
}
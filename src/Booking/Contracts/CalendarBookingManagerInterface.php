<?php

namespace TLBM\Booking\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Utilities\ExtendedDateTime;

interface CalendarBookingManagerInterface
{
    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime $extendedDateTime
     *
     * @return int
     */
    public function getFreeCapacitiesForCalendar(Calendar $calendar, ExtendedDateTime $extendedDateTime): int;


    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime|null $dateTime
     *
     * @return CalendarBooking[]
     */
    public function getCalendarBookingsForCalendar(Calendar $calendar, ?ExtendedDateTime $dateTime = null): array;
}
<?php

namespace TLBM\Booking\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Utilities\ExtendedDateTime;
use Traversable;

interface CalendarBookingManagerInterface
{
    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime $extendedDateTime
     *
     * @return int
     */
    public function getRemainingSlots(Calendar $calendar, ExtendedDateTime $extendedDateTime): int;

    /**
     * @param CalendarBooking $calendarBooking
     *
     * @return mixed
     */
    public function isValidCalendarBooking(CalendarBooking $calendarBooking);

    /**
     * @param array $calendarBookings
     *
     * @return array returns failing calendarBookings
     */
    public function areValidCalendarBookings(array $calendarBookings): array;

    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime|null $dateTime
     *
     * @return Traversable
     */
    public function getCalendarBookings(Calendar $calendar, ?ExtendedDateTime $dateTime = null): Traversable;
}
<?php

namespace TLBM\Booking\Contracts;

use Iterator;
use TLBM\Entity\CalendarBooking;
use TLBM\Utilities\ExtendedDateTime;

interface CalendarBookingManagerInterface
{

    /**
     * @param array $calendarIds
     * @param ExtendedDateTime $extendedDateTime
     *
     * @return int
     */
    public function getRemainingSlots(array $calendarIds, ExtendedDateTime $extendedDateTime): int;

    /**
     * @param array|null $calendarIds
     * @param ExtendedDateTime|null $dateTime
     *
     * @return int
     */
    public function getBookedSlots(?array $calendarIds = null, ?ExtendedDateTime $dateTime = null): int;

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
     * @param ?array $calendarIds
     * @param ExtendedDateTime|null $dateTime
     *
     * @return Iterator
     */
    public function getCalendarBookings(?array $calendarIds = null, ?ExtendedDateTime $dateTime = null): Iterator;
}
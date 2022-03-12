<?php

namespace TLBM\Booking;

use TLBM\Entity\Booking;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\BookingsQuery;
use TLBM\Utilities\ExtendedDateTime;

class Statistics
{
    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $repository;

    /**
     * @param EntityRepositoryInterface $repository
     */
    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ExtendedDateTime|null $from
     * @param ExtendedDateTime|null $to
     *
     * @return array
     */
    public function getBookingsCountMonthly(?ExtendedDateTime $from = null, ?ExtendedDateTime $to = null): array
    {
        $bookingsQuery = MainFactory::get(BookingsQuery::class);
        if($from != null) {
            $bookingsQuery->setFilterFromDateTime($from);
        }
        if($to != null) {
            $bookingsQuery->setFilterToDateTime($to);
        }

        $bookingsQuery->setFilterStates(["confirmed", "new", "processing", "appeared", "not_appeared"]);

        $bookings = $bookingsQuery->getResult();
        $bookingsMonthly = [];

        /**
         * @var Booking $booking
         */
        foreach ($bookings as $booking) {
            $dt = new ExtendedDateTime($booking->getTimestampCreated());
            $term = $dt->getInternalDateTime()->format("F Y");
            if(isset($bookingsMonthly[$term])) {
                $bookingsMonthly[$term]++;
            } else {
                $bookingsMonthly[$term] = 1;
            }
        }

        return $bookingsMonthly;
    }

    /**
     * @param ExtendedDateTime|null $from
     * @param ExtendedDateTime|null $to
     *
     * @return array
     */
    public function getMostBookedCalendars(?ExtendedDateTime $from = null, ?ExtendedDateTime $to = null): array
    {
        $bookingsQuery = MainFactory::get(BookingsQuery::class);
        if($from != null) {
            $bookingsQuery->setFilterFromDateTime($from);
        }
        if($to != null) {
            $bookingsQuery->setFilterToDateTime($to);
        }

        $bookings = $bookingsQuery->getResult();
        $bookedCalendarsCount = [];

        /**
         * @var Booking $booking
         */
        foreach ($bookings as $booking) {
            foreach($booking->getCalendarBookings() as $calendarBooking) {
                if($calendarBooking->getCalendar() != null) {
                    $calId = $calendarBooking->getCalendar()->getId();
                    if (isset($bookedCalendarsCount[$calId])) {
                        $bookedCalendarsCount[$calId]++;
                    } else {
                        $bookedCalendarsCount[$calId] = 1;
                    }
                }
            }
        }

        arsort($bookedCalendarsCount);

        return array_slice($bookedCalendarsCount, 0, 5, true);
    }
}
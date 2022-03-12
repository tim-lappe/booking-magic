<?php

namespace TLBM\Booking;

use Exception;
use Iterator;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Entity\CalendarBooking;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarBookingsQuery;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Utilities\ExtendedDateTime;

class CalendarBookingManager implements CalendarBookingManagerInterface
{
    /**
     * @var RulesCapacityManagerInterface
     */
    private RulesCapacityManagerInterface $capacityManager;

    public function __construct(RulesCapacityManagerInterface $capacityManager)
    {
        $this->capacityManager = $capacityManager;
    }

    /**
     * @param array $calendarIds
     * @param ExtendedDateTime $extendedDateTime
     * @param CalendarBooking|null $exclude
     * @param bool|null $fullDay
     *
     * @return int
     */
    public function getRemainingSlots(array $calendarIds, ExtendedDateTime $extendedDateTime, ?CalendarBooking $exclude = null, ?bool $fullDay = null): int
    {
        $capacity    = $this->capacityManager->getOriginalCapacity($calendarIds, $extendedDateTime);
        $bookedSlots = $this->getBookedSlots($calendarIds, $extendedDateTime, $fullDay);

        if ($exclude != null) {
            $bookedSlots -= $exclude->getSlots();
        }

        return max(0, ($capacity - $bookedSlots));
    }

    /**
     * @param array|null $calendarIds
     * @param ExtendedDateTime|null $dateTime
     *
     * @return int
     */
    public function getBookedSlots(?array $calendarIds = null, ?ExtendedDateTime $dateTime = null): int
    {
        $query = MainFactory::create(CalendarBookingsQuery::class);
        $query->setReturnSlotsScalar(true);

        if ($calendarIds != null) {
            $query->setCalendarIds($calendarIds);
        }

        if ($dateTime != null) {
            $query->setDateTime($dateTime);
        }

        $query->setExcludeBookingStates(["canceled"]);

        try {
            return $query->getQuery()->getSingleScalarResult() ?? 0;
        } catch (Exception $e) {
            if (WP_DEBUG) {
                echo $e->getMessage();
            }
        }

        return 0;
    }

    /**
     * @param ?int[] $calendarIds
     * @param ExtendedDateTime|null $dateTime
     *
     * @return Iterator
     */
    public function getCalendarBookings(?array $calendarIds = null, ?ExtendedDateTime $dateTime = null): Iterator
    {
        $query = MainFactory::create(CalendarBookingsQuery::class);
        if($calendarIds != null) {
            $query->setCalendarIds($calendarIds);
        }

        if($dateTime != null) {
            $query->setDateTime($dateTime);
        }

        foreach ($query->getResult() as $item) {
            $result = $item->getQueryResult();
            foreach($result as $calendarBooking) {
                if ($calendarBooking instanceof CalendarBooking) {
                    /** @var CalendarBooking $calendarBooking */
                    yield $calendarBooking;
                }
            }
        }
    }

    /**
     * @param array $calendarBookings
     *
     * @return array returns failing calendarBookings
     */
    public function areValidCalendarBookings(array $calendarBookings): array
    {
        $failing = [];
        foreach ($calendarBookings as $calendarBooking) {
            if(!$this->isValidCalendarBooking($calendarBooking)) {
                $failing[] = $calendarBooking;
            }
        }

        return $failing;
    }

    /**
     * @param CalendarBooking $calendarBooking
     *
     * @return bool
     */
    public function isValidCalendarBooking(CalendarBooking $calendarBooking): bool
    {
        //TODO: Wird nur auf "From" DateTime geprüft, muss also noch angepasst werden
        $remaining = $this->getRemainingSlots(array($calendarBooking->getCalendar()->getId()), $calendarBooking->getFromDateTime(), $calendarBooking);
        return $remaining >= $calendarBooking->getSlots();
    }
}
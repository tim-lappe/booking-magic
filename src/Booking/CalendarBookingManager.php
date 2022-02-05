<?php

namespace TLBM\Booking;

use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\MainFactory;
use TLBM\Repository\Query\CalendarBookingsQuery;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Utilities\ExtendedDateTime;
use Traversable;

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
     * @param Calendar $calendar
     * @param ExtendedDateTime $extendedDateTime
     *
     * @return int
     */
    public function getRemainingSlots(Calendar $calendar, ExtendedDateTime $extendedDateTime): int
    {
        $capacity = $this->capacityManager->getOriginalCapacity($calendar, $extendedDateTime);
        $calendarBookings = $this->getCalendarBookings($calendar, $extendedDateTime);
        $bookedSlots = 0;

        /**
         * @var CalendarBooking $calendarBooking
         */
        foreach ($calendarBookings as $calendarBooking) {
            $bookedSlots += $calendarBooking->getSlots();
        }

        return max(0,($capacity - $bookedSlots));
    }

    /**
     * @param ?Calendar $calendar
     * @param ExtendedDateTime|null $dateTime
     *
     * @return Traversable
     */
    public function getCalendarBookings(?Calendar $calendar = null, ?ExtendedDateTime $dateTime = null): Traversable
    {
        $query = MainFactory::create(CalendarBookingsQuery::class);

        if($calendar != null) {
            $query->setCalendar($calendar);
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
        $remaining = $this->getRemainingSlots($calendarBooking->getCalendar(), $calendarBooking->getFromDateTime());
        return $remaining >= $calendarBooking->getSlots();
    }
}
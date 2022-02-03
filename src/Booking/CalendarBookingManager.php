<?php

namespace TLBM\Booking;

use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Rules\Contracts\RulesCapacityManagerInterface;
use TLBM\Utilities\ExtendedDateTime;

class CalendarBookingManager implements CalendarBookingManagerInterface
{
    /**
     * @var RulesCapacityManagerInterface
     */
    private RulesCapacityManagerInterface $capacityManager;

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(RulesCapacityManagerInterface $capacityManager, ORMInterface $repository)
    {
        $this->repository = $repository;
        $this->capacityManager = $capacityManager;
    }

    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime $extendedDateTime
     *
     * @return int
     */
    public function getFreeCapacitiesForCalendar(Calendar $calendar, ExtendedDateTime $extendedDateTime): int
    {
        $capacity = $this->capacityManager->getCapacitiesForCalendar($calendar, $extendedDateTime);
        $bookedSlots = 0;
        $calendarBookings = $this->getCalendarBookingsForCalendar($calendar, $extendedDateTime);
        foreach ($calendarBookings as $calendarBooking) {
            $bookedSlots += $calendarBooking->getSlots();
        }

        return max(0,$capacity - $bookedSlots);
    }

    /**
     * @param Calendar $calendar
     * @param ExtendedDateTime|null $dateTime
     *
     * @return CalendarBooking[]
     */
    public function getCalendarBookingsForCalendar(Calendar $calendar, ?ExtendedDateTime $dateTime = null): array
    {
        $mgr = $this->repository->getEntityManager();
        $queryBuilder  = $mgr->createQueryBuilder();
        $expr = $queryBuilder->expr();
        $queryBuilder
            ->select("cb")
            ->from("\TLBM\Entity\CalendarBooking", "cb")
            ->where($expr->eq("cb.calendar", $calendar->getId()));

        if($dateTime != null) {
            $timestampBeginOfDay = $dateTime->getTimestampBeginOfDay();
            $timestampEndOfDay = $dateTime->getTimestampEndOfDay();

            $timestampFrom = $dateTime->isFullDay() ? $timestampEndOfDay : $dateTime->getTimestamp();
            $timestampTo = $dateTime->isFullDay() ? $timestampBeginOfDay : $dateTime->getTimestamp();

            $expr = $queryBuilder->expr();
            $periodsWhere = $expr->orX();
            $periodsNotEmpty = $expr->andX();

            $periodsFromOr = $expr->orX();
            $periodsFromOr->add("cb.fromTimestamp <= '" . $timestampFrom . "'");

            $periodsFromNoTimeset = $expr->andX();
            $periodsFromNoTimeset->add("cb.fromTimestamp <= '" . $timestampEndOfDay . "'");
            $periodsFromNoTimeset->add("cb.fromFullDay = true");
            $periodsFromOr->add($periodsFromNoTimeset);

            $periodsToOr = $expr->orX();
            $periodsToOr->add($expr->isNull("cb.toTimestamp"));
            $periodsToOr->add("cb.toTimestamp >= '" . $timestampTo . "'");

            $periodsToNoTimeset = $expr->andX();
            $periodsToNoTimeset->add("cb.toTimestamp >= '" . $timestampBeginOfDay . "'");
            $periodsToNoTimeset->add("cb.toFullDay = true");
            $periodsToOr->add($periodsToNoTimeset);

            $periodsNotEmpty->add($periodsFromOr);
            $periodsNotEmpty->add($periodsToOr);
            $periodsWhere->add($periodsNotEmpty);
        }

        $query  = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
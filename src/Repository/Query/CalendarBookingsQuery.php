<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Utilities\ExtendedDateTime;

class CalendarBookingsQuery extends TimeBasedQuery
{

    /**
     * @var ?Calendar
     */
    private ?Calendar $calendar = null;


    /**
     * @param ORMInterface $repository
     */
    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @return ?Calendar
     */
    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    /**
     * @param ?Calendar $calendar
     */
    public function setCalendar(?Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     * @param ExtendedDateTime|null $dateTime
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false, ?ExtendedDateTime $dateTime = null): void
    {
        $expr = $queryBuilder->expr();

        if($onlyCount) {
            $queryBuilder->select("count(calendarBooking.id)");
        } else {
            $queryBuilder->select("calendarBooking");
        }

        $queryBuilder->from(CalendarBooking::class, "calendarBooking");
        $queryBuilder->join("calendarBooking.calendar", "calendar");

        $where = $queryBuilder->expr()->andX();

        if($this->getCalendar() != null) {
            $calendar = $this->getCalendar();
            $where->add($expr->eq("calendar.id", $calendar->getId()));
        }

        if($dateTime != null) {
            $where->add($this->exprInTimeRange($queryBuilder, $dateTime, "calendarBooking"));
        }

        $queryBuilder->where($where);
    }
}
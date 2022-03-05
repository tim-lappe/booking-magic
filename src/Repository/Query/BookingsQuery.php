<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\Booking;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarBooking;
use TLBM\Utilities\ExtendedDateTime;

define("TLBM_BOOKING_QUERY_ALIAS", "b");

class BookingsQuery extends BaseQuery
{

    /**
     * @var array|null
     */
    private ?array $filterStates = null;

    /**
     * @var Calendar[]
     */
    private ?array $filterCalendars = null;

    /**
     * @var ExtendedDateTime|null
     */
    private ?ExtendedDateTime $filterFromDateTime = null;

    /**
     * @var ExtendedDateTime|null
     */
    private ?ExtendedDateTime $filterToDateTime = null;

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(" . TLBM_BOOKING_QUERY_ALIAS . ".id)")->from(Booking::class, TLBM_BOOKING_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_BOOKING_QUERY_ALIAS)->from(Booking::class, TLBM_BOOKING_QUERY_ALIAS);
        }

        $whereAndExpr = $queryBuilder->expr()->andX();
        if($this->filterFromDateTime != null) {
            $queryBuilder->setParameter("filterFromTstamp", $this->filterFromDateTime->getTimestamp());
            $whereAndExpr->add(TLBM_BOOKING_QUERY_ALIAS . ".timestampCreated >= :filterFromTstamp");
        }

        if($this->filterToDateTime != null) {
            $queryBuilder->setParameter("filterToTimestamp", $this->filterToDateTime->getTimestamp());
            $whereAndExpr->add(TLBM_BOOKING_QUERY_ALIAS . ".timestampCreated <= :filterToTimestamp");
        }

        if($this->filterCalendars != null) {
            $queryBuilder->leftJoin(CalendarBooking::class, "calendarBooking");
            $queryBuilder->leftJoin(Calendar::class, "calendar");
            $calendarOrExpr = $queryBuilder->expr()->orX();
            foreach ($this->filterCalendars as $calendar) {
                $calendarOrExpr->add($queryBuilder->expr()->eq("calendar.id", $calendar->getId()));
            }
            $whereAndExpr->add($calendarOrExpr);
        }

        if($this->filterStates != null) {
            $statesOrExpr = $queryBuilder->expr()->orX();
            foreach ($this->filterStates as $state) {
                $statesOrExpr->add($queryBuilder->expr()->eq(TLBM_BOOKING_QUERY_ALIAS . ".state", "'" . $state. "'"));
            }

            $whereAndExpr->add($statesOrExpr);
        }

        if($whereAndExpr->count() > 0) {
            $queryBuilder->where($whereAndExpr);
        }
    }

    /**
     * @return array|null
     */
    public function getFilterStates(): ?array
    {
        return $this->filterStates;
    }

    /**
     * @param array|null $filterStates
     */
    public function setFilterStates(?array $filterStates): void
    {
        $this->filterStates = $filterStates;
    }

    /**
     * @return Calendar[]
     */
    public function getFilterCalendars(): ?array
    {
        return $this->filterCalendars;
    }

    /**
     * @param Calendar[] $filterCalendars
     */
    public function setFilterCalendars(?array $filterCalendars): void
    {
        $this->filterCalendars = $filterCalendars;
    }

    /**
     * @return ExtendedDateTime|null
     */
    public function getFilterFromDateTime(): ?ExtendedDateTime
    {
        return $this->filterFromDateTime;
    }

    /**
     * @param ExtendedDateTime|null $filterFromDateTime
     */
    public function setFilterFromDateTime(?ExtendedDateTime $filterFromDateTime): void
    {
        $this->filterFromDateTime = $filterFromDateTime;
    }

    /**
     * @return ExtendedDateTime|null
     */
    public function getFilterToDateTime(): ?ExtendedDateTime
    {
        return $this->filterToDateTime;
    }

    /**
     * @param ExtendedDateTime|null $filterToDateTime
     */
    public function setFilterToDateTime(?ExtendedDateTime $filterToDateTime): void
    {
        $this->filterToDateTime = $filterToDateTime;
    }
}
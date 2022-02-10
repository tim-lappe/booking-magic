<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarBooking;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Utilities\ExtendedDateTime;

class CalendarBookingsQuery extends TimeBasedQuery
{

    /**
     * @var ?array
     */
    private ?array $calendarIds = null;

    /**
     * @var bool
     */
    private bool $returnSlotsScalar = false;

    /**
     * @param ORMInterface $repository
     */
    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @return bool
     */
    public function isReturnSlotsScalar(): bool
    {
        return $this->returnSlotsScalar;
    }

    /**
     * @param bool $returnSlotsScalar
     */
    public function setReturnSlotsScalar(bool $returnSlotsScalar): void
    {
        $this->returnSlotsScalar = $returnSlotsScalar;
    }

    /**
     * @return array|null
     */
    public function getCalendarIds(): ?array
    {
        return $this->calendarIds;
    }

    /**
     * @param array|null $calendarIds
     */
    public function setCalendarIds(?array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     * @param ExtendedDateTime|null $dateTime
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false, ?ExtendedDateTime $dateTime = null): void
    {
        $expr = $queryBuilder->expr();

        if($this->returnSlotsScalar) {
            $queryBuilder->select("sum(calendarBooking.slots)");
        } elseif ($onlyCount) {
            $queryBuilder->select("count(calendarBooking.id)");
        } else {
            $queryBuilder->select("calendarBooking");
        }

        $queryBuilder->from(CalendarBooking::class, "calendarBooking");
        $queryBuilder->join("calendarBooking.calendar", "calendar");

        $where = $queryBuilder->expr()->andX();

        if($this->getCalendarIds() != null) {
            $queryBuilder->setParameter("calendarIds", $this->getCalendarIds());
            $where->add($expr->in("calendar.id", ":calendarIds"));
        }

        if($dateTime != null) {
            $where->add($this->exprInTimeRange($queryBuilder, $dateTime, "calendarBooking"));
        }

        $queryBuilder->where($where);
    }
}
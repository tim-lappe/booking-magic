<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Iterator;
use Throwable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;
use TLBM\Repository\Query\Contracts\TimeBasedQueryInterface;
use TLBM\Utilities\ExtendedDateTime;

abstract class TimeBasedQuery extends BaseQuery implements TimeBasedQueryInterface
{

    /**
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $dateTime = null;

    /**
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $fromDateTime = null;

    /**
     *
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $toDateTime = null;

    /**
     * @param string $queryDateInterval
     *
     * @return Iterator
     */
    public function getResult(string $queryDateInterval = TLBM_EXTDATETIME_INTERVAL_DAY): Iterator
    {
        try {
            if ($this->dateTime) {
                yield new TimeBasedQueryResult($this->dateTime, $this->getTimedQueryResult(false, $this->dateTime));

            } elseif ($this->fromDateTime && $this->toDateTime) {
                $period = $this->fromDateTime->getDateTimesBetween($queryDateInterval, $this->toDateTime);
                foreach ($period as $dt) {
                    yield new TimeBasedQueryResult($dt, $this->getTimedQueryResult(false, $dt));
                }
            }
        } catch (Throwable $e) {
            if(WP_DEBUG) {
                $escaping = MainFactory::get(EscapingInterface::class);
                die($escaping->escHtml($e->getMessage()));
            }
        }
    }

    /**
     * @param bool $onlyCount
     * @param ExtendedDateTime|null $dateTime
     *
     * @return mixed
     */
    public function getTimedQueryResult(bool $onlyCount = false, ?ExtendedDateTime $dateTime = null)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->buildQuery($queryBuilder, false, $dateTime);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getQuery(): ?Query
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->buildQuery($queryBuilder, false, $this->dateTime);

        return $queryBuilder->getQuery();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     * @param ExtendedDateTime|null $dateTime
     *
     * @return void
     */
    abstract protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false, ?ExtendedDateTime $dateTime = null): void;

    /**
     * @param QueryBuilder $queryBuilder
     * @param ExtendedDateTime $dateTime
     * @param string $column
     *
     * @return Orx
     */
    protected function exprInTimeRange(QueryBuilder $queryBuilder, ExtendedDateTime $dateTime, string $column): Orx
    {
        $timestampBeginOfDay = $dateTime->getTimestampBeginOfDay();
        $timestampEndOfDay   = $dateTime->getTimestampEndOfDay();

        $timestampFrom = $dateTime->isFullDay() ? $timestampEndOfDay : $dateTime->getTimestamp();
        $timestampTo   = $dateTime->isFullDay() ? $timestampBeginOfDay : $dateTime->getTimestamp();

        $expr            = $queryBuilder->expr();
        $whereClause     = $expr->orX();
        $fullRangeClause = $expr->andX();

        $clauseFromOr = $expr->orX();
        if ($dateTime->isFullDay()) {
            $clauseFromFullDay = $expr->andX();
            $clauseFromFullDay->add($column . ".fromTimestamp <= '" . $timestampEndOfDay . "'");
            $clauseFromFullDay->add($column . ".fromFullDay = 1");
            $clauseFromOr->add($clauseFromFullDay);
        } else {
            $clauseFromOr->add($column . ".fromTimestamp <= '" . $timestampFrom . "'");
        }

        $clauseToOr = $expr->orX();
        if ($dateTime->isFullDay()) {
            $clauseToFullDay = $expr->andX();
            $clauseToFullDay->add($column . ".toTimestamp >= '" . $timestampBeginOfDay . "'");
            $clauseToFullDay->add($column . ".toFullDay = 1");
            $clauseToOr->add($clauseToFullDay);
        } else {
            $clauseToOr->add($expr->isNull($column . ".toTimestamp"));
            $clauseToOr->add($column . ".toTimestamp >= '" . $timestampTo . "'");
        }

        $fullRangeClause->add($clauseFromOr);
        $fullRangeClause->add($clauseToOr);
        $whereClause->add($fullRangeClause);

        return $whereClause;
    }

    /**
     * @param ExtendedDateTime $fromDateTime
     */
    public function setFromDateTime(ExtendedDateTime $fromDateTime): void
    {
        $this->dateTime     = null;
        $this->fromDateTime = $fromDateTime;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getFromDateTime(): ?ExtendedDateTime
    {
        return $this->fromDateTime;
    }

    /**
     * @param ExtendedDateTime $fromDt
     * @param ExtendedDateTime $toDt
     *
     * @return void
     */
    public function setDateTimeRange(ExtendedDateTime $fromDt, ExtendedDateTime $toDt)
    {
        $this->setFromDateTime($fromDt);
        $this->setToDateTime($toDt);
    }

    /**
     * @param ExtendedDateTime $dateTime
     */
    public function setDateTime(ExtendedDateTime $dateTime): void
    {
        $this->fromDateTime = null;
        $this->toDateTime   = null;
        $this->dateTime     = $dateTime;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getToDateTime(): ?ExtendedDateTime
    {
        return $this->toDateTime;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTime(): ?ExtendedDateTime
    {
        return $this->dateTime;
    }

    /**
     * @param ExtendedDateTime $toDateTime
     */
    public function setToDateTime(ExtendedDateTime $toDateTime): void
    {
        $this->dateTime   = null;
        $this->toDateTime = $toDateTime;
    }
}
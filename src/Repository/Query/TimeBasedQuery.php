<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Iterator;
use Throwable;
use TLBM\Repository\Contracts\TimeBasedQueryInterface;
use TLBM\Utilities\ExtendedDateTime;
use Traversable;

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

                $queryBuilder = $this->createQueryBuilder();
                $this->buildQuery($queryBuilder, $this->dateTime);

                yield new TimeBasedQueryResult($this->dateTime, $queryBuilder->getQuery()->getResult());

            } elseif ($this->fromDateTime && $this->toDateTime) {
                $period = $this->fromDateTime->getDateTimesBetween($queryDateInterval, $this->toDateTime);
                foreach ($period as $dt) {

                    $queryBuilder = $this->createQueryBuilder();
                    $this->buildQuery($queryBuilder, $dt);

                    yield new TimeBasedQueryResult($dt, $queryBuilder->getQuery()->getResult());
                }
            }
        } catch (Throwable $exception) {
            if(WP_DEBUG) {
                var_dump($exception->getMessage());
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param ExtendedDateTime|null $dateTime
     *
     * @return void
     */
    abstract protected function buildQuery(QueryBuilder $queryBuilder, ?ExtendedDateTime $dateTime = null): void;

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
        $timestampEndOfDay = $dateTime->getTimestampEndOfDay();

        $timestampFrom = $dateTime->isFullDay() ? $timestampEndOfDay : $dateTime->getTimestamp();
        $timestampTo = $dateTime->isFullDay() ? $timestampBeginOfDay : $dateTime->getTimestamp();

        $expr = $queryBuilder->expr();
        $whereClause = $expr->orX();
        $fullRangeClause = $expr->andX();

        $clauseFromOr = $expr->orX();
        $clauseFromOr->add($column . ".fromTimestamp <= '" . $timestampFrom . "'");

        $clauseFromFullDay = $expr->andX();
        $clauseFromFullDay->add($column . ".fromTimestamp <= '" . $timestampEndOfDay . "'");
        $clauseFromFullDay->add($column . ".fromFullDay = true");
        $clauseFromOr->add($clauseFromFullDay);

        $clauseToOr = $expr->orX();
        $clauseToOr->add($expr->isNull($column . ".toTimestamp"));
        $clauseToOr->add($column .".toTimestamp >= '" . $timestampTo . "'");

        $clauseToFullDay = $expr->andX();
        $clauseToFullDay->add($column . ".toTimestamp >= '" . $timestampBeginOfDay . "'");
        $clauseToFullDay->add($column . ".toFullDay = true");
        $clauseToOr->add($clauseToFullDay);

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
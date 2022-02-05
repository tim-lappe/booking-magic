<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_CALENDAR_QUERY_ALIAS", "c");

class CalendarQuery extends BaseQuery
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->select(TLBM_CALENDAR_QUERY_ALIAS)->from("\TLBM\Entity\Calendar", TLBM_CALENDAR_QUERY_ALIAS);
    }
}
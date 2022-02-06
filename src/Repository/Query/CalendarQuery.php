<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_CALENDAR_QUERY_ALIAS", "c");

class CalendarQuery extends BaseQuery
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(".TLBM_CALENDAR_QUERY_ALIAS.".id)")->from("\TLBM\Entity\Calendar", TLBM_CALENDAR_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_CALENDAR_QUERY_ALIAS)->from("\TLBM\Entity\Calendar", TLBM_CALENDAR_QUERY_ALIAS);
        }
    }
}
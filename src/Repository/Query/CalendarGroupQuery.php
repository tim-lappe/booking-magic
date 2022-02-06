<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarGroup;

define("TLBM_CALENDAR_GROUP_QUERY_ALIAS", "cg");

class CalendarGroupQuery extends BaseQuery
{

    /**
     * @inheritDoc
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(" .TLBM_CALENDAR_GROUP_QUERY_ALIAS . ".id)")->from(CalendarGroup::class, TLBM_CALENDAR_GROUP_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_CALENDAR_GROUP_QUERY_ALIAS)->from(CalendarGroup::class, TLBM_CALENDAR_GROUP_QUERY_ALIAS);
        }
    }
}
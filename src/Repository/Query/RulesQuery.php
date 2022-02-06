<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_RULE_QUERY_ALIAS", "r");

class RulesQuery extends BaseQuery
{

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if ($onlyCount) {
            $queryBuilder->select("count(" . TLBM_RULE_QUERY_ALIAS . ".id)")->from("\TLBM\Entity\Rule", TLBM_RULE_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_RULE_QUERY_ALIAS)->from("\TLBM\Entity\Rule", TLBM_RULE_QUERY_ALIAS);
        }
    }
}
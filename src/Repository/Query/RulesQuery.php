<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_RULE_QUERY_ALIAS", "r");

class RulesQuery extends BaseQuery
{

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->select(TLBM_RULE_QUERY_ALIAS)->from("\TLBM\Entity\Rule", TLBM_RULE_QUERY_ALIAS);
    }
}
<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_FORM_QUERY_ALIAS", "f");

class FormQuery extends BaseQuery
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
            $queryBuilder->select("count(" . TLBM_FORM_QUERY_ALIAS . ".id)")->from("\TLBM\Entity\Form", TLBM_FORM_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_FORM_QUERY_ALIAS)->from("\TLBM\Entity\Form", TLBM_FORM_QUERY_ALIAS);
        }
    }
}
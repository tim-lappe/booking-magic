<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;

define("TLBM_FORM_QUERY_ALIAS", "f");

class FormQuery extends BaseQuery
{

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->select(TLBM_FORM_QUERY_ALIAS)->from("\TLBM\Entity\Form", TLBM_FORM_QUERY_ALIAS);
    }
}
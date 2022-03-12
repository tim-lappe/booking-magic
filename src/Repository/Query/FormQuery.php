<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\Form;
use TLBM\Repository\Contracts\ORMInterface;

define("TLBM_FORM_QUERY_ALIAS", "f");

class FormQuery extends ManageableEntityQuery
{

    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
        $this->setEntityClass(Form::class);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        parent::buildQuery($queryBuilder, $onlyCount);
    }
}
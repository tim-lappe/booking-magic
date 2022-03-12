<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\ManageableEntity;

define("TLBM_ENTITY_QUERY_ALIAS", "e");

class ManageableEntityQuery extends BaseQuery
{

    /**
     * @var class-string<ManageableEntity>
     */
    private string $entityClass;

    /**
     * @var bool
     */
    private bool $includeDeleted = false;

    /**
     * @var bool
     */
    private bool $onlyDeleted = false;

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass(string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return bool
     */
    public function isIncludeDeleted(): bool
    {
        return $this->includeDeleted;
    }

    /**
     * @param bool $includeDeleted
     */
    public function setIncludeDeleted(bool $includeDeleted): void
    {
        $this->includeDeleted = $includeDeleted;
    }

    /**
     * @return bool
     */
    public function isOnlyDeleted(): bool
    {
        return $this->onlyDeleted;
    }

    /**
     * @param bool $onlyDeleted
     */
    public function setOnlyDeleted(bool $onlyDeleted): void
    {
        $this->onlyDeleted = $onlyDeleted;
    }

    /**
     * @inheritDoc
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(".TLBM_ENTITY_QUERY_ALIAS.".id)")->from($this->entityClass, TLBM_ENTITY_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_ENTITY_QUERY_ALIAS)->from($this->entityClass, TLBM_ENTITY_QUERY_ALIAS);
        }

        if(!$this->includeDeleted) {
            $queryBuilder->where($queryBuilder->expr()->notIn(TLBM_ENTITY_QUERY_ALIAS . ".administrationStatus", [
                TLBM_ENTITY_ADMINSTATUS_DELETED
            ]));
        }

        if($this->onlyDeleted) {
            $queryBuilder->where($queryBuilder->expr()->in(TLBM_ENTITY_QUERY_ALIAS . ".administrationStatus", [
                TLBM_ENTITY_ADMINSTATUS_DELETED
            ]));
        }
    }
}
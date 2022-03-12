<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarGroup;
use TLBM\Repository\Contracts\ORMInterface;

class CalendarGroupQuery extends ManageableEntityQuery
{

    /**
     * @var ?array
     */
    private ?array $groupIds = null;

    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
        $this->setEntityClass(CalendarGroup::class);
    }

    /**
     * @inheritDoc
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        parent::buildQuery($queryBuilder, $onlyCount);
        $where = $queryBuilder->expr()->andX();

        if ($this->groupIds) {
            $queryBuilder->setParameter(":groupIds", implode(",", $this->groupIds));
            $where->add($queryBuilder->expr()->in(TLBM_ENTITY_QUERY_ALIAS . ".id", ":groupIds"));
        }

        if($where->count() > 0) {
            $queryBuilder->where($where);
        }
    }

    /**
     * @return array|null
     */
    public function getGroupIds(): ?array
    {
        return $this->groupIds;
    }

    /**
     * @param array|null $groupIds
     */
    public function setGroupIds(?array $groupIds): void
    {
        $this->groupIds = $groupIds;
    }
}
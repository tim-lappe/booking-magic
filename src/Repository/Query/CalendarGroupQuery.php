<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarGroup;

define("TLBM_CALENDAR_GROUP_QUERY_ALIAS", "cg");

class CalendarGroupQuery extends BaseQuery
{

    /**
     * @var ?array
     */
    private ?array $groupIds = null;


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

        $where = $queryBuilder->expr()->andX();

        if($this->groupIds) {
            $queryBuilder->setParameter(":groupIds", implode(",", $this->groupIds));
            $where->add($queryBuilder->expr()->in(TLBM_CALENDAR_GROUP_QUERY_ALIAS . ".id",":groupIds"));
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
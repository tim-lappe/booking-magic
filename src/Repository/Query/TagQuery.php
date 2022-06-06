<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarCategory;
use TLBM\Repository\Contracts\ORMInterface;

class TagQuery extends ManageableEntityQuery
{
    /**
     * @var array
     */
    private array $filterCalendarIds = [];

    /**
     * @param ORMInterface $repository
     */
    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
        $this->setEntityClass(CalendarCategory::class);
    }

    /**
     * @inheritDoc
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        parent::buildQuery($queryBuilder, $onlyCount);
        $where = $queryBuilder->expr()->andX();

        if ($this->filterCalendarIds != null) {
            $queryBuilder->leftJoin(TLBM_ENTITY_QUERY_ALIAS . '.calendars', 'calendars');
            $where->add($queryBuilder->expr()->in("calendars.id", $this->filterCalendarIds));
        }

        if ($where->count() > 0) {
            $queryBuilder->where($where);
        }
    }

    /**
     * @return array
     */
    public function getFilterCalendarIds(): array
    {
        return $this->filterCalendarIds;
    }

    /**
     * @param array $filterCalendarIds
     */
    public function setFilterCalendarIds(array $filterCalendarIds): void
    {
        $this->filterCalendarIds = $filterCalendarIds;
    }
}
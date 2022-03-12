<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\Rule;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\ORMInterface;

class RulesQuery extends ManageableEntityQuery
{
    /**
     * @var int[]
     */
    private array $filterCalendarsIds = [];

    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);

        $this->setEntityClass(Rule::class);
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
        $where = $queryBuilder->expr()->andX();

        if ($this->filterCalendarsIds != null) {
            $queryBuilder->leftJoin(TLBM_ENTITY_QUERY_ALIAS . '.calendarSelection', 'calendarSelection');
            $queryBuilder->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars");

            $calendarSelectionHelper = MainFactory::create(CalendarSelectionQueryHelper::class);
            $calendarSelectionHelper->setCalendarIds($this->filterCalendarsIds);
            $where->add($calendarSelectionHelper->getQueryExpr($queryBuilder));
        }

        if ($where->count() > 0) {
            $queryBuilder->where($where);
        }
    }

    /**
     * @return array
     */
    public function getFilterCalendarsIds(): array
    {
        return $this->filterCalendarsIds;
    }

    /**
     * @param int[] $filterCalendarsIds
     */
    public function setFilterCalendarsIds(array $filterCalendarsIds): void
    {
        $this->filterCalendarsIds = $filterCalendarsIds;
    }
}
<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\Repository\Contracts\ORMInterface;

class CalendarQuery extends ManageableEntityQuery
{

    /**
     * @var CalendarSelection|null
     */
    private ?CalendarSelection $calendarSelection = null;

    /**
     * @param ORMInterface $repository
     */
    public function __construct(ORMInterface $repository)
    {
        parent::__construct($repository);
        $this->setEntityClass(Calendar::class);
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
        if($this->calendarSelection && $this->calendarSelection->getSelectionMode() != TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            if($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
                $where->add($queryBuilder->expr()->in(TLBM_ENTITY_QUERY_ALIAS . ".id", $this->calendarSelection->getCombinedCalendarIds()));
            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
                $where->add($queryBuilder->expr()->notIn(TLBM_ENTITY_QUERY_ALIAS . ".id", $this->calendarSelection->getCombinedCalendarIds()));
            }
        }

        if($where->count() > 0) {
            $queryBuilder->where($where);
        }
    }

    /**
     * @return CalendarSelection|null
     */
    public function getCalendarSelection(): ?CalendarSelection
    {
        return $this->calendarSelection;
    }

    /**
     * @param CalendarSelection|null $calendarSelection
     */
    public function setCalendarSelection(?CalendarSelection $calendarSelection): void
    {
        $this->calendarSelection = $calendarSelection;
    }


}
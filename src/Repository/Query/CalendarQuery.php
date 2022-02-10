<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarSelection;

define("TLBM_CALENDAR_QUERY_ALIAS", "c");

class CalendarQuery extends BaseQuery
{
    /**
     * @var CalendarSelection|null
     */
    private ?CalendarSelection $calendarSelection = null;

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(".TLBM_CALENDAR_QUERY_ALIAS.".id)")->from("\TLBM\Entity\Calendar", TLBM_CALENDAR_QUERY_ALIAS);
        } else {
            $queryBuilder->select(TLBM_CALENDAR_QUERY_ALIAS)->from("\TLBM\Entity\Calendar", TLBM_CALENDAR_QUERY_ALIAS);
        }

        $where = $queryBuilder->expr()->andX();
        if($this->calendarSelection && $this->calendarSelection->getSelectionMode() != TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            if($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
                $where->add($queryBuilder->expr()->in(TLBM_CALENDAR_QUERY_ALIAS . ".id", $this->calendarSelection->getCalendarIds()));
            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
                $where->add($queryBuilder->expr()->notIn(TLBM_CALENDAR_QUERY_ALIAS . ".id", $this->calendarSelection->getCalendarIds()));
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
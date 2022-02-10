<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query\Expr\Base;
use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarSelection;
use TLBM\Repository\Contracts\ORMInterface;

class CalendarSelectionQueryHelper
{
    /**
     * @var array
     */
    private array $calendarIds;


    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $calendarSelectionAlias
     * @param string $calendarSelectionCalendarsAlias
     *
     * @return Base
     */
    public function getQueryExpr(QueryBuilder $queryBuilder, string $calendarSelectionAlias = "calendarSelection", string $calendarSelectionCalendarsAlias = "calendarSelectionCalendars"): Base
    {
        $calendarIdExpr = implode(",", $this->calendarIds);
        $queryBuilder->setParameter("calendarIds", $calendarIdExpr);
        $selectionWhere = $queryBuilder->expr()->orX();
        $selectionWhere->add(
            $calendarSelectionAlias . ".selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL . "'"
        );

        $only = $queryBuilder->expr()->andX();
        $only->add($calendarSelectionAlias . ".selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ONLY . "'");
        $only->add($calendarSelectionCalendarsAlias . ".id IN (:calendarIds)");

        $allBut = $queryBuilder->expr()->andX();
        $allBut->add($calendarSelectionAlias . ".selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT . "'");

        $subqAllButIds = $this->repository->getEntityManager()->createQueryBuilder();
        $subqAllButIds->setParameter("calendarIds", $calendarIdExpr);
        $subqAllButIds->select("subCalendarSelection")->from(CalendarSelection::class, "subCalendarSelection")->leftJoin("subCalendarSelection.calendars", "subCalendarSeletcionCalendars")->where("subCalendarSeletcionCalendars.id IN (:calendarIds)");
        $allBut->add($queryBuilder->expr()->notIn($calendarSelectionAlias, $subqAllButIds->getDQL()));

        $selectionWhere->add($only);
        $selectionWhere->add($allBut);
        return $selectionWhere;
    }

    /**
     * @return array
     */
    public function getCalendarIds(): array
    {
        return $this->calendarIds;
    }

    /**
     * @param array $calendarIds
     */
    public function setCalendarIds(array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
    }
}
<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Rules\TimedRules;
use TLBM\Utilities\ExtendedDateTime;

use const TLBM_CALENDAR_SELECTION_TYPE_ALL;
use const TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT;
use const TLBM_CALENDAR_SELECTION_TYPE_ONLY;

class FullRuleActionQuery extends TimeBasedQuery implements FullRuleActionQueryInterface
{

    /**
     * @var int|null
     */
    private ?int $calendarId = null;

    /**
     * @var array|null
     */
    private ?array $actionTypes = null;


    /**
     * @var LabelsInterface
     */
    private LabelsInterface $labels;


    public function __construct(ORMInterface $repository, LabelsInterface $labels)
    {
        parent::__construct($repository);
        $this->labels     = $labels;
    }

    /**
     * @param int $calendarId
     *
     * @return void
     */
    public function setTypeCalendar(int $calendarId): void
    {
        $this->calendarId = $calendarId;
    }

    /**
     * @param array $actionType
     *
     * @return void
     */
    public function setActionTypes(array $actionType): void
    {
        $this->actionTypes = $actionType;
    }

    /**
     * @return array
     */
    public function getTimedRulesResult(): array
    {
        $timedRules = [];
        foreach ($this->getResult() as $result) {
            $timedRules[] = new TimedRules($result->getDateTime(), (array) $result->getQueryResult());
        }

        return $timedRules;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     * @param ExtendedDateTime|null $dateTime
     */
    protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false, ?ExtendedDateTime $dateTime = null): void
    {
        if($onlyCount) {
            $queryBuilder->select("count(rule.id)");
        } else {
            $queryBuilder->select("rule,actions,periods,calendarSelection,calendarSelectionCalendars");
        }

        $queryBuilder->from(Rule::class, "rule")
                     ->distinct(true)
                     ->leftJoin('rule.calendarSelection', 'calendarSelection')
                     ->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars")
                     ->leftJoin("rule.actions", "actions")
                     ->leftJoin("rule.periods", "periods");

        $where = $queryBuilder->expr()->andX();

        if ($this->calendarId) {
            $queryBuilder->setParameter("calendarId", $this->calendarId);
            $selectionWhere = $queryBuilder->expr()->orX();
            $selectionWhere->add(
                "calendarSelection.selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL . "'"
            );

            $only = $queryBuilder->expr()->andX();
            $only->add("calendarSelection.selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ONLY . "'");
            $only->add("calendarSelectionCalendars.id = :calendarId");

            $allBut = $queryBuilder->expr()->andX();
            $allBut->add("calendarSelection.selectionMode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT . "'");

            $subqAllButIds = $this->repository->getEntityManager()->createQueryBuilder();
            $subqAllButIds->setParameter("calendarId", $this->calendarId);
            $subqAllButIds->select("subCalendarSelection")->from(CalendarSelection::class, "subCalendarSelection")->leftJoin("subCalendarSelection.calendars", "subCalendarSeletcionCalendars")->where("subCalendarSeletcionCalendars.id = :calendarId");

            $allBut->add($queryBuilder->expr()->notIn("calendarSelection", $subqAllButIds->getDQL()));

            $selectionWhere->add($only);
            $selectionWhere->add($allBut);

            $where->add($selectionWhere);
        }

        if ($dateTime) {
            $where->add($this->buildWeekdaysWhereExpr($queryBuilder->expr(), $dateTime));
            $trangeOr = $this->exprInTimeRange($queryBuilder, $dateTime, "periods");
            $trangeOr->add("SIZE(rule.periods) = 0");
            $where->add($trangeOr);
        }

        if ($this->actionTypes) {
            $queryBuilder->setParameter("actionType", $this->actionTypes);
            $where->add("actions.action_type IN (:actionType)");
        }

        $queryBuilder->where($where);
    }

    public function getDefaultOrderBy(): ?array
    {
        return [
            ["rule.priority", "DESC"],
            ["actions.priority", "DESC"]
        ];
    }

    /**
     * @param Expr $expr
     * @param ExtendedDateTime $dateTime
     *
     * @return Expr\Base
     */
    private function buildWeekdaysWhereExpr(Expr $expr, ExtendedDateTime $dateTime): Expr\Base
    {
        $weekday       = $dateTime->getWeekday();
        $satOrSun      = $weekday == 6 || $weekday == 7;
        $mofr          = $weekday <= 5;
        $labels        = array_keys($this->labels->getWeekdayLabels());
        $weekday_label = $labels[$weekday - 1];

        $weekdays_where = $expr->orX();
        if ($satOrSun) {
            $weekdays_where->add("actions.weekdays = 'sat_and_sun'");
        } elseif ($mofr) {
            $weekdays_where->add("actions.weekdays = 'mo_to_fr'");
        }

        $weekdays_where->add("actions.weekdays = 'every_day'");
        $weekdays_where->add("actions.weekdays = '" . $weekday_label . "'");
        return $weekdays_where;
    }
}
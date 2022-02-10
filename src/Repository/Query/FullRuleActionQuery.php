<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Iterator;
use TLBM\Entity\Rule;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Rules\TimedRules;
use TLBM\Utilities\ExtendedDateTime;

class FullRuleActionQuery extends TimeBasedQuery implements FullRuleActionQueryInterface
{

    /**
     * @var array|null
     */
    private ?array $calendarIds = null;

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
     * @param array $calendarIds
     *
     * @return void
     */
    public function setCalendarIds(array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
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
     * @return Iterator
     */
    public function getTimedRulesResult(): Iterator
    {
        foreach ($this->getResult() as $result) {
            yield new TimedRules($result->getDateTime(), (array) $result->getQueryResult());
        }
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

        if ($this->calendarIds) {
            $calendarSelectionHelper = MainFactory::create(CalendarSelectionQueryHelper::class);
            $calendarSelectionHelper->setCalendarIds($this->calendarIds);
            $where->add($calendarSelectionHelper->getQueryExpr($queryBuilder));
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

        if($where->count() > 0) {
            $queryBuilder->where($where);
        }
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
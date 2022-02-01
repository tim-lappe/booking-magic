<?php

namespace TLBM\Rules;

use DateInterval;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Rules\Actions\TimedRuleCollection;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Utilities\ExtendedDateTime;

use const TLBM\Utilities\EXTDATETIME_INTERVAL_DAY;
use const TLBM_CALENDAR_SELECTION_TYPE_ALL;
use const TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT;
use const TLBM_CALENDAR_SELECTION_TYPE_ONLY;

class RulesQuery implements RulesQueryInterface
{

    /**
     * @var int|null
     */
    private ?int $calendarId = null;

    /**
     * @var ExtendedDateTime|null
     */
    private ?ExtendedDateTime $dateTime;

    /**
     * @var ExtendedDateTime|null
     */
    private ?ExtendedDateTime $dateTimeFrom;

    /**
     * @var ExtendedDateTime|null
     */
    private ?ExtendedDateTime $dateTimeTo;

    /**
     * @var array|null
     */
    private ?array $actionTypes = null;

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    /**
     * @var LabelsInterface
     */
    private LabelsInterface $labels;


    public function __construct(ORMInterface $repository, LabelsInterface $labels)
    {
        $this->repository = $repository;
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
     * @param ExtendedDateTime $dateTime
     *
     * @return void
     */
    public function setDateTime(ExtendedDateTime $dateTime): void
    {
        $this->dateTimeFrom = null;
        $this->dateTimeTo   = null;
        $this->dateTime     = $dateTime;
    }

    /**
     * @param ExtendedDateTime $dateTimeFrom
     * @param ExtendedDateTime $dateTimeTo
     *
     * @return void
     */
    public function setDateTimeRange(ExtendedDateTime $dateTimeFrom, ExtendedDateTime $dateTimeTo): void
    {
        $this->dateTimeFrom = $dateTimeFrom;
        $this->dateTimeTo   = $dateTimeTo;
        $this->dateTime     = null;
    }

    /**
     * @return TimedRules[]
     */
    public function getResult(): array
    {
        if ($this->dateTime) {
            $queryBuilder = $this->getQueryforSingle($this->dateTime);
            return [
                new TimedRules($this->dateTime, $queryBuilder->getQuery()->getResult())
            ];

        } elseif ($this->dateTimeTo && $this->dateTimeFrom) {
            $period = $this->dateTimeFrom->getDateTimesBetween(EXTDATETIME_INTERVAL_DAY, $this->dateTimeTo);
            $timedRules = [];
            foreach ($period as $dt) {
                $queryBuilder                 = $this->getQueryforSingle($dt);
                $timedRules[] = new TimedRules($dt, $queryBuilder->getQuery()->getResult());
            }

            return $timedRules;
        }

        return [];
    }

    /**
     * @param ExtendedDateTime|null $dateTime
     *
     * @return QueryBuilder
     */
    private function getQueryforSingle(?ExtendedDateTime $dateTime = null): QueryBuilder
    {
        $entityManager = $this->repository->getEntityManager();
        $queryBuilder  = $entityManager->createQueryBuilder();
        $queryBuilder->select("rule,actions,periods,calendarSelection,calendarSelectionCalendars")
                     ->from(Rule::class, "rule")
                     ->distinct(true)
                     ->leftJoin('rule.calendar_selection', 'calendarSelection')
                     ->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars")
                     ->leftJoin("rule.actions", "actions")
                     ->leftJoin("rule.periods", "periods");

        $where = $queryBuilder->expr()->andX();

        if ($this->calendarId) {
            $queryBuilder->setParameter("calendarId", $this->calendarId);
            $selectionWhere = $queryBuilder->expr()->orX();
            $selectionWhere->add(
                "calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL . "'"
            );

            $only = $queryBuilder->expr()->andX();
            $only->add("calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ONLY . "'");
            $only->add("calendarSelectionCalendars.id = :calendarId");

            $allBut = $queryBuilder->expr()->andX();
            $allBut->add("calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT . "'");
            $subqAllButIds = $entityManager->createQueryBuilder();
            $subqAllButIds->setParameter("calendarId", $this->calendarId);
            $subqAllButIds->select("subCalendarSelection")->from(CalendarSelection::class, "subCalendarSelection")->leftJoin("subCalendarSelection.calendars", "subCalendarSeletcionCalendars")->where("subCalendarSeletcionCalendars.id = :calendarId");

            $allBut->add($queryBuilder->expr()->notIn("calendarSelection", $subqAllButIds->getDQL()));

            $selectionWhere->add($only);
            $selectionWhere->add($allBut);

            $where->add($selectionWhere);
        }

        if ($dateTime) {
            $where->add($this->buildWeekdaysWhereExpr($queryBuilder->expr(), $dateTime));
            $where->add($this->buildPeriodsWhereExpr($queryBuilder, $dateTime));
        }

        if ($this->actionTypes) {
            $queryBuilder->setParameter("action_type", $this->actionTypes);
            $where->add("actions.action_type IN (:action_type)");
        }

        $queryBuilder->where($where);
        $queryBuilder->addOrderBy("rule.priority", "DESC");
        $queryBuilder->addOrderBy("actions.priority", "DESC");
        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param ExtendedDateTime $dateTime
     *
     * @return Expr\Base
     */
    private function buildPeriodsWhereExpr(QueryBuilder $queryBuilder, ExtendedDateTime $dateTime): Expr\Base
    {
        $timestampBeginOfDay = $dateTime->getTimestampBeginOfDay();
        $timestampEndOfDay = $dateTime->getTimestampEndOfDay();

        $timestampFrom = $dateTime->isFullDay() ? $timestampEndOfDay : $dateTime->getTimestamp();
        $timestampTo = $dateTime->isFullDay() ? $timestampBeginOfDay : $dateTime->getTimestamp();

        $expr = $queryBuilder->expr();
        $periodsWhere = $expr->orX();
        $periodsNotEmpty = $expr->andX();

        $periodsFromOr = $expr->orX();
        $periodsFromOr->add("periods.fromTimestamp <= '" . $timestampFrom . "'");

        $periodsFromNoTimeset = $expr->andX();
        $periodsFromNoTimeset->add("periods.fromTimestamp <= '" . $timestampEndOfDay . "'");
        $periodsFromNoTimeset->add("periods.fromTimeset = false");
        $periodsFromOr->add($periodsFromNoTimeset);

        $periodsToOr = $expr->orX();
        $periodsToOr->add($expr->isNull("periods.toTimestamp"));
        $periodsToOr->add("periods.toTimestamp >= '" . $timestampTo . "'");

        $periodsToNoTimeset = $expr->andX();
        $periodsToNoTimeset->add("periods.toTimestamp >= '" . $timestampBeginOfDay . "'");
        $periodsToNoTimeset->add("periods.toTimeset = false");
        $periodsToOr->add($periodsToNoTimeset);

        $periodsWhere->add("SIZE(rule.periods) = 0");
        $periodsNotEmpty->add($periodsFromOr);
        $periodsNotEmpty->add($periodsToOr);
        $periodsWhere->add($periodsNotEmpty);
        return $periodsWhere;
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
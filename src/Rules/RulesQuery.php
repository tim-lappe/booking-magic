<?php

namespace TLBM\Rules;

use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;

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
     * @var DateTime|null
     */
    private ?DateTime $dateTime;

    /**
     * @var DateTime|null
     */
    private ?DateTime $dateTimeFrom;

    /**
     * @var DateTime|null
     */
    private ?DateTime $dateTimeTo;

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
     * @param DateTime $dateTime
     *
     * @return void
     */
    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTimeFrom = null;
        $this->dateTimeTo   = null;
        $this->dateTime     = $dateTime;
    }

    /**
     * @param DateTime $dateTimeFrom
     * @param DateTime $dateTimeTo
     *
     * @return void
     */
    public function setDateTimeRange(DateTime $dateTimeFrom, DateTime $dateTimeTo): void
    {
        $this->dateTimeFrom = $dateTimeFrom;
        $this->dateTimeTo   = $dateTimeTo;
        $this->dateTime     = null;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        if ($this->dateTime) {
            $queryBuilder = $this->getQueryforSingle($this->dateTime);
            return array($queryBuilder->getQuery()->getResult());

        } elseif ($this->dateTimeTo && $this->dateTimeFrom) {
            $period = new DatePeriod($this->dateTimeFrom, new DateInterval('P1D'), $this->dateTimeTo);
            $results = array();

            /**
             * @var DateTime $dt
             */
            foreach ($period as $dt) {
                $queryBuilder = $this->getQueryforSingle($dt);
                $results[$dt->getTimestamp()] = $queryBuilder->getQuery()->getResult();
            }

            return $results;
        }

        return array();
    }

    /**
     * @param DateTime|null $date_time
     *
     * @return QueryBuilder
     */
    private function getQueryforSingle(?DateTime $date_time = null): QueryBuilder
    {
        $entityManager = $this->repository->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select("rule,actions,periods,calendarSelection,calendarSelectionCalendars")->from(Rule::class, "rule")->distinct(true)->leftJoin('rule.calendar_selection', 'calendarSelection')->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars")->leftJoin("rule.actions", "actions")->leftJoin("rule.periods", "periods");

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

        if ($date_time) {
            $weekday       = intval($date_time->format("N"));
            $satOrSun    = $weekday == 6 || $weekday == 7;
            $mofr          = $weekday <= 5;
            $labels        = array_keys($this->labels->getWeekdayLabels());
            $weekday_label = $labels[$weekday - 1];

            $weekdays_where = $queryBuilder->expr()->orX();
            if ($satOrSun) {
                $weekdays_where->add("actions.weekdays = 'sat_and_sun'");
            } elseif ($mofr) {
                $weekdays_where->add("actions.weekdays = 'mo_to_fr'");
            }

            $weekdays_where->add("actions.weekdays = 'every_day'");
            $weekdays_where->add("actions.weekdays = '" . $weekday_label . "'");
            $where->add($weekdays_where);
        }

        if ($this->actionTypes) {
            $queryBuilder->setParameter("action_type", $this->actionTypes);
            $where->add("actions.action_type IN (:action_type)");
        }

        $queryBuilder->where($where);
        $queryBuilder->addOrderBy("rule.priority","ASC");
        $queryBuilder->addOrderBy("actions.priority", "DESC");

        return $queryBuilder;
    }
}
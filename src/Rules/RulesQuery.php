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
            $qb = $this->getQueryforSingle($this->dateTime);
            return array($qb->getQuery()->getResult());

        } elseif ($this->dateTimeTo && $this->dateTimeFrom) {
            $period = new DatePeriod($this->dateTimeFrom, new DateInterval('P1D'), $this->dateTimeTo);
            $em     = $this->repository->getEntityManager();

            $results = array();
            $query   = $em->createQuery();

            /**
             * @var DateTime $dt
             */
            foreach ($period as $dt) {
                $qb                           = $this->getQueryforSingle($dt);
                $results[$dt->getTimestamp()] = $qb->getQuery()->getResult();
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
        $em = $this->repository->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select("rule,actions,periods,calendarSelection,calendarSelectionCalendars")->from(Rule::class, "rule")->distinct(true)->leftJoin('rule.calendar_selection', 'calendarSelection')->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars")->leftJoin("rule.actions", "actions")->leftJoin("rule.periods", "periods");

        $where = $qb->expr()->andX();

        if ($this->calendarId) {
            $qb->setParameter("calendarId", $this->calendarId);

            $calendar_selection_where = $qb->expr()->orX();
            $calendar_selection_where->add(
                "calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL . "'"
            );

            $only = $qb->expr()->andX();
            $only->add("calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ONLY . "'");
            $only->add("calendarSelectionCalendars.id = :calendarId");

            $all_but = $qb->expr()->andX();
            $all_but->add("calendarSelection.selection_mode = '" . TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT . "'");
            $subq_all_but_ids = $em->createQueryBuilder();
            $subq_all_but_ids->setParameter("calendarId", $this->calendarId);
            $subq_all_but_ids->select("subCalendarSelection")->from(CalendarSelection::class, "subCalendarSelection")->leftJoin("subCalendarSelection.calendars", "subCalendarSeletcionCalendars")->where("subCalendarSeletcionCalendars.id = :calendarId");

            $all_but->add($qb->expr()->notIn("calendarSelection", $subq_all_but_ids->getDQL()));

            $calendar_selection_where->add($only);
            $calendar_selection_where->add($all_but);

            $where->add($calendar_selection_where);
        }

        if ($date_time) {
            $weekday       = intval($date_time->format("N"));
            $sat_or_sun    = $weekday == 6 || $weekday == 7;
            $mofr          = $weekday <= 5;
            $labels        = array_keys($this->labels->getWeekdayLabels());
            $weekday_label = $labels[$weekday - 1];

            $weekdays_where = $qb->expr()->orX();
            if ($sat_or_sun) {
                $weekdays_where->add("actions.weekdays = 'sat_and_sun'");
            } elseif ($mofr) {
                $weekdays_where->add("actions.weekdays = 'mo_to_fr'");
            }

            $weekdays_where->add("actions.weekdays = 'every_day'");
            $weekdays_where->add("actions.weekdays = '" . $weekday_label . "'");
            $where->add($weekdays_where);
        }

        if ($this->actionTypes) {
            $qb->setParameter("action_type", $this->actionTypes);
            $where->add("actions.action_type IN (:action_type)");
        }

        $qb->where($where);

        return $qb;
    }
}
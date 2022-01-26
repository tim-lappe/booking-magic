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

use const TLBM_CALENDAR_SELECTION_TYPE_ALL;
use const TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT;
use const TLBM_CALENDAR_SELECTION_TYPE_ONLY;

class RulesQuery
{

    private ?int $calendar_id = null;
    private ?DateTime $date_time;
    private ?DateTime $date_time_from;
    private ?DateTime $date_time_to;
    private ?array $action_types = null;

    private ORMInterface $repository;

    private LabelsInterface $labels;

    public function __construct(ORMInterface $repository, LabelsInterface $labels, DateTime $date_time = null)
    {
        $this->date_time  = $date_time;
        $this->repository = $repository;
        $this->labels     = $labels;
    }

    public function setTypeCalendar(int $calendar_id)
    {
        $this->calendar_id = $calendar_id;
    }

    public function setActionTypes(array $action_type)
    {
        $this->action_types = $action_type;
    }

    public function setDateTime(DateTime $date_time)
    {
        $this->date_time_from = null;
        $this->date_time_to   = null;
        $this->date_time      = $date_time;
    }

    public function setDateTimeRange(DateTime $date_time_from, DateTime $date_time_to)
    {
        $this->date_time_from = $date_time_from;
        $this->date_time_to   = $date_time_to;
        $this->date_time      = null;
    }

    public function getResult(): array
    {
        if ($this->date_time) {
            $qb = $this->getQueryforSingle($this->date_time);

            return array($qb->getQuery()->getResult());
        } elseif ($this->date_time_to && $this->date_time_from) {
            $period = new DatePeriod($this->date_time_from, new DateInterval('P1D'), $this->date_time_to);
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

    private function getQueryforSingle(?DateTime $date_time = null): QueryBuilder
    {
        $em = $this->repository->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select("rule,actions,periods,calendarSelection,calendarSelectionCalendars")
           ->from(Rule::class, "rule")
           ->distinct(true)
           ->leftJoin('rule.calendar_selection', 'calendarSelection')
           ->leftJoin("calendarSelection.calendars", "calendarSelectionCalendars")
           ->leftJoin("rule.actions", "actions")
           ->leftJoin("rule.periods", "periods");

        $where = $qb->expr()->andX();

        if ($this->calendar_id) {
            $qb->setParameter("calendarId", $this->calendar_id);

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
            $subq_all_but_ids->setParameter("calendarId", $this->calendar_id);
            $subq_all_but_ids
                ->select("subCalendarSelection")
                ->from(CalendarSelection::class, "subCalendarSelection")
                ->leftJoin("subCalendarSelection.calendars", "subCalendarSeletcionCalendars")
                ->where("subCalendarSeletcionCalendars.id = :calendarId");

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

        if ($this->action_types) {
            $qb->setParameter("action_type", $this->action_types);
            $where->add("actions.action_type IN (:action_type)");
        }

        $qb->where($where);

        return $qb;
    }
}
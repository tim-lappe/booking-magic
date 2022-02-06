<?php


namespace TLBM\Calendar;

use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\CalendarQuery;

if ( !defined('ABSPATH')) {
    return;
}

class CalendarSelectionHandler implements CalendarSelectionHandlerInterface
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @param CalendarSelection $calendar_selection
     * @param int $calendar_id
     *
     * @return bool
     */
    public function containsCalendar(CalendarSelection $calendar_selection, int $calendar_id): bool
    {
        if ($calendar_selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return true;
        } elseif ($calendar_selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            return in_array($calendar_id, $calendar_selection->getCalendarIds());
        } elseif ($calendar_selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            return !in_array($calendar_id, $calendar_selection->getCalendarIds());
        }

        return false;
    }

    /**
     * @param CalendarSelection $calendarSelection
     *
     * @return array|Calendar[]
     */
    public function getSelectedCalendarList(CalendarSelection $calendarSelection): array
    {
        $calendarQuery = MainFactory::create(CalendarQuery::class);
        $allCalendars = iterator_to_array($calendarQuery->getResult());

        if ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return $allCalendars;
        } elseif ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            $list = array();
            foreach ($calendarSelection->getCalendarIds() as $id) {
                $cal    = $this->entityRepository->getEntity(Calendar::class, $id);
                $list[] = $cal;
            }

            return $list;
        } elseif ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            $allcals = $allCalendars;
            $list    = array();
            foreach ($allcals as $cal) {
                if ( !in_array($cal->getId(), $calendarSelection->getCalendarIds())) {
                    $list[] = $cal;
                }
            }

            return $list;
        }

        return array();
    }
}
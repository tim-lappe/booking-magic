<?php


namespace TLBM\Calendar;

use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;

if ( !defined('ABSPATH')) {
    return;
}

class CalendarSelectionHandler implements CalendarSelectionHandlerInterface
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    public function __construct(CalendarRepositoryInterface $calendarManager)
    {
        $this->calendarManager = $calendarManager;
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
        if ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return $this->calendarManager->getAllCalendars();
        } elseif ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            $list = array();
            foreach ($calendarSelection->getCalendarIds() as $id) {
                $cal    = $this->calendarManager->getCalendar($id);
                $list[] = $cal;
            }

            return $list;
        } elseif ($calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            $allcals = $this->calendarManager->getAllCalendars();
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
<?php


namespace TLBM\Calendar;

use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;

if ( ! defined('ABSPATH')) {
    return;
}

class CalendarSelectionHandler implements CalendarSelectionHandlerInterface
{

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct(CalendarManagerInterface $calendarManager)
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
        if ($calendar_selection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return true;
        } elseif ($calendar_selection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            return in_array($calendar_id, $calendar_selection->GetCalendarIds());
        } elseif ($calendar_selection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            return ! in_array($calendar_id, $calendar_selection->GetCalendarIds());
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
        if ($calendarSelection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            return $this->calendarManager->getAllCalendars();
        } elseif ($calendarSelection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            $list = array();
            foreach ($calendarSelection->GetCalendarIds() as $id) {
                $cal    = $this->calendarManager->getCalendar($id);
                $list[] = $cal;
            }

            return $list;
        } elseif ($calendarSelection->GetSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            $allcals = $this->calendarManager->getAllCalendars();
            $list    = array();
            foreach ($allcals as $cal) {
                if ( ! in_array($cal->GetId(), $calendarSelection->GetCalendarIds())) {
                    $list[] = $cal;
                }
            }

            return $list;
        }

        return array();
    }
}
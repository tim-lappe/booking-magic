<?php

namespace TLBM\Admin\Tables\DisplayHelper;

use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\Entity\CalendarSelection;

class DisplayCalendarSelection
{
    /**
     * @var CalendarSelection
     */
    private CalendarSelection $calendarSelection;

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarRepository;

    /**
     * @param CalendarRepositoryInterface $calendarRepository
     */
    public function __construct(CalendarRepositoryInterface $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    public function display() {
        if ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            echo __("All", TLBM_TEXT_DOMAIN);
        } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            foreach ($this->calendarSelection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarRepository->getCalendar($id);
                $link = get_edit_post_link($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'>" . $cal->getTitle() . "</a>";
            }
        } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach ($this->calendarSelection->getCalendarIds() as $key => $id) {
                $cal  = $this->calendarRepository->getCalendar($id);
                $link = get_edit_post_link($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<a href='" . $link . "'><s>" . $cal->getTitle() . "</s></a>";
            }
        }
    }

    /**
     * @return CalendarSelection
     */
    public function getCalendarSelection(): CalendarSelection
    {
        return $this->calendarSelection;
    }

    /**
     * @param CalendarSelection $calendarSelection
     */
    public function setCalendarSelection(CalendarSelection $calendarSelection): void
    {
        $this->calendarSelection = $calendarSelection;
    }
}
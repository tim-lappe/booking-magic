<?php

namespace TLBM\Admin\FormEditor;

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\CalendarQuery;

class CalendarElementSettingHelper
{

    /**
     * @var string
     */
    private string $selectedCalendarSetting;

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @return CalendarGroup|Calendar|null
     */
    public function getSelected()
    {
        if(strpos($this->selectedCalendarSetting, "group_") !== false) {
            $groupId = intval(str_replace("group_", "", $this->selectedCalendarSetting));
            $group = $this->entityRepository->getEntity(CalendarGroup::class, $groupId);
            if($group != null) {
                return $group;
            }
        }

        if(strpos($this->selectedCalendarSetting, "calendar_") !== false) {
            $calendarId = intval(str_replace("calendar_", "", $this->selectedCalendarSetting));
            $calendar = $this->entityRepository->getEntity(Calendar::class, $calendarId);
            if($calendar != null) {
                return $calendar;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getSelectedCalendarSetting(): string
    {
        return $this->selectedCalendarSetting;
    }

    /**
     * @param string $selectedCalendarSetting
     */
    public function setSelectedCalendarSetting(string $selectedCalendarSetting): void
    {
        $this->selectedCalendarSetting = $selectedCalendarSetting;
    }
}
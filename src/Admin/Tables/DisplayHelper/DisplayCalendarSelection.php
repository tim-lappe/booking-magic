<?php

namespace TLBM\Admin\Tables\DisplayHelper;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

class DisplayCalendarSelection
{
    /**
     * @var ?CalendarSelection
     */
    private ?CalendarSelection $calendarSelection;

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param AdminPageManagerInterface $adminPageManager
     * @param LocalizationInterface $localization
     */
    public function __construct(EntityRepositoryInterface $entityRepository, AdminPageManagerInterface $adminPageManager, LocalizationInterface $localization)
    {
        $this->adminPageManager = $adminPageManager;
        $this->entityRepository = $entityRepository;
        $this->localization = $localization;
    }

    public function display() {
        $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);


        if($this->calendarSelection) {
            if ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
                echo $this->localization->__("All", TLBM_TEXT_DOMAIN);
            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
                foreach ($this->calendarSelection->getCalendarIds() as $key => $id) {
                    $cal  = $this->entityRepository->getEntity(Calendar::class, $id);
                    $link = $calendarEditPage->getEditLink($id);
                    if ($key > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $link . "'>" . $cal->getTitle() . "</a>";
                }
            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
                echo $this->localization->__("All but ", TLBM_TEXT_DOMAIN);
                foreach ($this->calendarSelection->getCalendarIds() as $key => $id) {
                    $cal  = $this->entityRepository->getEntity(Calendar::class, $id);
                    $link = $calendarEditPage->getEditLink($id);
                    if ($key > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $link . "'><s>" . $cal->getTitle() . "</s></a>";
                }
            }
        } else {
            echo $this->localization->__("All", TLBM_TEXT_DOMAIN);
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
     * @param ?CalendarSelection $calendarSelection
     */
    public function setCalendarSelection(?CalendarSelection $calendarSelection): void
    {
        $this->calendarSelection = $calendarSelection;
    }
}
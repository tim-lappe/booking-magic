<?php

namespace TLBM\Admin\Tables\DisplayHelper;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\CalendarEditPage;
use TLBM\Admin\Pages\SinglePages\CalendarGroupEditPage;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
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
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param EntityRepositoryInterface $entityRepository
	 * @param AdminPageManagerInterface $adminPageManager
	 * @param LocalizationInterface $localization
	 */
    public function __construct(EscapingInterface $escaping, EntityRepositoryInterface $entityRepository, AdminPageManagerInterface $adminPageManager, LocalizationInterface $localization)
    {
		$this->escaping = $escaping;
        $this->adminPageManager = $adminPageManager;
        $this->entityRepository = $entityRepository;
        $this->localization = $localization;
    }

    public function display() {
        $calendarEditPage = $this->adminPageManager->getPage(CalendarEditPage::class);
        $groupEditPage = $this->adminPageManager->getPage(CalendarGroupEditPage::class);

        if($this->calendarSelection) {
            if ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
                $this->localization->echoText("All", TLBM_TEXT_DOMAIN);
            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
                $c = 0;
                foreach ($this->calendarSelection->getCalendars() as $key => $calendar) {
                    $link = $calendarEditPage->getEditLink($calendar->getId());
                    if ($c > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $this->escaping->escUrl($link) . "'>" . $this->escaping->escHtml($calendar->getTitle()) . "</a>";
                    $c++;
                }
                foreach ($this->calendarSelection->getCategories() as $key => $group) {
                    $link = $groupEditPage->getEditLink($group->getId());
                    if ($c > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $this->escaping->escUrl($link) . "'>" . $this->escaping->escHtml($group->getTitle()) . "</a>";
                    $c++;
                }

            } elseif ($this->calendarSelection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
				$this->localization->echoText("All but ", TLBM_TEXT_DOMAIN);
                $c = 0;
                foreach ($this->calendarSelection->getCalendars() as $calendar) {
                    $link = $calendarEditPage->getEditLink($calendar->getId());
                    if ($c > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $this->escaping->escUrl($link) . "'><s>" . $this->escaping->escHtml($calendar->getTitle()) . "</s></a>";
                    $c++;
                }
                foreach ($this->calendarSelection->getCategories() as $group) {
                    $link = $groupEditPage->getEditLink($group->getId());
                    if ($c > 0) {
                        echo ", ";
                    }

                    echo "<a href='" . $this->escaping->escUrl($link) . "'><s>" . $this->escaping->escHtml($group->getTitle()) . "</s></a>";
                    $c++;
                }
            }
        } else {
			$this->localization->echoText("All", TLBM_TEXT_DOMAIN);
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
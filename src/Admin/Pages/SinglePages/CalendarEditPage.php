<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use Psr\Container\ContainerInterface;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;

class CalendarEditPage extends FormPageBase
{

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct(AdminPageManagerInterface $adminPageManager, FormBuilderInterface $formBuilder, CalendarManagerInterface $calendarManager)
    {
        $this->calendarManager = $calendarManager;
        parent::__construct($adminPageManager, $formBuilder, "calendar-edit", "booking-calendar-edit", false);
    }

    public function showFormPageContent()
    {
        $calendar = null;
        if (isset($_REQUEST['calendar_id'])) {
            $calendar = $this->calendarManager->getCalendar($_REQUEST['calendar_id']);
            ?>
            <input type="hidden" name="calendar_id" value="<?php
            echo $calendar->GetId() ?>">
            <?php
        } else {
            $calendar = new Calendar();
        }
        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php
            echo $calendar->GetTitle() ?>" placeholder="<?php
            _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>

        <div class="tlbm-admin-page-tile">
            <?php
            echo CalendarOutput::GetCalendarContainerShell(
                $calendar->GetId(),
                time(),
                "month",
                new MonthViewSetting(),
                "calendar",
                true
            ); ?>
        </div>

        <?php
    }

    public function onSave($vars): array
    {
        $calendar = new Calendar();
        if (isset($_REQUEST['calendar_id'])) {
            $calendar = $this->calendarManager->getCalendar($vars['calendar_id']);
        }

        $calendar->SetTitle($vars['title']);
        $calendar->SetTimestampCreated(time());

        if (strlen($calendar->GetTitle()) < 3) {
            return array(
                "error" => __("Error: the title of the calendar is too short.")
            );
        }

        try {
            $this->calendarManager->saveCalendar($calendar);
        } catch (Exception $e) {
        }

        return array();
    }

    protected function getHeadTitle(): string
    {
        return $this->getEditingCalendar() == null ? __("Add New Calendar", TLBM_TEXT_DOMAIN) : __(
            "Edit Calendar",
            TLBM_TEXT_DOMAIN
        );
    }

    private function getEditingCalendar(): ?Calendar
    {
        $calendar = null;
        if (isset($_REQUEST['calendar_id'])) {
            $calendar = $this->calendarManager->getCalendar($_REQUEST['calendar_id']);
        }

        return $calendar;
    }
}
<?php

namespace TLBM\Admin\Pages\SinglePages;

use DI\DependencyException;
use DI\FactoryInterface;
use DI\NotFoundException;
use Exception;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Validation\ValidatorFactory;

class CalendarEditPage extends FormPageBase
{

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var Calendar|null
     */
    private ?Calendar $editingCalendar = null;

    public function __construct(CalendarManagerInterface $calendarManager)
    {
        $this->calendarManager = $calendarManager;
        parent::__construct("calendar-edit", "booking-calendar-edit", false);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function showFormPageContent()
    {
        $calendar = $this->getEditingCalendar();
        if ($calendar) {
            ?>
            <input type="hidden" name="calendar_id" value="<?php echo $calendar->getId() ?>">
            <?php
        } else {
            $calendar = new Calendar();
        }
        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php
            echo $calendar->getTitle() ?>" placeholder="<?php
            _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title"
                   class="tlbm-admin-form-input-title">
        </div>

        <div class="tlbm-admin-page-tile">
            <?php
            echo CalendarOutput::GetCalendarContainerShell(
                $calendar->getId(), null, "month", MainFactory::create(MonthViewSetting::class), "calendar", true
            ); ?>
        </div>

        <?php
    }

    /**
     * @param mixed $vars
     *
     * @return array
     */
    public function onSave($vars): array
    {
        $calendar = $this->getEditingCalendar();
        if (!$calendar) {
            $calendar = new Calendar();
        }

        $calendarValidator = ValidatorFactory::createCalendarValidator($calendar);
        $calendar->setTstampCreated(time());
        $calendar->setTitle($vars['title']);

        $validationResult = $calendarValidator->getValidationErrors();

        if(count($validationResult) == 0) {
            try {
                $this->calendarManager->saveCalendar($calendar);
                $this->editingCalendar = $calendar;
                return array(
                    "success" => __("Calendar has been saved", TLBM_TEXT_DOMAIN)
                );

            } catch (Exception $e) {
                return array(
                    "error" => __("An internal error occured: " . $e->getMessage(), TLBM_TEXT_DOMAIN)
                );
            }
        }

        return $validationResult;
    }

    /**
     * @param int $calendar_id
     *
     * @return string
     */
    public function getEditLink(int $calendar_id = -1): string
    {
        if ($calendar_id >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($this->menu_slug) . "&calendar_id=" . urlencode($calendar_id);
        }

        return admin_url() . "admin.php?page=" . urlencode($this->menu_slug);
    }

    protected function getHeadTitle(): string
    {
        return $this->getEditingCalendar() == null ? __("Add New Calendar", TLBM_TEXT_DOMAIN) : __(
            "Edit Calendar", TLBM_TEXT_DOMAIN
        );
    }

    private function getEditingCalendar(): ?Calendar
    {
        if($this->editingCalendar) {
            return $this->editingCalendar;
        }

        if (isset($_REQUEST['calendar_id'])) {
            return $this->calendarManager->getCalendar($_REQUEST['calendar_id']);
        }

        return null;
    }
}
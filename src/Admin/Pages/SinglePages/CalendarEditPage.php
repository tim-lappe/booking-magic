<?php

namespace TLBM\Admin\Pages\SinglePages;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use TLBM\Calendar\CalendarManager;
use TLBM\Entity\Calendar;
use TLBM\Model\CalendarGroup;
use TLBM\Output\Calendar\CalendarOutput;

class CalendarEditPage extends FormPageBase {

    public function __construct() {
        parent::__construct("calendar-edit", "booking-calendar-edit", false);
    }

    protected function GetHeadTitle(): string {
        return $this->GetEditingCalendar() == null ? __("Add New Calendar", TLBM_TEXT_DOMAIN) : __("Edit Calendar", TLBM_TEXT_DOMAIN);
    }

    private function GetEditingCalendar(): ?Calendar {
        $calendar = null;
        if(isset($_REQUEST['calendar_id'])) {
            $calendar = CalendarManager::GetCalendar($_REQUEST['calendar_id']);
        }
        return $calendar;
    }

    public function ShowFormPageContent() {
        $calendar = null;
        if(isset($_REQUEST['calendar_id'])) {
            $calendar = CalendarManager::GetCalendar($_REQUEST['calendar_id']);
            ?>
            <input type="hidden" name="calendar_id" value="<?php echo $calendar->GetId() ?>">
            <?php
        } else {
            $calendar = new Calendar();
        }
        ?>
        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $calendar->GetTitle() ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>

        <?php
    }

    public function OnSave($vars): array {
        $calendar = new Calendar();
        if(isset($_POST['calendar_id'])) {
            $calendar = CalendarManager::GetCalendar($_POST['calendar_id']);
        }

        $calendar->SetTitle($_POST['title']);
        $calendar->SetTimestampCreated(time());

        if(strlen($calendar->GetTitle()) < 3) {
            return array(
                "error" => __("Error: the title of the calendar is too short.")
            );
        }

        try {
            CalendarManager::SaveCalendar($calendar);
        } catch (Exception $e) { }

        return array();
    }
}
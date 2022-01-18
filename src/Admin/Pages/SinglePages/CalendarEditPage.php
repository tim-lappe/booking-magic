<?php

namespace TLBM\Admin\Pages\SinglePages;

use DateInterval;
use DateTime;
use Exception;
use TLBM\Calendar\CalendarManager;
use TLBM\Entity\Calendar;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\Output\Calendar\ViewSettings\MonthViewSetting;
use TLBM\Rules\ActionsReader;
use TLBM\Rules\RulesQuery;

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

        <div class="tlbm-admin-page-tile">

                <?php
                $query = new RulesQuery();
                $to = new DateTime();
                $to->add(new DateInterval("P20D"));
                $query->setDateTimeRange(new DateTime(), $to);

                $query->setTypeCalendar($calendar->GetId());
                $query->setActionTypes(array("date_slot", "message"));

                $action_reader = new ActionsReader($query);
                $actions = $action_reader->getRuleActionsMerged();

                echo "<pre>Results: " . count($actions) . "\n";
                echo json_encode($actions, JSON_PRETTY_PRINT);
                echo "</pre>";
                ?>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php echo CalendarOutput::GetCalendarContainerShell($calendar->GetId(), time(), "month", new MonthViewSetting(), "calendar", true); ?>
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
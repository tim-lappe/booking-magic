<?php

namespace TLBM\Admin\Pages\SinglePages;

use Doctrine\Common\Util\Debug;
use Exception;
use TLBM\Admin\Pages\PageManager;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Rules\RulesManager;

class RuleEditPage extends FormPageBase {

    public static function GetEditLink(int $id = -1): string {
        $page = PageManager::GetPageInstance(RuleEditPage::class);
        if($id >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($page->menu_slug) . "&rule_id=".urlencode($id);
        }
        return admin_url() . "admin.php?page=" . urlencode($page->menu_slug);
    }

    public function __construct() {
        parent::__construct("rule-edit", "booking-magic-rule-edit", false);

        $this->parent_slug = "booking-magic-rules";
    }

    public function GetHeadTitle(): string {
        return $this->GetEditingRule() == null ? __("Add New Rule", TLBM_TEXT_DOMAIN) : __("Edit Rule", TLBM_TEXT_DOMAIN);
    }

    private function GetEditingRule(): ?Rule {
        $rule = null;
        if(isset($_REQUEST['rule_id'])) {
            $rule = RulesManager::GetRule($_REQUEST['rule_id']);
        }
        return $rule;
    }

    public function ShowFormPageContent() {
        $rule = $this->GetEditingRule();
        $actions_val = $rule ? $rule->GetActions()->toArray() : array();
        $periods = $rule ? $rule->GetPeriods()->toArray() : array();
        $title = $rule ? $rule->GetTitle() : "";
        $selection = $rule ? $rule->GetCalendarSelection() : new CalendarSelection();

        ?>
            <div class="tlbm-admin-page-tile">
                <input value="<?php echo $title ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
            </div>
            <div class="tlbm-admin-page-tile">
                <?php
                $form_builder = new FormBuilder();
                $form_builder->PrintFormHead();
                $form_builder->PrintFormField(new CalendarPickerField("calendars",  __("Calendars", TLBM_TEXT_DOMAIN), $selection));
                $form_builder->PrintFormFooter();
                ?>
            </div>
            <div class="tlbm-admin-page-tile">
                <?php
                $form_builder->PrintFormHead();
                $form_builder->PrintFormField(new RuleActionsField("rule_actions",  __("Actions", TLBM_TEXT_DOMAIN), $actions_val));
                $form_builder->PrintFormFooter();
                ?>
            </div>
            <div class="tlbm-admin-page-tile">
                <?php
                $form_builder->PrintFormHead();
                $form_builder->PrintFormField(new PeriodEditorField("rule_periods", __("Periods", TLBM_TEXT_DOMAIN), $periods));
                $form_builder->PrintFormFooter();
                ?>
            </div>
        <?php
    }

    /**
     * @throws Exception
     */
    public function OnSave($vars): array {
        $rule = null;
        if(isset($_REQUEST['rule_id'])) {
            $rule = RulesManager::GetRule($_REQUEST['rule_id']);
        }

        if($rule == null) {
            $rule = new Rule();
        }

        $rule->SetTitle($vars['title']);

        $actions = RuleActionsField::ReadFromVars("rule_actions", $vars);
        $calendar_selection = CalendarPickerField::ReadFromVars("calendars", $vars);
        $periods = PeriodEditorField::ReadFromVars("rule_periods", $vars);

        $rule->ClearActions();
        foreach ($actions as $action) {
            $rule->AddAction($action);
        }

        $rule->ClearPeriods();
        foreach ($periods as $period) {
            $rule->AddPeriod($period);
        }

        $rule->SetCalendarSelection($calendar_selection);

        RulesManager::SaveRule($rule);

        return array();
    }
}
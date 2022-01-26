<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Rules\Contracts\RulesManagerInterface;

class RuleEditPage extends FormPageBase
{

    /**
     * @var RulesManagerInterface
     */
    private RulesManagerInterface $rulesManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;


    public function __construct(AdminPageManagerInterface $adminPageManager, FormBuilderInterface $formBuilder, RulesManagerInterface $rulesManager, CalendarManagerInterface $calendarManager)
    {
        parent::__construct($adminPageManager, $formBuilder, "rule-edit", "booking-magic-rule-edit", false);

        $this->rulesManager = $rulesManager;
        $this->calendarManager = $calendarManager;
        $this->parent_slug  = "booking-magic-rules";
    }

    public function getHeadTitle(): string
    {
        return $this->getEditingRule() == null ? __("Add New Rule", TLBM_TEXT_DOMAIN) : __(
            "Edit Rule",
            TLBM_TEXT_DOMAIN
        );
    }

    private function getEditingRule(): ?Rule
    {
        $rule = null;
        if (isset($_REQUEST['rule_id'])) {
            $rule = $this->rulesManager->getRule($_REQUEST['rule_id']);
        }

        return $rule;
    }

    public function getEditLink(int $id = -1): string
    {
        $page = $this->adminPageManager->getPage(RuleEditPage::class);
        if ($id >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($page->menu_slug) . "&rule_id=" . urlencode($id);
        }

        return admin_url() . "admin.php?page=" . urlencode($page->menu_slug);
    }

    public function showFormPageContent()
    {
        $rule        = $this->getEditingRule();
        $actions_val = $rule ? $rule->GetActions()->toArray() : array();
        $periods     = $rule ? $rule->GetPeriods()->toArray() : array();
        $title       = $rule ? $rule->GetTitle() : "";
        $selection   = $rule ? $rule->GetCalendarSelection() : new CalendarSelection();

        ?>
        <div class="tlbm-admin-page-tile">
            <input value="<?php
            echo $title ?>" placeholder="<?php
            _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField(
                new CalendarPickerField( $this->calendarManager, "calendars", __("Calendars", TLBM_TEXT_DOMAIN), $selection)
            );
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField (
                new RuleActionsField("rule_actions", __("Actions", TLBM_TEXT_DOMAIN), $actions_val)
            );
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField(
                new PeriodEditorField("rule_periods", __("Periods", TLBM_TEXT_DOMAIN), $periods)
            );
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <?php
    }

    /**
     * @throws Exception
     */
    public function onSave($vars): array
    {
        $rule = null;
        if (isset($_REQUEST['rule_id'])) {
            $rule = $this->rulesManager->getRule($_REQUEST['rule_id']);
        }

        if ($rule == null) {
            $rule = new Rule();
        }

        $rule->SetTitle($vars['title']);

        $actions            = $this->formBuilder->readVars("rule_actions", $vars);
        $calendar_selection = $this->formBuilder->readVars("calendars", $vars);
        $periods            = $this->formBuilder->readVars("rule_periods", $vars);

        $rule->ClearActions();
        foreach ($actions as $action) {
            $rule->AddAction($action);
        }

        $rule->ClearPeriods();
        foreach ($periods as $period) {
            $rule->AddPeriod($period);
        }

        $rule->SetCalendarSelection($calendar_selection);
        $this->rulesManager->saveRule($rule);

        return array();
    }
}
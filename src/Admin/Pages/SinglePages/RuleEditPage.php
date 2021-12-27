<?php

namespace TLBM\Admin\Pages\SinglePages;

use Doctrine\Common\Util\Debug;
use Exception;
use TLBM\Admin\Pages\PageManager;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Entity\Rule;
use TLBM\Rules\RulesManager;

class RuleEditPage extends FormPageBase {


    private RuleActionsField $ruleActionsField;

    private ?Rule $editingRule;

    public static function GetEditLink(int $id = -1): string {
        $page = PageManager::GetPageInstance(RuleEditPage::class);
        if($id >= 0) {
            return admin_url() . "?page=" . urlencode($page->menu_slug) . "&rule_id=".urlencode($id);
        }
        return admin_url() . "?page=" . urlencode($page->menu_slug);
    }

    public function __construct() {
        parent::__construct("rule-edit", "booking-calendar-rule-edit", false);

        $this->editingRule = $this->GetEditingRule();

        $value_arr = array();
        if($this->editingRule) {
            $value_arr = $this->editingRule->GetActions()->toArray();
        }

        $this->ruleActionsField = new RuleActionsField("rule_actions",  __("Actions", TLBM_TEXT_DOMAIN), $value_arr);
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
        ?>
        <div class="tlbm-admin-page-tile">
        <?php

        $rule = self::GetEditingRule();

        $form_builder = new FormBuilder();
        $form_builder->PrintFormHead();
        $form_builder->PrintFormField($this->ruleActionsField);
        $form_builder->PrintFormFooter();
        ?>
        </div>
        <?php
    }

    /**
     * @throws Exception
     */
    public function OnSave($vars): array {

        $rule = new Rule();
        $actions = $this->ruleActionsField->ReadFromVars($vars);
        $rule->ClearActions();
        foreach ($actions as $action) {
            $rule->AddAction($action);
        }

        RulesManager::SaveRule($rule);

        return array();
    }
}
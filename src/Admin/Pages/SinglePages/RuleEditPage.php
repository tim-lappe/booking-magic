<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Calendar\CalendarManager;
use TLBM\Entity\Rule;
use TLBM\Rules\RulesManager;

class RuleEditPage extends FormPageBase {


    public function __construct() {
        parent::__construct("rule-edit", "booking-calendar-rule-edit", false);
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
        $form_builder = new FormBuilder();
        $form_builder->PrintFormHead();
        $form_builder->PrintFormField(new RuleActionsField("actions",  __("Actions", TLBM_TEXT_DOMAIN), null));
        $form_builder->PrintFormFooter();
        ?>
        </div>
        <?php
    }

    public function OnSave($vars): array {
        return array();
    }
}
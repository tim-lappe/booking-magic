<?php

namespace TLBM\Admin\Pages\SinglePages;

use DI\FactoryInterface;
use Exception;
use Throwable;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\InputField;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Admin\WpForm\SelectField;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarSelection;
use TLBM\Entity\Rule;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Validation\ValidatorFactory;

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

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var Rule|null
     */
    private ?Rule $editingRule = null;

    public function __construct(
        RulesManagerInterface $rulesManager,
        SettingsManagerInterface $settingsManager,
        CalendarManagerInterface $calendarManager
    ) {
        parent::__construct("rule-edit", "booking-magic-rule-edit", false);

        $this->rulesManager    = $rulesManager;
        $this->settingsManager = $settingsManager;
        $this->calendarManager = $calendarManager;
        $this->parent_slug     = "booking-magic-rules";

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new CalendarPickerField($this->calendarManager, "calendars", __("Calendars", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new RuleActionsField("rule_actions", __("Actions", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new PeriodEditorField("rule_periods", __("Periods", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new SelectField("rule_priority", __("Priority", TLBM_TEXT_DOMAIN), $this->settingsManager->getValue(PriorityLevels::class))
        );
    }

    public function getHeadTitle(): string
    {
        return $this->getEditingRule() == null ? __("Add New Rule", TLBM_TEXT_DOMAIN) : __(
            "Edit Rule", TLBM_TEXT_DOMAIN
        );
    }

    /**
     * @return Rule|null
     */
    private function getEditingRule(): ?Rule
    {
        if($this->editingRule) {
            return $this->editingRule;
        }

        $rule = null;
        if (isset($_REQUEST['rule_id'])) {
            $rule = $this->rulesManager->getRule($_REQUEST['rule_id']);
        }

        return $rule;
    }

    /**
     * @param int $ruleId
     *
     * @return string
     */
    public function getEditLink(int $ruleId = -1): string
    {
        $page = $this->adminPageManager->getPage(RuleEditPage::class);
        if ($ruleId >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($page->menu_slug) . "&rule_id=" . urlencode($ruleId);
        }

        return admin_url() . "admin.php?page=" . urlencode($page->menu_slug);
    }

    public function showFormPageContent()
    {
        $rule = $this->getEditingRule();
        if ($rule) {
            ?>
            <input type="hidden" name="rule_id" value="<?php echo $rule->getId() ?>">
            <?php
        } else {
            $rule = new Rule();
        }
        ?>

        <div class="tlbm-admin-page-tile">
            <input value="<?php echo $rule->getTitle() ?>" placeholder="<?php _e("Enter Title here", TLBM_TEXT_DOMAIN) ?>" type="text" name="title" class="tlbm-admin-form-input-title">
        </div>
        <div class="tlbm-admin-page-tile-row">
            <div class="tlbm-admin-page-tile">
                <?php
                $this->formBuilder->displayFormHead();
                $this->formBuilder->displayFormField("calendars", $rule->getCalendarSelection());
                $this->formBuilder->displayFormFooter();
                ?>
            </div>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("rule_priority", $rule->getPriority());
            $this->formBuilder->displayFormField("rule_actions", $rule->getActions()->toArray());
            $this->formBuilder->displayFormFooter();
            ?>
        </div>
        <div class="tlbm-admin-page-tile">
            <?php
            $this->formBuilder->displayFormHead();
            $this->formBuilder->displayFormField("rule_periods", $rule->getPeriods()->toArray());
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
        $rule = $this->getEditingRule();
        if ($rule == null) {
            $rule = new Rule();
        }

        $rule->setTitle($vars['title']);
        $rule->setPriority($vars['rule_priority']);

        $actions            = $this->formBuilder->readVars("rule_actions", $vars);
        $calendar_selection = $this->formBuilder->readVars("calendars", $vars);
        $periods            = $this->formBuilder->readVars("rule_periods", $vars);

        $rule->clearActions();
        foreach ($actions as $action) {
            $rule->addAction($action);
        }

        $rule->clearPeriods();
        foreach ($periods as $period) {
            $rule->addPeriod($period);
        }

        $rule->setCalendarSelection($calendar_selection);

        $rulesValidator = ValidatorFactory::createRuleValidator($rule);
        $validationResult = $rulesValidator->getValidationErrors();

        if(count($validationResult) == 0) {
            try {
                $this->rulesManager->saveRule($rule);
                $this->editingRule = $rule;
            } catch (Throwable $exception) {
                return array(
                    "error" => __("An internal error occured: " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
                );
            }
        } else {
            return $validationResult;
        }

        return array(
            "success" => __("Rule has been saved", TLBM_TEXT_DOMAIN)
        );
    }
}
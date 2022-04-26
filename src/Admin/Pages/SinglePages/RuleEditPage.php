<?php

namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use Throwable;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Rules\PriorityLevels;
use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Admin\WpForm\SelectField;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\ManageableEntity;
use TLBM\Entity\Rule;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Validation\ValidatorFactory;

/**
 * @extends EntityEditPage<Rule>
 */
class RuleEditPage extends EntityEditPage
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(
        EntityRepositoryInterface $entityRepository,
        SettingsManagerInterface $settingsManager,
        LocalizationInterface $localization
    ) {
        parent::__construct($localization->getText("Rule", TLBM_TEXT_DOMAIN), "rule-edit", "booking-magic-rule-edit", false);

        $this->localization = $localization;
        $this->entityRepository    = $entityRepository;
        $this->settingsManager = $settingsManager;
        $this->parentSlug     = "booking-magic-rules";

        $this->defineFormFields();
    }

    public function defineFormFields()
    {
        $this->formBuilder->defineFormField(
            new CalendarPickerField($this->entityRepository, "calendars", $this->localization->getText("Calendars", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new RuleActionsField("rule_actions", $this->localization->getText("Actions", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new PeriodEditorField("rule_periods", $this->localization->getText("Periods", TLBM_TEXT_DOMAIN))
        );
        $this->formBuilder->defineFormField(
            new SelectField("rule_priority", $this->localization->getText("Priority", TLBM_TEXT_DOMAIN), $this->settingsManager->getValue(PriorityLevels::class))
        );
    }

    protected function displayEntityEditForm(): void
    {
        $rule = $this->getEditingEntity();
        if (!$rule) {
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
    protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        $rule = $this->getEditingEntity();
        if ($rule == null) {
            $rule = new Rule();
        }

        $rule->setTitle($this->sanitizing->sanitizeTitle($vars['title']));
        $rule->setPriority($this->sanitizing->sanitizeKey($vars['rule_priority']));

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
                $this->entityRepository->saveEntity($rule);
                $savedEntity = $rule;
            } catch (Throwable $exception) {
                return ["error" => $this->localization->getText("An internal error occured: " . $exception->getMessage(), TLBM_TEXT_DOMAIN)
                ];
            }
        } else {
            return $validationResult;
        }

        return ["success" => $this->localization->getText("Rule has been saved", TLBM_TEXT_DOMAIN)
        ];
    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->entityRepository->getEntity(Rule::class, $id);
    }
}
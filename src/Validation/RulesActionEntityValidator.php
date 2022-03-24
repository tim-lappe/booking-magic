<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\RuleAction;
use TLBM\Localization\Contracts\LabelsInterface;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Validation\Contracts\RulesActionEntityValidatorInterface;

class RulesActionEntityValidator implements RulesActionEntityValidatorInterface
{

    /**
     * @var RuleAction
     */
    private RuleAction $ruleAction;

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    /**
     * @var LabelsInterface
     */
    private LabelsInterface $labels;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @param RuleActionsManagerInterface $ruleActionsManager
     * @param LabelsInterface $labels
     * @param LocalizationInterface $localization
     * @param RuleAction $ruleAction
     */
    public function __construct(RuleActionsManagerInterface $ruleActionsManager, LabelsInterface $labels, LocalizationInterface $localization, RuleAction $ruleAction)
    {
        $this->ruleAction = $ruleAction;
        $this->ruleActionsManager = $ruleActionsManager;
        $this->labels = $labels;
        $this->localization = $localization;
    }

    /**
     * @return array
     */
    public function validateType(): array
    {
        $errors = array();

        $type = $this->ruleAction->getActionType();
        $allTypes = array_keys($this->ruleActionsManager->getAllActionsHandlerClasses());

        if(!in_array($type, $allTypes)) {
            $errors[] = sprintf($this->localization->getText("Unknown action type: %s", TLBM_TEXT_DOMAIN), $type);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function validateHour(): array
    {
        $errors = array();
        $hour = $this->ruleAction->getTimeHour();
        if(!($hour >= 0 && $hour < 24)) {
            $errors[] = sprintf($this->localization->getText("Invalid time hour: %s", TLBM_TEXT_DOMAIN), $hour);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function validateMinute(): array
    {
        $errors = array();
        $minute = $this->ruleAction->getTimeMin();

        if(!($minute >= 0 && $minute < 60)) {
            $errors[] = sprintf($this->localization->getText("Invalid time minute: %s", TLBM_TEXT_DOMAIN), $minute);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function validateWeekdays(): array
    {
        $errors = array();
        $weekdays = $this->ruleAction->getWeekdays();
        $weekdayKeys = array_keys($this->labels->getWeekdayLabels());
        $weekdayRangeKeys = array_keys($this->labels->getWeekdayRangeLabels());

        if(!in_array($weekdays, array_merge($weekdayRangeKeys, $weekdayKeys))) {
            $errors[] = sprintf($this->localization->getText("Unknown weekday definition: %s", TLBM_TEXT_DOMAIN), $weekdays);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->validateType(),
            $this->validateHour(),
            $this->validateMinute(),
            $this->validateWeekdays()
        );
    }
}
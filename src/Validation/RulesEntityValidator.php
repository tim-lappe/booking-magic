<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Entity\RulePeriod;
use TLBM\MainFactory;
use TLBM\Validation\Contracts\RulesEntityValidatorInterface;

class RulesEntityValidator implements RulesEntityValidatorInterface
{

    /**
     * @var Rule
     */
    private Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return array
     */
    public function isTitleValid(): array
    {
        $errors = array();
        $localization = MainFactory::get(LocalizationInterface::class);
        if(empty($this->rule->getTitle())) {
            $errors[] = $localization->getText("The title is too short", TLBM_TEXT_DOMAIN);
        }

        return $errors;
    }

    public function areActionsValid(): array
    {
        $errors = array();

        /**
         * @var RuleAction $action
         */
        foreach($this->rule->getActions() as $action) {
            $validator = ValidatorFactory::createRuleActionValidator($action);
            $errors = array_merge($errors, $validator->getValidationErrors());
        }

        return $errors;
    }

    public function arePeriodsValid(): array
    {
        $errors = array();

        /**
         * @var RulePeriod $period
         */
        foreach($this->rule->getPeriods() as $period) {
            $validator = ValidatorFactory::createRulePeriodValidator($period);
            $errors = array_merge($errors, $validator->getValidationErrors());
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->isTitleValid(),
            $this->areActionsValid(),
            $this->arePeriodsValid()
        );
    }
}
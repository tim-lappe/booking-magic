<?php

namespace TLBM\Validation;

use TLBM\Entity\RulePeriod;
use TLBM\Validation\Contracts\RulesPeriodEntityValidatorInterface;

class RulesPeriodEntityValidator implements RulesPeriodEntityValidatorInterface
{

    /**
     * @var RulePeriod
     */
    private RulePeriod $rulePeriod;

    /**
     * @param RulePeriod $rulePeriod
     */
    public function __construct(RulePeriod $rulePeriod)
    {
        $this->rulePeriod = $rulePeriod;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return [];
    }
}
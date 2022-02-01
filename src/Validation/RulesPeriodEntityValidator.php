<?php

namespace TLBM\Validation;

use TLBM\Entity\RulePeriod;
use TLBM\Entity\TimeSlot;
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
    public function validateTimeSlots(): array
    {
        $errors = array();

        /**
         * @var TimeSlot $dailyTimeRange
         */
        foreach ($this->rulePeriod->getDailyTimeRanges() as $dailyTimeRange) {
            $validator = ValidatorFactory::createTimeSlotValidator($dailyTimeRange);
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
            $this->validateTimeSlots()
        );
    }
}
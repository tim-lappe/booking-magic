<?php

namespace TLBM\Validation\Contracts;

interface RulesPeriodEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function validateTstamps(): array;

    /**
     * @return array
     */
    public function validateTimeSlots(): array;
}
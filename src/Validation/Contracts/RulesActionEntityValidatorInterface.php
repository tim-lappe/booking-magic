<?php

namespace TLBM\Validation\Contracts;

interface RulesActionEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function validateType(): array;

    /**
     * @return array
     */
    public function validateHour(): array;

    /**
     * @return array
     */
    public function validateMinute(): array;

    /**
     * @return array
     */
    public function validateWeekdays(): array;

}
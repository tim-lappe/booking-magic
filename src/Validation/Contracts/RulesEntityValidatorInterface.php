<?php

namespace TLBM\Validation\Contracts;

interface RulesEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function isTitleValid(): array;

    /**
     * @return array
     */
    public function areActionsValid(): array;

    /**
     * @return array
     */
    public function arePeriodsValid(): array;
}
<?php

namespace TLBM\Validation\Contracts;

interface CalendarEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function isTitleValid(): array;
}
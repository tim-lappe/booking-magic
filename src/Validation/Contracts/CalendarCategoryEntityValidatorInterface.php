<?php

namespace TLBM\Validation\Contracts;

interface CalendarCategoryEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function isTitleValid(): array;
}
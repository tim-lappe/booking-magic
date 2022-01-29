<?php

namespace TLBM\Validation\Contracts;

interface TimeSlotEntityValidatorInterface extends ValidatorInterface
{
    /**
     * @return array
     */
    public function validateFrom(): array;

    /**
     * @return array
     */
    public function validateTo(): array;

    /**
     * @return array
     */
    public function getValidationErrors(): array;
}
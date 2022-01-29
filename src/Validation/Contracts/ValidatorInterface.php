<?php

namespace TLBM\Validation\Contracts;

interface ValidatorInterface
{

    /**
     * @return array
     */
    public function getValidationErrors(): array;
}
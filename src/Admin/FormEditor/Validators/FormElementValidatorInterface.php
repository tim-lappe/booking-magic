<?php

namespace TLBM\Admin\FormEditor\Validators;

use TLBM\Admin\FormEditor\LinkedFormData;

interface FormElementValidatorInterface
{
    /**
     * @param LinkedFormData $linkedFormData
     *
     * @return bool
     */
    public function validate(LinkedFormData $linkedFormData): bool;
}
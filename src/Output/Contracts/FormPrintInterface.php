<?php

namespace TLBM\Output\Contracts;

interface FormPrintInterface
{

    /**
     * @param int $formId
     * @param mixed $inputVars
     *
     * @return string
     */
    public function printForm(int $formId, $inputVars = null): string;
}
<?php

namespace TLBM\Admin\WpForm\Contracts;

interface FormFieldReadVarsInterface
{
    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return mixed
     */
    public function readFromVars(string $name, $vars);
}
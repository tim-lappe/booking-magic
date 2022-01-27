<?php

namespace TLBM\Ajax\Contracts;

interface AjaxManagerInterface
{
    /**
     * @param AjaxFunctionInterface $ajaxFunction
     *
     * @return bool
     */
    public function registerAjaxFunction(AjaxFunctionInterface $ajaxFunction): bool;

    /**
     * @return AjaxFunctionInterface[]
     */
    public function getAllAjaxFunctions(): array;

    /**
     * @param AjaxFunctionInterface $ajaxFunction
     *
     * @return void
     */
    public function executeAjaxFunction(AjaxFunctionInterface $ajaxFunction);
}
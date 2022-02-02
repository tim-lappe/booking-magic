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
     * @param string $action
     *
     * @return AjaxFunctionInterface|null
     */
    public function getAjaxFunction(string $action): ?AjaxFunctionInterface;

    /**
     * @return mixed
     */
    public function initMainAjaxFunction();
}
<?php

namespace TLBM\ApiUtils\Contracts;

interface HooksInterface
{
    /**
     * @param string $action
     * @param callable $callable
     *
     * @return mixed
     */
    public function addAction(string $action, callable $callable);
}
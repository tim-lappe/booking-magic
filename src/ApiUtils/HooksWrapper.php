<?php

namespace TLBM\ApiUtils;

class HooksWrapper implements Contracts\HooksInterface
{

    /**
     * @inheritDoc
     */
    public function addAction(string $action, callable $callable)
    {
        add_action($action, $callable);
    }
}
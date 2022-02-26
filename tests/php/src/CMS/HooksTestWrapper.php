<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\HooksInterface;

class HooksTestWrapper implements HooksInterface
{

    /**
     * @inheritDoc
     */
    public function addAction(string $action, callable $callable)
    {
        $callable();
    }
}
<?php

namespace TLBMTEST\CMS;

use TLBM\ApiUtils\Contracts\ShortcodeInterface;

class ShortcodeTestWrapper implements ShortcodeInterface
{

    /**
     * @inheritDoc
     */
    public function addShortcode(string $tag, callable $callback)
    {

    }
}
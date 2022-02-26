<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\ShortcodeInterface;

class ShortcodeTestWrapper implements ShortcodeInterface
{

    /**
     * @inheritDoc
     */
    public function addShortcode(string $tag, callable $callback)
    {

    }
}
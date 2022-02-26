<?php

namespace TLBM\CMS;

class ShortcodeWrapper implements Contracts\ShortcodeInterface
{

    /**
     * @inheritDoc
     */
    public function addShortcode(string $tag, callable $callback)
    {
        add_shortcode($tag, $callback);
    }
}
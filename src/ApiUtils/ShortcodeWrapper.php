<?php

namespace TLBM\ApiUtils;

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
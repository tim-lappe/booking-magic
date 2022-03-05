<?php

namespace TLBM\ApiUtils\Contracts;

interface ShortcodeInterface
{
    /**
     * @param string $tag
     * @param callable $callback
     *
     * @return mixed
     */
    public function addShortcode(string $tag, callable $callback);
}
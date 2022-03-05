<?php

namespace TLBM\ApiUtils;

class LocalizationWrapper implements Contracts\LocalizationInterface
{

    /**
     * @inheritDoc
     */
    public function __(string $text, string $namespace): string
    {
        return __($text, $namespace);
    }

    /**
     * @inheritDoc
     */
    public function _e(string $text, string $namespace)
    {
        _e($text, $namespace);
    }
}
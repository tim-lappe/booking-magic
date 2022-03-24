<?php

namespace TLBM\ApiUtils;

class LocalizationWrapper implements Contracts\LocalizationInterface
{

    /**
     * @inheritDoc
     */
    public function getText(string $text, string $namespace): string
    {
        return __($text, $namespace);
    }

    /**
     * @inheritDoc
     */
    public function echoText(string $text, string $namespace): void
    {
        _e($text, $namespace);
    }
}
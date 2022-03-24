<?php

namespace TLBMTEST\CMS;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

class LocalizationTestWrapper implements LocalizationInterface
{

    /**
     * @inheritDoc
     */
    public function getText(string $text, string $namespace): string
    {
        return $text;
    }

    /**
     * @inheritDoc
     */
    public function echoText(string $text, string $namespace)
    {
        echo $text;
    }
}
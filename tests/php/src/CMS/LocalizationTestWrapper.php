<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\LocalizationInterface;

class LocalizationTestWrapper implements LocalizationInterface
{

    /**
     * @inheritDoc
     */
    public function __(string $text, string $namespace): string
    {
        return $text;
    }

    /**
     * @inheritDoc
     */
    public function _e(string $text, string $namespace)
    {
        echo $text;
    }
}
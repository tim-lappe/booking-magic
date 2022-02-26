<?php

namespace TLBM\CMS\Contracts;

interface LocalizationInterface
{
    /**
     * @param string $text
     * @param string $namespace
     *
     * @return string
     */
    public function __(string $text, string $namespace): string;

    /**
     * @param string $text
     * @param string $namespace
     *
     * @return mixed
     */
    public function _e(string $text, string $namespace);
}
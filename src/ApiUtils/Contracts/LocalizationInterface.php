<?php

namespace TLBM\ApiUtils\Contracts;

interface LocalizationInterface
{
    /**
     * @param string $text
     * @param string $namespace
     *
     * @return string
     */
    public function getText(string $text, string $namespace): string;

    /**
     * @param string $text
     * @param string $namespace
     *
     * @return void
     */
    public function echoText(string $text, string $namespace): void;
}
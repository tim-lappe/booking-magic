<?php

namespace TLBM\Output\Contracts;

interface FrontendMessengerInterface
{
    /**
     * @param string $html
     *
     * @return mixed
     */
    public function addMessage(string $html);

    /**
     * @return string
     */
    public function getContent(): string;
}
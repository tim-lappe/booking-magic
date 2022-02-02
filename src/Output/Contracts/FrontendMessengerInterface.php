<?php

namespace TLBM\Output\Contracts;

interface FrontendMessengerInterface
{
    /**
     * @param $html
     *
     * @return mixed
     */
    public function addMessage($html);

    /**
     * @return string
     */
    public function getContent(): string;
}
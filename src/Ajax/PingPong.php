<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;

class PingPong implements AjaxFunctionInterface
{

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return "ping";
    }

    /**
     * @param mixed $assocData
     *
     * @return array
     */
    public function execute($assocData): array
    {
        return array(
            "Pong!"
        );
    }
}
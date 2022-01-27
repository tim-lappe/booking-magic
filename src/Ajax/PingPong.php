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
     * @param mixed $data
     *
     * @return array
     */
    public function execute($data): array
    {
        return array(
            "Pong!"
        );
    }
}
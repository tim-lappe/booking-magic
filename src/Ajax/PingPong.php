<?php

namespace TLBM\Ajax;

class PingPong extends AjaxBase
{

    public function registerAjaxAction()
    {
        $this->addAjaxAction("ping");
    }

    public function apiRequest($data): array
    {
        return array(
            "Pong!"
        );
    }
}
<?php

namespace TLBM\Ajax;

class PingPong extends AjaxBase {

    function RegisterAjaxAction() {
        $this->AddAjaxAction("ping");
    }

    function ApiRequest($data): array {
        return array(
            "Pong!"
        );
    }
}
<?php

namespace TLBM;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Request\Contracts\RequestManagerInterface;


class Request
{
    /**
     * @var RequestManagerInterface
     */
    private RequestManagerInterface $requestManager;

    /**
     * @param RequestManagerInterface $requestManager
     */
    public function __construct(RequestManagerInterface $requestManager)
    {
        $this->requestManager = $requestManager;

        add_action("init", array($this, "init"));
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->requestManager->init();
    }
}
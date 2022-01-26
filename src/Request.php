<?php

namespace TLBM;

if ( ! defined('ABSPATH')) {
    return;
}

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use TLBM\Request\Contracts\RequestManagerInterface;
use TLBM\Request\DoBookingRequest;
use TLBM\Request\ShowBookingOverview;


class Request
{
    /**
     * @var RequestManagerInterface
     */
    private RequestManagerInterface $requestManager;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $requestManager = $container->get(RequestManagerInterface::class);
        $requestManager->registerEndpoint($container->get(DoBookingRequest::class));
        $requestManager->registerEndpoint($container->get(ShowBookingOverview::class));
        $requestManager->beforeInit();
        $this->requestManager = $requestManager;

        add_action("init", array($this, "init"));
    }

    public function init() {
        $this->requestManager->init();
    }
}
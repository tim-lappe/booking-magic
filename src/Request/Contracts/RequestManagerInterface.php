<?php

namespace TLBM\Request\Contracts;

use TLBM\Request\RequestBase;

interface RequestManagerInterface
{
    /**
     * @return void
     */
    public function init();

    /**
     * @param object $request
     *
     * @return mixed
     */
    public function registerEndpoint(object $request);

    /**
     * @return mixed
     */
    public function beforeInit();

    /**
     * @param string $action
     *
     * @return RequestBase
     */
    public function getRequest(string $action): ?RequestBase;
}
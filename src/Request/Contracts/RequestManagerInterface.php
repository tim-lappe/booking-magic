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
    public function getEndpointByAction(string $action): ?RequestBase;

    /**
     * @return RequestBase|null
     */
    public function getCurrentRequest(): ?RequestBase;

    /**
     * @return bool
     */
    public function hasContent(): bool;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return mixed
     */
    public function getVars();
}
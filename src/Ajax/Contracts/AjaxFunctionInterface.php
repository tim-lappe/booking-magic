<?php


namespace TLBM\Ajax\Contracts;

if ( !defined('ABSPATH')) {
    return;
}

interface AjaxFunctionInterface
{
    /**
     * @return string
     */
    public function getFunctionName(): string;

    /**
     * @param mixed $assocData
     *
     * @return mixed
     */
    public function execute($assocData);
}
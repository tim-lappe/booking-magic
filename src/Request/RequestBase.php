<?php


namespace TLBM\Request;

if ( ! defined('ABSPATH')) {
    return;
}


abstract class RequestBase
{

    /**
     * @var string
     */
    public string $action;

    /**
     * @var bool
     */
    public bool $hasContent = false;

    public function __construct()
    {
    }

    public function Init($vars)
    {
    }

    public function onAction($vars)
    {
    }

    /**
     * @param $vars
     *
     * @return string
     */
    public function getDisplayContent($vars): string
    {
        return "";
    }
}
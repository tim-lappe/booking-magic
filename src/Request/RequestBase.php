<?php


namespace TLBM\Request;

if ( !defined('ABSPATH')) {
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

    /**
     * @var mixed
     */
    protected $vars;

    public function __construct()
    {

    }

    /**
     * @return void
     */
    public function onAction()
    {

    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return "";
    }

    /**
     * @return mixed
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param mixed $vars
     */
    public function setVars($vars): void
    {
        $this->vars = $vars;
    }
}
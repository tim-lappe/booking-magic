<?php


namespace TLBM\Request;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

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

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

	/**
	 * @param LocalizationInterface $localization
	 */
    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
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
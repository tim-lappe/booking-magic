<?php


namespace TLBM\Admin\WpForm;

if ( ! defined('ABSPATH')) {
    return;
}

abstract class FormFieldBase
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public string $title;

    /**
     * @param string $name
     * @param string $title
     * @param mixed $value
     */
    public function __construct(string $name, string $title, $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
        $this->title = $title;
    }

    /**
     * @return void
     */
    abstract public function displayContent(): void;
}
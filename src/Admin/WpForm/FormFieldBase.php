<?php


namespace TLBM\Admin\WpForm;

if ( !defined('ABSPATH')) {
    return;
}

abstract class FormFieldBase
{

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $title;

    /**
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        $this->name  = $name;
        $this->title = $title;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    abstract public function displayContent($value): void;
}
<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;


if ( !defined('ABSPATH')) {
    return;
}


class Select extends ElementSetting
{
    /**
     * @var array
     */
    public array $keyValues = [];

    /**
     * @var ?string
     */
    public ?string $selectDataSource = null;

    /**
     * @param string $name
     * @param string $title
     * @param array $key_values
     * @param string $default_value
     * @param bool $readonly
     * @param bool $must_unique
     * @param string $categoryTitle
     */
    public function __construct(
        string $name,
        string $title,
        array $key_values,
        string $default_value = "",
        bool $readonly = false,
        bool $must_unique = false,
        string $categoryTitle = "General"
    ) {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, [], $categoryTitle);

        $this->keyValues = $key_values;
        $this->type      = "select";
    }
}
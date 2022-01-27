<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

if ( !defined('ABSPATH')) {
    return;
}


class Input extends ElementSetting
{

    /**
     * @var string
     */
    public string $input_type = "text";

    /**
     * @var string
     */
    public string $input_regex = ".*";

    /**
     * @var int
     */
    public int $input_minlength = 0;

    /**
     * @var int
     */
    public int $input_maxlength = 100;

    /**
     * @param $name
     * @param $title
     * @param string $input_type
     * @param string $default_value
     * @param bool $readonly
     * @param bool $must_unique
     * @param array $forbidden_values
     * @param string $category_title
     */
    public function __construct(
        $name,
        $title,
        string $input_type = "text",
        $default_value = "",
        bool $readonly = false,
        $must_unique = false,
        array $forbidden_values = array(),
        string $category_title = "General"
    ) {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, $forbidden_values, $category_title);
        $this->input_type = $input_type;
        $this->type       = "input";
    }

}
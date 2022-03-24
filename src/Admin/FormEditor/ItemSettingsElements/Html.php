<?php

namespace TLBM\Admin\FormEditor\ItemSettingsElements;

class Html extends ElementSetting
{
    public function __construct($name, $title, string $default_value = "", bool $readonly = false, bool $must_unique = false, array $forbidden_values = [], string $category_title = "General")
    {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, $forbidden_values, $category_title);
        $this->type = "html";
    }
}
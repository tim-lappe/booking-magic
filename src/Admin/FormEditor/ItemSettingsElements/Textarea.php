<?php

namespace TLBM\Admin\FormEditor\ItemSettingsElements;

class Textarea extends ElementSetting
{

    public function __construct(
        $name,
        $title,
        $default_value = "",
        bool $readonly = false,
        $must_unique = false,
        $forbidden_values = array(),
        $category_title = "General"
    ) {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, $forbidden_values, $category_title);
        $this->type = "textarea";
    }
}
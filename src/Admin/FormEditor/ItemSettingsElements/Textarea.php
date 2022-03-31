<?php

namespace TLBM\Admin\FormEditor\ItemSettingsElements;

class Textarea extends ElementSetting
{

    /**
     * @param string $name
     * @param string $title
     * @param mixed $defaultValue
     * @param bool $readonly
     * @param bool $mustUnique
     * @param array $forbiddenValues
     * @param string $categoryTitle
     */
    public function __construct(
        string $name,
        string $title,
        $defaultValue = "",
        bool $readonly = false,
        bool $mustUnique = false,
        array $forbiddenValues = [],
        string $categoryTitle = "General"
    ) {
        parent::__construct($name, $title, $defaultValue, $readonly, $mustUnique, $forbiddenValues, $categoryTitle);
        $this->type = "textarea";
    }
}
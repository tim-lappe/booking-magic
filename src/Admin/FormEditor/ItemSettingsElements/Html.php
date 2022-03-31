<?php

namespace TLBM\Admin\FormEditor\ItemSettingsElements;

class Html extends ElementSetting
{
    /**
     * @param string $name
     * @param string $title
     * @param string $defaultValue
     * @param bool $readonly
     * @param bool $mustUnique
     * @param array $forbiddenValues
     * @param string $categoryTitle
     */
    public function __construct(string $name, string $title, string $defaultValue = "", bool $readonly = false, bool $mustUnique = false, array $forbiddenValues = [], string $categoryTitle = "General")
    {
        parent::__construct($name, $title, $defaultValue, $readonly, $mustUnique, $forbiddenValues, $categoryTitle);
        $this->type = "html";
    }
}
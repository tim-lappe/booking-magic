<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

if ( ! defined('ABSPATH')) {
    return;
}


class Select extends ElementSetting
{

    public array $key_values = array();

    public function __construct(
        $name,
        $title,
        array $key_values,
        string $default_value = "",
        bool $readonly = false,
        bool $must_unique = false,
        string $category_title = "General"
    ) {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, array(), $category_title);

        $this->key_values = $key_values;
        $this->type       = "select";
    }

    public function GetEditorOutput(): string
    {
        $out = "<label>" . $this->title . "<br><select name=\"$this->name\">";
        foreach ($this->key_values as $key => $value) {
            if (is_array($value)) {
                $out .= "<optgroup label='" . $key . "'>";
                foreach ($value as $key_inner => $value_inner) {
                    $out .= "<option value=\"" . $key_inner . "\">" . $value_inner . "</option>";
                }
                $out .= "</optgroup>";
            } else {
                $out .= "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
        $out .= "</select></label>";

        return $out;
    }
}
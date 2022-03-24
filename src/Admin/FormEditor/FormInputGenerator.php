<?php

namespace TLBM\Admin\FormEditor;

class FormInputGenerator
{
    /**
     * @var LinkedFormData
     */
    private LinkedFormData $linkedFormData;

    /**
     * @param LinkedFormData $linkedFormData
     */
    public function __construct(LinkedFormData $linkedFormData)
    {
        $this->linkedFormData = $linkedFormData;
    }

    /**
     * @param string $inputType
     *
     * @return string
     */
    public function getFormControl(string $inputType = "text"): string
    {
        $attributes = $this->getAttributes();
        $css = implode(" ", $attributes['css_arr']);

        $value = $this->linkedFormData->getInputVarByName($attributes['name']);

        $html     = "<div class='".$css."'>";
        $html     .= "<label>";
        $html     .= "<span class='tlbm-input-title'>" . $attributes['title'] . "</span>";
        $html     .= "<input class='tlbm-input-field' value='".$value."' type='" . $inputType . "' name='" . $attributes['name'] . "' " . $attributes['required'] . ">";
        $html     .= "</label>";
        $html     .= "</div>";

        return $html;
    }

    /**
     * @param array $keyValues
     *
     * @return string
     */
    public function getSelect(array $keyValues): string
    {
        $attributes = $this->getAttributes();
        $css = implode(" ", $attributes['css_arr']);
        $fieldvalue = $this->linkedFormData->getInputVarByName($attributes['name']);

        $html = "<div class='tlbm-fe-form-control " . $css . "'>";
        $html .= "<label>";
        $html .= "<span class='tlbm-input-title'>" . $attributes['title'] . "</span>";
        $html .= "<select class='tlbm-input-field' name='" . $attributes['name'] . "' " . $attributes['required'] . ">";

        foreach ($keyValues as $key => $value) {
            $html .= "<option " . selected($key == $fieldvalue, true, false) . " value='" . $key . "'>" . $value . "</option>";
        }

        $html .= "</select>";
        $html .= "</label>";
        $html .= "</div>";

        return $html;
    }

    public function getTextarea()
    {
        $attributes = $this->getAttributes();
        $css        = implode(" ", $attributes['css_arr']);
        $value      = $this->linkedFormData->getInputVarByName($attributes['name']);

        $html = "<div class='" . $css . "'>";
        $html .= "<label>";
        $html .= "<span class='tlbm-input-title'>" . $attributes['title'] . "</span>";
        $html .= "<textarea class='tlbm-input-field' name='" . $attributes['name'] . "' " . $attributes['required'] . ">" . $value . "</textarea>";
        $html .= "</label>";
        $html .= "</div>";

        return $html;
    }

    /**
     * @return array
     */
    private function getAttributes(): array
    {
        $lsetting = $this->linkedFormData->getLinkedSettings();
        $css = array("tlbm-fe-form-control");
        $css[] = $lsetting->getValue("css_classes");

        $required = "";
        if($lsetting->getValue("required") == "yes") {
            $required = "required";
            $css[] = "tlbm-input-required";
        }

        $title = $lsetting->getValue("title");
        $name = $lsetting->getValue("name");

        return [
            "css_arr" => $css,
            "required" => $required,
            "title" => $title,
            "name" => $name
        ];
    }

    /**
     * @return LinkedFormData
     */
    public function getLinkedFormData(): LinkedFormData
    {
        return $this->linkedFormData;
    }

    /**
     * @param LinkedFormData $linkedFormData
     */
    public function setLinkedFormData(LinkedFormData $linkedFormData): void
    {
        $this->linkedFormData = $linkedFormData;
    }
}
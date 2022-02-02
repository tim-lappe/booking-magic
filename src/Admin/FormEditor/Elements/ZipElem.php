<?php


namespace TLBM\Admin\FormEditor\Elements;

class ZipElem extends FormInputElem
{

    public function __construct()
    {
        parent::__construct("field_zip_code", __("ZIP", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = __("number field for the zip-code", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "zip";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = __("Zip", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";
    }
}
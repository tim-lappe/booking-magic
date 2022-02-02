<?php


namespace TLBM\Admin\FormEditor\Elements;

class AddressElem extends FormInputElem
{

    public function __construct()
    {
        parent::__construct("field_address_line", __("Address", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = __("text field for the address-line", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "address";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = __("Address", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";
    }
}
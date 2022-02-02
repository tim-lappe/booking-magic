<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

final class FirstNameElem extends FormInputElem
{
    public function __construct()
    {
        parent::__construct("field_first_name", __("First Name", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = __("A field in which the user can enter his/her first name", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "first_name";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = __("First Name", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";
    }
}

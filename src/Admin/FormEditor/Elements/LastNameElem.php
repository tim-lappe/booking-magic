<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

if ( !defined('ABSPATH')) {
    return;
}

final class LastNameElem extends FormInputElem
{
    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection)
    {
        parent::__construct("field_last_name", __("Last Name", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);

        $this->description = __("A field in which the user can enter his/her last name", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "last_name";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = __("Last Name", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);

    }
}

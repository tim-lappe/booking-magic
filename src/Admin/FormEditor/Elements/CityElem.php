<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

class CityElem extends FormInputElem
{

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection)
    {
        parent::__construct("field_city", __("City", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = __("text field for the name of the city", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "city";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = __("City", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);

    }
}
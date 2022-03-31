<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

if ( !defined('ABSPATH')) {
    return;
}

final class FirstNameElem extends FormInputElem
{
    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        parent::__construct("field_first_name", $localization->getText("First Name", TLBM_TEXT_DOMAIN));

        $this->menuCategory = $localization->getText("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description  = $localization->getText("A field in which the user can enter his/her first name", TLBM_TEXT_DOMAIN);

        $name_setting               = $this->getSettingsType("name");
        $name_setting->defaultValue = "first_name";
        $name_setting->readonly     = true;

        $title_setting               = $this->getSettingsType("title");
        $title_setting->defaultValue = $localization->getText("First Name", TLBM_TEXT_DOMAIN);

        $required               = $this->getSettingsType("required");
        $required->defaultValue = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->defaultValue, $title_setting->defaultValue, $this->description);
    }
}

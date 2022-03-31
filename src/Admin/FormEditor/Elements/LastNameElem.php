<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

if ( !defined('ABSPATH')) {
    return;
}

final class LastNameElem extends FormInputElem
{
    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        parent::__construct("field_last_name", $localization->getText("Last Name", TLBM_TEXT_DOMAIN));

        $this->menuCategory = $this->localization->getText("Predefined fields", TLBM_TEXT_DOMAIN);

        $this->description = $this->localization->getText("A field in which the user can enter his/her last name", TLBM_TEXT_DOMAIN);

        $name_setting               = $this->getSettingsType("name");
        $name_setting->defaultValue = "last_name";
        $name_setting->readonly     = true;

        $title_setting               = $this->getSettingsType("title");
        $title_setting->defaultValue = $this->localization->getText("Last Name", TLBM_TEXT_DOMAIN);

        $required               = $this->getSettingsType("required");
        $required->defaultValue = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->defaultValue, $title_setting->defaultValue, $this->description);
    }
}

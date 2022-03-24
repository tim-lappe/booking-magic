<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

class ZipElem extends FormInputElem
{

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        parent::__construct("field_zip_code", $localization->getText("ZIP", TLBM_TEXT_DOMAIN));

        $this->menu_category = $this->localization->getText("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->getText("number field for the zip-code", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "zip";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = $this->localization->getText("Zip", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);
    }
}
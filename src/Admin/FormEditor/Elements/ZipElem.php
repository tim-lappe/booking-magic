<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;
use TLBM\CMS\Contracts\LocalizationInterface;

class ZipElem extends FormInputElem
{

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        parent::__construct("field_zip_code", $localization->__("ZIP", TLBM_TEXT_DOMAIN));

        $this->menu_category = $this->localization->__("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->__("number field for the zip-code", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "zip";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = $this->localization->__("Zip", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);
    }
}
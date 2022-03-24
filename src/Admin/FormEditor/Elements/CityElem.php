<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;

class CityElem extends FormInputElem
{

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        $this->localization = $localization;

        parent::__construct("field_city", $this->localization->getText("City", TLBM_TEXT_DOMAIN));

        $this->menu_category = $this->localization->getText("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->getText("text field for the name of the city", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "city";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = $this->localization->getText("City", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);

    }
}
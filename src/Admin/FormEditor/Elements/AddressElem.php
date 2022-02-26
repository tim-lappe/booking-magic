<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;
use TLBM\CMS\Contracts\LocalizationInterface;

class AddressElem extends FormInputElem
{

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        $this->localization = $localization;
        parent::__construct("field_address_line", $this->localization->__("Address", TLBM_TEXT_DOMAIN));

        $this->menu_category = $this->localization->__("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->__("text field for the address-line", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "address";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = $this->localization->__("Address", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);
    }
}
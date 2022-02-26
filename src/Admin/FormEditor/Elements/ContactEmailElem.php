<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Booking\Semantic\PredefinedValueFieldsCollection;
use TLBM\CMS\Contracts\LocalizationInterface;

if ( !defined('ABSPATH')) {
    return;
}

final class ContactEmailElem extends FormInputElem
{
    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(PredefinedValueFieldsCollection $predefinedValueFieldsCollection, LocalizationInterface $localization)
    {
        $this->localization = $localization;

        parent::__construct("field_contact_email", $this->localization->__("Contact E-Mail", TLBM_TEXT_DOMAIN));

        $this->menu_category = $this->localization->__("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description   = $this->localization->__(
            "The contact email to which the user receives emails such as booking confirmations", TLBM_TEXT_DOMAIN
        );

        $name_setting                = $this->getSettingsType("name");
        $name_setting->default_value = "contact_email";
        $name_setting->readonly      = true;

        $title_setting                = $this->getSettingsType("title");
        $title_setting->default_value = $this->localization->__("E-Mail", TLBM_TEXT_DOMAIN);

        $required                = $this->getSettingsType("required");
        $required->default_value = "yes";

        $predefinedValueFieldsCollection->addField($name_setting->default_value, $title_setting->default_value, $this->description);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $generator = new FormInputGenerator($linkedFormData);
        return $generator->getFormControl("email");
    }
}

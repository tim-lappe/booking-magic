<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

final class EmailElem extends FormInputElem
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("field_email", $localization->getText("E-Mail", TLBM_TEXT_DOMAIN));

        $this->description = $localization->getText("A field in which the user can enter an e-mail", TLBM_TEXT_DOMAIN);

        $this->getSettingsType("name")->default_value  = "email";
        $this->getSettingsType("title")->default_value = $localization->getText("E-Mail", TLBM_TEXT_DOMAIN);
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


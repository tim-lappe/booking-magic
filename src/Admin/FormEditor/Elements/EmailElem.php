<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\LinkedFormData;

final class EmailElem extends FormInputElem
{
    public function __construct()
    {
        parent::__construct("field_email", __("E-Mail", TLBM_TEXT_DOMAIN));

        $this->description = __("A field in which the user can enter an e-mail", TLBM_TEXT_DOMAIN);

        $this->getSettingsType("name")->default_value  = "email";
        $this->getSettingsType("title")->default_value = __("E-Mail", TLBM_TEXT_DOMAIN);
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


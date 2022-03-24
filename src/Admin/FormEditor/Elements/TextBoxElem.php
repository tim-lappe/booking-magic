<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

if ( !defined('ABSPATH')) {
    return;
}

final class TextBoxElem extends FormInputElem
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("field_text", $localization->getText("Textbox", TLBM_TEXT_DOMAIN));

        $this->description = $this->localization->getText("Simple text field", TLBM_TEXT_DOMAIN);
    }
}
<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\CMS\Contracts\LocalizationInterface;

if ( !defined('ABSPATH')) {
    return;
}

final class TextBoxElem extends FormInputElem
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("field_text", $localization->__("Textbox", TLBM_TEXT_DOMAIN));

        $this->description = $this->localization->__("Simple text field", TLBM_TEXT_DOMAIN);
    }
}
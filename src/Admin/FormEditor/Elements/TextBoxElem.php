<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

final class TextBoxElem extends FormInputElem
{
    public function __construct()
    {
        parent::__construct("field_text", __("Textbox", TLBM_TEXT_DOMAIN));

        $this->description = __("Simple text field", TLBM_TEXT_DOMAIN);
    }
}
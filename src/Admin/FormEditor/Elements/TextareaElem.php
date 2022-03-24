<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TextareaElem extends FormInputElem
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("field_textarea", $localization->getText("Textarea", TLBM_TEXT_DOMAIN));
        $this->description = $this->localization->getText("Multi-line textbox", TLBM_TEXT_DOMAIN);
    }


    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $generator = new FormInputGenerator($linkedFormData);

        return $generator->getTextarea();
    }
}
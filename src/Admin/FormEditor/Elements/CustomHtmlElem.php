<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\ItemSettingsElements\Html;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class CustomHtmlElem extends FormElem implements FrontendElementInterface
{

    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;

        parent::__construct("html", $this->localization->__("HTML", TLBM_TEXT_DOMAIN));

        $this->description   = $this->localization->__("Adds custom HTML to the form", TLBM_TEXT_DOMAIN);
        $this->menu_category = $this->localization->__("General", TLBM_TEXT_DOMAIN);
        $htmlItem            = new Html("html", $this->localization->__("HTML", TLBM_TEXT_DOMAIN), "", false, false, [], $this->localization->__("HTML", TLBM_TEXT_DOMAIN));
        $htmlItem->expand    = true;

        $this->addSettings($htmlItem);
    }

    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $linkedSettings = $linkedFormData->getLinkedSettings();

        return $linkedSettings->getValue("html");
    }
}
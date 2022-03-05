<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

final class SpacingElem extends FormElem implements FrontendElementInterface
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("spacing", $localization->__("Spacing", TLBM_TEXT_DOMAIN));
        $this->description   = $this->localization->__("Useful to leave space within the form", TLBM_TEXT_DOMAIN);
        $this->menu_category = $this->localization->__("Layout", TLBM_TEXT_DOMAIN);

        $this->addSettings(new Input("spacing", $this->localization->__("Spacing (in px)", TLBM_TEXT_DOMAIN), "number", 100));
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
        $lsetting = $linkedFormData->getLinkedSettings();
        $spacing = $lsetting->getValue("spacing");
        $css = [ "tlbm-fe-spacing" ];
        $css[] = $lsetting->getValue("css_classes");

        return "<div style='height: " . $spacing . "px' class='".implode(" ", $css)."'></div>";
    }
}


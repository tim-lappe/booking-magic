<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\CMS\Contracts\LocalizationInterface;

if ( !defined('ABSPATH')) {
    return;
}


final class HrElem extends FormElem implements FrontendElementInterface
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("hr", $localization->__("Horizontal Line", TLBM_TEXT_DOMAIN));

        $this->description   = $this->localization->__("Inserts a horizontal dividing line to visually separate areas from each other", TLBM_TEXT_DOMAIN);
        $this->menu_category = $this->localization->__("Layout", TLBM_TEXT_DOMAIN);
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
        $css = [ "tlbm-fe-hr" ];
        $css[] = trim($lsetting->getValue("css_classes"));

        return "<hr class='" . implode(" " , $css) . "'>";
    }
}


<?php


namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\LinkedFormData;

if ( !defined('ABSPATH')) {
    return;
}


final class HrElem extends FormElem implements FrontendElementInterface
{

    public function __construct()
    {
        parent::__construct("hr", __("Horizontal Line", TLBM_TEXT_DOMAIN));

        $this->description   = __("Inserts a horizontal dividing line to visually separate areas from each other", TLBM_TEXT_DOMAIN);
        $this->menu_category = __("Layout", TLBM_TEXT_DOMAIN);
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


<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( ! defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\ItemSettingsElements\Input;

final class SpacingElem extends FormElem
{

    public function __construct()
    {
        parent::__construct("spacing", __("Spacing", TLBM_TEXT_DOMAIN));
        $this->description   = __("Useful to leave space within the form", TLBM_TEXT_DOMAIN);
        $this->menu_category = __("Layout", TLBM_TEXT_DOMAIN);

        $this->AddSettings(new Input("spacing", __("Spacing (in px)", TLBM_TEXT_DOMAIN), "number", 100));
    }

    /**
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
    public function GetFrontendOutput($form_node, callable $insert_child = null): string
    {
        return "<div style='height: " . $form_node->formData->spacing . "px' class='tlbm-fe-spacing " . ($form_node->formData->css_classes ?? "") . "'></div>";
    }
}


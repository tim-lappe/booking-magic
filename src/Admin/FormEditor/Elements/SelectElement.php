<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Textarea;

class SelectElement extends FormInputElem {

    public function __construct() {
        parent::__construct( "select", __("Select", TLBM_TEXT_DOMAIN) );
        $this->description = __("let the user select from multiple items", TLBM_TEXT_DOMAIN);

        $setting_items = new Textarea(
            "items",
            __("Selectable Items (one Item per row)", TLBM_TEXT_DOMAIN),
            "",
            false,
            false,
            array(),
            __("Select", TLBM_TEXT_DOMAIN));

        $setting_default_selected = new Input(
            "default_selected",
            __("Default Selection", TLBM_TEXT_DOMAIN),
            "text",
            "",
            false,
            false,
            array(),
            __("Select", TLBM_TEXT_DOMAIN));

        $setting_default_selected->expand = true;

        $this->AddSettings($setting_items, $setting_default_selected);
    }

    /**
     * @inheritDoc
     */
    public function GetFrontendOutput($form_node, ?callable $insert_child = null) {
        $items = explode("\n", $form_node->formData->items);
        $key_values = array();
        foreach ($items as $values) {
            $key_values[$values] = $values;
        }

        return InputGenerator::GetSelectControle($form_node->formData->title, $form_node->formData->name, $key_values, $form_node->formData->required == "yes");
    }
}
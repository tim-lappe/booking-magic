<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Textarea;
use TLBM\Admin\FormEditor\LinkedFormData;

class SelectElement extends FormInputElem
{

    public function __construct()
    {
        parent::__construct("select", __("Select", TLBM_TEXT_DOMAIN));
        $this->description = __("let the user select from multiple items", TLBM_TEXT_DOMAIN);

        $setting_items = new Textarea("items", __("Selectable Items (one Item per row)", TLBM_TEXT_DOMAIN), "", false, false, [], __("Select", TLBM_TEXT_DOMAIN));
        $sDefaultSelected = new Input("default_selected", __("Default Selection", TLBM_TEXT_DOMAIN), "text", "", false, false, [], __("Select", TLBM_TEXT_DOMAIN));
        $sDefaultSelected->expand = true;

        $this->addSettings($setting_items, $sDefaultSelected);
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
        $items = $linkedFormData->getLinkedSettings()->getValue("items");
        $items = explode("\n", $items);

        $keyValues = [];
        foreach ($items as $values) {
            $keyValues[$values] = $values;
        }

        $generator = new FormInputGenerator($linkedFormData);
        return $generator->getSelect($keyValues);
    }
}
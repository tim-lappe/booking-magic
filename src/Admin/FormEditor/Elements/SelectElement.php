<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Textarea;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\CMS\Contracts\LocalizationInterface;

class SelectElement extends FormInputElem
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("select", $localization->__("Select", TLBM_TEXT_DOMAIN));
        $this->description = $this->localization->__("let the user select from multiple items", TLBM_TEXT_DOMAIN);

        $setting_items = new Textarea("items", $this->localization->__("Selectable Items (one Item per row)", TLBM_TEXT_DOMAIN), "", false, false, [], $this->localization->__("Select", TLBM_TEXT_DOMAIN));
        $sDefaultSelected = new Input("default_selected", $this->localization->__("Default Selection", TLBM_TEXT_DOMAIN), "text", "", false, false, [], $this->localization->__("Select", TLBM_TEXT_DOMAIN));
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
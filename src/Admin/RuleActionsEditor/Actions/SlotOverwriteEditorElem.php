<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

class SlotOverwriteEditorElem extends RuleActionEditorElem
{
    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->__("Overwrite", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->__("Overwrite Slots", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->__("Overwrite the capacity of existing slots", TLBM_TEXT_DOMAIN));
        $this->setName("slot_overwrite");
    }
}
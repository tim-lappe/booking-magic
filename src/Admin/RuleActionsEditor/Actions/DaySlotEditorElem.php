<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\Admin\RuleActionsEditor\Actions\Traits\CapacityFieldTrait;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class DaySlotEditorElem extends RuleActionEditorElem
{
    use CapacityFieldTrait;

    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->getText("All Day", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->getText("Day slot", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->getText("Create slots that can be booked all day ", TLBM_TEXT_DOMAIN));
        $this->setName("day_slot");
    }
}
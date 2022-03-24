<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\Admin\RuleActionsEditor\Actions\Traits\CapacityFieldTrait;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TimeSlotEditorElem extends RuleActionEditorElem
{
    use CapacityFieldTrait;

    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->getText("Time specific", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->getText("Time slot", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->getText("Create slots that can be booked at specific times", TLBM_TEXT_DOMAIN));
        $this->setName("time_slot");
    }
}
<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\Admin\RuleActionsEditor\Actions\Traits\CapacityFieldTrait;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TimeSlotEditorElem extends RuleActionEditorElem
{
    use CapacityFieldTrait;

    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->__("Time specific", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->__("Time slot", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->__("Create slots that can be booked at specific times", TLBM_TEXT_DOMAIN));
        $this->setName("time_slot");
    }
}
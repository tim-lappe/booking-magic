<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

class MultipleTimeSlotEditorElem extends RuleActionEditorElem
{
    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->getText("Time specific", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->getText("Multiple time slot", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->getText("Create multiple slots that can be booked at specific times", TLBM_TEXT_DOMAIN));
        $this->setName("multiple_time_slots");
    }
}
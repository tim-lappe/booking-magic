<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

class MessageEditorElem extends RuleActionEditorElem
{
    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->getText("All Day", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->getText("Message", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->getText("Display a message", TLBM_TEXT_DOMAIN));
        $this->setName("message");
    }
}
<?php

namespace TLBM\Admin\RuleActionsEditor\Actions;

use TLBM\ApiUtils\Contracts\LocalizationInterface;

class MessageEditorElem extends RuleActionEditorElem
{
    public function __construct(LocalizationInterface $localization)
    {
        $this->setCategory($localization->__("All Day", TLBM_TEXT_DOMAIN));

        $this->setTitle($localization->__("Message", TLBM_TEXT_DOMAIN));
        $this->setDescription($localization->__("Display a message", TLBM_TEXT_DOMAIN));
        $this->setName("message");
    }
}
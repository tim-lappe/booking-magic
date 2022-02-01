<?php

namespace TLBM\Rules\Actions\ActionData;

class MessageData extends ActionData
{
    /**
     * @return string
     */
    public function getMessage(): string
    {
        if(isset($this->mixedActionData['message'])) {
            return $this->mixedActionData['message'];
        }

        return "";
    }
}
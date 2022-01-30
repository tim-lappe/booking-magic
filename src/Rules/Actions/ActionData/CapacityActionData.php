<?php

namespace TLBM\Rules\Actions\ActionData;

class CapacityActionData extends ActionData
{
    /**
     * @return string
     */
    public function getCapacityMode(): string
    {
        if(isset($this->mixedActionData['mode'])) {
            return $this->mixedActionData['mode'];
        }

        return "";
    }

    /**
     * @return int
     */
    public function getCapacityAmount(): int
    {
        if(isset($this->mixedActionData['amount'])) {
            return intval($this->mixedActionData['amount']);
        }

        return 0;
    }
}
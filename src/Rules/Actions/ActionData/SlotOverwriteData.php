<?php

namespace TLBM\Rules\Actions\ActionData;

class SlotOverwriteData extends CapacityActionData
{
    /**
     * @return bool
     */
    public function isFullDay(): bool
    {
        if (isset($this->mixedActionData['isFullDay'])) {
            return $this->mixedActionData['isFullDay'];
        }

        return true;
    }

    /**
     * @return int
     */
    public function getTimeHourFrom(): int
    {
        if (isset($this->mixedActionData['fromHour'])) {
            return intval($this->mixedActionData['fromHour']);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getTimeMinuteFrom(): int
    {
        if (isset($this->mixedActionData['fromMinute'])) {
            return intval($this->mixedActionData['fromMinute']);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getTimeHourTo(): int
    {
        if (isset($this->mixedActionData['toHour'])) {
            return intval($this->mixedActionData['toHour']);
        }

        return 23;
    }

    /**
     * @return int
     */
    public function getTimeMinuteTo(): int
    {
        if (isset($this->mixedActionData['toMinute'])) {
            return intval($this->mixedActionData['toMinute']);
        }

        return 59;
    }
}
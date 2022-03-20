<?php

namespace TLBM\Rules\Actions\ActionData;

class MultipleTimeSlotData extends CapacityActionData
{

    public function getIntervalMinutes(): int
    {
        if (isset($this->mixedActionData['interval'])) {
            return intval($this->mixedActionData['interval']);
        }

        return 5;
    }

    public function getTimeHourFrom(): int
    {
        if (isset($this->mixedActionData['fromHour'])) {
            return intval($this->mixedActionData['fromHour']);
        }

        return 0;
    }

    public function getTimeMinuteFrom(): int
    {
        if (isset($this->mixedActionData['fromMinute'])) {
            return intval($this->mixedActionData['fromMinute']);
        }

        return 0;
    }

    public function getTimeHourTo(): int
    {
        if (isset($this->mixedActionData['toHour'])) {
            return intval($this->mixedActionData['toHour']);
        }

        return 23;
    }

    public function getTimeMinuteTo(): int
    {
        if (isset($this->mixedActionData['toMinute'])) {
            return intval($this->mixedActionData['toMinute']);
        }

        return 59;
    }
}
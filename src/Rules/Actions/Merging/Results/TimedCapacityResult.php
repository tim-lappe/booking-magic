<?php

namespace TLBM\Rules\Actions\Merging\Results;

class TimedCapacityResult extends CapacityResult
{
    /**
     * @var int
     */
    public int $hour = 0;

    /**
     * @var int
     */
    public int $minute = 0;

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @param int $hour
     */
    public function setHour(int $hour): void
    {
        $this->hour = $hour;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @param int $minute
     */
    public function setMinute(int $minute): void
    {
        $this->minute = $minute;
    }
}
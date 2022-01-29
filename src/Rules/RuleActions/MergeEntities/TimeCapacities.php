<?php

namespace TLBM\Rules\RuleActions\MergeEntities;

use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;

class TimeCapacities implements MergeEntityInterface
{

    /**
     * @var TimeCapacity[]
     */
    public array $capacities = array();

    public function __construct()
    {
        $this->capacities = array();
    }

    /**
     * @param int $hour
     * @param int $minute
     * @param bool $autoCreate
     *
     * @return TimeCapacity|null
     */
    public function getTimeCapacity(int $hour, int $minute, bool $autoCreate = true): ?TimeCapacity
    {
        foreach ($this->capacities as $timeCapacity) {
            if ($timeCapacity->hour == $hour && $timeCapacity->minute) {
                return $timeCapacity;
            }
        }

        if ($autoCreate) {
            $timeCap           = new TimeCapacity();
            $timeCap->hour     = $hour;
            $timeCap->minute   = $minute;
            $this->capacities[] = $timeCap;

            return $timeCap;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getMergeTerm(): string
    {
        return "time_capacities";
    }
}
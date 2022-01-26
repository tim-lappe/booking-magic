<?php

namespace TLBM\Rules\RuleActions\MergeEntities;

class TimeCapacities extends MergeEntityBase
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
     * @param bool $auto_create
     *
     * @return TimeCapacity|null
     */
    public function getTimeCapacity(int $hour, int $minute, bool $auto_create = true): ?TimeCapacity
    {
        foreach ($this->capacities as $time_capacity) {
            if ($time_capacity->hour == $hour && $time_capacity->minute) {
                return $time_capacity;
            }
        }

        if ($auto_create) {
            $time_cap           = new TimeCapacity();
            $time_cap->hour     = $hour;
            $time_cap->minute   = $minute;
            $this->capacities[] = $time_cap;

            return $time_cap;
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
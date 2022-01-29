<?php

namespace TLBM\Rules\RuleActions\MergeEntities;

use TLBM\Rules\RuleActions\MergeEntities\Contracts\CapacityMerge;

class TimeCapacity implements CapacityMerge
{

    public int $hour = 0;
    public int $minute = 0;
    public int $capacity = 0;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }


    /**
     * @param int $capacity
     *
     * @return void
     */
    public function setCapacity(int $capacity)
    {
        $this->capacity = $capacity;
    }
}
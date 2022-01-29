<?php

namespace TLBM\Rules\RuleActions\MergeEntities\Contracts;

interface CapacityMerge
{

    /**
     * @return int
     */
    public function getCapacity(): int;

    /**
     * @param int $capacity
     *
     * @return mixed
     */
    public function setCapacity(int $capacity);
}
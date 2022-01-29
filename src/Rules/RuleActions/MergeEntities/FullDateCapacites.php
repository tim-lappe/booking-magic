<?php

namespace TLBM\Rules\RuleActions\MergeEntities;

use TLBM\Rules\RuleActions\MergeEntities\Contracts\CapacityMerge;
use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;

class FullDateCapacites implements CapacityMerge, MergeEntityInterface
{

    public int $capacity = 0;

    public function getMergeTerm(): string
    {
        return "full_date_capacity";
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